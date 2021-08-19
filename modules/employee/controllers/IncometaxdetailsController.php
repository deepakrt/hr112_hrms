<?php

namespace app\modules\employee\controllers;
use yii;
use app\models\HrTourRequisition;
class IncometaxdetailsController extends \yii\web\Controller
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
    
    public function actionIndex(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."employee/incometaxdetails?securekey=$menuid";
        
        $this->layout = '@app/views/layouts/admin_layout.php';
        $fy =  Yii::$app->finance->financialYrListFromJoining();
        $selectedFy = $fy[0];
        if(isset($_GET['fn']) AND !empty($_GET['fn'])){
            $selectedFy = Yii::$app->utility->decryptString($_GET['fn']);
            if(empty($selectedFy)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid Financial Year.');
                return $this->redirect($url);
            }
        }
        $incometax = Yii::$app->finance->fn_display_incomeTax('Short', Yii::$app->user->identity->employee_code, $selectedFy);
        
        return $this->render('index', ['menuid'=>$menuid, 'fy'=>$fy, 'incometax'=>$incometax, 'selectedFy'=>$selectedFy ]);
    }
    
    public function actionDownloadtaxslip(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."employee/incometaxdetails?securekey=$menuid";
        if(isset($_GET['fn']) AND !empty($_GET['fn'])){
            $selectedFy = Yii::$app->utility->decryptString($_GET['fn']);
            if(empty($selectedFy)){
                
                Yii::$app->getSession()->setFlash('danger', 'Invalid Financial Year.');
                return $this->redirect($url);
            }
            $eid = Yii::$app->user->identity->e_id;
            $n = Yii::$app->user->identity->fullname;
            $gender = Yii::$app->user->identity->gender;
            $jd = date('d-m-Y', strtotime(Yii::$app->user->identity->joining_date));
            require_once './mpdf/mpdf.php';
            $mpdf = new \mPDF();
            $monthYear = date('M-Y', strtotime("$year-$month-01"));
            $date = date('d-m-Y H:i');
            $header = "<div style='width:100%;'>
                    <div style='width:75%;float:left;'>
                        <div style='text-align:center'>
                            <p style='margin:0px; font-size:15px;font-weight:bold;font-family:arial;'></b>".ORGANAZATION_NAME."</b></p>
                            <p style='margin:0px;font-size:13px;font-weight:bold;font-family:arial;'>Tax Slip $selectedFy</p>
                        </div>
                    </div>
                    <div style='width:24%;float:right;'>
                        <img src='".Yii::$app->homeUrl.PDF_Company_Logo."' style='width:50%;padding-left:60%;margin-bottom:20px;' />
                        <div style='text-align:right;font-weight:bold;font-size:11px;'>Date : $date<br>TS/".date('Y')."/$eid<div>
                    </div>
                    <div style='clear:both;'></div>
                </div><div style='clear:both;'></div>";
            $border = "border:1px solid black;font-size:12px;padding:3px;";
            $mpdf->WriteHTML($header);
            $html = "";
            
            $html .= "<br><table width='100%' style='$border border-collapse: collapse; overflow: wrap'>
                      <tr style='$border'>
                            <td style='$border'>EmpId</td>
                            <td style='$border'>$eid</td>
                            <td style='$border'>Name</td>
                            <td style='$border'>$n</td>
                    </tr>
                    <tr style='$border'>
                            <td style='$border'>PAN</td>
                            <td style='$border'></td>
                            <td style='$border'>Gender</td>
                            <td style='$border'>$gender</td>
                    </tr> 
                    <tr style='$border'>
                            <td style='$border'>Joining Date</td>
                            <td style='$border'>$jd</td>
                            <td style='$border'>Status</td>
                            <td style='$border'>Active</td>
                    </tr> 
                </table>
            ";
            $amtWidth = "width:25%;";
            $html .= "<br><div style='width:100%;'>
                    <div style='width:50%;float:left;'>
                        <table width='100%' style='$border border-collapse: collapse; overflow: wrap'>
                            <tr style='$border'>
                                <th style='$border; text-align:left;' colspan='2'># Gross Income</th>
                            </tr>
                            <tr style='$border'>
                                <td style='$border'>From Salary</td>
                                <td style='$border $amtWidth' align='right'>0</td>
                            </tr>
                            <tr style='$border'>
                                <td style='$border'>Children Education Allowance</td>
                                <td style='$border $amtWidth' align='right'>0</td>
                            </tr>
                            <tr style='$border'>
                                <td style='$border'><b># Total Gross Income</b></td>
                                <td style='$border $amtWidth' align='right'>0</td>
                            </tr>
                        </table>
                        <br>
                        <table width='100%' style='$border border-collapse: collapse; overflow: wrap'>
                            <tr style='$border'>
                                <th style='$border; text-align:left;' colspan='2'># Other Income From CDAC</th>
                            </tr>
                            <tr style='$border'>
                                <td style='$border'><b>Gross After CDAC Other Income</b></td>
                                <td style='$border $amtWidth' align='right'>0</td>
                            </tr>
                        </table>
                        <br>
                        <table width='100%' style='$border border-collapse: collapse; overflow: wrap'>
                            <tr style='$border'>
                                <th style='$border; text-align:left;' colspan='2'># Perquisites</th>
                            </tr>
                            <tr style='$border'>
                                <td style='$border'>Lease</td>
                                <td style='$border $amtWidth' align='right'>0</td>
                            </tr>
                            <tr style='$border'>
                                <td style='$border'>Medical Reimbursement</td>
                                <td style='$border $amtWidth' align='right'>0</td>
                            </tr>
                            <tr style='$border'>
                                <td style='$border'>Interest on Subsidized Loan</td>
                                <td style='$border $amtWidth' align='right'>0</td>
                            </tr>
                            <tr style='$border'>
                                <td style='$border'><b>Total Perquisites</b></td>
                                <td style='$border $amtWidth' align='right'>0</td>
                            </tr>
                            <tr style='$border'>
                                <td style='$border'><b>Gross After Perquisites</b></td>
                                <td style='$border $amtWidth' align='right'>0</td>
                            </tr>
                        </table>
                        <br>
                        <table width='100%' style='$border border-collapse: collapse; overflow: wrap'>
                            <tr style='$border'>
                                <th style='$border; text-align:left;' colspan='2'># Exemptions Under Section 10</th>
                            </tr>
                            <tr style='$border'>
                                <td style='$border'>HRA Exemption</td>
                                <td style='$border $amtWidth' align='right'>0</td>
                            </tr>
                            <tr style='$border'>
                                <td style='$border'>Transport Exemption</td>
                                <td style='$border $amtWidth' align='right'>0</td>
                            </tr>
                            <tr style='$border'>
                                <td style='$border'>Child Education Allowance Exemption</td>
                                <td style='$border $amtWidth' align='right'>0</td>
                            </tr>
                            <tr style='$border'>
                                <td style='$border'><b>Total Exemptions Under Section 10</b></td>
                                <td style='$border $amtWidth' align='right'>0</td>
                            </tr>
                            <tr style='$border'>
                                <td style='$border'><b>Gross After Section 10 Exemptions</b></td>
                                <td style='$border $amtWidth' align='right'>0</td>
                            </tr>
                        </table>
                        <br>
                        <table width='100%' style='$border border-collapse: collapse; overflow: wrap'>
                            <tr style='$border'>
                                <th style='$border; text-align:left;' colspan='2'># Other Income</th>
                            </tr>
                            <tr style='$border'>
                                <td style='$border'>Other Income Reported by Employee</td>
                                <td style='$border $amtWidth' align='right'>0</td>
                            </tr>
                            <tr style='$border'>
                                <td style='$border'>Income From House Property</td>
                                <td style='$border $amtWidth' align='right'>0</td>
                            </tr>
                            <tr style='$border'>
                                <td style='$border'>Previous Employer Income</td>
                                <td style='$border $amtWidth' align='right'>0</td>
                            </tr>
                            <tr style='$border'>
                                <td style='$border'><b>Total Other Income</b></td>
                                <td style='$border $amtWidth' align='right'>0</td>
                            </tr>
                            <tr style='$border'>
                                <td style='$border'><b>Gross After Other Income</b></td>
                                <td style='$border $amtWidth' align='right'>0</td>
                            </tr>
                        </table>
                        <br>
                        <table width='100%' style='$border border-collapse: collapse; overflow: wrap'>
                            <tr style='$border'>
                                <th style='$border; text-align:left;' colspan='2'># Exemptions Under Section 16</th>
                            </tr>
                            <tr style='$border'>
                                <td style='$border'>Professional Tax</td>
                                <td style='$border $amtWidth' align='right'>0</td>
                            </tr>
                            <tr style='$border'>
                                <td style='$border'><b>Total Exemptions Under Section 16</b></td>
                                <td style='$border $amtWidth' align='right'>0</td>
                            </tr>
                            <tr style='$border'>
                                <td style='$border'><b>Gross After Section 16 Exemptions</b></td>
                                <td style='$border $amtWidth' align='right'>0</td>
                            </tr>
                        </table>
                        <br>
                        <table width='100%' style='$border border-collapse: collapse; overflow: wrap'>
                            <tr style='$border'>
                                <th style='$border; text-align:left;' colspan='2'># Loss on House Property</th>
                            </tr>
                            <tr style='$border'>
                                <td style='$border'>Loss on House Property [Self]</td>
                                <td style='$border $amtWidth' align='right'>0</td>
                            </tr>
                            <tr style='$border'>
                                <td style='$border'><b>Gross After Loss on House Property</b></td>
                                <td style='$border $amtWidth' align='right'>0</td>
                            </tr>
                        </table>
                        
                        
                    </div>
                    
                    <div style='width:49%;float:right;'>
                        <table width='100%' style='$border border-collapse: collapse; overflow: wrap'>
                            <tr style='$border'>
                                <th style='$border; text-align:left;' colspan='2'># Deductions Under Chapter VIA</th>
                            </tr>
                            <tr style='$border'>
                                <td style='$border'>Investments under Sec80CCE (Qaulifying)</td>
                                <td style='$border $amtWidth' align='right'>0</td>
                            </tr>
                            <tr style='$border'>
                                <td style='$border'><b>Total Chapter VIA Deduction</b></td>
                                <td style='$border $amtWidth' align='right'>0</td>
                            </tr>
                            <tr style='$border'>
                                <td style='$border'><b>Gross After Chapter VIA</b></td>
                                <td style='$border $amtWidth' align='right'>0</td>
                            </tr>
                        </table>
                        <br>
                        <table width='100%' style='$border border-collapse: collapse; overflow: wrap'>
                            <tr style='$border'>
                                <th style='$border; text-align:left;' colspan='2'># Standard Deduction Details</th>
                            </tr>
                            <tr style='$border'>
                                <td style='$border'>Standard Deduction</td>
                                <td style='$border $amtWidth' align='right'>0</td>
                            </tr>
                            <tr style='$border'>
                                <td style='$border'><b>Gross After Std. Deduction</b></td>
                                <td style='$border $amtWidth' align='right'>0</td>
                            </tr>
                        </table>
                        <br>
                        <table width='100%' style='$border border-collapse: collapse; overflow: wrap'>
                            <tr style='$border'>
                                <th style='$border; text-align:left;' colspan='2'># Tax Summary</th>
                            </tr>
                            <tr style='$border'>
                                <td style='$border'><b>Taxable Income</b></td>
                                <td style='$border $amtWidth' align='right'>0</td>
                            </tr>
                            <tr style='$border'>
                                <td style='$border'>Income Tax</td>
                                <td style='$border $amtWidth' align='right'>0</td>
                            </tr>
                            <tr style='$border'>
                                <td style='$border'>Tax Rebate</td>
                                <td style='$border $amtWidth' align='right'>0</td>
                            </tr>
                            <tr style='$border'>
                                <td style='$border'>Net Income Tax</td>
                                <td style='$border $amtWidth' align='right'>0</td>
                            </tr>
                            <tr style='$border'>
                                <td style='$border'>Surcharge</td>
                                <td style='$border $amtWidth' align='right'>0</td>
                            </tr>
                            <tr style='$border'>
                                <td style='$border'>Education Cess</td>
                                <td style='$border $amtWidth' align='right'>0</td>
                            </tr>
                            <tr style='$border'>
                                <td style='$border'><b>[A] Total Tax Payable Without Sec89</b></td>
                                <td style='$border $amtWidth' align='right'>0</td>
                            </tr>
                            <tr style='$border'>
                                <td style='$border'><b>[B] Section 89 Relief</b></td>
                                <td style='$border $amtWidth' align='right'>0</td>
                            </tr>
                            <tr style='$border'>
                                <td style='$border'><b>[C] Net Tax Payable (A-B)</b></td>
                                <td style='$border $amtWidth' align='right'>0</td>
                            </tr>
                            <tr style='$border'>
                                <td style='$border'><b>[D] Tax Deducted Till Date</b></td>
                                <td style='$border $amtWidth' align='right'>0</td>
                            </tr>
                            <tr style='$border'>
                                <td style='$border'><b>[E] Previous Employer TDS</b></td>
                                <td style='$border $amtWidth' align='right'>0</td>
                            </tr>
                            <tr style='$border'>
                                <td style='$border'><b>[F] Tax Due/Refund [C-(D+E)]</b></td>
                                <td style='$border $amtWidth' align='right'>0</td>
                            </tr>
                        </table>
                    </div>
                    <div style='clear:both;'></div>
                </div>";
            $mpdf->WriteHTML($html);
            $mpdf->addPage();
            $page2 = "";
            $Page2amtWidth = "width:16%;font-size:12px;";
            $page2 .= "
            <div style='width:100%;'>
                <div style='width:50%;float:left;font-size:12px;'><b>Details</b></div>
                <div style='width:50%;float:right;font-size:12px;text-align:right;'><b>TS/".date('Y')."/$eid</b></div>
                <div style='clear:both;'></div>
            </div>
            <br>
            <div style='width:100%;'>
                <div style='width:50%;float:left;'>
                    <table width='100%' style='$border border-collapse: collapse; overflow: wrap'>
                        <tr style='$border'>
                            <th style='$border; text-align:left;' colspan='6'>1] Tax Deducted</th>
                        </tr>
                        <tr style='$border'>
                            <td style='$border'>Month</td>
                            <td style='$border'>Due</td>
                            <td style='$border'>Deducted</td>
                            <td style='$border'>Refund</td>
                            <td style='$border'>Cheque #</td>
                            <td style='$border'>Net ##</td>
                        </tr>
                        <tr style='$border'>
                            <td style='$border'>APR</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                        </tr>
                        <tr style='$border'>
                            <td style='$border'>MAY</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                        </tr>
                        <tr style='$border'>
                            <td style='$border'>JUNE</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                        </tr>
                        <tr style='$border'>
                            <td style='$border'>JULY</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                        </tr>
                        <tr style='$border'>
                            <td style='$border'>AUG</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                        </tr>
                        <tr style='$border'>
                            <td style='$border'>SEPT</td>
                            <td style='$border $amtWidth' align='right'>0</td>
                            <td style='$border $amtWidth' align='right'>0</td>
                            <td style='$border $amtWidth' align='right'>0</td>
                            <td style='$border $amtWidth' align='right'>0</td>
                            <td style='$border $amtWidth' align='right'>0</td>
                        </tr>
                        <tr style='$border'>
                            <td style='$border'>OCT</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                        </tr>
                        <tr style='$border'>
                            <td style='$border'>NOV</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                        </tr>
                        <tr style='$border'>
                            <td style='$border'>DEC</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                        </tr>
                        <tr style='$border'>
                            <td style='$border'>JAN</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                        </tr>
                        <tr style='$border'>
                            <td style='$border'>FEB</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                        </tr>
                        <tr style='$border'>
                            <td style='$border'>MAR</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                        </tr>
                        <tr style='$border'>
                            <td style='$border'>TOTAL</td>
                            <td style='$border $Page2amtWidth' align='right'></td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                            <td style='$border $Page2amtWidth' align='right'>0</td>
                        </tr>
                        <tr style='$border'>
                            <td style='$border color:red;' colspan='6'># Tax Paid By Cheque</td>
                        </tr>
                        <tr style='$border'>
                            <td style='$border color:red;' colspan='6'>## Net = Deducted - Refund + Tax Paid by Cheque</td>
                        </tr>
                    </table>
                    <br>
                    <table width='100%' style='$border border-collapse: collapse; overflow: wrap'>
                        <tr style='$border'>
                            <th style='$border; text-align:left;' colspan='2'>2] Section 80CCE</th>
                        </tr>
                        <tr style='$border'>
                            <td style='$border'>Life Insurance Premium [U/S 80C]</td>
                            <td style='$border $amtWidth' align='right'>0</td>
                        </tr>
                        <tr style='$border'>
                            <td style='$border'>National Savings Certificate [NSC] [U/S 80C]</td>
                            <td style='$border $amtWidth' align='right'>0</td>
                        </tr>
                        <tr style='$border'>
                            <td style='$border'>Public Provident Fund [PPF] [U/S 80C]</td>
                            <td style='$border $amtWidth' align='right'>0</td>
                        </tr>
                        <tr style='$border'>
                            <td style='$border'>Mutual Funds [U/S 80C]</td>
                            <td style='$border $amtWidth' align='right'>0</td>
                        </tr>
                        <tr style='$border'>
                            <td style='$border'>Provident Fund</td>
                            <td style='$border $amtWidth' align='right'>0</td>
                        </tr>
                        <tr style='$border'>
                            <td style='$border'>Voluntary Provident Fund</td>
                            <td style='$border $amtWidth' align='right'>0</td>
                        </tr>
                        <tr style='$border'>
                            <td style='$border'><b>Total Section 80CCE</b></td>
                            <td style='$border $amtWidth' align='right'>0</td>
                        </tr>
                        <tr style='$border'>
                            <td style='$border'><b>Qualifying Total Section 80CCE</b></td>
                            <td style='$border $amtWidth' align='right'>0</td>
                        </tr>
                    </table>
                    <br>
                    <table width='100%' style='$border border-collapse: collapse; overflow: wrap'>
                        <tr style='$border'>
                            <th style='$border; text-align:left;' colspan='2'>3] Loss on House Property Breakup</th>
                        </tr>
                        
                        <tr style='$border'>
                            <td style='$border'><b>Total House Loan Interest</b></td>
                            <td style='$border $amtWidth' align='right'>0</td>
                        </tr>
                        <tr style='$border'>
                            <td style='$border'><b>Total Qualifying Amount</b></td>
                            <td style='$border $amtWidth' align='right'>0</td>
                        </tr>
                    </table>
                </div>
                <div style='width:49%;float:right;'>
                    <table width='100%' style='$border border-collapse: collapse; overflow: wrap'>
                        <tr style='$border'>
                            <th style='$border; text-align:left;' colspan='2'>4] Calculation of HRA Exemption</th>
                        </tr>
                        
                        <tr style='$border'>
                            <td style='$border'>PiPB/ ConsPay + GradePay + DA #</td>
                            <td style='$border $amtWidth' align='right'>0</td>
                        </tr>
                        <tr style='$border'>
                            <td style='$border'>Rent Paid</td>
                            <td style='$border $amtWidth' align='right'>0</td>
                        </tr>
                        <tr style='$border'>
                            <td style='$border'>[A] Rent Paid - 10 % [PiPB/ ConsPay + GradePay + DA]</td>
                            <td style='$border $amtWidth' align='right'>0</td>
                        </tr>
                        <tr style='$border'>
                            <td style='$border'>[B] HRA Paid #</td>
                            <td style='$border $amtWidth' align='right'>0</td>
                        </tr>
                        <tr style='$border'>
                            <td style='$border'>[C] XX% [PiPB/ ConsPay + GradePay + DA]</td>
                            <td style='$border $amtWidth' align='right'>0</td>
                        </tr>
                        <tr style='$border'>
                            <td style='$border'><b>HRA Exemption [ Min(A,B,C) ]</b></td>
                            <td style='$border $amtWidth' align='right'>0</td>
                        </tr>
                        <tr style='$border'>
                            <td style='$border color:red;' colspan='2'>
                                # XX% [It is 50% for metropolitan cities and 40% for all other cities].#<br>
                                Allowances like PiPB/ Cons Pay, Grade Pay, DA and HRA are considered for the months for which 
                                HRA Exemption is to be availed.
                            </td>
                        </tr>
                    </table>
                    <br>
                    <table width='100%' style='$border border-collapse: collapse; overflow: wrap'>
                        <tr style='$border'>
                            <th style='$border; text-align:left;' colspan='2'>5] Calculation of Rent Free/ Leased Accommodation Perq</th>
                        </tr>
                        
                        <tr style='$border'>
                            <td style='$border'>Gross #</td>
                            <td style='$border $amtWidth' align='right'>0</td>
                        </tr>
                        <tr style='$border'>
                            <td style='$border'>[A] 15 % Gross</td>
                            <td style='$border $amtWidth' align='right'>0</td>
                        </tr>
                        <tr style='$border'>
                            <td style='$border'>[B] Lease Rent Paid$</td>
                            <td style='$border $amtWidth' align='right'>0</td>
                        </tr>
                        <tr style='$border'>
                            <td style='$border'>[C] Lease Rent Recovery [LRR]</td>
                            <td style='$border $amtWidth' align='right'>0</td>
                        </tr>
                        <tr style='$border'>
                            <td style='$border'><b>If [A] <= [B] [Applicable]</b></td>
                            <td style='$border $amtWidth' align='right'>0</td>
                        </tr>
                        <tr style='$border'>
                            <td style='$border'>Perquisite Value of Lease [A]</td>
                            <td style='$border $amtWidth' align='right'>0</td>
                        </tr>
                        <tr style='$border'>
                            <td style='$border'>Lease Value Paid by Employee [C]</td>
                            <td style='$border $amtWidth' align='right'>0</td>
                        </tr>
                        <tr style='$border'>
                            <td style='$border'>Taxable Lease [A - C]</td>
                            <td style='$border $amtWidth' align='right'>0</td>
                        </tr>
                        <tr style='$border'>
                            <td style='$border'><b>If [A] > [B] [Not Applicable]</b></td>
                            <td style='$border $amtWidth' align='right'>0</td>
                        </tr>
                        <tr style='$border'>
                            <td style='$border'>Perquisite Value of Lease [B]</td>
                            <td style='$border $amtWidth' align='right'>0</td>
                        </tr>
                        <tr style='$border'>
                            <td style='$border'>Lease Value Paid by Employee [C]</td>
                            <td style='$border $amtWidth' align='right'>0</td>
                        </tr>
                        <tr style='$border'>
                            <td style='$border'>Taxable Lease [B - C]</td>
                            <td style='$border $amtWidth' align='right'>0</td>
                        </tr>
                        <tr style='$border'>
                            <td style='$border color:red;' colspan='2'>
                                # Pro rate Gross for lease rent availed months + other income from CDAC - recovery - Exempted Conveyance Allowance
                            </td>
                        </tr>
                        <tr style='$border'>
                            <td style='$border color:red;' colspan='2'>$ Lease Rent Paid - Excess Lease Rent if any.</td>
                        </tr>
                    </table>
                    <br>
                    <table width='100%' style='$border border-collapse: collapse; overflow: wrap'>
                        <tr style='$border'>
                            <th style='$border; text-align:left;' colspan='2'>6] Calculation of 80GG Rental</th>
                        </tr>
                        
                        <tr style='$border'>
                            <td style='$border'>Gross for 80GG Rental #</td>
                            <td style='$border $amtWidth' align='right'>0</td>
                        </tr>
                    </table>
                </div>
                <div style='clear:both;'></div>
            </div>
            ";
            $mpdf->WriteHTML($page2);
            $mpdf->SetWatermarkText(ORGANAZATION_NAME);
            $mpdf->showWatermarkText = true;
            
            $name = "TaxSlip-$selectedFy".date('Y_m_d_H_i_s').".pdf";
	    $file = $mpdf->Output($name, 'I');
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . basename($file) . '"');
            header("Cache-Control: max-age=0");
            readfile($file);
        }
        Yii::$app->getSession()->setFlash('danger', 'Invalid Financial Year.');
        return $this->redirect($url);
    }
    
    public function actionViewincomeslip(){
        if(isset($_POST['fn']) AND !empty($_POST['fn'])){
            $fn = Yii::$app->utility->decryptString($_POST['fn']);
            if(empty($fn)){
                $result['Status']='FF';
                $result['Res']='Invalid params value found';
                echo json_encode($result);
                die;
            }
            
            $eid = Yii::$app->user->identity->e_id;
            $n = Yii::$app->user->identity->fullname;
            $gender = Yii::$app->user->identity->gender;
            $jd = date('d-m-Y', strtotime(Yii::$app->user->identity->joining_date));
            
            $html = "";
            $html .= "<div class='row'>
                    <div class='col-sm-12'>
                        <table class='table table-bordered'>
                            <tr class='thtitle'>
                                <th colspan='4'>Employee Details:</th>
                            </tr>
                            <tr>
                                <td>Emp ID</td>
                                <td>$eid</td>
                                <td>Name</td>
                                <td>$n</td>
                            </tr>
                            <tr>
                                <td>PAN</td>
                                <td></td>
                                <td>Gender</td>
                                <td>$gender</td>
                            </tr>
                            <tr>
                                <td>Joining Date</td>
                                <td>$jd</td>
                                <td>Status</td>
                                <td>Active</td>
                            </tr>
                        </table>
                        <br>
                        <table class='table table-bordered'>
                            <tr class='thtitle'>
                                <th colspan='10'>Payments / Emoluments made in [$fn]</th>
                            </tr>
                            <tr>
                                <th>For Year</th>
                                <th>Month</th>
                                <th>CONSPAY</th>
                                <th>CANA</th>
                                <th>Other Allowances</th>
                                <th>GROSS</th>
                                <th>CPF</th>
                                <th>VCPF</th>
                                <th>Other Deductions</th>
                                <th>Type</th>
                            </tr>";
            $inf = Yii::$app->finance->fn_display_incomeTax('Full', Yii::$app->user->identity->employee_code, $fn);
//            echo "<pre>";print_r($inf); die;
            if(!empty($inf)){
                $gCONSPAY = $CANA = $gOtherAllwance = $gtGROSS = $CPF = $VCPF = $gOtherdedu = 0;
                foreach($inf as $i){
                    $otherAllwance = $i['allowance_da']+$i['allowance_da_arrear']+$i['allowance_hra']+$i['allowance_ta']+$i['allowance_ta_arrear'];
                    $gross = $otherAllwance+$i['basic_cons_pay']+$i['allowance_canteen'];
                    $month = $i['salYear']."-".$i['salMonth']."-01";
                    $month = date('M-Y', strtotime($month));
                    $otherDeduc = $i['ded_pf_on_arrear']+$i['ded_incomeTax']+$i['ded_lfee']+$i['ded_club']+$i['ded_GSLI']+$i['ded_BenevolentFund']+$i['child_edu']+$i['other_income']+$i['perq_lease']+$i['perq_medical_reimbursement']+$i['perq_interest']+$i['hra_exemption']+$i['transport_exemption']+$i['child_education_allowance_exemption']+$i['other_income_reported_by_employee']+$i['income_from_house_property']+$i['previous_employer_income']+$i['professional_tax']+$i['loss_on_house_property'];
                    $html .= " 
                    <tr>
                        <td>$fn</td>
                        <td>$month</td>
                        <td>".$i['basic_cons_pay']."</td>
                        <td>".$i['allowance_canteen']."</td>
                        <td>$otherAllwance</td>
                        <td>$gross</td>
                        <td>".round($i['ded_empyee_pf_amt'])."</td>
                        <td>0</td>
                        <td>$otherDeduc</td>
                        <td>".$i['status']."</td>
                    </tr>
                    ";
                    $gCONSPAY = round($gCONSPAY+$i['basic_cons_pay']);
                    $CANA = round($CANA+$i['allowance_canteen']);
                    $gOtherAllwance = round($gOtherAllwance+$otherAllwance);
                    $gtGROSS = round($gtGROSS+$gross);
                    $CPF = round($CPF+$i['ded_empyee_pf_amt']);
                    $gOtherdedu = round($gOtherdedu+$otherDeduc);
                }
                $html .= " 
                    <tr>
                        <th colspan='2'>Grand Total</th>
                        <th>$gCONSPAY</th>
                        <th>$CANA</th>
                        <th>$gOtherAllwance</th>
                        <th>$gtGROSS</th>
                        <th>$CPF</th>
                        <th>0</th>
                        <th>$gOtherdedu</th>
                        <th></th>
                    </tr>
                    ";
            }
            $html .= "</table>
                </div>
            </div>";
            $result['Status']='SS';
            $result['Res']=$html;
            echo json_encode($result);
            die;
            
        }else{
            $result['Status']='FF';
            $result['Res']='Invalid params found';
            echo json_encode($result);
            die;
        }
//        echo "<pre>";print_r($_POST['fn']);
    }
}
