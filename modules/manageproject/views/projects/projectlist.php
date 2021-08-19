<div class="col-sm-12 form-group form-control">
	<?php  
	$dept_id=Yii::$app->user->identity->dept_id;
	if(Yii::$app->user->identity->role==6){$dept_id=NULL;}
    $projects = Yii::$app->projects->pr_get_projects($dept_id,NULL);   ?>
    <div class="row" style="width: 100%;">
		<div class="col-sm-1" style="padding: 5px 0 0 12px;"><b>Project:</b></div>
			<div class="col-sm-11">
				 <select style="width:104%" class="form-control js-example-basic-multiple" id="project_id" name="project_id">
					 <option value="">--Select Project--</option>
					 <?php  
					 foreach($projects as $emp){ ?> 
					 <option <?php if(Yii::$app->session->get('projects_id')==$emp['project_id']){echo "selected";}?> value="<?=Yii::$app->utility->encryptString($emp['project_id'])?>"><?=$emp['project_name']?></option>
					 <?php } ?>
				 </select>
			 </div>
		 </div>
</div>
<script>
var menuid='<?=$menuid?>';
var action='<?=Yii::$app->controller->action->id?>';
$(function(){
	$("#project_id").change(function () {
		var URL=BASEURL+'manageproject/projects/'+action+'?securekey='+menuid+'&key='+this.value;
		 window.location.href = URL; 
	}); 
});
</script>