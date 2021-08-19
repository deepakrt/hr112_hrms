<link href="<?=Yii::$app->homeUrl?>css/bootstrap-datepicker.css" rel="stylesheet">
<script src="<?=Yii::$app->homeUrl?>js/bootstrap-datepicker.js"></script>
<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
$getAllCategory = Yii::$app->utility->get_all_category();
$depts = Yii::$app->utility->get_dept(null);
if(!empty($depts)){
    $new = array();
    $i=0;
    foreach($depts as $dept){
        //$id = Yii::$app->utility->encryptString($dept['dept_id']);
        $id = base64_encode($dept['dept_id']);
        $new[$i]['dept_id'] = $id;
        $new[$i]['dept_name'] = $dept['dept_name'];
        $i++;
    }
    $depts = $new;
}
$depts = ArrayHelper::map($depts, 'dept_id', 'dept_name');
$desgs = Yii::$app->utility->get_designation(null);
if(!empty($desgs)){
    $new = array();
    $i=0;
    foreach($desgs as $desg){
        $id = base64_encode($desg['desg_id']);
        $new[$i]['desg_id'] = $id;
        $new[$i]['desg_name'] = $desg['desg_name'];
        $i++;
    }
    $desgs = $new;
}

$desgs = ArrayHelper::map($desgs, 'desg_id', 'desg_name');
$marital = Yii::$app->utility->get_marital_status();
$marital = ArrayHelper::map($marital, 'id', 'type');
$blood = Yii::$app->utility->get_blood_gourp();
$blood = ArrayHelper::map($blood, 'id', 'type');
$menuid = "";
$menuid = Yii::$app->utility->decryptString($_GET['securekey']);
$menuid = Yii::$app->utility->encryptString($menuid);
$empLevel = array(1,2,3,4,5,6,7,8,9,10,11,12);
$i=0;

/*$auth_emps1 = ArrayHelper::map($auth_emps1, 'employee_code', 'name');
$auth_emps2 = ArrayHelper::map($auth_emps2, 'employee_code', 'name');*/

foreach($empLevel as $e){
    $l[$i]['level_id'] = Yii::$app->utility->encryptString($e);
    $l[$i]['level'] = $e;
    $i++;
}
$empLevel = ArrayHelper::map($l, 'level_id', 'level');


// echo "<pre>"; print_r($model); die();

?>

<?php 
    $form = ActiveForm::begin(['action'=>'#', 'options' => ['enctype' => 'multipart/form-data']]); 
    // Yii::$app->homeUrl.'admin/manageemployees/update?securekey=$menuid
?>

