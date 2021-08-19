	<div class="col-sm-12 form-control">
<fieldset>
<legend class="">Project Technologies </legend>
  	 <?php 
  	$allres = Yii::$app->projects->get_project_technology();  ?>
     <div class="gggg">
    <div class="row0 row" style="width: 100%;">
    <div class="col-sm-12">
     <select style="width:104%" class="form-control js-example-basic-multiple tech_name" data-live-search="true"  id="tech_name" name="tech_name[]" multiple>
	 <option value="">--Select Technology--</option>
	<?php $allselected_tech=explode(",",$model->technology_used);
  	foreach($allres as $emp){ ?> 
	 <option <?= in_array($emp['id'],$allselected_tech) ? 'selected' : ''?> value="<?=$emp['id']?>"><?=$emp['technology']?></option>
	<?php } ?>
		 </select>
	 </div>
		<!--input style="float: right;margin: 5px 28px 0;cursor: pointer;" type="button" id="addtech" class="primary btn-info" value="Update"-->
	 </div>
	 </div>
	 
	 
	 
	 </fieldset>
</div>
<script>
	$(function(){
		$("#tech_name").change(function () {
			var tech_names = [];
		 	$. each($(".tech_name option:selected"), function(){
				tech_names. push($(this). val());
			});  
		 	$.ajax({
				url:BASEURL+'manageproject/projects/add_pro_tech?securekey='+menuid,
				type:'POST',
				data:{project_id:project_id,ids:tech_names,_csrf:_csrf},
				datatype:'json',
				success:function(data){
					
				}
		  });
 	  }); 
  });
 </script>