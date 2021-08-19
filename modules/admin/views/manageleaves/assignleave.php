<?php
$this->title = "Assign Leave";
use yii\widgets\ActiveForm;
$url =Yii::$app->homeUrl."admin/manageleaves/addnewentry?securekey=$menuid";
?>
<style>
    .col-sm-3{margin-bottom: 15px;}
</style>
<div class="text-right">
    <a href="<?=$url?>" class="btn btn-success btn-sm mybtn">Click here to Add Leave Type Entry</a>
</div>
<hr>
<input type="hidden" id='menuid' value='<?=$menuid?>' />
<?php $form = ActiveForm::begin(['options'=>['id'=>'assignleaveadmin']]); ?>
<div class="row">
    <div class="col-sm-3">
        <label>Session Year</label>
        <select class="form-control form-control-sm" id="assign_leave_year" name="AssignLeave[year]" required="">
            <option value=''>Select Year</option>
            <option value='<?=Yii::$app->utility->encryptString(date('Y'))?>'><?=date('Y')?></option>
            <?php 
//            $curYr = date('Y', strtotime('+1 year'));
//            $yrss = $curYr-3;
//            for($i=$curYr;$i>=$yrss;$i--){
//                $id = Yii::$app->utility->encryptString($i);
//                echo "<option value='$id'>$i</option>";
//            }
            ?>
        </select>
    </div>
    <div class="col-sm-3">
        <label>Session Type</label>
        <select class="form-control form-control-sm" id='sessiontype' name="AssignLeave[session_type]" required="">
            <option value=''>Select Session Type</option>
            <option value='<?=Yii::$app->utility->encryptString("Y")?>'>Yearly</option>
            <option value='<?=Yii::$app->utility->encryptString("FHY")?>'>First Half-Year</option>
            <option value='<?=Yii::$app->utility->encryptString("SHY")?>'>Second Half-Year</option>
        </select>
    </div>
    <div class="col-sm-3">
        <label>Select Employee Type</label>
        <select class="form-control form-control-sm assignemptype" id="assignemptype" name="AssignLeave[emp_type]" required="">
            <option value=''>Select Employee Type</option>
            <option value='R'>Regular</option>
            <option value='C'>Contractual</option>
        </select>
    </div>
    
    <div class="col-sm-3">
        <label>Select Leave Type</label>
        <select class="form-control form-control-sm leavetype" name="AssignLeave[leave_type]" required="">
            <option value=''>Select Leave Type</option>
        </select>
    </div>
    <div class="col-sm-3">
        <label>Leaves For</label>
        <input type='text' id='leave_for' class="form-control form-control-sm" readonly="" placeholder="Leaves For" />
        <input type='hidden' id='leave_for_enc' name="AssignLeave[leave_for]" readonly="" />
    </div>
    <div class="col-sm-3">
        <label>Carry Forward Leaves</label>
        <input type='text' id='carry_fwd' class="form-control form-control-sm" readonly="" placeholder="Carry Forward Leaves" />
        <input type='hidden' id='carry_fwd_enc' name="AssignLeave[carry_fwd]" readonly="" />
    </div>
    <div class="col-sm-3">
        <label>Can Encashment?</label>
        <input type='text' id='can_encash' class="form-control form-control-sm" readonly="" placeholder="Can Encashment?" />
        <input type='hidden' id='can_encash_enc' name="AssignLeave[can_encash]" readonly="" />
        <input type='hidden' id='leave_chart_id' name="AssignLeave[leave_chart_id]" readonly="" />
    </div>
    <div class="col-sm-3">
        <label>Total Leaves</label>
        <input type='text' id='leave_count' class="form-control form-control-sm" readonly="" placeholder="Total Leaves" />
        <input type='hidden' id='leave_count_enc' name="AssignLeave[leave_count]" readonly="" />
    </div>
    
    <div class="col-sm-3">
        <br>
        <!--<input type="radio" class="assignleaveto" checked="" name="assignleave" value="1" /> For All Employee<br>-->
        <!--<input type="radio" class="assignleaveto"  name="assignleave" value="2" /> For Particular Employee-->
        <input type="hidden" name="AssignLeave[assign_leave_to]" readonly="" id="assignleaveto" value="1"/>
    </div>
    <div class="col-sm-3" id="entremp" style="display:none;">
        <label>Employee Code</label>
        <input type='text' id='emp_code' name="AssignLeave[emp_code]" class="form-control form-control-sm" placeholder="Employee Code" />
    </div>
    <div class="col-sm-12">
        <div class="alert alert-danger"><b>Note :- </b> <br> (1) When you submit the details, all the leave will be assign to the employees. <br>(2) If session year change then balance leaves will carry forward.</div>
        <br>
        <div class="text-center">
            <button type="submit" class="btn btn-success btn-sm sl" id="submitassignleave">Submit</button>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>
