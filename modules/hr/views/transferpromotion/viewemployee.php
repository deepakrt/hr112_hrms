<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->title= 'View Employee';
$menuid = "";
if(isset($_GET['securekey']) AND !empty($_GET['securekey'])){
    $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
}
if(empty($menuid)){
    header('Location: '.Yii::$app->homeUrl); 
    exit;
}
$menuid = Yii::$app->utility->encryptString($menuid);
$e_id = Yii::$app->utility->decryptString($_GET['empid']);
//echo $e_id = Yii::$app->utility->encryptString($e_id);
// $encry = base64_encode($info['e_id']);
 $info = Yii::$app->utility->get_employees($e_id);
// echo "<pre>";
// print_r($info);
// echo "</pre>"; 

//$editUrl = Yii::$app->homeUrl."admin/manageemployees/updateemployee?key=$encry";
$editUrl = Yii::$app->homeUrl."admin/manageemployees/updateemployee?securekey=$menuid";
?>
<div class="row">
	<div class="col-md-3"><label>Employee Name</label></div>
	<div class="col-md-3"><p class="con"><?php echo $info['fullname'] ?></p></div>
	<div class="col-md-3"><label>Designation</label></div>
	<div class="col-md-3"><p class="con"><?php echo $info['desg_name'] ?></p></div>
</div>

<div class="row">
	<div class="col-md-3"><label>Department</label></div>
	<div class="col-md-3"><p class="con"><?php echo $info['dept_name'] ?></p></div>
	<div class="col-md-3"><label>Date of Birth</label></div>
	<div class="col-md-3"><p class="con"><?php echo $info['dob'] ?></p></div>
</div>


<div class="row">
	<div class="col-md-3"><label>Phone No</label></div>
	<div class="col-md-3"><p class="con"><?php echo $info['phone'] ?></p></div>
	<div class="col-md-3"><label>Gender</label></div>
	<div class="col-md-3"><p class="con"><?php echo $info['gender'] ?></p></div>
</div>
<div class="row">
	<div class="col-md-3"><label>Emergency Phone No</label></div>
	<div class="col-md-3"><p class="con"><?php echo $info['emergency_phone'] ?></p></div>
	<div class="col-md-3"><label>Address</label></div>
	<div class="col-md-3"><p class="con"><?php echo $info['address'] ?></p></div>
</div>



	<?php $form = ActiveForm::begin(); ?>
	<div class="row" style="margin-top: 30px;">
	
	<div class="col-sm-6">  <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'class'=>'form-control form-control-sm']) ?></div>
	<div class="col-sm-6">
	<?= $form->field($model, 'request_for')->dropDownList([ '1' => 'Transfer', '2' => 'Promotion', '3' => 'Suspension',], ['class'=>'form-control form-control-sm', 'prompt' => 'Select']) ?></div>

<div class="col-md-6">
    <?= $form->field($model, 'remarks')->textarea(['rows' => 6]) ?></div>

	<div class="col-md-6">

   </div>



  

    <div class="form-group">
       <input name="Transfer" type="submit" class="btn btn-success btn-sm sl" value="Save" />
    </div>
</div>
    <?php ActiveForm::end(); ?>

