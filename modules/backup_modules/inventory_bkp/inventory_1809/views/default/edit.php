  <!------ Include the above in your HEAD tag ---------->
<link href="<?=Yii::$app->homeUrl?>css/select2.min.css" rel="stylesheet" />
<script src="<?=Yii::$app->homeUrl?>js/select2.min.js"></script>
<?php
 use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

//echo "<pre>";print_r(Yii::$app->user->identity); die;
?>
<style>
legend {
	background-color: #c5dec5;color: #3F9E89;padding: 0px 6px;font-family: initial;font-size: 21px;
}
</style>
 
		<?php //$form = ActiveForm::begin(['action' => ['default/edit?securekey='.$_GET['securekey']],'options' => ['method' => 'post']]); //['class'=>'form-control form-control-sm']?>
		<?php $form = ActiveForm::begin();?>
	 <fieldset>
		 <b> MRN No: </b>  <b><?=@$_GET['MRN_No']?> </b> 
        <legend>Indentor Details</legend>
<div class='row'>						
 
 	<?= $form->field($model, 'ID')->hiddenInput(['class'=>'form-control form-control-sm'])->label(false) ?>
 	<?= $form->field($model, 'PO_no', ['options' => ['class'=>'form-group col-sm-4']])->textInput(['class'=>'form-control form-control-sm']) ?>
	<?= $form->field($model, 'PO_Date', ['options' => ['class'=>'form-group col-sm-4']])->textInput(['class'=>'date_picker form-control-sm form-control','readonly'=>'readonly']) ?>
	<?= $form->field($model, 'Indent_no', ['options' => ['class'=>'form-group col-sm-4']])->textInput(['class'=>'form-control form-control-sm']) ?>
	<?php $depts = ArrayHelper::map($depts, 'dept_id', 'dept_name'); ?>
	<?=$form->field($model,'Dept_code',['options' => ['class'=>'form-group col-sm-4 ']])->dropDownList($depts,['prompt' => '--Select--','class'=>'js-example-basic-multiple form-control form-control-sm'])?>
     
	 
	<?=$form->field($model,'Emp_code',['options' => ['class'=>'form-group col-sm-4']])->dropDownList(['' => '--Select--'],['class'=>'js-example-basic-multiple form-control form-control-sm'])->label('Employee')?>
	
	
	<?=$form->field($model,'Cost_Centre_Code',['options' => ['class'=>'form-group col-sm-4']])->dropDownList(['' => '--Select--'] , ['class'=>'js-example-basic-multiple form-control form-control-sm'])?>

	</div>  </fieldset>
	<!--------------------2nd----------------------------->
 
 	<fieldset>
        <legend>Supplier Details </legend>
    <div class='row'>	
 	<?php /*$suppliers = ArrayHelper::map($suppliers, 'Supplier_Code', 'Supplier_name'); ?>
  	<?=$form->field($model,'Supplier_Code',['options' => ['class'=>'form-group col-sm-4']])->dropDownList($suppliers,['prompt' => '--Select--']) */?>
<div class="form-group col-sm-4 field-storematreceipttemp-supplier_code required">
  <label class="control-label" for="storematreceipttemp-supplier_code">Supplier Name</label>
   <select id="storematreceipttemp-supplier_code" class="js-example-basic-multiple form-control form-control-sm" name="StoreMatReceiptTemp[Supplier_Code]">
		<option value="">-- Select --</option>
		<?php foreach($suppliers as $r){ 
		$sel='';if($r['Supplier_Code']==$model->Supplier_Code){$sel='selected="selected"';}
			echo '<option '.$sel.'alt="'.$r['Phone_no'].'" label1="'.$r['Supplier_address'].'" value="'.$r['Supplier_Code'].'">'.$r['Supplier_name'].'</option>';
		 } ?>
	   
	</select>
	 
	<div class="help-block"></div>
