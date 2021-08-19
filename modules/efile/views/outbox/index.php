<?php
$this->title = "Outbox";
$daks = Yii::$app->fts_utility->efile_get_outbox_daks(Yii::$app->user->identity->e_id);
?>

<table id="outbox" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Sr. No. </th>
            <th>Fwd To</th>
            <th>Emp Name</th>
            <th>Subject</th>
            <th>Priority</th>
            <th>Is Time Bound</th>
            <th>Sent Date</th>
            <th>View</th>
        </tr>
    </thead>
	<tbody>
	<?php 
	// echo "<pre>";print_r($lists);die;
	if(!empty($daks)){
		$i =1;
		foreach($daks as $l){
                    if($l['is_initiate_file']=='N'){
			$file = "";
			$file = Yii::$app->fts_utility->efile_get_dak($l['file_id'], NULL, NULL, NULL);
			$file_id = Yii::$app->utility->encryptString($l['file_id']);
			$movement_id = Yii::$app->utility->encryptString($l['id']);			
			$fwd_date = date('d-m-Y', strtotime($l['fwd_date']));
			$fwd_date .= "<br>".date('H:i:s', strtotime($l['fwd_date']));
			$fwdTo = "Individual";
                        if($l['fwd_to'] == 'G'){
                            $fwdTo = "Group / Committee";
                        }elseif($l['fwd_to'] == 'A'){
                            $fwdTo = "All Employees";
                        }
                        $emp = Yii::$app->utility->get_employees($l['fwd_emp_code']);
			$fwd_name = $emp['fname']." ".$emp['lname'].",<br>".$emp['desg_name'];
			
			$is_time_bound = "No";
			if($l['is_time_bound'] == 'Y'){
				$response_date = date('d-m-Y', strtotime($l['response_date']));
				$is_time_bound = "<span style='color:red;font-weight:bold;'>Yes<br>(Till $response_date)</span>";
			}
			$returnFile = "";
			if($l['status'] == 'Return'){
				$returnFile = "<b>Return File</b>:- ";
			}
			$view = Yii::$app->homeUrl."efile/outbox/viewoutboxdak?securekey=$menuid&key=$file_id&key2=$movement_id";
			echo "<tr>
				<td>$i</td>
				<td>$fwdTo</td>
				<td>$fwd_name</td>
				<td>$file[subject]</td>
				<td>$file[priority]</td>
				<td>$is_time_bound</td>
				<td>$fwd_date</td>
				<td><a href='$view' class='btn btn-success btn-xs'>View</a></td>
			</tr>";
			$i++;
                    }
		}
	}
	?>
		
	</tbody>
</table>