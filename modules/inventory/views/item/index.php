  <!------ Include the above in your HEAD tag ---------->
  <!------ Include the above in your HEAD tag ---------->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
<style>
.modal-lg
{
	/*width: 900px !important;*/
	max-width: 1024px !important;
}
</style>
<?php
$this->title= 'Manage Items';
//$lists = Yii::$app->inventory->get_cat_item(0,0); //Inventoryutility
//$groups=Yii::$app->inventory->get_groups();
//$category=Yii::$app->inventory->get_category();

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
<a style="float: right;padding: 6px;margin: 0 10px 0 20px;" href="<?=Yii::$app->homeUrl?>inventory/item/add?securekey=<?=$menuid?>" class="btn btn-success btn-sm mybtn">Add New Item</a> 
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
		  <!-- <select onchange="form.submit()" id="cat_name" class="js-example-basic-multiple  form-control form-control-sm" name="cat_name">-->
                      <select id="cat_name" class="js-example-basic-multiple  form-control form-control-sm" name="cat_name">
				<option value="">-- Select --</option>
				<?php foreach($category as $r){ 
					$sel="";
					if(isset($_POST['cat_name'])){
					if($_POST['cat_name']==$r['ITEM_CAT_CODE']){$sel="selected='selected'";}}
					echo '<option '.$sel.' value="'.$r['ITEM_CAT_CODE'].'">'.$r['ITEM_CAT_NAME'].'</option>';
				 } ?>
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
      <th>Code</th>
			<th>Item Name</th>
			<th>Type</th>
      <th>Measuring Unit</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
	<?php 
		if(!empty($lists))
		{
			$i =1;
			foreach($lists as $l)
			{
				$encry = base64_encode($l['ITEM_CODE']);
				$ITEM_CODE =$l['ITEM_CODE'];
				$item_name = $l['item_name'];
				$Item_type = $l['Item_type']; 
				$type_id = $l['Type_id'];
        $CLASSIFICATION_NAME = $l['CLASSIFICATION_NAME'];
        $Measuring_Unit = $l['Measuring_Unit'];
        $ITEM_CAT_NAME = $l['ITEM_CAT_NAME'];
				$viewUrl = Yii::$app->homeUrl."inventory/item/view?securekey=$menuid&empid=$encry";
				$editUrl = Yii::$app->homeUrl."inventory/item/update?securekey=$menuid&empid=$encry";
			?>
			<tr>
				<td><?=$i?></td>
        <td id="clf_name<?=$ITEM_CODE;?>"><?=$CLASSIFICATION_NAME?></td>
        <td id="itmc_name<?=$ITEM_CODE;?>"><?=$ITEM_CAT_NAME?></td>
				<td id="itc_code<?=$ITEM_CODE;?>"><?=$ITEM_CODE?></td>
				<td id="itc_name<?=$ITEM_CODE;?>"><?=$item_name?></td>
				<td><?=$Item_type?></td>			
				<td><?=$Measuring_Unit?></td>                        
 				<td style="padding: 0;">
				 	<button type="button" class="btn btn-primary" data-toggle="modal" data-target=".exampleModalCenter" onclick="getItemDetails('<?=$type_id?>','<?=$ITEM_CODE?>');">View</button>
         	<!-- <span onclick="getItemDetails('<?=$type_id?>','<?=$ITEM_CODE?>')" class="btn btn-success btn-sm btn-xs">View</span> -->
         	<!-- <a href="<?=$viewUrl?>" class="btn btn-success btn-sm btn-xs">View</a> -->
         	<!-- <a href="<?=$editUrl?>" class="btn btn-info btn-sm btn-xs">Edit</a> -->
      	</td> 
			</tr>	
		<?php 
				$i++;	
			}
		}
	?>
	</tbody>
	<tfoot>
      <th>Sr.</th>			
			<th>Classification Name</th>			
      <th>Category</th>
      <th>Code</th>
			<th>Name</th>
			<th>Type</th>
      <th>Measuring Unit</th>
			<th>Action</th>
	</tfoot>
</table>
</div>


<!-- Modal -->
<div class="modal fade exampleModalCenter" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalLongTitle"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="modalDataDivDisp">       
      </div>
    </div>
  </div>
</div>
<script>

	$(document).ready(function() {
	    $('table.display').dataTable();
	} );
	var BASE_URL='<?=Yii::$app->homeUrl?>';
	var securekey='<?=$menuid?>';
	$(function(){
	  $('#cat_name').on('change',function(){
	    
	      var ccode= $('#group_name').val();
	      var cat_id= $(this).val();
	      //alert(ccode + ' , ' + cat_id);
	     $.ajax({
					url:BASE_URL+'inventory/item/viewfilter?securekey='+securekey,
					type:'POST',
					data:{cat_id:cat_id,ccode:ccode},
					datatype:'json',
					success:function(data){
						if(data!=0)
						{
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

	function getItemDetails(itmtype_id,itemcode)
	{
	    $('#modalDataDivDisp').html('');
	    if(itmtype_id != '' && itemcode != '')
	    {

	        var clf_name = $('#clf_name'+itemcode).html();
	        var itmc_name = $('#itmc_name'+itemcode).html();
	        var itc_name = $('#itc_name'+itemcode).html(); // itc_code21070001
	        var itc_code = $('#itc_code'+itemcode).html(); // itc_code21070001

	        $('#modalLongTitle').html('');
	        $('#modalLongTitle').html('<b>Classification Name:</b> '+clf_name+' </br> <b>Item Category:</b> '+itmc_name+' </br> <b>Item Code:</b> '+itc_code+' </br> <b>Item Name:</b> '+itc_name );

		        startLoader();
	         $.ajax({
	            url: "<?php echo Yii::$app->homeUrl."inventory/item/get_item_detail_rec?securekey=$menuid";?>",
	            type: 'POST',
	            data: { itmtype_id:itmtype_id,itemcode:itemcode},
	            dataType: 'JSON',
	            success: function (data) 
	            {
	                $('#modalDataDivDisp').html(data.result);
	                stopLoader();
	            }
	        });
	    }
	}    

	function startLoader()
	{
	   $("#loading").show();
	}
	 
	function stopLoader()
	{
	    $("#loading").hide();
	}
</script>
