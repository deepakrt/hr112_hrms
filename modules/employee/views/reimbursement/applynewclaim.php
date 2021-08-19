<?php
//echo "<pre>";print_r($Singlebill);
use yii\widgets\ActiveForm;
$this->title="Fill Expense Details";
$types = Yii::$app->finance->fn_get_bill_type(NULL);
$dependent_id = $parent = $self = $patient_type = $bill_issuer =$bill_type =$bill_amt =$bill_date =$bill_date =$bill_num = "";
$self = 'checked="checked"';
$disabled="disabled=''";
if(!empty($Singlebill)){
    $bill_num = $Singlebill['bill_num'];
    $bill_date = date('d-m-Y', strtotime($Singlebill['bill_date']));
    $bill_amt = $Singlebill['bill_amt'];
    $bill_type = base64_encode($Singlebill['bill_type']);
    $dependent_id = base64_encode($Singlebill['dependent_id']);
    $bill_issuer = $Singlebill['bill_issuer'];
    if($Singlebill['patient_type'] == 'S'){
        $self = 'checked="checked"';
    }elseif($Singlebill['patient_type'] == 'D'){
        $self = '';
        $parent = 'checked="checked"';
        $disabled="";
    }
}


//echo "<pre>";print_r($detail);
?>
<input type="hidden" id="menuid" value="<?=$menuid?>" readonly="" />
<table class="table table-bordered">
    <tr>
        <th>Financial Year</th>
        <th>Total Entitlement</th>
        <th>Utilized</th>
        <th>Balance</th>
    </tr>
    <tr>
        <td><?=$detail['session_year']?></td>
        <td><?=$detail['yearly_entitlement']?></td>
        <td><?=$detail['utilized']?></td>
        <td><?=$detail['yearly_entitlement']-$detail['utilized']?></td>
    </tr>
</table>
<hr>
<?php $form = ActiveForm::begin(['action'=>Yii::$app->homeUrl."employee/reimbursement/saveclaim?securekey=$menuid", 'options' => ['id'=>'opdform', 'enctype' => 'multipart/form-data']]); ?>
<input type="hidden" name="Opt[opd_id]" value="<?=$opdid?>" readonly=""/>
<input type="hidden" name="Opt[bill_id]" value="<?=$billid?>" readonly=""/>
<div class="row">
    <div class="col-sm-6">
        <label class="col-sm-4">Patient</label>
        <input type="radio" name="Opt[patient]" id="Opt-Patient1" <?=$self?> value="1" class="opt_patient" /> Self 
        <input type="radio" name="Opt[patient]" id="Opt-Patient2" <?=$parent?> class="opt_patient" value="2" /> Dependent Family Member
        <input type="hidden" name="Opt[entitleid]" readonly="" value="<?=$entitleid?>" />
        
    </div>
    <div class="col-sm-3">
        <select class="form-control form-control-sm" name="Opt[dependent_id]" id="Opt-dependent_id" <?=$disabled?>>
            <option value="">-- Select --</option>
    <?php 
    if(!empty($parent)){
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
    
    <?php } ?>
            </select>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-sm-3">
        <label>Bill No.</label>
        <input type="text" name="Opt[bill_no]" value="<?=$bill_num?>" id="Opt-bill_no" placeholder="Bill No."  class="form-control form-control-sm" maxlength="10"/>
    </div>
    <div class="col-sm-3">
        <label>Bill Date</label>
        <input type="text" name="Opt[bill_date]" value="<?=$bill_date?>" placeholder="Bill Date" id="Opt-bill_date" class="form-control form-control-sm" readonly="" />
    </div>
    <div class="col-sm-3">
        <label>Bill Amount</label>
        <input type="text" onkeypress="return allowOnlyNumber(event)" id="Opt-bill_amount" name="Opt[bill_amount]" value="<?=$bill_amt?>" placeholder="Bill Amount" maxlength="6" class="form-control form-control-sm"/>
    </div>
    <div class="col-sm-3">
        <label>Bill Type</label>
        <select class="form-control form-control-sm" id="Opt-bill_type" name="Opt[bill_type]">
            <option value="">Select Bill Type</option>
            <?php 
            if(!empty($types)){
                foreach($types as $type){
                    $id = base64_encode($type['id']);
                    $n = $type['bill_type'];
                    $selected="";
                    if(!empty($bill_type)){
                        if($id  == $bill_type){
                            $selected="selected=''";
                        }
                    }
                    
                    echo "<option $selected value='$id'>$n</option>";
                }
            }
            ?>
        </select>
    </div>
    <div class="col-sm-8">
        <br>
        <label>Issuer</label>
        <input type="text" name="Opt[issuer]" value="<?=$bill_issuer?>" id="Opt-issuer" placeholder="Issuer"  class="form-control form-control-sm"/>
    </div>
