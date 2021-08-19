<?php

namespace app\modules\dashboard\controllers;

use yii\web\Controller;
use yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
class DefaultController extends Controller
{
    // public function beforeAction($action){
    //     if (!\Yii::$app->user->isGuest) {
    //         if(isset($_GET['securekey']) AND !empty($_GET['securekey'])){
    //             $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
				
    //             if(empty($menuid)){ return $this->redirect(Yii::$app->homeUrl); }

    //             $chkValid = Yii::$app->utility->validate_url($menuid);
				// // die($chkValid);
    //             if(empty($chkValid)){ return $this->redirect(Yii::$app->homeUrl); }
    //             return true;
    //         }else{ return $this->redirect(Yii::$app->homeUrl); }
    //     }else{
    //         return $this->redirect(Yii::$app->homeUrl);
    //     }
    //     parent::beforeAction($action);
    // }
    public function actionIndex()
    {
        
//        echo "<pre>"; print_r(Yii::$app->user->identity); die;
//        echo "<pre>";print_r(Yii::$app->user->identity); die;
    //echo "<pre>";print_r($_SESSION);die;
        $this->layout = '@app/views/layouts/admin_layout.php';
//        if(Yii::$app->user->identity->role == 3){
//            return $this->redirect(Yii::$app->homeUrl."employee/information");
//        }
        return $this->render('index');
    }
}
