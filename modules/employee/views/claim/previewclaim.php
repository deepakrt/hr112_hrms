<?php
$this->title = "Preview Tour Claim";
use yii\widgets\ActiveForm;
$g_SJ_Total = $g_SH_Total = $g_SC_Total = $g_SF_Total = $g_J_Total = $g_H_Total = $g_C_Total = $g_F_Total = 0.00;
//echo "<pre>";print_r($tourHeader); die;
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
        $JourneyTickets = JourneyTickets;
        foreach($journyDetails as $j){
            $start_date = date('d-m-Y H:i', strtotime($j['start_date']));
            $end_date = date('d-m-Y H:i', strtotime($j['end_date']));
            $jTotal=$jTotal+$j['amount'];
            
            $sanc_amount = $sanc_incentive = "0.00";
            
            if($tourHeader['status'] == 'Sanctioned'){ 
                if(!empty($j['sanc_amount'])){
                    $sanc_amount = $j['sanc_amount'];
                    $sanc_amount = number_format($sanc_amount, 2);
                }
                $SancJTotal = $SancJTotal+ $j['sanc_amount']; 
                 if(!empty($j['sanc_incentive'])){
                    $sanc_incentive = $j['sanc_incentive'];
                }
                $sanc_incentive = number_format($sanc_incentive, 2);
                $SancITotal = $SancITotal+ $j['sanc_incentive'];
            }
            echo "<tr>
                <td>$start_date</td>
                <td>$end_date</td>
                <td>".$j['place_from']."</td>
                <td>".$j['place_to']."</td>
                <td>".$j['t_class']."</td>
                <td>".$j['ticket']."</td>
                <td>".$j['sanc_ticket']."</td>
                <td align='right'>".$j['amount']."</td>
                <td>".$sanc_amount."</td>
                <td>".$sanc_incentive."</td>
            </tr>";
        }
        $g_J_Total = $jTotal = number_format($jTotal, 2);
        $g_SJ_Total = $SancJTotal = number_format($SancJTotal, 2);
        $SancITotal = number_format($SancITotal, 2);
        echo "<tr><td colspan='7' align='right'><b>Total</b></td><td align='right'><b>$jTotal</b></td><td><b>$SancJTotal</b></td><td><b>$SancITotal</b></td></tr>";
        
    }else{
        echo "<tr><td colspan='9' align='center'>No Journey Details Found.</td></tr>";
    }
    ?>
</table>
<hr class="hrline">
<h6><b>Halt Details</b></h6>
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
        $hTotal = $sTotal = $cTotal = 0;
 
        foreach($haltDetails as $h){
            $senc_comp = $sanc_charges = "0.00";
            if($tourHeader['status'] == 'Sanctioned'){ 
                if(!empty($h['sanc_charges'])){
                    $sanc_charges = $h['sanc_charges'];
                    $sanc_charges = number_format($sanc_charges, 2);
                }
            
                if(!empty($h['senc_comp'])){
                    $senc_comp = $h['senc_comp'];
                }
                $senc_comp = number_format($senc_comp, 2);
                $cTotal = $cTotal+$h['senc_comp'];
                $sTotal = $sTotal+$h['sanc_charges'];
            }
            
            $start_date = date('d-m-Y', strtotime($h['start_date']));
            $end_date = date('d-m-Y', strtotime($h['end_date']));
            $charges = $h['charges'];
            $hTotal = $hTotal+$charges;
            
        ?>
    <tr>
        <td><?=$start_date?></td>
        <td><?=$end_date?></td>
        <td><?=$h['city_name']?></td>
        <td><?=$h['stay']?></td>
        <td><?=$charges?></td>
        <td><?=$sanc_charges?></td>
        <td><?=$senc_comp?></td>
    </tr>
    <?php

        }
        $g_H_Total = $hTotal = number_format($hTotal, 2);
        $g_SH_Total = $sTotal = number_format($sTotal, 2);
        $cTotal = number_format($cTotal, 2);
        echo "<tr><td colspan='4' align='right'><b>Total</b></td><td align='right'><b>$hTotal</b></td><td><b>$sTotal</b></td><td><b>$cTotal</b></td></tr>";
    }else{
        echo "<tr><td colspan='7' align='center'>No Halt Details Found.</td></tr>";
    }
    ?>
