<?php
$dash = Yii::$app->fts_utility->showefiledashboard();
$title = $dash['display_type'];
//echo "<pre>"; print_r($title);
$this->title = "$title";
use app\models\EfileDakMovement;
?>
<style>
    .respantit {
	background-color: #893523 ;
	color: #fff;
	font-size: 17px;
	text-align: center;
}
</style>
<table id="viewdadhboard" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Sr. No.</th>
            <th>Ref. No. & Date</th>
            <th>From Emp</th>
            <th>Subject</th>
            <th>Priority</th>
            <th>Is Time Bound</th>
            <th>Forward Date</th>
            <th></th>
        </tr>
    </thead>
	<tbody>
	<?php 
//	 echo "<pre>";print_r($lists);die;
	if(!empty($lists))
        {
            $i =1;
            foreach($lists as $key=>$l)
            {
                $reference_num = $l['reference_num']."<br>Dated ".date('d-M-Y', strtotime($l['reference_date']));
                $file_id = Yii::$app->utility->encryptString($l['file_id']);
                $EfileDakMovement= EfileDakMovement::find()->where(['file_id' => $l['file_id'],'is_active'=>'Y'])->asArray()->all();              
                $response_date="";
                $is_time_bound = "No";
                if(!empty($EfileDakMovement))
                {
                    foreach($EfileDakMovement as $k=>$value)
                    {
                        if($value['is_time_bound'] == 'Y')
                        {
                            $response_date = date('d-m-Y', strtotime($value['response_date']));
                            $is_time_bound = "<span style='color:red;font-weight:bold;'>Yes<br>(Till $response_date)</span>";
                        }
                    }
                }
                $EfileDakMovementLatest= EfileDakMovement::find()->where(['file_id' => $l['file_id'],'is_active'=>'Y'])->orderBy(['id' => SORT_DESC
])->asArray()->one();
//                echo "<pre>";print_r($EfileDakMovementLatest);die;
                
                
                $fwd_date=$fwd_name=$status="";
                if(!empty($EfileDakMovementLatest))
                {
                    $fwd_date = date('d-m-Y', strtotime($EfileDakMovementLatest['fwd_date']));
                    if($EfileDakMovementLatest['fwd_by'] != Yii::$app->user->identity->e_id)
                    {
                        $emp = Yii::$app->utility->get_employees($EfileDakMovementLatest['fwd_by']);
                        $fwd_name = $emp['fname']." ".$emp['lname'].",<br>".$emp['desg_name'];
                    }
                    else
                    {
                        $fwd_name = "<b>New File Initiated By User</b>";
                    }
                    $movement_id = Yii::$app->utility->encryptString($EfileDakMovementLatest['id']);
                    
                }
                $subject=$l['subject'];
                $aa=$key+1;
                $view = Yii::$app->homeUrl."efile/efiledashboard/viewfiledetail?securekey=$menuid&key=$file_id&key2=$movement_id";
                echo "<tr>
                        <td>$aa</td>
                        <td>$reference_num</td>
                        <td>$fwd_name</td>
                        <td>$subject </td>
                        <td>$l[priority]</td>
                        <td>$is_time_bound</td>
                        <td>$fwd_date</td>
                        <td><a href='$view' class='btn btn-success btn-xs'>View</a></td>
                </tr>";
                $i++;
            }
            
	}
	?>
		
	</tbody>
	<tfoot>
            <tr>
            <th>Sr. No.</th>
            <th>Ref. No. & Date</th>
            <th>From</th>
            <th>Subject</th>
            <th>Priority</th>
            <th>Is Time Bound</th>
            <th>Forward Date</th>
            <th></th>
        </tr>
	</tfoot>
</table>