<?php
use app\models\EfileMasterCategory;
use app\models\EfileMasterProject;
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
$remarks = EfileDakNotes::find()->where(['file_id' => $fileinfo['file_id'], 'status'=>'S', 'content_type'=>'R', 'is_active'=>'Y'])->asArray()->all();

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
            <?php if(!empty($receiptInfo))
            { 
            $recNo = $receiptInfo['dak_number'];//."<br> Dated ".date('d-m-Y H:is', strtotime($receiptInfo['forwarded_date']));
            $dist = Yii::$app->fts_utility->get_master_districts($receiptInfo['org_district'], NULL);
            $address = $receiptInfo['org_address'];
            if(!empty($dist))
            {
                $address .= " Distt. $dist[district_name], $dist[state_name]";
            }
            $empinfo = Yii::$app->utility->get_employees($receiptInfo['dak_fwd_to']);
            $fwdto = $empinfo['fullname'].", ".$empinfo['desg_name'];
            ?>
            <h5 class='text-left'><b style="color:red">Receipt Detail</b></h5>
            <hr class='hrline'>
            <ul class='fileinfo'>
                <li><b>Receipt No.</b><br><?=$recNo?></li>
                <li><b>Received From </b><br> <?=$receiptInfo['rec_from']?></li>
                <li><b>Address </b><br> <?=$address?></li>
                <li><b>Received On</b><br> <?=date('d-M-Y', strtotime($receiptInfo['rec_date']))?></li>
                <li><b>Received Mode</b><br> <?=$receiptInfo['mode_of_rec']?></li>
                <li><b>Summary</b><br> <?=$receiptInfo['dak_summary']?></li>
                <li><b>Remarks</b><br> <?=$receiptInfo['dak_remarks']?></li>
                <li><b>Forwarded To</b><br> <?=$fwdto?></li>
            </ul>
            <?php	}?>
            <h5 class='text-left'><b style="color:red">File Detail</b></h5>
            <hr class='hrline'>
            <?php 
            $refNo = $fileinfo['reference_num']."<br>Dated ".date('d-M-Y', strtotime($fileinfo['reference_date']));
            $cat = EfileMasterCategory::find()->where(['file_category_id' => $fileinfo['file_category_id']])->asArray()->one();
            $project = EfileMasterProject::find()->where(['file_project_id' => $fileinfo['file_project_id']])->asArray()->one();		
            ?>
            <ul class='fileinfo'>
                <li><b>Status</b><br> <?=$fileinfo['status']?></li>
                <li><b>Ref. No. & Dated</b><br><?=$refNo?></li>
                <li><b>Category</b><br> <?=$cat['name']?></li>
                <?php if(!empty($project)){ ?>
                <li><b>Project Name</b><br> <?=$project['project_name']?></li>
                <?php } ?>
                <li><b>Is confidential?</b><br> <?=Yii::$app->fts_utility->showYesNo($fileinfo['is_confidential'])?></li>
                <li><b>Priority</b><br> <?=$fileinfo['priority']?></li>
                <li><b>Action Type</b><br> <?=$fileinfo['action_type']?></li>
                <li><b>Access Level</b><br> <?=Yii::$app->fts_utility->get_efile_access_level("G", $fileinfo['access_level'])?></li>
            </ul>
            <p class='text-justify'><b>Summary:- </b><?=$fileinfo['summary']?></p>
            <hr>
            <p class='text-justify'><b>Remarks:- </b><?=$fileinfo['remarks']?></p>
            <hr class='hrline'>
	</div>
    </div>
    <div class='row'>
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
                    <th>From</th>
                    <th>To</th>
                    <th>Forward Date</th>
            </tr>
            <?php 
            $history = EfileDakHistory::find()->where(['file_id' => $fileinfo['file_id']])->asArray()->all();
            $i=1;
            foreach($history as $h){
                    $fwd_by = Yii::$app->utility->get_employees($h['fwd_by']);
                    $fwd_by = $fwd_by['fullname'].", ".$fwd_by['desg_name'];
                    if($h['fwd_to'] == 'E'){
                            $fwd_type = "Individual";
                            $fwdto = Yii::$app->utility->get_employees($h['fwd_emp_code']);
                            $fwdto = $fwdto['fullname'].", ".$fwdto['desg_name']." ($fwdto[dept_name])";
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
$EfileDakGroups = EfileDakGroups::find()->where(['file_id'=>$fileinfo['file_id'], 'is_active'=>'Y'])->asArray()->all();
//echo "<pre>";print_R($EfileDakGroups); die;
$grpHTML="";
if(!empty($EfileDakGroups))
{
    foreach($EfileDakGroups as $key => $gvalues)
    {
        $allremartks = EfileDakGroupMembersRemarks::find()->where(['dak_group_id' =>$gvalues['dak_group_id'], 'file_id'=>$fileinfo['file_id'], 'status'=>'S', 'is_active'=>'Y'])->asArray()->all();
//	 echo "<pre>";print_r($allremartks); die;
	if(!empty($allremartks))
        {
		$remarksHTML = "<br><h6 class='text-left'><b style='color:red'>Previous Remarks of Group / Committee Members </b></h6><hr class='hrline'>
		<table class='table table-bordered remarktable'><thead class='thead-dark'><tr><th>Sr. No.</th><th>Member Name</th></tr></thead>";
		$i=1;
		foreach($allremartks as $m)
                {
                    $memberInfo = Yii::$app->utility->get_employees($m['employee_code']);
                    $role = "Member";
                    if($m['group_role'] == 'CH')
                    {
                        $role = "Chairman";
                    }
                    elseif($m['group_role'] == 'C')
                    {
                        $role = "Convenor";
                    }
                    $Group_Role = "";
                    $memberInfo = $memberInfo['fullname'].", ".$memberInfo['desg_name']." ($role)";
                    $date = date('d-m-Y H:i:s', strtotime($m['created_date']));
                    $remarksHTML .= "<tr><td>$i</td>
                        <td><u><b>$memberInfo comment on $date</b></u><br>$m[remarks]</td></tr>";
			$i++;
		}
		$remarksHTML .= "</table>";
		echo $remarksHTML;
	}    
        
    }
}

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
          <div class="modal-body"></div>
        </div>
    </div>
</div>
