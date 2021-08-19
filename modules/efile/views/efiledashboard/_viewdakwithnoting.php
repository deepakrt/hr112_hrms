<?php
use app\models\EfileMasterCategory;
use app\models\EfileMasterProject;
use app\models\EfileDakDocs;
use app\models\EfileDakMovement;
use yii\widgets\ActiveForm;
use app\models\EfileDakGroups;
use app\models\EfileDakGroupMembers;
$this->title = "View Inbox Dak";
// echo "<pre>";print_r($movement);
 $filedocspath = Yii::$app->Dakutility->makefilefromdocs($fileinfo['file_id']);
 ///$doc_path = Yii::$app->fts_utility->getdocumentpath($doc_path);
//$doc_path = Yii::$app->homeUrl."hrms.pdf";
//echo $doc_path;die;
$fileidfordnl=Yii::$app->utility->encryptString($fileinfo['file_id']);
?>
<div class='row'>
	<div class='col-sm-12'>
		<?php if(!empty($receiptInfo)){ 
		$recNo = $receiptInfo['dak_number']."<br> Dated ".date('d-m-Y H:is', strtotime($receiptInfo['forwarded_date']));
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
			<li><b>Receipt No. & Dated</b><br><?=$recNo?></li>
			<li><b>Received From </b><br> <?=$receiptInfo['rec_from']?></li>
			<li><b>Address </b><br> <?=$address?></li>
			<li><b>Received On</b><br> <?=date('d-m-Y', strtotime($receiptInfo['rec_date']))?></li>
			<li><b>Received Mode</b><br> <?=$receiptInfo['mode_of_rec']?></li>
			<li><b>Summary</b><br> <?=$receiptInfo['dak_summary']?></li>
			<li><b>Remarks</b><br> <?=$receiptInfo['dak_remarks']?></li>
			<li><b>Forwarded To</b><br> <?=$fwdto?></li>
		</ul>
		<hr class='hrline'>
		<?php	}
		?>
		<h5 class='text-center'><b>File Details:-</b></h5>
		<?php 
		$refNo = $fileinfo['reference_num']."<br>Dated ".date('d-m-Y', strtotime($fileinfo['reference_date']));
		$cat = EfileMasterCategory::find()->where(['file_category_id' => $fileinfo['file_category_id']])->asArray()->one();
		
		$project = EfileMasterProject::find()->where(['file_project_id' => $fileinfo['file_project_id']])->asArray()->one();
		
		?>
		<ul class='fileinfo'>
			<li><b>Status</b><br> <?=$fileinfo['status']?></li>
			<li><b>Ref. No. & Dated</b><br><?=$refNo?></li>
			<li><b>Category</b><br> <?=$cat['name']?></li>
			<?php if(!empty($project)){ ?>
			<li><b>Project Name</b><br> <?=$cat['project_name']?></li>
			<?php } ?>
			<li><b>Is confidential?</b><br> <?=Yii::$app->fts_utility->showYesNo($fileinfo['is_confidential'])?></li>
			<li><b>Priority</b><br> <?=$fileinfo['priority']?></li>
			<li><b>Action Type</b><br> <?=$fileinfo['action_type']?></li>
			<li><b>Access Level</b><br> <?=Yii::$app->fts_utility->get_efile_access_level("G", $fileinfo['access_level'])?></li>
		</ul>
		<hr class='hrline'>
	</div>
	<div class='col-sm-6 text-left'>
		<a href='<?=Yii::$app->homeUrl?>efile/inbox/downloadgreensheet?securekey=<?=$menuid?>&fileid=<?=$fileidfordnl?>' target='_blank' class='btn btn-success btn-xs'>Download Green Sheet</a>
	</div>
	<div class='col-sm-6 text-right'>
		<a href='<?=Yii::$app->homeUrl?>efile/inbox/downloadfile?securekey=<?=$menuid?>&fileid=<?=$fileidfordnl?>' target='_blank' class='btn btn-danger btn-xs'>Download File</a>
	</div>
