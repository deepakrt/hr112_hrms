
<?php
$this->title= 'Apply Leave';
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\MasterLeaveType;
$depts = Yii::$app->utility->get_dept(null);
$desgs = Yii::$app->utility->get_designation(null);
$depts = ArrayHelper::map($depts, 'dept_id', 'dept_name');
$desgs = ArrayHelper::map($desgs, 'desg_id', 'desg_name');

?>
<div class="employee-leaves-requests-form">
<?php $form = ActiveForm::begin(['action'=>Yii::$app->homeUrl.'admin/manageempleave/apply', 'options' => ['enctype' => 'multipart/form-data']]); ?>
<br> 
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
<?php
$list = ArrayHelper::map(MasterLeaveType::find()->select('lt_id, desc')->all(), 'lt_id', 'desc');
     echo $form->field($model, 'leave_type')->dropDownList($list, ['prompt'=>'Select'])->label(); 
        ?>
    
</div><div class="col-sm-5">
    <?= $form->field($model, 'whetherhalfday')->dropDownList([ 'FULL' => 'Full Day', 'F-HALF' => 'FIrst-Half', 'S-HALF' => 'Secound-Half', ])->label('&nbsp') ?>
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
<link href="<?=Yii::$app->homeUrl?>css/bootstrap-datepicker.css" rel="stylesheet">
<script src="<?=Yii::$app->homeUrl?>js/bootstrap-datepicker.js"></script>
<script>

    $(document).ready(function(){
     $("#employeeleavesrequests-from").datepicker({
        numberOfMonths: 2,
        onSelect: function (selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate() + 1);
            $("#employeeleavesrequests-till").datepicker("option", "minDate", dt);
        }
    }).click(function(){
        	$('.datepicker-days').css('display','block');
    	}); 
    $("#employeeleavesrequests-till").datepicker({
        numberOfMonths: 2,
        onSelect: function (selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate() - 1);
            $("#employeeleavesrequests-from").datepicker("option", "maxDate", dt);
        }
    }).click(function(){
        	$('.datepicker-days').css('display','block');
    	}); 
	 
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
