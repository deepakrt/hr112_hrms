<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PrProjectTasks */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pr-project-tasks-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'parent_task_id')->textInput() ?>

    <?= $form->field($model, 'project_id')->textInput() ?>

    <?= $form->field($model, 'task_name')->textInput() ?>

    <?= $form->field($model, 'task_description')->textInput() ?>

    <?= $form->field($model, 'assigned_to')->textInput() ?>

    <?= $form->field($model, 'assigned_by')->textInput() ?>

    <?= $form->field($model, 'priority')->dropDownList([ 'High' => 'High', 'Medium' => 'Medium', 'Low' => 'Low', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'type')->textInput() ?>

    <?= $form->field($model, 'start_date')->textInput() ?>

    <?= $form->field($model, 'task_end_date_fla')->textInput() ?>

    <?= $form->field($model, 'task_end_date_emp')->textInput() ?>

    <?= $form->field($model, 'progress')->textInput() ?>

    <?= $form->field($model, 'remarks')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'state')->dropDownList([ 'Open' => 'Open', 'Closed' => 'Closed', 'ReOpen' => 'ReOpen', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'created_on')->textInput() ?>

    <?= $form->field($model, 'updated_on')->textInput() ?>

    <?= $form->field($model, 'is_active')->dropDownList([ 'Y' => 'Y', 'N' => 'N', ], ['prompt' => '']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
