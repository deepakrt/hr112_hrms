<?php
namespace app\modules\admin\controllers;
use yii;
class MyappraiseController extends \yii\web\Controller
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
        $this->layout = '@app/views/layouts/admin_layout.php';
        
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        
        return $this->render('index', ['menuid'=>$menuid]);
    }
    
    public function actionViewemployee(){
        $mid = Yii::$app->utility->encryptString(1);
        $rdurl = Yii::$app->homeUrl."dashboard?securekey=$mid";
        if(isset($_GET['securekey']) AND !empty($_GET['securekey']) AND isset($_GET['eid']) AND !empty($_GET['eid'])){
            $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
            $e_id = Yii::$app->utility->decryptString($_GET['eid']);
            if(!empty($menuid) && !empty($e_id)){
                $info = Yii::$app->utility->get_employees($e_id);
                $role_id = Yii::$app->user->identity->role;
                
                $qualification = Yii::$app->utility->get_qualification($role_id, $e_id, NULL, NULL);
                $family_details = Yii::$app->utility->get_family_details($e_id);  
                $employee_leaves = Yii::$app->utility->get_employee_leaves($e_id);
                $this->layout = '@app/views/layouts/admin_layout.php';
                return $this->render('viewemployee', ['info'=>$info,'qualification'=>$qualification,'family_details'=>$family_details,'employee_leaves'=>$employee_leaves]);
            }else{
                return $this->redirect($rdurl);
            }
        }else{
            return $this->redirect($rdurl);
        }
    }
    
    public function actionRequestfornewmember(){    
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
//        if(Yii::$app->user->identity->role== '5' OR Yii::$app->user->identity->role== '1'){
            $this->layout = '@app/views/layouts/admin_layout.php';
            
            return $this->render('requestfornewmember', ['menuid'=>$menuid]);
//        }
//        $rdurl = Yii::$app->homeUrl."admin/myappraise?securekey=$menuid";
//        return $this->redirect($rdurl);
    }
    public function actionUpdatefamilydetails(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."admin/myappraise/requestfornewmember?securekey=$menuid";
        if(isset($_GET['securekey']) AND !empty($_GET['securekey']) AND isset($_GET['eid']) AND !empty($_GET['eid']) AND isset($_GET['efid']) AND !empty($_GET['efid'])){
            $e_id = Yii::$app->utility->decryptString($_GET['eid']);
            $efid = Yii::$app->utility->decryptString($_GET['efid']);
            if(empty($e_id) OR empty($efid) ){
                Yii::$app->getSession()->setFlash('danger', 'Invalid Params Value Found.'); 
                return $this->redirect($url);
            }
            $info = Yii::$app->utility->get_family_details($e_id);
            if(empty($info)){
                Yii::$app->getSession()->setFlash('danger', 'No Family Detail Found.'); 
                return $this->redirect($url);
            }
            $memberInfo = "";
            foreach($info as $i){
                if($i['ef_id'] == $efid AND $i['status'] == 'Unverified'){
                    $memberInfo['m_name'] = $i['m_name'];
                    $memberInfo['relation_name'] = $i['relation_name'];
                    $memberInfo['marital_status'] = $i['marital_status'];
                    $memberInfo['m_dob'] = $i['m_dob'];
                    $memberInfo['handicap'] = $i['handicap'];
                    $memberInfo['handicate_type'] = $i['handicate_type'];
                    $memberInfo['handicap_percentage'] = $i['handicap_percentage'];
                    $memberInfo['monthly_income'] = $i['monthly_income'];
                    $memberInfo['address'] = $i['address'];
                    $memberInfo['p_address'] = $i['p_address'];
                    $memberInfo['document_path'] = $i['document_path'];
                    $memberInfo['medical_benefit'] = $i['medical_benefit'];
                    $memberInfo['created_date'] = $i['created_date'];
                    $memberInfo['monthly_income'] = $i['monthly_income'];
                    $memberInfo['relation_id'] = $i['relation_id'];
                    $memberInfo['status'] = $i['status'];
                    $memberInfo['ef_id'] = $i['ef_id'];
                    $memberInfo['employee_code'] = $i['employee_code'];
                }
            }
            if(empty($memberInfo)){
                Yii::$app->getSession()->setFlash('danger', 'No Family Detail Found.'); 
                return $this->redirect($url);
            }
            $this->layout = '@app/views/layouts/admin_layout.php';
            return $this->render('updatefamilydetails', ['menuid'=>$menuid, 'memberInfo'=>$memberInfo]);
        }elseif(isset($_POST['Family']) AND !empty($_POST['Family'])){
            $post = $_POST['Family'];
            $old_status = Yii::$app->utility->decryptString($post['old_status']);
            $medical_benefit = Yii::$app->utility->decryptString($post['medical_benefit']);
            $edu_allowances = Yii::$app->utility->decryptString($post['edu_allowances']);
            $is_child_twins = Yii::$app->utility->decryptString($post['is_child_twins']);
            $status = Yii::$app->utility->decryptString($post['status']);
            $ef_id = Yii::$app->utility->decryptString($post['ef_id']);
            $employee_code = Yii::$app->utility->decryptString($post['employee_code']);
            
            if(empty($ef_id) OR empty($employee_code) OR empty($old_status) OR empty($medical_benefit) OR empty($edu_allowances) OR empty($is_child_twins) OR empty($status)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid Params Value Found.'); 
                return $this->redirect($url);
            }
            if($old_status != 'Unverified'){
                Yii::$app->getSession()->setFlash('danger', 'No Record Found.'); 
                return $this->redirect($url);
            }
            $result = Yii::$app->utility->hr_verify_family_member($ef_id, $employee_code, $status, $medical_benefit, $edu_allowances, $is_child_twins);
            /*
             * Logs
             */
            $logs['ef_id']=$ef_id;
            $logs['employee_code']=$employee_code;
            $logs['status']=$status;
            $logs['medical_benefit']=$medical_benefit;
            $logs['edu_allowances']=$edu_allowances;
            $logs['is_child_twins']=$is_child_twins;
            $jsonlogs = json_encode($logs);
            if($result == '1'){
                $msg = "Member Details Updated Successfully.";
                Yii::$app->getSession()->setFlash('success', 'Member Details Updated Successfully.'); 
            }else{
                $msg = "Application not updated. Invalid params found.";
                Yii::$app->getSession()->setFlash('danger', 'Application not updated. Invalid params found.'); 
            }
            Yii::$app->utility->activities_logs("Information", NULL, $employee_code, $jsonlogs, $msg);
            
            return $this->redirect($url);
        }else{
            Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Url');
            return $this->redirect($url);
        }
    }
    public function actionRequestforqualifcation(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        if(Yii::$app->user->identity->role== '5' OR Yii::$app->user->identity->role== '1'){
            $this->layout = '@app/views/layouts/admin_layout.php';   
            return $this->render('requestquali', ['menuid'=>$menuid]);
        }else{
            return $this->redirect(Yii::$app->homeUrl);
        }
    }
    
    public function actionUpdatequalifi(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."admin/myappraise/requestforqualifcation?securekey=$menuid";
        
        if(isset($_GET['eq_id']) AND !empty($_GET['eq_id']) AND isset($_GET['key']) AND !empty($_GET['key']) AND isset($_GET['empcode']) AND !empty($_GET['empcode'])){
            $eq_id = Yii::$app->utility->decryptString($_GET['eq_id']);
            $empcode = Yii::$app->utility->decryptString($_GET['empcode']);
            $status = base64_decode($_GET['key']);
            
            if(empty($eq_id) OR empty($empcode) OR empty($status)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Url');
                return $this->redirect($url);
            }
            if($status == '1'){
                $status = "Verified";
            }elseif($status == '2'){
                $status = "Rejected";
            }else{
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Status');
                return $this->redirect($url);
            }
            $result = Yii::$app->utility->verify_qualification($eq_id, $empcode,$status);
            if($result == '1'){
                Yii::$app->getSession()->setFlash('success', 'Successfully updated');
                return $this->redirect($url);
            }else{
                Yii::$app->getSession()->setFlash('danger', 'Qualifcation not updated. Contact Admin');
                return $this->redirect($url);
            }
        }
        Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Url');
        return $this->redirect($url);
    }
}

