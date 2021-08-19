<?php
use app\models\EfileMasterCategory;
use app\models\ProjectList;
use app\models\EfileDakDocs;
use app\models\EfileDakMovement;
use app\models\EfileDakHistory;
use yii\widgets\ActiveForm;
use app\models\EfileDakGroups;
use app\models\EfileDakGroupMembers;
use app\models\EfileDakGroupMembersRemarks;
use app\models\EfileDakNotes;
$this->title = "View File Detail";
$filedocspath = Yii::$app->Dakutility->makefilefromdocs($fileinfo['file_id']);
$fileidfordnl=Yii::$app->utility->encryptString($fileinfo['file_id']);
$notes = EfileDakNotes::find()->where(['file_id' => $fileinfo['file_id'], 'status'=>'S', 'content_type'=>'N', 'is_active'=>'Y'])->asArray()->all();
$noteFont =  $showNotes = "style=''";
$showdoc = "style=''";
$docClass = "col-sm-6 paddingzero";
$noteClass = "col-sm-6 paddingzero";
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
$remarks = EfileDakNotes::find()->where(['file_id' => $fileinfo['file_id'], 'status'=>'S', 'content_type'=>'R', 'is_active'=>'Y'])->asArray()->all();
$fid = Yii::$app->utility->encryptString($fileinfo['file_id']);
$map_id = Yii::$app->utility->encryptString(Yii::$app->user->identity->map_id);
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
			<li><b><span class="hindishow12">किस लिए अग्रेषित</span> / Forward For</b><br> <?=$action_type?></li>
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
    <div class='row'>
        <?php if(!empty($notes)){ ?>
	<div class='<?=$noteClass?>' <?=$showNotes?>>
		<div class='text-left'>
			<a href='<?=Yii::$app->homeUrl?>efile/inbox/downloadgreensheet?securekey=<?=$menuid?>&fileid=<?=$fileidfordnl?>' target='_blank' class='btn btn-success btn-xs'>Download Note</a>
		</div>
	
		<div class='greensheet'>
		<?php 
		
		if(!empty($notes)){
			$deplLogo=Yii::$app->homeUrl."images/cdac.jpeg";
			$swachhbharatabhiyan=Yii::$app->homeUrl."images/swacchbharatlogo.jpeg";
			echo '<div class="row">
				<div class="col-sm-2" style="text-align:left"><img src='."$deplLogo".'  style="'.$leftlogoCSS.'"/></div>
				<div class="col-sm-10"><p   style="text-align:center;'.$noteTitle_1.' ">प्रगत संगणन विकास केंद्र,मोहाली</p>
				<p  style="text-align:center;'.$noteTitle_2.'">CENTRE FOR DEVELOPMENT OF ADVANCED COMPUTING,'.ORGANAZATION_CENTRE.'</p></div>
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
			$noteby = $noteby['name_hindi']." / ".$noteby['fullname'].",<br>".$noteby['desg_name_hindi']." / ".$noteby['desg_name'];
				
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
				
				<div class='col-sm-12 text-justify note_html' <?=$noteFont?>>
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
	<?php 
        }
        if(!empty($filedocs)){ ?>
        <div class='<?=$docClass?>' <?=$showdoc?>>
	<div class='text-right'>
            <button type="button" class="btn btn-danger btn-xs" onclick="download_file('<?=$fid?>', '<?=$map_id?>')">
            Download Document</button>
        </div>
	<h5 class='text-center'><b>Document</b></h5>
            <div class="ifrmeborder">
		<?php 
		$filedocspath = substr($filedocspath,1);
		?>
           <object id='pdf_object'  data="<?=Yii::$app->homeUrl.$filedocspath?>#toolbar=0" type="application/pdf" style="width:100%; height:600px;" toolbar=0></object> 
        </div>
		
	</div>
        
	<?php
        }?>
        
        <div class="col-sm-5"></div>
        <div class="col-sm-4">
            <?php 
            $alltags = Yii::$app->fts_utility->efile_get_dak_tags($fileinfo['file_id'], "Active", NULL);
            if(!empty($alltags)){
                echo "<br><button type='button' class='btn btn-danger btn-xs' data-toggle='modal' data-target='#viewalltags'>Click to Tag on File</button>";
            }
            ?>
        </div>
        <div class="col-sm-3 text-right">
            <?php 
            if(!empty($remarks)){
                echo "<br><button type='button' class='btn btn-success btn-xs' data-toggle='modal' data-target='#viewallremarks'>Click to view Remarks on File</button>";
            } 
            ?>
        </div>
</div>
<?php
$histHTML="";
//$EfileDakHistory = EfileDakHistory::find()->where(['file_id'=>$fileinfo['file_id'], 'is_active'=>'Y'])->asArray()->orderBy(['file_history_id' => SORT_DESC])->all();
//echo "<pre>";print_R($EfileDakHistory); die;
//if(!empty($EfileDakHistory))
//{
//    $histHTML.= "<br><h6 class='text-left'><b style='color:red'>File Movement History</b></h6><hr class='hrline'>
//		<table class='table table-bordered'><thead class='thead-dark'><tr><th>Sr. No.</th><th>Forward by</th><th>Forward To</th><th>Forward Date</th></tr></thead>";
//    $i=1;
//    foreach($EfileDakHistory as $key=> $mh)
//    {
//        $fwd_emp_code= Yii::$app->utility->get_employees($mh['fwd_emp_code']);
//        $fwd_by= Yii::$app->utility->get_employees($mh['fwd_by']);
//        $fwdate= date("d-M-Y H:i:s", strtotime($mh['created_date']));
//        $frwto = $fwd_emp_code['fullname'].", ".$fwd_emp_code['desg_name'];
//        $frwby = $fwd_by['fullname'].", ".$fwd_by['desg_name'];
//        $histHTML .= "<tr><td>$i</td>
//            <td>$frwby</td><td>$frwto</td><td>$fwdate</td></tr>";
//            $i++;
//    }
//    $histHTML .= "</table>";
//    echo $histHTML;
//    
//}
?>
<div class='col-sm-12'>
    <h6><b><u>File Movement Record:-</u></b></h6>
    <table class='table table-bordered'>
            <tr>
                    <th>Sr. No.</th>
                    <th>Forward Type</th>
                    <th>Forward For</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Forward Date</th>
            </tr>
            <?php 
            $history = EfileDakHistory::find()->where(['file_id' => $fileinfo['file_id']])->asArray()->all();
            $i=1;
            foreach($history as $h){
                    $fwd_for = "-";
                    if(!empty($h['action_id'])){
                        $fwd_for = Yii::$app->fts_utility->efile_get_actions($h['action_id'], NULL);
                        $fwd_for = $fwd_for['action_name'];
                    }
                    $fwd_by = Yii::$app->utility->get_employees($h['fwd_by']);
                    $fwd_by = $fwd_by['fullname'].", ".$fwd_by['desg_name'];
                    if($h['fwd_to'] == 'E'){
                            $fwd_type = "Individual";
                            $fwdto = Yii::$app->utility->get_employees($h['fwd_emp_code']);
                            $fwdto = $fwdto['fullname'].", ".$fwdto['desg_name'];
                    }elseif($h['fwd_to'] == 'A'){
                            $fwd_type = "All";
                            $fwdto = "Forward to all employees.";
                    }elseif($h['fwd_to'] == 'G'){
                            $fwd_type = "Group";
                            $GrpInfo = EfileDakGroups::find()->where(['file_id' => $h['file_id'], 'dak_group_id'=>$h['dak_group_id']])->asArray()->one();
                            $grpHtml ="-";
                            if(!empty($GrpInfo)){
                                    $grpCrt = Yii::$app->utility->get_employees($GrpInfo['created_by']);
                                    $grpCrt = $grpCrt['fullname'].", ".$grpCrt['desg_name']." ($grpCrt[dept_name])";
                                    $grpdt = date('d-m-Y H:i:s', strtotime($GrpInfo['created_date']));
                                    $grpHtml ="Group Name: $GrpInfo[group_name], Created By: $grpCrt on $grpdt . <br>";
                                    $grpMem = EfileDakGroupMembers::find()->where(['dak_group_id'=>$h['dak_group_id']])->asArray()->all();
                                    $memHtml = "";
                                    if(!empty($grpMem)){
                                            foreach($grpMem as $m){
                                                    if($m['group_role'] == 'CH'){
                                                            $mem = Yii::$app->utility->get_employees($m['employee_code']);
                                                            $mem = $mem['fullname'].", ".$mem['desg_name'];
                                                            $memHtml .= "$mem (Chairman)<br>";
                                                    }
                                            }
                                            foreach($grpMem as $m){
                                                    if($m['group_role'] == 'M'){
                                                            $mem = Yii::$app->utility->get_employees($m['employee_code']);
                                                            $mem = $mem['fullname'].", ".$mem['desg_name'];
                                                            $memHtml .= "$mem (Member)<br>";
                                                    }
                                            }
                                            foreach($grpMem as $m){
                                                    if($m['group_role'] == 'C'){
                                                            $mem = Yii::$app->utility->get_employees($m['employee_code']);
                                                            $mem = $mem['fullname'].", ".$mem['desg_name'];
                                                            $memHtml .= "$mem (Convenor)<br>";
                                                    }
                                            }
                                    }
                                    $fwdto = $memHtml;
                            }
                    }
                    $fwdDate = date('d-m-Y', strtotime($h['created_date']));
                    $fwdDate .= "<br>".date('H:i:s', strtotime($h['created_date']));
                    echo "<tr>
                            <td>$i</td>
                            <td>$fwd_type</td>
                            <td>$fwd_for</td>
                            <td>$fwd_by</td>
                            <td>$fwdto</td>
                            <td>$fwdDate</td>
                    </tr>";
                    $i++;
            }
            // echo "<pre>";print_r($history);
            ?>
    </table>

</div>
<?php
echo Yii::$app->view->renderFile(Yii::getAlias('@app') . '/modules/efile/group_information.php', ['fileinfo'=>$fileinfo, 'movement'=>""]);
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
<input type="hidden" id='checkfile' />

<script>
    
    $(document).ready(function(){
        document.onkeydown = function(e) {
            if (e.ctrlKey && 
                (e.keyCode === 67 || 
                 e.keyCode === 86 || 
                 e.keyCode === 85 || 
                 e.keyCode === 117)) {
                return false;
            } else {
                return true;
            }
        };
        $(document).keypress("u",function(e) {
            if(e.ctrlKey){
                return false;
            }else{
                return true;
            }
        }); 
        
        setInterval(function(){
            var checkfile = $("#checkfile").val();
            if(!checkfile){
                var path = "<?=$filedocspath?>";
                unlinkfile(path);
            }
        }, 3000);
    });
</script>