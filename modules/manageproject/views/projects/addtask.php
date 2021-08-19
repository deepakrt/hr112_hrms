<link href="<?=Yii::$app->homeUrl?>css/select2.min.css" rel="stylesheet" />
<script src="<?=Yii::$app->homeUrl?>js/select2.min.js"></script>
<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
if(empty($menuid)){
    header('Location: '.Yii::$app->homeUrl); 
    exit;
}
?>
<style>
label{ font-weight:bold; font-size: 15px;}
.con { font-size: 15px;} 
.col-sm-3{margin-bottom: 10px;}
.nav > li {
	background: #dadada3b;
	border-radius: 2px 2px 0 0;
}
.active.show {
	color: #000;
	font-weight: bolder;
}
legend {
	margin: 0 0 15px 0;
	font-size: 18px;
}
</style>
 
 <script type="text/javascript">
	 var menuid='<?=$menuid?>';
 	$(function(){
		$("#project_id").change(function () {
			if(this.value==''){return false;}
			var URL=BASEURL+'manageproject/projects/tasks?securekey='+menuid+'&key='+this.value;
			 window.location.href = URL;  
  	    }); 
  });
</script>
<?= $this->render('projectlist', ['menuid'=>$menuid]); ?>
  
  <?php $form = ActiveForm::begin();
$depts = Yii::$app->utility->get_dept(NULL);
$depts = ArrayHelper::map($depts, 'dept_id', 'dept_name');
?>
<div class="col-sm-12 form-group form-control">
 <div class="row">
	 <div class="col-sm-3">
<?= $form->field($model, 'task_type')->dropDownList([ 'Development' => 'Development', 'Testing' => 'Testing', 'Document' => 'Documentation', 'Staging' => 'Staging', 'Production' => 'Production', ], ['prompt' => 'select']) ?>
	 </div>
	   <div class="col-sm-9">
    <?= $form->field($model, 'task_name')->textInput() ?>
 </div>
	 <div class="col-sm-12">
    <?= $form->field($model, 'task_description')->textInput() ?>
 </div>
  
	 <div class="col-sm-4">
    <?= $form->field($model, 'start_date')->textInput(['class'=>'projectlist-start_date']) ?>
 </div>
	 <div class="col-sm-4">
    <?= $form->field($model, 'task_end_date_fla')->textInput(['class'=>'projectlist-start_date']) ?>
 </div>
 
  <div class="col-sm-4">
<?= $form->field($model, 'priority')->dropDownList([ 'High' => 'High', 'Medium' => 'Medium', 'Low' => 'Low', ], ['prompt' => 'select']) ?>
	 </div>
	 <div class="col-sm-4"><?= $form->field($model, 'dept_id')->dropDownList($depts, ['prompt' => 'Select Department', 'class'=>'js-example-basic-multiple form-control form-control-sm projectlist-manager_dept'])->label('Department'); ?></div>
    
	 <div class="col-sm-4">
 		 <?=$form->field($model,'assigned_to')->dropDownList(['' => '-- Select --'],['class'=>'assigned_to js-example-basic-multiple form-control form-control-sm projectlist-contact_person'])?>
 		 <?=$form->field($model,'assigned_to_name')->hiddenInput()->label(false); ?>
 </div> 
	 <div class="col-sm-12">
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>
    </div>
	 <?php ActiveForm::end(); ?>
	 <script>
	$(document).ready(function(){
		 $('#prprojecttasks-assigned_to').change(function(){
     		 $('#prprojecttasks-assigned_to_name').val($( ".assigned_to option:selected" ).text());
          
    	});
	});
	 </script>
    </div>
    </div>
 
 
