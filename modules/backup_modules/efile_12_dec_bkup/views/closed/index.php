<?php
$this->title ="Closed Daks";
use app\models\EfileDak;
use app\models\EfileDakHistory;
$daks = EfileDak::find()->where(['status'=>'Closed', 'is_active'=>'Y'])->all();
?>
<table id="outbox" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Sr. No. </th>
            <th>File Type</th>
            <th>Subject</th>
            <th>Reference No. </th>
            <th>Closed Date</th>
            <th>View</th>
        </tr>
    </thead>
	<tbody>
	<?php 
	// echo "<pre>";print_r($lists);die;
	if(!empty($daks)){
            $i =1;
            foreach($daks as $l){
                $history = EfileDakHistory::find()->where(['file_id'=>$l['file_id'], 'fwd_emp_code'=>Yii::$app->user->identity->e_id, 'is_active'=>'Y'])->all();
                $show = "N";
                if($l['emp_code'] == Yii::$app->user->identity->e_id){
                    $show = "Y";
                }elseif(!empty($history)){
                    $show = "Y";
                }
                if($show == 'Y'){
                    $initiate_type = "File";
                    if($l['initiate_type'] == 'N'){
                        $initiate_type = "Note";
                    }elseif($l['initiate_type'] == 'P'){
                        $initiate_type = "Proposal";
                    }
                    $close_date = date('d-m-Y H:i:s', strtotime($l['last_updated']));
                    $file_id = Yii::$app->utility->encryptString($l['file_id']);
                    $view = Yii::$app->homeUrl."efile/closed/viewoutboxdak?securekey=$menuid&key=$file_id";
                    echo "<tr>
                            <td  class='text-center'>$i</td>
                            <td>$initiate_type</td>
                            <td>$l[subject]</td>
                            <td>$l[reference_num]</td>
                            <td>$close_date</td>
                            <td><a href='$view' class='btn btn-success btn-xs'>View</a></td>
                    </tr>";
                    $i++;
                }
            }
	}
	?>
		
	</tbody>
</table>
