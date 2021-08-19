<?php

use dosamigos\ckeditor\CKEditor;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\EfileMasterCategory;
use app\models\efile_master_project;
use app\models\EfileDakTemp;
$category = EfileMasterCategory::find()->where(['is_active' => "Y"])->asArray()->all();
$efile_action_type = Yii::$app->fts_utility->efile_get_actions(NULL, "1");
$randum_number = Yii::$app->user->identity->e_id.rand(10000, 100000);

if(!empty($recieveddak)){
    $draft_model = EfileDakTemp::find()->where(['employee_code'=>Yii::$app->user->identity->e_id, 'rec_id'=>$recieveddak['rec_id']])->one();
}else{
    $draft_model = EfileDakTemp::find()->where(['employee_code'=>Yii::$app->user->identity->e_id])->one();
}
$accesslevel = $note_subject = $note_comment = $file_remarks = $action_type = $filecategoryid = "";
$initiate_type = "F";
$inti_file = "btn-success";
$inti_proposal = $inti_note = "btn-secondary";
if(!empty($draft_model)){
    if(!empty($draft_model->temp_id)){
        $randum_number = $draft_model->temp_id;
    }
    
    if($draft_model->initiate_type == "N"){
        $initiate_type = "N";
        $inti_file = "btn-secondary";
        $inti_proposal = "btn-secondary";
        $inti_note = "btn-success"; ?>
       <script>$(document).ready(function(){ change_initiate_type('N'); })</script>
    <?php }elseif($draft_model->initiate_type == "P"){
        $initiate_type = "P";
        $inti_file = "btn-secondary";
        $inti_proposal = "btn-success";
        $inti_note = "btn-secondary";
    }
    
    $model->reference_num = $draft_model->reference_num;
    $model->reference_date = date('d-m-Y', strtotime($draft_model->reference_date));
    $model->subject = $draft_model->subject;
    $model->meta_keywords = $draft_model->meta_keywords;
    $model->remarks = $draft_model->remarks;
    $model->summary = $draft_model->summary;
    $model->priority = $draft_model->priority;
    $model->file_project_id = $draft_model->file_project_id;
    $note_subject = $draft_model->note_subject;
    $note_comment = $draft_model->note_comment;
    $file_remarks = $draft_model->file_remarks;
    $filecategoryid = $draft_model->file_category_id;
    $action_type = $draft_model->action_type;
    $accesslevel = $draft_model->access_level;
}
// echo "<pre>";print_r($draft_model);die;
$actionList = array();
$i=0;

foreach($efile_action_type as $a){
    $action_id = Yii::$app->utility->encryptString($a['action_id']);
    $actionList[$i]['actionid'] = $action_id;
    $actionList[$i]['name'] = $a['action_name_hindi']." / ".$a['action_name'];
    $i++;
}

//$actionlist = ArrayHelper::map($actionList, 'actionid', 'name');
$access_level = Yii::$app->fts_utility->get_efile_access_level("G", "");
//$access_level = ArrayHelper::map($access_level, 'id', 'name');
$priority = Yii::$app->fts_utility->get_efile_priority("G", "");
//$priority = ArrayHelper::map($priority, 'id', 'name');
$is_confidential = Yii::$app->fts_utility->get_efile_check_yes_no("G", "");
$is_confidential = ArrayHelper::map($is_confidential, 'id', 'name');
// echo "<pre>";print_r($priority);
$url = Yii::$app->homeUrl."efile/dakcommon/filemovement";

$required = ' <span style="color: red;font-weight: bold;">*</span>';
$rec_id = NULL;
if(!empty($recieveddak)){
    $rec_id = Yii::$app->utility->encryptString($recieveddak['rec_id']);
}
$form = ActiveForm::begin(['action'=>$url, 'id'=>'dakform', 'options' => ['enctype' => 'multipart/form-data']]); 
$scanOptionHide= "";
?>
<input type="hidden" name="randum_number" value="<?=$randum_number?>" />
<style>
    .col-sm-8, .col-sm-4, .col-sm-12{
        margin-bottom: 10px;
    }
    #cke_14, #cke_68, #cke_72, #cke_29, #cke_34 { display: none !important;}
    .cke_toolbar{float: none !important}
    
