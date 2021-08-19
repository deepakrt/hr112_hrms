<?php 
$this->title = "View File Details";
use app\models\EfileDakGroups;
use app\models\EfileDakGroupMembers;
use app\models\EfileDakGroupMembersRemarks;
use app\models\EfileDakNotes;

use app\models\EfileMasterCategory;
use app\models\ProjectList;
use app\models\EfileDakHistory;
use app\models\EfileDakDocs;

$filedocs = Yii::$app->Dakutility->efile_get_dak_docs($fileinfo['file_id'],NULL);

$filedocspath = "";
if(!empty($filedocs)){
    $filedocspath = Yii::$app->Dakutility->makefilefromdocs($fileinfo['file_id']);
}
$fileidfordnl=Yii::$app->utility->encryptString($fileinfo['file_id']);
$notes = EfileDakNotes::find()->where(['file_id' => $fileinfo['file_id'], 'status'=>'S', 'content_type'=>'N', 'is_active'=>'Y'])->asArray()->all();
$noteFont =  $showNotes = "style=''";
$showdoc = "style=''";
$docClass = "col-sm-6 paddingzero";
$noteClass = "col-sm-6 paddingzero";
$rightlogoCSS = $leftlogoCSS = "width:50px;";
$noteTitle_1 = $noteTitle_2 = "";

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
$remarks = EfileDakNotes::find()->where(['file_id' => $fileinfo['file_id'], 'status'=>'S', 'content_type'=>'R', 'is_active'=>'Y'])->asArray()->all();
?>
<style>
.greensheet{
    padding:15px;
}
.ifrmeborder{
 border: 2px #ED865D solid; 
}
.paddingzero{padding :0px;}
</style>
<div class='row'>
    <?php  if($fileinfo['status'] == 'Closed'){ ?>
    <div class='col-sm-12'>
        <h5 class="text-center" style="color:#fff;background: red;padding: 10px;font-weight: bold;border-radius: 10px;">This File has been Closed / Completed.</h5>
    </div>
    <?php } ?>
       
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
		$refNo = $fileinfo['reference_num']." Date ".date('d-m-Y', strtotime($fileinfo['reference_date']));
		$cat = EfileMasterCategory::find()->where(['file_category_id' => $fileinfo['file_category_id']])->asArray()->one();
		
		$project = ProjectList::find()->where(['project_id' => $fileinfo['file_project_id']])->asArray()->one();
		
                $refTitle = "<b><span class='hindishow12'>रसीद संख्या तथा दिनांक</span> / Ref. No. & Date</b>";
                
                if($fileinfo['initiate_type'] == 'P'){
                    $refTitle = "<b><span class='hindishow12'>प्रस्ताव शीर्षक तथा दिनांक</span> /Proposal Title & Date</b>";
                }
                $action_type = "";
                $efile_action_type = Yii::$app->fts_utility->efile_get_actions($fileinfo['action_type'], NULL);
                if(!empty($efile_action_type)){
                    $action_type = $efile_action_type['action_name'];
                }
		?>
		<ul class='fileinfo'>
                    <li><b><span class="hindishow12">स्थिति</span> / Status</b><br> <?=$fileinfo['status']?></li>
			<li><?=$refTitle?><br><?=$refNo?></li>
			<li><b><span class="hindishow12">श्रेणी</span> / Category</b><br> <?=$cat['name']?></li>
			<?php if(!empty($project)){ ?>
			<li><b><span class="hindishow12">परियोजना का नाम</span> / Project Name</b><br> <?=$project['project_name']?></li>
			<?php } ?>
			<li><b><span class="hindishow12">गोपनीय है?</span> / Is confidential?</b><br> <?=Yii::$app->fts_utility->showYesNo($fileinfo['is_confidential'])?></li>
			<li><b><span class="hindishow12">प्राथमिकता</span> / Priority</b><br> <?=$fileinfo['priority']?></li>
			<li><b><span class="hindishow12">उद्देश्य </span> / Forward Purpose</b><br> <?=$action_type?></li>
			<li><b>Access Level</b><br> <?=Yii::$app->fts_utility->get_efile_access_level("G", $fileinfo['access_level'])?></li>
		</ul>
		
                <p class='text-justify'><b><span class="hindishow12">विषय</span> / Subject:- </b><?=$fileinfo['subject']?></p>
                <hr>
		<?php if(!empty($fileinfo['summary'])) { ?>
		<p class='text-justify'><b><span class="hindishow12">फ़ाइल सारांश</span> / File Summary:- </b><?=$fileinfo['summary']?></p>
		<hr>
		<?php } ?>
		<?php if(!empty($fileinfo['remarks'])) { ?>
		<p class='text-justify'><b><span class="hindishow12">फ़ाइल टिप्पणी</span> / File Remarks:- </b><?=$fileinfo['remarks']?></p>
		<?php } ?>
		<hr class='hrline'>
		
	</div>
