<?php

$this->title = "Annual Reimbursement Claim";
use yii\widgets\ActiveForm;
$yr = explode('-', $master_info['financial_yr']);
$newspapers = array('Hindi - Dainik Jagran', 'Hindi - Dainik Bhaskar', 'Hindi - Hindustan Dainik', 'English - The Times of India', 'Punjabi - Punjab Keshari');
?>
<style>
    input.form-control-sm, select.form-control-sm{
        padding: .25rem;
        font-size: 13px;
    }
</style>
<h6><b><?=$master_info['name']?></b></h6>
<table class="table table-bordered table-hover">
    <tr>
        <th>Financial Year : <?=$master_info['financial_yr']?></th>
        <th>Entitlement : <?=$master_info['sanc_amt']?></th>
    </tr>
</table>
<?php ActiveForm::begin(["action"=> Yii::$app->homeUrl."employee/reimbursement/claimedform?securekey=$menuid", 'options' => ['enctype' => 'multipart/form-data']]);?>
<input type="hidden" name="Claim[formtype]" value='<?=Yii::$app->utility->encryptString('2')?>' readonly="" />
<input type="hidden" name="Claim[ari]" value="<?=Yii::$app->utility->encryptString($master_info['ann_reim_id'])?>" readonly="" />
<input type="hidden" name="Claim[fy]" value="<?=Yii::$app->utility->encryptString($master_info['financial_yr'])?>" readonly="" />
<table class="table table-bordered">
    <tr>
        <th>For Month</th>
        <th>News Paper</th>
        <th></th>
        <th></th>
        <th>Claimed Amount</th>
    </tr>
    <tr>
        <td>April-<?=$yr[0]?></td>
        <td><select class="form-control form-control-sm" name="Claim[np_paper][]" required="">
                <option value="">Select News Paper</option>
                <?php 
                foreach($newspapers as $n){
                    $n1 = Yii::$app->utility->encryptString($n);
                    echo "<option value='$n1'>$n</option>";
                }
                ?>
            </select>
        </td>
        <td></td><td></td>
        <td><input type="number" name="Claim[apr_month_amt]" class="form-control form-control-sm" required="" /> </td>
    </tr>
    <tr>
        <td>May-<?=$yr[0]?></td>
        <td><select class="form-control form-control-sm" name="Claim[np_paper][]" required="">
                <option value="">Select News Paper</option>
                <?php 
                foreach($newspapers as $n){
                    $n1 = Yii::$app->utility->encryptString($n);
                    echo "<option value='$n1'>$n</option>";
                }
                ?>
            </select>
        </td>
        <td></td><td></td>
        <td><input type="number" name="Claim[may_month_amt]" class="form-control form-control-sm" required="" /> </td>
    </tr>
    <tr>
        <td>June-<?=$yr[0]?></td>
        <td><select class="form-control form-control-sm" name="Claim[np_paper][]" required="">
                <option value="">Select News Paper</option>
                <?php 
                foreach($newspapers as $n){
                    $n1 = Yii::$app->utility->encryptString($n);
                    echo "<option value='$n1'>$n</option>";
                }
                ?>
            </select>
        </td>
        <td></td><td></td>
        <td><input type="number" name="Claim[june_month_amt]" class="form-control form-control-sm" required="" /> </td>
    </tr>
    <tr>
        <td>July-<?=$yr[0]?></td>
        <td><select class="form-control form-control-sm" name="Claim[np_paper][]" required="">
                <option value="">Select News Paper</option>
                <?php 
                foreach($newspapers as $n){
                    $n1 = Yii::$app->utility->encryptString($n);
                    echo "<option value='$n1'>$n</option>";
                }
                ?>
            </select>
        </td>
        <td></td><td></td>
        <td><input type="number" name="Claim[july_month_amt]" class="form-control form-control-sm" required="" /> </td>
    </tr>
    <tr>
        <td>Aug-<?=$yr[0]?></td>
        <td><select class="form-control form-control-sm" name="Claim[np_paper][]" required="">
                <option value="">Select News Paper</option>
                <?php 
                foreach($newspapers as $n){
                    $n1 = Yii::$app->utility->encryptString($n);
                    echo "<option value='$n1'>$n</option>";
                }
                ?>
            </select>
        </td>
        <td></td><td></td>
        <td><input type="number" name="Claim[aug_month_amt]" class="form-control form-control-sm" required="" /> </td>
    </tr>
    <tr>
        <td>Sept-<?=$yr[0]?></td>
        <td><select class="form-control form-control-sm" name="Claim[np_paper][]" required="">
                <option value="">Select News Paper</option>
                <?php 
                foreach($newspapers as $n){
                    $n1 = Yii::$app->utility->encryptString($n);
                    echo "<option value='$n1'>$n</option>";
                }
                ?>
            </select>
        </td>
        <td></td><td></td>
        <td><input type="number" name="Claim[sept_month_amt]" class="form-control form-control-sm" required="" /> </td>
    </tr>
    <tr>
        <td>Oct-<?=$yr[0]?></td>
        <td><select class="form-control form-control-sm" name="Claim[np_paper][]" required="">
                <option value="">Select News Paper</option>
                <?php 
                foreach($newspapers as $n){
                    $n1 = Yii::$app->utility->encryptString($n);
                    echo "<option value='$n1'>$n</option>";
                }
                ?>
            </select>
        </td>
        <td></td><td></td>
        <td><input type="number" name="Claim[oct_month_amt]" class="form-control form-control-sm" required="" /> </td>
    </tr>
    <tr>
        <td>Nov-<?=$yr[0]?></td>
        <td><select class="form-control form-control-sm" name="Claim[np_paper][]" required="">
                <option value="">Select News Paper</option>
                <?php 
                foreach($newspapers as $n){
                    $n1 = Yii::$app->utility->encryptString($n);
                    echo "<option value='$n1'>$n</option>";
                }
                ?>
            </select>
        </td>
        <td></td><td></td>
        <td><input type="number" name="Claim[nov_month_amt]" class="form-control form-control-sm" required="" /> </td>
    </tr>
    <tr>
        <td>Dec-<?=$yr[0]?></td>
        <td><select class="form-control form-control-sm" name="Claim[np_paper][]" required="">
                <option value="">Select News Paper</option>
                <?php 
                foreach($newspapers as $n){
                    $n1 = Yii::$app->utility->encryptString($n);
                    echo "<option value='$n1'>$n</option>";
                }
                ?>
            </select>
        </td>
        <td></td><td></td>
        <td><input type="number" name="Claim[dec_month_amt]" class="form-control form-control-sm" required="" /> </td>
    </tr>
    <tr>
        <td>Jan-<?=$yr[1]?></td>
        <td><select class="form-control form-control-sm" name="Claim[np_paper][]" required="">
                <option value="">Select News Paper</option>
                <?php 
                foreach($newspapers as $n){
                    $n1 = Yii::$app->utility->encryptString($n);
                    echo "<option value='$n1'>$n</option>";
                }
                ?>
            </select>
        </td>
        <td></td><td></td>
        <td><input type="number" name="Claim[jan_month_amt]" class="form-control form-control-sm" required="" /> </td>
    </tr>
    <tr>
        <td>Feb-<?=$yr[1]?></td>
        <td><select class="form-control form-control-sm" name="Claim[np_paper][]" required="">
                <option value="">Select News Paper</option>
                <?php 
                foreach($newspapers as $n){
                    $n1 = Yii::$app->utility->encryptString($n);
                    echo "<option value='$n1'>$n</option>";
                }
                ?>
            </select>
        </td>
        <td></td><td></td>
        <td><input type="number" name="Claim[feb_month_amt]" class="form-control form-control-sm" required="" /> </td>
    </tr>
    <tr>
        <td>Mar-<?=$yr[1]?></td>
        <td><select class="form-control form-control-sm" name="Claim[np_paper][]" required="">
                <option value="">Select News Paper</option>
                <?php 
                foreach($newspapers as $n){
                    $n1 = Yii::$app->utility->encryptString($n);
                    echo "<option value='$n1'>$n</option>";
                }
                ?>
            </select>
        </td>
        <td></td><td></td>
        <td><input type="number" name="Claim[mar_month_amt]" class="form-control form-control-sm" required="" /> </td>
    </tr>
</table>
<div class="row">
    <div class="col-sm-4">
        <label>Browse PDF</label>
        <input type='file' accept=".pdf" class="form-control form-control-sm pdf_file" name="doc_file" /><span style="color: red;font-size: 12px;">Max File is <?=FTS_Doc_Size?>MB</span>
    </div>
    <div class="col-sm-8">
        <div class="text-center">
        <br>
        <input type='submit' class="btn btn-success btn-sm" value='Submit to Finance' />
        <a href="<?=Yii::$app->homeUrl?>employee/reimbursement/annual?securekey=<?=$menuid?>" class="btn btn-danger btn-sm">Back</a>
    </div>
    </div>
</div>

<?php ActiveForm::end();?>
