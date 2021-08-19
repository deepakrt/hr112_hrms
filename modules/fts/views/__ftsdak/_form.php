<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\FtsDak */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fts-dak-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'send_to_type')->textInput() ?>

    <?= $form->field($model, 'send_to')->textInput() ?>

    <?= $form->field($model, 'send_from')->textInput() ?>

    <?= $form->field($model, 'refrence_no')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'file_date')->textInput() ?>

    <?= $form->field($model, 'file_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'subject')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'category')->textInput() ?>

    <?= $form->field($model, 'access_level')->dropDownList([ 'R' => 'R', 'W' => 'W', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'priority')->dropDownList([ 'Normal' => 'Normal', 'Moderate' => 'Moderate', 'High' => 'High', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'is_confidential')->dropDownList([ 'Y' => 'Y', 'N' => 'N', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'meta_keywords')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'remarks')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'summary')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'doc_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList([ 'DRAFT' => 'DRAFT', 'SENT' => 'SENT', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'created_date')->textInput() ?>

    <?= $form->field($model, 'modified_date')->textInput() ?>

    <?= $form->field($model, 'is_active')->dropDownList([ 'Y' => 'Y', 'N' => 'N', ], ['prompt' => '']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
