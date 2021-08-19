<?php
$this->title = "Claim Details";
use yii\widgets\ActiveForm;
$claimid1 = Yii::$app->utility->decryptString($claimid);
$reqid1 = Yii::$app->utility->decryptString($reqid);
$haltDetails = Yii::$app->finance->fn_get_claim_halt_details($claimid1, $reqid1, Yii::$app->user->identity->e_id);
$conveyDetails = Yii::$app->finance->fn_get_claim_conveyance_details($claimid1, $reqid1, Yii::$app->user->identity->e_id);
$journeyDetails = Yii::$app->finance->fn_get_claim_journey_details($claimid1, $reqid1, Yii::$app->user->identity->e_id);
$foodDetails = Yii::$app->finance->fn_get_claim_food_details($claimid1, $reqid1, Yii::$app->user->identity->e_id);

//echo "<pre>";print_r($haltDetails); 
$clamt = 0;
if(!empty($claimHeader['claimed_amount'])){
    $clamt = $claimHeader['claimed_amount'];
}
$citylists = Yii::$app->hr_utility->hr_get_city_list();

?>
<h6><b>Claim Header</b></h6>
<table class="table table-bordered">
    <tr>
        <td><b>Employee Code : </b> <?=$claimHeader['employee_code']?></td>
        <td><b>Name : </b> <?=$claimHeader['fullname']?>, <?=$claimHeader['desg_name']?></td>
        <td><b>Department : </b> <?=$claimHeader['dept_name']?></td>
    </tr>
    <tr>
        <td><b>Project Name : </b> <?=$claimHeader['project_name']?></td>
        <td><b>Location : </b> <?=$claimHeader['city_name']?></td>
        <td><b>Claimed Amount : </b> <?=$clamt?></td>
    </tr>
    <tr>
        <td><b>Start Date Time : </b> <?=date('d-m-Y H:i', strtotime($claimHeader['start_date']))?></td>
        <td><b>End Date Time : </b> <?=date('d-m-Y H:i', strtotime($claimHeader['end_date']))?></td>
    </tr>
    <tr>
        <!--<td colspan="3" align='right'><button type="button" class="btn btn-danger btn-sm btn-xs">Edit Claim Header</button></td>-->
        <td colspan="3" align='right'><a href="<?=Yii::$app->homeUrl?>employee/claim/applytourclaim?securekey=<?=$menuid?>&id=<?=$reqid?>&claimid=<?=$claimid?>" class="btn btn-danger btn-sm btn-xs">Edit Claim Header</a></td>
        
    </tr>
</table>
<hr class="hrline">
<h6><b>Journey Details</b></h6>
<?php 
$haltUrl = Yii::$app->homeUrl."employee/claim/addjourneydetail?securekey=$menuid";
$form = ActiveForm::begin(['action'=>$haltUrl, 'options'=>['id'=>'haltdetails', ]]); ?>
<input type="hidden" name="Journey[claimid]" value='<?=$claimid?>' />
<input type="hidden" name="Journey[reqid]" value='<?=$reqid?>' />
<input type="hidden" name="Journey[header_start_date]" value='<?=Yii::$app->utility->encryptString($claimHeader['start_date'])?>' />
<input type="hidden" name="Journey[header_end_date]" value='<?=Yii::$app->utility->encryptString($claimHeader['end_date'])?>' />

