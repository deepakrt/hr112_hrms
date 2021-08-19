<?php
$this->title= 'Create Dak';
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\FtsGroupMaster;
use app\models\EmployeeNew;
$Group_Master =Yii::$app->fts_utility->fts_getgroupmaster(NULL);
$Employee_Master =Yii::$app->utility->get_employees('');
$Category_Master =Yii::$app->fts_utility->fts_getcategorymaster();

$this->title = 'Create Dak';
?>
<div class="fts-dak-index">
<?php $form = 
        ActiveForm::begin
                ([
                    'action'=>Yii::$app->homeUrl.'fts/dak/create',
//                    'beforeSubmit' => 'leavevalidation',
						'options' => ['enctype' => 'multipart/form-data'],
                ]); 
?>
    <?php
	foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
		echo '<div class="col-sm-12 col-xs-12 text-center alert alert-' . $key . '" style="margin-bottom:15px;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> ' . $message . '</div>'; break;
	}
?>
<div class="row">

<div class="col-sm-4">
    <?= $form->field($model, 'refrence_no')->textInput(['maxlength' => true]) ?>
</div>

<div class="col-sm-4">
    <?= $form->field($model, 'file_name')->textInput(['maxlength' => true]) ?>
</div>

<div class="col-sm-4">
    <?= $form->field($model, 'subject')->textInput(['maxlength' => true]) ?>
</div>

<div class="col-sm-6">
    <div class="row">
    <div class="col-sm-6"> <label for="ftsdak-sent-type" class="control-label">Sent Type</label></div>
     </div>
     <div class="row">
    <div class="col-sm-6"> 
    <div class="btn-group">
    <button value="I" type="button" id="sent_individual" class="sent_type_btn btn btn-secondary " >Individual</button>&nbsp;
    <button value="G" type="button" id="sent_group" class="sent_type_btn btn btn-secondary " >Group</button>&nbsp;
    <input type="hidden"  id="sent_type_value" name="FtsDak[send_to_type]"  value=""/>
    </div>
    </div> 
    
    <div class="col-sm-6"> 
    <div id="individual_show_div" style='display:none'>
    <select class="form-control form-control-sm" id="frm_individual" name="FtsDak[send_to_emp]">
	<option selected="selected" value="">Select Employee</option>
    <?php    
    foreach($Employee_Master as $emk =>$emv)
    {
          $e_id = $emv['e_id'];
          $employee_code = $emv['employee_code'];

          $fname = $emv['fname'];
          echo "<option value='$e_id'>$fname ($employee_code)</option>";					
    
    }
    ?>
     </select>
    </div>
    
    <div id="group_show_div" style='display:none'>
    <select class="form-control form-control-sm" id="frm_Group" name="FtsDak[send_to_group]">
	<option selected="selected" value="">Select Group</option>
    <?php foreach($Group_Master as $gmk =>$gmv){
                  $g_id = $gmv['group_id'];
                  $gname = $gmv['group_name'];
                  echo "<option value='$g_id'>$gname</option>";					
                    }
        ?>
     </select>
    </div>

</div>
        
    </div>
</div>
<?php // echo "<pre>"; print_r($model); die;?>
<div class="col-sm-3">
							<?php
        $list = ArrayHelper::map($Category_Master, 'fts_category_id', 'cat_name');
             echo $form->field($model, 'category')->dropDownList($list, ['prompt'=>'Select Category', 'class'=>'form-control form-control-sm',])->label(); 
        ?>
        
</div>

<div class="col-sm-3">
    <?= $form->field($model, 'summary')->textInput(['maxlength' => true]) ?>
</div>

<div class="col-sm-3">
<label for="ftsdak-access-level" class="control-label">Access Level</label><br> <button value="R" type="button" id="access_level_read" class="access_level_btn btn btn-secondary " >Read</button>&nbsp;
    <button value="W" type="button" id="access_level_write" class="access_level_btn btn btn-secondary " >Write</button>&nbsp;
    <input type="hidden"  id="access_level_value" name="FtsDak[access_level]"  value=""/>

</div>


<div class="col-sm-4">
<?= $form->field($model, 'priority')->dropDownList([ 'Normal' => 'Normal', 'Moderate' => 'Moderate', 'High' => 'High', ], ['prompt' => 'Select Priority']) ?>
</div>


<div class="col-sm-4">

<?= $form->field($model, 'is_confidential')->dropDownList([ 'N' => 'No', 'Y' => 'Yes']) ?>

</div>

<div class="col-sm-4">
    <?= $form->field($model, 'file_date')->textInput(['class'=>'form-control datepicker', 'placeholder'=>'File Date', 'readonly'=>true]) ?>

</div>


<div class="col-sm-4">
    <?= $form->field($model, 'meta_keywords')->textInput(['maxlength' => true]) ?>

</div>


<div class="col-sm-4">
    <?= $form->field($model, 'remarks')->textInput(['maxlength' => true]) ?>

</div>


<div class="col-sm-3">
<label for="ftsdak-doc-type" class="control-label">Doc Type</label>
<br>

    <button value="P" type="button" id="doc_type_pdf" class="doc_type_btn btn btn-secondary " >PDF</button>&nbsp;
    <button value="I" type="button" id="doc_type_jpeg" class="doc_type_btn btn btn-secondary " >IMG</button>&nbsp;
    <input type="hidden"  id="doc_type_value" name="FtsDak[doc_type]"  value=""/>
    
  
</div>


<div class="col-sm-3">
<?= $form->field($model, 'document')->fileInput(); ?>
</div> 
 <input type="hidden"  id="securekey" name="FtsDak[securekey]"  value="<?=$_GET['securekey'];?>"/>
<div class="col-sm-1">
</div>
<div class="col-sm-5">
<div class="row">
<label style="height:12px"></label>
</div>
<div class="row">
<input type="submit" class="btn btn-success"  onclick="return leavevalidation()" value="Submit" />&nbsp;
        <input type="reset" class="btn btn-primary"  value="Reset" />&nbsp;
        <a href="" class="btn btn-secondary">Cancel</a>
</div>
</div>


 <div class="col-sm-12 text-center">
        
    </div> 
     
</div>
<?php ActiveForm::end(); ?>


    
</div>

</div>

