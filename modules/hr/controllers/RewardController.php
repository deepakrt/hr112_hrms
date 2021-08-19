<?php
namespace app\modules\hr\controllers;
use yii;
class RewardController extends \yii\web\Controller
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
    
    public function actionIndex(){
        
       $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('index', ['menuid'=>$menuid]);
           
      
    }
    public function actionCheck(){
        
       $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('check', ['menuid'=>$menuid]);
           
      
    }
    public function actionView(){
        $this->layout = '@app/views/layouts/admin_layout.php';
        $param_auth_type = $param_map_id = $param_status=$param_entry_type= NULL;
        if(Yii::$app->user->identity->role == '5'){
            $param_status = "Approved,Rejected";
        }elseif(Yii::$app->user->identity->role == '2'){
            $param_status = 'In-Process,Approved,Rejected';
            $param_auth_type="A2";
            $param_map_id = Yii::$app->user->identity->e_id;
        }elseif(Yii::$app->user->identity->role == '4'){
            $param_status = 'In-Process,Approved,Rejected';
            $param_auth_type="A1";
            $param_map_id = Yii::$app->user->identity->e_id;
        }
//        echo "$param_auth_type <br>";
//        echo "$param_map_id <br>";
//        echo "$param_status <br>";
//        echo "$param_entry_type <br>";
        
        $slips = Yii::$app->hr_utility->hr_view_general_form_detail($param_auth_type,$param_map_id,$param_status,$param_entry_type);
        return $this->render('view', ['slips'=>$slips]);
    }  
    public function actionViewreward(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        if(isset($_GET['securekey']) AND !empty($_GET['securekey']) AND isset($_GET['rewardid']) AND !empty($_GET['rewardid'])){
            $securekey = Yii::$app->utility->decryptString($_GET['securekey']);
            $e_id = base64_decode($_GET['rewardid']);
            
            if(empty($securekey) OR empty($e_id)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected');
                return $this->redirect(Yii::$app->homeUrl);
            }
           // die('sdsf');
            $info = Yii::$app->utility->get_rewards($e_id);
            $infoDetails = Yii::$app->utility->get_applied_rewards($e_id,Yii::$app->user->identity->e_id);
            //echo "<pre>";print_r($info);die;
            if(empty($info)){
                Yii::$app->getSession()->setFlash('danger', 'No Record found');
                return $this->redirect(Yii::$app->homeUrl."hr/reward?securekey=".$menuid);
            }
           
            $this->layout = '@app/views/layouts/admin_layout.php';
            return $this->render('viewreward', ['info'=>$info,'infoDetails'=>$infoDetails]);
        }else{
            die('hererere');
            return $this->redirect(Yii::$app->homeUrl."hr/reward?securekey=".$menuid);
        }
    }
    public function actionCheckdetail(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        if(isset($_GET['securekey']) AND !empty($_GET['securekey']) AND isset($_GET['rewardapplyid']) AND !empty($_GET['rewardapplyid'])){
            $securekey = Yii::$app->utility->decryptString($_GET['securekey']);
            $e_id = base64_decode($_GET['rewardapplyid']);
            
            if(empty($securekey) OR empty($e_id)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected');
                return $this->redirect(Yii::$app->homeUrl);
            }
           // die('sdsf');
            $info = Yii::$app->utility->get_rewards_apply_detail($e_id);
           //echo "<pre>";print_r($info);die;
            if(empty($info)){
                Yii::$app->getSession()->setFlash('danger', 'No Record found');
                return $this->redirect(Yii::$app->homeUrl."hr/reward?securekey=".$menuid);
            }
           
            $this->layout = '@app/views/layouts/admin_layout.php';
            return $this->render('checkdetail', ['info'=>$info]);
        }else{
            die('hererere');
            return $this->redirect(Yii::$app->homeUrl."hr/reward?securekey=".$menuid);
        }
    }
    
     public function actionApplyreward(){
         
        // die('here');
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        if(isset($_GET['securekey']) AND !empty($_GET['securekey']) AND isset($_GET['rewardid']) AND !empty($_GET['rewardid'])){
            $securekey = Yii::$app->utility->decryptString($_GET['securekey']);
            $r_id = base64_decode($_GET['rewardid']);
            
            if(empty($securekey) OR empty($r_id)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected');
                return $this->redirect(Yii::$app->homeUrl);
            }
           // die('sdsf');
            $e_id = Yii::$app->user->identity->e_id;
            $info = Yii::$app->utility->apply_rewards(null,$r_id,$e_id,$status='1');
            
            
            if(!empty($info)){
                     Yii::$app->getSession()->setFlash('success', 'Apply successfully');
                    return $this->redirect(Yii::$app->homeUrl."hr/reward?securekey=".$menuid."&rewardid=".$_GET['rewardid']);            }
           
          
        }else{
            die('hererere');
            return $this->redirect(Yii::$app->homeUrl."hr/reward?securekey=".$menuid);
        }
    }
        
     public function actionApprovereward(){
         
        // die('here');
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        if(isset($_GET['securekey']) AND !empty($_GET['securekey']) AND isset($_GET['rewardapplyid']) AND !empty($_GET['rewardapplyid'])){
            $securekey = Yii::$app->utility->decryptString($_GET['securekey']);
            $r_id = base64_decode($_GET['rewardapplyid']);
            
            if(empty($securekey) OR empty($r_id)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected');
                return $this->redirect(Yii::$app->homeUrl);
            }
           // die('sdsf');
            $e_id = Yii::$app->user->identity->e_id;
            if(Yii::$app->user->identity->role==4){
                $status = '2';
            }
            else if(Yii::$app->user->identity->role==2){
                $status = '3';
            }
            $info = Yii::$app->utility->apply_rewards($r_id,NULL,NULL,$status);
            
            
            if(!empty($info)){
                     Yii::$app->getSession()->setFlash('success', 'Action taken successfully');
                    return $this->redirect(Yii::$app->homeUrl."hr/reward/check?securekey=".$menuid);            }
           
          
        }else{
            die('hererere');
            return $this->redirect(Yii::$app->homeUrl."hr/reward?securekey=".$menuid);
        }
    }
     public function actionRejectreward(){
         
        // die('here');
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        if(isset($_GET['securekey']) AND !empty($_GET['securekey']) AND isset($_GET['rewardapplyid']) AND !empty($_GET['rewardapplyid'])){
            $securekey = Yii::$app->utility->decryptString($_GET['securekey']);
            $r_id = base64_decode($_GET['rewardapplyid']);
            
            if(empty($securekey) OR empty($r_id)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected');
                return $this->redirect(Yii::$app->homeUrl);
            }
           // die('sdsf');
            $e_id = Yii::$app->user->identity->e_id;
            if(Yii::$app->user->identity->role==4){
                $status = '4';
            }
            else if(Yii::$app->user->identity->role==2){
                $status = '5';
            }
            
            $info = Yii::$app->utility->apply_rewards($r_id,NULL,NULL,$status);
            
            
            if(!empty($info)){
                     Yii::$app->getSession()->setFlash('success', 'Action taken successfully');
                    return $this->redirect(Yii::$app->homeUrl."hr/reward/check?securekey=".$menuid);            }
           
          
        }else{
            die('hererere');
            return $this->redirect(Yii::$app->homeUrl."hr/reward?securekey=".$menuid);
        }
    }
    
    
    
}