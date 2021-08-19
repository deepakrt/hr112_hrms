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

		<div class="form-group col-sm-5">
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
		 
		 <div class="form-group col-sm-5">
		  <label class="control-label">Select Item </label>
		   <select id="cat_items" class="js-example-basic-multiple form-control form-control-sm bn"  name="item_code">
				<option value="">-- Select --</option>
			   <?php 
	   			foreach($items as $item){ 
						$sel="";
						if(isset($_POST['item_code'])){ if($_POST['item_code']==$item['ITEM_CODE']){$sel="selected='selected'";} 
							echo '<option '.$sel.' value="'.$item['ITEM_CODE'].'">'.$item['item_name'].'</option>';
						}
					 }
				  ?>
 			</select> 
		</div>

	</div>
</form>
</fieldset>	
</div>
<div class="col-sm-12">
<table id="dataTablefilter" class="display tabledata" style="width:100%" cellspacing="0">
	<thead>
		<tr>
			<th>Sr.</th>			
			<th>Classification Name</th>			
      <th>Category</th>
      <th>Item Code</th>
			<th>Item Name</th>
			<th>Item Type</th>
      <th>Measuring Unit</th>
			<!--<th>Action</th>-->
		</tr>
	</thead>
	<tbody>
		<?php 
		if(!empty($lists)){
			$i =1;
			foreach($lists as $l){ 
                        $encry = base64_encode($l['ITEM_CODE']);
			$ITEM_CODE =$l['ITEM_CODE'];
			$item_name = $l['item_name'];
			$Item_type = $l['Item_type'];
                        $CLASSIFICATION_NAME = $l['CLASSIFICATION_NAME'];
                        $Measuring_Unit = $l['Measuring_Unit'];
                        $ITEM_CAT_NAME = $l['ITEM_CAT_NAME'];
			$viewUrl = Yii::$app->homeUrl."inventory/item/view?securekey=$menuid&empid=$encry";
			$editUrl = Yii::$app->homeUrl."inventory/item/update?securekey=$menuid&empid=$encry";
			?>
			<tr>
			<td><?=$i?></td>
                        <td><?=$CLASSIFICATION_NAME?></td>
                        <td><?=$ITEM_CAT_NAME?></td>
			<td><?=$ITEM_CODE?></td>
			<td><?=$item_name?></td>
			<td><?=$Item_type?></td>			
			<td><?=$Measuring_Unit?></td>                        
			 <!--<td style="padding: 0;">
                           <a href="<?=$viewUrl?>" class="btn btn-success btn-sm btn-xs">View</a>
                           <a href="<?=$editUrl?>" class="btn btn-info btn-sm btn-xs">Edit</a>
                        </td>--> 
			</tr>	
		<?php $i++;	}
		}
		?>
	</tbody>
	<tfoot>
		      <th>Sr.</th>			
			<th>Classification Name</th>			
                        <th>Category</th>
                        <th>Item Code</th>
			<th>Item Name</th>
			<th>Item Type</th>
                        <th>Measuring Unit</th>
			<!--<th>Action</th>-->
	</tfoot>
</table>
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
			datatype:'JSON',
			success:function(data){

				$('#cat_items').html(data);
			}
	    });
	});





  $('#cat_items').on('change',function(){
    
      var ccode= $('#group_name').val();
      var cat_id= $(this).val();
      //alert(ccode + ' , ' + cat_id);
	     $.ajax({
				url:BASE_URL+'inventory/item/viewfilter?securekey='+securekey,
				type:'POST',
				data:{cat_id:cat_id,ccode:ccode},
				datatype:'json',
				success:function(data){
					if(data!=0){
					 $('.dataTables_wrapper').html(data);   
			                            ///////////////// default hide table filter options                               
          	 $('.dataTables_wrapper .container, .dataTables_length, .dataTables_filter, .dataTables_info, .dataTables_paginate').hide();
			                                                                        
					}else{
						 alert('Category can not be blank.');
					}
			   }
		 });

    }); 
}); 
</script>
