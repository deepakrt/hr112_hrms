<?php
$this->title = "Inbox";
use app\models\EfileDakReceived;
?>

<table id="outbox" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Sr. No.</th>
            <th>Fwd Type</th>
            <th style='width:20%;'>From</th>
            <th>Subject</th>
            <th>Priority</th>
            <th>Is Time Bound</th>
            <th>Dated</th>
            <th></th>
        </tr>
    </thead>
	<tbody>
	<?php 
	
//	  echo "<pre>";print_r($lists);die;
	if(!empty($lists)){
            $i =1;
            foreach($lists as $l){
                $file = "";
                $file = Yii::$app->fts_utility->efile_get_dak($l['file_id'], NULL, NULL, NULL);
                 // echo "<pre>";print_r($file);die;
                if($file['status'] == 'Open' OR $file['status'] == 'Scan'){

                    $fwdTo = "Individual";
                    if($l['fwd_to'] == 'G'){
                        $fwdTo = "Group";
                    }elseif($l['fwd_to'] == 'CC'){
                        $fwdTo = "Copy To";
                    }
                    $reference_num = $file['reference_num']."<br>Dated ".date('d-m-Y', strtotime($file['reference_date']));
                    $file_id = Yii::$app->utility->encryptString($l['file_id']);
                    $movement_id = Yii::$app->utility->encryptString($l['id']);
                    $view = "-";
                    $fwd_date = date('d-m-Y', strtotime($l['fwd_date']));
                    $fwd_date .= "<br>".date('H:i:s', strtotime($l['fwd_date']));

                    if($l['fwd_by'] != Yii::$app->user->identity->e_id){
                        $emp = Yii::$app->utility->get_employees($l['fwd_by']);
                        $fwd_name = $emp['fname']." ".$emp['lname']."<br>".$emp['desg_name'];
                    }else{
                        $fwd_name = "<b>New File Initiated</b>";
                    }
                        // echo "$reference_num<pre>";print_r($emp); die;
                    $is_time_bound = "No";
                    if($l['is_time_bound'] == 'Y'){
                            $response_date = date('d-m-Y', strtotime($l['response_date']));
                            $is_time_bound = "<span style='color:red;font-weight:bold;'>Yes<br>(Till $response_date)</span>";
                    }
                    $returnFile = "";
                    if($l['status'] == 'Return'){
                            $returnFile = "<b>Return File</b>:- ";
                    }
                    if(Yii::$app->user->identity->role == '20'){
                        if($file['status'] == 'Scan' AND $file['sent_for_scan'] == 'Y'){
                            $view = Yii::$app->homeUrl."efile/inbox/scandocupload?securekey=$menuid&key=$file_id&key2=$movement_id";
                            $view = "<a href='$view' class='btn btn-danger btn-xs'>View File For Scan</a>";
    //                            <td>$reference_num</td>
                            echo "<tr>
                                    <td>$i</td>
                                    <td>$fwdTo</td>
                                    <td>$fwd_name</td>
                                    <td>$returnFile $file[subject]</td>
                                    <td>$file[priority]</td>
                                    <td>$is_time_bound</td>
                                    <td>$fwd_date</td>
                                    <td>$view</td>
                            </tr>";
                            $i++;
                        }
                    }else{
                    if($file['status'] == 'Open'){
                        $view = Yii::$app->homeUrl."efile/inbox/viewdakwithnoting?securekey=$menuid&key=$file_id&key2=$movement_id";
                        $view = "<a href='$view' class='btn btn-success btn-xs'>View</a>";
//                            <td>$reference_num</td>
                        echo "<tr>
                                <td  class='text-center'>$i</td>
                                    <td>$fwdTo</td>
                                <td>$fwd_name</td>
                                <td>$returnFile $file[subject]</td>
                                <td>$file[priority]</td>
                                <td>$is_time_bound</td>
                                <td>$fwd_date</td>
                                <td>$view</td>
                        </tr>";
                        $i++;
                    }
                    }
                }
            }
    }
    ?>

    </tbody>
</table>