<?php

namespace app\modules\employee\controllers;
use yii;
class ReimbursementController extends \yii\web\Controller
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
        return $this->render('index');    
    }
    
    public function actionOpd() {
        $this->layout = '@app/views/layouts/admin_layout.php';
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $fn = $fnyr = $selected="";
        $url = Yii::$app->homeUrl."employee/reimbursement/opd?securekey=$menuid";
//        $fnYears = Yii::$app->finance->financialYrListFromJoining();
//        $currentFn=$fnYears[0];
        if(isset($_GET['fn']) AND !empty($_GET['fn'])){
            $fn = base64_decode($_GET['fn']);
            if(empty($fn)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid Financial Year Found.'); 
                return $this->redirect($url);
            }
            $selected="selected=selected";
            $fnyr=  base64_encode($fn);
        }
        //Entitlement Details
        $entitle = $currentFn = "";
        $AllEntitle = Yii::$app->finance->fn_get_medical_entitlement(Yii::$app->user->identity->e_id, NULL);
        if(!empty($AllEntitle)){
            if(isset($_GET['fn']) AND !empty($_GET['fn'])){
                $entitle = Yii::$app->finance->fn_get_medical_entitlement(Yii::$app->user->identity->e_id, $fn);
                $currentFn = $entitle['session_year'];
            }else{
                $currentFn = $AllEntitle[0]['session_year'];
                $entitle = Yii::$app->finance->fn_get_medical_entitlement(Yii::$app->user->identity->e_id, $AllEntitle[0]['entitle_id']);
            }
        }
//        echo "<pre>";print_r($entitle); die;
        if(!empty($entitle)){
            $yrEntitle = $entitle['yearly_entitlement'];
            $carryFwd = $entitle['carry_forward_balance'];
            $exEntitle = $entitle['excess_entitlement'];
            $deduction = $entitle['deduction_from_entitlement'];
            $totalEntitle = ($entitle['yearly_entitlement']+$entitle['carry_forward_balance']+$entitle['excess_entitlement'])-$entitle['deduction_from_entitlement'];
            $entitle['totalentitle'] = $totalEntitle;
            $totaluti = $entitle['utilized']-$entitle['recovery_amt'];
            $entitle['totaluti'] = $totaluti;
            $entitle['clearbalance'] = $totalEntitle-$totaluti;
        }else{
            Yii::$app->getSession()->setFlash('danger', 'No Medical Entitlement found. Contact Admin.'); 
        }
        
        return $this->render('opd', [
            'menuid'=>$menuid, 
            'fnyr'=>$fnyr, 
            'currentFn'=>$currentFn, 
            'AllEntitle'=>$AllEntitle, 
            'selected'=>$selected,
            'entitle'=>$entitle,
        ]);
    }
    
    public function actionApplynewclaim(){
        $this->layout = '@app/views/layouts/admin_layout.php';
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."employee/reimbursement/opd?securekey=$menuid";
        if(isset($_GET['entitleid']) AND !empty($_GET['entitleid'])){
            $Singlebill = $billid = $opdid = $opdid1 = $billDetails = "";
            $entitleid = Yii::$app->utility->decryptString($_GET['entitleid']);
            if(empty($entitleid)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid ID Found.'); 
                return $this->redirect($url);
            }
//            echo "<pre>";print_r($_GET); die;
            $entitleid = Yii::$app->utility->encryptString($entitleid);
            if(isset($_GET['opdid']) AND !empty($_GET['opdid'])){
                $opdid = Yii::$app->utility->decryptString($_GET['opdid']);
                if(empty($opdid)){
                    $url = Yii::$app->homeUrl."employee/reimbursement/opd?securekey=$menuid&entitleid=$entitleid";
                    Yii::$app->getSession()->setFlash('danger', 'Invalid OPD ID Found.'); 
                    return $this->redirect($url);
                }
                $opdid1 = Yii::$app->utility->encryptString($opdid);
                $billDetails = Yii::$app->finance->fn_get_opd_bill_details($opdid, Yii::$app->user->identity->e_id, NULL);
                if(isset($_GET['billid']) AND !empty($_GET['billid'])){
                    $billid = Yii::$app->utility->decryptString($_GET['billid']);
                    if(empty($billid)){
                        $url = Yii::$app->homeUrl."employee/reimbursement/opd?securekey=$menuid&entitleid=$entitleid&opdid=$opdid1";
                        Yii::$app->getSession()->setFlash('danger', 'Invalid Bill ID Found.'); 
                        return $this->redirect($url);
                    }
                    
                    $Singlebill = Yii::$app->finance->fn_get_opd_bill_details($opdid, Yii::$app->user->identity->e_id, $billid);
                    $billid = Yii::$app->utility->encryptString($billid);
                }
            }
            $id = Yii::$app->utility->decryptString($entitleid);
            $detail = Yii::$app->finance->fn_get_medical_entitlement(Yii::$app->user->identity->e_id, $id);
//            $curFy= Yii::$app->finance->getCurrentFY();
//            if($curFy != $detail['session_year']){
//                Yii::$app->getSession()->setFlash('danger', 'You can only apply claim in current Financial Year.'); 
//                return $this->redirect($url);
//            }
            return $this->render('applynewclaim', ['menuid'=>$menuid, 'entitleid'=>$entitleid, 'billDetails'=>$billDetails, 'Singlebill'=>$Singlebill, 'opdid'=>$opdid1, 'billid'=>$billid, 'detail'=>$detail]);
        }else{
            Yii::$app->getSession()->setFlash('danger', 'Invalid Params Found.'); 
            return $this->redirect($url);
        }
    }
    
    public function actionGet_dependent_family(){
        $data = Yii::$app->utility->get_family_details(Yii::$app->user->identity->e_id);
        $result['Status']='FF';
        $result['Res']='<option value="" disabled="disabled">No Family Record Found</option>';
        $html = "";
        if(!empty($data)){
            $result['Status']='SS';
            foreach($data as $d){
                if($d['status'] == 'Verified'){
                    $id = base64_encode($d['ef_id']);
                    $n = ucfirst($d['m_name']);
                    $html = $html."<option value='$id'>$n</option>";
                }
            }
            $result['Res']=$html;
        }
        echo json_encode($result);
        die;
    }
    
    public function actionSaveclaim(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."employee/reimbursement/applynewclaim?securekey=$menuid";
        if(isset($_POST['Opt']) AND !empty($_POST['Opt'])){
//            echo "<pre>";print_r($_POST); die;
            $dependent_id=NULL;
            $post = $_POST['Opt'];
            $entitleid = Yii::$app->utility->decryptString($post['entitleid']);
            if(empty($entitleid)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid Entitle ID Found.'); 
                return $this->redirect($url);
            }
            $entitleid1 = Yii::$app->utility->encryptString($entitleid);
            $url = Yii::$app->homeUrl."employee/reimbursement/applynewclaim?securekey=$menuid&entitleid=$entitleid1";
            if($post['patient'] == '1'){
                $patient_type = "S";
            }elseif($post['patient'] == '2'){
                $patient_type = "D";
                $dependent_id = base64_decode($post['dependent_id']);
            }else{
                Yii::$app->getSession()->setFlash('danger', 'Invalid Patient Type.'); 
                return $this->redirect($url);
            }
            $bill_id = $opd_id = NULL;
            if(!empty($post['opd_id'])){
                $opd_id = Yii::$app->utility->decryptString($post['opd_id']);
                if(empty($opd_id)){
                    Yii::$app->getSession()->setFlash('danger', 'Invalid OPD ID Found.'); 
                    return $this->redirect($url);
                }
            }
            if(!empty($post['bill_id'])){
                $bill_id = Yii::$app->utility->decryptString($post['bill_id']);
                if(empty($bill_id)){
                    Yii::$app->getSession()->setFlash('danger', 'Invalid Bill ID Found.'); 
                    return $this->redirect($url);
                }
            }
            $bill_no = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['bill_no']));
            $bill_date = date('Y-m-d', strtotime($post['bill_date']));
            $bill_amount = trim(preg_replace('/[^0-9]/', '', $post['bill_amount']));
            $bill_type = base64_decode($post['bill_type']);
            $issuer = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['issuer']));
            $e_code =Yii::$app->user->identity->e_id;
            $lastOpdId = Yii::$app->finance->fn_add_opd_claims($bill_id, $opd_id, $entitleid, $e_code, 'Draft', $patient_type, $dependent_id, $bill_no, $bill_date, $bill_amount, $bill_type, $issuer);
            
            /*
             * Logs
             */
            $logs['bill_id']=$bill_id;
            $logs['opd_id']=$opd_id;
            $logs['entitleid']=$entitleid;
            $logs['emp_code']=$e_code;
            $logs['status']="Draft";
            $logs['patient_type']=$patient_type;
            $logs['dependent_id']=$dependent_id;
            $logs['bill_no']=$bill_no;
            $logs['bill_date']=$bill_date;
            $logs['bill_amount']=$bill_amount;
            $logs['bill_type']=$bill_type;
            $logs['issuer']=$issuer;
            $jsonlogs = json_encode($logs);
            if(!empty($lastOpdId)){
                $lastOpdId = Yii::$app->utility->encryptString($lastOpdId);
                if(!empty($opd_id)){
                    $lastOpdId = Yii::$app->utility->encryptString($opd_id);
                }
                $url = Yii::$app->homeUrl."employee/reimbursement/applynewclaim?securekey=$menuid&entitleid=$entitleid1&opdid=$lastOpdId";
                Yii::$app->utility->activities_logs("Reimbursement", NULL, NULL, $jsonlogs, "OPD Claim Saved Succesfully");
                
                Yii::$app->getSession()->setFlash('success', 'Claim Saved Succesfully.'); 
                return $this->redirect($url);
            }else{
                Yii::$app->utility->activities_logs("Reimbursement", NULL, NULL, $jsonlogs, "OPD Claim not Saved. Contact Admin.");
                
                Yii::$app->getSession()->setFlash('danger', 'Claim not Saved. Contact Admin.'); 
                return $this->redirect($url);
            }
        }
    }
    
    public function actionSubmitclaimopd(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."employee/reimbursement/opd?securekey=$menuid";
        if(isset($_GET['opd_id']) AND !empty($_GET['opd_id']) AND isset($_GET['entitleid']) AND !empty($_GET['entitleid'])){
            $opd_id = Yii::$app->utility->decryptString($_GET['opd_id']);
            $entitleid = Yii::$app->utility->decryptString($_GET['entitleid']);
            if(empty($entitleid) OR empty($opd_id)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid param found.'); 
                return $this->redirect($url);
            }
            $e_code =Yii::$app->user->identity->e_id;
            $result = Yii::$app->finance->fn_add_opd_claims(NULL, $opd_id, $entitleid, $e_code, 'Submitted', NULL, NULL, NULL, NULL, NULL, NULL, NULL);
            /*
             * Logs
             */
            $logs['opd_id']=$opd_id;
            $logs['entitleid']=$entitleid;
            $logs['emp_code']=$e_code;
            $logs['status']="Submitted";
            $jsonlogs = json_encode($logs);
            if($result == '2'){
                Yii::$app->utility->activities_logs("Reimbursement", NULL, NULL, $jsonlogs, "OPD Claim Submitted Successfully.");
                
                Yii::$app->getSession()->setFlash('success', 'Claim Submitted Successfully.'); 
                return $this->redirect($url);
            }else{
                Yii::$app->utility->activities_logs("Reimbursement", NULL, NULL, $jsonlogs, "Claim Not Submitted. Contact Admin.");
                Yii::$app->getSession()->setFlash('danger', 'Claim Not Submitted. Contact Admin.'); 
                return $this->redirect($url);
            }
        }
        Yii::$app->getSession()->setFlash('danger', 'Invalid param found.'); 
        return $this->redirect($url);
    }
    
    public function actionDeletebill(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."employee/reimbursement/opd?securekey=$menuid";
        if(isset($_GET['entitleid']) AND !empty($_GET['entitleid']) AND isset($_GET['opdid']) AND !empty($_GET['opdid']) AND isset($_GET['billid']) AND !empty($_GET['billid'])){
            $entitleid = Yii::$app->utility->decryptString($_GET['entitleid']);
            $opdid = Yii::$app->utility->decryptString($_GET['opdid']);
            $billid = Yii::$app->utility->decryptString($_GET['billid']);
            if(empty($billid) OR empty($opdid) OR empty($entitleid)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid param found.'); 
                return $this->redirect($url);
            }
            $e_code =Yii::$app->user->identity->e_id;
            $result = Yii::$app->finance->fn_delete_opd_bill($entitleid, $opdid, $billid, $e_code, "Bill");
            
            /*
             * Logs
             */
            $logs['entitleid']=$entitleid;
            $logs['opdid']=$opdid;
            $logs['billid']=$billid;
            $logs['e_code']=$e_code;
            $logs['delete_type']="Bill";
            $jsonlogs = json_encode($logs);
            
            $entitleid = Yii::$app->utility->encryptString($entitleid);
            $opdid = Yii::$app->utility->encryptString($opdid);
            
            $url = Yii::$app->homeUrl."employee/reimbursement/applynewclaim?securekey=$menuid&entitleid=$entitleid&opdid=$opdid";
            if($result == '1'){
                
                Yii::$app->utility->activities_logs("Reimbursement", NULL, NULL, $jsonlogs, "OPD Bill Deleted Successfully.");
                
                Yii::$app->getSession()->setFlash('success', 'Bill Deleted Successfully.'); 
                return $this->redirect($url);
            }else{
                Yii::$app->utility->activities_logs("Reimbursement", NULL, NULL, $jsonlogs, "OPD Bill Has Not Deleted. Contact Admin.");
                
                Yii::$app->getSession()->setFlash('danger', 'Bill Has Not Deleted. Contact Admin.'); 
                return $this->redirect($url);
            }
        }
        Yii::$app->getSession()->setFlash('danger', 'Invalid Url Params Found.'); 
        return $this->redirect($url);
    }
    public function actionDeleteclaim(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."employee/reimbursement/opd?securekey=$menuid";
        if(isset($_GET['entitleid']) AND !empty($_GET['entitleid']) AND isset($_GET['opdid']) AND !empty($_GET['opdid'])){
            $entitleid = Yii::$app->utility->decryptString($_GET['entitleid']);
            $opdid = Yii::$app->utility->decryptString($_GET['opdid']);
            if(empty($opdid) OR empty($entitleid)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid param found.'); 
                return $this->redirect($url);
            }
            $e_code =Yii::$app->user->identity->e_id;
            $result = Yii::$app->finance->fn_delete_opd_bill($entitleid, $opdid, NULL, $e_code, "Claim");
            /*
             * Logs
             */
            $logs['entitleid']=$entitleid;
            $logs['opdid']=$opdid;
            $logs['bill_id']=NULL;
            $logs['e_code']=$e_code;
            $logs['delete_type']="Claim";
            $jsonlogs = json_encode($logs);
            
            $url = Yii::$app->homeUrl."employee/reimbursement/opd?securekey=$menuid";
            if($result == '1'){
                Yii::$app->utility->activities_logs("Reimbursement", NULL, NULL, $jsonlogs, "OPD Claim Deleted Successfully.");
                
                Yii::$app->getSession()->setFlash('success', 'Claim Deleted Successfully.'); 
                return $this->redirect($url);
            }else{
                Yii::$app->utility->activities_logs("Reimbursement", NULL, NULL, $jsonlogs, "OPD Claim Has Not Deleted. Contact Admin.");
                Yii::$app->getSession()->setFlash('danger', 'Claim Has Not Deleted. Contact Admin.'); 
                return $this->redirect($url);
            }
        }
        Yii::$app->getSession()->setFlash('danger', 'Invalid Url Params Found.'); 
        return $this->redirect($url);
    }
    
    public function actionClaimdetails(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."employee/reimbursement/opd?securekey=$menuid";
//        echo "<pre>";print_r($_GET); die;
        if(isset($_GET['entitle_id']) AND !empty($_GET['entitle_id']) AND isset($_GET['opd_id']) AND !empty($_GET['opd_id'])){
            $entitleid = Yii::$app->utility->decryptString($_GET['entitle_id']);
            $opdid = Yii::$app->utility->decryptString($_GET['opd_id']);
            if(empty($opdid) OR empty($entitleid)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid param found.'); 
                return $this->redirect($url);
            }
            $e_code =Yii::$app->user->identity->e_id;
            $billDetails = Yii::$app->finance->fn_get_opd_bill_details($opdid, $e_code, NULL);
            $this->layout = '@app/views/layouts/admin_layout.php';
            $submittedClaims = Yii::$app->finance->fn_get_opd_claims($opdid, $entitleid, $e_code, 'Submitted,In-Process,Sanctioned,Rejected');
            return $this->render('claimdetails', ['menuid'=>$menuid, 'billDetails'=>$billDetails, 'submittedClaims'=>$submittedClaims]);
        }
        Yii::$app->getSession()->setFlash('danger', 'Invalid Url Params Found.'); 
        return $this->redirect($url);
    }
    
