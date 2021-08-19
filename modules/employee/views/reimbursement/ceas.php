<?php
$this->title="Children Education Allowance Scheme";
use yii\widgets\ActiveForm;
$childClaims = Yii::$app->hr_utility->hr_get_CEA_child_details(Yii::$app->user->identity->e_id,NULL, $selectfnyr, NULL);
//echo "<pre>";print_r($childClaims);
?>

<input type="hidden" id="menuid" value="<?=$menuid?>" readonly="" />
<style>
    .card-header {
        padding: 5px;; 
	background-color: #FBE7C3;
	border-bottom: 1px solid #FBE7C3;
    }
    .fn_accordian {
	border: none;
	background: none;
	font-size: 14px;
	font-weight: bold;
	width: 100%;
	text-align: left;
	cursor: pointer;
    }
    .ct{
        width: 75%;
    }
</style>
<div id="accordion">
    <div class="card">
        <div class="card-header" id="headingOne">
          <h5 class="mb-0">
            <button class="fn_accordian" data-toggle="collapse" data-target="#fnyrs" aria-expanded="true" aria-controls="fnyrs">
              &rsaquo; Select Financial Year
            </button>
          </h5>
        </div>

        <div id="fnyrs" class="collapse show" style="display: block !important;" aria-labelledby="headingOne" data-parent="#accordion">
            <div class="card-body">
                
                <div class="row">
                    <div class="col-sm-4">
                        <label>Select Financial Year</label>
                    </div>
                    <div class="col-sm-4">
                        <select class="form-control form-control-sm" id="ceaFnYr">
                            <?php 
                            if(!empty($fnYears)){
                                foreach($fnYears as $fnYear){
                                    $id = Yii::$app->utility->encryptString($fnYear);
                                    if($selectfnyr == $fnYear){
                                        echo "<option value='$id' selected='selected'>".$fnYear."</option>";
                                    }else{
                                        echo "<option value='$id'>".$fnYear."</option>";
                                    }
                                }
                            }else{
                                echo "<option value='' selected='selected'>Details Not Added By HR.</option>";
                            }
                            ?>
                        </select>  
                        <br>
                    </div>
                </div>
                <?php 
                if(empty($allowances)){
                ?>
                <div class="row">
                    <div class="col-sm-4">
                        <label>Entitlement Period</label>
                    </div>
                    <div class="col-sm-5">
                        <?=$selectfnyr?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <label>Child Education Allowance</label>
                    </div>
                    <div class="col-sm-5">
                        Rs. null per child per academic year
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <label>Hostel Subsidy</label>
                    </div>
                    <div class="col-sm-5">
                        Rs. null per child per academic year
                    </div>
                </div>
                <?php 
                }else{
                    ?>
                <div class="row">
                    <div class="col-sm-4">
                        <label>Entitlement Period</label>
                    </div>
                    <div class="col-sm-5">
                        <?php 
                        $yr = explode('-', $selectfnyr);
                        $yr1 = $yr['0'];
                        $yr2 = $yr['1'];
                        echo "01-04-$yr1 to 31-03-$yr2";
                        ?>
                    </div>
                </div>
                <?php 
                    $masterEmp_Allowances = Emp_Allowances;
                    foreach($allowances as $a){
                        $allowance_type = $a['allowance_type'];
                        foreach($masterEmp_Allowances as $m){
                            if($m['shortname'] == $allowance_type){
                                $name = $m['name'];
                                $sanc_type = "Rs. ".$a['amount']." per child per academic year";
                                if($a['sanc_type'] == 'All'){
                                    $sanc_type = "Rs. ".$a['amount']." per academic year";
                                }
                            ?>
                <div class="row">
                    <div class="col-sm-4">
                        <label><?=$name?></label>
                    </div>
                    <div class="col-sm-5">
                        <?=$sanc_type?>
                    </div>
                </div>
                            <?php }
                        }
                    }
                }
                ?>
                
           
            </div>
        </div>
    </div>
    <?php 
    if(!empty($fnYears)){?>
    <div class="card">
        <div class="card-header" id="headingOne">
          <h5 class="mb-0">
            <button class="fn_accordian" data-toggle="collapse" data-target="#childclaim" aria-expanded="true" aria-controls="fnyrs">
              &rsaquo; Child / Children Available for Claim:-
            </button>
          </h5>
        </div>

        <div id="childclaim" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
            <div class="card-body">
                <table class="table table-hover">
                    <tr>
                        <th>Name</th>
                        <th>Date of Birth</th>
                        <th>Std</th>
                        <th>School</th>
                        <th>AY Start</th>
                        <th>AY End</th>
                        <th>Delete</th>
                        <th>Claim Type</th>
                        <th></th>
                    </tr>                
                <?php 
//                echo "<pre>";print_r($childClaims);
                if(!empty($childClaims)){
                    $j=1;
                    foreach($childClaims as $c){
                        $ea_id = Yii::$app->utility->encryptString($c['ea_id']);
                        $fy = Yii::$app->utility->encryptString($c['financial_year']);
                        $delUrl = Yii::$app->homeUrl."employee/reimbursement/deletechilddetail?securekey=$menuid&ea_id=$ea_id&fy=$fy";
                        $delUrl = "<a href='$delUrl' class='deletehalt'><img src='".Yii::$app->homeUrl."images/del.gif' /></a>";
                        $claimUrl = Yii::$app->homeUrl."employee/reimbursement/claimcea?securekey=$menuid&ea_id=$ea_id&fy=$fy";
                        $claimUrl = "<a href='javascript:void(0)' data-key='$j' class='linkcolor applyclaim'>Claim</a>";
                        
                        $chkclaimed = Yii::$app->hr_utility->hr_get_edu_allowance_claim($c['ea_id'], Yii::$app->user->identity->e_id,$c['financial_year'], "Submitted,In-Process,Approved,Sanctioned");
                        ?>
                    <tr>
                        <td>(<?=$c['relation_name']?>) <?=$c['m_name']?></td>
                        <td><?=date('d-m-Y', strtotime($c['m_dob']))?></td>
                        <td><?=$c['class_std']?></td>
                        <td><?=$c['school_name']?></td>
                        <td><?=date('d-m-Y', strtotime($c['ay_start']))?></td>
                        <td><?=date('d-m-Y', strtotime($c['ay_end']))?></td>
                        
                        <?php 
                        if(empty($chkclaimed)){
                        ?>
                        <td><?=$delUrl?></td>
                        <td>
                            <select id="claim_type_<?=$j?>" data-id="<?=$ea_id?>" data-fy="<?=$fy?>" class="ct">
                                <option value=''>Select Claim Type</option>
                                <?php 
                                $Emp_Allowances = Emp_Allowances;
                                foreach($Emp_Allowances as $e){
                                    if($e['is_active'] == 'Y'){
                                        $s = Yii::$app->utility->encryptString($e['shortname']);
                                        $name = $e['name'];
                                        echo "<option value='$s'>$name</option>";
                                    }
                                }
                                ?>
                            </select>
                        </td>
                        <td><?=$claimUrl?></td>
                        <?php }else{
                            echo "<td>-</td>";
                            echo "<td>-</td>";
                            echo "<td style='color:red;'>Claimed</td>";
                        }?>
                    </tr>   
                <?php $j++; }
                }else{
                    echo "<tr><td colspan='9'>No Record Found</td></tr>";
                }
                ?>
                </table>
            </div>
        </div>
    </div>
    <?php  } 
    if(!empty($childs) AND !empty($allowances) AND !empty($fnYears)){
    ?>
    <div class="card">
        <div class="card-header" id="headingOne">
          <h5 class="mb-0">
            <button class="fn_accordian" data-toggle="collapse" data-target="#childdetail" aria-expanded="true" aria-controls="fnyrs">
              &rsaquo; Fill Academic Details of Child / Children to Claim:
            </button>
          </h5>
        </div>

        <div id="childdetail" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
            <div class="card-body">
            <?php 
            
                ActiveForm::begin(["action"=> Yii::$app->homeUrl."employee/reimbursement/eduallowancechilddetails?securekey=$menuid", 'id'=>'ceform']);
                
                $i=0;
                $j=1;
                $Stds_list = Stds_list;
                $Emp_Allowances = Emp_Allowances;
                foreach($childs as $c){
                ?>
            <div class="row">
                <div class="col-sm-1">
                    <input type="checkbox" data-key="<?=$j?>" name='Edu[<?=$i?>][ef_id]' value='<?=Yii::$app->utility->encryptString($c['ef_id'])?>'  />
                </div>
                <div class="col-sm-3">
                    <input type='hidden' name='Edu[<?=$i?>][fnyr]' value='<?=Yii::$app->utility->encryptString($selectfnyr)?>' />
                    <b>Name : </b><br>(<?=$c['relation_name']?>) <?=$c['m_name']?>
                </div>
                <div class="col-sm-2">
                    <b>Date of Birth : </b> <br><?=date('d-m-Y', strtotime($c['m_dob']))?>
                </div>
                <div class="col-sm-3">
                    <label>Select Std.</label>
                    <select class="form-control form-control-sm" id="std-<?=$j?>" name='Edu[<?=$i?>][class_std]'>
                        <option value=''>Select Std.</option>
                        <?php 
                        foreach($Stds_list as $S){
                            $std = Yii::$app->utility->encryptString($S);
                            echo "<option value='$std'>$S</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-sm-3">
                    <label>School Name</label>
                    <input type='text' class="form-control form-control-sm" id="school-<?=$j?>" name='Edu[<?=$i?>][school]' placeholder="Schoo Name"  />
                </div>
                <div class="col-sm-1"></div>
                <div class="col-sm-3">
                    <label>AY Start</label>
                    <input type='text' id="ay_start-<?=$j?>" class="form-control form-control-sm aydate" name='Edu[<?=$i?>][ay_start]'  placeholder="Schoo Name" readonly=""  />
                </div>
                <div class="col-sm-3">
                    <label>AY End</label>
                    <input type='text' id="ay_end-<?=$j?>" class="form-control form-control-sm aydate" name='Edu[<?=$i?>][ay_end]' placeholder="Schoo End" readonly=""  />
                </div>
            </div>
                <hr>
                <?php $i++;
                $j++; }
                ?>
                <div class="col-sm-12 text-center">
                    <input type='button' id="submit-eduform" class="btn btn-success btn-sm" value='Submit' />
                </div>
                <?php ActiveForm::end();?>
            
            
            </div>
        </div>
    </div>
    <?php }  ?>
    
    <div class="card">
        <div class="card-header" id="headingOne">
          <h5 class="mb-0">
            <button class="fn_accordian" data-toggle="collapse" data-target="#claimed" aria-expanded="true" aria-controls="fnyrs">
              &rsaquo; Application List:
            </button>
          </h5>
        </div>

        <div id="claimed" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
            <div class="card-body">
                <?php 
                $claims = Yii::$app->hr_utility->hr_get_edu_allowance_claim(NULL, Yii::$app->user->identity->e_id,$selectfnyr, "Submitted,In-Process,Approved,Rejected,Sanctioned");
//                echo "<pre>";print_r($claims);
                ?>
                <table class="table table-bordered table-hover">
                    <tr>
                        <th>Application Date</th>
                        <th>Status</th>
                        <th>Total Claimed Amt</th>
                        <th>Total Sanctioned Amt </th>
                        <th>Remarks</th>
                        <th>View</th>
                    </tr>
                    <?php 
                    if(!empty($claims)){
//                        echo "<pre>";print_r($claims);
                        foreach($claims as $c){
                            $remarks =  $totalClaimed="-";
                            if($c['claim_type'] == 'CEA'){
                                $totalClaimed = $c['books_amount']+$c['shoes_amount']+$c['notebooks_amount']+$c['uniform_amount']+$c['tuition_fees'];
                            }elseif($c['claim_type'] == 'HS'){
                                $totalClaimed = $c['hostel_fees'];
                            }
                            $totalSanc = $c['total_sanc_amt'];
                            if(empty($c['finance_approved_by'])){
                                $totalSanc = "-";
                            }else{
                                $remarks = substr($c['finance_remarks'],0,50);
                            }
                            $i=  rand(100, 1000);
                            ?>
                    <tr>
                        <td><input type="hidden" id="ea_id-<?=$i?>" value="<?=Yii::$app->utility->encryptString($c['ea_id'])?>" />
                        <input type="hidden" id="fy-<?=$i?>" value="<?=Yii::$app->utility->encryptString($c['financial_year'])?>" />
                        <?=date('d-m-Y', strtotime($c['created_date']))?></td>
                        <td><?=$c['status']?></td>
                        <td align='right'><?=$totalClaimed?></td>
                        <td align='right'><?=$totalSanc?></td>
                        <td title="<?=$remarks?>"><?=substr($remarks,0,25)?></td>
                        <td><a href="javascript:void(0)" data-key="<?=$i?>" class="linkcolor viewceaapp">View</a></td>
                    </tr>
                    <?php    }
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="mviewceaapp" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Children Education Allowance Application</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row" id='apphtml'>
                    
                </div>
            </div>
        </div>
</div>
</div>