<!--    <div class="col-sm-4">
        <br>
        <label>Upload Documents</label>
        <input type="file" name="Opt[documents][]" class="form-control form-control-sm" accept=".jpg, .jpeg, .png" multiple=""/>
    </div>-->
    <div class="col-sm-12 text-center">
        <br>
        <button type="button" class="btn btn-success btn-sm" id="opt_save">Save</button>
        <a href="" class="btn btn-danger btn-sm">Cancel</a>
    </div>
</div>
<?php ActiveForm::end(); ?>
<?php 
if(!empty($billDetails)){ 
    $submitUrl = Yii::$app->homeUrl."employee/reimbursement/submitclaimopd?securekey=$menuid&opd_id=$opdid&entitleid=$entitleid";
?>
<hr>
<div class="text-right">
    <a href="javascript:void(0)" class="btn btn-outline-secondary btn-sm">Preview</a>
    <a href="<?=$submitUrl?>" class="btn btn-info btn-sm">Submit</a>
</div>
<hr>
<table class="table table-bordered">
    <tr>
        <th>Self/Family</th>
        <th>Patient Name</th>
        <th>Bill No</th>
        <th>Bill Date</th>
        <th>Bill Type</th>
        <th>Issuer</th>
        <th>Amount</th>
        <th>Edit</th>
        <th>Delete</th>
    </tr>
    <?php
    $totalClaim=0;
    foreach($billDetails as $bill){
        $billid = Yii::$app->utility->encryptString($bill['id']);
        $patienttype = $bill['patienttype'];
        $name = Yii::$app->user->identity->fullname;
        if(!empty($bill['m_name'])){
            $name = $bill['m_name'];
        }
        $editUrl = Yii::$app->homeUrl."employee/reimbursement/applynewclaim?securekey=$menuid&entitleid=$entitleid&opdid=$opdid&billid=$billid";
        $deleteUrl = Yii::$app->homeUrl."employee/reimbursement/deletebill?securekey=$menuid&entitleid=$entitleid&opdid=$opdid&billid=$billid";
        $editUrl = "<a href='$editUrl'><img src='".Yii::$app->homeUrl."images/edit.gif' /></a>";
        $deleteUrl = "<a href='$deleteUrl' class='deletebill'><img src='".Yii::$app->homeUrl."images/del.gif' /></a>";
        echo "
            <tr>
                <td>$patienttype</td>
                <td>$name</td>
                <td>".$bill['bill_num']."</td>
                <td>".date('d-M-Y', strtotime($bill['bill_date']))."</td>
                <td>".$bill['billtype']."</td>
                <td>".$bill['bill_issuer']."</td>
                <td>".number_format($bill['bill_amt'],2)."</td>
                <td>$editUrl</td>
                <td>$deleteUrl</td>
            </tr>
        ";
        $totalClaim = $totalClaim+$bill['bill_amt'];
        
    }
    $totalClaim = number_format($totalClaim,2);
    echo "<tr>
        <td colspan='6' align='right'><b>Grand Total</b></td>
        <td><b>".$totalClaim."</b></td>
        <td></td>
        <td></td>
    </tr>";
    ?>
</table>
<?php }
?>