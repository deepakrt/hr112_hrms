<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PolicyMaster */

$this->title = 'Create Policy Master';
$this->params['breadcrumbs'][] = ['label' => 'Policy Masters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="policy-master-create">

  <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
<div class="row">
	<div class="col-sm-12">
    <div class="col-sm-6">
  
     <?= $form->field($model, 'police_name')->textInput(['placeholder'=>'Police name', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?>
        
    </div>
   
     </div>
   
  
    <div class="col-sm-12">
    <div class="col-sm-6"><?= $form->field($model, 'is_active')->dropDownList([ 'Y' => 'Yes', 'N' => 'No',], ['class'=>'form-control form-control-sm', 'prompt' => 'Select Is Active']) ?></div></div>
    <div class="col-sm-12"><div class="col-sm-12">
        <button type="submit" name="policymaster" class="btn btn-success btn-sm sl">Submit</button>
       </div>
       
    </div>
</div>
<?php ActiveForm::end(); ?>

</div>