<input type="hidden" name="Employee[e_id]" value="<?=Yii::$app->utility->encryptString($model->e_id);?>" readonly="" />
<input type="hidden" name="Employee[old_image]" value="<?=Yii::$app->utility->encryptString($model->emp_image);?>" readonly="" />
<input type="hidden" name="Employee[old_sign]" value="<?=Yii::$app->utility->encryptString($model->emp_signature);?>" readonly="" />
<input type="hidden" name="Employee[old_email]" value="<?=Yii::$app->utility->encryptString($model->personal_email);?>" readonly="" />
<input type="hidden" name="Employee[menuid]" value="<?=$menuid?>" readonly="" />
    <div class="row">
        <div class="col-sm-3"><?= $form->field($model, 'employee_code')->textInput(['placeholder'=>'Employee Code', 'class'=>'form-control form-control-sm', 'readonly'=>true, 'maxlength' => true]) ?></div>
        <div class="col-sm-3"><?= $form->field($model, 'fname')->textInput(['placeholder'=>'First Name', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>
        <div class="col-sm-3"><?= $form->field($model, 'lname')->textInput(['placeholder'=>'Last Name', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>
        <div class="col-sm-3"><?= $form->field($model, 'gender')->dropDownList([ 'M' => 'Male', 'F' => 'Female', ], ['class'=>'form-control form-control-sm', 'prompt' => 'Select Gender']) ?></div>
        <div class="col-sm-3"><?= $form->field($model, 'name_hindi')->textInput(['placeholder'=>'Name in Hindi', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>
        
        <div class="col-sm-3"><?= $form->field($model, 'dob')->textInput(['placeholder'=>'Date of Birth', 'class'=>'form-control form-control-sm', 'readonly'=>true, 'maxlength' => true]) ?></div>
        
        <div class="col-sm-3"><?= $form->field($model, 'contact')->textInput(['maxlength'=>'10', 'onkeypress'=>'return allowOnlyNumber(event)', 'placeholder'=>'Contact No.', 'class'=>'form-control form-control-sm', ]) ?></div>
        <div class="col-sm-3"><?= $form->field($model, 'emergency_contact')->textInput(['maxlength'=>'10','placeholder'=>'Emergency Contact No.', 'class'=>'form-control form-control-sm']) ?></div>
        <div class="col-sm-3"><?= $form->field($model, 'personal_email')->textInput(['placeholder'=>'Personal Email', 'class'=>'form-control form-control-sm', 'readonly'=>false, 'maxlength' => true]) ?></div>
        
        <div class="col-sm-3"><?= $form->field($model, 'marital_status')->dropDownList($marital, ['class'=>'form-control form-control-sm', 'prompt' => 'Select Marrital Status']) ?></div>
        <div class="col-sm-3"><?= $form->field($model, 'blood_group')->dropDownList($blood, ['class'=>'form-control form-control-sm', 'prompt' => 'Select Blood Group']) ?></div>

        <div class="col-sm-3">
            <div class="form-group field-employee-religion required">
                <label class="control-label" for="employee-religion">Religion</label>
                <input type="text" id="employee-religion" class="form-control form-control-sm" name="Employee[religion]" placeholder="Employee Religion" aria-required="true" value="<?=$model->religion?>">
            </div>
        </div>
        <div class="col-sm-3">
            <div class="form-group field-employee-category required">
                <label class="control-label" for="employee-category">Category</label>                
                <select id="employee-category" class="form-control form-control-sm" name="Employee[category]" aria-required="true" aria-invalid="true">
                    <option>Select Category</option>
                    <?php
                        if(!empty($getAllCategory))
                        {
                            foreach($getAllCategory as $cat)
                            {
                                $cat = (object)$cat;

                                $sel="";

                                if($model->category_id != '')
                                {
                                    if($cat->category_id == $model->category_id)
                                    {
                                        $sel=" selected='selected' ";
                                    }
                                }
                            ?>
                                <option datak="<?=$cat->category_id;?>" <?=$sel;?> value="<?=base64_encode($cat->category_id);?>"><?=$cat->category_name;?></option>
                            <?php
                            }
                        }
                    ?>
                </select>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="form-group field-employee-caste required">
                <label class="control-label" for="employee-caste">Caste</label>
                <input type="text" id="employee-caste" class="form-control form-control-sm" name="Employee[caste]" placeholder="Employee Caste" aria-required="true" value="<?=$model->caste?>">
            </div>
        </div>
        <div class="col-sm-3"><?= $form->field($model, 'pan_number')->textInput(['placeholder'=>'PAN Number', 'class'=>'form-control form-control-sm', 'maxlength' => true, 'readonly'=>false]) ?></div>

        <div class="col-sm-3">
            <div class="form-group field-employee-passport_detail required">
                <label class="control-label" for="employee-passport_detail">Passport Details</label>
                <input type="text" id="employee-passport_detail" class="form-control form-control-sm" name="Employee[passport_detail]" placeholder="Passport Details" aria-required="true" value="<?=$model->passport_detail?>">
            </div>
        </div>
    </div>
    <div class="inrhead">Correspondence Address</div>
	<div class="row">
            <div class="col-sm-3"><?= $form->field($model, 'address')->textInput(['placeholder'=>'House No.', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>
            <div class="col-sm-3"><?= $form->field($model, 'city')->textInput(['placeholder'=>'City', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>
            <div class="col-sm-3"><?= $form->field($model, 'state')->textInput(['placeholder'=>'State', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>
            <div class="col-sm-3"><?= $form->field($model, 'zip')->textInput(['maxlength'=>'6','placeholder'=>'Pin Code', 'class'=>'form-control form-control-sm', ]) ?></div>
            <!-- <div class="col-sm-3"><?php // $form->field($model, 'contact1')->textInput(['maxlength'=>'10', 'onkeypress'=>'return allowOnlyNumber(event)', 'placeholder'=>'Landline / Mobile', 'class'=>'form-control form-control-sm',]) ?></div> -->
		
	</div>
	<div class="inrhead">Permanent Address </div>
	<div class="row">
            <div class="col-sm-12"><input type='checkbox' id="sameAddress" />&nbsp Same as Correspondence</div>
            <div class="col-sm-3"><?= $form->field($model, 'p_address')->textInput(['placeholder'=>'House No.', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>
            <div class="col-sm-3"><?= $form->field($model, 'p_city')->textInput(['placeholder'=>'City', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>
            <div class="col-sm-3"><?= $form->field($model, 'p_state')->textInput(['placeholder'=>'State', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>
            <div class="col-sm-3"><?= $form->field($model, 'p_zip')->textInput(['maxlength'=>'6','placeholder'=>'Pin Code', 'class'=>'form-control form-control-sm', ]) ?></div>
            <!-- <div class="col-sm-3"><?php //  $form->field($model, 'contact2')->textInput(['maxlength'=>'10', 'onkeypress'=>'return allowOnlyNumber(event)', 'placeholder'=>'Landline / Mobile', 'class'=>'form-control form-control-sm',]) ?></div> -->
	</div>
    <?php
    /*
    ?>
       <div class="inrhead">Service Information</div>
        <div class="row">
            <div class="col-sm-3"><?= $form->field($model, 'employment_type')->dropDownList([ 'R' => 'Regular', 'C' => 'Contract', ], ['class'=>'form-control form-control-sm', 'prompt' => 'Select Employment Type']) ?></div>
            <div class="col-sm-3"><?= $form->field($model, 'joining_date')->textInput(['placeholder'=>'Joining Date', 'class'=>'form-control form-control-sm', 'readonly'=>true, 'maxlength' => true]) ?></div>
            <div class="col-sm-3"><?= $form->field($model, 'dept_id')->dropDownList($depts, ['prompt'=>'Select Department', 'class'=>'form-control form-control-sm authemp',]); ?></div>
            <div class="col-sm-3"><?= $form->field($model, 'desg_id')->dropDownList($desgs, ['prompt'=>'Select Designation', 'class'=>'form-control form-control-sm']); ?></div>
            <div class="col-sm-3"><?= $form->field($model, 'authority1')->dropDownList($auth_emps1, ['prompt'=>'Select Reporting Authority', 'class'=>'form-control form-control-sm']); ?></div>
            <div class="col-sm-3"><?= $form->field($model, 'authority2')->dropDownList($auth_emps2, ['prompt'=>'Select Head of Department', 'class'=>'form-control form-control-sm']); ?></div>
            <div class="col-sm-3"><?= $form->field($model, 'effected_from')->textInput(['placeholder'=>'Effected From', 'class'=>'form-control form-control-sm', 'readonly'=>true, 'maxlength' => true]) ?></div>
            <div class="col-sm-3"><?= $form->field($model, 'grade_pay_scale')->textInput(['placeholder'=>'Grade Pay Scale', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>
            <div class="col-sm-3"><?= $form->field($model, 'emplevel')->dropDownList($empLevel, ['class'=>'form-control form-control-sm', 'prompt' => 'Select Level']) ?></div>
        </div>

    <?php
        */
    ?>
        <div class="inrhead">Upload Photo & Signature</div>
        <div class="row">
            <?php 
            if(!empty($model->emp_image)){ ?>
                <div class="col-sm-6">
                    <span id="showempimgae">
                        <img src="<?=Yii::$app->homeUrl.$model->emp_image?>" width="100" />
                        <br><button id="changeEmpImage" type="button" class="btn btn-danger btn-sm btn-xs">Change Photo</button>
                    </span>
                    <span id="changeempimgae" style="display: none;">
                        <?= $form->field($model, 'emp_image')->fileInput(['accept'=>'.jpg,.jpeg,.png', 'placeholder'=>'Photo', 'class'=>'form-control form-control-sm PhotoSign', 'maxlength' => true]) ?>
                    </span>
                </div>
            <?php }else{
                echo $form->field($model, 'emp_image')->fileInput(['accept'=>'.jpg,.jpeg,.png', 'placeholder'=>'Photo', 'class'=>'form-control form-control-sm PhotoSign', 'maxlength' => true]);
            }
            ?>
            <?php 
            if(!empty($model->emp_signature)){ ?>
                <div class="col-sm-6">
                    <span id="showempsign">
                        <img src="<?=Yii::$app->homeUrl.$model->emp_signature?>" width="100" />
                        <br><button id="changeEmpSign" type="button" class="btn btn-danger btn-sm btn-xs">Change Signature</button>
                    </span>
                    <span id="changeempsign" style="display: none;">
                        <?= $form->field($model, 'emp_signature')->fileInput(['accept'=>'.jpg,.jpeg,.png', 'placeholder'=>'Photo', 'class'=>'form-control form-control-sm PhotoSign', 'maxlength' => true]) ?>
                    </span>
                </div>
            <?php }else{
                echo $form->field($model, 'emp_signature')->fileInput(['accept'=>'.jpg,.jpeg,.png','placeholder'=>'Signature', 'class'=>'form-control form-control-sm PhotoSign', 'maxlength' => true]);
            }
            ?>
            <div class="col-sm-3"><?= $form->field($model, 'is_active')->dropDownList([ 'Y' => 'Yes', 'N' => 'No'], ['class'=>'form-control form-control-sm', 'prompt' => 'Select Is Active']) ?></div>
            <div class="col-sm-3"><?= $form->field($model, 'created_date')->textInput(['placeholder'=>'Added Date', 'class'=>'form-control form-control-sm', 'readonly'=>true]) ?></div>
        </div>
        
	<div class="row">
            <div class="col-sm-12 text-center">
                <button type="submit" class="btn btn-success btn-sm sl">Submit</button>
                <a href="<?=Yii::$app->homeUrl?>admin/manageemployees?securekey=<?=$menuid?>" class="btn btn-danger btn-sm">Cancel</a>
            </div>
	</div>
<?php ActiveForm::end(); ?>
<script>
    $(document).ready(function(){
        $("#changeEmpImage").click(function(){
            $("#changeempimgae").show();
            $("#showempimgae").remove();
            $("#chk_image_change").val('1');
            $("#employee-emp_image").attr('required',true);
            
        });
        $("#changeEmpSign").click(function(){
            $("#changeempsign").show();
            $("#showempsign").remove();
            $("#chk_sign_change").val('1');
            $("#employee-emp_signature").attr('required',true);
        });
    });


     $("#w0").submit(function(e) {
        // actionHideContent();

         e.preventDefault(); // avoid to execute the actual submit of the form.

        // var form = $(this);
        // var url = form.attr('action');

        e.preventDefault();    
        var formData = new FormData(this);


        var formd = $('#w0');

            // startLoader();
             $.ajax({
                url: "<?php echo Yii::$app->homeUrl."admin/manageemployees/update?securekey=$menuid";?>",
                type: 'POST',
                data: formData,
                dataType: 'JSON',
                success: function (data) 
                {
                    if(data.data_suc == 1)
                    {
                        $('#w0').html('');
                        $('#w0').hide();
                        $('#w0').html('PLease wait....');
                        swal('Done.',data.msg,'success');

                        console.log(data.red_url);
                        setTimeout(function(){ 
                            window.location.assign('<?=Yii::$app->homeUrl?>admin/manageemployees/viewemployee?securekey=<?=$menuid?>&tab=info');
                        }, 3000);                        
                    }
                    else
                    {
                        swal('Warning!',data.msg,'error');
                    }
                },
                cache: false,
                contentType: false,
                processData: false
            });
        
    });

</script>