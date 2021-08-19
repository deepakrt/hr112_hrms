<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FtsGroupMaster */

$this->title = 'Update Group ';// . ' ' . $model->group_id;
$this->params['breadcrumbs'][] = ['label' => 'Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'View', 'url' => ['view', 'id' => $model->group_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="fts-group-master-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
