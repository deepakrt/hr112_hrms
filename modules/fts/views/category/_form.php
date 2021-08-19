<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\FtsCategory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="fts-category-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'cat_name')->textInput() ?>

    <?= $form->field($model, 'is_hierarchical')->dropDownList([ 'Y' => 'Yes', 'N' => 'No', ]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_active')->dropDownList([ 'Y' => 'Yes', 'N' => 'No', ]) ?>

   <div class="form-group">
    <div class="col-sm-6 col-xs-12">
	 
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		 
		<button type="button" class="btn btn-info" onclick="window.history.go(-1); return false;" name="back" ><span class="">Back</span> </button> 
		 
    </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
