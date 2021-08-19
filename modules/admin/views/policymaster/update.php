<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PolicyMaster */

$this->title = 'Update Policy Master: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Policy Masters', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="policy-master-update">

  

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
