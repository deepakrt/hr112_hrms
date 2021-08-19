<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\models\PoliciesGuidelines */

$this->title = 'Create Policies Guidelines';
$this->params['breadcrumbs'][] = ['label' => 'Policies Guidelines', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$lists = Yii::$app->utility->get_policy_master(null);
$listData=ArrayHelper::map($lists,'id','police_name')
?>
<link href="<?=Yii::$app->homeUrl?>css/bootstrap-datepicker.css" rel="stylesheet">
<script src="<?=Yii::$app->homeUrl?>js/bootstrap-datepicker.js"></script>
<div class="policies-guidelines-create">

     <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
<div class="row">
	
    
 <div class="col-sm-6">
  <?= $form->field($model, 'title')->textInput(['placeholder'=>'Title', 'class'=>'form-control form-control-sm',  'maxlength' => true]) ?></div>
       <div class="col-sm-6">
        <?php    echo $form->field($model, 'policy_id')->dropDownList($listData, ['prompt'=>'Select...'] );  ?>
    
        
    </div>
   <div class="col-sm-6"> <?= $form->field($model, 'valid_upto')->textInput(['placeholder'=>'Valid upto Date', 'class'=>'form-control form-control-sm', 'readonly'=>true, 'maxlength' => true]) ?></div>
     
   <div class="col-sm-6"><?= $form->field($model, 'document')->fileInput(['class'=>'form-control form-control-sm']) ?></div>
   <div class="col-sm-6"> <?= $form->field($model, 'description')->textArea(['placeholder'=>'Description', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>
    <div class="col-sm-6">
<?= $form->field($model, 'is_active')->dropDownList([ 'Y' => 'Yes', 'N' => 'No',], ['class'=>'form-control form-control-sm', 'prompt' => 'Select Is Active']) ?>
    </div>
 
    <div class="col-sm-12">
        <button type="submit" name="Policies" class="btn btn-success btn-sm sl">Submit</button>
       <!--  <input name="add" type="GrievanceType" class="btn btn-success btn-sm sl" value="Save" /> -->
       
    </div>
</div>
<?php ActiveForm::end(); ?>

</div>
        