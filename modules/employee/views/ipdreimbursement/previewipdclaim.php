<?php
$this->title = "Preview IPD Claim Details";
use yii\widgets\ActiveForm;
?>
<h6><b>IPD Claim Header</b></h6>
<table class="table table-bordered">
    <tr>
        <td><b>Employee Code : </b><?=$claimDetail['employee_code']?></td>
        <td><b>Employee Name : </b><?=$claimDetail['name']?>, <?=$claimDetail['desg_name']?></td>
        <td><b>Department : </b><?=$claimDetail['dept_name']?></td>
    </tr>
    <tr>
        <td><b>Patient Type : </b><?=$claimDetail['member_name']?></td>
        <td><b>Date Of Admission : </b><?=date('d-M-Y', strtotime($claimDetail['date_of_admission']))?></td>
        <td><b>Date Of Discharge : </b><?=date('d-M-Y', strtotime($claimDetail['date_of_discharge']))?></td>
    </tr>
    <tr>
        <td colspan="3"><b>Admitted For : </b><?=$claimDetail['admitted_for']?></td>
    </tr>
    <tr>
        <td><b>Claim Type : </b><?=$claimDetail['claim_type']?></td>
        <td><b>Claimed Amount : </b><?=$claimDetail['total_claimed_amt']?></td>
        <td>
            <?php 
            $sancAmt="0.00";
            if($claimDetail['status'] == 'Sanctioned'){
                $sancAmt= $claimDetail['total_sanctioned_amt'];
            }
            ?>
            <b>Sanctioned Amount : </b><?=$sancAmt?>
        </td>
    </tr>
</table>
<hr>
<hr>
<h6><b><u>IPD Bill Details</u></b></h6>
<table class="table table-bordered">
    <tr>
        <th>Sr. No.</th>
        <th>Bill No.</th>
        <th>Bill Date</th>
        <th>Bill Issuer</th>
        <th>Claimed Amount</th>
        <th>Sanc. Amount</th>
    </tr>
    <?php 
    if(!empty($billDetails)){
        $i=1;
        $sancTotal = $gTotal = 0;
        foreach($billDetails as $bill){
            $gTotal = $gTotal+$bill['bill_amt'];
            $sancAmt = "0.00";
            if($claimDetail['status'] == 'Sanctioned'){
                $sancAmt = $bill['sanc_amt'];
                $sancTotal = $sancTotal+$bill['sanc_amt'];
            }
        ?>
    <tr>
        <td><?=$i?></td>
        <td><?=$bill['bill_number']?></td>
        <td><?=date('d-m-Y', strtotime($bill['bill_date']))?></td>
        <td><?=$bill['issuer']?></td>
        <td><?=$bill['bill_amt']?></td>
        <td><?=$sancAmt?></td>
    </tr>
            
    <?php   $i++; } ?>
    <tr>
        <td colspan="4" align="right"><b>Total Claimed Amount</b></td>
        <td><b><?=  number_format($gTotal, 2)?></b></td>
        <td><b><?=  number_format($sancTotal, 2)?></b></td>
    </tr>
    <?php }else{
        
    }
    ?>
</table>
<hr>
<div class="text-center">
    <a href="<?=Yii::$app->homeUrl?>employee/ipdreimbursement?securekey=<?=$menuid?>" class="btn btn-danger btn-sm">Back</a>
</div>