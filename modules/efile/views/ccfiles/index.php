<?php
$this->title ="CC Files";
use app\models\EfileCcDak;
use app\models\EfileDakMovement;

$daks = EfileCcDak::find()->where(['emp_code'=>Yii::$app->user->identity->e_id, 'is_active'=>'Y'])->asArray()->all();

?>
<table id="outbox" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Sr. No.</th>
            <th style='width:20%;'>From</th>
            <th>Subject</th>
            <th>Priority</th>
            <th>Is Time Bound</th>
            <th>Moved On</th>
            <th></th>
        </tr>
    </thead>
	<tbody>
	<?php 
	
//	  echo "<pre>";print_r($lists);die;
	if(!empty($daks)){
            $i =1;
            foreach($daks as $d){
                $file = "";
                $file = Yii::$app->fts_utility->efile_get_dak($d['file_id'], NULL, NULL, NULL);
                $l = "";
                $l = EfileDakMovement::find()->where(['id'=>$d['movement_id']])->asArray()->one();
//                  echo "<pre>";print_r($file);die;
                if($file['status'] == 'Open'){
                    $reference_num = $file['reference_num']."<br>Dated ".date('d-m-Y', strtotime($file['reference_date']));
                    $file_id = Yii::$app->utility->encryptString($l['file_id']);
                    $cc_id = Yii::$app->utility->encryptString($d['cc_id']);
                    $view = "-";
                    $move_date = date('d-m-Y', strtotime($d['created_date']));
                    $move_date .= "<br>".date('H:i:s', strtotime($d['created_date']));

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
                    $view = Yii::$app->homeUrl."efile/ccfiles/viewccdak?securekey=$menuid&key=$file_id&key2=$cc_id";
                    $view = "<a href='$view' style='background-color:#3F9E89;' class='btn btn-success btn-xs'>View</a>";
//                            <td>$reference_num</td>
                    echo "<tr>
                            <td  class='text-center'>$i</td>
                            <td>$fwd_name</td>
                            <td>$file[subject]</td>
                            <td>$file[priority]</td>
                            <td>$is_time_bound</td>
                            <td>$move_date</td>
                            <td>$view</td>
                    </tr>";
                    $i++;
                }
            }
    }
    ?>

    </tbody>
</table>
