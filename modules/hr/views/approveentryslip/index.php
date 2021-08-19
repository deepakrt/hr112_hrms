<?php
use yii\widgets\ActiveForm;
$this->title= 'Approve / Reject :  Entry / OutDoor Slip';
$menuid = "";
if(isset($_GET['securekey']) AND !empty($_GET['securekey'])){
    $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
}
if(empty($menuid)){
    header('Location: '.Yii::$app->homeUrl); 
    exit;
}
$menuid = Yii::$app->utility->encryptString($menuid);
?>
<br>
<?php $form = ActiveForm::begin(['id'=>'entryslipform']); ?>
<input type="hidden" readonly="" name="menuid" value="<?=$menuid?>" />
<table id="dataTableShow" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Sr.</th>
            <th>Name</th>
            <?php if(Yii::$app->user->identity->role == '5'){ echo "<th>Department</th>";} ?>
            <th>Entry Type</th>
            <th>Entry Date & Time</th>
            <th>Applied On</th>
            <th>Reason</th>
            <th></th>             
            <!--<th></th>-->            
        </tr>
    </thead>
    <tbody>
        <?php if(!empty($slips)){
            $i=1;
            $j=0;
            //echo "<pre>";print_r($apps);
            foreach($slips as $app){
               
               $fullname = $app['fullname'].", ".$app['desg_name'];
               $dept_name = $app['dept_name'];
               $type = $app['type'];
               $entry_date = date('d-M-Y', strtotime($app['entry_date']));
               $entry_time = $app['entry_time'];
               $exit_time = $app['exit_time'];
               $entry_date = "$entry_date <br> Entry Time : $entry_time <br> Exit Time: $exit_time";
               $applied_date = date('d-M-Y', strtotime($app['submitted_on']));
               $reason = $app['reason'];
               if($app['reason'] == 'Other'){
                   $reason = "<b>Other :</b> ".$app['other_reason'];
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
            <td>
                <input type="checkbox" value="<?=$leave_id?>" id="approve_<?=$i?>" onclick="return entrySlipCheck('<?=$i?>','A')" /> Approve<br>
                <input type="hidden" id="is_approved_<?=$i?>" value="N" name="EntrySlip[<?=$j?>][is_approved]" readonly=""/>
                <input type="checkbox" value="<?=$leave_id?>" id="reject_<?=$i?>" onclick="return entrySlipCheck('<?=$i?>','R')" /> Reject
                <input type="hidden" id="is_rejected_<?=$i?>" value="N" name="EntrySlip[<?=$j?>][is_rejected]" readonly=""/>
            </td>
            <!--<td><a href="" class="btn btn btn-light btn-xs">View</a></td>-->
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
            <th>Entry Date & Time</th>
            <th>Applied On</th>
            <th>Reason</th>
            <th></th>            
            <!--<th></th>-->            
        </tr>
    </tfoot>
</table>
<?php if(!empty($slips)){ ?>
<div class="text-right">
    <button type="button" class="btn btn-success btn-sm" onclick="return validateEntrySlipLeave()">Submit</button>
</div>

<?php } ActiveForm::end(); ?>