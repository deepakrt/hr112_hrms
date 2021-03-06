<?php
$this->title = 'eAkadamik WebApp';
echo \app\components\Topbarwidget::widget(array('menuid'=>$menuid));
$homeUrl = Yii::$app->homeUrl; 
    $gender=GENDER;
    $gender = explode(",",$gender);        
    $blood_group=BLOOD_GROUP;
    $blood_group = explode(",",$blood_group); 
    //$college=Yii::$app->Utility->USP_ExtractCollege();
    $departments=Yii::$app->Utility->USP_ExtractDepartment();
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
            <h3 class="panel-title"> Create Student </h3>
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
            <form method="post" action="<?php echo $homeUrl?>workflow/umsstudentmaster/insert" name="frm" id="frm">
                <input type="hidden"  class="form-control" readonly="" name="secureKey" value="<?=  base64_encode($menuid)?>" />
                <input type="hidden"  class="form-control" readonly="" name="secureHash" value="<?=Yii::$app->Utility->getHashInsert($menuid)?>" />
                <input type="hidden" id="_csrf" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
              <div class="col-lg-12 mar10">
                <div class="col-lg-2"></div>
                <div class="col-lg-5"></div>
              </div>
              <div class="col-lg-12 mar10"> </div>
              <div class="col-lg-12 mar10">
                <div class="col-lg-2">First Name <span class="required">*</span></div>
                <div class="col-lg-4">
                  <input type="text"  id="frm_student_firstName" value="" maxlength="20" size="22" name="student[firstName]">
                </div>
                <div class="col-lg-2">Last Name</div>
                <div class="col-lg-4">
                  <input type="text"  id="frm_student_lastName" value="" maxlength="20" size="22" name="student[lastName]">
                </div>
              </div>
              <div class="col-lg-12 mar10">
                <div class="col-lg-2">Gender<span class="required">*</span></div>
                <div class="col-lg-4">
                  <select  id="frm_student_gender" name="student[gender]">
                    <option value="">Select Gender</option>
                    <?php
                    foreach ($gender as $key=>$val) {
                        ?>

                        <option value="<?= $val ?>"><?= $val?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="col-lg-2">DOB<span class="required">*</span></div>
                <div class="col-lg-4"><input readonly='readonly' type="text" id="student_dob" name="student[student_dob]" style="width: 100px;">
                  <img style="vertical-align:middle; cursor:pointer; cursor:hand" src="<?php echo $homeUrl;?>images/dateIcon.gif">
                </div>
              </div>
              <div class="col-lg-12 mar10">
                <div class="col-lg-2">Contact No<span class="required">*</span></div>
                <div class="col-lg-4">
                  <input type="text"  id="frm_student_contactNumber" value="" maxlength="10" size="22" name="student[contactNumber]">
                </div>
                <div class="col-lg-2">Blood Group<span class="required">*</span></div>
                <div class="col-lg-4">
                  <select  id="frm_student_bloodGroup" name="student[bloodGroup]">
                    <option value="">Select Blood Group</option>
                    <?php
                    foreach ($blood_group as $key2=>$val2) {
                        ?>

                        <option value="<?= $val2 ?>"><?= $val2?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="col-lg-12 mar10">
                <div class="col-lg-2">Email-Id<span class="required">*</span></div>
                <div class="col-lg-4">
                  <input type="text"  id="frm_student_emailId" value="" maxlength="36" size="35" name="student[emailId]">
                </div>
                <div class="col-lg-2">Address<span class="required">*</span></div>
                <div class="col-lg-4">
                  <textarea  id="frm_student_address" rows="5" cols="28" name="student[address]" style="height:30px;width:100%;"></textarea>
                </div>
              </div>
              <div class="col-lg-12 mar10">
                <div class="col-lg-2">Category<span class="required">*</span></div>
                <div class="col-lg-4">
                   <select id="frm_student_category" name="student[category]">
                        <option value=""> Select Category</option>
                    <?php foreach ($MainCategory as $key=>$val) {  ?>
                    
                    <?php
                    $Category_id =$val['Category_id']; 
                    $Category_Code = $val['Category_Name'];
                    $Category_Name =$val['category_description']."(".$Category_Code.")"; 
?>                    
                     <option value="<?= $Category_id ?>"><?= $Category_Name?></option>
                    
                    <?php } ?>
                  </select>
                </div> 
                <div class="col-lg-2">Sub Category</div>
                <div class="col-lg-4">
                   <select id="frm_student_subcategory" name="student[subcategory]">
                        <option value=""> Select Sub Category</option>
                    <?php foreach ($Subcategory as $key=>$val) {  ?>
                    
                    <?php
                    $Category_id =$val['Category_id']; 
                    $Category_Code = $val['Category_Name'];
                    $Category_Name =$val['category_description']."(".$Category_Code.")";  
?>                    
                     <option value="<?= $Category_id ?>"><?= $Category_Name?></option>
                    
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
                  <div class="col-lg-4">
                   <input type="radio" name="student[StudentType]" id="stu_detail_dep" checked="checked" value="1"/>&nbsp; New Student
                  </div>
                  <div class="col-lg-4">
                   <input type="radio" name="student[StudentType]" id="stu_detail_leet" value="2"/>&nbsp; LEET Student
                  </div>
              	</div>
               <div class="col-lg-12 mar20 stu_detail_dep">
                    <div class="col-lg-2">Department<span class="required">*</span></div>
                    <div class="col-lg-4">
                      <select class="CY" id="Assign_Department" name="student[beDepartment]">
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
                    <div class="col-lg-2">Course<span class="required">*</span></div>
                        <div class="col-lg-4">
                        <select id="Assign_Course" name="student[beCourse]">
                            <option selected="selected" value="">Select Course</option>
                          </select>	
                        </div>
                </div>
               
              <div class="col-lg-12 mar10">
                <div class="col-lg-2">Roll No<span class="required">*</span></div>
                <div class="col-lg-4">
                  <input type="text" id="frm_student_rollnumber" value="" maxlength="16" size="22" name="student[rollnumber]">
                </div>
                <div class="col-lg-2">Registration No<span class="required">*</span></div>
                <div class="col-lg-4">
                  <input type="text"  id="frm_student_registrationNumber" value="" maxlength="16" size="22" name="student[registrationNumber]">
                </div>
              </div>
              
              <div class="col-lg-12 mar10">
                <div class="col-lg-2">Start Batch<span class="required">*</span></div>
                <div class="col-lg-4">
                  <input type="text" id="frm_student_startSession" value="<?php echo date('Y');?>" maxlength="4" size="22" name="student[startSession]">
                </div>
                <div class="col-lg-2">End Batch<span class="required">*</span></div>
                <div class="col-lg-4">
                  <input type="text"  id="frm_student_endSession" value="" maxlength="4" size="22" name="student[endSession]">
                </div>
              </div>             
              
              <div class="col-lg-12 mar10"> </div>
              <div class="col-lg-12 mar10"> </div>
              <div style="font-weight:bold ;padding:10px;">Family Details</div>
                <div class="col-lg-12 mar10">
                  <div class="col-lg-2">Father Name</div>
                  <div class="col-lg-4">
                    <input type="text"  id="frm_student_fathersName" value="" name="student[fathersName]">
                  </div>
                  <div class="col-lg-2">Mother Name</div>
                  <div class="col-lg-4">
                    <input type="text"  id="frm_student_mothersName" value="" name="student[mothersName]">
                  </div>
                </div>
                <div class="col-lg-12 mar10">
                  <div class="col-lg-2">Father's Email</div>
                  <div class="col-lg-4">
                    <input type="text"  id="frm_student_fathersEmail" value="" name="student[fathersEmail]">
                  </div>
                  <div class="col-lg-2">Father's DOB</div>
                  <div class="col-lg-4"> 
                  <input readonly='readonly' type="text" id="student_fatherdob" name="student[student_fatherdob]" style="width: 100px;">
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
                  <div class="col-lg-3">
                
                  <?php
                      $secureKey =  base64_encode($menuid);
                      $secureHash = Yii::$app->Utility->getHashView($menuid);
                      ?>
                    <input name='insert' class="btn btn-primary" type="submit" onclick="return validate_StudentMaster()" value="Save" id="frm_0">
                    <a class='btn btn-default' href='<?php echo $homeUrl;?>base/umsprivilege/index?secureKey=<?php echo $secureKey;?>&secureHash=<?php echo $secureHash;?>'>Cancel</a>
                </div>
              </div>
              <table class="wwFormTable">
                <tbody>
                  <tr style="display:none;">
                    <td colspan="2"><input type="hidden" id="frm_student_studentId" value="" name="student.studentId"></td>
                  </tr>
                </tbody>
              </table>
            </form>
            
          </div>
        </div>
      </div>
        <div class="row" style="margin-bottom: 70px;"> </div>
    </div>
    
    
