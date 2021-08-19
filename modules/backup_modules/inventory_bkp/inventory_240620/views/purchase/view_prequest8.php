  <!------ Include the above in your HEAD tag ---------->
<?php
 use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
 //echo "<pre>";print_r($data);die;
?>
 <style>
	    fieldset 
	{
		border: 1px solid #ddd !important;
		margin: 0;
		min-width: 0;
		padding: 10px;       
		position: relative;
		border-radius:4px;
		background-color:#fef7f7;
		padding-left:10px!important;
	}	
	
		legend
		{
			font-size:14px;
			font-weight:bold;
			margin-bottom: 0px; 
			width: 35%; 
			border: 1px solid #ddd;
			border-radius: 4px; 
			padding: 5px 5px 5px 10px; 
			background-color: #f2c3a9;
		}
</style>
<form action="" id="reqform">
	 <fieldset><legend>Items:</legend>	
     <table id="table" class="display" cellpadding="2" style="width:100%">
        <thead>
            <tr>
                 <th>#</th>
                 <th>Item Name</th>
                 <th>Item (Req Qty)</th>
                <th>Available</th>
				<th>Qty Available</th>
             </tr>
        </thead>
        <tbody>
            <?php 
 				$check="";
            foreach($data as $k=>$c){ ?>
            <tr>
				
                <td><?=$k+1;?></td>
                <td><?=$c['item_name'];?></td>
                <td><?=$c['quantity_required']?></td>
                <td>
					<input type="checkbox" class="checkqtyb checkqtyb_<?=$c['item_id']?>" id="<?=$c['item_id']?>" name="item_chk[]" value='1'/>
					<input type="hidden" id="QtyR_<?=$c['item_id']?>" name="QtyR[]" value='<?=$c['quantity_required']?>'/>
					<input type="hidden" id="req_id_<?=$c['item_id']?>" class="req_id" name="req_id[]" value='<?=$c['req_id']?>'/>
					<input type="hidden" id="item_id_<?=$c['item_id']?>" name="item_id[]" value='<?=$c['item_id']?>'/>
				</td>
				<td id="">
					<input type="text" style="width: 87px;margin-right: 28px;" class="checkqty" id="avail_qty_<?=$c['item_id']?>" readonly name="avail_qty[]" value='0' />
					<!--input type="button" style="cursor:not-allowed;" disabled class="btn-info update update_<?=$c['item_id']?>" id="<?=$c['item_id']?>" value='Update' /-->
					<br><span class="msj_<?=$c['item_id']?>"></span>
				</td>
				 
              </tr>   
             <?php }   ?>
        </tbody>
    </table>
		 <div class="form-group col-sm-12" style="text-align: center;margin: 16px 0 0 172px;">
		<label>
        	<button type="button" class="approve btn btn-success">Submit</button>        	
        	<button type="button"  class="reject btn btn-danger">Reject</button>        	
		</label>
    </div>
		  </fieldset>
		 </form>
 
<script>
var BASE_URL='<?=Yii::$app->homeUrl?>';
var securekey='<?=$menuid?>';
$(function(){
 		 
 		 $('.checkqtyb').on('click', function () {
				 var id=$(this).attr('id');
			if($(this).is(":checked")) {
				$('#avail_qty_'+id).val('');
				$('#avail_qty_'+id).removeAttr('readonly',false);
				//$('.update_'+id).prop('disabled',false);
				//$('.update_'+id).css('cursor','pointer');
  			}else{
				$('#avail_qty_'+id).val(0);
				$('#avail_qty_'+id).attr('readonly',true);
				//$('.update_'+id).prop('disabled',true);
				//$('.update_'+id).css('cursor','not-allowed');
			}
		 });
		
	 $('.reject').on('click', function () {   
			swal({
				  title: "Are you sure?",
				  text: "",
				  icon: "warning",
				  buttons: [
					'No, cancel it!',
					'Yes, I am sure!'
				  ],
				  dangerMode: true,
				}).then(function(isConfirm) {
				  if (isConfirm) {
				  var req_id=$(".req_id").val();
					var csrftoken=$("#_csrf").val();
   				  $.ajax({
						url:BASE_URL+'inventory/purchase/update_pur_req?securekey='+securekey,
						type:'POST',
						data:{rejected:1,req_id:req_id,_csrf:csrftoken},
						datatype:'json',
						success:function(data){
							swal("Updated Successfully! ", "", "success")
							.then((value) => {
							  window.location.reload(); 
							});
 						}
				});
				 }  
				});
			 });
 		 $('.approve').on('click', function () {      
			 
 			 var csrftoken=$("#_csrf").val();
 			 if($('.checkqty').val()==''){
					swal("Please Enter Available Quantity!", "", "warning"); 
					return false;
					}
  				  $.ajax({
						url:BASE_URL+'inventory/purchase/update_pur_req?securekey='+securekey,
						type:'POST',
						data:{data:$("#reqform").serialize(),_csrf:csrftoken},
						datatype:'json',
						success:function(data){
							swal("Updated Successfully! ", "", "success")
							.then((value) => {
							  window.location.reload(); 
							});
 						}
				});
			 });
	}); 
	
function validate(form) {

     if(!confirm('Do you really want to submit the form?')){
		 return false;
	 }
     
}
</script>
 
 
                   
               
