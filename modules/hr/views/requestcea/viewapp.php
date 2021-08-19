<?php
$this->title ="View & Action";
use yii\widgets\ActiveForm;
?>

<div class="col-sm-12">
    <h6><b>Employee Details</b></h6>
    <table class="table table-bordered table-hover">
        <tr>
            <td><b>Employee Code : </b><?=$emp['employee_code'];?></td>
            <td><b>Emp Name : </b><?=$emp['fullname'];?></td>
            <td><b>Designation : </b><?=$emp['fullname'];?></td>
        </tr>
        <tr>
            <td><b>Department : </b><?=$emp['dept_name'];?></td>
            <td><b>Employee Type : </b><?=$emp['employment_type'];?></td>
            <td><b>Contact : </b><?=$emp['phone'];?></td>
        </tr>
    </table>
</div>
<div class="col-sm-12">
    <h6><b>Child / Children Details</b></h6>
    <table class="table table-bordered table-hover">
        <tr>
            <td><b>Name : </b>(<?=$cinfo['relation_name'];?>) <?=$cinfo['m_name'];?></td>
            <td><b>Date of Birth : </b><?=date('d-m-Y', strtotime($cinfo['m_dob']));?></td>
            <td><b>Std : </b><?=$cinfo['class_std'];?></td>
        </tr>
        <tr>
            <td><b>School Name : </b><?=$cinfo['school_name'];?></td>
            <td><b>AY Start : </b><?=date('d-m-Y', strtotime($cinfo['ay_start']));?></td>
            <td><b>AY End : </b><?=date('d-m-Y', strtotime($cinfo['ay_end']));?></td>
        </tr>
        <tr>
            <td><b>Financial Year : </b><?=$cinfo['financial_year'];?></td>
            <td><b>Total Claimed : </b><?php 
            $claimtype =  Yii::$app->utility->getclaimtypename($cinfo['claim_type']);
            $toalclaimed = "";
            if($cinfo['claim_type'] == 'CEA'){
                $toalclaimed = $cinfo['books_amount']+$cinfo['shoes_amount']+$cinfo['notebooks_amount']+$cinfo['uniform_amount']+$cinfo['tuition_fees'];
                
            }elseif($cinfo['claim_type'] == 'HS'){
                $toalclaimed = $cinfo['hostel_fees'];
            }
            $toalclaimed1 = number_format($toalclaimed, 2);
            echo $toalclaimed1;
            ?></td>
            <td><b>Application Date : </b><?=date('d-m-Y', strtotime($cinfo['created_date']));?></td>
        </tr>
        <tr>
            <td colspan="3"><b>Emp Remarks : </b><?=$cinfo['emp_remarks']?></td>
        </tr>
    </table>
</div>
<?php  $allowances = Yii::$app->utility->get_emp_allowance($emp['desg_id'], $emp['employmenttype'], $cinfo['financial_year']); ?>
<hr>
<div class="col-sm-12">
    <?php 
    $totalen = "";
    $can_sanc=0; 
    if(!empty($allowances)){
        foreach($allowances as $a){
            if($a['allowance_type'] == $cinfo['claim_type']){
                //echo "1 <br>";
                $totalen = "Rs. ".$a['amount']." per child per academic year";
                if($a['sanc_type'] == 'All'){
                    $totalen = "Rs. ".$a['amount']." per academic year";
                }
                $can_sanc=$a['amount'];
            }
        }
    }
    ?>
    <h6><b>Entitlement : </b><?=$totalen?></h6>
