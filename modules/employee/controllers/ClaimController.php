<?php

namespace app\modules\employee\controllers;
use yii;
use mPDF;
use app\models\HrTourRequisition;
class ClaimController extends \yii\web\Controller
{
    public function beforeAction($action){
        
        if (!\Yii::$app->user->isGuest) {
            if(isset($_GET['securekey']) AND !empty($_GET['securekey'])){
                $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
				
                if(empty($menuid)){ return $this->redirect(Yii::$app->homeUrl); }

                $chkValid = Yii::$app->utility->validate_url($menuid);
				// die($chkValid);
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
        return $this->render('index');
    }
    
 
    public function actionTourrequisition()
    {
        $model = new HrTourRequisition();
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."employee/claim/tourrequisition?securekey=$menuid";
        $model->advance_required = "N";
        $model->advance_amount = "0";
        $projectlist = Yii::$app->hr_utility->hr_get_project_list();
        $tourtype = Yii::$app->finance->get_tour_type();
        $tourlocation = Yii::$app->hr_utility->hr_get_city_list();
        //Edit
        if(isset($_GET['req_id']) AND !empty($_GET['req_id'])){
            $req_id = Yii::$app->utility->decryptString($_GET['req_id']);
            if(empty($req_id)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid ID.');
                return $this->redirect($url);
            }
            $lists = Yii::$app->finance->fn_get_tour_detail('Draft,Revoked',$req_id);
            $model->req_id = Yii::$app->utility->encryptString($req_id);
            $model->project_id = base64_encode($lists['project_id']);
            $model->tour_type = base64_encode($lists['tour_type']);
            $model->tour_location = base64_encode($lists['tour_location']);
            $model->advance_required = $lists['advance_required'];
            $model->advance_amount = $lists['advance_amount'];
            $model->start_date = date('d-m-Y', strtotime($lists['start_date']));
            $model->end_date = date('d-m-Y', strtotime($lists['end_date']));
            $model->purpose = $lists['purpose'];
        }
        
        if(isset($_POST['HrTourRequisition']) AND !empty($_POST['HrTourRequisition'])){
            //echo "<pre>";print_r($_POST); die;
            $post = $_POST['HrTourRequisition'];
            $req_id = NULL;
            if(!empty($post['req_id'])){
                $req_id = Yii::$app->utility->decryptString($post['req_id']);
                if(empty($req_id)){
                    Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid ID');
                    return $this->redirect($url);
                }
            }
            $project_id = base64_decode($post['project_id']);
            $tour_type = base64_decode($post['tour_type']);
            $tour_location = base64_decode($post['tour_location']);
            if(empty($project_id) AND empty($tour_type) AND empty($tour_location)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected.');
                return $this->redirect($url);
            }
            
            $advance_required = $post['advance_required'];
            $advance_amount = $post['advance_amount'];
            $purpose = $post['purpose'];
            $start_date = date('Y-m-d', strtotime($post['start_date']));
            $end_date = date('Y-m-d', strtotime($post['end_date']));
            if(isset($post['Submit']) AND !empty($post['Submit'])){
                $status="Pending";
                $msg = "Application Submitted successfully";
                $msg1 = "Application Submitted successfully";
            }elseif(isset($post['Draft']) AND !empty($post['Draft'])){
                $status="Draft";
                $msg = "Application Submitted as Draft Successfully";
                $msg1 = "Application Submitted as Draft Successfully";
            }
            $param_role_id = Yii::$app->user->identity->role;
            $param_e_id = Yii::$app->user->identity->e_id;
            $param_dept_id = Yii::$app->user->identity->dept_id;
            
            $result = Yii::$app->finance->fn_add_update_tour_requisition($param_role_id, $req_id, $param_e_id, $param_dept_id, $project_id, $tour_type, $tour_location, $advance_required, $advance_amount, NULL, $start_date, $end_date, $purpose, NULL, $status);
            /*
             * Add Logs
             */
            $logs['role_id']=$param_role_id;
            $logs['req_id']=$req_id;
            $logs['employee_code']=$param_e_id;
            $logs['dept_id']=$param_dept_id;
            $logs['project_id']=$project_id;
            $logs['tour_type']=$tour_type;
            $logs['tour_location']=$tour_location;
            $logs['advance_required']=$advance_required;
            $logs['advance_amount']=$advance_amount;
            $logs['start_date']=$start_date;
            $logs['end_date']=$end_date;
            $logs['purpose']=$purpose;
            $logs['status']=$status;
            $jsonlogs = json_encode($logs);
            
            if($result == '1'){
                Yii::$app->utility->activities_logs('Claim', 'employee/claim/tourrequisition', NULL, $jsonlogs, $msg);
                
                Yii::$app->getSession()->setFlash('success', $msg);
                return $this->redirect($url);
            }elseif($result == '3'){
                Yii::$app->utility->activities_logs('Claim', 'employee/claim/tourrequisition', NULL, $jsonlogs, $msg1);
                Yii::$app->getSession()->setFlash('success', $msg1);
                return $this->redirect($url);
            }            
        }
        
        $lists = Yii::$app->finance->fn_get_tour_detail('Draft,Revoked');
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('tourrequisition',[
            'model'=>$model, 
            'menuid'=>$menuid, 
            'lists'=>$lists, 
            'projectlist'=>$projectlist, 
            'tourtype'=>$tourtype, 
            'tourlocation'=>$tourlocation
        ]);
    }
    
    public function actionViewtourrequisition(){
        if(isset($_GET['securekey']) AND !empty($_GET['securekey'])){
            $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
            $menuid = Yii::$app->utility->encryptString($menuid);
            $lists = Yii::$app->finance->fn_get_tour_detail('Pending,In-Process,Sanctioned,Rejected');
            $this->layout = '@app/views/layouts/admin_layout.php';
            return $this->render('viewrequisition', ['menuid'=>$menuid, 'lists'=>$lists]);
        }
    }
    
    public function actionDownloadtr(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."employee/claim/viewtourrequisition?securekey=$menuid";
        if(isset($_GET['key']) AND !empty($_GET['key'])){
            $req_id = Yii::$app->utility->decryptString($_GET['key']);
            if(empty($req_id)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid params value.');
                return $this->redirect($url);
            }
            
            $details = Yii::$app->finance->fn_get_tour_detail('Pending,In-Process,Sanctioned,Rejected', $req_id);
            if(empty($details)){
                Yii::$app->getSession()->setFlash('danger', 'No Records Found.');
                return $this->redirect($url);
            }

          //  require_once './mpdf/mpdf.php';
            $mpdf = new \Mpdf\Mpdf();
            $date = date('d-m-Y H:i');
		$header = "<div style='text-align:center;'><p style='margin:0px; font-size:18px;font-weight:bold;font-family:arial;'></b>".ORGANAZATION_NAME."</b></p> <br><p style='margin:0px;font-size:16px;font-weight:bold;font-family:arial;'>Tour Requisition</p></div>";

            $mpdf->WriteHTML($header);
            if($details['status'] == 'Sanctioned'){

                     $hh = "<p style='font-size:15px;color:red;font-family:arial;'>This application is already sanctioned</p>";
                    $mpdf->WriteHTML($hh);
            }
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
            $html .= "<p style='font-size:11px;font-weight:bold;margin:0px;font-family:arial;'>Personal Info:-</p>";
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
            $appdtd = date('d-m-Y', strtotime($details['submitted_on']));
            $start = date('d-m-Y', strtotime($details['start_date']));
            $end = date('d-m-Y', strtotime($details['end_date']));
            $project = $details['project_name'];
            $city = $details['city_name'];

            $purpose = $details['purpose'];
            $tfont = "$border padding:8px;font-size:13px;font-family:arial;";	
            $amt = $details['advance_amount'];
            $advance_amount = Yii::$app->utility->numberTowords($details['advance_amount']);

            $sanctioned_adv_amount = $details['sanctioned_adv_amount'];

            $san_amount = Yii::$app->utility->numberTowords($details['sanctioned_adv_amount']);

            //echo "<pre>";print_r(Yii::$app->user->identity);
            //echo "<pre>";print_r($details); die;
            $dt = date('d-M-Y');
            $t = "padding:0px;font-size:13px;font-family:arial;";
            $html .= "<br><p style='font-size:11px;font-weight:bold;margin:0px;'>Advanced Details:-</p>";
            $html .= "<table width='100%' style='$border border-collapse: collapse; overflow: wrap; margin-bottom:80px;'>
                    <tr style='$border'>
                            <td style='$tfont'>Application Date</td>
                            <td style='$tfont' colspan='3'>$appdtd</td>
                    </tr>
                    <tr style='$border'>
                            <td style='$tfont width:20%'>Project</td>
                            <td style='$tfont '>$project</td>
                            <td style='$tfont width:20%'>Tour Type</td>
                            <td style='$tfont'>$city</td>
                    </tr>
                    <tr style='$border'>
                            <td style='$tfont'>Purpose</td>
                            <td style='$tfont' colspan='3'>$purpose</td>
                    </tr>
                    <tr style='$border'>
                            <td style='$tfont width:20%'>Start Date</td>
                            <td style='$tfont'>$start</td>
                            <td style='$tfont width:20%'>End Date</td>
                            <td style='$tfont'>$end</td>
                    </tr>
                    <tr style='$border'>
                            <td style='$tfont'><b>Amount</b></td>
                            <td style='$tfont' colspan='3'><b>Rs. $amt/- (Rupees $advance_amount)</b></td>
                    </tr>
                    <tr style='$border'>
                            <td style='$tfont'><b>Sanctioned</b></td>
                            <td style='$tfont' colspan='3'><b>Rs. $sanctioned_adv_amount/-</b></td>
                    </tr>
            </table>
            <div style='margin-bottom:50px;'>
                    <p style='font-size:13px;margin:0px;'>Applicant</p>
                    <p style='font-size:13px;margin:0px;'>$n</p>
                    <p style='font-size:13px;margin:0px;'>Date: $dt</p>
            </div>
            <div style='margin-bottom:50px;'>
                    <table width='100%' style='border-collapse: collapse; overflow: wrap;'>
                            <tr style=''>
                                    <td style='$t width:33%;'>Verified By<br>(FLA/SLA)<br>Name:<br>Date:</td>
                                    <td style='$t width:33%;'>Recommended By<br>(HOD)<br>Name:<br>Date:</td>
                                    <td style='$t width:33%'>Approved By<br>(Director/ED/DG)<br>Name:<br>Date:</td>
                            </tr>
                    </table>
    <hr>
    <p style='font-size:13px;margin:0px;'>Received Cash <b>Rs. $sanctioned_adv_amount/-</b></p>
    <p style='font-size:13px;margin:0px;'>(Rupees $san_amount)</p>

    <div style='margin-top:50px;'>
                    <p style='font-size:13px;margin:0px;'>Sign</p>
                    <p style='font-size:13px;margin:0px;'>$n</p>
                    <p style='font-size:13px;margin:0px;'>Date: $dt</p>
            </div>
            </div>


            ";
            $mpdf->WriteHTML($html);
            $name = "TourRequisition_".date('Y_m_d_H_i_s').".pdf";
	    $file = $mpdf->Output($name, 'I');
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . basename($file) . '"');
            header("Cache-Control: max-age=0");
            readfile($file);
        }
        Yii::$app->getSession()->setFlash('danger', 'Invalid Url.');
        return $this->redirect($url);
        
    }
     
    public function actionTourclaims()
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $lists  = Yii::$app->finance->fn_get_tour_detail('Sanctioned');
        $drafts  = Yii::$app->finance->fn_get_tour_claim_details(NULL, "Draft,Revoked", NULL);
        $allTours  = Yii::$app->finance->fn_get_tour_claim_details(NULL, "In-Process,Sanctioned,Rejected,Submitted", NULL);
        
        $this->layout = '@app/views/layouts/admin_layout.php';
//        echo "<pre>";print_r($allTours); die;
        return $this->render('tourclaims', ['menuid'=>$menuid, 'lists'=>$lists,'drafts'=>$drafts,'allTours'=>$allTours]); 
    }
    public function actionApplytourclaim(){


        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."employee/claim/tourclaims?securekey=$menuid";
        $req_id1= "";
        $model = new HrTourRequisition();
        if(isset($_POST['HrTourRequisition']) AND !empty($_POST['HrTourRequisition'])){
            $post = $_POST['HrTourRequisition'];
            $req_id = Yii::$app->utility->decryptString($post['req_id']);
            if(empty($req_id)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Tour Requisition ID');
                return $this->redirect($url);
            }
            $claimid = NULL;
            if(!empty($post['claim_id'])){
                $claimid = Yii::$app->utility->decryptString($post['claim_id']);
                if(empty($claimid)){
                    Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Claim ID');
                    return $this->redirect($url);
                }
            }
            $tour_location = base64_decode($post['tour_location']);
            $project_id = Yii::$app->utility->decryptString($post['project_id']);
            $tour_type = Yii::$app->utility->decryptString($post['tour_type']);
            if(empty($project_id) OR empty($tour_type)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid Project or Tour Type');
                return $this->redirect($url);
            }
//            echo "<pre>";print_r($post);
//            die();
            $start_date = $post['start_date']." ".$post['start_hh'].":".$post['start_mi'];
            $start_date = date('Y-m-d H:i:s', strtotime($start_date));
            if($start_date == '1970-01-01 00:00:00'){
                Yii::$app->getSession()->setFlash('danger', 'Invalid Start Date.');
                return $this->redirect($url);
            }
            $end_date = $post['end_date']." ".$post['end_hh'].":".$post['end_mi'];
            $end_date = date('Y-m-d H:i:s', strtotime($end_date));
            if($end_date == '1970-01-01 00:00:00'){
                Yii::$app->getSession()->setFlash('danger', 'Invalid End Date.');
                return $this->redirect($url);
            }
            $e_code = Yii::$app->user->identity->e_id;
            $dept_id = Yii::$app->user->identity->dept_id;
            $purpose = $address = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['purpose']));
             
