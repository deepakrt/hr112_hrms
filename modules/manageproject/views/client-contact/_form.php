<?php 
//echo "<pre>";print_r(Yii::$app->user->identity->dept_id);
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\EfileMasterCategory;
use app\models\efile_master_project;

$url = Yii::$app->homeUrl."manageproject/client-contact/create";
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
	 
<?php $Prid = Yii::$app->utility->encryptString($model->id); ?>

<div class="ordermaster-form">
<?php $form = ActiveForm::begin(['action'=>'', 'id'=>'clientContact', 'options' => ['enctype' => 'multipart/form-data']]); ?>

<style>
    .col-sm-12{
        margin-bottom: 10px;
    }
</style>
<h6><b><u>Client Information</u></b></h6>
<input type='hidden' name='key' value='<?=$menuid?>' readonly />
<!--<input type='hidden' name='rec_id' value='<?//=$rec_id?>' readonly />-->

<div class="row">
    
    <div class="col-sm-8">
        <?php if(isset($_GET['view']) || ($this->context->action->id=='update')) {?>
            <?= $form->field($model, 'clientid')->DropDownList(ArrayHelper::map($this->params['client'],'id','deptName'),
                    ['disabled' => 'true', 'prompt'=>'Please select','onchange'=>'
                        $.post( "'.Yii::$app->urlManager->createUrl('proposal/lists?id=').'"+$(this).val(), function( data ) {
                            $( "select#ordermaster-proposalid" ).html( data );
                        });
                        ', 'placeholder'=>$model->getAttributeLabel('clientid'), 'class'=>'form-control form-control-sm']);?>
        <?php } else {?>
            <?= $form->field($model, 'clientid')->DropDownList(ArrayHelper::map($this->params['client'],'id','deptName'),
                    [
                        'disabled' => 'true',
                        'prompt'=>'Please select',
                        'onchange'=>'
                        $.post( "'.Yii::$app->urlManager->createUrl('manageproject/proposal/lists?securekey='.$menuid.'&id=').'"+$(this).val(), function( data ) {
                            $( "select#ordermaster-proposalid" ).html( data );
                        });
                        ', 'placeholder'=>$model->getAttributeLabel('clientid'), 'class'=>'form-control form-control-sm']);?>
        <?php }?>
    </div>
    <div class="col-sm-4">
        <?= $form->field($model, 'proposalid')->DropDownList(ArrayHelper::map($this->params['status'],'id','proposalnumber'),
                        ['disabled' => 'true', 'prompt'=>'Please select', 'class'=> 'form-control form-control-sm']); ?>        
    </div>
    <div class="col-sm-12">
        <?= $form->field($model, 'projectname')->textInput(['disabled' => 'true', 'placeholder'=>$model->getAttributeLabel('projectname'), 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?>
    </div>
    <div class="col-sm-6">        
        <?= $form->field($model, 'fundingagency')->textInput(['disabled' => 'true', 'class'=>'form-control form-control-sm', 'prompt' => 'Select Project']) ?>
    </div>    
    <div class="col-sm-3">        
        <?= $form->field($modelp, 'projectstartdate')->textInput(['disabled' => 'true', 'placeholder'=>$model->getAttributeLabel('projectstartdate'), 'class'=>'form-control form-control-sm date_picker']) ?>        
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'amount')->textInput([ 'disabled' => 'true', 'placeholder'=>$model->getAttributeLabel('amount'), 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?>
    </div>
    <div class="col-sm-6">
        <?= $form->field($model, 'number')->textInput(['disabled' => 'true',  'placeholder'=>$model->getAttributeLabel('number'), 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($modelp, 'status')->DropDownList(['On Going' => 'On Going', 'Closed'=>'Closed', 'Completed' => 'Completed', 'Terminated' => 'Terminated'], ['disabled' => 'true', ]) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($modelp, 'expectedenddate')->textInput(['disabled' => 'true', 'placeholder'=>$model->getAttributeLabel('expectedenddate'), 'class'=>'form-control form-control-sm date_picker']) ?>        
    </div>
    <div class="col-sm-6">
        <?= $form->field($modelp, 'projectrefno')->textInput(['disabled' => 'true', 'placeholder'=>$modelp->getAttributeLabel('projectrefno'), 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?>
    </div>  
    <div class="col-sm-3">
        <?= $form->field($modelp, 'filenumber')->textInput(['disabled' => 'true', 'placeholder'=>$modelp->getAttributeLabel('filenumber'), 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?>
    </div>   
    <div class="col-sm-3">
        <?= $form->field($modelp, 'projecttypeid')->DropDownList(ArrayHelper::map($this->params['projectType'],'id','type'),
                    ['disabled' => 'true', 'prompt'=>'Please select','placeholder'=>$modelp->getAttributeLabel('projecttypeid'), 'class'=>'form-control form-control-sm']) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($modelp, 'completionreport')->radioList(array(1 => 'Yes', 0 => 'No'), array('separator' => '                  ')) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($modelp, 'appreciationcert')->radioList(array(1 => 'Recieved', 0 => 'Not Recieved'), array('separator' => '                  ')) ?>
    </div> 
    <div class="col-sm-3">
        <?= $form->field($modelp, 'actualcompletiondate')->textInput(['disabled' => 'true', 'placeholder'=>$model->getAttributeLabel('actualcompletiondate'), 'class'=>'form-control form-control-sm date_picker'])?>
    </div>
    <div class="col-sm-12">
        <?= $form->field($modelp, 'objectives')->textArea(['disabled' => 'true', 'placeholder'=>$modelp->getAttributeLabel('objectives'), 'class'=>'form-control form-control-sm', 'maxlength' => true, 'rows' => 2]) ?>
    </div>
    <div class="col-sm-12">
        <?= $form->field($modelp, 'finaloutcome')->textInput(['disabled' => 'true', 'placeholder'=>$modelp->getAttributeLabel('finaloutcome'), 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?>
    </div>
    <div class="col-sm-12">
        <?php $tech=$this->params['tech']; ?>
        <?= $form->field($modelp, 'technologyid')->checkboxList(ArrayHelper::map($tech,'id','technology'),
                        ['disabled' => 'true', 
                            'separator' => '<br>',                            
                            'itemOptions' => [
                                'multiple'=>'multiple',                                
                                ],                           
                        ])?>        
    </div>
    <div class="col-sm-12">
    <?= $form->field($modelp, 'referenceid')->DropDownList(ArrayHelper::map($this->params['allprojects'],'id','projectname','fundingagency'),
                    ['disabled' => 'true', 'prompt'=>'Please select','placeholder'=>$modelp->getAttributeLabel('referenceid'), 'class'=>'form-control form-control-sm']) ?>
    </div>
    <div class="col-sm-12">
        <?= $form->field($modelp, 'remarks')->textArea(['disabled' => 'true', 'maxlength' => true, 'rows' => 6, 'placeholder'=>$modelp->getAttributeLabel('remarks'), 'class'=>'form-control form-control-sm']) ?>
    </div>    
    
    <div class="col-sm-12">        
        <?php /*if(($modelp->id !=NULL) && ($modelp->projectci->investigator !=NULL)){ ?>
                    <b>Chief Investigator: </b><?php echo $modelp->projectci->investigator->name->name; ?>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <b>Co-Investigator: </b><?php  echo $modelp->projectci->investigator->co->name;?>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <b>Team Leader: </b><?php echo $modelp->projectci->investigator->lead->name;?> <br/><br/>
                <?php }*/?>        
    </div>    

    <?= $form->field($modelc, 'name')->textInput(['maxlength' => true])->label('Person in Contact') ?>

            <?= $form->field($modelc, 'phone')->textInput()->label('Phone Number') ?>

            <?= $form->field($modelc, 'mobile')->textInput()->label('Mobile Number') ?>

            <?= $form->field($modelc, 'email')->textInput(['maxlength' => true])->label('eMail') ?>
    
<?php ActiveForm::end(); ?>
    
</div>
<link href="<?=Yii::$app->homeUrl?>css/bootstrap-datepicker.css" rel="stylesheet">
<script src="<?=Yii::$app->homeUrl?>js/bootstrap-datepicker.js"></script>