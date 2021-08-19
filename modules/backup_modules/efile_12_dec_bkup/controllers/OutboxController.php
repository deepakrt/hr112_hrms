<?php

namespace app\modules\efile\controllers;
use Yii;
class OutboxController extends \yii\web\Controller
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
	
	public function actionViewoutboxdak()
    {
		$menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
		
        $url = Yii::$app->homeUrl."efile/outbox?securekey=$menuid";
        if(isset($_GET['key']) AND !empty($_GET['key'])){
			$file_id = Yii::$app->utility->decryptString($_GET['key']);
//			$movement_id = Yii::$app->utility->decryptString($_GET['key2']);
				
//			if(empty($file_id) OR empty($movement_id)){
			if(empty($file_id)){
				Yii::$app->getSession()->setFlash('danger', "Invalid params value found.");
				return $this->redirect($url);
			}
			
			$fileinfo = Yii::$app->fts_utility->efile_get_dak($file_id, NULL, NULL, NULL);
			
			if(empty($fileinfo)){
				Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
				return $this->redirect($url);
			}
			// if($fileinfo['is_active'] == 'N'){
				
				// Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
				// return $this->redirect($url);
			// }
			
			if($fileinfo['status'] == 'Open' OR $fileinfo['status'] == 'Closed'){
			}elseif($fileinfo['status'] == 'Scan'){}else{
				Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
				return $this->redirect($url);
			}
			
			$receiptInfo = "";
			
			// Check file send to current user or not
//			$movement = Yii::$app->fts_utility->efile_get_efile_dak_movement(NULL, $movement_id);
			// echo "<pre>";print_r($movement);die;
			// echo "<pre>";print_r($movement);die;
			// if(empty($movement)){
				// Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
				// return $this->redirect($url);
			// }
			
			// if(!empty($fileinfo['rec_id'])){
				// $receiptInfo = Yii::$app->fts_utility->efile_get_dak_received($fileinfo['rec_id'], NULL);
			// }
			$this->layout = '@app/views/layouts/filewithnoting_layout.php';
//			return $this->render('viewoutboxdak', ['menuid'=>$menuid, 'fileinfo'=>$fileinfo, 'movement'=>$movement, 'receiptInfo'=>$receiptInfo]);		
			return $this->render('viewoutboxdak', ['menuid'=>$menuid, 'fileinfo'=>$fileinfo,  'receiptInfo'=>$receiptInfo]);		
		}else{
			Yii::$app->getSession()->setFlash('danger', "Invalid params  found.");
			return $this->redirect($url);
		}
    }

}
