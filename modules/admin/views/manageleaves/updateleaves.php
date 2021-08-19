<?php
$this->title = "Assign / Update Leaves of ".$empInfo['fullname'];
//echo "<pre>";print_r($leavesinfo);
use yii\widgets\ActiveForm;
?>
<input type="hidden" value="<?=$menuid?>" id="menuid" readonly="" />
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
//$url = Yii::$app->homeUrl."employee/information/attendance?securekey=$menuid";
$url = Yii::$app->homeUrl."admin/manageleaves/updateleaves?securekey=$menuid&securecode=".Yii::$app->utility->encryptString($empInfo['employee_code']);
//$form = ActiveForm::begin(['id'=>'calenderForm','action'=>$url, 'method'=>'GET']); 
$form = ActiveForm::begin(['id'=>'calenderForm','action'=>$url, 'method'=>'POST']);
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
                $total_leav=$leave['total_leaves'];
            ?>
            <tr>
                <td id="session_year_<?=$i?>"> <input id="leave_id_<?=$leave['ld']?>" type="hidden" name="UpdateLeave['ld']" value='<?=Yii::$app->utility->encryptString($leave['ld'])?>' />
                <?=$leave['session_year']?></td>
                <td id="session_type_<?=$i?>"><?=$leave['session_type']?></td>
                <td id="desc_<?=$i?>"><?=$leave['desc']?></td>
                <td><?=$balance?></td>
                <td id="pending_<?=$i?>"><?=$leave['pending_leaves']?></td>
                <td id="available_<?=$i?>"><?=$leave['balance_leaves']?></td>
                <td><!-- <?php if($total_leav!=20) { ?> <a href="<?=Yii::$app->homeUrl?>admin/manageleaves/updateleavetoemployee?securekey=<?=$menuid?>&securecode=<?=Yii::$app->utility->encryptString($empInfo['employee_code'])?>&leve=<?=Yii::$app->utility->encryptString($leave['ld'])?>" class="btn btn-success btn-sm mybtn">Update </a> <?php  } ?> -->
                <a href='javascript:void(0)' data-srno="<?=$leave['ld']?>" class='btn btn-success btn-sm mybtn add_more_leave'  >Add More</a>
            </td>
            </tr>    
            <?php }
            ?>
        </table>
          <div class="modal fade" id="viewleavedetails" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add More Employee Leave </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
              
           
               
                <div></div>
                <table id="leaveinfo" class="table table-bordered table-hover">
                   
                </table>

            </div>
        </div>
    </div>
</div>
    </div>
</div>
<?php  } 
ActiveForm::end(); ?>
