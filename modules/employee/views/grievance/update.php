<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Grievance */

$this->title = 'Update Grievance: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Grievances', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="grievance-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
