<?php 
$allDepts= Yii::$app->utility->get_dept(NULL);
use app\models\EfileDakGroups;
use app\models\EfileDakGroupMembers;
use app\models\EfileDakNotes;
use app\models\RbacEmployeeRole;

$alhods = RbacEmployeeRole::find()->where(['role_id'=>'2', 'is_active'=>'Y'])->all();
//$alhods = Yii::$app->utility->get_all_hod();
if(!empty($file_id)){
$fileinfo = Yii::$app->fts_utility->efile_get_dak($file_id, NULL, NULL, NULL);	
}
$fwdLabel = "Forward";
if(!empty($file_id)){
	$fwdLabel = "Forward / Return";
}

// echo "<pre>";print_r(Yii::$app->user->identity->e_id);
//$show = "Y";
//if(!empty($fileinfo)){
//    if($fileinfo['access_level'] == 'R'){
//        $show = "N";
//    }
//}
//if($show == 'Y'){
?>

<div class='row'>
    <?php 
    if(Yii::$app->user->identity->e_id == '343252'){ ?>
    <div class='col-sm-12'>
        <hr class='hrline'>
        <button type="button" class="btn btn-secondary btn-sm" id="ED_Approve" onclick="EDapprove('A')">Approve</button>
        <button type="button" class="btn btn-secondary btn-sm" id="ED_NotApprove" onclick="EDapprove('NA')">Not Approve</button>
        <input type="hidden" name="Forward[ED_Note]" readonly="" id="ED_Note" />
    </div>
    <?php } ?>
    <input type="hidden" id="check_ed_approval" value="N" />
    <div class='col-sm-12'>
        <hr class='hrline'>
        <h6><b><span class="hindishow">इस फ़ाइल को अग्रेषित करना चाहते हैं? / </span>Want to forward this file?</b></h6><br>
        <button type='button' class='btn btn-secondary btn-sm' id='btn_show_yes' onclick='forwardOption("Y")'><?=$fwdLabel?> </button>
        <?php 
        $showFwdBtn = "N";
        if(!empty($file_id)){
        if($fileinfo['emp_code'] != Yii::$app->user->identity->e_id){ 
            if($movement['fwd_to'] == 'G'){
                $members = EfileDakGroupMembers::find()->where(['dak_group_id' => $movement['dak_group_id'], 'is_active'=>'Y'])->asArray()->all();
                foreach($members as $m){
                    if($m['employee_code'] == Yii::$app->user->identity->e_id AND $m['group_role'] == 'CH'){
                        $showFwdBtn = "N";
                    }
                }
            }
            if($movement['fwd_to'] == 'E'){
                    $showFwdBtn == 'Y';
            }
        }else{ ?>

        <button type='button' class='btn btn-success btn-sm' id='btn_show_no' onclick='forwardOption("N")'>No</button>
        <?php } 
        if($showFwdBtn == 'Y'){
            $file_id = Yii::$app->utility->encryptString($fileinfo['file_id']);
            $movement_id = Yii::$app->utility->encryptString($movement['id']);
            $fwdUrl = Yii::$app->homeUrl."efile/inbox/forwardbacktosender?securekey=$menuid&key=$file_id&key2=$movement_id
            ";
            echo "<a href='$fwdUrl' class='btn btn-info btn-sm' id='fwdback'>Forward back to Sender</a>";
        }

        }?>

        <input type='hidden' name='forward_dak' id='forward_dak' value='N' readonly />
    </div>
</div>
<div class='row' id='forwardHtml' style='display:none;'>
    <div class='col-sm-12'><hr class='hrline'></div>
    <div class='col-sm-9'><h6><b>Forward To</b></h6></div>
    <?php if(!empty($file_id) AND Yii::$app->user->identity->role != '7'){ ?>
    <div class='col-sm-12'>
        <input type="checkbox" name="is_hierarchy" id="file_is_hierarchy" />&nbsp;&nbsp; Forward in Hierarchy?
    </div>
    <?php } ?>
    <div class='col-sm-12'>
        <button type="button" class="btn btn-secondary btn-sm selectsenttype" data-key='I' id='dak_btn_individual'>Individual</button>
        <button type="button" class="btn btn-secondary btn-sm selectsenttype" data-key='G' id='dak_btn_group'>Group / Committee</button>
        <?php if(!empty($file_id) AND Yii::$app->user->identity->role == '7'){ ?>
        <button type="button" class="btn btn-secondary btn-sm selectsenttype" data-key='H' id='dak_btn_hods'>All HOD's</button>
        <button type="button" class='btn btn-success btn-sm' onclick="fwdtoheadmmg()"  data-key='MMG' id='dak_btn_mmg'>Head MMG</button>
        <?php } ?>
        <button type="button" class="btn btn-secondary btn-sm selectsenttype" data-key='A' id='dak_btn_allemp'>All Employee</button>
        
        <input type='hidden' name='forward_type' id='forward_type' />
    </div>
