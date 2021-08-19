<?php
$this->title= 'Pending Leave Application';
?>
<table id="dataTableShow" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Sr.</th>
            <th>Name</th>
            <th>Designation</th>
            <?php if(Yii::$app->user->identity->role == '5'){ echo "<th>Department</th>";} ?>
            <th>From to Till Date</th>
            <th>Applied On</th>
            <th></th>            
        </tr>
    </thead>
    <tbody>
        <?php if(!empty($apps)){
//            echo "<pre>";print_r($apps);die;
            $i=1;
            foreach($apps as $a){
                $leave_app_id = Yii::$app->utility->encryptString($a['leave_app_id']);
                $ec = Yii::$app->utility->encryptString($a['employee_code']);
                $empinfo = Yii::$app->utility->get_employees($a['employee_code']);
                $leave_from = date('d-m-Y', strtotime($a['leave_from']));
                $leave_to = date('d-m-Y', strtotime($a['leave_to']));
                $from = "$leave_from to $leave_to";
                $view = Yii::$app->homeUrl."hr/approveleaveapplication/viewandaction?securekey=$menuid&key=$leave_app_id&key2=$ec";
                $view = "<a href='$view' class='linkcolor'>View & Action</a>";
            ?>
        <tr>
            <td><?=$i?></td>
            <td><?=$empinfo['fullname']?></td>
            <td><?=$empinfo['desg_name']?></td>
            <?php if(Yii::$app->user->identity->role == '5'){ echo "<td>".$empinfo['dept_name']."</td>";} ?>
            <td><?=$from?></td>
            <td><?=date('d-m-Y', strtotime($a['applied_date']))?></td>
            <td><?=$view?></td>
        </tr>
        <?php 
        $i++;
            }
        }?>
    </tbody>
    <tfoot>
        <tr>
            <th>Sr.</th>
            <th>Name</th>
            <th>Designation</th>
            <?php if(Yii::$app->user->identity->role == '5'){ echo "<th>Department</th>";} ?>
            <th>From to Till Date</th>
            <th>Applied On</th>
            <th></th>            
        </tr>
    </tfoot>
</table>

<!--<div class="modal fade" id="leavedata" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document" style="width:850px;">
    <div class="modal-content">
        <div class="modal-header" style="background-color:#1DABB8">
        <h5 class="modal-title" id="myModalLabel" style="color: white;">Leave Application Form</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="opacity: 1; color: white;"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body" id ="modal_contentdata"> 
            <label>Date: <?php echo date('d-M-Y'); ?></label>
            <table class="table table-bordered" id="leavedata_tr">
                
            </table>
        </div>
    </div>
  </div>
</div>-->