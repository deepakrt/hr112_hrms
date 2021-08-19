<?php
use yii\widgets\ActiveForm;
$this->title="View OPD Claims";
$empInfo = Yii::$app->utility->get_employees($ec);

//$claimdetails = $claimdetails[0];
//echo "<pre>";print_r($details);
?>
<style>
    .opdSancAmt{width: 80px; border: 1px dotted;}
</style>

<h6><b>Personal Information</b></h6>
<table class="table table-bordered">
    <tr>
        <td><b>Emp Code :</b><br><?=$ec?></td>
        <td><b>Emp Name :</b><br><?=$empInfo['fullname']?></td>
        <td><b>Designation :</b><br><?=$empInfo['desg_name']?></td>
        <td><b>Department :</b><br><?=$empInfo['dept_name']?></td>
    </tr>
    <tr>
        <td><b>Staff Type :</b><br><?=$empInfo['employment_type']?></td>
        <td><b>Joining Date :</b><br><?=date('d-M-Y', strtotime($empInfo['joining_date']))?></td>
        <td></td>
        <td></td>
    </tr>
</table>
<hr>

<div class="text-right">
    <p><b>Claim No.</b> <?=$claimdetails['claim_id']?></p>
    <p><b>Total Claimed Rs.</b> <?=$claimdetails['total_claim']?></p>
    <p><b>Claim Date</b> <?=date('d-m-Y', strtotime($claimdetails['created_on']))?></p>
</div>
<h6><b>Claim Details</b></h6>
<?php $form = ActiveForm::begin(); ?>

<div id="opdclaims">
<table  class="table table-bordered">
    <tr>
        <th>Sr. No.</th>
        <th>Patient Name</th>
        <th>Bill No. & Dtd</th>
        <th>Bill Type</th>
        <th>Bill Issuer</th>
        <th>Claimed Amt</th>
        <th>Sanctioned Amt</th>
    </tr>
    <?php 
    $i=1;
    $j=0;
    $totalSanc = $totalClaim=0;
    foreach($details as $detail){
        $name = $empInfo['fullname']." [Self]";
        if(!empty($detail['m_name'])){
            $name = $detail['m_name']." [".$detail['patienttype']."]";
        }
        $billnum = $detail['bill_num']." dtd ".date('d-m-Y', strtotime($detail['bill_date']));
        $totalClaim = $totalClaim+$detail['bill_amt'];
        $sancAmt = 0;
        if(!empty($detail['sanctioned_amt'])){
            $sancAmt = $detail['sanctioned_amt'];
        }
        $totalSanc = $totalSanc+$detail['sanctioned_amt'];
    ?>
    <tr>
        <td><?=$i?></td>
        <td><?=$name?></td>
        <td><?=$billnum?></td>
        <td><?=$detail['billtype']?></td>
        <td><?=$detail['bill_issuer']?></td>
        <td><?=$detail['bill_amt']?></td>
        <td><?=$sancAmt?></td>
    </tr>
    <?php $j++; $i++; } ?>
    <tr>
        <td colspan="5"><b>Total [Rs.]</b></td>
        <td><b><?=$totalClaim?></b></td>
        <td><span id="totalSanc" style="font-weight: bold;color:red;"><?=$totalSanc?></span></td>
    </tr>
</table>
</div>
<?php ActiveForm::end(); ?>