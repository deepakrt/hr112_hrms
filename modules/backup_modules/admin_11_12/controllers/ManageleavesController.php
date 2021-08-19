<?php

namespace app\modules\admin\controllers;
use yii;

class ManageleavesController extends \yii\web\Controller
{
    public function beforeAction($action){
        if(isset($_GET['securekey']) AND !empty($_GET['securekey'])){
            $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
            if(empty($menuid)){ return $this->redirect(Yii::$app->homeUrl); }            
            $chkValid = Yii::$app->utility->validate_url($menuid);
            if(empty($chkValid)){ return $this->redirect(Yii::$app->homeUrl); }
            return true;
        }else{ return $this->redirect(Yii::$app->homeUrl); }
        parent::beforeAction($action);
    }
    
    public function actionIndex(){
        $this->layout = '@app/views/layouts/admin_layout.php';
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $leaves = Yii::$app->hr_utility->hr_get_leaves_chart();
        return $this->render('index', ['menuid'=>$menuid, 'leaves'=>$leaves]);
    }
    
    public function actionAddnewentry() {
        $this->layout = '@app/views/layouts/admin_layout.php';
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."admin/manageleaves/addnewentry?securekey=$menuid";
        if(isset($_POST['LeaveChart']) AND !empty($_POST['LeaveChart'])){
            $post = $_POST['LeaveChart'];
//            echo "<pre>";print_r($post); die;
            $year = Yii::$app->utility->decryptString($post['year']);
            $session_type = Yii::$app->utility->decryptString($post['session_type']);
            $leave_type = Yii::$app->utility->decryptString($post['leave_type']);
            $emp_type = Yii::$app->utility->decryptString($post['emp_type']);
            $leave_for = Yii::$app->utility->decryptString($post['leave_for']);
            $can_apply_half_day = Yii::$app->utility->decryptString($post['can_apply_half_day']);
            $can_encashment = Yii::$app->utility->decryptString($post['can_encashment']);
            
            if(empty($leave_type) OR empty($emp_type) OR empty($year) OR empty($session_type) OR empty($can_encashment) OR empty($leave_for) OR empty($can_apply_half_day)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid Params value Found.'); 
                return $this->redirect($url);
            }
            $leave_count = trim(preg_replace('/[^0-9-]/', '', $post['leave_count']));
            $carry_fwd = trim(preg_replace('/[^0-9-]/', '', $post['carry_fwd']));
            
            if((empty($leave_count) AND $leave_count < 0 ) OR (empty($carry_fwd) AND $carry_fwd < 0 )){
                Yii::$app->getSession()->setFlash('danger', 'Invalid Params value Found.'); 
                return $this->redirect($url);
            }
            
            $result = Yii::$app->hr_utility->hr_add_update_leaves_chart(NULL, $leave_type, $leave_for, $can_apply_half_day, $leave_count, $emp_type, $year, $session_type, $carry_fwd, $can_encashment);
            
            /*
             * Logs
             */
            $logs['year']=$year;
            $logs['leave_type']=$leave_type;
            $logs['leave_for']=$leave_for;
            $logs['can_apply_half_day']=$can_apply_half_day;
            $logs['leave_count']=$leave_count;
            $logs['emp_type']=$emp_type;
            $logs['session_type']=$session_type;
            $logs['carry_fwd']=$carry_fwd;
            $logs['can_encashment']=$can_encashment;
            $jsonlogs = json_encode($logs);
            if($result == '3'){
                
                Yii::$app->utility->activities_logs("Master Data", NULL, NULL, $jsonlogs, "Manege Leave : Details Already Exits.");
                
                Yii::$app->getSession()->setFlash('danger', 'Details Already Exits.');
                return $this->redirect($url);
            }elseif($result == '1'){
                Yii::$app->utility->activities_logs("Master Data", NULL, NULL, $jsonlogs, "Manege Leave : Details Added Successfully.");
                
                Yii::$app->getSession()->setFlash('success', 'Details Added Successfully.');
                return $this->redirect($url);
            }else{
                Yii::$app->utility->activities_logs("Master Data", NULL, NULL, $jsonlogs, "Manege Leave : Entry Not Added. Contact Admin.");
                Yii::$app->getSession()->setFlash('danger', 'Entry Not Added. Contact Admin.');
                return $this->redirect($url);
            }
        }
        
        $leaveType = Yii::$app->hr_utility->hr_get_master_leave_type(NULL);
        return $this->render('addnewentry', ['menuid'=>$menuid, 'leaveType'=>$leaveType]);
    }
    public function actionViewassignedleaves() {
        $allEmps = Yii::$app->utility->get_employees();
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('viewassignedleaves', ['allEmps'=>$allEmps, 'menuid'=>$menuid]);
    }
    public function actionGetleavedetail() {
        if(isset($_GET['code']) AND !empty($_GET['code'])){
            $code = Yii::$app->utility->decryptString($_GET['code']);
            if(empty($code)){
                $result['Status']='FF';
                $result['Res']='Invalid Employee ID';
                echo json_encode($result);
                die;
            }
            $info = Yii::$app->utility->get_employee_leaves($code);
            if(empty($info)){
                $result['Status']='FF';
                $result['Res']='No Leaves Assigned';
                echo json_encode($result);
                die;
            }
             $html = "<tr>
                        <th></th>
                        <th>Session Year</th>
                        <th>Balance</th>
                        <th>Pending</th>
                        <th>Available</th>
                    </tr>";
            foreach($info as $in){
                $bal=$in['balance_leaves'];
                $pending=$in['pending_leaves'];
                
                $avail=$bal-$pending;
                $avail = number_format($avail,1);
                $desc=$in['desc'];
                $session_year=$in['session_year'];
                $html .= "<tr>
                        <td>$desc</td>
                        <td>$session_year</td>
                        <td>$bal</td>
                        <td>$pending</td>
                        <td>$avail</td>
                        </tr>";
            }
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
    
    
    public function actionLeavestype(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."admin/manageleaves/leavestype?securekey=$menuid";
        if(isset($_POST['LeaveType']) AND !empty($_POST['LeaveType'])){
            $post = $_POST['LeaveType'];
            $label = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['label']));
            $description = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['description']));
            $result = Yii::$app->hr_utility->hr_add_update_master_leave_type(NULL, $label, $description);
            
