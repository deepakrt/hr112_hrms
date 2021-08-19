<?php 
$curdate=date("d-m-Y");
$DISPATCH= Yii::$app->utility->encryptString("DISPATCH");
$paraf_id="1519";
$param_fileno=Yii::$app->Dakutility->efile_get_dakno("Dispatch");
//echo "<pre>";print_r($param_fileno);
if(empty($param_fileno))
{
    $lastfileno=Yii::$app->Dakutility->getefileNo("Dispatch");
    $lastfileno=$lastfileno.$paraf_id;
}
else 
{
    $param_fileno=$param_fileno["disp_number"];
    $fielno=explode("/",$param_fileno);
//    echo "<pre>";print_r($fielno[4]);
//    foreach ($fielno as $key => $value) 
//    {
//        $lastno=   $value;
//    }
    $lastno=$fielno[4]+1;
    
    
    $lastfileno=Yii::$app->Dakutility->getefileNo("Dispatch");
    $lastfileno=$lastfileno.$lastno;
}

$url = Yii::$app->homeUrl."efile/dakmanagement/dakdispatch?securekey=$menuid";
?>
<h5 class="text-danger"  style="text-align:center"><b>डाक डिस्पैच विवरण / Dak Dispatch Detail</b></h5>
<form  id="dakdispatchtorm" action="<?=$url?>" method="POST" enctype="multipart/form-data">
    <div class="row">
        <div class="col-sm-6 mb15" >
            <label>डिस्पैच का तरीका / Select Mode of Dispatch</label>
            <select class="form-control form-control-sm" id="dipatch_mode" name="dipatch_mode" required="">
                <option value="">Select Mode of Dispatch</option>
                <option value="Speed Post">Speed Post</option>
                <option value="Register Parcel">Ordinary Post</option>
                <option value="Register Parcel">Register Parcel</option>
                <option value="Registered Post">Registered Post</option>
                <option value="Courier">Courier</option>
                <option value="By Hand">By Hand</option>
                <option value="Email">Email</option>
            </select>
        </div>
        <div class="col-sm-6 mb15" >
            <label class="required">डाक प्रवेश भाषा / Select Entry Language</label>
            <select class="form-control form-control-sm" id="dispatch_language" name="dispatch_language" required="">
                <option value="">Select Language</option>
                <option value="Hindi">Hindi</option>
                <option value="English">English</option>
            </select>
        </div>
	<div class="col-sm-6">
            <label>डिस्पैच नंबर / Dispatch Number</label>
            <input value="<?=$lastfileno?>" readonly="" type="text" class="form-control form-control-sm" id="daknodispatch" name="daknodispatch" required=""  placeholder="Dispatch Number"/>
        </div>
        <div class="col-sm-6 mb15">
            <label>डिस्पैच तारीख / Dispatch Date</label>
            <input value="<?=$curdate?> "readonly="" type="text" class="form-control form-control-sm dispdate" id="dispdate" name="dispdate" required=""  placeholder="Dispatch Date"/>
        </div>
        <div class='col-sm-12 mb15'>
            <label>पत्र संदर्भ संख्या / Letter Reference Number</label>
            <input type="text" class="form-control form-control-sm" name="letter_reference_num" placeholder="Letter Reference Number" required="" />
        </div>
        <div class='col-sm-6 mb15'>
            <label>किस भाषा में पत्र / Letter Language</label>
            <select class="form-control form-control-sm" name="letter_language" required="">
                <option value="">Select Letter Language</option>
                <option value="Hindi">Hindi</option>
                <option value="English">English</option>
                <option value="Other">Other</option>
            </select>
        </div>
    </div>
    <?php $i=1; ?>
    <input type="hidden" id="serial_number" value="<?=$i;?>"/>
    <div class="row" id="addressdiv<?=$i;?>">
        <div class="col-sm-6 mb15">
            <label>डिस्पैच (नाम और पदनाम) / Dispatch To(Person Name/Designation)</label>
            <input type="text" class="form-control form-control-sm " id="disptchfor<?=$i;?>" name="disptchfor[]" required=""  placeholder="Dispatch To(Person Name/Designation)"/>
        </div>
        <div class='col-sm-6 mb15'>
            <label>संगठन राज्य / Organization State</label>
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
            <label>संगठन जिला / Organization District</label>
            <select class='form-control form-control-sm' name='district_id_dis[]' id='district_id_dis<?=$i;?>'>
                <option value=''>Select District</option>
                
            </select>	
        </div>
        <div class="col-sm-5 mb15">
            <label>संगठन का पता / Organization Address</label>
            <textarea id="disporgadd<?=$i;?>" name="disporgadd[]" class="form-control form-control-sm" placeholder="Organization Address"></textarea>
        </div>
        <div class="col-sm-1 mb15">
        <label></label>
        <button data-toggle="tooltip" title="Add More Organization address" class="btn btn-sm" id='addmorediv' onclick="return addmorreaddress()"><img src="<?=Yii::$app->homeUrl?>images/details_open.png"/></button>
        </div>
    </div>
    <div id="appendhtmlforaddress"></div>
    <div class="row">
        
        <div class="col-sm-12 mb15">
        <label>संक्षिप्त सारांश (यदि कोई हो) / Short Summary (if any)</label>
        <textarea id="dissummary" name="dissummary" class="form-control form-control-sm" placeholder="Short Summary of dispatch" ></textarea>
        </div>
        
        <input type="hidden" id="_csrf" name="_csrf" value="<?= Yii::$app->request->csrfToken; ?>" />
        <input type="hidden" id="daktype" name="daktype" value="<?= $DISPATCH; ?>" />
        
    </div>
<div class="row">
<!--    <div class="col-sm-6 mb15" style="display: none;">
        <label>Remarks (If any)</label>
        <textarea id="disremarks" name="disremarks" class="form-control form-control-sm" placeholder="Remarks"></textarea>
    </div>
    <div class="col-sm-6 mb15"  style="display: none;">
        <label>Attachment (If any)</label>
        <input onchange="return checkfilesizeofmultiple('dispatchfile')" type="file" id="dispatchfile" class="form-control form-control-sm" name="dispatchfile" accept=".jpeg,.jpg,.png,.pdf" />
        <span style="color: red;font-size: 12px;">File size cannot be more then 1 MB</span>
    </div>-->
    <div class='col-sm-12'><h6 class="text-danger"><b>Dak Dispatch From:</b></h6></div>
	<div class='col-sm-6'>
            <label>विभाग / Select Department</label>
            <select required="" class="form-control form-control-sm" onchange='get_dept_emp_list("dept_emp_dropdown_disptach", "dept_emp_list_disptach")' name="dept_emp_dropdown_disptach" id="dept_emp_dropdown_disptach">
                <option value="">Select Department</option>
                <?php 
                $allDepts= Yii::$app->utility->get_dept(NULL);
                if(!empty($allDepts))
                {
                    foreach($allDepts as $d)
                    {
                        $dept_id = Yii::$app->utility->encryptString($d['dept_id']);
                        $dept_name = $d['dept_name'];
                        echo "<option value='$dept_id'>$dept_name</option>";
                    }
                }
                ?>
            </select>
	</div>
	<div class='col-sm-6'>
		<label>कर्मचारी / Select Employee</label>
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