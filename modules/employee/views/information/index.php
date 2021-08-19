<?php
$this->title= 'Personal Information';
$menuid = "";
if(isset($_GET['securekey']) AND !empty($_GET['securekey'])){
    $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
}
if(empty($menuid)){
    header('Location: '.Yii::$app->homeUrl); exit;
}
$menuid = Yii::$app->utility->encryptString($menuid);
//echo "<pre>";print_r($info); die;
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
    //var cur_url = window.location.href;
        var cur_url = '<?=Yii::$app->homeUrl."employee/information?securekey=$menuid"?>'
	var title='';
        
	url=cur_url+'&tab='+url;
        window.location.href = url;
//     if (typeof (history.pushState) != "undefined") {
//        var obj = { Title: title, Url: url };
//        history.pushState(obj, obj.Title, obj.Url);
//    } else {
//        alert("Browser does not support HTML5.");
//    }
} 
</script>
<?php if(!isset($_GET['tab'])){$_GET['tab']='';} ?>
<div id="exTab1" class="exTab1">	
    <ul class="nav nav-pills">
        <?php  $langclass=$trnlass=$exclass=$iclass=$qclass=$fclass=$lclass=''; 
            if($_GET['tab']=='info'){$iclass='active show'; } 
            elseif($_GET['tab']=='qualification'){$qclass='active show'; }
            elseif($_GET['tab']=='family'){ $fclass='active show'; }
            elseif($_GET['tab']=='leaves'){ $lclass='active show'; }
            elseif($_GET['tab']=='experience'){ $exclass='active show'; }
            elseif($_GET['tab']=='training_det'){ $trnlass='active show'; } 
            elseif($_GET['tab']=='language_details'){ $langclass='active show'; } // language_details
            else{ $iclass='active show'; } ?>
    <li><a class="<?=$iclass;?>" onclick="changeUrl('info')" href="#info" data-toggle="tab">Personal Information</a></li>
    <li><a class="<?=$qclass;?>" onclick="changeUrl('qualification')" href="#qualification" data-toggle="tab">Qualification </a></li>
    <li><a class="<?=$fclass;?>" onclick="changeUrl('family')" href="#family" data-toggle="tab">Family Details</a> </li>
    <li><a class="<?=$fclass;?>" onclick="changeUrl('experience')" href="#experience" data-toggle="tab">Experience Details</a> </li>
    <li><a class="<?=$fclass;?>" onclick="changeUrl('training_det')" href="#training_det" data-toggle="tab">Training Details</a> </li>
    <li><a class="<?=$fclass;?>" onclick="changeUrl('language_details')" href="#language_details" data-toggle="tab">Language Known</a> </li>
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
            <label>Designation</label>
            <p class="con"><?=$info['desg_name']?></p>
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
            <label>Department</label>
            <p class="con"><?=$info['dept_name']?></p>
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
            <label>Permanent Address</label>
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
<div class="tab-pane <?=$exclass;?>" id="experience">
<?php echo $this->render('employee_experience', ['experience_details'=>$experience_details]);?>
</div>

<?php
    // $training_details = array();
?>
<!----------------------4th---------------------------->
<div class="tab-pane <?=$trnlass;?>" id="training_det">
<?php echo $this->render('training_details', ['training_details'=>$training_details]);?>
</div>


<?php
     // $language_details = array();
?>
<!----------------------4th---------------------------->
<div class="tab-pane <?=$langclass;?>" id="language_details">
<?php echo $this->render('language_details', ['emp_language_details'=>$emp_language_details]);?>
</div>


</div>
</div>
