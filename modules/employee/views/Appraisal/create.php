<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Appraisal */

$this->title = 'Create Appraisal';
$this->params['breadcrumbs'][] = ['label' => 'Appraisals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
 <!--    <h1><?= Html::encode($this->title) ?></h1> -->
    <div class="row">
    	 <div class="col-sm-12">

      <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'class'=>'form-control form-control-sm', 'placeholder'=>'Title'])         

             ?>
       
     </div>

	<div class="col-sm-6">
            <?= $form->field($model, 'document')->fileInput(['class'=>'form-control form-control-sm']) ?>
         </div>

          	<div class="col-sm-6">
          		<!--  <?= $form->field($model, 'rating')->dropDownList([ '1' => '1 star', '2' => '1 star', '3' => '3 star','4' => '4 star', '5' => '5 star'], ['class'=>'form-control form-control-sm']) ?> -->
         </div>
          		
          		
          
    
<div class="col-sm-12">
       <?=  $form->field($model, 'job_description')->textArea(['maxlength' => true,'rows' => '6', 'class'=>'form-control form-control-sm', 'placeholder'=>'Job Description']) ?>
     </div>
     <div class="col-sm-12">
       <?=  $form->field($model, 'achievement')->textArea(['maxlength' => true,'rows' => '6', 'class'=>'form-control form-control-sm', 'placeholder'=>'Achievement']) ?>
     </div>
       <div class="col-sm-12 text-center">
        <input name="add" type="submit" class="btn btn-success btn-sm sl" value="Save" />
    </div>
    
    </div>

<?php ActiveForm::end(); 


