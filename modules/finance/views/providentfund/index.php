<?php
use yii\widgets\ActiveForm;
$this->title ="Provident Fund";
?>
<?php ActiveForm::begin(['method'=>'GET']); ?>
<div class="row">
    <div class="col-sm-3">
        <label>Select Month</label>
        <select class="form-control form-control-sm" id="search_pf_month" name="PF[month]" required="">
            <option value="">Select Month</option>
            <?php 
            for ($m=1; $m<=12; $m++) {
                $selected = "";
                if($month == $m){ $selected = "selected='selected'"; }
                $month1 = date('F', \mktime(0,0,0,$m, 1, date('Y')));
                $m1 = Yii::$app->utility->encryptString($m);
                echo "<option $selected value='$m1'>$month1</option>";
            }
            ?>
        </select>
    </div>
    <div class="col-sm-3">
        <label>Select Year</label>
        <?php 
            $curYr = date('Y');
            $nextYr = $curYr+1;
            $prevYr = $curYr-1;

            $selcurYr = $selnextYr = $selprevYr = "";

            if($year == $curYr){
                $selcurYr = "selected='selected'";
            }elseif($year == $nextYr){
                $selnextYr = "selected='selected'";
            }elseif($year == $prevYr){
                $selprevYr = "selected='selected'";
            }

            $curYr1 = Yii::$app->utility->encryptString($curYr);
            $nextYr1 = Yii::$app->utility->encryptString($nextYr);
            $prevYr1 = Yii::$app->utility->encryptString($prevYr);
        ?>
        <select class="form-control form-control-sm" id="search_pf_year" name="PF[year]" required="">
            <option value="">Select Year</option>
            <?php 
            echo "<option $selprevYr value='$prevYr1'>$prevYr</option>";
            echo "<option $selcurYr value='$curYr1'>$curYr</option>";
            echo "<option $selnextYr value='$nextYr1'>$nextYr</option>";
            
            ?>
        </select>
    </div>
    <div class="col-sm-3">
        <label>Select Status</label>
        <?php 
        $PaidSel = $ProSel = "";
        if($status == "Projected"){ 
            $ProSel = "selected='selected'";
        }elseif($status == "Paid"){ 
            $PaidSel = "selected='selected'";
        }
        ?>
        <select class="form-control form-control-sm" id="search_pf_status" name="PF[status]" required="">
            <option value="">Select Status</option>
            <?php 
            $projected = Yii::$app->utility->encryptString("Projected");
            $paid = Yii::$app->utility->encryptString("Paid");
            echo "<option $ProSel value='$projected'>Projected</option>";
            echo "<option $PaidSel value='$paid'>Paid</option>";
            ?>
        </select>
    </div>
    <div class='col-sm-3 text-center formbtn' style='padding-top:25px;'>
        <button type='submit' class='btn btn-success btn-sm' id="searchpdf">Search</button>
        <a href='' class='btn btn-danger btn-sm'>Cancel</a>
    </div>
</div>
<?php ActiveForm::end(); 
if(!empty($pfdata)){ ?>
<hr>
<div class="row">
    <?php 
    $d = "$year-$month-01";
    $my = date('M-Y', strtotime($d));
    if($status == "Projected"){ 
    ?>
    <div class="col-sm-6">
        <h5 class="text-justify">Click to Submit PF for the <?=$my?>.</h5>
    </div>
    <div class="col-sm-5">
        <?php ActiveForm::begin(['action'=>Yii::$app->homeUrl."finance/providentfund/updatepf?securekey=$menuid", 'method'=>'POST']); ?>
        <input type="hidden" name="Paid[year]" value="<?=Yii::$app->utility->encryptString($year)?>" />
        <input type="hidden" name="Paid[month]" value="<?=Yii::$app->utility->encryptString($month)?>" />
        <button type='submit' class='btn btn-outline-primary btn-sm' >Pay PF for the month <?=$my?></button>
        <?php ActiveForm::end(); ?>
    </div>
    <?php } ?>
    <div class="col-sm-12">
        <hr>
        <table id="dataTableShow" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>Sr.</th>
                    <th>Emp Code</th>
                    <th>Financial Year</th>
                    <th>Member PF</th>
                    <th>Member VPF</th>
                    <th>Employer PF</th>
                    <th>Employer FPF</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            <?php 
            if(!empty($pfdata)){
                $i=1;
                foreach($pfdata as $pf){
                  echo "<tr>
                        <td>$i</td>
                        <td>".$pf['employee_code']."</td>
                        <td>".$pf['financial_year']."</td>
                        <td>".number_format($pf['member_pf'], 2)."</td>
                        <td>".number_format($pf['member_vpf'], 2)."</td>
                        <td>".number_format($pf['employer_pf'], 2)."</td>
                        <td>".number_format($pf['employer_fpf'], 2)."</td>
                        <td>".$pf['status']."</td>
                        </tr>";
                    $i++;
                }
            }
            ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Sr.</th>
                    <th>Emp Code</th>
                    <th>Financial Year</th>
                    <th>Member PF</th>
                    <th>Member VPF</th>
                    <th>Employer PF</th>
                    <th>Employer FPF</th>
                    <th>Status</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<?php }
?>
