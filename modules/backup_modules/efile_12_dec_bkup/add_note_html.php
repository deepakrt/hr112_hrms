<?php 
use yii\widgets\ActiveForm;
use app\models\EfileDakNotes;
use app\models\EfileDakTemp;
use app\models\EfileDakDocs;
use dosamigos\ckeditor\CKEditor;
//echo "<pre>";print_r($fileinfo['voucher_number']);
$checkDraft = EfileDakNotes::find()->where(['file_id' => $fileinfo['file_id'], 'added_by' => Yii::$app->user->identity->e_id, 'status'=>'D', 'content_type'=>'N'])->asArray()->one();
$file_draft = EfileDakTemp::find()->where(['file_id' => $fileinfo['file_id']])->one();
$all_docs = EfileDakDocs::find()->where(['file_id' => $fileinfo['file_id'], 'is_active'=>'Y', 'is_protected'=>'Y' ])->all();
$noteid = $note_subject = $note_comment = "";
if(!empty($checkDraft)){
    $note_subject = $checkDraft['note_subject'];
    $note_comment = $checkDraft['note_comment'];
    $noteid = Yii::$app->utility->encryptString($checkDraft['noteid']);
}
$draftfile_remarks = "";
if(!empty($file_draft)){
    if(empty($note_subject)){
        $note_subject = $file_draft->note_subject;
    }
    if(empty($note_comment)){
        $note_comment = $file_draft->note_comment;
    }
    $draftfile_remarks = $file_draft->file_remarks;
}
?>
<style>
    #cke_14, #cke_68, #cke_72, #cke_29, #cke_34 { display: none !important;}
    .cke_toolbar{float: none !important}
</style>
<div class='row'>
<div class='col-sm-12'><hr class='hrline'></div>
<?php
$noteBG = "";
$showNotes = "N";
if($movement['fwd_by'] == Yii::$app->user->identity->e_id AND $movement['fwd_emp_code'] == Yii::$app->user->identity->e_id){
	$showNotes = "Y";
        $noteBG = "background: #cfefc6;padding-top: 15px;";
}

if($fileinfo['access_level'] == 'RW' AND $movement['is_reply_required'] == 'Y' AND $movement['reply_status'] == 'N' AND $movement['fwd_by'] != Yii::$app->user->identity->e_id){
    
    $noteBG = "background: #cfefc6;padding-top: 15px;";
	$showNotes = "Y";
}

