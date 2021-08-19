<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\FtsDakSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fts-dak-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'dak_id') ?>

    <?= $form->field($model, 'send_to_type') ?>

    <?= $form->field($model, 'send_to') ?>

    <?= $form->field($model, 'send_from') ?>

    <?= $form->field($model, 'refrence_no') ?>

    <?php // echo $form->field($model, 'file_date') ?>

    <?php // echo $form->field($model, 'file_name') ?>

    <?php // echo $form->field($model, 'subject') ?>

    <?php // echo $form->field($model, 'category') ?>

    <?php // echo $form->field($model, 'access_level') ?>

    <?php // echo $form->field($model, 'priority') ?>

    <?php // echo $form->field($model, 'is_confidential') ?>

    <?php // echo $form->field($model, 'meta_keywords') ?>

    <?php // echo $form->field($model, 'remarks') ?>

    <?php // echo $form->field($model, 'summary') ?>

    <?php // echo $form->field($model, 'doc_type') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_date') ?>

    <?php // echo $form->field($model, 'modified_date') ?>

    <?php // echo $form->field($model, 'is_active') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
