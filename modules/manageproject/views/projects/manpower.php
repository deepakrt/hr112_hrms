	<div class="col-sm-12 form-control">
<fieldset>
<legend class="">Project Manpower Details </legend>
	 <div class="row" style="width: 100%;margin: 0 0 -15px -15px;">
		<div class="col-sm-3">
			<label class="control-label" for="projectlist-date_from">Employee Name</label>
		</div>
		<div class="col-sm-3">
			<label class="control-label" for="projectlist-date_from">Salary (%)</label>
		</div>
		<div class="col-sm-3">
			<label class="control-label" for="projectlist-category">Working As</label>
		</div>
		<div class="col-sm-3">
			<label class="control-label" for="projectlist-project_cost">Working On</label>
		</div> 
  	</div>
  	 <?php 
	$project_id=$model->project_id;
 	$allres = Yii::$app->projects->get_manpower($project_id);
	// if(!empty($allres)){
	foreach($allres as $k=>$res){ ?>
	 <div class="row" style="width: 100%;">
    <div class="col-sm-3">
     <input type="text" disabled class="form-control form-control-sm" title="Employee Name" value="<?=$res['emp_name'];?>">
	 </div>
	<div class="col-sm-3">
    <input type="text" disabled class="form-control form-control-sm" title="Salary" value="<?=$res['salary'];?>">
	</div>
	<div class="col-sm-3">
	  <input type="text" disabled class="form-control form-control-sm" title="Working As" value="<?=$res['working_as'];?>">
 	</div>
	<div class="col-sm-3">
     <input type="text" disabled class="form-control form-control-sm" title="Working On" value="<?=$res['working_on'];?>">
 	</div><span id='ssssss'>
	<?php 
	$curdateTime=strtotime(date('Y-m-d H:i:s'));
 	$endTime = strtotime("+1 day", strtotime($res['added_date']));
	
	if($curdateTime < $endTime){ ?>
	<button type="button" id="<?=$res['id']?>" class="btn btn-danger add_btn1_p6" onclick="removemp(this)" style="margin: 2px -21px 0 -6px;float: right;height: 25px;padding: 0 4px 0 5px;">X</button> <?php } ?></span>
 	</div>
	 <?php } ?>
     <div class="manpowerp">
    <div class="row0 row" style="width: 100%;">
    <div class="col-sm-3">
     <select style="width:100%" class="form-control form-control-sm emp_id js-example-basic-multiple" name="" id="emp_id">
	 <option value=""> - - Select - - </option>
	<?php 
	$dept_id=Yii::$app->user->identity->dept_id;
	$dept_emp=Yii::$app->inventory->get_dept_emp($dept_id);
	foreach($dept_emp as $emp){ ?> 
	 <option value="<?=$emp['employee_code']?>"><?=$emp['name']?></option>
	<?php } ?>
		 </select>
	 </div>
	<div class="col-sm-3">
     <input type="text" id="salary" class="form-control salary form-control-sm" name="Project[salary]" title="Salary %" placeholder="Salary %" autocomplete="off">
	</div>
	<div class="col-sm-3">
     <input type="text" id="post" class="form-control post form-control-sm" name="Project[post]" title="Working As" placeholder="Working As" autocomplete="off">
      
	</div>
	<div class="col-sm-3">
     <input type="text" id="work" class="form-control work form-control-sm" name="Project[work]" title="Working On" placeholder="Working On" autocomplete="off">
 	</div>
	 <span id='ssssss0'></span>
 	</div>
	</div>
	<input style="float: right;margin: -9px 28px 0;cursor: pointer;" type="button" id="addmapower" class="primary btn-info" value="Add +">
	 
	<span style="color:red;"><b> &nbsp;&nbsp;&nbsp; Note: 1. Details once entred can't be Edited. <br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 2. Deletion/Removal of entry(if any) is allowed up to 24 Hours.</b></span>
	<?php //} ?></fieldset>
</div>
<script>
var menuid='<?=$menuid?>';
var _csrf = $('#_csrf').val();
var project_id='<?=Yii::$app->utility->encryptString($project_id)?>';
    	   // getdeptemp(dept_id,emp_code);
 
function removemp(dis){
	var id=$(dis).attr('id');
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
				url:BASEURL+'manageproject/projects/del_manpower?securekey='+menuid,
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
 $("#addmapower").click(function () {
 		//var project_id=$('#project_id').val();
		var emp_id=$('#emp_id').val();
         var salary=$('#salary').val();
        //var pc_cat_text=$('#cost_category :selected').text();
        var empname=$('#emp_id :selected').text();
         var post=$('#post').val();
         var work=$('#work').val();
		 
		if(emp_id==''){
			showError("Please select Employee");return false;
		}
 		if(salary==''){
			showError("Please select Salary Percentage");return false;
		}
		if(post==''){
			showError("Please enter working As");return false;
		}
	 	if(work==''){
			showError("Please enter working On");return false;
		}
		var intRegex = /^\d+$/;
		var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;

		var str = $('#myTextBox').val();
		if(intRegex.test(salary) || floatRegex.test(salary)) {
		   
		}else{
			alert('Invalid salary percentage');
			return false;
			}
    
	$.ajax({
		url:BASEURL+'manageproject/projects/add_manpower?securekey='+menuid,
		type:'POST',
		data:{project_id:project_id,emp_id:emp_id,empname:empname,salary:salary,post:post,work:work,_csrf:_csrf},
		datatype:'json',
		success:function(data){
			if(data=='0'){
				showError(data); 
				return false;
			}else{
 				html='<div class="row" style="width: 100%;"><div class="col-sm-3"><input type="text" disabled class="form-control form-control-sm" value="'+empname+'"></div><div class="col-sm-3"><input type="text" disabled class="form-control form-control-sm"  value="'+salary+'"></div><div class="col-sm-3"><input type="text" disabled class="form-control form-control-sm"  value="'+post+'"></div><div class="col-sm-3"><input type="text" disabled class="form-control form-control-sm" value="'+work+'"></div><span id="ssssss"><button type="button" id="'+data+'" class="btn btn-danger add_btn1_p6" onclick="removemp(this)" style="margin: 2px -21px 0 -6px;float: right;height: 25px;padding: 0 4px 0 5px;">X</button></span></div>';
				$( html ).insertBefore( ".manpowerp" );
				$('#emp_id').val('');
				$('#emp_id').select2();
				$('#salary').val('');
				$('#post').val('');
				$('#work').val('');
   	 
	 }
		}
	  });
	 
  }); 
  });
 </script>