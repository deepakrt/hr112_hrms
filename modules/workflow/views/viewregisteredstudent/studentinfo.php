<?php
$this->title = 'eAkadamik WebApp';
echo \app\components\Topbarwidget::widget(array('menuid'=>$menuid));
$homeUrl = Yii::$app->homeUrl; 
$gender=GENDER;
$gender = explode(",",$gender);        
$blood_group=BLOOD_GROUP;
$blood_group = explode(",",$blood_group); 
$college=Yii::$app->Utility->USP_ExtractCollege();
$course=array('course1'=>'course1');
$alldepartments=Yii::$app->Utility1->USP_ExtractDepartmentAll();
 $MainCategory=Yii::$app->Utility->USP_ExtractCategory('1');
    $Subcategory=Yii::$app->Utility->USP_ExtractCategory('2');
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
<div style="margin-top:30px; " class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-heading respantit">
            <h3 class="panel-title"> Update Student Info </h3>
          </div>
               <span id='widgetversion_1_0_error_block' >
<div class="alert alert-error error_main" id="error_main" style="display:none">  
<ul id="widget_error_main_inner" >
</ul>
</div>         
</span>
          <div class="panel-body">
            <div style="font-weight:bold ;padding:10px;"> Enter Student's General Details</div>
                <?php
        foreach (Yii::$app->session->getAllFlashes() as $key => $message)
        echo '<div class="col-sm-12" ><div class="text-center alert alert-' . $key . '">' . $message . '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div></div>';
            
    ?> 
            <form method="post" action="<?php echo $homeUrl?>workflow/viewregisteredstudent/update" name="frm" id="frm">
                <input type="hidden"  class="form-control" readonly="" id="secureKey" name="secureKey" value="<?=  base64_encode($menuid)?>" />
                <input type="hidden"  class="form-control" readonly=""  id="secureHash" name="secureHash" value="<?=Yii::$app->Utility->getHashInsert($menuid)?>" />
                <input type="hidden" id="_csrf" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
              <div class="col-lg-12 mar10">
                <div class="col-lg-2"></div>
                <div class="col-lg-5"></div>
              </div>
              <div class="col-lg-12 mar10"> </div>
              <div class="col-lg-12 mar10">
                <div class="col-lg-2">First Name <span class="required">*</span></div>
                <div class="col-lg-4">
                  <input type="text" readonly="readonly" id="frm_student_firstName" value="<?php echo $data['first_name'];?>" maxlength="20" size="22" name="first_name">
                </div>
                <div class="col-lg-2">Last Name</div>
                <div class="col-lg-4">
                  <input type="text"  id="frm_student_lastName" value="<?php echo $data['last_name'];?>" maxlength="20" size="22" name="last_name">
                </div>
              </div>
              <div class="col-lg-12 mar10">
                <div class="col-lg-2">Gender<span class="required">*</span></div>
                <div class="col-lg-4">
                  <select id="frm_student_gender" name="gender">
                        <option value=""> Select Gender</option>
                    <?php foreach ($gender as $key=>$val) {  ?>
                    
                    <?php if ($val == Yii::$app->Utility->getupperstring($data['gender'])) { ?>
                        <option value="<?= $val ?>" selected="selected"><?= $val?></option>
                    <?php } else { ?>
                     <option value="<?= $val ?>"><?= $val?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
                
                <?php
                if(isset($data['dob']) && !empty($data['dob']))
                $data['dob'] =   date("d-m-Y", strtotime($data['dob']));
                ?>
                <div class="col-lg-2">DOB<span class="required">*</span></div>
                <div class="col-lg-4"><input readonly="readonly" type="text" id="student_dob" value="<?php echo $data['dob'];?>" name="dob" style="width: 100px;">
                  <img style="vertical-align:middle; cursor:pointer; cursor:hand" src="<?php echo $homeUrl;?>images/dateIcon.gif">
                </div>
              </div>
              <div class="col-lg-12 mar10">
                <div class="col-lg-2">Contact No<span class="required">*</span></div>
                <div class="col-lg-4">
                  <input type="text" id="frm_student_contactNumber" value="<?php echo $data['contact_no'];?>" maxlength="10" size="22" name="contact_no">
                </div>
                <div class="col-lg-2">Blood Group<span class="required">*</span></div>
                <div class="col-lg-4">
                  <select  id="frm_student_bloodGroup" name="Blood_group">
                    <?php foreach ($blood_group as $key=>$blood_groups) {  ?>
                    <?php if ($blood_groups == $data['Blood_group']) { ?>
                        <option value="<?= $blood_groups ?>" selected="selected"><?= $blood_groups?></option>
                    <?php } else { ?>
                     <option value="<?= $blood_groups ?>"><?= $blood_groups?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="col-lg-12 mar10">
                <div class="col-lg-2">Email-Id<span class="required">*</span></div>
                <div class="col-lg-4">
                  <input type="text"  id="frm_student_emailId" value="<?php echo $data['email_id'];?>" maxlength="36" size="35" name="email_id">
                </div>
                <div class="col-lg-2">Address<span class="required">*</span></div>
                <div class="col-lg-4">
                  <textarea  id="frm_student_address" rows="5" cols="28" name="address" style="height:30px;width:100%;"><?php echo $data['address'];?></textarea>
                </div>
              </div>
              
              <div class="col-lg-12 mar10">
                <div class="col-lg-2">Category<span class="required">*</span></div>
                <div class="col-lg-4">
                   <select id="frm_student_category" name="category">
                        <option value=""> Select Category</option>
                    <?php foreach ($MainCategory as $key=>$val) {  ?>
                    
                    <?php
                    $Category_id =$val['Category_id']; 
                    $Category_Code = $val['Category_Name'];
                    $Category_Name =$val['category_description']."(".$Category_Code.")";  
                    if ($Category_id == $data['category']) { ?>
                        <option value="<?= $Category_id ?>" selected="selected"><?= $Category_Name?></option>
                    <?php } else { ?>
                     <option value="<?= $Category_id ?>"><?= $Category_Name?></option>
                    <?php } ?>
                    <?php } ?>
                  </select>
                </div> 
                <div class="col-lg-2">Sub Category</div>
                <div class="col-lg-4">
                   <select id="frm_student_subcategory" name="subcategory">
                        <option value=""> Select Sub Category</option>
                    <?php foreach ($Subcategory as $key=>$val) {  ?>
                    
                    <?php
                    $Category_id =$val['Category_id']; 
                    $Category_Code = $val['Category_Name'];
                    $Category_Name =$val['category_description']."(".$Category_Code.")";  
                
                     if ($Category_id == $data['subcategory']) { ?>
                        <option value="<?= $Category_id ?>" selected="selected"><?= $Category_Name?></option>
                    <?php } else { ?>
                     <option value="<?= $Category_id ?>"><?= $Category_Name?></option>
                    <?php } ?>
                    <?php } ?>
                    
                    
                  </select>
                </div>
              </div>
              
              <div class="col-md-12 mar10">
                <div class="col-md-2"></div>
                <div class="col-md-8"></div>
              </div>
              <div style="font-weight:bold ;padding:10px;"> Enter Student's Admission Details</div>
              
               <div class="col-lg-12 mar10">
                    <div class="col-lg-2">Department<span class="required">*</span></div>
                    <div class="col-lg-4">
                      <select class="CY" id="Assign_Department" name="Department_Id">
                        <option selected="selected" value="<?php echo $data['Department_Id'];?>"><?php echo $data['Department_Name'];?></option>
                      </select>
                    </div>
                    <div class="col-lg-2">Course<span class="required">*</span></div>
                    <div class="col-lg-4">
                    <select id="Assign_Course" name="Course_Id">
                        <option selected="selected" value="<?php echo $data['Course_Id'];?>"><?php echo $data['Course_Name'];?></option>
                      </select>
                  </div>
              </div>
              
              <div class="col-lg-12 mar10" >
              <input class ="change_studentDep" type="checkbox" name="change_studentDep" /><font color="#FF0000">&nbsp; Shift Student to other Department ?</font>
              </div>
              <div class="col-lg-12 mar10 chage_dep_section" style="display:none">
                    <div class="col-lg-2">To Department<span class="required">*</span></div>
                    <div class="col-lg-4">
                      <select name="Department_IdTo" id="New_Department">
                        <?php
                        if(!empty($alldepartments)){
                            echo '<option value="" >Select Department</option>';
                            foreach ($alldepartments as $alldepartment) {
                                $id= $alldepartment['Department_Id'];
                                $name= $alldepartment['Department_Name'];
                                echo "<option value='$id'>$name</option>";
                            }
                        }else{
                            echo "<option value=''>No Records Found in DB. Contact Admin</option>";
                        }
                        ?>
                      </select>
                    </div>
                    <?php
              $batchid = $data['Batch'];
              $USP_ExtractSemester = Yii::$app->Utility->USP_ExtractSemester($batchid);
              ?>
                    <div class="col-lg-2">To Semester<span class="required">*</span></div>
                    <div class="col-lg-4">
                      <select name="Semester_IdTo" id="New_Semester">
                        <?php
                        if(!empty($USP_ExtractSemester)){
                            echo '<option value="" >Select Semester</option>';
                            for($i=1; $i<=$USP_ExtractSemester; $i++)   
                            {
                                echo "<option value='$i'>$i</option>";   
                            }
                        }
                            else{
                            echo "<option value=''>No Records Found in DB. Contact Admin</option>";
                            }
                        ?>
                      </select>
                    </div>
              </div>
              
              <div class="col-lg-12 mar10">
                <div class="col-lg-2">Roll No<span class="required">*</span></div>
                <div class="col-lg-4">
                  <input type="text" readonly="readonly" id="frm_student_rollnumber" value="<?php echo $data['Roll_Number'];?>" maxlength="16" size="22" name="Roll_Number">
                </div>
                <div class="col-lg-2">Registration No<span class="required">*</span></div>
                <div class="col-lg-4">
                  <input type="text" readonly="readonly"  id="frm_student_registrationNumber" value="<?php echo $data['Registration_Number'];?>" maxlength="16" size="22" name="Registration_Number">
                </div>
              </div>
              
              <div class="col-lg-12 mar10">
                <div class="col-lg-2">Batch<span class="required">*</span></div>
                <div class="col-lg-4">
                  <input type="text"  id="frm_student_endSession" value="<?php echo $data['Batch'];?>" maxlength="4" size="22" name="Batch" readonly="readonly">
                  <input type="hidden" re id="College_Id" value="<?php echo $data['College_Id'];?>" maxlength="36" size="35" name="College_Id">
                </div>
              </div>             
              
              <div class="col-lg-12 mar10"> </div>
              <div class="col-lg-12 mar10"> </div>
              <div style="font-weight:bold ;padding:10px;">Family Details</div>
                <div class="col-lg-12 mar10">
                  <div class="col-lg-2">Father Name</div>
                  <div class="col-lg-4">
                    <input type="text"  id="frm_student_fathersName" value="<?php echo $data['father_name'];?>" name="father_name">
                  </div>
                  <div class="col-lg-2">Mother Name</div>
                  <div class="col-lg-4">
                    <input type="text"  id="frm_student_mothersName" value="<?php echo $data['mother_name'];?>" name="mother_name">
                  </div>
                </div>
                <div class="col-lg-12 mar10">
                  <div class="col-lg-2">Father's Email</div>
                  <div class="col-lg-4">
                    <input type="text"  id="frm_student_fathersEmail" value="<?php echo $data['father_email'];?>" name="father_email">
                  </div>
                  <div class="col-lg-2">Father's DOB</div>
                  <div class="col-lg-4"> 
                       <?php
                       if(isset($data['father_dob']) && !empty($data['father_dob']))
                $data['father_dob'] =   date("d-m-Y", strtotime($data['father_dob']));
                ?>
                  <input readonly="readonly" type="text" id="student_fatherdob" value="<?php echo $data['father_dob'];?>" name="father_dob" style="width: 100px;">
                  <img style="vertical-align:middle; cursor:pointer; cursor:hand" src="<?php echo $homeUrl;?>images/dateIcon.gif">
                  </div>
                </div>
              <div class="col-lg-12 mar10">
                <div class="col-lg-2"> </div>
                <div class="col-lg-5">
                  <div id="error_fname"></div>
                  <div id="error_fnamechar"></div>
                  <div id="error_fnamelen"></div>
                </div>
                <div class="col-lg-2"> </div>
                <div class="col-lg-5">
                  <div id="error_dob"></div>
                  <div id="error_dobmon"></div>
                  <div id="error_dobday"></div>
                  <div id="error_dobdays"></div>
                  <div id="error_dobleap"></div>
                </div>
              </div>
              <div class="col-lg-12 mar10">
                <div class="col-lg-2"> </div>
                <div class="col-lg-5">
                  <div id="error_gender"></div>
                </div>
                <div class="col-lg-2"> </div>
                <div class="col-lg-5">
                  <div id="error_emailval"></div>
                  <div id="error_emailreq"></div>
                </div>
              </div>
              <div class="col-lg-12 mar10">
                  <div class="col-lg-1"></div>
                  <div class="col-lg-3"></div>
                  <div class="col-lg-1"></div>
                  <div class="col-lg-6">
                
                  <?php
                      $secureKey =  base64_encode($menuid);
                      $secureHash = Yii::$app->Utility->getHashView($menuid);
                      ?>
                    <input name='update' class="btn btn-primary" type="submit" onclick="return validate_StudentMaster()" value="Update" id="frm_0">
                    
                    <a class='btn btn-default' href='<?php echo $homeUrl;?>workflow/viewregisteredstudent/index?secureKey=<?php echo $secureKey;?>&secureHash=<?php echo $secureHash;?>'>Cancel</a>
                </div>
              </div>
             
            </form>
            
          </div>
        </div>
      </div>
        <div class="row" style="margin-bottom: 70px;"> </div>
    </div>
    
    
