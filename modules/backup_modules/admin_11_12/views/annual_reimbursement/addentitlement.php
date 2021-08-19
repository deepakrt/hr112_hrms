<?php
$this->title = "Add Entitlement";
use yii\widgets\ActiveForm;
$degns = Yii::$app->utility->get_designation(NULL);
$curFnYr = Yii::$app->finance->getCurrentFY();
$reimtype = Yii::$app->finance->get_reim_type();
?>

<?php ActiveForm::begin()?>
<div class="row">
    <div class="col-sm-4">
        <label>Financial Year</label>
        <select class="form-control form-control-sm" name="Reim[financial_yr]" required="">
            <option value="">Select Financial Year</option>
            <option value="<?=Yii::$app->utility->encryptString($curFnYr)?>"><?=$curFnYr?></option>
        </select>
    </div>
    <div class="col-sm-4">
        <label>Reim. Type</label>
        <select class="form-control form-control-sm" name="Reim[reim_type_id]" required="">
            <option value="">Select Reim. Type</option>
            <?php 
            if(!empty($reimtype)){
                foreach($reimtype as $d){
                    $reim_type_id = Yii::$app->utility->encryptString($d['reim_type_id']);
                    $name = $d['name'];
                    echo "<option value='$reim_type_id'>$name</option>";
                    
                }
            }
            ?>
        </select>
    </div>
    <div class="col-sm-4">
        <label>Designation</label>
        <select name="Reim[designation_id]" class="form-control form-control-sm" required="">
            <option value="">Select Designation</option>
            <?php 
            if(!empty($degns)){
                foreach($degns as $d){
                    if($d['desg_id'] !='1'){
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
        <label>Emp. Type</label>
        <select name="Reim[emp_type]" class="form-control form-control-sm" required="">
            <option value="">Select Emp. Type</option>
            <option value="<?=Yii::$app->utility->encryptString('R')?>">Regular</option>
            <option value="<?=Yii::$app->utility->encryptString('C')?>">Contractual</option>
        </select>
    </div>
    <div class="col-sm-4">
        <label>Amount</label>
        <input type="number" name="Reim[sanc_amt]" class="form-control form-control-sm" required=""placeholder="Amount"/>
    </div>
    <div class="col-sm-12 text-center">
        <br>
        <input type="submit" class="btn btn-success btn-sm sl" value="Submit"  />
        <a href="<?=Yii::$app->homeUrl?>admin/annual_reimbursement?securekey=<?=$menuid?>" class="btn btn-danger btn-sm">Back</a>
    </div>
</div>
<?php ActiveForm::end()?>