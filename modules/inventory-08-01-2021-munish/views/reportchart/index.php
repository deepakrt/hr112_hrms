  <!------ Include the above in your HEAD tag ---------->
  <!------ Include the above in your HEAD tag ---------->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
<?php
$this->title= 'Manage Report';
$menuid = "";
if(isset($_GET['securekey']) AND !empty($_GET['securekey'])){
    $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
    if(empty($menuid)){
        header('Location: '.Yii::$app->homeUrl); 
        exit;
    }
    $menuid = Yii::$app->utility->encryptString($menuid);
}
$groups=Yii::$app->inventory->get_groups();
$category=Yii::$app->inventory->get_category();
?>
<div class="container">
<fieldset>
  <form action="" method="post">
     <div class='row'>			 
		<div class="form-group col-sm-4">
		  <label class="control-label">Select Group </label>
		   <select id="group_name" class="js-example-basic-multiple  form-control form-control-sm" name="group_name">
				<?php foreach($groups as $r){ 
					$sel="";
					if(isset($_POST['group_name'])){
					if($_POST['group_name']==$r['CLASSIFICATION_CODE']){$sel="selected='selected'";}}
					echo '<option '.$sel.' value="'.$r['CLASSIFICATION_CODE'].'">'.$r['CLASSIFICATION_NAME'].'</option>';
				 } ?>
			</select> 
		</div>

		<div class="form-group col-sm-4">
		  <label class="control-label">Select Category </label>
		   <select id="cat_name" class="js-example-basic-multiple  form-control form-control-sm" name="cat_name">
				<option value="">-- Select --</option>
				<?php foreach($category as $r){ 
					$sel="";
					if(isset($_POST['cat_name'])){if($_POST['cat_name']==$r['ITEM_CAT_CODE']){$sel="selected='selected'";}}
					echo '<option '.$sel.' value="'.$r['ITEM_CAT_CODE'].'">'.$r['ITEM_CAT_NAME'].'</option>';
				 } ?>
			</select> 
		</div>
		 
		 <div class="form-group col-sm-4">
		  <label class="control-label">Select Item </label>
		   <select id="cat_items" class="js-example-basic-multiple  form-control form-control-sm bn"  name="item_code">
			<!-----------   Data come from reportcontroller     -------------------->
 		  </select> 
		</div>

	</div>
</form>
</fieldset>	
</div>
<div class="col-sm-12">
   <!----------- Chart Area ------------------>
</div>
<script>
$(document).ready(function() {
    $('table.display').dataTable();
} );
var BASE_URL='<?=Yii::$app->homeUrl?>';
var securekey='<?=$menuid?>';
$(function(){

  $('#cat_name').change(function(){
                var ccode= $('#group_name').val();
		var cat_id= $(this).val();
                //alert(ccode + ' , ' + cat_id);
		$.ajax({
			url:BASE_URL+'inventory/reportchart/get_cat_code?securekey='+securekey,
			type:'POST',
			data:{cat_id:cat_id,ccode:ccode},
			datatype:'json',
			success:function(data){
                                //alert(data);
				$('#cat_items').html(data);
			}
	    });
	}); 

  $('#cat_items').on('change',function(){
    
      var ccode= $('#group_name').val();
      var cat_id= $('#cat_name').val();
      var cat_items = $(this).val();
      //alert(ccode + ' , ' + cat_id  + ' , ' + cat_items);
	     $.ajax({
		url:BASE_URL+'inventory/item/viewchart?securekey='+securekey,
		type:'POST',
		data:{cat_id:cat_id,ccode:ccode},
		datatype:'json',
		success:function(data){
			
		   }
		 });

    }); 
}); 
</script>
