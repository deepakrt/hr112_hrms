<?php
use yii\widgets\ActiveForm;
$this->title= 'View Approved / Rejected :  Entry / OutDoor Slip';
?>
<br>
<table id="dataTableShow" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Sr.</th>
            <th>Name</th>
            <?php if(Yii::$app->user->identity->role == '5'){ echo "<th>Department</th>";} ?>
            <th>Entry Type</th>
            <th>Entry Date <br> and Time</th>
            <th>Applied On</th>
            <th>Reason</th>
            <th>Status</th>             
            <th></th>            
        </tr>
    </thead>
    <tbody>
        <?php if(!empty($slips)){
            $i=1;
            $j=0;
            //echo "<pre>";print_r($apps);
            foreach($slips as $app){
               
               $fullname = $app['fullname'].", <br>".$app['desg_name'];
               $dept_name = $app['dept_name'];
               $type = $app['type'];
               $entry_date = date('d-M-Y', strtotime($app['entry_date']));
               $entry_time = $app['entry_time'];
               $exit_time = $app['exit_time'];
               $status = $app['status'];
               $entry_date = "$entry_date <br> Entry Time : $entry_time <br> Exit Time: $exit_time";
               $applied_date = date('d-M-Y', strtotime($app['submitted_on']));
               $reason = $app['reason'];
               if($app['reason'] == 'Other'){
                   $reason = $app['other_reason'];
               }
               $e_id = Yii::$app->utility->encryptString($app['employee_code']);
               $leave_id = Yii::$app->utility->encryptString($app['id']);
        ?><tr>
            <td><?=$i?>
                <input type="hidden" value="<?=$e_id?>" name="EntrySlip[<?=$j?>][e_id]" readonly=""/>
                <input type="hidden" id="leave_id_<?=$i?>" name="EntrySlip[<?=$j?>][leave_id]" readonly=""/>
            </td>
            <td><?=$fullname?></td>
            <?php if(Yii::$app->user->identity->role == '5'){ echo "<td>$dept_name</tb>";} ?>
            <td><?=$type?></td>
            <td><?=$entry_date?></td>
            <td><?=$applied_date?></td>
            <td><?=$reason?></td>
            <td><?=$status?></td>
            <td><a href="" class="btn btn btn-light btn-xs">View</a></td>
        </tr>
        <?php
               $i++;
               $j++;
           } 
        }?>
    </tbody>
    <tfoot>
        <tr>
            <th>Sr.</th>
            <th>Name</th>
            <?php if(Yii::$app->user->identity->role == '5'){ echo "<th>Department</th>";} ?>
            <th>Entry Type</th>
            <th>Entry Date <br> and Time</th>
            <th>Applied On</th>
            <th>Reason</th>
            <th>Status</th>            
            <th></th>            
        </tr>
    </tfoot>
</table>