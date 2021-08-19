	<div class="col-sm-12 form-control">
<fieldset>
<legend class="">Project Cost Breakdown </legend>
	 
	 <div class="row" style="width: 100%;margin: 0 0 -15px -15px;">
		<div class="col-sm-3">
			<label class="control-label" for="projectlist-date_from">Date From</label>
		</div>
		<div class="col-sm-3">
			<label class="control-label" for="projectlist-date_from">Date To</label>
		</div>
		<div class="col-sm-3">
			<label class="control-label" for="projectlist-category">Category</label>
		</div>
		<div class="col-sm-3">
			<label class="control-label" for="projectlist-project_cost">Project Cost</label>
		</div> 
  	</div>
	
	
	 <?php 
	$project_id=$model->project_id;
	$added_by = Yii::$app->user->identity->e_id;
	$pr_cats = Yii::$app->projects->get_pr_cat();
	$allres = Yii::$app->projects->get_pur_fund($project_id, $added_by);
	//if(!empty($allres)){
		foreach($allres as $k=>$res){ ?>
	 <div class="row" style="width: 100%;">
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
	 <?php }?>
     <div class="costbreakp">
    <div class="row0 row addmorerow" style="width: 100%;">
    <div class="col-sm-3">
     <input type="text" id="project_start_date" class="adddata form-control form-control-sm project_start_date" name="Project[start_date]" readonly="" title="Start Date" placeholder="" autocomplete="off" style="color:#e9ecef;cursor: pointer;">
	 </div>
	<div class="col-sm-3">
     <input type="text" id="project_end_date" disabled class="adddata form-control form-control-sm project_end_date" name="Project[end_date]" readonly="" title="End Date" placeholder="" autocomplete="off" style="cursor: pointer;">
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
	<input style="float: right;margin: -5px 28px 0;cursor: pointer;" type="button" id="addmore" class="primary btn-info" value="Add +">
	 
	<span style="color:red;"><b> &nbsp;&nbsp;&nbsp; Note: 1. Details once entred can't be Edited. <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 2. Deletion/Removal of entry(if any) is allowed up to 24 Hours.</b></span>
	<?php //} ?></fieldset>
</div>
<script>
var menuid='<?=$menuid?>';
var _csrf = $('#_csrf').val();
var pstart_date= '';
var pend_date= '';
<?php if(!empty($model->manager_dept)){ ?>
 
  
	var project_id='<?=Yii::$app->utility->encryptString($project_id)?>';
	//var dept_id='<?=$model->manager_dept?>';
	//var emp_code= '<?=$model->contact_person?>';
	var pstart_date= '<?=$model->start_date?>';
	var pend_date= '<?=$model->end_date?>';
	$('#project_start_date').val(pstart_date);
 	   //getdeptemp(dept_id,emp_code);
<?php }  ?> 
function removethis(dis){
	var id=$(dis).attr('id');
  	//if(!confirm('Are You sure to delete?')){return false;}
	swal({
				  title: "Are you sure?",
				  text: '',
 				  buttons: [
					'No, cancel it!',
					'Yes, I am sure!'
				  ],
				  dangerMode: false,
				}).then(function(isConfirm) {
			    if (isConfirm) {
 						 $.ajax({
							url:BASEURL+'manageproject/projects/del_project_cat?securekey='+menuid,
							type:'POST',
							data:{id:id,_csrf:_csrf},
							datatype:'json',
							success:function(data){
								if(data==1){
									$(dis).parent().parent().fadeOut(300).remove();
									swal("Deleted!", "", "success");
								}
							}
						  });
				} 
          });
  }
$(function(){
 $("#addmore").click(function () {
 		//var project_id=$('#project_id').val();
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
 				html='<div class="row" style="width: 100%;"><div class="col-sm-3"><input type="text" disabled class="form-control form-control-sm" value="'+start_date+'"></div><div class="col-sm-3"><input type="text" disabled class="form-control form-control-sm"  value="'+end_date+'"></div><div class="col-sm-3"><select disabled class="form-control form-control-sm cost_category"><option value="'+pc_cat+'">'+pc_cat_text+'</option></select></div><div class="col-sm-3"><input type="text" disabled class="form-control form-control-sm" value="'+amount+'"></div><span id="ssssss"><button type="button" id="'+data+'" class="btn btn-danger add_btn1_p6" onclick="removethis(this)" style="margin: 2px -21px 0 -6px;float: right;height: 25px;padding: 0 4px 0 5px;">X</button></span></div>';
				$( html ).insertBefore( ".costbreakp" );
				$('#project_start_date').val(pstart_date);$('#project_start_date').css('color','#e9ecef');
				$('#project_end_date').val('');$('#project_end_date').attr('disabled',true);
				$('#cost_category').val('');
				$('#project_fund').val('');
   	 
	 }
		}
	  });
	 
  }); 
  });
 </script>