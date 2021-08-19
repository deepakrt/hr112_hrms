<?php
namespace app\modules\admin\controllers;
use yii;
class ManagerolesController extends \yii\web\Controller
{
    public function beforeAction($action){
        if (!\Yii::$app->user->isGuest){
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
        
        $url = Yii::$app->homeUrl."admin/manageroles?securekey=$menuid";
       
        if(isset($_POST['role_id']) AND isset($_POST['role_name']) AND !empty($_POST['role_name']) AND isset($_POST['desc']) AND !empty($_POST['desc']) AND isset($_POST['is_active']) AND !empty($_POST['is_active'])){
             
            $role_id = NULL;
            if(!empty($_POST['role_id'])){
                $role_id = Yii::$app->utility->decryptString($_POST['role_id']);
                if(empty($role_id)){
                     Yii::$app->getSession()->setFlash('success', "Invalid Role ID"); 
                     return $this->redirect($url);
                }
            }
            $role_name = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $_POST['role_name']));
            $desc = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $_POST['desc']));
            $is_active = trim(preg_replace('/[^A-Z-]/', '', $_POST['is_active']));
            $result = Yii::$app->utility->add_update_master_role($role_id, $role_name, $desc, $is_active);
            /*
             * Logs
             */
            $logs['role_id']=$role_id;
            $logs['role_name']=$role_name;
            $logs['desc']=$desc;
            $logs['is_active']=$is_active;
            $jsonlogs = json_encode($logs);
            if($result == '1'){
                $msg = "Role Added Successfully.";
                Yii::$app->getSession()->setFlash('success', $msg); 
            }elseif($result == '2'){
                $msg = "Role Updated Successfully.";
                Yii::$app->getSession()->setFlash('success', $msg); 
            }elseif($result == '3'){
                $msg = "Role Already Exits.";
                Yii::$app->getSession()->setFlash('danger', $msg); 
            }else{
                $msg = "Role Not Added.";
                Yii::$app->getSession()->setFlash('danger', $msg); 
            }
            Yii::$app->utility->activities_logs("Master Data", NULL, NULL, $jsonlogs, $msg);
            return $this->redirect($url);
        }
        $this->layout = '@app/views/layouts/admin_layout.php';
        $roles = Yii::$app->utility->get_master_roles();
        return $this->render('index', ['menuid'=>$menuid,'roles'=>$roles]);
    }
}