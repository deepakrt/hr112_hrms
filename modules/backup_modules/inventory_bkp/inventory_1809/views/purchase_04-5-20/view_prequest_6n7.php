  <!------ Include the above in your HEAD tag ---------->
<?php
use yii\helpers\Html;

$role = Yii::$app->user->identity->role;
//echo "<pre>";print_r($maindata);die;

$check=$total_approx_cost=0;
$tabledata='';
//echo "<pre>";print_r($data); die;
foreach($data as $k=>$c){ 
$qty_buy=$c['quantity_required']-$c['qty'];
if($qty_buy>0){
$approx_cost=$qty_buy*$c['approx_cost'];
}else{
	$approx_cost=0;
}
$total_approx_cost=$total_approx_cost+$approx_cost;
$kk=$k+1;
$tabledata.="<tr><td>".$kk."</td><td>".$c['item_name']."</td><td>".$qty_buy."</td><td>".$approx_cost."</td><td width=30%>".substr($c['item_specification'],50)."</td><td>".$c['purpose']."</td></tr>";
              }   ?>
 <style>
 td span{font-family:initial;}
 </style>
<div class="col-sm-12" style="text-align:center;">
<h4>प्रगत संगणक विकास केंद्र</h4>
<h6>ए -३४  औद्योगिक क्षेत्र, फेज 8, मोहाली-160071 (चंडीगढ़), पंजाब, भारत</h6>
<h4>Center for Development of Advanced Computing</h4>
<h6>A-34 Industrial Area, Phase VIII, Mohali (Chandigarh)</h6>
	<?php if($total_approx_cost<25000){ ?>
<h6><b>REQUISITION FOR PURCHASE OF ITEM</b></h6>
	<?php }else{ ?>
<h6>मांग पत्र /<b>INDENT</b></h6>
	<?php } ?>
 </div>
