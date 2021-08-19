<!------ Include the above in your HEAD tag ---------->
<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
//echo "<pre>";print_r(Yii::$app->user->identity);die;
$menuid = Yii::$app->utility->decryptString($_GET['securekey']);
$menuid = Yii::$app->utility->encryptString($menuid);
$role = Yii::$app->user->identity->role;
?>
 
                   
<div class="col-sm-12">
	<div class='row'>
		<div class="form-group col-sm-10 text-center">
			<form  action='' method="post">
			Pending On <select class="purchase_emp" onchange="form.submit();" name="purchase_emp" id="purchase_emp">
				<option value="">--Select--</option>
			<?php $purchase_emp=Yii::$app->inventory->get_emp_by_role(21);
				foreach($purchase_emp as $emp)  {  $sel='';
 				     if(@$_POST['purchase_emp']==$emp['employee_code']){$sel='selected="selected"';} ?>
				   <option <?=$sel?> value="<?=$emp['employee_code']?>"><?=$emp['fname']?></option>
				   <?php } ?>
			</select>
		  </form>
		</div>	 
	</div>
    <table id="dataTableShow1" class="display" style="width:100%">
        <thead>
            <tr>
               
                <th>Voucher No</th>
                <th>Employee</th>
                <th>Item (Req Qty)</th>
				<?php //if($role>6){ ?>
                <th>Available(Qty)</th><?php //} ?>
                <th>Cost per Item</th>
                <th>Project</th>
                <th>Status</th>
				<?php if($role=='17'){ ?>
				<th>Forward To</th>
				<?php } ?>
                <?php if($role=='17' || $role=='21'){ ?>
				<th>Download</th>
				<?php } ?>
                
            </tr>
        </thead>
        <tbody>
            <?php  //echo "<pre>";print_r($data);die;
            if(!empty($data)){ $find = array("Y", "N");$replace = array("Yes", "No");
            foreach($data as $k=>$c){ 
			$c['item_name']=str_replace('inventory/purchase/viewdoc?file=amF2YXNjcmlwdDo=', '/javascript: class="black" onclick="return false;"',$c['item_name']);
			$c['item_name']=str_replace('/inventory/purchase/viewdoc?file=', Yii::$app->homeUrl.'inventory/purchase/viewdoc?file=',$c['item_name']);?>
            <tr>
                
                <td align="center" 
				<?php if($c['flag']>7){ ?> 
				style="cursor:pointer;" data-toggle="modal" data-target="#myModal3"
				<?php } ?>>
				<p id="<?=$c['id']?>" <?php if($c['flag']>7){ ?>  class="model_<?=$c['id']?> getdetails footer-content"<?php } ?>>
				<?=$c['voucher_no'].'<hr style="margin: 2px 9px 2px 0px;">'.date('d-m-y',strtotime($c['request_date']));?>
				</p>
				</td>
                <td id="Employee"><?=$c['fname']?></td>
                <td id="item_name"><?=$c['item_name'];?></td>
				<?php //if($role>6){ 
				$avail='No';if($c['qty_avail']=='Y'){$avail='Yes ('.$c['qty'].')';}?>
                 <td id="qty"><?=str_replace($find, $replace,$c['qty_avail_ll']);?></td><?php //} ?>
                <td id="approx_cost"><?=$c['approx_cost']?></td>
                <td id="project"><?php if($c['project']){echo $c['project'];}else{echo "---";}?></td>
				<td align="center" <?php /*if($c['flag']>7){ ?> 
				style="cursor:pointer;" data-toggle="modal" data-target="#myModal3"
				<?php }*/ ?>>
				<p id="<?=$c['id']?>">
				<?php 
					$status=0;
					if($c['purchase_status']=='Completed'){
						$status=2;
						echo $c['purchase_status']; 
					  }elseif($c['ipurchase_status']!='defaultY'){
						$i_status=explode('<br>',$c['ipurchase_status']);
							// echo "<pre>";print_r($i_status); 
							 $i=0;
						foreach($i_status as $s){
							if($s!='defaultY' && $s!='defaultN'){ $i=1;
								$status=1;
								//echo $s.'<br>';
							}
						}
						//echo count($i_status).'---'.$i;
						if($i==1){ 
							$statusmsj = str_replace('InitiatedY', 'Initiated',$c['ipurchase_status']);
							$statusmsj = str_replace('Order-DeclinedY', 'Order-Declined',$statusmsj);
							$statusmsj = str_replace('Order-PlacedY', 'Order-Placed',$statusmsj);
							$statusmsj = str_replace('Re-OrderedY', 'Re-Ordered',$statusmsj);
							$statusmsj = str_replace('CompletedY', 'Completed',$statusmsj);
							$statusmsj = str_replace('defaultY', 'Not Initiated',$statusmsj);
							$statusmsj = str_replace('defaultN', 'Not Approved',$statusmsj);
							echo $statusmsj = rtrim($statusmsj,'Y');
						}
 					  }
					  if($status==0){
						echo $c['Status'];
    	    	      }  if($role=='2'){
						  if($c['discuss_WCH']==1){ ?><hr style='margin: 7px 7px -12px 9px;'><br>
					<span style='color:red;cursor: pointer;' data-toggle="modal" id="<?=$c['id']?>" data-target="#myModal3" class="getmsjs">ED: Pl. Discuss. </span>
					<?php }elseif($c['discuss_WCH']==4){ ?>
							<span style='color:red;cursor: pointer;' data-toggle="modal" id="<?=$c['id']?>" data-target="#myModal3" class="getmsjs">Emp: View Response. </span>
						 <?php    }else{ ?>
							<span style='color:red;cursor: pointer;' data-toggle="modal" id="<?=$c['id']?>" data-target="#myModal3" class="getmsjs">View Response. </span>
						 <?php  }
					  }
				
				if($role=='3'){
						  if($c['discuss_WCH']==3){ ?><hr style='margin: 7px 7px -12px 9px;'><br>
					<span style='color:red;cursor: pointer;' data-toggle="modal" id="<?=$c['id']?>" data-target="#myModal3" class="getmsjs">HOD: Pl. Discuss. </span>
					<?php }elseif($c['discuss_WCH']!=0 && $c['discuss_WCH']!=3){ ?>
							<span style='color:red;cursor: pointer;' data-toggle="modal" id="<?=$c['id']?>" data-target="#myModal3" class="getmsjs">Emp: View Response. </span>
						 <?php    }else{ ?>
							<span style='color:red;cursor: pointer;' data-toggle="modal" id="<?=$c['id']?>" data-target="#myModal3" class="getmsjs">View Response. </span>
						 <?php  }
					  }
				if($role=='7'){
						  if($c['discuss_WCH']==1){ ?><hr style='margin: 7px 7px -12px 9px;'><br>
					<span style='color:red;cursor: pointer;' data-toggle="modal" id="<?=$c['id']?>" data-target="#myModal3" class="getmsjs">Discussed. </span>
					<?php }elseif($c['discuss_WCH']!=0 && $c['discuss_WCH']!=1){ ?>
							<span style='color:red;cursor: pointer;' data-toggle="modal" id="<?=$c['id']?>" data-target="#myModal3" class="getmsjs">View Response. </span>
						 <?php    } 
					  }
				
				if(@$c['discuss_WCH']==1 && $role=='7'){ ?><hr style='margin: 7px 7px -12px 9px;'><br>
					<span style='color:red;' class="Discussed"><b>Discussed. </b></span>
					<?php }if(@$c['discuss_WCH']==2 && $role=='7'){ ?><hr style='margin: 7px 7px -12px 9px;'><br>
					<span style='color:green;cursor: pointer;' data-toggle="modal" id="<?=$c['id']?>" data-target="#myModal3" class="Replied getmsjs">View Response. </span>
					<?php } ?></p></td>
				<?php if($role=='17'){
					if($c['purchase_status']=='Completed'){echo "<td>".$c['forward_name']."</td>";}else{
					?>
					<td>
						<input type="hidden" class="purchase_oldempid<?=$c['id']?>" name="purchase_oldempid" readonly value="<?=$c['forward_to']?>" />
						 <select class="purchase_empid" onchange="" name="purchase_empid" id="purchase_empid<?=$c['id']?>">
					<?php foreach($purchase_emp as $emp)  {  $sel='';
 				    if($c['forward_to']==$emp['employee_code']){$sel='selected="selected"';} ?>
				   <option <?=$sel?> alt="<?=$c['id']?>" value="<?=$emp['employee_code']?>"><?=$emp['fname']?></option>
				   <?php } ?>
				<option  alt="<?=$c['id']?>" value="<?=Yii::$app->user->identity->e_id?>">Revert Back</option>
			  </select>
						<br> 
					<?php if($c['forward_time']){echo date('d-m-y H:i:s',strtotime($c['forward_time']));} ?>
					  </td>
					<?php } ?>	 
				<?php }
				  if($role=='17' || $role=='21'){ ?>
				<td>
				&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="download?securekey=<?=$menuid?>&id=<?=base64_encode($c['id'])?>">
					<img src="<?=Yii::$app->homeUrl?>images/pdf.png" title="Download"  style="cursor:pointer;width: 25px;">  </a> 
				&nbsp;&nbsp;&nbsp;&nbsp; <a target="_blank" class='btn btn-info btn-sm btn-xs' href="savepdf?securekey=<?=$menuid?>&id=<?=base64_encode($c['id'])?>">
					Initiate Note </a> 
					 
 				</td>
				<?php } ?>
            </tr>   
             <?php } } ?>
        </tbody>
        
    </table>
