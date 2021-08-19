  <!------ Include the above in your HEAD tag ---------->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
<?php
 use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
//echo "<pre>";print_r(Yii::$app->user->identity);die;
?>
<style>
 .tooltip-inner {
    max-width: 225px;
     width: 225px; 
}
.form-group.col-sm-3.field-storematerialpurchaserequest-item_doc {
	margin: 7px 0 0 -25px;
}
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
	#storematerialpurchaserequest-underproject input {
			margin: 11px 0 0 20px;
		}
</style>
 <!--div class="col-sm-8 offset-sm-4">
    <div class="col-sm-12" style="margin-bottom: 15px;">
        <label>Current Password</label>
        Under<input type="radio" class="col-sm-4 form-control form-control-sm" required="" value="U25" name="Password[req_type]">
        <input type="radio" class="col-sm-4 form-control form-control-sm" required="" value="G25" name="Password[req_type]">
    </div>
</div-->		

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
        <div class='row'>
 		 <?= $form->field($model, 'dept_name', ['options' => ['class'=>'form-group col-sm-6']])->textInput(['value' => trim(Yii::$app->user->identity->dept_name),'readonly'=>'readonly','class'=>'form-control'],['maxlength' => true]) ?>
 		 <?= $form->field($model, 'emp_name', ['options' => ['class'=>'form-group col-sm-6']])->textInput(['value' => trim(Yii::$app->user->identity->fullname),'readonly'=>'readonly','class'=>'form-control'],['maxlength' => true]) ?>
		</div>
	<fieldset class="border p-2" style="width: 100%;">
    <legend>Add Items:</legend>	
	 <div class='row'>
		<?php //print_r($category);die; ?>
		<div class="form-group col-sm-6 field-inventory-category required has-success" data-select2-id="8">
		<label class="control-label" for="inventory-category">Category</label>
	<select id="category" class="js-example-basic-multiple form-control form-control-sm" name="category">
		<option value="">--Select--</option>
		<?php foreach($category as $cat){?>
		<option value="<?=$cat['ITEM_CAT_CODE'];?>"><?=$cat['ITEM_CAT_NAME'];?></option>
		<?php } ?>
		</select></div>
		<?=$form->field($model,'item_name',['options' => ['class'=>'form-group col-sm-6']])->dropDownList(['' => '--Select--'],['class'=>'js-example-basic-multiple form-control form-control-sm']);?>
		 <?php //echo $form->field($model, 'item_name', ['options' => ['class'=>'form-group col-sm-5']])->textInput(['placeholder' => "Name"],['maxlength' => true]) ?>
	 
		<?= $form->field($model, 'item_specification',['options' => ['class'=>'form-group col-sm-9']])->textInput(['placeholder' => "Specification",'maxlength' => 200]) ?>
		<?= $form->field($model, 'item_doc',['options' => ['class'=>'form-group col-sm-3']])->fileInput() ?>
		<?= $form->field($model, 'item_purpose', ['options' => ['class'=>'form-group col-sm-12']])->textInput(['placeholder' => "Purpose",'maxlength' => 200]) ?>
		<?= $form->field($model, 'quantity_required', ['options' => ['class'=>'form-group col-sm-5']])->textInput(['placeholder' => "Qty Required"],['maxlength' => true]) ?>
		<?= $form->field($model, 'approx_cost', ['options' => ['class'=>'form-group col-sm-5','title'=>'Total amount should not exceeded 25 lakh','data-toggle'=>'tooltip']])->textInput(['placeholder' => "Cost per Item(Approx)"],['maxlength' => true])  ?>
		
		 <div class="form-group col-sm-2"><br> 
			<button type="button" class="btn btn-info add_btn" style="margin: 10px 0;float: left;height: 31px;padding: 0 4px 0 5px;">Add Item</button>
			<span id="itemerroremsj" style="color: #db0505;margin:-5px 0 0 -15px;float: left;width: 120%;"></span>
		</div>
		<div class="form-group col-sm-10"><br> 
 			 <span id="itemtablemsj" style="color: #db0505;margin:-35px 30px 0 0;float: right;"></span>
 		</div>
		 
	 </div>
   </fieldset>	
	 <table id="itemtable" class="table table-hover" class="display" cellpadding="2" style="width:100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Item Name</th>
                <th>Specification</th>
                <th>Purpose</th>
                <th>Req Qty</th>
                <th>Cost per Item(Approx)</th>
                <th>Item Doc</th>
            </tr>
        </thead>
        <tbody>
			<?php if($itemdata){  $html='';
				foreach($itemdata as $k=>$res){
					$html.='<tr>';
					$html.='<td>'.($k+1).'</td>';
					$html.='<td>'.$res['item_name'].'</td>';
					$html.='<td>'.$res['item_specification'].'</td>';
					$html.='<td>'.$res['purpose'].'</td>';
					$html.='<td>'.$res['quantity_required'].'</td>';
					$html.='<td>'.$res['approx_cost'].'</td>';
					//$html.='<td><a target="_blank" href="view?securekey='.$menuid.'">Doc</a></td>';
					$link='javascript:';$target='';
					if($res['item_doc']){
						$target='target="_blank"';
						$link=Yii::$app->homeUrl.Inventory_Docs.$res['item_doc'];}
					$html.='<td><a '.$target.' href="'.$link.'">Doc</a></td>';
					$html.='</tr>';
				}
			echo $html;
			}else{echo '<tr><th colspan="3" class="text-center">No Item Added</th><th colspan="3" class="text-center"></th></tr>';}?>
        </tbody>
	</table>
