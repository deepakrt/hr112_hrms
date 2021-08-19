<?php
$this->title="Employee Provident Fund";
//echo "<pre>";print_r($pfAcc);
if(!empty($pfAcc)){
    $currentFy = Yii::$app->finance->getCurrentFY();
    
?>
<div class="row">
    <div class="col-sm-6">
        <table class="table table-bordered font12">
            <tr class="thtitle">
                <th colspan="2">Account Details</th>
            </tr>
            <tr>
                <td>UAN</td>
                <td><?=$pfAcc['uan_number']?></td>
            </tr>
            <tr>
                <td>PF Account No.</td>
                <td><?=$pfAcc['pf_number']?></td>
            </tr>
            <tr>
                <td>Subscription Date</td>
                <td><?=date('d-M-Y', strtotime($pfAcc['subscription_date']));?></td>
            </tr>
            <tr>
                <td>FPF Account No.</td>
                <td><?=$pfAcc['fpf_account']?></td>
            </tr>
            <tr>
                <td>Account Details</td>
                <td>
                    <a href="javascript:void(0)" class="linkcolor">Download</a>
                    <a href="javascript:void(0)" class="linkcolor">Summary</a>
                </td>
            </tr>
            
        </table>
    </div>
    <div class="col-sm-6">
        <?php 
        $monthlyreport = Yii::$app->finance->pf_get_monthwise_details($currentFy, NULL, NULL, Yii::$app->user->identity->e_id, "Paid");
//        echo "<pre>";print_r($monthlyreport);
        $Cdt =$Opdt = "-";
        $grandTotal =$Employerclosing =$Memberclosing = $monthlyTotal =$Employermonth =$Membermonth =$totalop =$memberPf = $employerPf= 0;
        if(!empty($monthlyreport)){
            $Opdt = date('d-M-Y', strtotime($monthlyreport[0]['pf_year']."-".$monthlyreport[0]['pf_month']."-01"));
            $Cdt = date('t-M-Y', strtotime($monthlyreport[0]['pf_year']."-".$monthlyreport[0]['pf_month']."-31"));
            
            foreach($monthlyreport as $m){
                $memberPf = $memberPf+$m['member_pf']+$m['member_vpf'];
                $employerPf = $employerPf+$m['employer_pf']+$m['employer_fpf'];
            }
            $totalop = $memberPf+$employerPf;
            $Membermonth = $monthlyreport[0]['member_pf']+$monthlyreport[0]['member_vpf'];
            $Employermonth = $monthlyreport[0]['employer_pf']+$monthlyreport[0]['employer_fpf'];
            $monthlyTotal = $Membermonth+$Employermonth;
            
            $Memberclosing = $memberPf+$Membermonth;
            $Employerclosing = $employerPf+$Employermonth;
            $grandTotal = $Memberclosing+$Employerclosing;
        }
        ?>
        <table class="table table-bordered font12">
            <tr class="thtitle">
                <th></th>
                <th>Member</th>
                <th>Employer</th>
                <th>Total</th>
            </tr>
            <tr>
                <td><b>Opening Balance (<?=$Opdt?>)</b></td>
                <td><?=number_format($memberPf)?></td>
                <td><?=number_format($employerPf)?></td>
                <td><?=number_format($totalop)?></td>
            </tr>
            <tr>
                <td>[+] Monthly Contribution</td>
                <td><?=number_format($Membermonth)?></td>
                <td><?=number_format($Employermonth)?></td>
                <td><?=number_format($monthlyTotal)?></td>
            </tr>
            <tr>
                <td><b>Closing Balance (<?=$Cdt?>)</b></td>
                <td><?=number_format($Memberclosing)?></td>
                <td><?=number_format($Employerclosing)?></td>
                <td><?=number_format($grandTotal)?></td>
            </tr>
        </table>
    </div>
</div>

<table class="table table-bordered font12">
    <tr class="thtitle">
        <th colspan="10"> Financial Year wise Summary </th>
    </tr>
    <tr>
        <th>Fin-Year</th>
        <th>Total Op Balance</th>
        <th>Total Contri</th>
        <th>Total Withdrawal</th>
        <th>Total Transfer</th>
        <th>Mem Interest</th>
        <th>Emp Interest</th>
        <th>Total Interest</th>
        <th>Total Closing Bal</th>
        <th></th>
    </tr>
    <?php 
    $fywise = Yii::$app->finance->pf_get_fy_iwse_emp_details(Yii::$app->user->identity->e_id);
    if(!empty($fywise)){
        foreach($fywise as $f){
            $fy = Yii::$app->utility->encryptString($f['financial_year']);
            $totalint = $f['total_member_int']+$f['total_employer_int'];
            $clocinsg_bal = ($f['opening_balance']+$f['totalcontri']+$f['total_member_int']+$f['total_employer_int'])-($f['totalwithdrawal']-$f['totaltransfer']);
            
            
            $dwnl = Yii::$app->homeUrl."employee/payroll/pfreport?securekey=$menuid&key=$fy";
            $dwnl = "<a href='$dwnl' target='_blank' title='Download ".$f['financial_year']." EPF Report' ><img width='20' src='".Yii::$app->homeUrl."images/pdf.png' /></a>";
            echo "<tr>
                <td>".$f['financial_year']."</td>
                <td>".number_format($f['opening_balance'])."</td>
                <td>".number_format($f['totalcontri'])."</td>
                <td>".number_format($f['totalwithdrawal'])."</td>
                <td>".number_format($f['totaltransfer'])."</td>
                <td>".number_format($f['total_member_int'])."</td>
                <td>".number_format($f['total_employer_int'])."</td>
                <td>".number_format($totalint)."</td>
                <td>".number_format($clocinsg_bal)."</td>
                <td>$dwnl</td>
            </tr>";
        }
    }else{
        echo "<tr>
            <td colspan='10'>No Record Found</td>
            ";
    }
    ?>
</table>

<?php
}else{ ?>
<div class="alert alert-danger">
    <p>No Provident Details Found</p>
</div>  
<?php } ?>