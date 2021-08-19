  <!------ Include the above in your HEAD tag ---------->
<link href="<?=Yii::$app->homeUrl?>css/select2.min.css" rel="stylesheet" />
<script src="<?=Yii::$app->homeUrl?>js/select2.min.js"></script>
<?php
 use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
//echo "<pre>";print_r(Yii::$app->user->identity);die;
 //echo $model->Emp_code;
?>
<style>
legend {
	background-color: #c5dec5;color: #3F9E89;padding: 0px 6px;font-family: initial;font-size: 21px;
}
</style>
 
		<?php $form = ActiveForm::begin(); //['class'=>'form-control form-control-sm']?>
	 <fieldset>
		 <b>NEW MRN: </b> <br> <input type="text" id="" name="StoreMatReceiptTemp[MRN_No]" readonly value="<?=$new_mrn['newmrn']?>" />
        <legend>Indentor Details</legend>
<div class='row'>						
 
 	<?= $form->field($model, 'PO_no', ['options' => ['class'=>'form-group col-sm-4']])->textInput(['value'=>'00','class'=>'form-control form-control-sm']) ?>
	<?= $form->field($model, 'PO_Date', ['options' => ['class'=>'form-group col-sm-4']])->textInput(['class'=>'date_picker_tilltoday form-control-sm form-control','readonly'=>'readonly']) ?>
	<?= $form->field($model, 'Indent_no', ['options' => ['class'=>'form-group col-sm-4']])->textInput(['value'=>'00','class'=>'form-control form-control-sm']) ?>
	
	<?php $depts = ArrayHelper::map($depts, 'dept_id', 'dept_name'); ?>
    
	<?=$form->field($model,'Dept_code',['options' => ['class'=>'form-group col-sm-4 ']])->dropDownList($depts,['prompt' => '--Select--','class'=>'js-example-basic-multiple form-control form-control-sm'])?>
     
	 
	<?=$form->field($model,'Emp_code',['options' => ['class'=>'form-group col-sm-4']])->dropDownList(['' => '--Select--'],['class'=>'js-example-basic-multiple form-control form-control-sm'])->label('Employee')?>
	
	
	<?=$form->field($model,'Cost_Centre_Code',['options' => ['class'=>'form-group col-sm-4']])->dropDownList(['' => '--Select--'] , ['class'=>'js-example-basic-multiple form-control form-control-sm'])?>
	
 	

	</div>  </fieldset>
	<!--------------------2nd----------------------------->
 
 	<fieldset>
        <legend>Supplier Details</legend>
    <div class='row'>	
 	<?php
	//echo $model->Supplier_Code;die;
	/*$suppliers = ArrayHelper::map($suppliers, 'Supplier_Code', 'Supplier_name'); ?>
  	<?=$form->field($model,'Supplier_Code',['options' => ['class'=>'form-group col-sm-4']])->dropDownList($suppliers,['prompt' => '--Select--']) */?>
<div class="form-group col-sm-4 field-storematreceipttemp-supplier_code required">
  <label class="control-label" for="storematreceipttemp-supplier_code">Supplier Name</label>
   <select id="storematreceipttemp-supplier_code" required class="js-example-basic-multiple  form-control form-control-sm" name="StoreMatReceiptTemp[Supplier_Code]">
		<option value="">-- Select --</option>
		<?php foreach($suppliers as $r){ 
			$sel="";
			if($model->Supplier_Code==$r['Supplier_Code']){$sel="selected='selected'";}
			echo '<option '.$sel.' alt="'.$r['Phone_no'].'" label1="'.$r['Supplier_address'].'" value="'.$r['Supplier_Code'].'">'.$r['Supplier_name'].'</option>';
		 } ?>
	   <option value='other'>Other</option>
	</select> 
 	<input type="text" style='margin-top:2px;display:none;' class="form-control form-control-sm" placeholder="Enter Supplier Name" id="supplier_name" name="StoreMatReceiptTemp[supplier_name]" value="" />
	<div class="help-block"></div>
