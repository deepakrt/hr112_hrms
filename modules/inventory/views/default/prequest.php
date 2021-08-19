<!------ Include the above in your HEAD tag ---------->
<?php
	use yii\helpers\Html;
	use yii\widgets\ActiveForm;
	use yii\helpers\ArrayHelper;
	//echo "<pre>";print_r(Yii::$app->user->identity);die;
?>      
	<input type="hidden" name="menuid" id="menuid" value="<?=$menuid;?>" /> 
  <div class="col-sm-12">
 		<table id="dataTableShow" class="display" cellpadding="2" style="width:100%">
      <thead>
        <tr>
          <td><input type='checkbox' id='checkAll' class='checkAll'></td>
          <th>Voucher No</th>
          <th>Employee</th>
          <th>Item Category</th>
          <th>Item</th>
					<?php if(Yii::$app->user->identity->role==2 || Yii::$app->user->identity->role==4){ ?>
	                	<th> Quantity Required </th>
					<?php } if(Yii::$app->user->identity->role==4){ ?>
	                	<th> Approved Quantity </th>
	 				<?php } if(Yii::$app->user->identity->role==2){ ?>
						<th> Quantity Approved(FLA) </th>
	                	<th> Quantity Approved </th>
	 				<?php } if(Yii::$app->user->identity->role==8){ ?>
						<th> Quantity Approved </th>
						<th> Quantity Available </th>
						<th> Quantity Approved Store </th>
	 				<?php } if(Yii::$app->user->identity->role==9){ ?>
						<th> Quantity Available </th>
						<th> Quantity Approved Store </th>
					<?php } ?> 
      </tr>
      </thead>
      <tbody>
        <?php 
					$check="disabled='disabled'";
	        if(!empty($data)){
					$check="";
	        foreach($data as $k=>$c){ ?>
	          <tr id="row_<?=$c['ID']?>">
							<?php $disable='';
							if(Yii::$app->user->identity->role==9 && $c['Quantity']<$c['Qty_Approved_STORE']){ $c['ID']='';$disable="disabled='disabled'";}?>
							
			                <td width="5%" ><input name="vvalues" <?=$disable?> type="checkbox" value="<?=$c['ID']?>"></td>
			                <td width="10%" id="Voucher_No"><?=$c['Voucher_No'].'<hr style="margin: 2px 9px 2px 0px;">'.date('d-m-y',strtotime($c['Issue_Request_Date']));?></td>
			                <td width="10%" id=""><?=$c['fname']?></td>
			                <td width="25%" id="ITEM_CAT_NAME"><?=$c['ITEM_CAT_NAME'].'('.$c['Item_Type'].')';?></td>
			                <td width="15%" id="item_name"><?=$c['item_name'];?></td>
							<?php if(Yii::$app->user->identity->role==2 || Yii::$app->user->identity->role==4){ ?>
			                <td width="10%" id="Quantity_Required"><?=$c['Quantity_Required']?></td>
							<?php } if(Yii::$app->user->identity->role==4){ ?>
			                <td width="25%"> &nbsp;&nbsp;&nbsp;&nbsp;<input id="Qty_Approved_<?=$c['Voucher_No']?>" readonly maxlength="2" style="width:30%" type='number' value='<?php echo $c['Qty_Approved_FLA']; ?>' > 
			                 <button type='button' class="updateqty btn btn-primary btn-sm btn_<?=$c['Voucher_No']?>" id="<?=$c['Voucher_No']?>">Edit</button>
							</td>
							<?php } ?>
							
							<?php if(Yii::$app->user->identity->role==2){ ?>
			                <td id="Qty_Approved_FLA"><?=$c['Qty_Approved_FLA']?></td>
			                <td width="20%"><input id="Qty_Approved_<?=$c['Voucher_No']?>" readonly maxlength="2" style="width:30%" type='number' value='<?php echo $c['Qty_Approved_HOD']; ?>' > 
			                 
												<button type='button' class="updateqty btn btn-primary btn-sm btn_<?=$c['Voucher_No']?>" id="<?=$c['Voucher_No']?>">Edit</button>
											</td>
							<?php } ?>
							<?php if(Yii::$app->user->identity->role==8){ ?>
			                 <td id="Qty_Approved_HOD_<?=$c['Voucher_No']?>"><?=$c['Qty_Approved_HOD']?></td>
										<?php $style='';if($c['Quantity']<$c['Qty_Approved_STORE']){ $style='style="color: red;"';}?>
			                <td id="Quantity"><b <?=$style?> class="Quantity_<?=$c['Voucher_No']?>"><?=$c['Quantity']?></b>
												<input id="Quantity_<?=$c['Voucher_No']?>" readonly maxlength="2" style="width:50%" type='hidden' value='<?php echo $c['Quantity']; ?>' >
											</td>
			                <td width="25%"><input id="Qty_Approved_<?=$c['Voucher_No']?>" readonly maxlength="2" style="width:50%" type='number' value='<?php echo $c['Qty_Approved_STORE']; ?>' > 
			                 <button type='button' class="updateqty btn btn-primary btn-sm btn_<?=$c['Voucher_No']?>" id="<?=$c['Voucher_No']?>">Edit</button>
											</td>
							<?php } ?>
			        <?php if(Yii::$app->user->identity->role==9){ ?>
						 		<?php $style='';if($c['Quantity']<$c['Qty_Approved_STORE']){ $style='style="color: red;"';}?>
			          <td id="Quantity"><b <?=$style?> class="Quantity_<?=$c['Voucher_No']?>"><?=$c['Quantity']?></b>
								<td id="Qty_Approved_STORE"> <?=$c['Qty_Approved_STORE']?> </td>
							<?php } ?>
	        	</tr>   
	        <?php } } ?>
   	  </tbody>
    </table>
	  <div class='row' id="controll_drop_div">
		  <?php 
		  	if(Yii::$app->user->identity->role==4)
		  	{
 				  if(array_search(Yii::$app->user->identity->e_id, array_column($allhod, 'employee_code')) !== False) {
						$emp_hod=Yii::$app->user->identity->e_id;
					} else {
						$emp_hod=Yii::$app->user->identity->authority1;
					}
				 
			  	?>
					<button type='button' class="forward_btn btn btn-success" id="forward_btn" onclick="clickStatusAction('Forward')">Forward</button>
					<?php
							/*
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
						  <?php } //} ?>
						</select>

						<?php 
							*/
					?>
 			<?php 
 				}
 				else
				{ 
			?>
					<input id="hod_id" type='hidden' value='0' > 
			<?php	
				}
		 	?>
		 	<input id="emp_code_get" type='hidden' value='<?=Yii::$app->user->identity->e_id;?>' > 


	 	  <?php if(Yii::$app->user->identity->role==9){ ?>						
		  	&nbsp;<button <?=$check?> type='button' value="3" class="issue btn btn-success" id="">Issue</button>
	  	<?php	}else{ ?>

				&nbsp;<button <?=$check?> type='button' value="1" class="apr_rejs btn btn-success" id="">Approve</button>
				&nbsp;<button <?=$check?> type='button' value="2" class="apr_rejs btn btn-danger" id="">Reject</button>
	   	<?php } ?>
		</div>

		<div class='row' id="forward_div" style="display:none;">
			
		</div>
		
		<div class='row' id="cancel_button" style="display:none; clear: both;">
			<div class="col-sm-12">
				<div class="col-sm-4" style="float:right;clear: both;">
					<button style="margin-top: 10px !important;" type="button" class="btn btn-danger" name="" id="" onclick="cancel_function()">Cancel</button>
				</div>
				<div class="col-sm-4" style="float:right;">
					<button style="margin-top: 10px !important;" type="button" class="btn btn-danger" name="" id="" onclick="submit_function()">Submit</button>
				</div>
			</div>
		</div>
	</div>

	<script>
		function cancel_function()
		{
			$('#forward_div').css({"display": "none"});
			$('#cancel_button').css({"display": "none"});
      $('#controll_drop_div').show();
		}


		function submit_function()
		{
			var ids = [];
			$.each($("input[name='vvalues']:checked"), function(){
			    ids.push($(this).val());
				  <?php if(Yii::$app->user->identity->role==8){ ?>
						//alert(ids);
					<?php }?>
			});
      var v_nos=  ids.join(",");
		 	if(v_nos == ''){
			 	// alert('Please select any Record'); 
			 	swal("Note!",'Please select any Record',"warning");     
			 	return false;
			}


			console.log(v_nos);

      var auth_id = $("#employee_Data").val();
      var login_id = '<?=Yii::$app->user->identity->e_id?>';
      var status = 1; // $(this).val();
      var forward = 1; // $(this).val();
	    
			// console.log('----auth_id-'+auth_id+'----login_id-'+login_id+'====status'+status);


	    if(login_id == auth_id && status == 1){
				if (!confirm("Forwarding requests directly to Store-Incharge as HOD.")) {
						return false;
				}
			}
	  	var csrftoken=$("#_csrf").val();
		  $.ajax({
					url:BASE_URL+'inventory/default/apr_rej_irequest?securekey='+securekey,
					type:'POST',
					data:{v_nos:v_nos,auth_id:auth_id,forward:forward,status:status,_csrf:csrftoken},
					datatype:'json',
					success:function(data){
						// alert('Updated Successfully');
						swal("Done",'Updated Successfully',"success");
						window.location.reload();
					}
			});
		}

		function clickStatusAction(selectedVal)
    {
        $('#forward_div').html('');

        // var selectedVal = $(e).find(':selected').attr('data');
        var menuid = $('#menuid').val();

        if(selectedVal == 'Forward')
        {
            $('#forward_div').css({"display": "block"});
            $('#cancel_button').css({"display": "block"});
            $('#controll_drop_div').hide();

            var url = BASEURL+"hr/approveleaveapplication/get_comman_section?securekey="+menuid;
     
            // console.log(attendate+"=====employment_type="+employment_type+"======"+dept_id);

            $.ajax({
                type: "POST",
                url: url,
                dataType: 'JSON',
                data:{ keyval:selectedVal },
                success: function(data){

                    // console.log(data.render_data);


                    $('#forward_div').html(data.render_data);

                    // stopLoader();
                }
            });
        }
        else
        {
            $('#forward_div').css({"display": "none"});
            $('#cancel_button').css({"display": "none"});
            $('#controll_drop_div').css({"display": "block;"});

            // $('#for_forward_leave').css({"background-color": "yellow", "font-size": "200%"});
        }
    }



		var BASE_URL='<?=Yii::$app->homeUrl?>';
		var securekey='<?=$menuid?>';
		$(function()
		{
		
	 		/*$("#hod_id").focus(function() {
					prev_val = $(this).val();
			  }).change(function() {
				 var text=$(this).find("option:selected").text();
			   if (!confirm("Are you sure! Do you want to forward to "+text+' ?')) {
			  	$(this).val(prev_val);
			   }
		  });*/

			 $(document).on('click','.updateqty',function(e) {
	  
			 		// $('.updateqty').click(function(){
				  var id=$(this).attr("id");
				  if($(".btn_"+id).html()=='Edit'){
					  $("#Qty_Approved_"+id).removeAttr('readonly');
					  $(".btn_"+id).html('Update');
					  $(".btn_"+id).removeClass('btn-primary');
					  $(".btn_"+id).addClass('btn-success');
				  }else{
					  var id=$(this).attr("id");
					  var qty=$("#Qty_Approved_"+id).val();
					  
					   <?php if(Yii::$app->user->identity->role==8){ ?>
					 	      var t_qty=$("#Quantity_"+id).val();
								 
							  if(eval(t_qty) < eval(qty)){
								  $(".Quantity_"+id).css("color", "red");
								  alert('Quantity in store is low('+t_qty+')');
								  $("#Qty_Approved_"+id).val($("#Qty_Approved_HOD_"+id).html()); return false;
							  }else{
							   	$(".Quantity_"+id).css("color", "black");
							  }
					<?php }?>

					  var csrftoken=$("#_csrf").val();
					  $.ajax({
							url:BASE_URL+'inventory/default/updateqty?securekey='+securekey,
							type:'POST',
							data:{voucherno:id,qty:qty,_csrf:csrftoken},
							datatype:'json',
							success:function(data){
								$('#inventory-item').html(data);
								$("#inventory-units").val('');
							}
						  });
					  
					  $("#Qty_Approved_"+id).attr('readonly',true);
					  $(".btn_"+id).html('Edit');
					  $(".btn_"+id).removeClass('btn-success');
					  $(".btn_"+id).addClass('btn-primary');
				 }
	  		});
		
			$(".apr_rejs").click(function()
			{
				var ids = [];
				$.each($("input[name='vvalues']:checked"), function(){
				    ids.push($(this).val());
					  <?php if(Yii::$app->user->identity->role==8){ ?>
					//alert(ids);
					<?php }?>
				});
     	  var v_nos= ids.join(",");
			 	if(v_nos==''){
			 		swal("Note!",'Please select any Record',"warning");    
				 	return false;
				}
			
        var auth_id= $("#emp_code_get").val();

        // alert(auth_id);

        var login_id= '<?=Yii::$app->user->identity->e_id?>';
        var status= $(this).val();
		    /*if(login_id== auth_id && status==1)
		    {	*/				
					/*if (!confirm("Forwarding requests directly to Store-Incharge as HOD.")) {
							return false;
					}*/

				var	swlText = '';
				if(status != 2)
				{
					var swlText = 'Forwarding requests directly to Store-Manager!';
				}

				swal({
				    title: "Are your sure to "+$(this).html()+"?",
				    text: swlText,
				    icon: "warning",
				    buttons: true,
				    dangerMode: true,
				})
				.then((willDelete) => {
				  if (willDelete) {
				    var csrftoken=$("#_csrf").val();
						 $.ajax({
								url:BASE_URL+'inventory/default/apr_rej_irequest?securekey='+securekey,
								type:'POST',
								data:{v_nos:v_nos,auth_id:auth_id,status:status,forward:0,_csrf:csrftoken},
								datatype:'json',
								success:function(data){
									swal("Done",'Updated Successfully',"success");
									// alert('Updated Successfully');
								  window.location.reload();
								}
						});
				  }
				});		

				/*}
				else
				{
				 	var csrftoken=$("#_csrf").val();
					 $.ajax({
							url:BASE_URL+'inventory/default/apr_rej_irequest?securekey='+securekey,
							type:'POST',
							data:{v_nos:v_nos,auth_id:auth_id,status:status,forward:0,_csrf:csrftoken},
							datatype:'json',
							success:function(data){
								swal("Done",'Updated Successfully',"success");
								// alert('Updated Successfully');
							  window.location.reload();
							}
					});
					
				}*/
			});


			$(".issue").click(function(){
			
				var ids = [];
				$.each($("input[name='vvalues']:checked"), function(){
				    ids.push($(this).val());
				});
	            var v_nos= ids.join(",");
	 		 	if(v_nos==''){
				 	swal("Note!",'Please select any Record',"warning");  
					return false;
				}
			 //alert(v_nos);
			  var	swlText = '';
				//var swlText = 'Forwarding requests directly to Store-Manager!';
				swal({
				    title: "Are your sure to "+$(this).html()+"?",
				    text: swlText,
				    icon: "warning",
				    buttons: true,
				    dangerMode: true,
				})
				.then((willDelete) => {
				  if (willDelete) {
	            var csrftoken=$("#_csrf").val();
				 $.ajax({
						url:BASE_URL+'inventory/default/issue_str_item?securekey='+securekey,
						type:'POST',
						data:{v_nos:v_nos,_csrf:csrftoken},
						datatype:'json',
						success:function(data){
							swal("Done",'Updated Successfully',"success");
							// window.location.reload();
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