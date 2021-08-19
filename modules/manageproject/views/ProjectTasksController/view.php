<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\PrProjectTasks */

$this->title = $model->task_id;
$this->params['breadcrumbs'][] = ['label' => 'Pr Project Tasks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="pr-project-tasks-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->task_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->task_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'task_id',
            'parent_task_id',
            'project_id',
            'task_name',
            'task_description',
            'assigned_to',
            'assigned_by',
            'priority',
            'type',
            'start_date',
            'task_end_date_fla',
            'task_end_date_emp',
            'progress',
            'remarks:ntext',
            'state',
            'created_on',
            'updated_on',
            'is_active',
        ],
    ]) ?>

</div>
