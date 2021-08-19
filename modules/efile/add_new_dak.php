<?php
//echo "<pre>";print_r(Yii::$app->user->identity->dept_id);

use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\EfileMasterCategory;
use app\models\efile_master_project;
$category = EfileMasterCategory::find()->where(['is_active' => "Y"])->asArray()->all();
$efile_action_type = efile_action_type;
// echo "<pre>";print_r($category);die;
$actionList = array();
$i=0;
foreach($efile_action_type as $a){
	$actionid = Yii::$app->utility->encryptString($a);
	$actionList[$i]['actionid'] = $actionid;
	$actionList[$i]['name'] = $a;
	$i++;
}
$actionlist = ArrayHelper::map($actionList, 'actionid', 'name');
$access_level = Yii::$app->fts_utility->get_efile_access_level("G", "");
$access_level = ArrayHelper::map($access_level, 'id', 'name');
$priority = Yii::$app->fts_utility->get_efile_priority("G", "");
$priority = ArrayHelper::map($priority, 'id', 'name');
$is_confidential = Yii::$app->fts_utility->get_efile_check_yes_no("G", "");
$is_confidential = ArrayHelper::map($is_confidential, 'id', 'name');
// echo "<pre>";print_r($recieveddak);
$url = Yii::$app->homeUrl."efile/dakcommon/filemovement";


$rec_id = NULL;
if(!empty($recieveddak)){
    $rec_id = Yii::$app->utility->encryptString($recieveddak['rec_id']);
}
$form = ActiveForm::begin(['action'=>$url, 'id'=>'dakform', 'options' => ['enctype' => 'multipart/form-data']]); ?>

<style>
    .col-sm-12{
        margin-bottom: 10px;
    }
</style>
<h6><b><u>फ़ाइल की जानकारी / File / Note Information</u></b></h6>
<input type='hidden' name='key' value='<?=$menuid?>' readonly />
<input type='hidden' name='rec_id' value='<?=$rec_id?>' readonly />

