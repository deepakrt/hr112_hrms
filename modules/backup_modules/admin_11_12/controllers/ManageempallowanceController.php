<?php

namespace app\modules\admin\controllers;
use app\models\Department; 
use yii;
class ManageempallowanceController extends \yii\web\Controller
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
        $allowances = Yii::$app->utility->get_emp_allowance(NULL, NULL, NULL);
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('index', ['menuid'=>$menuid, 'allowances'=>$allowances]);
    }
    public function actionAdd()
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."admin/manageempallowance/add?securekey=$menuid";
        if(isset($_POST['Allowance']) AND !empty($_POST['Allowance'])){
            $post = $_POST['Allowance'];
            $financial_yr = Yii::$app->utility->decryptString($post['financial_yr']);
            $emp_type = Yii::$app->utility->decryptString($post['emp_type']);
            $allowance_type = Yii::$app->utility->decryptString($post['allowance_type']);
            $designation_id = Yii::$app->utility->decryptString($post['designation_id']);
            $sanc_type = Yii::$app->utility->decryptString($post['sanc_type']);
            
            if(empty($financial_yr) OR empty($emp_type) OR empty($allowance_type) OR empty($designation_id)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid Params Value Found.'); 
                return $this->redirect($url);
            }
            
            $amount = trim(preg_replace('/[^0-9]/', '', $post['amount']));
            $result = Yii::$app->utility->add_update_emp_allowance(NULL, $designation_id, $emp_type, $financial_yr, $allowance_type, $amount, $sanc_type);
            
            /*
             * Logs
             */
            $logs['designation_id']=$designation_id;
            $logs['emp_type']=$emp_type;
            $logs['financial_yr']=$financial_yr;
            $logs['allowance_type']=$allowance_type;
            $logs['amount']=$amount;
            $logs['sanc_type']=$sanc_type;
            $jsonlogs = json_encode($logs);
            $msg = "Employee Allowance Added Successfully for $financial_yr";
            
            if($result == '3'){
                Yii::$app->utility->activities_logs('Master Data', NULL, NULL, $jsonlogs, 'Employee Allowance for Financial year, Designation, Employee Type already exits.');
                Yii::$app->getSession()->setFlash('danger', 'Employee Allowance for Financial year, Designation, Employee Type already exits.'); 
                return $this->redirect($url);
            }elseif($result == '1'){
                Yii::$app->utility->activities_logs('Master Data', NULL, NULL, $jsonlogs, $msg);
                Yii::$app->getSession()->setFlash('success', $msg); 
                return $this->redirect($url);
            }else{
                Yii::$app->utility->activities_logs('Master Data', NULL, NULL, $jsonlogs, "Employee Allowance Has Not Added.");
                Yii::$app->getSession()->setFlash('success', "Employee Allowance Has Not Added."); 
                return $this->redirect($url);
            }
        }
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('add', ['menuid'=>$menuid]);
    }
    
    public function actionDeleteallowance(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."admin/manageempallowance?securekey=$menuid";
        if(isset($_GET['id']) AND !empty($_GET['id']) AND isset($_GET['desg_id']) AND !empty($_GET['desg_id'])){
            $id = Yii::$app->utility->decryptString($_GET['id']);
            $desg_id = Yii::$app->utility->decryptString($_GET['desg_id']);
        }
        if(empty($id) OR empty($desg_id)){
            Yii::$app->getSession()->setFlash('danger', 'Invalid Params Value Found.'); 
            return $this->redirect($url);
        }
        
        $result = Yii::$app->utility->add_update_emp_allowance($id, $desg_id, NULL, NULL, NULL, NULL, NULL);
        
        /*
         * Logs
         */
        $logs['id']=$id;
        $logs['desg_id']=$desg_id;
        $jsonlogs=  json_encode($logs);
        if($result == '2'){
            $msg = "Employee Allowance Deleted Successfully.";
            Yii::$app->utility->activities_logs('Master Data', NULL, NULL, $jsonlogs, $msg);
            Yii::$app->getSession()->setFlash('success', $msg); 
            return $this->redirect($url);
        }else{
            Yii::$app->utility->activities_logs('Master Data', NULL, NULL, $jsonlogs, "Employee Allowance Has Not Deleted. Contact Admin.");
            Yii::$app->getSession()->setFlash('success', "Employee Allowance Not Deleted. Contact Admin."); 
            return $this->redirect($url);
        }
    }
}