//    public function actionIpd(){
//        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
//        $menuid = Yii::$app->utility->encryptString($menuid);
//        $this->layout = '@app/views/layouts/admin_layout.php';
//        return $this->render('ipd', ['menuid'=>$menuid]);
//    }
    public function actionCeas(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."employee/reimbursement/ceas?securekey=$menuid";
        //$fnYears = Yii::$app->finance->financialYrListFromJoining();
        
        $fnyr = Yii::$app->utility->get_emp_allowance(Yii::$app->user->identity->desg_id, NULL, NULL);
        $childs = $allowances = $fnYears = $selectfnyr = $newfnyr = "";
        if(!empty($fnyr)){
            foreach($fnyr as $f){
                $newfnyr[] = $f['financial_yr'];
            }
            $fnYears = array_unique($newfnyr);
            $selectfnyr = $fnYears[0];
        }
        
        if(!empty($fnYears)){
            if(isset($_GET['ceaFnYr']) AND !empty($_GET['ceaFnYr'])){
                $selectfnyr = Yii::$app->utility->decryptString($_GET['ceaFnYr']);
    //            echo $selectfnyr; die;
                if(empty($selectfnyr)){
                    Yii::$app->getSession()->setFlash('danger', 'Invalid Url param value Found.'); 
                    return $this->redirect($url);
                }
            }
            //die($selectfnyr);
            $allowances = Yii::$app->utility->get_emp_allowance(Yii::$app->user->identity->desg_id, Yii::$app->user->identity->employmenttype, $selectfnyr);

            $fmember = Yii::$app->utility->get_family_details(Yii::$app->user->identity->e_id);
    //        echo "<pre>";print_r($fmember);
            $childs = "";
            if(!empty($fmember)){
                $i=0;
                foreach($fmember as $f){
                    if($f['edu_allowances'] == 'Y'){
                        $checkexit = Yii::$app->hr_utility->hr_get_CEA_child_details(Yii::$app->user->identity->e_id,$f['ef_id'], $selectfnyr, NULL);
                        if(empty($checkexit)){
                            $childs[$i]['ef_id'] = $f['ef_id'];
                            $childs[$i]['m_name'] = $f['m_name'];
                            $childs[$i]['m_dob'] = $f['m_dob'];
                            $childs[$i]['relation_name'] = $f['relation_name'];
                            $i++;
                        }
                    }
                }
            }
        }
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('ceas', ['menuid'=>$menuid, 'fnYears'=>$fnYears, 'selectfnyr'=>$selectfnyr, 'allowances'=>$allowances, 'childs'=>$childs]);
    }
    
    public function actionEduallowancechilddetails(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."employee/reimbursement/ceas?securekey=$menuid";
        if(isset($_POST['Edu']) AND !empty($_POST['Edu'])){
            $post = $_POST['Edu'];
            $newarray= "";
            $i=0;
            foreach($post as $p){
                if(isset($p['ef_id']) AND !empty($p['ef_id'])){
                    $ef_id= Yii::$app->utility->decryptString($p['ef_id']);
                    $class_std = Yii::$app->utility->decryptString($p['class_std']);
                    $fnyr = Yii::$app->utility->decryptString($p['fnyr']);
                    
                    if(empty($ef_id) OR empty($class_std) OR empty($fnyr)){
                        Yii::$app->getSession()->setFlash('danger', 'Invalid param value found.'); 
                        return $this->redirect($url);
                    }
                    $chkfy = explode("-", $fnyr);
                    $y1 = $chkfy[0].'-04-01';
                    $y2 = $chkfy[1].'-03-31';
                    $yr1 = strtotime($y1);
                    $yr2 = strtotime($y2);
                    
                    
                    $ay_start = strtotime($p['ay_start']);
                    $ay_end = strtotime($p['ay_end']);
                    if(($ay_start < $yr1) OR ($ay_start > $yr2)){
                        Yii::$app->getSession()->setFlash('danger', "AY Start / AY End Date should be select from financial year $fnyr"); 
                        return $this->redirect($url);
                    }elseif($ay_end > $yr2){
                        Yii::$app->getSession()->setFlash('danger', "AY Start / AY End Date should be select from financial year $fnyr"); 
                        return $this->redirect($url);
                    }
                    
                    if($ay_start > $ay_end){
                        Yii::$app->getSession()->setFlash('danger', 'AY Start date cannot greater then AY End date.'); 
                        return $this->redirect($url);
                    }
                    $ay_start = date('Y-m-d', strtotime($p['ay_start']));
                    $ay_end = date('Y-m-d', strtotime($p['ay_end']));
                    $school = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $p['school']));
                    
                    $newarray[$i]['ef_id']= $ef_id;
                    $newarray[$i]['class_std']= $class_std;
                    $newarray[$i]['school']= $school;
                    $newarray[$i]['ay_start']= $ay_start;
                    $newarray[$i]['ay_end']= $ay_end;
                    $newarray[$i]['fnyr']= $fnyr;
                    $i++;
                }
            }
            if(empty($newarray)){
                Yii::$app->getSession()->setFlash('danger', 'Child / Children Academic Not Found.'); 
                return $this->redirect($url);
            }
            
            foreach($newarray as $n){
                $result = Yii::$app->hr_utility->hr_add_update_CEA_child_details(NULL, Yii::$app->user->identity->e_id, $n['ef_id'], $n['class_std'], $n['school'], $n['ay_start'], $n['ay_end'], $n['fnyr']);
                /*
                 * Logs
                 */
                $logs['ea_id'] = NULL;
                $logs['employee_code'] = Yii::$app->user->identity->e_id;
                $logs['ef_id'] = $n['ef_id'];
                $logs['class_std'] = $n['class_std'];
                $logs['school'] = $n['school'];
                $logs['ay_start'] = $n['ay_start'];
                $logs['ay_end'] = $n['ay_end'];
                $logs['fnyr'] = $n['fnyr'];
                $jsonlogs = json_encode($logs);
                if($result == '3'){
                    $msg = "Child / Children Details Already Exits";
                    Yii::$app->utility->activities_logs("Reimbursement", NULL, NULL, $jsonlogs, "$msg");
                            
                    Yii::$app->getSession()->setFlash('danger', $msg); 
                    return $this->redirect($url);
                }elseif($result == '1'){
                    $msg = "Child / Children Details Added Succesfully.";
                    Yii::$app->utility->activities_logs("Reimbursement", NULL, NULL, $jsonlogs, "$msg");
                            
                    Yii::$app->getSession()->setFlash('success', $msg); 
                    return $this->redirect($url);
                }else{
                    $msg = "Child / Children Details Not added Contact Admin.";
                    Yii::$app->utility->activities_logs("Reimbursement", NULL, NULL, $jsonlogs, "$msg");
                    Yii::$app->getSession()->setFlash('danger', $msg); 
                    return $this->redirect($url);
                }
            }
        }else{
            Yii::$app->getSession()->setFlash('danger', 'Invalid params found.'); 
            return $this->redirect($url);
        }
    }
    
    public function actionDeletechilddetail(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."employee/reimbursement/ceas?securekey=$menuid";
        if(isset($_GET['ea_id']) AND !empty($_GET['ea_id']) AND isset($_GET['fy']) AND !empty($_GET['fy'])){
            $ea_id = Yii::$app->utility->decryptString($_GET['ea_id']);
            $fy = Yii::$app->utility->decryptString($_GET['fy']);
            
            if(empty($ea_id) OR empty($fy)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid params value found.'); 
                return $this->redirect($url);
            }
            $result = Yii::$app->hr_utility->hr_add_update_CEA_child_details($ea_id, Yii::$app->user->identity->e_id, NULL, NULL, NULL, NULL, NULL, $fy);
            /*
             * Logs
             */
            $logs['ea_id']=$ea_id;
            $logs['employee-code']=Yii::$app->user->identity->e_id;
            $logs['financial_year']=$fy;
            $jsonlogs = json_encode($logs);
            if($result == '3'){
                $msg = "Already Claimed. Child / Children Details Cannot be Deleted.";
                Yii::$app->getSession()->setFlash('danger', $msg); 
            }elseif($result == '2'){
                $msg = "Child Details Deleted Successfully.";
                Yii::$app->getSession()->setFlash('success', $msg); 
            }else{
                $msg = "Child Not Deleted. Contact Admin.";
                Yii::$app->getSession()->setFlash('danger', $msg); 
            }
            Yii::$app->utility->activities_logs("Reimbursement", NULL, NULL, $jsonlogs, "$msg");
            return $this->redirect($url);
        }
        Yii::$app->getSession()->setFlash('danger', 'Invalid params found.'); 
        return $this->redirect($url);
        
    }
    
    public function actionClaimcea(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."employee/reimbursement/ceas?securekey=$menuid";
        
        if(isset($_POST['CEAClaim']) AND !empty($_POST['CEAClaim'])){
            $post = $_POST['CEAClaim'];
            $ea_id = Yii::$app->utility->decryptString($post['ea_id']);
            $fy = Yii::$app->utility->decryptString($post['fy']);
            $ct = Yii::$app->utility->decryptString($post['ct']);
            
            if(empty($ea_id) OR empty($fy) OR empty($ct)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid params value found.'); 
                return $this->redirect($url);
            }
            
            $Emp_Allowances = Emp_Allowances;
            $check_ct = false;
            foreach($Emp_Allowances as $e){
                if($e['shortname']==$ct){
                    $check_ct = true;
                }
            }
            if(empty($check_ct)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid Claim Type.'); 
                return $this->redirect($url);
            }
            $action_type = "I";
            $emp_remarks = $doc_path = $doc_type = $hostel_fees = $tuition_fees =  $shoes_amount = $notebooks = $uniform_amount = $books_amount = NULL;
            $nullvalue  = NULL;
            if($ct == 'CEA'){
                $books_amount = trim(preg_replace('/[^0-9.-]/', '', $post['books_amount']));
                $shoes_amount = trim(preg_replace('/[^0-9.-]/', '', $post['shoes_amount']));
                $notebooks = trim(preg_replace('/[^0-9.-]/', '', $post['notebooks']));
                $uniform_amount = trim(preg_replace('/[^0-9.-]/', '', $post['uniform_amount']));
                $tuition_fees = trim(preg_replace('/[^0-9.-]/', '', $post['tuition_fees']));
            }elseif ($ct == 'HS') {
                $hostel_fees = trim(preg_replace('/[^0-9.-]/', '', $post['hostel_fees']));
            }else{
                Yii::$app->getSession()->setFlash('danger', 'Invalid Claim Type.'); 
                return $this->redirect($url);
            }
            $emp_remarks = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['emp_remarks']));
            
            $result = Yii::$app->hr_utility->hr_add_update_edu_allowance_claim($action_type, NULL, $ea_id, Yii::$app->user->identity->e_id, $fy, $books_amount, $shoes_amount, $notebooks, $uniform_amount, $tuition_fees, $hostel_fees, $ct, $doc_type, $doc_path, $emp_remarks, $nullvalue, $nullvalue, $nullvalue, $nullvalue, $nullvalue, $nullvalue, "Submitted ", $nullvalue, $nullvalue);
            
            /*
             * Logs
             */
            $logs['action_type']=$action_type;
            $logs['ea_id']=$ea_id;
            $logs['financial_year']=$fy;
            $logs['books_amount']=$books_amount;
            $logs['shoes_amount']=$shoes_amount;
            $logs['notebooks']=$notebooks;
            $logs['uniform_amount']=$uniform_amount;
            $logs['tuition_fees']=$tuition_fees;
            $logs['hostel_fees']=$hostel_fees;
            $logs['claim_type']=$ct;
            $logs['doc_type']=$doc_type;
            $logs['doc_path']=$doc_path;
            $logs['emp_remarks']=$emp_remarks;
            $logs['status']="Submitted";
            $jsonlogs= json_encode($logs);
            if($result == '1'){
                $msg = "Children Education Allowance Claimed Successfully.";
                Yii::$app->getSession()->setFlash('success', $msg); 
            }elseif($result == '3'){
                $msg = "$ct Claimed Already Applied.";
                Yii::$app->getSession()->setFlash('danger', $msg); 
            }elseif($result == '4'){
                $msg = "Invalid Action Type.";
                Yii::$app->getSession()->setFlash('danger', $msg); 
            }else{
                $msg = "Found Error : Claimed Not Applied. Contact Admin.";
                Yii::$app->getSession()->setFlash('danger', $msg); 
            }
            Yii::$app->utility->activities_logs("Reimbursement", NULL, NULL, $jsonlogs, $msg);
            return $this->redirect($url);
        }
        
        if(isset($_GET['ea_id']) AND !empty($_GET['ea_id']) AND isset($_GET['fy']) AND !empty($_GET['fy']) AND isset($_GET['ct']) AND !empty($_GET['ct'])){
            $ea_id = Yii::$app->utility->decryptString($_GET['ea_id']);
            $fy = Yii::$app->utility->decryptString($_GET['fy']);
            $ct = Yii::$app->utility->decryptString($_GET['ct']);
            
            if(empty($ea_id) OR empty($fy) OR empty($ct)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid params value found.'); 
                return $this->redirect($url);
            }
            /*
             * Check Claim Already Exits or not
             */
            $checkexit = Yii::$app->hr_utility->hr_get_edu_allowance_claim($ea_id, Yii::$app->user->identity->e_id,$fy, "Submitted,In-Process,Approved,Sanctioned");
            if(!empty($checkexit)){
                Yii::$app->getSession()->setFlash('danger', $checkexit['claim_type'].' Already Claimed.'); 
                return $this->redirect($url);
            }
            
            $Emp_Allowances = Emp_Allowances;
            $check_ct = false;
            $cType="";
            foreach($Emp_Allowances as $e){
                if($e['shortname']==$ct){
                    $check_ct = true;
                    $cType = $e['name'];
                }
            }
            if(empty($check_ct)){
                Yii::$app->getSession()->setFlash('danger', "Invalid Claim Type."); 
                return $this->redirect($url);
            }
            
            $this->layout = '@app/views/layouts/admin_layout.php';
            return $this->render('claimcea', ['menuid'=>$menuid, 'ea_id'=>$ea_id, 'fy'=>$fy, 'ct'=>$ct, 'cType'=>$cType]);
        }
        Yii::$app->getSession()->setFlash('danger', 'Invalid params found.'); 
        return $this->redirect($url);
        
        
    }
    
    public function actionViewceaapp(){
        
        if(isset($_GET['ea_id']) AND !empty($_GET['ea_id']) AND isset($_GET['fy']) AND !empty($_GET['fy'])){
            $ea_id = Yii::$app->utility->decryptString($_GET['ea_id']);
            $fy = Yii::$app->utility->decryptString($_GET['fy']);
            
            if(empty($ea_id) AND !empty($fy)){
                $result['Status']="FF";
                $result['Res']="Invalid params found.";
                echo json_encode($result); die;
            }
            
            $claim = Yii::$app->hr_utility->hr_get_edu_allowance_claim($ea_id, Yii::$app->user->identity->e_id,$fy, "Submitted,In-Process,Approved,Rejected,Sanctioned");
            if(empty($claim)){
                $result['Status']="FF";
                $result['Res']="No Record Found.";
                echo json_encode($result); die;
            }
            $relation_name = $claim['relation_name'];
            $status = $claim['status'];
            $m_name = $claim['m_name'];
            $class_std = $claim['class_std'];
            $school_name = $claim['school_name'];
            $m_dob = date('d-m-Y', strtotime($claim['m_dob']));
            $ay_start = date('d-m-Y', strtotime($claim['ay_start']));
            $ay_end = date('d-m-Y', strtotime($claim['ay_end']));
            $appdate = date('d-m-Y', strtotime($claim['created_date']));
            $claim_type="";
            $Emp_Allowances = Emp_Allowances;
            foreach($Emp_Allowances as $cl){
                if($cl['shortname'] == $claim['claim_type']){
                    $claim_type=$cl['name'];
                }
            }
            if(empty($claim_type)){
                $result['Status']="FF";
                $result['Res']="Invalid Claim Type Found. Contact Admin.";
                echo json_encode($result); die;
            }
            $html="<div class='col-sm-12'>
                    <table class='table table-bordered table-hover'>
                        <tr>
                            <td><b>Name :</b> ($relation_name) $m_name</td>
                            <td><b>Date of Birth :</b> $m_dob</td>
                            <td><b>Std :</b> $class_std</td>
                        </tr>
                        <tr>
                            <td><b>School :</b> $school_name</td>
                            <td><b>Financial Year :</b> $fy</td>
                            <td><b>AY Start :</b> $ay_start</td>
                        </tr>
                        <tr>
                            <td><b>AY End :</b> $ay_end</td>
                            <td><b>Status :</b> $status</td>
                            <td><b>Application Dated :</b> $appdate</td>
                        </tr>
                        ";
            
            if($claim['claim_type'] == 'CEA'){
                $books_amt = $claim['books_amount'];
                $shoes_amt = $claim['shoes_amount'];
                $note_amt = $claim['notebooks_amount'];
                $uniform_amt = $claim['uniform_amount'];
                $tuition = $claim['tuition_fees'];
                
                $totalClaimed = $books_amt+$shoes_amt+$note_amt+$uniform_amt+$tuition;
                $totalClaimed = number_format($totalClaimed, 2);
                
                $totalSanc = "-";
                if(empty($c['finance_approved_by'])){
                    $totalSanc = $claim['total_sanc_amt'];
                }
                
                $html .="<tr>
                            <td><b>Claim Type : </b>$claim_type</td>
                            <td><b>Total Claimed : </b>$totalClaimed</td>
                            <td><b>Total Sanctioned : </b>$totalSanc</td>
                        </tr>
                    </table>
                    </div>
                    <div class='col-sm-6'>
                        <h6><b>Details : -</b></h6>
                        <table class='table table-bordered table-hover'>
                            <tr>
                                <th></th>
                                <th>Total Claimed  (Rs.)</th>
                            </tr>
                            <tr>
                                <td><b>Purchase of Books</b></td>
                                <td align='right'>$books_amt</td>
                            </tr>
                            <tr>
                                <td><b>Purchase of Shoes</b></td>
                                <td align='right'>$shoes_amt</td>
                            </tr>
                            <tr>
                                <td><b>Purchase of Notebooks</b></td>
                                <td align='right'>$note_amt</td>
                            </tr>
                            <tr>
                                <td><b>Purchase of Uniform</b></td>
                                <td align='right'>$uniform_amt</td>
                            </tr>
                            <tr>
                                <td><b>Tuition Fees</b></td>
                                <td align='right'>$tuition</td>
                            </tr>
                        </table>
                    </div>";
            }elseif($claim['claim_type'] == 'HS'){
                $hostel_fees = $claim['hostel_fees'];
                $html .="<tr>
                            <td><b>Claim Type</b><br>$claim_type</td>
                            <td><b>Total Claimed</b><br>$hostel_fees</td>
                        </tr>
                    </table>
                    </div>
                    <div class='col-sm-12'>
                        <h6><b>Details : -</b></h6>
                        <table class='table table-bordered table-hover'>
                            <tr>
                                <th></th>
                                <th>Total Claimed  (Rs.)</th>
                            </tr>
                            <tr>
                                <td><b>Hostel Fees</b></td>
                                <td align='right'>$hostel_fees</td>
                            </tr>
                        </table>
                    </div>";
            }
            $result['Status']="SS";
            $result['Res']=$html;
            echo json_encode($result); die;
        }else{
            $result['Status']="FF";
            $result['Res']="Invalid params found.";
            echo json_encode($result); die;
        }
                    
    }
    public function actionAnnual(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $this->layout = '@app/views/layouts/admin_layout.php';
        $fyrs = Yii::$app->finance->get_ann_reim_master();
        $selectedyrs = $yrs = "";
        if(!empty($fyrs)){
            $yrs = "";
            foreach($fyrs as $f){
                if(Yii::$app->user->identity->desg_id == $f['designation_id'] AND Yii::$app->user->identity->employmenttype == $f['emp_type']){
                    $yrs[]=$f['financial_yr'];
                }
            }
            if(!empty($yrs)){
                $yrs = array_unique($yrs);
                $selectedyrs = $yrs[0];
            }
            
        }
        return $this->render('annual', ['menuid'=>$menuid, 'yrs'=>$yrs, 'selectedyrs'=>$selectedyrs]);
    }
    
    public function actionAnnreimclaim(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."employee/reimbursement/annual?securekey=$menuid";
        if(isset($_GET['key1']) AND !empty($_GET['key1']) AND isset($_GET['key2']) AND !empty($_GET['key2'])){
            $ann_reim_id = Yii::$app->utility->decryptString($_GET['key1']);
            $financial_yr = Yii::$app->utility->decryptString($_GET['key2']);
            if(empty($ann_reim_id) OR empty($financial_yr)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid params value found.'); 
                return $this->redirect($url);
            }
            $master_info = Yii::$app->finance->get_ann_reim_master($ann_reim_id);
            if(!empty($master_info)){
                if($master_info['designation_id'] == Yii::$app->user->identity->desg_id AND $master_info['financial_yr'] == $financial_yr){
                    $this->layout = '@app/views/layouts/admin_layout.php';
                    return $this->render('annreimclaim', ['menuid'=>$menuid, 'master_info'=>$master_info]);
                    
                }else{
                    Yii::$app->getSession()->setFlash('danger', 'Invalid params value found.'); 
                    return $this->redirect($url);
                }
            }else{
                Yii::$app->getSession()->setFlash('danger', 'Invalid params value found.'); 
                return $this->redirect($url);
            }
            
        }else{
            Yii::$app->getSession()->setFlash('danger', 'Invalid params found.'); 
            return $this->redirect($url);
        }
    }
    
    public function actionClaimedform(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."employee/reimbursement/annual?securekey=$menuid";
        if(isset($_POST['Claim']) AND !empty($_POST['Claim'])){
            if(Yii::$app->user->identity->employmenttype != 'R'){
                Yii::$app->getSession()->setFlash('danger', 'You are not eligible for Annual Reimbursement.'); 
                return $this->redirect($url);
            }
            
            $post = $_POST['Claim'];
            $formtype = Yii::$app->utility->decryptString($post['formtype']);
            $ann_reim_id = Yii::$app->utility->decryptString($post['ari']);
            $financial_yr = Yii::$app->utility->decryptString($post['fy']);
            if(empty($ann_reim_id) OR empty($financial_yr) OR empty($formtype)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid params value found.'); 
                return $this->redirect($url);
            }
            $financial_year = $other_detail = $apr_month_amt = $may_month_amt = $june_month_amt = $july_month_amt = $aug_month_amt = $sept_month_amt = $oct_month_amt = $nov_month_amt = $dec_month_amt = $jan_month_amt = $feb_month_amt = $mar_month_amt = $total_claimed = $doc_path = $sanc_claimed = $sanc_by = $sanc_remarks = NULL;
            $status ="Submitted";
            
            if(!empty($_FILES['doc_file']['tmp_name'])){
                $doc_path= $this->uploadFile($_FILES['doc_file']['tmp_name'], $_FILES['doc_file']['name']);
            }
            
            if($formtype == '1'){
                $total_claimed = trim(preg_replace('/[^0-9.-]/', '', $post['amount']));
                if(!is_numeric($total_claimed)){
                    Yii::$app->getSession()->setFlash('danger', 'Amount should be in numbers.'); 
                    return $this->redirect($url);
                }
            }elseif($formtype == '2'){
                $np_paper = $post['np_paper'];
                $other_detail = "";
                $chk=0;
                foreach($np_paper as $n){
                    $n1 =  Yii::$app->utility->decryptString($n);
                    if(empty($n1)){
                        Yii::$app->getSession()->setFlash('danger', 'Invalid Newspaper value found.'); 
                        return $this->redirect($url);
                    }
                    $chk=$chk+1;
                    $other_detail .= "$n1,";
                }
                if($chk !='12'){
                    if(!empty($doc_path)){
                        $docs = getcwd().$doc_path;
                        @unlink($path);
                    }
                    Yii::$app->getSession()->setFlash('danger', 'Newpaper name cannot empty.'); 
                    return $this->redirect($url);
                }
                $other_detail = rtrim($other_detail,',');
                
                $apr_month_amt = trim(preg_replace('/[^0-9.-]/', '', $post['apr_month_amt']));
                $may_month_amt = trim(preg_replace('/[^0-9.-]/', '', $post['may_month_amt']));
                $june_month_amt = trim(preg_replace('/[^0-9.-]/', '', $post['june_month_amt']));
                $july_month_amt = trim(preg_replace('/[^0-9.-]/', '', $post['july_month_amt']));
                $aug_month_amt = trim(preg_replace('/[^0-9.-]/', '', $post['aug_month_amt']));
                $sept_month_amt = trim(preg_replace('/[^0-9.-]/', '', $post['sept_month_amt']));
                $oct_month_amt = trim(preg_replace('/[^0-9.-]/', '', $post['oct_month_amt']));
                $nov_month_amt = trim(preg_replace('/[^0-9.-]/', '', $post['nov_month_amt']));
                $dec_month_amt = trim(preg_replace('/[^0-9.-]/', '', $post['dec_month_amt']));
                $jan_month_amt = trim(preg_replace('/[^0-9.-]/', '', $post['jan_month_amt']));
                $feb_month_amt = trim(preg_replace('/[^0-9.-]/', '', $post['feb_month_amt']));
                $mar_month_amt = trim(preg_replace('/[^0-9.-]/', '', $post['mar_month_amt']));
                
                $total_claimed = $apr_month_amt+$may_month_amt+$june_month_amt+$july_month_amt+$aug_month_amt+$sept_month_amt+$oct_month_amt+$nov_month_amt+$dec_month_amt+$jan_month_amt+$feb_month_amt+$mar_month_amt;
            }else{
                if(!empty($doc_path)){
                    $docs = getcwd().$doc_path;
                    @unlink($path);
                }
                Yii::$app->getSession()->setFlash('danger', 'Invalid Submission Found.'); 
                return $this->redirect($url);
            }
            
            $result = Yii::$app->finance->fn_add_update_ann_reim_claim(NULL, Yii::$app->user->identity->e_id, $ann_reim_id, $financial_yr, $other_detail, $apr_month_amt, $may_month_amt, $june_month_amt, $july_month_amt, $aug_month_amt, $sept_month_amt, $oct_month_amt, $nov_month_amt, $dec_month_amt, $jan_month_amt, $feb_month_amt, $mar_month_amt, $total_claimed, $doc_path, $sanc_claimed, $sanc_by, $sanc_remarks, $status);
            /*
             * Logs
             */
            $logs['arc_id']=NULL;
            $logs['employee_code']=Yii::$app->user->identity->e_id;
            $logs['ann_reim_id']=$ann_reim_id;
            $logs['financial_yr']=$financial_yr;
            $logs['other_detail']=$other_detail;
            $logs['apr_month_amt']=$apr_month_amt;
            $logs['may_month_amt']=$may_month_amt;
            $logs['june_month_amt']=$june_month_amt;
            $logs['july_month_amt']=$july_month_amt;
            $logs['aug_month_amt']=$aug_month_amt;
            $logs['sept_month_amt']=$sept_month_amt;
            $logs['oct_month_amt']=$oct_month_amt;
            $logs['nov_month_amt']=$nov_month_amt;
            $logs['dec_month_amt']=$dec_month_amt;
            $logs['jan_month_amt']=$jan_month_amt;
            $logs['feb_month_amt']=$feb_month_amt;
            $logs['mar_month_amt']=$mar_month_amt;
            $logs['total_claimed']=$total_claimed;
            $logs['doc_path']=$doc_path;
            $logs['sanc_claimed']=$sanc_claimed;
            $logs['sanc_by']=$sanc_by;
            $logs['sanc_remarks']=$sanc_remarks;
            $logs['status']=$status;
            $jsonlogs = json_encode($logs);
            if($result == '3'){
                if(!empty($doc_path)){
                    $docs = getcwd().$doc_path;
                    @unlink($path);
                }
                
                $msg = "Annual Reimbursement Claim Already Submitted.";
                Yii::$app->getSession()->setFlash('danger', $msg); 
            }elseif($result == '1'){
                $msg = "Annual Reimbursement Claim Submitted Successfully.";
                Yii::$app->getSession()->setFlash('success', $msg); 
            }else{
                if(!empty($doc_path)){
                    $docs = getcwd().$doc_path;
                    @unlink($path);
                }
                $msg = "Annual Reimbursement Claim Not Submitted. Contact Admin.";
                Yii::$app->getSession()->setFlash('danger', $msg); 
            }
            Yii::$app->utility->activities_logs("Reimbursement", NULL, NULL, $jsonlogs, $msg);
            return $this->redirect($url);
        }
        Yii::$app->getSession()->setFlash('danger', 'Invalid params found.'); 
        return $this->redirect($url);
    }
    public function uploadFile($temPth, $Name){
        $info = new \SplFileInfo($Name);
        $ext = $info->getExtension();
        $Employee_Documents = Employee_Documents;
        $createFolder = getcwd().$Employee_Documents.Yii::$app->user->identity->e_id."/";
        if(!file_exists($createFolder)){
            mkdir($createFolder, 0777, true);
        }
        $random_number = mt_rand(100000, 999999);
        $newName = $random_number.strtotime(date('Y-m-d H:i:s')).".$ext";
        $finalName = $createFolder.$newName;
        $fileUploadedCheck = false;
        if(move_uploaded_file($temPth,$finalName)){
            chmod($finalName, 0777);
            $fileUploadedCheck = true;
        }

        if(!empty($fileUploadedCheck)){
            $returnName = $Employee_Documents.Yii::$app->user->identity->e_id."/$newName";
        }else{
            $returnName = "";
        }
        return $returnName;
    }
    public function actionViewannureim(){
         
        if(isset($_GET['key1']) AND !empty($_GET['key1']) AND isset($_GET['key2']) AND !empty($_GET['key2'])){
            
            $ann_reim_id = Yii::$app->utility->decryptString($_GET['key1']);
            $fy = Yii::$app->utility->decryptString($_GET['key2']);
            
            if(empty($ann_reim_id) OR empty($fy)){
                $result['Status']='FF';
                $result['Res']='Invalid params value found';
                echo json_encode($result);die;
            }
            $info = Yii::$app->finance->fn_get_ann_reim_claim(NULL, Yii::$app->user->identity->e_id, $fy, NULL, $ann_reim_id);
            
            if(empty($info)){
                $result['Status']='FF';
                $result['Res']='Invalid params value found';
                echo json_encode($result);die;
            }
            $doc_path = "";
            if(!empty($info['doc_path'])){
                $doc_path = "<a href='".Yii::$app->homeUrl.$info['doc_path']."' target='_blank' class='linkcolor'>View</a>";
            }
            $sanc_amt = $info['sanc_amt'];
            $html = "";
            $html = "<table class='table table-bordered'>
                    <tr>
                    <th>Financial Year : $fy</th>
                    <th>Entitlement : $sanc_amt</th>
                    <th>Attached Document : $doc_path</th>
                    </tr>
                    </table>";
            $name = $info['name'];
            if($info['reim_type_id'] == '1'){
                $d = explode(',', $info['other_detail']);
                $f = explode('-', $info['financial_year']);
                $html .= "<h6><b>$name</b></h6><table class='table table-bordered'>
                    <tr>
                        <th>Month</th>
                        <th>Details</th>
                        <th>Claimed Amount</th>
                    </tr>
                    <tr>
                        <td>April-".$f[0]."</td>
                        <td>".@$d[0]."</td>
                        <td>".$info['apr_month_amt']."</td>
                    </tr>
                    <tr>
                        <td>May-".$f[0]."</td>
                        <td>".@$d[1]."</td>
                        <td>".$info['may_month_amt']."</td>
                    </tr>
                    <tr>
                        <td>June-".$f[0]."</td>
                        <td>".@$d[2]."</td>
                        <td>".$info['june_month_amt']."</td>
                    </tr>
                    <tr>
                        <td>July-".$f[0]."</td>
                        <td>".@$d[3]."</td>
                        <td>".$info['july_month_amt']."</td>
                    </tr>
                    <tr>
                        <td>Aug-".$f[0]."</td>
                        <td>".@$d[4]."</td>
                        <td>".$info['aug_month_amt']."</td>
                    </tr>
                    <tr>
                        <td>Sept-".$f[0]."</td>
                        <td>".@$d[5]."</td>
                        <td>".$info['sept_month_amt']."</td>
                    </tr>
                    <tr>
                        <td>Oct-".$f[0]."</td>
                        <td>".@$d[6]."</td>
                        <td>".$info['oct_month_amt']."</td>
                    </tr>
                    <tr>
                        <td>Nov-".$f[0]."</td>
                        <td>".@$d[7]."</td>
                        <td>".$info['nov_month_amt']."</td>
                    </tr>
                    <tr>
                        <td>Dec-".$f[0]."</td>
                        <td>".@$d[8]."</td>
                        <td>".$info['dec_month_amt']."</td>
                    </tr>
                    <tr>
                        <td>Jan-".$f[1]."</td>
                        <td>".@$d[9]."</td>
                        <td>".$info['jan_month_amt']."</td>
                    </tr>
                    <tr>
                        <td>Feb-".$f[1]."</td>
                        <td>".@$d[10]."</td>
                        <td>".$info['feb_month_amt']."</td>
                    </tr>
                    <tr>
                        <td>Mar-".$f[1]."</td>
                        <td>".@$d[11]."</td>
                        <td>".$info['mar_month_amt']."</td>
                    </tr>
                    <tr>
                        <td colspan='2' align='right'><b>Total Claimed</b></td>
                        <td>".$info['total_claimed']."</td>
                    </tr>
                    <tr>
                        <td colspan='2' align='right'><b>Total Sanctioned</b></td>
                        <td>".$info['sanc_claimed']."</td>
                    </tr>
                    </table>";
            }else{
               $html .= "<h6><b>$name</b></h6>
                       <table class='table table-bordered'>
                    <tr>
                    <th>Total Claimed : ".$info['total_claimed']."</th>
                    <th>Total Sanctioned : ".$info['sanc_claimed']."</th>
                    </tr>
                    </table>
                       </div>";
            }
            $result['Status']='SS';
            $result['Res']=$html;
            echo json_encode($result);die;
        }else{
            $result['Status']='FF';
            $result['Res']='Invalid params found';
            echo json_encode($result);die;
        }
    }
    
    public function actionDownloadopdclaim(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."employee/reimbursement/opd?securekey=$menuid";
//        echo "<pre>";print_r($_GET); die;
        if(isset($_GET['entitle_id']) AND !empty($_GET['entitle_id']) AND isset($_GET['opd_id']) AND !empty($_GET['opd_id'])){
            $entitleid = Yii::$app->utility->decryptString($_GET['entitle_id']);
            $opdid = Yii::$app->utility->decryptString($_GET['opd_id']);
            if(empty($opdid) OR empty($entitleid)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid param found.'); 
                return $this->redirect($url);
            }
            $e_code =Yii::$app->user->identity->e_id;
            $opd = Yii::$app->finance->fn_get_opd_claims($opdid, $entitleid, $e_code, 'Submitted,In-Process,Sanctioned,Rejected');
            $bill = Yii::$app->finance->fn_get_opd_bill_details($opdid, $e_code, NULL);
            if(empty($opd) OR empty($bill)){
                Yii::$app->getSession()->setFlash('danger', 'No Record Found.'); 
                return $this->redirect($url);
            }
//            echo "<pre>";print_r($opd); die;
            require_once './mpdf/mpdf.php';
            $mpdf = new \mPDF();
            $date = date('d-m-Y H:i');
            $session = $opd['session_year'];
            $claim_id = $opd['claim_id'];
            $claimdate = date('d-M-Y', strtotime($opd['created_on']));
            $header = "<div style='text-align:center;'><p style='margin:0px; font-size:18px;font-weight:bold;font-family:arial;'></b>".ORGANAZATION_NAME."</b></p><p style='margin:0px;font-size:16px;font-weight:bold;font-family:arial;'>Reimbursement of Medical [OPD] Claim</p></div>
                <div style='text-align:right;margin-top:15px;'>
                <p style='margin:0px;'><b>RIM/MC/$session/$e_code/$claim_id</b></p>
                <p style='margin:0px;'>Claim Date : $claimdate</p>
                <p style='margin:0px;'>(MOH Finance Reimbursement)</p>
</div>
                    ";

            $mpdf->WriteHTML($header);
            
            $footer = "<table style='width:100%;font-size:10px;'><tr><td align='left'>{PAGENO} of {nbpg}</td><td align='right'>$date</td></tr></table>";
            $mpdf->setFooter($footer);
            
            $eid = Yii::$app->user->identity->e_id;
            $n = Yii::$app->user->identity->fullname;
            $degn = Yii::$app->user->identity->desg_name;
            $jd = date('d-m-Y', strtotime(Yii::$app->user->identity->joining_date));
            $dept = Yii::$app->user->identity->dept_name;
            $st = Yii::$app->user->identity->employment_type;
            $scale = Yii::$app->user->identity->grade_pay_scale;
            $email_id = Yii::$app->user->identity->email_id;
            $phone = Yii::$app->user->identity->phone;
            $border = "border:1px solid black;";
            $tfont = "$border padding:10px;font-size:13px;font-family:arial;";
            $html = "";
            $html .= "<p style='font-weight:bold;margin:0px;font-family:arial;'>Personal Info:-</p>";
            $html .= "<table width='100%' style='$border border-collapse: collapse; overflow: wrap'>
                    <tr style='$border'>
                            <td style='$tfont'>EmpId</td>
                            <td style='$tfont'>$eid</td>
                            <td style='$tfont'>Name</td>
                            <td style='$tfont'>$n</td>
                    </tr>
                    <tr style='$border'>
                            <td style='$tfont'>Designation</td>
                            <td style='$tfont'>$degn</td>
                            <td style='$tfont'>Joining Date</td>
                            <td style='$tfont'>$jd</td>
                    </tr>
                    <tr style='$border'>
                            <td style='$tfont'>Centre</td>
                            <td style='$tfont'>Mohali</td>
                            <td style='$tfont'>Group</td>
                            <td style='$tfont'>$dept</td>
                    </tr>
                    <tr style='$border'>
                            <td style='$tfont'>Staff Type</td>
                            <td style='$tfont'>$st</td>
                            <td style='$tfont'>Scale</td>
                            <td style='$tfont'>$scale</td>
                    </tr>
                    <tr style='$border'>
                            <td style='$tfont'>Basic/PiPb/ConsPay</td>
                            <td style='$tfont' colspan='3'>$scale</td>
                    </tr>
                    <tr style='$border'>
                            <td style='$tfont'>Email-Id</td>
                            <td style='$tfont'>$email_id</td>
                            <td style='$tfont'>Phone</td>
                            <td style='$tfont'>$phone</td>
                    </tr>
                    </table>";
            $html .= "<br><p style='font-weight:bold;margin:0px;font-family:arial;'>Claim Details:-</p>";
            $html .= "<table width='100%' style='$border border-collapse: collapse; overflow: wrap'>
                    <tr style='$border'>
                            <td style='$tfont'>Sr. No.</td>
                            <td style='$tfont'>Patient Name</td>
                            <td style='$tfont'>Bill Date</td>
                            <td style='$tfont'>Bill No.</td>
                            <td style='$tfont'>Bill Type</td>
                            <td style='$tfont'>Issuer</td>
                            <td style='$tfont'>Claimed Amount</td>
                            <td style='$tfont'>Sanc. Amount</td>
                    </tr>";
            $tamt = $tsancamt=0;
            $i=1;
            foreach($bill as $b){
                $patienttype = $b['patienttype'];
                $m_name = $b['m_name'];
                $billtype = $b['billtype'];
                $bill_num = $b['bill_num'];
                $bill_date = date('d-M-Y', strtotime($b['bill_date']));
                $bill_amt = $b['bill_amt'];
                $bill_issuer = $b['bill_issuer'];
                $sanctioned_amt = $b['sanctioned_amt'];
                $tamt = $tamt+$bill_amt;
                $tsancamt = $tsancamt+$sanctioned_amt;
                $bill_amt = number_format($bill_amt, 2);
                $sanctioned_amt = number_format($sanctioned_amt, 2);
                if($b['patient_type'] == 'S'){
                    $m_name = $n;
                }
                $m_name = $m_name."[$patienttype]";
                $html .= "<tr style='$border'>
                    <td style='$tfont width:6%;'>$i]</td>
                    <td style='$tfont width:15%;'>$m_name</td>
                    <td style='$tfont width:15%;'>$bill_date</td>
                    <td style='$tfont width:8%;'>$bill_num</td>
                    <td style='$tfont'>$billtype</td>
                    <td style='$tfont'>$bill_issuer</td>
                    <td style='$tfont width:12%;'>$bill_amt</td>
                    <td style='$tfont width:12%;'>$sanctioned_amt</td>
                </tr>";
                $i++;
            }
            $tamt = number_format($tamt, 2);
            $tsancamt = number_format($tsancamt, 2);
            $html .= "<tr style='$border'>
                    <td style='$tfont' colspan='6'>Total (Rs.)</td>
                    <td style='$tfont'>$tamt</td>
                    <td style='$tfont'>$tsancamt</td>
                </tr>";  
            $html .= "</table>";
            
            if($opd['status'] == 'Submitted'){
                $html .= "<div style='border:1px solid #000;padding:5px;margin-top:15px;'>
                        <h4 style='text-align: center;margin-bottom:5px;'><b>UNDERTAKING / DECLARATION</b></h4>
                        <table style='width:100%'>
                            <tr>
                                <td colspan='2'>I hereby declare that,</td>
                            </tr>
                            <tr>
                                <td style='width:5%' align='center'>01.</td>
                                <td style='width:94%; text-align:justify;'>All information given above is true and correct to the best of my knowledge and belief.</td>
                            </tr>
                            <tr>
                                <td style='width:5%' align='center'>02.</td>
                                <td style='width:94%; text-align:justify;'>All the expenditure for which this medical reimbursement is claimed has been actually incurred by me.</td>
                            </tr>
                            <tr>
                                <td style='width:5%' align='center'>03.</td>
                                <td style='width:94%; text-align:justify;'>I have not claimed any of the above amounts from any authority.</td>
                            </tr>
                            <tr>
                                <td style='width:5%' align='center'>04.</td>
                                <td style='width:94%; text-align:justify;'>All dependent family members for whom this medical reimbursement is claimed are actually dependent on me as per central government rules for the entire period for which reimbursement is claimed.</td>
                            </tr>
                            <tr>
                                <td style='width:5%' align='center'>05.</td>
                                <td style='width:94%; text-align:justify;'>I undertake to refund the amount in one single installment paid by C-DAC under this claim if anything declared above is proven false / wrong. It is therefore requested to reimburse me the above amount.</td>
                            </tr>
                    </table></div>";
                $html .= "<div style='text-align:right;margin-top:40px;margin-bottom:15px;'><b>Signature Of Employee</b></div>";
                $html .= "<div style='border:1px solid #000;padding:5px;'>
                    <h4 style='text-align: center;margin-bottom:10px;'><b>For Office Purpose Only</b></h4>
                    <table style='width:100%'>
                        <tr>
                            <td style='width:50%' align='left'>Claim passed for</td>
                            <td style='width:49%' align='right'>CFO/Finance Executive</td>
                        </tr>
                    </table>
                    <table style='width:100%;margin-top:25px;'>
                        <tr style='margin-top:30px;'>
                            <td style='width:50%' align='left'>Checked by</td>
                            <td style='width:49%' align='right'>Passed by</td>
                        </tr>
                    </table>
                </div>";
            }
            //echo "<pre>";print_r($opd);die;
            
            $mpdf->WriteHTML($html);
            $name = "reimbursement_opd_".date('Y_m_d_H_i_s').".pdf";
	    $file = $mpdf->Output($name, 'I');
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . basename($file) . '"');
            header("Cache-Control: max-age=0");
            readfile($file);
            
        }
        Yii::$app->getSession()->setFlash('danger', 'Invalid params found.'); 
        return $this->redirect($url);
    }
}
