<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\FtsGroupMaster */

$this->title = 'View';$model->group_id;
$this->params['breadcrumbs'][] = ['label' => 'Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fts-group-master-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->group_id], ['class' => 'btn btn-primary']) /*?>
        <?= Html::a('Delete', ['delete', 'id' => $model->group_id], [
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
            'group_id',
            'group_name',
            'group_description',
             
            'created_by',
            'creation_date',
            'last_modified_date',
        ],
    ]) ?>

</div>
