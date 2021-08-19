<?php
namespace app\modules\finance\controllers;
use yii;
class RequisitiontourController extends \yii\web\Controller
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
        $lists = Yii::$app->finance->fn_get_tour_detail('Pending');
        return $this->render('index', ['menuid'=>$menuid, 'lists'=>$lists ]);
    }
    
    public function actionView(){
       
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."finance/requisitiontour/?securekey=$menuid";
        //Update 
        if(isset($_POST['TourClaim']) AND !empty($_POST['TourClaim'])){
            $post = $_POST['TourClaim'];
            $menuid = Yii::$app->utility->decryptString($post['menuid']);
            $req_id = Yii::$app->utility->decryptString($post['req_id']);
            $e_id = Yii::$app->utility->decryptString($post['e_id']);
            $advance_required = Yii::$app->utility->decryptString($post['advance_required']);
            if(empty($menuid) OR empty($advance_required) OR empty($req_id)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid params found.');
                return $this->redirect($url);
            } 
            //echo "1<pre>";print_r($_POST); die;
            $sancAmt="0";
            if(isset($post['sanctioned_amt']) AND !empty($post['sanctioned_amt'])){
                $sancAmt = trim(preg_replace('/[^0-9]/', '', $post['sanctioned_amt']));
            }
            if(isset($post['Submit']) AND !empty($post['Submit'])){
                if($post['Submit'] == '1'){
                    $status="Sanctioned";
                }elseif($post['Submit'] == '2'){
                    $status="Rejected";
                }elseif($post['Submit'] == '3'){
                    $status="Revoked";
                }else{
                    Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid status found.');
                    return $this->redirect($url);
                }                
                $null = NULL;
                
                $Result= Yii::$app->finance->fn_add_update_tour_requisition($null, $req_id, $e_id, $null, $null, $null, $null, $null, $null, $sancAmt, $null, $null, $null, Yii::$app->user->identity->e_id, $status);
                
                /*
                 * Logs
                 */
                $logs['req_id']=$req_id;
                $logs['e_id']=$e_id;
                $logs['sancAmt']=$sancAmt;
                $logs['status']=$status;
                $jsonlogs = json_encode($logs);
                if($Result == '2'){
                    Yii::$app->getSession()->setFlash('success', 'Tour Requisition Application updated successfully.');
                    Yii::$app->utility->activities_logs('Claim', 'finance/requisitiontour/view', $e_id, $jsonlogs, "Tour Requisition Application updated successfully.");
                    return $this->redirect($url);
                }else{
                    Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. No record found for updation. Contact Admin');
                    Yii::$app->utility->activities_logs('Claim', 'finance/requisitiontour/view', $e_id, $jsonlogs, "Tour Requisition. Fraudulent Data Detected. No record found for updation. Contact Admin.");
                    return $this->redirect($url);
                }
            }else{
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid params found.');
                return $this->redirect($url);
            }
        }elseif(isset($_GET['req_id']) AND !empty($_GET['req_id']) AND isset($_GET['e_id']) AND !empty($_GET['e_id'])){
            $req_id = Yii::$app->utility->decryptString($_GET['req_id']);
            $e_id = Yii::$app->utility->decryptString($_GET['e_id']);
            if(empty($req_id) OR empty($e_id)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid params value.');
                return $this->redirect($url);
            }
            $info = Yii::$app->finance->fn_get_tour_detail('Pending',$req_id,$e_id);
            $this->layout = '@app/views/layouts/admin_layout.php';
            return $this->render('view', ['menuid'=>$menuid, 'model'=>$info ]);
        }else{
            Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Url.');
            return $this->redirect($url);
        }
    }
    
    public function actionAllrequisition(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $this->layout = '@app/views/layouts/admin_layout.php';
        
        $lists = Yii::$app->finance->fn_get_tour_detail('Sanctioned,Rejected');
        
        return $this->render('allrequisition', ['menuid'=>$menuid, 'lists'=>$lists]);
    }
    
    public function actionTourclaims(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('tourclaims', ['menuid'=>$menuid]);
    }
    
    //View Claim Details
    public function actionViewclaimdetails(){
        
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."finance/requisitiontour/tourclaims?securekey=$menuid";
        if(isset($_GET['claimid']) AND !empty($_GET['claimid']) AND isset($_GET['reqid']) AND !empty($_GET['reqid'])){
            $claimid = Yii::$app->utility->decryptString($_GET['claimid']);
            $reqid = Yii::$app->utility->decryptString($_GET['reqid']);
            $e_id = Yii::$app->utility->decryptString($_GET['e_id']);
            
            if(empty($claimid) OR empty($reqid) OR empty($e_id)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid params value found.');
                return $this->redirect($url);
            }
            $tourHeader = Yii::$app->finance->fn_get_tour_claim_details($claimid, "Submitted,In-Process", $reqid, $e_id);
            if(empty($tourHeader)){
                Yii::$app->getSession()->setFlash('danger', 'No Record Found.');
                return $this->redirect($url);
            }
            $foodDetails = Yii::$app->finance->fn_get_claim_food_details($claimid, $reqid, $e_id);
            $journyDetails = Yii::$app->finance->fn_get_claim_journey_details($claimid, $reqid, $e_id);
            $convenDetails = Yii::$app->finance->fn_get_claim_conveyance_details($claimid, $reqid, $e_id);
            $haltDetails = Yii::$app->finance->fn_get_claim_halt_details($claimid, $reqid, $e_id);
            $this->layout = '@app/views/layouts/admin_layout.php';
            $reqiotionInfo = Yii::$app->finance->fn_get_tour_detail('Sanctioned',$reqid,$e_id);
            $claimid = Yii::$app->utility->encryptString($claimid);
            $reqid = Yii::$app->utility->encryptString($reqid);
            $e_id = Yii::$app->utility->encryptString($e_id);
//            echo "<pre>";print_r($reqiotionInfo); die;
            return $this->render('viewclaimdetails', [
                'menuid'=>$menuid, 
                'tourHeader'=>$tourHeader, 
                'foodDetails'=>$foodDetails, 
                'journyDetails'=>$journyDetails, 
                'convenDetails'=>$convenDetails,
                'haltDetails'=>$haltDetails,
                'claimid'=>$claimid, 
                'reqid'=>$reqid,
                'e_id'=>$e_id,
                'reqiotionInfo'=>$reqiotionInfo
            ]);
        }
        Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Url.');
        return $this->redirect($url);
    }
    
    public function actionUpdatejourneydetail(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."finance/requisitiontour/tourclaims?securekey=$menuid";
        if(isset($_POST['claimid']) AND !empty($_POST['claimid']) AND isset($_POST['reqid']) AND !empty($_POST['reqid']) AND isset($_POST['Details']) AND !empty($_POST['Details']) AND isset($_POST['e_id']) AND !empty($_POST['e_id'])){
            
            $claimid = Yii::$app->utility->decryptString($_POST['claimid']);
            $reqid = Yii::$app->utility->decryptString($_POST['reqid']);
            $e_id = Yii::$app->utility->decryptString($_POST['e_id']);
            if(empty($claimid) AND empty($reqid) AND empty($e_id)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid params value.');
                return $this->redirect($url);
            }
            $Details = $_POST['Details'];
            $claimid1 = Yii::$app->utility->encryptString($claimid);
            $reqid1 = Yii::$app->utility->encryptString($reqid);
            $e_id1 = Yii::$app->utility->encryptString($e_id);
            $url = Yii::$app->homeUrl."finance/requisitiontour/viewclaimdetails?securekey=$menuid&claimid=$claimid1&reqid=$reqid1&e_id=$e_id1";
            foreach($Details as $d){
                $j_id = Yii::$app->utility->decryptString($d['j_id']);
                $amount = Yii::$app->utility->decryptString($d['amount']);
                $sanc_ticket = Yii::$app->utility->decryptString($d['sanc_ticket']);
                
                $sanc_amt = trim(preg_replace('/[^0-9.]/', '', $d['sanc_amt']));
                $incentive = trim(preg_replace('/[^0-9.]/', '', $d['incentive']));
                
                if(empty($j_id) OR empty($amount) OR empty($sanc_ticket)){
                    Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid params value.');
                    return $this->redirect($url);
                }
                if($sanc_amt > $amount){
                    Yii::$app->getSession()->setFlash('danger', ' Journey Sanctioned Amount Cannot Greater Then Claimed Amount.');
                    return $this->redirect($url);
                }
                $result = Yii::$app->finance->fn_add_update_claim_journey($j_id, $claimid, $reqid, NULL, NULL, NULL, NULL, NULL, "N", "N", NULL, $sanc_ticket, NULL, $sanc_amt, $incentive, $incentive, $e_id);
                //$incentive : this amount is for eligible and sacntioned using same variable 
                
                /*
                 * Logs
                 */
                $logs['role_id']= Yii::$app->user->identity->role;
                $logs['j_id']= $j_id;
                $logs['claimid']= $claimid;
                $logs['reqid']= $reqid;
                $logs['greater_500Km']= "N";
                $logs['greater_8Hrs']= "N";
                $logs['sanc_ticket']= $sanc_ticket;
                $logs['sanc_amt']= $sanc_amt;
                $logs['incentive']= $incentive;
                $logs['emp_code']= $e_id;
                $jsonlogs = json_encode($logs);
                
                if($result != '2'){
                    Yii::$app->utility->activities_logs("Claim", NULL, $e_id, $jsonlogs, "Journey Details Not Updated");
                    Yii::$app->getSession()->setFlash('danger', 'Journey Details Not Updated');
                    return $this->redirect($url);
                }else{
                    Yii::$app->utility->activities_logs("Claim", NULL, $e_id, $jsonlogs, "Journey Details Updated Successfully.");
                }
            }
            Yii::$app->getSession()->setFlash('success', 'Journey Details Updated Successfully.');
            return $this->redirect($url);
        }
        Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Url.');
        return $this->redirect($url);
    }
    
    public function actionUpdatehaltdetail(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."finance/requisitiontour/tourclaims?securekey=$menuid";
        if(isset($_POST['claimid']) AND !empty($_POST['claimid']) AND isset($_POST['reqid']) AND !empty($_POST['reqid']) AND isset($_POST['Halt']) AND !empty($_POST['Halt']) AND isset($_POST['e_id']) AND !empty($_POST['e_id'])){
            
            $claimid = Yii::$app->utility->decryptString($_POST['claimid']);
            $reqid = Yii::$app->utility->decryptString($_POST['reqid']);
            $e_id = Yii::$app->utility->decryptString($_POST['e_id']);
            if(empty($claimid) AND empty($reqid) AND empty($e_id)){
                Yii::$app->getSession()->setFlash('danger', '1Fraudulent Data Detected. Invalid params value.');
                return $this->redirect($url);
            }
            $Details = $_POST['Halt'];
            $claimid1 = Yii::$app->utility->encryptString($claimid);
            $reqid1 = Yii::$app->utility->encryptString($reqid);
            $e_id1 = Yii::$app->utility->encryptString($e_id);
            $url = Yii::$app->homeUrl."finance/requisitiontour/viewclaimdetails?securekey=$menuid&claimid=$claimid1&reqid=$reqid1&e_id=$e_id1";
            foreach($Details as $d){
                $th_id = Yii::$app->utility->decryptString($d['th_id']);
                $charges = Yii::$app->utility->decryptString($d['charges']);
              
                $sanc_amt = trim(preg_replace('/[^0-9.]/', '', $d['sanc_amt']));
                $senc_comp = trim(preg_replace('/[^0-9.]/', '', $d['senc_comp']));
                
                if(empty($th_id) OR empty($charges)){
                    Yii::$app->getSession()->setFlash('danger', '2Fraudulent Data Detected. Invalid params value.');
                    return $this->redirect($url);
                }
                if($sanc_amt > $charges){
                    Yii::$app->getSession()->setFlash('danger', 'Halt Sanctioned Amount Cannot Greater Then Claimed Amount.');
                    return $this->redirect($url);
                }
                $result = Yii::$app->finance->fn_add_update_tour_halt_details($th_id, $claimid, $reqid, NULL, NULL, NULL, NULL, NULL, $sanc_amt,$senc_comp, $e_id);
                /*
                 * Logs
                 */
                $logs['role_id']= Yii::$app->user->identity->role;
                $logs['th_id']= $th_id;
                $logs['claimid']= $claimid;
                $logs['reqid']= $reqid;
                $logs['sanc_amt']= $sanc_amt;
                $logs['senc_comp']= $senc_comp;
                $logs['emp_code']= $e_id;
                $jsonlogs = json_encode($logs);
                
                if($result != '2'){
                    Yii::$app->utility->activities_logs("Claim", NULL, $e_id, $jsonlogs, "Halt Details Not Updated");
                    Yii::$app->getSession()->setFlash('danger', 'Halt Details Not Updated');
                    return $this->redirect($url);
                }
                Yii::$app->utility->activities_logs("Claim", NULL, $e_id, $jsonlogs, "Halt Details Updated Successfully.");
            }
            Yii::$app->getSession()->setFlash('success', 'Halt Details Updated Successfully.');
            return $this->redirect($url);
        }
        Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Url.');
        return $this->redirect($url);
    }
    
    public function actionUpdateconveyance(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."finance/requisitiontour/tourclaims?securekey=$menuid";
        if(isset($_POST['claimid']) AND !empty($_POST['claimid']) AND isset($_POST['reqid']) AND !empty($_POST['reqid']) AND isset($_POST['Conveyance']) AND !empty($_POST['Conveyance']) AND isset($_POST['e_id']) AND !empty($_POST['e_id'])){
            
            $claimid = Yii::$app->utility->decryptString($_POST['claimid']);
            $reqid = Yii::$app->utility->decryptString($_POST['reqid']);
            $e_id = Yii::$app->utility->decryptString($_POST['e_id']);
            if(empty($claimid) AND empty($reqid) AND empty($e_id)){
                Yii::$app->getSession()->setFlash('danger', '1Fraudulent Data Detected. Invalid params value.');
                return $this->redirect($url);
            }
            $Details = $_POST['Conveyance'];
            $claimid1 = Yii::$app->utility->encryptString($claimid);
            $reqid1 = Yii::$app->utility->encryptString($reqid);
            $e_id1 = Yii::$app->utility->encryptString($e_id);
            $url = Yii::$app->homeUrl."finance/requisitiontour/viewclaimdetails?securekey=$menuid&claimid=$claimid1&reqid=$reqid1&e_id=$e_id1";
            foreach($Details as $d){
                $tc_id = Yii::$app->utility->decryptString($d['tc_id']);
                $amount = Yii::$app->utility->decryptString($d['amount']);
                $sanc_amt = trim(preg_replace('/[^0-9.]/', '', $d['sanctioned_amount']));
                if(empty($tc_id) OR empty($amount)){
                    Yii::$app->getSession()->setFlash('danger', '2Fraudulent Data Detected. Invalid params value.');
                    return $this->redirect($url);
                }
                if($sanc_amt > $amount){
                    Yii::$app->getSession()->setFlash('danger', 'Conveyance Sanctioned Amount Cannot Greater Then Claimed Amount.');
                    return $this->redirect($url);
                }
                $result = Yii::$app->finance->fn_add_update_claim_conveyance($tc_id, $claimid, $reqid, NULL, NULL, NULL, NULL, NULL, NULL, NULL, $sanc_amt, $e_id);
                /*
                 * Logs
                 */
                $logs['role_id']= Yii::$app->user->identity->role;
                $logs['tc_id']= $tc_id;
                $logs['claimid']= $claimid;
                $logs['reqid']= $reqid;
                $logs['sanc_amt']= $sanc_amt;
                $logs['emp_code']= $e_id;
                $jsonlogs = json_encode($logs);
                if($result != '2'){
                    Yii::$app->utility->activities_logs("Claim", NULL, $e_id, $jsonlogs, "Conveyance Details Not Updated");
                    
                    Yii::$app->getSession()->setFlash('danger', 'Conveyance Details Not Updated');
                    return $this->redirect($url);
                }
                Yii::$app->utility->activities_logs("Claim", NULL, $e_id, $jsonlogs, "Conveyance Details Updated Successfully");
            }
            Yii::$app->getSession()->setFlash('success', 'Conveyance Details Updated Successfully.');
            return $this->redirect($url);
        }
        Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Url.');
        return $this->redirect($url);
    }
    
    public function actionUpdatefooddetails(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."finance/requisitiontour/tourclaims?securekey=$menuid";
        if(isset($_POST['claimid']) AND !empty($_POST['claimid']) AND isset($_POST['reqid']) AND !empty($_POST['reqid']) AND isset($_POST['Food']) AND !empty($_POST['Food']) AND isset($_POST['e_id']) AND !empty($_POST['e_id'])){
            
            $claimid = Yii::$app->utility->decryptString($_POST['claimid']);
            $reqid = Yii::$app->utility->decryptString($_POST['reqid']);
            $e_id = Yii::$app->utility->decryptString($_POST['e_id']);
            if(empty($claimid) AND empty($reqid) AND empty($e_id)){
                Yii::$app->getSession()->setFlash('danger', '1Fraudulent Data Detected. Invalid params value.');
                return $this->redirect($url);
            }
            $Details = $_POST['Food'];
            $claimid1 = Yii::$app->utility->encryptString($claimid);
            $reqid1 = Yii::$app->utility->encryptString($reqid);
            $e_id1 = Yii::$app->utility->encryptString($e_id);
            $url = Yii::$app->homeUrl."finance/requisitiontour/viewclaimdetails?securekey=$menuid&claimid=$claimid1&reqid=$reqid1&e_id=$e_id1";
            foreach($Details as $d){
                $tf_id = Yii::$app->utility->decryptString($d['tf_id']);
                $amount = Yii::$app->utility->decryptString($d['amount']);
                $sanc_amt = trim(preg_replace('/[^0-9.]/', '', $d['sanctioned_amount']));
                if(empty($tf_id) OR empty($amount)){
                    Yii::$app->getSession()->setFlash('danger', '2Fraudulent Data Detected. Invalid params value.');
                    return $this->redirect($url);
                }
                if($sanc_amt > $amount){
                    Yii::$app->getSession()->setFlash('danger', 'Food Sanctioned Amount Cannot Greater Then Claimed Amount.');
                    return $this->redirect($url);
                }
                $result = Yii::$app->finance->fn_add_update_claim_food($tf_id, $claimid, $reqid, NULL, NULL, NULL, $sanc_amt, $e_id);
                /*
                 * Logs
                 */
                $logs['role_id']= Yii::$app->user->identity->role;
                $logs['tf_id']= $tf_id;
                $logs['claimid']= $claimid;
                $logs['reqid']= $reqid;
                $logs['sanc_amt']= $sanc_amt;
                $logs['emp_code']= $e_id;
                $jsonlogs = json_encode($logs);
                
                if($result != '2'){
                    Yii::$app->utility->activities_logs("Claim", NULL, $e_id, $jsonlogs, "Food Details Not Updated");
                    
                    Yii::$app->getSession()->setFlash('danger', 'Food Details Not Updated');
                    return $this->redirect($url);
                }
                Yii::$app->utility->activities_logs("Claim", NULL, $e_id, $jsonlogs, "Food Details Updated Successfully.");
            }
            Yii::$app->getSession()->setFlash('success', 'Food Details Updated Successfully.');
            return $this->redirect($url);
        }
        Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Url.');
        return $this->redirect($url);
    }
    
    public function actionFinalsubmission(){
//        echo "<pre>";print_r($_POST['Final']); die;
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."finance/requisitiontour/tourclaims?securekey=$menuid";
        if(isset($_POST['Final']) AND !empty($_POST['Final'])){
            $post = $_POST['Final'];
            $claimid = Yii::$app->utility->decryptString($post['claimid']);
            $reqid = Yii::$app->utility->decryptString($post['reqid']);
            $e_id = Yii::$app->utility->decryptString($post['e_id']);
            $total_sanc = Yii::$app->utility->decryptString($post['total_sanc']);
            if(empty($claimid) AND empty($reqid) AND empty($e_id) AND empty($total_sanc)){
                Yii::$app->getSession()->setFlash('danger', '1Fraudulent Data Detected. Invalid params value.');
                return $this->redirect($url);
            }
            $status = $msg = "";
            //echo "<pre>";print_r($post); 
            if(isset($post['sanction']) AND !empty($post['sanction'])){
                $info = Yii::$app->finance->fn_get_tour_detail('Sanctioned',$reqid,$e_id);
                if($info['sanctioned_adv_amount'] > 0){
                    $total_sanc = $total_sanc - $info['sanctioned_adv_amount'];
                }
//                echo "<pre>";print_r($info); die; 
                $status = "Sanctioned";
                $msg = "Tour Application : Sanctioned Successfully.";
            }elseif(isset($post['process']) AND !empty($post['process'])){
                $status = "In-Process";
                $msg = "Tour Application : In-Process Updated Successfully.";
                $total_sanc = NULL;
            }elseif(isset($post['revoke']) AND !empty($post['revoke'])){
                $status = "Revoked";
                $msg = "Tour Application : Revoked Successfully.";
                $total_sanc = NULL;
            }elseif(isset($post['reject']) AND !empty($post['reject'])){
                $status = "Rejected";
                $msg = "Tour Application : Rejected Successfully.";
                $total_sanc = NULL;
            }
//            die;
            if(empty($status)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Status Params.');
                return $this->redirect($url);
            }
            $result = Yii::$app->finance->fn_add_update_tour_claim_header($claimid,$reqid, $e_id, NULL, NULL, NULL, NULL, NULL, NULL, NULL, $total_sanc, Yii::$app->user->identity->e_id, $status);
            
            /*
             * Logs
             */
            $logs['role_id']= Yii::$app->user->identity->role;
            $logs['claimid']= $claimid;
            $logs['reqid']= $reqid;
            $logs['emp_code']= $e_id;
            $logs['total_sanc']= $total_sanc;
            $logs['status']= $status;
            $jsonlogs = json_encode($logs);
                
            if($result == '2'){
                Yii::$app->utility->activities_logs("Claim", NULL, $e_id, $jsonlogs, $msg);
                
                Yii::$app->getSession()->setFlash('success', $msg);
                return $this->redirect($url);
            }else{
                Yii::$app->utility->activities_logs("Claim", NULL, $e_id, $jsonlogs, "Claim not updated. Contact Admin.");
                
                Yii::$app->getSession()->setFlash('danger', 'Claim not updated. Contact Admin.');
                return $this->redirect($url);
            }
        }
        Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Url.');
        return $this->redirect($url);
        
    }
    
}

