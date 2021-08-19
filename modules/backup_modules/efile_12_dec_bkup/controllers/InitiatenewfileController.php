<?php

namespace app\modules\efile\controllers;
use Yii;
use app\models\EfileDak;
class InitiatenewfileController extends \yii\web\Controller
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
        $model = new EfileDak();
        $url = Yii::$app->homeUrl."efile/initiatenewfile?securekey=$menuid";
        $voucher_path = $voucher_number = "";
//        echo "<pre>";print_r($_GET);die;
        if(isset($_GET['vid']) AND !empty($_GET['vid']) AND isset($_GET['vpath']) AND !empty($_GET['vpath'])){
            $voucher_number = Yii::$app->utility->decryptString($_GET['vid']);
            $voucher_path = Yii::$app->utility->decryptString($_GET['vpath']);
            if(empty($voucher_number) OR empty($voucher_path)){
                Yii::$app->getSession()->setFlash('danger', "Invalid Voucher Number.");
                return $this->redirect($url);
            }
            $checkVoucher = EfileDak::find()->where(['voucher_number'=>$voucher_number, 'is_active' => 'Y'])->asArray()->one();
//            echo "<pre>";print_r($checkVoucher); die;
            if(!empty($checkVoucher)){
                Yii::$app->getSession()->setFlash('danger', "Note Already Initiated againt Voucher Number: $voucher_number.");
                return $this->redirect($url);
            }
            $voucher_path = "/$voucher_path";
//            die($voucher_path);
        }
        return $this->render('index', ['menuid'=>$menuid, 'model'=>$model, 'voucher_number'=>$voucher_number, 'voucher_path'=>$voucher_path]);
    }

}
