<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PrProjectTasks */

$this->title = 'Create Pr Project Tasks';
$this->params['breadcrumbs'][] = ['label' => 'Pr Project Tasks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pr-project-tasks-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
