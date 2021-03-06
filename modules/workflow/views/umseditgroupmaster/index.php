<?php
$this->title = 'eAkadamik WebApp';
echo \app\components\Topbarwidget::widget(array('menuid'=>$menuid));
$homeUrl = Yii::$app->homeUrl;

$college=Yii::$app->Utility->USP_ExtractCollege();
//$batchSessions=Yii::$app->Utility2->USP_ExtractSession();
$departments=Yii::$app->Utility->USP_ExtractDepartment();
$GROUP_FOR=GROUP_FOR_LABEL;
$GROUP_FOR = explode(",",$GROUP_FOR); 
$GROUP_FOR_VALUE = GROUP_FOR_VALUE;
$GROUP_FOR_VALUE = explode(",",$GROUP_FOR_VALUE); 
$Edit_Actions = array('S'=>'Change Group','D'=>'Assign Group','U'=>'Un Group'); 
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
    
    <div class="col-lg-9" id="form_group_serailize_EDIT">
    <div class="col-md-12 mar30">
        <div class="panel panel-default">
          <div class="panel-heading respantit">
            <h3 class="panel-title"> Edit/Update Group</h3>
          </div>
            <span id='widgetversion_1_0_error_block' >
        <div class="alert alert-error error_main" id="error_main" style="display:none">  
        <ul id="widget_error_main_inner" >
        </ul>
        </div>         
        </span>
          <div class="panel-body">
            <div style="font-weight:bold ;padding:10px;"> Select Details</div>
             <?php
        foreach (Yii::$app->session->getAllFlashes() as $key => $message)
        echo '<div class="col-sm-12" ><div class="text-center alert alert-' . $key . '">' . $message . '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div></div>';
            
    ?> 
            <form method="post" action="#">
                <input type="hidden"  class="form-control" readonly="" name="secureKey" value="<?=  base64_encode($menuid)?>" />
                <input type="hidden"  class="form-control" readonly="" name="secureHash" value="<?=Yii::$app->Utility->getHashInsert($menuid)?>" />
                <input id='_csrf' type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
              <table class="wwFormTable">
                <div class="col-lg-12 mar10">
                  <div id="errmsg" style="color:red"></div>
                </div>
                
<!--                <div class="col-lg-12 mar10">
                    <div class="col-lg-2">College </div>
                    <div class="col-lg-4">
                        <select id="clgId" name="beGroupMaster[college]" >
                            <option value="" >Select College</option>
                             <?php
//                                foreach ($college as $collegeK=>$collegeV) {
//                                  $College_Name = $collegeV['College_Name'];
//                                  $College_Id = $collegeV['College_Id'];
//                                  echo "<option value='$College_Id'>$College_Name</option>";
//                                 } 
                                 ?>
                        </select>
                    </div>
                </div>-->
                <div class="col-lg-12 mar10">
                    <div class="col-lg-2">Department</div>
                    <div class="col-lg-4">
                        <select name="beGroupMaster[deptInfo]" id="Assign_Department" class="CY new_Dept_Select reset_editGroup_div">
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
                    
                    <div class="col-lg-2">Course</div>
                    <div class="col-lg-4">
                        <select name="beGroupMaster[beCourse]" id="Assign_Course" class="new_Course_Select reset_editGroup_div" >
                            <option value="">Select Course</option>
                        </select>
                    </div>
                    
                </div>                

<div class="col-lg-12 mar10">
                    <div class="col-lg-2">Batch  </div>
                    <div class="col-lg-4">
                        <select name="beGroupMaster[session_yr]" id="actionSelect" class="new_Batch_Select reset_editGroup_div" onchange="EditGroupList('Y')">
                           <option value="">Select Batch</option>
                        </select>
                    </div>
                    <div class="col-lg-2">Semester</div>
                    <div class="col-lg-4">
                        <select class="new_Semester_Select reset_editGroup_div" name="beGroupMaster[semesterId]"  id="semesterId">
                            <option value="" >Select Semester</option>
                        </select>
                    </div>
                </div>
            <div class="col-lg-12 mar10">
                    <div class="col-lg-2">Action </div>
                    <div class="col-lg-4">
                        <select name="beGroupMaster[action]" id="beGroupMaster_action" class="beGroupMaster_action" >
                           <option value="">Select Action</option>
                           <?php
                           
                           foreach($Edit_Actions as $Edit_ActionsK=>$Edit_ActionsV)
                           {
                            echo "<option value='$Edit_ActionsK'>$Edit_ActionsV</option>";
                           }
                           ?>
                        </select>
                    </div>
                    
            </div>

                  <div id = "beGroupMaster_Group_For_Main_div" style="display:none" class="col-lg-12 mar10">
                    <div class="col-lg-2">Group For</div>
                    <div class="col-lg-4">
                        <select  id="Group_For" name="beGroupMaster[Group_For]" onchange="EditGroupList('Y')">
                    <option value="">Select Group For</option>
                    <?php
                    foreach ($GROUP_FOR as $key=>$val) {                        ?>

                        <option value="<?= $GROUP_FOR_VALUE[$key] ?>"><?= $val?></option>
                    <?php } ?>
                  </select>
                    </div>
                    <div id="Elective_Subject_For" class="Elective_Subject_For" style="display: none">
                </div>
                </div>
                
                <div class="col-lg-12 mar10" style="display:none" id ="beGroupMaster_RollNo_main_div">
                <div class="col-lg-2">Roll No</div>
                <div class="col-lg-4">
                <input type="text" name="beGroupMaster[RollNo]" maxlength="20" value="" id="beGroupMaster_RollNo"/>    
                </div>
                <div class="col-lg-6">
                      <?php
                      $secureKey =  base64_encode($menuid);
                      $secureHash = Yii::$app->Utility->getHashView($menuid);
                      ?>
                    <input style="display:none" name='search' class="btn btn-primary Editgroup_Search" type="button" onclick="return validateEditgroupMaster()" value="Search" id="Editgroup_Search">
                    <input class="btn btn-default" type="reset" Value="Reset">
      <a class='btn btn-default' href='<?php echo $homeUrl;?>base/umsprivilege/index?secureKey=<?php echo $secureKey;?>&secureHash=<?php echo $secureHash;?>'>Cancel</a>
                  </div>
                </div>

                  <div id = "beGroupMaster_Group_List" style="display:none" class="col-lg-12 mar10 beGroupMaster_Group_List">
                    
                   
                 </div>

                <div class="col-lg-12 mar10"> </div>
                <div class="col-lg-12 mar10">
                  <div class="col-lg-2"></div>
                  
                </div>
              </table>
             </form>
          </div>
        </div>
      </div>
        <div style="padding:29px" id="EDIT_studentinfo" class="row marbot70">
            
        </div>
    </div>
    
    
</div>


<div id="border-bottom">
  <div>
    <div></div>
  </div>
</div>
