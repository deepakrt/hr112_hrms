<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\FtsCategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Categories';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fts-category-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Category', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'fts_category_id',
            'cat_name',
            //'is_hierarchical',
			[
             'label'=>'Is Hierarchical',
			 'contentOptions'=>['style' => 'text-align:center'],
             'format'=>'raw',
             'value' => function($model, $key, $index, $column) { return $model->is_hierarchical == 'N' ? 'No' : 'Yes';},
            ],
            'description',
			[
             'label'=>'Is Active',
			 'contentOptions'=>['style' => 'text-align:center'],
             'format'=>'raw',
             'value' => function($model, $key, $index, $column) { return $model->is_active == 'N' ? 'No' : 'Yes';},
            ],
             

            //['class' => 'yii\grid\ActionColumn'],
			['class' => 'yii\grid\ActionColumn', 'template' => '{view}{update}', 'header'=>"Actions",
          'buttons' => [
            'view' => function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-eye-open">View / </span>', $url, [
                            'title' => Yii::t('app', 'lead-view'),
                ]);
            },

            'update' => function ($url, $model) {
                return Html::a('<span class="glyphicon glyphicon-pencil">Edit</span>', $url, [
                            'title' => Yii::t('app', 'lead-update'),
                ]);
            }

          ],],
        ],
    ]); ?>

</div>
