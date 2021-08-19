<?php
$this->title = "View Details of Tour Claim";
use yii\widgets\ActiveForm;
$g_SJ_Total = $g_SH_Total = $g_SC_Total = $g_SF_Total = $g_J_Total = $g_H_Total = $g_C_Total = $g_F_Total = 0.00;

?>
<div class="text-right">
    <p><b>Claim Dated : </b> <?=date('d-m-Y', strtotime($tourHeader['claim_date']))?></p>
    <p><b>Status : </b> <?=$tourHeader['status']?></p>
</div>
<h6><b>Claim Header</b></h6>
<table class="table table-bordered">
    <tr>
        <td><b>Employee Code :</b> <?=$tourHeader['employee_code']?></td>
        <td><b>Employee Name :</b> <?=$tourHeader['fullname']?>, <?=$tourHeader['desg_name']?></td>
        <td><b>Department :</b> <?=$tourHeader['dept_name']?></td>
    </tr>
    <tr>
        <td><b>Project Name :</b> <?=$tourHeader['project_name']?></td>
        <td><b>Location :</b> <?=$tourHeader['city_name']?></td>
        <td><b>Purpose :</b> <?=$tourHeader['purpose']?></td>
    </tr>
    <tr>
        <td><b>Tour Start Date Time :</b> <?=date('d-m-Y H:i', strtotime($tourHeader['start_date']))?></td>
        <td><b>Tour End Date Time :</b> <?=date('d-m-Y H:i', strtotime($tourHeader['end_date']))?></td>
        <td></td>
    </tr>
    <tr>
        <td><b>Advanced Amount :</b> <?=$reqiotionInfo['advance_amount']?></td>
        <td><b>Sanctioned Advanced:</b> <?=$reqiotionInfo['sanctioned_adv_amount']?></td>
        <td><b>Total Claimed :</b> <?=$tourHeader['claimed_amount']?></td>
    </tr>
</table>
<hr class="hrline">
<?php 
$url = Yii::$app->homeUrl."finance/requisitiontour/updatejourneydetail?securekey=$menuid";
$form = ActiveForm::begin(['action'=>$url, 'options'=>['id'=>'journeysanc', ]]); ?>
<input type="hidden" name="claimid" value='<?=$claimid?>' />
<input type="hidden" name="reqid" value='<?=$reqid?>' />
<input type="hidden" name="e_id" value='<?=$e_id?>' />

