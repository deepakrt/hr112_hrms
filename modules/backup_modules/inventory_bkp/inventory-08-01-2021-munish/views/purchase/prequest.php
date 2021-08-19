<!------ Include the above in your HEAD tag ---------->
<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
//echo "<pre>";print_r(Yii::$app->user->identity);die;
?>
   
                   
               <div class="col-sm-12">
    <table id="dataTableShow1" class="display" cellpadding="2" style="width:100%">
        <thead>
            <tr>
                <th><input type='checkbox' id='checkAll' class='checkAll'/></th>
                <th>Voucher No</th>
                <th>Employee</th>
                <th>Item (Req Qty)</th>
                <th>Approx Cost</th>
                <th>Under Project</th>
                <th>Status</th>    
            </tr>
        </thead>
        <tbody>
            <?php 
			$check="disabled='disabled'";
            if(!empty($data)){
				$check="";
            foreach($data as $k=>$c){ 
			$c['item_name']=str_replace('inventory/purchase/viewdoc?file=amF2YXNjcmlwdDo=', '/javascript: class="black" onclick="return false;"',$c['item_name']);
			$c['item_name']=str_replace('/inventory/purchase/viewdoc?file=', Yii::$app->homeUrl.'inventory/purchase/viewdoc?file=',$c['item_name']);?>
			
            <tr id="row_<?=$c['id']?>">
                <td width="5%" ><input name="vvalues" type="checkbox" value="<?=$c['id']?>"></td>
                <td width="10%" id="voucher_no"><?=$c['voucher_no'].'<hr style="margin: 2px 9px 2px 0px;">'.date('d-m-y',strtotime($c['request_date']));?></td>
                <td width="10%" id=""><?=$c['fname']?></td>
                <td width="25%" id="item_name"><?=$c['item_name'];?></td>
                <td width="10%" id="approx_cost"><?=$c['approx_cost']?></td>
                <td width="10%" id="project"><?=$c['project']?></td>
                <td width="10%" id="status"><?=$c['Status']?></td>
             </tr>   
             <?php } } ?>
        </tbody>
    </table>
		  <div class='row'>
			  <?php if(Yii::$app->user->identity->role==4){ 
			  if(array_search(Yii::$app->user->identity->e_id, array_column($allhod, 'employee_code')) !== False) {
						$emp_hod=Yii::$app->user->identity->e_id;
					} else {
						$emp_hod=Yii::$app->user->identity->authority1;
					}
			  ?>
				   <label class="control-label" style="margin: 7px;">Forward to HOD: </label> &nbsp;
			  <select class="form-control col-sm-4" name="hod_id" id="hod_id">
					<?php foreach($allhod as $hod)  { 
					$sel='';
					//if(Yii::$app->user->identity->e_id!=$hod['employee_code']){
					if($emp_hod==$hod['employee_code']){$sel='selected="selected"';}
				    elseif(Yii::$app->user->identity->authority1==$hod['employee_code']){$sel='selected="selected"';}
				   ?>
				   <option <?=$sel?> value="<?=$hod['employee_code']?>"><?=$hod['fname']?></option>
				   <?php //}
					} ?>
					</select>
			  <?php }else{ ?>
				<input id="hod_id" type='hidden' value='0' > 
			<?php	} ?>
			 <input class="form-control" placeholder="Rejection Remarks" style="display:none;" type="text" id="reject_remarks" value="">		
				   &nbsp;<button <?=$check?> type='button' value="1" class="apr_rej btn btn-success" id="">Approve</button>
				   &nbsp;<button <?=$check?> type='button' value="2" class="apr_rej btn btn-danger" id="">Reject</button>
				   
					</div>
		</div>
<script>
var BASE_URL='<?=Yii::$app->homeUrl?>';
var securekey='<?=$menuid?>';
$(function(){
	
 		$("#hod_id").focus(function() {
		prev_val = $(this).val();
		}).change(function() {
 			swal({
				  title: "Are you sure! Do you want to Forward to other HOD?",
				  text: '',
				  buttons: [
					'No, cancel it!',
					'Yes, I am sure!'
				  ],
				  dangerMode: false,
				}).then(function(isConfirm) {
				if (!isConfirm) {
   					$("#hod_id").val(prev_val);
				}
 		   });
  		});
		 
		  
	
	$( "body" ).on( "click", ".apr_rej", function() { 
		 
			 
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
			if($(this).val()=='2'){
			 if($.trim($("#reject_remarks").val())==''){
				 $("#reject_remarks").show();
				 $("#reject_remarks").val('');
				 $("#reject_remarks").focus();
			 return false;
			 }}else{
				 $("#reject_remarks").val('');
				 $("#reject_remarks").hide();
			 }
            var auth_id= $("#hod_id").val();
            var status= $(this).val();var reject_remarks=$("#reject_remarks").val();
		 	var csrftoken=$("#_csrf").val();
		 	 var login_id= '<?=Yii::$app->user->identity->e_id?>';
		 var alertmsj='';
 		    if(login_id== auth_id && status==1){
				var alertmsj= "Forwarding requests directly to Store-Incharge as HOD.";
			}
			swal({
				  title: "Are you sure to "+$(this).text()+ "?",
				  text: alertmsj,
				  buttons: [
					'No, cancel it!',
					'Yes, I am sure!'
				  ],
				  dangerMode: false,
				}).then(function(isConfirm) {
				if (isConfirm) {
					 $.ajax({
							url:BASE_URL+'inventory/purchase/apr_rej_prequest?securekey='+securekey,
							type:'POST',
							data:{v_nos:v_nos,auth_id:auth_id,status:status,reject_remarks:reject_remarks,_csrf:csrftoken},
							datatype:'json',
							success:function(data){
									swal({
										  title: "Updated Successfully",
										  text: '',
										  icon: 'success',
										  dangerMode: false,
										}).then(function(isConfirm) {
										if (isConfirm) {
												window.location.reload();
										} 

								 });

							}
					});
				}
 		   });
        });
	 $(".issue").click(function(){
		
			var ids = [];
			$.each($("input[name='vvalues']:checked"), function(){
			    ids.push($(this).val());
			});
            var v_nos= ids.join(",");
 		 	if(v_nos==''){
			 	alert('Please select any Record');
				return false;
			}
		 //alert(v_nos);
            var csrftoken=$("#_csrf").val();
			 $.ajax({
					url:BASE_URL+'inventory/purchase/purchase_str_item?securekey='+securekey,
					type:'POST',
					data:{v_nos:v_nos,_csrf:csrftoken},
					datatype:'json',
					success:function(data){
						alert('Updated Successfully');
						window.location.reload();
					}
			});
        });
	 
	$("#checkAll").click(function(){
    	$('input:checkbox').not(this).prop('checked', this.checked);
	});
     
	
		}); 
</script>