<?php 
use app\models\EfileDakDispatchAddress;
$fy = Yii::$app->finance->getCurrentFY();
$amt = Yii::$app->fts_utility->efile_get_dispatch_amount($fy, NULL);
?>
<input type="hidden" id="menuid" value="<?=$menuid?>" />
<h5 class="text-danger"  style="text-align:center"><b>डाक डिस्पैच / Dak Dispatch</b></h5>
<?php if(!empty($amt)){ 
    $total = $amt['first_quarter_amt']+$amt['second_quarter_amt']+$amt['third_quarter_amt']+$amt['forth_quarter_amt'];
    ?>
<table class="table table-bordered">
    <tr>
        <th>Financial Year</th>
        <th>Credited Amount</th>
        <th>Debited Amount</th>
        <th>Balance</th>
    </tr>
    <tr>
        <td><?=$amt['financial_year']?></td>
        <td><a href="javascript:void(0)" class="display_info" data-type="C" data-msg="Details of Credited in <?=$amt['financial_year']?>">Rs. <?=number_format($total, 2)?></a></td>
        <td><a href="javascript:void(0)" class="display_info" data-type="D" data-msg="Details of Debited in <?=$amt['financial_year']?>">Rs. <?=number_format($amt['debited_amount'], 2)?></a></td>
        <td>Rs. <?=number_format(($total-$amt['debited_amount']), 2)?></td>
    </tr>
</table>
<div id="credit_html" style="display: none">
<table class="table table-bordered">
    <tr><th>Credited On</th><th>Credited Amount</th></tr>
    <?php 
    if($amt['first_quarter_amt'] > 0){
        $date = date('d-M-Y', strtotime($amt['first_entry_date']));
        $amount = number_format($amt['first_quarter_amt']);
        echo "<tr><td>Rs. $amount/-</td><td>$date</td></tr>";
    }
    if($amt['second_quarter_amt'] > 0){
        $date = date('d-M-Y', strtotime($amt['second_entry_date']));
        $amount = number_format($amt['second_quarter_amt']);
        echo "<tr><td>Rs. $amount/-</td><td>$date</td></tr>";
    }
    if($amt['third_quarter_amt'] > 0){
        $date = date('d-M-Y', strtotime($amt['third_entry_date']));
        $amount = number_format($amt['third_quarter_amt']);
        echo "<tr><td>Rs. $amount/-</td><td>$date</td></tr>";
    }
    if($amt['forth_quarter_amt'] > 0){
        $date = date('d-M-Y', strtotime($amt['forth_entry_date']));
        $amount = number_format($amt['forth_quarter_amt']);
        echo "<tr><td>Rs. $amount/-</td><td>$date</td></tr>";
    }
    ?>
</table>
</div>
<hr class="hrline">
<?php } ?>
<hr>
<div class="row">
    <table id="viewdisdata" class="display" style="width:100%">
        <thead>
            <tr class="text-center">
                <th>Sr. No.</th>
                <th>Dispatch Number</th>
                <th>Dispatch Date</th>
                <th>Dispatched From</th>
                <th>Amount</th>
                <th>Action</th>
            </tr>
        </thead>
          <tbody>
                <?php 
                
                if(!empty($dak_dispatch))
                {
                    $i =1;
                    foreach($dak_dispatch as $k=>$fd)
                    { 
                        $disp_id =Yii::$app->utility->encryptString($fd['disp_id']);
                        $rec_date=date("d-M-Y",strtotime($fd['disp_date']));
                        $url=Yii::$app->homeUrl."efile/dakmanagement/viewdisdetail?securekey=".$menuid."&disp_id=".$disp_id;
                        $verify_link ="<a href='$url' class='btn btn-sm btn-danger btn-xs'>View</a>";
                        $paymentUpdate = "";
                        if($fd['mode_of_rec'] == 'ई-मेल / E-mail' OR $fd['mode_of_rec'] == 'हस्तगत / By Hand'){
                               $amount = "-"; 
                        }else{
 							if(empty($fd['postal_date'])){
								$paymentUpdate = "style='color:red;font-weight:bold;'";
							}else{
 								$amount = "Rs. ".number_format($fd['total_amt'], 2);
							}
						 }  ?>
                <tr>
                    <td class="text-center" <?=$paymentUpdate?>><?=$i?></td>
                    <td><?=ucwords($fd['disp_number'])?></td>
                    <td><?=ucwords($rec_date)?></td>
                    <td><?=ucwords($fd['Dispatched_from'])?></td>
                    <td class="text-center"><?=$amount?></td>
                    <td class="text-center"><?=$verify_link?></td>
                </tr>	
                <?php $i++;	
                    }
                }
                ?>
            </tbody>
    </table>
 </div>
<!-- Modal -->
<div class="modal fade" id="fy_details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fy_details_title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="fy_details_html"></div>
            </div>
        </div>
    </div>
</div>