</div>
	<?= $form->field($model, 'Memo_no', ['options' => ['class'=>'form-group col-sm-4']])->textInput(['class'=>'form-control form-control-sm'])?>
	<?= $form->field($model, 'Memo_Date', ['options' => ['class'=>'form-group col-sm-4']])->textInput(['class'=>'date_picker form-control-sm form-control','readonly'=>'readonly'])?>
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
	<?=$form->field($model,'CLASSIFICATION_CODE',['options' => ['class'=>'form-group col-sm-4']])->dropDownList($groups,['' => '--Select--','class'=>'form-control form-control-sm']) ?>
	
	<?php $category = ArrayHelper::map($category, 'ITEM_CAT_CODE', 'ITEM_CAT_NAME'); ?>
	<?=$form->field($model,'ITEM_CAT_CODE',['options' => ['class'=>'form-group col-sm-4']])->dropDownList($category,['prompt' => '--Select--','class'=>'js-example-basic-multiple form-control form-control-sm'])?>
 	<?=$form->field($model,'ITEM_CODE',['options' => ['class'=>'form-group col-sm-4']])->dropDownList(['' => '--Select--'],['class'=>'js-example-basic-multiple form-control form-control-sm']) ?>
	<?= $form->field($model, 'item_type', ['options' => ['class'=>'form-group col-sm-4']])->textInput(['id'=>'item_alt','class'=>'form-control form-control-sm','readonly' => "readonly"]) ?>
			
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

    <?= $form->field($model, 'Edu_Cess', ['options' => ['class'=>'form-group col-sm-4']])->textInput(['class'=>'form-control form-control-sm']) ?>

    <?= $form->field($model, 'SED', ['options' => ['class'=>'form-group col-sm-4']])->textInput(['class'=>'form-control form-control-sm']) ?>

    <?= $form->field($model, 'ED', ['options' => ['class'=>'form-group col-sm-4']])->textInput(['class'=>'form-control form-control-sm']) ?>

    <?= $form->field($model, 'Packing_Forword', ['options' => ['class'=>'form-group col-sm-4']])->textInput(['class'=>'form-control form-control-sm']) ?>
    <?= $form->field($model, 'Discount', ['options' => ['class'=>'form-group col-sm-4']])->textInput(['class'=>'form-control form-control-sm']) ?>
    <?= $form->field($model, 'Cartage', ['options' => ['class'=>'form-group col-sm-4']])->textInput(['class'=>'form-control form-control-sm']) ?>

    <?= $form->field($model, 'Insurance', ['options' => ['class'=>'form-group col-sm-4']])->textInput(['class'=>'form-control form-control-sm']) ?>
    <?= $form->field($model, 'Surcharge', ['options' => ['class'=>'form-group col-sm-4']])->textInput(['class'=>'form-control form-control-sm']) ?>


    <?= $form->field($model, 'Octroi', ['options' => ['class'=>'form-group col-sm-4']])->textInput(['class'=>'form-control form-control-sm']) ?>

    <?= $form->field($model, 'Remark', ['options' => ['class'=>'form-group col-sm-10']])->textInput(['class'=>'form-control form-control-sm']) ?>

</div>  </fieldset>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? $sub_btn : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?> <?= \yii\helpers\Html::a( 'Back', Yii::$app->request->referrer, ['class' => 'btn btn-info']); ?>
    </div>

    <?php ActiveForm::end(); ?>
