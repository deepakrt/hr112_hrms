<?php
use app\models\EfileDakMovement;
use app\models\EfileDakGroupMembers;

$this->title = "Outbox";
$daks = Yii::$app->fts_utility->efile_get_outbox_daks(Yii::$app->user->identity->e_id);
?>

<table id="outbox" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Sr. No. </th>
            <th>Fwd To</th>
            <th style='width:20%;'>Emp Name</th>
            <th>Subject</th>
            <!--<th>Priority</th>-->
            <th>Is Time Bound</th>
            <th>Sent Date</th>
            <th>Available With</th>
            <th>View</th>
        </tr>
    </thead>
	<tbody>
	<?php 
//	 echo "<pre>";print_r($daks);die;
	if(!empty($daks)){
		$i =1;
		foreach($daks as $l){
//                    $l = EfileDakMovement::find()->where(['file_id'=>$l['file_id'], 'fwd_by'=>Yii::$app->user->identity->e_id])->asArray()->orderBy(['id' => SORT_DESC])->one();
                    $l = EfileDakMovement::find()->where("file_id = $l[file_id] AND fwd_by ='".Yii::$app->user->identity->e_id."' AND fwd_to != 'CC'")->asArray()->orderBy(['id' => SORT_DESC])->one();
//                    if($l['fwd_to'] == 'CC'){}else{
//                    $available = EfileDakMovement::find()->where(['file_id'=>$l['file_id']])->asArray()->orderBy(['id' => SORT_DESC])->one();
                    $available = EfileDakMovement::find()->where("file_id = $l[file_id] AND fwd_to != 'CC'")->asArray()->orderBy(['id' => SORT_DESC])->one();
//                    echo "<pre>";print_r($l);die;
                    if(!empty($l)){
                        $availableWith = "";
                    if($l['is_initiate_file']=='N'){
			$file = "";
			$file = Yii::$app->fts_utility->efile_get_dak($l['file_id'], NULL, NULL, NULL);
                        if($file['status'] == 'Open' OR $file['status'] == 'Scan' OR $file['status'] == 'Scanned'){
//                        echo "<pre>";print_r($file['status']);die;
			$file_id = Yii::$app->utility->encryptString($l['file_id']);
			$movement_id = Yii::$app->utility->encryptString($l['id']);			
			$fwd_date = date('d-m-Y', strtotime($l['fwd_date']));
			$fwd_date .= "<br>".date('H:i:s', strtotime($l['fwd_date']));
			$fwdTo = "Individual";
                        $emp = Yii::$app->utility->get_employees($l['fwd_emp_code']);
			$fwd_name = $emp['fname']." ".$emp['lname']."<br>".$emp['desg_name'];
                        if($l['fwd_to'] == 'G'){
                            $fwdTo = "Group / Committee";
                            $group = EfileDakGroupMembers::find()->where(['dak_group_id'=>$l['dak_group_id'], 'group_role'=>'CH'])->one();
                            if(!empty($group)){
                                $emp = Yii::$app->utility->get_employees($group->employee_code);
                                $fwd_name = $emp['fname']." ".$emp['lname']."<br>".$emp['desg_name']." <b>(Chairman)</b>";
                            }
                        }elseif($l['fwd_to'] == 'A'){
                            $fwdTo = "All Employees";
                        }
                        
                        
                        $availableWith = Yii::$app->utility->get_employees($available['fwd_emp_code']);
			$availableWith = $availableWith['fname']." ".$availableWith['lname']."<br>".$availableWith['desg_name'];
			
			$is_time_bound = "No";
			if($l['is_time_bound'] == 'Y'){
				$response_date = date('d-m-Y', strtotime($l['response_date']));
				$is_time_bound = "<span style='color:red;font-weight:bold;'>Yes<br>(Till $response_date)</span>";
			}
			$returnFile = "";
			if($l['status'] == 'Return'){
				$returnFile = "<b>Return File</b>:- ";
			}
//			$view = Yii::$app->homeUrl."efile/outbox/viewoutboxdak?securekey=$menuid&key=$file_id&key2=$movement_id";
			$view = Yii::$app->homeUrl."efile/outbox/viewoutboxdak?securekey=$menuid&key=$file_id";
			if($file['status'] == 'Closed'){
                            $view = "<a href='$view' class='btn btn-danger btn-xs'>File Closed</a>";
                        }else{
                            $view = "<a href='$view' class='btn btn-success btn-xs'>View</a>";
                        }
//                        <td>$file[priority]</td>
			echo "<tr>
				<td class='text-center'>$i</td>
				<td>$fwdTo</td>
				<td>$fwd_name</td>
				<td>$file[subject]</td>
				
				<td>$is_time_bound</td>
				<td>$fwd_date</td>
				<td style='color:red;font-size:12px;'>$availableWith</td>
				<td>$view</td>
			</tr>";
			$i++;
                    }
                    }
                    }
//                    }
		}
	}
	?>
		
	</tbody>
</table>