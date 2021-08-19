<?php
$this->title= 'Add New Leave Entry';
use yii\widgets\ActiveForm;
$masterLeaveTypes = Yii::$app->hr_utility->hr_get_master_leave_type(NULL);
//echo "<pre>"; print_r($masterLeaveTypes);
?>
<style>
    .col-sm-4{margin-bottom: 15px;}
</style>
<?php $form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-sm-4">
        <label>Year</label>
<!--        <input type='text' class="form-control form-control-sm" value="<?=date('Y')?>" readonly="" />
        <input type='hidden' class="form-control form-control-sm" name="LeaveChart[year]" required="" value="<?=Yii::$app->utility->encryptString(date('Y'))?>" readonly=""/>-->
        <select class="form-control form-control-sm" name="LeaveChart[year]" required="">
            <option value=''>Select Year</option>
            <?php 
            $cYr = date('Y');
            $Y = Yii::$app->utility->encryptString($cYr);
            echo "<option value='$Y'>$cYr</option>";
//            $curYr = date('Y', strtotime('+1 year'));            
//            $yrss = $curYr-3;
//            for($i=$curYr;$i>=$yrss;$i--){
//                $id = Yii::$app->utility->encryptString($i);
//                echo "<option value='$id'>$i</option>";
//            }
            ?>
        </select>
    </div>
    <div class="col-sm-4">
        <label>Session Type</label>
        <select class="form-control form-control-sm" name="LeaveChart[session_type]" required="">
            <option value=''>Select Session Type</option>
            <option value='<?=Yii::$app->utility->encryptString("Y")?>'>Yearly</option>
            <option value='<?=Yii::$app->utility->encryptString("FHY")?>'>First Half-Year</option>
            <option value='<?=Yii::$app->utility->encryptString("SHY")?>'>Second Half-Year</option>
        </select>
    </div>
    <div class="col-sm-4">
        <label>Leave Type</label>
        <select class="form-control form-control-sm" name="LeaveChart[leave_type]" required="">
            <option value=''>Select Leave Type</option>
            <?php 
            if(!empty($masterLeaveTypes)){
                foreach($masterLeaveTypes as $type){
                    if($type['is_active'] == 'Y'){
                        $lt_id = Yii::$app->utility->encryptString($type['lt_id']);
                        $name = $type['desc']." (".$type['label'].")";
                        echo "<option value='$lt_id'>$name</option>";
                    }
                }
            }
            ?>
        </select>
    </div>
    <div class="col-sm-4">
        <label>Employee Type</label>
        <select class="form-control form-control-sm" name="LeaveChart[emp_type]" required="">
            <option value=''>Select Employee Type</option>
            <option value='<?=Yii::$app->utility->encryptString("R")?>'>Regular</option>
            <option value='<?=Yii::$app->utility->encryptString("C")?>'>Contractual</option>
        </select>
    </div>
    <div class="col-sm-4">
        <label>Leave For</label>
        <select class="form-control form-control-sm" name="LeaveChart[leave_for]" required="">
            <option value='<?=Yii::$app->utility->encryptString("A")?>'>All</option>
            <option value='<?=Yii::$app->utility->encryptString("M")?>'>Male</option>
            <option value='<?=Yii::$app->utility->encryptString("F")?>'>Female</option>
        </select>
    </div>
    <div class="col-sm-4">
        <label>Can Apply For Half Day</label>
        <select class="form-control form-control-sm" name="LeaveChart[can_apply_half_day]" required="">
            <option value=''>Select</option>
            <option value='<?=Yii::$app->utility->encryptString("N")?>'>No</option>
            <option value='<?=Yii::$app->utility->encryptString("Y")?>'>Yes</option>
        </select>
    </div>
    <div class="col-sm-4">
        <label>Total Leaves Assign</label>
        <input type='number' class="form-control form-control-sm" name="LeaveChart[leave_count]" required="" placeholder="Total Leaves" min="0" />
    </div>
    <div class="col-sm-4">
        <label>Carry Forward From Balance Leaves</label>
        <input type='number' class="form-control form-control-sm" name="LeaveChart[carry_fwd]" required="" placeholder="Carry Forward Leaves" min="0" />
    </div>
    <div class="col-sm-4">
        <label>Can Encashment of Balance Leaves</label>
        <select class="form-control form-control-sm" name="LeaveChart[can_encashment]" required="">
            <option value=''>Select</option>
            <option value='<?=Yii::$app->utility->encryptString("N")?>'>No</option>
            <option value='<?=Yii::$app->utility->encryptString("Y")?>'>Yes</option>
        </select>
    </div>
    
    <div class="col-sm-12 text-center">
        <br>
        <input type="submit" class="btn btn-success btn-sm sl" value="Submit" />
        <a href='<?=Yii::$app->homeUrl?>admin/manageleaves?securekey=<?=$menuid?>' class="btn btn-danger btn-sm">Back</a>
    </div>
</div>
<?php ActiveForm::end(); ?>
