<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PrProjectTasksSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pr-project-tasks-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'task_id') ?>

    <?= $form->field($model, 'parent_task_id') ?>

    <?= $form->field($model, 'project_id') ?>

    <?= $form->field($model, 'task_name') ?>

    <?= $form->field($model, 'task_description') ?>

    <?php // echo $form->field($model, 'assigned_to') ?>

    <?php // echo $form->field($model, 'assigned_by') ?>

    <?php // echo $form->field($model, 'priority') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'start_date') ?>

    <?php // echo $form->field($model, 'task_end_date_fla') ?>

    <?php // echo $form->field($model, 'task_end_date_emp') ?>

    <?php // echo $form->field($model, 'progress') ?>

    <?php // echo $form->field($model, 'remarks') ?>

    <?php // echo $form->field($model, 'state') ?>

    <?php // echo $form->field($model, 'created_on') ?>

    <?php // echo $form->field($model, 'updated_on') ?>

    <?php // echo $form->field($model, 'is_active') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
