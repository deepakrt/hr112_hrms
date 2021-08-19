<?php
$this->title= 'Update Unit';
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin(); ?>
<input type="hidden" name="Unit[Unit_id]" value="<?=Yii::$app->utility->encryptString($model->Unit_id);?>" readonly="" />

     <div class='row'>
 		
		 
		<?= $form->field($model, 'qty_required', ['options' => ['class'=>'form-group col-sm-6']])->textInput(['placeholder' => "Qty Required"],['maxlength' => true]) ?>
		

		</div>

    <div class="row">
        <div class="col-sm-12 text-center">
            <button type="submit" class="btn btn-success btn-sm sl">Submit</button>
            <a href="<?=Yii::$app->homeUrl?>inventory/unit?securekey=<?=$menuid?>" class="btn btn-danger btn-sm">Cancel</a>
        </div>
    </div>
<?php ActiveForm::end(); ?>
