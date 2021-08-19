<?php
$this->title= 'View Employee';
$menuid = "";
if(isset($_GET['securekey']) AND !empty($_GET['securekey'])){
    $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
}
if(empty($menuid)){
    header('Location: '.Yii::$app->homeUrl); 
    exit;
}
$menuid = Yii::$app->utility->encryptString($menuid);
$e_id = Yii::$app->utility->decryptString($_GET['empid']);
$e_id = Yii::$app->utility->encryptString($e_id);
$encry = base64_encode($info['e_id']);
//$editUrl = Yii::$app->homeUrl."admin/manageemployees/updateemployee?key=$encry";
$editUrl = Yii::$app->homeUrl."admin/manageemployees/updateemployee?securekey=$menuid";
?>
<style>
label{ font-weight:bold; font-size: 15px;}
.con {
	font-size: 15px;
}  
.col-sm-3{margin-bottom: 10px;}
</style>
 
 <script type="text/javascript">
function changeUrl(url) {
	var eid='<?=$encry;?>';
	var menuid='<?=$menuid;?>';
	var emp='<?=$e_id;?>';
	var title='';
	url='viewemployee?securekey='+menuid+'&empid='+emp+'&tab='+url;
     if (typeof (history.pushState) != "undefined") {
        var obj = { Title: title, Url: url };
        history.pushState(obj, obj.Title, obj.Url);
    } else {
        alert("Browser does not support HTML5.");
    }
} 
</script>
<?php if(!isset($_GET['tab'])){$_GET['tab']='';} ?>
<div id="exTab1" class="exTab1">	
	<ul  class="nav nav-pills">
		<?php  $iclass=$qclass=$fclass=$lclass=''; 
			if($_GET['tab']=='info'){$iclass='active show'; } 
			elseif($_GET['tab']=='qualification'){$qclass='active show'; }
			elseif($_GET['tab']=='family'){ $fclass='active show'; }
			elseif($_GET['tab']=='leaves'){ $lclass='active show'; }
			else{ $iclass='active show'; } ?>
			<li><a class="<?=$iclass;?>" onclick="changeUrl('info')" href="#info" data-toggle="tab">Personal Information</a></li>
			<li><a class="<?=$qclass;?>" onclick="changeUrl('qualification')" href="#qualification" data-toggle="tab">Qualification </a></li>
			<li><a class="<?=$fclass;?>" onclick="changeUrl('family')" href="#family" data-toggle="tab">Family Details</a> </li>
			<li><a class="<?=$lclass;?>" onclick="changeUrl('leaves')" href="#leaves" data-toggle="tab">Leave Details</a> </li>
		</ul>

<div class="tab-content clearfix">
<div class="tab-pane <?=$iclass;?>" id="info">
<h3>Personal Information:</h3>
          <div class="row">
	<div class="col-sm-3">
		<label></label>
                <?php 
                if(empty($info['emp_image'])){
                    $info['emp_image'] = DefaultImageEmployee;
                }
                ?>
		<img width="100" src='<?=Yii::$app->homeUrl.$info['emp_image']?>' />
	</div>

	<div class="col-sm-3">
		<label>Name of Employee</label>
		<p class="con"><?=ucwords($info['fname'])?> <?=ucwords($info['lname'])?></p>
	</div>
        <div class="col-sm-3">
		<label>Designation</label>
		<p class="con"><?=$info['desg_name']?></p>
	</div>
        <div class="col-sm-3">
		<label>Department</label>
		<p class="con"><?=$info['dept_name']?></p>
	</div>
	
		<div class="col-sm-3">
		<label>Rank</label>
		<p class="con"><?=$info['rank1']?></p>
	</div>
		<div class="col-sm-3">
		<label>Unit</label>
		<p class="con"><?=$info['city']?></p>
	</div>
	<div class="col-sm-3">
		<label>Belt No</label>
	<p class="con"><?=$info['belt_no']?></p>
	</div>
	<div class="col-sm-3">
		<label>Gender</label>
		<p class="con"><?php if($info['gender'] == 'M'){ echo "Male";}elseif($info['gender'] == 'F'){ echo "Female";}?></p>
	</div>
	<div class="col-sm-3">
		<label>Date of Birth</label>
		<p class="con"><?=date('d-m-Y', strtotime($info['dob']))?></p>
	</div>
	<div class="col-sm-3">
		<label>Correspondence Address</label>
		<p class="con"><?=$info['address'].", ".$info['city']." ".$info['zip']." (".$info['state'].") <br> Contact:".$info['contact'];?></p>
	</div>
	<div class="col-sm-3">
		<label>Permanenet Address</label>
		<p class="con"><?=$info['p_address'].", ".$info['p_city']." ".$info['p_zip']." (".$info['p_state'].") <br> Contact:".$info['p_contact'];?></p>
	</div>
	<div class="col-sm-3">
		<label>Contact</label>
		<p class="con"><?=$info['phone']?></p>
	</div>
	<div class="col-sm-3">
		<label>Emergency Contact</label>
		<p class="con"><?=$info['emergency_phone']?></p>
	</div>
	<div class="col-sm-3">
		<label>Department</label>
		<p class="con">-</p>
	</div>
	<div class="col-sm-3">
		<label>Personal Email</label>
		<p class="con"><?=$info['email_id']?></p>
	</div>
	<div class="col-sm-3">
		<label>Marital Status</label>
		<p class="con"><?=$info['marital_status']?></p>
	</div>
	<div class="col-sm-3">
		<label>Blood Group</label>
		<p class="con"><?=$info['blood_group']?></p>
	</div>
	<div class="col-sm-3">
		<label>Joining Date</label>
		<p class="con"><?=date('d-m-Y', strtotime($info['joining_date']))?></p>
	</div>
</div>
<hr>
<div class="col-sm-12 text-center">
<a href="<?=$editUrl?>&empid=<?=Yii::$app->utility->encryptString($info['employee_code'])?>" class="btn btn-danger btn-sm">Click here to update employee</a>
</div>
</div>
<!-------------------2nd------------------------------->
<div class="tab-pane <?=$qclass;?>" id="qualification">
<?php echo $this->render('employee_qualification', ['qualification'=>$qualification]);?>
</div>
 
<!----------------------3th---------------------------->
<div class="tab-pane <?=$fclass;?>" id="family">
<?php echo $this->render('employee_family', ['family_details'=>$family_details]);?>
</div>
<!----------------------4th---------------------------->
<div class="tab-pane <?=$lclass;?>" id="leaves">
<?php echo $this->render('employee_leaves', ['employee_leaves'=>$employee_leaves]);?>
</div>
<!-------------------------------------------------->
</div>
</div>