<form action="" id="reqform" method="post" onsubmit="return false;" >
		<table id="table" class="display" cellpadding="2" style="width:100%;font-size: 14px;margin: 35px 0 20px 0;">
        <thead>
            <tr>
				<td><b>क्रमांक /Sr No:</b> <?=$maindata['voucher_no']?></td>
 				<td><b>परियोजना /Project:</b> <?php if($maindata['project']!=''){ echo $maindata['project'];}else{echo "N/A";}?></td>
 				<td><b>दिनांक /Date:</b> <?=date('d-m-Y',strtotime($maindata['request_date']));?></td>
				  
				 
             </tr>
        </thead>
        </table>
	 <fieldset> 
     <table id="table" class="display" cellpadding="2" style="width:100%;margin: 20px 0 40px 0;font-size: 14px;">
        <thead>
            <tr>
				<th>#</th>
				<th>Item Name</th>
				<th>Req Qty </th>
 				<th>Approx Cost</th>
				<th>Item Specification</th>
				<th>Purpose</th>
				<!--th>Available(Qty)</th-->
             </tr>
        </thead>
        <tbody>
            <?php echo $tabledata; ?>
        </tbody>
    </table> </fieldset>
	 <div class="form-group col-sm-12" style="width:100%;">
		<table id="table" class="display" cellpadding="2" style="font-size: 14px;margin: 10px;width:100%">
        <thead>
		<tr> <td Colspan="2"><b>Approximate Total Cost:</b><span> <?=$total_approx_cost?>/-</span></td> </tr>
		<tr> <td><b>In Words:</b> <span><?=Yii::$app->inventory->get_amount_in_words($total_approx_cost)?>.</span></td>
		<?php if($maindata['flag']=='9' || $maindata['flag']=='11'){ ?>
					<td align="left"> <b> Approved by StoreInc</td>
				<?php } ?>
		</tr>
		<?php $dept=" ( ".Yii::$app->inventory->get_empdept($maindata['emp_code'])." )";?>
		<tr>
			<td><b>Indented By:</b><span> <?=$maindata['fname'].$dept?></span></td>
			<?php if($maindata['flag']=='9' && $maindata['project']!=''){ ?>
			<td align="left"> <b> Approved by Finance Manager</td>
			<?php } ?>
			
		</tr>
		<tr>
			<td width="70%"><b>Recomended By (HOD):</b> 
			<span><?php echo Yii::$app->inventory->get_empname($maindata['HOD_ID']);?></span></td>
			<td align="left"> <b><?=$maindata['Status']?></b>
			<?php if($maindata['CH_remarks']!=''){ ?><b>Remarks: </b><?php echo $maindata['CH_remarks'];}  ?>
			<input type="hidden" id="req_id" name="req_id" value='<?=$maindata['id']?>'/></td>
		</tr>
		<?php if($maindata['project']){ ?>
            <tr>
				<td colspan="2"><b>Funds are available in Project </b>
				<span><?=$maindata['project']?></span>.</td>
				
				
			 </tr> 
		
			<tr>
				<td style="display: flex;">
				
				<b style="width: 24%;margin: 3px 0;">Project Heads:</b> 
				 <?php if($role!='6'){?> <span style="margin: 3px;"> <?=$maindata['project_head'];?> </span><?php }else{ ?>
				<input type="text" id="project_head" class="form-control" name="project_head" value=''/>
				 <?php } ?>
 				</td>
			</tr> <?php } ?><tr>
				<th> </th>
				 
             </tr>
			<?php if($role=='17'){ ?>	
			<tr>
				<td Colspan="2" style="display: flex;width: 127%;margin-top: 26px;">
				<input type="hidden" id="req_id" name="req_id" value='<?=$maindata['id']?>'/>
				<b style="width: 35%;margin: 9px 0;">Purchase Status:</b> 
 				<?php  if($maindata['purchase_status']!='Completed'){ ?>
				<select required id="purc_status" class="form-control" name="purc_status" style="width: 50%;">
					<option value="">--Select--</option>
					<?php if($maindata['purchase_status']=='Order-Declined'){ 
								echo '<option value="Initiated">Re-Ordered</option>';}
						  else{ 
					if($maindata['purchase_status']!='Initiated' && $maindata['purchase_status']!='Order-Placed'){	  ?>
					<option <?php if($maindata['purchase_status']=='Initiated' || $maindata['purchase_status']=='Re-Ordered') echo "selected"?> value="Initiated">Order-Initiated</option>
					<?php }    
					if($maindata['purchase_status']!='Order-Placed'){	  ?>
					<option <?php if($maindata['purchase_status']=='Order-Placed') echo "selected"?> value="Order-Placed">Order-Placed</option>
					<?php }   ?>
					<option <?php if($maindata['purchase_status']=='Completed') echo "selected"?> value="Completed">Order-Completed</option>
					<?php if($maindata['purchase_status']!='default'){  ?>
					<option value="Order-Declined">Order-Declined</option>
					<?php } } ?>
				</select>
				 <?php }else{echo "<span style='margin: 9px 30px 0 -50px;'>".$maindata['purchase_status']."</span>";} ?>
				 
				<b style="width: 36%;margin: 9px 0 0 30px;">Purchase Mode:</b> 
				<?php if($maindata['purchase_status']=='Order-Declined' || $maindata['purchase_status']=='default'){ ?>
				<select required id="purchase_mod" class="form-control" name="purchase_mod" style="width:50%;">
				 <option value="">--Select--</option>
				<?php //if($maindata['purchase_mod']!='Gem'){ ?>
				 <option value="Gem">Gem</option>
				<?php //} if($maindata['purchase_mod']!='CPP'){ ?>
				 <option value="CPP">CPP</option>
				<?php //} if($maindata['purchase_mod']!='Local'){ ?>
				 <option value="Local">Local</option>
				<?php //} if($maindata['purchase_mod']!='Online'){ ?>
				 <option value="Online">Online</option>
				<?php //} if($maindata['purchase_mod']!='Email'){ ?>
				 <option value="Email">Email</option>
            </select><?php  }else{ echo "<span style='margin: 9px 0 0 -60px;'>".$maindata['purchase_mod']."</span>";} ?>
			</td>
			</tr>
			 <?php }  ?>
			<tr id="remarksrow" 
			<?php if($maindata['purchase_remarks']==NULL){ ?>style="display:none;" <?php } ?>  >
			<td colspan="">
 				<b>Specify reason for not purchasing the above item through Gem/CPP: </b>  
 				<input style="margin-top: 8px;" type="text" <?php if($maindata['purchase_remarks']!=NULL){ ?> disabled  <?php } ?> id="remarks" class="form-control" name="remarks" value='<?=$maindata['purchase_remarks']?>'/>
  				</td>
			</tr>
				 
			<?php	if($role=='7' && $maindata['approved_by_CH']==NULL){ ?>		
				<tr>
				<td style="display: flex;">
				
				<b style="width: 38%;margin: 6px 0;">Remarks(Optional): &nbsp;&nbsp;</b> 
				 <?php if($maindata['CH_remarks']!=''){?> <span style="margin: 3px;"> <?=$maindata['CH_remarks'];?> </span><?php }else{ ?>
				 <input type="text" id="CH_remarks" class="form-control" name="CH_remarks" value=''/>
				 <?php } ?>
 				</td>
			</tr>
				<?php } ?>				
        </thead>
        </table> 
    </div>
		 <div class="form-group col-sm-12" style="text-align: center;">
			<label>
				<?php 
				if($maindata['purchase_status']!='Completed'){
				if($role=='7' && $maindata['approved_by_CH']==NULL){ ?>
					<button type="button" class="approve btn btn-success">Approve <?php //echo $role.$maindata['approved_by_CH']?></button>     
					<button type="button" class="reject btn btn-danger">Reject</button>    
				<?php }elseif($role=='6'){ ?>			
					<button type="button" class="process btn btn-success">Process</button>     
				<?php }elseif($role=='17'){ ?>			
					<button type="button" class="update btn btn-success">Update</button>     
				<?php }else{ //<button type="button" class="process btn btn-success">Forward</button>     
				} 
				
				if($role!='16' && $role!='17'){ ?>		
				<?php } }?>
			</label>
		</div>
		 
		 </form>
 
