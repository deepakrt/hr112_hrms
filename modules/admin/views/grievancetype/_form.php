<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Grievancetype */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="grievancetype-form">

    <?php $form = ActiveForm::begin(); ?>
<div class="row">
	<div class="col-sm-12">
    <div class="col-sm-6">
    <?= $form->field($model, 'title')->textInput() ?>
</div></div>
<div class="col-sm-12">
    <div class="col-sm-6">

    <?= $form->field($model, 'description')->textInput() ?> </div></div>
<div class="col-sm-12">
    <div class="col-sm-6">
    <?= $form->field($model, 'is_active')->dropDownList([ 'Y' => 'Yes', 'N' => 'No',], ['class'=>'form-control form-control-sm', 'prompt' => 'Select Is Active']) ?>
</div></div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
