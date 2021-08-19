<?php

namespace app\modules\finance\controllers;
use Yii;
class CanteenallowancesController extends \yii\web\Controller
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
        $url = Yii::$app->homeUrl."finance/canteenallowances?securekey=$menuid";
        if(isset($_POST['GS']) AND !empty($_POST['GS'])){
            $post = $_POST['GS'];
            
            $emp_type = Yii::$app->utility->decryptString($post['emp_type']);
            $month = Yii::$app->utility->decryptString($post['month']);
            $year = Yii::$app->utility->decryptString($post['year']);
            $da_en_workdays = Yii::$app->utility->decryptString($post['da_en_workdays']);

            if(empty($emp_type) OR empty($month) OR empty($year) OR empty($da_en_workdays)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid params value.');
                return $this->redirect($url);
            }
            $da_workdays = trim(preg_replace('/[^0-9-]/', '', $post['da_workdays']));
            $holidays = trim(preg_replace('/[^0-9-]/', '', $post['holidays']));
            if($da_en_workdays != $da_workdays){
                Yii::$app->getSession()->setFlash('danger', 'Invalid Working Days.');
                return $this->redirect($url);
            }
            $emp = Yii::$app->utility->get_employees();
            $Super_Admin_Emp_Code = Super_Admin_Emp_Code;
           
            $newempList=array();
            $i=0;
            if($emp_type == 'A'){
                foreach($emp as $e){
                    if($e['employee_code'] != $Super_Admin_Emp_Code){
                        $newempList[$i]=$e['employee_code'];
                        $i++;
                    }
                }
            }else{
                foreach($emp as $e){
                    if($e['employment_type'] == $emp_type AND $e['employee_code'] != $Super_Admin_Emp_Code){
                        $newempList[$i]=$e['employee_code'];
                        $i++;
                    }
                }
            }
            if(empty($newempList)){
                Yii::$app->getSession()->setFlash('danger', 'No Employees Found.');
                return $this->redirect($url);
            }
            $da_en_workdays1 = $da_en_workdays-$holidays;
            $Canteen_Allowance_Per_day = Canteen_Allowance_Per_day;
            $canteenAllowance = $da_en_workdays * $Canteen_Allowance_Per_day;
            $chkAlreadyPaid=0;
            foreach($newempList as $empCode){
                $result = Yii::$app->finance->fn_update_canteen_allowances($empCode, $month, $year, $canteenAllowance, $da_en_workdays1);
                if($result == 2){
                    $chkAlreadyPaid = $chkAlreadyPaid+1;
                }
            }
            
            $totalEmp = count($newempList);
            
            if($totalEmp == $chkAlreadyPaid ){
                $msg = "Salary for the month $month-$year has already paid";
                Yii::$app->getSession()->setFlash('danger', $msg);
            }else{
                $msg = "Canteen Allowances Updated Successfully.";
                Yii::$app->getSession()->setFlash('success', $msg);
            }
            /*
             * Logs
             */
            $logs['emp_type']=$emp_type;
            $logs['month']=$month;
            $logs['year']=$year;
            $logs['total_workdays_in_the_month']=$da_en_workdays;
            $logs['holidays']=$holidays;
            $logs['working_days_after_holidays']=$da_en_workdays1;
            $logs['canteenAllowance']=$canteenAllowance;
            $logs['emp_list']=$newempList;
            $jsonLogs = json_encode($logs);
            
            Yii::$app->utility->activities_logs("Finance", NULL, NULL, $jsonLogs, $msg);
            
            return $this->redirect($url);
        }else{
            if(isset($_POST) AND !empty($_POST)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid params value.');
            }
        }
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('index', ['menuid'=>$menuid]);
    }
    
    public function actionGetdaysofmonth(){
        if(isset($_POST['GS_emp_type']) AND !empty($_POST['GS_emp_type']) AND isset($_POST['GS_month']) AND !empty($_POST['GS_month']) AND isset($_POST['GS_yr']) AND !empty($_POST['GS_yr'])){
            $GS_emp_type = Yii::$app->utility->decryptString($_POST['GS_emp_type']);
            $GS_month = Yii::$app->utility->decryptString($_POST['GS_month']);
            $GS_yr = Yii::$app->utility->decryptString($_POST['GS_yr']);
            
            if($GS_emp_type == 'R'){                
            }elseif($GS_emp_type == 'C'){
            }elseif($GS_emp_type == 'A'){
            }else{
                $result['Status']='FF';
                $result['Res']='Invalid Employee Type';
                echo json_encode($result);
                die;
            }
            if($GS_month > 12 OR $GS_month < 1){
                $result['Status']='FF';
                $result['Res']='Invalid Month Selected';
                echo json_encode($result);
                die;
            }
            
            $totaldays=0;
            for($d=1; $d<=31; $d++){
                $time=mktime(12, 0, 0, $GS_month, $d, $GS_yr);          
                if (date('m', $time)==$GS_month){
                    if(date('D', $time) == 'Sun'){
                    }elseif(date('D', $time) == 'Sat'){
                    }else{ $totaldays = $totaldays +1;}
                }       

            }
            if($totaldays == '0'){
                $result['Status']='FF';
                $result['Res']='Days not calculated. Contact Admin.';
                echo json_encode($result);
                die;
            }
            
            /*
             * Check Salary Status
             */
            $check = Yii::$app->finance->fn_check_salary_status($GS_month, $GS_yr, "Paid");
            if(!empty($check)){
                $result['Status']='FF';
                $result['Res']="Salary has been paid for the period $GS_month-$GS_yr.";
                echo json_encode($result);
                die;
            }
            $check = "";
            $check = Yii::$app->finance->fn_check_salary_status($GS_month, $GS_yr, "Projected");
            if(empty($check)){
                $result['Status']='FF';
                $result['Res']="No Salary Details found for the period $GS_month-$GS_yr.";
                echo json_encode($result);
                die;
            }
            
            $entotaldays = Yii::$app->utility->encryptString($totaldays);
            $Canteen_Allowance_Per_day = Canteen_Allowance_Per_day;
            $html = "";
            $html = "<hr>
                <div class='row'>
                    <div class='col-sm-12'>
                        <h6><b><u>Canteen Allowance</u></b></h6>
                    </div>
                    <div class='col-sm-3'>
                        <label>Total Working Days</label>
                        <input type='text' class='form-control form-control-sm' readonly='' name='GS[da_workdays]' value='$totaldays' id='GS_workdays' placeholder='Working Days' />
                        <input type='hidden' class='form-control form-control-sm' name='GS[da_en_workdays]' value='$entotaldays' id='GS_workdays_en' required='' />
                    </div>
                    <div class='col-sm-3'>
                        <label>Canteen Allowance Per Day</label>
                        <input type='number' class='form-control form-control-sm' required='' value='$Canteen_Allowance_Per_day' readonly />
                    </div>
                    <div class='col-sm-5'>
                        <label>Enter Total Holidays in the selected month</label>
                        <input type='number' class='form-control form-control-sm' required='' name='GS[holidays]' id='GS_holidays' placeholder='Total Holidays' />
                    </div>

                    <div class='col-sm-12 text-center formbtn' style='padding-top:30px;'>
                        <button type='submit' class='btn btn-success btn-sm' id='update_canteen_allowance'>Update Canteen Allowance</button>
                        <a href='' class='btn btn-danger btn-sm'>Cancel</a>
                    </div>
                </div>";
            $result['Status']='SS';
            $result['Res']=$html;
            echo json_encode($result);
            die;
        }else{
            $result['Status']='FF';
            $result['Res']='Invalid Params';
            echo json_encode($result);
            die;
        }
    }
    
    public function actionUpdatecanteenallowance(){
//        if(isset($_POST['formdata']) AND !empty($_POST['formdata'])){
//            parse_str($_POST['formdata'],$post);
            if(isset($_POST['GS']) AND !empty($_POST['GS'])){
                $post = $post['GS'];
                echo "<pre>";print_r($post);die;
                $emp_type = Yii::$app->utility->decryptString($post['emp_type']);
                $month = Yii::$app->utility->decryptString($post['month']);
                $year = Yii::$app->utility->decryptString($post['year']);
                $da_en_workdays = Yii::$app->utility->decryptString($post['da_en_workdays']);
                
                if(empty($emp_type) OR empty($month) OR empty($year) OR empty($da_en_workdays)){
                    $result['Status']='FF';
                    $result['Res']='Invalid params value';
                    echo json_encode($result);
                    die;
                }
                
                if($da_en_workdays != $da_workdays){
                    $result['Status']='FF';
                    $result['Res']='Invalid Working Days';
                    echo json_encode($result);
                    die;
                }
                
                $da_workdays = trim(preg_replace('/[^0-9-]/', '', $post['da_workdays']));
                $holidays = trim(preg_replace('/[^0-9-]/', '', $post['holidays']));
                
            }else{
                $result['Status']='FF';
                $result['Res']='Invalid Params';
                echo json_encode($result);
                die;
            }
            
//        }else{
//            $result['Status']='FF';
//            $result['Res']='Invalid Params';
//            echo json_encode($result);
//            die;
//        }
        
    }
}