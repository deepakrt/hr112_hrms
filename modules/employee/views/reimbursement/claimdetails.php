<?php
$this->title="Claim Details";
//echo "<pre>";print_r(Yii::$app->user->identity);
//echo "<pre>";print_r($submittedClaims);
//echo "<pre>";print_r($billDetails);
?>
<div class="text-right">
    <p><b>Claim No. </b><?=$submittedClaims['claim_id']?></p>
    <p><b>Claim Date. </b><?=date('d-m-Y', strtotime($submittedClaims['created_on']))?></p>
    <p><b>Status </b><?=$submittedClaims['status']?></p>
</div>
<h6><b>Personal Information</b></h6>
<table class="table table-bordered">
    <tr>
        <td>Employee Code</td>
        <td><?=Yii::$app->user->identity->e_id?></td>
        <td>Name</td>
        <td><?=Yii::$app->user->identity->fullname?></td>
    </tr>
    <tr>
        <td>Designation</td>
        <td><?=Yii::$app->user->identity->desg_name?></td>
        <td>Department</td>
        <td><?=Yii::$app->user->identity->dept_name?></td>
    </tr>
    <tr>
        <td>Email</td>
        <td><?=Yii::$app->user->identity->email_id?></td>
        <td>Phone</td>
        <td><?=Yii::$app->user->identity->phone?></td>
    </tr>
    <tr>
        <td>Joining Date</td>
        <td><?=date('d-m-Y', strtotime(Yii::$app->user->identity->joining_date))?></td>
        <td>Department</td>
        <td><?=Yii::$app->user->identity->dept_name?></td>
    </tr>
</table>
<hr>

<h6><b>Claim Details</b></h6>
<table class="table table-bordered">
    <tr>
        <th>Sr. No. </th>
        <th>Patient Name</th>
        <th>Bill Date</th>
        <th>Bill No.</th>
        <th>Bill Type</th>
        <th>Issuer</th>
        <th>Claimed Amount</th>
        <th>Sanc. Amount</th>
    </tr>
    <?php 
    $i=1;
    foreach($billDetails as $bill){
        $name = Yii::$app->user->identity->fullname." [Self]";
        if(!empty($bill['m_name'])){
            $name = $bill['m_name']." [".$bill['patienttype']."]";
        }
        $sanc = 0;
        if($submittedClaims['status'] == 'Sanctioned'){
            $sanc = $bill['sanctioned_amt'];
        }
    ?>
    <tr>
        <td><?=$i?></td>
        <td><?=$name?></td>
        <td><?=date('d-m-Y', strtotime($bill['bill_date']))?></td>
        <td><?=$bill['bill_num']?></td>
        <td><?=$bill['billtype']?></td>
        <td><?=$bill['bill_issuer']?></td>
        <td><?=$bill['bill_amt']?></td>
        <td><?=$sanc?></td>
    </tr>
    <?php $i++; }
    ?>
</table>