<script>
var BASE_URL='<?=Yii::$app->homeUrl?>';
var securekey='<?=$menuid?>';
var csrf=$("#_csrf").val();
function get_cat_code(cat_id){
	var ccode= $('#storematreceipttemp-classification_code').val();
	$.ajax({
		url:BASE_URL+'inventory/default/get_cat_code?securekey='+securekey,
		type:'POST',
		data:{cat_id:cat_id,ccode:ccode,page:1,_csrf:csrf},
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
function get_emp(dept_id){
			$.ajax({
				url:BASE_URL+'inventory/default/get_dept_emp?securekey='+securekey,
				type:'POST',
				data:{dept_id:dept_id,_csrf:csrf},
				datatype:'json',
				success:function(data){
					$('#storematreceipttemp-emp_code').html(data);
					var emp_id='<?=$model->Emp_code?>';
					$('#storematreceipttemp-emp_code').val(emp_id);
				}
			  });
	
	}
 
	
$(function(){
		 //$('#storematreceipttemp-qtyo, #storematreceipttemp-qtys').keyup(function(){
		 $('#storematreceipttemp-qtyo, #storematreceipttemp-qtys').on('keyup blur', function (e) {
			 
			var qtyo=$('#storematreceipttemp-qtyo').val();
			var qtys=$('#storematreceipttemp-qtys').val();
			if(qtyo=='' || qtyo<0){qtyo=0;$('#storematreceipttemp-qtyo').val(0);}
			if(qtys=='' || qtys<0){qtys=0;$('#storematreceipttemp-qtys').val(0);}
			//qtyo=parseInt(qtyo);
			//qtys=parseInt(qtys);
			 var qtyr=eval(qtyo)-eval(qtys);
			 if(qtyr<0){qtyr=0;}
			$('#storematreceipttemp-qtyr').val(qtyr);
			 
		 });
	
		$('#storematreceipttemp-classification_code').change(function(){
		  	$('#storematreceipttemp-item_cat_code').val('');
		  	$('#storematreceipttemp-item_cat_code').select2();
			  $('#storematreceipttemp-item_code').val('');
		  	$('#storematreceipttemp-item_code').select2();
		  });
	
		  $('#storematreceipttemp-item_code').change(function(){
			   var alt= $('option:selected', this).attr('alt');
 			   var units= $('option:selected', this).attr('label1');
			   $("#item_alt").val(alt);
			   $("#storematreceipttemp-measuring_unit option:contains(" + units + ")").prop('selected', true);
  		  });
		   $('#storematreceipttemp-supplier_code').change(function(){
			   /*if($('#storematreceipttemp-supplier_code').val()=='other'){
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
			   }*/
			   var phone= $('option:selected', this).attr('alt');
 			   var addr= $('option:selected', this).attr('label1');
			   $("#storematreceipttemp-phoneno").val(phone);
			   $("#storematreceipttemp-address").val(addr);
  		  });
		  $('#storematreceipttemp-item_cat_code').change(function(){
 					var cat_id= $(this).val();
			  		$("#storematreceipttemp-measuring_unit").val('');
 					$.ajax({
					url:BASE_URL+'inventory/default/get_cat_code?securekey='+securekey,
					type:'POST',
					data:{cat_id:cat_id,page:1,_csrf:csrf},
					datatype:'json',
					success:function(data){
						$('#storematreceipttemp-item_code').html(data);
					}
				  });
			});
	
		$('#storematreceipttemp-dept_code').change(function(){
 					var dept_id= $(this).val();
 					$.ajax({
					url:BASE_URL+'inventory/default/get_dept_emp?securekey='+securekey,
					type:'POST',
					data:{dept_id:dept_id,_csrf:csrf},
					datatype:'json',
					success:function(data){
						$('#storematreceipttemp-emp_code').html(data);
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
		function getdeptemp(){
 					var dept_id= '<?=$model->Dept_code?>';
 					var emp_code= '<?=$model->Emp_code?>';
 					$.ajax({
						url:BASE_URL+'inventory/default/get_dept_emp?securekey='+securekey,
						type:'POST',
						data:{dept_id:dept_id,emp_code:emp_code,_csrf:csrf},
						datatype:'json',
						success:function(data){
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
	
</script>
 
<?php if(!empty($model->Dept_code)){?>
<script type="text/javascript">
	var dept_id='<?=$model->Dept_code?>';
	var cat_id='<?=$model->ITEM_CAT_CODE?>';
	get_emp(dept_id);
	get_cat_code(cat_id);
 	var phone= $('option:selected', "#storematreceipttemp-supplier_code").attr('alt'); 
 	var addr= $('option:selected', "#storematreceipttemp-supplier_code").attr('label1'); 
	$("#storematreceipttemp-phoneno").val(phone);
	$("#storematreceipttemp-address").val(addr);
</script>
<?php }  ?>                   
               