</div>
<?php 
if(!empty($totalen)){
?>
<div class="col-sm-6">
    <table class="table table-bordered table-hover">
        <tr>
            <th></th>
            <th>Total Claimed</th>
        </tr>
        <?php 
        if($cinfo['claim_type'] == 'CEA'){ ?>
        <tr>
            <td>Purchase of Book</td>
            <td align="right"><?=$cinfo['books_amount']?></td>
            
        </tr>
        <tr>
            <td>Purchase of Shoes</td>
            <td align="right"><?=$cinfo['shoes_amount']?></td>
        </tr>
        <tr>
            <td>Purchase of Notebooks</td>
            <td align="right"><?=$cinfo['notebooks_amount']?></td>
        </tr>
        <tr>
            <td>Purchase of Uniform</td>
            <td align="right"><?=$cinfo['uniform_amount']?></td>
        </tr>
        <tr>
            <td>Tuition Fees</td>
            <td align="right"><?=$cinfo['tuition_fees']?></td>
        </tr>
        <?php }elseif($cinfo['claim_type'] == 'HS'){ ?>
            <tr>
                <td>Hostel Fees</td>
                <td align="right"><?=$cinfo['hostel_fees']?></td>
            </tr>
        <?php }
        ?>
        <tr>
            <th>Total Claimed</th>
            <th style="text-align: right;"><?=$toalclaimed1?></th>
        </tr>
    </table>
</div>
<?php ActiveForm::begin(['id'=>'form']); 
//echo "<pre>";print_r($cinfo);
?>
<div class="row">
    <input type="hidden" readonly="" name="fy" value="<?=Yii::$app->utility->encryptString($cinfo['financial_year'])?>" />
    <input type="hidden" readonly="" name="ea_id" value="<?=Yii::$app->utility->encryptString($cinfo['ea_id'])?>" />
    <input type="hidden" readonly="" name="ec" value="<?=Yii::$app->utility->encryptString($cinfo['employee_code'])?>" />
    <input type="hidden" readonly="" name="id" value="<?=Yii::$app->utility->encryptString($cinfo['id'])?>" />
    <input type="hidden" readonly="" name="claim_type" value="<?=Yii::$app->utility->encryptString($cinfo['claim_type'])?>" />
    <input type="hidden" readonly="" name="can_sanc_id" value="<?=Yii::$app->utility->encryptString($can_sanc)?>" />
    <?php if(Yii::$app->user->identity->role == '5'){
    ?>
    <div class="col-sm-3">
        <label>Total Sanctioned Amount</label>
        <input type="number" class="form-control form-control-sm" min="0" max="<?=$can_sanc?>" name="sanc_amt" required="" placeholder="Amount" />
    </div>
    <div class="col-sm-3">
        <label>Select Action</label>
        <select class="form-control form-control-sm" name="action_type" required="">
            <option value="">Select Action</option>
            <option value="<?=Yii::$app->utility->encryptString('Approved')?>">Approve</option>
            <option value="<?=Yii::$app->utility->encryptString('Rejected')?>">Reject</option>
            <option value="<?=Yii::$app->utility->encryptString('In-Process')?>">In-Process</option>
        </select>
    </div>
    <?php }elseif(Yii::$app->user->identity->role == '6'){
        echo '<input type="hidden" value="'.Yii::$app->utility->encryptString($cinfo['total_sanc_amt']).'" name="sanc_amt"  />';
        echo '<input type="hidden" value="'.Yii::$app->utility->encryptString($can_sanc).'" name="can_sanc_id" />';
        echo '<input type="hidden" value="'.Yii::$app->utility->encryptString('Sanctioned').'" name="action_type" />';
        echo '<input type="hidden" value="'.Yii::$app->utility->encryptString($cinfo['total_sanc_amt']).'" name="keytype"  />';
        echo '<input type="hidden" value="'.Yii::$app->utility->encryptString($cinfo['hr_approved_by']).'" name="hab"  />';
        echo '<input type="hidden" value="'.Yii::$app->utility->encryptString($cinfo['hr_remarks']).'" name="habr"  />';
        echo "<div class='alert alert-danger'><b>Rs. ".$cinfo['total_sanc_amt']."/- approved by HR.</b></div>";
    }
    ?>
    <div class="col-sm-12">
        <label>Remarks (If any)</label>
        <textarea class="form-control form-control-sm" name="remarks" placeholder="Remarks (If any)"></textarea>
    </div>
    <div class="col-sm-12 text-center">
        <br>
        <input type="submit" class="btn btn-success btn-sm sl" value="Submit" />
        <a href="" class="btn btn-danger btn-sm">Cancel</a>
    </div>
</div>
<?php 
}else{
    echo "<div class='alert alert-danger text-center'><b>Entitlement Details Not Found. Contact Admin.</b></div>";
}
ActiveForm::end(); ?>