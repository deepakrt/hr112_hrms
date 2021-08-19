<?php
$this->title = "IPD Bill Details";
use yii\widgets\ActiveForm;
?>
<h6><b>IPD Claim Header</b></h6>
<table class="table table-bordered">
    <tr>
        <td><b>Employee Code : </b><?=$claimDetail['employee_code']?></td>
        <td><b>Employee Name : </b><?=$claimDetail['name']?>, <?=$claimDetail['desg_name']?></td>
        <td><b>Department : </b><?=$claimDetail['dept_name']?></td>
    </tr>
    <tr>
        <td><b>Patient Type : </b><?=$claimDetail['member_name']?></td>
        <td><b>Date Of Admission : </b><?=date('d-M-Y', strtotime($claimDetail['date_of_admission']))?></td>
        <td><b>Date Of Discharge : </b><?=date('d-M-Y', strtotime($claimDetail['date_of_discharge']))?></td>
    </tr>
    <tr>
        <td colspan="3"><b>Admitted For : </b><?=$claimDetail['admitted_for']?></td>
    </tr>
    <tr>
        <td><b>Claim Type : </b><?=$claimDetail['claim_type']?></td>
        <td><b>Claimed Amount : </b><?=$claimDetail['total_claimed_amt']?></td>
        <td></td>
    </tr>
</table>
<hr>
<h6><b><u>Add Bill Details</u></b></h6>
<?php $form = ActiveForm::begin(['action'=>Yii::$app->homeUrl."employee/ipdreimbursement/saveipdbill?securekey=$menuid", 'options' => ['id'=>'ipdbillform', 'enctype' => 'multipart/form-data']]); ?>
<input type="hidden" value="<?=$ipd_id?>" name="IPD_Bill[ipd_id]" />
<input type="hidden" value="" name="IPD_Bill[ipd_bill_id]" />
<input type="hidden" value="<?=Yii::$app->utility->encryptString($claimDetail['date_of_admission'])?>" name="IPD_Bill[date_of_admission]" />
<input type="hidden" value="<?=Yii::$app->utility->encryptString($claimDetail['date_of_discharge'])?>" name="IPD_Bill[date_of_discharge]" />
<div class="row">
    <div class="col-sm-3">
        <label>Bill No.</label>
        <input type="text" id="bill_number" name="IPD_Bill[bill_number]" class="form-control form-control-sm" placeholder="Bill No." required="" />
    </div>
    <div class="col-sm-3">
        <label>Bill Date</label>
        <input type="text" id="bill_date" name="IPD_Bill[bill_date]" class="form-control form-control-sm" placeholder="Bill Date" readonly="" />
    </div>
    <div class="col-sm-3">
        <label>Bill Amount</label>
        <input type="text" id="bill_amt" name="IPD_Bill[bill_amt]" class="form-control form-control-sm" placeholder="Bill Amount" onkeypress="return allowOnlyNumber(event)" required="" />
    </div>
    <div class="col-sm-9">
        <label>Bill Issuer</label>
        <input type="text" id="issuer" name="IPD_Bill[issuer]" class="form-control form-control-sm" placeholder="Bill Issuer" required="" />
    </div>
    <div class="col-sm-3">
        <br>
        <button type="button" id="saveipdbill" class="btn btn-primary btn-sm">Save</button>
    </div>
</div>
<?php ActiveForm::end();?>
<br>
<hr>
<h6><b><u>IPD Bill Details</u></b></h6>
<table class="table table-bordered">
    <tr>
        <th>Sr. No.</th>
        <th>Bill No.</th>
        <th>Bill Date</th>
        <th>Bill Issuer</th>
        <th>Bill Amount</th>
        <th>Delete</th>
    </tr>
    <?php 
    $id = Yii::$app->utility->decryptString($ipd_id);
    $billDetails = Yii::$app->finance->fn_get_ipd_details(Yii::$app->user->identity->e_id, $id);
    if(!empty($billDetails)){
        $i=1;
        $gTotal = 0;
        foreach($billDetails as $bill){
            $amt = Yii::$app->utility->encryptString($bill['bill_amt']);
            $ipd_bill_id = Yii::$app->utility->encryptString($bill['id']);
            $url = Yii::$app->homeUrl."employee/ipdreimbursement/saveipdbill?securekey=$menuid&amt=$amt&ipd_bill_id=$ipd_bill_id&ipd_id=$ipd_id";
            $gTotal = $gTotal+$bill['bill_amt'];
            
        ?>
    <tr>
        <td><?=$i?></td>
        <td><?=$bill['bill_number']?></td>
        <td><?=date('d-m-Y', strtotime($bill['bill_date']))?></td>
        <td><?=$bill['issuer']?></td>
        <td><?=$bill['bill_amt']?></td>
        <td><a href="<?=$url?>" class="deleteipdbill"><img src="<?=Yii::$app->homeUrl?>images/del.gif" /></a></td>
    </tr>
            
    <?php   $i++; } ?>
    <tr>
        <td colspan="4" align="right"><b>Total Claimed Amount</b></td>
        <td><b><?=  number_format($gTotal, 2)?></b></td>
        <td></td>
    </tr>
    <?php }else{
    }
    ?>
</table>

<div class="text-center">
    <?php 
    $submitUrl = Yii::$app->homeUrl."employee/ipdreimbursement/submitipdclaim?securekey=$menuid&ipd_id=$ipd_id";
    echo $submitUrl = "<a href='$submitUrl' id='submitipdclaim' title='Submit IPD Claim' class='btn btn-success btn-sm'>Submit</a>";
    ?>
</div>

<script>
$(document).ready(function(){
    $("#bill_date").css('cursor','pointer');
    $('#bill_date').datepicker({
        autoclose:true,
        format: "dd-mm-yyyy",
        orientation: "top-left",
        startDate: '<?=date('d-m-Y', strtotime($claimDetail['date_of_admission']))?>',
        endDate: '<?=date('d-m-Y', strtotime($claimDetail['date_of_discharge']))?>',
    }).on('changeDate', function (selected){
        
    });
    $("#submitipdclaim").click(function(){
            if(confirm("Are you sure want to Submit IPD Claim? After submission you cannot add or update details.")){
                return true;
            }
            return false;
        });
});
</script>