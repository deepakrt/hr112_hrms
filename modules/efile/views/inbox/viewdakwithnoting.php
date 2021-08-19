<?php
use app\models\EfileMasterCategory;
use app\models\EfileMasterProject;
use app\models\EfileDakDocs;
use app\models\EfileDakMovement;
use yii\widgets\ActiveForm;
use app\models\EfileDakGroups;
use app\models\EfileDakGroupMembers;
use app\models\EfileDakNotes;
$this->title = "View Inbox Dak";
// echo "<pre>";print_r($movement);
  $filedocspath = Yii::$app->Dakutility->makefilefromdocs($fileinfo['file_id']);

$fileidfordnl=Yii::$app->utility->encryptString($fileinfo['file_id']);

$notes = EfileDakNotes::find()->where(['file_id' => $fileinfo['file_id'], 'status'=>'S', 'content_type'=>'N', 'is_active'=>'Y'])->asArray()->all();
$noteFont =  $showNotes = "style=''";
$showdoc = "style=''";
$docClass = "col-sm-7";
$noteClass = "col-sm-5";
$rightlogoCSS = $leftlogoCSS = "width:50px;";
$noteTitle_1 = $noteTitle_2 = "";
$filedocs = Yii::$app->Dakutility->efile_get_dak_docs($fileinfo['file_id'],NULL);
if(empty($notes)){
	$showNotes = "style='display:none;'";
	if(!empty($filedocs)){
		$docClass = "col-sm-12";
	}
	
}else{
	if(empty($filedocs)){
		$leftlogoCSS = "width:85%;";
		$rightlogoCSS = "width:12%;";
		$noteTitle_1 = "font-size:32px;";
		
		$noteTitle_2 = "font-size:20px;font-weight:bold;";
		$noteClass = "col-sm-12";
		$showdoc = "style='display:none;'";
		$noteFont = "style='font-size:14px;'";
	}
}
// echo "**$showNotes";die;
$remarks = EfileDakNotes::find()->where(['file_id' => $fileinfo['file_id'], 'status'=>'S', 'content_type'=>'R', 'is_active'=>'Y'])->asArray()->all();
// echo "<pre>";print_r($remarks);
?>
<style>
.greensheet{
	padding:5px;
}
.ifrmeborder{
 border: 2px #ED865D solid; 
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
		<h5 class='text-center'><b><span class='hindishow'>रसीद जानकारी</span> / Receipt Details:-</b></h5>
		<ul class='fileinfo'>
			<li><b><span class="hindishow12">रसीद संख्या तथा तारीख </span> / Receipt No. & Date</b><br><?=$recNo?></li>
			<li><b><span class="hindishow12">से प्राप्त किया / </span>Received From </b><br> <?=$receiptInfo['rec_from'].", $address"?></li>
                        <li><b><span class="hindishow12">प्राप्त साधन / </span>Received Mode</b><br> <?=$receiptInfo['mode_of_rec']?></li>
			<?php if(!empty($receiptInfo['dak_summary'])){ ?>
                        <li><b><span class="hindishow12">संक्षिप्त सारांश </span>/ Summary</b><br> <?=$receiptInfo['dak_summary']?></li>
                        <?php } ?>
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
			<?php if(!empty($project)){ ?>
			<li><b><span class="hindishow12">परियोजना का नाम</span> / Project Name</b><br> <?=$project['project_name']?></li>
			<?php } ?>
			<li><b><span class="hindishow12">गोपनीय है?</span> / Is confidential?</b><br> <?=Yii::$app->fts_utility->showYesNo($fileinfo['is_confidential'])?></li>
			<li><b><span class="hindishow12">प्राथमिकता</span> / Priority</b><br> <?=$fileinfo['priority']?></li>
			<li><b><span class="hindishow12">किस लिए अग्रेषित</span> / Forward For</b><br> <?=$fileinfo['action_type']?></li>
			<li><b>Access Level</b><br> <?=Yii::$app->fts_utility->get_efile_access_level("G", $fileinfo['access_level'])?></li>
		</ul>
		
                <p class='text-justify'><b><span class="hindishow12">विषय</span> / Subject:- </b><?=$fileinfo['subject']?></p>
                <hr>
		<?php if(!empty($fileinfo['summary'])) { ?>
		<p class='text-justify'><b><span class="hindishow12">सारांश</span> / Summary:- </b><?=$fileinfo['summary']?></p>
		<hr>
		<?php } ?>
		<?php if(!empty($fileinfo['remarks'])) { ?>
		<p class='text-justify'><b><span class="hindishow12">टिप्पणी</span> / Remarks:- </b><?=$fileinfo['remarks']?></p>
		<?php } ?>
		<hr class='hrline'>
		
	</div>
</div>
<div class='row'>
	<div class='<?=$noteClass?>' <?=$showNotes?>>
		<div class='text-left'>
			<a href='<?=Yii::$app->homeUrl?>efile/inbox/downloadgreensheet?securekey=<?=$menuid?>&fileid=<?=$fileidfordnl?>' target='_blank' class='btn btn-success btn-xs '>Download Note</a>
		</div>
	
		<div class='greensheet'>
		<?php 
		
		if(!empty($notes)){
			$deplLogo=Yii::$app->homeUrl."images/cdac.jpeg";
			$swachhbharatabhiyan=Yii::$app->homeUrl."images/swacchbharatlogo.jpeg";
			echo '<div class="row">
				<div class="col-sm-2" style="text-align:left"><img src='."$deplLogo".'  style="'.$leftlogoCSS.'"/></div>
				<div class="col-sm-10"><p   style="text-align:center;'.$noteTitle_1.' ">प्रगत संगणन विकास केंद्र,मोहाली</p>
				<p  style="text-align:center;'.$noteTitle_2.'">CENTRE FOR DEVELOPMENT OF ADVANCED COMPUTING,MOHALI</p></div>
				<div class="col-sm-12" style="text-align:right"><img src='."$swachhbharatabhiyan".' style="'.$rightlogoCSS.'" /></div>
			</div>';
			echo "<div class='col-sm-12 text-center'>
					<p style='".$noteFont."'><u><b>NOTE</b></u></p>
				</div>";
			foreach($notes as $n){ 
			$subject = $n['note_subject'];
			$ranid = rand(100,1000);
			$noteby = "";
			$noteby = Yii::$app->utility->get_employees($n['added_by']);
			$noteby = $noteby['fullname'].",<br>".$noteby['desg_name']." ($noteby[dept_name])";
				
			$notedoc = EfileDakDocs::find()->where(['file_id' => $fileinfo['file_id'], 'noteid'=>$n['noteid']])->asArray()->one();
			
                        ?>
                        
			<div class='row' >
				<?php if(!empty($subject )){ 
				echo "<div class='col-sm-12' $noteFont>
					<b>Subject : $subject</b>
					<hr>
				</div>";
				} ?>
				<div class='col-sm-12 text-right' <?=$noteFont?>>
					<u><b><?=date('d-m-Y', strtotime($n['added_date']))?></b></u>
				</div>
				
				<div class='col-sm-12' <?=$noteFont?>>
					<?php 
					$checklnght = strlen($n['note_comment']);
					if($checklnght > 250){
						echo "<p class='text-justify'>".substr($n['note_comment'],0,250)."........</p>";
						echo "<p  class='text-justify' id='fullnote_$ranid' style='display:none;'>$n[note_comment]</p>
					<div class='text-right'><button type='button' class='btn btn-primary btn-xs viewnote' data-key='$ranid' data-toggle='modal' >View Full Note</button></div>";
					}else{
						echo "<p>".$n['note_comment']."</p>";
					}
					?>
					
				</div>
				<div class='col-sm-12 text-right' <?=$noteFont?>><b><?=$noteby?></b><hr></div>
				
			</div>	
		<?php }
		}
		?>
		</div>
	</div>
	<div class='<?=$docClass?>' <?=$showdoc?>>
	<div class='text-right'>
		<a href='<?=Yii::$app->homeUrl?>efile/inbox/downloadfile?securekey=<?=$menuid?>&fileid=<?=$fileidfordnl?>' class='btn btn-danger btn-xs'>Download Document</a>
	</div>
	<h5 class='text-center'><b>Document</b></h5>
		<div class="ifrmeborder">
           <iframe src="<?=Yii::$app->homeUrl.$filedocspath?>" style="width:100%; height:600px;" frameborder="0"></iframe> 
        </div>
		
	</div>
	<?php if(!empty($remarks)){ ?>
		<div class='col-sm-12'>
			<br>
			<div class='text-right'>
				<button type='button' class='btn btn-success btn-sm' data-toggle="modal" data-target="#viewallremarks">Click to view previous Remarks</button>
			</div>
		</div>
		<?php } ?>
</div>
<!-- ========Note show only for file Creater-->

<!--Group Remarks -->
<?= \Yii::$app->view->renderFile(Yii::getAlias('@app') . '/modules/efile/group_members_remarks.php', ['file_id'=>$fileinfo['file_id'], 'fileinfo'=>$fileinfo, 'movement'=>$movement, 'menuid'=>$menuid ]);?>
<!--End Group Remarks -->




<?php 
if($movement['fwd_by'] == Yii::$app->user->identity->e_id AND $movement['fwd_emp_code'] == Yii::$app->user->identity->e_id){
	//echo \Yii::$app->view->renderFile(Yii::getAlias('@app') . '/modules/efile/add_note_html.php', ['file_id'=>$fileinfo['file_id'], 'fileinfo' =>$fileinfo, 'movement'=>$movement, 'menuid'=>$menuid , 'notes'=>$notes]);
}
?>
<!-- ========End Note show only for file Creater-->
<?php 

echo \Yii::$app->view->renderFile(Yii::getAlias('@app') . '/modules/efile/add_note_html.php', ['file_id'=>$fileinfo['file_id'], 'fileinfo' =>$fileinfo, 'movement'=>$movement , 'menuid'=>$menuid, 'notes'=>$notes]);

if($movement['fwd_to'] == 'G'){
    $dakgrpid = Yii::$app->utility->encryptString($movement['dak_group_id']);
    $fw = "<input type='hidden' name='old_id' value='$dakgrpid' readonly />";
}else{
    $id = Yii::$app->utility->encryptString($movement['id']);
    $fw = "<input type='hidden' name='old_id' value='$id' readonly />";
}

// if($fileinfo['access_level'] == 'RW' AND $movement['is_reply_required'] == 'Y' AND $movement['reply_status'] == 'N' AND $movement['fwd_by'] != Yii::$app->user->identity->e_id){

	// check is member of group
    if($movement['fwd_to'] == 'G'){
        $members = EfileDakGroupMembers::find()->where(['dak_group_id' =>$movement['dak_group_id'], 'is_active'=>'Y'])->asArray()->all();
        $group_role = "";
		foreach($members as $m){
			if($m['employee_code'] == Yii::$app->user->identity->e_id){
				$group_role = $m['group_role'];
			}
		}
		if($group_role == 'CH'){
                        
                    
			$fwdurl = Yii::$app->homeUrl."efile/inbox/forwarddaktoother?securekey=$menuid";
			ActiveForm::begin(['action'=>$fwdurl, 'id'=>'fwrdform', 'options' => ['enctype' => 'multipart/form-data']]);
			$file_id = Yii::$app->utility->encryptString($fileinfo['file_id']);
			$movement_id = Yii::$app->utility->encryptString($movement['id']);
			echo "<input type='hidden' name='Forward[key]' value='$file_id' readonly />";
			echo "<input type='hidden' name='Forward[key1]' value='$movement_id' readonly />";
			echo $fw;
			echo \Yii::$app->view->renderFile(Yii::getAlias('@app') . '/modules/efile/forwardto_html.php', ['file_id'=>$fileinfo['file_id'], 'movement'=>$movement, 'menuid'=>$menuid ]);

			ActiveForm::end();
		}
    }else{
		$fwdurl = Yii::$app->homeUrl."efile/inbox/forwarddaktoother?securekey=$menuid";
		ActiveForm::begin(['action'=>$fwdurl, 'id'=>'fwrdform', 'options' => ['enctype' => 'multipart/form-data']]);
		$file_id = Yii::$app->utility->encryptString($fileinfo['file_id']);
		$movement_id = Yii::$app->utility->encryptString($movement['id']);
		echo "<input type='hidden' name='Forward[key]' value='$file_id' readonly />";
                echo $fw;
		echo "<input type='hidden' name='Forward[key1]' value='$movement_id' readonly />";
		echo \Yii::$app->view->renderFile(Yii::getAlias('@app') . '/modules/efile/forwardto_html.php', ['file_id'=>$fileinfo['file_id'], 'movement'=>$movement, 'menuid'=>$menuid ]);
		
		ActiveForm::end();
    }
// }
?>
<!-- For notes view for owner-->
<?php 
if($movement['fwd_by'] == Yii::$app->user->identity->e_id AND $movement['fwd_emp_code'] == Yii::$app->user->identity->e_id){
//	$fwdurl = Yii::$app->homeUrl."efile/inbox/forwarddaktoother?securekey=$menuid";
//	ActiveForm::begin(['action'=>$fwdurl, 'options' => ['enctype' => 'multipart/form-data']]);
//	$file_id = Yii::$app->utility->encryptString($fileinfo['file_id']);
//	$movement_id = Yii::$app->utility->encryptString($movement['id']);
//	echo "<input type='hidden' name='Forward[key]' value='$file_id' readonly />";
//	echo "<input type='hidden' name='Forward[key1]' value='$movement_id' readonly />";
//	echo \Yii::$app->view->renderFile(Yii::getAlias('@app') . '/modules/efile/forwardto_html.php', ['file_id'=>$fileinfo['file_id'], 'movement'=>$movement, 'menuid'=>$menuid ]);
//	
//	
//	ActiveForm::end();
	
	
}
?>
<!-- End For own-->


<div class="modal fade" id="viewallremarks" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">All Remarks on File</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
			<div class='row'>
				<?php 
				if(!empty($remarks)){
					
					foreach($remarks as $r){
						// die("***");
						if(!empty($r['note_comment'])){
						$m = Yii::$app->utility->get_employees($r['added_by']);
						$m = $m['fullname'].", ".$m['desg_name']." ($m[dept_name])";
				?>
				<div class='col-sm-12'>
					<p><b>Remarks By <?=$m?></b></p>
					<p class='text-justify'><?=$r['note_comment']?></p>
					<p class='text-right'>
						<b>Dated: <?=date('d-m-Y', strtotime($r['added_date']));?></b>
					</p>
					<hr>
				</div>
				<?php 
				}
				}
				}
				?>
			</div>
      </div>
      
    </div>
  </div>
</div>


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
        
      </div>
      
    </div>
  </div>
</div>
