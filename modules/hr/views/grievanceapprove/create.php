<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Grievance */

$this->title = 'Create Grievance';
$this->params['breadcrumbs'][] = ['label' => 'Grievances', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
     <div class="row">
         <div class="col-sm-12">

    <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'class'=>'form-control form-control-sm']) ?>
</div>
  
<div class="col-sm-6">
      <?=  $form->field($model, 'complaint_type')->dropDownList(['1' => '1 star', '2' => '2 star', '3' => '4 star', '4' => '4 star', '5' => '5 star'],['class'=>'form-control form-control-sm']); ?>
</div>
<div class="col-sm-6"> <?= $form->field($model, 'filename')->fileInput(['class'=>'form-control form-control-sm']) ?></div>
   <div class="col-sm-12">
<?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
</div>
<div class="col-sm-12 text-center">
        <input name="add" type="submit" class="btn btn-success btn-sm sl" value="Save" />
    </div>
</div>

  
     

    <?php ActiveForm::end(); ?>


