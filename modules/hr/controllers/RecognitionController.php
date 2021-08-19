<?php
namespace app\modules\hr\controllers;
use app\models\RewardMaster; 
use yii;
class RecognitionController extends \yii\web\Controller
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
    
    public function actionCheck()
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('check', ['menuid'=>$menuid]);
    }
     public function actionAdd(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."admin/manageemployees/add?securekey=$menuid";
        $this->layout = '@app/views/layouts/admin_layout.php';
         if(isset($_POST['Recognition']) AND !empty($_POST['Recognition'])){

            $post = $_POST['Recognition'];
            $reco_name = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['name']));
            $reco_desc = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['description']));
            
            $reco_type = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['reco_type']));
            $from_type = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['from_type']));
            $from_department = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['from_department']));
           
            $isActive = trim(preg_replace('/[^A-Za-z]/', '', $post['is_active']));
            
            if(!empty($reco_name) AND !empty($reco_desc) AND !empty($isActive) AND !empty($reco_type) AND !empty($from_department)){
                
                $created_by = Yii::$app->user->identity->e_id;
                $result = Yii::$app->utility->add_update_recognition(null, $reco_name,$reco_desc,$reco_type,$from_type,$from_department,$isActive,$created_by);
                if($result == '1'){
                    Yii::$app->getSession()->setFlash('success', 'Recognition added successfully');
                    return $this->redirect(Yii::$app->homeUrl."hr/recognition?securekey=".$menuid);
                }
            }else{
                Yii::$app->getSession()->setFlash('danger', 'Invalid / Empty params found.');
                return $this->redirect(Yii::$app->homeUrl."hr/recognition?securekey=".$menuid);
            }
        }
        $category= Yii::$app->utility->get_reward_category();
        $model = new \app\models\Recognition();
        return $this->render('add', ['model'=>$model, 'menuid'=>$menuid,'category'=>$category]);
    }
    public function actionUpdatereward(){
        
        //die('herer');
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        if(isset($_GET['rewardid']) AND !empty($_GET['rewardid'])){
            $id = base64_decode($_GET['rewardid']);
            $info = Yii::$app->utility->get_rewards($id);
            if(empty($info)){
                Yii::$app->getSession()->setFlash('danger', 'No Record found');
                return $this->redirect(Yii::$app->homeUrl."admin/managedepartment?securekey=".$menuid);
            }
            
            if(isset($_POST['RewardMaster']) AND !empty($_POST['RewardMaster'])){
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
             $category= Yii::$app->utility->get_reward_category();
            $model = new RewardMaster();
            $model->id = $info['id'];
            $model->name = $info['name'];
            $model->description = $info['description'];
            $model->reward_type_id = $info['reward_type_id'];
            $model->reward_sub_cat = $info['reward_sub_cat'];
            $model->is_active = $info['is_active'];
            $this->layout = '@app/views/layouts/admin_layout.php';
            
            return $this->render('updatereward', ['model'=>$model, 'menuid'=>$menuid,'category'=>$category]);
        }else{
            return $this->redirect(Yii::$app->homeUrl."managedepartment?securekey=".$menuid);
        }
    }
        public function actionViewrecognition(){
             
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        if(isset($_GET['securekey']) AND !empty($_GET['securekey']) AND isset($_GET['recoid']) AND !empty($_GET['recoid'])){
            $securekey = Yii::$app->utility->decryptString($_GET['securekey']);
            $e_id = base64_decode($_GET['recoid']);
            
            if(empty($securekey) OR empty($e_id)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected');
                return $this->redirect(Yii::$app->homeUrl);
            }
          
            $info = Yii::$app->utility->get_recognitions($e_id,null);
            
            if(empty($info)){
                Yii::$app->getSession()->setFlash('danger', 'No Record found');
                return $this->redirect(Yii::$app->homeUrl."admin/reward?securekey=".$menuid);
            }
           
            $this->layout = '@app/views/layouts/admin_layout.php';
            return $this->render('viewrecognition', ['info'=>$info]);
        }else{
            die('hererere');
            return $this->redirect(Yii::$app->homeUrl."admin/manageemployees?securekey=".$menuid);
        }
    }

}