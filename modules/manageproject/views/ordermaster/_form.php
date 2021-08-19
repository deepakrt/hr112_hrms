<?php
//echo "<pre>";print_r(Yii::$app->user->identity->dept_id);
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\EfileMasterCategory;
use app\models\efile_master_project;

$url = Yii::$app->homeUrl."manageproject/ordermaster/update";
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
        $("#ordermaster :input").prop("disabled", true);
    });
</script>
<?php }

$Prid = Yii::$app->utility->encryptString($model->id); ?>

<div class="ordermaster-form">
<?php 
if($modelp->end_date!=NULL){
    $modelp->end_date=date('d-m-Y',strtotime($modelp->end_date));
}
if($modelp->start_date != NULL){
    $modelp->start_date=date('d-m-Y',strtotime($modelp->start_date));
}
if($modelp->actualcompletiondate !=NULL){
    $modelp->actualcompletiondate=date('d-m-Y',strtotime($modelp->actualcompletiondate));
}
if($model->proposalsubmissiondate !=NULL){
    $model->proposalsubmissiondate=date('d-m-Y',strtotime($model->proposalsubmissiondate));
}

$form = ActiveForm::begin(['action'=>'', 'id'=>'ordermaster', 'options' => ['enctype' => 'multipart/form-data']]); ?>

<style>
    .col-sm-12{
        margin-bottom: 10px;
    }
</style>
<h6><b><u>Project Information</u></b></h6>
<input type='hidden' name='key' value='<?=$menuid?>' readonly />
<input type='hidden' name='pid' value='<?=Yii::$app->utility->encryptString($model->id)?>' readonly />
<input type='hidden' name='cid' value='<?=Yii::$app->utility->encryptString($model->clientid)?>' readonly />
<input type='hidden' name='ppid' value='' readonly />

<!--<input type='hidden' name='rec_id' value='<?//=$rec_id?>' readonly />-->

