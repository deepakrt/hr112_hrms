  <!------ Include the above in your HEAD tag ---------->
<?php
 use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
// echo "<pre>";print_r($data);die;
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
                 
                 <th>Item Name</th>
                 <th>Required Qty</th>
                <th>Purchase Status:</th>
				<th>Purchase Mode:</th>
             </tr>
        </thead>
        <tbody>
            <?php 
 				$check="";
              ?>
            <tr>
				
               
                <td width="30%"><?=$data['item_name'];?></td>
                <td width="20%"><?=$data['quantity_required']-$data['qty']?></td>
                <td width="25%">
			 
				<select required id="purc_status" class="form-control" name="purc_status">
					<option value="">--Select--</option>
					<?php if($data['ipurchase_status']=='Order-Declined'){ 
								echo '<option value="Initiated">Re-Ordered</option>';}
						  else{ 
					if($data['ipurchase_status']!='Initiated' && $data['ipurchase_status']!='Order-Placed'){	  ?>
					<option <?php if($data['ipurchase_status']=='Initiated' || $data['ipurchase_status']=='Re-Ordered') echo "selected"?> value="Initiated">Order-Initiated</option>
					<?php }    
					if($data['ipurchase_status']!='Order-Placed'){	  ?>
					<option <?php if($data['ipurchase_status']=='Order-Placed') echo "selected"?> value="Order-Placed">Order-Placed</option>
					<?php }   ?>
					<option <?php if($data['ipurchase_status']=='Completed') echo "selected"?> value="Completed">Order-Completed</option>
					<?php if($data['ipurchase_status']!=NULL){  ?>
					<option value="Order-Declined">Order-Declined</option>
					<?php } } ?>
				</select> 
				</td>
				<td width="25%">
				<?php if($data['ipurchase_status']=='Order-Declined' || $data['ipurchase_status']=='default'){ ?>
					 <select required id="ipurchase_mod" class="form-control" name="ipurchase_mod">
				 <option value="">--Select--</option>
 				 <option value="Gem">Gem</option>
 				 <option value="CPP">CPP</option>
 				 <option value="Local">Local</option>
 				 <option value="Online">Online</option>
 				 <option value="Email">Email</option>
            </select>
			<?php  }else{ ?>
				<input type="hidden" name="ipurchase_mod" value='<?=$data['ipurchase_mod'];?>'/>
			<?php	echo "<span style='margin: 9px 0 0 30px;'>".$data['ipurchase_mod']."</span>";} ?>
				</td>
				 </tr>  
				 <tr id="remarksrow" <?php if($data['ipurchase_status']=='Order-Declined' || $data['ipurchase_remarks']==NULL){ ?>style="display:none;" <?php } ?>  >
				<td>
				<input type="hidden" name="item_id" value='<?=$data['item_id'];?>'/>
				<input type="hidden" name="req_id" value='<?=$data['req_id'];?>'/>
				</td>
				<td colspan="3">
				<b>Specify reason for not purchasing the above item through Gem/CPP: </b>  
 				<input style="margin-top: 8px;" type="text" <?php if($data['ipurchase_remarks']!=NULL){ ?> disabled  <?php } ?> id="remarks" class="form-control" name="remarks" value='<?=$data['ipurchase_remarks']?>'/>
				</td>
				 
              </tr>   
              
        </tbody>
    </table>
		 <div class="form-group col-sm-12" style="text-align: center;margin: 16px 0 0 172px;">
		<label>
        	<button type="button" class="approve btn btn-success">Update</button>        	
        	<button type="button"  class="reject btn btn-danger">Reject</button>        	
		</label>
    </div>
		  </fieldset>
		 </form>
 
<script>
var BASE_URL='<?=Yii::$app->homeUrl?>';
var securekey='<?=$menuid?>';
$(function(){
 		 
 		 $('#ipurchase_mod').on('change', function () {
		 
		if($(this).val()!='Gem' && $(this).val()!='CPP'){
			$("#remarksrow").show();
			$("#remarks").val('');
			$("#remarks").attr('disabled',false);
		}else{
			$("#remarksrow").hide();
			$("#remarks").val('');$("#remarks").attr('disabled',true);
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
  			 if($('#purc_status').val()==''){
					swal("Please select purchase status!", "", "warning"); 
					return false;
				}
			if($('#ipurchase_mod').val()==''){
					swal("Please select purchase mode!", "", "warning"); 
					return false;
				}
			if(typeof $('#ipurchase_mod').val()!=='undefined'){
				if($('#ipurchase_mod').val()!='Gem' && $('#ipurchase_mod').val()!='CPP' && $('#remarks').val()==''){
					 swal(" Please Enter Remarks!", "", "warning"); 
					return false;
				}
			}
  				  $.ajax({
						url:BASE_URL+'inventory/purchase/update_item_pur_req?securekey='+securekey,
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
 
 
                   
               
