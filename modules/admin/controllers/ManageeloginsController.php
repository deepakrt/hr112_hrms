<?php
namespace app\modules\admin\controllers;
use yii;
class ManageeloginsController extends \yii\web\Controller
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
        return $this->render('index');
    }
    
    public function actionEdit(){
        
        if(isset($_GET['securekey']) AND !empty($_GET['securekey']) AND isset($_GET['map_key']) AND !empty($_GET['map_key']) AND isset($_GET['status']) AND !empty($_GET['status'])){
            $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
            $map_id = Yii::$app->utility->decryptString($_GET['map_key']);
            $status = Yii::$app->utility->decryptString($_GET['status']);
            
            if(empty($menuid) OR empty($map_id) OR empty($status)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid URL'); 
                return $this->redirect(Yii::$app->homeUrl);
            }
            
            $result = Yii::$app->utility->update_rbac_status($map_id,$status);
            $menuid = Yii::$app->utility->encryptString($menuid);
            if($status == 'Y'){
                $msg = "Login activated successfully.";
            }else{
                $msg = "Login deactivated successfully.";
            }
            if($result == '1'){
                Yii::$app->getSession()->setFlash('success', $msg);
                return $this->redirect(Yii::$app->homeUrl."admin/manageelogins?securekey=$menuid");
            }else{
                Yii::$app->getSession()->setFlash('danger', 'Invalid params found.');
                return $this->redirect(Yii::$app->homeUrl."admin/manageelogins?securekey=$menuid");
            }
        }
    }
}
