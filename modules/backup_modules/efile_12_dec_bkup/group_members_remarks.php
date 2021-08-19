<?php
use app\models\EfileDakGroupMembers;
use app\models\EfileDakGroupMembersRemarks;
use app\models\EfileDakGroupMemberApproval;
use app\models\EfileDakGroups;
use yii\widgets\ActiveForm;
$group_role = "";
if($movement["fwd_to"]=="G"){
	$members = EfileDakGroupMembers::find()->where(['dak_group_id' =>$movement['dak_group_id'], 'is_active'=>'Y'])->asArray()->all();
	$grpInfo = EfileDakGroups::find()->where(['dak_group_id' =>$movement['dak_group_id']])->asArray()->one();
        $gmem = Yii::$app->utility->get_employees($grpInfo['created_by']);
        $gmem = $gmem['fullname'].", ".$gmem['desg_name']." ($gmem[dept_name])";
        $grpDt = date('d-M, Y', strtotime($grpInfo['created_date']));
//	echo "<pre>";print_r($grpInfo);
        $grpHtml = "
                <h6 class='text-left'><b style='color:red'>Group / Committee Name : </b><b>$grpInfo[group_name]</b></h6>
                <h6 class='text-left'><b>Created By : $gmem on $grpDt</b> </h6>";
        
	$i=1;
	$html = "";
	$group_role = "";
	foreach($members as $m){
		if($m['employee_code'] == Yii::$app->user->identity->e_id){
			$group_role = $m['group_role'];
		}
		$memberInfo = Yii::$app->utility->get_employees($m['employee_code']);
		$memberInfo = $memberInfo['fullname'].", ".$memberInfo['desg_name']." ($memberInfo[dept_name])";
		if($m['group_role'] == 'CH'){
			$html .="<tr>
				<td>$i</td>
				<td>$memberInfo</td>
				<td>Chairman</td>
			</tr>";
			$i++;
		}elseif($m['group_role'] == 'M'){
			$html .="<tr>
				<td>$i</td>
				<td>$memberInfo</td>
				<td>Member</td>
			</tr>";
			$i++;
		}
	}
	$i = $i;
	foreach($members as $m){
		$memberInfo = Yii::$app->utility->get_employees($m['employee_code']);
		$memberInfo = $memberInfo['fullname'].", ".$memberInfo['desg_name']." ($memberInfo[dept_name])";
		if($m['group_role'] == 'C'){
			$html .="<tr>
				<td>$i</td>
				<td>$memberInfo</td>
				<td>Convenor</td>
			</tr>";
			$i++;
		}
	}
	echo "<hr class='hrline'>$grpHtml<h6 class='text-left'><b style='color:red'>Group / Committee Members </b></h6>";
        if($group_role == 'CH'){
            echo "<div class='text-right' ><button type='button' class='btn btn-outline-success btn-xs' onclick='btnnewmember()' data-toggle='modal' data-target='#addnewmember' data-backdrop='static' data-keyboard='false'>Add New Member</button><br><br></div>";
        }
	echo '<table class="table table-bordered"><thead class="thead-dark"><tr><th>Sr. No.</th><th>Member Name</th><th></th></tr></thead>'.$html.'</table>';

	$allremartks = EfileDakGroupMembersRemarks::find()->where(['dak_group_id' =>$movement['dak_group_id'], 'file_id'=>$file_id, 'status'=>'S', 'is_active'=>'Y'])->asArray()->all();
	// echo "<pre>";print_r($allremartks); die;
    if(!empty($allremartks)){
    		$f_id = Yii::$app->utility->encryptString($fileinfo['file_id']);
    	$mid = Yii::$app->utility->encryptString($movement['id']);
    	$dakgrpid = Yii::$app->utility->encryptString($movement['dak_group_id']);
    
    				$dwnRmrk = Yii::$app->homeUrl."efile/dakcommon/downloadgrpremarks?securekey=$menuid&key=$f_id&key1=$mid&key2=$dakgrpid";
        $remarksHTML = "<hr class='hrline'>
        <div class='row'>
        	<div class='col-sm-6'><h6 class='text-left'><b style='color:red'>Previous Remarks of Group / Committee Members </b></h6></div>
        	<div class='col-sm-6'><div class='text-right'><a href='$dwnRmrk' target='_blank' class='btn btn-success btn-sm'><b>Download Group / Committee Remarks</b></a></div><br></div>
        </div>
        
        
        
        
        <table class='table table-bordered '><thead class='thead-dark'><tr><th>Sr. No.</th><th>Member Name</th></tr></thead>";
        $i=1;
        foreach($allremartks as $m){
                $memberInfo = Yii::$app->utility->get_employees($m['employee_code']);
                $role = "Member";
                if($m['group_role'] == 'CH'){
                        $role = "Chairman";
                }elseif($m['group_role'] == 'C'){
                        $role = "Convenor";
                }
                $Group_Role = "";
                $memberInfo = $memberInfo['fullname'].", ".$memberInfo['desg_name']." ($role)";
                $date = date('d-m-Y H:i:s', strtotime($m['created_date']));
                $remarksHTML .= "
                        <tr>
                                <td>$i</td>
                                <td><u><b>$memberInfo inputs dated $date</b></u><br>$m[remarks]</td>
                        </tr>
                ";
                $i++;

        }
        $remarksHTML .= "</table>";
        
        if($group_role != 'CH'){
            $info = EfileDakGroupMembersRemarks::find()->where([
                'dak_group_id'=>$movement['dak_group_id'], 
                'file_id'=>$movement['file_id'], 
                'group_role'=>'CH', 
                'is_active' => 'Y',
                'status'=>"CHD"
            ])->one();
            
            if(!empty($info)){
                $remarksHTML .= "<h6 class='text-left'><b style='color:red'>Final Draft by Chairman</b></h6>
                        <textarea class='form-control form-control-sm' readonly>".$info->remarks."</textarea>
                        ";
            }
            
        }
        
        echo $remarksHTML;
    }
    
    
    
    $remarks_id = $remarks = "";
    $info = EfileDakGroupMembersRemarks::find()->where([
        'dak_group_id'=>$movement['dak_group_id'], 
        'file_id'=>$movement['file_id'], 
        'employee_code'=>Yii::$app->user->identity->e_id, 
        'group_role'=>$group_role, 
        'is_active' => 'Y',
        'status'=>"D"
    ])->one();
    
    
    if(!empty($info)){
        $remarks = $info->remarks;
        $remarks_id = Yii::$app->utility->encryptString($info->id);
    }
    $remksurl = Yii::$app->homeUrl."efile/inbox/addmemberremarks?securekey=$menuid";
    ActiveForm::begin(['action'=>$remksurl, 'id'=>'grpremarkform', 'options' => ['enctype' => 'multipart/form-data']]);
    $file_id = Yii::$app->utility->encryptString($fileinfo['file_id']);
    $movement_id = Yii::$app->utility->encryptString($movement['id']);
    $dak_group_id= Yii::$app->utility->encryptString($movement['dak_group_id']);
    $grouprole= Yii::$app->utility->encryptString($group_role);
    echo "<input type='hidden' name='Remarks[key]' value='$file_id' readonly />";
    echo "<input type='hidden' name='Remarks[key1]' value='$dak_group_id' readonly />";
    echo "<input type='hidden' name='Remarks[key2]' value='$movement_id' readonly />";
    echo "<input type='hidden' name='Remarks[membertype]' value='$grouprole' readonly />";
    echo "<input type='hidden' name='Remarks[remarks_id]' value='$remarks_id' readonly />";
    ?>
    <div class="row">
        <div class='col-sm-12'>
            <hr class='hrline'>
            <h6 class='text-center'><b><span class="hindishow">समूह / समिति (टिप्पणी) - </span>Group / Committee (Remarks / Comments) </b></h6>
        </div>
        <div class="col-sm-12 mb15">
            <textarea required="" id="memberremarks" name="Remarks[remarks]" class="form-control form-control-sm" placeholder="Remarks" rows='2' required><?=$remarks?></textarea>
        </div>
        <div class="col-sm-12 text-center mb15">
            <br>
			<input type='hidden' id='grp_remarks_submit_type' name='submit_type' />
            <button type='button' class="btn btn-dark btn-sm saveassbmt"  value="D">Save as Draft</button>
            <button type='button' class="btn btn-success btn-sm saveassbmt" value="S">Share with Group / Committee Members</button>
        </div>
    </div>
<?php ActiveForm::end(); 
    // Get Final for agree and disgree 
     $getFinal = EfileDakGroupMembersRemarks::find()->where([
        'dak_group_id'=>$movement['dak_group_id'], 
        'file_id'=>$movement['file_id'], 
        'group_role'=>'CH', 
        'is_active' => 'Y',
        'status'=>"CHF"
    ])->one();
     
    if(empty($getFinal)){
    }else{
        $check = EfileDakGroupMemberApproval::find()->where([
            'dak_group_id'=>$movement['dak_group_id'], 
            'file_id'=>$movement['file_id'], 
            'employee_code'=>Yii::$app->user->identity->e_id, 
            'is_active' => 'Y',
        ])->one();
        
        $remarks = $getFinal->remarks;
        echo "<hr class='hrline'>"; 
        if($group_role != 'CH'){
        if(empty($check)){
            
            
        $remksurl = Yii::$app->homeUrl."efile/inbox/members_acceptance?securekey=$menuid";
        ActiveForm::begin(['action'=>$remksurl, 'id'=>'members_acceptance_form', 'options' => ['enctype' => 'multipart/form-data']]);
        $file_id = Yii::$app->utility->encryptString($fileinfo['file_id']);
        $movement_id = Yii::$app->utility->encryptString($movement['id']);
        $dak_group_id= Yii::$app->utility->encryptString($movement['dak_group_id']);

        echo "<input type='hidden' name='Chairman[key]' value='$file_id' readonly />";
        echo "<input type='hidden' name='Chairman[key1]' value='$dak_group_id' readonly />";
        echo "<input type='hidden' name='Chairman[key2]' value='$movement_id' readonly />";
        }
        }
    ?>
		
	

    <div class="row">
        <div class='col-sm-12'>
            <h6 class='text-center'><b><span class="hindishow">Final Inputs / Decision - </span>Final Inputs / Decision</b></h6>
        </div>
        <div class="col-sm-12 ">
            <textarea class="form-control form-control-sm" placeholder="Remarks" rows='6' readonly><?=$remarks?></textarea>
        </div>
        <?php 
        if($group_role != 'CH'){
        if(empty($check)){ ?>
        <div class="col-sm-4">
            <label>Select Action</label>
            <select class='form-control form-control-sm' name='Chairman[remarks_final_status]' required >
                <option value=''>Select Action</option>
                <option value='<?=Yii::$app->utility->encryptString("Agreed")?>'>सहमत / Agree</option>
                <option value='<?=Yii::$app->utility->encryptString("Disagreed")?>'>असहमत / Disagree</option>
            </select>
        </div>
        <div class="col-sm-6">
            <br>
            <button type='button' id='agreedisagree' class="btn btn-success btn-sm" >Forward To Chairman</button>
        </div>
        <?php }else{
            echo "<div class='col-sm-12'><div class='alert alert-info text-center'><b>I ".$check->remarks_final_status." with final inputs</b></div></div>";
        } 
        }
        ?>
    </div>

<?php     
        if($group_role != 'CH'){
        if(empty($check)){
            ActiveForm::end();
        }
        }
    }


if($group_role == 'CH'){
    // $checkMemberResponse = EfileDakGroupMemberApproval::find()->where([
            // 'dak_group_id'=>$movement['dak_group_id'], 
            // 'file_id'=>$movement['file_id'], 
            // 'is_active' => 'Y',
        // ])->one();
		$checkMemberResponse = EfileDakGroupMembersRemarks::find()->where([
			'dak_group_id'=>$movement['dak_group_id'], 
			'file_id'=>$movement['file_id'], 
			'employee_code'=>Yii::$app->user->identity->e_id, 
			'group_role'=>"CH", 
			'is_active' => 'Y',
			'status'=>"CHF"
		])->one();
    if(empty($checkMemberResponse)){
		// $finalComment = EfileDakGroupMembersRemarks::find()->where([
			// 'dak_group_id'=>$movement['dak_group_id'], 
			// 'file_id'=>$movement['file_id'], 
			// 'employee_code'=>Yii::$app->user->identity->e_id, 
			// 'group_role'=>$group_role, 
			// 'is_active' => 'Y',
			// 'status'=>"CHF"
		// ])->one();
		
		$draft_remarks = "";
		$status = "CHD";
		$submitVal = "CHF";
		$btnLabel = "Click here to enter Final Minutes of Meeting";
		// if(!empty($finalComment)){
			// $draft_remarks = $finalComment->remarks;
			// $btnLabel = "Click here to enter Final Comment";
			 // $status = "CHD";
			 // $submitVal = "FS";
		// }
  

?>



<div class='text-right'>
    <button type='button' class='btn btn-danger btn-sm' id='finalbox'><?=$btnLabel?></button>
</div>
<div id='final_draft' style='display:none;'>
    <?php 
    
    $info = $remarks_id = $remarks = "";
    $info = EfileDakGroupMembersRemarks::find()->where([
        'dak_group_id'=>$movement['dak_group_id'], 
        'file_id'=>$movement['file_id'], 
        'employee_code'=>Yii::$app->user->identity->e_id, 
        'group_role'=>$group_role, 
        'is_active' => 'Y',
        'status'=>$status
    ])->one();
    
    if(!empty($info)){
        $remarks = $info->remarks;
        $remarks_id = Yii::$app->utility->encryptString($info->id);
    }
    
    
    $remksurl = Yii::$app->homeUrl."efile/inbox/addmemberremarks?securekey=$menuid";
    ActiveForm::begin(['action'=>$remksurl, 'id'=>'finalcommentform', 'options' => ['enctype' => 'multipart/form-data']]);
    $file_id = Yii::$app->utility->encryptString($fileinfo['file_id']);
    $movement_id = Yii::$app->utility->encryptString($movement['id']);
    $dak_group_id= Yii::$app->utility->encryptString($movement['dak_group_id']);
    $grouprole= Yii::$app->utility->encryptString($group_role);
    echo "<input type='hidden' name='Remarks[key]' value='$file_id' readonly />";
    echo "<input type='hidden' name='Remarks[key1]' value='$dak_group_id' readonly />";
    echo "<input type='hidden' name='Remarks[key2]' value='$movement_id' readonly />";
    echo "<input type='hidden' name='Remarks[membertype]' value='$grouprole' readonly />";
    echo "<input type='hidden' name='Remarks[remarks_id]' value='$remarks_id' readonly />";
    ?>
    <div class="row">
        <div class='col-sm-12'>
            <hr class='hrline'>
            <h6 class='text-center'><b><span class="hindishow">बैठक का अंतिम निर्णय / </span>Final Minutes of Meeting </b></h6>
        </div>
        <div class="col-sm-12 mb15">
            <textarea required="" id="final_comment_input" name="Remarks[remarks]" placeholder='बैठक का अंतिम निर्णय / Final Minutes of Meeting' class="form-control form-control-sm" placeholder="Remarks" rows='6' required><?=$remarks?></textarea>
            <?php if($status == 'CHD'){ ?>
            <div class='alert alert-danger'>
                <b>Note :- If you click on save as Draft, then Draft Minutes of Meeting will show to all members of group / committee</b>
            </div>
            <?php } ?>
        </div>
        <div class="col-sm-12 text-center mb15">
            <br>
			<input type='hidden' id='input_final' name='submit_type' />
            <button type='button' class="btn btn-dark btn-sm final_comment_submit" value="<?=$status?>">Save as Draft</button>
            <button type='button'  class="btn btn-success btn-sm final_comment_submit" value="<?=$submitVal?>">Forward to members for Agree / Disagree</button>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php 
}else{
    $checkAnyResponse = EfileDakGroupMemberApproval::find()->where([
            'dak_group_id'=>$movement['dak_group_id'], 
            'file_id'=>$movement['file_id'], 
            'is_active' => 'Y',
        ])->one();
    if(!empty($checkAnyResponse)){
	$i=1;
	$html = "";
	$group_role = "";
	foreach($members as $m){
            if($m['employee_code'] == Yii::$app->user->identity->e_id){
                $group_role = $m['group_role'];
            }
            $memberInfo = Yii::$app->utility->get_employees($m['employee_code']);
            $memberInfo = $memberInfo['fullname'].", ".$memberInfo['desg_name']." ($memberInfo[dept_name])";

            if($m['group_role'] == 'M'){
                $dis = EfileDakGroupMemberApproval::find()->where(['dak_group_id'=>$movement['dak_group_id'], 'file_id'=>$movement['file_id'], 'is_active' => 'Y', 'employee_code'=>$m['employee_code']])->one();
                if(!empty($dis)){
                    $dis = $dis->remarks_final_status;
                }
                
              
                $html .="<tr>
                    <td>$i</td>
                    <td>$memberInfo (Member)</td>
                    <td>$dis</td>
                </tr>";
                $i++;
            }
	}
	$i = $i;
	foreach($members as $m){
		$memberInfo = Yii::$app->utility->get_employees($m['employee_code']);
		$memberInfo = $memberInfo['fullname'].", ".$memberInfo['desg_name']." ($memberInfo[dept_name])";
		if($m['group_role'] == 'C'){
                    $dis = EfileDakGroupMemberApproval::find()->where(['dak_group_id'=>$movement['dak_group_id'], 'file_id'=>$movement['file_id'], 'is_active' => 'Y', 'employee_code'=>$m['employee_code']])->one();
                if(!empty($dis)){
                    $dis = $dis->remarks_final_status;
                }
                    $html .="<tr>
                        <td>$i</td>
                        <td>$memberInfo (Convenor)</td>
                        <td>$dis</td>
                    </tr>";
                    $i++;
		}
	}
	echo "<hr class='hrline'><h6 class='text-left'><b style='color:red'>Members Agree / Disgree with Chairman's remarks</b></h6>";
	echo '<table class="table table-bordered"><thead class="thead-dark"><tr><th>Sr. No.</th><th>Member Name</th><th></th></tr></thead>'.$html.'</table>';
   
}else{
    echo "<div class='alert alert-info text-center'><b>अब तक किसी ने सहमति या असहमति नहीं जताई है / No one has Agree or disagree till date</b></div>";
}
    
    
    
	}
}
} ?>	

