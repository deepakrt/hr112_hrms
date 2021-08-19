<?php
$this->title = "Add Employee Children Education Allowance ";
use yii\widgets\ActiveForm;
$form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);
$curFnYr = Yii::$app->finance->getCurrentFY();
$Emp_Allowances = Emp_Allowances;
$designations = Yii::$app->utility->get_designation(NULL);
//echo "<pre>";print_r($designations); 
?>
<div class="row">
    <div class="col-sm-4">
        <label>Financial Year</label>
        <select class="form-control form-control-sm" name="Allowance[financial_yr]" required="">
            <option value="">Select Financial Year</option>
            <option value="<?=Yii::$app->utility->encryptString($curFnYr)?>"><?=$curFnYr?></option>
        </select>
    </div>
    <div class="col-sm-4">
        <label>Select Designation</label>
        <select class="form-control form-control-sm" name="Allowance[designation_id]" required="">
            <option value="">Select Designation</option>
            <?php 
            if(!empty($designations)){
                foreach($designations as $d){
                    if($d['desg_id'] != '1'){
                        $desg_id = Yii::$app->utility->encryptString($d['desg_id']);
                        $desg_name = $d['desg_name'];
                        echo "<option value='$desg_id'>$desg_name</option>";
                    }
                    
                }
            }
            ?>
        </select>
    </div>
    <div class="col-sm-4">
        <label>Emp Type</label>
        <select class="form-control form-control-sm" name="Allowance[emp_type]" required="">
            <option value="">Select Emp Type</option>
            <option value="<?=Yii::$app->utility->encryptString('R')?>">Regular</option>
            <option value="<?=Yii::$app->utility->encryptString('C')?>">Contractual</option>
        </select>
    </div>
    <div class="col-sm-4">
        <label>Allowance Type</label>
        <select class="form-control form-control-sm" name="Allowance[allowance_type]" required="">
            <option value="">Select Allowance Type</option>
            <?php 
            foreach($Emp_Allowances as $a){
                if($a['is_active'] == 'Y'){
                    $a1 = Yii::$app->utility->encryptString($a['shortname']);
                    $name = $a['name'];
                    echo "<option value='$a1'>$name</option>";
                }
            }
            ?>
        </select>
    </div>
    <div class="col-sm-4">
        <label>Amount</label>
        <input type='number' class="form-control form-control-sm" name="Allowance[amount]" onkeypress="return allowOnlyNumber(event)" placeholder="Amount" maxlength="6" required=""  />
    </div>
    <div class="col-sm-4">
        <label>Sanction Type</label>
        <select class="form-control form-control-sm" name="Allowance[sanc_type]" required="">
            <option value="">Select Sanction Type</option>
            <option value="<?=Yii::$app->utility->encryptString('All')?>">For All Child</option>
            <option value="<?=Yii::$app->utility->encryptString('Each')?>">For Each Child</option>
        </select>
    </div>
    <div class="col-sm-12 text-center">
        <br>
        <button type='submit' class="btn btn-success btn-sm">Submit</button>
        <a href='' class="btn btn-danger btn-sm">Cancel</a>
    </div>
</div>
<?php ActiveForm::end(); ?>