</div>
<div class='row'>
	<div class='col-sm-5'>
		<h5 class='text-center'><b>Green Sheet</b></h5>
		<div class='greensheet'>
		<?php 
		$notes = Yii::$app->fts_utility->efile_get_efile_dak_notes($fileinfo['file_id'], NULL);
		
		if(!empty($notes)){
			foreach($notes as $n){ 
			
			$ranid = rand(100,1000);
			$noteby = "";
			$noteby = Yii::$app->utility->get_employees($n['added_by']);
			$noteby = $noteby['fullname'].", ".$noteby['desg_name']." ($noteby[dept_name])";
				
			$notedoc = EfileDakDocs::find()->where(['file_id' => $fileinfo['file_id'], 'noteid'=>$n['noteid']])->asArray()->one();
			?>
			<div class='row'>
				<div class='col-sm-12'><hr></div>
				<div class='col-sm-6'><b><?=$noteby?></b></div>
				<div class='col-sm-6'>
					<b>Note Dated : <?=date('d-m-Y H:i:s', strtotime($n['added_by']))?></b>
				</div>
				<div class='col-sm-12'>
				<hr>
					<?php 
					$checklnght = strlen($n['note_comment']);
					if($checklnght > 250){
						echo "<p>".substr($n['note_comment'],0,250)."........</p>";
						echo "<p id='fullnote_$ranid' style='display:none;'>$n[note_comment]</p>
					<div class='text-right'><button type='button' class='btn btn-primary btn-xs viewnote' data-key='$ranid' data-toggle='modal' >View Full Note</button></div>";
					}else{
						echo "<p>".$n['note_comment']."</p>";
					}
					?>
					
				</div>
				<?php if(!empty($notedoc)){ ?>
				<div class='col-sm-12 text-right'>
					<a href='<?=Yii::$app->homeUrl?>hrms.pdf' target='_blank' class='btn btn-success btn-xs'>View Attached File</a>
				</div>
				<?php } ?>
				
			</div>	
		<?php }
		}
		?>
		</div>
	</div>
	<div class='col-sm-7'>
	<h5 class='text-center'><b>Document</b></h5>
		<iframe src="<?=Yii::$app->homeUrl.$filedocspath?>" style="width:100%; height:600px;" frameborder="0"></iframe>
	</div>
	
</div>

