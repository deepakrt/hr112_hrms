<?php

namespace app\modules\efile\controllers;
use Yii;
use app\models\EfileCcDak;
use app\models\EfileDakMovement;
class CcfilesController extends \yii\web\Controller
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
    
    public function actionViewccdak(){
        
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."efile/ccfiles?securekey=$menuid";
        
        if(isset($_GET['key']) AND !empty($_GET['key']) AND isset($_GET['key2']) AND !empty($_GET['key2'])){
            $file_id = Yii::$app->utility->decryptString($_GET['key']);
            $cc_id = Yii::$app->utility->decryptString($_GET['key2']);

            if(empty($file_id) OR empty($cc_id)){
                Yii::$app->getSession()->setFlash('danger', "Invalid params value found.");
                return $this->redirect($url);
            }

            $fileinfo = Yii::$app->fts_utility->efile_get_dak($file_id, NULL, NULL, NULL);
            if(empty($fileinfo)){
                Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
                return $this->redirect($url);
            }
            if($fileinfo['is_active'] == 'N'){
                Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
                return $this->redirect($url);
            }

            if($fileinfo['status'] != 'Open'){
                Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
                return $this->redirect($url);
            }
            
            $ccInfo = EfileCcDak::find()->where(['emp_code'=>Yii::$app->user->identity->e_id, 'cc_id'=>$cc_id, 'is_active'=>'Y'])->asArray()->one();
            if(empty($ccInfo)){
                Yii::$app->getSession()->setFlash('danger', "No Record Found.");
                return $this->redirect($url);
            }
            $receiptInfo = array();
            if(!empty($fileinfo['rec_id'])){
                $receiptInfo = Yii::$app->fts_utility->efile_get_dak_received($fileinfo['rec_id'], NULL);
            }
            $this->layout = '@app/views/layouts/filewithnoting_layout.php';
            return $this->render('viewccdak', ['menuid'=>$menuid, 'fileinfo'=>$fileinfo, 'ccInfo'=>$ccInfo, 'receiptInfo'=>$receiptInfo]);
//            echo "<pre>";print_r($ccInfo);die;
        }else{
            Yii::$app->getSession()->setFlash('danger', "Invalid param found.");
            return $this->redirect($url);
        }
    }
    public function actionMoveininbox(){
        
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."efile/ccfiles?securekey=$menuid";
       
        if(isset($_GET['key']) AND !empty($_GET['key']) AND isset($_GET['key2']) AND !empty($_GET['key2'])){
            $file_id = Yii::$app->utility->decryptString($_GET['key']);
            $cc_id = Yii::$app->utility->decryptString($_GET['key2']);

            if(empty($file_id) OR empty($cc_id)){
                Yii::$app->getSession()->setFlash('danger', "Invalid params value found.");
                return $this->redirect($url);
            }
//die("NNNN");
            $fileinfo = Yii::$app->fts_utility->efile_get_dak($file_id, NULL, NULL, NULL);
            if(empty($fileinfo)){
                Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
                return $this->redirect($url);
            }
            if($fileinfo['is_active'] == 'N'){
                Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
                return $this->redirect($url);
            }

            if($fileinfo['status'] != 'Open'){
                Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
                return $this->redirect($url);
            }
            $model = EfileCcDak::find()->where(['cc_id'=>$cc_id, 'is_active'=>'Y', 'emp_code'=>Yii::$app->user->identity->e_id])->one();
            if(empty($model)){
                Yii::$app->getSession()->setFlash('danger', "No Record Found.");
                return $this->redirect($url);
            }
            $EfileDakMovement = EfileDakMovement::find()->where(['id'=>$model->movement_id, 'is_active'=>'N', 'move_to_cc'=>'Y', 'fwd_emp_code'=>Yii::$app->user->identity->e_id])->one();
            if(empty($EfileDakMovement)){
                Yii::$app->getSession()->setFlash('danger', "No Record Found.");
                return $this->redirect($url);
            }
            
            $model->is_active = "N";
            $model->last_updated = date('Y-m-d H:i:s');
            
            
            $EfileDakMovement->is_active = "Y";
            $EfileDakMovement->move_to_cc = "N";
            
            $model->save();
            $EfileDakMovement->save();
            
            Yii::$app->getSession()->setFlash('success', "File Moved in INBOX Successfully.");
            return $this->redirect($url);
//            echo "<pre>";print_r($model); 
//            echo "<pre>";print_r($EfileDakMovement); 
//            die;
        }else{
            Yii::$app->getSession()->setFlash('danger', "Invalid param found.");
            return $this->redirect($url);
        }
    }
    
}
