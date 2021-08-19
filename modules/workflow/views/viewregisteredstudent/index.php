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
View/Update Registered Students
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
    <!--<form method="post" action="<?php echo $homeUrl?>result/uploadresult/insert" name="frm" id="frm">-->
               <input type="hidden"  class="form-control" readonly="" id="secureKey" name="secureKey" value="<?=  base64_encode($menuid)?>" />
                <input type="hidden"  class="form-control" readonly=""  id="secureHash" name="secureHash" value="<?=Yii::$app->Utility->getHashView($menuid)?>" />
                <input id='_csrf' type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
                <div class="col-lg-12 mar10">
                  <div id="errmsg" style="color:red"></div>
                </div>
                
              
                <div style="font-weight:bold ;padding:10px;"> Select Details </div>
                
                
                
                <div class="col-lg-12 mar10">
                 <div class="col-lg-2">Department </div>
                 <div class="col-lg-4">
                  <select name="student[dept_id]" id="Assign_Department" class="CY new_Dept_Select">
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
                  <select name="student[course]" id="Assign_Course" class="new_Course_Select">
                       <option value="">Select Course</option>
                    </select>
                 </div>
                </div>
                
                                 
                <div class="col-lg-12 mar10">
                    <div class="col-lg-2">Batch </div>
                    <div class="col-lg-4">
                        <select name="student[session_yr]" id="actionSelect" class="new_Batch_Select">
                            <option value="">Select Batch</option>
                            
                        </select>
                    </div>
                    
                    <div class="col-lg-2">Semester </div>
                 <div class="col-lg-4">
                  <select name="student[semesterId]" id="semesterId" class="new_Semester_Select">
                        <option value="" >Select Semester</option>
                    </select>
                 </div>
                    
                </div>
                
                 <div class="col-lg-12 mar10">
                 
                </div>
                
                <div class="col-lg-12 mar10">
                 <div class="col-lg-2"></div>
                 <div class="col-lg-4">
                   <?php
                  $secureKey =  base64_encode($menuid);
                  $secureHash = Yii::$app->Utility->getHashView($menuid);
                  ?>
                    <input name='getstudentreglist' class="btn btn-primary" type="submit" value="View Registered Students" id="sturegviewbtn">
                </div>
              </div>
           <!-- </form>-->
           <div class="col-lg-12 mar10"></div>
           <div class="col-lg-12 mar10"></div>
            
            <div id="rstudentviewinfo" class="mar17"> 
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