<?php 
if($fileinfo['access_level'] == 'RW' AND $movement['is_reply_required'] == 'Y' AND $movement['reply_status'] == 'N' AND $movement['fwd_by'] != Yii::$app->user->identity->e_id){
?>
<div class='row'>
<div class='col-sm-12'><hr class='hrline'></div>
	<div class='col-sm-12'>
		<?php 
		$noteurl = Yii::$app->homeUrl."efile/inbox/addnewnote?securekey=$menuid";
		ActiveForm::begin(['action'=>$noteurl, 'options' => ['enctype' => 'multipart/form-data']]);
		$file_id = Yii::$app->utility->encryptString($fileinfo['file_id']);
		$movement_id = Yii::$app->utility->encryptString($movement['id']);
		?>
		<input type='hidden' name='Newnote[key]' value='<?=$file_id?>' readonly />
		<input type='hidden' name='Newnote[key1]' value='<?=$movement_id?>' readonly />
		<div class='row'>
			<div class='col-sm-12'>
				<h5 class='text-center'><b>Add Comments on Green Sheet:-</b></h5>
				<textarea class='form-control form-control-sm' name='Newnote[note_comment]' placeholder='Enter Comments' rows='6' required></textarea>
			</div>
			<div class='col-sm-8'>
				<div class='row'>
					<div class="col-sm-12">
						<br>
						<h6><b><u>Upload File</u></b></h6>
					</div>
					<div class="col-sm-6">
						<label>Upload File Type</label>
						<select id="efile_doc_type" name="Newnote[doc_type]" class="form-control form-control-sm" >
							<option data-key="0" value="">Select File Type</option>
							<option data-key="1" value="<?=Yii::$app->utility->encryptString('PDF')?>">PDF</option>
							<option data-key="2" value="<?=Yii::$app->utility->encryptString('Image')?>">Image</option>
						</select>
					</div>
					<div class="col-sm-6">
						<label>Browse File</label>
						<span id='pdf_file_html'>
							<input type="file" id="pdf_docs_path" name="pdf_path" class="form-control form-control-sm fts_pdf" accept=".pdf" />
							<span style="color: red;font-size: 11px;">File size cannot be more then <?=FTS_Doc_Size?> MB</span>
						</span>
						<span id='image_file_html' style='display:none;'>
							<input type="file" id="fts_image_multiple" name="image_path[]" class="form-control form-control-sm fts_image_multiple" accept=".jpg,.png, .jpeg" multiple  />
							<span style="color: red;font-size: 11px;">You can select multiple Images.<br>Each image size cannot be more then <?=FTS_Image_Size?> MB</span>
						</span>
					</div>
				</div>
			</div>
			
			<div class='col-sm-2'>
				<br><br><br>
				<button type='submit' class='btn btn-success btn-sm'>Add Note</button>
			</div>
		</div>
		<?php ActiveForm::end();?>
	</div>
</div>

<?php

	$fwdurl = Yii::$app->homeUrl."efile/inbox/forwarddaktoother?securekey=$menuid";
	ActiveForm::begin(['action'=>$fwdurl, 'options' => ['enctype' => 'multipart/form-data']]);
	$file_id = Yii::$app->utility->encryptString($fileinfo['file_id']);
	$movement_id = Yii::$app->utility->encryptString($movement['id']);
	echo "<input type='hidden' name='Forward[key]' value='$file_id' readonly />";
	echo "<input type='hidden' name='Forward[key1]' value='$movement_id' readonly />";
	echo \Yii::$app->view->renderFile(Yii::getAlias('@app') . '/modules/efile/forwardto_html.php', ['file_id'=>$fileinfo['file_id']]);
	
	ActiveForm::end();
}
?>
<!-- For own-->
<?php 
if($movement['fwd_by'] == Yii::$app->user->identity->e_id AND $movement['fwd_emp_code'] == Yii::$app->user->identity->e_id){
	$fwdurl = Yii::$app->homeUrl."efile/inbox/forwarddaktoother?securekey=$menuid";
	ActiveForm::begin(['action'=>$fwdurl, 'options' => ['enctype' => 'multipart/form-data']]);
	$file_id = Yii::$app->utility->encryptString($fileinfo['file_id']);
	$movement_id = Yii::$app->utility->encryptString($movement['id']);
	echo "<input type='hidden' name='Forward[key]' value='$file_id' readonly />";
	echo "<input type='hidden' name='Forward[key1]' value='$movement_id' readonly />";
	echo \Yii::$app->view->renderFile(Yii::getAlias('@app') . '/modules/efile/forwardto_html.php', ['file_id'=>$fileinfo['file_id']]);
	
	ActiveForm::end();
	
	
}
?>