<script>
var BASE_URL='<?=Yii::$app->homeUrl?>';
var securekey='<?=$menuid?>';
$(function(){
	
	 $('#purchase_mod').on('change', function () {
		 
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
			  title: "Are you sure to reject?",
			  text: "",
			  icon: "warning",
			  buttons: [
				'No, cancel it!',
				'Yes, I am sure!'
			  ],
			  dangerMode: true,
			}).then(function(isConfirm) {
			  if (isConfirm) {
				var req_id=$("#req_id").val();
				 var csrftoken=$("#_csrf").val();
					  $.ajax({
							url:BASE_URL+'inventory/purchase/update_pur_req_heads?securekey='+securekey,
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
 		$('.process').on('click', function () {  
			if($('#project_head').val()==''){
				 swal(" Please Enter Project Heads!", "", "warning"); 
				return false;
			}		 
			var req_id=$("#req_id").val();
			var project_head=$("#project_head").val();
			var csrftoken=$("#_csrf").val();
			  $.ajax({
					url:BASE_URL+'inventory/purchase/update_pur_req_heads?securekey='+securekey,
					type:'POST',
					data:{project_head:project_head,req_id:req_id,_csrf:csrftoken},
					datatype:'json',
					success:function(data){
						swal("Updated Successfully! ", "", "success")
						.then((value) => {
						  window.location.reload(); 
						}); 
						 
					}
			});
		});
			 
		$('.update').on('click', function () {  
			if($('#purc_status').val()==''){
				 swal(" Please Select Purchase Status!", "", "warning"); 
				return false;
			}		 
			if($('#purchase_mod').val()==''){
				 swal(" Please Select PurchaseMode!", "", "warning"); 
				return false;
			}	
			if(typeof $('#purchase_mod').val()!=='undefined'){
				if($('#purchase_mod').val()!='Gem' && $('#purchase_mod').val()!='CPP' && $('#remarks').val()==''){
					 swal(" Please Enter Remarks!", "", "warning"); 
					return false;
				}
			}
			var req_id=$("#req_id").val();
			var purc_status=$("#purc_status").val();
			var purchase_mod=$("#purchase_mod").val();
			var remarks=$("#remarks").val();
 			var csrftoken=$("#_csrf").val();
			  $.ajax({
					url:BASE_URL+'inventory/purchase/update_pur_req_heads?securekey='+securekey,
					type:'POST',
					data:{purc_status:purc_status,purchase_mod:purchase_mod,remarks:remarks,req_id:req_id,_csrf:csrftoken},
					datatype:'json',
					success:function(data){
						swal("Updated Successfully! ", "", "success")
						.then((value) => {
						  window.location.reload(); 
						}); 
						 
					}
			});
		});
		$('.approve').on('click', function () {  
			var req_id=$("#req_id").val();
			var chremarks=$("#CH_remarks").val();
 			var csrftoken=$("#_csrf").val();
  				  $.ajax({
					url:BASE_URL+'inventory/purchase/update_pur_req_heads?securekey='+securekey,
					type:'POST',
					data:{req_id:req_id,chremarks:chremarks,_csrf:csrftoken},
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
 
 
                   
               
