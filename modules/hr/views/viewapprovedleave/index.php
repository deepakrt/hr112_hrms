<?php
use yii\widgets\ActiveForm;
$this->title= 'View Approved / Rejected Leave Application';
// echo "<pre>";print_r($apps); die();
?>
<input type='hidden' id='menuid' value="<?=$menuid?>" />
<hr>
<div class="col-sm-12">
    <table id="dataTableShow" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Employee Code</th>
                <th>Name</th>
                <th>App. Date</th>
                <th>From Date</th>
                <th>Till Date</th>
                <th>Status</th>
                <th>View</th>
            </tr>
        </thead>
            <tbody>
                <?php 
                if(!empty($apps)){
                    $i =1;
                    foreach($apps as $l)
                    { 
                        $fname=$l['fname'];
                        $lname=$l['lname'];
                        $employee_code=$l['employee_code'];
                        $applied_date=date('d-M-Y', strtotime($l['applied_date']));
                        $fromdate=date('d-M-Y', strtotime($l['leave_from']));
                        $leave_to=date('d-M-Y', strtotime($l['leave_to']));
                        $status=$l['status'];
                        $leave_app_id = Yii::$app->utility->encryptString($l['leave_app_id']);
                        $ec = Yii::$app->utility->encryptString($l['employee_code']);

                        if($status == 'ABRA')
                        {
                            $status = 'Approved';
                        }
                    ?>
                    <tr>
                        <td><?=$employee_code?></td>
                        <td><?=$fname.' '.$lname?></td>
                        <td><?=$applied_date?></td>
                        <td><?=$fromdate;?></td>
                        <td><?=$leave_to;?></td>
                        <td><?=$status;?></td>
                        <td>
                            <a href="javascript:void(0)" class='hrviewleavedetails' data-key='<?=$leave_app_id?>' data-key1='<?=$ec?>' ><img src="<?=Yii::$app->homeUrl?>images/view.png" style="width: 23px;"/></a>
                    </td>
                    </tr>	
                    <?php $i++;	
                    }
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>App. Date</th>
                    <th>From Date</th>
                    <th>Till Date</th>
                    <th>Status</th>
                    <th>View</th>
                </tr>
            </tfoot>
    </table>
</div>

<div class="modal fade" id="leavedata" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" style="width:850px;">
    <div class="modal-content">
        <div class="modal-header" style="background-color:#1DABB8">
        <h5 class="modal-title" id="myModalLabel" style="color: white;">Leave Application Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="opacity: 1; color: white;"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body" id ="modal_contentdata"></div>
    </div>
  </div>
</div>