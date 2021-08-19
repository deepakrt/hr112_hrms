<?php
namespace app\modules\hr\controllers;
use yii;
class ApproveleaveapplicationController extends \yii\web\Controller
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
        if(isset($_POST['Leaves']) AND !empty($_POST['Leaves']))
        {
            
            $menuid = Yii::$app->utility->decryptString($_POST['menuid']);
            if(empty($menuid)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Menu ID'); 
                return $this->redirect(Yii::$app->homeUrl);
            }
            $menuid = Yii::$app->utility->encryptString($menuid);
            $url = Yii::$app->homeUrl."hr/approveleaveapplication?securekey=$menuid";
            $post =$_POST['Leaves'];
            
            $newList = array();
            $i=0;
            foreach($post as $key=>$po){
                $emp_leave_id = Yii::$app->utility->decryptString($po['emp_leave_id']);
                if(!empty($emp_leave_id)){
                    $status = "";
                    if(Yii::$app->user->identity->role == '5'){
                        if($po['is_approved'] == 'Y' AND $po['is_rejected'] == 'N'){
                            $status = "Approved";
                        }elseif($po['is_approved'] == 'N' AND $po['is_rejected'] == 'Y'){
                            $status = "Rejected";
                        }
                    }elseif(Yii::$app->user->identity->role == '2' OR Yii::$app->user->identity->role == '4'){
                        if($po['is_approved'] == 'Y' AND $po['is_rejected'] == 'N'){
                            $status = "In-Process";
                        }elseif($po['is_approved'] == 'N' AND $po['is_rejected'] == 'Y'){
                            $status = "Rejected";
                        }
                    }
                    if(empty($status)){
                        Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Status'); 
                        return $this->redirect($url);
                    }
                    $leave_type = Yii::$app->utility->decryptString($po['leave_type']);
                    $from = Yii::$app->utility->decryptString($po['from']);
                    $till = Yii::$app->utility->decryptString($po['till']);
                    $e_id = Yii::$app->utility->decryptString($po['e_id']);
                    $totaldays = Yii::$app->utility->decryptString($po['totaldays']);
                    if(empty($leave_type) OR empty($from) OR empty($till) OR empty($e_id) OR empty($totaldays)){
                        Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Fields Found'); 
                        return $this->redirect($url);
                    }
                    $newList[$i]['leave_type']=$leave_type;
                    $newList[$i]['from']=$from;
                    $newList[$i]['till']=$till;
                    $newList[$i]['e_id']=$e_id;
                    $newList[$i]['totaldays']=$totaldays;
                    $newList[$i]['emp_leave_id']=$emp_leave_id;
                    $newList[$i]['status']=$status;
                    $i++;
                }
            }
            
            if(!empty($newList)){
                foreach($newList as $list){
                    $approvedBy = Yii::$app->user->identity->e_id;
                    $role = Yii::$app->user->identity->role;
                    
                    if($role == '5'){
                        $remarks = "Updated by HR";
                    }else{
                        $remarks = "Approved by Reporting Authority";
                    }
                    
                    $result = Yii::$app->hr_utility->hr_approve_leave($role, $list['emp_leave_id'], $list['leave_type'],$list['e_id'],$list['from'], $list['till'], $list['totaldays'], $remarks, $approvedBy,$list['status']);
                    /*
                    * Add Logs
                    */
                    $logs['emp_leave_id']=$list['emp_leave_id'];
                    $logs['leave_type']=$list['leave_type'];
                    $logs['e_id']=$list['e_id'];
                    $logs['from']=$list['from'];
                    $logs['till']=$list['till'];
                    $logs['totaldays']=$list['totaldays'];
                    $logs['remarks']=$remarks;
                    $logs['approvedBy']=$approvedBy;
                    $logs['status']=$list['status'];
                    $jsonlogs = json_encode($logs);
                    if($result == '1'){
                        Yii::$app->utility->activities_logs('Claim', 'hr/approveleaveapplication', $list['e_id'], $jsonlogs, 'No records found in Database of Emp ID '.$list['e_id']);
                        
                        Yii::$app->getSession()->setFlash('danger', 'No records found in Database of Emp ID '.$list['e_id']); 
                        return $this->redirect($url);
                    }
                    Yii::$app->utility->activities_logs('Claim', 'hr/approveleaveapplication', $list['e_id'], $jsonlogs, 'Application Updated Successfully');
                }
                Yii::$app->getSession()->setFlash('success', 'Application(s) Updated Successfully'); 
                return $this->redirect($url);
            }else{
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Fields Found'); 
                return $this->redirect($url);
            }
        }
        
        //        $param_map_id=$param_auth_type = null;
        //        $param_status="Pending";
        //        if(Yii::$app->user->identity->role == '2'){
        //            $param_auth_type = 'A2';
        //            $param_map_id = Yii::$app->user->identity->e_id;
        //        }elseif(Yii::$app->user->identity->role == '4'){
        //            $param_auth_type = 'A1';
        //            $param_map_id = Yii::$app->user->identity->e_id;
        //        }elseif(Yii::$app->user->identity->role == '5'){
        //            $param_status="In-Process";
        //        }
        //        $apps = Yii::$app->hr_utility->hr_get_leave_request($param_auth_type,$param_map_id,$param_status);

        if(Yii::$app->user->identity->role == '5'){
            $apps = Yii::$app->hr_utility->hr_get_leave_request(NULL,"ABRA,In-Process");
        }else{
            /*$emplist = Yii::$app->hr_utility->hr_get_appraise_list();
            $apps="";
            if(!empty($emplist)){
                $list="";
                foreach($emplist as $e){
                    $list .=$e['employee_code'].",";
                }
                $list = rtrim($list, ",");
                $apps = Yii::$app->hr_utility->hr_get_leave_request($list,"Submitted");
            }*/

            $apps = Yii::$app->hr_utility->hr_get_leave_requests(Yii::$app->user->identity->e_id,"Submitted,In-Process");
        }

        // echo Yii::$app->user->identity->role."-------<pre>";print_r($apps); die;
        
        return $this->render('index', ['menuid'=>$menuid, 'apps'=>$apps]);
    }
    public function actionViewandaction(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."hr/approveleaveapplication?securekey=$menuid";
        if(isset($_GET['key']) AND !empty($_GET['key']) AND isset($_GET['key2']) AND !empty($_GET['key2'])){
            $leave_app_id = Yii::$app->utility->decryptString($_GET['key']);
            $emp_code = Yii::$app->utility->decryptString($_GET['key2']);
            if(empty($leave_app_id) OR empty($emp_code)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid params value found.'); 
                return $this->redirect(Yii::$app->homeUrl);
            }
            if(Yii::$app->user->identity->role == '5'){
                $st = "ABRA,In-Process";
            }else{
                $st = "Submitted,In-Process";
            }
            
            $app = Yii::$app->hr_utility->hr_get_leaves('A', $emp_code, $leave_app_id, $st);

            /*echo $emp_code.'--'.$leave_app_id.'--'.$st."<pre>"; print_r($app);

            die();*/

            if(empty($app)){
                Yii::$app->getSession()->setFlash('danger', "No Application Found.");
                return $this->redirect($url);
            }
            
            $details = Yii::$app->hr_utility->hr_get_leaves('R', $emp_code, $leave_app_id, $st);
            if(empty($details)){
                Yii::$app->getSession()->setFlash('danger', "No Application Details Found.");
                return $this->redirect($url);
            }
            
            $emp = Yii::$app->utility->get_employees($emp_code);
            if(empty($emp)){
                Yii::$app->getSession()->setFlash('danger', "Employee Information Not Found.");
                return $this->redirect($url);
            }
            $this->layout = '@app/views/layouts/admin_layout.php';
            return $this->render('viewandaction', ['menuid'=>$menuid, 'app'=>$app, 'details'=>$details, 'emp'=>$emp]);
        }
        Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Url.'); 
        return $this->redirect(Yii::$app->homeUrl);
    }
    
    public function actionUpdateapplication()
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."hr/approveleaveapplication?securekey=$menuid";
        if(isset($_POST['App']) AND !empty($_POST['App']) AND isset($_POST['Leaves']) AND !empty($_POST['Leaves'])){

            /*echo "<pre>"; print_r($_POST);
            die();*/
            
            $post = $_POST['App'];
            $leave_app_id = Yii::$app->utility->decryptString($post['leave_app_id']);
            $employee_code = Yii::$app->utility->decryptString($post['employee_code']);
            $status = Yii::$app->utility->decryptString($post['status']);
            $remarks = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['remarks']));

            if(empty($leave_app_id) OR empty($employee_code) OR empty($status)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid params value found.'); 
                return $this->redirect($url);
            }
            
            $new = array();            


            if(isset($_POST['forward_to']))
            {   
                $forward_to = $_POST['forward_to'];

                $dept_id = $forward_to['dept_id'];
                $designation_id = $forward_to['designation_id'];
                $forward_to_id = $forward_to['employee_Data'];

                $status = "In-Process";

                $new['leave_app_id'] = $leave_app_id;
                $new['employee_code'] = $employee_code;
                $new['status'] = $status;
                $new['remarks'] = $remarks;
                $new['forward_to'] = $forward_to;                

                $result = Yii::$app->hr_utility->hr_update_emp_leave_application_forward($leave_app_id, $employee_code, $status, $remarks,$forward_to_id);

                // Yii::$app->hr_utility->hr_update_emp_leave_application("Action", NULL, $leave_app_id, $employee_code, $status, $remarks);

                // $result = 2;
            }
            else
            {
                
                if(Yii::$app->user->identity->role == '5'){
                    $st = "ABRA,In-Process";
                }else{
                    $st = "Submitted,In-Process";
                }

                /*
                 * Check Application Exits
                 */
                $app = Yii::$app->hr_utility->hr_get_leaves('A', $employee_code, $leave_app_id, $st);



                if(empty($app)){
                    Yii::$app->getSession()->setFlash('danger', "No Application Found.");
                    return $this->redirect($url);
                }
                                
                
                $new=array();
                
                $leaves = $_POST['Leaves'];

                $i=0;
                foreach($leaves as $l){
                    $leave_type = Yii::$app->utility->decryptString($l['key1']);
                    $totaldays = Yii::$app->utility->decryptString($l['key2']);
                    if(empty($leave_type) OR empty($totaldays)){
                        Yii::$app->getSession()->setFlash('danger', "Invalid Leave Found.");
                        return $this->redirect($url);
                    }

                    $lcards = Yii::$app->hr_utility->get_employee_leaves($employee_code);
                    foreach($lcards as $c){
                        if($c['leave_type'] == $leave_type){
                            if($c['pending_leaves'] > $totaldays){
                                $pending_leaves = $c['pending_leaves']-$totaldays;
                            }else{
                                $pending_leaves = $c['pending_leaves'];
                            }
                            $new[$i]['leave_type']=$leave_type;
                            $new[$i]['totaldays']=$totaldays;
                            $new[$i]['pending_leaves']=$pending_leaves;
                            $new[$i]['balance_leaves']=$c['balance_leaves'];
                            $i++;
                        }
                    }
                }

                if(empty($new)){
                    Yii::$app->getSession()->setFlash('danger', "Invalid Leave Details Found.");
                    return $this->redirect($url);
                }
                /*
                 * Update Leave Card
                 */
                
                $details = Yii::$app->hr_utility->hr_get_leaves('R', $employee_code, $leave_app_id, "Submitted,ABRA,In-Process");
                
                //            echo "<pre>";print_r($new); 
                //            echo "$status<pre>";print_r($details); 
                //            die;
                foreach($details as $d){
                    $param_balance = $d['balance_leaves'];
                    if($status == 'Rejected'){
                        $param_balance = $d['balance_leaves']+$d['totaldays'];
                    }
                    Yii::$app->hr_utility->hr_add_leave_card_details($d['leave_type'], "Leave", $d['req_from_date'], NULL, $d['totaldays'], $param_balance, "Updated by ".Yii::$app->user->identity->role_name, Yii::$app->user->identity->employee_code, $employee_code, $status, $leave_app_id, $d['req_to_date']);
                    
                }
                
                /*
                 * Update Leave Chart and application
                 */
                if($status == 'ABRA' || $status == 'Approved' || $status == 'Rejected'){
                    $param_action_type = $param_balance_leaves = NULL;
                    
                    foreach($new as $n){
                        $pending_leaves = $n['pending_leaves'];
                        if($status == 'Rejected'){
                            $param_action_type = "R";
                            $param_balance_leaves = $n['balance_leaves']+$n['totaldays'];
                            $pending_leaves = $n['pending_leaves']-$n['totaldays'];
                        }
                        if($status == 'Approved' OR $status == 'ABRA'){
                            $pending_leaves = $n['pending_leaves']-$n['totaldays'];
                        }
                        Yii::$app->hr_utility->hr_update_emp_leave_chart($param_action_type, $employee_code, $n['leave_type'], $pending_leaves, $param_balance_leaves);
                    }
                }
                
                $result = Yii::$app->hr_utility->hr_update_emp_leave_application("Action", NULL, $leave_app_id, $employee_code, $status, $remarks);
            }

            //            die($result);
            /*
             * $logs
             */
            $logs['action_type']="Action";
            $logs['role_id']=Yii::$app->user->identity->role;
            $logs['leave_app_id']=$leave_app_id;
            $logs['employee_code']=$employee_code;
            $logs['status']=$status;
            $logs['remarks']=$remarks;
            $new = json_encode($new);
            $logs['leave_chart_details']=$new;
            $jsonlogs = json_encode($logs);
            if($result == '1'){
                $msg = "Leave Application Updated Successfully";
                Yii::$app->getSession()->setFlash('success', $msg); 
            }elseif($result == '3'){
                $msg = "Leave Application Forwarded Successfully";
                Yii::$app->getSession()->setFlash('success', $msg); 
            }else{
                $msg = "Leave Application Not Updated. Contact Admin.";
                Yii::$app->getSession()->setFlash('danger', $msg); 
            }
            Yii::$app->utility->activities_logs('Leave', NULL, $employee_code, $jsonlogs, $msg);
            return $this->redirect($url);
        }
        Yii::$app->getSession()->setFlash('danger', 'Invalid params found.'); 
        return $this->redirect($url);
    }
    
    public function actionViewleaverequests(){
        
        if(isset($_GET['key']) && !empty($_GET['key']) AND isset($_GET['key2']) && !empty($_GET['key2']) ){
            $leave_app_id = Yii::$app->utility->decryptString($_GET['key']);
            $ec = Yii::$app->utility->decryptString($_GET['key2']);
            if(empty($leave_app_id) OR empty($ec)){
                $result['Status']='FF';
                $result['Res']='Invalid ID';
                echo json_encode($result);
                die;
            }
            if(Yii::$app->user->identity->role == '5'){
                $st = "Approved,Rejected";
            }else{
                $st = "ABRA,Approved,Rejected";
            }
            $leaves = Yii::$app->hr_utility->hr_get_leaves('R', $ec, $leave_app_id, $st);
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

    public function actionGet_comman_section()
    {
        $keyval = $_POST['keyval'];

        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);


        if($keyval != 'Forward')
        {
            $result['Status']='FF';
            echo json_encode($result);die;            
        }


        $collectData['menuid'] = $menuid;

        // $attid = NULL;
        $collectData['depts'] = Yii::$app->utility->get_dept(null);
        // $collectData['desgs'] = Yii::$app->utility->get_designation(null);

        // echo "<pre>"; print_r($collectData); die();


        $html = $this->renderPartial('comman_section', $collectData);
        $concat = '';

        $allConcat['render_data'] = $html;

        echo json_encode($allConcat);
        die();

    }


//    public function actionRejectappcation(){
//        if(isset($_GET['securekey']) AND !empty($_GET['securekey']) AND isset($_GET['leaveid']) AND !empty($_GET['leaveid'])){
//            $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
//            $emp_leave_id = Yii::$app->utility->decryptString($_GET['leaveid']);
//            if(empty($menuid) OR empty($emp_leave_id)){
//                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Url.'); 
//                return $this->redirect(Yii::$app->homeUrl);
//            }
//            echo "<pre>"; print_r($menuid); 
//            echo "<pre>"; print_r($emp_leave_id); 
//            die;
//            
//        }else{
//            return $this->redirect(Yii::$app->homeUrl);
//        }
//    }
}


