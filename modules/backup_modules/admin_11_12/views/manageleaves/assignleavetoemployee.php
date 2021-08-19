<?php
$this->title="Assign Leave To Employee";
use yii\widgets\ActiveForm;
//echo "<pre>";print_r($leaves);
?>
<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered">
            <tr>
                <td><b>Name : </b><br><?=$empInfo['fullname']?></td>
                <td><b>Designation : </b><br><?=$empInfo['desg_name']?></td>
                <td><b>Department : </b><br><?=$empInfo['dept_name']?></td>
            </tr>
            <tr>
                <td><b>Joining Date : </b><br><?=date('d-m-Y', strtotime($empInfo['joining_date']))?></td>
                <td><b>Employee Type : </b><br><?=$empInfo['employment_type']?></td>
                <td><b>Contact No. : </b><br><?=$empInfo['phone']?></td>
            </tr>
        </table>
    </div>
</div>
<hr>
<?php $form = ActiveForm::begin(); ?>
<input type="hidden" id="menuid" value="<?=$menuid?>" />
<div class="row">
    <div class="col-sm-3">
        <label>Select Leave</label>
        <select class="form-control form-control-sm" name="AssignLeave[leave_type]" id="assignleavetoemp" required="">
            <option value="">Select Leave</option>
            <?php 
            foreach($leaves as $leave){
                $leave_type = Yii::$app->utility->encryptString($leave['leave_type']);
                $lc_id = Yii::$app->utility->encryptString($leave['lc_id']);
                $desc = $leave['desc'];
                echo "<option value='$leave_type' data-key='$lc_id'>$desc</option>";
            }
            ?>
        </select>
    </div>
    <div class="col-sm-3">
        <label>Session Year</label>
        <input type="text" class="form-control form-control-sm" id="session_year" readonly="" placeholder="Session Year" />
        <input type="hidden" readonly="" id="session_year_enc" name="AssignLeave[session_year]" />
        <input type="hidden" readonly="" name="AssignLeave[emp_code]" value="<?=Yii::$app->utility->encryptString($empInfo['employee_code'])?>" />
    </div>
    <div class="col-sm-3">
        <label>Session Type</label>
        <input type="text" class="form-control form-control-sm" id="session_type" readonly="" placeholder="Session Type" />
        <input type="hidden" readonly="" id="session_type_enc" name="AssignLeave[session_type]" />
    </div>
    <div class="col-sm-3">
        <label>Total Leaves</label>
        <input type="text" class="form-control form-control-sm" id="leave_count" readonly="" placeholder="Total Leaves" />
        <input type="hidden" readonly="" id="leave_count_enc" name="AssignLeave[leave_count]" />
        <input type="hidden" readonly="" id="lc_id" name="AssignLeave[lc_id]" />
    </div>
    <div class="col-sm-12 text-center">
        <br>
        <button type="submit" class="btn btn-success btn-sm sl">Submit</button>
        <a href="" class="btn btn-danger btn-sm">Cancel</a>
    </div>
    
</div>
<?php ActiveForm::end()?>