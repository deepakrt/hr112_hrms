<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
$label= $model->attributeLabels();
$sd = $ed = "";
if(empty($model->project_id)){
    $sd = "projectlist-start_date";
    $ed = "projectlist-end_date";
}

$depts = Yii::$app->utility->get_dept(NULL);
$depts = ArrayHelper::map($depts, 'dept_id', 'dept_name');
$manager_emp_id = "";
if(!empty($model->manager_emp_id)){
    $manager_emp_id = Yii::$app->utility->encryptString($model->manager_emp_id);
}
?>
<input type="hidden" id="menuid" value="<?=$menuid?>" readonly="" />
<input type="hidden" id="param_manager_emp_id" value="<?=$manager_emp_id?>" readonly="" />

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
<div class="row">
    <div class="col-sm-3"><?= $form->field($model, 'project_name')->textInput(['class'=>'form-control form-control-sm', 'maxlength' => true, 'placeholder'=>$label['project_name'], 'title'=>$label['project_name'], 'autocomplete'=>'off' ]) ?></div>
    <div class="col-sm-3"><?= $form->field($model, 'short_name')->textInput(['class'=>'form-control form-control-sm', 'maxlength' => true, 'placeholder'=>$label['short_name'], 'title'=>$label['short_name'], 'autocomplete'=>'off' ]) ?></div>
    <div class="col-sm-3"><?= $form->field($model, 'project_type')->dropDownList([ 'Business' => 'Business', 'Funded' => 'Funded', 'Mission' => 'Mission', ], ['prompt' => 'Select Project Type', 'class'=>'form-control form-control-sm', 'title'=>$label['short_name']]) ?></div>
    
    <div class="col-sm-6"><?= $form->field($model, 'description')->textarea(['rows' => 2, 'class'=>'form-control form-control-sm', 'title'=>$label['description'], 'placeholder'=>$label['description'] ]) ?></div>
    
    <div class="col-sm-6"><?= $form->field($model, 'address')->textarea(['rows' => 2, 'class'=>'form-control form-control-sm', 'title'=>$label['address'], 'placeholder'=>$label['address'] ]) ?></div>
    <div class="col-sm-3"><?= $form->field($model, 'contact_person')->textInput(['class'=>'form-control form-control-sm', 'maxlength' => true, 'placeholder'=>$label['contact_person'], 'title'=>$label['contact_person'], 'autocomplete'=>'off' ]) ?></div>
    <div class="col-sm-3"><?= $form->field($model, 'contact_no')->textInput(['class'=>'form-control form-control-sm', 'maxlength' => true, 'placeholder'=>$label['contact_no'], 'title'=>$label['contact_no'], 'maxlength'=>'10', 'autocomplete'=>'off', 'onkeypress'=>'return allowOnlyNumber(event)' ]) ?></div>
    <div class="col-sm-3"><?= $form->field($model, 'alternate_contact_no')->textInput(['class'=>'form-control form-control-sm', 'maxlength' => true, 'placeholder'=>$label['alternate_contact_no'], 'title'=>$label['alternate_contact_no'], 'autocomplete'=>'off', 'maxlength'=>'10', 'onkeypress'=>'return allowOnlyNumber(event)' ]) ?></div>
    <div class="col-sm-3"><?= $form->field($model, 'project_cost')->textInput(['class'=>'form-control form-control-sm', 'maxlength' => true, 'placeholder'=>$label['project_cost'], 'title'=>$label['project_cost'], 'autocomplete'=>'off', 'onkeypress'=>'return allowOnlyNumber(event)' ]) ?></div>
    <div class="col-sm-3"><?= $form->field($model, 'start_date')->textInput(['class'=>'form-control form-control-sm '.$sd, 'maxlength' => true, 'placeholder'=>$label['start_date'],'readonly'=>true, 'title'=>$label['start_date'], 'autocomplete'=>'off' ]) ?></div>
    <div class="col-sm-3"><?= $form->field($model, 'end_date')->textInput(['class'=>'form-control form-control-sm '.$ed, 'maxlength' => true, 'placeholder'=>$label['end_date'], 'readonly'=>true, 'title'=>$label['end_date'], 'autocomplete'=>'off' ]) ?></div>
    <div class="col-sm-3"><?= $form->field($model, 'num_working_days')->dropDownList([ '1' => '1', '2' => '2', '3' => '3', '4' => '4','5' => '5', '6' => '6' ], ['prompt' => 'Select No. of Working Days', 'class'=>'form-control form-control-sm', 'title'=>$label['num_working_days']]) ?></div>
    
    <div class="col-sm-3"><?= $form->field($model, 'num_manpower')->textInput(['class'=>'form-control form-control-sm', 'maxlength' => true, 'placeholder'=>$label['num_manpower'], 'title'=>$label['num_manpower'], 'autocomplete'=>'off', 'onkeypress'=>'return allowOnlyNumber(event)' ]) ?></div>
    
    <div class="col-sm-5"><?= $form->field($model, 'technology_used')->textarea(['rows' => 2, 'class'=>'form-control form-control-sm', 'title'=>$label['technology_used'], 'placeholder'=>$label['technology_used'] ]) ?></div>
    
    <input type="hidden" class="form-control form-control-sm" name="ProjectList[key_encript]" value="<?=Yii::$app->utility->encryptString($model->project_id)?>" readonly="">
    <div class="col-sm-4">
        <input type="hidden" class="form-control form-control-sm" id="doc_path1" name="ProjectList[doc_path1]" value="N" readonly="">
        <?php
        $encryptPath = "";
        $haveDoc = Yii::$app->utility->encryptString("N");
        if(!empty($model->approval_doc)){
            $haveDoc = Yii::$app->utility->encryptString("Y");
            $encryptPath = Yii::$app->utility->encryptString($model->approval_doc); 
            $path = Yii::$app->homeUrl.$model->approval_doc;
        ?>
        
        
        <span id="pdfview">
            <a href="<?=$path?>" style="font-weight: bold"><img src="<?=Yii::$app->homeUrl?>images/pdf.png" /> View Project File</a>
            <button type="button" class="btn btn-outline-danger btn-sm btnxs" id="changeProjectFile">Click to Change File</button>
        </span>
        <span id="pdfadd" style="display: none;">
            <?=$form->field($model, 'approval_doc')->fileInput(['class'=>'form-control form-control-sm pdf_file','maxlength' => true, 'accept'=>'.pdf']);?>
        <button type="button" class="btn btn-outline-danger btn-sm btnxs" id="resetProjectFile">Reset</button>
        </span>
        <?php }else{
            echo $form->field($model, 'approval_doc')->fileInput(['class'=>'form-control form-control-sm pdf_file','maxlength' => true, 'accept'=>'.pdf']);
        }
        
        ?>
        
        <input type="hidden" class="form-control form-control-sm" name="ProjectList[doc_path]" value="<?=$encryptPath?>" readonly="">
    </div>
    <div class="col-sm-3"><?= $form->field($model, 'manager_dept')->dropDownList($depts, ['prompt' => 'Select Department', 'class'=>'form-control form-control-sm', 'title'=>$label['manager_dept']]) ?></div>
