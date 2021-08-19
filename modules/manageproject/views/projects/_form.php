<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
$label= $model->attributeLabels();
$sd = $ed = "";
//if(empty($model->project_id)){
    $sd = "projectlist-start_date";
    $ed = "projectlist-end_date";
//}

$depts = Yii::$app->utility->get_dept(NULL);
$depts = ArrayHelper::map($depts, 'dept_id', 'dept_name');
$manager_emp_id = "";
if(!empty($model->manager_emp_id)){
    $manager_emp_id = Yii::$app->utility->encryptString($model->manager_emp_id);
}
?><link href="<?=Yii::$app->homeUrl?>css/select2.min.css" rel="stylesheet" />
<script src="<?=Yii::$app->homeUrl?>js/select2.min.js"></script>
<input type="hidden" id="menuid" value="<?=$menuid?>" readonly="" />
<input type="hidden" id="param_manager_emp_id" value="<?=$manager_emp_id?>" readonly="" />
<style>
.row.addmorerow {
	width: 90%;
	margin-top: 5px;
}
.cost_category {
	padding: 0 !important;
}
legend {
	background-color: #f4f4f400;
	color: #00110D;
	font-family: initial;
	font-size: 16px;
}
</style>
	 
<?php if(isset($_GET['view'])) {?>
<script>
  $(document).ready(function(){
        $("#w0 :input").prop("disabled", true);
		
    });
</script>
	<div class="col-sm-12 form-control">
<fieldset>
<legend class="">Project Information  </legend>
 <?php  } $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
