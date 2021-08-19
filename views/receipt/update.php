<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\StoreMatReceiptTemp */

$this->title = 'Update Store Mat Receipt Temp: ' . ' ' . $model->ID;
$this->params['breadcrumbs'][] = ['label' => 'Store Mat Receipt Temps', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ID, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="store-mat-receipt-temp-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