<hr>
 	  <div class='row'>
		  
<?php $model->underproject = 1; echo $form->field($model, 'underproject', ['options' => ['class'=>'form-group col-sm-5']])->radioList([1=>'Yes', 2 => 'No']);?>
  
<?= $form->field($model, 'project',['options' => ['class'=>'form-group col-sm-7']])->textInput(['placeholder' => "Project Name"],['maxlength' => true]) ?>
<?= $form->field($model, 'remarks',['options' => ['class'=>'form-group col-sm-12']])->textInput(['placeholder' => "Description"],['maxlength' => true]) ?>
		 
		</div>

    
        <div class="form-group">
            <?php if($itemdata){ echo Html::submitButton('Submit', ['class' => 'btn btn-primary sub_btn']);} ?>
        </div>
    <?php ActiveForm::end(); ?>
	
<script>
		var BASE_URL='<?=Yii::$app->homeUrl?>';
		var securekey='<?=$menuid?>';
		$(function(){
			$('#category').change(function(){
 					var cat_id= $(this).val();
  					$.ajax({
						url:BASE_URL+'inventory/default/get_cat_code?securekey='+securekey,
						type:'POST',
						data:{cat_id:cat_id},
						datatype:'json',
						success:function(data){
							$('#storematerialpurchaserequest-item_name').html(data);
 						}
					  });
				});
			$('#storematerialpurchaserequest-item_name').change(function(){
				
 			  if($('#storematerialpurchaserequest-item_name').val()=='000'){
				    $('#item_name').remove();
			   		$('#select2-storematerialpurchaserequest-item_name-container').parent().parent().parent().after('<input required type="text" style="float:left;margin-top: 2px;" class="form-control form-control-sm" placeholder="Enter Item Name" id="item_name" name="StoreMaterialPurchaseRequest[item_names]" value="">');
			   		 
  			   }else{
				   $('#item_name').remove();
				   var text=$('#storematerialpurchaserequest-item_name option:selected').text();
				   $('#select2-storematerialpurchaserequest-item_name-container').parent().parent().parent().after('<input required type="hidden" style="float:left;margin-top: 2px;" class="form-control form-control-sm" placeholder="Enter Item Name" id="item_name" name="StoreMaterialPurchaseRequest[item_names]" value="'+text+'">');
 				   
 			   }
			  
			   var alt= $('option:selected', this).attr('alt');
 			   var units= $('option:selected', this).attr('label1');
			   $("#storematreceipttemp-item_type").val(alt);
			   $("#storematreceipttemp-measuring_unit option:contains(" + units + ")").prop('selected', true);
  		  });
				$(".sub_btn").click(function(){
   				if($("#storematerialpurchaserequest-underproject  input:checked").val()==1) {
					if($.trim($("#storematerialpurchaserequest-project").val())=='' ) {
						$("#storematerialpurchaserequest-project").val('');
						alert("Please enter Project name");return false;
					}
					if($.trim($("#storematerialpurchaserequest-project").val()).length<3){
						$("#storematerialpurchaserequest-project").val($.trim($("#storematerialpurchaserequest-project").val()));
						alert('Project name should be minimum 3 Characters');return false;
					}
				}
			 });
		 $('.field-storematerialpurchaserequest-underproject :input').click(function(){
				 
 			 if($(this).val()==1) {
				 $('.field-storematerialpurchaserequest-project').show(); 
 			 }else if($(this).val()==2) {
				 $('.field-storematerialpurchaserequest-project').hide(); 
				 $('#storematerialpurchaserequest-project').val(''); 
			 } 
		 });
		 $('.add_btn').click(function(){
 					var name =  $.trim($("#item_name").val());
 					var specfic=$.trim($("#storematerialpurchaserequest-item_specification").val());
 					var qty =   $.trim($("#storematerialpurchaserequest-quantity_required").val());
 					var purpose=$.trim($("#storematerialpurchaserequest-item_purpose").val());
 					var cost =  $.trim($("#storematerialpurchaserequest-approx_cost").val());
					var items_doc = $('#storematerialpurchaserequest-item_doc').val();
				 
 					var totalcost=eval(cost)*eval(qty);
 					$('#itemerroremsj').html('');
 					$('#itemtablemsj').html('');
					if(name=='' || specfic=='' || qty=='' || purpose=='' || cost==''){
						$('#itemerroremsj').html('All field are required');return false;
					}
					if(totalcost>250000){
						$('#itemtablemsj').html('Total amount should not exceeded 2.5 lakh.');return false;
					}
					var formData = new FormData();
					if(items_doc!=''){
					formData.append('item_doc', $('#storematerialpurchaserequest-item_doc')[0].files[0]);
					}
					var other_data = $('form').serializeArray();
					$.each(other_data,function(key,input){
						formData.append(input.name,input.value);
					});
 					$.ajax({
						url:BASE_URL+'inventory/purchase/add_item?securekey='+securekey,
						type:'POST',
						//data:{name:name,specfic:specfic,qty:qty,purpose:purpose,cost:cost},
						data: formData,
						datatype:'json',processData: false,contentType: false,
						success:function(data){
							if(data==1){
								//$('#itemtable tbody').html(data);
								 window.location.reload();
							}else{
								$('#itemtablemsj').html(data);
							}
						}
					  });
				}); 
		  $('#storematerialpurchaserequest-approx_cost').blur(function(){
			   var alt= $('option:selected', this).attr('alt');
 			   var units= $('option:selected', this).attr('label');
 			   var quantity= $('option:selected', this).data('quantity');
			   $(".sub_btn").attr('disabled',false);
			   $(".sub_btn").css('cursor','auto');
			   if(quantity<1){
				$(".sub_btn").attr('disabled',true);
				$(".sub_btn").css('cursor','not-allowed');
			   }
			   $("#item_alt").val(alt);
			   $("#inventory-units option:contains(" + units + ")").attr('selected', 'selected');
  		  });
		   $('#storematerialpurchaserequest-approx_cost, #storematerialpurchaserequest-quantity_required').on('keyup blur', function (e) {
			 
			
			 
		 });
		   
		}); 
</script>
                   
               