<!-- End For own-->

	
<?php 
// echo "<pre>";print_r($receiptInfo);
// Response Html
if($movement['is_reply_required'] == 'Y' AND $movement['reply_status'] == 'N' AND $movement['fwd_by'] != Yii::$app->user->identity->e_id ){
	$hidefromowner = "";
	if(!empty($receiptInfo)){
		if($receiptInfo['dak_fwd_to'] == Yii::$app->user->identity->e_id){
			$hidefromowner = "Y";
		}
	}
	if(empty($hidefromowner)){
	// echo $fileinfo['file_id']."<br>";
	// echo Yii::$app->user->identity->e_id."<br>";
	$checkMovement = EfileDakMovement::find()->where(['file_id' => $fileinfo['file_id'], 'fwd_by' => Yii::$app->user->identity->e_id])->asArray()->all();
	$canRevert = "N";
	// echo "<pre>";print_r($checkMovement); 
	?>
<div class='row'>
	<?php
	$html = "";
	if(!empty($checkMovement)){
		$html .= "<h6><b>You forwarded this file to:-</b></h6>";
		$html .=" <ul class='movementli'>";
		foreach($checkMovement as $c){
			$empinfo = Yii::$app->utility->get_employees($c['fwd_emp_code']);
			$fwd_to = $empinfo['fname']." ".$empinfo['lname'].", ".$empinfo['desg_name'];
			$fwd_type = "Individual";
			$is_time_bound= "No";
			if($c['is_time_bound'] == 'Y'){
				$response_date = date('d-m-Y', strtotime($l['response_date']));
				$is_time_bound = "<span style='color:red;font-weight:bold;'>Yes<br>(Till $response_date)</span>";
			}
			$reply_status = "No action taken. Still pending";
			if($c['reply_status'] == 'Y'){
				$reply_status = "Already revert back";
				$canRevert = "Y";
			}
			
			if($c['fwd_to'] == 'G'){
				$fwd_to = "";
				$fwd_type = "Group";
				// $groups = EfileDakGroups::find()->where(['file_id' => $file_id, 'dak_group_id'=>$c['dak_group_id'], 'is_active'=>'Y'])->asArray()->all();
				$members = EfileDakGroupMembers::find()->where(['dak_group_id' => $g['dak_group_id'], 'is_active'=>'Y'])->asArray()->all();
				foreach($members as $m){
					$group_role = "Member";
						if($m['group_role'] == 'CH'){
							$group_role = "<b>Chairman</b>";
						}elseif($m['group_role'] == 'C'){
							$group_role = "<b>Convenor</b>";
						}
						$memberInfo = Yii::$app->utility->get_employees($m['employee_code']);
						$memberInfo = $memberInfo['fullname'].", ".$memberInfo['desg_name']." ($memberInfo[dept_name]) ($group_role)";
						$fwd_to .= "$i. $memberInfo, ";
				}
			}
			$html .="<li><b>Forwarded Type</b><br>$fwd_type</li>
			<li><b>Forwarded To</b><br>$fwd_to</li>
			<li><b>Is Time Bound?</b><br>$is_time_bound</li>
			<li><b>Reply Status?</b><br>$reply_status</li>";
		}
		$html .= "</ul>";
	}?>
	<div class='col-sm-12'>
		<hr class='hrline'>
		<?=$html?>
	</div>
	</div>
	<?php 
	$canRevert = "Y";
	if($canRevert == 'Y'){
		$fwdback = EfileDakMovement::find()->where(['id' => $movement['id']])->asArray()->one();
		$empinfo = Yii::$app->utility->get_employees($fwdback['fwd_by']);
		$fwd_to = $empinfo['fname']." ".$empinfo['lname'].", ".$empinfo['desg_name'];
		$file_id = Yii::$app->utility->encryptString($fileinfo['file_id']);
		$movement_id = Yii::$app->utility->encryptString($movement['id']);
		?>
	<div class='col-sm-12 text-center'>
		<div class='alert alert-info'>
			<h6><b>After action you return file back to <b><?=$fwd_to?></b></b></h6>
		</div>
		<a href='<?=Yii::$app->homeUrl?>efile/inbox/responseback?securekey=<?=$menuid?>&key=<?=$file_id?>&key1=<?=$movement_id?>' class='btn btn-success btn-sm returnfile'> Click to response and send file back</a>
	</div>
	<?php } ?> 


<?php } // end hidefromowner
}
?> 



<div class="modal fade" id="viewfullnote" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">View Full Note</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id='show_full_note'></p>
      </div>
      
    </div>
  </div>
</div>