<?php
$this->title= 'Dispatch Dak';
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\FtsGroupMaster;
use app\models\EmployeeNew;
$Group_Master =Yii::$app->fts_utility->fts_getgroupmaster();
$Employee_Master =Yii::$app->utility->get_employees('');
$Employee =Yii::$app->utility->get_employees($model['send_to']);
$Category_Master =Yii::$app->fts_utility->fts_getcategorymaster();

 // echo "<pre>";print_r($model);die;
if ($model['send_to_type'] == 'G'){
    $gname= "";
 foreach ($Group_Master as $k=>$g){
    if ($g['group_id'] == $model['send_to']){
        $gname = $g['group_name'];
        break;
    }
 }
}

$this->title = 'Dispatch Dak';

preg_match("/[^\/]+$/", $model['document'], $matches);
$last_word = $matches[0]; 
$file_extenstion = substr($last_word, strpos($last_word, ".") + 1);    
if($file_extenstion == 'pdf'){
    $ftype = "P";
}else{
    $ftype = "I";
}
?>
<div class="fts-dak-index">
<?php $form = 
        ActiveForm::begin
                ([
                    'action'=>Yii::$app->homeUrl.'fts/dak/update',
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
        <div class="col-sm-6">
            <label for="ftsdak-sent-type" class="control-label">Sent Type</label></div>
     </div>
    
    <div class="row">
        <div class="col-sm-6"> 
            <div class="btn-group">
                <button value="I" type="button" id="sent_individual" class="sent_type_btn btn btn-secondary " >Individual</button>&nbsp;
    
                <button value="G" type="button" id="sent_group" class="sent_type_btn btn btn-secondary " >Group</button>&nbsp;
    
                <input type="hidden"  id="sent_type_value" name="FtsDak[send_to_type]"  value="<?=$model['send_to_type'];?>"/>
            </div>
        </div> 
    <?php //echo "<pre>"; print_r(Yii::$app->utility->get_employees('1')); die; 
      //  echo "<pre>"; print_r($model);die;?>
    <div class="col-sm-6"> 
        <div id="individual_show_div" style='display:none'>
            <select class="form-control form-control-sm" id="frm_individual" name="FtsDak[send_to_emp]">
	           <option value=''>--Select--</option>
                    <?php    
                        foreach($Employee_Master as $emk =>$emv){
                            $e_id = $emv['e_id'];
                            $employee_code = $emv['employee_code'];
                            $fname = $emv['fname'];
							$sel='';
							if($e_id==$model['send_to']){$sel= "selected='selected'";}
                            echo "<option $sel value='$e_id'>$fname ($employee_code)</option>";		
                        }
                    ?>
            </select>
        </div>
    
    <div id="group_show_div" style='display:none'>
    <select class="form-control form-control-sm" id="frm_Group" name="FtsDak[send_to_group]">
	<option value=''>--Select--</option>
    <?php foreach($Group_Master as $gmk =>$gmv){
                  $g_id = $gmv['group_id'];
                  $gname = $gmv['group_name'];
				  $sel='';
				if($g_id==$model['send_to']){$sel= "selected='selected'";}
                  echo "<option $sel value='$g_id'>$gname</option>";					
                    }
        ?>
      </select>
    </div>

</div>
        
    </div>
</div>
<?php //echo $dak_id; echo "<pre>"; print_r($model); die;?>
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
    <label for="ftsdak-access-level" class="control-label">
        Access Level
    </label>
    
    <br>
    
    <button value="R" type="button" id="access_level_read" class="access_level_btn btn btn-secondary " >Read</button>&nbsp;
    <button value="W" type="button" id="access_level_write" class="access_level_btn btn btn-secondary " >Write</button>&nbsp;
    <input type="hidden"  id="access_level_value" name="FtsDak[access_level]"  value="<?=$model['access_level'];?>"/>

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
    <input type="hidden"  id="doc_type_value" name="FtsDak[doc_type]"  value="<?=$ftype;?>"/>
    
  
</div>

 <input type="hidden" name="FtsDak[doc_change]" id="doc_change" value="0" />
 <input type="hidden" name="FtsDak[dak_id]" id="dak_id" value="<?=$dak_id;?>" />
    
<div class="col-sm-3">
    
    <?php
    //echo "<pre>";print_r($model); echo $model->document; die;
     if(!empty($model->document))
            { ?>
                <div class="col-sm-6">
                    <span id="showdoc">
                        <img src="<?=Yii::$app->homeUrl.$model->document;?>" width="100" /><br><br> <button id="changeDakDoc" type="button" class="btn btn-danger btn-sm btn-xs">Change</button>
                    </span>
                    <span  id="changeDak" style="display: none;">
                        <input type="file" name="FtsDak[document]" id="ftsdak-document">
                    </span>
                </div>
            <?php }else{
                echo $form->field($model, 'document')->fileInput(['placeholder'=>'Document', 'class'=>'form-control form-control-sm PhotoSign', 'maxlength' => true]);
            }
    
    ?>
    
    
    
</div> 
 <input type="hidden"  id="securekey" name="FtsDak[securekey]"  value="<?=$_GET['securekey'];?>"/>
<div class="col-sm-1">
</div>
<div class="col-sm-5">
<div class="row">
<label style="height:12px"></label>
</div>
<div class="row"><!--
<input type="submit"   onclick="return leavevalidation()" value="Submit" />&nbsp;--> 
<input type="submit" class="btn btn-success" value="Submit" />&nbsp;
       
        <a href="" class="btn btn-secondary">Cancel</a>
</div>
</div>


 <div class="col-sm-12 text-center">
        
    </div> 
     
</div>
<?php ActiveForm::end(); ?>


    
</div>

</div>
<script>
$(document).ready(function (){

    var sent_type_value = $("#sent_type_value").val();
    var access_level_value = $("#access_level_value").val();
    var doc_type_value = $("#doc_type_value").val();
  //  alert(access_level_value);
  //  $("#ftsdak-cange").val('1');
    
    if( doc_type_value=="P"){
       $("#doc_type_pdf").addClass('btn-success');
    }else{
         $("#doc_type_jpeg").addClass('btn-success');
    }
    
    if( access_level_value=="R"){
       $("#access_level_read").addClass('btn-success');
    }else{
         $("#access_level_write").addClass('btn-success');
    }
    
    if( sent_type_value=="G"){
        $("#group_show_div").show();
        $("#individual_show_div").hide();
        $("#sent_group").addClass('btn-success');
    }
    if( sent_type_value=="I"){
        $("#group_show_div").hide();
        $("#individual_show_div").show();
        $("#sent_individual").addClass('btn-success');
    }
    
      $("#changeDakDoc").click(function(){
            $("#changeDak").show();
            $("#showdoc").remove();
            $("#ftsdak-document").attr('required',true);
            $("#doc_change").val('1');
        });
    
$(".sent_type_btn").click(function ()
 {
		      var Value = $(this).val();
								$("#group_show_div").hide();
								$("#individual_show_div").hide();
        if(Value=="I" || Value=="G")
        {
        	$("#individual_show_div").show();
        if( Value=="G")
        {
        $("#group_show_div").show();
								$("#individual_show_div").hide();
        }
        $("#sent_type_value").val(Value);
        $('.sent_type_btn').removeClass('btn-success');
        $(this).addClass('btn-success');
        }

    });
    
});
  function leavevalidation(){
      alert("eee");
       $("#w0").submit(); alert("wwweee");
  }  


</script>