</style>
<h6><b><u>फ़ाइल की जानकारी / File or Note Information</u></b></h6>
<input type='hidden' name='key' value='<?=$menuid?>' readonly />
<input type='hidden' name='rec_id' value='<?=$rec_id?>' readonly />


<div id="dak_form">
<div class="row">
    <?php if(empty($recieveddak)){ ?>
    <div class='col-sm-12 text-center'>
        <button type="button" class="btn <?=$inti_file?> btn-sm" id='initiate_file' onclick="change_initiate_type('F')">Initiate File</button>
        <button type="button" class="btn <?=$inti_note?> btn-sm"  id='initiate_note' onclick="change_initiate_type('N')">Initiate Note</button>
        <button type="button" class="btn <?=$inti_proposal?> btn-sm"  id='initiate_proposal' onclick="change_initiate_type('P')">Initiate Proposal</button>
        <input type="hidden" id='initiate_type' name="initiate_type" value="F" readonly="" />
        <hr>
        <?php 
        
        if(!empty($voucher_number)){
            $scanOptionHide= "display:none;";
            echo "<script>$(document).ready(function(){showLoader();});</script>";
            echo "<b style='color:red;font-weigth:bold;'>Voucher Number : $voucher_number</b>";
        }
        ?>
    </div>
    <div class="col-sm-8 hideref">
        <label id="ref_title">संदर्भ संख्या / Reference Num <?=$required?></label>
        <input type="text" id='efiledak-reference_num' class="form-control form-control-sm" name="EfileDak[reference_num]" placeholder="Reference Num" value="<?=$model->reference_num?>">
    </div>
    <div class="col-sm-4 hideref">
        <label id="ref_date">संदर्भ दिनांक / Reference Date <?=$required?></label>
        <input type="text" id="efiledak-reference_date" class="form-control form-control-sm" name="EfileDak[reference_date]" value="<?=date('d-m-Y');?>" readonly="" placeholder="Reference Date" style="cursor: pointer;" value="<?=$model->reference_date?>">
    </div>
    <div class="col-sm-12 hideref">
        <label>विषय / Subject <?=$required?></label>
        <input type="text" id='efiledak-subject' class="form-control form-control-sm" name="EfileDak[subject]" placeholder="Subject" value="<?=$model->subject?>">
    </div>
    <?php }else{ ?>
    <div class="col-sm-8">
        <input type="hidden" id='initiate_type' name="initiate_type" value="F" readonly="" />
        <?= $form->field($model, 'reference_num')->textInput([ 'placeholder'=>$model->getAttributeLabel('reference_num'), 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>
    <div class="col-sm-4"><?= $form->field($model, 'reference_date')->textInput(['readonly'=>true, 'placeholder'=>$model->getAttributeLabel('reference_date'), 'class'=>'form-control form-control-sm', 'maxlength' => true, 'value'=>date('d-m-Y')]) ?></div>
    <div class="col-sm-12"><?= $form->field($model, 'subject')->textInput(['placeholder'=>$model->getAttributeLabel('subject'), 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>
    <?php } ?>
    

    <div class="col-sm-6">
        <label>फ़ाइल श्रेणी / File Category <?=$required?></label>
        <select id="efiledak-file_category_id" class="form-control form-control-sm" name="EfileDak[file_category_id]">
            <option value="">Select File Category</option>
            <?php 
            if(!empty($category)){
                $listt = array();
                $i=0;
                foreach($category as $c){
                    $selected = "";
                    if($filecategoryid == $c['file_category_id']){
                        $selected = "selected='selected'";
                    }
                    $file_category_id = Yii::$app->utility->encryptString($c['file_category_id']);
                    $name = $c['name']." / ".$c['name_hindi'];
                    $related_to_project = $c['related_to_project'];
                    echo "<option value='$file_category_id' $selected data-key='$related_to_project'>$name</option>";
                }
            }
            ?>
        </select>
    </div>
    <?php 
    if(!empty($model->file_project_id)){ ?>
    <div class="col-sm-6" id='project_list' style='display:block;'>
        <label class="control-label" for="efiledak-file_project_id">परियोजना का नाम /Project Name</label>
        <select id="efiledak-file_project_id" class="form-control form-control-sm" name="EfileDak[file_project_id]">
            <option value=''>Select Project</option>
            <?php 
            $projects = Yii::$app->hr_utility->hr_get_project_list();
            if(!empty($projects)){
                foreach($projects as $p){
                    $selected='';
                    $project_id = base64_decode($p['id']);
                    if($project_id == $model->file_project_id ){
                        $selected='selected=""';
                    }
                    $id_ = Yii::$app->utility->encryptString($project_id);
                    echo "<option value='$id_' $selected>$p[project]</option>";
                }
            }
            ?>
        </select>
    </div>
    <?php }else{
    ?>
    <div class="col-sm-6" id='project_list' style='display:none;'>
        <?= $form->field($model, 'file_project_id')->dropDownList([], ['class'=>'form-control form-control-sm', 'prompt' => 'Select Project']) ?>
    </div>
    <?php } ?>
    
	<div class="col-sm-6">
            <label> उद्देश्य / Forward Purpose <?=$required?></label>
            <select id="efiledak-action_type" class="form-control form-control-sm" name="EfileDak[action_type]" required="">
                <option value="">Select Forward Purpose</option>
                <?php 
                if(!empty($efile_action_type)){
                    foreach($efile_action_type as $e){
                        $id = Yii::$app->utility->encryptString($e['action_id']);
                        $selected="";
                        if($action_type == $e['action_id']){
                            $selected="selected='selected'";
                        }
                        echo "<option value='$id' $selected>$e[action_name_hindi] / $e[action_name]</option>";
                    }
                }
                ?>
            </select>
            
            
        </div>
	<div class="col-sm-6">
            <label> एक्सेस मोड / Access Mode <?=$required?></label>
            <select id="efiledak-access_level" class="form-control form-control-sm" name="EfileDak[access_level]" aria-required="true" aria-invalid="true">
                <option value=''>Select Access Level</option>
                <?php 
                if(!empty($access_level)){
                    foreach($access_level as $a){
                        $actual_id = Yii::$app->utility->decryptString($a['id']);
                        $selected="";
                        if($actual_id == $accesslevel){
                            $selected="selected='selected'";
                        }
                        echo "<option $selected value='$a[id]'>$a[name]</option>";
                    }
                }
                ?>
            </select>
        </div>
	<div class="col-sm-6">
            <label>  प्राथमिकता / Priority  <?=$required?></label>
            <select id="efiledak-priority" class="form-control form-control-sm" name="EfileDak[priority]" aria-required="true" aria-invalid="true">
                <option value="">Select Priority</option>
                <?php 
                if(!empty($priority)){
                    foreach($priority as $a){
                        $actual_id = Yii::$app->utility->decryptString($a['id']);
                        $selected="";
                        if($actual_id == $model->priority){
                            $selected="selected='selected'";
                        }
                        echo "<option $selected value='$a[id]'>$a[name]</option>";
                    }
                }
                ?>
            </select>
        </div>
	<div class="col-sm-6">
            <label>गोपनीय है / Is Confidential <?=$required?></label>
            <select id="efiledak-is_confidential" class="form-control form-control-sm" name="EfileDak[is_confidential]">
                <option value="">Select Is Confidential?</option>
                <?php 
                $no = "selected='selected'";
                $yes = "";
                if($model->is_confidential == 'Y'){ $yes = "selected='selected'"; }
                ?>
                <option <?=$no?> value="<?=Yii::$app->utility->encryptString('N');?>">No</option>
                <option <?=$yes?> value="<?=Yii::$app->utility->encryptString('Y');?>">Yes</option>
            </select>
        </div>
	<div class="col-sm-12"><?= $form->field($model, 'meta_keywords')->textInput([ 'placeholder'=>$model->getAttributeLabel('meta_keywords'), 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>
	<div class="col-sm-6"><?= $form->field($model, 'summary')->textarea([ 'placeholder'=>$model->getAttributeLabel('summary'), 'class'=>'form-control form-control-sm', 'rows'=>'6', 'maxlength' => true]) ?></div>
    <div class="col-sm-6"><?= $form->field($model, 'remarks')->textarea([ 'placeholder'=>$model->getAttributeLabel('remarks'), 'class'=>'form-control form-control-sm', 'rows'=>'6', 'maxlength' => true]) ?></div>
    
    <div class="col-sm-12" style="<?=$scanOptionHide?>">
        <hr class='hrline'>
        <h6><b><u><span class="hindishow">अगर आप स्कैन के लिए फाइल भेजना चाहते हैं /</span> If you wants to send file for scan</u></b></h6>
    </div>
    <div class="col-sm-5" style="<?=$scanOptionHide?>">
        <input type='hidden' class='form-control form-control-sm' id='request_scan' name='EfileDak[request_scan]' value='N' readonly />
        <?php 
        $list = Yii::$app->fts_utility->scannerEmpCode();
        ?>
        <label><span class="hindishow12">नियोजित कर्मचारी /</span> Assigned Employee for scan</label>
        <select class="form-control form-control-sm" name="EfileDak[request_scan_emp_code]" id="request_scan_emp_code">
            <option value="">Select Assigned Employee for scan</option>
            <?php 
            if(!empty($list)){
                foreach($list as $l){
                    echo "<option value='$l[employee_code]'>$l[name]</option>";
                }
            }
            ?>
        </select>
    </div>

    <?php if(!empty($list)){ ?>
    <div class="col-sm-4" style="<?=$scanOptionHide?>">

        <br>
        <button type='button' class='btn btn-danger btn-sm' onclick='return sendforscan()'>Click to forward file for Scan</button>
    </div>
    <?php } ?>
    
</div>
<div id='note_fwd_view'>
<div class="row">
    <div class="col-sm-12">
        <hr class="hrline">
    </div>
    <div class='col-sm-6 borderright' style="background: #cfefc6; padding-top: 15px;">
        <h6 class='text-center'><b><span class="hindishow">नोट पत्र पर टिप्पणी / </span>Add Comments on Note Sheet:-</b>
        <?php if(!empty($voucher_number)){ ?>
            <br><p style="text-align: center;color:red;font-weight: bold;font-size:12px;">Voucher No. <?=$voucher_number?></p>
        <?php } ?>
        </h6>
        <input type='text' class='form-control form-control-sm' id='note_subject' name='EfileDak[note_subject]' placeholder='विषय नोट शीट / Subject for Note Sheet' value="<?=$note_subject?>" autocomplete="off" /><br>
        <!--<textarea class='form-control form-control-sm' id='note_comment' name='EfileDak[note_comment]' placeholder='टिप्पणी दर्ज करें / Enter Comments' rows='12' ><?=$note_comment?></textarea>-->
        
        <?php echo CKEditor::widget([
        'name' => 'EfileDak[note_comment]',
        'id' => 'note_comment',
        'class' => 'form-control',
        'preset' => 'full',
                    'clientOptions' => [
                    'filebrowserUploadUrl' => 'url'
                ],
        'value'=>"$note_comment"
]);?>
    </div>
    <div class="col-sm-6">
        <?php 
        $vid = $vpath = NULL;
        if(!empty($voucher_number) AND !empty($voucher_path)){
            $vid = Yii::$app->utility->encryptString($voucher_number);
            $vpath = Yii::$app->utility->encryptString($voucher_path);
        }
        
        echo "<input type='hidden' id='voucher_number' name='voucher_number' value='$vid' readonly />";
        echo "<input type='hidden' id='voucher_path' name='voucher_path' value='$vpath' readonly />";
        if(!empty($voucher_number)){
            $vpth = Yii::$app->fts_utility->getdocumentpath($voucher_path);
            echo "<br><div class='text-center'>
                <h5><b>Voucher Document</b></h5>
                <a href='$vpth' target='_blank'><img src='".Yii::$app->homeUrl."images/pdf.png' /></a>
            </div>
            <input type='hidden' id='file_remarks' name='EfileDak[file_remarks]' />
        ";
            
        }else{
        ?>
        
        <div class='row'>
            <div class='col-sm-12'>
                <h6 class='text-center'><b><span class="hindishow">दस्तावेज़ में टिप्पणी / परिशिष्ट सामग्री जोड़ें</span><br>Add Remarks / Append content in document:-</b></h6>
                <textarea class='form-control form-control-sm' id='file_remarks' name='EfileDak[file_remarks]' placeholder='टिप्पणी दर्ज करें / Enter Remarks' rows='6'><?=$file_remarks?></textarea>
            </div>
            <div class="col-sm-12"> <br> <h6><b><u><span class="hindishow">दस्तावेज अपलोड करें / </span>Upload File</u></b></h6> </div>
            
            <div class="col-sm-5">
                <label>Upload File Type</label>
                <select id="efile_doc_type" name="EfileDak[doc_type]" class="form-control form-control-sm" >
                    <option data-key="0" value="">Select File Type</option>
                    <option data-key="1" value="<?=Yii::$app->utility->encryptString('PDF')?>">PDF</option>
                    <!--<option data-key="2" value="<?php //Yii::$app->utility->encryptString('Image')?>">Image</option>-->
                </select>
            </div>
            <div class="col-sm-7">
                <label id='file_html_label' style="display: none;">Browse File <span style="color: red;font-weight: bold;">*</span></label>
                <span id='pdf_file_html' style='display:none;'>
                    <input type="file" id="pdf_docs_path" name="pdf_path" class="form-control form-control-sm fts_pdf" accept=".pdf" />
                    <span style="font-size: 11px;">Note : File size cannot be more then <?=FTS_Doc_Size?> MB</span>
                </span>
<!--                <span id='image_file_html' style='display:none;'>
                    <input type="file" id="fts_image_multiple" name="image_path[]" class="form-control form-control-sm fts_image_multiple" accept=".jpg,.png, .jpeg" multiple  />
                    <span style="color: red;font-size: 11px;">You can select multiple Images.<br>Each image size cannot be more then <?=FTS_Image_Size?> MB</span>
                </span>-->
            </div>
            <div class="col-sm-12 protectcss" style="display: none;">
                <label>Document Title <span style="color: red;font-weight: bold;">*</span></label>
                <input type="text" class="form-control form-control-sm" id="doc_title" placeholder="Document Title" name="doc_title" autocomplete="off" />
            </div>
            <div class="col-sm-12 protectcss" style="display: none;">
                <label>संरक्षित दस्तावेज़ का पासवर्ड रखना चाहते हैं? / Want to password Protected document? <span style="color: red;font-weight: bold;">*</span></label>
                <select class="form-control form-control-sm" name="is_protected" id="is_protected">
                    <option data-key="0" value="">Select</option>
                    <option data-key="1" value="<?=Yii::$app->utility->encryptString('Y')?>">Yes</option>
                    <option data-key="2" value="<?=Yii::$app->utility->encryptString('N')?>">No</option>
                </select>
            </div>

            <div class="col-sm-12 protectcss_s" style="display: none;">
                <label>File / Document Password <span style="color: red;font-weight: bold;">*</span></label>
                <input type="password" class="form-control form-control-sm" id="file_password" placeholder="File / Document Password" name="file_password" autocomplete="off" />
            </div>
            <div class="col-sm-12">
                <div class="alert alert-danger">
                    <b>Note: Document once submitted, cannot be remove.</b>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

<?php 
	// if(Yii::$app->user->identity->role == '7' OR Yii::$app->user->identity->role == '19'){ 
	if(empty($recieveddak)){
		echo \Yii::$app->view->renderFile(Yii::getAlias('@app') . '/modules/efile/forwardto_html.php', ['file_id'=>'', 'movement'=>'', 'menuid'=>$menuid]);
	?>
	
	<?php }else{?>
	<div class='row'>
		<div class="col-sm-12 text-center">
		<br>
			<input type='hidden' name='forward_dak' id='forward_dak' value='N' readonly />
			<button type="submit" class="btn btn-success btn-sm" onclick='return validateReceivedFileForm()'>Submit</button>
			<a href="<?=Yii::$app->homeUrl?>efile/receiveddak?securekey=<?=$menuid?>" class="btn btn-danger btn-sm">Back</a>
		</div>
	</div>
	<?php }
	?>
</div>
<?php ActiveForm::end(); ?>
</div>
<div class="modal fade" id="addProjectModal"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Add New Project</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class='row'>
					<div class='col-sm-12'>
						<label>Project Name</label>
						<input type='text' placeholder='Enter Project Name' class='form-control form-control-sm' id='project_name' />
					</div>
					<div class='col-sm-12'>
						<br>
						<button type='button' class='btn btn-success btn-sm' onclick="savenewproject()">Submit</button>
						<a href='' class='btn btn-danger btn-sm'>Cancel</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
$(document).ready(function(){
     setInterval(function(){ dakdraft() }, 120000);
//     $('.cke_toolbar').css('float',"");
});
</script>
<?php 
//echo "----$voucher_number---";
if(!empty($voucher_number)){
    
    echo "<script>$(document).ready(function(){change_initiate_type('N'); hideLoader();});</script>";
}
?>