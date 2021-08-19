<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PrProjectTasksSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pr Project Tasks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pr-project-tasks-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Pr Project Tasks', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'task_id',
            'parent_task_id',
            'project_id',
            'task_name',
            'task_description',
            //'assigned_to',
            //'assigned_by',
            //'priority',
            //'type',
            //'start_date',
            //'task_end_date_fla',
            //'task_end_date_emp',
            //'progress',
            //'remarks:ntext',
            //'state',
            //'created_on',
            //'updated_on',
            //'is_active',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
