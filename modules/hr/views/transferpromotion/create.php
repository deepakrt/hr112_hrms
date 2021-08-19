<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Transferpromotion */

$this->title = 'Create Transferpromotion';
$this->params['breadcrumbs'][] = ['label' => 'Transferpromotions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transferpromotion-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
