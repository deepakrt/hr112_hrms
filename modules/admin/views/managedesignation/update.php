<?php
$this->title= 'Update Designation';
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin(); ?>
<input type="hidden" name="Designation[desg_id]" value="<?=Yii::$app->utility->encryptString($model->desg_id);?>" readonly="" />

    <div class="row">
         <div class="col-sm-4"><?= $form->field($model, 'desg_name')->textInput(['placeholder'=>'Designation Name', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>
    <div class="col-sm-4"><?= $form->field($model, 'desg_desc')->textArea(['placeholder'=>'Description', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>
    <div class="col-sm-4"><?= $form->field($model, 'is_active')->dropDownList([ 'Y' => 'Yes', 'N' => 'No',], ['class'=>'form-control form-control-sm', 'prompt' => 'Select Is Active']) ?></div>
    </div>
    <div class="row">
        <div class="col-sm-12 text-center">
            <button type="submit" class="btn btn-success btn-sm sl">Submit</button>
            <a href="<?=Yii::$app->homeUrl?>admin/managedesignation?securekey=<?=$menuid?>" class="btn btn-danger btn-sm">Cancel</a>
        </div>
    </div>
<?php ActiveForm::end(); ?>