  <!------ Include the above in your HEAD tag ---------->
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

<link href="<?=Yii::$app->homeUrl?>css/select2.min.css" rel="stylesheet" />
<script src="<?=Yii::$app->homeUrl?>js/select2.min.js"></script>
<?php
 use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
echo "asas";
echo "<pre>";print_r($model);die;
 //echo $model->Emp_code;

?>
<style>
legend {
	background-color: #c5dec5;color: #3F9E89;padding: 0px 6px;font-family: initial;font-size: 21px;
}
</style><div class="col-sm-12 text-right">
<a style="margin-bottom:10px;" href="<?=Yii::$app->homeUrl?>inventory/comparison/index?securekey=<?=$menuid?>" class="btn btn-success btn-sm mybtn">view</a>
	
</div>
 
		<?php $form = ActiveForm::begin(); //['class'=>'form-control form-control-sm']?>
		<fieldset>
        <legend>Item Details</legend>
    <div class='row'>
		
 	<?php $groups = ArrayHelper::map($groups, 'CLASSIFICATION_CODE', 'CLASSIFICATION_NAME'); ?>
	<?=$form->field($model,'CLASSIFICATION_CODE',['options' => ['class'=>'form-group col-sm-4']])->dropDownList($groups,['' => '--Select--','class'=>'js-example-basic-multiple form-control form-control-sm']) ?>
	
	<?php $category = ArrayHelper::map($category, 'ITEM_CAT_CODE', 'ITEM_CAT_NAME'); ?>
	<?=$form->field($model,'ITEM_CAT_CODE',['options' => ['class'=>'form-group col-sm-4']])->dropDownList($category,['prompt' => '--Select--','class'=>'js-example-basic-multiple form-control form-control-sm'])?>
 	<?=$form->field($model,'ITEM_CODE',['options' => ['class'=>'form-group col-sm-4']])->dropDownList(['prompt' => '--Select--'],['class'=>'js-example-basic-multiple form-control form-control-sm']) ?>

 		<?=$form->field($model,'Supplier_Code',['options' => ['class'=>'form-group col-sm-4']])->dropDownList(['prompt' => '--Select--'],['class'=>' form-control form-control-sm']) ?>	 
   	
	  
    <?= $form->field($model, 'Qty', ['options' => ['class'=>'form-group col-sm-4']])->textInput(['class'=>'form-control form-control-sm'])?>

                    <?= $form->field($model, 'tax')->radioList([1 => 'Include', 0 => 'Exclude']);?>
    <?= $form->field($model, 'Amount', ['options' => ['class'=>'form-group col-sm-4']])->textInput(['class'=>'form-control form-control-sm','readonly'=>false])?>

  
    <?= $form->field($model, 'remarks', ['options' => ['class'=>'form-group col-sm-10']])->textInput(['class'=>'form-control form-control-sm'])->label(' Remarks') ?>

</div>  </fieldset>
 <div class="form-group">
       <?= Html::submitButton('Finalize', ['name'=>'Finalize','class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

<script>
	var securekey='<?=$menuid?>';
	var csrf=$("#_csrf").val();
	var BASE_URL='<?=Yii::$app->homeUrl?>';
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
	 	  	


 $('#comparisonreport-classification_code').change(function(){

 	
		  	$('#comparisonreport-item_cat_code').val('');
		  	$('#comparisonreport-item_cat_code').select2();
			  $('#comparisonreport-item_code').val('');
		  	$('#comparisonreport-item_code').select2();
		  });
		  $('#comparisonreport-item_cat_code').change(function(){
			  		$('#item_name').remove(); 			  		
 				    $('#item_alt').attr('readonly',true);
 					var cat_id= $(this).val();
 					var ccode= $('#comparisonreport-classification_code').val();
 					$.ajax({
						url:BASE_URL+'inventory/comparison/get_cat_code?securekey='+securekey,
						type:'POST',
						data:{cat_id:cat_id,ccode:ccode,_csrf:csrf},
						datatype:'json',
						success:function(data){
							$('#comparisonreport-item_code').html(data);
							//$("#comparisonreport-measuring_unit").val('');
						}
					  });
				});
		    $('#comparisonreport-item_code').change(function(){
			  		
 					//var item_id= $(this).val();


 				var item_id= $('#comparisonreport-item_cat_code').val();
 					$.ajax({
						url:BASE_URL+'inventory/comparison/get_item_code?securekey='+securekey,
						type:'POST',
						data:{item_id:item_id,_csrf:csrf},
						datatype:'json',
						success:function(data){
							$('#comparisonreport-supplier_code').html(data);
							//$("#comparisonreport-measuring_unit").val('');
						}
					  });
				});
		  

	function startLoader()
	{
	   $("#loading").show();
	}
	 
	function stopLoader()
	{
	    $("#loading").hide();
	}             
</script>
<?php if(!empty($model->ITEM_CAT_CODE)) echo "asas";{?> 
<script type="text/javascript">
	//var dept_id='<?=$model->Dept_code?>';
	alert('');
	var cat_id='<?=$model->ITEM_CAT_CODE?>'; 
	//get_emp(dept_id);
	get_cat_code(cat_id);
 	
</script>
<?php } else { echo "asas";}  ?>  