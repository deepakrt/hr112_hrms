<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Transferpromotion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="transferpromotion-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'sdare')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'createdby')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lastupdate')->textInput() ?>

    <?= $form->field($model, 'action_emp')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_active')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
