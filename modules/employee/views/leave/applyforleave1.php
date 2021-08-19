<?php
$this->title= 'Leave Application Form';
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
$lTys =Yii::$app->hr_utility->get_employee_leaves(Yii::$app->user->identity->e_id);
//echo "<pre>";print_r($lTys);die;
if(!empty($lTys)){
    $lTypes = array();
    $i=0;
    foreach($lTys as $lTy){
        $id = Yii::$app->utility->encryptString($lTy['lt_id']);
        $lTypes[$i]['lt_id'] =$id;
        $lTypes[$i]['desc'] =$lTy['desc'];
        $lTypes[$i]['leaves_chart_id'] =Yii::$app->utility->encryptString($lTy['leaves_chart_id']);
        $i++;
    }
    $lTys = $lTypes;
}
$lcards = Yii::$app->hr_utility->get_employee_leaves(Yii::$app->user->identity->e_id);
$lastdate="";
if(!empty($leave_app_id)){
    $leaves = Yii::$app->hr_utility->hr_get_leaves('R', Yii::$app->user->identity->e_id, $leave_app_id, "Draft");
    if(!empty($leaves)){
        $datelist = array();
        foreach($leaves as $l){
            $datelist[]= strtotime($l['req_to_date']);
        }
        $datelist1 = max($datelist);
        $lastdate = date('d-m-Y', strtotime('+1 day', $datelist1));
    }
}
?>
<?php 
if(!empty($lcards)){
    echo $this->render('leavecard', ['lcards'=>$lcards]);
$form = ActiveForm::begin(); ?>
    <input type="hidden" id="checkhalfday" name="EmployeeLeavesRequests[checkhalfday]" value="1" readonly="" />
    <input type="hidden" name="EmployeeLeavesRequests[leave_app_id]" value="<?=Yii::$app->utility->encryptString($leave_app_id)?>" readonly="" />
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'leave_reason')->textInput(['maxlength' => true, 'class'=>'form-control form-control-sm', 'placeholder'=>'Leave Reason']) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'contact_address')->textarea(['rows' => TRUE, 'maxlength' => 255, 'class'=>'form-control form-control-sm', 'placeholder'=>'Contact Address']) ?> 
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'contact_no')->textInput(['onkeypress'=>'return allowOnlyNumber(event)', 'maxlength' => '10', 'class'=>'form-control form-control-sm', 'placeholder'=>'Contact No.']) ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'availing_for_LTC')->dropDownList([ 'N' => 'No','Y' => 'Yes', ],  [ 'class'=>'form-control form-control-sm']) ?>
        </div>
    <div class="col-sm-3">
        <label>Select Leave Type</label>
        <select id="employeeleavesrequests-leave_type" class="form-control form-control-sm" name="EmployeeLeavesRequests[leave_type]">
            <option value="">Select Leave Type</option>
            <?php 
            if(!empty($lTys)){
                foreach($lTys as $l){
                    $lt_id = $l['lt_id'];
                    $leaves_chart_id = $l['leaves_chart_id'];
                    $desc = $l['desc'];
                    ?>
                    <option value="<?=$lt_id?>" data-key="<?=$leaves_chart_id?>"><?=$desc?></option>
                <?php }
            }
            ?>
        </select>
        <?php
        // lt_id is hr_master_leave_type is 
        //        $list = ArrayHelper::map($lTys, 'lt_id', 'desc');
        //             echo $form->field($model, 'leave_type')->dropDownList($list, ['prompt'=>'Select', 'class'=>'form-control form-control-sm',])->label(); 
        ?>
    </div>
 
    <div class="col-sm-3" id="show_fullday" style="display:none;">
        <div class="form-group field-employeeleavesrequests-whetherhalfday">
            <label class="control-label" for="employeeleavesrequests-whetherhalfday">&nbsp;</label>
            <select id="employeeleavesrequests-whetherhalfday" class="form-control form-control-sm" name="EmployeeLeavesRequests[whetherhalfday]">

            </select>
        </div>
    </div>
    
    
    <?php 
    if(!empty($lastdate)){ ?>
        <div class="col-sm-3">
            <label class="control-label">From</label>
            <input type="text" class="form-control form-control-sm" value="<?=$lastdate?>" readonly="" placeholder="From Date">
            <input type="hidden" class="form-control form-control-sm" name="EmployeeLeavesRequests[reqfromdate]" value="<?=Yii::$app->utility->encryptString($lastdate)?>" readonly="">
        </div>

    <?php }else{ ?>
        <div class="col-sm-3">
            <?= $form->field($model, 'req_from_date')->textInput(['class'=>'form-control form-control-sm', 'placeholder'=>'From Date', 'readonly'=>true]) ?>
        </div>
    <?php } ?>
        
    
    <div class="col-sm-3">
        <?= $form->field($model, 'req_to_date')->textInput(['class'=>'form-control form-control-sm', 'placeholder'=>'Till Date', 'readonly'=>true]) ?>
    </div>
    <div class="col-sm-12 text-center">
        <input type="submit" class="btn btn-success btn-sm sl" value="Save" />
        <a href="" class="btn btn-danger btn-sm">Reset</a>
    </div> 
     
