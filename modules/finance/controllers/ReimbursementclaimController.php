<?php

namespace app\modules\finance\controllers;
use Yii;
class ReimbursementclaimController extends \yii\web\Controller
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
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('index', ['menuid'=>$menuid]);
    }
    public function actionPendingopdclaims()
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $this->layout = '@app/views/layouts/admin_layout.php';
        $claims = Yii::$app->finance->fn_get_opd_claims(NULL, NULL, NULL, 'In-Process,Submitted');
        return $this->render('pendingopdclaims', ['menuid'=>$menuid, 'claims'=>$claims]);
    }
    
    public function actionViewallopdclaims(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $this->layout = '@app/views/layouts/admin_layout.php';
        $claims = Yii::$app->finance->fn_get_opd_claims(NULL, NULL, NULL, 'Sanctioned,Rejected');
        return $this->render('viewallopdclaims', ['menuid'=>$menuid, 'claims'=>$claims]);
    }
    
    public function actionViewsancclaims(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."finance/reimbursementclaim/pendingopdclaims?securekey=$menuid";
        if(isset($_GET['opd_id']) AND !empty($_GET['opd_id']) AND isset($_GET['ec']) AND !empty($_GET['ec']) AND isset($_GET['entitle_id']) AND !empty($_GET['entitle_id'])){
            $opd_id = Yii::$app->utility->decryptString($_GET['opd_id']);
            $entitle_id = Yii::$app->utility->decryptString($_GET['entitle_id']);
            $ec = Yii::$app->utility->decryptString($_GET['ec']);
            if(empty($ec) OR empty($opd_id)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid Url Params Found.'); 
                return $this->redirect($url);
            }
            
            $details= Yii::$app->finance->fn_get_opd_bill_details($opd_id,$ec,NULL);
            $claimdetails= Yii::$app->finance->fn_get_opd_claims($opd_id,$entitle_id,$ec,NULL);
            if(empty($details) OR empty($claimdetails)){
                Yii::$app->getSession()->setFlash('danger', 'No Claim / OPD details found.'); 
                return $this->redirect($url);
            }
            $this->layout = '@app/views/layouts/admin_layout.php';
            return $this->render('viewsancclaims', ['menuid'=>$menuid, 'entitle_id'=>$entitle_id, 'details'=>$details, 'claimdetails'=>$claimdetails, 'opd_id'=>$opd_id, 'ec'=>$ec]);
        }
        Yii::$app->getSession()->setFlash('danger', 'Invalid Params Found.'); 
        return $this->redirect($url);
    }
    public function actionViewopdclaims(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."finance/reimbursementclaim/pendingopdclaims?securekey=$menuid";
        if(isset($_POST['Claim']) AND !empty($_POST['Claim'])){
            $data = $_POST['Claim'];
            $status = "";
            if(isset($_POST['ip']) AND !empty($_POST['ip'])){
                $status = "In-Process";
                $msg = "Claim Application has been Updated Successfully.";
            }elseif(isset($_POST['sanc']) AND !empty($_POST['sanc'])){
                $status = "Sanctioned";
                $msg = "Claim Application Sanctioned Successfully.";
            }elseif(isset($_POST['reject']) AND !empty($_POST['reject'])){
                $status = "Rejected";
                $msg = "Claim Application Rejected Successfully.";
            }else{
                Yii::$app->getSession()->setFlash('danger', 'Invalid Status.'); 
                return $this->redirect($url);
            }
            
            $opd_id = $ec = $bill_id = $bill_amt = "";
            $newArray = "";
            $i=0;
            $totalSanction =  0;
            foreach($data as $key=>$d){
                $entitle_id = Yii::$app->utility->decryptString($d['entitle_id']);
                $opd_id = Yii::$app->utility->decryptString($d['opd_id']);
                $ec = Yii::$app->utility->decryptString($d['ec']);
                $bill_id = Yii::$app->utility->decryptString($d['bill_id']);
                $bill_amt = Yii::$app->utility->decryptString($d['bill_amt']);
                $sanctioned_amt = $d['sanctioned_amt'];
                
                if(empty($entitle_id) OR empty($opd_id) OR empty($ec) OR empty($bill_id) OR empty($bill_amt)){
                    Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Params Found.'); 
                    return $this->redirect($url);
                }
                
                if(!is_numeric($sanctioned_amt)){
                    Yii::$app->getSession()->setFlash('danger', 'Amount should be in number.'); 
                    return $this->redirect($url);
                }
                
                if($sanctioned_amt > $bill_amt){
                    Yii::$app->getSession()->setFlash('danger', 'Sanctioned Amount cannot be greater than Claimed Amount.'); 
                    return $this->redirect($url);
                }
                $newArray[$i]['opd_id'] = $opd_id;
                $newArray[$i]['ec'] = $ec;
                $newArray[$i]['bill_id'] = $bill_id;
                $newArray[$i]['sanctioned_amt'] = $sanctioned_amt;
                $i++;   
                $totalSanction = $totalSanction+$sanctioned_amt;
            }
            $getEntitlement = Yii::$app->finance->fn_get_medical_entitlement($ec,$entitle_id);
            if(empty($getEntitlement)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. No Entitlement Found.'); 
                return $this->redirect($url);
            }
                        
            $pending= $getEntitlement['yearly_entitlement'] - $getEntitlement['utilized'];
            if($pending < $totalSanction){
                Yii::$app->getSession()->setFlash('danger', 'Employee don\'t have sufficient balance.'); 
                return $this->redirect($url);
            }
            $totalReq = count($newArray);
            
            $emp_code = $opd_id ="";
            foreach($newArray as $new){
                Yii::$app->finance->fn_update_opd_claims("Bill", $new['sanctioned_amt'], Yii::$app->user->identity->e_id, $new['bill_id'], $new['opd_id'], $new['ec'], NULL, NULL);
                $opd_id = $new['opd_id'];
                $emp_code = $new['ec'];
                
            }
            
            Yii::$app->finance->fn_update_opd_claims("Claim", NULL, Yii::$app->user->identity->e_id, NULL, $opd_id, $emp_code, $totalSanction, $status);
            
            $totalUtili=NULL;
            if($status == 'Sanctioned'){
                $totalUtili = $totalSanction+$getEntitlement['utilized'];
                Yii::$app->finance->fn_add_medical_entitlement($entitle_id, $ec, NULL,NULL, $totalUtili);
            }
            /*
             * Logs
             */
            $logs['entitle_id']=$entitle_id;
            $logs['emp_code']=$ec;
            $logs['totalSanction']=$totalSanction;
            $logs['totalUtili']=$totalUtili;
            $logs['status']=$status;
            $logs['billdetail']=$newArray;
            $jsonlogs = json_encode($logs);
            
            Yii::$app->utility->activities_logs("Reimbursement", NULL, $ec, $jsonlogs, $msg);
            
            Yii::$app->getSession()->setFlash('success', $msg); 
            return $this->redirect($url);
        }
        
        if(isset($_GET['opd_id']) AND !empty($_GET['opd_id']) AND isset($_GET['ec']) AND !empty($_GET['ec']) AND isset($_GET['entitle_id']) AND !empty($_GET['entitle_id'])){
            $opd_id = Yii::$app->utility->decryptString($_GET['opd_id']);
            $entitle_id = Yii::$app->utility->decryptString($_GET['entitle_id']);
            $ec = Yii::$app->utility->decryptString($_GET['ec']);
            if(empty($ec) OR empty($opd_id)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid Url Params Found.'); 
                return $this->redirect($url);
            }
            
            $details= Yii::$app->finance->fn_get_opd_bill_details($opd_id,$ec,NULL);
            $claimdetails= Yii::$app->finance->fn_get_opd_claims($opd_id,$entitle_id,$ec,NULL);
            if(empty($details) OR empty($claimdetails)){
                Yii::$app->getSession()->setFlash('danger', 'No Claim / OPD details found.'); 
                return $this->redirect($url);
            }
            $this->layout = '@app/views/layouts/admin_layout.php';
            return $this->render('viewopdclaims', ['menuid'=>$menuid, 'entitle_id'=>$entitle_id, 'details'=>$details, 'claimdetails'=>$claimdetails, 'opd_id'=>$opd_id, 'ec'=>$ec]);
        }
        Yii::$app->getSession()->setFlash('danger', 'Invalid Params Found.'); 
        return $this->redirect($url);
    }
}

