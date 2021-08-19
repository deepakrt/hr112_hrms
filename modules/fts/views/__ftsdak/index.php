<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FtsDakSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Fts Daks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fts-dak-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Fts Dak', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'dak_id',
            'send_to_type',
            'send_to',
            'send_from',
            'refrence_no',
            // 'file_date',
            // 'file_name',
            // 'subject',
            // 'category',
            // 'access_level',
            // 'priority',
            // 'is_confidential',
            // 'meta_keywords',
            // 'remarks',
            // 'summary',
            // 'doc_type',
            // 'status',
            // 'created_date',
            // 'modified_date',
            // 'is_active',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
