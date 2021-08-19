<?php
//$this->title= 'Supplier';
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
$category=Yii::$app->inventory->get_category();
?>
<?php 
	$form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);

 ?>
<div class="row">
    <div class="col-sm-12"><?= $form->field($model, 'Supplier_name')->textInput(['placeholder'=>'Supplier Name', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>
    <div class="col-sm-12"><?= $form->field($model, 'Supplier_address')->textArea(['placeholder'=>'Supplier Address', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>
<div class="col-sm-12"><?= $form->field($model, 'Phone_no')->textInput(['placeholder'=>'Phone No', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>


 
 <!--<?php /*$category = ArrayHelper::map($category, 'ITEM_CAT_CODE', 'ITEM_CAT_NAME'); ?>
<div class="col-sm-12"><?=$form->field($model,'Category')->checkboxList($category, ['class'=>'form-control form-control-sm supplycat'])*/ ?></div>
   <div class="col-sm-12"><?php /* $form->field($model, 'is_active')->dropDownList([ 'Y' => 'Yes', 'N' => 'No',], ['class'=>'form-control form-control-sm', 'prompt' => 'Select Is Active'])*/ ?></div> -->

<?php
	// echo "helooo"; die();
	
?>
    <div class="col-sm-12 text-center">
        <button type="submit" class="btn btn-success btn-sm sl">Submit</button>
        <a href="<?=Yii::$app->homeUrl?>inventory/supplier?securekey=<?=$menuid?>" class="btn btn-danger btn-sm">Cancel</a>
    </div>
</div>
<?php ActiveForm::end(); ?>
