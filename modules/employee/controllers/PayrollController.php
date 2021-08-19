<?php

namespace app\modules\employee\controllers;
use yii;
class PayrollController extends \yii\web\Controller
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
//    public function actionIndex()
//    {
//        $this->layout = '@app/views/layouts/admin_layout.php';
//        return $this->render('index');
//    }
    
    public function actionPayslip() {
        $this->layout = '@app/views/layouts/admin_layout.php';
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $salaryInfo = Yii::$app->finance->get_emp_yearly_sal(Yii::$app->user->identity->e_id, 'Paid');
        return $this->render('payslip', ['menuid'=>$menuid, 'salaryInfo'=>$salaryInfo]);
    }
    public function actionEpf() {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $this->layout = '@app/views/layouts/admin_layout.php';
        $pfAcc = Yii::$app->finance->pf_get_accounts(Yii::$app->user->identity->e_id);
        return $this->render('epf', ['menuid'=>$menuid, 'pfAcc'=>$pfAcc]);
    }
    
    public function actionViewpayslip(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."employee/payroll/payslip?securekey=$menuid";
        if(isset($_GET['key1']) AND !empty($_GET['key1']) AND isset($_GET['key2']) AND !empty($_GET['key2']) ){
            $month = Yii::$app->utility->decryptString($_GET['key1']);
            $year = Yii::$app->utility->decryptString($_GET['key2']);
            
            if(empty($month) OR empty($year)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid params value');
                return $this->redirect($url);
            }
            $sal = Yii::$app->finance->get_emp_yearly_sal(Yii::$app->user->identity->e_id, "Paid",$month, $year);
            if(empty($sal)){
                Yii::$app->getSession()->setFlash('danger', 'Salary Details not found');
                return $this->redirect($url);
            }
            $eid = Yii::$app->user->identity->e_id;
            require_once './mpdf/mpdf.php';
            $mpdf = new \mPDF();
            $monthYear = date('M-Y', strtotime("$year-$month-01"));
            $date = date('d-m-Y H:i');
            $header = "<div style='text-align:center;'><p style='margin:0px; font-size:18px;font-weight:bold;font-family:arial;'></b>".ORGANAZATION_NAME."</b></p> <br><p style='margin:0px;font-size:16px;font-weight:bold;font-family:arial;'>Pay Slip $monthYear</p></div><br><div style='text-align:right'>PS/$monthYear/$eid<div>";
            
            $n = Yii::$app->user->identity->fullname;
            $degn = Yii::$app->user->identity->desg_name;
            
            
            $html = "";
            $html .= $header;
            $html .= "<br><table style='border:1px solid #000;width:100%'>
                    <tr>
                        <td>Emp Id</td>
                        <td>$eid</td>
                        <td>Name</td>
                        <td>$n</td>
                    </tr>
                    <tr>
                        <td>Designation</td>
                        <td>$degn</td>
                        <td></td>
                        <td></td>
                    </tr>
                    </table>";
            $wd40 = "width:35%;padding-left:8px;text-align:left;";
            $wd10 = "width:13%;text-align:right;";
            $html .= "<br><table style='border:1px solid #000;width:100%;font-size:12px;'>
                    <tr>
                        <td colspan='2' style='width:50%'><b>Salary Details</b></td>
                        <td colspan='2' style='width:49%;text-align:center'><b>Deductions (D) / Recoveries (R)</b></td>
                    </tr>
                    <tr>
                        <td style='$wd40'>Consolidated Pay</td>
                        <td style='$wd10'>27104</td>
                        <td style='$wd40'>Contributory Provident Fund [D]</td>
                        <td style='$wd10'>1800</td>
                    </tr>
                    <tr>
                        <td style='$wd40'>Transport Allowance</td>
                        <td style='$wd10'>1</td>
                        <td style='$wd40'>Voluntary Provdent Fund [D]</td>
                        <td style='$wd10'>2</td>
                    </tr>
                    <tr>
                        <td style='$wd40'>House Rent Allowance</td>
                        <td style='$wd10'>3</td>
                        <td style='$wd40'>C Cube Recovery [R]</td>
                        <td style='$wd10'>4</td>
                    </tr>
                    <tr>
                        <td style='$wd40'>Canteen Allowance</td>
                        <td style='$wd10'>5</td>
                        <td style='$wd40'></td>
                        <td style='$wd10'></td>
                    </tr>
                    <tr>
                        <td style='$wd40'></td>
                        <td style='$wd10'></td>
                        <td style='$wd40'></td>
                        <td style='$wd10'></td>
                    </tr>
                    <tr>
                        <th style='$wd40'>Gross Salary</th>
                        <th style='$wd10'>Rs. 28,304</td>
                        <th style='$wd40'>Total [ Deductions + Recoveries ]</th>
                        <th style='$wd10'>Rs. 3,615</th>
                    </tr>
                    </table>";
            
            $netPay = Yii::$app->utility->numberTowords('28304');
            $html .= "<br><p style='text-align:left;font-size:12px;'><b>Rs. $netPay</b></p>";
            $html .= "<p style='text-align:left;font-size:12px;font-style: italic;'>This Pay slip is computer generated and hence no authentication is required.</p>";
            $mpdf->WriteHTML($html);
            $mpdf->SetWatermarkText(ORGANAZATION_NAME);
            $mpdf->showWatermarkText = true;
            
            $name = "TourRequisition_".date('Y_m_d_H_i_s').".pdf";
	    $file = $mpdf->Output($name, 'I');
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . basename($file) . '"');
            header("Cache-Control: max-age=0");
            readfile($file);
        }
        Yii::$app->getSession()->setFlash('danger', 'Invalid params');
        return $this->redirect($url);
        
    }
    public function actionPfreport(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."employee/payroll/epf?securekey=$menuid";
        if(isset($_GET['key']) AND !empty($_GET['key'])){
            $fy = Yii::$app->utility->decryptString($_GET['key']);
            if(empty($fy)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid params value');
                return $this->redirect($url);
            }
            $eid = Yii::$app->user->identity->e_id;
            require_once './mpdf/mpdf.php';
//            $mpdf = new \mPDF();
            $margin_right = $margin_left = 10;
            $margin_top = 10;
            $margin_bottom = 5;
            $mpdf = new \mPDF('utf-8', 'A4-L', '', '', $margin_left, $margin_right, $margin_top, $margin_bottom, 0, 0);

            $date = date('d-m-Y H:i');
            $n = Yii::$app->user->identity->fullname;
            
            $mpdf->SetWatermarkText(ORGANAZATION_NAME);
            $mpdf->showWatermarkText = true;
//            $stylesheet1 = file_get_contents(getcwd().'/css/roboto/Roboto-Light.ttf'); // external css
//            $mpdf->WriteHTML($stylesheet1,1);
            $stylesheet = file_get_contents(getcwd().'/css/common.css'); // external css
            $mpdf->WriteHTML($stylesheet,1);
            
            $pfAcc = Yii::$app->finance->pf_get_accounts(Yii::$app->user->identity->e_id);
            if(empty($pfAcc)){
                Yii::$app->getSession()->setFlash('danger', 'PF Account details not found');
                return $this->redirect($url);
            }
            $uan_number = $pfAcc['uan_number'];
            $pf_number = $pfAcc['pf_number'];
//            echo "$fy<pre>";print_r($pfAcc); die;
            $html = "";
            $html .= "<div class='pfheader'>
                        <div class='pfheaderimg'><img width='100' src='".Yii::$app->homeUrl.PDF_Company_Logo."' /></div>
                        <div class='pftitle'><p>".ORGANAZATION_NAME." (".SHORT_ORGANAZATION_NAME.")</p>
                        <p class='pfsubtitle'>PF Statement Report - Financial Year-$fy</p></div>
                        <div style='clear:both;'></div>
                    </div>";
            $html .= "
                <br>
                <table style='width:100%;font-size:12px;'>
                    <tr>
                        <th style='width:20%'>Employee ID : $eid </th>
                        <th style='width:20%'>Name: $n </th>
                        <th style='width:20%'>C-DAC PF No.:  $pf_number</th>
                        <th style='width:20%'>UAN No.:  $uan_number</th>
                    </tr>
                </table>
                    ";
            $tc = "text-align:left;";
            $thtitle = "font-weight:bold;";
            $html .= "<br>
                <table class='pfdtl' style='width:100%;font-size:12px; border-collapse: collapse; overflow: wrap'>
                    <tr>
                        <td style='$thtitle'>Sr. No.</td>
                        <td style='$thtitle width:20%'>Particulars</td>
                        <td style='$thtitle'>Apr</td>
                        <td style='$thtitle'>May</td>
                        <td style='$thtitle'>June</td>
                        <td style='$thtitle'>July</td>
                        <td style='$thtitle'>Aug</td>
                        <td style='$thtitle'>Sept</td>
                        <td style='$thtitle'>Oct</td>
                        <td style='$thtitle'>Nov</td>
                        <td style='$thtitle'>Dec</td>
                        <td style='$thtitle'>Jan</td>
                        <td style='$thtitle'>Feb</td>
                        <td style='$thtitle'>Mar</td>
                        <td style='$thtitle'>Total</td>
                    </tr>
                    
                    ";
            $monthlyreport = Yii::$app->finance->pf_get_monthwise_details($fy, NULL, NULL, Yii::$app->user->identity->e_id, "Paid");
//            pf_get_monthwise_details($param_financial_year, $param_pf_month, $param_pf_year, $param_employee_code, $param_status)
            if(!empty($monthlyreport)){
                $tdpd = "padding-left: 15px;";
                $html .="<tr>
                        <td style='$tc' rowspan='3'>1</td>
                        <td style='$tc $thtitle'>Opening Balance</td>
                    </tr>
                    <tr>
                        <td style='$tc $tdpd'>Employee</td>
                        <td style='$tc'></td>
                        <td style='$tc' colspan='11'></td>
                        <td style='$tc'></td>
                    </tr>
                    <tr>
                        <td style='$tc $tdpd'>Employer</td>
                        <td style='$tc'></td>
                        <td style='$tc' colspan='11'></td>
                        <td style='$tc'></td>
                    </tr>
                     ";
                     $html .="<tr>
                        <td style='$tc'>2</td>
                        <td style='$tc $thtitle'>PiPB + DA + GP</td>";
                        for($i=1;$i<=12;$i++){
                            $html .="<td style='$tc'>0</td>";
                        }
                    $html .="<td style='$tc'>0</td></tr>";
                
                // Employee PF
                $html .="<tr>
                        <td style='$tc' rowspan='3'>3</td>
                        <td style='$tc $thtitle' >Employee</td>
                    </tr>";
                $tdpd = "padding-left: 15px;";
                $html .="<tr>";
                $html .= "<td style='$tc $tdpd'>PF</td>";
                for($i=4;$i<=12;$i++){
                    $year = substr($fy, 0,4);
                    $pf = 0;
                    $emp = Yii::$app->finance->pf_get_monthwise_details(NULL, $i, $year, Yii::$app->user->identity->e_id, "Paid");
                    if(!empty($emp)){ 
                        $pf = $emp['member_pf'];
                        $empPFtotal = $empPFtotal+$pf;
                    }
                    $html .= "<td style='$tc'>$pf</td>"; 
                }
                for($i=1;$i<=3;$i++){
                    $year = substr($fy, 5,8);
                    $pf = 0;
                    $emp = Yii::$app->finance->pf_get_monthwise_details(NULL, $i, $year, Yii::$app->user->identity->e_id, "Paid");
                    if(!empty($emp)){ 
                        $pf = $emp['member_pf'];
                        $empPFtotal = $empPFtotal+$pf;
                    }
                    $html .= "<td  style='$tc'>$pf</td>"; 
                }
                $html .= "<td  style='$tc'>$empPFtotal</td>"; 
                $html .="</tr>";
                
                $empVPFtotal = 0;
                $html .="<tr>";
                $html .= "<td style='$tc $tdpd'>VPF</td>";
                for($i=4;$i<=12;$i++){
                    $year = substr($fy, 0,4);
                    $pf = 0;
                    $emp = Yii::$app->finance->pf_get_monthwise_details(NULL, $i, $year, Yii::$app->user->identity->e_id, "Paid");
                    if(!empty($emp)){ 
                        $pf = $emp['member_vpf'];
                        $empVPFtotal = $empVPFtotal+$pf;
                    }
                    $html .= "<td  style='$tc'>$pf</td>"; 
                }
                for($i=1;$i<=3;$i++){
                    $year = substr($fy, 5,8);
                    $pf = 0;
                    $emp = Yii::$app->finance->pf_get_monthwise_details(NULL, $i, $year, Yii::$app->user->identity->e_id, "Paid");
                    if(!empty($emp)){ 
                        $pf = $emp['member_vpf'];
                        $empVPFtotal = $empVPFtotal+$pf;
                    }
                    $html .= "<td style='$tc'>$pf</td>"; 
                }
                $html .= "<td  style='$tc'>$empVPFtotal</td>"; 
                $html .="</tr>";
                // End Employee VPF
                
                // Employer PF
                $emprPFtotal = 0;
                $html .="<tr>
                        <td style='$tc' rowspan='3'>4</td>
                        <td style='$tc $thtitle'>Employer</td>
                    </tr>";
                
                $html .="<tr >";
                $html .= "<td style='$tc $tdpd'>PF</td>";
                for($i=4;$i<=12;$i++){
                    $year = substr($fy, 0,4);
                    $pf = 0;
                    $emp = Yii::$app->finance->pf_get_monthwise_details(NULL, $i, $year, Yii::$app->user->identity->e_id, "Paid");
                    if(!empty($emp)){ 
                        $pf = $emp['employer_pf'];
                        $emprPFtotal = $emprPFtotal+$pf;
                    }
                    $html .= "<td style='$tc'>$pf</td>"; 
                }
                for($i=1;$i<=3;$i++){
                    $year = substr($fy, 5,8);
                    $pf = 0;
                    $emp = Yii::$app->finance->pf_get_monthwise_details(NULL, $i, $year, Yii::$app->user->identity->e_id, "Paid");
                    if(!empty($emp)){ 
                        $pf = $emp['employer_pf'];
                        $emprPFtotal = $emprPFtotal+$pf;
                    }
                    $html .= "<td style='$tc'>$pf</td>"; 
                }
                $html .= "<td style='$tc'>$emprPFtotal</td>"; 
                $html .="</tr>";
                
                $emprVPFtotal = 0;
                $html .="<tr>";
                $html .= "<td style='$tc $tdpd'>FPF</td>";
                for($i=4;$i<=12;$i++){
                    $year = substr($fy, 0,4);
                    $pf = 0;
                    $emp = Yii::$app->finance->pf_get_monthwise_details(NULL, $i, $year, Yii::$app->user->identity->e_id, "Paid");
                    if(!empty($emp)){ 
                        $pf = $emp['employer_fpf'];
                        $emprVPFtotal = $emprVPFtotal+$pf;
                    }
                    $html .= "<td style='$tc'>$pf</td>"; 
                }
                for($i=1;$i<=3;$i++){
                    $year = substr($fy, 5,8);
                    $pf = 0;
                    $emp = Yii::$app->finance->pf_get_monthwise_details(NULL, $i, $year, Yii::$app->user->identity->e_id, "Paid");
                    if(!empty($emp)){ 
                        $pf = $emp['employer_fpf'];
                        $emprVPFtotal = $emprVPFtotal+$pf;
                    }
                    $html .= "<td style='$tc'>$pf</td>"; 
                }
                $html .= "<td style='$tc'>$emprVPFtotal</td>"; 
                $html .="</tr>";
                // End Employer PF
                
                // interest on PF
                $Current_Interest_Rate_In_PF = Current_Interest_Rate_In_PF;
                $html .="<tr>
                        <td style='$tc' rowspan='3'>5</td>
                        <td style='$tc $thtitle'>Interest on PF @ $Current_Interest_Rate_In_PF</td>
                    </tr>
                    <tr>
                        <td style='$tc $tdpd'>Employee</td>
                        <td style='$tc' colspan='12'></td>
                        <td style='$tc'>0</td>
                    </tr>
                    <tr>
                        <td style='$tc $tdpd'>Employer</td>
                        <td style='$tc' colspan='12'></td>
                        <td style='$tc'>0</td>
                    </tr>
                     ";
                // End interest on PF
                
                // PF IN
                $html .="<tr>
                        <td style='$tc' rowspan='3'>6</td>
                        <td style='$tc $thtitle'>PF IN</td>
                    </tr>
                    <tr>
                        <td style='$tc $tdpd'>Employee</td>";
                        for($i=1;$i<=12;$i++){
                            $html .="<td style='$tc'>0</td>";
                        }
                    $html .="<td style='$tc'>0</td></tr>
                    
                    <tr>
                        <td style='$tc $tdpd'>Employer</td>";
                        for($i=1;$i<=12;$i++){
                            $html .="<td style='$tc'>0</td>";
                        }
                    $html .="<td style='$tc'>0</td></tr>";
                // End PF IN
                
                // PF OUT
                
                $html .="<tr>
                        <td style='$tc' rowspan='3'>7</td>
                        <td style='$tc $thtitle'>PF OUT</td>
                    </tr>
                    <tr>
                        <td style='$tc $tdpd'>Employee</td>";
                        for($i=1;$i<=12;$i++){
                            $html .="<td style='$tc'>0</td>";
                        }
                    $html .="<td style='$tc'>0</td></tr>
                    
                    <tr>
                        <td style='$tc $tdpd'>Employer</td>";
                        for($i=1;$i<=12;$i++){
                            $html .="<td style='$tc'>0</td>";
                        }
                    $html .="<td style='$tc'>0</td></tr>";
                // End PF OUT
                
                // closing
                $Current_Interest_Rate_In_PF = Current_Interest_Rate_In_PF;
                $html .="<tr>
                        <td style='$tc' rowspan='3'>8</td>
                        <td style='$tc $thtitle'>Closing Balance</td>
                    </tr>
                    <tr>
                        <td style='$tc $tdpd'>Employee</td>
                        <td style='$tc' colspan='12'></td>
                        <td style='$tc'>0</td>
                    </tr>
                    <tr>
                        <td style='$tc $tdpd'>Employer</td>
                        <td style='$tc' colspan='12'></td>
                        <td style='$tc'>0</td>
                    </tr>
                     ";
                // closing
                
                $html .="<tr>
                        <td style='$tc $tdpd'>9</td>
                        <td style='$tc $tdpd'><b>Total PF Balance</b></td>
                        <td style='$tc' colspan='12'></td>
                        <td style='$tc'>0</td>
                    </tr>";
                $html .="<tr>
                        <td style='$tc $tdpd'>10</td>
                        <td style='$tc $tdpd'><b>Total FPF Balance</b></td>
                        <td style='$tc' colspan='12'></td>
                        <td style='$tc'>0</td>
                    </tr>";
            }
            $html .= "</table>";
            $html .= "<br><p style='font-size:11px;'>* Family Pension Fund (FPF) is a statutory contribution paid from employer's contribution to Regional Provident Fund Commissioner.<br>This contribution entitles the member's family for the pension on death or attaining 50/58 years age.<br>*Note :- This statement is for your information only and is subject to change depending upon the audit. For an accurate balance, please refer to audited statement of balance.</p>";
            
            $mpdf->WriteHTML($html);
            $name = "PFDetails_$fy_".date('Y_m_d_H_i_s').".pdf";
	    $file = $mpdf->Output($name, 'I');
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . basename($file) . '"');
            header("Cache-Control: max-age=0");
            readfile($file);
            
            
        }
        Yii::$app->getSession()->setFlash('danger', 'Invalid params');
        return $this->redirect($url);
    }
}
