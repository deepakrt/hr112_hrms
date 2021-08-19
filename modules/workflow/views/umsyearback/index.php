<?php
$this->title = 'eAkadamik WebApp';
echo \app\components\Topbarwidget::widget(array('menuid'=>$menuid));
$homeUrl = Yii::$app->homeUrl;
//$batch_session=Yii::$app->Utility->USP_ExtractSession(0);
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
            <h3 class="panel-title">  
Year Back Student Details
  </h3>
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
    <form method="post" action="<?php echo $homeUrl?>workflow/umsyearback/process" name="frm" id="frm">
               <input type="hidden"  class="form-control" readonly="" id="secureKey" name="secureKey" value="<?=  base64_encode($menuid)?>" />
                <input type="hidden"  class="form-control" readonly=""  id="secureHash" name="secureHash" value="<?=Yii::$app->Utility->getHashView($menuid)?>" />
                <input id='_csrf' type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
                <div class="col-lg-12 mar10">
                  <div id="errmsg" style="color:red"></div>
                </div>
                
                <div class="col-lg-12 mar10">
                 <div class="col-lg-2">Department </div>
                 <div class="col-lg-4">
                  <select name="yearback[dept_id]" id="Assign_Department" class="CY new_Dept_Select">
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
                 <div class="col-lg-2">Course </div>
                 <div class="col-lg-4">
                  <select name="yearback[course]" id="Assign_Course" class="new_Course_Select">
                       <option value="">Select Course</option>
                    </select>
                 </div>
                </div>
                
                                 
                <div class="col-lg-12 mar10">
                    <div class="col-lg-2">Batch From</div>
                    <div class="col-lg-4">
                        <select name="yearback[session_yr]" id="actionSelect" class="new_Batch_Select">
                            <option value="">Select Batch</option>
                            
                        </select>
                    </div>
                    
                    <div class="col-lg-2">Semester From </div>
                 <div class="col-lg-4">
                  <select name="yearback[semesterId]" id="semesterId" class="new_Semester_Select">
                        <option value="" >Select Semester</option>
                    </select>
                 </div>
                    
                </div> 
                
                <div class="col-lg-12 mar10">
                  <div class="col-lg-2">Roll No </div>
                 <div class="col-lg-4">
                  <input type="text" name="yearback[RollNo]" maxlength="10" value="" id="yearback_RollNo"/>    
                 </div>                   
                 <div class="col-lg-4">
                   <?php
                  $secureKey =  base64_encode($menuid);
                  $secureHash = Yii::$app->Utility->getHashView($menuid);
                  ?>
                    <input class="yearback_searchStudentbtn btn btn-primary" type="button" value="Search" id="yearback_searchStudentbtn" onclick="return validateYearBack()">
                    <a class='btn btn-default' href='<?php echo $homeUrl;?>base/umsprivilege/index?secureKey=<?php echo $secureKey;?>&secureHash=<?php echo $secureHash;?>'>Cancel</a>
                </div>
                    
                </div>
                
                <div style="display:none" id="Year_Back_ProcessDiv" class="Year_Back_ProcessDiv">
                 <div class="col-lg-12 mar10">
                     <hr>
                    <div class="col-lg-2">Batch To </div>
                    <div class="col-lg-4">
                    <select name="yearback[session_yrTo]" id="actionSelectTo" class="new_Batch_SelectTo">
                            
                    </select>
                    </div>
                    
                    <div class="col-lg-2">Semester To </div>
                 <div class="col-lg-4">
                  <select name="yearback[semesterIdTo]" id="semesterIdTo" class="new_Semester_SelectTo">
                        <option value="" >Select Semester</option>
                    </select>
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
                    <input name='update' class="btn btn-primary" type="submit" onclick="return validateYearBackSubmit()" value="Submit" id="frm_0">
                    
                    <a class='btn btn-default' href='<?php echo $homeUrl;?>base/umsprivilege/index?secureKey=<?php echo $secureKey;?>&secureHash=<?php echo $secureHash;?>'>Cancel</a>
                    
                </div>
              </div>
          </div>
                
                <div class="col-lg-12 mar10"></div>
           <div class="col-lg-12 mar10"></div>
                
              
           </form>          
            
            <div id="YearBackStudentDetails" class="mar17"> 
                <!--in this section show faculty information-->
            </div>
            
          </div>
        </div>
      </div>  
         
      <div class="row" style="margin-bottom:70px;"> 
      </div>
    </div>
</div>
<div id="border-bottom">
  <div>
    <div></div>
  </div>
</div>