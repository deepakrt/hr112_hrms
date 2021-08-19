<?php 
$this->title = "View Outbox File";
use app\models\EfileMasterCategory;
use app\models\EfileMasterProject;
use app\models\EfileDakHistory;
use app\models\EfileDakGroups;
use app\models\EfileDakGroupMembers;
?>
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
			<li><b>Receipt No. & Date</b><br><?=$recNo?></li>
			<li><b>Received From </b><br> <?=$receiptInfo['rec_from']?></li>
			<li><b>Address </b><br> <?=$address?></li>
			<li><b>Received Mode</b><br> <?=$receiptInfo['mode_of_rec']?></li>
			<li><b>Summary</b><br> <?=$receiptInfo['dak_summary']?></li>
			<li><b>Remarks</b><br> <?=$receiptInfo['dak_remarks']?></li>
			<li><b>Forward On</b><br> <?=date('d-m-Y', strtotime($receiptInfo['forwarded_date']))?></li>
			<li><b>Forwarded To</b><br> <?=$fwdto?></li>
		</ul>
		<hr class='hrline'>
		<?php	}
		?>
		<h5 class='text-center'><b>File Details:-</b></h5>
		<?php 
		$refNo = $fileinfo['reference_num']."<br>Date ".date('d-m-Y', strtotime($fileinfo['reference_date']));
		$cat = EfileMasterCategory::find()->where(['file_category_id' => $fileinfo['file_category_id']])->asArray()->one();
		
		$project = EfileMasterProject::find()->where(['file_project_id' => $fileinfo['file_project_id']])->asArray()->one();
		
		?>
		<ul class='fileinfo'>
			<li><b>Status</b><br> <?=$fileinfo['status']?></li>
			<li><b>Ref. No. & Date</b><br><?=$refNo?></li>
			<li><b>Category</b><br> <?=$cat['name']?></li>
			<?php if(!empty($project)){ ?>
			<li><b>Project Name</b><br> <?=$project['project_name']?></li>
			<?php } ?>
			<li><b>Is confidential?</b><br> <?=Yii::$app->fts_utility->showYesNo($fileinfo['is_confidential'])?></li>
			<li><b>Priority</b><br> <?=$fileinfo['priority']?></li>
			<li><b>Action Type</b><br> <?=$fileinfo['action_type']?></li>
			<li><b>Access Level</b><br> <?=Yii::$app->fts_utility->get_efile_access_level("G", $fileinfo['access_level'])?></li>
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
	
</div>