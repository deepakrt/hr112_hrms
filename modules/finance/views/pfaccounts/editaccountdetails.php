<?php
use yii\widgets\ActiveForm;
$this->title = "Edit PF Account Details";
$ecinf = Yii::$app->utility->get_employees($pfacs['employee_code']);
//echo "<pre>";print_r($ecinf);
?>
<style>
    .col-sm-3{
        margin-bottom: 10px;
    }
</style>
<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered">
            <tr>
                <td>Emp Code</td>
                <td><?=$pfacs['employee_code']?></td>
                <td>Name</td>
                <td><?=$ecinf['fullname']?></td>
            </tr>
            <tr>
                <td>Designation</td>
                <td><?=$ecinf['desg_name']?></td>
                <td>Department</td>
                <td><?=$ecinf['dept_name']?></td>
            </tr>
            <tr>
                <td>PAN Number</td>
                <td><?=$ecinf['pan_number']?></td>
                <td>Joining Date</td>
                <td><?=date('d-M-Y', strtotime($ecinf['joining_date']))?></td>
            </tr>
        </table>
    </div>
</div>
<?php ActiveForm::begin(); ?>
<input type="hidden"  name="PF[pfid]" value="<?=$pfid?>" readonly=""/>
<input type="hidden"  name="PF[ec]" value="<?=$ec?>" readonly=""/>
<div class="row">
    <div class="col-sm-12"><h6><b>Update Details</b></h6></div>
    <div class="col-sm-3">
        <label>UAN Number</label>
        <input type="text" class="form-control form-control-sm" name="PF[uan_number]" value="<?=$pfacs['uan_number']?>" required="" />
    </div>
    <div class="col-sm-3">
        <label>PF Account Number</label>
        <input type="text" class="form-control form-control-sm" name="PF[pf_number]" value="<?=$pfacs['pf_number']?>" required="" />
    </div>
    <div class="col-sm-3">
        <label>Subscription Date</label>
        <input type="text" class="form-control form-control-sm" id="pf_subscription_date" name="PF[subscription_date]" value="<?=date('d-m-Y', strtotime($pfacs['subscription_date']))?>" readonly=""/>
    </div>
    <div class="col-sm-3">
        <label>FPF Account Number</label>
        <input type="text" class="form-control form-control-sm" name="PF[fpf_account]" value="<?=$pfacs['fpf_account']?>" required="" />
    </div>
    <div class="col-sm-3">
        <label>Is VPF Deduct?</label>
        <select class="form-control form-control-sm" name="PF[vpf_deduct]" required="">
            <?php
            $selectedYes = $selectedNo = "";
            if($pfacs['vpf_deduct'] == 'Y'){
                $selectedYes = "selected='selected'";
            }elseif($pfacs['vpf_deduct'] == 'N'){
                $selectedNo = "selected='selected'";
            }
            ?>
            <option <?=$selectedYes?> value='Y'>Yes</option>
            <option <?=$selectedNo?> value='N'>No</option>
        </select>
    </div>
    <div class="col-sm-3">
        <label>Is Eligible for FPF?</label>
        <select class="form-control form-control-sm" name="PF[is_eligible_fpf]" required="">
            <?php
            $selectedYes = $selectedNo = "";
            if($pfacs['is_eligible_fpf'] == 'Y'){
                $selectedYes = "selected='selected'";
            }elseif($pfacs['is_eligible_fpf'] == 'N'){
                $selectedNo = "selected='selected'";
            }
            ?>
            <option <?=$selectedYes?> value='Y'>Yes</option>
            <option <?=$selectedNo?> value='N'>No</option>
        </select>
    </div>
    <div class="col-sm-3">
        <label>Is Active</label>
        <select class="form-control form-control-sm" name="PF[is_active]" required="">
            <?php
            $selectedYes = $selectedNo = "";
            if($pfacs['is_active'] == 'Y'){
                $selectedYes = "selected='selected'";
            }elseif($pfacs['is_active'] == 'N'){
                $selectedNo = "selected='selected'";
            }
            ?>
            <option <?=$selectedYes?> value='Y'>Yes</option>
            <option <?=$selectedNo?> value='N'>No</option>
        </select>
    </div>
    <div class="col-sm-12 text-center">
        <br>
        <button type="submit" class="btn btn-success btn-sm">Update</button>
        <a href="<?=Yii::$app->homeUrl?>finance/pfaccounts?securekey=<?=$menuid?>" class="btn btn-danger btn-sm">Cancel</a>
    </div>
</div>
<?php ActiveForm::end(); ?>