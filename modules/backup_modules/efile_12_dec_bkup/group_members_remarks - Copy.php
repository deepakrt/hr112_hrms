<?php
use app\models\EfileDakGroupMembers;
use app\models\EfileDakGroupMembersRemarks;
use app\models\EfileDakGroupMemberApproval;
use yii\widgets\ActiveForm;
    if($movement["fwd_to"]=="G")
    {
		
		//Chairman Login to show Group Member Information
        $dak_group_id=$movement["dak_group_id"];
        $param_employee_code=Yii::$app->user->identity->e_id;
        $param_status="S";
        $param_file_id=$fileinfo['file_id'];
        $members = EfileDakGroupMembers::find()->where(['dak_group_id' =>$dak_group_id, 'is_active'=>'Y'])->asArray()->all();
		
		
		$chkmembersrole = EfileDakGroupMembers::find()->where(['dak_group_id' =>$dak_group_id, 'employee_code'=>$param_employee_code,'is_active'=>'Y'])->asArray()->all();
		$showChairmanRemarks = "";
		$grpprole="M";
		foreach($chkmembersrole as $key=>$m)
        {
			if($m['group_role'] == 'CH' AND $m['employee_code'] == $param_employee_code)
			{
				$showChairmanRemarks = "Y";
				$grpprole="CH";
			}
		}
		
		// Chairman Remarks HTML
		//echo "<pre>";print_r($grpprole); die;
		if($grpprole=="CH")
		{
			$groupmemhtml="<hr class='hrline'><h6 class='text-left'><b style='color:red'>Group Member Detail</b></h6>";
        $groupmemhtml.='<table class="table table-bordered"><thead class="thead-dark"><tr><th>Sr. No.</th><th>Member Name</th><th>Member Remarks</th></tr></thead>';
        $i=1;
		
        foreach($members as $key=>$m)
        {
            $group_role = "Member";
            if($m['group_role'] == 'CH'){
                    $group_role = "<b>Chairman</b>";
            }elseif($m['group_role'] == 'C'){
                    $group_role = "<b>Convenor</b>";
            }
            $employee_code=$m["employee_code"];
            $infoabotremarks= Yii::$app->Dakutility->efile_get_dak_group_members_remarks($param_file_id,NULL,$employee_code);
            $remremarks="";
            if(!empty($infoabotremarks))
            {
                foreach ($infoabotremarks as $key => $value) 
                {
                    if($value["status"]=="S")
                    $remremarks=$value["remarks"];
                }
            }
            $memberInfo = Yii::$app->utility->get_employees($m['employee_code']);
            $memberInfo = $memberInfo['fullname'].", ".$memberInfo['desg_name']." ($memberInfo[dept_name]) ($group_role)";
            $groupmemhtml.="<tr><td>$i</td><td>$memberInfo</td><td>$remremarks</td></tr>";
            $i++;
        }
        $groupmemhtml.="</table>";
        echo $groupmemhtml;
		$check = "";
		$check = EfileDakGroupMembersRemarks::find()->where([
				'dak_group_id'=>$movement['dak_group_id'], 
				'file_id'=>$fileinfo['file_id'], 
				'employee_code'=>Yii::$app->user->identity->e_id, 
				'group_role'=>'CH', 
				'is_active' => 'Y',
				'status'=>"S"
			])->one();
		//Show Agree 
		if(!empty($check)){
		echo "<table class='table table-bordered'>
				<tr>
					<th colspan='4'>Members Agree / Disgree with Chairman's remarks</th>
				</tr>
				<tr>
					<th>Sr. No.</th>
					<th>Employee Name</th>
					<th></th>
					<th>Status</th>
				</tr>
				";
				$i=1;
		foreach($members as $s){
			if($s['group_role'] != 'CH'){
				$memberInfo = Yii::$app->utility->get_employees($s['employee_code']);
				$memberInfo = $memberInfo['fullname'].", ".$memberInfo['desg_name']." ($memberInfo[dept_name])";
				
				$check = EfileDakGroupMemberApproval::find()->where([
					'dak_group_id'=>$movement['dak_group_id'], 
					'file_id'=>$movement['file_id'], 
					'employee_code'=>$s['employee_code'], 
					'is_active' => 'Y'
				])->one();
				$response = ""; 
				$memderRole = "Member";
				if($s['group_role'] == 'C'){
					$memderRole = "Convenor";
				}
				if(!empty($check)){
					$response = $check->remarks_final_status;
				}
				echo "<tr>
					<td>$i</td>
					<td>$memberInfo</td>
					<td>$memderRole</td>
					<td>$response</td>
				</tr>";
				$i++;
			
			
			}
			
		}
		echo "</table>";
		}
		
		// Chairman Remarks HTML
		if(!empty($showChairmanRemarks)){ 
			$check = EfileDakGroupMembersRemarks::find()->where([
				'dak_group_id'=>$movement['dak_group_id'], 
				'file_id'=>$fileinfo['file_id'], 
				'employee_code'=>Yii::$app->user->identity->e_id, 
				'group_role'=>'CH', 
				'is_active' => 'Y',
				'status'=>"S"
			])->one();
			if(empty($check)){ 
			$remksurl = Yii::$app->homeUrl."efile/inbox/finalremarks?securekey=$menuid";
			ActiveForm::begin(['action'=>$remksurl, 'options' => ['enctype' => 'multipart/form-data']]);
			$file_id = Yii::$app->utility->encryptString($fileinfo['file_id']);
			$movement_id = Yii::$app->utility->encryptString($movement['id']);
			$dak_group_id= Yii::$app->utility->encryptString($movement['dak_group_id']);
			$group_role= Yii::$app->utility->encryptString("CH");
			echo "<input type='hidden' name='Chairman[key]' value='$file_id' readonly />";
			echo "<input type='hidden' name='Chairman[key1]' value='$dak_group_id' readonly />";
			echo "<input type='hidden' name='Chairman[key2]' value='$movement_id' readonly />";
			echo "<input type='hidden' name='Chairman[membertype]' value='$group_role' readonly />";
			
			$oldRemarks = EfileDakGroupMembersRemarks::find()->where([
				'dak_group_id'=>$movement['dak_group_id'], 
				'file_id'=>$fileinfo['file_id'], 
				'employee_code'=>Yii::$app->user->identity->e_id, 
				'group_role'=>'CH', 
				'is_active' => 'Y',
				'status'=>"D"
			])->one();
			$remarks = "";
			$remarks_id=NULL;
			if(!empty($oldRemarks)){
				$remarks = $oldRemarks->remarks;
				$remarks_id= Yii::$app->utility->encryptString($oldRemarks->id);
			}
			echo "<input type='hidden' name='Chairman[remarks_id]' value='$remarks_id' readonly />";
		?>

    <div class="row">
        <div class='col-sm-12'>
            <hr class='hrline'>
            <h6 class='text-center'><b>Final Remarks</b></h6>
        </div>
        <div class="col-sm-12 mb15">
            <textarea required="" id="memberremarks" name="Chairman[remarks]" class="form-control form-control-sm" placeholder="Remarks" rows='6'><?=$remarks?></textarea>
        </div>
        <div class="col-sm-12 text-center mb15">
            <br>
			<button type='submit' class="btn btn-dark btn-sm" name="Chairman[submit]" value='D'>Save as Draft</button>
			<button type='submit' class="btn btn-success btn-sm" id='fwdtomem' name="Chairman[submit]" value='S'>Forward To Members for Approval</button>
            
        </div>
    </div>
<?php
		ActiveForm::end();
		
			
		} //end Check
		} //end $showChairmanRemarks
	 
		}else{
       //echo "<pre>";print_r($members['group_role']); die;
        
		
		
		
		
        $dak_group_id=$movement["dak_group_id"];
        $param_employee_code=Yii::$app->user->identity->e_id;
        $members = EfileDakGroupMembers::find()->where(['dak_group_id' =>$dak_group_id, 'is_active'=>'Y'])->asArray()->all();
//        echo "<pre>";print_r($members); die;
        $groupmemhtml="<hr class='hrline'><h6 class='text-left'><b style='color:red'>Group Member Detail</b></h6>";
        $groupmemhtml.='<table class="table table-bordered"><thead class="thead-dark"><tr><th>Sr. No.</th><th>Member Name</th><th>Member Type</th></tr></thead>';
        $i=1;
        foreach($members as $key=>$m)
        {
            $group_role = "Member";
            if($m['group_role'] == 'CH'){
                    $group_role = "<b>Chairman</b>";
            }elseif($m['group_role'] == 'C'){
                    $group_role = "<b>Convenor</b>";
            }
            
            $memberInfo = Yii::$app->utility->get_employees($m['employee_code']);
            $memberInfo = $memberInfo['fullname'].", ".$memberInfo['desg_name']." ($memberInfo[dept_name]) ($group_role)";
            $groupmemhtml.="<tr><td>$i</td><td>$memberInfo</td><td>$group_role</td></tr>";
            $i++;
        }
        $groupmemhtml.="</table>";
        
		
        $members = EfileDakGroupMembers::find()->where(['dak_group_id' =>$dak_group_id,'employee_code'=>$param_employee_code, 'is_active'=>'Y'])->asArray()->all();
      
        foreach($members as $m)
        {
            $group_role=$m["group_role"];
        }
        //echo "<pre>";print_r($group_role); die;
    $param_file_id=$fileinfo['file_id'];
    $param_status="";
    $infoabotremarks= Yii::$app->Dakutility->efile_get_dak_group_members_remarks($param_file_id,$param_status,$param_employee_code);
   // echo "<pre>";print_r($infoabotremarks); die;
    $remremarks=$remrsts="";
    if(!empty($infoabotremarks))
    {
        foreach ($infoabotremarks as $key => $value) 
        {
            if($value["status"]=="D")
            {
                $remremarks=$value["remarks"];
            }
            else if($value["status"]=="S")
            {
                $remrsts="S";
                $remremarks=$value["remarks"];
            }
        }
        
    }
    echo $groupmemhtml;
	
    if($remrsts=="S")
    {
        $groupmemsavermks="<hr class='hrline'><h6 class='text-left'><b style='color:red'>Remarks Added by You:</b></h6>";
        $groupmemsavermks.="<div class='row'><div class='col-sm-12'>$remremarks</div></div>";
        echo $groupmemsavermks;
		
		$FinalRemarks = EfileDakGroupMembersRemarks::find()->where([
					'dak_group_id'=>$movement['dak_group_id'], 
					'file_id'=>$fileinfo['file_id'], 
					'group_role'=>'CH', 
					'is_active' => 'Y'
				])->one();
				
		if(!empty($FinalRemarks)){
			$check = EfileDakGroupMemberApproval::find()->where([
					'dak_group_id'=>$dak_group_id, 
					'file_id'=>$file_id, 
					'employee_code'=>Yii::$app->user->identity->e_id, 
					'is_active' => 'Y'
				])->one();
			if(empty($check)){
	
			
        echo "<hr class='hrline'>"; 
		$remarks = $FinalRemarks->remarks;
		$remksurl = Yii::$app->homeUrl."efile/inbox/members_acceptance?securekey=$menuid";
		ActiveForm::begin(['action'=>$remksurl, 'options' => ['enctype' => 'multipart/form-data']]);
		$file_id = Yii::$app->utility->encryptString($fileinfo['file_id']);
		$movement_id = Yii::$app->utility->encryptString($movement['id']);
		$dak_group_id= Yii::$app->utility->encryptString($movement['dak_group_id']);
		
		echo "<input type='hidden' name='Chairman[key]' value='$file_id' readonly />";
		echo "<input type='hidden' name='Chairman[key1]' value='$dak_group_id' readonly />";
		echo "<input type='hidden' name='Chairman[key2]' value='$movement_id' readonly />";
		
		
		?>
		
	

    <div class="row">
        <div class='col-sm-12'>
            <h6 class='text-center'><b>Final Remarks of Chairman</b></h6>
        </div>
        <div class="col-sm-12 ">
            <textarea class="form-control form-control-sm" placeholder="Remarks" rows='6' readonly><?=$remarks?></textarea>
        </div>
		<div class="col-sm-4">
			<label>Select Action</label>
			<select class='form-control form-control-sm' name='Chairman[remarks_final_status]' required >
				<option value=''>Select Action</option>
				<option value='<?=Yii::$app->utility->encryptString("Agree")?>'>Agree</option>
				<option value='<?=Yii::$app->utility->encryptString("Disagree")?>'>Disagree</option>
			</select>
        </div>
        <div class="col-sm-6">
            <br>
			<button type='submit' class="btn btn-success btn-sm" >Forward To Chairman</button>
            
        </div>
    </div>
<?php

		ActiveForm::end();
	} 
	} 
	}
    
    if($remrsts!=="S")
    {
    $remksurl = Yii::$app->homeUrl."efile/inbox/addmemberremarks?securekey=$menuid";
    ActiveForm::begin(['action'=>$remksurl, 'options' => ['enctype' => 'multipart/form-data']]);
    $file_id = Yii::$app->utility->encryptString($fileinfo['file_id']);
    $movement_id = Yii::$app->utility->encryptString($movement['id']);
    $dak_group_id= Yii::$app->utility->encryptString($movement['dak_group_id']);
    echo "<input type='hidden' name='key' value='$file_id' readonly />";
    echo "<input type='hidden' name='sbmttype' id='sbmttype' value=''  />";
    echo "<input type='hidden' name='key1' value='$dak_group_id' readonly />";
    echo "<input type='hidden' name='key2' value='$movement_id' readonly />";
    echo "<input type='hidden' name='membertype' value='$group_role' readonly />";
    ?>
    <div class="row">
        <div class='col-sm-12'>
            <hr class='hrline'>
            <h6 class='text-center'><b>Remarks</b></h6>
        </div>
        <div class="col-sm-12 mb15">
            <textarea required="" id="memberremarks" name="memberremarks" class="form-control form-control-sm" placeholder="Remarks" rows='6'><?=$remremarks?></textarea>
        </div>
        <div class="col-sm-12 text-center mb15">
            <br>
            <input type="submit" id="saveasdrf"  class="btn btn-dark btn-sm" value="Save as Draft" name="btntype">
            <input type="submit" id="saveassbmt"  class="btn btn-success btn-sm" value="Submit/Forward" name="btntype">
        </div>
    </div>
<?php
   ActiveForm::end();
        }
       }
       }

?>