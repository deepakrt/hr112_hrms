<!------ Include the above in your HEAD tag ---------->
	<link href="<?=Yii::$app->homeUrl?>css/select2.min.css" rel="stylesheet" />
	<script src="<?=Yii::$app->homeUrl?>js/select2.min.js"></script>
	<?php
		use yii\helpers\Html;
		use yii\widgets\ActiveForm;
		use yii\helpers\ArrayHelper;
		//echo "<pre>";print_r(Yii::$app->user->identity);die;

		// echo "<pre>"; print_r($model); die();
	?>

 
							
	<?php $form = ActiveForm::begin(); ?>
    <div class='row'>
			<?= $form->field($model, 'dept_name', ['options' => ['class'=>'form-group col-sm-6']])->textInput(['value' => trim(Yii::$app->user->identity->dept_name),'readonly'=>'readonly'],['maxlength' => true]) ?>
			<?= $form->field($model, 'dept_id')->hiddenInput(['value'=>Yii::$app->user->identity->dept_id])->label(false);?>
			 
			<?= $form->field($model, 'employee', ['options' => ['class'=>'form-group col-sm-6']])->textInput(['value' => trim(Yii::$app->user->identity->fullname),'readonly'=>'readonly'],['maxlength' => true]) ?>
			<?php 
			
			    echo "<pre>";
			    print_r($groups);
			    echo "</pre>";
			    $groups = ArrayHelper::map($groups, 'CLASSIFICATION_CODE', 'CLASSIFICATION_NAME');
			    echo "<pre>";
			    print_r($groups);
			    echo "</pre>";
			
			?>
			<?=$form->field($model,'group',['options' => ['class'=>'form-group col-sm-6']])->dropDownList($groups)?>
			
			<?php /*$cost_centre = ArrayHelper::map($cost_centre, 'SUB_DEPT_CODE', 'SUB_DEPT_NAME'); ?>
			<?=$form->field($model,'cost_centre',['options' => ['class'=>'form-group col-sm-5']])->dropDownList($cost_centre,['prompt' => '--Select--','class'=>'js-example-basic-multiple form-control form-control-sm','disabled'=>true])*/?>
			 
			<?php $category = ArrayHelper::map($category, 'ITEM_CAT_CODE', 'ITEM_CAT_NAME'); ?>
			<?=$form->field($model,'category',['options' => ['class'=>'form-group col-sm-6']])->dropDownList($category,['prompt' => '--Select--','class'=>'js-example-basic-multiple form-control form-control-sm'])?>
			
			 
			<?=$form->field($model,'item',['options' => ['class'=>'form-group col-sm-6']])->dropDownList(['' => '--Select--'],['class'=>'js-example-basic-multiple form-control form-control-sm'])->label('Item')?>
			 
			<?= $form->field($model, 'item_type', ['options' => ['class'=>'form-group col-sm-6']])->textInput(['id'=>'item_alt','readonly' => "readonly"]) ?>

			<input type="hidden" id="data_type_id" name="data_type_id" value="" />
				
			<?php $unit_master = ArrayHelper::map($unit_master, 'Unit_id', 'Unit_Name'); ?>
			<?=$form->field($model,'units',['options' => ['class'=>'form-group col-sm-6']])->dropDownList($unit_master,['prompt' => '--Select--'])?>
			
			<?= $form->field($model, 'qty_required', ['options' => ['class'=>'form-group col-sm-6']])->textInput(['placeholder' => "Qty Required"],['maxlength' => true]) ?>
			
			 
			<?= $form->field($model, 'purpose',['options' => ['class'=>'form-group col-sm-6']])->textArea(['placeholder' => "Purpose"],['maxlength' => true]) ?>
			<?= $form->field($model, 'remarks',['options' => ['class'=>'form-group col-sm-6']])->textArea(['placeholder' => "Remarks"],['maxlength' => true]) ?>
		</div>    
    <div class="form-group">
			<div class="help-block qmsj"></div>
			<?= Html::submitButton('Submit', ['class' => 'btn btn-primary sub_btn']) ?>
    </div>
  <?php ActiveForm::end(); ?>
	<script>
		var BASE_URL='<?=Yii::$app->homeUrl?>';
		var securekey='<?=$menuid?>';
		$(function(){
		 
		  $('#inventory-item').change(function(){
		  	// data_type_id
			   $("#data_type_id").val('');

			    var alt= $('option:selected', this).attr('alt');
			    var data_type_id= $('option:selected', this).attr('data_type_id');
 			    var units= $('option:selected', this).attr('label1');
 			    var quantity= $('option:selected', this).data('quantity');
			    $(".sub_btn").attr('disabled',false);
			    $(".sub_btn").css('cursor','pointer');
			    $(".qmsj").html('');
 			   if(quantity<1){
				$(".qmsj").html('<b>Item Out Of Stock');
				$(".sub_btn").attr('disabled',true);
				$(".sub_btn").css('cursor','not-allowed');
			   }
			   $("#item_alt").val(alt);
			   $("#data_type_id").val(data_type_id);
			   $("#inventory-units option:contains(" + units + ")").attr('selected', 'selected');
  		  });
		  $('#inventory-group').change(function(){
			   $('#inventory-category').val('');
			   $('#inventory-category').select2();
		   });
	
		  $('#inventory-category').change(function(){
 					var cat_id= $(this).val();
 					var ccode= $('#inventory-group').val();
 					$.ajax({
						url:BASE_URL+'inventory/default/get_cat_code?securekey='+securekey,
						type:'POST',
						data:{cat_id:cat_id,ccode:ccode,page:1},
						datatype:'json',
						success:function(data){
							$('#inventory-item').html(data);
							$("#inventory-units").val('');
						}
					  });
				}); 
		}); 
	</script>    