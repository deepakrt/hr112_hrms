<?php
//echo "<pre>";print_r($model);die;
use yii\widgets\ActiveForm;
$this->title= 'View Tour Claim of '.$model['fullname'].'('.$model['employee_code'].')';
?>
<h6><u>Tour Details</u></h6>
<?php $form = ActiveForm::begin(['id'=>'entryslipform']); ?>
<div class="row">
    <div class="col-sm-3">
        <label>Department</label>
        <input type="text" class="form-control form-control-sm" value="<?=$model['dept_name']?>" readonly="" />
    </div>
    <div class="col-sm-3">
        <label>Employee Code</label>
        <input type="text" class="form-control form-control-sm" value="<?=$model['employee_code']?>" readonly="" />
    </div>
    <div class="col-sm-3">
        <label>Employee Name</label>
        <input type="text" class="form-control form-control-sm" value="<?=$model['fullname']?>" readonly="" />
    </div>
    <input type="hidden" name="TourClaim[menuid]" value="<?=$menuid?>" readonly="" />
    <input type="hidden" name="TourClaim[req_id]" value="<?=Yii::$app->utility->encryptString($model['req_id'])?>" readonly="" />
    <input type="hidden" name="TourClaim[e_id]" value="<?=Yii::$app->utility->encryptString($model['employee_code'])?>" readonly="" />
    <input type="hidden" name="TourClaim[advance_required]" value="<?=Yii::$app->utility->encryptString($model['advance_required'])?>" readonly="" />
    <div class="col-sm-3">
        <label>Designation</label>
        <input type="text" class="form-control form-control-sm" value="<?=$model['desg_name']?>" readonly="" />
    </div>
    <div class="col-sm-3">
        <label>Project Name</label>
        <input type="text" class="form-control form-control-sm" value="<?=$model['project_name']?>" readonly="" />
    </div>
    <div class="col-sm-3">
        <label>Tour Type</label>
        <input type="text" class="form-control form-control-sm" value="<?=$model['tour_type']?>" readonly="" />
    </div>
    <div class="col-sm-3">
        <label>Tour Location</label>
        <input type="text" class="form-control form-control-sm" value="<?=$model['city_name']?>" readonly="" />
    </div>
</div>
<br>
<div class="row">
     <div class="col-sm-3">
        <label>Start Date</label>
        <input type="text" class="form-control form-control-sm" value="<?=$model['start_date']?>" readonly="" />
    </div>
    <div class="col-sm-3">
        <label>End Date</label>
        <input type="text" class="form-control form-control-sm" value="<?=$model['end_date']?>" readonly="" />
    </div>
    <div class="col-sm-6">
        <label>Purpose</label>
        <textarea class="form-control form-control-sm" readonly=""><?=$model['purpose']?></textarea>
    </div>
    <div class="col-sm-3">
        <label>Advance Amount (Rs.)</label>
        <input type="text" class="form-control form-control-sm" value="<?=$model['advance_amount']?>" readonly="" />
    </div>
    <?php if($model['advance_required'] == 'Y'){?>
    <div class="col-sm-3">
        <label>Sanctioned Amount (Rs.)</label>
        <input type="text" required="" onkeypress="return allowOnlyNumber(event)" maxlength="10" class="form-control form-control-sm" value="" name="TourClaim[sanctioned_amt]"/>
    </div>
    <?php } ?>
    <div class="col-sm-6">
        <br>
        <button type="submit" name="TourClaim[Submit]" value="1" class="btn btn-success btn-sm sl">Approved</button>
        <button type="submit" name="TourClaim[Submit]" value="2" class="btn btn-danger btn-sm sl">Reject</button>
        <button type="submit" name="TourClaim[Submit]" value="3" class="btn btn-outline-dark btn-sm sl">Revoke</button>
    </div>
</div>

<?php ActiveForm::end(); ?>