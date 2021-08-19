<?php

namespace app\modules\finance\controllers;
use Yii;
class AnnualreimbursementController extends \yii\web\Controller
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
        $request =  Yii::$app->finance->fn_get_ann_reim_claim(NULL, NULL, NULL, "Submitted", NULL);
        return $this->render('index', ['menuid'=>$menuid, 'request'=>$request]);
    }
    public function actionView()
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."finance/annualreimbursement/index?securekey=$menuid";
        
        if(isset($_POST['Reim']) AND !empty($_POST['Reim'])){
            $post = $_POST['Reim'];
            $arc_id = Yii::$app->utility->decryptString($post['arc_id']);
            $ec = Yii::$app->utility->decryptString($post['ec']);
            $ann_reim_id = Yii::$app->utility->decryptString($post['ann_reim_id']);
            $fy = Yii::$app->utility->decryptString($post['fy']);
            $status = Yii::$app->utility->decryptString($post['status']);
            $entitlement = Yii::$app->utility->decryptString($post['key']);
            
            if(empty($arc_id) OR empty($ec) OR empty($ann_reim_id) OR empty($fy) OR empty($status) OR empty($entitlement)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid params value found.'); 
                return $this->redirect($url);
            }
            $record = Yii::$app->finance->fn_get_ann_reim_claim($arc_id, $ec, $fy, NULL, $ann_reim_id);
            
            if($record['status'] != 'Submitted'){
                Yii::$app->getSession()->setFlash('danger', 'Action taken on Annual Reimbursement Aplication.'); 
                return $this->redirect($url);
            }
            $sanc_amt = trim(preg_replace('/[^0-9.-]/', '', $post['sanc_amt']));
            if(empty($sanc_amt)){
                Yii::$app->getSession()->setFlash('danger', 'Amount Should be in numbers only.'); 
                return $this->redirect($url);
            }
            if($sanc_amt > $entitlement){
                Yii::$app->getSession()->setFlash('danger', "Sanction amount cannot be greater then entitlemnent i.e. Rs. $entitlement/-"); 
                return $this->redirect($url);
            }
//            echo "arc_id $arc_id <br>";
//            die;
            $sanc_remarks = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['sanc_remarks']));
            $result = Yii::$app->finance->fn_add_update_ann_reim_claim($arc_id, $ec, $ann_reim_id, $fy, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, $sanc_amt, Yii::$app->user->identity->e_id, $sanc_remarks, $status);
            
            /*
             * Logs
             */
            $logs['arc_id']=$arc_id;
            $logs['employee_code']=$ec;
            $logs['ann_reim_id']=$ann_reim_id;
            $logs['financial_year']=$fy;
            $logs['entitlement']=$entitlement;
            $logs['sanc_by']=Yii::$app->user->identity->e_id;
            $logs['sanc_amt']=$sanc_amt;
            $logs['sanc_remarks']=$sanc_remarks;
            $logs['status']=$status;
            $jsonlogs = json_encode($logs);
            if($result == '2'){
                $msg = "Annual Reimbursement $status Successfully.";
                Yii::$app->getSession()->setFlash('success', $msg); 
            }else{
                $msg = "Annual Reimbursement Not Updated. Contact Admin.";
                Yii::$app->getSession()->setFlash('danger', $msg); 
            }
            Yii::$app->utility->activities_logs("Reimbursement", NULL, $ec, $jsonlogs, $msg);
            return $this->redirect($url);
        }
        
        if(isset($_GET['key']) AND !empty($_GET['key']) AND isset($_GET['key1']) AND !empty($_GET['key1']) AND isset($_GET['key2']) AND !empty($_GET['key2']) AND isset($_GET['key3']) AND !empty($_GET['key3'])){
            $arc_id = Yii::$app->utility->decryptString($_GET['key']);
            $ec = Yii::$app->utility->decryptString($_GET['key1']);
            $ann_reim_id = Yii::$app->utility->decryptString($_GET['key2']);
            $fy = Yii::$app->utility->decryptString($_GET['key3']);
            
            if(empty($arc_id) OR empty($ec) OR empty($ann_reim_id) OR empty($fy)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid params value found.'); 
                return $this->redirect($url);
            }
            $record = Yii::$app->finance->fn_get_ann_reim_claim($arc_id, $ec, $fy, NULL, $ann_reim_id);
//            echo "<pre>";print_r($record); die;
            if($record['status'] != 'Submitted'){
                Yii::$app->getSession()->setFlash('danger', 'Action taken on Annual Reimbursement Aplication.'); 
                return $this->redirect($url);
            }
            $this->layout = '@app/views/layouts/admin_layout.php';
            return $this->render('view', ['menuid'=>$menuid, 'record'=>$record]);
        }else{
            Yii::$app->getSession()->setFlash('danger', 'Invalid params found.'); 
            return $this->redirect($url);
        }
    }
}