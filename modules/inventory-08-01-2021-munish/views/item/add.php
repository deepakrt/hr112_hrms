  <!------ Include the above in your HEAD tag ---------->
<link href="<?=Yii::$app->homeUrl?>css/select2.min.css" rel="stylesheet" />
<script src="<?=Yii::$app->homeUrl?>js/select2.min.js"></script>
<?php
 use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
//echo "<pre>";print_r(Yii::$app->user->identity);die;
?>

 <?php $form = ActiveForm::begin(); ?>
        <div class='row'>
 		 
		<?php $groups = ArrayHelper::map($groups, 'CLASSIFICATION_CODE', 'CLASSIFICATION_NAME'); ?>
		<?=$form->field($model,'group',['options' => ['class'=>'form-group col-sm-6']])->dropDownList($groups)?>
		
		 
		<?php $category = ArrayHelper::map($category, 'ITEM_CAT_CODE', 'ITEM_CAT_NAME'); ?>
		<?=$form->field($model,'category',['options' => ['class'=>'form-group col-sm-6']])->dropDownList($category,['prompt' => '--Select--','class'=>'js-example-basic-multiple form-control form-control-sm'])?>
		

                <?= $form->field($model, 'item_name', ['options' => ['class'=>'form-group col-sm-6']])->textInput(['placeholder' => "Item Name"],['maxlength' => true]) ?>
		 
               <?php $itemtype_master = ArrayHelper::map($itemtype_master, 'Type_id', 'Item_type'); ?>
		<?=$form->field($model,'item_type',['options' => ['class'=>'form-group col-sm-6']])->dropDownList($itemtype_master)?>
	
	
			
		<?php $unit_master = ArrayHelper::map($unit_master, 'Unit_id', 'Unit_Name'); ?>
		<?=$form->field($model,'units',['options' => ['class'=>'form-group col-sm-6']])->dropDownList($unit_master,['prompt' => '--Select--'])?>
		
			

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
			    var alt= $('option:selected', this).attr('alt');
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
                   
               
