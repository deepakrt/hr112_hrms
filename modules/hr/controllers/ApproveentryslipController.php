<?php
namespace app\modules\hr\controllers;
use yii;
class ApproveentryslipController extends \yii\web\Controller
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
        
        if(isset($_POST['EntrySlip']) AND !empty($_POST['EntrySlip'])){
            $menuid = Yii::$app->utility->decryptString($_POST['menuid']);
            if(empty($menuid)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Menu ID'); 
                return $this->redirect(Yii::$app->homeUrl);
            }
            $menuid = Yii::$app->utility->encryptString($menuid);
            $url = Yii::$app->homeUrl."hr/approveentryslip?securekey=$menuid";
            
            $post = $_POST['EntrySlip'];
            $newList = array();
            $i=0;
            foreach($post as $key=>$po){
                $leave_id = Yii::$app->utility->decryptString($po['leave_id']);
                if(!empty($leave_id)){
                    $status = "";
                    if(Yii::$app->user->identity->role == '5'){
                        if($po['is_approved'] == 'Y' AND $po['is_rejected'] == 'N'){
                            $status = "Approved";
                        }elseif($po['is_approved'] == 'N' AND $po['is_rejected'] == 'Y'){
                            $status = "Rejected";
                        }
                    }elseif(Yii::$app->user->identity->role == '2' OR Yii::$app->user->identity->role == '4'){
                        if($po['is_approved'] == 'Y' AND $po['is_rejected'] == 'N'){
                            $status = "In-Process";
                        }elseif($po['is_approved'] == 'N' AND $po['is_rejected'] == 'Y'){
                            $status = "Rejected";
                        }
                    }
                    if(empty($status)){
                        Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Status'); 
                        return $this->redirect($url);
                    }
                    $e_id = Yii::$app->utility->decryptString($po['e_id']);
                    if(empty($status) OR empty($e_id) OR empty($leave_id)){
                        Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Fields Found'); 
                        return $this->redirect($url);
                    }
                    $newList[$i]['leave_id']=$leave_id;
                    $newList[$i]['e_id']=$e_id;
                    $newList[$i]['status']=$status;
                    $i++;
                }
            }
            if(empty($newList)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Fields Found'); 
                return $this->redirect($url);
            }
//            echo "<pre>"; print_r($newList); die;
            foreach($newList as $new){
                $leave_id = $new['leave_id'];
                $e_id = $new['e_id'];
                $status = $new['status'];
                $approved_by = Yii::$app->user->identity->e_id;
                $param_type =$param_entry_date=$param_entry_time=$param_exit_time=$param_reason= $param_approved_on=$param_other_reason= NULL;
                $result = Yii::$app->hr_utility->hr_add_update_general_form($leave_id,$e_id, $param_type, $param_entry_date,$param_entry_time,$param_exit_time, $param_reason, $param_other_reason, $status, $approved_by,$param_approved_on);
                /*
                 * Logs
                 */
                $logs['leave_id'] = $leave_id;
                $logs['type'] = $param_type;
                $logs['emp_code'] = $e_id;
                $logs['status'] = $status;
                $logs['approved_by'] = $approved_by;
                $jsonlogs = json_encode($logs);
                if($result == '3'){
                    Yii::$app->utility->activities_logs("General Form", NULL, $e_id, $jsonlogs, "No records found in Database.");
                    
                    Yii::$app->getSession()->setFlash('danger', 'No records found in Database.'); 
                    return $this->redirect($url);
                }
                Yii::$app->utility->activities_logs("General Form", NULL, $e_id, $jsonlogs, "Application(s) updated successfully.");
            }
            Yii::$app->getSession()->setFlash('success', 'Application(s) updated successfully'); 
            return $this->redirect($url);
        }
        
        $this->layout = '@app/views/layouts/admin_layout.php';
        
        $param_auth_type = $param_map_id = $param_status=$param_entry_type= NULL;
        if(Yii::$app->user->identity->role == '5'){
            $param_status = "In-Process";
        }elseif(Yii::$app->user->identity->role == '2'){
            $param_status = "Pending";
            $param_auth_type="A2";
            $param_map_id = Yii::$app->user->identity->e_id;
        }elseif(Yii::$app->user->identity->role == '4'){
            $param_status = "Pending";
            $param_auth_type="A1";
            $param_map_id = Yii::$app->user->identity->e_id;
        }
        $slips = Yii::$app->hr_utility->hr_view_general_form_detail($param_auth_type,$param_map_id,$param_status,$param_entry_type);
        return $this->render('index', ['slips'=>$slips]);
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
}