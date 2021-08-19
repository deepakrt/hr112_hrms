<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\FtsCategory */

$this->title = 'View';$model->fts_category_id;
$this->params['breadcrumbs'][] = ['label' => 'Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fts-category-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->fts_category_id], ['class' => 'btn btn-primary']) /*?>
        <?= Html::a('Delete', ['delete', 'id' => $model->fts_category_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) */?>
		 <button type="button" class="btn btn-info" onclick="window.history.go(-1); return false;" name="back" ><span class="">Back</span> </button> 
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'fts_category_id',
            'cat_name',
            //'is_hierarchical',
			[
            'label'  => 'Is Hierarchical',
            'value' => $model->is_hierarchical == 'Y' ? 'Yes' : 'No'
			],
            'description',
            [
            'label'  => 'Is Active',
            'value' => $model->is_active == 'Y' ? 'Yes' : 'No'
			],
        ],
    ]) ?>

</div>
