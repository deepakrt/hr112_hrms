<?php
use yii\widgets\ActiveForm;
$this->title ="View Contingency Claim";
$project = "N.A.";
if(!empty($record['project_id'])){
    $project = $record['project_name'];
}
//echo "<pre>";print_r($record); die;
?>
<?php $form = ActiveForm::begin(['id'=>'contingencyform', 'action'=>Yii::$app->homeUrl."finance/claimscontingency/updateclaim?securekey=".$menuid]); ?>
<input type="hidden" readonly="" name="Contingency[id]" value="<?=Yii::$app->utility->encryptString($record['id'])?>" />
<input type="hidden" readonly="" name="Contingency[employee_code]" value="<?=Yii::$app->utility->encryptString($record['employee_code'])?>" />
<div class="row">
    
    <div class="col-sm-3">
        <label>Project Name</label>
        <input type="text" class="form-control form-control-sm" readonly="" placeholder="Claim Amount" value="<?=$project?>" maxlength="4" />
        <br>
    </div>
    
    <div class="col-sm-9">
        <label>Purpose</label>
        <textarea class="form-control form-control-sm" readonly="" placeholder="Purpose"><?=$record['purpose']?></textarea>
    </div>
    <div class="col-sm-9">
        <label>Claim Details</label>
        <textarea class="form-control form-control-sm" readonly="" placeholder="Claim Details" ><?=$record['details']?></textarea>
    </div>
    <div class="col-sm-3">
        <br>
        <label>Status</label>
        <input type="text" class="form-control form-control-sm" value="<?=$record['status']?>" readonly="" />
    </div>
    
    <div class="col-sm-3">
        <br>
        <label>Claimed Amount</label>
        <input type="text" class="form-control form-control-sm" readonly="" placeholder="Claim Amount" value="<?=$record['claimed_amt']?>" maxlength="4" />
    </div>
    
    <div class="col-sm-3">
        <br>
        <label>Enter Sanctioned Amount</label>
        <input type="text" class="form-control form-control-sm" name="Contingency[sanctioned_amt]" id="sanctioned_amt" onkeypress="return allowOnlyNumber(event)" placeholder="Enter Sanctioned Amount" maxlength="4" required="" />
    </div>
    <input type="hidden" readonly="" name="Contingency[submit_type]" id="submit_type" />
    <div class="col-sm-6">
        <br><br>
        <?php 
        if($record['status'] == 'Pending'){
            echo '<button type="button" class="btn btn-primary btn-sm" onclick="claimSanction(1)">In-Process</button>
            <button type="button" class="btn btn-success btn-sm" onclick="claimSanction(2)">Sanction</button>';
        }elseif($record['status'] == 'In-Process'){
            echo '<button type="button" class="btn btn-success btn-sm" onclick="claimSanction(2)">Sanction</button>';
        }
        ?>
        <button type="button" class="btn btn-danger btn-sm" onclick="claimSanction(3)">Reject</button>
        <!--<a href="<?=Yii::$app->homeUrl?>finance/claimscontingency?securekey=<?=$menuid?>" class="btn btn-danger btn-sm">Cancel</a>-->
    </div>
</div>
<?php ActiveForm::end(); ?>