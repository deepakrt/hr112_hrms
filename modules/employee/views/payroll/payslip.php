<?php
$this->title="Pay Slip";
?>
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>Month</th>
            <th>Basic/PiPb</th>
            <th>DA</th>
            <th>Total Allowances</th>
            <th>Total Deductions</th>
            <th>Total Recoveries</th>
            <th>Net Amount</th>
        </tr>
    </thead>
    <tbody>
    <?php 
    //echo "<pre>";print_r($salaryInfo);die;
    if(!empty($salaryInfo)){
        foreach($salaryInfo as $inf){
            $code = Yii::$app->utility->encryptString(Yii::$app->user->identity->e_id);
            $salMonth = Yii::$app->utility->encryptString($inf['salMonth']);
            $salYear = Yii::$app->utility->encryptString($inf['salYear']);
            
            $viewUrl = Yii::$app->HomeUrl."employee/payroll/viewpayslip?securekey=$menuid&key1=$salMonth&key2=$salYear";
           
            $date = $inf['salYear']."-".$inf['salMonth']."-01";
            $date = date('M-Y', strtotime($date));
            $totalAllowance = $inf['allowance_da']+$inf['allowance_da_arrear']+$inf['allowance_hra']+$inf['allowance_tabasic']+$inf['allowance_ta']+$inf['allowance_ta_arrear']+$inf['allowance_canteen'];
            
            $totalDeduct = $inf['ded_empyee_pf_amt']+$inf['ded_pf_on_arrear']+$inf['ded_incomeTax']+$inf['ded_lfee']+$inf['ded_club']+$inf['ded_GSLI']+$inf['ded_BenevolentFund'];
        ?>
        <tr>
            <td><a href="<?=$viewUrl?>" target="_blank" title="View / Download Pay Slip" class="linkcolor"><?=$date?></a></td>
            <td><?=$inf['basic_cons_pay']?></td>
            <td><?=$inf['allowance_da']?></td>
            <td><?=$totalAllowance?></td>
            <td><?=$totalDeduct?></td>
            <td>0</td>
            <td><?=$inf['NetSal']?></td>
        </tr>
        <?php }
    }else{
        echo "<tr><td colspan='7' align='center'><b>No Record Found</b></td></tr>";
    }
    ?>
    </tbody>
    <tfoot>
        <tr>
            <th>Month</th>
            <th>Basic/PiPb</th>
            <th>DA</th>
            <th>Total Allowances</th>
            <th>Total Deductions</th>
            <th>Total Recoveries</th>
            <th>Net Amount</th>
        </tr>
    </tfoot>
</table>