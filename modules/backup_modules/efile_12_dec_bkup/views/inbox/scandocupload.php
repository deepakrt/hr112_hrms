<?php
use app\models\EfileMasterCategory;
use app\models\EfileMasterProject;
use yii\widgets\ActiveForm;

$this->title = "स्कैन दस्तावेज़ के लिए अनुरोध / Request for Scan Document";

 
?>
<style>
    .fileinfo li {
	width: 49%;
    }
</style>
<div class='row'>
	<div class='col-sm-12'>
		<?php if(!empty($receiptInfo)){ 
		$recNo = $receiptInfo['dak_number']." Dated ".date('d-m-Y', strtotime($receiptInfo['rec_date']));
		$dist = Yii::$app->fts_utility->get_master_districts($receiptInfo['org_district'], NULL);
		$address = $receiptInfo['org_address'];
		if(!empty($dist)){
			$address .= " Distt. $dist[district_name], $dist[state_name]";
		}
		
		$empinfo = Yii::$app->utility->get_employees($receiptInfo['dak_fwd_to']);
		$fwdto = $empinfo['fullname'].", ".$empinfo['desg_name'];
		?>
		<h5 class='text-center'><b>Receipt Details:-</b></h5>
		<ul class='fileinfo'>
                    <li><b><span class="hindishow12">रसीद संख्या तथा दिनांक / </span>Receipt No. & Date</b><br><?=$recNo?></li>
                    <li><b><span class="hindishow12">से प्राप्त किया / </span>Received From </b><br> <?=$receiptInfo['rec_from'].", $address"?></li>
                    <li><b><span class="hindishow12">प्राप्त साधन / </span>Received Mode</b><br> <?=$receiptInfo['mode_of_rec']?></li>
                    <?php if(!empty($receiptInfo['dak_remarks'])){ ?>
                    <li><b><span class="hindishow12">टिप्पणी / </span>Remarks</b><br> <?=$receiptInfo['dak_remarks']?></li>
                    <?php } ?>
                    <li><b><span class="hindishow12">अग्रेषित दिनांक</span>Forwarded On</b><br> <?=date('d-m-Y', strtotime($receiptInfo['forwarded_date']))?></li>
                    <li><b><span class="hindishow12">किस को अग्रेषित</span>Forwarded To</b><br> <?=$fwdto?></li>
		</ul>
		<hr class='hrline'>
		<?php	}
		?>
		<h5 class='text-center'><b>फ़ाइल विवरण / File Details:-</b></h5>
		<?php 
		$refNo = $fileinfo['reference_num']."<br>Date ".date('d-m-Y', strtotime($fileinfo['reference_date']));
		$cat = EfileMasterCategory::find()->where(['file_category_id' => $fileinfo['file_category_id']])->asArray()->one();
		
		$project = EfileMasterProject::find()->where(['file_project_id' => $fileinfo['file_project_id']])->asArray()->one();
		
		?>
		<ul class='fileinfo'>
                    <li><b><span class="hindishow12">स्थिति</span> / Status</b><br> <?=$fileinfo['status']?></li>
			<li><b><span class="hindishow12">रसीद संख्या तथा दिनांक</span> / Ref. No. & Date</b><br><?=$refNo?></li>
			<li><b><span class="hindishow12">श्रेणी</span> / Category</b><br> <?=$cat['name']?></li>
		</ul>
		<p class='text-justify'><b>Subject:- </b><?=$fileinfo['subject']?></p>
		<?php if(!empty($fileinfo['summary'])) { ?>
		<p class='text-justify'><b>Summary:- </b><?=$fileinfo['summary']?></p>
		<hr>
		<?php } ?>
		<?php if(!empty($fileinfo['remarks'])) { ?>
		<p class='text-justify'><b>Remarks:- </b><?=$fileinfo['remarks']?></p>
		<?php } ?>
		<hr class='hrline'>
		
	</div>
</div>
<?php ActiveForm::begin(['id'=>'scandocform', 'options' => ['enctype' => 'multipart/form-data']]); 
$file_id = Yii::$app->utility->encryptString($fileinfo['file_id']);
$movement_id = Yii::$app->utility->encryptString($movement['id']);
echo "<input type='hidden' name='Scan[key]' value='$file_id' readonly />";
echo "<input type='hidden' name='Scan[key1]' value='$movement_id' readonly />";
?>
<div class='row'>
    <div class="col-sm-12"><h6><b><u><span class="hindishow">स्कैन फ़ाइल अपलोड करें / </span>Upload Scan File</u></b></h6></div>
	<div class="col-sm-5">
		<label>Upload File Type</label>
		<select id="efile_doc_type" name="Scan[doc_type]" class="form-control form-control-sm" >
			<option data-key="0" value="">Select File Type</option>
			<option data-key="1" selected='selected' value="<?=Yii::$app->utility->encryptString('PDF')?>">PDF</option>
			<!-- <option data-key="2" value="<?=Yii::$app->utility->encryptString('Image')?>">Image</option>-->
		</select>
	</div>
	<div class="col-sm-7">
		<label>Browse File</label>
		<span id='pdf_file_html' >
			<input type="file" id="pdf_docs_path" name="pdf_path" class="form-control form-control-sm fts_pdf" required accept=".pdf" />
			<span style="color: red;font-size: 11px;">File size cannot be more then <?=FTS_Doc_Size?> MB</span>
		</span>
		<span id='image_file_html' style='display:none;'>
			<input type="file" id="fts_image_multiple" name="image_path[]" class="form-control form-control-sm fts_image_multiple" accept=".jpg,.png, .jpeg" multiple  />
			<span style="color: red;font-size: 11px;">You can select multiple Images.<br>Each image size cannot be more then <?=FTS_Image_Size?> MB</span>
		</span>
	</div>
	<div class="col-sm-12 text-center">
		<br>
		<button type='button' id='scansubmitbtn' class='btn btn-success btn-sm'>Forward Back to Sender</button>
	</div>
</div>
<?php ActiveForm::end(); ?>