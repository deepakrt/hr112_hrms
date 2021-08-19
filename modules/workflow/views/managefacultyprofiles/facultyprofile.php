<?php
$this->title = 'eAkadamik WebApp';
echo \app\components\Topbarwidget::widget(array('menuid'=>$menuid));
$homeUrl = Yii::$app->homeUrl;
$gender=GENDER;
$gender = explode(",",$gender);    
$frole=Yii::$app->Utility1->USPExtractRoleNewFaculty();
$college=Yii::$app->Utility->USP_ExtractCollege();
    
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
    <div class="col-md-12 mar30">
        <div class="panel panel-default">
          <div class="panel-heading respantit">
            <h3 class="panel-title"> Update Faculty Info</h3>
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
            <form method="post" action="<?php echo $homeUrl?>workflow/managefacultyprofiles/update" name="frm" id="frm">
               <input type="hidden"  class="form-control" readonly="" name="secureKey" id="secureKey" value="<?=  base64_encode($menuid)?>" />
	<input type="hidden"  class="form-control" readonly="" name="secureHash" id="secureHash" value="<?=Yii::$app->Utility->getHashInsert($menuid)?>" />
                <input id="_csrf" type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
                <input type='hidden' name='faculty_id' value="<?php echo $data['faculty_id']; ?>" />
              <div class="col-lg-12 mar10"> </div>
              <div style="font-weight: bold; padding: 10px;">Faculty's Role</div>
              <div class="col-lg-12 mar10">
                <div class="col-lg-2"> Faculty Type <span class="required">*</span> </div>
                <div class="col-lg-4">
                 <select id="frm_roleName" name="role_name">
                  <option selected="selected" value="<?php echo $data['role_name'];?>">
				   <?php echo $data['role_name'];?>
                  </option>
                </select>
                 
                </div>
              </div>
              <div class="col-md-12 mar10">
                <div class="col-md-2"></div>
                <div class="col-md-8"></div>
              </div>
              <div style="font-weight: bold; padding: 10px;">Faculty's Personal Details</div>
              <div class="col-lg-12 mar10">
                <div class="col-lg-2"> First Name <span class="required">*</span></div>
                <div class="col-lg-4">
                  
                  <input type="text" readonly="readonly" id="frm_fac_firstName" value="<?php echo $data['first_name'];?>" name="first_name">
                </div>
                <div class="col-lg-2"> Last Name <span class="required">*</span></div>
                <div class="col-lg-4">
                  <input type="text"  id="frm_fac_lastName" value="<?php echo $data['last_name'];?>" name="last_name">
                </div>
              </div>
              <div class="col-lg-12 mar10">
                <div class="col-lg-2"> Gender <span class="required">*</span></div>
                <div class="col-lg-4">
                  <select id="frm_fac_gender" name="gender">
                    <?php foreach ($gender as $key=>$val) {  ?>
                    <?php if ($val == $data['gender']) { ?>
                        <option value="<?= $val ?>" selected="selected"><?= $val?></option>
                    <?php } else { ?>
                     <option value="<?= $val ?>"><?= $val?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
                <div class="col-lg-2"> DOB <span class="required">*</span></div>
                <div class="col-lg-4">
                        <?php
                       if(isset($data['dob']) && !empty($data['dob']))
                $data['dob'] =   date("d-m-Y", strtotime($data['dob']));
                ?>
                 <input readonly="readonly" type="text" id="dob" style="width: 90px;" value="<?php echo $data['dob'];?>" name="dob">
                  <img src="<?=Yii::$app->homeUrl?>images/dateIcon.gif">
                  <span style="display: none; position: absolute;"></span>
                </div>
              </div>
              <div class="col-lg-12 mar10">
                <div class="col-lg-2"> Contact No. <span class="required">*</span></div>
                <div class="col-lg-4">
                  <input type="text" id="frm_fac_contactNo" value="<?php echo $data['contact_no'];?>" maxlength="10" name="contact_no">
                </div>
                <div class="col-lg-2"> Email ID <span class="required">*</span></div>
                <div class="col-lg-4">
                  <input type="text" readonly="readonly" id="frm_fac_emailId" value="<?php echo $data['email_id'];?>" name="email_id">
                </div>
              </div>
              <div class="col-lg-12 mar10">
                <div class="col-lg-2"> Address <span class="required">*</span> </div>
                <div class="col-lg-4">
                  <textarea  id="frm_fac_address" rows="5" cols="20" name="address" style="height:40px; width:100%"><?php echo $data['address'];?></textarea>
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
                  <input type="text"  id="frm_fac_fatherName" value="<?php echo $data['father_name'];?>" name="father_name">
                </div>
                <div style="font-weight:300;" class="col-lg-2">Mother Name</div>
                <div class="col-lg-4">
                  <input type="text"  id="frm_fac_motherName" value="<?php echo $data['mother_name'];?>" name="mother_name">
                </div>
              </div>
              <div class="col-lg-12 mar10">
                <div style="font-weight:300;" class="col-lg-2">Father's Email</div>
                <div class="col-lg-4">
                  <input type="text"  id="frm_fac_fatherEmail" value="<?php echo $data['father_email'];?>" name="father_email">
                </div>
                <div style="font-weight:300;" class="col-lg-2">Father's DOB</div>
                <div class="col-lg-4">
                         <?php
                       if(isset($data['father_dob']) && !empty($data['father_dob']))
                $data['father_dob'] =   date("d-m-Y", strtotime($data['father_dob']));
                ?>
                   <input type="text" readonly="readonly" id="fatherDob" style="width: 90px;" name="father_dob" value="<?php echo $data['father_dob'];?>">
                  <img src="<?=Yii::$app->homeUrl?>images/dateIcon.gif">
                   <span style="display: none; position: absolute;"></span>
                </div>
              </div>
              <div class="col-md-12 mar10">
                <div class="col-md-2"></div>
                <div class="col-md-8"></div>
              </div>
              <div style="font-weight: bold; padding: 10px;">Faculty's Joining Details</div>
              <div class="col-lg-12 mar10">
                
                <div class="col-lg-2">Department<span class="required">*</span></div>
                <div class="col-lg-4">
                  <select id="Assign_Department" name="Department_Id">
                    <option selected="" value="<?php echo $data['Department_Id'];?>"><?php echo $data['Department_Name'];?></option>
                  </select>
                </div>
                <div class="col-lg-2"> Faculty Code <span class="required">*</span></div>
                <div class="col-lg-4">
                  <input type="text" readonly="readonly" id="frm_fac_empCode" value="<?php echo $data['emp_code'];?>" name="emp_code">
                </div>
              </div>
             <div class="col-lg-12 mar10">
                <div class="col-lg-2"> Designation <span class="required">*</span></div>
                <div class="col-lg-4">
                  <input type="text"  id="frm_fac_designation" value="<?php echo $data['designation'];?>" name="designation">
                </div>
                <div class="col-lg-2"> Date of Joining <span class="required">*</span></div>
                <div class="col-lg-4"> 
                          <?php
                       if(isset($data['date_of_joining']) && !empty($data['date_of_joining']))
                $data['date_of_joining'] =   date("d-m-Y", strtotime($data['date_of_joining']));
                ?>
                  <input readonly='readonly' type="text" id="doj" style="width: 90px;" name="date_of_joining" value="<?php echo $data['date_of_joining'];?>">
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
