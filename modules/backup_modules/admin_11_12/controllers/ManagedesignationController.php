<?php

namespace app\modules\admin\controllers;
use app\models\Designation; 
use yii;
class ManagedesignationController extends \yii\web\Controller
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
        return $this->render('index', ['menuid'=>$menuid]);
    }
    
    public function actionAdd(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $this->layout = '@app/views/layouts/admin_layout.php';
        if(isset($_POST['Designation']) AND !empty($_POST['Designation'])){
            $post = $_POST['Designation'];
            $desg_name = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['desg_name']));
            $desg_desc = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['desg_desc']));
            $isActive = trim(preg_replace('/[^A-Za-z]/', '', $post['is_active']));
            if(!empty($desg_name) AND !empty($desg_desc) AND !empty($isActive)){
                $result = Yii::$app->utility->add_update_desg(null, $desg_name,$desg_desc,$isActive);
                if($result == '1'){
                    Yii::$app->getSession()->setFlash('success', 'Designation added successfully');
                    return $this->redirect(Yii::$app->homeUrl."admin/managedesignation?securekey=".$menuid); 
                }
            }else{
                Yii::$app->getSession()->setFlash('danger', 'Invalid / Empty params found');
                return $this->redirect(Yii::$app->homeUrl."admin/managedesignation?securekey=".$menuid); 
            }
        }
        $model = new Designation();
        return $this->render('add', ['model'=>$model, 'menuid'=>$menuid]);
    }
    
    /*
     * View for Update Designation
     */
    public function actionUpdatedesignation(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        if(isset($_GET['key']) AND !empty($_GET['key'])){
            $id = base64_decode($_GET['key']);
            $info = Yii::$app->utility->get_designation($id);
            
            if(empty($info)){
                Yii::$app->getSession()->setFlash('danger', 'No Record found');
                return $this->redirect(Yii::$app->homeUrl."admin/managedesignation?securekey=".$menuid);
            }
            
            if(isset($_POST['Designation']) AND !empty($_POST['Designation'])){
                $post = $_POST['Designation'];
                $desg_id = Yii::$app->utility->decryptString($post['desg_id']);
                $desg_name = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['desg_name']));
                $desg_desc = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['desg_desc']));
                $isActive = trim(preg_replace('/[^A-Za-z]/', '', $post['is_active']));
                if(!empty($desg_id) AND !empty($desg_name) AND !empty($desg_desc) AND !empty($isActive)){
                $result = Yii::$app->utility->add_update_desg($desg_id, $desg_name,$desg_desc,$isActive);
                    if($result == '2'){
                        Yii::$app->getSession()->setFlash('success', 'Designation updated successfully');
                        return $this->redirect(Yii::$app->homeUrl."admin/managedesignation?securekey=".$menuid); 
                    }
                }else{
                    Yii::$app->getSession()->setFlash('danger', 'Invalid / Empty params found');
                    return $this->redirect(Yii::$app->homeUrl."admin/managedesignation?securekey=".$menuid); 
                }
            }
            $model = new Designation();
            $model->desg_id = $info['desg_id'];
            $model->desg_name = $info['desg_name'];
            $model->desg_desc = $info['desg_desc'];
            $model->is_active = $info['is_active'];
            $this->layout = '@app/views/layouts/admin_layout.php';
            return $this->render('update', ['model'=>$model, 'menuid'=>$menuid]);
        }else{
            return $this->redirect(Yii::$app->homeUrl."managedesignation?securekey=".$menuid);
        }
    }
}
