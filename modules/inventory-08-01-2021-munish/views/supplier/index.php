 <!------ Include the above in your HEAD tag ---------->
  <!------ Include the above in your HEAD tag ---------->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
<?php
$this->title= 'Manage Supplier';
//Inventoryutility
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
<div class="col-sm-12">
<div class="datashowhide">
<div class="col-sm-6 float-left">
<fieldset>
  <form action="" method="post">
     <div class='row'>			 
		<div class="form-group col-sm-12">
		  <label class="control-label">Select Category </label>
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
<div class="col-sm-6 float-right text-right">
<a style="margin-bottom:10px;" href="<?=Yii::$app->homeUrl?>inventory/supplier/add?securekey=<?=$menuid?>" class="btn btn-success btn-sm mybtn">Add New Item</a>	
</div>
</div>
<table id="dataTableShow" class="display" cellspacing="0">
	<thead>
		<tr>
			<th>Sr.</th>	
			<th>Supplier Code</th>
			<th>Supplier Name</th>
			<th>Supplier Address</th>
			<th>Phone No</th>
                        <th>Category</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		if(!empty($lists)){
			$i =1;
			foreach($lists as $l){ 
                        $encry = $l['Supplier_Code'];
			$Supplier_Code =$l['Supplier_Code'];
                        $Supplier_name =$l['Supplier_name'];
                        $Supplier_address =$l['Supplier_address'];
                        $Phone_no =$l['Phone_no'];
                        $ITEM_CAT_NAME =$l['ITEM_CAT_NAME'];
			$editUrl = Yii::$app->homeUrl."inventory/supplier/categorylink?securekey=$menuid&id=$encry";
			?>
		<tr>
			<td><?=$i?></td>
			<td><?=$Supplier_Code?></td>
			<td><?=$Supplier_name?></td>
			<td><?=$Supplier_address?></td>
			<td><?=$Phone_no?></td>
                        <td><?=$ITEM_CAT_NAME?></td>
			<td><button data-toggle="modal" data-target="#myModal3" class="btn btn-info btn-sm btn-xs getdetails" id="<?=$encry?>" style="color:#fff">Link to Category</button></td>
		</tr>	
		<?php $i++; }
		}
		?>
	</tbody>
	<tfoot>
                   <tr>
		        <th>Sr.</th>	
			<th>Supplier Code</th>
			<th>Supplier Name</th>
			<th>Supplier Address</th>
			<th>Phone No</th>
                        <th>Category</th>
			<th>Action</th>
                  </tr>
	</tfoot>
</table>

</div>

<div class="modal fade show" id="myModal3" role="dialog" style="padding-left: 10px;">
    <div class="modal-dialog" style="min-width: 60%;">   
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header" style="background: #DD7869;">
          <h4 class="modal-title">Link to Category</h4>
          <button type="button" class="close" data-dismiss="modal">×</button>
         
        </div>
        <div class="modal-body" style="border-bottom: 5px solid #DD7869;">
            
        </div>
         
      </div>
      
    </div>
</div>

<script>
$(document).ready(function() {
    $('table.display').dataTable();
    $('.hidedata').hide();
} );
var BASE_URL='<?=Yii::$app->homeUrl?>';
var securekey='<?=$menuid?>';
$(function(){
$('#cat_name').on('change',function(){
      var cat_id= $(this).val();
      //alert(ccode + ' , ' + cat_id);
     $.ajax({
	url:BASE_URL+'inventory/supplier/viewfilter?securekey='+securekey,
	type:'POST',
	data:{cat_id:cat_id},
	datatype:'json',
	success:function(data){
		if(data!=0){
                   
		 $('.dataTables_wrapper').html(data);   
                            ///////////////// default hide table filter options                               
                $('.dataTables_wrapper .datashowhide, .dataTables_length, .dataTables_filter, .dataTables_info, .dataTables_paginate').hide();
                                                                        
		}else{
			 alert('Category can not be blank.');
		}
	   }
	 });

    }); 

       //alert($("#q_id123").val())
  	$( "body" ).on( "click", ".getdetails", function() { 
             var id= $(this).attr('id');
              //alert(id);
		 	var csrftoken=$("#_csrf").val();
			 $.ajax({
				url:BASE_URL+'inventory/supplier/categorylink?securekey='+securekey,
				type:'POST',
				data:{id:id,_csrf:csrftoken},
				datatype:'json',
				success:function(data){
					 $(".modal-body").html(data);
				}
			});
        });
  
  }); 
</script>
