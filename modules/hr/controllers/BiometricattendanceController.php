<?php
namespace app\modules\hr\controllers;
use yii;
class BiometricattendanceController extends \yii\web\Controller
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
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $year_month = date('yy-m');
        $this->layout = '@app/views/layouts/admin_layout.php';
        $st = "Biometric Attendance Logs";

        $attndn = Yii::$app->hr_utility->hr_get_biometric_attendance('All',Yii::$app->user->identity->e_id,date('yy-m-d'));
        if(isset($_GET['key'])){
            $year_month = $_GET['key'];
            $attndn = Yii::$app->hr_utility->hr_get_biometric_attendance('Month',Yii::$app->user->identity->e_id,$year_month);

        }
        //echo '<pre>';
        //print_r(Yii::$app->user->identity->e_id);
        //Yii::$app->user->identity->e_id
        
        //First Agument view type, second employee code third date

        return $this->render('index', ['menuid'=>$menuid, 'st'=>$st,'attndn'=>$attndn,'year_month'=>$year_month]);
    }

     public function actionView(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $year_month = date('yy-m');
        $this->layout = '@app/views/layouts/admin_layout.php';
        $st = "FLA Biometric Attendance Logs";

        $attndn = Yii::$app->hr_utility->hr_get_manage_biometric_attendance('All',Yii::$app->user->identity->e_id,date('yy-m-d'));
        if(isset($_GET['key'])){
            $year_month = $_GET['key'];
            $attndn = Yii::$app->hr_utility->hr_get_manage_biometric_attendance('Month',Yii::$app->user->identity->e_id,$year_month);

        }
        //echo '<pre>';
        //print_r(Yii::$app->user->identity->e_id);
        //Yii::$app->user->identity->e_id
        
        //First Agument view type, second employee code third date

        return $this->render('index', ['menuid'=>$menuid, 'st'=>$st,'attndn'=>$attndn,'year_month'=>$year_month]);
    }


}
