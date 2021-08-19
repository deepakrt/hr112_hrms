<?php
$this->title = "Income Tax Details";

//echo "<pre>";print_r($incometax);
?>
<input type='hidden' id='menuid' value='<?=$menuid?>' />
<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered">
            <tr style="background: lightgrey;">
                <th colspan="4">Select Financial Year</th>
            </tr>
            <tr>
                <td>Financial Year</td>
                <td ><select class="form-control form-control-sm itaxfy">
                        <?php 
                        foreach($fy as $f){
                            $aa = Yii::$app->utility->encryptString($f);
                            $SELECTED = "";
                            if($selectedFy == $f){
                                $SELECTED = "selected=selected";
                            }
                            echo "<option value='$aa' $SELECTED>$f</option>";
                        }
                        ?>
                    </select>
                </td>
                <td>Open for Projections</td>
                <td>Yes</td>
            </tr>
            <tr>
                <td>PAN</td>
                <td>AAAAA1111A</td>
                <td></td>
                <td></td>
            </tr>
        </table>
    </div>
</div>
<br>
<div class="row">
    <div class="col-sm-12 text-right">
        <a href="javascript:void(0)" style="padding: 0px 10px;" data-key='<?=Yii::$app->utility->encryptString($selectedFy)?>' class="linkcolor" id='incomeslip' data-toggle="modal" data-target="#viewincomeslip">Income Slip</a>
        <a href="<?=Yii::$app->homeUrl?>employee/incometaxdetails/downloadtaxslip?securekey=<?=$menuid?>&fn=<?=Yii::$app->utility->encryptString($selectedFy)?>" target="_blank" style="padding: 0px 10px;" class="linkcolor">Tax Slip</a>
        <a href="javascript:void(0)" style="padding: 0px 10px;" class="linkcolor">Form 16</a>
    </div>
    <div class="col-sm-6">
        <table class="table table-bordered">
            <tr style="background: lightgrey;">
                <th colspan="2">Projected Gross / Deductions</th>
            </tr>
            <tr>
                <td>Estimated Gross Income</td>
                <td align='right' width='25%'><?php 
                    if(empty($incometax['grossIncome'])){
                    echo '0';
                }else{ echo $incometax['grossIncome']; }?></td>
            </tr>
            <tr>
                <td>Estimated total CPF/GPF</td>
                <td align='right'><?php if(empty($incometax['ded_empyee_pf_amt'])){
                    echo '0';
                }else{ echo $incometax['ded_empyee_pf_amt'];}?></td>
            </tr>
            <tr>
                <td>Estimated total VCPF/VGPF</td>
                <td align='right'>0</td>
            </tr>
            <tr>
                <td>Estimated total CGEIS</td>
                <td align='right'>0</td>
            </tr>
            <tr>
                <td>Estimated total Pension</td>
                <td align='right'>0</td>
            </tr>
            <tr>
                <td>Estimated total PT</td>
                <td align='right'>0</td>
            </tr>
        </table>
    </div>
    <div class="col-sm-6">
        <table class="table table-bordered">
            <tr style="background: lightgrey;">
                <th colspan="2">Tax Summary</th>
            </tr>
            <tr>
                <td>[A] Income Tax</td>
                <td align='right' width='25%'>0</td>
            </tr>
            <tr>
                <td>[B] Tax Rebate</td>
                <td align='right'>0</td>
            </tr>
            <tr>
                <td><b>[C] Net Income Tax [A-B]</b></td>
                <td align='right'>0</td>
            </tr>
            <tr>
                <td>[D] Education Cess</td>
                <td align='right'>0</td>
            </tr>
            <tr>
                <td><b>[E] Total Tax Payable [C+D]</b></td>
                <td align='right'>0</td>
            </tr>
            <tr>
                <td>[F] Relief Under Section89</td>
                <td align='right'>0</td>
            </tr>
            <tr>
                <td><b>[G] Net Tax Payable [E-F]</b></td>
                <td align='right'>0</td>
            </tr>
            <tr>
                <td>[H] Tax Deducted Till Date</td>
                <td align='right'>0</td>
            </tr>
            <tr>
                <td><b>[I] Tax Due/Refund [G-H]</b></td>
                <td align='right'>0</td>
            </tr>
            <tr>
                <td colspan="2" style="font-style: italic;color:grey;">Note: Calculated using Approved Amount of the projection.</td>
            </tr>
        </table>
    </div>
</div>
<div class="modal fade" id="viewincomeslip" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Income Tax Slip</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="html_view_incomeslip"></div>
            </div>
        </div>
    </div>
</div>