<div class="row">
    <div class="col-sm-4">
        <label>Start Date</label>
        <div class="row">
            <div class="col-sm-6">
                <input type="text" id="j_start_date" name="Journey[start_date]" class="form-control form-control-sm" readonly="" placeholder="Start Date" required="" />
            </div>
            <div class="col-sm-3" style="padding: 0;">
                <select id="j_start_date_hh" name="Journey[start_date_hh]" class="form-control form-control-sm" required="">
                    <option value="">HH</option>
                    <?php 
                    for($i=0;$i<=23;$i++){
                        $ii = sprintf("%02d", $i);
                        echo "<option value='$ii'>$ii</option>";
                    }
                    ?>
                </select>
            </div>
            
            <div class="col-sm-3" style="padding: 0;">
                <select id="j_start_date_mm" name="Journey[start_date_mm]" class="form-control form-control-sm" required="">
                    <option value="">MM</option>
                    <?php 
                    for($i=0;$i<=59;$i++){
                        $ii = sprintf("%02d", $i);
                        echo "<option value='$ii'>$ii</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <label>End Date</label>
        <div class="row">
            <div class="col-sm-6">
                <input type="text" id="j_end_date" name="Journey[end_date]" class="form-control form-control-sm" readonly="" placeholder="End Date" required="" />
            </div>
            <div class="col-sm-3" style="padding: 0;">
                <select id="j_end_date_hh" name="Journey[end_date_hh]" class="form-control form-control-sm" required="">
                    <option value="">HH</option>
                    <?php 
                    for($i=0;$i<=23;$i++){
                        $ii = sprintf("%02d", $i);
                        echo "<option value='$ii'>$ii</option>";
                    }
                    ?>
                </select>
            </div>
            
            <div class="col-sm-3" style="padding: 0;">
                <select id="j_end_date_mm" name="Journey[end_date_mm]" class="form-control form-control-sm" required="">
                    <option value="">MM</option>
                    <?php 
                    for($i=0;$i<=59;$i++){
                        $ii = sprintf("%02d", $i);
                        echo "<option value='$ii'>$ii</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <label>T. Class</label>
        <select class="form-control form-control-sm" name="Journey[t_class]" id="j_t_class" required="">
            <option value="">Select Class</option>
            <?php 
            $classType = JourneyClassType; //array('Bus','AC Chair Car Shatabdi','AC Bus', 'Second Class Sitting');
            foreach($classType as $class){
                $id = Yii::$app->utility->encryptString($class);
                echo "<option value='$id'>$class</option>";
            }
            ?>
        </select>
    </div>
</div>
<br>
<div class="row">
    <div class="col-sm-3">
        <label>Place From</label>
        <input type='text' id="j_place_from" name="Journey[place_from]" class="form-control form-control-sm"  placeholder="Place From" required="" />
    </div>
    <div class="col-sm-3">
        <label>Place To</label>
        <input type='text' id="j_place_to" name="Journey[place_to]" class="form-control form-control-sm"  placeholder="Place To" required="" />
    </div>
    <div class="col-sm-2">
        <label>Ticket</label>
        <select class="form-control form-control-sm" name="Journey[ticket]" id="j_ticket" required="">
            <option value="">Select Ticket</option>
            <?php 
            $ticket = JourneyTickets;
            foreach($ticket as $tic){
                $id = Yii::$app->utility->encryptString($tic);
                echo "<option value='$id'>$tic</option>";
            }
            ?>
        </select>
    </div>
    <div class="col-sm-2">
        <label>Amount</label>
        <input type='text' class="form-control form-control-sm" name="Journey[amount]" maxlength="6" onkeypress="return allowOnlyNumber(event)" placeholder="Enter Amount" id="j_amount" required="" />
    </div>
    <div class="col-sm-1" style="padding: 0px; margin-left: -5px;">
        <button type="submit" id="j_detail_add" style="margin-top: 30px;" class="btn btn-outline-success btn-sm sl">Add Journey Details</button>
    </div>    
</div>
<?php ActiveForm::end(); ?>
<br>
<table class="table table-bordered">
    <tr>
        <th>Sr. No. </th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Place From</th>
        <th>Place To</th>
        <th>TClass</th>
        <th>Amount</th>
        <th></th>
    </tr>
    <?php 
    if(!empty($journeyDetails)){
        $i= 1;
        $total = 0;
        foreach($journeyDetails as $journey){
            $j_id = Yii::$app->utility->encryptString($journey['j_id']);
            $start_date = date('d-m-Y H:i', strtotime($journey['start_date']));
            $end_date = date('d-m-Y H:i', strtotime($journey['end_date']));
            $place_from = $journey['place_from'];
            $place_to = $journey['place_to'];
            $amount= $journey['amount'];
            $t_class= $journey['t_class'];
            $total = $total+$amount;
            $delUrl = Yii::$app->homeUrl."employee/claim/deletehourneydetail?securekey=$menuid&j_id=$j_id&claimid=$claimid&reqid=$reqid";
            $delUrl = "<a href='$delUrl' class='deletehalt'><img src='".Yii::$app->homeUrl."images/del.gif' /></a>";
            echo "
            <tr>
                <td>$i</td>
                <td>$start_date</td>
                <td>$end_date</td>
                <td>$place_from</td>
                <td>$place_to</td>
                <td>$t_class</td>
                <td align='right'>$amount</td>
                <td>$delUrl</td>
            </tr>
            ";
            $i++;
        }
        echo "<tr><td colspan='6' align='right'><b>Total Halt Amount</b></td><td align='right'><b>".number_format($total, 2)."</b></td></tr>";
    }
    ?>
</table>

<hr class="hrline">
<h6><b>Halt Details</b></h6>
<?php 
$haltUrl = Yii::$app->homeUrl."employee/claim/addhaltdetail?securekey=$menuid";
$form = ActiveForm::begin(['action'=>$haltUrl, 'options'=>['id'=>'haltdetails', ]]); ?>
<input type="hidden" name="Halt[claimid]" value='<?=$claimid?>' />
<input type="hidden" name="Halt[reqid]" value='<?=$reqid?>' />
<input type="hidden" name="Halt[header_start_date]" value='<?=Yii::$app->utility->encryptString($claimHeader['start_date'])?>' />
<input type="hidden" name="Halt[header_end_date]" value='<?=Yii::$app->utility->encryptString($claimHeader['end_date'])?>' />
<div class="row">
    <div class="col-sm-3">
        <label>Start Date</label>
        <input type='text' class="form-control form-control-sm" name="Halt[start_date]" value="<?=date('d-m-Y', strtotime($claimHeader['start_date']))?>" id="halt_start_date" placeholder="Select Start Date" readonly="" />
    </div>
    <div class="col-sm-3">
        <label>End Date</label>
        <input type='text' class="form-control form-control-sm" name="Halt[end_date]" value="<?=date('d-m-Y', strtotime($claimHeader['end_date']))?>" placeholder="Select End Date" id="halt_end_date" readonly="" />
    </div>
    <div class="col-sm-3">
        <label>Select City</label>
        <select class="form-control form-control-sm" name="Halt[city_id]" id="halt_city_id" required="">
            <option value="">Select City</option>
            <?php 
            if(!empty($citylists)){
                foreach($citylists as $city){
                    $city_id = base64_decode($city['id']);
                    $city_id = Yii::$app->utility->encryptString($city_id);
                    $cityname = $city['cityname'];
                    echo "<option value='$city_id'>$cityname</option>";
                }
            }
            ?>
        </select>
    </div>
    <div class="col-sm-3">
        <label>Select Stay Type</label>
        <select class="form-control form-control-sm" name="Halt[stay_type]" id="halt_stay_type" required="">
            <option value="">Select Stay Type</option>
            <?php 
            $stayType = StayType;
            foreach($stayType as $stay){
                $id = Yii::$app->utility->encryptString($stay);
                echo "<option value='$id'>$stay</option>";
            }
            ?>
        </select>
    </div>
    <div class="col-sm-3">
        <label>Amount</label>
        <input type='text' class="form-control form-control-sm" name="Halt[charges]" maxlength="6" onkeypress="return allowOnlyNumber(event)" placeholder="Enter Amount" id="halt_charges" required="" />
    </div>
    <div class="col-sm-3">
        <button type="submit" id="halt_detail_add" style="margin-top: 30px;" class="btn btn-outline-success btn-sm sl">Add Halt</button>
    </div>
    
</div>
<?php ActiveForm::end(); ?>
<br>
<table class="table table-bordered">
    <tr>
        <th>Sr. No. </th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>City</th>
        <th>Stay</th>
        <th>Charges</th>
        <th></th>
    </tr>
    <?php 
    if(!empty($haltDetails)){
        $i= 1;
        $total = 0;
        foreach($haltDetails as $halt){
            $th_id = Yii::$app->utility->encryptString($halt['th_id']);
            $start_date = date('d-m-Y', strtotime($halt['start_date']));
            $end_date = date('d-m-Y', strtotime($halt['end_date']));
            $city_name = $halt['city_name'];
            $stay = $halt['stay'];
            $charges = $halt['charges'];
            $total = $total+$charges;
            $delUrl = Yii::$app->homeUrl."employee/claim/deletehaltdetail?securekey=$menuid&th_id=$th_id&claimid=$claimid&reqid=$reqid";
            $delUrl = "<a href='$delUrl' class='deletehalt'><img src='".Yii::$app->homeUrl."images/del.gif' /></a>";
            echo "
            <tr>
                <td>$i</td>
                <td>$start_date</td>
                <td>$end_date</td>
                <td>$city_name</td>
                <td>$stay</td>
                <td align='right'>$charges</td>
                <td>$delUrl</td>
            </tr>
            ";
            $i++;
        }
        echo "<tr><td colspan='5' align='right'><b>Total Halt Amount</b></td><td align='right'><b>".number_format($total, 2)."</b></td></tr>";
    }
    ?>
</table>
<hr class="hrline">
<h6><b>Conveyance Details</b></h6>
<?php 
$haltUrl = Yii::$app->homeUrl."employee/claim/addconveyance?securekey=$menuid";
$form = ActiveForm::begin(['action'=>$haltUrl, 'options'=>['id'=>'conveyance', ]]); ?>
<input type="hidden" name="Conveyance[claimid]" value='<?=$claimid?>' />
<input type="hidden" name="Conveyance[reqid]" value='<?=$reqid?>' />
<input type="hidden" name="Conveyance[header_start_date]" value='<?=Yii::$app->utility->encryptString($claimHeader['start_date'])?>' />
<input type="hidden" name="Conveyance[header_end_date]" value='<?=Yii::$app->utility->encryptString($claimHeader['end_date'])?>' />

<div class="row">
    <div class="col-sm-4">
        <label>Start Date</label>
        <div class="row">
            <div class="col-sm-6">
                <input type="text" id="c_start_date" name="Conveyance[start_date]" class="form-control form-control-sm" readonly="" placeholder="Start Date" required="" />
            </div>
            <div class="col-sm-3" style="padding: 0;">
                <select id="c_start_date_hh" name="Conveyance[start_date_hh]" class="form-control form-control-sm" required="">
                    <option value="">HH</option>
                    <?php 
                    for($i=0;$i<=23;$i++){
                        $ii = sprintf("%02d", $i);
                        echo "<option value='$i'>$ii</option>";
                    }
                    ?>
                </select>
            </div>
            
            <div class="col-sm-3" style="padding: 0;">
                <select id="c_start_date_mm" name="Conveyance[start_date_mm]" class="form-control form-control-sm" required="">
                    <option value="">MM</option>
                    <?php 
                    for($i=0;$i<=59;$i++){
                        $ii = sprintf("%02d", $i);
                        echo "<option value='$i'>$ii</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <label>End Date</label>
        <div class="row">
            <div class="col-sm-6">
                <input type="text" id="c_end_date" name="Conveyance[end_date]" class="form-control form-control-sm" readonly="" placeholder="End Date" required="" />
            </div>
            <div class="col-sm-3" style="padding: 0;">
                <select id="c_end_date_hh" name="Conveyance[end_date_hh]" class="form-control form-control-sm" required="">
                    <option value="">HH</option>
                    <?php 
                    for($i=0;$i<=23;$i++){
                        $ii = sprintf("%02d", $i);
                        echo "<option value='$i'>$ii</option>";
                    }
                    ?>
                </select>
            </div>
            
            <div class="col-sm-3" style="padding: 0;">
                <select id="c_end_date_mm" name="Conveyance[end_date_mm]" class="form-control form-control-sm" required="">
                    <option value="">MM</option>
                    <?php 
                    for($i=0;$i<=59;$i++){
                        $ii = sprintf("%02d", $i);
                        echo "<option value='$i'>$ii</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="col-sm-3">
        <label>Mode</label>
        <select id="c_Mode" name="Conveyance[mode]" class="form-control form-control-sm" required="">
            <option value="">Select Mode</option>
            <?php 
            $modes = transportMode;
            foreach($modes as $mode){
                $id = Yii::$app->utility->encryptString($mode);
                echo "<option value='$id'>$mode</option>";
            }
            ?>
        </select>
    </div>
</div>
<br>
<div class="row">
    <div class="col-sm-3">
        <label>From</label>
        <input type="text" id="c_place_from" name="Conveyance[place_from]" class="form-control form-control-sm" placeholder="From" required="" />
    </div>
    <div class="col-sm-3">
        <label>To</label>
        <input type="text" id="c_place_to" name="Conveyance[place_to]" class="form-control form-control-sm" placeholder="To" required="" />
    </div>
    <div class="col-sm-2">
        <label>Distance (in Kms)</label>
        <input type="text" id="c_distance" name="Conveyance[distance]" class="form-control form-control-sm" onkeypress="return allowOnlyNumber(event)" placeholder="Distance" required="" />
    </div>
    <div class="col-sm-2">
        <label>Amount</label>
        <input type="text" id="c_amount" name="Conveyance[amount]" class="form-control form-control-sm" onkeypress="return allowOnlyNumber(event)" placeholder="Amount" required="" />
    </div>
    <div class="col-sm-2">
        <button type="submit" id="conveyance_detail_add" style="margin-top: 30px;" class="btn btn-outline-success btn-sm sl">Add Conveyance</button>
    </div>
</div>

<?php ActiveForm::end();?>
<br>
<table class="table table-bordered">
    <tr>
        <th>Sr. No. </th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Place From</th>
        <th>Place To</th>
        <th>Mode</th>
        <th>Distance (in Kms)</th>
        <th>Amount</th>
        <th></th>
    </tr>
    <?php 
    if(!empty($conveyDetails)){
        $i= 1;
        $total = 0;
        foreach($conveyDetails as $convy){
            $tc_id = Yii::$app->utility->encryptString($convy['tc_id']);
            $start_date = date('d-m-Y H:i', strtotime($convy['start_date']));
            $end_date = date('d-m-Y H:i', strtotime($convy['end_date']));
            $place_from = $convy['place_from'];
            $place_to = $convy['place_to'];
            $mode = $convy['mode'];
            $distance = $convy['distance'];
            $amount = $convy['amount'];
            $total = $total+$amount;
            $delUrl = Yii::$app->homeUrl."employee/claim/deleteconveydetail?securekey=$menuid&tc_id=$tc_id&claimid=$claimid&reqid=$reqid";
            $delUrl = "<a href='$delUrl' class='deletehalt'><img src='".Yii::$app->homeUrl."images/del.gif' /></a>";
            echo "
            <tr>
                <td>$i</td>
                <td>$start_date</td>
                <td>$end_date</td>
                <td>$place_from</td>
                <td>$place_to</td>
                <td>$mode</td>
                <td>$distance</td>
                <td>$amount</td>
                <td>$delUrl</td>
            </tr>
            ";
            $i++;
        }
        echo "<tr><td colspan='7' align='right'><b>Total Conveyance Amount</b></td><td align='right'><b>".number_format($total, 2)."</b></td></tr>";
    }
    
    ?>
</table>
<hr class="hrline">
<h6><b>Food Details</b></h6>
<?php 
$haltUrl = Yii::$app->homeUrl."employee/claim/addfooddetail?securekey=$menuid";
$form = ActiveForm::begin(['action'=>$haltUrl, 'options'=>['id'=>'conveyance', ]]); ?>
<input type="hidden" name="Food[claimid]" value='<?=$claimid?>' />
<input type="hidden" name="Food[reqid]" value='<?=$reqid?>' />
<input type="hidden" name="Food[header_start_date]" value='<?=Yii::$app->utility->encryptString($claimHeader['start_date'])?>' />
<input type="hidden" name="Food[header_end_date]" value='<?=Yii::$app->utility->encryptString($claimHeader['end_date'])?>' />
<div class="row">
    <div class="col-sm-3">
        <label>Bill Date</label>
        <input type="text" id="food_billdate" name="Food[bill_date]" class="form-control form-control-sm" placeholder="Bill Date" required="" readonly="" value="<?=date('d-m-Y', strtotime($claimHeader['start_date']))?>" />
    </div>
    <div class="col-sm-4">
        <label>Purpose</label>
        <input type="text" id="food_Purpose" name="Food[purpose]" class="form-control form-control-sm" placeholder="Purpose" required="" />
    </div>   
    <div class="col-sm-3">
        <label>Bill Amount</label>
        <input type="text" id="c_amount" name="Food[amount]" class="form-control form-control-sm" onkeypress="return allowOnlyNumber(event)" placeholder="Amount" maxlength="6" required="" />
    </div>
    <div class="col-sm-1">
        <button type="submit" id="" style="margin-top: 30px;" class="btn btn-outline-success btn-sm sl">Add Food Detail</button>
    </div>
</div>
<?php ActiveForm::end(); ?>
<br>
<table class="table table-bordered">
    <tr>
        <th>Sr. No. </th>
        <th>Purpose</th>
        <th>Bill Date</th>
        <th>Amount</th>
        <th></th>
    </tr>
    <?php 
    if(!empty($foodDetails)){
        $i= 1;
        $total = 0;
        foreach($foodDetails as $food){
            $tf_id = Yii::$app->utility->encryptString($food['tf_id']);
            $bill_date = date('d-m-Y', strtotime($food['bill_date']));
            $purpose = $food['purpose'];
            $amount = $food['amount'];
            $total = $total+$amount;
            $delUrl = Yii::$app->homeUrl."employee/claim/deletefooddetail?securekey=$menuid&tf_id=$tf_id&claimid=$claimid&reqid=$reqid";
            $delUrl = "<a href='$delUrl' class='deletehalt'><img src='".Yii::$app->homeUrl."images/del.gif' /></a>";
            echo "
            <tr>
                <td>$i</td>
                <td>$purpose</td>
                <td>$bill_date</td>
                <td align='right'>$amount</td>
                <td>$delUrl</td>
            </tr>
            ";
            $i++;
        }
        echo "<tr><td colspan='3' align='right'><b>Total Conveyance Amount</b></td><td align='right'><b>".number_format($total, 2)."</b></td></tr>";
    }
    
    ?>
</table>
<br>
<hr class="hrline">
<div class="text-center">
    <?php 
    $submitUrl = Yii::$app->homeUrl."employee/claim/finalsubmitclaim?securekey=$menuid&claimid=$claimid&reqid=$reqid";
    ?>
    <p style="color:red;">Note : If you submit the claim you cannot edit again.</p>
    <a href="<?=$submitUrl?>" class="btn btn-success btn-sm">Submit</a>
</div>
<script>
$(document).ready(function(){
    $("#food_billdate, #j_start_date, #j_end_date, #c_start_date, #c_end_date, #halt_start_date, #halt_end_date").css('cursor','pointer');
    $('#c_start_date').datepicker({
        autoclose:true,
        format: "dd-mm-yyyy",
        orientation: "top-left",
        startDate: '<?=date('d-m-Y', strtotime($claimHeader['start_date']))?>',
        endDate: '<?=date('d-m-Y', strtotime($claimHeader['end_date']))?>',
    }).on('changeDate', function (selected){
        var minDate = new Date(selected.date.valueOf());
        $('#c_end_date').datepicker('setStartDate', minDate);
        $("#c_end_date").val($(this).val());
    });
    $('#c_end_date').datepicker({
        autoclose:true,
        format: "dd-mm-yyyy",
        orientation: "top-left",
        startDate: '<?=date('d-m-Y', strtotime($claimHeader['start_date']))?>',
        endDate: '<?=date('d-m-Y', strtotime($claimHeader['end_date']))?>',
    }).on('changeDate', function (selected){
//        var minDate = new Date(selected.date.valueOf());
//        $('#c_end_date').datepicker('setStartDate', minDate);
//        $("#c_end_date").val($(this).val());
    });
    $('#j_start_date').datepicker({
        autoclose:true,
        //defaultViewDate: '<?=date('d-m-Y', strtotime($claimHeader['start_date']))?>',
        format: "dd-mm-yyyy",
        orientation: "top-left",
        startDate: '<?=date('d-m-Y', strtotime($claimHeader['start_date']))?>',
        endDate: '<?=date('d-m-Y', strtotime($claimHeader['end_date']))?>',
    }).on('changeDate', function (selected){
        var minDate = new Date(selected.date.valueOf());
        $('#j_end_date').datepicker('setStartDate', minDate);
        $("#j_end_date").val($(this).val());
    });
    $('#j_end_date').datepicker({
        autoclose:true,
        //defaultViewDate: '<?=date('d-m-Y', strtotime($claimHeader['start_date']))?>',
        format: "dd-mm-yyyy",
        orientation: "top-left",
        startDate: '<?=date('d-m-Y', strtotime($claimHeader['start_date']))?>',
        endDate: '<?=date('d-m-Y', strtotime($claimHeader['end_date']))?>',
    }).on('changeDate', function (selected){
//        var minDate = new Date(selected.date.valueOf());
//        $('#j_end_date').datepicker('setStartDate', minDate);
//        $("#j_end_date").val($(this).val());
    });
    $('#food_billdate').datepicker({
        autoclose:true,
        format: "dd-mm-yyyy",
        orientation: "top-left",
        startDate: '<?=date('d-m-Y', strtotime($claimHeader['start_date']))?>',
        endDate: '<?=date('d-m-Y', strtotime($claimHeader['end_date']))?>',
    }).on('changeDate', function (selected){
        
    });
    
    $('#halt_start_date').datepicker({
        autoclose:true,
        format: "dd-mm-yyyy",
        orientation: "top-left",
        startDate: '<?=date('d-m-Y', strtotime($claimHeader['start_date']))?>',
        endDate: '<?=date('d-m-Y', strtotime($claimHeader['end_date']))?>',
    }).on('changeDate', function (selected){
//        alert($(this).val());
        var minDate = new Date(selected.date.valueOf());
        $('#halt_end_date').datepicker('setStartDate', minDate);
        $("#halt_end_date").val($(this).val());
    });
        
    $("#halt_end_date").datepicker({
        autoclose:true,
        format: "dd-mm-yyyy",
        orientation: "top",
        endDate: '<?=date('d-m-Y', strtotime($claimHeader['end_date']))?>',
    }).on('changeDate', function(ev){
    {
        var fromdateval= $("#halt_start_date").val();
        if(fromdateval==''){
            $("#halt_end_date").val('');
            showError("Select Start Date First.");
            return false;
        }
    }
    });
});
</script>