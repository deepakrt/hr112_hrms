<link href="<?=Yii::$app->homeUrl?>css/bootstrap-datepicker.css" rel="stylesheet">
<script src="<?=Yii::$app->homeUrl?>js/bootstrap-datepicker.js"></script>
<?php
$this->title= 'Add New Employee';
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
$depts = Yii::$app->utility->get_dept(null);
$getAllCategory = Yii::$app->utility->get_all_category();
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
        if($desg['desg_id'] != '1'){
            $id = base64_encode($desg['desg_id']);
            $new[$i]['desg_id'] = $id;
            $new[$i]['desg_name'] = $desg['desg_name'];
            $i++;   
        }
    }
    $desgs = $new;
}
$auth_emps1 = $auth_emps2=array();
$desgs = ArrayHelper::map($desgs, 'desg_id', 'desg_name');
$marital = Yii::$app->utility->get_marital_status();
$marital = ArrayHelper::map($marital, 'id', 'type');
$blood = Yii::$app->utility->get_blood_gourp();
$blood = ArrayHelper::map($blood, 'id', 'type');
$menuid = "";
$menuid = Yii::$app->utility->decryptString($_GET['securekey']);
$menuid = Yii::$app->utility->encryptString($menuid);
$empLevel = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15);
$i=0;
foreach($empLevel as $e){
    $l[$i]['level_id'] = Yii::$app->utility->encryptString($e);
    $l[$i]['level'] = $e;
    $i++;
}
$empLevel = ArrayHelper::map($l, 'level_id', 'level');
$dataArr = array();

if(empty($model->employee_id))
{
    // $newemp_code = Yii::$app->utility->get_last_emp_code();
    /*if(!empty($newemp_code)){
        $model->employee_id = $newemp_code['emp_code']+1;
    }*/

}
else
{
    $dataArr['readonly'] = true;
}


    $dataArr['placeholder'] = 'Employee ID';
    $dataArr['class'] = 'form-control form-control-sm';
    $dataArr['maxlength'] = true;
    // echo "<pre>"; print_r($getAllCategory); die();
