<?php 
use app\models\EfileDakHistory;
use app\models\EfileDakGroups;
use app\models\EfileDakGroupMembers;

?>
<div class="text-right">
    <a class="btn btn-danger btn-sm" data-toggle="collapse" href="#filehistory" role="button" aria-expanded="false" aria-controls="filehistory">View File Movement History</a>
</div>
<div class='row'>
    <div class='col-sm-12'>
        <div class="collapse multi-collapse" id="filehistory" >
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
                $history = EfileDakHistory::find()->where(['file_id' => $fileinfo['file_id'], 'is_active'=>'Y'])->asArray()->all();
                $i=1;
                foreach($history as $h){
                        $fwd_by = Yii::$app->utility->get_employees($h['fwd_by']);
                        $fwd_by = $fwd_by['fullname'].", ".$fwd_by['desg_name'];
                        $fwd_for = "-";
                        if(!empty($h['action_id'])){
                            $fwd_for = Yii::$app->fts_utility->efile_get_actions($h['action_id'], NULL);
                            $fwd_for = $fwd_for['action_name'];
                        }

                        if($h['fwd_to'] == 'E'){
                            $fwd_type = "Individual";
                            $fwdto = Yii::$app->utility->get_employees($h['fwd_emp_code']);
                            $fwdto = $fwdto['fullname'].", ".$fwdto['desg_name'];
                        }elseif($h['fwd_to'] == 'CC'){
                            $fwd_type = "Copy To";
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
//                                <td>$fwd_for</td>
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

</div>