</div>
<div class='row' id='dak_btn_individual_html' style='display:none;padding-top: 10px;'>
    <div class='col-sm-3'>
        <label><span class="hindishow12">विभाग / </span> Department</label>
        <select class="form-control form-control-sm" onchange="get_dept_emp_list('indi_dept_id', 'indi_emp_code')" id="indi_dept_id" name='indi_dept_id'>
            <option value="">Select Department</option>
            <?php 
            if(!empty($allDepts)){
                foreach($allDepts as $d){
                    $dept_id = Yii::$app->utility->encryptString($d['dept_id']);
                    $dept_name = $d['dept_name'];
                    echo "<option value='$dept_id'>$dept_name</option>";
                }
            }
            ?>
        </select>
    </div>
    <div class='col-sm-3'>
        <label><span class="hindishow12">कर्मचारी / </span> Employee</label>
        <select class="form-control form-control-sm showcchtml" id='indi_emp_code' name='indi_emp_code' >
            <option value="">Select Employee</option>
        </select>
    </div>
    <div class='col-sm-12 file_cc_html' style="display:none;">
        <br>
        <input type="checkbox" id="file_cc" name="file_cc" />&nbsp;&nbsp; Copy to (if any):-
        <div class="row showcss_html_" style="display: none;">
            <div class='col-sm-3'>
                <label><span class="hindishow12">विभाग / </span> Department</label>
                <select class="form-control form-control-sm" onchange="cc_dept_emp_list('cc_dept_id', 'cc_emp_list')" id="cc_dept_id">
                    <option value="">Select Department</option>
                    <?php 
                    if(!empty($allDepts)){
                        foreach($allDepts as $d){
                            $dept_id = Yii::$app->utility->encryptString($d['dept_id']);
                            $dept_name = $d['dept_name'];
                            echo "<option value='$dept_id'>$dept_name</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class='col-sm-9 selectccc'>
                <ul id="cc_emp_list"></ul>
            </div>
            <div class='col-sm-12 finalselectedcc' style="display: none;margin-top:10px;">
                <h6><b>Final Employees for CC</b></h6>
                <ul id="final_cc_list"></ul>
            </div>
        </div>
    </div>
    
</div>
<?php if(!empty($file_id) AND Yii::$app->user->identity->role == '7'){ ?>
<div class='row' id='dak_btn_hod_html' style='display:none;padding-top: 10px;'>
    <div class='col-sm-3'>
        <label><span class="hindishow12">विभाग के प्रमुख / </span> All HOD's</label>
        <select class="form-control form-control-sm" id='hod_emp_code' name='hod_emp_code' >
            <option value="">Select HOD</option>
            <?php 
            if(!empty($alhods)){
                foreach($alhods as $a){
                    $employee_code = Yii::$app->utility->encryptString($a['employee_code']);
                    $emp_name = Yii::$app->utility->get_employees($a['employee_code']);
                    $emp_name = "$emp_name[fullname], $emp_name[desg_name]";
                    echo "<option value='$employee_code'>$emp_name</option>";
                }
            }
            ?>
        </select>
    </div>
</div>
<?php } ?>
<div class='row' id='dak_btn_group_html' style='display:none;'>
    <div class='col-sm-12'>
        <hr class='hrline'>
        <?php  if(!empty($file_id)){ ?>
        <button type='button' class='btn btn-secondary btn-sm btn-xs existgrp' data-key='E' id='btn_existing_group'>Existing Group</button>
        <?php } ?>
        <button type='button' class='btn btn-secondary btn-sm btn-xs existgrp' data-key='C' id='btn_create_group'>Create New Group / Committee</button>
        <input type='hidden' name='group_type' id='group_type' />
    </div>
    <?php  if(!empty($file_id)){ 
    $groups = EfileDakGroups::find()->where(['file_id' => $file_id, 'is_active'=>'Y'])->asArray()->all();
    ?>
	<div class='col-sm-9' id='showexistinggroup' style='display:none;'>
		<label>Existing Groups / Committees</label>
		<ul class='exitlist'>
			<?php 
			if(!empty($groups)){
				foreach($groups as $g){
					$dak_group_id = Yii::$app->utility->encryptString($g['dak_group_id']);
					echo "<li><input type='checkbox' name='ExitGroup[]' value='$dak_group_id' /> ".$g['group_name']."</li>
					<li>";
					$members = EfileDakGroupMembers::find()->where(['dak_group_id' => $g['dak_group_id'], 'is_active'=>'Y'])->asArray()->all();
					if(!empty($members)){
						echo "<h6><b>Group Members</b></h6>";
						$i=1;
						foreach($members as $m){
							$group_role = "Member";
							if($m['group_role'] == 'CH'){
								$group_role = "<b>Chairman</b>";
							}elseif($m['group_role'] == 'C'){
								$group_role = "<b>Convenor</b>";
							}
							$memberInfo = Yii::$app->utility->get_employees($m['employee_code']);
							$memberInfo = $memberInfo['fullname'].", ".$memberInfo['desg_name']." ($memberInfo[dept_name]) ($group_role)";
							echo "$i. $memberInfo <br>";
							$i++;
						}
					}
					echo "</li>";
				}
			}else{
				echo "<li>No Group / Committee Found</li>";
			}
			?>
		</ul>
	</div>
	<?php } ?>
	<div class='col-sm-3'style='display:none;'></div>
	<div class='col-sm-12' id='creategroup'style='display:none;'>
		<div id='create_member_dept_emp_list' class='col-sm-12'></div>
	</div>
</div>
<?php 
$showTime = "N";
if(empty($model)){
    $showTime = "Y";
}
if(!empty($file_id)){
//    echo "<pre>";print_r($movement);
     $showTime = "N";
}

if($showTime == 'Y'){
?>
<div class='row' id='timeboundhtml' style='display:none;'>
	<div class='col-sm-12'>
		<hr class='hrline'>
	</div>
	<div class='col-sm-3'>
            <label><span class="hindishow">क्या समय सीमा है?</span><br>Is Time Bound?</label>
		<select class="form-control form-control-sm" id='is_time_bound' name='is_time_bound' >
                    <option value="<?=Yii::$app->utility->encryptString("N")?>" data-key='N'>No</option>
                    <option value="<?=Yii::$app->utility->encryptString("Y")?>"  data-key='Y'>Yes</option>
		</select>
	</div>
	<div class='col-sm-3'>
            <label><span class="hindishow">प्रतिक्रिया तारीख </span><br>Response Date Required</label>
		<input type='text' class='form-control form-control-sm' id='response_date' placeholder='Response Date' name='response_date'  />
	</div>
</div>

<?php 
}	

$btnshow = "";

$submitBtn = "Submit";
$fun = "onclick='return validateInitaiteFileForm()'";

$note_required = "N";
$is_new_note = "Y";

if(!empty($file_id)){
    $btnshow = "style='display:none;'  id='forwardsubmitbtn'";
    
    $submitBtn = "Forward";
    $fun = "onclick='return validateForwardFileForm()'";
    
    $notes = EfileDakNotes::find()->where(['file_id' => $file_id, 'status'=>'S', 'content_type'=>'N', 'is_active'=>'Y'])->orderBy(['noteid' => SORT_DESC])->asArray()->all();
if(!empty($notes)){
    $is_new_note = "N";
    if($notes[0]['added_by'] != Yii::$app->user->identity->e_id){
        $note_required = "Y";
    }
}
}

?>
<input type="hidden" name="Forward[note_required]" id="note_required" value="<?=$note_required?>" readonly="" />
<input type="hidden" name="Forward[is_new_note]" id="is_new_note" value="<?=$is_new_note?>" readonly="" />
<input type='hidden' id='fwd_note_subject' name='Forward[fwd_note_subject]' value='' />
<input type='hidden' id='fwd_note_comment' name='Forward[fwd_note_comment]' value='' />
<div class='row' <?=$btnshow?> >
	<div class='col-sm-12 text-center'>
		<hr class='hrline'>
		
                <button type='button' id="btn_fwd_submit" <?=$fun?>  class='btn btn-success btn-sm'><?=$submitBtn?></button>
		<a href='' class='btn btn-danger btn-sm'>Cancel</a>
	</div>
</div>

<?php //  } ?>