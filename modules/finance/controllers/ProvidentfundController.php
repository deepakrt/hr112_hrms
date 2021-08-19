<?php

namespace app\modules\finance\controllers;
use Yii;
class ProvidentfundController extends \yii\web\Controller
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
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."finance/providentfund?securekey=$menuid";
        $month = $year = $status = $pfdata = "";
        if(isset($_GET['PF']) AND !empty($_GET['PF'])){
            $get = $_GET['PF'];
            $month = Yii::$app->utility->decryptString($get['month']);
            $year = Yii::$app->utility->decryptString($get['year']);
            $status = Yii::$app->utility->decryptString($get['status']);
            if(empty($month) OR empty($year) OR empty($status)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid params value.');
                return $this->redirect($url);
            }
            
            $pfdata = Yii::$app->finance->pf_get_monthwise_details(NULL, $month, $year, NULL, $status);
            if(empty($pfdata)){
                Yii::$app->getSession()->setFlash('danger', 'No Record Found.');
                return $this->redirect($url);
            }
        }
        return $this->render('index', ['menuid'=>$menuid, 'month'=>$month, "year"=>$year, 'status'=>$status, 'pfdata'=>$pfdata]);
    }

    public function actionUpdatepf(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."finance/providentfund?securekey=$menuid";
        
        if(isset($_POST['Paid']) AND !empty($_POST['Paid'])){
            $post = $_POST['Paid'];
            $year = Yii::$app->utility->decryptString($post['year']);
            $month = Yii::$app->utility->decryptString($post['month']);
            
            if(empty($month) OR empty($year)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid params value.');
                return $this->redirect($url);
            }
            $d = "$year-$month-01";
            $my = date('M-Y', strtotime($d));
            
            $pfcheck = Yii::$app->finance->pf_get_monthwise_details(NULL, $month, $year, NULL, "Paid");
            if(!empty($pfcheck)){
                $action = "danger";
                $msg = "PF Details Already Updated as Paid for the month $my";
                /*
                * Logs
                */
               $logs['financial_year'] = $fy;
               $logs['month'] = $month;
               $logs['year'] = $year;
               $logs['status'] = "Paid";
               $logs['employee'] = "Procedure call for all employees. pf_generate_pf";
               $jsonlogs = json_encode($logs);

               Yii::$app->utility->activities_logs("PF", NULL, NULL, $jsonlogs, $msg);

               Yii::$app->getSession()->setFlash($action, $msg);
               return $this->redirect($url);
            }
//            echo "<pre>";print_r($pfcheck); die;
            $fy = Yii::$app->finance->financialYrWithMonthYear($month, $year);
            $result = Yii::$app->finance->pf_generate_pf($fy, $month, $year, "Paid");
            
            if($result == '1'){
                $action = "success";
                $msg = "PF Details Updated as Paid for the month $my Successfully";
            }else{
                $action = "danger";
                $msg = "PF Details Not Updated as Paid for the month $my";
            }
            
            /*
             * Logs
             */
            $logs['financial_year'] = $fy;
            $logs['month'] = $month;
            $logs['year'] = $year;
            $logs['status'] = "Paid";
            $logs['employee'] = "Procedure call for all employees. pf_generate_pf";
            $jsonlogs = json_encode($logs);
            
            Yii::$app->utility->activities_logs("PF", NULL, NULL, $jsonlogs, $msg);
            
            Yii::$app->getSession()->setFlash($action, $msg);
            return $this->redirect($url);
        }
        Yii::$app->getSession()->setFlash('danger', 'Invalid params found.');
        return $this->redirect($url);
        echo "<pre>";print_r($_POST['Paid']);
    }
}
