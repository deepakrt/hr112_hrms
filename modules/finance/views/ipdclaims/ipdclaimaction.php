<?php
$this->title = "Details of IPD Claim";
use yii\widgets\ActiveForm;
//echo "<pre>";print_r($details); 
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
        <td><b>Claimed Date : </b><?=date('d-M-Y', strtotime($claimDetail['claimed_on']))?></td>
    </tr>
</table>
<hr>
<h6><b>IPD Claim Bill Details</b></h6>
<?php $form = ActiveForm::begin(['action'=>Yii::$app->homeUrl."finance/ipdclaims/updateipdbill?securekey=$menuid", 'options' => ['id'=>'ipdbillform', 'enctype' => 'multipart/form-data']]); ?>
<input type="hidden" name="ipd_id" value="<?=$ipd_id?>" readonly="" />
<input type="hidden" name="ec" value="<?=$ec?>" readonly="" />
<table class="table table-bordered">
    <tr>
        <th>Sr. No.</th>
        <th>Bill Date</th>
        <th>Bill No. </th>
        <th>Issuer </th>
        <th>Bill Amount</th>
        <th>Sanc. Amount</th>
    </tr>
    <?php 
    $sancTotal = $gTotal = "0";
    if(!empty($details)){
        $j=0;
        $i=1;
        
        foreach($details as $detail){
            $sanc_amt = 0;
            if(!empty($detail['sanc_amt'])){
                $sanc_amt = $detail['sanc_amt'];
                $sancTotal =$sancTotal+$detail['sanc_amt'];
            }
            $gTotal = $gTotal+$detail['bill_amt'];
    ?>
    <tr>
        <td><?=$i?>
<!--            <input type="hidden" readonly="" name="Bill[<?=$j?>][employee_code]" value="<?=Yii::$app->utility->encryptString($detail['employee_code'])?>" />
            <input type="hidden" readonly="" name="Bill[<?=$j?>][ipd_id]" value="<?=Yii::$app->utility->encryptString($detail['ipd_id'])?>" />-->
            <input type="hidden" readonly="" name="Bill[<?=$j?>][bill_id]" value="<?=Yii::$app->utility->encryptString($detail['id'])?>" />
            <input type="hidden" readonly="" name="Bill[<?=$j?>][bill_amt]" value="<?=Yii::$app->utility->encryptString($detail['bill_amt'])?>" />
        </td>
        <td><?=date('d-m-Y', strtotime($detail['bill_date']))?></td>
        <td><?=$detail['bill_number']?></td>
        <td><?=$detail['issuer']?></td>
        <td align="right"><?=$detail['bill_amt']?></td>
        <td><input type="number" id="sanc_amt" name="Bill[<?=$j?>][sanc_amt]" onkeypress="return allowOnlyNumber(event)" class="form-control form-control-sm" value="<?=$sanc_amt?>" required="" min="1" max="<?=$detail['bill_amt']?>" /></td>
    </tr>
    <?php $j++; $i++; } ?>
    <tr>
        <td colspan="4" align="right"><b>Total</b></td>
        <td align="right"><b><?=number_format($gTotal, 2)?></b></td>
        <td><b><?=number_format($sancTotal, 2)?></b></td>
    </tr>
    <tr>
        <td colspan="6" align="right"><button type="submit" class="btn btn-success btn-sm sl">Update Sanc. Amt</button> </td>
    </tr>
    <?php    }?>
</table>

<?php ActiveForm::end();?>
<hr>
<?php $form = ActiveForm::begin(['action'=>Yii::$app->homeUrl."finance/ipdclaims/updateipdclaim?securekey=$menuid", 'options' => ['id'=>'ipdbillform', 'enctype' => 'multipart/form-data']]); ?>
<input type="hidden" name="IPD[ipd_id]" value="<?=$ipd_id?>" readonly="" />
<input type="hidden" name="IPD[ec]" value="<?=$ec?>" readonly="" />
<div class="row">
    <div class="col-sm-12">
        <label>Remakrs (If any)</label>
        <textarea name="IPD[remarks]" value="<?=$claimDetail['remarks']?>" rows="5" class="form-control form-control-sm"></textarea>
    </div>
    <div class="col-sm-3">
        <label>Action Type</label>
        <select name="IPD[action_type]" class="form-control form-control-sm" required="" oninvalid="this.setCustomValidity('Select Action Type')">
            <option value="">Select Action</option>
            <option value="<?=Yii::$app->utility->encryptString("Revoked")?>">Revoked</option>
            <option value="<?=Yii::$app->utility->encryptString("In-Process")?>">In-Process</option>
            <option value="<?=Yii::$app->utility->encryptString("Rejected")?>">Rejected</option>
            <option value="<?=Yii::$app->utility->encryptString("Sanctioned")?>">Sanctioned</option>
        </select>
    </div>
    <div class="col-sm-3">
        <button type="submit" style="margin-top: 28px;" class="btn btn-success btn-sm sl">Submit</button>
    </div>
</div>
<?php ActiveForm::end();?>
