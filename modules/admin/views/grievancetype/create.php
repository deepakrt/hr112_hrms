<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Grievancetype */

$this->title = 'Create Grievancetype';
$this->params['breadcrumbs'][] = ['label' => 'Grievancetypes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="grievancetype-create">

   <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
<div class="row">
	<div class="col-sm-12">
    <div class="col-sm-6"><?= $form->field($model, 'title')->textInput(['placeholder'=>'Grievance types', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div> </div>
    <div class="col-sm-12"><div class="col-sm-6"><?= $form->field($model, 'description')->textArea(['placeholder'=>'Description', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div></div>
    <div class="col-sm-12">
    <div class="col-sm-4"><?= $form->field($model, 'is_active')->dropDownList([ 'Y' => 'Yes', 'N' => 'No',], ['class'=>'form-control form-control-sm', 'prompt' => 'Select Is Active']) ?></div></div>
    <div class="col-sm-12 text-center">
        <button type="submit" name="GrievanceType1" class="btn btn-success btn-sm sl">Submit</button>
       <!--  <input name="add" type="GrievanceType" class="btn btn-success btn-sm sl" value="Save" /> -->
       
    </div>
</div>
<?php ActiveForm::end(); ?>

</div>
