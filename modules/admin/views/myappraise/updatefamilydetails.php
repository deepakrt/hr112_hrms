<?php
$this->title = "Update Family Details";
use yii\widgets\ActiveForm;
//echo "<pre>";print_r($memberInfo);
//            die("OK");
$form = ActiveForm::begin(["action"=> Yii::$app->homeUrl."admin/myappraise/updatefamilydetails?securekey=$menuid"]);
echo "<input type='hidden' name='Family[old_status]' value='".Yii::$app->utility->encryptString($memberInfo['status'])."' />";
echo "<input type='hidden' name='Family[employee_code]' value='".Yii::$app->utility->encryptString($memberInfo['employee_code'])."' />";
echo "<input type='hidden' name='Family[ef_id]' value='".Yii::$app->utility->encryptString($memberInfo['ef_id'])."' />";
?>
<style>
    .col-sm-4{ margin-bottom: 15px;}
</style>
<h6><b>Member Details</b></h6>
<div class="row">
    <div class="col-sm-4"><b>Member Name : </b><?=$memberInfo['m_name']?></div>
    <div class="col-sm-4"><b>Relation : </b><?=$memberInfo['relation_name']?></div>
    <div class="col-sm-4"><b>Marital Status : </b><?=$memberInfo['marital_status']?></div>
    <div class="col-sm-4"><b>Monthly Income : </b><?=$memberInfo['monthly_income']?></div>
    <div class="col-sm-4"><b>Address : </b><?php if(empty($memberInfo['address'])){ echo "-";}else{ echo $memberInfo['address'];}?></div>
    <div class="col-sm-4"><b>Permanent Address : </b><?php if(empty($memberInfo['p_address'])){ echo "-";}else{ echo $memberInfo['p_address'];}?></div>
    <div class="col-sm-4">
        <b>Is Handicap? : </b> 
            <?php if($memberInfo['handicap'] == 'Y'){ 
                echo "Yes"; 
                $handicate_type = $memberInfo['handicate_type'];
                $handicap_percentage = $memberInfo['handicap_percentage'];
            }else{ 
                echo "No"; 
                $handicap_percentage = $handicate_type = "-";
                
            }?>
    </div>
    <div class="col-sm-4"><b>Handicap Type : </b><?=$handicate_type?></div>
    <div class="col-sm-4"><b>Handicap %age : </b><?=$handicap_percentage?></div>
    <div class="col-sm-4">
        <label>Is Eligible for Medical Benefits?</label>
        <select class="form-control form-control-sm" name="Family[medical_benefit]" required="">
            <option value="">Select</option>
            <option value="<?=Yii::$app->utility->encryptString("Y")?>">Yes</option>
            <option value="<?=Yii::$app->utility->encryptString("N")?>">No</option>
        </select>
    </div>
    <div class="col-sm-4">
        <label>Is Eligible for Edu. Allowances</label>
        <select class="form-control form-control-sm" name="Family[edu_allowances]" required="">
            <option value="">Select</option>
            <option value="<?=Yii::$app->utility->encryptString("Y")?>">Yes</option>
            <option value="<?=Yii::$app->utility->encryptString("N")?>">No</option>
        </select>
    </div>
    <?php 
    if($memberInfo['relation_id'] == '7' OR $memberInfo['relation_id'] == '2'){ ?>
    <div class="col-sm-4">
        <label>Is Children Twins? </label>
        <select class="form-control form-control-sm" name="Family[is_child_twins]" required="">
            <option value="">Select</option>
            <option value="<?=Yii::$app->utility->encryptString("Y")?>">Yes</option>
            <option value="<?=Yii::$app->utility->encryptString("N")?>">No</option>
        </select>
    </div>
    <?php }else{
        echo "<input type='hidden' name='Family[is_child_twins]' value='".Yii::$app->utility->encryptString("N")."' />";
        
    }
    ?>
    <div class="col-sm-4">
        <label>Select Action</label>
        <select class="form-control form-control-sm" name="Family[status]" required="">
            <option value="">Select</option>
            <option value="<?=Yii::$app->utility->encryptString("Verified")?>">Verified</option>
            <option value="<?=Yii::$app->utility->encryptString("Rejected")?>">Rejected</option>
        </select>
    </div>
    <div class="col-sm-4">
        <br>
        <input type='submit' class="btn btn-success btn-sm" value='Submit' />
    </div>
</div>


<?php ActiveForm::end(); ?>

