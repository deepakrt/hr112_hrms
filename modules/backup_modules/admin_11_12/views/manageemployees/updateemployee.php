<link href="<?=Yii::$app->homeUrl?>css/bootstrap-datepicker.css" rel="stylesheet">
<script src="<?=Yii::$app->homeUrl?>js/bootstrap-datepicker.js"></script>
<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
$depts = Yii::$app->utility->get_dept(null);
$this->title = "Update Employee ";
if(!empty($depts)){
    //$new = "";
    $new = array();
    $i=0;
       //echo "<pre>";print_r($depts); die;
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
    //$new = "";
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
$auth_emps1 = ArrayHelper::map($auth_emps1, 'employee_code', 'name');
$auth_emps2 = ArrayHelper::map($auth_emps2, 'employee_code', 'name');

foreach($empLevel as $e){
    $l[$i]['level_id'] = Yii::$app->utility->encryptString($e);
    $l[$i]['level'] = $e;
    $i++;
}
$empLevel = ArrayHelper::map($l, 'level_id', 'level');
?>

<?php $form = ActiveForm::begin(['action'=>Yii::$app->homeUrl.'admin/manageemployees/update?securekey=$menuid', 'options' => ['enctype' => 'multipart/form-data']]); ?>
<input type="hidden" name="Employee[e_id]" value="<?=Yii::$app->utility->encryptString($model->e_id);?>" readonly="" />
<input type="hidden" name="Employee[old_image]" value="<?=Yii::$app->utility->encryptString($model->emp_image);?>" readonly="" />
<input type="hidden" name="Employee[old_sign]" value="<?=Yii::$app->utility->encryptString($model->emp_signature);?>" readonly="" />
<input type="hidden" name="Employee[old_email]" value="<?=Yii::$app->utility->encryptString($model->personal_email);?>" readonly="" />
<input type="hidden" name="Employee[menuid]" value="<?=$menuid?>" readonly="" />
    <div class="row">
        <div class="col-sm-3"><?= $form->field($model, 'employee_code')->textInput(['placeholder'=>'Employee Code', 'class'=>'form-control form-control-sm', 'readonly'=>true, 'maxlength' => true]) ?></div>
        <div class="col-sm-3"><?= $form->field($model, 'fname')->textInput(['placeholder'=>'First Name', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>
        <div class="col-sm-3"><?= $form->field($model, 'lname')->textInput(['placeholder'=>'Last Name', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>
        
        <div class="col-sm-3"><?= $form->field($model, 'name_hindi')->textInput(['placeholder'=>'Name in Hindi', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>

        <div class="col-sm-3"><?= $form->field($model, 'gender')->dropDownList([ 'M' => 'Male', 'F' => 'Female', ], ['class'=>'form-control form-control-sm', 'prompt' => 'Select Gender']) ?></div>
        <div class="col-sm-3"><?= $form->field($model, 'dob')->textInput(['placeholder'=>'Date of Birth', 'class'=>'form-control form-control-sm', 'readonly'=>true, 'maxlength' => true]) ?></div>
        
        <div class="col-sm-3"><?= $form->field($model, 'contact')->textInput(['maxlength'=>'10', 'onkeypress'=>'return allowOnlyNumber(event)', 'placeholder'=>'Contact No.', 'class'=>'form-control form-control-sm', ]) ?></div>
        <div class="col-sm-3"><?= $form->field($model, 'emergency_contact')->textInput(['maxlength'=>'10','placeholder'=>'Emergency Contact No.', 'class'=>'form-control form-control-sm']) ?></div>
        <div class="col-sm-3"><?= $form->field($model, 'personal_email')->textInput(['placeholder'=>'Personal Email', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>
        
        <div class="col-sm-3"><?= $form->field($model, 'marital_status')->dropDownList($marital, ['class'=>'form-control form-control-sm', 'prompt' => 'Select Marrital Status']) ?></div>
        <div class="col-sm-3"><?= $form->field($model, 'blood_group')->dropDownList($blood, ['class'=>'form-control form-control-sm', 'prompt' => 'Select Blood Group']) ?></div>
        <div class="col-sm-3"><?= $form->field($model, 'pan_number')->textInput(['placeholder'=>'PAN Number', 'class'=>'form-control form-control-sm', 'maxlength' => true, 'readonly'=>true]) ?></div>
    </div>
    <div class="inrhead">Correspondence Address</div>
	<div class="row">
            <div class="col-sm-3"><?= $form->field($model, 'address')->textInput(['placeholder'=>'House No.', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>
            <div class="col-sm-3"><?= $form->field($model, 'city')->textInput(['placeholder'=>'City', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>
            <div class="col-sm-3"><?= $form->field($model, 'state')->textInput(['placeholder'=>'State', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>
            <div class="col-sm-3"><?= $form->field($model, 'zip')->textInput(['maxlength'=>'6','placeholder'=>'Pin Code', 'class'=>'form-control form-control-sm', ]) ?></div>
            <div class="col-sm-3"><?= $form->field($model, 'contact1')->textInput(['maxlength'=>'10', 'onkeypress'=>'return allowOnlyNumber(event)', 'placeholder'=>'Landline / Mobile', 'class'=>'form-control form-control-sm',]) ?></div>
		
	</div>
	<div class="inrhead">Permanent Address </div>
	<div class="row">
            <div class="col-sm-12"><input type='checkbox' id="sameAddress" />&nbsp Same as Correspondence</div>
            <div class="col-sm-3"><?= $form->field($model, 'p_address')->textInput(['placeholder'=>'House No.', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>
            <div class="col-sm-3"><?= $form->field($model, 'p_city')->textInput(['placeholder'=>'City', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>
            <div class="col-sm-3"><?= $form->field($model, 'p_state')->textInput(['placeholder'=>'State', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>
            <div class="col-sm-3"><?= $form->field($model, 'p_zip')->textInput(['maxlength'=>'6','placeholder'=>'Pin Code', 'class'=>'form-control form-control-sm', ]) ?></div>
            <div class="col-sm-3"><?= $form->field($model, 'contact2')->textInput(['maxlength'=>'10', 'onkeypress'=>'return allowOnlyNumber(event)', 'placeholder'=>'Landline / Mobile', 'class'=>'form-control form-control-sm',]) ?></div>
	</div>      
        <div class="inrhead">Upload Photo & Signature</div>
        <div class="row">
            <?php 
            if(!empty($model->emp_image)){ ?>
                <div class="col-sm-6">
                    <span id="showempimgae">
                        <img src="<?=Yii::$app->homeUrl.$model->emp_image?>" width="100" /> <button id="changeEmpImage" type="button" class="btn btn-danger btn-sm btn-xs">Change</button>
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
                        <img src="<?=Yii::$app->homeUrl.$model->emp_signature?>" width="100" /> <button id="changeEmpSign" type="button" class="btn btn-danger btn-sm btn-xs">Change</button>
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
</script>
