<?php
$this->title="Mark Attandence";
use yii\widgets\ActiveForm;
?>
<style>
    #emp_atte_info{
/*        height: 300px;
        overflow: auto;*/
        margin-top: 20px;
    }
</style>
<?php ActiveForm::begin(); ?>
<input type='hidden' id='menuid' value='<?=$menuid?>' />
<div class="row">
    <div class="offset-2 col-sm-2">
        <label>Select Date</label>
    </div>
    <div class="col-sm-3">
        <input type="text" class="form-control form-control-sm" placeholder="Select Date" id="attendence_date" name="attendence_date" readonly="" />
    </div>
    <div class="col-sm-12">
        <hr>
        <div id="emp_atte_info"></div>
        <div id="emp_atte_info_btn"></div>
    </div>
</div>
<?php ActiveForm::end(); ?>

