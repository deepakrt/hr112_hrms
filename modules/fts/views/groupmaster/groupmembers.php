<?php
$this->title = 'Group Members';
use yii\widgets\ActiveForm;
//$groupList = Yii::$app->fts_utility->fts_getgroupmaster(NULL);
$dptList = Yii::$app->utility->get_dept(NULL);
?>
<input type="hidden" id="menuid" value="<?=$menuid?>" />
<?php $form = ActiveForm::begin();
$groupid = Yii::$app->utility->encryptString($group_id);
?> 
<input type="hidden" id="group_id" name="GroupMember[group_id]" value='<?=$groupid?>'  />
<div class="row">
    <div class="col-sm-12">
        <h6><b><u>Add Members in Group</u></b></h6>
    </div>
<!--    <div class="col-sm-3">
        <label>Select Group</label>
        
        <select name="GroupMember[group_id]" id="group_id" class="form-control form-control-sm" required="" >
            <option value="">Select Group</option>
            <?php
//            if(!empty($groupList)){
//                foreach($groupList as $gr){
//                    $group_id = Yii::$app->utility->encryptString($gr['group_id']);
//                    $group_name = $gr['group_name'];
//                    echo "<option value='$group_id'>$group_name</option>";
//                }
//            }
            ?>
        </select>
    </div>-->
    <div class="col-sm-4">
        <label>Select Department</label>
        <select name="GroupMember[dept_id]" id="get_groups_emp" class="form-control form-control-sm" required="" >
            <option value="">Select Dept</option>
            <?php
            if(!empty($dptList)){
                foreach($dptList as $d){
                    $dept_id = Yii::$app->utility->encryptString($d['dept_id']);
                    $dept_name = $d['dept_name'];
                    echo "<option value='$dept_id'>$dept_name</option>";
                }
            }
            ?>
        </select>
    </div>
    <div class="col-sm-3">
        <br>
        <input type="submit" class="btn btn-success btn-sm sl" value="Submit" />
    </div>
    <div class="col-sm-12">
        <br>
        <label>Select Employee</label><br>
        <ul id="group_emp_list"><li>Select Department First</li></ul>
    </div>
    
</div>
<?php ActiveForm::end();
$members = Yii::$app->fts_utility->fts_get_group_members($group_id);
if(!empty($members)){
    echo "<h6><b><u>Members in Group</u></b></h6>";
    echo "<table class='table table-bordered table-hover'>
        <tr>
            <th>Sr. No.</th>
            <th>Employee Code</th>
            <th>Employee Name</th>
            <th>Designation</th>
            <th>Department</th>
        </tr>
    ";
    $i=1;
    foreach($members as $m){
        $employee_code = $m['employee_code'];
        $emp_name = $m['emp_name'];
        $desg_name = $m['desg_name'];
        $dept_name = $m['dept_name'];
        echo "<tr>
            <td>$i</td>
            <td>$employee_code</td>
            <td>$emp_name</td>
            <td>$desg_name</td>
            <td>$dept_name</td>
        </tr>";
        $i++;
    }
    echo "</table>";
}
?> 

