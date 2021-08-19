<?php 
$curdate=date("d-m-Y");
$DISPATCH= Yii::$app->utility->encryptString("DISPATCH");
$paraf_id="1";
$param_fileno=Yii::$app->Dakutility->efile_get_dakno("DISPATCH");
if(empty($param_fileno))
{
    $lastfileno=Yii::$app->Dakutility->getefileNo("DISPATCH");
    $lastfileno=$lastfileno.$paraf_id;
}
else 
{
    $param_fileno=$param_fileno["disp_number"];
    $fielno=explode("/",$param_fileno);
    foreach ($fielno as $key => $value) 
    {
        $lastno=   $value;
    }
    $lastno=$lastno+$paraf_id;
    $lastfileno=Yii::$app->Dakutility->getefileNo("DISPATCH");
    $lastfileno=$lastfileno.$lastno;
}
?>
<h5 class="text-danger"  style="text-align:center"><b>Dak Dispatch Detail</b></h5>
<form  id="dakdispatchtorm" method="POST" enctype="multipart/form-data">
    <div class="row">
	<div class="col-sm-6">
            <label>Dispatch Number</label>
            <input value="<?=$lastfileno?>" readonly="" type="text" class="form-control form-control-sm" id="daknodispatch" name="daknodispatch" required=""  placeholder="Dispatch Number"/>
        </div>
        <div class="col-sm-6 mb15">
            <label>Dispatch Date</label>
            <input value="<?=$curdate?> "readonly="" type="text" class="form-control form-control-sm dispdate" id="dispdate" name="dispdate" required=""  placeholder="Dispatch Date"/>
        </div>
    </div>
    <?php $i=1; ?>
    <input type="hidden" id="serial_number" value="<?=$i;?>"/>
    <div class="row" id="addressdiv<?=$i;?>">
        <div class="col-sm-6 mb15">
            <label>Dispatch To(Person Name/Designation)</label>
            <input type="text" class="form-control form-control-sm " id="disptchfor<?=$i;?>" name="disptchfor[]" required=""  placeholder="Dispatch To(Person Name/Designation)"/>
        </div>
        <div class='col-sm-6 mb15'>
            <label>Organization State</label>
            <select class='form-control form-control-sm' name='state_id_dis[]' id='state_id_dis<?=$i;?>' onchange="return getdispatchdist('<?=$i;?>')">
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
            <select class='form-control form-control-sm' name='district_id_dis[]' id='district_id_dis<?=$i;?>'>
                <option value=''>Select District</option>
                
            </select>	
        </div>
        <div class="col-sm-5 mb15">
            <label>Organization Address</label>
            <textarea id="disporgadd<?=$i;?>" name="disporgadd[]" class="form-control form-control-sm" placeholder="Organization Address"></textarea>
        </div>
        <div class="col-sm-1 mb15">
        <label></label>
        <button class="btn btn-sm" id='addmorediv' onclick="return addmorreaddress()"><img src="<?=Yii::$app->homeUrl?>images/details_open.png"/></button>
        </div>
    </div>
    <div id="appendhtmlforaddress"></div>
    <div class="row">
        <div class="col-sm-9 mb15">
        <label>Short Summary of Dak</label>
        <textarea id="dissummary" name="dissummary" class="form-control form-control-sm" placeholder="Short Summary of dispatch" required="" ></textarea>
        </div>
        <div class="col-sm-3 mb15" >
        <label>Select Mode of Dispatch</label>
        <select class="form-control form-control-sm" id="dipatch_mode" name="dipatch_mode" required="">
            <option value="">Select Mode of Dispatch</option>
            <option value="Speed Post">Speed Post</option>
            <option value="By Hand">By Hand</option>
            <option value="Email">Email</option>
        </select>
        </div>
        <input type="hidden" id="_csrf" name="_csrf" value="<?= Yii::$app->request->csrfToken; ?>" />
        <input type="hidden" id="daktype" name="daktype" value="<?= $DISPATCH; ?>" />
        
    </div>
<div class="row">
    <div class="col-sm-6 mb15">
        <label>Remarks (If any)</label>
        <textarea id="disremarks" name="disremarks" class="form-control form-control-sm" placeholder="Remarks"></textarea>
    </div>
    <div class="col-sm-6 mb15">
        <label>Attachment (If any)</label>
        <input onchange="return checkfilesizeofmultiple('dispatchfile')" type="file" id="dispatchfile" class="form-control form-control-sm" name="dispatchfile" accept=".jpeg,.jpg,.png,.pdf" />
        <span style="color: red;font-size: 12px;">File size cannot be more then 1 MB</span>
    </div>
    <div class='col-sm-12'><h6 class="text-danger"><b>Dak Dispatch From:</b></h6></div>
	<div class='col-sm-6'>
            <label>Select Department</label>
            <select required="" class="form-control form-control-sm" onchange='get_dept_emp_list("dept_emp_dropdown_disptach", "dept_emp_list_disptach")' name="dept_emp_dropdown_disptach" id="dept_emp_dropdown_disptach">
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
		<select required="" class='form-control form-control-sm' id='dept_emp_list_disptach' name="dept_emp_list_disptach">
			<option value=''>Select Employee</option>
		</select>
	</div>
        <div class="col-sm-12 text-center">
        <br>
        <input id="dakdispatchtormsave"  type="submit" class="btn btn-success btn-sm" value="Dispatch"/>
        <input type="reset"  class="btn btn-danger btn-sm" value="Reset">
    </div>
</div>
    <br>
</div>
</form>