</div>
<?php if(Yii::$app->user->identity->role != '20'){?>
<div class='row'>
        <?php if(!empty($notes)){ 
//            $roleID=Yii::$app->utility->encryptString(Yii::$app->user->identity->role);
            ?>
	<div class='<?=$noteClass?>' <?=$showNotes?>>
<!--		<div class='text-left'>
			<a href='<?=Yii::$app->homeUrl?>efile/inbox/downloadgreensheet?securekey=<?=$menuid?>&fileid=<?=$fileidfordnl?>' target='_blank' class='btn btn-success btn-xs'>Download Note</a>
		</div>-->
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
                        $chairman_emp_code = $committeeName = "";

			foreach($notes as $n){ 
			$subject = $n['note_subject'];
			$ranid = rand(100,1000);
			$note_align = $noteby = "";
                        if($n['file_fwd_type'] == 'G'){
                            $groupInfo = Yii::$app->fts_utility->efile_outboxgroups($n['file_id']); 
                            $noteby = $groupInfo['members'];
                            $note_align = "text-left";
                        }else{
							$role_name = "";
							if(!empty($n['current_role'])){
								if($n['current_role'] == '3' OR $n['current_role'] == '1'){
								}else{
									$role = Yii::$app->utility->get_roles($n['current_role']);
									if(!empty($role)){
										$role_name = "<br>($role[role])";
									}
								}
							}
                            $note_align = "text-right";
                            $noteby = Yii::$app->utility->get_employees($n['added_by']);
                            $noteby = $noteby['name_hindi']." / ".$noteby['fullname'].",<br>".$noteby['desg_name_hindi']." / ".$noteby['desg_name'].$role_name;
                        }
			
                           		
			$notedoc = EfileDakDocs::find()->where(['file_id' => $fileinfo['file_id'], 'noteid'=>$n['noteid']])->asArray()->one();
			
                        ?>
                        
			<div class='row' >
				<?php if(!empty($subject )){ 
				echo "<div class='col-sm-12 text-justify' $noteFont>
					<b>Subject : $subject</b>
					<hr>
				</div>";
				} ?>
				<div class='col-sm-12 text-right' <?=$noteFont?>>
					<u><b><?=date('d-m-Y', strtotime($n['added_date']))?></b></u>
				</div>
				
				<div class='col-sm-12 note_html text-justify' <?=$noteFont?>>
					<?php 
                                        echo $n['note_comment'];
//					$checklnght = strlen($n['note_comment']);
//					if($checklnght > 250){
//						echo "<p class='text-justify'>".substr($n['note_comment'],0,250)."........</p>";
//						echo "<p  class='text-justify' id='fullnote_$ranid' style='display:none;'>$n[note_comment]</p>
//					<div class='text-right'><button type='button' class='btn btn-primary btn-xs viewnote' data-key='$ranid' data-toggle='modal' >View Full Note</button></div>";
//					}else{
//						echo "<p>".$n['note_comment']."</p>";
//					}
					?>
					
				</div>
				<div class='col-sm-12 <?=$note_align?>' <?=$noteFont?>><b><?=$noteby?></b><hr></div>
				
			</div>	
		<?php }
		}
		?>
		</div>
	</div>
	<?php 
        }
        if(!empty($filedocs)){ ?>
        <div class='<?=$docClass?>' <?=$showdoc?>>
	
	<h5 class='text-center'><b>Document</b></h5>
            <div class="ifrmeborder">
		<?php 
		$filedocspath = substr($filedocspath,1);
		?>
                <object id='pdf_object'  data="<?=Yii::$app->homeUrl.$filedocspath?>#toolbar=0" type="application/pdf" style="width:100%; height:600px;" toolbar=0></object> 
        </div>
		
	</div>
        
	<?php
        } ?>
		<div class="col-sm-5"></div>
    <div class="col-sm-4">
        <?php 
        $alltags = Yii::$app->fts_utility->efile_get_dak_tags($fileinfo['file_id'], "Active", NULL);
        if(!empty($alltags)){
            echo "<br><button type='button' class='btn btn-danger btn-xs' data-toggle='modal' data-target='#viewalltags'>Click to Tag on File</button>";
        }
        ?>
    </div>
    
        <?php 
        if(!empty($remarks)){
            echo "<div class='col-sm-3 text-right'><br><button type='button' class='btn btn-success btn-xs' data-toggle='modal' data-target='#viewallremarks'>Click to view Remarks on File</button></div>  <div class='col-sm-12'><hr class='hrline' /></div>";
        } 
        ?>
    