</div>
	<?= $form->field($model, 'Memo_no', ['options' => ['class'=>'form-group col-sm-4']])->textInput(['class'=>'form-control form-control-sm'])?>
	<?= $form->field($model, 'Memo_Date', ['options' => ['class'=>'form-group col-sm-4']])->textInput(['class'=>'date_picker_tilltoday form-control-sm form-control','readonly'=>'readonly'])?>
	<?= $form->field($model, 'address', ['options' => ['class'=>'form-group col-sm-8']])->textInput(['class'=>'form-control form-control-sm','readonly' => "readonly"])->label('Address'); ?>
	<?= $form->field($model, 'phoneno', ['options' => ['class'=>'form-group col-sm-4']])->textInput(['class'=>'form-control form-control-sm','readonly' => "readonly"])->label('Phone No'); ?>  
	<?= $form->field($model, 'Receipt_mode', ['options' => ['class'=>'form-group col-sm-4']])->textInput(['class'=>'form-control form-control-sm']) ?>
	<?= $form->field($model, 'Consignment_no', ['options' => ['class'=>'form-group col-sm-4']])->textInput(['class'=>'form-control form-control-sm']) ?>
	<?= $form->field($model, 'Vehicle_no', ['options' => ['class'=>'form-group col-sm-4']])->textInput(['class'=>'form-control form-control-sm']) ?>
	</div>  </fieldset>
	<!--------------------3nd----------------------------->
 
 	<fieldset>
        <legend>Item Details</legend>
    <div class='row'>
		
 	<?php $groups = ArrayHelper::map($groups, 'CLASSIFICATION_CODE', 'CLASSIFICATION_NAME'); ?>
	<?=$form->field($model,'CLASSIFICATION_CODE',['options' => ['class'=>'form-group col-sm-4']])->dropDownList($groups,['' => '--Select--','class'=>'js-example-basic-multiple form-control form-control-sm']) ?>
	
	<?php $category = ArrayHelper::map($category, 'ITEM_CAT_CODE', 'ITEM_CAT_NAME'); ?>
	<?=$form->field($model,'ITEM_CAT_CODE',['options' => ['class'=>'form-group col-sm-4']])->dropDownList($category,['prompt' => '--Select--','class'=>'js-example-basic-multiple form-control form-control-sm'])?>
 	<?=$form->field($model,'ITEM_CODE',['options' => ['class'=>'form-group col-sm-4']])->dropDownList(['' => '--Select--'],['class'=>'js-example-basic-multiple form-control form-control-sm']) ?>
		 
   	
	    <?= $form->field($model, 'item_type', ['options' => ['class'=>'form-group col-sm-4']])->dropDownList([1 => 'Consumable', 2 => 'Non Consumable'],['prompt' => '--Select--','class'=>'form-control form-control-sm']);?>
			
		<?php $unit_master = ArrayHelper::map($unit_master, 'Unit_id', 'Unit_Name'); ?>
		<?=$form->field($model,'Measuring_Unit',['options' => ['class'=>'form-group col-sm-4']])->dropDownList($unit_master,['prompt' => '--Select--','class'=>'form-control form-control-sm'])?>
    <?= $form->field($model, 'QtyO', ['options' => ['class'=>'form-group col-sm-4']])->textInput(['class'=>'form-control form-control-sm'])?>

    <?= $form->field($model, 'QtyS', ['options' => ['class'=>'form-group col-sm-4']])->textInput(['class'=>'form-control form-control-sm'])?>

    <?= $form->field($model, 'QtyR', ['options' => ['class'=>'form-group col-sm-4']])->textInput(['class'=>'form-control form-control-sm','readonly'=>false])?>

  
    <?= $form->field($model, 'Description', ['options' => ['class'=>'form-group col-sm-10']])->textInput(['class'=>'form-control form-control-sm'])->label('Item Description') ?>

</div>  </fieldset>
<!--------------------2nd----------------------------->

