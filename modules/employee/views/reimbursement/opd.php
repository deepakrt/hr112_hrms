<?php
use yii\widgets\ActiveForm;
$this->title="Medical [OPD] Claim ";

//echo "<pre>";print_r($entitle);
?>
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
<input type="hidden" id="menuid" value="<?=$menuid?>" readonly="" />
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
                    <select class="form-control form-control-sm" id="opdFnYr">
                        <?php 
                        if(!empty($AllEntitle)){
                            foreach($AllEntitle as $fnYear){
                                $id = base64_encode($fnYear['entitle_id']);
                                if($fnyr == $id){
                                    echo "<option value='$id' $selected>".$fnYear['session_year']."</option>";
                                }else{
                                    echo "<option value='$id'>".$fnYear['session_year']."</option>";
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
    <?php 
    if(!empty($entitle)){
//        echo "<pre>";print_r($entitle); die;
        $draftClaims = Yii::$app->finance->fn_get_opd_claims(NULL, $entitle['entitle_id'], Yii::$app->user->identity->e_id, 'Draft');
        $submittedClaims = Yii::$app->finance->fn_get_opd_claims(NULL, $entitle['entitle_id'], Yii::$app->user->identity->e_id, 'Submitted,In-Process,Sanctioned,Rejected');
//        echo "<pre>";print_r($submittedClaims); die;
        $entitle_id = Yii::$app->utility->encryptString($entitle['entitle_id']);
    ?>
    <div class="row">
        <div class="col-sm-6" style="margin: 8px 0px;">
            <a href="javascript:void(0)" class="linkcolor" data-toggle="modal" data-target=".fysummary" style="font-size: 12px;">Financial Year wise Summary</a>
        </div>
        <div class="col-sm-6 text-right" style="margin: 8px 0px;">
            <?php 
            $curFy= Yii::$app->finance->getCurrentFY();
            if($curFy == $entitle['session_year']){
            ?>
            <a href="<?=Yii::$app->homeUrl?>employee/reimbursement/applynewclaim?securekey=<?=$menuid?>&entitleid=<?=$entitle_id?>" class="linkcolor" title="Start New Medical Claim" >Apply For New Medical Claim</a>
            <?php }?>
        </div>
    </div>
    <div class="card">
        <div class="card-header" id="headingTwo">
            <h5 class="mb-0">
              <button class="fn_accordian collapsed" data-toggle="collapse" data-target="#entitle" aria-expanded="false" aria-controls="entitle"> &rsaquo; Entitlement Details [OPD] for <?=$currentFn?> : Clear Balance : <?=$entitle['clearbalance']?> Rs.</button>
            </h5>
        </div>
        <div id="entitle" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <tr>
                        <td>[A] Yearly Entitlement</td>
                        <td align="right"><?=$entitle['yearly_entitlement']?> Rs. </td>
                        <td>[F] Utilized</td>
                        <td align="right"><?=$entitle['utilized']?> Rs. </td>
                    </tr>
                    <tr>
                        <td>[B] Carry Forward Balance</td>
                        <td align="right"><?=$entitle['carry_forward_balance']?> Rs. </td>
                        <td>[G] Recovery Amount</td>
                        <td align="right"><?=$entitle['recovery_amt']?> Rs. </td>
                    </tr>
                    <tr>
                        <td>[C] Excess Entitlement</td>
                        <td align="right"><?=$entitle['excess_entitlement']?> Rs. </td>
                        <td></td>
                        <td align="right"></td>
                    </tr>
                    <tr>
                        <td>[D] Deduction From Entitlement</td>
                        <td align="right"><?=$entitle['deduction_from_entitlement']?> Rs. </td>
                        <td></td>
                        <td align="right"></td>
                    </tr>
                    <tr>
                        <td>[E] Total Entitlement [A + B + C - D]</td>
                        <td align="right"><?=$entitle['totalentitle']?> Rs. </td>
                        <td>[H] Total Utilized [ F - G ] </td>
                        <td align="right"><?=$entitle['totaluti']?> Rs.</td>
                    </tr>
                    <tr>
                        <td>[G] Clear Balance [E - H]</td>
                        <td align="right"><?=$entitle['clearbalance']?> Rs. </td>
                        <td></td>
                        <td align="right"></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <?php 
    if(!empty($draftClaims)){
    ?>
    <div class="card">
        <div class="card-header" id="headingFour">
            <h5 class="mb-0">
            <button class="fn_accordian collapsed" data-toggle="collapse" data-target="#draftclaims" aria-expanded="false" aria-controls="recentMedical">&rsaquo; Claims [OPD] To Submit:</button>
          </h5>
        </div>
      <div id="draftclaims" class="collapse show" aria-labelledby="headingFour" data-parent="#accordion">
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th>Sr. No.</th>
                    <th>Claim Date</th>
                    <th>Total Claimed Amount</th>
                    <th>Edit</th>
                    <th>Delete</th>
                    <th>Preview</th>
                    <th>Submit</th>
                </tr>
                <?php 
                $i=1;
//                echo "<pre>";print_r($draftClaims);
                foreach($draftClaims as $draft){
                    $claimdt=date('d-M-Y', strtotime($draft['created_on']));
                    $opd_id = Yii::$app->utility->encryptString($draft['opd_id']);
                    $entitleid = Yii::$app->utility->encryptString($draft['entitle_id']);
                    $editUrl = Yii::$app->homeUrl."employee/reimbursement/applynewclaim?securekey=$menuid&entitleid=$entitle_id&opdid=$opd_id";
                    $submitUrl = Yii::$app->homeUrl."employee/reimbursement/submitclaimopd?securekey=$menuid&opd_id=$opd_id&entitleid=$entitleid";
                    $deleteUrl = Yii::$app->homeUrl."employee/reimbursement/deleteclaim?securekey=$menuid&entitleid=$entitle_id&opdid=$opd_id";
                ?>
                <tr>
                    <td><?=$i?></td>
                    <td><?=$claimdt?></td>
                    <td><?=$draft['total_claim']?></td>
                    <td><a href="<?=$editUrl?>"><img src="<?=Yii::$app->homeUrl?>images/edit.gif" /></a></td>
                    <td><a href="<?=$deleteUrl?>" class="deleteclaim"><img src="<?=Yii::$app->homeUrl?>images/del.gif" /></a></td>
                    <td><a href="" class="linkcolor">Preview</a></td>
                    <td><a href="<?=$submitUrl?>" class="linkcolor">Submit</a></td>
                </tr>
                <?php
                $i++;
                }
                ?>
            </table>
        </div>
      </div>
    </div>
    <?php }?>
    <div class="card">
        <div class="card-header" id="headingThree">
            <h5 class="mb-0">
            <button class="fn_accordian collapsed" data-toggle="collapse" data-target="#recentMedical" aria-expanded="false" aria-controls="recentMedical">&rsaquo; Recent Medical [OPD] Claims:</button>
          </h5>
        </div>
      <div id="recentMedical" class="collapse show" aria-labelledby="headingThree" data-parent="#accordion">
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th>Claim Id</th>
                    <th>Claim Date</th>
                    <th>Total Claimed Amount</th>
                    <th>Total Sanctioned Amount</th>
                    <th>Status</th>
                    <th></th>
                    <th></th>
                </tr>
                <?php 
                if(!empty($submittedClaims)){
                    foreach($submittedClaims as $claim){ 
                        $sanc=0;
                    if($claim['status'] == 'Sanctioned'){
                        $sanc=$claim['total_sanctioned'];
                    }
                    $opd_id = Yii::$app->utility->encryptString($claim['opd_id']);
                    $entitle_id = Yii::$app->utility->encryptString($claim['entitle_id']);
                    $detailUrl = Yii::$app->homeUrl."employee/reimbursement/claimdetails?securekey=$menuid&opd_id=$opd_id&entitle_id=$entitle_id";
                    
                    $durl = Yii::$app->homeUrl."employee/reimbursement/downloadopdclaim?securekey=$menuid&opd_id=$opd_id&entitle_id=$entitle_id";
		    $durl = "<a href='$durl' target='_blank'><img width='20' src='".Yii::$app->homeUrl."images/pdf.png' /></a>";
                ?>
                <tr>
                    <td><?=$claim['claim_id']?></td>
                    <td><?=date('d-m-Y', strtotime($claim['created_on']))?></td>
                    <td><?=$claim['total_claim']?></td>
                    <td><?=$sanc?></td>
                    <td><?=$claim['status']?></td> 
                    <td><a href="<?=$detailUrl?>" class="linkcolor">Details</a></td>
                    <td><?=$durl?></td> 
                </tr>        
                <?php }
                }
                ?>
            </table>
        </div>
      </div>
    </div>
    <?php 
    }
    ?>
</div>
<div class="modal fade fysummary" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Financial Year wise Summary</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Financial Year</th>
                        <th>Yearly Entitlement</th>
                        <th>Carry Fwd.</th>
                        <th>Excess Entitlement</th>
                        <th>Deduction</th>
                        <th>Total Entitlement</th>
                        <th>Utilized</th>
                        <th>Recovered</th>
                        <th>Balance</th>
                    </tr>
                    <?php 
                    foreach($AllEntitle as $ent){
                        $totalent = $ent['yearly_entitlement']+$ent['carry_forward_balance']+$ent['excess_entitlement']+$ent['deduction_from_entitlement'];
                    ?>
                    <tr>
                        <td><?=$ent['session_year']?></td>
                        <td><?=$ent['yearly_entitlement']?></td>
                        <td><?=$ent['carry_forward_balance']?></td>
                        <td><?=$ent['excess_entitlement']?></td>
                        <td><?=$ent['deduction_from_entitlement']?></td>
                        <td><?=$totalent?></td>
                        <td><?=$ent['utilized']?></td>
                        <td><?=$ent['recovery_amt']?></td>
                        <td><?=$totalent-$ent['recovery_amt']-$ent['utilized']?></td>
                    </tr>
                    <?php }
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>