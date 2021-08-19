<?php
$this->title = "Assign / Update Leaves of ".$empInfo['fullname'];
//echo "<pre>";print_r($leavesinfo);
use yii\widgets\ActiveForm;
?>
<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered">
            <tr>
                <td><b>Name : </b><br><?=$empInfo['fullname']?></td>
                <td><b>Designation : </b><br><?=$empInfo['desg_name']?></td>
                <td><b>Department : </b><br><?=$empInfo['dept_name']?></td>
            </tr>
            <tr>
                <td><b>Joining Date : </b><br><?=date('d-m-Y', strtotime($empInfo['joining_date']))?></td>
                <td><b>Employee Type : </b><br><?=$empInfo['employment_type']?></td>
                <td><b>Contact No. : </b><br><?=$empInfo['phone']?></td>
            </tr>
        </table>
    </div>
</div>
<hr>
<div class="text-right">
    <a href="<?=Yii::$app->homeUrl?>admin/manageleaves/assignleavetoemp?securekey=<?=$menuid?>&securecode=<?=Yii::$app->utility->encryptString($empInfo['employee_code'])?>" class="btn btn-success btn-sm mybtn">Assign Another Leave to <?=$empInfo['fullname']?></a>
</div>
<h6><b><u>Current Leave Assigned Details</u></b></h6>
<?php 
$url = Yii::$app->homeUrl."employee/information/attendance?securekey=$menuid";
$form = ActiveForm::begin(['id'=>'calenderForm','action'=>$url, 'method'=>'GET']); 
if(!empty($leavesinfo)){ ?>
<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered">
            <tr>
                <th>Session Year</th>
                <th>Session Type</th>
                <th>Leave Type</th>
                <th>Balance</th>
                <th>Pending</th>
                <th>Available</th>
                <th></th>
            </tr>
            <?php
            $i=1;
            foreach($leavesinfo as $leave){ 
                $balance = $leave['balance_leaves']+$leave['pending_leaves'];
                $balance = number_format($balance, 1);
            ?>
            <tr>
                <td id="session_year_<?=$i?>"> <input type="hidden" name="UpdateLeave['ld']" value='<?=Yii::$app->utility->encryptString($leave['ld'])?>' />
                <?=$leave['session_year']?></td>
                <td id="session_type_<?=$i?>"><?=$leave['session_type']?></td>
                <td id="desc_<?=$i?>"><?=$leave['desc']?></td>
                <td><?=$balance?></td>
                <td id="pending_<?=$i?>"><?=$leave['pending_leaves']?></td>
                <td id="available_<?=$i?>"><?=$leave['balance_leaves']?></td>
                <td><a href="" class="linkcolor">Update</a></td>
            </tr>    
            <?php }
            ?>
        </table>
    </div>
</div>
<?php  } 
ActiveForm::end(); ?>
