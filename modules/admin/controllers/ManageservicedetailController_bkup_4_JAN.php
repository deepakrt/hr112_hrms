<?php
namespace app\modules\admin\controllers;
use yii;
class ManageservicedetailController extends \yii\web\Controller
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
    
    public function actionIndex()
    {
        $this->layout = '@app/views/layouts/admin_layout.php';
        $allEmps = Yii::$app->utility->get_employees();
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        return $this->render('index', ['allEmps'=>$allEmps,'menuid'=>$menuid]);
    }
    
    public function actionView(){
        
        if(isset($_GET['securekey']) AND !empty($_GET['securekey']) AND isset($_GET['securecode']) AND !empty($_GET['securecode'])){
            $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
            $emp_code = Yii::$app->utility->decryptString($_GET['securecode']);
            if(empty($menuid) OR empty($emp_code)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected.'); 
                return $this->redirect(Yii::$app->homeUrl);
            }
            $servicedetail = Yii::$app->utility->get_service_details($emp_code,'Full');
            $info = Yii::$app->utility->get_employees($emp_code);
//            echo "<pre>";print_r($servicedetail); die;
            if(empty($servicedetail) OR empty($info)){
                $menuid = Yii::$app->utility->encryptString($menuid);
                Yii::$app->getSession()->setFlash('danger', 'No record found.'); 
                return $this->redirect(Yii::$app->homeUrl."admin/manageservicedetail?securekey=$menuid");
            }
            $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
            $menuid = Yii::$app->utility->encryptString($menuid);
            $this->layout = '@app/views/layouts/admin_layout.php';
            return $this->render('view', ['servicedetail'=>$servicedetail, 'info'=>$info, 'menuid'=>$menuid]);
        }else{
            return $this->redirect(Yii::$app->homeUrl);
        }
    }
    public function actionUpdate(){
        echo "<pre>";print_r($_GET); die;
    }

}