<?php

namespace app\modules\admin\controllers;
use app\models\Department; 
use yii;
class Annual_reimbursementController extends \yii\web\Controller
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
        $details = Yii::$app->finance->get_ann_reim_master();
        return $this->render('index', ['menuid'=>$menuid, 'details'=>$details]);
    }
    public function actionReimtypelist(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url= Yii::$app->homeUrl."admin/annual_reimbursement/reimtypelist?securekey=$menuid";
        if(isset($_POST) AND !empty($_POST)){
            $type_name = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $_POST['type_name']));
            $result = Yii::$app->finance->add_update_reim_type(NULL, $type_name);
            /*
             * Logs
             */
            $logs['type_name'] =$type_name;
            $jsonlogs = json_encode($logs);
            if($result == '1'){
                $msg = "Annual Reimburesement Type Added Successfully.";
                Yii::$app->getSession()->setFlash('success', $msg); 
            }else{
                $msg = "Annual Reimburesement Type Not Added. Contact Admin";
                Yii::$app->getSession()->setFlash('danger', $msg); 
            }
            Yii::$app->utility->activities_logs("Master Data", NULL, NULL, $jsonlogs, $msg);
            return $this->redirect($url);
        }
        $records = Yii::$app->finance->get_reim_type();
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('reimtypelist', ['menuid'=>$menuid, 'records'=>$records]);
    }
    public function actionDelreimtype(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url= Yii::$app->homeUrl."admin/annual_reimbursement/reimtypelist?securekey=$menuid";
        if(isset($_GET['key1']) AND !empty($_GET['key1'])){
            $reim_type_id = Yii::$app->utility->decryptString($_GET['key1']);
            if(empty($reim_type_id)){
                Yii::$app->getSession()->setFlash('danger', "Invalid params value found.");
                return $this->redirect($url);
            }
            $result = Yii::$app->finance->add_update_reim_type($reim_type_id, NULL);
            /*
             * Logs
             */
            $logs['reim_type_id'] =$reim_type_id;
            $jsonlogs = json_encode($logs);
            if($result == '2'){
                $msg = "Annual Reimburesement Type Deleted Successfully.";
                Yii::$app->getSession()->setFlash('success', $msg); 
            }else{
                $msg = "Annual Reimburesement Type Not Deleted. Contact Admin";
                Yii::$app->getSession()->setFlash('danger', $msg); 
            }
            Yii::$app->utility->activities_logs("Master Data", NULL, NULL, $jsonlogs, $msg);
            return $this->redirect($url);
        }else{
            Yii::$app->getSession()->setFlash('danger', "Invalid param found.");
            return $this->redirect($url);
        }
    }
    
    public function actionAddentitlement(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url= Yii::$app->homeUrl."admin/annual_reimbursement/addentitlement?securekey=$menuid";
        if(isset($_POST['Reim']) AND !empty($_POST['Reim'])){
            $post = $_POST['Reim'];
            $financial_yr = Yii::$app->utility->decryptString($post['financial_yr']);
            $reim_type_id = Yii::$app->utility->decryptString($post['reim_type_id']);
            $designation_id = Yii::$app->utility->decryptString($post['designation_id']);
            $emp_type = Yii::$app->utility->decryptString($post['emp_type']);
            if(empty($financial_yr) OR empty($reim_type_id) OR empty($designation_id) OR empty($emp_type)){
                Yii::$app->getSession()->setFlash('danger', "Invalid params value found");
                return $this->redirect($url);
            }
            $sanc_amt = trim(preg_replace('/[^0-9.]/', '', $post['sanc_amt']));
            $result = Yii::$app->finance->add_update_reim_master(NULL, $reim_type_id, $designation_id, $emp_type, $financial_yr, $sanc_amt);
            /*
             * Logs
             */
            $logs['reim_type_id']=$reim_type_id;
            $logs['designation_id']=$designation_id;
            $logs['emp_type']=$emp_type;
            $logs['financial_yr']=$financial_yr;
            $logs['sanc_amt']=$sanc_amt;
            $jsonlogs=  json_encode($logs);
            if($result == '3'){
                $msg="Annual Reimbursement Master Details Already Exits.";
                Yii::$app->getSession()->setFlash('danger', $msg);
            }elseif($result == '1'){
                $msg="Annual Reimbursement Master Details Added Successfully.";
                Yii::$app->getSession()->setFlash('success', $msg);
            }else{
                $msg="Annual Reimbursement Master Details Not Added. Contact Admin.";
                Yii::$app->getSession()->setFlash('danger', $msg);
            }
            Yii::$app->utility->activities_logs("Master Data", NULL, NULL, $jsonlogs, $msg);
            return $this->redirect($url);
        }
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('addentitlement', ['menuid'=>$menuid]);
    }
}