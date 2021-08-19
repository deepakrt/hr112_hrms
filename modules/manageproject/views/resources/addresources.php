<?php
$this->title = "Add Project Resources";
$depts = Yii::$app->utility->get_dept(NULL);
$proDept = Yii::$app->utility->get_dept($proInfo['manager_dept']);

use yii\widgets\ActiveForm;
ActiveForm::begin();
?>
<div class="row">
    <div class="col-sm-12">
        <h5>Project Information:-</h5>
        <table class="table table-bordered">
            <tr>
                <td><label>Project Name : </label> <?=$proInfo['project_name']?></td>
                <td><label>Project Type : </label> <?=$proInfo['project_type']?></td>
                <td><label>Project Cost : </label> Rs. <?=$proInfo['project_cost']?></td>
            </tr>
            <tr>
                <td><label>State Date : </label> <?=date('d-m-Y', strtotime($proInfo['start_date']))?></td>
                <td><label>End Date : </label> <?=date('d-m-Y', strtotime($proInfo['end_date']))?></td>
                <td><label>No. of Working Days : </label> <?=$proInfo['num_working_days']?></td>
            </tr>
            <tr>
                <td><label>Technology : </label> <?=$proInfo['technology_used']?></td>
                <td><label>Department : </label> <?=$proDept['dept_name']?></td>
                <td></td>
            </tr>
        </table>
    </div>
    
</div>
<div class="row">
    <div class="col-sm-12">
        <h5>Select Members:-</h5>
    </div>
    <div class="col-sm-3">
        <label>Select Role</label>
        <select class="form-control form-control-sm" id="role_id" name="R[role_id]" required="">
            <option value="">Select Role</option>
            <option value="<?=Yii::$app->utility->encryptString("13")?>">Project Manager</option>
            <option value="<?=Yii::$app->utility->encryptString("14")?>">Team Leader</option>
            <option value="<?=Yii::$app->utility->encryptString("15")?>">Team Member</option>
        </select>
    </div>
<!--    <div class="col-sm-3">
        <label>Select Department</label>
        <select class="form-control form-control-sm" id="role_id" name="R[dept_id]" required="">
            <option value="">Select Department</option>
            <?php 
            if(!empty($depts)){
                foreach($depts as $d){
//                    $selected="";
//                    if($proInfo['manager_dept'] == $d['dept_id']){
//                        $selected="selected='selected'";
//                    }
//                    $id = Yii::$app->utility->encryptString($d['dept_id']);
//                    $name = $d['dept_name'];
//                    echo "<option $selected value='$id'>$name</option>";
                }
            }
            ?>
        </select>
    </div>-->
    <div class="col-sm-7">
        <label>Select Member <span style="font-size: 11px; color:red;">You can select multiple members</span></label>
        <select class="form-control form-control-sm" id="role_id" name="R[dept_mem_id][]" required="" multiple="">
            <?php 
            $deptsmem = Yii::$app->utility->get_dept_emp($proInfo['manager_dept']);
            if(!empty($deptsmem)){
                foreach($deptsmem as $d){
                    $id = base64_decode($d['employee_code']);
                    $id1 = Yii::$app->utility->encryptString($id); 
                    
                    $name = $d['name'];
                    echo "<option value='$id1'>$name ($id)</option>";
                }
            }
            ?>
        </select>
    </div>
    <div class="col-sm-12">
        <label>Responsibility</label>
        <textarea class="form-control form-control-sm" id="responsibility" name="R[responsibility]" placeholder="Responsibility" required=""></textarea>
        <input type="hidden" name="R[project_id]" value="<?=Yii::$app->utility->encryptString($proInfo['project_id']); ?>" />
    </div>
    <div class="col-sm-9 text-center">
        <br>
        <button type="submit" class="btn btn-success btn-sm">Submit</button>
        <a href="" class="btn btn-danger btn-sm">Cancel</a>
    </div>
    
</div>
<?php ActiveForm::end(); ?>