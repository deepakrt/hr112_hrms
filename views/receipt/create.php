<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\StoreMatReceiptTemp */

$this->title = 'Create Store Mat Receipt Temp';
$this->params['breadcrumbs'][] = ['label' => 'Store Mat Receipt Temps', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="store-mat-receipt-temp-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
