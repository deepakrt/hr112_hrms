<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\FtsCategory */

$this->title = 'Update Category: ' . ' ' . $model->fts_category_id;
$this->params['breadcrumbs'][] = ['label' => 'Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->fts_category_id, 'url' => ['view', 'id' => $model->fts_category_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="fts-category-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
