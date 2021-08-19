<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AppraisalSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="appraisal-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'feedback') ?>

    <?= $form->field($model, 'deleted') ?>

    <?= $form->field($model, 'sdate') ?>

    <?php // echo $form->field($model, 'uplodatedby') ?>

    <?php // echo $form->field($model, 'lastupdate') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
