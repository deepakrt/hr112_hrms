<?php
$this->title="Medical Claim [IPD] ";
//if(Yii::$app->user->identity->e_id == '341814'){
//echo Yii::$app->user->identity->e_id;
$draftClaims = Yii::$app->finance->fn_get_ipd_claims(NULL, Yii::$app->user->identity->e_id, "Draft,Revoked");
$allClaims = Yii::$app->finance->fn_get_ipd_claims(NULL, Yii::$app->user->identity->e_id, "Submitted,In-Process,Sanctioned,Rejected");
?>
<input type="hidden" id="menuid" value="<?=$menuid?>" readonly="" />
<style>
    .card-header {
        padding: 5px;; 
	background-color: #FBE7C3;
	border-bottom: 1px solid #FBE7C3;
    }
    .fn_accordian {
	border: none;
	background: none;
	font-size: 14px;
	font-weight: bold;
	width: 100%;
	text-align: left;
	cursor: pointer;
    }
</style>
<div id="accordion">
    <div class="card">
        <div class="card-header" id="headingOne">
          <h5 class="mb-0">
            <button class="fn_accordian" data-toggle="collapse" data-target="#fnyrs" aria-expanded="true" aria-controls="fnyrs">
              &rsaquo; Select Financial Year
            </button>
          </h5>
        </div>

        <div id="fnyrs" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-3">
                        <label>Select Financial Year</label>
                    </div>
                    <div class="col-sm-4">
                        <select class="form-control form-control-sm" id="ipdFnYr">
                            <?php 
                            if(!empty($fnYears)){
                                foreach($fnYears as $fnYear){
                                    $id = base64_encode($fnYear);
                                    if($selectfnyr == $id){
                                        echo "<option value='$id' $selected>".$fnYear."</option>";
                                    }else{
                                        echo "<option value='$id'>".$fnYear."</option>";
                                    }
                                }
                            }
                            ?>
                        </select>  
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6" style="margin: 8px 0px;">
            <a href="javascript:void(0)" class="linkcolor" data-toggle="modal" data-target=".fysummary" style="font-size: 12px;">Employee Insurance Details</a>
        </div>
        <div class="col-sm-6 text-right" style="margin: 8px 0px;">
            <a href="<?=Yii::$app->homeUrl?>employee/ipdreimbursement/applynewclaim?securekey=<?=$menuid?>" class="linkcolor" title="Start New Medical Claim" >Apply For New IPD Claim</a>
        </div>
    </div>
    <?php 
    if(!empty($draftClaims)){
    ?>
    <div class="card">
        <div class="card-header" id="headingTwo">
            <h5 class="mb-0">
              <button class="fn_accordian collapsed " data-toggle="collapse" data-target="#draftipd" aria-expanded="false" aria-controls="entitle"> &rsaquo; Draft / Revoked IPD Claims </button>
            </h5>
        </div>
        <div id="draftipd" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordion">
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Claimed Date</th>
                        <th>Patient Type</th>
                        <th>Date of Admission</th>
                        <th>Date of Discharge</th>
                        <th>Total Claimed</th>
                        <th>Status</th>
                        <th>Edit</th>
                        <th>Delete</th>
                        <th>Submit</th>
                    </tr>
                    <?php 
                    if(!empty($draftClaims)){
                        foreach($draftClaims as $claim){
                            $ipd_id = Yii::$app->utility->encryptString($claim['ipd_id']); 
                            $editUrl = Yii::$app->homeUrl."employee/ipdreimbursement/ipdbilldetails?securekey=$menuid&ipd_id=$ipd_id";
                            $editUrl = "<a href='$editUrl' title='Edit Claim Details'><img src='".Yii::$app->homeUrl."images/edit.gif' /></a>";
                            $deleteUrl = Yii::$app->homeUrl."employee/ipdreimbursement/deleteipdclaim?securekey=$menuid&ipd_id=$ipd_id";
                            $deleteUrl = "<a href='$deleteUrl' id='deleteipdclaim' title='Delete IPD Claim Details'><img src='".Yii::$app->homeUrl."images/del.gif' /></a>";
                            $submitUrl = Yii::$app->homeUrl."employee/ipdreimbursement/submitipdclaim?securekey=$menuid&ipd_id=$ipd_id";
                            $submitUrl = "<a href='$submitUrl' id='submitipdclaim' title='Submit IPD Claim' class='linkcolor'>Submit</a>";
                    ?>
                    <tr>
                        <td><?=date('d-m-Y', strtotime($claim['claimed_on']))?></td>
                        <td><?=$claim['member_name']?></td>
                        <td><?=date('d-m-Y', strtotime($claim['date_of_admission']))?></td>
                        <td><?=date('d-m-Y', strtotime($claim['date_of_discharge']))?></td>
                        <td><?=$claim['total_claimed_amt']?></td>
                        <td><?=$claim['status']?></td>
                        <td><?=$editUrl?></td>
                        <td><?=$deleteUrl?></td>
                        <td><?=$submitUrl?></td>
                    </tr>
                    <?php    }
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
    <?php }?>
    <div class="card">
        <div class="card-header" id="headingTwo">
            <h5 class="mb-0">
              <button class="fn_accordian collapsed " data-toggle="collapse" data-target="#allipd" aria-expanded="false" aria-controls="entitle"> &rsaquo; All IPD Claims </button>
            </h5>
        </div>
        <div id="allipd" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordion">
            <div class="card-body">
              
                <table class="table table-bordered">
                    <tr>
                        <th>Claimed Date</th>
                        <th>Patient Type</th>
                        <th>Date of Admission</th>
                        <th>Date of Discharge</th>
                        <th>Total Claimed Amt</th>
                        <th>Total Sanc. Amt</th>
                        <th>Status</th>
                        <th>Preview</th>
                    </tr>
                    <?php 
//                    echo "<pre>";print_r($allClaims);
                    if(!empty($allClaims)){
                        foreach($allClaims as $claim){
                            $ipd_id = Yii::$app->utility->encryptString($claim['ipd_id']); 
                            $editUrl = Yii::$app->homeUrl."employee/ipdreimbursement/previewipdclaim?securekey=$menuid&ipd_id=$ipd_id";
                            $pUrl = "<a href='$editUrl' class='linkcolor' title='Preview IPD Claim Details'>Preview</a>";
                            $sancAmt = 0;
                            if($claim['status'] == "Sanctioned"){
                                $sancAmt = $claim['total_sanctioned_amt'];
                            }
                            $durl = Yii::$app->homeUrl."employee/ipdreimbursement/downloadipdclaim?securekey=$menuid&ipd_id=$ipd_id";
                            $durl = "<a href='$durl' target='_blank'><img width='20' src='".Yii::$app->homeUrl."images/pdf.png' /></a>";
                    ?>
                    <tr>
                        <td><?=date('d-m-Y', strtotime($claim['claimed_on']))?></td>
                        <td><?=$claim['member_name']?></td>
                        <td><?=date('d-m-Y', strtotime($claim['date_of_admission']))?></td>
                        <td><?=date('d-m-Y', strtotime($claim['date_of_discharge']))?></td>
                        <td><?=$claim['total_claimed_amt']?></td>
                        <td><?=$sancAmt?></td>
                        <td><?=$claim['status']?></td>
                        <td><?=$pUrl?></td>
                        <td><?=$durl?></td>
                    </tr>
                    <?php    }
                    }else{
                        echo "<tr><td align='center' colspan='8'><b>No IPD Claim Found</b></td></tr>";
                    }
                    ?>
                </table> 
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $("#deleteipdclaim").click(function(){
            if(confirm("Are you sure want to delete IPD Claim?")){
                return true;
            }
            return false;
        });
        $("#submitipdclaim").click(function(){
            if(confirm("Are you sure want to Submit IPD Claim? After submission you cannot add or update details.")){
                return true;
            }
            return false;
        });
    });
</script>
<?php //}?>