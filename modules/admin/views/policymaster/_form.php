<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PolicyMaster */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="policy-master-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
	<div class="col-sm-12">
<div class="col-sm-6">
      <?= $form->field($model, 'police_name')->textInput(['placeholder'=>'Police name', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?>
</div></div>
<div class="col-sm-12">
	<div class="col-sm-6">
    <?= $form->field($model, 'is_active')->dropDownList([ 'Y' => 'Yes', 'N' => 'No',], ['class'=>'form-control form-control-sm', 'prompt' => 'Select Is Active']) ?>
</div>
</div><div class="col-sm-12"><div class="col-sm-6">
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>
</div></div>
    <?php ActiveForm::end(); ?>

</div>
