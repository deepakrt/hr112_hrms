<?php
$this->title="Emp Annual Reimbursement";
$masters = Yii::$app->finance->get_ann_reim_master();
//echo "<pre>"; print_r($masters);
$records = "";
use yii\widgets\ActiveForm;
if(!empty($masters)){
    $i=0;
    foreach($masters as $f){
        if($selectedyrs == $f['financial_yr'] AND Yii::$app->user->identity->desg_id == $f['designation_id'] AND Yii::$app->user->identity->employmenttype == $f['emp_type']){
            $records[$i]['ann_reim_id']=$f['ann_reim_id'];
            $records[$i]['name']=$f['name'];
            $records[$i]['sanc_amt']=$f['sanc_amt'];
            $records[$i]['financial_yr']=$f['financial_yr'];
            $records[$i]['reim_type_id']=$f['reim_type_id'];
            $i++;
        }
    }
}
//echo "<pre>";print_r($records);
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
    .ct{
        width: 75%;
    }
</style>
<div id="accordion">
    <div class="card">
        <div class="card-header" id="headingOne">
          <h5 class="mb-0">
            <button class="fn_accordian"  data-target="#fnyrs" aria-expanded="true" aria-controls="fnyrs">
              &rsaquo; Select Financial Year
            </button>
          </h5>
        </div>

        <div id="fnyrs" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-3"><label>Select Financial Year</label></div>
                    <div class="col-sm-3">
                        <select class="form-control form-control-sm">
                            <?php 
                            if(!empty($yrs)){
                                foreach($yrs as $y){
                                    $seltd = "";
                                    if($selectedyrs == $y){
                                        $seltd = "selected=selected";
                                    }
                                    $y1 = Yii::$app->utility->encryptString($y);
                                    echo "<option $seltd value='$y1'>$y</option>";
                                }
                            }else{
                                echo "<option value=''>No details are available</option>";
                            }
                            ?>
                            
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header" id="headingOne">
          <h5 class="mb-0">
            <button class="fn_accordian" data-target="#claims" aria-expanded="true" aria-controls="fnyrs">
              &rsaquo; Claims Summary
            </button>
          </h5>
        </div>

        <div id="claims" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-bordered table-hover">
                            <tr>
                                <th>Reimburse Type</th>
                                <th>Status</th>
                                <th>Entitlement</th>
                                <th>Claimed Amount</th>
                                <th>Sanctioned Amount</th>
                                <th></th>
                            </tr>
                            <?php 
//                            echo "<pre>";print_r($records);
                            if(!empty($records)){
                                foreach($records as $r){
                                    $ann_reim_id = Yii::$app->utility->encryptString($r['ann_reim_id']);
                                    $financial_yr = Yii::$app->utility->encryptString($r['financial_yr']);
                                    
                                    if($r['reim_type_id'] == '1'){
                                        $claimUrl= Yii::$app->homeUrl."employee/reimbursement/annreimclaim?securekey=$menuid&key1=$ann_reim_id&key2=$financial_yr";
                                        $url = "<a href='$claimUrl' class='linkcolor'>Claim</a>";
                                    }else{
                                        $url = "<a href='javascript:void(0)' class='linkcolor annclaimmodel' data-name='".$r['name']."' data-key1='$ann_reim_id' data-key2='$financial_yr' data-samt='".$r['sanc_amt']."'>Claim</a>";
                                    }
                                    
                                    $status="Not Claimed";
                                    $sanc_amt = $claim_amt = "-";
                                    $chk = Yii::$app->finance->fn_get_ann_reim_claim(NULL, Yii::$app->user->identity->e_id, $r['financial_yr'], NULL, $r['ann_reim_id']);
                                    if(!empty($chk)){
                                        $status = $chk['status'];
                                        $claim_amt = $chk['total_claimed'];
                                        if(!empty($chk['sanc_claimed'])){
                                            $sanc_amt = $chk['sanc_claimed'];
                                        }
                                        $url = "<a href='javascript:void(0)' data-key2='$financial_yr' data-key1='$ann_reim_id' class='linkcolor view_ann_reim'>View</a>";
                                    }
                            ?>
                            <tr>
                                <td><?=$r['name']?></td>
                                <td><?=$status?></td>
                                <td><?=$r['sanc_amt']?></td>
                                <td><?=$claim_amt?></td>
                                <td><?=$sanc_amt?></td>
                                <td><?=$url?></td>
                            </tr>
                                    
                            <?php  }
                            }else{
                                echo "<tr><td colspan='6' align='center' style='color:red;'>No reimbursement details are available.</td></tr>";
                            }
                            ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::begin(["action"=> Yii::$app->homeUrl."employee/reimbursement/claimedform?securekey=$menuid", 'options' => ['enctype' => 'multipart/form-data']]);?>
<!-- Modal -->
<div class="modal fade" id="annclaim" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="c_title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="Claim[formtype]" value='<?=Yii::$app->utility->encryptString('1')?>' readonly="" />
                <input type="hidden" name="Claim[ari]" id='c_ari' readonly="" />
                <input type="hidden" name="Claim[fy]" id='c_fy' readonly="" />
                <div class="row">
                    <div class="col-sm-5"><label>Entitlement</label></div>
                    <div class="col-sm-7"><span id='c_entitle'></span></div>
                </div>
                
                <div class="row">
                    <div class="col-sm-5"><label>Claimed Amount</label></div>
                    <div class="col-sm-7"><input type='number' class="form-control form-control-sm" name="Claim[amount]" placeholder="Amount" required="" /></div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-5"><label>Browse PDF</label></div>
                    <div class="col-sm-7"><input type='file' accept=".pdf" class="form-control form-control-sm pdf_file" name="doc_file" /><span style="color: red;font-size: 12px;">Max File is <?=FTS_Doc_Size?>MB</span></div>
                </div>
            </div>
            <div class="modal-footer">
                <input type='submit' class="btn btn-success btn-sm" value='Submit to Finance' />
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end();?>

<!-- View Modal -->
<div class="modal fade" id="viewdetails" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Details of Annual Reimbursement Claim</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="htmlform"></div>
            </div>
      </div>
    </div>
</div>