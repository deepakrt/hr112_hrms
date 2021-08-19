<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Appraisal */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="appraisal-form">

    <?php $form = ActiveForm::begin(); ?>
<div class="row">
         <div class="col-sm-12">
    <?= $form->field($model, 'title')->textarea(['rows' => 6]) ?>
</div>
<div class="col-sm-12"> <?=  $form->field($model, 'job_description')->textArea(['maxlength' => true,'rows' => '6', 'class'=>'form-control form-control-sm', 'placeholder'=>'Job Description']) ?></div>
<div class="col-sm-12"> <?=  $form->field($model, 'achievement')->textArea(['maxlength' => true,'rows' => '6', 'class'=>'form-control form-control-sm', 'placeholder'=>'Achievement']) ?></div>
<div class="col-sm-6"><?= $form->field($model, 'sdate')->textInput() ?></div>
<div class="col-sm-6">  <?= $form->field($model, 'uploadedby')->textInput(['maxlength' => true]) ?></div>
<div class="col-sm-6">
      <?=  $form->field($model, 'rating')->dropDownList(['1' => '1 star', '2' => '2 star', '3' => '4 star', '4' => '4 star', '5' => '5 star'],['class'=>'form-control form-control-sm']); ?>
</div>
<div class="col-sm-6">
    <?= $form->field($model, 'feedback')->textarea(['rows' => 6]) ?>
</div>
 

    

  


  

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>
</div>
    <?php ActiveForm::end(); ?>

</div>
