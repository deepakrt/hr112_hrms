<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FtsDak */

$this->title = 'Update Fts Dak: ' . ' ' . $model->dak_id;
$this->params['breadcrumbs'][] = ['label' => 'Fts Daks', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->dak_id, 'url' => ['view', 'id' => $model->dak_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="fts-dak-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
