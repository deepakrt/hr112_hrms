<?php
$this->title = 'eAkadamik WebApp';
echo \app\components\Topbarwidget::widget(array('menuid'=>$menuid));
$homeUrl = Yii::$app->homeUrl;
    $gender=GENDER;
    $gender = explode(",",$gender);    
    $frole=Yii::$app->Utility1->USPExtractRoleNewFaculty();
	//$college=Yii::$app->Utility->USP_ExtractCollege();
        $departments=Yii::$app->Utility->USP_ExtractDepartment();
    
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
    
    <div class="col-lg-9">
    <div class="col-md-12 mar30">
        <div class="panel panel-default">
          <div class="panel-heading respantit">
            <h3 class="panel-title"> Create Faculty </h3>
          </div>
            <span id='widgetversion_1_0_error_block' >
              <div class="alert alert-error error_main" id="error_main" style="display:none">  
                <ul id="widget_error_main_inner" >
                </ul>
              </div>         
            </span>
          <div class="panel-body">
             <?php
				foreach (Yii::$app->session->getAllFlashes() as $key => $message)
				echo '<div class="col-sm-12" ><div class="text-center alert alert-' . $key . '">' . $message . '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div></div>';
			 ?> 
            <form method="post" action="<?php echo $homeUrl?>workflow/umsfacultymaster/insert" name="frm" id="frm">
                <input type="hidden"  class="form-control" readonly="" name="secureKey" value="<?=  base64_encode($menuid)?>" />
                <input type="hidden"  class="form-control" readonly="" name="secureHash" value="<?=Yii::$app->Utility->getHashInsert($menuid)?>" />
                <input id="_csrf" type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
              <div class="col-lg-12 mar10"> </div>
              <div style="font-weight: bold; padding: 10px;">Select Faculty's Role</div>
              <div class="col-lg-12 mar10">
                <div class="col-lg-2"> Faculty Type <span class="required">*</span> </div>
                <div class="col-lg-4">
                 <select id="frm_roleName" name="beFaculty[Role_id]">
                  <option selected="selected" value="">Select Role</option>
                  <?php
					foreach ($frole as $froleK=>$froleV) {
					  $role_name = $froleV['role_name'];
					  $role_id = $froleV['role_id'];
					  echo "<option value='$role_id'>$role_name</option>";
					 } ?>
                </select>
                 
                </div>
              </div>
              <div class="col-md-12 mar10">
                <div class="col-md-2"></div>
                <div class="col-md-8"></div>
              </div>
              <div style="font-weight: bold; padding: 10px;">Enter Faculty's Personal Details</div>
              <div class="col-lg-12 mar10">
                <div class="col-lg-2"> First Name <span class="required">*</span></div>
                <div class="col-lg-4">
                  <input type="text"  id="frm_fac_firstName" value="" name="beFaculty[firstName]">
                </div>
                <div class="col-lg-2"> Last Name <span class="required">*</span></div>
                <div class="col-lg-4">
                  <input type="text"  id="frm_fac_lastName" value="" name="beFaculty[lastName]">
                </div>
              </div>
              <div class="col-lg-12 mar10">
                <div class="col-lg-2"> Gender <span class="required">*</span></div>
                <div class="col-lg-4">
                  <select id="frm_fac_gender" name="beFaculty[gender]">
                    <option value="">Select Gender</option>
                    <?php
                    foreach ($gender as $key=>$val) {
                        ?>
                        <option value="<?= $val ?>"><?= $val?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="col-lg-2"> DOB <span class="required">*</span></div>
                <div class="col-lg-4"> 
                 <input readonly='readonly' type="text" id="dob" style="width: 100px;" name="beFaculty[dob]">
                  <img src="<?=Yii::$app->homeUrl?>images/dateIcon.gif">
                  <span style="display: none; position: absolute;"></span>
                </div>
              </div>
              <div class="col-lg-12 mar10">
                <div class="col-lg-2"> Contact No. <span class="required">*</span></div>
                <div class="col-lg-4">
                  <input type="text" id="frm_fac_contactNo" value="" maxlength="10" name="beFaculty[contactNo]">
                </div>
                <div class="col-lg-2"> Email ID <span class="required">*</span></div>
                <div class="col-lg-4">
                  <input type="text"  id="frm_fac_emailId" value="" name="beFaculty[emailId]">
                </div>
              </div>
              <div class="col-lg-12 mar10">
                <div class="col-lg-2"> Address <span class="required">*</span> </div>
                <div class="col-lg-4">
                  <textarea  id="frm_fac_address" rows="5" cols="20" name="beFaculty[address]" style="height:40px; width:100%"></textarea>
                </div>
              </div>
              <div class="col-md-12 mar10">
                <div class="col-md-2"></div>
                <div class="col-md-8"></div>
              </div>
              <div style="font-weight:bold ;padding:10px;">Family Details</div>
              <div class="col-lg-12 mar10">
                <div style="font-weight:300;" class="col-lg-2">Father Name</div>
                <div class="col-lg-4">
                  <input type="text"  id="frm_fac_fatherName" value="" name="beFaculty[fatherName]">
                </div>
                <div style="font-weight:300;" class="col-lg-2">Mother Name</div>
                <div class="col-lg-4">
                  <input type="text"  id="frm_fac_motherName" value="" name="beFaculty[motherName]">
                </div>
              </div>
              <div class="col-lg-12 mar10">
                <div style="font-weight:300;" class="col-lg-2">Father's Email</div>
                <div class="col-lg-4">
                  <input type="text"  id="frm_fac_fatherEmail" value="" name="beFaculty[fatherEmail]">
                </div>
                <div style="font-weight:300;" class="col-lg-2">Father's DOB</div>
                <div class="col-lg-4">
                   <input type="text" readonly='readonly' id="fatherDob" style="width: 100px;" name="beFaculty[father_dob]">
                  <img src="<?=Yii::$app->homeUrl?>images/dateIcon.gif">
                   <span style="display: none; position: absolute;"></span>
                </div>
              </div>
              <div class="col-md-12 mar10">
                <div class="col-md-2"></div>
                <div class="col-md-8"></div>
              </div>
              <div style="font-weight: bold; padding: 10px;">Enter Faculty's Joining Details</div>
              <div class="col-lg-12 mar10">
               <!-- <div class="col-lg-2"> College <span class="required">*</span></div>
                <div class="col-lg-4">
                <select id="clgId" name="beFaculty[college_id]">
                  <option value="">Select College </option>
                  <?php
//					foreach ($college as $collegeK=>$collegeV) {
//					  $College_Name = $collegeV['College_Name'];
//					  $College_Id = $collegeV['College_Id'];
//					  echo "<option value='$College_Id'>$College_Name</option>";
//					 } ?>
                </select>
                </div>
               --->
                <div class="col-lg-2">Department<span class="required">*</span></div>
                <div class="col-lg-4">
                  <select id="Assign_Department" name="beFaculty[beDepartment]">
                    <?php
                    if(!empty($departments)){
                        echo '<option value="" >Select Department</option>';
                        foreach ($departments as $departments) {
                            $id= $departments['Department_Id'];
                            $name= $departments['Department_Name'];
                            echo "<option value='$id'>$name</option>";
                        }
                    }else{
                        echo "<option value=''>No Records Found in DB. Contact Admin</option>";
                    }
                    ?>
                  </select>
                </div>
                <div class="col-lg-2"> Faculty Code <span class="required">*</span></div>
                <div class="col-lg-4">
                  <input type="text"  id="frm_fac_empCode" value="" name="beFaculty[empCode]">
                </div>
              </div>
             <div class="col-lg-12 mar10">
                
                
                <div class="col-lg-2"> Designation <span class="required">*</span></div>
                <div class="col-lg-4">
                  <input type="text"  id="frm_fac_designation" value="" name="beFaculty[designation]">
                </div>
                
                <div class="col-lg-2"> Date of Joining <span class="required">*</span></div>
                <div class="col-lg-4"> 
                  <input readonly='readonly' type="text" id="doj" style="width: 100px;" name="beFaculty[date_of_joining]">
                  <img src="<?=Yii::$app->homeUrl?>images/dateIcon.gif">
                  <span style="display: none; position: absolute;"></span>
                </div>
              </div>
               
              <div class="col-lg-12 mar10"> </div>
              <div class="col-lg-12 mar10">
                  <div class="col-lg-1"></div>
                  <div class="col-lg-3"></div>
                  <div class="col-lg-1"></div>
                  <div class="col-lg-3">
                
                  <?php
                      $secureKey =  base64_encode($menuid);
                      $secureHash = Yii::$app->Utility->getHashView($menuid);
                      ?>
                    <input name='insert' class="btn btn-primary" type="submit" onclick="return validate_facultyMaster()" value="Save" id="frm_0">
                    <a class='btn btn-default' href='<?php echo $homeUrl;?>base/umsprivilege/index?secureKey=<?php echo $secureKey;?>&secureHash=<?php echo $secureHash;?>'>Cancel</a>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    
    
</div>


<div id="border-bottom">
  <div>
    <div></div>
  </div>
</div>