            $result = Yii::$app->finance->fn_add_update_tour_claim_header($claimid,$req_id, $e_code, $project_id, $dept_id, $start_date, $end_date, $tour_location, $purpose, NULL, NULL, NULL, "Draft");
            /*
            * Add Logs
            */

            $logs['claimid'] = $claimid;
            $logs['req_id'] = $req_id;
            $logs['e_code'] = $e_code;
            $logs['project_id'] = $project_id;
            $logs['dept_id'] = $dept_id;
            $logs['purpose'] = $purpose;
            $logs['start_date'] = $start_date;
            $logs['end_date'] = $end_date;
            $logs['tour_location'] = $tour_location;
            $logs['status'] = "Draft";
            $jsonlogs = json_encode($logs);
            if(!empty($result)){
                $result = Yii::$app->utility->encryptString($result);
                if(!empty($claimid)){
                    $result = Yii::$app->utility->encryptString($claimid);
                    Yii::$app->getSession()->setFlash('success', 'Tour Claim Header Updated Succssfully.');
                }
                Yii::$app->utility->activities_logs('Claim', 'employee/claim/applytourclaim', NULL, $jsonlogs, "Tour Claim Header Updated Succssfully.");
                $req_id = Yii::$app->utility->encryptString($req_id);
                $url = Yii::$app->homeUrl."employee/claim/otherdetails?securekey=$menuid&claimid=$result&reqid=$req_id";
                return $this->redirect($url);
            }
            Yii::$app->getSession()->setFlash('danger', 'Claim details not saved. Contact Admin.');
            return $this->redirect($url);
        }
        if(isset($_GET['id']) AND !empty($_GET['id'])){
            $req_id = Yii::$app->utility->decryptString($_GET['id']);
            if(empty($req_id)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Tour Requisition ID');
                return $this->redirect($url);
            }
            $data['projectlist'] = Yii::$app->hr_utility->hr_get_project_list();
            $data['tourtype'] = Yii::$app->finance->get_tour_type();
            $data['tourlocation'] = Yii::$app->hr_utility->hr_get_city_list();
            $lists  = Yii::$app->finance->fn_get_tour_detail('Sanctioned',$req_id);
            $tour_type = $lists['tour_type'];
            $advamt = $lists['advance_amount'];
            $claimid = "";
            $istourHeader= "";
            if(isset($_GET['claimid']) AND !empty($_GET['claimid'])){
                $claimid = Yii::$app->utility->decryptString($_GET['claimid']);
                if(empty($claimid)){
                    Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Tour Claim ID');
                    return $this->redirect($url);
                }
                $istourHeader= "Yes";
                $lists  = Yii::$app->finance->fn_get_tour_claim_details($claimid, "Draft,Revoked", $req_id);
                $lists['tour_location']= $lists['location'];
                $lists['tour_type']= $tour_type;
                $lists['advance_amount']= $advamt;
                $claimid = Yii::$app->utility->encryptString($claimid);
                $req_id1 = Yii::$app->utility->encryptString($req_id);
            }else{
                if($lists['applied_for_claim'] == 'Y'){
                    Yii::$app->getSession()->setFlash('danger', 'Claim has been applied to selected requisition.');
                    return $this->redirect($url);
                }
            }
            
            
            $this->layout = '@app/views/layouts/admin_layout.php';
            return $this->render('applytourclaim', ['model'=>$model,'lists'=>$lists, 'reqid'=>$req_id1, 'menuid'=>$menuid, 'claimid'=>$claimid, 'istourHeader'=>$istourHeader,'data'=>$data]);
        }
        Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Url');
        return $this->redirect($url);
    }
    
    public function actionOtherdetails(){
//        echo "<pre>";print_r($_GET); die;
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."employee/claim/tourclaims?securekey=$menuid";
        if(isset($_GET['claimid']) AND !empty($_GET['claimid']) AND isset($_GET['reqid']) AND !empty($_GET['reqid'])){
            $claimid = Yii::$app->utility->decryptString($_GET['claimid']);
            $reqid = Yii::$app->utility->decryptString($_GET['reqid']);
            if(empty($claimid) or empty($reqid)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Url');
                return $this->redirect($url);
            }
            $claimHeader = Yii::$app->finance->fn_get_tour_claim_details($claimid, "Draft,Revoked", $reqid);
            if(empty($claimHeader)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. No Record Found');
                return $this->redirect($url);
            }
            $this->layout = '@app/views/layouts/admin_layout.php';
            $claimid = Yii::$app->utility->encryptString($claimid);
            $reqid = Yii::$app->utility->encryptString($reqid);
            return $this->render('otherdetails', ['claimHeader'=>$claimHeader, 'menuid'=>$menuid, 'claimid'=>$claimid, 'reqid'=>$reqid]);
        }
        Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Url');
        return $this->redirect($url);
    }
    
    public function actionContingencyclaim()
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('contingencyclaim', ['menuid'=>$menuid]);
    }
    public function actionSubmitclaim()
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."employee/claim/contingencyclaim?securekey=$menuid";
        $id = $project_id = $purpose = $details = $claimed_amt= "";
        
        if(isset($_GET['id']) AND !empty($_GET['id']) AND isset($_GET['code']) AND !empty($_GET['code'])){
            $get = $_GET;
            $id = Yii::$app->utility->decryptString($_GET['id']);
            $empcode = Yii::$app->utility->decryptString($_GET['code']);
            if(empty($id) OR empty($empcode)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Url params'); 
                return $this->redirect($url);
            }
            $record = Yii::$app->finance->fn_get_contingency(NULL, $id, $empcode, "Draft,Revoked");
            if(empty($record)){
                Yii::$app->getSession()->setFlash('danger', 'No record found.'); 
                return $this->redirect($url);
            }
            $project_id = base64_encode($record['project_id']);
            $purpose = $record['purpose'];
            $details = $record['details'];
            $claimed_amt = $record['claimed_amt'];
            $id = Yii::$app->utility->encryptString($id);
//            echo "<pre>";print_r($record); die;
        }
        
        
        if(isset($_POST['Contingency']) AND !empty($_POST['Contingency'])){
            $post = $_POST['Contingency'];
//            echo "<pre>";print_r($post); die;
            $purpose = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['purpose']));
            $details = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['details']));
            $amount = trim(preg_replace('/[^0-9]/', '', $post['amount']));
            $project=NULL;
            if(!empty($post['project'])){
                $project = base64_decode($post['project']); 
            }
            $status="";
            if($post['submit_type'] == 'Pending' OR $post['submit_type'] == 'Draft'){
                $status=$post['submit_type'];
            }else{
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Status Found'); 
                return $this->redirect($url);
            }
            $claimid = NULL;
            if(!empty($post['id'])){
                $claimid = Yii::$app->utility->decryptString($post['id']);
                if(empty($claimid)){
                    Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid ID'); 
                    return $this->redirect($url);
                }
            }
            
            $emp_code = Yii::$app->user->identity->e_id;
            $result = Yii::$app->finance->fn_add_update_contingency($claimid, $emp_code, NULL, $project, $purpose, $details, $amount, NULL, $status, NULL, "Y");
            /*
             * Add Logs
             */
            $logs['claimid']=$claimid;
            $logs['emp_code']=$emp_code;
            $logs['project']=$project;
            $logs['purpose']=$purpose;
            $logs['details']=$details;
            $logs['amount']=$amount;
            $logs['status']=$status;
            $jsonlogs = json_encode($logs);
            $msg = "";
            if($status == 'Pending'){
                $msg = "Contingency Claim Submitted Successfully.";
            }elseif($status == 'Draft'){
                $msg = "Contingency Claim Saved Successfully.";
            }
            if($result == '1'){
                Yii::$app->utility->activities_logs('Claim', 'employee/claim/submitclaim', NULL, $jsonlogs, $msg);
                Yii::$app->getSession()->setFlash('success', $msg); 
                return $this->redirect($url);
            }elseif($result == '2'){
                Yii::$app->utility->activities_logs('Claim', 'employee/claim/submitclaim', NULL, $jsonlogs, $msg);
                Yii::$app->getSession()->setFlash('success', $msg); 
                return $this->redirect($url);
            }else{
                Yii::$app->utility->activities_logs('Claim', 'employee/claim/submitclaim', NULL, $jsonlogs, 'Error Found. Contact Admin');
                Yii::$app->getSession()->setFlash('danger', 'Error Found. Contact Admin'); 
                return $this->redirect($url);
            }
            //echo "<prE>";print_r($_POST['Contingency']);die;
        }
        $projectlist = Yii::$app->hr_utility->hr_get_project_list();
        
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('add_update_claim', [
            'projectlist'=>$projectlist, 'menuid'=>$menuid,
            'project_id'=>$project_id, 'purpose'=>$purpose,
            'details'=>$details,'claimed_amt'=>$claimed_amt, 'id'=>$id
        ]);
    }
    
    public function actionDeleteapp(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."employee/claim/contingencyclaim?securekey=$menuid";
        if(isset($_GET['id']) AND !empty($_GET['id']) AND isset($_GET['code']) AND !empty($_GET['code'])){
            $get = $_GET;
            $id = Yii::$app->utility->decryptString($_GET['id']);
            $empcode = Yii::$app->utility->decryptString($_GET['code']);
            if(empty($id) OR empty($empcode)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Url params'); 
                return $this->redirect($url);
            }
            $record = Yii::$app->finance->fn_get_contingency(NULL, $id, $empcode, "Draft,Revoked");
//             echo "<pre>";print_r($record); die;
            if(empty($record)){
                Yii::$app->getSession()->setFlash('danger', 'No record found.'); 
                return $this->redirect($url);
            }
            
            $emp_code = Yii::$app->user->identity->e_id;
            $result = Yii::$app->finance->fn_add_update_contingency($id, $empcode, NULL, NULL, NULL, NULL, NULL, NULL, NULL, $emp_code, "N");
            /*
             * Add Logs
             */
            $logs['id']=$id;
            $logs['employee_code']=$empcode;
            $logs['action_by']=$empcode;
            $logs['is_active']="N";
            $jsonlogs = json_encode($logs);
            if($result == '2'){
                Yii::$app->utility->activities_logs('Claim', 'employee/claim/seleteapp', NULL, $jsonlogs, 'Contingency Claim Deleted Successfully');
                Yii::$app->getSession()->setFlash('success', "Contingency Claim Deleted Successfully."); 
                return $this->redirect($url);
            }else{
                Yii::$app->utility->activities_logs('Claim', 'employee/claim/seleteapp', NULL, $jsonlogs, 'Error Found. Contact Admin');
                Yii::$app->getSession()->setFlash('danger', 'Error Found. Contact Admin'); 
                return $this->redirect($url);
            }
        }else{
            Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Url params'); 
            return $this->redirect($url);
        }
    }
    
    public function actionAddhaltdetail(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."employee/claim/tourclaims?securekey=$menuid";
        if(isset($_POST['Halt']) AND !empty($_POST['Halt'])){
            $post = $_POST['Halt'];
            $claimid = Yii::$app->utility->decryptString($post['claimid']);
            $reqid = Yii::$app->utility->decryptString($post['reqid']);
            $header_start_date = Yii::$app->utility->decryptString($post['header_start_date']);
            $header_end_date = Yii::$app->utility->decryptString($post['header_end_date']);
            $city_id = Yii::$app->utility->decryptString($post['city_id']);
            $stay_type = Yii::$app->utility->decryptString($post['stay_type']);
            
            if(empty($claimid) OR empty($reqid) OR empty($header_start_date) OR empty($header_end_date) OR empty($city_id) OR empty($stay_type)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid params value found.'); 
                return $this->redirect($url);
            }
            $claimid1 = Yii::$app->utility->encryptString($claimid);
            $reqid1 = Yii::$app->utility->encryptString($reqid);
            $url = Yii::$app->homeUrl."employee/claim/otherdetails?securekey=$menuid&claimid=$claimid1&reqid=$reqid1";
            $charges = $post['charges'];
            if(!is_numeric($charges)){
                Yii::$app->getSession()->setFlash('danger', 'Charges should be in number only.'); 
                return $this->redirect($url);
            }
            $start_date = date('Y-m-d', strtotime($post['start_date']));
            $end_date = date('Y-m-d', strtotime($post['end_date']));
            
            if(strtotime($end_date) < strtotime($start_date)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid Start or End Date Time.'); 
                return $this->redirect($url);
            }
            $header_start_date = date('Y-m-d', strtotime($header_start_date));
            $str_startDate = strtotime($start_date);
            $str_headerStartDate = strtotime($header_start_date);
            $startCheck = $str_headerStartDate - $str_startDate;
//            echo "H : $str_headerStartDate <br>";
//            echo "S : $str_startDate <br>";
//            echo "C : $startCheck <br>";
//            die;
            if($startCheck > 0){
                Yii::$app->getSession()->setFlash('danger', 'Halt Detail Start date / time cannot less then tour started date / time.'); 
                return $this->redirect($url);
            }
            $header_end_date = date('Y-m-d', strtotime($header_end_date));
            $str_endDate = strtotime($end_date);
            $str_headerEndDate = strtotime($header_end_date);
            $endCheck = $str_headerEndDate - $str_endDate;
            if($endCheck < 0){
                Yii::$app->getSession()->setFlash('danger', 'Halt Detail End date / time cannot greater then tour ended date / time.'); 
                return $this->redirect($url);
            }
            
            $result = Yii::$app->finance->fn_add_update_tour_halt_details(NULL, $claimid, $reqid, $start_date, $end_date, $city_id, $stay_type, $charges, NULL);
            /*
             * Add Logs
             */
            $logs['claimid']=$claimid;
            $logs['reqid']=$reqid;
            $logs['start_date']=$start_date;
            $logs['end_date']=$end_date;
            $logs['city_id']=$city_id;
            $logs['stay_type']=$stay_type;
            $logs['charges']=$charges;
            $jsonlogs = json_encode($logs);
            if($result == '1'){
                Yii::$app->utility->activities_logs('Claim', 'employee/claim/addhaltdetail', NULL, $jsonlogs, "Tour Halt Details added Successfully.");
                Yii::$app->getSession()->setFlash('success', 'Tour Halt Details added Successfully.'); 
                return $this->redirect($url);
            }elseif($result == '2'){
                Yii::$app->utility->activities_logs('Claim', 'employee/claim/addhaltdetail', NULL, $jsonlogs, "Tour Halt Details Updated Successfully.");
                Yii::$app->getSession()->setFlash('success', 'Tour Halt Details Updated Successfully.'); 
                return $this->redirect($url);
            }else{
                Yii::$app->utility->activities_logs('Claim', 'employee/claim/addhaltdetail', NULL, $jsonlogs, "Tour Halt Details not added or updated. Contact Admin.");
                Yii::$app->getSession()->setFlash('danger', 'Tour Halt Details not added or updated. Contact Admin.'); 
                return $this->redirect($url);
            }
            
        }
        Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Url.'); 
        return $this->redirect($url);        
    }
    public function actionDeletehaltdetail(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."employee/claim/tourclaims?securekey=$menuid";
        if(isset($_GET['claimid']) AND !empty($_GET['claimid']) AND isset($_GET['reqid']) AND !empty($_GET['reqid']) AND isset($_GET['th_id']) AND !empty($_GET['th_id'])){
            $claimid = Yii::$app->utility->decryptString($_GET['claimid']);
            $reqid = Yii::$app->utility->decryptString($_GET['reqid']);
            $th_id = Yii::$app->utility->decryptString($_GET['th_id']);
            
            if(empty($claimid) OR empty($reqid) OR empty($th_id)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid params value found.'); 
                return $this->redirect($url);
            }
            $result = Yii::$app->finance->fn_add_update_tour_halt_details($th_id, $claimid, $reqid, NULL, NULL, NULL, NULL, NULL, NULL);
            $claimid = Yii::$app->utility->encryptString($claimid);
            $reqid = Yii::$app->utility->encryptString($reqid);
            $url = Yii::$app->homeUrl."employee/claim/otherdetails?securekey=$menuid&claimid=$claimid&reqid=$reqid";
            /*
             * Add Logs
             */
            $logs['th_id']=$th_id;
            $logs['claimid']=$claimid;
            $logs['reqid']=$reqid;
            $jsonlogs = json_encode($logs);
            if($result == '3'){
                Yii::$app->utility->activities_logs('Claim', 'employee/claim/deletehaltdetail', NULL, $jsonlogs, "Halt Detail Deleted Successfully.");
                
                Yii::$app->getSession()->setFlash('success', 'Halt Detail Deleted Successfully.'); 
                return $this->redirect($url);
            }else{
                Yii::$app->utility->activities_logs('Claim', 'employee/claim/deletehaltdetail', NULL, $jsonlogs, "Halt Detail has not deleted. Contact Admin.");
                Yii::$app->getSession()->setFlash('danger', 'Halt Detail has not deleted. Contact Admin.'); 
                return $this->redirect($url);
            }
        }
        Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Url.'); 
        return $this->redirect($url); 
    }
    
    public function actionAddconveyance(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."employee/claim/tourclaims?securekey=$menuid";
        if(isset($_POST['Conveyance']) AND !empty($_POST['Conveyance'])){
            $post = $_POST['Conveyance'];
            $claimid = Yii::$app->utility->decryptString($post['claimid']);
            $reqid = Yii::$app->utility->decryptString($post['reqid']);
            $header_start_date = Yii::$app->utility->decryptString($post['header_start_date']);
            $header_end_date = Yii::$app->utility->decryptString($post['header_end_date']);
            $mode = Yii::$app->utility->decryptString($post['mode']);
            
            if(empty($claimid) OR empty($reqid) OR empty($header_start_date) OR empty($header_end_date) OR empty($mode)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid params value found.'); 
                return $this->redirect($url);
            }
            $start_date = $post['start_date']." ".$post['start_date_hh'].":".$post['start_date_mm'];
            $start_date = date('Y-m-d H:i', strtotime($start_date)); 
            $end_date = $post['end_date']." ".$post['end_date_hh'].":".$post['end_date_mm'];
            $end_date = date('Y-m-d H:i', strtotime($end_date)); 
            $claimid1 = Yii::$app->utility->encryptString($claimid);
            $reqid1 = Yii::$app->utility->encryptString($reqid);
            $url = Yii::$app->homeUrl."employee/claim/otherdetails?securekey=$menuid&claimid=$claimid1&reqid=$reqid1";
            
            if(strtotime($end_date) < strtotime($start_date)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid Start or End Date Time.'); 
                return $this->redirect($url);
            }
            $str_startDate = strtotime($start_date);
            $str_headerStartDate = strtotime($header_start_date);
            $startCheck = $str_headerStartDate - $str_startDate;
            
            if($startCheck > 0){
                Yii::$app->getSession()->setFlash('danger', 'Conveyance Start date / time cannot less then tour started date / time.'); 
                return $this->redirect($url);
            }
            $str_endDate = strtotime($end_date);
            $str_headerEndDate = strtotime($header_end_date);
            $endCheck = $str_headerEndDate - $str_endDate;
            if($endCheck < 0){
                Yii::$app->getSession()->setFlash('danger', 'Conveyance End date / time cannot greater then tour ended date / time.'); 
                return $this->redirect($url);
            }
            $place_from = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['place_from']));
            $place_to = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['place_to']));
            $distance = trim(preg_replace('/[^0-9 .]/', '', $post['distance']));
            $amount = trim(preg_replace('/[^0-9 .]/', '', $post['amount']));
            
            $result = Yii::$app->finance->fn_add_update_claim_conveyance(NULL, $claimid, $reqid, $start_date, $end_date, $place_from, $place_to, $mode, $distance, $amount, NULL);
            /*
             * Add Logs
             */
            $logs['claimid']=$claimid;
            $logs['reqid']=$reqid;
            $logs['start_date']=$start_date;
            $logs['end_date']=$end_date;
            $logs['place_from']=$place_from;
            $logs['place_to']=$place_to;
            $logs['mode']=$mode;
            $logs['distance']=$distance;
            $logs['amount']=$amount;
            $jsonlogs = json_encode($logs);
            if($result == '1'){
                Yii::$app->utility->activities_logs('Claim', 'employee/claim/addconveyance', NULL, $jsonlogs, "Conveyance Detail Added Successfully.");
                Yii::$app->getSession()->setFlash('success', 'Conveyance Detail Added Successfully.'); 
                return $this->redirect($url);
            }else{
                Yii::$app->utility->activities_logs('Claim', 'employee/claim/addconveyance', NULL, $jsonlogs, "Conveyance Detail has not added. Contact Admin.");
                Yii::$app->getSession()->setFlash('danger', 'Conveyance Detail has not added. Contact Admin.'); 
                return $this->redirect($url);
            }
        }
    }
    
    public function actionDeleteconveydetail(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."employee/claim/tourclaims?securekey=$menuid";
        if(isset($_GET['claimid']) AND !empty($_GET['claimid']) AND isset($_GET['reqid']) AND !empty($_GET['reqid']) AND isset($_GET['tc_id']) AND !empty($_GET['tc_id'])){
            $claimid = Yii::$app->utility->decryptString($_GET['claimid']);
            $reqid = Yii::$app->utility->decryptString($_GET['reqid']);
            $tc_id = Yii::$app->utility->decryptString($_GET['tc_id']);
            
            if(empty($claimid) OR empty($reqid) OR empty($tc_id)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid params value found.'); 
                return $this->redirect($url);
            }
            $result = Yii::$app->finance->fn_add_update_claim_conveyance($tc_id, $claimid, $reqid, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
            $claimid = Yii::$app->utility->encryptString($claimid);
            $reqid = Yii::$app->utility->encryptString($reqid);
            $url = Yii::$app->homeUrl."employee/claim/otherdetails?securekey=$menuid&claimid=$claimid&reqid=$reqid";
            
            /*
             * Add Logs
             */
            $logs['tc_id']=$tc_id;
            $logs['claimid']=$claimid;
            $logs['reqid']=$reqid;
            $jsonlogs = json_encode($logs);
            if($result == '3'){
                Yii::$app->utility->activities_logs('Claim', 'employee/claim/deleteconveydetail', NULL, $jsonlogs, "Conveyance Detail Deleted Successfully.");
                
                Yii::$app->getSession()->setFlash('success', 'Conveyance Detail Deleted Successfully.'); 
                return $this->redirect($url);
            }else{
                Yii::$app->utility->activities_logs('Claim', 'employee/claim/deleteconveydetail', NULL, $jsonlogs, "Conveyance Detail has not deleted. Contact Admin.");
                Yii::$app->getSession()->setFlash('danger', 'Conveyance Detail has not deleted. Contact Admin.'); 
                return $this->redirect($url);
            }
        }
        Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Url.'); 
        return $this->redirect($url); 
    }
    
    public function actionAddjourneydetail(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."employee/claim/tourclaims?securekey=$menuid";
        if(isset($_POST['Journey']) AND !empty($_POST['Journey'])){
            $post = $_POST['Journey'];
            $claimid = Yii::$app->utility->decryptString($post['claimid']);
            $reqid = Yii::$app->utility->decryptString($post['reqid']);
            $header_start_date = Yii::$app->utility->decryptString($post['header_start_date']);
            $header_end_date = Yii::$app->utility->decryptString($post['header_end_date']);
            $t_class = Yii::$app->utility->decryptString($post['t_class']);
            $ticket = Yii::$app->utility->decryptString($post['ticket']);
            
            if(empty($claimid) OR empty($reqid) OR empty($header_start_date) OR empty($header_end_date)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid params value found.'); 
                return $this->redirect($url);
            }
            $claimid1 = Yii::$app->utility->encryptString($claimid);
            $reqid1 = Yii::$app->utility->encryptString($reqid);
            $url = Yii::$app->homeUrl."employee/claim/otherdetails?securekey=$menuid&claimid=$claimid1&reqid=$reqid1";
            $start_date = $post['start_date']." ".$post['start_date_hh'].":".$post['start_date_mm'];
            $start_date = date('Y-m-d H:i', strtotime($start_date)); 
            $end_date = $post['end_date']." ".$post['end_date_hh'].":".$post['end_date_mm'];
            $end_date = date('Y-m-d H:i', strtotime($end_date)); 
            if(strtotime($end_date) < strtotime($start_date)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid Start or End Date Time.'); 
                return $this->redirect($url);
            }
            $header_start_date = date('Y-m-d H:i', strtotime($header_start_date)); 
//            echo "Start : $start_date <br>";
//            echo "H : $header_start_date <br>";
            
            $str_startDate = strtotime($start_date);
            $str_headerStartDate = strtotime($header_start_date);
            $startCheck = $str_headerStartDate - $str_startDate;
//            echo "Start : $str_startDate <br>";
//            echo "H : $str_headerStartDate <br>";
//            echo "C : $startCheck <br>";
            if($startCheck > 0){
                Yii::$app->getSession()->setFlash('danger', 'Journey Start date / time cannot less then tour started date / time.'); 
                return $this->redirect($url);
            }
//            die("OUT");
            $str_endDate = strtotime($end_date);
            $str_headerEndDate = strtotime($header_end_date);
            $endCheck = $str_headerEndDate - $str_endDate;
            if($endCheck < 0){
                Yii::$app->getSession()->setFlash('danger', 'Journey End date / time cannot greater then tour ended date / time.'); 
                return $this->redirect($url);
            }
            
            $place_from = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['place_from']));
            $place_to = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['place_to']));
            $amount = trim(preg_replace('/[^0-9 .]/', '', $post['amount']));
            
            $result = Yii::$app->finance->fn_add_update_claim_journey(NULL, $claimid, $reqid, $start_date, $end_date, $place_from, $place_to, $t_class, NULL, NULL, $ticket, NULL, $amount, NULL, NULL, NULL);
            
            /*
             * Logs
             */
            $logs['claimid']=$claimid;
            $logs['reqid']=$reqid;
            $logs['start_date']=$start_date;
            $logs['end_date']=$end_date;
            $logs['place_from']=$place_from;
            $logs['place_to']=$place_to;
            $logs['t_class']=$t_class;
            $logs['ticket']=$ticket;
            $logs['amount']=$amount;
            $jsonlogs = json_encode($logs);
            if($result == '1'){
                Yii::$app->utility->activities_logs('Claim', 'employee/claim/addjourneydetail', NULL, $jsonlogs, "Journey Detail Added Successfully.");
                
                Yii::$app->getSession()->setFlash('success', 'Journey Detail Added Successfully.'); 
                return $this->redirect($url);
            }else{
                Yii::$app->utility->activities_logs('Claim', 'employee/claim/addjourneydetail', NULL, $jsonlogs, "Journey Detail has not added. Contact Admin.");
                
                Yii::$app->getSession()->setFlash('danger', 'Journey Detail has not added. Contact Admin.'); 
                return $this->redirect($url);
            }
        }
    }
    
    public function actionDeletehourneydetail(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."employee/claim/tourclaims?securekey=$menuid";
        if(isset($_GET['claimid']) AND !empty($_GET['claimid']) AND isset($_GET['reqid']) AND !empty($_GET['reqid']) AND isset($_GET['j_id']) AND !empty($_GET['j_id'])){
            $claimid = Yii::$app->utility->decryptString($_GET['claimid']);
            $reqid = Yii::$app->utility->decryptString($_GET['reqid']);
            $j_id = Yii::$app->utility->decryptString($_GET['j_id']);
            
            if(empty($claimid) OR empty($reqid) OR empty($j_id)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid params value found.'); 
                return $this->redirect($url);
            }
            $result = Yii::$app->finance->fn_add_update_claim_journey($j_id, $claimid, $reqid, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
            $claimid = Yii::$app->utility->encryptString($claimid);
            $reqid = Yii::$app->utility->encryptString($reqid);
            $url = Yii::$app->homeUrl."employee/claim/otherdetails?securekey=$menuid&claimid=$claimid&reqid=$reqid";
            
            /*
             * Logs
             */
            $logs['j_id']=$j_id;
            $logs['claimid']=$claimid;
            $logs['reqid']=$reqid;
            $jsonlogs = json_encode($logs);
            
            if($result == '3'){
                Yii::$app->utility->activities_logs('Claim', 'employee/claim/deletehourneydetail', NULL, $jsonlogs, "Journey Detail Deleted Successfully.");
                Yii::$app->getSession()->setFlash('success', 'Journey Detail Deleted Successfully.'); 
                return $this->redirect($url);
            }else{
                Yii::$app->utility->activities_logs('Claim', 'employee/claim/deletehourneydetail', NULL, $jsonlogs, "Journey Detail has not deleted. Contact Admin.");
                Yii::$app->getSession()->setFlash('danger', 'Journey Detail has not deleted. Contact Admin.'); 
                return $this->redirect($url);
            }
        }
        Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Url.'); 
        return $this->redirect($url); 
    }
    
    public function actionAddfooddetail(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."employee/claim/tourclaims?securekey=$menuid";
        if(isset($_POST['Food']) AND !empty($_POST['Food'])){
            $post = $_POST['Food'];
            
            $claimid = Yii::$app->utility->decryptString($post['claimid']);
            $reqid = Yii::$app->utility->decryptString($post['reqid']);
            $header_start_date = Yii::$app->utility->decryptString($post['header_start_date']);
            $header_end_date = Yii::$app->utility->decryptString($post['header_end_date']);
            
            if(empty($claimid) OR empty($reqid) OR empty($header_start_date) OR empty($header_end_date)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid params value found.'); 
                return $this->redirect($url);
            }
            $bill_date = date('Y-m-d', strtotime($post['bill_date']));
            $purpose = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['purpose']));
            $amount = trim(preg_replace('/[^0-9 .]/', '', $post['amount']));
            $header_end_date = date('Y-m-d', strtotime($header_end_date));
            if(strtotime($bill_date) > strtotime($header_end_date)){
                Yii::$app->getSession()->setFlash('danger', 'End date cannot grester then claim header.'); 
                return $this->redirect($url);
            }
            
            $result = Yii::$app->finance->fn_add_update_claim_food(NULL, $claimid, $reqid, $purpose, $amount, $bill_date, NULL);
            
            $claimid = Yii::$app->utility->encryptString($claimid);
            $reqid = Yii::$app->utility->encryptString($reqid);
            $url = Yii::$app->homeUrl."employee/claim/otherdetails?securekey=$menuid&claimid=$claimid&reqid=$reqid";
            
            /*
             * Logs
             */
            $logs['claimid']=$claimid;
            $logs['reqid']=$reqid;
            $logs['purpose']=$purpose;
            $logs['amount']=$amount;
            $logs['bill_date']=$bill_date;
            $jsonlogs = json_encode($logs);
            
            if($result == '1'){
                Yii::$app->utility->activities_logs('Claim', 'employee/claim/addfooddetail', NULL, $jsonlogs, "Food Detail Added Successfully.");
                
                Yii::$app->getSession()->setFlash('success', 'Food Detail Added Successfully.'); 
                return $this->redirect($url);
            }else{
                Yii::$app->utility->activities_logs('Claim', 'employee/claim/addfooddetail', NULL, $jsonlogs, "Food Detail has not added. Contact Admin.");
                Yii::$app->getSession()->setFlash('danger', 'Food Detail has not added. Contact Admin.'); 
                return $this->redirect($url);
            }
        }
        Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Url.'); 
        return $this->redirect($url); 
    }
    
    public function actionDeletefooddetail(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."employee/claim/tourclaims?securekey=$menuid";
        if(isset($_GET['claimid']) AND !empty($_GET['claimid']) AND isset($_GET['reqid']) AND !empty($_GET['reqid']) AND isset($_GET['tf_id']) AND !empty($_GET['tf_id'])){
            $claimid = Yii::$app->utility->decryptString($_GET['claimid']);
            $reqid = Yii::$app->utility->decryptString($_GET['reqid']);
            $tf_id = Yii::$app->utility->decryptString($_GET['tf_id']);
            
            if(empty($claimid) OR empty($reqid) OR empty($tf_id)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid params value found.'); 
                return $this->redirect($url);
            }
            $result = Yii::$app->finance->fn_add_update_claim_food($tf_id, $claimid, $reqid, NULL, NULL, NULL, NULL, NULL);
            $claimid = Yii::$app->utility->encryptString($claimid);
            $reqid = Yii::$app->utility->encryptString($reqid);
            $url = Yii::$app->homeUrl."employee/claim/otherdetails?securekey=$menuid&claimid=$claimid&reqid=$reqid";
            /*
             * Logs
             */
            $logs['tf_id']=$tf_id;
            $logs['claimid']=$claimid;
            $logs['reqid']=$reqid;
            $jsonlogs = json_encode($logs);
            
            if($result == '3'){
                Yii::$app->utility->activities_logs('Claim', 'employee/claim/deletefooddetail', NULL, $jsonlogs, "Food Detail Deleted Successfully.");
                
                Yii::$app->getSession()->setFlash('success', 'Food Detail Deleted Successfully.'); 
                return $this->redirect($url);
            }else{
                Yii::$app->utility->activities_logs('Claim', 'employee/claim/deletefooddetail', NULL, $jsonlogs, "Food Detail has not deleted. Contact Admin.");
                Yii::$app->getSession()->setFlash('danger', 'Food Detail has not deleted. Contact Admin.'); 
                return $this->redirect($url);
            }
        }
        Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Url.'); 
        return $this->redirect($url); 
    }
    
    public function actionFinalsubmitclaim(){
//        echo "<pre>";print_r($_GET); die;
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."employee/claim/tourclaims?securekey=$menuid";
        if(isset($_GET['claimid']) AND !empty($_GET['claimid']) AND isset($_GET['reqid']) AND !empty($_GET['reqid'])){
            $claimid = Yii::$app->utility->decryptString($_GET['claimid']);
            $reqid = Yii::$app->utility->decryptString($_GET['reqid']);
            if(empty($claimid) or empty($reqid)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Url');
                return $this->redirect($url);
            }
            $detail = Yii::$app->finance->fn_get_tour_claim_details($claimid, "Draft,Revoked", $reqid);
            if($detail['status'] == 'Draft' OR $detail['status'] == 'Revoked'){
                $halt = Yii::$app->finance->fn_get_claim_halt_details($claimid,$reqid, Yii::$app->user->identity->e_id);
                $convey = Yii::$app->finance->fn_get_claim_conveyance_details($claimid,$reqid, Yii::$app->user->identity->e_id);
                $journey = Yii::$app->finance->fn_get_claim_journey_details($claimid,$reqid, Yii::$app->user->identity->e_id);
                $food = Yii::$app->finance->fn_get_claim_food_details($claimid,$reqid, Yii::$app->user->identity->e_id);
                
                $haltTotal = $conveyTotal = $JournyTotal = $foodTotal = 0;
                if(!empty($halt)){
                    foreach($halt as $h){
                        $haltTotal = $haltTotal + $h['charges'];
                    }
                }
                if(!empty($convey)){
                    foreach($convey as $c){
                        $conveyTotal = $conveyTotal + $c['amount'];
                    }
                }
                if(!empty($journey)){
                    foreach($journey as $j){
                        $JournyTotal = $JournyTotal + $j['amount'];
                    }
                }
                if(!empty($food)){
                    foreach($food as $f){
                        $foodTotal = $foodTotal + $f['amount'];
                    }
                }
                $totalClaim = $haltTotal+$conveyTotal+$JournyTotal+$foodTotal;
                $project_id = $detail['project_id'];
                $result= Yii::$app->finance->fn_add_update_tour_claim_header($claimid,$reqid, Yii::$app->user->identity->e_id, $detail['project_id'], $detail['dept_id'], $detail['start_date'], $detail['end_date'], $detail['location'], $detail['purpose'], $totalClaim, NULL, NULL, "Submitted");
                
                /*
                 * Logs
                 */
                $logs['claimid']=$claimid;
                $logs['reqid']=$reqid;
                $logs['employee_code']=Yii::$app->user->identity->e_id;
                $logs['totalClaim']=$totalClaim;
                $logs['status']="Submitted";
                $jsonlogs = json_encode($logs);
                if($result == '2'){
                    Yii::$app->utility->activities_logs('Claim', 'employee/claim/finalsubmitclaim', NULL, $jsonlogs, "Tour Claim Submitted Successfully and Sent To Finance.");
                    
                    Yii::$app->getSession()->setFlash('success', 'Tour Claim Submitted Successfully and Sent To Finance.'); 
                    return $this->redirect($url); 
                }else{
                    Yii::$app->utility->activities_logs('Claim', 'employee/claim/finalsubmitclaim', NULL, $jsonlogs, "Fraudulent Data Detected. Tour Claim has not submitted. Contact Admin.");
                    Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Tour Claim has not submitted. Contact Admin.'); 
                    return $this->redirect($url); 
                }
            }else{
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Tour Claim Already Submitted.'); 
                return $this->redirect($url); 
            }
            
        }
        Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Url.'); 
        return $this->redirect($url); 
    }
    
    public function actionPreviewclaim(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."employee/claim/tourclaims?securekey=$menuid";
        if(isset($_GET['claimid']) AND !empty($_GET['claimid']) AND isset($_GET['reqid']) AND !empty($_GET['reqid'])){
            $claimid = Yii::$app->utility->decryptString($_GET['claimid']);
            $reqid = Yii::$app->utility->decryptString($_GET['reqid']);
            
            if(empty($claimid) OR empty($reqid)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid params value found.');
                return $this->redirect($url);
            }
            $e_id = Yii::$app->user->identity->e_id;
            $tourHeader = Yii::$app->finance->fn_get_tour_claim_details($claimid, "Submitted,In-Process,Sanctioned,Rejected", $reqid, $e_id);
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
            
            return $this->render('previewclaim', [
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

    /*
     * Download Tour Claim
     */
    public function actionDownloadclaim(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."employee/claim/tourclaims?securekey=$menuid";
        if(isset($_GET['claimid']) AND !empty($_GET['claimid']) AND isset($_GET['reqid']) AND !empty($_GET['reqid'])){
            
            $claimid = Yii::$app->utility->decryptString($_GET['claimid']);
            $reqid = Yii::$app->utility->decryptString($_GET['reqid']);
            if(empty($claimid) or empty($reqid)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Url');
                return $this->redirect($url);
            }
            $ch = Yii::$app->finance->fn_get_tour_claim_details($claimid, 'Submitted,In-Process,Sanctioned,Rejected', $reqid);
            if(empty($ch)){
                Yii::$app->getSession()->setFlash('danger', 'No Record');
                return $this->redirect($url);
            }
            $e_id = Yii::$app->user->identity->e_id;
           // error_reporting(E_ALL ^ E_DEPRECATED);
            $mpdf = new \Mpdf\Mpdf();
            $date = date('d-m-Y H:i');
            $header = "<div style='text-align:center;'><p style='margin:0px; font-size:18px;font-weight:bold;font-family:arial;'></b>".ORGANAZATION_NAME."</b></p> <br><p style='margin:0px;font-size:16px;font-weight:bold;font-family:arial;'>Tour Claim</p></div>";
            
            $footer = "<table style='width:100%;font-size:10px;'><tr><td align='left'>{PAGENO} of {nbpg}</td><td align='right'>".date('d-M-Y H:i')."</td></tr></table>";
            $mpdf->setFooter($footer);
            $mpdf->WriteHTML($header);

            if($ch['status'] == 'Sanctioned'){
                $hh = "<p style='font-size:15px;color:red;font-family:arial;'> This Claim Application is Sanctioned. You Cannot use this Print. </p>";
                $mpdf->WriteHTML($hh);
            }

            $n = Yii::$app->user->identity->fullname;
            $degn = Yii::$app->user->identity->desg_name;
            $jd = date('d-m-Y', strtotime(Yii::$app->user->identity->joining_date));
            $dept = Yii::$app->user->identity->dept_name;
            $st = Yii::$app->user->identity->employment_type;
            $scale = Yii::$app->user->identity->grade_pay_scale;
            $email_id = Yii::$app->user->identity->email_id;
            $phone = Yii::$app->user->identity->phone;
            $border = "border:1px solid black;";
            $tfont = "$border padding:5px;font-size:13px;font-family:arial;";

            $start_date = date('d-m-Y H:i', strtotime($ch['start_date'])); 
            $end_date = date('d-m-Y H:i', strtotime($ch['end_date'])); 
            $city_name = $ch['city_name'];
            $advance_amount = $ch['advance_amount'];
            $purpose = $ch['purpose'];


            $date1=date_create(date('Y-m-d', strtotime($ch['start_date'])));
            $date2=date_create(date('Y-m-d', strtotime($ch['end_date'])));
            $days=date_diff($date1,$date2);
            if($days->days > 0){
            $days=$days->days+1;
            }else{
            $days=1;
            }

            $project_name = $ch['project_name'];
//echo "$claimid - $reqid<pre>";print_r($days); die;
            $html = "";
            $html .= "<p style='font-size:13px;font-weight:bold;margin:0px;font-family:arial;'>Header:-</p>";
            $html .= "<table width='100%' style='$border border-collapse: collapse; overflow: wrap'>
                <tr style='$border'>
                        <td style='$tfont'>EmpId</td>
                        <td style='$tfont'>$e_id</td>
                        <td style='$tfont'>Name</td>
                        <td style='$tfont'>$n</td>
                </tr>
                <tr style='$border'>
                        <td style='$tfont'>Start Date</td>
                        <td style='$tfont'>$start_date</td>
                        <td style='$tfont'>End Date</td>
                        <td style='$tfont'>$end_date</td>
                </tr>
                <tr style='$border'>
                        <td style='$tfont'>Location</td>
                        <td style='$tfont'>$city_name</td>
                        <td style='$tfont'>Advance</td>
                        <td style='$tfont'>$advance_amount</td>
                </tr>
                <tr style='$border'>
                        <td style='$tfont'>Group</td>
                        <td style='$tfont'>$dept</td>
                        <td style='$tfont'>For Project</td>
                        <td style='$tfont'>$project_name</td>
                </tr>
                <tr style='$border'>
                        <td style='$tfont'>Staff Type</td>
                        <td style='$tfont'>$st</td>
                        <td style='$tfont'>Designation</td>
                        <td style='$tfont'>$degn</td>
                </tr>
                <tr style='$border'>
                        <td style='$tfont'>Basic/PiPb/ConsPay</td>
                        <td style='$tfont'>$scale</td>
                        <td style='$tfont'>Scale</td>
                        <td style='$tfont'>$scale</td>
                </tr>
                <tr style='$border'>
                        <td style='$tfont'>Tour Days</td>
                        <td style='$tfont' colspan='3'>$days</td>
                </tr>
                <tr style='$border'>
                        <td style='$tfont'>Purpose</td>
                        <td style='$tfont' colspan='3'>$purpose</td>
                </tr>
                <tr style='$border'>
                        <td style='$tfont'>Email-Id</td>
                        <td style='$tfont'>$email_id</td>
                        <td style='$tfont'>Phone</td>
                        <td style='$tfont'>$phone</td>
                </tr>

                </table>";
            /*
             * Journey Details
             */
            $journyDetails = Yii::$app->finance->fn_get_claim_journey_details($claimid, $reqid, $e_id);
            if(!empty($journyDetails)){
                $tfont = "$border padding:5px;font-size:12px;font-family:arial;";
                $html .= "<br> <p style='font-size:13px;font-weight:bold;margin:0px;font-family:arial;'>Journey Details:-</p>";
                $html .= "<table width='100%' style='$border border-collapse: collapse; overflow: wrap'>
                <tr style='$border'>
                        <th style='$tfont'>Start Date</th>
                        <th style='$tfont'>End Date</th>
                        <th style='$tfont'>From</th>
                        <th style='$tfont'>To</th>
                        <th style='$tfont'>TClass</th>
                        <th style='$tfont'> > 500Km</th>
                        <th style='$tfont'> > 8Hrs</th>
                        <th style='$tfont'>Tickets</th>
                        <th style='$tfont'>Sanc Ticket</th>
                        <th style='$tfont'>Amount</th>
                        <th style='$tfont'>Sanc Amt</th>
                        <th style='$tfont'>Incentive</th>
                        <th style='$tfont'>Sanc. Incentive</th>
                </tr>";
                $tamount=$tsanc_amount= $tincentive=$tsanc_incentive=0;
                foreach($journyDetails as $j){
                    $start_date = date('d-m-Y H:i', strtotime($j['start_date']));
                    $end_date = date('d-m-Y H:i', strtotime($j['end_date']));
                    $place_to = $j['place_to'];
                    $place_from = $j['place_from'];
                    $t_class = $j['t_class'];
                    $greater_500Km = "No";
                    if($j['greater_500Km'] == 'Y'){ $greater_500Km = "Yes"; }
                    $greater_8Hrs = "No";
                    if($j['greater_8Hrs'] == 'Y'){ $greater_8Hrs = "Yes"; }
                    $ticket = $j['ticket'];
                    $sanc_ticket = $j['sanc_ticket'];
                    $amount = $j['amount'];
                    $sanc_amount = $j['sanc_amount'];
                    $incentive = $j['incentive'];
                    $sanc_incentive = $j['sanc_incentive'];
                    $tamount = $tamount+$amount;
                    $tsanc_amount = $tsanc_amount+$sanc_amount;
                    $tincentive = $tincentive+$incentive;
                    $tsanc_incentive = $tsanc_incentive+$sanc_incentive;
                    $html .= "<tr style='$border'>
                        <td style='$tfont'>$start_date</td>
                        <td style='$tfont'>$end_date</td>
                        <td style='$tfont'>$place_from</td>
                        <td style='$tfont'>$place_to</td>
                        <td style='$tfont'>$t_class</td>
                        <td style='$tfont'>$greater_500Km</td>
                        <td style='$tfont'>$greater_8Hrs</td>
                        <td style='$tfont'>$ticket</td>
                        <td style='$tfont'>$sanc_ticket</td>
                        <td style='$tfont'>$amount</td>
                        <td style='$tfont'>$sanc_amount</td>
                        <td style='$tfont'>$incentive</td>
                        <td style='$tfont'>$sanc_incentive</td>
                </tr>";
                }
                $tamount = number_format($tamount, 2);
                $tsanc_amount = number_format($tsanc_amount, 2);
                $tincentive = number_format($tincentive, 2);
                $tsanc_incentive = number_format($tsanc_incentive, 2);
                $html .= "<tr style='$border'>
                        <td style='$tfont' colspan='9'><b>Sub Total</b></td>
                        <td style='$tfont'><b>$tamount</b></td>
                        <td style='$tfont'><b>$tsanc_amount</b></td>
                        <td style='$tfont'><b>$tincentive</b></td>
                        <td style='$tfont'><b>$tsanc_incentive</b></td>
                </tr>";
                $html .= "</table>";
            }
            /*
             * Halt Details
             */
            $haltDetails = Yii::$app->finance->fn_get_claim_halt_details($claimid, $reqid, $e_id);
            if(!empty($haltDetails)){
                $tfont = "$border padding:5px;font-size:12px;font-family:arial;";
                $html .= "<br> <p style='font-size:13px;font-weight:bold;margin:0px;font-family:arial;'>Halt Details:-</p>";
                $html .= "<table width='100%' style='$border border-collapse: collapse; overflow: wrap'>
                <tr style='$border'>
                        <th style='$tfont'>Start Date</th>
                        <th style='$tfont'>End Date</th>
                        <th style='$tfont'>City</th>
                        <th style='$tfont'>Stay</th>
                        <th style='$tfont'>Charges</th>
                        <th style='$tfont'>Sanc Charges</th>
                        <th style='$tfont'>Comp</th>
                        <th style='$tfont'>Sanc Comp</th>
                </tr>";
                $tcharges=$tsanc_charges= $tcomp=$tsenc_comp=0;
                foreach($haltDetails as $j){
                    $start_date = date('d-m-Y H:i', strtotime($j['start_date']));
                    $end_date = date('d-m-Y H:i', strtotime($j['end_date']));
                    $city_name = $j['city_name'];
                    $stay = $j['stay'];
                    $charges = $j['charges'];
                    $sanc_charges = $j['sanc_charges'];
                    $comp = $j['comp'];
                    $senc_comp = $j['senc_comp'];
                    $tcharges = $tcharges+$charges;
                    $tsanc_charges = $tsanc_charges+$sanc_charges;
                    $tcomp = $tcomp+$comp;
                    $tsenc_comp = $tsenc_comp+$senc_comp;
                    $html .= "<tr style='$border'>
                        <td style='$tfont'>$start_date</td>
                        <td style='$tfont'>$end_date</td>
                        <td style='$tfont'>$city_name</td>
                        <td style='$tfont'>$stay</td>
                        <td style='$tfont'>$charges</td>
                        <td style='$tfont'>$sanc_charges</td>
                        <td style='$tfont'>$comp</td>
                        <td style='$tfont'>$senc_comp</td>
                </tr>";
                }
                $charges = number_format($charges, 2);
                $sanc_charges = number_format($sanc_charges, 2);
                $comp = number_format($comp, 2);
                $senc_comp = number_format($senc_comp, 2);
                $html .= "<tr style='$border'>
                        <td style='$tfont' colspan='4'><b>Sub Total</b></td>
                        <td style='$tfont'><b>$charges</b></td>
                        <td style='$tfont'><b>$sanc_charges</b></td>
                        <td style='$tfont'><b>$comp</b></td>
                        <td style='$tfont'><b>$senc_comp</b></td>
                </tr>";
                $html .= "</table>";
            }
            /*
             * Conveyance Details
             */
            $convenDetails = Yii::$app->finance->fn_get_claim_conveyance_details($claimid, $reqid, $e_id);
            if(!empty($convenDetails)){
                $tfont = "$border padding:5px;font-size:12px;font-family:arial;";
                $html .= "<br> <p style='font-size:13px;font-weight:bold;margin:0px;font-family:arial;'>Journey Details:-</p>";
                $html .= "<table width='100%' style='$border border-collapse: collapse; overflow: wrap'>
                <tr style='$border'>
                        <th style='$tfont'>Start Date</th>
                        <th style='$tfont'>End Date</th>
                        <th style='$tfont'>From</th>
                        <th style='$tfont'>To</th>
                        <th style='$tfont'>Mode</th>
                        <th style='$tfont'>Distance (Km)</th>
                        <th style='$tfont'>Amount</th>
                        <th style='$tfont'>Sanc Amount</th>
                </tr>";
                $tamount=$tsanctioned_amount=0;
                foreach($convenDetails as $j){
                   $start_date = date('d-m-Y H:i', strtotime($j['start_date']));
                    $end_date = date('d-m-Y H:i', strtotime($j['end_date']));
                    $place_from = $j['place_from'];
                    $place_to = $j['place_to'];
                    $mode = $j['mode'];
                    $distance = $j['distance'];
                    $amount = $j['amount'];
                    $sanctioned_amount = $j['sanctioned_amount'];
                    $tamount = $tamount+$amount;
                    $tsanctioned_amount = $tsanctioned_amount+$sanctioned_amount;
                    $html .= "<tr style='$border'>
                        <td style='$tfont'>$start_date</td>
                        <td style='$tfont'>$end_date</td>
                        <td style='$tfont'>$place_from</td>
                        <td style='$tfont'>$place_to</td>
                        <td style='$tfont'>$mode</td>
                        <td style='$tfont'>$distance</td>
                        <td style='$tfont'>$amount</td>
                        <td style='$tfont'>$sanctioned_amount</td>
                </tr>";
                }
                $tamount = number_format($tamount, 2);
                $tsanctioned_amount = number_format($tsanctioned_amount, 2);
                $html .= "<tr style='$border'>
                        <td style='$tfont' colspan='6'><b>Sub Total</b></td>
                        <td style='$tfont'><b>$tamount</b></td>
                        <td style='$tfont'><b>$tsanctioned_amount</b></td>
                </tr>";
                $html .= "</table>";
            }
            
            /*
             * Food Details
             */
            $foodDetails = Yii::$app->finance->fn_get_claim_food_details($claimid, $reqid, $e_id);
            if(!empty($foodDetails)){
                $tfont = "$border padding:5px;font-size:12px;font-family:arial;";
                $html .= "<br> <p style='font-size:13px;font-weight:bold;margin:0px;font-family:arial;'>Food Details:-</p>";
                $html .= "<table width='100%' style='$border border-collapse: collapse; overflow: wrap'>
                <tr style='$border'>
                        <th style='$tfont'>Bill Date</th>
                        <th style='$tfont'>Amount</th>
                        <th style='$tfont'>Sanc Amount</th>
                </tr>";
                $tamount=$tsanctioned_amount=0;
                foreach($foodDetails as $j){
                    $bill_date = date('d-m-Y', strtotime($j['bill_date']));
                    $amount = $j['amount'];
                    $sanctioned_amount = $j['sanctioned_amount'];
                                        
                    $tamount = $tamount+$amount;
                    $tsanctioned_amount = $tsanctioned_amount+$sanctioned_amount;
                    $html .= "<tr style='$border'>
                        <td style='$tfont'>$bill_date</td>
                        <td style='$tfont'>$amount</td>
                        <td style='$tfont'>$sanctioned_amount</td>
                </tr>";
                }
                $tamount = number_format($tamount, 2);
                $tsanctioned_amount = number_format($tsanctioned_amount, 2);
                $html .= "<tr style='$border'>
                        <td style='$tfont' colspan=''><b>Sub Total</b></td>
                        <td style='$tfont'><b>$tamount</b></td>
                        <td style='$tfont'><b>$tsanctioned_amount</b></td>
                </tr>";
                $html .= "</table>";
            }
            $hwid = "";
            $tfont = "padding:5px;font-size:12px;font-family:arial;";
            $html .= "<br> <p style='font-size:13px;font-weight:bold;margin:0px;font-family:arial;'>Expenses Summary:-</p>";
            $html .= "<table width='100%' style='border-collapse: collapse; overflow: wrap'>
                <tr style=''>
                        <th style='$tfont'>Heading</th>
                        <th style='$tfont'>Claimed</th>
                        <th style='$tfont'>Sanctioned</th>
                        <th style='$tfont'>Heading</th>
                        <th style='$tfont'>Claimed</th>
                        <th style='$tfont'>Sanctioned</th>
                </tr>
                <tr>
                    <td style='$tfont'>Advance</td>
                    <td style='$tfont' align='right'>0</td>
                    <td style='$tfont' align='right'>0</td>
                    <td style='$tfont'>Lower Class Incentive</td>
                    <td style='$tfont' align='right'>0</td>
                    <td style='$tfont' align='right'>0</td>
                </tr>
                <tr>
                    <td style='$tfont'>Ticket Booked by CDAC</td>
                    <td style='$tfont' align='right'>0</td>
                    <td style='$tfont' align='right'>0</td>
                    <td style='$tfont'>Tickets</td>
                    <td style='$tfont' align='right'>0</td>
                    <td style='$tfont' align='right'>0</td>
                </tr>
                <tr>
                    <td style='$tfont'></td>
                    <td style='$tfont'></td>
                    <td style='$tfont'></td>
                    <td style='$tfont'>Hotel/ Guest House Charges</td>
                    <td style='$tfont' align='right'>0</td>
                    <td style='$tfont' align='right'>0</td>
                </tr>
                <tr>
                    <td style='$tfont'></td>
                    <td style='$tfont'></td>
                    <td style='$tfont'></td>
                    <td style='$tfont'>Guest House/ Self Arrangement Comp</td>
                    <td style='$tfont' align='right'>0</td>
                    <td style='$tfont' align='right'>0</td>
                </tr>
                <tr>
                    <td style='$tfont'></td>
                    <td style='$tfont'></td>
                    <td style='$tfont'></td>
                    <td style='$tfont'>Conveyance</td>
                    <td style='$tfont' align='right'>0</td>
                    <td style='$tfont' align='right'>0</td>
                </tr>
                <tr>
                    <td style='$tfont'></td>
                    <td style='$tfont'></td>
                    <td style='$tfont'></td>
                    <td style='$tfont'>Contingency</td>
                    <td style='$tfont' align='right'>0</td>
                    <td style='$tfont' align='right'>0</td>
                </tr>
                <tr>
                    <td style='$tfont'><b>Sub Total</b></td>
                    <td style='$tfont'><b>0</b></td>
                    <td style='$tfont'><b>0</b></td>
                    <td style='$tfont'><b>Sub Total</b></td>
                    <td style='$tfont' align='right'><b>0</b></td>
                    <td style='$tfont' align='right'><b>0</b></td>
                </tr>
                </table>";
//            echo "<pre>";print_r($journyDetails); die;
            $mpdf->WriteHTML($html);
            
            $name = "TourClaim_".date('Y_m_d_H_i_s').".pdf";
            $file = $mpdf->Output($name, 'I');
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . basename($file) . '"');
            header("Cache-Control: max-age=0");
            readfile($file);
        }else{
            Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Url.'); 
            return $this->redirect($url);		
        }
    }
    /*
     * Download Contigency Claim
     */
    public function actionDownloadconticlaim(){
        
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."employee/claim/contingencyclaim?securekey=$menuid";
        if(isset($_GET['req_id']) AND !empty($_GET['req_id'])){
            $reqid = Yii::$app->utility->decryptString($_GET['req_id']);
            if(empty($reqid)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid Url');
                return $this->redirect($url);
            }
            $app = Yii::$app->finance->fn_get_contingency('3', $reqid, Yii::$app->user->identity->e_id, "Sanctioned,Rejected");
            if(empty($app)){
                Yii::$app->getSession()->setFlash('danger', 'No Record');
                return $this->redirect($url);
            }
//            echo "<pre>";print_r($app); die;
            require_once './mpdf/mpdf.php';
            $mpdf = new \mPDF();
            $date = date('d-m-Y H:i');
		$header = "<div style='text-align:center;'><p style='margin:0px; font-size:18px;font-weight:bold;font-family:arial;'></b>".ORGANAZATION_NAME."</b></p> <br><p style='margin:0px;font-size:16px;font-weight:bold;font-family:arial;'>Contingency Claim</p></div>";
            $footer = "<table style='width:100%;font-size:10px;'><tr><td align='left'>{PAGENO} of {nbpg}</td><td align='right'>".date('d-M-Y H:i')."</td></tr></table>";
            $mpdf->setFooter($footer);
            $mpdf->WriteHTML($header);
            if($app['status'] == 'Sanctioned'){

                     $hh = "<p style='font-size:15px;color:red;font-family:arial;'>This application is already sanctioned</p>";
                    $mpdf->WriteHTML($hh);
            }
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
            $html .= "<p style='font-size:11px;font-weight:bold;margin:0px;font-family:arial;'>Personal Info:-</p>";
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
           

            //echo "<pre>";print_r(Yii::$app->user->identity);
            //echo "<pre>";print_r($details); die;
            $tfont = "$border padding:10px;font-size:13px;font-family:arial;";
            $dt = date('d-M-Y');
            $purpose = $app['purpose'];
            $advanced = $app['advanced'];
            $claimed_amt = $app['claimed_amt'];
            $sanctioned_amt = $app['sanctioned_amt'];
            $details = $app['details'];
            $dept_project = $app['dept_name'].", ".$app['project_name'];
            $claimDt = date('d-M-Y', strtotime($app['submitted_on']));
            $action_on = date('d-M-Y', strtotime($app['action_on']));
            $t = "padding:0px;font-size:13px;font-family:arial;";
            $html .= "<br><p style='font-size:11px;font-weight:bold;margin:0px;'>Claim Details:-</p>";
            $html .= "<table width='100%' style='border-collapse: collapse; overflow: wrap;'>
                <tr style=''>
                        <td style='$tfont'>Purpose</td>
                        <td style='$tfont' colspan='3'>$purpose</td>
                </tr>
                <tr style=''>
                        <td style='$tfont'>Claim Date:</td>
                        <td style='$tfont' >$claimDt</td>
                        <td style='$tfont'>Sanctioned Date:</td>
                        <td style='$tfont' >$action_on</td>
                </tr>
                <tr style=''>
                        <td style='$tfont'>Claim Amount:</td>
                        <td style='$tfont' >$claimed_amt</td>
                        <td style='$tfont'>Sanctioned Amount:</td>
                        <td style='$tfont' >$sanctioned_amt</td>
                </tr>
            </table>
            
            <br><p style='font-size:11px;font-weight:bold;margin:0px;'>Expense Details:-</p>
            <table width='100%' style='border-collapse: collapse; overflow: wrap; margin-bottom:80px;'>
                <tr style=''>
                        <td style='$tfont'>Purpose</td>
                        <td style='$tfont'>Group / Project</td>
                        <td style='$tfont'>Claimed</td>
                        <td style='$tfont'>Sanctioned</td>
                </tr>
                <tr style=''>
                        <td style='$tfont'>$details</td>
                        <td style='$tfont' >$dept_project</td>
                        <td style='$tfont'>$claimed_amt</td>
                        <td style='$tfont' >$sanctioned_amt</td>
                </tr>
                <tr style=''>
                        <td style='$tfont' >Advanced Amount</td>
                        <td style='$tfont' >$advanced</td>
                        <td style='$tfont'></td>
                        <td style='$tfont' ></td>
                </tr>
            </table>
            
            <div style='margin-bottom:50px;'>
                    <p style='font-size:13px;margin:0px;'>Applicant</p>
                    <p style='font-size:13px;margin:0px;'>$n</p>
                    <p style='font-size:13px;margin:0px;'>Date: $dt</p>
            </div>
            <div style='margin-bottom:50px;'>
                    <table width='100%' style='border-collapse: collapse; overflow: wrap;'>
                            <tr style=''>
                                    <td style='$t width:33%;'>Verified By<br>(FLA/SLA)<br>Name:<br>Date:</td>
                                    <td style='$t width:33%;'>Recommended By<br>(HOD)<br>Name:<br>Date:</td>
                                    <td style='$t width:33%'>Approved By<br>(Director/ED/DG)<br>Name:<br>Date:</td>
                            </tr>
                    </table>
   
            </div>";
            $mpdf->WriteHTML($html);
            $name = "ContingencyClaim_".date('Y_m_d_H_i_s').".pdf";
	    $file = $mpdf->Output($name, 'I');
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . basename($file) . '"');
            header("Cache-Control: max-age=0");
            readfile($file);
        }
        Yii::$app->getSession()->setFlash('danger', 'Invalid Url.');
        return $this->redirect($url);
    }
}
