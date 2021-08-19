<?php

namespace app\modules\hr\controllers;
use yii;
class RequestceaController extends \yii\web\Controller
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
        if(Yii::$app->user->identity->role == '5'){
            $cea_reqs = Yii::$app->hr_utility->hr_get_edu_allowance_claim(NULL, NULL,NULL, "Submitted,In-Process");
        }elseif(Yii::$app->user->identity->role == '6'){
            $cea_reqs = Yii::$app->hr_utility->hr_get_edu_allowance_claim(NULL, NULL,NULL, "Approved");
        }
        
        
        return $this->render('index', ['menuid'=>$menuid, 'cea_reqs'=>$cea_reqs]);
    }
    
    public function actionViewapp(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."hr/requestcea?securekey=$menuid";
        if(isset($_POST) AND !empty($_POST)){
            if(Yii::$app->user->identity->role == '5' OR Yii::$app->user->identity->role == '6'){
                
            if(isset($_POST['fy']) AND !empty($_POST['fy']) AND isset($_POST['ea_id']) AND !empty($_POST['ea_id']) AND isset($_POST['ec']) AND !empty($_POST['ec']) AND isset($_POST['id']) AND !empty($_POST['id']) AND isset($_POST['claim_type']) AND !empty($_POST['claim_type']) AND isset($_POST['can_sanc_id']) AND !empty($_POST['can_sanc_id']) AND isset($_POST['sanc_amt']) AND !empty($_POST['sanc_amt']) AND isset($_POST['action_type']) AND !empty($_POST['action_type']) AND isset($_POST['remarks']) AND !empty($_POST['remarks'])){
                
                $fy = Yii::$app->utility->decryptString($_POST['fy']);
                $ea_id = Yii::$app->utility->decryptString($_POST['ea_id']);
                $ec = Yii::$app->utility->decryptString($_POST['ec']);
                $id = Yii::$app->utility->decryptString($_POST['id']);
                $claim_type = Yii::$app->utility->decryptString($_POST['claim_type']);
                $action_type = Yii::$app->utility->decryptString($_POST['action_type']);
                $can_sanc_id = Yii::$app->utility->decryptString($_POST['can_sanc_id']);
                
                $hr_remarks = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $_POST['remarks']));
                
                
                if(empty($fy) OR empty($ea_id) OR empty($ec) OR empty($id) OR empty($claim_type) OR empty($action_type) OR empty($can_sanc_id)){ 
                    Yii::$app->getSession()->setFlash('danger', 'Invalid params value found.'); 
                    return $this->redirect($url);
                }
                
                $fyr = $hrr = "";
                $finance_approved_by = $hr_approved_by = NULL;
                if(Yii::$app->user->identity->role == '5'){
                    $hrr = $hr_remarks;
                    $hr_approved_by = Yii::$app->user->identity->e_id;
                    $sanc_amt = trim(preg_replace('/[^0-9]/', '', $_POST['sanc_amt']));
                }elseif(Yii::$app->user->identity->role == '6'){
                    $fyr = $hr_remarks;
                    $finance_approved_by = Yii::$app->user->identity->e_id;
                    $sanc_amt = Yii::$app->utility->decryptString($_POST['sanc_amt']); 
                    $chkamt = Yii::$app->utility->decryptString($_POST['keytype']); 
                    if($chkamt != $sanc_amt){
                        Yii::$app->getSession()->setFlash('danger', "Sanctioned Amount cannot be change."); 
                        return $this->redirect($url);
                    }
                    $hr_approved_by = Yii::$app->utility->decryptString($_POST['hab']);
                    $hrr = Yii::$app->utility->decryptString($_POST['habr']);
                    if(empty($hr_approved_by) OR empty($hrr)){ 
                        Yii::$app->getSession()->setFlash('danger', 'Invalid params value found.'); 
                        return $this->redirect($url);
                    }
                }
                if($sanc_amt > $can_sanc_id){
                    Yii::$app->getSession()->setFlash('danger', "Sanctioned amount cannot greater then entitled amount i.e. Rs. $can_sanc_id/-."); 
                    return $this->redirect($url);
                }
                $result = Yii::$app->hr_utility->hr_add_update_edu_allowance_claim("U", $id, $ea_id, $ec, $fy, NULL, NULL, NULL, NULL, NULL, NULL, $claim_type, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, $action_type, $hrr, $fyr, $sanc_amt, $hr_approved_by, $finance_approved_by);
                
                /*
                 * Logs
                 */
                $logs['role_id']=Yii::$app->user->identity->role;
                $logs['action_type']="U";
                $logs['id']=$id;
                $logs['ea_id']=$ea_id;
                $logs['employee_code']=$ec;
                $logs['financial_year']=$fy;
                $logs['claim_type']=$claim_type;
                $logs['status']=$action_type;
                $logs['hr_remarks']=$hr_remarks;
                $logs['hr_approved_by']=$hr_approved_by;
                $logs['finance_approved_by']=$finance_approved_by;
                $jsonlogs = json_encode($logs);
                if($result == '2'){
                    $msg = "CEA request $action_type Successfully.";
                    Yii::$app->getSession()->setFlash('success', $msg);
                }else{
                    $msg = "CEA request not updated. Contact Admin.";
                    Yii::$app->getSession()->setFlash('danger', $msg);
                }
                Yii::$app->utility->activities_logs("Reimbursement", NULL, $ec, $jsonlogs, $msg);
                return $this->redirect($url);
            }else{
                Yii::$app->getSession()->setFlash('danger', 'Invalid params found.'); 
            }
            } // role check
        }
        
        if(isset($_GET['ea_id']) AND !empty($_GET['ea_id']) AND isset($_GET['ec']) AND !empty($_GET['ec']) AND isset($_GET['fy']) AND !empty($_GET['fy'])){
            $ea_id = Yii::$app->utility->decryptString($_GET['ea_id']);
            $ec = Yii::$app->utility->decryptString($_GET['ec']);
            $fy = Yii::$app->utility->decryptString($_GET['fy']);
            if(empty($ea_id) OR empty($ec) OR empty($fy)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid params value found'); 
                return $this->redirect($url);
            }
            $this->layout = '@app/views/layouts/admin_layout.php';
            if(Yii::$app->user->identity->role == '5'){
                $cinfo = Yii::$app->hr_utility->hr_get_edu_allowance_claim($ea_id, $ec,$fy, "Submitted,In-Process");
            }elseif(Yii::$app->user->identity->role == '6'){
                $cinfo = Yii::$app->hr_utility->hr_get_edu_allowance_claim($ea_id, $ec,$fy, "Approved");
            }
            
            if(empty($cinfo)){ 
                Yii::$app->getSession()->setFlash('danger', 'Action has been taken again CEA Application.'); 
                return $this->redirect($url);
            }
            $emp = Yii::$app->utility->get_employees($ec);
            
            return $this->render('viewapp', ['menuid'=>$menuid, 'cinfo'=>$cinfo, 'emp'=>$emp]);
        }else{
            Yii::$app->getSession()->setFlash('danger', 'Invalid params found'); 
            return $this->redirect($url);
        }
        
    }
}