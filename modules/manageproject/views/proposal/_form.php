<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
$label= $model->attributeLabels();

$url = Yii::$app->homeUrl."manageproject/proposal/update";

?>
<link href="<?=Yii::$app->homeUrl?>css/select2.min.css" rel="stylesheet" />
<script src="<?=Yii::$app->homeUrl?>js/select2.min.js"></script>
<input type="hidden" id="menuid" value="<?=$menuid?>" readonly="" />

<style>
.row.addmorerow {
	width: 90%;
	margin-top: 5px;
}
.cost_category {
	padding: 0 !important;
}
</style>
	 
<?php if(isset($_GET['view']) || ($this->context->action->id=='update' && !empty($model->project_id))) {?>
<script>
    $(document).ready(function(){
        $("#proposal :input").prop("disabled", true);  
        
    });
</script>
<?php }

$Prid = Yii::$app->utility->encryptString($model->id); 

if($model->submissiondate != NULL){
    $model->submissiondate = date('d-m-Y',strtotime($model->submissiondate));
}
$form = ActiveForm::begin(['action'=>'', 'id'=>'proposal', 'options' => ['enctype' => 'multipart/form-data']]); ?>

<style>
    .col-sm-12{
        margin-bottom: 10px;
    }
</style>

<h6><b><u>Project Information</u></b></h6>
<input type='hidden' name='key' value='<?=$menuid?>' readonly />
<div class="row">
    <input type="hidden" class="form-control form-control-sm" id="project_id" name="Proposal[key_encript]" value="<?=Yii::$app->utility->encryptString($model->id);?>" readonly="">
    <div class="col-sm-8">
        <?php if(isset($_GET['view']) || ($this->context->action->id=='update')) {?>
            <?= $form->field($model, 'clientid')->DropDownList(ArrayHelper::map($this->params['client'],'id','deptName'),
                ['disabled' => 'true', 'prompt'=>'Please select', 'placeholder'=>$model->getAttributeLabel('clientid'), 'class'=>'form-control form-control-sm']);?>
        <?php } else {?>
            <?= $form->field($model, 'clientid')->DropDownList(ArrayHelper::map($this->params['client'],'id','deptName'),
                ['prompt'=>'Please select', 'placeholder'=>$model->getAttributeLabel('clientid'), 'class'=>'form-control form-control-sm']);?>
        <?php } ?>
        
    </div>
    <div class="col-sm-4">        
        <?= $form->field($model, 'submissiondate')->textInput(['placeholder'=>$model->getAttributeLabel('submissiondate'), 'class'=>'form-control form-control-sm date_picker']) ?>        
    </div>
    <div class="col-sm-6">
        <?= $form->field($model, 'proposalnumber')->textInput(['placeholder'=>$model->getAttributeLabel('proposalnumber'), 'class'=>'form-control form-control-sm'])?>        
    </div>
    
    <div class="col-sm-3">
        <?= $form->field($model, 'cost')->textInput(['placeholder'=>$model->getAttributeLabel('cost'), 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?>               
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'validity')->textInput(['placeholder'=>$model->getAttributeLabel('validity'), 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?>
    </div>    
    <div class="col-sm-3">
        <?php $projectType = $this->params['projectType'] ?>
        <?= $form->field($model, 'proposaltype')->DropDownList(ArrayHelper::map($projectType,'id','type'),
            ['prompt'=>'Please select','placeholder'=>$model->getAttributeLabel('proposaltype'), 'class'=>'form-control form-control-sm']); ?>                
    </div>
    <div class="col-sm-3">
        <?php $client=$this->params['submissiontype']; ?>
        <?= $form->field($model, 'submissionmedium')->DropDownList(ArrayHelper::map($client,'id','type'),
                            ['prompt'=>'Select medium of submission', 'placeholder'=>$model->getAttributeLabel('submissionmedium'), 'class'=>'form-control form-control-sm']); ?>                
    </div>
    
    <div class="col-sm-12">
        <?= $form->field($model, 'remarks')->textInput(['placeholder'=>$model->getAttributeLabel('remarks'), 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?>
    </div>
    <div class="col-sm-12">
        <!--<?//= $form->field($model, 'activeuser')->hiddenInput(['value' => userid()])->label(FALSE) ?>
        <?//= $form->field($model, 'sessionid')->hiddenInput(['maxlength' => true, 'value' => sessionid()])->label(FALSE) ?>-->
    </div>
    <?php 
    if(!empty($model->project_id)){?>
        <div class="col-sm-3"><?= $form->field($model, 'status')->dropDownList([ 'Started' => 'Started', 'InProcess' => 'InProcess', 'Completed' => 'Completed'], ['prompt' => 'Select Status', 'class'=>'form-control form-control-sm', 'title'=>$label['status']]) ?></div>
        <div class="col-sm-3"><?= $form->field($model, 'is_active')->dropDownList([ 'Y' => 'Yes', 'N' => 'No'], ['prompt' => 'Select Is Active', 'class'=>'form-control form-control-sm', 'title'=>$label['is_active']]) ?></div>
    <?php } ?>
     <div class="col-sm-12 text-center">
	<?php if($this->context->action->id=='update' || empty($model->id)) {?>
            <?php if($this->context->action->id=='update') {?>
         <input type="hidden" id="enterpcb" name="Proposal[enterpcb]" readonly value="">
            <?php } ?>
            <?php if(!isset($_GET['view']) || ($this->context->action->id=='update' && !empty($model->project_id))) {?>
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm']) ?>
                <a href="<?=Yii::$app->homeUrl?>manageproject/proposal?securekey=<?=$menuid?>"  class="btn btn-danger btn-sm">Cancel</a>
            <?php } ?>
	 <?php } ?>
    </div>
    
<?php ActiveForm::end(); ?>


    <!--<?//= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                    <?php // if (isset($_SESSION['prjsession'])) { 
                    //} else { ?>
                        <?//= Html::a(Yii::t('app', 'Cancel'), ['proposal/index'], ['class' => 'btn btn-default']) ?>
                    <?php //} ?>-->


<link href="<?=Yii::$app->homeUrl?>css/bootstrap-datepicker.css" rel="stylesheet">
<script src="<?=Yii::$app->homeUrl?>js/bootstrap-datepicker.js"></script>