</table>
<hr class="hrline">
<h6><b>Conveyance Details</b></h6><table class="table table-bordered">
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
        $cTotal = 0;
        $totalSanc= 0;
        foreach($convenDetails as $c){
            $start_date = date('d-m-Y H:i', strtotime($c['start_date']));
            $end_date = date('d-m-Y H:i', strtotime($c['end_date']));
            $sanc_amt = "0.00";
            if($tourHeader['status'] == 'Sanctioned'){ 
                if(!empty($c['sanctioned_amount'])){
                    $sanc_amt = $c['sanctioned_amount'];
                }
                $totalSanc = $totalSanc+$c['sanctioned_amount'];
            }
            
            $cTotal = $cTotal+$c['amount'];
            
        ?>
    <tr>
        <td><?=$start_date?></td>
        <td><?=$end_date?></td>
        <td><?=$c['place_from']?></td>
        <td><?=$c['place_to']?></td>
        <td><?=$c['mode']?></td>
        <td><?=$c['distance']?></td>
        <td align='right'><?=$c['amount']?></td>
        <td align='right'><?=$sanc_amt?></td>
    </tr>
    <?php
        }
        $g_SC_Total = $totalSanc = number_format($totalSanc, 2);
        $sanc_amt = number_format($sanc_amt, 2);
        $g_C_Total = $cTotal = number_format($cTotal, 2);
        echo "<tr><td colspan='6' align='right'><b>Total</b></td><td align='right'><b>$cTotal</b></td><td align='right'><b>$totalSanc</b></td></tr>";
    }else{
        echo "<tr><td colspan='8' align='center'>No Conveyance Details Found.</td></tr>";
    }
    ?>
</table>
<hr class="hrline">
<h6><b>Food Details</b></h6><table class="table table-bordered">
    <tr>
        <th>Purpose</th>
        <th>Bill Date</th>
        <th>Amount</th>
        <th>Sanc. Amount</th>
    </tr>
    <?php 
    if(!empty($foodDetails)){
        $sancTotal = $fTotal=0;
        foreach($foodDetails as $f){
            $sanc_amt="0.00";
            if($tourHeader['status'] == 'Sanctioned'){ 
                if(!empty($f['sanctioned_amount'])){
                    $sanc_amt = $f['sanctioned_amount'];
                    $sanc_amt = number_format($sanc_amt, 2);
                    $sancTotal = $sancTotal+$f['sanctioned_amount'];
                }
            }
            
            $fTotal = $fTotal+$f['amount'];
    ?>
    <tr>
        <td><?=$f['purpose']?></td>
        <td><?=date('d-m-Y', strtotime($f['bill_date']))?></td>
        <td><?=$f['amount']?></td>
        <td><?=$sanc_amt?></td>
    </tr>
    <?php
        }
        $fTotal;
        $sancTotal;
        $g_F_Total = $fTotal = number_format($fTotal, 2);
        $g_SF_Total = $sancTotal = number_format($sancTotal, 2);
        echo "<tr><td colspan='2' align='right'><b>Total</b></td><td ><b>$fTotal</b></td><td ><b>$sancTotal</b></td></tr>";
    }else{
        echo "<tr><td colspan='4' align='center'>No Food Details Found.</td></tr>";
    }
    ?>
</table>
<?php 

$claimedT=  str_replace(",", "", $g_F_Total)+str_replace(",", "", $g_C_Total)+str_replace(",", "", $g_H_Total)+str_replace(",", "", $g_J_Total);
$claimedT = number_format($claimedT, 2);
$sacnedT="0.00";
if($tourHeader['status'] == 'Sanctioned'){ 
    $sacnedT=  str_replace(",", "", $g_SF_Total)+str_replace(",", "", $g_SC_Total)+str_replace(",", "", $g_SH_Total)+str_replace(",", "", $g_SJ_Total);
    $sacnedT = number_format($sacnedT, 2);
}

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
if($tourHeader['status'] == 'Sanctioned'){ 
if($reqiotionInfo['sanctioned_adv_amount'] > 0){
    $sacnedT = str_replace(",", "", $sacnedT);
    $sacnedT = $sacnedT - $reqiotionInfo['sanctioned_adv_amount'];
    $sacnedT = number_format($sacnedT, 2);
    echo "<p class='notehead'>Advanced of Rs. ".$reqiotionInfo['sanctioned_adv_amount']." has already sanctioned. Total Claimed Sanctioned Amount is <b></>$sacnedT</p>";
}
}
$url = Yii::$app->homeUrl."employee/claim/tourclaims?securekey=$menuid";
?>
<div class="text-center">
    <a href="<?=$url?>" class="btn btn-danger btn-sm">Back</a>
</div>
