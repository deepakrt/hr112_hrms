<?php

namespace app\modules\employee\controllers;
use yii;
use app\models\HrGeneralForms;
class GeneralformsController extends \yii\web\Controller
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
        $menuid = Yii::$app->utility->encryptString($_GET['securekey']);
        return $this->render('index', ['menuid'=>$menuid ]);
    }
    
    public function actionApplyentryslip() 
    {
        $model = new HrGeneralForms();
        if(isset($_POST['HrGeneralForms']) && !empty($_POST['HrGeneralForms']))
        {
            $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
            $menuid = Yii::$app->utility->encryptString($menuid);
            $urll = Yii::$app->homeUrl."employee/generalforms/applyentryslip?securekey=$menuid";
            $post=$_POST['HrGeneralForms'];
            
            $entrydate=$post['entry_date'];
            $param_entry_date=date('Y-m-d', strtotime($entrydate));
            $entry_time=$post['entry_time'];
            $exit_time=$post['exit_time'];
            $entrymintus=$post['entrymintus'];
            $exitmintus=$post['exitmintus'];
            $param_reason=$post['reason'];
            $param_reason=Yii::$app->utility->decryptString($param_reason);
            if(empty($param_reason)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid Reason');
                return $this->redirect($urll);
            }
            if(empty($post['otherreason']))
            {
                $param_other_reason=null;
            }
            else
            {
                $param_other_reason=$post['otherreason'];
            }
            $param_entry_time=$entry_time.":".$entrymintus;
            $param_exit_time=$exit_time.":".$exitmintus;
            $param_e_id=Yii::$app->user->identity->e_id;
            $param_type="Entry Slip";
            $param_status="Pending";
            $param_approved_by=$param_approved_on=$param_id=null;
            
            $EntryDate = $param_entry_date." ".$param_entry_time;
            $ExitDate = $param_entry_date." ".$param_exit_time;
//            echo strtotime($EntryDate)."<br>";
//            echo strtotime($ExitDate)."<br>";
            
            if(strtotime($ExitDate) < strtotime($EntryDate)){
                Yii::$app->getSession()->setFlash('danger', 'Exit Time cannot less then entry time.');
                return $this->redirect($urll);
            }
            
            $param_entry_time = date("H:i", strtotime($EntryDate));
            $param_exit_time = date("H:i", strtotime($ExitDate));
            
//            echo "EntryDate $EntryDate <br>";
//            echo "ExitDate $ExitDate <br>";
//            echo "$param_entry_time <br>";
//            echo "$param_exit_time <br>";
//            echo "<pre>";print_r($post); die;
            $info=Yii::$app->hr_utility->hr_add_update_general_form($param_id,$param_e_id, $param_type, $param_entry_date,$param_entry_time,$param_exit_time, $param_reason, $param_other_reason, $param_status, $param_approved_by,$param_approved_on);
            
            /*
             * Logs
             */
            $logs['id']=$param_id;
            $logs['emp_code']=$param_e_id;
            $logs['type']=$param_type;
            $logs['entry_date']=$param_entry_date;
            $logs['entry_time']=$param_entry_time;
            $logs['exit_time']=$param_exit_time;
            $logs['reason']=$param_reason;
            $logs['other_reason']=$param_other_reason;
            $logs['status']=$param_status;
            $jsonlogs = json_encode($logs);
            
            if($info == 1){
                Yii::$app->utility->activities_logs("General Form", NULL, NULL, $jsonlogs, "Entry Slip applied successfully.");
                Yii::$app->getSession()->setFlash('success', 'Entry Slip applied successfully.');
                return $this->redirect($urll);
            }elseif($info == 2){
                Yii::$app->utility->activities_logs("General Form", NULL, NULL, $jsonlogs, "You dont\'s have Entry Slip applied. Contact HR.");
                Yii::$app->getSession()->setFlash('danger', 'You dont\'s have Entry Slip applied. Contact HR.');
                return $this->redirect($urll);
            }elseif($info == 3){
                Yii::$app->utility->activities_logs("General Form", NULL, NULL, $jsonlogs, "Entry Date already exits.");
                Yii::$app->getSession()->setFlash('danger', 'Entry Date already exits.');
                return $this->redirect($urll);
            }else{
                Yii::$app->utility->activities_logs("General Form", NULL, NULL, $jsonlogs, "Error found. Contact Admin.");
                Yii::$app->getSession()->setFlash('danger', 'Error found. Contact Admin.');
                return $this->redirect($urll);
            }
        }
        $this->layout = '@app/views/layouts/admin_layout.php';
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($_GET['securekey']);
        return $this->render('applyentryslip', ['model'=>$model, 'menuid'=>$menuid ]);
    }
    
    public function actionOutdoorduty() 
    {
        $model = new HrGeneralForms();
//        echo "<pre>";print_R($_POST); die;
        if(isset($_POST) && !empty($_POST))
        {
            $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
            $menuid = Yii::$app->utility->encryptString($menuid);
            $urll = Yii::$app->homeUrl."employee/generalforms/outdoorduty?securekey=$menuid";
            $post=$_POST['HrGeneralForms'];
            $entrydate=$post['entry_date'];
            $param_entry_date=date('Y-m-d', strtotime($entrydate));
            $entry_time=$post['entry_time'];
            $exit_time=$post['exit_time'];
            $entrymintus=$_POST['entrymintus'];
            $exitmintus=$_POST['exitmintus'];
            $param_reason=$post['reason'];
            $param_entry_time=$entry_time.":".$entrymintus;
            $param_exit_time=$exit_time.":".$exitmintus;
            $param_e_id=Yii::$app->user->identity->e_id;
            $param_type="Outdoor Duty";
            $param_status="Pending";
            $param_approved_by=$param_approved_on=$param_id=$param_other_reason=null;
            
            
            $EntryDate = $param_entry_date." ".$param_entry_time;
            $ExitDate = $param_entry_date." ".$param_exit_time;
            if(strtotime($ExitDate) < strtotime($EntryDate)){
                Yii::$app->getSession()->setFlash('danger', 'Exit Time cannot less then entry time.');
                return $this->redirect($urll);
            }
            $param_entry_time = date("H:i", strtotime($EntryDate));
            $param_exit_time = date("H:i", strtotime($ExitDate));
            $info=Yii::$app->hr_utility->hr_add_update_general_form($param_id,$param_e_id, $param_type, $param_entry_date,$param_entry_time,$param_exit_time, $param_reason, $param_other_reason, $param_status, $param_approved_by,$param_approved_on);
            
            /*
             * Logs
             */
            $logs['id']=$param_id;
            $logs['emp_code']=$param_e_id;
            $logs['type']=$param_type;
            $logs['entry_date']=$param_entry_date;
            $logs['entry_time']=$param_entry_time;
            $logs['exit_time']=$param_exit_time;
            $logs['reason']=$param_reason;
            $logs['other_reason']=$param_other_reason;
            $logs['status']=$param_status;
            $jsonlogs = json_encode($logs);
            
            if($info == 1)
            {
                Yii::$app->utility->activities_logs("General Form", NULL, NULL, $jsonlogs, "OutDoor Duty Slip applied successfully.");
                
                Yii::$app->getSession()->setFlash('success', 'OutDoor Duty Slip applied successfully.');
                return $this->redirect($urll);
            }
            elseif($info == 2)
            {
                Yii::$app->utility->activities_logs("General Form", NULL, NULL, $jsonlogs, "You dont\'s have OutDoor Duty Slip applied. Contact HR.");
                
                Yii::$app->getSession()->setFlash('danger', 'You dont\'s have OutDoor Duty Slip applied. Contact HR.');
                return $this->redirect($urll);
            }
            else
            {
                Yii::$app->utility->activities_logs("General Form", NULL, NULL, $jsonlogs, "Error found. Contact Admin.");
                
                Yii::$app->getSession()->setFlash('danger', 'Error found. Contact Admin.');
                return $this->redirect($urll);
            }
        }
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('outdoorduty', ['model'=>$model]);
    }
    
}