<h6><b>Journey Details</b></h6>
<table class="table table-bordered">
    <tr>
        <th>Start Date</th>
        <th>End Date</th>
        <th>From</th>
        <th>To</th>
        <th>TClass</th>
        <th>Ticket</th>
        <th>Sanc. Ticket</th>
        <th>Amount</th>
        <th>Sanc. Amount</th>
        <th>Incentive (If any)</th>
    </tr>
    <?php 
    if(!empty($journyDetails)){
        $jTotal = $SancJTotal= $SancITotal =0;
        $i=0;
        $JourneyTickets = JourneyTickets;
        foreach($journyDetails as $j){
            $start_date = date('d-m-Y H:i', strtotime($j['start_date']));
            $end_date = date('d-m-Y H:i', strtotime($j['end_date']));
            $jTotal=$jTotal+$j['amount'];
            $sanc_amount = "";
            if(!empty($j['sanc_amount'])){
                $sanc_amount = $j['sanc_amount'];
                $sanc_amount = number_format($sanc_amount, 2);
            }
            $SancJTotal = $SancJTotal+ $j['sanc_amount'];
            
            $sanc_incentive = 0;
            if(!empty($j['sanc_incentive'])){
                $sanc_incentive = $j['sanc_incentive'];
            }
            $sanc_incentive = number_format($sanc_incentive, 2);
            $SancITotal = $SancITotal+ $j['sanc_incentive'];
            
            
            $jTicket = "";
            foreach($JourneyTickets as $JourneyTicket){
                $self = "";
                if($j['sanc_ticket']== $JourneyTicket){
                    $self = "selected=''";
                }
                $jTicket = $jTicket."<option $self value='".Yii::$app->utility->encryptString($JourneyTicket)."'>$JourneyTicket</option>";
            }
            echo "<tr>
                <td>$start_date</td>
                <td>$end_date</td>
                <td>".$j['place_from']."</td>
                <td>".$j['place_to']."</td>
                <td>".$j['t_class']."</td>
                <td>".$j['ticket']."</td>
                <td><select name='Details[$i][sanc_ticket]' required>
                    <option value=''>Select</option>
                    $jTicket
                </select></td>
                <td align='right'>".$j['amount']."<input type='hidden' name='Details[$i][j_id]' value='".Yii::$app->utility->encryptString($j['j_id'])."' readonly='' /><input type='hidden' name='Details[$i][amount]' value='".Yii::$app->utility->encryptString($j['amount'])."' readonly='' /></td>
                <td><input type='text' name='Details[$i][sanc_amt]' required='' maxlength='11' placeholder='Sanc Amt' onkeypress='return allowOnlyNumber(event)' class='form-control form-control-sm' value='$sanc_amount' /></td>
                <td><input type='text' name='Details[$i][incentive]' required='' maxlength='11' onkeypress='return allowOnlyNumber(event)' value='$sanc_incentive' class='form-control form-control-sm' /></td>
            </tr>";
            $i++;
        }
        $g_J_Total = $jTotal = number_format($jTotal, 2);
        $g_SJ_Total = $SancJTotal = number_format($SancJTotal, 2);
        $SancITotal = number_format($SancITotal, 2);
        
        echo "<tr><td colspan='10' align='right'><button type='submit' class='btn btn-success btn-sm'>Update Journey</button></td></tr>";
        echo "<tr><td colspan='7' align='right'><b>Total</b></td><td align='right'><b>$jTotal</b></td><td><b>$SancJTotal</b></td><td><b>$SancITotal</b></td></tr>";
        
    }else{
        echo "<tr><td colspan='9' align='center'>No Journey Details Found.</td></tr>";
    }
    ?>
</table>
<?php ActiveForm::end(); ?>
<hr class="hrline">
<h6><b>Halt Details</b></h6>
<?php
//echo "<pre>";print_r($haltDetails);
$url = Yii::$app->homeUrl."finance/requisitiontour/updatehaltdetail?securekey=$menuid";
$form = ActiveForm::begin(['action'=>$url, 'options'=>['id'=>'haltsanc', ]]); ?>
<input type="hidden" name="claimid" value='<?=$claimid?>' />
<input type="hidden" name="reqid" value='<?=$reqid?>' />
<input type="hidden" name="e_id" value='<?=$e_id?>' />
<table class="table table-bordered">
    <tr>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Location</th>
        <th>Stay</th>
        <th>Charges</th>
        <th>Sanc. Amount</th>
        <th>Sanc. Comp (If any)</th>
    </tr>
    <?php 
    if(!empty($haltDetails)){
        $i=0;
        $hTotal = $sTotal = $cTotal = 0;
        foreach($haltDetails as $h){
            $sanc_charges = "";
            if(!empty($h['sanc_charges'])){
                $sanc_charges = $h['sanc_charges'];
                $sanc_charges = number_format($sanc_charges, 2);
            }
            
            $senc_comp = 0;
            if(!empty($h['senc_comp'])){
                $senc_comp = $h['senc_comp'];
            }
            $senc_comp = number_format($senc_comp, 2);
            $cTotal = $cTotal+$h['senc_comp'];
            
            $sTotal = $sTotal+$h['sanc_charges'];
            
            $start_date = date('d-m-Y', strtotime($h['start_date']));
            $end_date = date('d-m-Y', strtotime($h['end_date']));
            $charges = $h['charges'];
            $hTotal = $hTotal+$charges;
            
        ?>
    <tr>
        <td> <input type="hidden" name='Halt[<?=$i?>][charges]' value="<?=Yii::$app->utility->encryptString($charges)?>" />
         <input type="hidden" name='Halt[<?=$i?>][th_id]' value="<?=Yii::$app->utility->encryptString($h['th_id'])?>" />
            <?=$start_date?></td>
        <td><?=$end_date?></td>
        <td><?=$h['city_name']?></td>
        <td><?=$h['stay']?></td>
        <td><?=$charges?></td>
        <td><input type='text' name='Halt[<?=$i?>][sanc_amt]' required='' maxlength='11' placeholder='Sanc Amt' onkeypress='return allowOnlyNumber(event)' class='form-control form-control-sm' value='<?=$sanc_charges?>'  /></td>
        <td><input type='text' name='Halt[<?=$i?>][senc_comp]' required='' maxlength='11' placeholder='Sanc Amt' onkeypress='return allowOnlyNumber(event)' class='form-control form-control-sm' value='<?=$senc_comp?>' /></td>
    </tr>
    <?php
    $i++;
        }
        $g_H_Total = $hTotal = number_format($hTotal, 2);
        $g_SH_Total = $sTotal = number_format($sTotal, 2);
        $cTotal = number_format($cTotal, 2);
        echo "<tr><td colspan='7' align='right'><button type='submit' class='btn btn-success btn-sm'>Update Halt Details</button></td></tr>";
         echo "<tr><td colspan='4' align='right'><b>Total</b></td><td align='right'><b>$hTotal</b></td><td><b>$sTotal</b></td><td><b>$cTotal</b></td></tr>";
    }else{
        echo "<tr><td colspan='7' align='center'>No Halt Details Found.</td></tr>";
    }
    ?>
