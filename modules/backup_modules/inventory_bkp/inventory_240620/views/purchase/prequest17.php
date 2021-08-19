<!------ Include the above in your HEAD tag ---------->
<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
//echo "<pre>";print_r($data);die;
$role = Yii::$app->user->identity->role;
?>
   
                   
               <div class="col-sm-12">
    <table id="dataTableShow1" class="display" cellpadding="2" style="width:100%">
        <thead>
            <tr>
                <th><input type='checkbox' id='checkAll' class='checkAll'/></th>
                <th>Voucher No</th>
                <th>Employee</th>
                <th>Item Name</th>
                 <th>Qty</th>
                <th>Approx Cost</th>
                <th>Project</th>
                <th>Action</th>
                
                
            </tr>
        </thead>
        <tbody>
            <?php 
			$check="disabled='disabled'";
            if(!empty($data)){
				$check="";$find = array("Y", "N");$replace = array("Yes", "No");
            foreach($data as $k=>$c){ 
			/* $c['item_name']=str_replace('inventory_docspath/javascript:', 'javascript:',$c['item_name']);
			$c['item_name']=str_replace('inventory_docspath', Yii::$app->homeUrl.Inventory_Docs,$c['item_name']); */?>
            <tr id="row_<?=$c['item_id']?>">
                 <td width="5%" ><input name="vvalues" type="checkbox" value="<?=$c['item_id']?>"></td>
                <td id="voucher_no"><?=$c['voucher_no'].'<hr style="margin: 2px 9px 2px 0px;">'.date('d-m-y',strtotime($c['request_date']));?></td>
                <td id=""><?=$c['fname']?></td>
                <td id="item_name">
				<?php if($c['item_doc']=='javascript:'){
						$link='javascript:';
						$target='';
					}else{
						$target='target="_blank"';
						$link=Yii::$app->homeUrl.Inventory_Docs.$c['item_doc'];
					}
				?>
				<a href="<?=$link;?>"><?=$c['item_name'];?></a>
			 
				</td>
                 
                 <td id="qty"><?=$c['quantity_required']-$c['qty'];?></td><?php   ?>
                <td id="tot_cost"><?=$c['total_cost']?></td>
                <td id="project"><?php if($c['project']){echo $c['project'];}else{echo "---";}?></td>
                <td align="center">
				<?php if($c['ipurchase_status']!='Completed'){ ?>
				 
				<img data-toggle="modal" data-target="#myModal3" id="<?=$c['item_id']?>" class="model_<?=$c['item_id']?> getdetails footer-content" title="View" src="<?=Yii::$app->homeUrl?>images/view.png" style="cursor:pointer;width: 25px;"> 
				<?php }else{echo "Completed<br>";}  ?>
				<a target="_blank" href="download?securekey=<?=$menuid?>&id=<?=base64_encode($c['id'])?>"><img src="<?=Yii::$app->homeUrl?>images/pdf.png" title="Download"  style="cursor:pointer;width: 25px;">  </a>
				</td>
             </tr> 
			 
             <?php } } ?>
        </tbody>
    </table>
		     <div class='row'>
 				   <label class="control-label" style="margin: 7px;">Forward To Purchase: </label> &nbsp;
			  <select class="form-control col-sm-4" name="purchase_empid" id="purchase_empid">
					<?php foreach($purchase_emp as $emp)  {  $sel='';
 				    if(Yii::$app->user->identity->authority1==$emp['employee_code']){$sel='selected="selected"';} ?>
				   <option <?=$sel?> value="<?=$emp['employee_code']?>"><?=$emp['fname']?></option>
				   <?php } ?>
			  </select>
			  
			 <input class="form-control" placeholder="Rejection Remarks" style="display:none;" type="text" id="reject_remarks" value="">		
				   &nbsp;<button <?=$check?> type='button' value="1" class="apr_rej btn btn-success" id="">Forward</button>
				   
					</div>
		</div>
		<div class="modal fade show" id="myModal3" role="dialog" style="padding-left: 10px;">
    <div class="modal-dialog" style="min-width: 60%;">
    
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header" style="background: #DD7869;">
          <h4 class="modal-title">View Request</h4>
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
  		$(".getdetails").click(function(){
             var id= $(this).attr('id');
		 	var csrftoken=$("#_csrf").val();
			 $.ajax({
					url:BASE_URL+'inventory/purchase/viewitem?securekey='+securekey,
					type:'POST',
					data:{id:id,_csrf:csrftoken},
					datatype:'json',
					success:function(data){
						 $(".modal-body").html(data);
					}
			});
        });
 	
	 $(".apr_rej").click(function(){
		 
			 
			var ids = [];
			$.each($("input[name='vvalues']:checked"), function(){
			    ids.push($(this).val());
 			});
            var v_nos= ids.join(",");
 		 	if(v_nos==''){
 				swal({
					  title: "Please select any Record",
					  text: '',icon: "warning",
  					});
				return false;
				}
             var purchase_empid= $("#purchase_empid").val();
 		 	var csrftoken=$("#_csrf").val();
			swal({
				  title: "Are you sure to "+$(this).text()+ "?",
				  text: '',
				  buttons: [
					'No, cancel it!',
					'Yes, I am sure!'
				  ],
				  dangerMode: false,
				}).then(function(isConfirm) {
				if (isConfirm) {
					 $.ajax({
							url:BASE_URL+'inventory/purchase/forward_prequest?securekey='+securekey,
							type:'POST',
							data:{v_nos:v_nos,purchase_empid:purchase_empid,_csrf:csrftoken},
							datatype:'json',
							success:function(data){
								if(data){
								swal({
										  title: "Forwarded Successfully",
										  text: '',
										  icon: 'success',
										  dangerMode: false,
										}).then(function(isConfirm) {
										if (isConfirm) {
												window.location.reload();
										} 
 								 });
								}
							}
					});
				}
 		   });
        });
 	 
	$("#checkAll").click(function(){
    	$('input:checkbox').not(this).prop('checked', this.checked);
	});
     
	
		}); 
</script>