<?php

namespace app\modules\finance\controllers;
use Yii;
class DamasterController extends \yii\web\Controller
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
        $this->layout = '@app/views/layouts/admin_layout.php';
        $daLists = Yii::$app->finance->fn_get_da_master();
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        return $this->render('index', ['daLists'=>$daLists, 'menuid'=>$menuid]);
    }
    public function actionAddda()
    {
        $this->layout = '@app/views/layouts/admin_layout.php';
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."finance/damaster/addda?securekey=$menuid";
        if(isset($_POST['DA']) AND !empty($_POST['DA'])){
            
            $month = Yii::$app->utility->decryptString($_POST['DA']['month']);
            $percentage = $dept_id =  trim(preg_replace('/[^0-9.]/', '', $_POST['DA']['percentage']));;
            
            if(empty($month)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Period Selected.');
                return $this->redirect($url);
            }
            if($month == '1' OR $month == '2'){
                $curYr = date('Y');
                if($month == '1'){
                    $monthYr = "01-".$curYr;
                    $fnYr = date("Y",strtotime("-1 year"))."-".$curYr;
                    $effFr = "$curYr-01-01";
                }elseif($month == '2'){
                    $monthYr = "07-".$curYr;
                    $fnYr = $curYr."-".date("Y",strtotime("1 year"));
                    $effFr = "$curYr-07-01";
                }
                $result = Yii::$app->finance->fn_add_update_damaster(NULL, $monthYr, $percentage, $effFr, $fnYr);
                
                if($result == '3'){
                    $msg = "DA Details Already Exits.";
                    Yii::$app->getSession()->setFlash('danger', $msg);
                }elseif($result == '1'){
                    $msg = "DA Details Added Successfully.";
                    Yii::$app->getSession()->setFlash('success', $msg);
                }
                /*
                 * Logs
                 */
                $logs['da_id']=NULL;
                $logs['month_year']=$monthYr;
                $logs['da_percentage']=$percentage;
                $logs['effected_from']=$effFr;
                $logs['financial_year']=$fnYr;
                $jsonLogs = json_encode($logs);
                Yii::$app->utility->activities_logs("Finance", NULL, NULL, $jsonLogs, $msg);
                
                return $this->redirect($url);
            }else{
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected.');
                return $this->redirect($url);
            }
            
        }
        return $this->render('addda', ['menuid'=>$menuid]);
    }
    
}
