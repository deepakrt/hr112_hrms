<?php
$this->title= 'Department';
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
<div class="row">
    <div class="col-sm-4"><?= $form->field($model, 'dept_name')->textInput(['placeholder'=>'Department Name', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>
    <div class="col-sm-4"><?= $form->field($model, 'dept_desc')->textArea(['placeholder'=>'Description', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>
    <div class="col-sm-4"><?= $form->field($model, 'is_active')->dropDownList([ 'Y' => 'Yes', 'N' => 'No',], ['class'=>'form-control form-control-sm', 'prompt' => 'Select Is Active']) ?></div>
    <div class="col-sm-12 text-center">
        <button type="submit" class="btn btn-success btn-sm sl">Submit</button>
        <a href="<?=Yii::$app->homeUrl?>admin/managedepartment?securekey=<?=$menuid?>" class="btn btn-danger btn-sm">Cancel</a>
    </div>
</div>
<?php ActiveForm::end(); ?>