<?php
use yii\widgets\ActiveForm;
$this->title = "View Annual Reimbursement Application";
$emp = Yii::$app->utility->get_employees($record['employee_code']);
$doc_path ="-";
if(!empty($doc_path)){
    $doc_path = "<a href='".Yii::$app->homeUrl.$record['doc_path']."' target='_blank' class='linkcolor'>View</a>";
}
//echo "<pre>";print_r($record);
?>
<table class="table table-bordered">
    <tr>
        <td><b>Employee Code</b><br><?=$emp['employee_code']?></td>
        <td><b>Employee Name</b><br><?=$emp['fullname']?></td>
        <td><b>Designation</b><br><?=$emp['desg_name']?></td>
        <td><b>Department</b><br><?=$emp['dept_name']?></td>
    </tr>
    <tr>
        <td><b>Reimbursement Type</b><br><?=$record['name']?></td>
        <td><b>Entitlement</b><br><?=$record['sanc_amt']?></td>
        <td><b>Total Claimed</b><br><?=$record['total_claimed']?></td>
        <td><b>Application Date</b><br><?=date('d-m-Y', strtotime($record['created_date']))?></td>
    </tr>
</table>
<table class='table table-bordered'>
    <tr>
        <th>Financial Year : <?=$record['financial_year']?></th>
        <th>Entitlement : <?=$record['sanc_amt']?></th>
        <th>Attached Document : <?=$doc_path?></th>
    </tr>
</table>
<?php
if($record['reim_type_id'] == '1'){ 
    $d = explode(',', $record['other_detail']);
    $f = explode('-', $record['financial_year']);
    echo "<table class='table table-bordered'>
                    <tr>
                        <th>Month</th>
                        <th>Details</th>
                        <th>Claimed Amount</th>
                    </tr>
                    <tr>
                        <td>April-".$f[0]."</td>
                        <td>".@$d[0]."</td>
                        <td>".$record['apr_month_amt']."</td>
                    </tr>
                    <tr>
                        <td>May-".$f[0]."</td>
                        <td>".@$d[1]."</td>
                        <td>".$record['may_month_amt']."</td>
                    </tr>
                    <tr>
                        <td>June-".$f[0]."</td>
                        <td>".@$d[2]."</td>
                        <td>".$record['june_month_amt']."</td>
                    </tr>
                    <tr>
                        <td>July-".$f[0]."</td>
                        <td>".@$d[3]."</td>
                        <td>".$record['july_month_amt']."</td>
                    </tr>
                    <tr>
                        <td>Aug-".$f[0]."</td>
                        <td>".@$d[4]."</td>
                        <td>".$record['aug_month_amt']."</td>
                    </tr>
                    <tr>
                        <td>Sept-".$f[0]."</td>
                        <td>".@$d[5]."</td>
                        <td>".$record['sept_month_amt']."</td>
                    </tr>
                    <tr>
                        <td>Oct-".$f[0]."</td>
                        <td>".@$d[6]."</td>
                        <td>".$record['oct_month_amt']."</td>
                    </tr>
                    <tr>
                        <td>Nov-".$f[0]."</td>
                        <td>".@$d[7]."</td>
                        <td>".$record['nov_month_amt']."</td>
                    </tr>
                    <tr>
                        <td>Dec-".$f[0]."</td>
                        <td>".@$d[8]."</td>
                        <td>".$record['dec_month_amt']."</td>
                    </tr>
                    <tr>
                        <td>Jan-".$f[1]."</td>
                        <td>".@$d[9]."</td>
                        <td>".$record['jan_month_amt']."</td>
                    </tr>
                    <tr>
                        <td>Feb-".$f[1]."</td>
                        <td>".@$d[10]."</td>
                        <td>".$record['feb_month_amt']."</td>
                    </tr>
                    <tr>
                        <td>Mar-".$f[1]."</td>
                        <td>".@$d[11]."</td>
                        <td>".$record['mar_month_amt']."</td>
                    </tr>
                    <tr>
                        <td colspan='2' align='right'><b>Total Claimed</b></td>
                        <td>".$record['total_claimed']."</td>
                    </tr>
                    </table>";
?>
    
<?php } ?>
<?php ActiveForm::begin()?>
<div class="row">
    <input type="hidden" name="Reim[arc_id]" value="<?=Yii::$app->utility->encryptString($record['arc_id'])?>" readonly="" />
    <input type="hidden" name="Reim[ec]" value="<?=Yii::$app->utility->encryptString($record['employee_code'])?>" readonly="" />
    <input type="hidden" name="Reim[ann_reim_id]" value="<?=Yii::$app->utility->encryptString($record['ann_reim_id'])?>" readonly="" />
    <input type="hidden" name="Reim[fy]" value="<?=Yii::$app->utility->encryptString($record['financial_year'])?>" readonly="" />
    <input type="hidden" name="Reim[key]" value="<?=Yii::$app->utility->encryptString($record['sanc_amt'])?>" readonly="" />
    
    <div class="col-sm-3">
        <label>Sanction Amount</label>
        <input type="number" class="form-control form-control-sm" name="Reim[sanc_amt]" placeholder="Sanc. Amount" required="" />
    </div>
    <div class="col-sm-3">
        <label>Action Type</label>
        <select class="form-control form-control-sm" name="Reim[status]" required="">
            <option value="">Select Action Type</option>
            <option value="<?=Yii::$app->utility->encryptString('Sanctioned')?>">Sanction</option>
            <option value="<?=Yii::$app->utility->encryptString('Rejected')?>">Reject</option>
        </select>
    </div>
    <div class="col-sm-6">
        <label>Remarks (If any)</label>
        <textarea class="form-control form-control-sm" name="Reim[sanc_remarks]" placeholder="Remarks (If any)" maxlength="254"></textarea>
    </div>
    <div class="col-sm-12 text-center">
        <br>
        <input type="submit" class="btn btn-success btn-sm sl" value="Submit"/>
        <a href="<?=Yii::$app->homeUrl?>finance/annualreimbursement/index?securekey=<?=$menuid?>" class="btn btn-danger btn-sm">Back</a>
    </div>
</div>
<?php ActiveForm::end()?>
