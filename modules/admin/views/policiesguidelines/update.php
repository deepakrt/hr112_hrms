<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PoliciesGuidelines */

$this->title = 'Update Policies Guidelines: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Policies Guidelines', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="policies-guidelines-update">



    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
