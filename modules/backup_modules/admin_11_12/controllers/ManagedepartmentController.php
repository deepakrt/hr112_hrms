<?php

namespace app\modules\admin\controllers;
use app\models\Department; 
use yii;
class ManagedepartmentController extends \yii\web\Controller
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
        if(isset($_POST['Department']) AND !empty($_POST['Department'])){
            $post = $_POST['Department'];
            $dept_name = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['dept_name']));
            $dept_desc = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['dept_desc']));
            $isActive = trim(preg_replace('/[^A-Za-z]/', '', $post['is_active']));
            if(!empty($dept_name) AND !empty($dept_desc) AND !empty($isActive)){
                $result = Yii::$app->utility->add_update_dept(null, $dept_name,$dept_desc,$isActive);
                if($result == '1'){
                    Yii::$app->getSession()->setFlash('success', 'Department added successfully');
                    return $this->redirect(Yii::$app->homeUrl."admin/managedepartment?securekey=".$menuid);
                }
            }else{
                Yii::$app->getSession()->setFlash('danger', 'Invalid / Empty params found.');
                return $this->redirect(Yii::$app->homeUrl."admin/managedepartment?securekey=".$menuid);
            }
        }
        $model = new Department();
        return $this->render('add', ['model'=>$model, 'menuid'=>$menuid]);
    }
    
    /*
     * View for Update Department
     */
    public function actionUpdatedepartment(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        if(isset($_GET['key']) AND !empty($_GET['key'])){
            $id = base64_decode($_GET['key']);
            $info = Yii::$app->utility->get_dept($id);
            if(empty($info)){
                Yii::$app->getSession()->setFlash('danger', 'No Record found');
                return $this->redirect(Yii::$app->homeUrl."admin/managedepartment?securekey=".$menuid);
            }
            
            if(isset($_POST['Department']) AND !empty($_POST['Department'])){
                $post = $_POST['Department'];
                $dept_id = Yii::$app->utility->decryptString($post['dept_id']);
                $dept_name = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['dept_name']));
                $dept_desc = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['dept_desc']));
                $isActive = trim(preg_replace('/[^A-Za-z]/', '', $post['is_active']));
                
                if(!empty($dept_id) AND !empty($dept_name) AND !empty($dept_desc) AND !empty($isActive)){
                    $result = Yii::$app->utility->add_update_dept($dept_id, $dept_name,$dept_desc,$isActive);
                    if($result == '2'){
                        Yii::$app->getSession()->setFlash('success', 'Department updated successfully');
                        return $this->redirect(Yii::$app->homeUrl."admin/managedepartment?securekey=".$menuid);
                    }
                }else{
                    Yii::$app->getSession()->setFlash('danger', 'Invalid / Empty params found.');
                    return $this->redirect(Yii::$app->homeUrl."admin/managedepartment?securekey=".$menuid);
                }
                
            }
            $model = new Department();
            $model->dept_id = $info['dept_id'];
            $model->dept_name = $info['dept_name'];
            $model->dept_desc = $info['dept_desc'];
            $model->is_active = $info['is_active'];
            $this->layout = '@app/views/layouts/admin_layout.php';
            
            return $this->render('update', ['model'=>$model, 'menuid'=>$menuid]);
        }else{
            return $this->redirect(Yii::$app->homeUrl."managedepartment?securekey=".$menuid);
        }
    }
}