</div>
<?php ActiveForm::end(); 

if(!empty($leave_app_id)){
    $leaves = Yii::$app->hr_utility->hr_get_leaves('R', Yii::$app->user->identity->e_id, $leave_app_id, "Draft");
    if(!empty($leaves)){ ?>
    <div class="col-sm-12">
        <hr>
        <h6><b>Added Leave Entries:</b></h6>
        <table class="table table-bordered">
            <tr>
                <th>Leave Type</th>
                <th>From Date</th>
                <th>Till Date</th>
                <th>No. of Days</th>
            </tr>
            <?php 
            foreach($leaves as $l){
                echo "<tr>
                    <td>".$l['desc']."</td>
                    <td>".date('d-m-Y', strtotime($l['req_from_date']))."</td>
                    <td>".date('d-m-Y', strtotime($l['req_to_date']))."</td>
                    <td>".$l['totaldays']."</td>
                </tr>";
            }
            ?>
        </table>
        <hr>
        <?php 
        $leave_app_id = Yii::$app->utility->encryptString($leave_app_id);
        $E = Yii::$app->utility->encryptString('E');
        $A = Yii::$app->utility->encryptString('A');
        $S = Yii::$app->utility->encryptString('S');
        
        $delE = Yii::$app->homeUrl."employee/leave/applicationaction?securekey=$menuid&key=$leave_app_id&action=$E";
        $delA = Yii::$app->homeUrl."employee/leave/applicationaction?securekey=$menuid&key=$leave_app_id&action=$A";
        $SubA = Yii::$app->homeUrl."employee/leave/applicationaction?securekey=$menuid&key=$leave_app_id&action=$S";
        ?>
        <a href="<?=$delE?>" class="btn btn-secondary btn-sm btn-xs checktapptype" data-type="E" title="Discard All Leave Entires">Discard All Leave Entires</a>
        <a href="<?=$delA?>" class="btn btn-secondary btn-sm btn-xs checktapptype" data-type="A" title="Delete Leave Application">Delete Leave Application</a>
        <a href="<?=$SubA?>" class="btn btn-success btn-sm btn-xs checktapptype" data-type="S" title="Submit For Approval">Submit For Approval</a>
    </div>
    
    
    <?php }
}
?>

<?php 
}else{?>
  <div class="col-sm-12 col-xs-12 text-center alert alert-danger">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <b>No Leaves Record Found. You cannot apply for leave</b></div>  
<?php }

if(!empty($lastdate)){ 
    //$lastdate = date('Y-m-d', strtotime('+1 day', strtotime($lastdate)));
    ?>
    <script>
        $(document).ready(function(){
            $("#employeeleavesrequests-req_to_date").datepicker({
                autoclose:true,
                format: "dd-mm-yyyy",
                orientation: "top",
                startDate:"<?=$lastdate?>"
            }).on('changeDate', function(ev){
            {
                
            }
            });
        });
    </script>
<?php }else{ ?>
    <script>
        $(document).ready(function(){
            $('#employeeleavesrequests-req_from_date').datepicker({
                autoclose:true,
                format: "dd-mm-yyyy",
                orientation: "top-left"
            }).on('changeDate', function (selected){
                var minDate = new Date(selected.date.valueOf());
                $('#employeeleavesrequests-req_to_date').datepicker('setStartDate', minDate);
                $("#employeeleavesrequests-req_to_date").val('');
            });


            $("#employeeleavesrequests-req_to_date").datepicker({
                autoclose:true,
                format: "dd-mm-yyyy",
                orientation: "top"
            }).on('changeDate', function(ev){
            {
                    hideError();
                var fromdateval= $("#employeeleavesrequests-req_from_date").val();
                if(fromdateval==''){
                    $("#employeeleavesrequests-till").val('');
                    showError("Please enter from date first");
                    return false;
                }
                var from = $("#employeeleavesrequests-req_from_date").val();
                var till = $("#employeeleavesrequests-req_to_date").val();
                //  var leaveType = $('#employeeleavesrequests-leave_type').find(":selected").text();
                if($("#checkhalfday").val() == '2'){
                    var whetherhalfday = $("#employeeleavesrequests-whetherhalfday").val();
                    if(whetherhalfday !='FULL'){
                        if(from != till){
                            $("#employeeleavesrequests-req_to_date").val('');
                            showError("From and Till date should be same, if leave not for full day.");
                            return false;
                        }
                    }
                }
            }
            });
        });
    </script>
<?php }
?>