</table>
<?php ActiveForm::end(); ?>
<hr class="hrline">
<h6><b>Conveyance Details</b></h6>
<?php
//echo "<pre>";print_r($convenDetails);
$url = Yii::$app->homeUrl."finance/requisitiontour/updateconveyance?securekey=$menuid";
$form = ActiveForm::begin(['action'=>$url, 'options'=>['id'=>'convysanc', ]]); ?>
<input type="hidden" name="claimid" value='<?=$claimid?>' />
<input type="hidden" name="reqid" value='<?=$reqid?>' />
<input type="hidden" name="e_id" value='<?=$e_id?>' />
<table class="table table-bordered">
    <tr>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Place From</th>
        <th>Place To</th>
        <th>Mode</th>
        <th>Distance (Kms)</th>
        <th>Amount</th>
        <th>Sanc. Amount</th>
    </tr>
    <?php 
    if(!empty($convenDetails)){
        $i=0;
        $cTotal = 0;
        $totalSanc= 0;
        foreach($convenDetails as $c){
            $start_date = date('d-m-Y H:i', strtotime($c['start_date']));
            $end_date = date('d-m-Y H:i', strtotime($c['end_date']));
            $sanc_amt = "";
            if(!empty($c['sanctioned_amount'])){
                $sanc_amt = $c['sanctioned_amount'];
                
            }
            $totalSanc = $totalSanc+$c['sanctioned_amount'];
            $cTotal = $cTotal+$c['amount'];
            
        ?>
    <tr>
        <td>
            <input type="hidden" name='Conveyance[<?=$i?>][amount]' value="<?=Yii::$app->utility->encryptString($c['amount'])?>" />
            <input type="hidden" name='Conveyance[<?=$i?>][tc_id]' value="<?=Yii::$app->utility->encryptString($c['tc_id'])?>" />
            <?=$start_date?></td>
        <td><?=$end_date?></td>
        <td><?=$c['place_from']?></td>
        <td><?=$c['place_to']?></td>
        <td><?=$c['mode']?></td>
        <td><?=$c['distance']?></td>
        <td align='right'><?=$c['amount']?></td>
        <td><input type='text' name='Conveyance[<?=$i?>][sanctioned_amount]' required='' maxlength='11' placeholder='Sanc Amt' onkeypress='return allowOnlyNumber(event)' class='form-control form-control-sm' value='<?=$sanc_amt?>'  /></td>
    </tr>
    <?php
        $i++;
        }
        $g_SC_Total = $totalSanc = number_format($totalSanc, 2);
        if(!empty($sanc_amt)){
            $sanc_amt = number_format($sanc_amt, 2);
        }
        
        $g_C_Total = $cTotal = number_format($cTotal, 2);
        echo "<tr><td colspan='8' align='right'><button type='submit' class='btn btn-success btn-sm'>Update Conveyance Details</button></td></tr>";
        echo "<tr><td colspan='6' align='right'><b>Total</b></td><td align='right'><b>$cTotal</b></td><td align='right'><b>$totalSanc</b></td></tr>";
    }else{
        echo "<tr><td colspan='8' align='center'>No Conveyance Details Found.</td></tr>";
    }
    ?>
