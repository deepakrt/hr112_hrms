<?php
use yii\widgets\ActiveForm;
$this->title="View OPD Claims";
$empInfo = Yii::$app->utility->get_employees($ec);

$claimdetails = $claimdetails;
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
        <td>
        <?=$i?>
        <input type="hidden" value="<?=Yii::$app->utility->encryptString($opd_id)?>" name="Claim[<?=$j?>][opd_id]" />
        <input type="hidden" value="<?=Yii::$app->utility->encryptString($entitle_id)?>" name="Claim[<?=$j?>][entitle_id]" />
        <input type="hidden" value="<?=Yii::$app->utility->encryptString($ec)?>" name="Claim[<?=$j?>][ec]" />
        <input type="hidden" value="<?=Yii::$app->utility->encryptString($detail['id'])?>" name="Claim[<?=$j?>][bill_id]" />
        <input type="hidden" value="<?=Yii::$app->utility->encryptString($detail['bill_amt'])?>" name="Claim[<?=$j?>][bill_amt]" />
        </td>
        <td><?=$name?></td>
        <td><?=$billnum?></td>
        <td><?=$detail['billtype']?></td>
        <td><?=$detail['bill_issuer']?></td>
        <td><?=$detail['bill_amt']?></td>
        <td><input type="text" class="opdSancAmt" maxlength="7" value="<?=$sancAmt?>" name="Claim[<?=$j?>][sanctioned_amt]" onkeypress="return allowOnlyNumber(event)" /></td>
    </tr>
    <?php $j++; $i++; } ?>
    <tr>
        <td colspan="5"><b>Total [Rs.]</b></td>
        <td><b><?=$totalClaim?></b></td>
        <td><span id="totalSanc" style="font-weight: bold;color:red;"><?=$totalSanc?></span></td>
    </tr>
</table>
    <div class="row">
        <div class="col-sm-12 text-center">
            <button type="submit" value="In-Process" name="ip" class="btn btn-primary btn-sm sl">In-Process</button>
            <button type="submit" value="Sanction" name="sanc" class="btn btn-success btn-sm sl">Sanction</button>
            <button type="submit" value="Reject" name="reject" class="btn btn-danger btn-sm sl">Reject</button>
            
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
<script>
    $(document).ready(function(){
        $('.opdSancAmt').blur(function(){
//            alert($(this).val());
            var totalAmt = 0;
//            if($(this).val()){
                $('#opdclaims :input[type=text]').each(function() {
                    var tt = parseInt($(this).val());
                    if(!tt){
                        $(this).val('0');
                    }
                    var tt = parseInt($(this).val());
//                    alert("INPUT : "+tt);
//                    alert("Total : "+totalAmt);
                    totalAmt = parseInt(totalAmt) + parseInt(tt);
                });
//            }
//alert("Total "+totalAmt);
//totalAmt = $.number(totalAmt, 2);
            $('#totalSanc').html(totalAmt);
        });
    });
</script>
    