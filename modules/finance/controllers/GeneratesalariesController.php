<?php

namespace app\modules\finance\controllers;
use Yii;
class GeneratesalariesController extends \yii\web\Controller
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
        $url = Yii::$app->homeUrl."finance/generatesalaries?securekey=$menuid";
        if(isset($_POST['Salary']) AND !empty($_POST['Salary'])){
            $post = $_POST['Salary'];
            $emp_type = Yii::$app->utility->decryptString($post['emp_type']);
            $month = Yii::$app->utility->decryptString($post['month']);
            $year = Yii::$app->utility->decryptString($post['year']);
            
            if(empty($emp_type) OR empty($month) OR empty($year)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid params value.');
                return $this->redirect($url);
            }
            /*
             * Check Salary Status
             */
            $check = Yii::$app->finance->fn_check_salary_status($month, $year, "Paid");
            $checkProjected = Yii::$app->finance->fn_check_salary_status($month, $year, "Projected");
            
            if(!empty($check)){
               Yii::$app->getSession()->setFlash('danger', "Salary Already Generated for the month $month-$year.");
                return $this->redirect($url);
            }elseif(empty($checkProjected)){
               Yii::$app->getSession()->setFlash('danger', "Salary details  not found for the month $month-$year.");
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
            
            $financial_year = Yii::$app->finance->financialYrWithMonthYear($month, $year);
            if(empty($financial_year)){
                Yii::$app->getSession()->setFlash('danger', 'Financial Year Error.');
                return $this->redirect($url);
            }
            
            foreach($newempList as $empCode){
                $result = Yii::$app->finance->fn_update_salary("Generate", $empCode, $month, $year);
                
                if($result != '1'){
                    $msg = "";
                    if($result == '3'){
                        $msg = "Salary Details not Found for the month $month-$year";
                    }elseif($result == '2'){
                        $msg = "Canteen Allowances not updated for the month $month-$year";
                    }elseif($result == '4'){
                        $msg = "Salary Already Generated for the month $month-$year";
                    }elseif($result == '5'){
                        $msg = "Invalid Action Type";
                    }
                    
                    /*
                    * Logs
                    */
                    $logs['action_type']="Generate";
                    $logs['emp_type']=$emp_type;
                    $logs['month']=$month;
                    $logs['year']=$year;
                    $logs['empCode']=$empCode;
                    $jsonLogs = json_encode($logs);
                    
                    Yii::$app->utility->activities_logs("Finance", NULL, NULL, $jsonLogs, $msg);
                    Yii::$app->getSession()->setFlash('danger', $msg);
                    return $this->redirect($url);
                }
            }
            /*
             * Logs
             */
            $logs="";
            $logs['action_type']="Generate";
            $logs['emp_type']=$emp_type;
            $logs['month']=$month;
            $logs['year']=$year;
            $logs['emp_list']=$newempList;
            $jsonLogs = json_encode($logs);
            
            $msg = "Salary for the month $month-$year Generated Successfully.";
            Yii::$app->utility->activities_logs("Finance", NULL, NULL, $jsonLogs, $msg);
            /*
             * Generate PF 
             */
            $pfresult = Yii::$app->finance->pf_generate_pf($financial_year, $month, $year, "Projected");
            if($pfresult == '1'){
                $pfmsg = "PF for the month $month-$year generated successfully as Projected.";
            }else{
                $pfmsg = "PF for the month $month-$year not generated as Projected.";
            }
            /*
             * Logs
             */
            $logs="";
            $logs['financial_year']=$financial_year;
            $logs['month']=$month;
            $logs['year']=$year;
            $logs['status']="Projected";
            $logs['employee'] = "Procedure call for all employees. pf_generate_pf";
            $jsonLogs = json_encode($logs);
            Yii::$app->utility->activities_logs("PF", NULL, NULL, $jsonLogs, $pfmsg);
            
            Yii::$app->getSession()->setFlash('success', $msg);
            return $this->redirect($url);
        }
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('index', ['menuid'=>$menuid]);
    }
    
}