<div class="row">
    <?php if(empty($recieveddak)){ ?>
    <div class='col-sm-12 text-center'>
        <button type="button" class="btn btn-success btn-sm" id='initiate_file' onclick="change_initiate_type('F')">Initiate File</button>
        <button type="button" class="btn btn-secondary btn-sm"  id='initiate_note' onclick="change_initiate_type('N')">Initiate Note</button>
        <input type="hidden" id='initiate_type' name="initiate_type" value="F" readonly="" />
        <hr>
    </div>
    <div class="col-sm-8 hideref">
        <label>संदर्भ संख्या / Reference Num</label>
        <input type="text" id='efiledak-reference_num' class="form-control form-control-sm" name="EfileDak[reference_num]" placeholder="Reference Num">
    </div>
    <div class="col-sm-4 hideref">
        <label>संदर्भ दिनांक / Reference Date</label>
        <input type="text" id="efiledak-reference_date" class="form-control form-control-sm" name="EfileDak[reference_date]" value="<?=date('d-m-Y');?>" readonly="" placeholder="Reference Date" style="cursor: pointer;">
    </div>
    <div class="col-sm-12 hideref">
        <label>विषय / Subject</label>
        <input type="text" id='efiledak-subject' class="form-control form-control-sm" name="EfileDak[subject]" placeholder="Subject">
    </div>
    <?php }else{ ?>
    <div class="col-sm-8">
        <input type="hidden" id='initiate_type' name="initiate_type" value="F" readonly="" />
        <?= $form->field($model, 'reference_num')->textInput([ 'placeholder'=>$model->getAttributeLabel('reference_num'), 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>
    <div class="col-sm-4"><?= $form->field($model, 'reference_date')->textInput(['readonly'=>true, 'placeholder'=>$model->getAttributeLabel('reference_date'), 'class'=>'form-control form-control-sm', 'maxlength' => true, 'value'=>date('d-m-Y')]) ?></div>
    <div class="col-sm-12"><?= $form->field($model, 'subject')->textInput(['placeholder'=>$model->getAttributeLabel('subject'), 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>
    <?php } ?>
    

    <div class="col-sm-6">
        <label>फ़ाइल श्रेणी / File Category</label>
        <select id="efiledak-file_category_id" class="form-control form-control-sm" name="EfileDak[file_category_id]">
            <option value="">Select File Category</option>
            <?php 
            if(!empty($category)){
                $listt = array();
                $i=0;
                foreach($category as $c){
                    $file_category_id = Yii::$app->utility->encryptString($c['file_category_id']);
                    $name = $c['name']." / ".$c['name_hindi'];
                    $related_to_project = $c['related_to_project'];
                    echo "<option value='$file_category_id' data-key='$related_to_project'>$name</option>";
                }
            }
            ?>
        </select>
    </div>
    <div class="col-sm-6" id='project_list' style='display:none;'>
        <?= $form->field($model, 'file_project_id')->dropDownList([], ['class'=>'form-control form-control-sm', 'prompt' => 'Select Project']) ?>
        
    </div>
	<div class="col-sm-6">
        <?= $form->field($model, 'action_type')->dropDownList($actionlist, ['class'=>'form-control form-control-sm', 'prompt' => 'Select Action']) ?>
    </div>
	<div class="col-sm-6">
        <?= $form->field($model, 'access_level')->dropDownList($access_level, ['class'=>'form-control form-control-sm', 'prompt' => 'Select Access Level']) ?>
    </div>
	<div class="col-sm-6">
        <?= $form->field($model, 'priority')->dropDownList($priority, ['class'=>'form-control form-control-sm', 'prompt' => 'Select Priority']) ?>
    </div>
	<div class="col-sm-6">
        <?= $form->field($model, 'is_confidential')->dropDownList($is_confidential, ['class'=>'form-control form-control-sm', 'prompt' => 'Select Is Confidential?', 'options'=>[]]) ?>
    </div>
	<div class="col-sm-12"><?= $form->field($model, 'meta_keywords')->textInput([ 'placeholder'=>$model->getAttributeLabel('meta_keywords'), 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>
	<div class="col-sm-6"><?= $form->field($model, 'summary')->textarea([ 'placeholder'=>$model->getAttributeLabel('summary'), 'class'=>'form-control form-control-sm', 'rows'=>'6', 'maxlength' => true]) ?></div>
    <div class="col-sm-6"><?= $form->field($model, 'remarks')->textarea([ 'placeholder'=>$model->getAttributeLabel('remarks'), 'class'=>'form-control form-control-sm', 'rows'=>'6', 'maxlength' => true]) ?></div>
    <div class="col-sm-12">
        <hr class='hrline'>
        <h6><b><u><span class="hindishow">अगर आप स्कैन के लिए फाइल भेजना चाहते हैं /</span> If you wants to send file for scan</u></b></h6>
    </div>
    <div class="col-sm-5">
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
    <div class="col-sm-4">

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
    <div class='col-sm-6 borderright'>
        <h6 class='text-center'><b><span class="hindishow">नोट पत्र पर टिप्पणी / </span>Add Comments on Note Sheet:-</b></h6>
        <input type='text' class='form-control form-control-sm' id='note_subject' name='EfileDak[note_subject]' placeholder='विषय नोट शीट / Subject for Note Sheet' /><br>
        <textarea class='form-control form-control-sm' id='note_comment' name='EfileDak[note_comment]' placeholder='टिप्पणी दर्ज करें / Enter Comments' rows='6' ></textarea>
    </div>
    <div class="col-sm-6">
        <div class='row'>
            <div class='col-sm-12'>
                <h6 class='text-center'><b><span class="hindishow">दस्तावेज़ में टिप्पणी / परिशिष्ट सामग्री जोड़ें</span><br>Add Remarks / Append content in document:-</b></h6>
                <textarea class='form-control form-control-sm' id='file_remarks' name='EfileDak[file_remarks]' placeholder='टिप्पणी दर्ज करें / Enter Remarks' rows='6'></textarea>
            </div>
            <div class="col-sm-12"> <br> <h6><b><u><span class="hindishow">दस्तावेज अपलोड करें / </span>Upload File</u></b></h6> </div>
            <div class="col-sm-5">
                <label>Upload File Type</label>
                <select id="efile_doc_type" name="EfileDak[doc_type]" class="form-control form-control-sm" >
                    <option data-key="0" value="">Select File Type</option>
                    <option data-key="1" value="<?=Yii::$app->utility->encryptString('PDF')?>">PDF</option>
                    <option data-key="2" value="<?=Yii::$app->utility->encryptString('Image')?>">Image</option>
                </select>
            </div>
            <div class="col-sm-7">
                <label id='file_html_label' style="display: none;">Browse File</label>
                <span id='pdf_file_html' style='display:none;'>
                    <input type="file" id="pdf_docs_path" name="pdf_path" class="form-control form-control-sm fts_pdf" accept=".pdf" />
                    <span style="color: red;font-size: 11px;">File size cannot be more then <?=FTS_Doc_Size?> MB</span>
                </span>
                <span id='image_file_html' style='display:none;'>
                    <input type="file" id="fts_image_multiple" name="image_path[]" class="form-control form-control-sm fts_image_multiple" accept=".jpg,.png, .jpeg" multiple  />
                    <span style="color: red;font-size: 11px;">You can select multiple Images.<br>Each image size cannot be more then <?=FTS_Image_Size?> MB</span>
                </span>
            </div>
            <div class="col-sm-12">
                <div class="alert alert-danger">
                    <b>Note: Document once submitted, cannot be remove.</b>
                </div>
            </div>
        </div>
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