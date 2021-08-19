<?php

namespace app\modules\employee\controllers;
use yii;
class IpdreimbursementController extends \yii\web\Controller
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
        $fnYears = Yii::$app->finance->financialYrListFromJoining();
        return $this->render('index', ['menuid'=>$menuid, 'fnYears'=>$fnYears, 'selectfnyr'=>""]);    
    }
    
    public function actionGet_dependent_family(){
        $data = Yii::$app->utility->get_family_details(Yii::$app->user->identity->e_id);
        $result['Status']='FF';
        $html = $result['Res']='';
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
    
    public function actionApplynewclaim(){
        $this->layout = '@app/views/layouts/admin_layout.php';
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $ipd_id = "";
        return $this->render('applynewclaim', ['menuid'=>$menuid, 'ipd_id'=>$ipd_id]);
    }
    
    public function actionSaveipdclaim(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."employee/ipdreimbursement/applynewclaim?securekey=$menuid";
        if(isset($_POST['Ipd']) AND !empty($_POST['Ipd'])){
            $post = $_POST['Ipd'];
            $insrn_sanc_amt = $insurance_id = $dependent_id = $ipd_id = NULL;
            $patient = trim(preg_replace('/[^0-9-]/', '', $post['patient']));
            if($patient == '1'){
                $patient_type = "S";
            }elseif($patient == '2'){
                $patient_type = "D";
                $dependent_id = base64_decode($post['dependent_id']);
                if(!is_numeric($dependent_id)){
                    Yii::$app->getSession()->setFlash('danger', 'Invalid Dependent ID');
                    return $this->redirect($url);
                }
            }
            $date_of_admission = date('Y-m-d', strtotime($post['date_of_admission']));
            $date_of_discharge = date('Y-m-d', strtotime($post['date_of_discharge']));
            $admitted_for = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['admitted_for']));
            $claim_type = base64_decode($post['claim_type']);
            
            if(!empty($post['insurance_id'])){
                $insurance_id = base64_decode($post['insurance_id']);
                if(!is_numeric($insurance_id)){
                    Yii::$app->getSession()->setFlash('danger', 'Invalid Insurance ID');
                    return $this->redirect($url);
                }
                $insrn_sanc_amt = $patient = trim(preg_replace('/[^0-9-]/', '', $post['insrn_sanc_amt']));
            }
            
            $fn_yr = Yii::$app->finance->getCurrentFY();
            //echo "<pre>";print_r($_POST); die;
            $result = Yii::$app->finance->fn_add_update_ipd_claims($ipd_id, Yii::$app->user->identity->e_id, $fn_yr, $patient_type, $dependent_id, $date_of_admission, $date_of_discharge, $admitted_for, $claim_type, $insurance_id, $insrn_sanc_amt, NULL, NULL, "Draft", NULL);
            /*
             * Logs
             */
            $logs['ipd_id']=$ipd_id;
            $logs['emp_code']=Yii::$app->user->identity->e_id;
            $logs['fn_yr']=$fn_yr;
            $logs['patient_type']=$patient_type;
            $logs['dependent_id']=$dependent_id;
            $logs['date_of_admission']=$date_of_admission;
            $logs['date_of_discharge']=$date_of_discharge;
            $logs['admitted_for']=$admitted_for;
            $logs['claim_type']=$claim_type;
            $logs['insurance_id']=$insurance_id;
            $logs['insrn_sanc_amt']=$insrn_sanc_amt;
            $logs['status']="Draft";
            $jsonlogs = json_encode($logs);
            
            if(!empty($result)){
                Yii::$app->getSession()->setFlash('success', 'IPD Claim Submitted. Add Bill Details.');
                
                Yii::$app->utility->activities_logs("Reimbursement", NULL, NULL, $jsonlogs, "IPD Claim Submitted. Add Bill Details.");
                
                $result = Yii::$app->utility->encryptString($result);
                $url = Yii::$app->homeUrl."employee/ipdreimbursement/ipdbilldetails?securekey=$menuid&ipd_id=$result";
                return $this->redirect($url);
            }else{
                Yii::$app->utility->activities_logs("Reimbursement", NULL, NULL, $jsonlogs, "IPD Claim not submitted. Contact Admin.");
                
                Yii::$app->getSession()->setFlash('danger', 'IPD Claim not submitted. Contact Admin.');
                return $this->redirect($url);
            }
        }else{
            Yii::$app->getSession()->setFlash('danger', 'Invalid Url.');
            return $this->redirect($url);
        }
    }
    
    public function actionIpdbilldetails(){
        
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."employee/ipdreimbursement/applynewclaim?securekey=$menuid";
        if(isset($_GET['ipd_id']) AND !empty($_GET['ipd_id'])){
            $ipd_id = Yii::$app->utility->decryptString($_GET['ipd_id']);
            if(empty($ipd_id)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid IPD ID.');
                return $this->redirect($url);
            }
            $claimDetail = Yii::$app->finance->fn_get_ipd_claims($ipd_id, Yii::$app->user->identity->e_id, "Draft,Revoked");
            if(empty($claimDetail)){
                Yii::$app->getSession()->setFlash('danger', 'IPD Claim Details Not Found.');
                return $this->redirect($url);
            }
            $this->layout = '@app/views/layouts/admin_layout.php';
            $ipd_id = Yii::$app->utility->encryptString($ipd_id);
            return $this->render('ipdbilldetails', ['menuid'=>$menuid, 'claimDetail'=>$claimDetail, 'ipd_id'=>$ipd_id ]);
        }else{
            Yii::$app->getSession()->setFlash('danger', 'Invalid Url.');
            return $this->redirect($url);
        }
        
    }
    public function actionGetinsurancedetails(){
        if(isset($_GET['dependent_id']) AND isset($_GET['pt']) AND !empty($_GET['pt'])){
            $did = NULL;
            if(!empty($_GET['dependent_id'])){
                $did = base64_decode($_GET['dependent_id']);
            }
            if($_GET['pt'] == '1'){
                $patient_type = "S";
            }elseif($_GET['pt'] == '2'){
                $patient_type = "D";
            }
            
            $lists = Yii::$app->finance->fn_get_emp_insurance($did, $patient_type, Yii::$app->user->identity->e_id);
            $html = "<option value=''>Select Insurance Details</option>";
            if(!empty($lists)){
                foreach($lists as $list){
                    $insurnID = base64_encode($list['id']);
                    $company_name = $list['company_name'];
                    $valid_from = strtotime($list['valid_from']);
                    $valid_till = strtotime($list['valid_till']);
                    $curDate = strtotime(date('Y-m-d'));
                    $disable = "disabled=''";
                    $txtcolor = "color:red;";
                    if(($curDate >= $valid_from) AND ($curDate <= $valid_till)){
                        $txtcolor = $disable = "";
                    }
                    $valid_from =date('d-m-Y', strtotime($list['valid_from']));
                    $valid_till =date('d-m-Y', strtotime($list['valid_till']));
                    $html = $html .= "<option style='$txtcolor' $disable value='$insurnID'>$company_name ($valid_from to $valid_till)</option>";
                }
                $result['Status'] = "SS";
            }else{
                $result['Status'] = "FF";
                $html = $html."<option disabled='' value=''>No Record Found</option>";
            }
            $result['Res'] = $html;
            echo json_encode($result);
            die;
        }
    }
    
    public function actionSaveinsurance(){
        parse_str($_POST['info'],$post);
        if(isset($post['Insurance']) AND !empty($post['Insurance'])){
            $post = $post['Insurance'];
            $patient_type = $post['patient_type'];
            $dependent_id = NULL;
            $result = array();
            if($patient_type == '1'){
                $patient_type = "S";
            }elseif($patient_type == '2'){
                $patient_type = "D";
                $dependent_id = base64_decode($post['dependent_id']);
                if(empty($dependent_id)){
                    $result['Status']= 'FF';
                    $result['Res']= 'Invalid Dependent Family Member';
                    echo json_encode($result);
                    die;
                }
                if(!is_numeric($dependent_id)){
                    $result['Status']= 'FF';
                    $result['Res']= 'Invalid ID Dependent Family Member';
                    echo json_encode($result);
                    die;
                }
            }else{
                $result['Status']= 'FF';
                $result['Res']= 'Invalid Patient Type or ID';
                echo json_encode($result);
                die;
            }
            $company_name =  trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['company_name']));
            $policynumber =  trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['policynumber']));
            $validfrom = date('Y-m-d', strtotime($post['validfrom']));
            $validtill = date('Y-m-d', strtotime($post['validtill']));
            
            if(strtotime($post['validtill']) <= strtotime($post['validfrom'])){
                $result['Status']= 'FF';
                $result['Res']= 'Invalid Policy Valid Date From or Till';
                echo json_encode($result);
                die;
            }
            $output = Yii::$app->finance->fn_add_update_emp_insurance(NULL, Yii::$app->user->identity->e_id, $patient_type, $dependent_id, $company_name, $policynumber, $validfrom, $validtill);
            
            /*
             * Logs
             */
            $logs['emp_code']=Yii::$app->user->identity->e_id;
            $logs['patient_type']=$patient_type;
            $logs['dependent_id']=$dependent_id;
            $logs['company_name']=$company_name;
            $logs['policynumber']=$policynumber;
            $logs['validfrom']=$validfrom;
            $logs['validtill']=$validtill;
            $jsonlogs = json_encode($logs);
            
            if($output == '2'){
                $result['Status']= 'FF';
                $result['Res']= 'Dependent Family Member not exits';
                Yii::$app->utility->activities_logs("Reimbursement", NULL, NULL, $jsonlogs, "Insurance Details : Dependent Family Member not exits");
            }elseif($output == '3'){
                $result['Status']= 'FF';
                $result['Res']= 'Insurance Details Already exits';
                Yii::$app->utility->activities_logs("Reimbursement", NULL, NULL, $jsonlogs, "Insurance Details Already exits");
            }elseif($output == '1'){
                $result['Status']= 'SS';
                $result['Res']= 'Insurance Details Add Successfully.';
                Yii::$app->utility->activities_logs("Reimbursement", NULL, NULL, $jsonlogs, "Insurance Details Add Successfully");
            }else{
                $result['Status']= 'FF';
                $result['Res']= 'No Response Found. Contact Admin.';
                Yii::$app->utility->activities_logs("Reimbursement", NULL, NULL, $jsonlogs, "No Response Found. Contact Admin.");
            }
            echo json_encode($result);
            die;
        }else{
            $result['Status']= 'FF';
            $result['Res']= 'Invalid Params Found.';
            echo json_encode($result);
            die;
        }
    }
    
    public function actionSaveipdbill(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."employee/ipdreimbursement?securekey=$menuid";
        
//        For Add and Update
        if(isset($_POST['IPD_Bill']) AND !empty($_POST['IPD_Bill'])){
            $post = $_POST['IPD_Bill'];
            $ipd_id = Yii::$app->utility->decryptString($post['ipd_id']);
            $date_of_admission = Yii::$app->utility->decryptString($post['date_of_admission']);
            $date_of_discharge = Yii::$app->utility->decryptString($post['date_of_discharge']);
            if(empty($ipd_id) OR empty($date_of_admission) OR empty($date_of_discharge)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid Params Value Found.');
                return $this->redirect($url);
            }
            $claimDetail = Yii::$app->finance->fn_get_ipd_claims($ipd_id, Yii::$app->user->identity->e_id, "Draft,Revoked");
            if(empty($claimDetail)){
                Yii::$app->getSession()->setFlash('danger', 'Claimed Already Submitted.');
                return $this->redirect($url);
            }
            $ipd_id1 = Yii::$app->utility->encryptString($ipd_id);
            $url = Yii::$app->homeUrl."employee/ipdreimbursement/ipdbilldetails?securekey=$menuid&ipd_id=$ipd_id1";
            $action_type = "I";
            $ipd_bill_id = NULL;
            if(!empty($post['ipd_bill_id'])){
                $ipd_bill_id = Yii::$app->utility->encryptString($post['ipd_bill_id']);
                if(empty($ipd_bill_id)){
                    Yii::$app->getSession()->setFlash('danger', 'Invalid Params Value Found.');
                    return $this->redirect($url);
                }
                $action_type = "U";
            }
            $bill_date = date('Y-m-d', strtotime($post['bill_date']));
            if(strtotime($bill_date) < strtotime($date_of_admission)){
                Yii::$app->getSession()->setFlash('danger', 'Bill Date Cannot Less Then Date of Admission.');
                return $this->redirect($url);
            }elseif(strtotime($bill_date) > strtotime($date_of_discharge)){
                Yii::$app->getSession()->setFlash('danger', 'Bill Date Cannot Less Then Date of Admission.');
                return $this->redirect($url);
            }
            $bill_number =  trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['bill_number']));
            $issuer =  trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['issuer']));
            $bill_amt =  trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['bill_amt']));

            $result = Yii::$app->finance->fn_add_update_ipd_details($action_type, $ipd_bill_id, Yii::$app->user->identity->e_id, $ipd_id, $bill_number, $bill_date, $issuer, $bill_amt, NULL);
            
            /*
             * Logs
             */
            $logs['action_type']=$action_type;
            $logs['ipd_bill_id']=$ipd_bill_id;
            $logs['ipd_id']=$ipd_id;
            $logs['bill_number']=$bill_number;
            $logs['bill_date']=$bill_date;
            $logs['issuer']=$issuer;
            $logs['bill_amt']=$bill_amt;
            
            $jsonlogs = json_encode($logs);
            
            if($result == '1'){
                $msg = "IPD Bill Added Successfully.";
            }elseif($result == '2'){
                $msg = "IPD Bill Updated Successfully.";
            }
            Yii::$app->utility->activities_logs("Reimbursement", NULL, NULL, $jsonlogs, $msg);
            
            Yii::$app->getSession()->setFlash('success', $msg);
            return $this->redirect($url);
        }
        
