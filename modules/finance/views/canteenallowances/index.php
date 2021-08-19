<?php
use yii\widgets\ActiveForm;
$this->title = 'Canteen Allowances';
?>
<style>
  .ui-datepicker-calendar { display: none; }
</style>
<input type="hidden" id='menuid' value='<?=$menuid?>' readonly="" />
<?php ActiveForm::begin(['id'=>'salary_form']); ?>
<div class='row'>
    <div class='col-sm-3'>
        <label>Employee Type</label>
        <select class='form-control form-control-sm' id='GS_emp_type' name='GS[emp_type]' required=''>
            <option value=''>Select Emp Type</option>
            <option value='<?=Yii::$app->utility->encryptString('R')?>'>Regular</option>
            <option value='<?=Yii::$app->utility->encryptString('C')?>'>Contractual</option>
            <option value='<?=Yii::$app->utility->encryptString('A')?>'>All Employees</option>
        </select>
    </div>
    <div class='col-sm-3'>
        <label>Select Month</label>
        <select class='form-control form-control-sm' id='GS_month' name='GS[month]' required=''>
            <option value=''>Select Month</option>
            <?php 
            for($m=1; $m<=12; ++$m){
                $mmm = date('F', mktime(0, 0, 0, $m, 1)).'<br>';
                $em = Yii::$app->utility->encryptString($m);
                echo "<option value='$em'>$mmm</option>";
            }
            ?>
        </select>
    </div>
    <div class='col-sm-3'>
        <label>Select Year</label>
        <select class='form-control form-control-sm' id='GS_year' name='GS[year]' required=''>
            <option value=''>Select Year</option>
            <option value='<?=Yii::$app->utility->encryptString(date('Y',strtotime('-1 year')))?>'><?=date("Y",strtotime("-1 year"));?></option>
            <option value='<?=Yii::$app->utility->encryptString(date('Y'))?>'><?=date('Y')?></option>
        </select>
    </div>
</div>
<div id='canteen_allowances'></div>
<?php ActiveForm::end(); ?>
