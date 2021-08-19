  <!------ Include the above in your HEAD tag ---------->
  <!------ Include the above in your HEAD tag ---------->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
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
<table id="dataTableShow" class="display tabledata" style="width:100%">
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
<div class="modal fade show" id="myModal3" role="dialog" style="padding-left: 10px;">
    <div class="modal-dialog" style="min-width: 60%;">
    
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header" style="background: #DD7869;">
          <h4 class="modal-title"></h4>
          <button type="button" class="close" data-dismiss="modal">×</button>
         
        </div>
        <div class="modal-body" style="border-bottom: 5px solid #DD7869;">
            
        </div>
         
      </div>
      
    </div>
  </div>
<script>
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
					if(data!=0){
						$('.modal-body').html(data);
					}else{
						 alert('Category can not be blank.');
					}
				}
			 });
      
		    }); 
		}); 
</script>
