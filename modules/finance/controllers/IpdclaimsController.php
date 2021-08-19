<?php

namespace app\modules\finance\controllers;
use Yii;
class IpdclaimsController extends \yii\web\Controller
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
        $allClaims = Yii::$app->finance->fn_get_ipd_claims(NULL, NULL, "Submitted,In-Process");
        return $this->render('index', ['menuid'=>$menuid, 'allClaims'=>$allClaims]);
    }
    
    public function actionIpdclaimaction(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."finance/ipdclaims/index?securekey=$menuid";
        if(isset($_GET['ipd_id']) AND !empty($_GET['ipd_id']) AND isset($_GET['ec']) AND !empty($_GET['ec'])){
            $ipd_id = Yii::$app->utility->decryptString($_GET['ipd_id']); 
            $ec = Yii::$app->utility->decryptString($_GET['ec']); 
            if(empty($ipd_id) AND empty($ec)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid Params Value Found.'); 
                return $this->redirect($url);
            }
            $claimDetails = Yii::$app->finance->fn_get_ipd_claims($ipd_id, $ec, "Submitted,In-Process");
            $details = Yii::$app->finance->fn_get_ipd_details($ec, $ipd_id);
            if(empty($claimDetails)){
                Yii::$app->getSession()->setFlash('danger', 'NO IPD Claim Detail Found.'); 
                return $this->redirect($url);
            }
            $ipd_id = Yii::$app->utility->encryptString($ipd_id);
            $ec = Yii::$app->utility->encryptString($ec);
            $this->layout = '@app/views/layouts/admin_layout.php';
            return $this->render('ipdclaimaction', ['menuid'=>$menuid, 'ipd_id'=>$ipd_id, 'ec'=>$ec, 'claimDetail'=>$claimDetails,'details'=>$details]);
        }
        Yii::$app->getSession()->setFlash('danger', 'Invalid Params Found.'); 
        return $this->redirect($url);
    }
    
    public function actionUpdateipdbill(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."finance/ipdclaims/index?securekey=$menuid"; 
        if(isset($_POST['Bill']) AND !empty($_POST['Bill']) AND isset($_POST['ipd_id']) AND !empty($_POST['ipd_id']) AND isset($_POST['ec']) AND !empty($_POST['ec'])){
            $ipd_id = Yii::$app->utility->decryptString($_POST['ipd_id']);
            $ec = Yii::$app->utility->decryptString($_POST['ec']);
            if(empty($ipd_id) AND empty($ec)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid Params Value Found.'); 
                return $this->redirect($url);
            }
            $claimDetails = Yii::$app->finance->fn_get_ipd_claims($ipd_id, $ec, "Submitted,In-Process");
            $ipd_id1 = Yii::$app->utility->encryptString($ipd_id);
            $ec1 = Yii::$app->utility->encryptString($ec);
            $url = Yii::$app->homeUrl."finance/ipdclaims/ipdclaimaction?securekey=$menuid&ipd_id=$ipd_id1&ec=$ec1";
            if(empty($claimDetails)){
                Yii::$app->getSession()->setFlash('danger', 'NO IPD Claim Detail Found.'); 
                return $this->redirect($url);
            }
            
            $bills = $_POST['Bill'];
            
            $newArray = array();
            $i=0;
            foreach ($bills as $bill){
                $bill_id = Yii::$app->utility->decryptString($bill['bill_id']);
                $bill_amt = Yii::$app->utility->decryptString($bill['bill_amt']);
                
                if(empty($bill_id) OR empty($bill_amt)){
                    Yii::$app->getSession()->setFlash('danger', 'Invalid Params Value Found.'); 
                    return $this->redirect($url);
                }
                $sanc_amt = trim(preg_replace('/[^0-9.-]/', '', $bill['sanc_amt']));
                if($sanc_amt > $bill_amt){
                    Yii::$app->getSession()->setFlash('danger', 'Invalid Params Value Found.'); 
                    return $this->redirect($url);
                }
                $newArray[$i]['bill_id'] = $bill_id;
                $newArray[$i]['sanc_amt'] = $sanc_amt;
                $i++;
            }
            //echo "<pre>";print_r($newArray); die;
            foreach($newArray as $new){
                $bill_id = $new['bill_id'];
                $sanc_amt = $new['sanc_amt'];
                Yii::$app->finance->fn_add_update_ipd_details("U", $bill_id, $ec, $ipd_id, NULL, NULL, NULL, NULL, $sanc_amt);
                
            }
            /*
            * Logs
            */
           $logs['action_type']="U";
           $logs['emp_code']=$ec;
           $logs['ipd_id']=$ipd_id;
           $logs['bill_details']=$newArray;
            $jsonlogs = json_encode($logs);
            Yii::$app->utility->activities_logs("Reimbursement", NULL, $ec, $jsonlogs, "IPD CLaim : Bill Sanctioned Amount Updated Successfully.");
            Yii::$app->getSession()->setFlash('success', 'IPD CLaim : Bill Sanctioned Amount Updated Successfully.'); 
            return $this->redirect($url);
            
        }
        Yii::$app->getSession()->setFlash('danger', 'Invalid Params Found.'); 
        return $this->redirect($url);
    }
    
    public function actionUpdateipdclaim(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."finance/ipdclaims/index?securekey=$menuid"; 
        if(isset($_POST['IPD']) AND !empty($_POST['IPD'])){
            $post = $_POST['IPD'];
            $ipd_id = Yii::$app->utility->decryptString($post['ipd_id']);
            $ec = Yii::$app->utility->decryptString($post['ec']);
            $action_type = Yii::$app->utility->decryptString($post['action_type']);
            if(empty($ipd_id) OR empty($ec) OR empty($action_type)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid Params Value Found.'); 
                return $this->redirect($url);
            }
            $sancTotal = NULL;
            $msg = "IPD Claim Updated Succesfully.";
            if($action_type == 'Sanctioned'){
                $details = Yii::$app->finance->fn_get_ipd_details($ec, $ipd_id);
                $sancTotal = 0;
                if(!empty($details)){
                    foreach($details as $d){
                        $sancTotal = $sancTotal+$d['sanc_amt'];
                    }
                }
                $msg = "IPD Claim Sanctioned Succesfully.";
            }
            
            $remarks = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['remarks']));
            $result = Yii::$app->finance->fn_add_update_ipd_claims($ipd_id, $ec, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, $sancTotal, $action_type, $remarks);
            
            /*
             * $logs
             */
            $logs['ipd_id']=$ipd_id;
            $logs['emp_code']=$ec;
            $logs['sancTotal']=$sancTotal;
            $logs['status']=$action_type;
            $logs['remarks']=$remarks;
            $jsonlogs = json_encode($logs);
            
            if($result == '2'){
                
                Yii::$app->utility->activities_logs("Reimbursement", NULL, $ec, $jsonlogs, $msg);
                
                Yii::$app->getSession()->setFlash('success', $msg); 
            }else{
                Yii::$app->utility->activities_logs("Reimbursement", NULL, $ec, $jsonlogs, "IPD NOT Updated. Contact Admin.");
                
                Yii::$app->getSession()->setFlash('danger', 'IPD NOT Updated. Contact Admin.'); 
            }
            return $this->redirect($url);
        }
        Yii::$app->getSession()->setFlash('danger', 'Invalid Params Found.'); 
        return $this->redirect($url);
        
    }
    public function actionViewallipdclaims(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('viewallipdclaims', ['menuid'=>$menuid]);
    }
}