<?php
use yii\widgets\ActiveForm;
$this->title = "Salary Details ".$info['fullname']." (".$info['employee_code'].")";
//echo "<pre>";print_r($salaryInfo);
?>
<style>
    .col-sm-3{
        margin-bottom: 10px;
    }
</style>
<table class="table table-bordered">
    <tr>
        <td>Emp ID</td>
        <td><?=$info['employee_code']?></td>
        <td>Name</td>
        <td><?=$info['fullname']?></td>
    </tr>
    <tr>
        <td>Designation</td>
        <td><?=$info['desg_name']?></td>
        <td>Department</td>
        <td><?=$info['dept_name']?></td>
    </tr>
    <tr>
        <td>Email ID</td>
        <td><?=$info['email_id']?></td>
        <td>Employee Type</td>
        <td><?=$info['employment_type']?></td>
    </tr>
    <tr>
        <th>Current Salary Status</th>
        <th><?=$salaryInfo['status']?></th>
        <th>Last Updated</th>
        <th><?=date('d-m-Y', strtotime($salaryInfo['modified_date']))?></th>
    </tr>
</table>
<hr>
<h5><b><u>Update Salary Details for the month <?=$salaryInfo['salMonth']?>-<?=$salaryInfo['salYear']?> </u></b></h5>
<?php ActiveForm::begin(); ?>
<input type="hidden" name="Salary[employee_code]" value="<?=Yii::$app->utility->encryptString($salaryInfo['employee_code'])?>" readonly="" />
<input type="hidden" name="Salary[salMonth]" value="<?=Yii::$app->utility->encryptString($salaryInfo['salMonth'])?>" readonly="" />
<input type="hidden" name="Salary[salYear]" value="<?=Yii::$app->utility->encryptString($salaryInfo['salYear'])?>" readonly="" />
<div class="row">
    <div class="col-sm-12">
        <h6><u>Allowances</u></h6>
    </div>
    <div class="col-sm-3">
        <label>DA Arrear</label>
        <input type="number" class="form-control form-control-sm" name="Salary[allowance_da_arrear]" value="<?=$salaryInfo['allowance_da_arrear']?>" required="" />
    </div>
    <div class="col-sm-3">
        <label>TA Arrear</label>
        <input type="number" class="form-control form-control-sm" name="Salary[allowance_ta_arrear]" value="<?=$salaryInfo['allowance_ta_arrear']?>" required="" />
    </div>
</div>
<br>
<div class="row">
    <div class="col-sm-12">
        <h6><u>Deductions</u></h6>
    </div>
    <div class="col-sm-3">
        <label>PF on Arrear</label>
        <input type="number" class="form-control form-control-sm" name="Salary[ded_pf_on_arrear]" value="<?=$salaryInfo['ded_pf_on_arrear']?>" required="" />
    </div>
    <div class="col-sm-3">
        <label>Income Tax</label>
        <input type="number" class="form-control form-control-sm" name="Salary[ded_incomeTax]" value="<?=$salaryInfo['ded_incomeTax']?>" required="" />
    </div>
    <div class="col-sm-3">
        <label>Fee</label>
        <input type="number" class="form-control form-control-sm" name="Salary[ded_lfee]" value="<?=$salaryInfo['ded_lfee']?>" required="" />
    </div>
    <div class="col-sm-3">
        <label>Club</label>
        <input type="number" class="form-control form-control-sm" name="Salary[ded_club]" value="<?=$salaryInfo['ded_club']?>" required="" />
    </div>
    <div class="col-sm-3">
        <label>GSLI</label>
        <input type="number" class="form-control form-control-sm" name="Salary[ded_GSLI]" value="<?=$salaryInfo['ded_GSLI']?>" required="" />
    </div>
    <div class="col-sm-3">
        <label>Benevolent Fund</label>
        <input type="number" class="form-control form-control-sm" name="Salary[ded_BenevolentFund]" value="<?=$salaryInfo['ded_BenevolentFund']?>" required="" />
    </div>
    <div class="col-sm-3">
        <label>Child Edu.</label>
        <input type="number" class="form-control form-control-sm" name="Salary[child_edu]" value="<?=$salaryInfo['child_edu']?>" required="" />
    </div>
    <div class="col-sm-3">
        <label>Other Income</label>
        <input type="number" class="form-control form-control-sm" name="Salary[other_income]" value="<?=$salaryInfo['other_income']?>" required="" />
    </div>
    <div class="col-sm-3">
        <label>Perq Lease</label>
        <input type="number" class="form-control form-control-sm" name="Salary[perq_lease]" value="<?=$salaryInfo['perq_lease']?>" required="" />
    </div>
    <div class="col-sm-3">
        <label>Perq Medical Reimbursement</label>
        <input type="number" class="form-control form-control-sm" name="Salary[perq_medical_reimbursement]" value="<?=$salaryInfo['perq_medical_reimbursement']?>" required="" />
    </div>
    <div class="col-sm-3">
        <label>Perq Interest</label>
        <input type="number" class="form-control form-control-sm" name="Salary[perq_interest]" value="<?=$salaryInfo['perq_interest']?>" required="" />
    </div>
    <div class="col-sm-3">
        <label>HRA Exemption</label>
        <input type="number" class="form-control form-control-sm" name="Salary[hra_exemption]" value="<?=$salaryInfo['hra_exemption']?>" required="" />
    </div>
    <div class="col-sm-3">
        <label>Transport Exemption</label>
        <input type="number" class="form-control form-control-sm" name="Salary[transport_exemption]" value="<?=$salaryInfo['transport_exemption']?>" required="" />
    </div>
    <div class="col-sm-3">
        <label>Child Edu Allowance Exemption</label>
        <input type="number" class="form-control form-control-sm" name="Salary[child_education_allowance_exemption]" value="<?=$salaryInfo['child_education_allowance_exemption']?>" required="" />
    </div>
    <div class="col-sm-3">
        <label>Other Income Reported By Employee</label>
        <input type="number" class="form-control form-control-sm" name="Salary[other_income_reported_by_employee]" value="<?=$salaryInfo['other_income_reported_by_employee']?>" required="" />
    </div>
    <div class="col-sm-3">
        <label>Income From House Property</label>
        <input type="number" class="form-control form-control-sm" name="Salary[income_from_house_property]" value="<?=$salaryInfo['income_from_house_property']?>" required="" />
    </div>
    <div class="col-sm-3">
        <label>Previous Employer Income</label>
        <input type="number" class="form-control form-control-sm" name="Salary[previous_employer_income]" value="<?=$salaryInfo['previous_employer_income']?>" required="" />
    </div>
    <div class="col-sm-3">
        <label>Professional Tax</label>
        <input type="number" class="form-control form-control-sm" name="Salary[professional_tax]" value="<?=$salaryInfo['professional_tax']?>" required="" />
    </div>
    <div class="col-sm-3">
        <label>Loss On House Property</label>
        <input type="number" class="form-control form-control-sm" name="Salary[loss_on_house_property]" value="<?=$salaryInfo['loss_on_house_property']?>" required="" />
    </div>
    <div class="col-sm-12 text-center">
        <br>
        <button type="submit" class="btn btn-success btn-sm">Update</button>
        <a href="<?=Yii::$app->homeUrl?>finance/salarydetails/viewdetail?securekey=<?=$menuid?>&securecode=<?=Yii::$app->utility->encryptString($salaryInfo['employee_code'])?>" class="btn btn-danger btn-sm">Cancel</a>
    </div>
    
</div>
<?php ActiveForm::end(); ?>