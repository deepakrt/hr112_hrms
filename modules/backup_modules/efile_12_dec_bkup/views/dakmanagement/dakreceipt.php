<?php 
$curdate=date("d-m-Y");
$lastfileno="";
$DAKREC= Yii::$app->utility->encryptString("DAKREC");
$paraf_id="1844";
$param_fileno=Yii::$app->Dakutility->efile_get_dakno(NULL);
//echo "<pre>";print_r($param_fileno); 
if(empty($param_fileno)){
    $lastfileno=$paraf_id;
}else {
    $param_fileno=$param_fileno["dak_number"];
    $lastno=$param_fileno + 1;
    if($param_fileno == '1985'){
        $lastno = "1";
    }
    $lastno = sprintf("%03d", $lastno);
    $lastfileno=$lastno;
}
$url = Yii::$app->homeUrl."efile/dakmanagement/dakreceipt?securekey=$menuid";
?>
<h5 class="text-danger"  style="text-align:center"><b>डाक रसीद विवरण / Dak Receipt Details</b></h5>

<form  id="dakreceiptorm" method="POST" action="<?=$url?>" enctype="multipart/form-data">
    <div class="row">
        <input type="hidden" id="daktype" name="daktype" value="<?= $DAKREC; ?>" />
	<div class="col-sm-6 mb15" >
            <label class="required">प्राप्ति की विधि / Select Mode of Receipt</label>
            <select class="form-control form-control-sm" id="rece_mode" name="rece_mode" required="">
                <option value="">Select Mode of Receipt</option>
                <option value="साधारण पोस्ट / Ordinary Post">साधारण पोस्ट / Ordinary Post</option>
                <option value="स्पीड पोस्ट / Speed Post">स्पीड पोस्ट / Speed Post</option>
                <option value="रजिस्टर पोस्ट / Registered Post">रजिस्टर पोस्ट / Registered Post</option>
                <option value="रजिस्टर पार्सल / Register Parcel">रजिस्टर पार्सल / Register Parcel</option>
                <option value="कोरियर / Courier">कोरियर / Courier</option>
                <option value="हस्तगत / By Hand">हस्तगत / By Hand</option>
                <option value="ई-मेल / E-mail">ई-मेल / E-mail</option>
            </select>
        </div>
        <div class="col-sm-6 mb15" >
            <label class="required">रसीद प्रवेश भाषा/ Entry Language</label>
            <select class="form-control form-control-sm" id="recpt_language" name="recpt_language" required="">
                <option value="">Select Language</option>
                <option value="Hindi">Hindi</option>
                <option value="English">English</option>
            </select>
        </div>
	
	<div class="col-sm-6 mb15">
            <label>रसीद संख्या / Receipt Number</label>
            <input  readonly="" value="<?=$lastfileno?>" type="text" class="form-control form-control-sm" id="dakno" name="dakno" placeholder="Receipt Number" autocomplete="off" required="" />
	</div>
	<div class="col-sm-6 mb15">
            <label>रसीद तारीख / Receipt Date</label>
            <input value="<?=$curdate?>" readonly="" type="text" class="form-control form-control-sm dakrecpdate" id="receiptdate" name="receiptdate" autocomplete="off" placeholder="Receipt Date" required="" />
	</div>
	<div class="col-sm-6 mb15">
            <label>कहाँ से प्राप्त (नाम और पदनाम) / Received From(Person Name/Designation)</label>
            <input type="text" class="form-control form-control-sm" id="receiptfrom" required=""  name="receiptfrom" placeholder="Receipt From(Person Name/Designation)" autocomplete="off"/>
	</div>
        <div class='col-sm-6 mb15'>
            <label>क्या इंटरनेशनल डाक है? / Is International Dak? </label><br>
            <button type="button" class="btn btn-secondary btn-sm" id='int_yes' onclick="dak_intnl('Y')">Yes</button>
            <button type="button" class="btn btn-success btn-sm" id='int_no' onclick="dak_intnl('N')">No</button>
            <input type='hidden' id='is_international' name='is_international' readonly="" value="N" />
        </div>
	<div class='col-sm-6 mb15 hidestate'>
            <label>संगठन राज्य / Organization State</label>
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
        <div class='col-sm-6 mb15 hidestate'>
            <label>संगठन जिला / Organization District</label>
            <select class='form-control form-control-sm' name='district_id_rec' id='district_id_rec' required="">
                <option value=''>Select District</option>
                
            </select>	
        </div>
	<div class="col-sm-6 mb15">
            <label>संगठन का पता / Organization Address</label>
            <textarea required="" id="orgname" name="orgname" class="form-control form-control-sm" placeholder="Organization Address"></textarea>
            <!--<input type="text" class="form-control form-control-sm" id="orgname" name="orgname" placeholder="Organization Address" required="" /> -->
	</div>
	
    <div class="col-sm-6 mb15">
        <label>संक्षिप्त सारांश (यदि कोई हो) / Short Summary (if any)</label>
        <textarea id="recsummary" name="recsummary" class="form-control form-control-sm" placeholder="Short Summary of Receipt "  ></textarea>
    </div>
    <div class="col-sm-6 mb15">
        <label>टिप्पणी (यदि कोई हो) / Remarks (If any)</label>
        <textarea id="recremarks" name="recremarks" class="form-control form-control-sm" placeholder="Remarks"></textarea>
    </div>
</div>
<div class="row"> 

    
    <!--<div class="col-sm-6 mb15">
        <label>Attachment (If any)</label>
        <input id="recnotefile" onchange="return checkfilesizeofmultiple('recnotefile')" type="file" class="form-control form-control-sm" name="recnotefile" accept=".jpeg,.jpg,.png,.pdf" />
        <span style="color: red;font-size: 12px;">File size cannot be more then 1 MB</span>
    </div>-->
    <div class='col-sm-12'><h6 class="text-danger"><b>डाक फॉरवर्ड / Dak Forward To:</b></h6></div>
	<div class='col-sm-6'>
            <label>विभाग / Department</label>
            <select required="" class="form-control form-control-sm" onchange='get_dept_emp_list("dept_emp_dropdown", "dept_emp_list_dropdown")' id="dept_emp_dropdown" name="dept_emp_dropdown">
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
            <label>कर्मचारी / Employee</label>
            <select required="" class='form-control form-control-sm' id='dept_emp_list_dropdown' name="dept_emp_list_dropdown">
                <option value=''>Select Employee</option>
            </select>
	</div>
    <div class="col-sm-12 text-center mb15">
        <br>
        <button type="button" id="dakreceiptormsave"  class="btn btn-success btn-sm">Forward</button>
        <!--<input id="dakreceiptormsave" type="button" class="btn btn-success btn-sm sl" value="Forward"/>-->
        <input type="reset"  class="btn btn-danger btn-sm" value="Reset">
    </div>
</div>
</form>