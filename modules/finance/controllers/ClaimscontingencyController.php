<?php

namespace app\modules\finance\controllers;
use Yii;
class ClaimscontingencyController extends \yii\web\Controller
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
        $claimApps = Yii::$app->finance->fn_get_contingency(NULL, NULL, NULL,"Pending,In-Process");
        return $this->render('index', ['menuid'=>$menuid, 'claimApps'=>$claimApps]);
    }
    
    public function actionView(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."finance/claimscontingency?securekey=$menuid";
        if(isset($_GET['id']) AND !empty($_GET['id']) AND isset($_GET['code']) AND !empty($_GET['code'])){
            $get = $_GET;
            $id = Yii::$app->utility->decryptString($_GET['id']);
            $empcode = Yii::$app->utility->decryptString($_GET['code']);
            if(empty($id) OR empty($empcode)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Url params'); 
                return $this->redirect($url);
            }
            $record = Yii::$app->finance->fn_get_contingency(NULL, $id, $empcode, "Draft,Revoked");
            
            if(empty($record)){
                Yii::$app->getSession()->setFlash('danger', 'No record found.'); 
                return $this->redirect($url);
            }
            $this->layout = '@app/views/layouts/admin_layout.php';
            return $this->render('view', ['menuid'=>$menuid, 'record'=>$record]);
        }else{
            Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Url params'); 
            return $this->redirect($url);
        }
    }
    
    public function actionUpdateclaim(){ 
        
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."finance/claimscontingency?securekey=$menuid";
        if(isset($_POST['Contingency']) AND !empty($_POST['Contingency'])){
            $post = $_POST['Contingency'];
            $id = Yii::$app->utility->decryptString($post['id']);
            $empcode = Yii::$app->utility->decryptString($post['employee_code']);
            if(empty($id) OR empty($empcode)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Url params'); 
                return $this->redirect($url);
            }
            $sanctionamount = trim(preg_replace('/[^0-9]/', '', $post['sanctioned_amt']));
            if(empty($sanctionamount)){
                Yii::$app->getSession()->setFlash('danger', 'Enter Sanction Amount'); 
                return $this->redirect($url);
            }
            $result = Yii::$app->finance->fn_add_update_contingency($id, $empcode, NULL, NULL, NULL, NULL, NULL, $sanctionamount, $post['submit_type'], Yii::$app->user->identity->e_id, NULL);
            
            /*
             * Logs
             */
            $logs['role_id']= Yii::$app->user->identity->role;
            $logs['id']= $id;
            $logs['empcode']= $empcode;
            $logs['sanctionamount']= $sanctionamount;
            $logs['status']= $post['submit_type'];
            $jsonlogs = json_encode($logs);
            
            if($result == '2'){
                Yii::$app->utility->activities_logs("Claim", NULL, $empcode, $jsonlogs, "Contingency Claim Updated Successfully.");
                
                Yii::$app->getSession()->setFlash('success', 'Contingency Claim Updated Successfully'); 
                return $this->redirect($url);
            }else{
                Yii::$app->utility->activities_logs("Claim", NULL, $empcode, $jsonlogs, "Contingency Claim Not Updated Successfully. Contact Admin.");
                
                Yii::$app->getSession()->setFlash('danger', 'Contingency Claim Not Updated Successfully. Contact Admin.'); 
                return $this->redirect($url);
            }
        }else{
            Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Url params'); 
            return $this->redirect($url);
        }
        
    }
    
    public function actionAllcontingency(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $this->layout = '@app/views/layouts/admin_layout.php';
        $info = Yii::$app->finance->fn_get_contingency(NULL, NULL, NULL,"Sanctioned,Rejected");
        return $this->render('allcontingency', ['menuid'=>$menuid, 'info'=>$info]);
    }
}