<!--    <div class="col-sm-3"><?php //$form->field($model, 'manager_emp_id')->dropDownList([], ['prompt' => 'Select Project Manager', 'class'=>'form-control form-control-sm', 'title'=>$label['manager_emp_id']]) ?></div>
    <div class="col-sm-5">
        <div class="form-group field-projectlist-responsibility ">
            <label class="control-label" for="projectlist-responsibility">Responsibility</label>
            <textarea id="projectlist-responsibility" class="form-control form-control-sm" name="ProjectList[responsibility]" rows="2" title="Responsibility" placeholder="Responsibility"></textarea>
        </div>
    </div>-->
    <?php 
    if(!empty($model->project_id)){?>
        <div class="col-sm-3"><?= $form->field($model, 'status')->dropDownList([ 'Open' => 'Open', 'Working' => 'Working', 'Closed' => 'Closed'], ['prompt' => 'Select Status', 'class'=>'form-control form-control-sm', 'title'=>$label['status']]) ?></div>
        <div class="col-sm-3"><?= $form->field($model, 'is_active')->dropDownList([ 'Y' => 'Yes', 'N' => 'No'], ['prompt' => 'Select Is Active', 'class'=>'form-control form-control-sm', 'title'=>$label['is_active']]) ?></div>
    <?php }
    ?>
    <input type="hidden" name="ProjectList[doc_path_key]" value="<?=$haveDoc?>" readonly="">
    <div class="col-sm-12 text-center">
        <?= Html::submitButton($model->isNewRecord ? 'Submit' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm']) ?>
        <a href="<?=Yii::$app->homeUrl?>manageproject/projects?securekey=<?=$menuid?>"  class="btn btn-danger btn-sm">Cancel</a>
    </div>
</div>
<?php ActiveForm::end(); 
if(!empty($model->manager_dept)){ ?>
<script>
//    $(document).ready(function(){
//        getProjectManager('<?=$model->manager_dept?>', '<?=$manager_emp_id?>');
//    });
    
</script>
<?php } ?>