</table>
<?php ActiveForm::end(); ?>
<hr class="hrline">
<h6><b>Food Details</b></h6>
<?php
//echo "<pre>";print_r($foodDetails);
$url = Yii::$app->homeUrl."finance/requisitiontour/updatefooddetails?securekey=$menuid";
$form = ActiveForm::begin(['action'=>$url, 'options'=>['id'=>'foodsanc', ]]); ?>
<input type="hidden" name="claimid" value='<?=$claimid?>' />
<input type="hidden" name="reqid" value='<?=$reqid?>' />
<input type="hidden" name="e_id" value='<?=$e_id?>' />
<table class="table table-bordered">
    <tr>
        <th>Purpose</th>
        <th>Bill Date</th>
        <th>Amount</th>
        <th>Sanc. Amount</th>
    </tr>
    <?php 
    if(!empty($foodDetails)){
        $i=0;
        $sancTotal = $fTotal=0;
        foreach($foodDetails as $f){
            $sanc_amt="";
            if(!empty($f['sanctioned_amount'])){
                $sanc_amt = $f['sanctioned_amount'];
                $sanc_amt = number_format($sanc_amt, 2);
                
                $sancTotal = $sancTotal+$f['sanctioned_amount'];
            }
            $fTotal = $fTotal+$f['amount'];
    ?>
    <tr>
        <td><?=$f['purpose']?></td>
        <td><?=date('d-m-Y', strtotime($f['bill_date']))?></td>
        <td>
            <input type="hidden" name='Food[<?=$i?>][amount]' value="<?=Yii::$app->utility->encryptString($f['amount'])?>" />
            <input type="hidden" name='Food[<?=$i?>][tf_id]' value="<?=Yii::$app->utility->encryptString($f['tf_id'])?>" />
            <?=$f['amount']?></td>
        <td><input type='text' name='Food[<?=$i?>][sanctioned_amount]' required='' maxlength='11' placeholder='Sanc Amt' onkeypress='return allowOnlyNumber(event)' class='form-control form-control-sm col-sm-6' value='<?=$sanc_amt?>'  /></td>
    </tr>
    <?php
        $i++;
        }
        $fTotal;
        $sancTotal;
        $g_F_Total = $fTotal = number_format($fTotal, 2);
        $g_SF_Total = $sancTotal = number_format($sancTotal, 2);
         echo "<tr><td colspan='4' align='right'><button type='submit' class='btn btn-success btn-sm'>Update Food Details</button></td></tr>";
        echo "<tr><td colspan='2' align='right'><b>Total</b></td><td ><b>$fTotal</b></td><td ><b>$sancTotal</b></td></tr>";
    }else{
        echo "<tr><td colspan='4' align='center'>No Food Details Found.</td></tr>";
    }
    ?>
