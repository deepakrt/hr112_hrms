<?php 
$curdate=date("d-m-Y");
$lastfileno="";
$DAKREC= Yii::$app->utility->encryptString("DAKREC");
$paraf_id="1";
$param_fileno=Yii::$app->Dakutility->efile_get_dakno(NULL);
if(empty($param_fileno))
{
    $lastfileno=Yii::$app->Dakutility->getefileNo("DAKREC");
    $lastfileno=$lastfileno.$paraf_id;
}
else 
{
    $param_fileno=$param_fileno["dak_number"];
    $fielno=explode("/",$param_fileno);
    foreach ($fielno as $key => $value) 
    {
        $lastno=   $value;
    }
    $lastno=$lastno+$paraf_id;
    $lastfileno=Yii::$app->Dakutility->getefileNo("DAKREC");
    $lastfileno=$lastfileno.$lastno;
}
?>
<h5 class="text-danger"  style="text-align:center"><b>Dak Receipt Detail</b></h5>

<form  id="dakreceiptorm" method="POST" enctype="multipart/form-data">
    <div class="row">
        <input type="hidden" id="daktype" name="daktype" value="<?= $DAKREC; ?>" />
	<div class="col-sm-6 mb15" >
            <label class="required">Select Mode of Received</label>
        <select class="form-control form-control-sm" id="rece_mode" name="rece_mode" required="">
            <option value="">Select Mode of Received</option>
            <option value="Speed Post">Speed Post</option>
            <option value="By Hand">By Hand</option>
            <option value="Email">Email</option>
        </select>
        </div>
	<div class="col-sm-6 mb15"></div>
	<div class="col-sm-6 mb15">
            <label>Receipt Number</label>
            <input readonly="" value="<?=$lastfileno?>" type="text" class="form-control form-control-sm" id="dakno" name="dakno" placeholder="Receipt Number" required="" />
	</div>
	<div class="col-sm-6 mb15">
            <label>Receipt Date</label>
            <input value="<?=$curdate?>" readonly="" type="text" class="form-control form-control-sm dakrecpdate" id="receiptdate" name="receiptdate" placeholder="Receipt Date" required="" />
	</div>
	<div class="col-sm-6 mb15">
            <label>Recieved From(Person Name/Designation)</label>
            <input type="text" class="form-control form-control-sm" id="receiptfrom" required=""  name="receiptfrom" placeholder="Receipt From(Person Name/Designation)"/>
	</div>
	<div class='col-sm-6 mb15'>
            <label>Organization State</label>
            <select class='form-control form-control-sm' name='state_id_rec' id='state_id_rec' required="">
                    <option value=''>Select State</option>
                        <?php 
                        foreach($states as $c)
                        {
                            $state_id = Yii::$app->utility->encryptString($c['state_id']);
                            $selected = "";
                            $state_name = ucwords($c['state_name']);
                            echo "<option value='$state_id' $selected>$state_name</option>";
                        }
                        ?>
                </select>
        </div>
        <div class='col-sm-6 mb15'>
            <label>Organization District</label>
            <select class='form-control form-control-sm' name='district_id_rec' id='district_id_rec' required="">
                <option value=''>Select District</option>
                
            </select>	
        </div>
	<div class="col-sm-6 mb15">
            <label>Organization Address</label>
            <textarea required="" id="orgname" name="orgname" class="form-control form-control-sm" placeholder="Organization Address"></textarea>
            <!--<input type="text" class="form-control form-control-sm" id="orgname" name="orgname" placeholder="Organization Address" required="" /> -->
	</div>
	
    <div class="col-sm-12 mb15">
        <label>Short Summary of Dak</label>
        <textarea id="recsummary" name="recsummary" class="form-control form-control-sm" placeholder="Short Summary of Receipt " required="" ></textarea>
    </div>
    
</div>
<div class="row"> 

    <div class="col-sm-6 mb15">
        <label>Remarks (If any)</label>
        <textarea id="recremarks" name="recremarks" class="form-control form-control-sm" placeholder="Remarks"></textarea>
    </div>
    <div class="col-sm-6 mb15">
        <label>Attachment (If any)</label>
        <input id="recnotefile" onchange="return checkfilesizeofmultiple('recnotefile')" type="file" class="form-control form-control-sm" name="recnotefile" accept=".jpeg,.jpg,.png,.pdf" />
        <span style="color: red;font-size: 12px;">File size cannot be more then 1 MB</span>
    </div>
    <div class='col-sm-12'><h6 class="text-danger"><b>Dak Forward To:</b></h6></div>
	<div class='col-sm-6'>
            <label>Select Department</label>
            <select required="" class="form-control form-control-sm" onchange='get_dept_emp_list("dept_emp_dropdown", "dept_emp_list_dropdown")' id="dept_emp_dropdown" name="dept_emp_dropdown">
                <option value="">Select Department</option>
                <?php 
                $allDepts= Yii::$app->utility->get_dept(NULL);
                if(!empty($allDepts))
                {
                    foreach($allDepts as $d)
                    {
                        $dept_id = $d['dept_id'];
                        $dept_name = $d['dept_name'];
                        echo "<option value='$dept_id'>$dept_name</option>";
                    }
                }
                ?>
            </select>
	</div>
	<div class='col-sm-6'>
            <label>Select Employee</label>
            <select required="" class='form-control form-control-sm' id='dept_emp_list_dropdown' name="dept_emp_list_dropdown">
                <option value=''>Select Employee</option>
            </select>
	</div>
    <div class="col-sm-12 text-center mb15">
        <br>
        <button id="dakreceiptormsave"  class="btn btn-success btn-sm">Forward</button>
        <!--<input id="dakreceiptormsave" type="button" class="btn btn-success btn-sm sl" value="Forward"/>-->
        <input type="reset"  class="btn btn-danger btn-sm" value="Reset">
    </div>
</div>
</form>