<?php
$this->title = 'eAkadamik WebApp';
echo \app\components\Topbarwidget::widget(array('menuid'=>$menuid));
$homeUrl = Yii::$app->homeUrl;
?>

<div class="container paddtop117">
  <div class="col-md-12">
    <div class="col-md-9">
      <div class="breadcrump-content"><!-- Home / Dashboard --></div>
    </div>
    <div class="col-md-3 datetext text-right" >
      <div class="breadcrump-content-right"> <?php echo date('D M d h:i:s T Y');?> </div>
    </div>
  </div>
  <?php echo \app\components\Sidebarwidget::widget(array('menuid'=>$menuid)); ?>
  <div class="col-lg-9 marbot70">
  <?php
	foreach (Yii::$app->session->getAllFlashes() as $key => $message)
	echo '<div class="col-sm-12" ><div class="text-center alert alert-' . $key . '">' . $message . '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div></div>';
 ?> 
  
    <table id="facView" class="display adminlist" cellspacing="0" width="100%">
      <thead>
        <tr>
          <th>#</th>
          <th>User Login Name</th>
          <th>User First Name</th>
          <th>User Last Name</th>
          <th>User Role</th>
          <th>Update</th>
          <th>Activate/De-Activate</th>
        </tr>
      </thead>
      <?php
      $departments=Yii::$app->Utility->USP_ExtractDepartment();
      $department_id = $departments[0]['Department_Id'];
      if(!empty($departments))
{
    $facultyinfo = array();
foreach($departments as $departmentsK=>$departmentsV)
{
 $department_id = $departmentsV['Department_Id'];
    $facultyinfodept  = Yii::$app->Utility1->USP_ExtractFaculty($department_id);
    $facultyinfo = array_merge($facultyinfo,$facultyinfodept);
}

     //echo "<pre>"; print_r($facultyinfo); die;
	
		 
		  $secureKey =  base64_encode($menuid);
          $secureHash = Yii::$app->Utility->getHashView($menuid);
		?>
      <tbody class="list">
        <?php
				$i=1;
				foreach($facultyinfo as $facultydataK=>$facultydataV)
				{
				 $email_id = $facultydataV['email_id'];
				 $first_name= $facultydataV['first_name'];
				 $last_name = $facultydataV['last_name'];
				 $gender = $facultydataV['gender'];
				 
				 $faculty_id = $facultydataV['faculty_id'];
				 $address= $facultydataV['address'];
				 $blood_grp = $facultydataV['blood_grp'];
				 $contact_no = $facultydataV['contact_no'];
				 $date_of_joining = $facultydataV['date_of_joining'];
				 $dob = $facultydataV['dob'];
				 $designation = $facultydataV['designation'];
				 $emp_code= $facultydataV['emp_code'];
				 $Department_Id = $facultydataV['Department_Id'];
				 $Department_Name = $facultydataV['Department_Name'];
				 $college_id = $facultydataV['college_id'];
				 
				 $father_dob = $facultydataV['father_dob'];
				 $father_email= $facultydataV['father_email'];
				 $father_name = $facultydataV['father_name'];
				 $mother_name = $facultydataV['mother_name'];
				 $role_name  = $facultydataV['role_name'];
                                 
                                 $activestatus = $facultydataV['activestatus'];
                                 $activestatus_class = '';
                                 if(strtolower($activestatus) == 'deactivate') $activestatus_class = 'btn-danger';
                                 else if(strtolower($activestatus) == 'activate') $activestatus_class = 'btn-success';
                                 
                                 
				?>
              <tr>
                <td class=""><?php echo $i;?></td>
                <td class=""><?php echo $email_id;?></td>
                <td class=""><?php echo $first_name;?></td>
                <td class=""><?php echo $last_name;?></td>
                <td class=""><?php echo $role_name;?></td>
                <td class=""><?php 
                $class_serialize = "Serialize_$i";
                echo "<form class= '$class_serialize' action='$homeUrl"."workflow/managefacultyprofiles/viewfacinfo?secureKey=$secureKey&secureHash=$secureHash;' method='POST'>"; ?>
                      
	<input id="_csrf" type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
                     
                     <input type='hidden' name='faculty_id' value='<?php echo $faculty_id; ?>' />
                     <input type='hidden' name='first_name' value='<?php echo $first_name; ?>' />
                     <input type='hidden' name='last_name' value='<?php echo $last_name; ?>' />
                     <input type='hidden' name='address' value='<?php echo $address; ?>' />
                     <input type='hidden' name='blood_grp' value='<?php echo $blood_grp; ?>' />
                     <input type='hidden' name='contact_no' value='<?php echo $contact_no; ?>' />
                     <input type='hidden' name='date_of_joining' value='<?php echo $date_of_joining; ?>' />
                     <input type='hidden' name='dob' value='<?php echo $dob; ?>' />
                     <input type='hidden' name='email_id' value='<?php echo $email_id; ?>' />
                     <input type='hidden' name='emp_code' value='<?php echo $emp_code; ?>' />
                     
                     <input type='hidden' name='gender' value='<?php echo $gender; ?>' />
                     <input type='hidden' name='last_name' value='<?php echo $last_name; ?>' />
                     <input type='hidden' name='Department_Id' value='<?php echo $Department_Id; ?>' />
                     <input type='hidden' name='Department_Name' value='<?php echo $Department_Name; ?>' />
                     <input type='hidden' name='college_id' value='<?php echo $college_id; ?>' />
                     <input type='hidden' name='designation' value='<?php echo $designation; ?>' />
                     <input type='hidden' name='father_dob' value='<?php echo $father_dob; ?>' />
                     <input type='hidden' name='father_email' value='<?php echo $father_email; ?>' />
                     <input type='hidden' name='father_name' value='<?php echo $father_name; ?>' />
                     <input type='hidden' name='mother_name' value='<?php echo $mother_name; ?>' />
                     <input type='hidden' name='role_name' value='<?php echo $role_name; ?>' />
                     <input type='hidden' name='activate_decativate' value='<?php echo $activestatus; ?>' />
                     <input type='hidden' name='secureKey' value='<?php echo $secureKey; ?>' />
                     <input type='hidden' name='secureHash' value='<?php echo $secureHash; ?>' />
                     
                     <input type='submit' class='btn btn-primary' value='Update Info'></form> 
                  </td>
                  <td>
                     <input class="activate_decativate_action_faculty btn <?php echo $activestatus_class;?>" type="button"  value="<?php echo $activestatus;?>" id="<?php echo $class_serialize;?>">   
                    </td>
              </tr>
              <?php
				$i++;
				}
				?>
    </table>
  </div>
</div>
<div id="border-bottom">
  <div>
    <div></div>
    <?php
    
    }
else {
?>    
<div class="col-lg-12 mar10">
<div class="text-center alert alert-warning">
<strong>There is no Department, Contact Admin.</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
</div>
</div>
<?php
}
?>
  </div>
</div>
<script>
$(document).ready(function()
{
 $('#facView').DataTable( {
	 "scrollX": true
	 });
         
$('body').on('click','.activate_decativate_action_faculty',function()
{
    startLoader();
  $form_serialize_class  = $(this).attr('id');  
  var formdata = $('.'+$form_serialize_class+' :input').serialize(); 
  $csrf = $("#_csrf").val();   
  var url = BASEURL + "workflow/managefacultyprofiles/activatedeactivate";    
  $.ajax
    ({
        type: "POST",
        url: url,
        dataType: "json",
        data:
        {
        formdata: formdata,
        _csrf:$csrf
        },
        success: function (data) 
        {         
        var status_id = data.STATUS_ID;
        var res = data.STATUS_RESPONSE;         
        window.location.replace(BASEURL + res);
    } 
});

});

});
</script>