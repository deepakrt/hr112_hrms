<?php 
use yii\widgets\ActiveForm;
use app\models\EfileDakNotes;
$checkDraft = EfileDakNotes::find()->where(['file_id' => $fileinfo['file_id'], 'added_by' => Yii::$app->user->identity->e_id, 'status'=>'D', 'content_type'=>'N'])->asArray()->one();

$noteid = $note_subject = $note_comment = "";
if(!empty($checkDraft)){
	$note_subject = $checkDraft['note_subject'];
	$note_comment = $checkDraft['note_comment'];
	$noteid = Yii::$app->utility->encryptString($checkDraft['noteid']);
}
?>
<div class='row'>
<div class='col-sm-12'><hr class='hrline'></div>
<?php
$showNotes = "N";
if($movement['fwd_by'] == Yii::$app->user->identity->e_id AND $movement['fwd_emp_code'] == Yii::$app->user->identity->e_id){
	$showNotes = "Y";
}
if($fileinfo['access_level'] == 'RW' AND $movement['is_reply_required'] == 'Y' AND $movement['reply_status'] == 'N' AND $movement['fwd_by'] != Yii::$app->user->identity->e_id){
	$showNotes = "Y";
}

?>
	<div class='col-sm-6 borderright'>
		<?php 
		if($showNotes == 'Y'){
		$noteurl = Yii::$app->homeUrl."efile/inbox/addnewnote?securekey=$menuid";
		ActiveForm::begin(['action'=>$noteurl, 'options' => ['enctype' => 'multipart/form-data']]);
		$file_id = Yii::$app->utility->encryptString($fileinfo['file_id']);
		$movement_id = Yii::$app->utility->encryptString($movement['id']);
		?>
		<input type='hidden' name='Newnote[key]' value='<?=$file_id?>' readonly />
		<input type='hidden' name='Newnote[key1]' value='<?=$movement_id?>' readonly />
		<input type='hidden' name='Newnote[key2]' value='<?=$noteid?>' readonly />
		<div class='row'>
			<div class='col-sm-12'>
				<h6 class='text-center'><b><span class="hindishow">नोट पत्र पर टिप्पणी</span><br>Add Comments on Note Sheet:-</b></h6>
				<?php 
				$checksubject = "N";
				if(empty($notes)){
				$checksubject = "Y";
					?>
				<input type='text' class='form-control form-control-sm' id='note_subject' name='note_subject' placeholder='विषय नोट शीट / Subject for Note Sheet' value='<?=$note_subject?>' /><br>
				<?php } ?>
				<input type='hidden' id='checksubject' <?=$checksubject?> readonly />
				<textarea class='form-control form-control-sm' id='note_comment' name='Newnote[note_comment]' placeholder='टिप्पणी दर्ज करें / Enter Comments' rows='6' required><?=$note_comment?></textarea>
			</div>
			<div class='col-sm-12 text-center'>
				<br>
				<button type='submit' class='btn btn-secondary btn-sm notesubmitbtn' name='submit_type' value='D'>Save as Draft</button>
				<button type='submit' class='btn btn-success btn-sm notesubmitbtn' name='submit_type' value='S'>Save</button>
			</div>
		</div>
		<?php ActiveForm::end();
		}
		?>
	</div>
	<div class='col-sm-6'>
		<?php 
		$noteurl = Yii::$app->homeUrl."efile/inbox/addnewremarks?securekey=$menuid";
		ActiveForm::begin(['action'=>$noteurl, 'id'=>'fileremarksform', 'options' => ['enctype' => 'multipart/form-data']]);
		$file_id = Yii::$app->utility->encryptString($fileinfo['file_id']);
		$movement_id = Yii::$app->utility->encryptString($movement['id']);
		?>
		<input type='hidden' name='Remarks[key]' value='<?=$file_id?>' readonly />
		<input type='hidden' name='Remarks[key1]' value='<?=$movement_id?>' readonly />
		<div class='row'>
			<div class='col-sm-12'>
				<h6 class='text-center'><b><span class="hindishow">दस्तावेज़ में टिप्पणी / परिशिष्ट सामग्री जोड़ें</span><br>Add Remarks / Append content in document:-</b></h6>
				<textarea class='form-control form-control-sm' id='file_remarks' name='Remarks[file_remarks]' placeholder='टिप्पणी दर्ज करें / Enter Remarks' rows='6'></textarea>
			</div>
                    <?php if($showNotes == 'Y'){ ?>
			<div class="col-sm-12">
				<br>
				<h6><b><u>Upload File</u></b></h6>
			</div>
			<div class="col-sm-6">
				<label>Upload File Type</label>
				<select id="efile_doc_type" name="Remarks[doc_type]" class="form-control form-control-sm" >
					<option data-key="0" value="">Select File Type</option>
					<option data-key="1" value="<?=Yii::$app->utility->encryptString('PDF')?>">PDF</option>
					<option data-key="2" value="<?=Yii::$app->utility->encryptString('Image')?>">Image</option>
				</select>
			</div>
			<div class="col-sm-6">
				<label>Browse File</label>
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
                    <?php } ?>
			<div class='col-sm-12 text-center'>
				<button type='button' class='btn btn-success btn-sm' onclick='validateRemarks()'>Submit</button>
			</div>
		</div>
		<?php ActiveForm::end();?>
	</div>
</div>