<fieldset>
<legend>Account Details</legend>
    <div class='row'>
    <?= $form->field($model, 'Rate_per_unit', ['options' => ['class'=>'form-group col-sm-4']])->textInput(['onkeyup' => 'calculatePrice()','class'=>'form-control form-control-sm']) ?>

    <?= $form->field($model, 'Sale_tax_per', ['options' => ['class'=>'form-group col-sm-4']])->textInput(['onkeyup' => 'calculatePrice()','class'=>'form-control form-control-sm']);?>
    <?= $form->field($model, 'Sale_tax', ['options' => ['class'=>'form-group col-sm-4']])->textInput(['class'=>'form-control form-control-sm']) ?>

    <?= $form->field($model, 'Edu_Cess', ['options' => ['class'=>'form-group col-sm-4']])->textInput(['value'=>'0','class'=>'form-control form-control-sm']) ?>

    <?= $form->field($model, 'SED', ['options' => ['class'=>'form-group col-sm-4']])->textInput(['value'=>'0','class'=>'form-control form-control-sm']) ?>

    <?= $form->field($model, 'ED', ['options' => ['class'=>'form-group col-sm-4']])->textInput(['value'=>'0','class'=>'form-control form-control-sm']) ?>

    <?= $form->field($model, 'Packing_Forword', ['options' => ['class'=>'form-group col-sm-4']])->textInput(['value'=>'0','class'=>'form-control form-control-sm']) ?>
    <?= $form->field($model, 'Discount', ['options' => ['class'=>'form-group col-sm-4']])->textInput(['value'=>'0','class'=>'form-control form-control-sm']) ?>
    <?= $form->field($model, 'Cartage', ['options' => ['class'=>'form-group col-sm-4']])->textInput(['value'=>'0','class'=>'form-control form-control-sm']) ?>

    <?= $form->field($model, 'Insurance', ['options' => ['class'=>'form-group col-sm-4']])->textInput(['value'=>'0','class'=>'form-control form-control-sm']) ?>
    <?= $form->field($model, 'Surcharge', ['options' => ['class'=>'form-group col-sm-4']])->textInput(['value'=>'0','class'=>'form-control form-control-sm']) ?>


    <?= $form->field($model, 'Octroi', ['options' => ['class'=>'form-group col-sm-4']])->textInput(['value'=>'0','class'=>'form-control form-control-sm']) ?>

    <?= $form->field($model, 'Remark', ['options' => ['class'=>'form-group col-sm-10']])->textInput(['class'=>'form-control form-control-sm']) ?>

</div>  </fieldset>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Add Details' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
<?php if(!empty($tmp_mat_receipt)){ ?>
	<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);?>
		<fieldset>
		  <legend> Draft Receipts </legend>
			<div class="row">
				<div class="form-group col-sm-12">
					<table class="table table-bordered">
				      <tbody><tr>
				          <th>#</th>
				          <th>MRN No</th>
				          <th>Receipt Date</th>
				          <th>PO No</th>
				          <th>Indent No</th>
				          <th>Emp Name</th>
				          <th>Action</th>
				      </tr>
							<?php foreach($tmp_mat_receipt as $k=>$receipt){?>
										<tr id="tr_<?=$receipt['ID']?>">
											<td><?=$k+1;?></td>
											<td><?=$new_mrn['newmrn']?></td>
											<td><?=date('d-M-Y',strtotime($receipt['Receipt_date']))?></td>
											<td><?=$receipt['PO_no']?></td>
											<td><?=$receipt['Indent_no']?></td>
											<td><?=$receipt['emp_name']?></td>
											<td>
											<a href="<?=Yii::$app->homeUrl.'inventory/default/edit?securekey='.$menuid.'&MRN_No='.$receipt['MRN_No'].'&rno='.$receipt['ID']?>"> 
											<img src="<?=Yii::$app->homeUrl?>images/edit.gif" style="width: 23px;">
											</a>
											<a href="javascript:" onclick="delete_rec(<?=$receipt['ID']?>);"><img src="<?=Yii::$app->homeUrl?>images/del.gif" style="width: 23px;"></a>
											</td>
										</tr>
							<?php } ?>
				    </tbody></table>
				</div>

				<div class="form-group col-sm-12">
					<div class="form-group col-sm-4 field-invoice_file required has-error">
						<label class="control-label" for="invoice_file">Upload Invoice File</label>
						<input type="file" name="invoice_file" id="invoice_file" />
					</div>
				</div>
				<div class="form-group col-sm-12 text-right">
				  <?= Html::submitButton('Finalize', ['name'=>'Finalize','class' => 'btn btn-primary finalrec']) ?>
				</div>
			</div>
		</fieldset>
	<?php ActiveForm::end(); ?>
<?php } ?>
<script>
    $(document).ready(function(){
        $('.finalrec').click(function(){
            if(confirm('Are you sure want to Finalize?')){
                return true;
            }
            return false;;
        });
    });