            /*
             * Logs
             */
            $logs['label']=$label;
            $logs['description']=$description;
            $jsonlogs = json_encode($logs);
            if($result == '1'){
                Yii::$app->utility->activities_logs("Master Data", NULL, NULL, $jsonlogs, "Leave Type Added Successfully.");
                
                Yii::$app->getSession()->setFlash('success', 'Leave Type Added Successfully.'); 
                return $this->redirect($url);
            }else{
                Yii::$app->utility->activities_logs("Master Data", NULL, NULL, $jsonlogs, "Leave Type Not Added.");
                Yii::$app->getSession()->setFlash('danger', 'Leave Type Not Added.'); 
                return $this->redirect($url);
            }
        }
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('leavestype', ['menuid'=>$menuid]);
    }
    
    public function actionDeleteleavestype(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."admin/manageleaves/leavestype?securekey=$menuid";
        if(isset($_GET['ltid']) AND !empty($_GET['ltid'])){
            $ltid = Yii::$app->utility->decryptString($_GET['ltid']);
            if(empty($ltid)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid Leave Type ID.'); 
                return $this->redirect($url);
            }
            $result = Yii::$app->hr_utility->hr_add_update_master_leave_type($ltid, NULL, NULL);
            /*
             * Logs
             */
            $logs['lt_id']=$ltid;
            $jsonlogs = json_encode($logs);
            
            if($result == '2'){
                Yii::$app->utility->activities_logs("Master Data", NULL, NULL, $jsonlogs, "Leave Type Deleted Successfully.");
                Yii::$app->getSession()->setFlash('success', 'Leave Type Deleted Successfully.'); 
                return $this->redirect($url);
            }else{
                Yii::$app->utility->activities_logs("Master Data", NULL, NULL, $jsonlogs, "Leave Type Not Deleted.");
                Yii::$app->getSession()->setFlash('danger', 'Leave Type Not Deleted.'); 
                return $this->redirect($url);
            }
        }
        Yii::$app->getSession()->setFlash('danger', 'Invalid Params Found.'); 
        return $this->redirect($url);
    }
    
    public function actionAssignleave(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."admin/manageleaves/assignleave?securekey=$menuid";
        if(isset($_POST['AssignLeave']) AND !empty($_POST['AssignLeave'])){
            $post = $_POST['AssignLeave'];
//            echo "<pre>";print_r($post); die;
            $emp_type = $post['emp_type'];
            if($emp_type == 'R'){}elseif($emp_type == 'C'){}else{
                Yii::$app->getSession()->setFlash('danger', 'Invalid Employee Type..'); 
                return $this->redirect($url);
            }
            $year = Yii::$app->utility->decryptString($post['year']);
            $session_type = Yii::$app->utility->decryptString($post['session_type']);
            $leave_type = Yii::$app->utility->decryptString($post['leave_type']);
            $leave_for = Yii::$app->utility->decryptString($post['leave_for']);
            $carry_fwd = Yii::$app->utility->decryptString($post['carry_fwd']);
            $can_encash = Yii::$app->utility->decryptString($post['can_encash']);
            $leave_count = Yii::$app->utility->decryptString($post['leave_count']);
            $leave_chart_id = Yii::$app->utility->decryptString($post['leave_chart_id']);
            //$carry_fwd 
            if(empty($leave_chart_id) OR empty($emp_type) OR empty($leave_type) OR empty($leave_count) OR empty($year) OR empty($session_type) OR empty($can_encash) OR empty($leave_for)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid Params Value Found.'); 
                return $this->redirect($url);
            }
            if(empty($carry_fwd) AND $carry_fwd < 0 ){
                Yii::$app->getSession()->setFlash('danger', 'Invalid Params value Found.'); 
                return $this->redirect($url);
            }
            
            /*
             * Leave Chart ID is valid  
             */
            
            
            if($leave_for == 'F'){
                $leave_for = "F";
            }elseif($leave_for == 'M'){
                $leave_for = "M";
            }else{
                $leave_for = "A";
            }
//            echo "$leave_chart_id<br>";
//            echo "$year <br>";
//            echo "$session_type <br>";
//            echo "$leave_type <br>";
//            echo "$emp_type <br>";
//            echo "$leave_for <br>";
            $check = Yii::$app->hr_utility->hr_get_leaves_chart($leave_chart_id);
            $ch = false;
            if($check['year'] == $year AND $check['session_type'] == $session_type AND $check['leave_type'] == $leave_type AND $check['emp_type'] == $emp_type AND $check['leave_for'] == $leave_for){
                $ch = true;
            }
            if(empty($ch)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid Leave Chart ID.'); 
                return $this->redirect($url);
            }
//            echo "<pre>";print_r($check); die;
            
//            echo "$year<br>";
//            echo "$session_type<br>";
//            echo "$leave_type<br>";
//            echo "$emp_type<br>";
            //Check Record Exits
            $record = Yii::$app->hr_utility->hr_get_leaves_detail_chart($year,$session_type, $leave_type, NULL, $emp_type);

            if(!empty($record)){
                Yii::$app->getSession()->setFlash('danger', "Leave Details of Session Year : $year / Session Type : $session_type of selected leave already exits."); 
                return $this->redirect($url);
            }else{
                /*
                 * Check if session type lapsed example first half yearly leaves.
                 */
                $record = Yii::$app->hr_utility->hr_get_leaves_detail_chart($year,$session_type, $leave_type, NULL, $emp_type, "N");
                if(!empty($record)){
                    Yii::$app->getSession()->setFlash('danger', "Session Lapsed. Cannot Assign Leaves."); 
                return $this->redirect($url);
                }
            }
//            echo "<pre>";print_r($record);
//            die('000');
            $empLists = Yii::$app->hr_utility->hr_get_emp_for_assign_leave($leave_for, $emp_type);

            if(empty($empLists)){
                Yii::$app->getSession()->setFlash('danger', "No Employee List Found."); 
                return $this->redirect($url);
            }
            
            /*
             * Get Last Leave Detail Chart 
             */
            $l_year = $year;
            if($session_type == 'Y'){
                $l_year = $year-1;
                $effected_from_date = "$year-01-01";
            }elseif($session_type == 'FHY'){
                $effected_from_date = "$year-01-01";
            }elseif($session_type == 'SHY'){
                $effected_from_date = "$year-07-01";
            }
            $finalList = "";
            $i=0;
            
            foreach($empLists as $emp){
                $record = "";
                
//                echo $emp['employee_code']."<br>";
                $record = Yii::$app->hr_utility->hr_get_leaves_detail_chart($l_year,NULL, $leave_type, $emp['employee_code'], $emp_type);
//                echo "111<pre>"; print_r($record); die;
                
                if(!empty($record)){
                    $pending_leaves = $record['pending_leaves'];
                    $balance_leaves = $record['balance_leaves'];
                    
                    $totalLapsed = $totalCarryFwd=0;
                     
                    if($balance_leaves > $carry_fwd){
                        $totalCarryFwd=$carry_fwd;
                        $totalLapsed = $balance_leaves-$carry_fwd;
                    }
                    $totalBalLeaves = $leave_count+$totalCarryFwd;
                    $lasped_entry_type = "Lapsed";
                    if($can_encash == 'Y'){
                        $lasped_entry_type = "Encasement";
                    }
                    $finalList[$i]['employee_code'] = $emp['employee_code'];
                    $finalList[$i]['details_chart_id'] = $record['ld'];
                    $finalList[$i]['last_session_year'] = $l_year;
                    $finalList[$i]['session_year'] = $year;
                    $finalList[$i]['session_type'] = $session_type;
                    $finalList[$i]['leave_type'] = $leave_type;
                    $finalList[$i]['total_leaves'] = $leave_count;
                    $finalList[$i]['credit_leaves'] = $leave_count;
                    $finalList[$i]['pending_leaves'] = $record['pending_leaves'];
                    $finalList[$i]['balance_leaves'] = $totalBalLeaves;
                    $finalList[$i]['entry_type'] = "Accrual";
                    $finalList[$i]['lasped_entry_type'] = $lasped_entry_type;
                    $finalList[$i]['effected_from_date'] = $effected_from_date;
                    $finalList[$i]['total_leave_lapsed'] = $totalLapsed;
                    $finalList[$i]['lapsedbalanceleaves'] = $balance_leaves;
                    $finalList[$i]['emp_type'] = $emp_type;
                    $i++;
                }else{
                    $finalList[$i]['employee_code'] = $emp['employee_code'];
                    $finalList[$i]['details_chart_id'] = NULL;
                    $finalList[$i]['last_session_year'] = $l_year;
                    $finalList[$i]['session_year'] = $year;
                    $finalList[$i]['session_type'] = $session_type;
                    $finalList[$i]['leave_type'] = $leave_type;
                    $finalList[$i]['total_leaves'] = $leave_count;
                    $finalList[$i]['credit_leaves'] = $leave_count;
                    $finalList[$i]['pending_leaves'] = '0';
                    $finalList[$i]['balance_leaves'] = $leave_count;
                    $finalList[$i]['entry_type'] = "Accrual";
                    $finalList[$i]['lasped_entry_type'] = "Accrual";
                    $finalList[$i]['effected_from_date'] = $effected_from_date;
                    $finalList[$i]['total_leave_lapsed'] = '0';
                    $finalList[$i]['lapsedbalanceleaves'] = $leave_count;
                    $finalList[$i]['emp_type'] = $emp_type;
                    $i++;
                }
            }
            $totalEmp = count($empLists);
            $TotalfinalList = count($finalList);
//            echo "<pre>";print_r($finalList); die;
            if(empty($finalList)){
                Yii::$app->getSession()->setFlash('danger', "Leaves Already Assigned."); 
                return $this->redirect($url);
            }
//            echo "<pre>";print_r($finalList); die;
            //Final Insert in Details Chart and Card Detail
            foreach($finalList as $final){
                //If entry lapsed
                $remarks = "";
                if($final['total_leave_lapsed'] > 0){
                    $remarks = $final['total_leave_lapsed']." Leaves ".$final['lasped_entry_type'];
                    
                    Yii::$app->hr_utility->hr_add_leave_card_details($final['leave_type'], $final['lasped_entry_type'], $final['effected_from_date'], NULL,$final['total_leave_lapsed'], $final['lapsedbalanceleaves'], $remarks, Yii::$app->user->identity->e_id, $final['employee_code'], "Approved");
                    
                }else{
                    $remarks = "New Leaves Assigned by HR for the Session $year ($session_type)";
                    Yii::$app->hr_utility->hr_add_leave_card_details($final['leave_type'], $final['lasped_entry_type'], $final['effected_from_date'], $final['credit_leaves'],$final['total_leave_lapsed'], $final['lapsedbalanceleaves'], $remarks, Yii::$app->user->identity->e_id, $final['employee_code'], "Approved");
                }
                
                /*
                 * hr_leave_card_details Logs
                 */
                $logs['leave_chart_id']=$leave_chart_id;
                $logs['employee_code']=$final['employee_code'];
                $logs['emp_type']=$final['emp_type'];
                $logs['details_chart_id']=$final['details_chart_id'];
                $logs['session_year']=$final['session_year'];
                $logs['session_type']=$final['session_type'];
                $logs['leave_type']=$final['leave_type'];
                $logs['credit_leaves']=$final['credit_leaves'];
                $logs['total_leaves']=$final['total_leaves'];
                $logs['lasped_entry_type']=$final['lasped_entry_type'];
                $logs['effected_from_date']=$final['effected_from_date'];
                $logs['total_leave_lapsed']=$final['total_leave_lapsed'];
                $logs['lapsedbalanceleaves']=$final['lapsedbalanceleaves'];
                $logs['remarks']=$remarks;
                $jsonlogs = json_encode($logs);
                
                Yii::$app->utility->activities_logs("Master Data", NULL, $final['employee_code'], $jsonlogs, "Master Leave : Leave Card Updated. Final Insertion Pending");
                
                if($final['last_session_year'] == $final['session_year']){
                    //Final Insert in Details Chart and Card Detail
                    /*
                     * Add new Entry
                     */
                    
                    if($session_type != 'Y' AND !empty($final['details_chart_id'])){
                        //For invalid the old Record example First Half Year leaves
                        $result  = Yii::$app->hr_utility->hr_add_update_leaves_detail_chart($final['details_chart_id'], $final['session_year'], $final['session_type'], $final['leave_type'], $final['total_leaves'], $final['pending_leaves'], $final['balance_leaves'], "Leaves Assigned By HR", $final['employee_code'], $final['emp_type'], $leave_chart_id);
                    }
                    
                    $result = Yii::$app->hr_utility->hr_add_update_leaves_detail_chart(NULL, $final['session_year'], $final['session_type'], $final['leave_type'], $final['total_leaves'], $final['pending_leaves'], $final['balance_leaves'], "Leaves Assigned By HR", $final['employee_code'], $final['emp_type'], $leave_chart_id);
                    /* 
                    * Logs
                    */
                    $logs1['leave_chart_id']=$leave_chart_id;
                    $logs1['emp_type']=$final['emp_type'];
                    $logs1['employee_code']=$final['employee_code'];
                    $logs1['details_chart_id']=$final['details_chart_id'];
                    $logs1['session_year']=$final['session_year'];
                    $logs1['session_type']=$final['session_type'];
                    $logs1['leave_type']=$final['leave_type'];
                    $logs1['total_leaves']=$final['total_leaves'];
                    $logs1['pending_leaves']=$final['pending_leaves'];
                    $logs1['balance_leaves']=$final['balance_leaves'];
                    $logs1['Remarks']="Leaves Assigned By HR";
                    $jsonlogs1 = json_encode($logs1);
                    Yii::$app->utility->activities_logs("Master Data", NULL, $final['employee_code'], $jsonlogs1, "Master Leave : Session Year is same. Leaves Assigned Successfully.");
                }  else {
                    /*
                     * Update is Active NO
                     */
                    if(!empty($final['details_chart_id'])){
                        $result  = Yii::$app->hr_utility->hr_add_update_leaves_detail_chart($final['details_chart_id'], $final['session_year'], $final['session_type'], $final['leave_type'], $final['total_leaves'], $final['pending_leaves'], $final['balance_leaves'], "Leaves Assigned By HR", $final['employee_code'], $final['emp_type'], $leave_chart_id);
                    }
                    /*
                    * Add new Entry
                    */
                    $result = Yii::$app->hr_utility->hr_add_update_leaves_detail_chart(NULL, $final['session_year'], $final['session_type'], $final['leave_type'], $final['total_leaves'], $final['pending_leaves'], $final['balance_leaves'], "Leaves Assigned By HR", $final['employee_code'], $final['emp_type'], $leave_chart_id);
                    /*
                    * Logs
                    */
                   $logs1['leave_chart_id']=$leave_chart_id;
                   $logs1['employee_code']=$final['employee_code'];
                   $logs1['session_year']=$final['session_year'];
                   $logs1['session_type']=$final['session_type'];
                   $logs1['leave_type']=$final['leave_type'];
                   $logs1['total_leaves']=$final['total_leaves'];
                   $logs1['pending_leaves']=$final['pending_leaves'];
                   $logs1['balance_leaves']=$final['balance_leaves'];
                   $logs1['Remarks']="Leaves Assigned By HR. details_chart_id is_active updated with 'N'. ID ".$final['details_chart_id'];
                   $jsonlogs1 = json_encode($logs1);
                   Yii::$app->utility->activities_logs("Master Data", NULL, $final['employee_code'], $jsonlogs1, "Master Leave : Leaves Assigned Successfully.");
                }
            }
            Yii::$app->getSession()->setFlash('success', "Leaves Assigned Successfully."); 
            return $this->redirect($url);
        }
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('assignleave', ['menuid'=>$menuid]);
    }
    
    public function actionGethrleavechart(){
//        echo "<pre>";print_r($_GET); die;
        if(isset($_GET['emptype']) AND !empty($_GET['emptype']) AND isset($_GET['year']) AND !empty($_GET['year']) AND isset($_GET['sessiontype']) AND !empty($_GET['sessiontype'])){
            $year = Yii::$app->utility->decryptString($_GET['year']);
            $sessiontype = Yii::$app->utility->decryptString($_GET['sessiontype']);
            if(empty($year) OR empty($sessiontype)){
                $result['Status'] = 'FF';
                $result['Msg'] = "Invalid Session Year";
                echo json_encode($result);
                die;
            }
            
            $leavesChart = Yii::$app->hr_utility->hr_get_leaves_chart();
            
            $html = "<option value=''>Select Leave Type</option>";
            $chk = true;

            if(!empty($leavesChart)){
               $i=1;
                foreach($leavesChart as $leave){
                    if($leave['year'] == $year AND $leave['session_type'] == $sessiontype AND $leave['emp_type'] == $_GET['emptype']){
                        $i=$i+1;
                        $chk = false;
                        $name = $leave['desc']." (".$leave['label'].")";
                        $lt_id = Yii::$app->utility->encryptString($leave['lt_id']);
//                        $leave_chart_id = Yii::$app->utility->encryptString($leave['lc_id']);
                        $html .= "<option value='$lt_id'>$name</option>";
                        
                    }
                }
                if(!empty($chk)){
                    $html = "<option value=''>Select Leave Type</option><option value='' disabled>No Details Found in Leave Chart</option>";
                }
                $result['Status'] = 'SS';
                $result['leaveType'] = $html;
                echo json_encode($result);
                die;
            }else{
                $result['Status'] = 'FF';
                $result['Msg'] = "Leaves Chart is Empty.";
                echo json_encode($result);
                die;
            }
            
        }
    }
    
    public function actionGetleavechartdetailsbyid(){
//         echo "<pre>";print_r($_GET);die;
        if(isset($_GET['id']) AND !empty($_GET['id']) AND isset($_GET['assign_leave_year']) AND !empty($_GET['assign_leave_year']) AND isset($_GET['assignemptype']) AND !empty($_GET['assignemptype']) AND isset($_GET['sessiontype']) AND !empty($_GET['sessiontype'])){
            
            $id = Yii::$app->utility->decryptString($_GET['id']);
            
            $assign_leave_year = Yii::$app->utility->decryptString($_GET['assign_leave_year']);
            $sessiontype = Yii::$app->utility->decryptString($_GET['sessiontype']);
            
            if(empty($id) OR empty($assign_leave_year)){
                $result['Status'] = 'FF';
                $result['Res'] = "Invalid Params Value Found";
                echo json_encode($result); die;
            }
            $assignemptype = $_GET['assignemptype'];
            //echo "$id";
            $leavesChart = Yii::$app->hr_utility->hr_get_leaves_chart();
            
            $result['Status'] = 'SS';
            $result['leave_count'] = "";
            $result['leave_count_enc'] = "";
            $result['year'] = "";
            $result['year_enc'] = "";
            $result['session_type'] = "";
            $result['session_type_enc'] = "";
            if(!empty($leavesChart)){
                
                foreach($leavesChart as $leave){
                    if($leave['leave_type'] == $id AND $leave['emp_type'] == $assignemptype AND $leave['year'] == $assign_leave_year AND $leave['session_type'] == $sessiontype){
//                        echo "<pre>";print_r($leave);die;
                        $result['leave_count'] = $leave['leave_count'];
                        $result['leave_count_enc'] = Yii::$app->utility->encryptString($leave['leave_count']);
                        $result['leave_for'] = $leave['leave_for'];
                        $result['leave_for_enc'] = Yii::$app->utility->encryptString($leave['leave_for']);
                        $result['year'] = $leave['year'];
                        $result['year_enc'] = Yii::$app->utility->encryptString($leave['year']);
                        $result['carry_fwd'] = $leave['can_carryfwd'];
                        $result['carry_fwd_enc'] = Yii::$app->utility->encryptString($leave['can_carryfwd']);
                        $result['can_encash'] = $leave['can_encashment'];
                        $result['can_encash_enc'] = Yii::$app->utility->encryptString($leave['can_encashment']);
                        
                        $result['session_type'] = $leave['session_type'];
                        $result['session_type_enc'] = Yii::$app->utility->encryptString($leave['session_type']);
                        $result['leave_chart_id'] = Yii::$app->utility->encryptString($leave['lc_id']);
                    }
                }
            }
            
            echo json_encode($result);
            die;
        }
    }
    
    public function actionUpdateleaves(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."admin/manageleaves/updateleaves?securekey=$menuid";
        if(isset($_GET['securecode']) AND !empty($_GET['securecode'])){
            $emp_code = Yii::$app->utility->decryptString($_GET['securecode']);
            if(empty($emp_code)){
                Yii::$app->getSession()->setFlash('danger', "Invalid Params Value Found."); 
                return $this->redirect($url);
            }
            $empInfo = Yii::$app->utility->get_employees($emp_code);
            if(empty($empInfo)){
                Yii::$app->getSession()->setFlash('danger', "Employee's No Record Found."); 
                return $this->redirect($url);
            }
            $leavesinfo = Yii::$app->utility->get_employee_leaves($emp_code);
            
            $this->layout = '@app/views/layouts/admin_layout.php';
            return $this->render('updateleaves', ['menuid'=>$menuid, 'leavesinfo'=>$leavesinfo, 'empInfo'=>$empInfo]);
        }
        Yii::$app->getSession()->setFlash('danger', "Invalid Params Found."); 
        return $this->redirect($url);
    }
    public function actionAssignleavetoemp(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."admin/manageleaves/viewassignedleaves?securekey=$menuid";
        
        //Save data
        if(isset($_POST['AssignLeave']) AND !empty($_POST['AssignLeave'])){
            $post = $_POST['AssignLeave'];
            $leave_type = Yii::$app->utility->decryptString($post['leave_type']);
            $session_year = Yii::$app->utility->decryptString($post['session_year']);
            $session_type = Yii::$app->utility->decryptString($post['session_type']);
            $leave_count = Yii::$app->utility->decryptString($post['leave_count']);
            $emp_code = Yii::$app->utility->decryptString($post['emp_code']);
            $lc_id = Yii::$app->utility->decryptString($post['lc_id']);
            if(empty($leave_type) OR empty($session_year) OR empty($session_type)){
                Yii::$app->getSession()->setFlash('danger', "Invalid Params Value Found."); 
                return $this->redirect($url);
            }
            $empcode = Yii::$app->utility->encryptString($emp_code);
            $url = Yii::$app->homeUrl."admin/manageleaves/assignleavetoemp?securekey=$menuid&securecode=$empcode";
            $leavesinfo = Yii::$app->utility->get_employee_leaves($emp_code);
            $checkExits = false;
            
            foreach($leavesinfo  as $l){
                if($l['session_year'] == $session_year AND $l['session_type'] == $session_type AND $l['leave_type'] == $leave_type){
                    echo "Yes <br>";
                    $checkExits = $l['desc'];
                }
            }
            if(!empty($checkExits)){
                Yii::$app->getSession()->setFlash('danger', "$checkExits Already Assigned to the Employee."); 
                return $this->redirect($url);
            }
            $result = Yii::$app->hr_utility->hr_add_update_leaves_detail_chart(NULL, $session_year, $session_type, $leave_type, $leave_count, "0", $leave_count, "Leave Assigned By Admin to particular emp", $emp_code);
            
            /*
             * Logs
             */
            $logs['emp_code']=$emp_code;
            $logs['session_year']=$session_year;
            $logs['session_type']=$session_type;
            $logs['leave_type']=$leave_type;
            $logs['total_leave']=$leave_count;
            $logs['remarks']="Leave Assigned By Admin to particular emp";
            $jsonlogs = json_encode($logs);
            if($result == '1'){
                
                Yii::$app->utility->activities_logs("Master Data", NULL, $emp_code, $jsonlogs, "Leave Assigned By Admin to particular emp.");
                
                $reslt = Yii::$app->hr_utility->hr_add_leave_card_details($leave_type, "Accrual", date('Y-m-d'), $leave_count,"0", $leave_count, "Leave Assigned By Admin to particular emp", Yii::$app->user->identity->e_id, $emp_code, "Approved");
                
                /*
                 * Logs
                 */
                $logs['emp_code']=$emp_code;
                $logs['entry_type']="Accrual";
                $logs['from']=date('Y-m-d');
                $logs['total_leave']=$leave_count;
                $logs['remarks']="Leave Assigned By Admin to particular emp";
                $jsonlogs = json_encode($logs);
                if($reslt == '1'){
                    Yii::$app->utility->activities_logs("Master Data", NULL, $emp_code, $jsonlogs, "Leave Assigned to particular emp and entry in Leave Card Detail.");
                    Yii::$app->getSession()->setFlash('success', "Leave Assigned Successfully. "); 
                    return $this->redirect($url);
                }else{
                    Yii::$app->utility->activities_logs("Master Data", NULL, $emp_code, $jsonlogs, "Leave Assigned to particular emp. But Card Details Not updated.");
                    
                    Yii::$app->getSession()->setFlash('danger', "Leave Assigned Successfully. But Card Details Not updated"); 
                    return $this->redirect($url);
                }
            }else{
                Yii::$app->getSession()->setFlash('danger', "Leave Not Assigned. Contact Admin."); 
                return $this->redirect($url);
            }
            
        }
        if(isset($_GET['securecode']) AND !empty($_GET['securecode'])){
            $emp_code = Yii::$app->utility->decryptString($_GET['securecode']);
            if(empty($emp_code)){
                Yii::$app->getSession()->setFlash('danger', "Invalid Params Value Found."); 
                return $this->redirect($url);
            }
            $empInfo = Yii::$app->utility->get_employees($emp_code);
            if(empty($empInfo)){
                Yii::$app->getSession()->setFlash('danger', "Employee's No Record Found."); 
                return $this->redirect($url);
            }
            
            $leavesChart = Yii::$app->hr_utility->hr_get_leaves_chart(NULL);
            $leaves = "";
            if(!empty($leavesChart)){
                $gender = $empInfo['gender'];
                $emp_type = $empInfo['employmenttype'];
                $i=0;
                $curMonth = date('m');
                $sessType= "FHY";
                if($curMonth > 6){
                    $sessType= "SHY";
                }
                
                foreach($leavesChart as $l){
                    if($l['year'] == date('Y')){
                        if($l['emp_type'] == $empInfo['employmenttype']){
                            if($l['leave_for'] == 'A' OR $l['leave_for'] == $empInfo['gender']){
                                if($l['session_type'] == 'Y' OR $l['session_type'] == $sessType){
                                    $leaves[$i]['session_type'] = $l['session_type'];
                                    $leaves[$i]['leave_type'] = $l['leave_type'];
                                    $leaves[$i]['lc_id'] = $l['lc_id'];
                                    $leaves[$i]['desc'] = $l['desc']." (".$l['label'].")";
                                    $i++;
                                }
                            }
                        }
                    }
                }
            }
            $empcode = Yii::$app->utility->encryptString($emp_code);
            $url = Yii::$app->homeUrl."admin/manageleaves/updateleaves?securekey=$menuid&securecode=$empcode";
            if(empty($leaves)){
                Yii::$app->getSession()->setFlash('danger', "No Leave Chart Found For selected employee."); 
                return $this->redirect($url);
            }
            $this->layout = '@app/views/layouts/admin_layout.php';
            return $this->render('assignleavetoemployee', ['menuid'=>$menuid, 'empInfo'=>$empInfo, 'leaves'=>$leaves]);
        }
        Yii::$app->getSession()->setFlash('danger', "Invalid Params Found."); 
        return $this->redirect($url);
    }
    
    public function actionAssignleavetoemployee(){
        if(isset($_GET['lc_id']) AND !empty($_GET['lc_id']) AND isset($_GET['leaveid']) AND !empty($_GET['leaveid'])){
            $lc_id = Yii::$app->utility->decryptString($_GET['lc_id']);
            $leaveid = Yii::$app->utility->decryptString($_GET['leaveid']);
            if(empty($lc_id) OR empty($leaveid)){
                $result['Status']='FF';
                $result['Res']='Invalid Params Value Found';
                echo json_encode($result); die;
            }
            $leavesChart = Yii::$app->hr_utility->hr_get_leaves_chart($lc_id);
            if(empty($leavesChart)){
                $result['Status']='FF';
                $result['Res']='No Record Found';
                echo json_encode($result); die;
            }
            $total = $leavesChart['leave_count'];
            $curmonth = date('m');
            
            if($curmonth > 6){
                if($leavesChart['session_type'] == 'Y'){
                    $total = $leavesChart['leave_count'] / 2;
                }
            }
            
            $result['Status']='SS';
            $result['session_year']= $leavesChart['year'];
            $result['session_type']= $leavesChart['session_type'];
            $result['leave_count']= $total;
            $result['session_year_enc']= Yii::$app->utility->encryptString($leavesChart['year']);
            $result['session_type_enc']= Yii::$app->utility->encryptString($leavesChart['session_type']);
            $result['leave_count_enc']= Yii::$app->utility->encryptString($total);
            $result['lc_id']= Yii::$app->utility->encryptString($lc_id);
            echo json_encode($result); die;
        }else{
            $result['Status']='FF';
            $result['Res']='Invalid Params Found';
            echo json_encode($result); die;
        }
        echo "<pre>";print_r($_GET['lc_id']);
    }
}