?>
<input type="hidden" id="menuid" value="<?=$menuid?>" />
<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
<input type="hidden" readonly="" id="menuid" name="Employee[menuid]" value="<?=$menuid?>" />
    <div class="row">
        <div class="col-sm-3"><?= $form->field($model, 'employee_id')->textInput($dataArr) ?><button onclick="checkemp()"  type="button" class="btn btn-success btn-sm sl">Check Employee</button></div>
        <div class="col-sm-3"><?= $form->field($model, 'fname')->textInput(['placeholder'=>'First Name', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>
        <div class="col-sm-3"><?= $form->field($model, 'lname')->textInput(['placeholder'=>'Last Name', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>
        
        <div class="col-sm-3"><?= $form->field($model, 'gender')->dropDownList([ 'M' => 'Male', 'F' => 'Female', ], ['class'=>'form-control form-control-sm', 'prompt' => 'Select Gender']) ?></div>
        <div class="col-sm-3"><?= $form->field($model, 'dob')->textInput(['placeholder'=>'Date of Birth', 'class'=>'form-control form-control-sm', 'readonly'=>true, 'maxlength' => true]) ?></div>
        
        <div class="col-sm-3"><?= $form->field($model, 'contact')->textInput(['maxlength'=>'10', 'onkeypress'=>'return allowOnlyNumber(event)', 'placeholder'=>'Contact No.', 'class'=>'form-control form-control-sm', ]) ?></div>
       
        <div class="col-sm-3"><?= $form->field($model, 'emergency_contact')->textInput(['maxlength'=>'10','placeholder'=>'Emergency Contact No.', 'class'=>'form-control form-control-sm']) ?></div>
        
        <div class="col-sm-3"><?= $form->field($model, 'personal_email')->textInput(['placeholder'=>'Personal Email', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>
        
        <div class="col-sm-3"><?= $form->field($model, 'marital_status')->dropDownList($marital, ['class'=>'form-control form-control-sm','prompt' => 'Select marital status']) ?></div>
        <div class="col-sm-3"><?= $form->field($model, 'blood_group')->dropDownList($blood, ['class'=>'form-control form-control-sm', 'prompt' => 'Select blood group']) ?></div>

        <div class="col-sm-3">
            <div class="form-group field-employee-religion required">
                <label class="control-label" for="employee-religion">Religion</label>
                <input type="text" id="employee-religion" class="form-control form-control-sm" name="Employee[religion]" placeholder="Employee Religion" aria-required="true">
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
                            ?>
                                <option value="<?=base64_encode($cat->category_id);?>"><?=$cat->category_name;?></option>
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
                <input type="text" id="employee-caste" class="form-control form-control-sm" name="Employee[caste]" placeholder="Employee Caste" aria-required="true">
            </div>
        </div>

        <div class="col-sm-3"><?= $form->field($model, 'pan_number')->textInput(['placeholder'=>'PAN Number', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>
        <div class="col-sm-3">
            <div class="form-group field-employee-passport_detail required">
                <label class="control-label" for="employee-passport_detail">Passport Details</label>
                <input type="text" id="employee-passport_detail" class="form-control form-control-sm" name="Employee[passport_detail]" placeholder="Passport Details" aria-required="true">
            </div>
        </div>
    </div>
    <div class="inrhead">Correspondence Address</div>
	<div class="row">
            <div class="col-sm-3"><?= $form->field($model, 'address')->textInput(['placeholder'=>'House No.', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>
            <div class="col-sm-3"><?= $form->field($model, 'city')->textInput(['placeholder'=>'City', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>
            <div class="col-sm-3"><?= $form->field($model, 'state')->textInput(['placeholder'=>'State', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>
            <div class="col-sm-3"><?= $form->field($model, 'zip')->textInput(['maxlength'=>'6','placeholder'=>'Pin Code', 'class'=>'form-control form-control-sm', ]) ?></div>
            <!-- <div class="col-sm-3"><?php // echo $form->field($model, 'contact1')->textInput(['maxlength'=>'10', 'onkeypress'=>'return allowOnlyNumber(event)', 'placeholder'=>'Landline / Mobile', 'class'=>'form-control form-control-sm',]) ?></div>  -->
		
	</div>
	<div class="inrhead">Permanent Address </div>
	<div class="row">
            <div class="col-sm-12"><input type='checkbox' id="sameAddress" />&nbsp Same as Correspondence</div>
            <div class="col-sm-3"><?= $form->field($model, 'p_address')->textInput(['placeholder'=>'House No.', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>
            <div class="col-sm-3"><?= $form->field($model, 'p_city')->textInput(['placeholder'=>'City', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>
            <div class="col-sm-3"><?= $form->field($model, 'p_state')->textInput(['placeholder'=>'State', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>
            <div class="col-sm-3"><?= $form->field($model, 'p_zip')->textInput(['maxlength'=>'6','placeholder'=>'Pin Code', 'class'=>'form-control form-control-sm', ]) ?></div>
            <!-- <div class="col-sm-3"><?php // echo  $form->field($model, 'contact2')->textInput(['maxlength'=>'10', 'onkeypress'=>'return allowOnlyNumber(event)', 'placeholder'=>'Landline / Mobile', 'class'=>'form-control form-control-sm',]) ?></div> -->
	</div>
        <div class="inrhead">Service Information</div>
        <div class="row">
            <div class="col-sm-3"><?= $form->field($model, 'employment_type')->dropDownList([ 'R' => 'Regular', 'C' => 'Contract','T' => 'Trainees' ], ['class'=>'form-control form-control-sm', 'prompt' => 'Select Employment Type']) ?></div>

            <div class="col-sm-3"><?= $form->field($model, 'joining_date')->textInput(['placeholder'=>'Joining Date', 'class'=>'form-control form-control-sm', 'readonly'=>true, 'maxlength' => true]) ?></div>
            <div class="col-sm-3"><?= $form->field($model, 'dept_id')->dropDownList($depts, ['prompt'=>'Select Department', 'class'=>'form-control form-control-sm authemp',]); ?></div>
            <div class="col-sm-3"><?= $form->field($model, 'desg_id')->dropDownList($desgs, ['prompt'=>'Select Designation', 'class'=>'form-control form-control-sm']); ?></div>
            <div class="col-sm-3"><?= $form->field($model, 'authority1')->dropDownList($auth_emps1, ['prompt'=>'Select Reporting Authority', 'class'=>'form-control form-control-sm']); ?></div>
            <div class="col-sm-3"><?= $form->field($model, 'authority2')->dropDownList($auth_emps2, ['prompt'=>'Select Head of Department', 'class'=>'form-control form-control-sm']); ?></div>
<!--            <div class="col-sm-3"><?= $form->field($model, 'effected_from')->textInput(['placeholder'=>'Effected From', 'class'=>'form-control form-control-sm', 'readonly'=>true, 'maxlength' => true]) ?></div>-->
            <div class="col-sm-3"><?= $form->field($model, 'basic_cons_pay')->textInput(['placeholder'=>'Basic Pay', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>
            <!-- <div class="col-sm-3"><?php // echo $form->field($model, 'grade_pay_scale')->textInput(['placeholder'=>'Grade Pay Scale', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div> -->
            <div class="col-sm-3"><?= $form->field($model, 'emplevel')->dropDownList($empLevel, ['class'=>'form-control form-control-sm', 'prompt' => 'Select Level']) ?></div>
        </div>
        <div class="inrhead">Upload Photo & Signature</div>
        <div class="row">
            <div class="col-sm-6"><?= $form->field($model, 'emp_image')->fileInput(['accept'=>'.jpg,.jpeg,.png', 'placeholder'=>'Photo', 'class'=>'form-control form-control-sm PhotoSign', 'maxlength' => true]) ?></div>
            <div class="col-sm-6"><?= $form->field($model, 'emp_signature')->fileInput(['accept'=>'.jpg,.jpeg,.png','placeholder'=>'Signature', 'class'=>'form-control form-control-sm PhotoSign', 'maxlength' => true]) ?></div>
        </div>
	<div class="row">
		<div class="col-sm-12 text-center">
			<button type="submit" class="btn btn-success btn-sm sl">Submit</button>
			<a href="<?=Yii::$app->homeUrl?>admin/manageemployees?securekey=<?=$menuid?>" class="btn btn-danger btn-sm">Cancel</a>
		</div>
	</div>
<?php ActiveForm::end(); ?>
<script type="text/javascript">
    
    function checkemp()
  {
  //  startLoader();

   
    
        

      
        var empcode = $('#employee-employee_id').val();
  var menuid = $('#menuid').val();

        // getdeptempdropdown
        var url = BASEURL+"admin/manageemployees/getemp?securekey="+menuid;

        $.ajax({
            type: "POST",
            url: url,
            dataType: 'JSON',
            data:{ empcode:empcode },
            success: function(data)
            {
             
                // console.log(data.Status);

                if(data==1)
                {
                       swal('Employee already exists');
                   // alert('Employee already exists ');
                }
                else
                {
                     swal('Employee does not exist');
                 
                }

             
            }
        });
   
  }


  function startLoader()
  {
     $("#loading").show();
  }
   
  function stopLoader()
  {
      $("#loading").hide();
  }

</script>