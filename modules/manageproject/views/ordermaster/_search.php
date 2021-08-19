<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\OrdermasterSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ordermaster-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'clientid') ?>

    <?= $form->field($model, 'orderdate') ?>

    <?= $form->field($model, 'number') ?>

    <?= $form->field($model, 'amount') ?>

    <?php // echo $form->field($model, 'ordertype') ?>

    <?php // echo $form->field($model, 'fundingagency') ?>

    <?php // echo $form->field($model, 'activeuser') ?>

    <?php // echo $form->field($model, 'deleted') ?>

    <?php // echo $form->field($model, 'sessionid') ?>

    <?php // echo $form->field($model, 'updatedon') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