<div class="row">    
    <div class="col-sm-8">
        <?= $form->field($model, 'proposalno')->textInput(['placeholder'=>$model->getAttributeLabel('proposalno'), 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?>        
    </div>
    <div class="col-sm-4">
        <?= $form->field($model, 'proposalsubmissiondate')->textInput(['placeholder'=>$model->getAttributeLabel('proposalsubmissiondate'), 'class'=>'form-control form-control-sm date_picker']) ?>        
    </div>
    <div class="col-sm-12">
        <?= $form->field($model, 'projectname')->textInput(['placeholder'=>$model->getAttributeLabel('projectname'), 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?>
    </div>
    <div class="col-sm-6">
        <?= $form->field($modelp, 'projectrefno')->textInput(['placeholder'=>$modelp->getAttributeLabel('projectrefno'), 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?>
    </div>  
    <div class="col-sm-6">
        <?= $form->field($model, 'number')->textInput([ 'placeholder'=>$model->getAttributeLabel('number'), 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'amount')->textInput([ 'placeholder'=>$model->getAttributeLabel('amount'), 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?>
    </div>
    <div class="col-sm-3">        
        <?= $form->field($modelp, 'start_date')->textInput(['placeholder'=>$model->getAttributeLabel('start_date'), 'class'=>'form-control form-control-sm date_picker']) ?>        
    </div>  
    
    <div class="col-sm-3">
        <?= $form->field($modelp, 'end_date')->textInput(['placeholder'=>$model->getAttributeLabel('end_date'), 'class'=>'form-control form-control-sm date_picker']) ?>        
    </div>
    
    <div class="col-sm-3">
        <?= $form->field($modelp, 'filenumber')->textInput(['placeholder'=>$modelp->getAttributeLabel('filenumber'), 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?>
    </div>   
    <div class="col-sm-6">        
        <?= $form->field($model, 'fundingagency')->textInput(['class'=>'form-control form-control-sm', 'prompt' => 'Select Project']) ?>
    </div> 
    <div class="col-sm-3">
        <?= $form->field($modelp, 'project_type')->dropDownList([ 'Business' => 'Business', 'Funded' => 'Funded', 'Mission' => 'Mission', ], ['prompt' => 'Select Project Type', 'class'=>'form-control form-control-sm','placeholder'=>$modelp->getAttributeLabel('projecttypeid')])?>
        <!--<?/*= $form->field($modelp, 'projecttypeid')->DropDownList(ArrayHelper::map($this->params['projectType'],'id','type'),
                    ['prompt'=>'Please select','placeholder'=>$modelp->getAttributeLabel('projecttypeid'), 'class'=>'form-control form-control-sm'])*/ ?>-->
    </div>
    <!--<div class="col-sm-3">        
        <?//= $form->field($modelp, 'technologyid')->DropDownList(ArrayHelper::map($this->params['tech'],'id','technology'),
            //            ['prompt'=>'Please select','placeholder'=>$modelp->getAttributeLabel('technologyid'), 'class'=>'form-control form-control-sm'])?>        
    </div>-->
    <div class="col-sm-3 d-none">
        <?= $form->field($modelp, 'completionreport')->radioList(array(1 => 'Yes', 0 => 'No'), array('separator' => '                  ')) ?>
    </div>
    <div class="col-sm-3 d-none">
        <?= $form->field($modelp, 'appreciationcert')->radioList(array(1 => 'Recieved', 0 => 'Not Recieved'), array('separator' => '                  ')) ?>
    </div> 
    <div class="col-sm-3 d-none">
        <?= $form->field($modelp, 'actualcompletiondate')->textInput(['placeholder'=>$model->getAttributeLabel('actualcompletiondate'), 'class'=>'form-control form-control-sm date_picker'])?>
    </div>
    <div class="col-sm-12">
        <?= $form->field($modelp, 'objectives')->textArea(['placeholder'=>$modelp->getAttributeLabel('objectives'), 'class'=>'form-control form-control-sm', 'maxlength' => true, 'rows' => 2]) ?>
    </div>
    <div class="col-sm-12">
        <?= $form->field($modelp, 'finaloutcome')->textInput(['placeholder'=>$modelp->getAttributeLabel('finaloutcome'), 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?>
    </div>
    
    <div class="col-sm-12">
    <?= $form->field($modelp, 'reference_projectid')->DropDownList(ArrayHelper::map($this->params['allprojects'],'id','projectname','fundingagency'),
                    ['prompt'=>'Please select','placeholder'=>$modelp->getAttributeLabel('reference_projectid'), 'class'=>'form-control form-control-sm']) ?>
    </div>
    <div class="col-sm-12">
        <?= $form->field($modelp, 'description')->textArea(['maxlength' => true, 'rows' => 2, 'placeholder'=>$modelp->getAttributeLabel('description'), 'class'=>'form-control form-control-sm']) ?>
    </div>
    <div class="col-sm-12 text-center">        
	<?php if($this->context->action->id=='update' || empty($model->id)) {?>
            <?php if($this->context->action->id=='update') {?>
         <input type="hidden" id="enterpcb" name="Ordermaster[enterpcb]" readonly value="">
            <?php } ?>
            <?php if(!isset($_GET['view']) || ($this->context->action->id=='update' && !empty($model->project_id))) {?>
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm']) ?>
                <a href="<?=Yii::$app->homeUrl?>manageproject/ordermaster?securekey=<?=$menuid?>"  class="btn btn-danger btn-sm">Cancel</a>
            <?php } ?>
	 <?php } ?>
    </div>
    <div class="col-sm-12">
        <?php if(isset($_GET['view']) && $_GET['view']==1){ //if($this->context->action->id=='update' || empty($model->id) || $this->context->action->id=='addclientcontact') {?>
        <div class="col-sm-12">
            <h6><b><u>Manpower Mapping</u></b></h6>
        </div>
        <div class="col-sm-12">
            <hr>
            <table id="dataTableShow2" class="display table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>Sr. No.</th>
                        <th>Employee</th>                        
                        <th>Sectioned Post</th>
                        <th>Salary</th>
                    </tr>
                </thead>
                    <tbody>
                        <?php     
                        $lists='';
                        if(!empty($modelmm)){                            
                            $i =1;
                            foreach($modelmm as $p){  
                                $emp = Yii::$app->utility->get_employees($p['manpowerid']);
                                if($emp['fullname']!=NULL){?>
                                    <tr>
                                        <td><?=$i?></td>
                                        <td><?=$emp['fullname']?></td>                                        
                                        <td><?=$p['sactionpost']?></td>
                                        <td><?=$p['salary']?> %</td>
                                    </tr>
                                <?php $i++; 
                                }
                            }
                        }
                        ?>
                    </tbody>

            </table>
        </div>
        <div class="col-sm-12">
            <br>
            <h6><b><u>Client Contact Details</u></b></h6>
        </div>
        <div class="col-sm-12">
            <hr>
            <table id="dataTableShow2" class="display table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>Sr. No.</th>
                        <th>Contact Person</th>
                        <th>Contact Number</th>
                        <th>E-Mail</th>                        
                    </tr>
                </thead>
                    <tbody>
                        <?php     
                        $lists='';
                        if(!empty($modelcc)){                            
                            $i =1;
                            foreach($modelcc as $p){   ?>
                                    <tr>
                                        <td><?=$i?></td>
                                        <td><?=$p['name']?></td>
                                        <td><?=$p['phone']?></td>
                                        <td><?=$p['email']?></td>
                                    </tr>
                                <?php $i++; 
                            }
                        }
                        ?>
                    </tbody>

            </table>
        </div>
            
        
        <?php } ?>
    </div>
<?php ActiveForm::end(); ?>
    
    
    
</div>
<link href="<?=Yii::$app->homeUrl?>css/bootstrap-datepicker.css" rel="stylesheet">
<script src="<?=Yii::$app->homeUrl?>js/bootstrap-datepicker.js"></script>