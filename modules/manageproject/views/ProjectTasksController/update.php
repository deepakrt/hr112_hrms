<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PrProjectTasks */

$this->title = 'Update Pr Project Tasks: ' . $model->task_id;
$this->params['breadcrumbs'][] = ['label' => 'Pr Project Tasks', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->task_id, 'url' => ['view', 'id' => $model->task_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="pr-project-tasks-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