//        For Delete Only
        if(isset($_GET['ipd_bill_id']) AND !empty($_GET['ipd_bill_id']) AND isset($_GET['amt']) AND !empty($_GET['amt']) AND isset($_GET['ipd_id']) AND !empty($_GET['ipd_id'])){
            $amt = Yii::$app->utility->decryptString($_GET['amt']);
            $ipd_bill_id = Yii::$app->utility->decryptString($_GET['ipd_bill_id']);
            $ipd_id = Yii::$app->utility->decryptString($_GET['ipd_id']);
            
            if(empty($amt) OR empty($ipd_bill_id) OR empty($ipd_id)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid Params Value Found.');
                return $this->redirect($url);
            }
            $claimDetail = Yii::$app->finance->fn_get_ipd_claims($ipd_id, Yii::$app->user->identity->e_id, "Draft,Revoked");
            if(empty($claimDetail)){
                Yii::$app->getSession()->setFlash('danger', 'Claimed Already Submitted.');
                return $this->redirect($url);
            }
            
            $ipd_id1 = Yii::$app->utility->encryptString($ipd_id);
            $url = Yii::$app->homeUrl."employee/ipdreimbursement/ipdbilldetails?securekey=$menuid&ipd_id=$ipd_id1";
            
            $result = Yii::$app->finance->fn_add_update_ipd_details("D", $ipd_bill_id, Yii::$app->user->identity->e_id, $ipd_id, NULL, NULL, NULL, $amt, NULL);
            
            /*
             * Logs
             */
            $logs['action_type']='D';
            $logs['ipd_id']=$ipd_id;
            $logs['ipd_bill_id']=$ipd_bill_id;
            $logs['amt']=$amt;
            $jsonlogs = json_encode($logs);
            
            if($result == '3'){
                Yii::$app->utility->activities_logs("Reimbursement", NULL, NULL, $jsonlogs, "IPD Bill Deleted Successfully.");
                
                Yii::$app->getSession()->setFlash('success', "IPD Bill Deleted Successfully.");
                return $this->redirect($url);
            }
        }
        Yii::$app->getSession()->setFlash('danger', 'Invalid Url.');
        return $this->redirect($url);
    }
    
    public function actionSubmitipdclaim(){
//        echo "<pre>";print_r($_GET);die;
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."employee/ipdreimbursement?securekey=$menuid";
        if(isset($_GET['ipd_id']) AND !empty($_GET['ipd_id'])){
            $ipd_id = Yii::$app->utility->decryptString($_GET['ipd_id']);
            if(empty($ipd_id)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid Params Value Found.');
                return $this->redirect($url);
            }
            $claimDetail = Yii::$app->finance->fn_get_ipd_claims($ipd_id, Yii::$app->user->identity->e_id, "Draft,Revoked");
            if(empty($claimDetail)){
                Yii::$app->getSession()->setFlash('danger', 'Claimed Already Submitted.');
                return $this->redirect($url);
            }
            $billDetails = Yii::$app->finance->fn_get_ipd_details(Yii::$app->user->identity->e_id, $ipd_id);
            if(empty($billDetails)){
                Yii::$app->getSession()->setFlash('danger', 'No Bill Details Found. Add Atleast One Entry.');
                return $this->redirect($url);
            }
            $grandTotal = 0;
            if(!empty($billDetails)){
                foreach($billDetails as $bill){
                    $grandTotal = $grandTotal+$bill['bill_amt'];
                }
            }
            //echo "<pre>";print_r($billDetails);die;
            $result = Yii::$app->finance->fn_add_update_ipd_claims($ipd_id, Yii::$app->user->identity->e_id, NULL, $claimDetail['patient_type'], $claimDetail['dependent_id'], $claimDetail['date_of_admission'], $claimDetail['date_of_discharge'], $claimDetail['admitted_for'], $claimDetail['claim_type'], $claimDetail['insurance_id'], $claimDetail['insrn_sanc_amt'], $grandTotal, NULL, "Submitted", NULL);
            
            /*
             * Logs
             */
            $logs['ipd_id']=$ipd_id;
            $logs['grandTotal']=$grandTotal;
            $logs['status']="Submitted";
            $jsonlogs = json_encode($logs);
            
            if($result == '2'){
                
                Yii::$app->utility->activities_logs("Reimbursement", NULL, NULL, $jsonlogs, "IPD Claim Submitted Successfully.");
                
                Yii::$app->getSession()->setFlash('success', "IPD Claim Submitted Successfully.");
                return $this->redirect($url);
            }else{
                Yii::$app->utility->activities_logs("Reimbursement", NULL, NULL, $jsonlogs, "Claim has not submitted. Contact Admin.");
                
                Yii::$app->getSession()->setFlash('danger', 'Claim has not submitted. Contact Admin.');
                return $this->redirect($url);
            }
        }
        Yii::$app->getSession()->setFlash('danger', 'Invalid Url.');
        return $this->redirect($url);
        
    }
    
    public function actionPreviewipdclaim(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."employee/ipdreimbursement?securekey=$menuid";
        if(isset($_GET['ipd_id']) AND !empty($_GET['ipd_id'])){
            $ipd_id = Yii::$app->utility->decryptString($_GET['ipd_id']);
            if(empty($ipd_id)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid Params Value Found.');
                return $this->redirect($url);
            }
            $claimDetail = Yii::$app->finance->fn_get_ipd_claims($ipd_id, Yii::$app->user->identity->e_id, "Submitted,In-Process,Sanctioned,Rejected");
            if(empty($claimDetail)){
                Yii::$app->getSession()->setFlash('danger', 'No IPD Claim Found.');
                return $this->redirect($url);
            }
            $billDetails = Yii::$app->finance->fn_get_ipd_details(Yii::$app->user->identity->e_id, $ipd_id);
            $this->layout = '@app/views/layouts/admin_layout.php';
            return $this->render('previewipdclaim', ['menuid'=>$menuid, 'claimDetail'=>$claimDetail, 'billDetails'=>$billDetails]);
        }
        Yii::$app->getSession()->setFlash('danger', 'Invalid Url.');
        return $this->redirect($url);
    }
    
    public function actionDownloadipdclaim(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."employee/ipdreimbursement?securekey=$menuid";
        if(isset($_GET['ipd_id']) AND !empty($_GET['ipd_id'])){
            $ipd_id = Yii::$app->utility->decryptString($_GET['ipd_id']);
            if(empty($ipd_id)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid Params Value Found.');
                return $this->redirect($url);
            }
            $claimDetail = Yii::$app->finance->fn_get_ipd_claims($ipd_id, Yii::$app->user->identity->e_id, "Submitted,In-Process,Sanctioned,Rejected");
            if(empty($claimDetail)){
                Yii::$app->getSession()->setFlash('danger', 'No IPD Claim Found.');
                return $this->redirect($url);
            }
            $billDetails = Yii::$app->finance->fn_get_ipd_details(Yii::$app->user->identity->e_id, $ipd_id);
            if(empty($billDetails)){
                Yii::$app->getSession()->setFlash('danger', 'No IPD Bills Found.');
                return $this->redirect($url);
            }
            require_once './mpdf/mpdf.php';
            $mpdf = new \mPDF();
            $date = date('d-m-Y H:i');
            $session = $claimDetail['fn_year'];
            $claim_number = $claimDetail['claim_number'];
            $eid = Yii::$app->user->identity->e_id;
            
            $claimdate = date('d-M-Y', strtotime($claimDetail['claimed_on']));
            
            $header = "<div style='text-align:center;'><p style='margin:0px; font-size:18px;font-weight:bold;font-family:arial;'></b>".ORGANAZATION_NAME."</b></p><p style='margin:0px;font-size:16px;font-weight:bold;font-family:arial;'>Reimbursement of Medical [IPD] Claim</p></div>
                <div style='text-align:right;margin-top:15px;'>
                <p style='margin:0px;'><b>RIM/MC/$session/$eid/$claim_number</b></p>
                <p style='margin:0px;'>Claim Date : $claimdate</p>
                <p style='margin:0px;'>(MOH Finance Reimbursement)</p>
</div>
                    ";

            $mpdf->WriteHTML($header);
            
            $footer = "<table style='width:100%;font-size:10px;'><tr><td align='left'>{PAGENO} of {nbpg}</td><td align='right'>$date</td></tr></table>";
            $mpdf->setFooter($footer);
            
            
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
            $html .= "<br><p style='font-weight:bold;margin:0px;font-family:arial;'>IPD Claim Details:-</p>";
            
            $member_name = $claimDetail['member_name'];
            $date_of_admission = date('d-M-Y', strtotime($claimDetail['date_of_admission']));
            $date_of_discharge = date('d-M-Y', strtotime($claimDetail['date_of_discharge']));
            $admitted_for = $claimDetail['admitted_for'];
            $claim_type = $claimDetail['claim_type'];
            
            $html .= "<table width='100%' style='$border border-collapse: collapse; overflow: wrap'>
                    <tr style='$border'>
                        <td style='$tfont'>Financial Year</td>
                        <td style='$tfont'>$session</td>
                        <td style='$tfont'>Patient Type</td>
                        <td style='$tfont'>$member_name</td>
                    </tr>
                    <tr style='$border'>
                        <td style='$tfont'>Date of Admission</td>
                        <td style='$tfont'>$date_of_admission</td>
                        <td style='$tfont'>Date Of Discharge</td>
                        <td style='$tfont'>$date_of_discharge</td>
                    </tr>
                    <tr style='$border'>
                        <td style='$tfont'>Admitted For</td>
                        <td style='$tfont'>$admitted_for</td>
                        <td style='$tfont'>Claim Type</td>
                        <td style='$tfont'>$claim_type</td>
                    </tr>";
            if(!empty($claimDetail['insurance_id'])){
                $company_name = $claimDetail['company_name'];
                $policy_number = $claimDetail['policy_number'];
                $insrn_sanc_amt = $claimDetail['insrn_sanc_amt'];
                $valid_from = date('d-M-Y', strtotime($claimDetail['valid_from']));
                $valid_till = date('d-M-Y', strtotime($claimDetail['valid_till']));
                $html .= "
                    <tr style='$border'>
                        <td style='$tfont' colspan='4'><b>Insurance Details</b></td>
                    </tr>
                    <tr style='$border'>
                        <td style='$tfont'>Company Name</td>
                        <td style='$tfont'>$company_name</td>
                        <td style='$tfont'>Policy Number</td>
                        <td style='$tfont'>$policy_number</td>
                    </tr>
                    <tr style='$border'>
                        <td style='$tfont'>Valid From</td>
                        <td style='$tfont'>$valid_from</td>
                        <td style='$tfont'>Valid Till</td>
                        <td style='$tfont'>$valid_till</td>
                    </tr>
                    <tr style='$border'>
                        <td style='$tfont'>Insurance Sanctioned Amount</td>
                        <td style='$tfont'>$insrn_sanc_amt</td>
                        <td style='$tfont'></td>
                        <td style='$tfont'></td>
                    </tr>
                ";
            }
            $html .= "</table>";
            $html .= "<br><p style='font-weight:bold;margin:0px;font-family:arial;'>Bill Details:-</p>";
            $html .= "<table width='100%' style='$border border-collapse: collapse; overflow: wrap'>
                <tr style='$border'>
                        <td style='$tfont'>Bill Date</td>
                        <td style='$tfont'>Bill No.</td>
                        <td style='$tfont'>Issuer</td>
                        <td style='$tfont'>Claimed Amount</td>
                        <td style='$tfont'>Sanc. Amount</td>
                </tr>";
            $tamt = $tsamt=0;
            foreach($billDetails as $b){
                $bill_number = $b['bill_number'];
                $bill_date = date('d-M-Y', strtotime($b['bill_date']));
                $issuer = $b['issuer'];
                $bill_amt = $b['bill_amt'];
                $sanc_amt = 0;
                if(!empty($b['sanc_amt'])){
                    $sanc_amt = $b['sanc_amt'];
                }
                $tamt = $tamt+$bill_amt;
                $tsamt = $tsamt+$sanc_amt;
                $bill_amt = number_format($bill_amt, 2);
                $sanc_amt = number_format($sanc_amt, 2);
                $html .= "<tr style='$border'>
                        <td style='$tfont'>$bill_date</td>
                        <td style='$tfont'>$bill_number</td>
                        <td style='$tfont'>$issuer</td>
                        <td style='$tfont'>$bill_amt</td>
                        <td style='$tfont'>$sanc_amt</td>
                </tr>";
            }
            $tamt = number_format($tamt, 2);
            $tsamt = number_format($tsamt, 2);
            $html .= "<tr style='$border'>
                    <td style='$tfont' colspan='3'>Sub-Total (Rs.)</td>
                    <td style='$tfont'>$tamt</td>
                    <td style='$tfont'>$tsamt</td>
                </tr>";
            $html .= "</table>";
            if($claimDetail['status'] == 'Submitted'){
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
//            echo "<pre>";print_r($claimDetail);
//            echo "<pre>";print_r($billDetails);
//            die;
            $mpdf->WriteHTML($html);
            $name = "reimbursement_opd_".date('Y_m_d_H_i_s').".pdf";
	    $file = $mpdf->Output($name, 'I');
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . basename($file) . '"');
            header("Cache-Control: max-age=0");
            readfile($file);
            
        }
        Yii::$app->getSession()->setFlash('danger', 'Invalid Url.');
        return $this->redirect($url);
    }
//    public function actionDeleteipdclaim(){
//        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
//        $menuid = Yii::$app->utility->encryptString($menuid);
//        $url = Yii::$app->homeUrl."employee/ipdreimbursement?securekey=$menuid";
//        if(isset($_GET['ipd_id']) AND !empty($_GET['ipd_id'])){
//            $ipd_id = Yii::$app->utility->decryptString($_GET['ipd_id']);
//
//        }
//        Yii::$app->getSession()->setFlash('danger', 'Invalid Url Param .');
//        return $this->redirect($url);
//    }
}