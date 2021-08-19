<?php

namespace app\modules\employee\controllers;
use yii;
use app\models\EmployeeLeavesRequests;
class LeaveController extends \yii\web\Controller
{
    public function beforeAction($action){
        if (!\Yii::$app->user->isGuest) {
            if(isset($_GET['securekey']) AND !empty($_GET['securekey'])){
                $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
                if(empty($menuid)){ return $this->redirect(Yii::$app->homeUrl); }

                $chkValid = Yii::$app->utility->validate_url($menuid);
                if(empty($chkValid)){ return $this->redirect(Yii::$app->homeUrl); }
                return true;
            }else{ return $this->redirect(Yii::$app->homeUrl); }
        }else{
            return $this->redirect(Yii::$app->homeUrl);
        }
        parent::beforeAction($action);
    }
    
    public function actionIndex()
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('index', ['menuid'=>$menuid]);
    }
    

    //Holidays Calendar
     public function actionHolidaylist(){

        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $this->layout = '@app/views/layouts/admin_layout.php';
       $years = date("Y");
       // $years = '2020';
        $HolidaysList = Yii::$app->hr_utility->hr_get_holiday_list($years);
        if(isset($_GET['key'])){
            $years = $_GET['key'];
             $HolidaysList = Yii::$app->hr_utility->hr_get_holiday_list($years);
                
        }
        
        
         return $this->render('holidaylist', ['HolidaysList'=>$HolidaysList,'menuid'=>$menuid,'years' => $years]);

        // return $this->render('holidaylist', ['HolidaysList'=>$HolidaysList,'menuid'=>$menuid]);

     }

    
    //Apply for leave
    public function actionApplyforleave()
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."employee/leave/applyforleave?securekey=$menuid";
        $leave_app_id=NULL;
        if(isset($_GET['key']) AND !empty($_GET['key'])){
            $leave_app_id = Yii::$app->utility->decryptString($_GET['key']);
            if(empty($leave_app_id)){
                Yii::$app->getSession()->setFlash('danger', "Invalid Key Found");
                return $this->redirect($url);
            }
            /*
             * Check Is Submitted or Not
             */
            $chleaves = Yii::$app->hr_utility->hr_get_leaves('A', Yii::$app->user->identity->e_id, $leave_app_id, "Draft");
            if(empty($chleaves)){
                Yii::$app->getSession()->setFlash('danger', "No Application Found.");
                return $this->redirect($url);
            }
        }
        
        if(isset($_POST['EmployeeLeavesRequests'])&& !empty($_POST['EmployeeLeavesRequests'])){
            $post = $_POST['EmployeeLeavesRequests'];
            if(!empty($leave_app_id)){
                $leaveappid = Yii::$app->utility->encryptString($leave_app_id);
                $url = Yii::$app->homeUrl."employee/leave/applyforleave?securekey=$menuid&key=$leaveappid";
            }
            //            echo "<pre>";print_r($post); die;
            //            if(!empty($post['leave_app_id'])){
            //                $leave_app_id = Yii::$app->utility->decryptString($post['leave_app_id']);
            //                if(empty($leave_app_id)){
            //                    Yii::$app->getSession()->setFlash('danger', 'Invalid Leave ID.');
            //                    return $this->redirect($url);
            //                }
            //                /*
            //                * Check Is Submitted or Not
            //                */
            //               $chleaves = Yii::$app->hr_utility->hr_get_leaves('A', Yii::$app->user->identity->e_id, $leave_app_id, "Draft");
            //               if(empty($chleaves)){
            //                   Yii::$app->getSession()->setFlash('danger', "No Application Found.");
            //                   return $this->redirect($url);
            //               }
            //            }
            // die($leave_app_id);
            $leave_reason= preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['leave_reason']);
            $contact_address= preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['contact_address']);
            $contact_no= preg_replace('/[^0-9-]/', '', $post['contact_no']);
            $availing_for_LTC= preg_replace('/[^A-Z-]/', '', $post['availing_for_LTC']);
            
            $leave_type = Yii::$app->utility->decryptString($post['leave_type']);
            if(empty($leave_type)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid Leave Type.');
                return $this->redirect($url);
            }
            //            echo "-----$leave_type";
            $whetherhalfday = "FULL";
            
            if(isset($post['reqfromdate']) AND !empty($post['reqfromdate'])){
                /*
                 * Last date check If applying in same aplication
                 */
                $from = Yii::$app->utility->decryptString($post['reqfromdate']);
                if(empty($from)){
                    Yii::$app->getSession()->setFlash('danger', 'Invalid From Date.');
                    return $this->redirect($url);
                }
                
                $from = date("Y-m-d", strtotime($from));
                
            }else{
                $from = date("Y-m-d", strtotime($post['req_from_date']));
            }
            
            $till = date("Y-m-d", strtotime($post['req_to_date']));
            if($from == '1970-01-01' OR $till == '1970-01-01'){
                Yii::$app->getSession()->setFlash('danger', 'Invalid From or To Date .');
                return $this->redirect($url);
            }
            $fromdatetime = strtotime($from);
            $tilldatetime = strtotime($till);
            $datediff = $tilldatetime-$fromdatetime  ;
            $noofdays= round($datediff / (60 * 60 * 24));
            if($noofdays == "0"){
                $noofdays = "1";
            }else{
                $noofdays=$noofdays+1;
            }
            if(isset($post['whetherhalfday']) AND !empty($post['whetherhalfday'])){
                $whetherhalfday = $post['whetherhalfday'];
                if($whetherhalfday !='FULL'){ $noofdays="0.5"; }
            }
            
            $lcards = Yii::$app->hr_utility->get_employee_leaves(Yii::$app->user->identity->e_id);
            //            echo "<pre>";print_r($lcards);
            if(empty($lcards)){
                Yii::$app->getSession()->setFlash('danger', 'No Leave Details Found. Contact HR.');
                return $this->redirect($url);
            }
            $leavependingcheck=0;
            $leavename="";
            foreach($lcards as $l){
                if($leave_type == $l['leave_type']){
                    $leavependingcheck=$l['balance_leaves'];
                    $leavename=$l['desc'];
                }
            }
            //            echo "$noofdays<br>";
            //            echo "--$leavependingcheck<br>";
            //            die;
            if($noofdays > $leavependingcheck){
                Yii::$app->getSession()->setFlash('danger', "You have $leavependingcheck balance leave of $leavename.");
                return $this->redirect($url);
            }
            $balLeave = $leavependingcheck-$noofdays;
            $result = Yii::$app->hr_utility->hr_add_leave_app(NULL, $leave_app_id, Yii::$app->user->identity->e_id, $leave_reason, $availing_for_LTC, $contact_address, $contact_no, $leave_type, $whetherhalfday, $from, $till, $noofdays, $balLeave, "Draft");
            
            /*
             * Logs
             */
            $logs['emp_req_id'] = NULL;
            $logs['leave_app_id'] = $leave_app_id;
            $logs['employee_code'] = Yii::$app->user->identity->e_id;
            $logs['leave_reason'] = $leave_reason;
            $logs['availing_for_LTC'] = $availing_for_LTC;
            $logs['contact_address'] = $contact_address;
            $logs['contact_no'] = $contact_no;
            $logs['leave_type'] = $leave_type;
            $logs['whetherhalfday'] = $whetherhalfday;
            $logs['req_from_date'] = $from;
            $logs['req_to_date'] = $till;
            $logs['totaldays'] = $noofdays;
            $logs['balance_leaves'] = $balLeave;
            $logs['status'] = "Draft";
            $jsonlogs = json_encode($logs);
            if(empty($result)){
                $msg = "No Leave applied. Try again or Contact Admin.";
                Yii::$app->getSession()->setFlash('danger', $msg);
            }elseif($result == '99999997'){
                $msg = "You have $leavependingcheck balance leave of $leavename.";
                Yii::$app->getSession()->setFlash('danger', $msg);
            }elseif($result == '99999999'){
                $msg = "Select Dates Leave already Exits.";
                Yii::$app->getSession()->setFlash('danger', $msg);
            }elseif($result == '99999998'){
                $msg = "Leave Saved as Draft Successfully.";
                Yii::$app->getSession()->setFlash('success', $msg);
            //                $key = Yii::$app->utility->encryptString($leave_app_id);
            //                $url = Yii::$app->homeUrl."employee/leave/applyforleave?securekey=$menuid&key=$leave_app_id";
            }else{
                $msg = "Leave Saved as Draft Successfully.";
                Yii::$app->getSession()->setFlash('success', $msg);
                $key = Yii::$app->utility->encryptString($result);
                $url = Yii::$app->homeUrl."employee/leave/applyforleave?securekey=$menuid&key=$key";
            }
            Yii::$app->utility->activities_logs('Leave', NULL, NULL, $jsonlogs, $msg);
            return $this->redirect($url);
        }
        
        $model = new EmployeeLeavesRequests();
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('applyforleave1', ['menuid'=>$menuid, 'model'=>$model, 'leave_app_id'=>$leave_app_id]);
    }
    
    public function actionApplicationaction(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."employee/leave/applyforleave?securekey=$menuid";
        if(isset($_GET['key']) AND !empty($_GET['key']) AND isset($_GET['action']) AND !empty($_GET['action'])){
            $leave_app_id = Yii::$app->utility->decryptString($_GET['key']);
            $actionType = Yii::$app->utility->decryptString($_GET['action']);
            if(empty($leave_app_id) OR empty($actionType)){
                Yii::$app->getSession()->setFlash('danger', "Invalid params value Found.");
                return $this->redirect($url);
            }
            /*
            * Check Is Submitted or Not
            */
            $chleaves = Yii::$app->hr_utility->hr_get_leaves('A', Yii::$app->user->identity->e_id, $leave_app_id, "Draft");
            if(empty($chleaves)){
                Yii::$app->getSession()->setFlash('danger', "No Application Found.");
                return $this->redirect($url);
            }
               
            if($actionType == 'E'){
                $leaveappid = Yii::$app->utility->encryptString($leave_app_id);
                $url = Yii::$app->homeUrl."employee/leave/applyforleave?securekey=$menuid&key=$leaveappid";
                /*
                 * Delete All Entries
                 */
                $result = $this->deleteAllLeaveReq($leave_app_id);
                if(empty($result)){
                    Yii::$app->getSession()->setFlash('danger', "No Record Found.");
                    return $this->redirect($url);
                }else{
                    Yii::$app->getSession()->setFlash('success', "Leave Entires Deleted Successfully.");
                    return $this->redirect($url);
                }
            }elseif($actionType == 'A'){
               /*
                * Delete All Requets
                */
                $this->deleteAllLeaveReq($leave_app_id);
                
                
                $result = Yii::$app->hr_utility->hr_update_emp_leave_application("Remove ", NULL, $leave_app_id, Yii::$app->user->identity->e_id, NULL, NULL);
                /*
                 * Logs
                 */
                $logs['action_type']="Remove";
                $logs['leave_app_id']=$leave_app_id;
                $logs['employee_code']=Yii::$app->user->identity->e_id;
                $jsonlogs= json_encode($logs);
                if($result == '2'){
                    $msg="Leave Application Deleted Successfully.";
                    Yii::$app->utility->activities_logs('Leave', NULL, NULL, $jsonlogs, $msg);
                    Yii::$app->getSession()->setFlash('success', $msg);                    
                }else{
                    $msg="Leave Application Not Deleted. Contact Admin.";
                    Yii::$app->utility->activities_logs('Leave', NULL, NULL, $jsonlogs, $msg);
                    Yii::$app->getSession()->setFlash('danger', $msg);
                }
                return $this->redirect($url);
            }elseif($actionType == 'S'){
                $result = Yii::$app->hr_utility->hr_update_emp_leave_application("Action", Yii::$app->user->identity->role, $leave_app_id, Yii::$app->user->identity->e_id, "Submitted ", NULL);
                /*
                 * Logs
                 */
                $logs['action_type']="Action";
                $logs['leave_app_id']=$leave_app_id;
                $logs['employee_code']=Yii::$app->user->identity->e_id;
                $logs['status']="Submitted";
                $jsonlogs= json_encode($logs);
                if($result == '3'){
                    $msg="Leave Application Sent For Approval.";
                    Yii::$app->utility->activities_logs('Leave', NULL, NULL, $jsonlogs, $msg);
                    Yii::$app->getSession()->setFlash('success', $msg);                    
                }else{
                    $msg="Leave Application Not Sent. Contact Admin.";
                    Yii::$app->utility->activities_logs('Leave', NULL, NULL, $jsonlogs, $msg);
                    Yii::$app->getSession()->setFlash('danger', $msg);
                }
                return $this->redirect($url);
            }else{
                $logs['leave_app_id']=$leave_app_id;
                $logs['employee_code']=Yii::$app->user->identity->e_id;
                $logs['remarks']="Fraudulent Data Detected. Invalid Action Type Found";
                $jsonlogs= json_encode($logs);
                $msg="Fraudulent Data Detected. Invalid Action Type Found.";
                Yii::$app->utility->activities_logs('Leave', NULL, NULL, $jsonlogs, $msg);
                Yii::$app->getSession()->setFlash('danger', $msg);
                return $this->redirect($url);
            }
            
        }
        Yii::$app->getSession()->setFlash('danger', "Invalid Params Found.");
        return $this->redirect($url);
    }
    public function deleteAllLeaveReq($leave_app_id)
    {
        $result = false;
        
        // echo "<pre>";print_r($leave_app_id); // die;

        $leaves = Yii::$app->hr_utility->hr_get_leaves('R', Yii::$app->user->identity->e_id, $leave_app_id, "Draft");

        // echo "<pre>";print_r($leaves); die;

        if(!empty($leaves))
        {
            $newArray = array();
            $i=0;
            
            foreach($leaves as $l)
            {
                $newArray[$i]['emp_req_id'] = $l['emp_req_id'];
                $newArray[$i]['leave_type'] = $l['leave_type'];
                $newArray[$i]['totaldays'] = $l['totaldays'];
                $newArray[$i]['leave_app_id'] = $l['leave_app_id'];
                $i++;
            }

            foreach($newArray as $n)
            {
                $null = NULL;
                $result = Yii::$app->hr_utility->hr_add_leave_app($n['emp_req_id'], $n['leave_app_id'], Yii::$app->user->identity->e_id, $null, $null, $null, $null, $n['leave_type'], $null, $null, $null, $n['totaldays'], $null, $null);
            }

            $jsonlogs = json_encode($newArray);
            Yii::$app->utility->activities_logs('Leave', NULL, NULL, $jsonlogs, "Leave Entires Deleted Successfully.");
            $result = true;
        }
        return $result;
    }
  
    //Apply for leave
    //    public function actionApplyforleave()
    //    {
    //        
    //        $this->layout = '@app/views/layouts/admin_layout.php';
    //        $model = new EmployeeLeavesRequests();
    //        if(isset($_POST['EmployeeLeavesRequests'])&& !empty($_POST['EmployeeLeavesRequests'])){
    //           
    //            $data=$_POST['EmployeeLeavesRequests'];
    //           
    //            $menuid = Yii::$app->utility->decryptString($data['menuid']);
    //            if(empty($menuid)){
    //                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Url.');
    //                return $this->redirect(Yii::$app->homeUrl);
    //            }
    //            $menuid = Yii::$app->utility->encryptString($menuid);
    //            $urll = Yii::$app->homeUrl."employee/leave/applyforleave?securekey=".$menuid;
    //            
    //            $emPID=Yii::$app->user->identity->e_id;
    //            $leave_reason=$data['leave_reason'];
    //            $contact_address= preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $data['contact_address']);
    //            $leave_type=Yii::$app->utility->decryptString($data['leave_type']);
    //            if(empty($leave_type)){
    //                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Leave Type');
    //                return $this->redirect($urll);
    //            }
    ////            echo "<pre>";print_r($data); die;
    //            
    //            
    //            if($leave_type != '1' ){ $whetherhalfday = "FULL"; }
    //            $from = date("Y-m-d", strtotime($data['from']));
    //            $till = date("Y-m-d", strtotime($data['till']));
    //            
    //            $fromdatetime = strtotime($from);
    //            $tilldatetime = strtotime($till);
    //            $datediff = $tilldatetime-$fromdatetime  ;
    //            $noofdays= round($datediff / (60 * 60 * 24));
    //            if($noofdays == "0"){
    //                $noofdays = "1";
    //            }else{
    //                $noofdays=$noofdays+1;
    //            }
    //            $whetherhalfday = "FULL";
    //            if(isset($data['whetherhalfday']) AND !empty($data['whetherhalfday'])){
    //                $whetherhalfday = $data['whetherhalfday'];
    //                if($whetherhalfday !='FULL'){ $noofdays="0.5"; }
    //            }
    //            
    //            $contno = $data['contact_no'];
    //            $availing_for_LTC = $data['availing_for_LTC'];
    //            
    //            $lcards = Yii::$app->hr_utility->get_employee_leaves(Yii::$app->user->identity->e_id);
    //            if(empty($lcards)){
    //                Yii::$app->getSession()->setFlash('danger', 'No Leave Details Found. Contact HR.');
    //                return $this->redirect($urll);
    //            }
    //            $leavependingcheck=0;
    //            $leavename="";
    //            foreach($lcards as $l){
    //                if($leave_type == $l['leave_type']){
    //                    $leavependingcheck=$l['balance_leaves'];
    //                    $leavename=$l['desc'];
    //                }
    //            }
    //            if($noofdays > $leavependingcheck){
    //                Yii::$app->getSession()->setFlash('danger', "You have $leavependingcheck balance leave of $leavename.");
    //                return $this->redirect($urll);
    //            }
    //                        
    //            $info = Yii::$app->hr_utility->hr_add_leave_app($emPID, $leave_reason, $availing_for_LTC, $contact_address, $contno, $leave_type, $whetherhalfday, $from, $till, $noofdays);
    //            /*
    //             * Add Logs
    //             */
    //            $logs['employee_code']=$emPID;
    //            $logs['leave_reason']=$leave_reason;
    //            $logs['availing_for_LTC']=$availing_for_LTC;
    //            $logs['contact_address']=$contact_address;
    //            $logs['contact_no']=$contno;
    //            $logs['leave_type']=$leave_type;
    //            $logs['whetherhalfday']=$whetherhalfday;
    //            $logs['from']=$from;
    //            $logs['till']=$till;
    //            $logs['totaldays']=$noofdays;
    //            $logs['status']="Pending";
    //            $jsonlogs = json_encode($logs);
    //            if($info == 1){
    //                Yii::$app->getSession()->setFlash('success', 'Leave applied successfully');
    //                Yii::$app->utility->activities_logs('Leave', 'employee/leave/applyforleave', NULL, $jsonlogs, "Leave applied successfully.");
    //                return $this->redirect($urll);
    //            }elseif($info == 2){
    //                Yii::$app->getSession()->setFlash('danger', 'You dont\'s have leave. Contact HR.');
    //                Yii::$app->utility->activities_logs('Leave', 'employee/leave/applyforleave', NULL, $jsonlogs, "You dont\'s have leave. Contact HR.");
    //                return $this->redirect($urll);
    //            }else{
    //                Yii::$app->getSession()->setFlash('danger', 'Error found. Contact Admin.');
    //                Yii::$app->utility->activities_logs('Leave', 'employee/leave/applyforleave', NULL, $jsonlogs, "Error found. Contact Admin.");
    //                return $this->redirect($urll);
    //            }
    //        }
    //        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
    //        $menuid = Yii::$app->utility->encryptString($menuid);
    //        return $this->render('applyforleave', ['model'=>$model, 'menuid'=>$menuid]);
    //    }
    //View Previous Leaves
    public function actionViewpreviousleave()
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('viewpreviousleave', ['menuid'=>$menuid]);
    }
    
    public function actionViewleaverequests(){
        
        if(isset($_GET['key']) && !empty($_GET['key'])){
            $leave_app_id = Yii::$app->utility->decryptString($_GET['key']);
            if(empty($leave_app_id)){
                $result['Status']='FF';
                $result['Res']='Invalid ID';
                echo json_encode($result);
                die;
            }
            
            $leaves = Yii::$app->hr_utility->hr_get_leaves('R', Yii::$app->user->identity->e_id, $leave_app_id, "Submitted,In-Process,Approved,Rejected");
            $html = "<table class='table table-bordered'>
                <tr>
                    <th>Leave Type</th>
                    <th>From Date</th>
                    <th>To Date</th>
                    <th>Total Days</th>
                </tr>";
            $detail = "";
            if(empty($leaves)){
                $detail = " <tr>
                    <td rowspan='4'>No Record Found</td>
                </tr>";
            }else{
                $detail = "";
                foreach($leaves as $l){
                    
                    $desc = $l['desc'];
                    $req_from_date = date('d-m-Y', strtotime($l['req_from_date']));
                    $req_to_date = date('d-m-Y', strtotime($l['req_to_date']));
                    $totaldays = $l['totaldays'];
                    $detail .= " <tr>
                        <td>$desc</td>
                        <td>$req_from_date</td>
                        <td>$req_to_date</td>
                        <td>$totaldays</td>
                    </tr>";
                }
            }
            $html = $html.$detail;
            $result['Status']='SS';
            $result['Res']=$html;
            echo json_encode($result);
            die;
        }else{
            $result['Status']='FF';
            $result['Res']='Invalid params found';
            echo json_encode($result);
            die;
        }
    }
    public function actionViewleavecard(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."employee/leave?securekey=$menuid";
        
        if(isset($_GET['key']) && !empty($_GET['key'])){
            
            $leavetype=Yii::$app->utility->decryptString($_GET['key']);
            if(empty($leavetype)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid params value.');
                return $this->redirect($url);
            }
            
            $leaves = Yii::$app->hr_utility->hr_get_card_leave_details($leavetype);
            
        //           echo "<pre>";print_r($leaves); die;
            $empname=Yii::$app->user->identity->fullname;
            $employee_code=Yii::$app->user->identity->employee_code;
            $dept_name=Yii::$app->user->identity->dept_name;
            $employment_type=Yii::$app->user->identity->employment_type;
            $jnddate=Yii::$app->user->identity->joining_date;
            $jnddate=date('d-M-Y', strtotime($jnddate));
            $employment_type=Yii::$app->hr_utility->fetchstaftype($employment_type);
            require_once './mpdff/mpdf.php';
            $mpdf = new \mPDF();
            
            $mpdf->WriteHTML($html = '<table width="100%" style="overflow: wrap"><tr><td align = "center">');
            $mpdf->WriteHTML($html = '<h3>'.ORGANAZATION_NAME.'</h3>');
            $mpdf->WriteHTML($html = '<br />');
            $mpdf->WriteHTML($html = '<h3 align="left">'.$levetyp['desc'].'</h3>');
            $mpdf->WriteHTML($html = '</td>');
            $mpdf->WriteHTML($html = '<td>');
            $imgurl = "";

            $mpdf->WriteHTML($html = '</td></tr></table><br />');
            $date=  date('d-M-Y H:i'); 
            $headerfont="border: 1px solid black;padding:5px;font-size:12px;";
            
            $detailfont="border: 1px solid black;padding:5px;font-size:12px;";
            
            $html ="<h5 style='padding:0px;margin:0px;text-align:right;'><b>Date : $date</b></h5>
                <h5 style='padding:0px;margin:0px;'><b>Appointment Details :-</b></h5>
            <table style='border: 1px solid black;border-collapse: collapse;' width='100%' >
                <tr style='border: 1px solid black'>
                    <td style='$headerfont' width='25%'>Employee Id:</td>
                    <td style='$headerfont' width='25%'>".$employee_code."</td>
                    <td style='$headerfont' width='25%'>Name:</td>
                    <td style='$headerfont' width='25%'>".$empname."</td>
                </tr>
                <tr>
                    <td style='$headerfont' width='25%'>Joining Date:</td>
                    <td style='$headerfont' width='25%'>".$jnddate."</td>
                    <td style='$headerfont' width='25%'>Left Date:</td>
                    <td style='$headerfont' width='25%'></td>
                </tr>
                <tr>
                    <td style='$headerfont' width='25%'>Staff Type:</td>
                    <td style='$headerfont' colspan='3'>".Yii::$app->user->identity->employment_type."</td>
                </tr>
                <tr>
                    <td style='$headerfont' width='25%'>Centre:</td>
                    <td style='$headerfont' width='25%'>".ORGANAZATION_CENTRE."</td>
                    <td style='$headerfont' width='25%'>Group:</td>
                    <td style='$headerfont' width='25%'>".$dept_name."</td>
                </tr>
            </table>
            <h5 style='padding:0px;margin:15px 0px 0px 0px;'><b>Leave Card Details :-</b></h5>
            <table style='border: 1px solid black;border-collapse: collapse;' width='100%' >
            <tr>
                <th style='$detailfont' width='15%'>Entry Type</th>
                <th style='$detailfont' width='12%'>From Date</th>
                <th style='$detailfont' width='12%'>To Date</th>
                <th style='$detailfont' width='12%'>Credit</th>
                <th style='$detailfont' width='12%'>Leaves</th>
                <th style='$detailfont' width='12%'>Balance</th>
                
            </tr>";
        //            <th style='$detailfont' width='25%'>Remarks</th>
            if(!empty($leaves)){
            foreach ($leaves as $key => $value) 
            { 
                $fromdate=date('d-M-Y', strtotime($value['from_date']));
                $todate=date('d-M-Y', strtotime($value['from_date']));
                if($value['status'] == "Approved" || $value['status'] == "ABRA"){
                    $leave = $credit="-";
                    if(!empty($value['credit'])){
                        $credit = $value['credit'];
                    }
                    if($value['leave'] > 0){
                        $leave = $value['leave'];
                    }
                $html.= "<tr><td style='$detailfont' width='15%'>$value[entry_type]</td> ;
                        <td style='$detailfont' width='12%'>$fromdate</td> 
                        <td style='$detailfont' width='12%'>$todate</td>
                        <td style='$detailfont' width='12%'>$credit</td> 
                        <td style='$detailfont' width='12%'>$leave</td> 
                        <td style='$detailfont' width='12%'>$value[balance]</td> 
                        
                    </tr>";
            //                <td style='$detailfont' width='25%'>Updated By HR</td>
                }
            }
            }else{
                $html .= "<tr><td colspan='6' style='$detailfont'>No Application Found</td> ;</tr>";
            }
            $html.="</table>";
            $mpdf->WriteHTML($html);
            $file = $mpdf->Output('download.pdf', 'I');
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . basename($file) . '"');
            header("Cache-Control: max-age=0");
            readfile($file);
        }
        Yii::$app->getSession()->setFlash('danger', 'Invalid Url.');
        return $this->redirect($url);
    }
    
    // Apply Extra Duty
    public function actionApplyextraduty()
    {
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('applyextraduty');
    }
    
    // View Extra Duty
    public function actionViewextraduty()
    {
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('viewextraduty');
    }
    
    public function actionCheckcanapplyhalfday(){
        if(isset($_POST['lcid']) AND !empty($_POST['lcid'])){
            $lcid = Yii::$app->utility->decryptString($_POST['lcid']);
            if(empty($lcid)){
                $result['Status']='FF';
                $result['Res']='Invalid Leave ID';
                echo json_encode($result);
                die;
            }
            $record = Yii::$app->hr_utility->hr_get_leaves_chart($lcid);
            if(empty($record)){
                $result['Status']='FF';
                $result['Res']='Invalid ID, No Leaves Found';
                echo json_encode($result);
                die;
            }
            
            if($record['can_apply_half_day'] == 'Y'){
                $html = '<option value="FULL">Full Day</option>
                <option value="F-HALF">First-Half</option>
                <option value="S-HALF">Second-Half</option>';
                $result['Status']='SS';
                $result['Res']=$html;
            }else{
                $result['Status']='SS';
                $result['Res']='';
            }
            
            echo json_encode($result);
            die;
        }else{
            $result['Status']='FF';
            $result['Res']='Invalid Param Found';
            echo json_encode($result);
            die;
        }
    }
}