<?php if($group_role == 'CH'){ ?>
<!-- Modal -->
<div class="modal fade" id="addnewmember" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">समूह / समिति में नया सदस्य जोड़ें /<br>Add New Member in Group / Committee</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <form id="newform">
                <input type="hidden" name="key" value="<?=Yii::$app->utility->encryptString($fileinfo['file_id'])?>" readonly="" />
                <input type="hidden" name="key1" value="<?=Yii::$app->utility->encryptString($movement['dak_group_id'])?>" readonly="" />
                <div class="row">
                    <div class="col-sm-12">
                        <label><span class="hindishow12">विभाग / </span> Department</label>
                        <select class="form-control form-control-sm" onchange="get_dept_emp_list_modal('add_mem_dept_id', 'add_mem_emp_code')" id="add_mem_dept_id" name='add_mem_dept_id'>
                        <option value="">Select Department</option>
                        <?php 
                        $allDepts= Yii::$app->utility->get_dept(NULL);
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
                    <div class='col-sm-12'>
                        <label><span class="hindishow12">कर्मचारी / </span> Employee</label>
                        <select class="form-control form-control-sm" id='add_mem_emp_code' name='add_mem_emp_code' >
                            <option value="">Select Employee</option>
                        </select>
                    </div>
                </div>
                <hr class="hrline">
                <div class="col-sm-12">
                    <div class="text-center">
                        <button type="button" class="btn btn-success btn-sm" onclick="addNewGroupMember()">Add</button>
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php } ?>