</div>
<?php } //role check ?>
    
<?=Yii::$app->view->renderFile(Yii::getAlias('@app') . '/modules/efile/history_view.php', ['fileinfo'=>$fileinfo]);?>
<?php if(Yii::$app->user->identity->role != '20'){
echo Yii::$app->view->renderFile(Yii::getAlias('@app') . '/modules/efile/group_information.php', ['fileinfo'=>$fileinfo, 'movement'=>""]);

//$EfileDakGroups = EfileDakGroups::find()->where(['file_id'=>$fileinfo['file_id'], 'is_active'=>'Y'])->asArray()->all();
//$grpHTML="";
//if(!empty($EfileDakGroups))
//{
//    foreach($EfileDakGroups as $key => $gvalues)
//    {
//        $allremartks = EfileDakGroupMembersRemarks::find()->where(['dak_group_id' =>$gvalues['dak_group_id'], 'file_id'=>$fileinfo['file_id'], 'status'=>'S', 'is_active'=>'Y'])->asArray()->all();
//	if(!empty($allremartks))
//        {
//		$remarksHTML = "<br><h6 class='text-left'><b style='color:red'>Previous Remarks of Group / Committee Members </b></h6><hr class='hrline'>
//		<table class='table table-bordered remarktable'><thead class='thead-dark'><tr><th>Sr. No.</th><th>Member Name</th></tr></thead>";
//		$i=1;
//		foreach($allremartks as $m)
//                {
//                    $memberInfo = Yii::$app->utility->get_employees($m['employee_code']);
//                    $role = "Member";
//                    if($m['group_role'] == 'CH')
//                    {
//                        $role = "Chairman";
//                    }
//                    elseif($m['group_role'] == 'C')
//                    {
//                        $role = "Convenor";
//                    }
//                    $Group_Role = "";
//                    $memberInfo = $memberInfo['fullname'].", ".$memberInfo['desg_name'];
//                    $date = date('d-m-Y H:i:s', strtotime($m['created_date']));
//                    $remarksHTML .= "<tr><td>$i</td>
//                        <td><u><b>$memberInfo comment on $date</b></u><br>$m[remarks]</td></tr>";
//			$i++;
//		}
//		$remarksHTML .= "</table>";
//		echo $remarksHTML;
//	}    
//        
//    }
//}

?>

<div class="modal fade" id="viewallremarks" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">All Remarks on File</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class='row'>
                    <?php 
                    if(!empty($remarks))
                    {
                        foreach($remarks as $r)
                        {
                            if(!empty($r['note_comment']))
                            {
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
                <div id="show_full_note" class="text-justify"></div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="viewalltags" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">All Tags on File</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
          <div class="modal-body">
                <div class='row'>
                    <div class='col-sm-12'>
                        <table class="table table-bordered">
                    <?php 
                    $alltags = Yii::$app->fts_utility->efile_get_dak_tags($fileinfo['file_id'], "Active", NULL);
                    if(!empty($alltags)){
                        foreach($alltags as $r){
                            if(!empty($r['tag_content'])){
                            $m = Yii::$app->utility->get_employees($r['added_by']);
                            $m = $m['fullname'].",<br>".$m['desg_name'];
                            $fID = Yii::$app->utility->encryptString($fileinfo['file_id']);
                            $tag_id = Yii::$app->utility->encryptString($r['tag_id']);
                    ?>
                    
                            <tr>
                                <td style="width: 30%;"><?=$m?></td>
                                <td style="width: 70%;"><span style="color:red;font-weight: bold;">Page No. <?=$r['page_number']?> : </span><?=$r['tag_content']?></td>
                               
                            </tr>
                        
                    </div>
                    <?php 
                        }
                    }
                    }
                    ?>
                    </table>
                </div>
          </div>
        </div>
        </div>
    </div>
</div>
<?php }?>