</script>
<script>
	var securekey='<?=$menuid?>';
	var csrf=$("#_csrf").val();
	var BASE_URL='<?=Yii::$app->homeUrl?>';
	setInterval(function(){ receiptpage(); }, 300000);

	function receiptpage(){
		$.ajax({
			url:BASE_URL+'inventory/default/response?securekey='+securekey,
			type:'POST',
			data:{_csrf:csrf},
			datatype:'json',
			success:function(data){ }
		  });
	}
	
	function delete_rec(id)
	{
	  if (confirm("Are you sure! you want to Delete this?")){
	  	$.ajax({
				url:BASE_URL+'inventory/default/delete_mat_rec?securekey='+securekey,
				type:'POST',
				data:{id:id,_csrf:csrf},
				datatype:'json',
				success:function(data){
					$('#tr_'+id).remove();
				}
		  });
	  }
	}
	
	$(function()
	{
		 //$('#storematreceipttemp-qtyo, #storematreceipttemp-qtys').keyup(function(){
		 $('#storematreceipttemp-description').on('keyup blur', function (e) {
			var des = $('#storematreceipttemp-description').val();
			$('#storematreceipttemp-remark').val(des); 
		 });
		 $('#storematreceipttemp-qtyo, #storematreceipttemp-qtys').on('keyup blur', function (e) {
			 
			var qtyo=$('#storematreceipttemp-qtyo').val();
			 if(qtyo.length>1){
			 	qtyo = qtyo.replace(/^0+/, '');
				 $('#storematreceipttemp-qtyo').val(qtyo);
			 }
			// alert(qtyo);
			var qtys=$('#storematreceipttemp-qtys').val();
			 if(qtys.length>1){
			 	qtys = qtys.replace(/^0+/, '');
				  $('#storematreceipttemp-qtyo').val(qtys);
			 }
			if(qtyo=='' || qtyo<0){qtyo=0;$('#storematreceipttemp-qtyo').val(0);}
			if(qtys=='' || qtys<0){qtys=0;$('#storematreceipttemp-qtys').val(0);}
			//qtyo=parseInt(qtyo);
			//qtys=parseInt(qtys);
			 var qtyr=eval(qtyo)-eval(qtys);
			 if(qtyr<0){qtyr=0;}
			$('#storematreceipttemp-qtyr').val(qtyr);
			 
		 });
		  $('#storematreceipttemp-item_code').change(function(){
 			  if($('#storematreceipttemp-item_code').val()=='000'){
				  //$('#select2-storematreceipttemp-item_code-container').parent().parent().parent().addClass('testtttt');
			   		$('#select2-storematreceipttemp-item_code-container').parent().parent().parent().after('<input required type="text" style="float:left;margin-top: 2px;" class="form-control form-control-sm" placeholder="Enter Item Name" id="item_name" name="StoreMatReceiptTemp[item_name]" value="">');
			   		$('#storematreceipttemp-item_type').removeAttr('readonly');
			   		$('#storematreceipttemp-measuring_unit').val('');
  			   }else{
				   $('#item_name').remove();
 				   $('#storematreceipttemp-item_type').attr('readonly',true);
 			   }
			  
			   var alt= $('option:selected', this).attr('alt');
 			   var units= $('option:selected', this).attr('label1');
			   $("#storematreceipttemp-item_type").val(alt);
			   $("#storematreceipttemp-measuring_unit option:contains(" + units + ")").prop('selected', true);
  		  });
			  
	
		   $('#storematreceipttemp-supplier_code').change(function(){
			   if($('#storematreceipttemp-supplier_code').val()=='other'){
			   		$('#supplier_name').show();
			   		$('#storematreceipttemp-address').removeAttr('readonly');
			   		$('#storematreceipttemp-phoneno').removeAttr('readonly');
 			   		$('#supplier_name').attr('required',true);
			   }else{
				   $('#supplier_name').hide();
				   $('#supplier_name').removeAttr('required');
				   $('#storematreceipttemp-address').attr('readonly',true);
			   		$('#storematreceipttemp-phoneno').attr('readonly',true);
				   $('#supplier_name').val('');
			   }
			   var phone= $('option:selected', this).attr('alt');
 			   var addr= $('option:selected', this).attr('label1');
			   $("#storematreceipttemp-phoneno").val(phone);
			   $("#storematreceipttemp-address").val(addr);
  		  });
		  $('#storematreceipttemp-classification_code').change(function(){
		  	$('#storematreceipttemp-item_cat_code').val('');
		  	$('#storematreceipttemp-item_cat_code').select2();
			  $('#storematreceipttemp-item_code').val('');
		  	$('#storematreceipttemp-item_code').select2();
		  });
		  $('#storematreceipttemp-item_cat_code').change(function(){
			  		$('#item_name').remove();
 				    $('#item_alt').attr('readonly',true);
 					var cat_id= $(this).val();
 					var ccode= $('#storematreceipttemp-classification_code').val();
 					$.ajax({
						url:BASE_URL+'inventory/default/get_cat_code?securekey='+securekey,
						type:'POST',
						data:{cat_id:cat_id,ccode:ccode,_csrf:csrf},
						datatype:'json',
						success:function(data){
							$('#storematreceipttemp-item_code').html(data);
							$("#storematreceipttemp-measuring_unit").val('');
						}
					  });
				});
		$('#storematreceipttemp-dept_code').change(function(){
				var dept_id= $(this).val();
				startLoader();
				$.ajax({
					url:BASE_URL+'inventory/default/get_dept_emp?securekey='+securekey,
					type:'POST',
					data:{dept_id:dept_id,_csrf:csrf},
					datatype:'json',
					success:function(data){
						$('#storematreceipttemp-emp_code').html(data);

						stopLoader();
					}
			  });
				$.ajax({
					url:BASE_URL+'inventory/default/get_dept_cc?securekey='+securekey,
					type:'POST',
					data:{dept_id:dept_id,_csrf:csrf},
					datatype:'json',
					success:function(data){
							$('#storematreceipttemp-cost_centre_code').html(data);
					}
			  });
			});		
		}); 
	<?php if(isset($model->Dept_code) && isset($model->Emp_code)){?>
		getdeptemp();
	<?php } 
	if(isset($model->Dept_code) && isset($model->Cost_Centre_Code)){?>
		getdeptcc();
	<?php } ?>
			function getdeptemp()
			{
				startLoader();
				var dept_id= '<?=$model->Dept_code?>';
				var emp_code= '<?=$model->Emp_code?>';
				$.ajax({
					url:BASE_URL+'inventory/default/get_dept_emp?securekey='+securekey,
					type:'POST',
					data:{dept_id:dept_id,emp_code:emp_code,_csrf:csrf},
					datatype:'json',
					success:function(data){
						stopLoader()
						$('#storematreceipttemp-emp_code').html(data);
						}
			  });
			}
	
		function getdeptcc(){
 					var dept_id= '<?=$model->Dept_code?>';
 					var cc_id= '<?=$model->Cost_Centre_Code?>';
 					$.ajax({
						url:BASE_URL+'inventory/default/get_dept_cc?securekey='+securekey,
						type:'POST',
						data:{dept_id:dept_id,cc_id:cc_id,_csrf:csrf},
						datatype:'json',
						success:function(data){
							$('#storematreceipttemp-cost_centre_code').html(data);
 						}
					  });
			}
	function calculatePrice() {
		var totamount = $('#storematreceipttemp-rate_per_unit').val();
		percent = $('#storematreceipttemp-sale_tax_per').val();
		calcPrice = (totamount / 100) * percent;
		calcPrice = parseFloat(this.calcPrice).toFixed(2);
		$('#storematreceipttemp-sale_tax').val(calcPrice);
	}
	
 function get_cat_code(cat_id){
	$.ajax({
		url:BASE_URL+'inventory/default/get_cat_code?securekey='+securekey,
		type:'POST',
		data:{cat_id:cat_id,page:1,_csrf:csrf},
		datatype:'json',
		success:function(data){
			$('#storematreceipttemp-item_code').html(data);
			//$("#storematreceipttemp-measuring_unit").val('');
			var ITEM_CODE='<?=$model->ITEM_CODE?>';
			$('#storematreceipttemp-item_code').val(ITEM_CODE);
			var alt= $('option:selected', "#storematreceipttemp-item_code").attr('alt');
 			   var units= $('option:selected', "#storematreceipttemp-item_code").attr('label1');
			   $("#item_alt").val(alt);
			   $("#storematreceipttemp-measuring_unit option:contains(" + units + ")").prop('selected', 'selected');
		}
	  });	
}
 
            <?php if(!empty($model->Dept_code)){?>
 
	var dept_id='<?=$model->Dept_code?>';
	var cat_id='<?=$model->ITEM_CAT_CODE?>';
	//get_emp(dept_id);
	get_cat_code(cat_id);
 	var phone= $('option:selected', "#storematreceipttemp-supplier_code").attr('alt'); 
 	var addr= $('option:selected', "#storematreceipttemp-supplier_code").attr('label1'); 
	$("#storematreceipttemp-phoneno").val(phone);
	$("#storematreceipttemp-address").val(addr);
<?php } ?>      



	function startLoader()
	{
	   $("#loading").show();
	}
	 
	function stopLoader()
	{
	    $("#loading").hide();
	}             
</script>