</div><?php //if($role==7 || $role==16 || $role==17){
//if($c['flag']>7){ 	?>
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
 	$( "body" ).on( "change", ".purchase_empid", function() { 
        var purchase_empid=  $(this).val();
   		var text= $('option:selected', this).text();
 		var alt= $('option:selected', this).attr('alt');
		 	swal({
			  title: "Are you sure!",
			  text: "you want to forward to "+text+" ?",
			  icon: "warning",
			  buttons: [
				'No, cancel it!',
				'Yes, I am sure!'
			  ],
			  dangerMode: false,
			}).then(function(isConfirm) {
			if (isConfirm) {
  			var csrftoken=$("#_csrf").val();
 					  $.ajax({
							url:BASE_URL+'inventory/purchase/forward_frequest?securekey='+securekey,
							type:'POST',
							data:{v_nos:alt,purchase_empid:purchase_empid,_csrf:csrftoken},
							datatype:'json',
							success:function(data){
								$('.purchase_oldempid'+alt).val(purchase_empid);
								setTimeout(function(){ window.location.reload();  }, 1500);
								swal("forwarded Successfully! ", "", "success")
								.then((value) => {
									window.location.reload();
 								});
							}
					});
			  }else{
				  var previnp = $('.purchase_oldempid'+alt).val();
  				  $('#purchase_empid'+alt).val(previnp);
 				}
			});	
	 });
	//$(".getdetails").click(function(){
	$( "body" ).on( "click", ".getdetails", function() {
			$(".modal-title").html('View Item');
            var id= $(this).attr('id');
		 	var csrftoken=$("#_csrf").val();
			 $.ajax({
					url:BASE_URL+'inventory/purchase/viewreq?securekey='+securekey,
					type:'POST',
					data:{id:id,_csrf:csrftoken},
					datatype:'json',
					success:function(data){
						 $(".modal-body").html(data);
					}
			});
        });
	$( "body" ).on( "click", ".insert_msj", function() { 
             var id= $(this).attr('id');
             var msj= $.trim($('#dis_msj').val());
             var type= $.trim($('#type').val());
		 
		if(msj=='' || msj.length<=3){
		   $('#dis_msj').focus();
			return false;
		}
		 	var csrftoken=$("#_csrf").val();
			$(".modal-title").html('Discussion');
			 $.ajax({
					url:BASE_URL+'inventory/purchase/insert_msj?securekey='+securekey,
					type:'POST',
					data:{req_id:id,type:type,msj:msj,_csrf:csrftoken},
					datatype:'json',
					success:function(data){
						$("body .getmsjs").html('Replied');
						$("body .getmsjs").css('color','green');
						 //$(".modal-body").html(data);
						getmssje(id);
					}
			});
        });
	$( "body" ).on( "click", ".getmsjs", function() {
             var id= $(this).attr('id');
			$(".modal-title").html('Discussion');
		 	getmssje(id);
        });
			});
	
	function getmssje(id){
 		 	var csrftoken=$("#_csrf").val();
 		 	var type=$("#type").val();
			if(typeof type === "undefined")  {
				type='ED';
			}
			 
			 $.ajax({
					url:BASE_URL+'inventory/purchase/getmsjs?securekey='+securekey,
					type:'POST',
					data:{req_id:id,type:type,_csrf:csrftoken},
					datatype:'json',
					success:function(data){
						$(".modal-body").html(data);
						
					}
			});
 	  }
	
function change_type(type){
	if(type==1){
		$("#type").val('ED');
	}else{
		$("#type").val('EMP');
	}
 	var id=$("#req_id").val();
	getmssje(id);
}
</script> <?php //}  ?>