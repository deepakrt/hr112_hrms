<?php
$this->title= 'View Service Details';
use yii\widgets\ActiveForm;
$sla = $fla = $sla1 = $fla1 = "";
$sla_emp_list = $fla_emp_list = array();
if(!empty($info['authority1'])){
    $fla = Yii::$app->utility->get_employees($info['authority1']);
    $fla1 = "$fla[name_hindi] / $fla[fullname], $fla[desg_name]";
    $fla_emp_list = Yii::$app->utility->get_dept_emp($fla['dept_id']);
//    echo "<pre>";print_r($fla_emp_list);
}
if(!empty($info['authority2'])){
    $sla = Yii::$app->utility->get_employees($info['authority2']);
    $sla1 = "$sla[name_hindi] / $sla[fullname], $sla[desg_name]";
    $sla_emp_list = Yii::$app->utility->get_dept_emp($sla['dept_id']);
}
$ser_id = Yii::$app->utility->encryptString($servicedetail[0]['ser_id']);

//    echo "$fla1<pre>";print_r($servicedetail[0]['ser_id']);die;

?>
<b>Personal Information</b>
<table class="table table-bordered">
    <tr>
        <td>Employee ID</td>
        <td><?=$info['employee_code']?></td>
    </tr>
    <tr>
        <td>Name</td>
        <td><?=$info['fullname']?></td>
    </tr>
    <tr>
        <td>Designation</td>
        <td><?=$info['desg_name']?></td>
    </tr>
    <tr>
        <td>Department</td>
        <td><?=$info['dept_name']?></td>
    </tr>
    <tr>
        <td>FLA</td>
        <td><?=$fla1?></td>
    </tr>
    <tr>
        <td>HOD</td>
        <td><?=$sla1?></td>
    </tr>
</table>
<div id="updateservice">
<h6><b>Update Service</b></h6>
<?php ActiveForm::begin();
$depts = Yii::$app->utility->get_dept(null);
$desgs = Yii::$app->utility->get_designation(null);

//echo "<pre>";print_r($desgs);
?>
<input type="hidden" name="ser_id" value="<?=$ser_id?>" readonly="" />
<div class="row">
    <div class="col-sm-4">
        <label><span class="hindishow12">पद</span> / Designation</label>
        <select class="form-control form-control-sm" name="designation_id" required="">
            <option value="">Select Designation</option>
            <?php 
            if(!empty($desgs)){
                foreach($desgs as $d){
                    $selected = "";
                    if($d['desg_id'] == $info['desg_id']){
                        $selected = "selected=''";
                    }
                    echo "<option $selected value='$d[desg_id]'>$d[desg_name_hindi] / $d[desg_name]</option>";
                }
            }
            ?>
        </select>
    </div>
    <div class="col-sm-4">
        <label><span class="hindishow12">विभाग</span> / Department</label>
        <select class="form-control form-control-sm" name="dept_id" required="">
            <option value="">Select Department</option>
            <?php 
            if(!empty($depts)){
                foreach($depts as $d){
                    $selected = "";
                    if($d['dept_id'] == $info['dept_id']){
                        $selected = "selected=''";
                    }
                    echo "<option $selected value='$d[dept_id]'>$d[dept_name]</option>";
                }
            }
            ?>
        </select>
    </div>
    <div class="col-sm-4"></div>
    <div class="col-sm-6">
        <div class="row">
            <div class="col-sm-12 text-center"><br><b><u>FLA</u></b></div>
            <div class='col-sm-5'>
                <label><span class="hindishow12">विभाग / </span> Department</label>
		<select class="form-control form-control-sm" onchange="get_dept_emp_list('service_dept_id', 'service_emp_code')" id="service_dept_id" name='fla_dept_id' required="">
                    <option value="">Select Department</option>
                    <?php 
                    if(!empty($depts)){
                        foreach($depts as $d){
                            $selected = "";
                            if(!empty($fla)){
                                if($d['dept_id'] == $fla['dept_id']){
                                    $selected = "selected=''";
                                }
                            }
                            $dept_id = Yii::$app->utility->encryptString($d['dept_id']);
                            $dept_name = $d['dept_name'];
                            echo "<option $selected value='$dept_id'>$dept_name</option>";
                        }
                    }
                    ?>
		</select>
            </div>
            <div class='col-sm-7'>
                <label><span class="hindishow12">कर्मचारी / </span> Employee</label>
                <select class="form-control form-control-sm" id='service_emp_code' name='fla_emp_code' required="">
                    <option value="">Select Employee</option>
                    <?php
                    if(!empty($fla_emp_list)){
                        foreach($fla_emp_list as $f){
                            $selected='';
                            $emp = base64_decode($f['employee_code']);
                            
                            if($emp == $info['authority1']){
                                $selected="selected=''";
                                $fla12 = $fla1;
                            }else{
                                $fla12 = $f['name'];
                            }
                            $emp = Yii::$app->utility->encryptString($emp);
                            echo "<option $selected value='$emp'>$fla12</option>";
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="row">
            <div class="col-sm-12 text-center"><br><b><u>SLA</u></b></div>
            <div class='col-sm-5'>
                <label><span class="hindishow12">विभाग / </span> Department</label>
		<select class="form-control form-control-sm" onchange="get_dept_emp_list('service1_dept_id', 'service1_emp_code')" id="service1_dept_id" name='sla_dept_id' required="">
                    <option value="">Select Department</option>
                    <?php 
                    if(!empty($depts)){
                        foreach($depts as $d){
                            $selected = "";
                            if(!empty($sla)){
                                if($d['dept_id'] == $sla['dept_id']){
                                    $selected = "selected=''";
                                }
                            }
                            $dept_id = Yii::$app->utility->encryptString($d['dept_id']);
                            $dept_name = $d['dept_name'];
                            echo "<option $selected value='$dept_id'>$dept_name</option>";
                        }
                    }
                    ?>
		</select>
            </div>
            <div class='col-sm-7'>
                <label><span class="hindishow12">कर्मचारी / </span> Employee</label>
                <select class="form-control form-control-sm" id='service1_emp_code' name='sla_emp_code' required="" >
                    <option value="">Select Employee</option>
                    <?php
                    if(!empty($sla_emp_list)){
                        foreach($sla_emp_list as $f){
                            $selected='';
                            $emp = base64_decode($f['employee_code']);
                            if($emp == $info['authority2']){
                                $selected="selected=''";
                                $sla12 = $sla1;
                            }else{
                                $sla12 = $f['name'];
                            }
                            $emp = Yii::$app->utility->encryptString($emp);
                            echo "<option $selected value='$emp'>$sla12</option>";
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="col-sm-12 text-center">
        <button type="submit" class="btn btn-success btn-sm">Update</button>
        <a href="<?=Yii::$app->homeUrl?>admin/manageservicedetail?securekey=<?=$menuid?>" class="btn btn-danger btn-sm">Back</a>
    </div>
</div>
<?php ActiveForm::end(); ?>
</div>