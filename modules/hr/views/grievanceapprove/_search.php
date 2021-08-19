<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\GrievanceSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="grievance-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'complaint_type') ?>

    <?= $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'sdate') ?>

    <?php // echo $form->field($model, 'lastupdate') ?>

    <?php // echo $form->field($model, 'createdby') ?>

    <?php // echo $form->field($model, 'sent_to') ?>

    <?php // echo $form->field($model, 'dockerno') ?>

    <?php // echo $form->field($model, 'filename') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
