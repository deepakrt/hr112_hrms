<?php
$this->title= 'Update Item';
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin(); ?>
<input type="hidden" name="Item[itm_id]" value="<?=Yii::$app->utility->encryptString($model->itm_id);?>" readonly="" />

     <div class='row'>
 		 
		<?php $groups = ArrayHelper::map($groups, 'CLASSIFICATION_CODE', 'CLASSIFICATION_NAME'); ?>
		<?=$form->field($model,'group',['options' => ['class'=>'form-group col-sm-6']])->dropDownList($groups)?>
		
		<?php /*$cost_centre = ArrayHelper::map($cost_centre, 'SUB_DEPT_CODE', 'SUB_DEPT_NAME'); ?>
		<?=$form->field($model,'cost_centre',['options' => ['class'=>'form-group col-sm-5']])->dropDownList($cost_centre,['prompt' => '--Select--','class'=>'js-example-basic-multiple form-control form-control-sm','disabled'=>true])*/?>
		 
		<?php $category = ArrayHelper::map($category, 'ITEM_CAT_CODE', 'ITEM_CAT_NAME'); ?>
		<?=$form->field($model,'category',['options' => ['class'=>'form-group col-sm-6']])->dropDownList($category,['prompt' => '--Select--','class'=>'js-example-basic-multiple form-control form-control-sm'])?>
		
		 
		<?=$form->field($model,'item',['options' => ['class'=>'form-group col-sm-6']])->dropDownList(['' => '--Select--'],['class'=>'js-example-basic-multiple form-control form-control-sm'])->label('Item')?>

                <?= $form->field($model, 'item_name', ['options' => ['class'=>'form-group col-sm-6']])->textInput(['placeholder' => "Item Name"],['maxlength' => true]) ?>
		 
		<?= $form->field($model, 'item_type', ['options' => ['class'=>'form-group col-sm-6']])->textInput(['id'=>'item_alt','readonly' => "readonly"]) ?>
			
		<?php $unit_master = ArrayHelper::map($unit_master, 'Unit_id', 'Unit_Name'); ?>
		<?=$form->field($model,'units',['options' => ['class'=>'form-group col-sm-6']])->dropDownList($unit_master,['prompt' => '--Select--'])?>
		
		<?= $form->field($model, 'qty_required', ['options' => ['class'=>'form-group col-sm-6']])->textInput(['placeholder' => "Qty Required"],['maxlength' => true]) ?>
		

		</div>

    <div class="row">
        <div class="col-sm-12 text-center">
            <button type="submit" class="btn btn-success btn-sm sl">Submit</button>
            <a href="<?=Yii::$app->homeUrl?>inventory/item?securekey=<?=$menuid?>" class="btn btn-danger btn-sm">Cancel</a>
        </div>
    </div>
<?php ActiveForm::end(); ?>
