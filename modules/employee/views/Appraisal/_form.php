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

    <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'class'=>'form-control form-control-sm']) ?>
</div>
<div class="col-sm-6">  <?= $form->field($model, 'document')->fileInput(['class'=>'form-control form-control-sm']) ?></div>
<div class="col-sm-6"><?= $form->field($model, 'document')->textInput(['maxlength' => true, 'class'=>'form-control form-control-sm']) ?>
    

</div>
<div class="col-sm-12"> <?=  $form->field($model, 'job_description')->textArea(['maxlength' => true,'rows' => '6', 'class'=>'form-control form-control-sm', 'placeholder'=>'Job Description']) ?></div>
<div class="col-sm-12"> <?=  $form->field($model, 'achievement')->textArea(['maxlength' => true,'rows' => '6', 'class'=>'form-control form-control-sm', 'placeholder'=>'Achievement']) ?></div>

  


  

<div class="col-sm-12 text-center">
        <input name="add" type="submit" class="btn btn-success btn-sm sl" value="Save" />
    </div>
  

  

   
</div>
    <?php ActiveForm::end(); ?>

</div>