?>
        <div class='col-sm-6 borderright' style="<?=$noteBG?>">
            
            <?php 
            
            if($showNotes == 'Y'){
            $noteurl = Yii::$app->homeUrl."efile/inbox/addnewnote?securekey=$menuid";
//            ActiveForm::begin(['action'=>$noteurl, 'id'=>'viewfilenoteform', 'options' => ['enctype' => 'multipart/form-data']]);
            $file_id = Yii::$app->utility->encryptString($fileinfo['file_id']);
            $movement_id = Yii::$app->utility->encryptString($movement['id']);
            ?>
            <input type='hidden' id='Newnote_key' name='Newnote[key]' value='<?=$file_id?>' readonly />
            <input type='hidden' name='Newnote[key1]' value='<?=$movement_id?>' readonly />
            <input type='hidden' name='Newnote[key2]' value='<?=$noteid?>' readonly />
            <div class='row'>
                <div class='col-sm-12'>
                    <h6 class='text-center'><b><span class="hindishow">नोट पत्र पर टिप्पणी</span><br>Add Comments on Note Sheet:-</b>
                    <?php if(!empty($fileinfo['voucher_number'])){ ?>
                        <br><p style="text-align: center;color:red;font-weight: bold;font-size:12px;">Voucher No. <?=$fileinfo['voucher_number']?></p>
                    <?php } ?>
                    </h6>
                    <?php 
                    $checksubject = "N";
                    if(empty($notes)){
                    $checksubject = "Y";
                            ?>
                    <input type='text' class='form-control form-control-sm' id='note_subject' name='note_subject' placeholder='विषय नोट शीट / Subject for Note Sheet' value='<?=$note_subject?>' required='' /><br>
                    <?php }else{
                        echo "<input type='hidden' class='form-control form-control-sm' id='note_subject' value='' readonly />";
                    } ?>
                    <input type='hidden' id='checksubject' value='<?=$checksubject?>' readonly />
                    <!--<textarea class='form-control form-control-sm' id='note_comment' name='Newnote[note_comment]' placeholder='टिप्पणी दर्ज करें / Enter Comments' rows='14' required><?=$note_comment?></textarea>-->
                    <?php echo CKEditor::widget([
        'name' => 'Newnote[note_comment]',
        'id' => 'note_comment',
        'class' => 'form-control',
        'preset' => 'full',
                    'clientOptions' => [
                    'filebrowserUploadUrl' => 'url'
                ],
        'value'=>"$note_comment",
		// 'required'=>true,
]);
if(Yii::$app->user->identity->e_id == '100057'){
	echo "<br><div class='text-center'><h5><b style='color:red;'>Current Role : ".Yii::$app->user->identity->role_name."</b></h6></div>";
}
?>
                </div>
<!--                <div class='col-sm-12 text-center'>
                    <br>
					<input type='hidden' name='submit_type' id='submit_type' />
                    <button type='button' class='btn btn-secondary btn-sm notesubmitbtn' value='D'>Save as Draft</button>
                    <button type='button' class='btn btn-success btn-sm notesubmitbtn'  value='S'>Save</button>
                </div>-->
            </div>
            <?php 
//            ActiveForm::end();
            }
            ?>
	</div>
	<div class='col-sm-6'>
		<?php 
                $newFileSubbtn = "Submit";
		$noteurl = Yii::$app->homeUrl."efile/inbox/addnewremarks?securekey=$menuid";
		ActiveForm::begin(['action'=>$noteurl, 'id'=>'fileremarksform', 'options' => ['enctype' => 'multipart/form-data']]);
		$file_id = Yii::$app->utility->encryptString($fileinfo['file_id']);
		$movement_id = Yii::$app->utility->encryptString($movement['id']);
		?>
		<input type='hidden' name='Remarks[key]' value='<?=$file_id?>' readonly />
		<input type='hidden' name='Remarks[key1]' value='<?=$movement_id?>' readonly />
		<div class='row'>
			<?php if(!empty($all_docs)){ ?>
			<div class='col-sm-12'>
				<h6 style='background:#930404;color:#fff;padding: 5px;'><b><span class="hindishow">पासवर्ड संरक्षित दस्तावेजों की सूची</span> / List of password protected Documents.:-</b></h6>
				<table class='table table-bordered'>
					<tr>
						<th>Title</th>
						<th>Is Protected?</th>
						<th>Protected By</th>
						<th></th>
					</tr>
					<?php 
					foreach($all_docs as $a){
						$dakdocs_id = Yii::$app->utility->encryptString($a->dakdocs_id);
						$protect = "Yes";
						$protectby = Yii::$app->utility->get_employees($a->added_by);
						$protectby = $protectby['fullname'].", ".$protectby['desg_name'];
						
						if($a->is_protected == 'N'){
							$protect = "No";
						}
						$view  = "<button type='button' class='btn btn-primary btn-xs view_document_' data-key='$dakdocs_id' data-protect='$protect'>View</button>";
						echo "<tr>
							<td>$a->doc_title</td>
							<td>$protect</td>
							<td>$protectby</td>
							<td>$view</td>
						</tr>";
					}
					?>
				</table>
			</div>
			<?php } ?>
			<div class='col-sm-12'>
				<h6 class='text-center'><b><span class="hindishow">दस्तावेज़ में टिप्पणी या दस्तावेज़ संलग्न करें</span><br>Add Remarks OR Append document:-</b></h6>
				<textarea class='form-control form-control-sm' id='file_remarks' name='Remarks[file_remarks]' placeholder='टिप्पणी दर्ज करें / Enter Remarks' rows='1'><?=$draftfile_remarks?></textarea>
			</div>
                    <?php if($showNotes == 'Y'){ 
                        $upDoc= "Upload Document";
                        $upDocType= "Upload Document Type";
                        $BroDoc= "Browse Document";
                        if($fileinfo['action_type'] == '5'){
                            $upDoc= "Upload Proposal";
                            $BroDoc= "Browse Proposal";
                            $upDocType= "Upload Proposal Type";
                        }
                        ?>
			<div class="col-sm-6">
                            <br>
                            <h6><b><u><?=$upDoc?></u></b></h6>
			</div>
                        <div class="col-sm-6">
                            <?php 
                            $filedocs = Yii::$app->Dakutility->efile_get_dak_docs($fileinfo['file_id'],NULL);
                            
                            if(!empty($filedocs)){
                                $newFileSubbtn = "Submit to Append Document";
                            }
                            if($fileinfo['action_type'] == '5'){
                                if(!empty($filedocs)){
                                    $newFileSubbtn = "Submit to Proposal Document";
                                ?>
                            <input type="hidden" id="check_proposal" readonly="" value="Y" />
                            <label>Proposal Action</label>
                            <select id="efile_proposal_action" name="Remarks[proposal_action]" class="form-control form-control-sm">
                                <option data-key="0" value="">Select Proposal Action</option>
                                <option data-key="1" value="<?=Yii::$app->utility->encryptString('O')?>">Overwrite</option>
                                <option data-key="2" value="<?=Yii::$app->utility->encryptString('A')?>">Append</option>
                            </select>
                            <?php }else{
                                    echo '<input type="hidden" id="check_proposal" readonly="" value="N" />';
                                }
                            }else{
                                echo '<input type="hidden" id="check_proposal" readonly="" value="N" />';
                            } ?>
			</div>
			<div class="col-sm-6">
                            <label><?=$upDocType?></label>
                            <select id="efile_doc_type" name="Remarks[doc_type]" class="form-control form-control-sm" >
                                <option data-key="0" value="">Select File Type</option>
                                <option data-key="1" value="<?=Yii::$app->utility->encryptString('PDF')?>">PDF</option>
                            </select>
			</div>
			<div class="col-sm-6">
				<label><?=$BroDoc?></label>
				<span id='pdf_file_html' style='display:block;'>
					<input type="file" id="pdf_docs_path" name="pdf_path" class="form-control form-control-sm fts_pdf" accept=".pdf" />
					<span style="font-size: 11px;">File size cannot be more then <?=FTS_Doc_Size?> MB</span>
				</span>
				<!--<span id='image_file_html' style='display:none;'>
					<input type="file" id="fts_image_multiple" name="image_path[]" class="form-control form-control-sm fts_image_multiple" accept=".jpg,.png, .jpeg" multiple  />
					<span style="color: red;font-size: 11px;">You can select multiple Images.<br>Each image size cannot be more then <?=FTS_Image_Size?> MB</span>
				</span>-->
			</div>
			<div class="col-sm-12 protectcss" style="display: none;">
                <label>Document Title <span style="color: red;font-weight: bold;">*</span></label>
                <input type="text" class="form-control form-control-sm" id="doc_title" placeholder="Document Title" name="doc_title" autocomplete='off' />
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
                <label>Document Password <span style="color: red;font-weight: bold;">*</span></label>
                <input type="password" class="form-control form-control-sm" id="file_password" placeholder="Document Password" name="file_password" />
            </div>
                        <div class="col-sm-12">
                            <div class="alert alert-danger">
                                
                                <b>Note:-<br>
                                    1.  Document once submitted, cannot be removed.<br>
                                    2. You can upload document multiple time.
                                </b>
                            </div>
			</div>
                    <?php } ?>
			<div class='col-sm-12 text-center'>
				<button type='button' class='btn btn-success btn-sm' onclick='validateRemarks()'><?=$newFileSubbtn?></button>
			</div>
		</div>
		<?php ActiveForm::end();?>
	</div>
</div>
<!--<option data-key="2" value="<?php //Yii::$app->utility->encryptString('Image')?>">Image</option>-->
<script>
$(document).ready(function(){
	setInterval(function(){ draft_file(); }, 120000); 
});
</script>