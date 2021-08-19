  <!------ Include the above in your HEAD tag ---------->
<?php
 use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
  //  echo "<pre>";print_r($maindata);die;
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
<div class="col-sm-12" style="text-align:center;">
<h4>प्रगत संगणक विकास केंद्र</h4>
<h6>ए -३४  औद्योगिक क्षेत्र, फेज 8, मोहाली-160071 (चंडीगढ़), पंजाब, भारत</h6>
<h4>Center for Development of Advanced Computing</h4>
<h6>A-34 Industrial Area, Phase VIII, Mohali (Chandigarh)</h6>
<h6>मांग पत्र /<b>INDENT</b></h6>
</div>
<form action="" id="reqform" method="post" onsubmit="return false;" >
		<table id="table" class="display" cellpadding="2" style="width:100%;font-size: 14px;">
        <thead>
            <tr>
				<td><b>क्रमांक /Sr No:</b> <?=$maindata['voucher_no']?></td>
 				<td><b>परियोजना /Project:</b> <?=$maindata['project']?></td>
 				<td><b>दिनांक /Date:</b> <?=date('d-m-Y',strtotime($maindata['request_date']));?></td>
				  
				 
             </tr>
        </thead>
        </table>
	 <fieldset> 
     <table id="table" class="display" cellpadding="2" style="width:100%">
        <thead>
            <tr>
				<th>#</th>
				<th>Item Name</th>
				<th>Req Qty </th>
 				<th>Approx Cost</th>
				<th>Item Specification</th>
				<th>Purpose</th>
				<th>Available(Qty)</th>
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
                <td><?=$c['approx_cost']?></td>
                <td><?=$c['item_specification']?></td>
                <td><?=$c['purpose']?></td>
                <td> <?php if($c['qty_avail']=='Y'){echo "Yes (".$c['qty'].')';}else{echo "No";}?> </td>
				 
			</tr>   
             <?php }   ?>
        </tbody>
    </table> </fieldset>
	 <div class="form-group col-sm-12" style="width:100%;">
		<table id="table" class="display" cellpadding="2" style="font-size: 14px;margin: 10px;width:100%">
        <thead>
		<tr> <td><b>Approximate Total Cost:</b> <?=$maindata['tot_approx_cost']?></td> </tr>
		<tr><td><b>Indented By:</b> <?=$maindata['fname']?></td></tr>
		<tr><td><b>Recomended By (HOD):</b> <?php print_r(Yii::$app->inventory->get_empname($maindata['HOD_ID']));?></td><td> <b><?=$maindata['Status']?></b></td></tr>
		 
            <tr>
				<td>Funds are available in Project <b><?=$maindata['project']?><b/>.</td>
			 </tr>
			<tr>
				<td style="display: flex;">
				<b style="width: 25%;margin: 6px 0;">Project Heads:</b> 
				<input type="hidden" id="req_id" name="req_id" value='<?=$maindata['id']?>'/>
				<input type="text" id="project_head" placeholder="Project Heads" class="form-control" name="project_head" value=''/>
				</td>
			</tr> 
				  
				 
             
        </thead>
        </table> 
    </div>
		 <div class="form-group col-sm-12" style="text-align: center;margin: 16px 0 0 172px;">
		<label>
        	<button type="button" class="approve btn btn-success">Process</button>        	
        	<button type="button"  class="reject btn btn-danger">Reject</button>        	
		</label>
    </div>
		 
		 </form>
 
<script>
var BASE_URL='<?=Yii::$app->homeUrl?>';
var securekey='<?=$menuid?>';
$(function(){
 		
 
		
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
 		 $('.approve').on('click', function () {  
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
	}); 
	
function validate(form) {

     if(!confirm('Do you really want to submit the form?')){
		 return false;
	 }
     
}
</script>
 
 
                   
               