</table>
<?php ActiveForm::end(); 
$url = Yii::$app->homeUrl."finance/requisitiontour/tourclaims?securekey=$menuid";
$claimedT=  str_replace(",", "", $g_F_Total)+str_replace(",", "", $g_C_Total)+str_replace(",", "", $g_H_Total)+str_replace(",", "", $g_J_Total);
$claimedT = number_format($claimedT, 2);

$sacnedT=  str_replace(",", "", $g_SF_Total)+str_replace(",", "", $g_SC_Total)+str_replace(",", "", $g_SH_Total)+str_replace(",", "", $g_SJ_Total);
$sacnedT = number_format($sacnedT, 2);
?>
<hr class="hrline">
<h6><b>Summary</b></h6>
<div class="row">
    <div class="col-sm-6">
   <table class="table table-bordered">
    <tr>
        <th></th>
        <th>Claimed Amount</th>
        <th>Sanctioned Amount</th>
    </tr>
<!--    <tr>
        <td>Advanced Amount</td>
        <td><?=$reqiotionInfo['advance_amount']?></td>
        <td><?=$reqiotionInfo['sanctioned_adv_amount']?></td>
    </tr>-->
    <tr>
        <td>Journey</td>
        <td><?=$g_J_Total?></td>
        <td><?=$g_SJ_Total?></td>
    </tr>
    <tr>
        <td>Halt</td>
        <td><?=$g_H_Total?></td>
        <td><?=$g_SH_Total?></td>
    </tr>
    <tr>
        <td>Conveyance </td>
        <td><?=$g_C_Total?></td>
        <td><?=$g_SC_Total?></td>
    </tr>
    <tr>
        <td>Food</td>
        <td><?=$g_F_Total?></td>
        <td><?=$g_SF_Total?></td>
    </tr>
    <tr>
        <th>Total</th>
        <th><?=$claimedT?></th>
        <th><?=$sacnedT?></th>
    </tr>
</table> 
</div>
</div>
<?php
if($reqiotionInfo['sanctioned_adv_amount'] > 0){
    $sacnedT = str_replace(",", "", $sacnedT);
    $sacnedT = $sacnedT - $reqiotionInfo['sanctioned_adv_amount'];
    $sacnedT = number_format($sacnedT, 2);
    echo "<p class='notehead'>Advanced of Rs. ".$reqiotionInfo['sanctioned_adv_amount']." has already sanctioned. Total Claimed Sanctioned Amount is <b></>$sacnedT</p>";
}
$sacnedT = str_replace(",", '', $sacnedT);
$url = Yii::$app->homeUrl."finance/requisitiontour/finalsubmission?securekey=$menuid";
$form = ActiveForm::begin(['action'=>$url, 'options'=>['id'=>'foodsanc', ]]); ?>
<input type="hidden" name="Final[claimid]" value='<?=$claimid?>' />
<input type="hidden" name="Final[reqid]" value='<?=$reqid?>' />
<input type="hidden" name="Final[e_id]" value='<?=$e_id?>' />
<input type="hidden" name="Final[e_id]" value='<?=$e_id?>' />
<input type="hidden" name="Final[total_sanc]" value='<?=Yii::$app->utility->encryptString($sacnedT)?>' />
<?php 
if($tourHeader['status'] == 'Submitted'){
    echo '<button type="submit" name="Final[sanction]" value="1" class="btn btn-success btn-sm sl">Sanction</button>
<button type="submit" name="Final[process]" value="2" class="btn btn-primary btn-sm sl">In-Process</button>';
}elseif($tourHeader['status'] == 'In-Process'){
    echo '<button type="submit" name="Final[sanction]" value="1" class="btn btn-success btn-sm sl">Sanction</button>';
}
?>
<button type="submit" name="Final[revoke]" value="3" class="btn btn-warning btn-sm sl">Revoke</button>
<button type="submit" name="Final[reject]" value="4" class="btn btn-danger btn-sm sl">Reject</button>
<a href="<?=$url?>" class="btn btn-light btn-sm">Back</a>
</div>
<?php ActiveForm::end(); ?>