<?php
$this->title= 'Apply for Leave';
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\MasterLeaveType;
$lTys =Yii::$app->hr_utility->get_employee_leaves(Yii::$app->user->identity->e_id);
//echo "<pre>";print_r($lTys); die; 
if(!empty($lTys)){
    $lTypes = "";
    $i=0;
    foreach($lTys as $lTy){
        $id = Yii::$app->utility->encryptString($lTy['lt_id']);
        $lTypes[$i]['lt_id'] =$id;
        $lTypes[$i]['desc'] =$lTy['desc'];
        $i++;
    }
    $lTys = $lTypes;
}
$lcards = Yii::$app->hr_utility->get_employee_leaves(Yii::$app->user->identity->e_id);
//echo "<pre>";print_r($lcards); 
$cl = $pl = $rh=0;
?>
<style>
    label{font-size: 13px;}
</style>
<?php 
if(!empty($lcards)){
?>
<div class="text-right">
    <button type="button" class="btn btn-secondary btn-sm btnxs" value="H" id="leavebalance">Leave Balance</button>
</div>
<span id="leavebalances" style="display: none;">
    <table class="table table-bordered">
        <tr>
            <th>Leave Type</th>
            <th>Balance</th>
            <th>Pending</th>
            <th>Available</th>
            <th>Leave Card</th>
        </tr>
        <?php 
//        echo "<pre>";print_r($lcards);
        if(!empty($lcards)){
            foreach($lcards as $lcard){
//                $levfor = " (".$lcard['label'].")";
//                if($lcard['leave_for'] != 'All'){
//                    $levfor .= "- ".$lcard['leave_for'];
//                }
                
                $desc= $lcard['desc'];
                $pending= $lcard['pending_leaves'];
                $balance = $lcard['balance_leaves']+$pending;
                //$avail = $balance - $pending;
                $avail = number_format($lcard['balance_leaves'],1);
                $balance = number_format($balance,1);
//                if($lcard['label'] == "CL"){ $cl =$avail; }elseif($lcard['label'] == "PL"){ $pl =$avail; }elseif($lcard['label'] == "RH"){ $rh =$avail; }
                echo "
                    <tr>
                        <td>$desc</td>
                        <td>$balance</td>
                        <td>$pending</td>
                        <td>$avail</td>
                        <td><a href='javascript:void(0)' onclick='leavecarddetail($lcard[leave_type]);'><img src=".Yii::$app->homeUrl.'images/view.png'." style='width: 23px;'/></a></td>
                    </tr>
                ";
            }
        }
        ?>
    </table>
</span>
<div class="employee-leaves-requests-form">
<?php $form = 
        ActiveForm::begin
                ([
                    'action'=>Yii::$app->homeUrl.'employee/leave/applyforleave',
//                    'beforeSubmit' => 'leavevalidation',
                ]); 
?>
<!--    <input type="hidden" id="cl_l" readonly="" value="<?=$cl?>" />
    <input type="hidden" id="pl_l" readonly="" value="<?=$pl?>" />
    <input type="hidden" id="rh_l" readonly="" value="<?=$rh?>" />-->
    <input type="hidden" id="checkhalfday" value="1" readonly="" />
    <input type="hidden" id="menuid" readonly="" name="EmployeeLeavesRequests[menuid]" value="<?=$menuid?>" />
<div class="row">
    <?php //$form->field($model, 'e_id')->hiddenInput(['value'=>Yii::$app->user->identity->e_id])->label(false) ?>
    <div class="col-sm-12">
        <?= $form->field($model, 'leave_reason')->textInput(['maxlength' => true, 'class'=>'form-control form-control-sm', 'placeholder'=>'Leave Reason']) ?>
    </div>
    <div class="col-sm-6">
        <?= $form->field($model, 'contact_address')->textarea(['rows' => TRUE, 'maxlength' => 255, 'class'=>'form-control form-control-sm', 'placeholder'=>'Contact Address']) ?> 
        <?php // echo $form->field($model, 'contact_address')->textInput(['maxlength' => true, 'class'=>'form-control form-control-sm', 'placeholder'=>'Contact Address']) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'contact_no')->textInput(['maxlength' => '10', 'class'=>'form-control form-control-sm', 'placeholder'=>'Contact No.']) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'availing_for_LTC')->dropDownList([ 'N' => 'No','Y' => 'Yes', ],  [ 'class'=>'form-control form-control-sm']) ?>
    </div>
    <div class="col-sm-3">
        <?php
//        $list = ArrayHelper::map(MasterLeaveType::find()->select('lt_id, desc')->all(), 'lt_id', 'desc');
        $list = ArrayHelper::map($lTys, 'lt_id', 'desc');
             echo $form->field($model, 'leave_type')->dropDownList($list, ['prompt'=>'Select', 'class'=>'form-control form-control-sm',])->label(); 
        ?>
    </div>
 
    <div class="col-sm-3" id="show_fullday" style="display:none;">
        <div class="form-group field-employeeleavesrequests-whetherhalfday">
            <label class="control-label" for="employeeleavesrequests-whetherhalfday">&nbsp;</label>
            <select id="employeeleavesrequests-whetherhalfday" class="form-control form-control-sm" name="EmployeeLeavesRequests[whetherhalfday]">

            </select>
        </div>
        <?php //$form->field($model, 'whetherhalfday')->dropDownList([ 'FULL' => 'Full Day', 'F-HALF' => 'FIrst-Half', 'S-HALF' => 'Secound-Half', ], ['class'=>'form-control form-control-sm',])->label('&nbsp') ?>
    </div>
    
    <div class="col-sm-3">
        <?= $form->field($model, 'from')->textInput(['class'=>'form-control form-control-sm', 'placeholder'=>'From Date', 'readonly'=>true]) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'till')->textInput(['class'=>'form-control form-control-sm', 'placeholder'=>'Till Date', 'readonly'=>true]) ?>
    </div>
    <div class="col-sm-12 text-center">
        <input type="submit" class="btn btn-success btn-sm"  onclick="return leavevalidation()" value="Submit" />
        <a href="" class="btn btn-danger btn-sm">Reset</a>
    </div> 
     
</div>
<?php ActiveForm::end(); ?>
</div>
<?php 
}else{?>
  <div class="col-sm-12 col-xs-12 text-center alert alert-danger">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <b>No Leaves Record Found. You cannot apply for leave</b></div>  
<?php }
?>
<script>
function leavevalidation(){
    hideError();
    var phoneno = $("#employeeleavesrequests-contact_no").val();
    if(!$.isNumeric(phoneno))
    {
        showError("Please enter valid contact number");
        return false;
    }

    var checkhalfday = $("#checkhalfday").val();
//    alert(checkhalfday);
    if(checkhalfday == '2'){
        if($("#employeeleavesrequests-whetherhalfday").val() != 'FULL'){
            var from = $("#employeeleavesrequests-from").val();
            var till = $("#employeeleavesrequests-till").val();
            if(from != till){
                showError("Date should be same for First / Second Half");
                return false;
            }
        }
    }
    
//    var types = $("#employeeleavesrequests-leave_type").find(":selected").text();
//    var from = $("#employeeleavesrequests-from").val();
//    var till = $("#employeeleavesrequests-till").val();
//    if(types == "Casual Leave"){}
//    return false;
}
function leavecarddetail(id)
{
    var url = BASEURL + 'employee/leave/viewleavecard?leavetype=' + id + '';
    window.open(url, '_blank');
}
</script>
