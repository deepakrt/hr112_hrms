<?php
$this->title="Apply For New IPD Claim";
use yii\widgets\ActiveForm;
$dependent_id = $parent = $self = $patient_type = "";
$disabled="disabled=''";
$self = 'checked="checked"';
$IpdClaimType = IpdClaimType;
?>
<input type="hidden" id="menuid" value="<?=$menuid?>" readonly="" />
<?php $form = ActiveForm::begin(['action'=>Yii::$app->homeUrl."employee/ipdreimbursement/saveipdclaim?securekey=$menuid", 'options' => ['id'=>'opdform', 'enctype' => 'multipart/form-data']]); ?>
<input type="hidden" name="Ipd[ipd_id]" value="<?=$ipd_id?>" readonly=""/>
<div class="row">
    <div class="col-sm-6" id="PatientList">
        <label class="col-sm-4">Patient</label>
        <input type="radio" name="Ipd[patient]" id="ipd-Patient1" <?=$self?> value="1" class="ipd_patient" /> Self 
        <input type="radio" name="Ipd[patient]" id="ipd-Patient2" <?=$parent?> class="ipd_patient" value="2" /> Dependent Family Member
    </div>
    <div class="col-sm-3">
        <select class="form-control form-control-sm" name="Ipd[dependent_id]" id="ipd-dependent_id" <?=$disabled?>>
            <option value="">-- Select --</option>
    <?php 
//    if(!empty($parent)){
        $data = Yii::$app->utility->get_family_details(Yii::$app->user->identity->e_id);
        if(!empty($data)){
            foreach($data as $d){
                if($d['status'] == 'Verified'){
                    $id = base64_encode($d['ef_id']);
                    $n = ucfirst($d['m_name']);
                    $selected="";
                    if($dependent_id == $id){
                        $selected="selected=''";
                    }
                    echo "<option $selected value='$id'>$n</option>";
                }
            }
        }
    ?>
    
    <?php //} ?>
            </select>
    </div>
</div>
<br>
<div class="row">
    <div class="col-sm-3">
        <label>Date of Admission</label>
        <input type="text" name="Ipd[date_of_admission]" id="date_of_admission" readonly="" class="form-control form-control-sm" placeholder="Date of Admission"  />
    </div>
    <div class="col-sm-2"></div>
    <div class="col-sm-3">
        <label>Date of Discharge</label>
        <input type="text" name="Ipd[date_of_discharge]" id="date_of_discharge" readonly="" class="form-control form-control-sm" placeholder="Date of Discharge"/>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <br>
        <label>Admitted For</label><br>
        <textarea name="Ipd[admitted_for]" maxlength="254" id="admitted_for" required="" class="form-control form-control-sm" placeholder="Admitted For"></textarea>
    </div>
    <div class="col-sm-6" id="typeclaim">
        <br>
        <label>Claim Type</label><br>
        <?php
        $i=1;
        if(!empty($IpdClaimType)){
            foreach($IpdClaimType as $Ipd){
                $select="";
                $id = base64_encode($Ipd);
                echo "<input type='radio' name='Ipd[claim_type]' $select class='claimtype' data-key='$i' value='$id' /> $Ipd <br>";
                $i++;
            }
        }
        ?>
    </div>
</div>
<div id="insuranceinfo" style="margin-bottom: 15px;margin-top: 15px; display: none;">
    <div class="row">
        <div class="col-sm-5">
            <label>Insurance Details</label>
            <select name="Ipd[insurance_id]" id="insurance_id" class="form-control form-control-sm">
                <option value=''>Select Insurance Details</option>
            </select>
        </div>
        <div class="col-sm-3">
            <label>Sanctioned Amount</label>
            <input type="text" name="Ipd[insrn_sanc_amt]" onkeypress="return allowOnlyNumber(event)" id="insrn_sanc_amt" class="form-control form-control-sm" placeholder="Sanctioned Amount" />
        </div>
        <div class="col-sm-3"><br><a href="javascript:void(0)" data-toggle="modal" data-target="#addInsurnDetailsModal" id="addInsurnDetails" class="btn btn-outline-primary btn-xs">Add New Insurance</a></div>
    </div>
</div>
<!--<a href="<?=Yii::$app->homeUrl?>employee/ipdreimbursement/addinsurancedetails?securekey=<?=$menuid?>" class="btn btn-outline-primary btn-xs">Add New Insurance</a>-->
<div class="modal fade" id="addInsurnDetailsModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Insurance Details</h5>
                <button type="button" style="display: none;" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="saveinsrnForm">
                <span id="display_modal_error" style="display:none;">
                    <div class="alert alert-danger text-center">
                        <span id="display_modal_error_message"></span>
                    </div>
                </span>
                <table class="table table-bordered">
                    <tr>
                        <th colspan="2">Member Name</th>
                        <td colspan="2"><span id="membername"></span>
                            <input type="hidden" id="dependent_id" name="Insurance[dependent_id]" readonly="" />
                            <input type="hidden" id="pType" name="Insurance[patient_type]" readonly="" />
                        </td>
                    </tr>
                    <tr>
                        <th colspan="2">Insurance Company Name</th>
                        <td colspan="2"><input type="text" id="comname" name="Insurance[company_name]" class="form-control form-control-sm" placeholder="Company Name" /></td>
                    </tr>
                    <tr>
                        <th colspan="2">Policy Number</th>
                        <td colspan="2"><input type="text" id="policynumber" name="Insurance[policynumber]" class="form-control form-control-sm" placeholder="Policy Number" /></td>
                    </tr>
                    <tr>
                        <th>Policy Valid From</th>
                        <td><input type="text" id="validfrom" name="Insurance[validfrom]" class="form-control form-control-sm" placeholder="Valid From" readonly="" /></td>
                        <th>Policy Valid Till</th>
                        <td><input type="text" id="validtill" name="Insurance[validtill]" class="form-control form-control-sm" placeholder="Valid Till" readonly="" /></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-sm" id="saveinsrn">Save</button>
                <a href="" class="btn btn-danger btn-sm">Cancel</a>
            </div>
        </div>
    </div>
</div>
<div class="col-sm-12 text-center">
    <br>
    <button type="submit" class="btn btn-success btn-sm sl" id="saveipd" >Save</button>
</div>
<?php ActiveForm::end()?>