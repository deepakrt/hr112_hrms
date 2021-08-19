<?php 
$curdate=date("d-m-Y");
$DISPATCH= Yii::$app->utility->encryptString("DISPATCH");
$lastno="1519";
$param_fileno=Yii::$app->Dakutility->efile_get_dakno("Dispatch");
//echo "<pre>";print_r($param_fileno);
if(empty($param_fileno))
{
    $lastfileno=Yii::$app->Dakutility->getefileNo();
    $lastfileno=Yii::$app->Dakutility->getefileNo();
    $fileNumFormat = "$lastfileno/Dispatch/";
    $lastfileno=$fileNumFormat.$lastno;
}
else 
{
    $param_fileno=$param_fileno["disp_number"];
    $fielno=explode("/",$param_fileno);
    $lastno=$fielno[4]+1;
    if($fielno[4] == '1569'){
        $lastno = "1";
    }
    $lastno = sprintf("%03d", $lastno);
    $lastfileno=Yii::$app->Dakutility->getefileNo();
    $fileNumFormat = "$lastfileno/Dispatch/";
    $lastfileno=$fileNumFormat.$lastno;
}

$url = Yii::$app->homeUrl."efile/dakmanagement/dakdispatch?securekey=$menuid";
$fy = Yii::$app->finance->getCurrentFY();
$amt = Yii::$app->fts_utility->efile_get_dispatch_amount($fy, NULL);
//echo "<pre>";print_r($amt);
?>
<input type="hidden" id="menuid" value="<?=$menuid?>" />
<h5 class="text-danger"  style="text-align:center"><b>डाक डिस्पैच विवरण / Dak Dispatch Detail</b></h5>
<?php if(!empty($amt)){ 
    $total = $amt['first_quarter_amt']+$amt['second_quarter_amt']+$amt['third_quarter_amt']+$amt['forth_quarter_amt'];
    ?>
<table class="table table-bordered">
    <tr>
        <th>Financial Year</th>
        <th>Credited Amount</th>
        <th>Debited Amount</th>
        <th>Balance</th>
    </tr>
    <tr>
        <td><?=$amt['financial_year']?></td>
        <td><a href="javascript:void(0)" class="display_info" data-type="C" data-msg="Details of Credited in <?=$amt['financial_year']?>">Rs. <?=number_format($total, 2)?></a></td>
        <td><a href="javascript:void(0)" class="display_info" data-type="D" data-msg="Details of Debited in <?=$amt['financial_year']?>">Rs. <?=number_format($amt['debited_amount'], 2)?></a></td>
        <td>Rs. <?=number_format(($total-$amt['debited_amount']), 2)?></td>
    </tr>
</table>
<div id="credit_html" style="display: none">
<table class="table table-bordered">
    <tr><th>Credited On</th><th>Credited Amount</th></tr>
    <?php 
    if($amt['first_quarter_amt'] > 0){
        $date = date('d-M-Y', strtotime($amt['first_entry_date']));
        $amount = number_format($amt['first_quarter_amt']);
        echo "<tr><td>Rs. $amount/-</td><td>$date</td></tr>";
    }
    if($amt['second_quarter_amt'] > 0){
        $date = date('d-M-Y', strtotime($amt['second_entry_date']));
        $amount = number_format($amt['second_quarter_amt']);
        echo "<tr><td>Rs. $amount/-</td><td>$date</td></tr>";
    }
    if($amt['third_quarter_amt'] > 0){
        $date = date('d-M-Y', strtotime($amt['third_entry_date']));
        $amount = number_format($amt['third_quarter_amt']);
        echo "<tr><td>Rs. $amount/-</td><td>$date</td></tr>";
    }
    if($amt['forth_quarter_amt'] > 0){
        $date = date('d-M-Y', strtotime($amt['forth_entry_date']));
        $amount = number_format($amt['forth_quarter_amt']);
        echo "<tr><td>Rs. $amount/-</td><td>$date</td></tr>";
    }
    ?>
</table>
</div>
<hr class="hrline">
<?php } ?>
<form  id="dakdispatchtorm" action="<?=$url?>" method="POST" enctype="multipart/form-data">
    <div class="row">
        <div class="col-sm-6 mb15" >
            <label>डिस्पैच का तरीका / Select Mode of Dispatch</label>
            <select class="form-control form-control-sm" id="dipatch_mode" name="dipatch_mode" required="">
                <option value="">Select Mode of Dispatch</option>
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
            <label class="required">डाक प्रवेश भाषा / Select Entry Language</label>
            <select class="form-control form-control-sm" id="dispatch_language" name="dispatch_language" required="">
                <option value="">Select Language</option>
                <option value="Hindi">Hindi</option>
                <option value="English">English</option>
            </select>
        </div>
	<div class="col-sm-6">
            <label>डिस्पैच नंबर / Dispatch Number</label>
            <input value="<?=$lastfileno?>" readonly="" type="text" class="form-control form-control-sm" id="daknodispatch" name="daknodispatch[]" required="" autocomplete="off"  placeholder="Dispatch Number"/>
        </div>
        <div class="col-sm-6 mb15">
            <label>डिस्पैच तारीख / Dispatch Date</label>
            <input value="<?=$curdate?> "readonly="" type="text" class="form-control form-control-sm dispdate" id="dispdate" name="dispdate" required="" autocomplete="off"  placeholder="Dispatch Date"/>
        </div>
        <div class='col-sm-12 mb15'>
            <label>पत्र संदर्भ संख्या / Letter Reference Number</label>
            <input type="text" class="form-control form-control-sm" name="letter_reference_num" placeholder="Letter Reference Number" required="" autocomplete="off" />
        </div>
        <div class='col-sm-6 mb15'>
            <label>किस भाषा में पत्र / Letter Language</label>
            <select class="form-control form-control-sm" name="letter_language" required="">
                <option value="">Select Letter Language</option>
                <option value="हिन्दी / Hindi">हिन्दी / Hindi</option>
                <option value="अंग्रेज़ी /English">अंग्रेज़ी /English</option>
                <option value="द्विभाषिक / Bilingual">द्विभाषिक / Bilingual</option>
                <option value="अन्य / Other">अन्य / Other</option>
            </select>
        </div>
    </div>
    <?php $i=1; ?>
    <input type="hidden" id="serial_number" value="<?=$i;?>"/>
    <input type="hidden" id="lastno" value="<?=$lastno;?>"/>
    <input type="hidden" id="fileNumFormat" value="<?=$fileNumFormat;?>"/>
    <div class="row" id="addressdiv<?=$i;?>">
        <div class="col-sm-6 mb15">
            <label>डिस्पैच (नाम और पदनाम) / Dispatch To(Person Name/Designation)</label>
            <input type="text" class="form-control form-control-sm " id="disptchfor<?=$i;?>" name="disptchfor[]" required=""  placeholder="Dispatch To(Person Name/Designation)"/>
        </div>
        <div class='col-sm-6 mb15'>
            <label>क्या इंटरनेशनल डाक है? / Is International Dak? </label><br>
            <button type="button" class="btn btn-secondary btn-sm " id='int_yes_<?=$i;?>' onclick="dakdisptach_intnl('<?=$i?>', 'Y')">Yes</button>
            <button type="button" class="btn btn-success btn-sm" id='int_no_<?=$i;?>' onclick="dakdisptach_intnl('<?=$i?>', 'N')" >No</button>
            <input type='hidden' id='is_international_<?=$i?>' name='is_international[]' readonly="" value="N" />
        </div>
        <div class='col-sm-6 mb15 hidestate_<?=$i;?>'>
            <label>संगठन राज्य / Organization State</label>
            <select class='form-control form-control-sm' name='state_id_dis[]' id='state_id_dis_<?=$i;?>' onchange="return getdispatchdist('<?=$i;?>')">
                <option value=''>Select State</option>
                <?php 
                foreach($states as $c){
                    $state_id = Yii::$app->utility->encryptString($c['state_id']);
                    $selected = "";
                    $state_name = ucwords($c['state_name']);
                    echo "<option value='$state_id' $selected>$state_name</option>";
                }
                ?>
            </select>
        </div>
        <div class='col-sm-6 mb15 hidestate_<?=$i;?>'>
            <label>संगठन जिला / Organization District</label>
            <select class='form-control form-control-sm' name='district_id_dis[]' id='district_id_dis_<?=$i;?>'>
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

    <div class='col-sm-12'><h6 class="text-danger"><b>Dak Dispatch From:</b></h6></div>
	<div class='col-sm-6'>
            <label>विभाग / Select Department</label>
            <select required="" class="form-control form-control-sm" onchange='get_dept_emp_list("dept_emp_dropdown_disptach", "dept_emp_list_disptach")' name="dept_emp_dropdown_disptach" id="dept_emp_dropdown_disptach">
                <option value=""> Department</option>
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
		<label>कर्मचारी /  Employee</label>
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

<!-- Modal -->
<div class="modal fade" id="fy_details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fy_details_title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="fy_details_html"></div>
            </div>
        </div>
    </div>
</div>