<div class="row"> 
 <input type="hidden" class="form-control form-control-sm" id="project_id" name="ProjectList[key_encript]" value="<?=Yii::$app->utility->encryptString($model->project_id);?>" readonly="">
	
	<div class="col-sm-6">
		<?= $form->field($model, 'proposal_no')->textInput(['placeholder'=>$model->getAttributeLabel('proposal_no'), 'class'=>'form-control form-control-sm', 'maxlength' => true])->label($label['proposal_no'].' (C-D\AC(M)/STD/xxxx/xxxx/xxx)') ?>
	</div>
		<div class="col-sm-6">
        <?= $form->field($model, 'projectrefno')->textInput(['placeholder'=>$model->getAttributeLabel('projectrefno'), 'class'=>'form-control form-control-sm', 'maxlength' => true])->label($label['projectrefno'].' (C-DAC(M)/xxxx/xxx/000)') ?>
    </div> 
	<div class="col-sm-4">
        <?= $form->field($model, 'proposal_submission_date')->textInput(['placeholder'=>$model->getAttributeLabel('proposal_submission_date'),'readonly'=>true, 'class'=>'form-control form-control-sm date_picker']) ?>        
    </div>

	<div class="col-sm-4">
        <?= $form->field($model, 'order_num')->textInput([ 'placeholder'=>$model->getAttributeLabel('order_num'), 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?>
    </div>
	<div class="col-sm-4">
        <?= $form->field($model, 'filenumber')->textInput(['placeholder'=>$model->getAttributeLabel('filenumber'), 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?>
    </div> 
    <div class="col-sm-8"><?= $form->field($model, 'project_name')->textInput(['class'=>'form-control form-control-sm', 'maxlength' => true, 'placeholder'=>$label['project_name'], 'title'=>$label['project_name'], 'autocomplete'=>'off' ]) ?></div>
    <div class="col-sm-4"><?= $form->field($model, 'short_name')->textInput(['class'=>'form-control form-control-sm', 'maxlength' => true, 'placeholder'=>$label['short_name'], 'title'=>$label['short_name'], 'autocomplete'=>'off' ]) ?></div>
    <div class="col-sm-4"><?= $form->field($model, 'project_type')->dropDownList([ 'Business' => 'Business', 'Funded' => 'Funded', 'Mission' => 'Mission', ], ['prompt' => 'Select Project Type', 'class'=>'form-control form-control-sm', 'title'=>$label['short_name']]) ?></div>
     <div class="col-sm-4"><?= $form->field($model, 'manager_dept')->dropDownList($depts, ['prompt' => 'Select Department', 'class'=>'js-example-basic-multiple form-control form-control-sm projectlist-manager_dept', 'title'=>$label['manager_dept']]) ?></div>
    <div class="col-sm-4">
	<?=$form->field($model,'contact_person')->dropDownList(['' => '--Select--'],['class'=>'js-example-basic-multiple form-control form-control-sm projectlist-contact_person'])->label('Employee')?>
	 </div>
      
     <div class="col-sm-4"><?= $form->field($model, 'start_date')->textInput(['class'=>'form-control form-control-sm '.$sd, 'maxlength' => true, 'placeholder'=>$label['start_date'],'readonly'=>true, 'title'=>$label['start_date'], 'autocomplete'=>'off' ]) ?></div>
    <div class="col-sm-4"><?= $form->field($model, 'end_date')->textInput(['class'=>'form-control form-control-sm '.$ed, 'maxlength' => true, 'placeholder'=>$label['end_date'], 'readonly'=>true, 'title'=>$label['end_date'], 'autocomplete'=>'off' ]) ?></div>
	<div class="col-sm-4"><?= $form->field($model, 'project_cost')->textInput(['class'=>'form-control form-control-sm', 'maxlength' => true, 'placeholder'=>$label['project_cost'], 'title'=>$label['project_cost'], 'autocomplete'=>'off', 'onkeypress'=>'return allowOnlyNumber(event)' ]) ?></div>
	   
	 <div class="col-sm-4">        
        <?= $form->field($model, 'funding_agency')->textInput(['class'=>'form-control form-control-sm', 'title'=>$label['funding_agency'], 'placeholder'=>$label['funding_agency']]) ?>
    </div> 
	
     <?php 
	$project_id=$model->project_id;
	$added_by = Yii::$app->user->identity->e_id;
	$pr_cats = Yii::$app->projects->get_pr_cat();
	$allres = Yii::$app->projects->get_pur_fund($project_id, $added_by);
	?>
     
	 
    <div class="col-sm-8">
	<?= $form->field($model, 'address')->textInput(['class'=>'form-control form-control-sm', 'title'=>$label['address'], 'placeholder'=>$label['address'] ]) ?>
	</div>
	<div class="col-sm-6">
        <?= $form->field($model, 'objectives')->textArea(['placeholder'=>$model->getAttributeLabel('objectives'), 'class'=>'form-control form-control-sm', 'maxlength' => true, 'rows' => 2]) ?>
    </div>
    <div class="col-sm-6">
        <?= $form->field($model, 'description')->textArea(['placeholder'=>$model->getAttributeLabel('description'), 'class'=>'form-control form-control-sm', 'maxlength' => true, 'rows' => 2]) ?>
    </div>
	
	 
    <?php 
    if(!empty($model->project_id)){?>
        <div class="col-sm-4"><?= $form->field($model, 'status')->dropDownList([ 'Started' => 'Started', 'InProcess' => 'InProcess', 'Completed' => 'Completed'], ['prompt' => 'Select Status', 'class'=>'form-control form-control-sm', 'title'=>$label['status']]) ?></div>
        <div class="col-sm-4"><?= $form->field($model, 'is_active')->dropDownList([ 'Y' => 'Yes', 'N' => 'No'], ['prompt' => 'Select Is Active', 'class'=>'form-control form-control-sm', 'title'=>$label['is_active']]) ?></div>
    <?php } ?>
     <div class="col-sm-12 text-center">
	 <?php if($this->context->action->id=='updateproject' || empty($model->project_id)) {?>
	 <?php if($this->context->action->id=='addnewproject') {?>
	  <input type="hidden" id="enterpcb" name="ProjectList[enterpcb]" readonly value="">
	   <?php } ?>
        <?= Html::submitButton($model->isNewRecord ? 'Submit' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm']) ?>
        <a href="<?=Yii::$app->homeUrl?>manageproject/projects?securekey=<?=$menuid?>"  class="btn btn-danger btn-sm">Cancel</a>
	 <?php } ?>
    </div>
</div>
	<?php ActiveForm::end(); ?>
	</fieldset></div>
 <hr>
<?php /*if($this->context->action->id=='addnewproject' && !empty($model->project_id)) { ?>
	<div class="col-sm-12 form-control">
<fieldset>
<legend class="">Project Cost Breakdown (Optional) </legend>
	 
	
	 <?php if(!empty($allres)){foreach($allres as $k=>$res){ ?>
	 <div class="row" style="width: 90%;">
    <div class="col-sm-3">
   <input type="text" disabled class="form-control form-control-sm" title="Project Fund" value="<?= date('d-m-Y', strtotime($res['date_from']));?>">
	 </div>
	<div class="col-sm-3">
    <input type="text" disabled class="form-control form-control-sm" title="Project Fund" value="<?= date('d-m-Y', strtotime($res['date_to']));?>">
	</div>
	<div class="col-sm-3">
	 <select disabled class="form-control form-control-sm cost_category" name="" id="">
	<?php foreach($pr_cats as $cat){
		$selected='';
		if($cat['id']==$res['fund_category']){$selected="selected='selected'";}
		?> 
	 <option <?=$selected;?> value="<?=$cat['id']?>"><?=$cat['name']?></option>
	<?php } ?>
     </select>
    
	</div>
	<div class="col-sm-3">
     <input type="text" disabled class="form-control form-control-sm" title="Project Fund" value="<?=$res['amount'];?>">
 	</div><span id='ssssss'>
	<?php 
	$curdateTime=strtotime(date('Y-m-d H:i:s'));
 	$endTime = strtotime("+1 day", strtotime($res['added_date']));
	
	if($curdateTime < $endTime){ ?>
	<button type="button" id="<?=$res['id_pf']?>" class="btn btn-danger add_btn1_p6" onclick="removethis(this)" style="margin: 2px -21px 0 -6px;float: right;height: 25px;padding: 0 4px 0 5px;">X</button> <?php } ?></span>
 	</div>
	 <?php }} ?>
     <div class="gggg">
    <div class="row0 row addmorerow" style="width: 90%;">
    <div class="col-sm-3">
     <input type="text" id="project_start_date" class="adddata form-control form-control-sm project_start_date" name="Project[start_date]" readonly="" title="Start Date" placeholder="Start Date" autocomplete="off" style="cursor: pointer;">
	 </div>
	<div class="col-sm-3">
     <input type="text" id="project_end_date" class="adddata form-control form-control-sm project_end_date" name="Project[end_date]" readonly="" title="End Date" placeholder="End Date" autocomplete="off" style="cursor: pointer;">
	</div>
	<div class="col-sm-3">
     <select class="adddata form-control form-control-sm cost_category" name="Project[cost_category]" id="cost_category">
	 <option value="">Select Category</option>
	  <?php foreach($pr_cats as $cat){ ?> 
	 <option value="<?=$cat['id']?>"><?=$cat['name']?></option>
	<?php } ?>
     </select>
	</div>
	<div class="col-sm-3">
     <input type="text" id="project_fund" class="adddata form-control form-control-sm project_fund" name="Project[project_fund]" title="Amount" placeholder="Amount" autocomplete="off">
 	</div>
	 <span id='ssssss0'></span>
 	</div>
	</div>
	<input style="float: right;margin: -30px 39px 7px;cursor: pointer;" type="button" id="addmore" class="primary btn-info" value="Add +">
	 
	<span style="color:red;"><b> &nbsp;&nbsp;&nbsp; Note: 1. Details once entred can't be Edited. <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 2. Deletion/Removal of entry(if any) is allowed up to 24 Hours.</b></span>
</div>
	<?php } */?>
 

<script>
var menuid='<?=$menuid?>';
var _csrf = $('#_csrf').val();
var pstart_date= '';
var pend_date= '';
	 
<?php if(!empty($model->manager_dept)){ ?>
//    $(document).ready(function(){
//        getProjectManager('<?=$model->manager_dept?>', '<?=$manager_emp_id?>');
//    });
  
	var dept_id='<?=$model->manager_dept?>';
	var emp_code= '<?=$model->contact_person?>';
	var pstart_date= '<?=$model->start_date?>';
	var pend_date= '<?=$model->end_date?>';
	$('#project_start_date').val(pstart_date);
 	   getdeptemp(dept_id,emp_code);
<?php }?>
	</script>
	
<?php	/*?> 
function removethis(dis){
	var id=$(dis).attr('id');
 	if(!confirm('Are You sure to delete?')){return false;}
	$.ajax({
		url:BASEURL+'manageproject/projects/del_project_cat?securekey='+menuid,
		type:'POST',
		data:{id:id,_csrf:_csrf},
		datatype:'json',
		success:function(data){
			if(data==1){
				$(dis).parent().parent().fadeOut(300).remove();
			}
		}
	  });
}
$(function(){
 $("#addmore").click(function () {
 		var project_id=$('#project_id').val();
		var start_date=$('#project_start_date').val();
        var end_date=$('#project_end_date').val();
        var pc_cat=$('#cost_category').val();
        var pc_cat_text=$('#cost_category :selected').text();
         var amount=$('#project_fund').val();
		 
		if(start_date==''){
			showError("Please select Project Start Date");return false;
		}
		if(end_date==''){
			showError("Please select Project End Date");return false;
		}
		if(pc_cat==''){
			showError("Please select Category");return false;
		}
		if(amount==''){
			showError("Please enter Project Fund");return false;
		}
		var intRegex = /^\d+$/;
		var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;

		var str = $('#myTextBox').val();
		if(intRegex.test(amount) || floatRegex.test(amount)) {
		   
		}else{
			alert('Invalid Amount');
			return false;
			}
    
	$.ajax({
		url:BASEURL+'manageproject/projects/add_project_cat?securekey='+menuid,
		type:'POST',
		data:{project_id:project_id,start_date:start_date,end_date:end_date,pc_cat:pc_cat,amount:amount,_csrf:_csrf},
		datatype:'json',
		success:function(data){
			if(data=='Invalid Request'){
				showError(data); 
				return false;
			}else{
 				html='<div class="row" style="width: 90%;"><div class="col-sm-3"><input type="text" disabled class="form-control form-control-sm" value="'+start_date+'"></div><div class="col-sm-3"><input type="text" disabled class="form-control form-control-sm"  value="'+end_date+'"></div><div class="col-sm-3"><select disabled class="form-control form-control-sm cost_category"><option value="'+pc_cat+'">'+pc_cat_text+'</option></select></div><div class="col-sm-3"><input type="text" disabled class="form-control form-control-sm" value="'+amount+'"></div><span id="ssssss"><button type="button" id="'+data+'" class="btn btn-danger add_btn1_p6" onclick="removethis(this)" style="margin: 2px -21px 0 -6px;float: right;height: 25px;padding: 0 4px 0 5px;">X</button></span></div>';
				$( html ).insertBefore( ".gggg" );
				$('#project_start_date').val(pstart_date);
				$('#project_end_date').val('');
				$('#cost_category').val('');
				$('#project_fund').val('');
   	 
	 }
		}
	  });
	 
  }); 
  });*/

