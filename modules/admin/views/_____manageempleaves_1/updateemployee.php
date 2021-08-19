<link href="<?=Yii::$app->homeUrl?>css/bootstrap-datepicker.css" rel="stylesheet">
<script src="<?=Yii::$app->homeUrl?>js/bootstrap-datepicker.js"></script>
<?php
$this->title= 'Apply Leave';
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
$depts = Yii::$app->utility->get_dept(null);
$desgs = Yii::$app->utility->get_designation(null);
$depts = ArrayHelper::map($depts, 'dept_id', 'dept_name');
$desgs = ArrayHelper::map($desgs, 'desg_id', 'desg_name');

?>
<div class="employee-leaves-requests-form">
<?php $form = ActiveForm::begin(['action'=>Yii::$app->homeUrl.'admin/manageempleave/apply', 'options' => ['enctype' => 'multipart/form-data']]); ?>
 
    <div class="row">

    <?= $form->field($model, 'e_id')->hiddenInput(['value'=>Yii::$app->user->identity->e_id])->label(false) ?>
<div class="col-sm-5">
    <?= $form->field($model, 'leave_reason')->textInput(['maxlength' => true]) ?>
</div><div class="col-sm-5">
    <?= $form->field($model, 'availing_for_LTC')->dropDownList([ 'Y' => 'Yes', 'N' => 'No', ], ['prompt' => '--Select--']) ?>
</div><div class="col-sm-5">
    <?= $form->field($model, 'contact_address')->textInput(['maxlength' => true]) ?>
</div><div class="col-sm-5">
    <?= $form->field($model, 'contact_no')->textInput(['maxlength' => true]) ?>
</div><div class="col-sm-5">
    <?= $form->field($model, 'leave_type')->textInput() ?>
</div><div class="col-sm-5">
    <?= $form->field($model, 'whetherhalfday')->dropDownList([ 'FULL' => 'Full Day', 'F-HALF' => 'FIrst-Half', 'S-HALF' => 'Secound-Half', ], ['prompt' => '--Select--']) ?>
</div><div class="col-sm-5">
    <?= $form->field($model, 'from')->textInput() ?>
</div><div class="col-sm-5">
    <?= $form->field($model, 'till')->textInput() ?>
    </div><div class="col-sm-5">

   
 
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Apply' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
</div> 
     
</div>
<?php ActiveForm::end(); ?>
<script>
    $(document).ready(function(){
        $("#changeEmpImage").click(function(){
            $("#changeempimgae").show();
            $("#showempimgae").remove();
            $("#chk_image_change").val('1');
            $("#employee-emp_image").attr('required',true);
            
        });
        $("#changeEmpSign").click(function(){
            $("#changeempsign").show();
            $("#showempsign").remove();
            $("#chk_sign_change").val('1');
            $("#employee-emp_signature").attr('required',true);
        });
    });
</script>
