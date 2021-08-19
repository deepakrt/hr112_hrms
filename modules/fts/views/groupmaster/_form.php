<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\MasterDepartment;
/* @var $this yii\web\View */
/* @var $model app\models\FtsGroupMaster */
/* @var $form yii\widgets\ActiveForm */

$alldepts=Yii::$app->fts_utility->fts_getdept();
$alldeptss=ArrayHelper::map($alldepts,'dept_id','dept_name');

/* $allemployees=Yii::$app->utility->get_employees();
 

foreach($allemployees as $k=>$d){
	$data[$k]['e_id']=$d['e_id'];
	$data[$k]['name']=$d['fname'].' '.$d['lname'].'('.$d['employee_code'].')';
} */
 ?>
 <style>
 .btn.active.btn-info.btn-sm.lftlink {
    background: #47c2a6 none repeat scroll 0 0 !important;
}
 .navbar {margin-bottom:0 !important;}
 
     .leftside{
         height: 325px;
     }
    body {
    
    font-family: "Roboto",sans-serif !important;
    
}
.nav-link.mylink.active {
    background: #edb3aa none repeat scroll 0 0;
    font-weight: bold;
    height: 50px;
    padding: 14px 15px 8px 16px !important;
    margin-right: 17px !important;
}
.mainmenu .nav-link {
  /* padding: 13px 30px 3px 3px !important;*/
	 padding: 11px 23px 19px 3px !important;
}
     .collapse.navbar-collapse {
    margin-left: -23px;
}
     .navbar-nav.mr-auto form {
    margin-top: -3px;
}
     .lftlink {
    background: #3f9e89 none repeat scroll 0 0 !important;
    display: block !important;
    margin-bottom: 5px !important;
    text-align: left !important;
         font-size: 14px !important;
}

     hr {
         border: 1px solid #f2d9a7 !important;
         margin-bottom: 5px !important;
         margin-top: 3px !important;
         width: 100% !important;
         margin: 181px 0 0 -237px !important;
         width: 258px !important;
     }
     
</style>
<link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
	
<div class="fts-group-master-form">

    <?php $form = ActiveForm::begin(); 
	 $members=array(''=>'-- Select --');
	  
	if(isset($_POST['FtsGroupMaster']['departments'])){
		$model->departments=$_POST['FtsGroupMaster']['departments'];
	}
	if(isset($_POST['FtsGroupMaster']['members'])){
		$model->members=$_POST['FtsGroupMaster']['members'];
	}
	 
	//echo "<pre>";print_r($model);die;
	?>

    <?= $form->field($model, 'group_name', ['options' => ['class' => 'col-sm-6 col-xs-12']])->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'group_description', ['options' => ['class' => 'col-sm-6 col-xs-12']])->textInput(['maxlength' => true]) ?>
	 
	<?=$form->field($model,'departments', ['options' => ['class' => 'col-sm-6 col-xs-12']])->dropDownList($alldeptss,['multiple'=>'multiple','class'=>'selectpicker show-tick form-control','data-live-search' => 'true','data-actions-box'=>'true'])->label('Select Departments <span class="mstar">*</span>')?>
	<?=$form->field($model,'members', ['options' => ['class' => 'col-sm-6 col-xs-12']])->dropDownList($members,['multiple'=>'multiple','class'=>'selectpicker show-tick form-control','data-live-search' => 'true','data-actions-box'=>'true'])->label('Select Members <span class="mstar">*</span>')?>
	 
	 
    <?= $form->field($model, 'created_by')->hiddenInput(['value'=> Yii::$app->user->identity->e_id])->label(false);  /* ?>
 		
    <?= $form->field($model, 'creation_date')->textInput()  

    <?= $form->field($model, 'last_modified_date')->textInput()*/ ?>

    <div class="form-group">
    <div class="col-sm-6 col-xs-12">
	 
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		 
		<button type="button" class="btn btn-info" onclick="window.history.go(-1); return false;" name="back" ><span class="">Back</span> </button> 
		 
    </div>
    </div>

    <?php ActiveForm::end(); ?>
 
 <script>
	$(document).ready(function () {
		var dept_ids='';
		<?php if(isset($model->departments)){ 
				$dept_ids=implode(",",$model->departments);?>
				var dept_ids='<?=$dept_ids?>';
		<?php } ?>
		var members='';
		<?php if(isset($model->members)){ 
				$members=implode(",",$model->members);?>
				members='<?=$members?>';
		<?php } ?>
		if(dept_ids){
			getempdata(dept_ids,members);
		}

		$("#ftsgroupmaster-departments").change(function(){
			getempdata($(this).val(),members);
 		});
	});
	
	function getempdata(depts,members){
		$.ajax({
			url: BASEURL+"fts/group/getdeptemployees", 
			data:'dept_ids='+depts+"&members="+members, 
			success: function(result){
 				$("select#ftsgroupmaster-members").html(result);
				$("select#ftsgroupmaster-members").selectpicker('refresh');
 			}
		});
	}
</script>
 	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
   <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
</div>
