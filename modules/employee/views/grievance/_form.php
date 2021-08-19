<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\models\Grievance */
/* @var $form yii\widgets\ActiveForm */
$info = Yii::$app->utility->get_grievance_type(null);
 
 $listData=ArrayHelper::map($info,'id','title');
?>

<div class="grievance-form">

    <?php $form = ActiveForm::begin(); ?>
     <div class="row">
         <div class="col-sm-12">

    <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'class'=>'form-control form-control-sm']) ?>
</div>
  
<div class="col-sm-6">
     <?php    echo $form->field($model, 'complaint_type')->dropDownList($listData, ['prompt'=>'Select...'] );  ?>
</div>
<div class="col-sm-6"> <?= $form->field($model, 'filename')->fileInput(['class'=>'form-control form-control-sm']) ?></div>
   <div class="col-sm-12">
<?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
</div>
</div>

  
<div class="col-sm-12 text-center">
        <input name="add" type="submit" class="btn btn-success btn-sm sl" value="Save" />
    </div>

    <?php ActiveForm::end(); ?>

</div>
