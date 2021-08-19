<?php
use yii\widgets\ActiveForm;
$this->title = "CEA Claim financial year $fy";
$childinfo = Yii::$app->hr_utility->hr_get_CEA_child_details(Yii::$app->user->identity->e_id,NULL, $fy, $ea_id);
//echo "<pre>";print_r($childinfo);
?>
<style>
    .col-sm-4{
        margin-bottom: 15px;
    }
</style>
<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered table-hover">
            <tr>
                <td><b>Name :</b> <br>(<?=$childinfo['relation_name']?>) <?=$childinfo['m_name']?></td>
                <td><b>Date of Birth :</b> <br><?=date('d-m-Y', strtotime($childinfo['m_dob']))?></td>
                <td><b>Class :</b><br><?=$childinfo['class_std']?></td>
                <td><b>School Name :</b><br><?=$childinfo['school_name']?></td>
            </tr>
            <tr>
                <td><b>Financial Year :</b><br><?=$childinfo['financial_year']?></td>
                <td><b>AY Start :</b> <br><?=date('d-m-Y', strtotime($childinfo['ay_start']))?></td>
                <td><b>AY End :</b> <br><?=date('d-m-Y', strtotime($childinfo['ay_end']))?></td>
            </tr>
        </table>
    </div>
</div>
<hr>
<?php 
ActiveForm::begin(['id'=>'ceclaimform', 'options' => ['enctype' => 'multipart/form-data']]);
?>
<input type="hidden" name="CEAClaim[ea_id]" value="<?=Yii::$app->utility->encryptString($ea_id)?>" readonly="" />
<input type="hidden" name="CEAClaim[fy]" value="<?=Yii::$app->utility->encryptString($fy)?>" readonly="" />
<input type="hidden" name="CEAClaim[ct]" value="<?=Yii::$app->utility->encryptString($ct)?>" readonly="" />
<h6><b>Claim Type : </b><?=$cType?></h6>
<hr>
<?php
if($ct == 'CEA'){ ?>
<div class="row">
    <div class="col-sm-4">
        <label>Purchase of Book [Rs.]</label>
        <input type="number" min="0" max="99999" class="form-control form-control-sm" name="CEAClaim[books_amount]" placeholder="Enter Total Amount" required="" />
    </div>
    <div class="col-sm-4">
        <label>Purchase of Shoes [Rs.]</label>
        <input type="number" min="0" max="99999" class="form-control form-control-sm" name="CEAClaim[shoes_amount]" placeholder="Enter Total Amount" required="" />
    </div>
    <div class="col-sm-4">
        <label>Purchase of Notebooks [Rs.]</label>
        <input type="number" min="0" max="99999" class="form-control form-control-sm" name="CEAClaim[notebooks]" placeholder="Enter Total Amount" required="" />
    </div>
    <div class="col-sm-4">
        <label>Purchase of Uniform [Rs.]</label>
        <input type="number" min="0" max="99999" class="form-control form-control-sm" name="CEAClaim[uniform_amount]" placeholder="Enter Total Amount" required="" />
    </div>
    <div class="col-sm-4">
        <label>Tuition Fees [Rs.]</label>
        <input type="number" min="0" max="99999" class="form-control form-control-sm" name="CEAClaim[tuition_fees]" placeholder="Enter Total Amount" required="" />
    </div>
</div>
<?php }elseif($ct == 'HS'){ ?>
<div class="row">
    <div class="col-sm-4">
        <label>Hostel Fees</label>
        <input type="number" min="0" max="99999" class="form-control form-control-sm" name="CEAClaim[hostel_fees]" placeholder="Enter Total Amount" required="" />
    </div>
</div>
<?php } ?>
<div class="row">
    <div class="col-sm-4">
        <label>File Type</label>
        <select id="doc_type" name="CEAClaim[file_type]" class="form-control form-control-sm" required="">
            <option value="<?=Yii::$app->utility->encryptString('PDF')?>">PDF</option>
        </select>
    </div>
    <div class="col-sm-4">
        <label>Upload All Bills</label>
        <input type="file" class="form-control form-control-sm pdf_file" name="scanned_doc" accept=".pdf" />
        <p style="color:red;font-size: 12px;">Max File Size <?=FTS_Doc_Size?> MB</p>
    </div>
    <div class="col-sm-12">
        <label>Remarks (If any)</label>
        <textarea rows="6" class="form-control form-control-sm" name="CEAClaim[emp_remarks]" placeholder="Remarks (If any)"></textarea>
        <br>
    </div>
    <div class="col-sm-12 text-center">
        <input type="submit" class="btn btn-success btn-sm sl" value="Submit For Approval" />
        <a href="" class="btn btn-danger btn-sm">Reset</a>
    </div>
</div>
<?php
ActiveForm::end();
?>

