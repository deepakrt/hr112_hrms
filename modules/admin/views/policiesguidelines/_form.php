<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\models\PoliciesGuidelines */
/* @var $form yii\widgets\ActiveForm */
$lists = Yii::$app->utility->get_policy_master($model->policy_id);
$listData=ArrayHelper::map($lists,'id','police_name')
?>
<link href="<?=Yii::$app->homeUrl?>css/bootstrap-datepicker.css" rel="stylesheet">
<script src="<?=Yii::$app->homeUrl?>js/bootstrap-datepicker.js"></script>
<div class="policies-guidelines-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">

    <div class="col-sm-6">
   <?php    echo $form->field($model, 'title')->dropDownList($listData, ['prompt'=>'Select...'] );  ?>
    
</div>
 <div class="col-sm-6"> <?= $form->field($model, 'valid_upto')->textInput(['placeholder'=>'Valid upto Date', 'class'=>'form-control form-control-sm', 'readonly'=>true, 'maxlength' => true]) ?>
</div>
<div class="col-sm-6">
 <?= $form->field($model, 'document')->fileInput(['class'=>'form-control form-control-sm']) ?>
    </div>
     <div class="col-sm-6"><?= $form->field($model, 'is_active')->dropDownList([ 'Y' => 'Yes', 'N' => 'No',], ['class'=>'form-control form-control-sm', 'prompt' => 'Select Is Active']) ?></div>
<div class="col-sm-6">
 <?= $form->field($model, 'description')->textArea(['placeholder'=>'Description', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?>
    </div>





  

   <div class="col-sm-12">

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
   
</div>
</div>
</div>
    <?php ActiveForm::end(); ?>

</div>
