<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\FtsDak */

$this->title = $model->dak_id;
$this->params['breadcrumbs'][] = ['label' => 'Fts Daks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fts-dak-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->dak_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->dak_id], [
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
            'dak_id',
            'send_to_type',
            'send_to',
            'send_from',
            'refrence_no',
            'file_date',
            'file_name',
            'subject',
            'category',
            'access_level',
            'priority',
            'is_confidential',
            'meta_keywords',
            'remarks',
            'summary',
            'doc_type',
            'status',
            'created_date',
            'modified_date',
            'is_active',
        ],
    ]) ?>

</div>
