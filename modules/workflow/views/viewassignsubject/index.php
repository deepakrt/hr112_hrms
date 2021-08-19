<?php
$this->title = 'eAkadamik WebApp';
echo \app\components\Topbarwidget::widget(array('menuid'=>$menuid));
$homeUrl = Yii::$app->homeUrl;
//$batchSessions=Yii::$app->Utility->USP_ExtractSession(0);
$departments=Yii::$app->Utility->USP_ExtractDepartment();
$GROUP_FOR=GROUP_FOR_LABEL;
$GROUP_FOR = explode(",",$GROUP_FOR); 
$GROUP_FOR_VALUE = GROUP_FOR_VALUE;
$GROUP_FOR_VALUE = explode(",",$GROUP_FOR_VALUE); 
?>

<!-- Modal -->
<div class="modal fade" id="viewgroupstudentlist" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div style="width:60%" class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background:#36A8AB;color:#fff;">
        <button style="opacity: 1; color: #fff;" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="" >Students List</h4>
      </div>
      <div class="modal-body">
          <div id="viewgroupstudentlist_html"></div>
      </div>
    </div>
  </div>
</div>

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
            <h3 class="panel-title"> View Assigned/Unassigned Subjects </h3>
          </div>
            <span id='widgetversion_1_0_error_block' >
                <div class="alert alert-error error_main" id="error_main" style="display:none">  
                <ul id="widget_error_main_inner" >
                </ul>
                </div>         
                </span>
          <div class="panel-body">
            <div style="font-weight:bold ;padding:10px;"> Enter Details</div>
             <?php
        foreach (Yii::$app->session->getAllFlashes() as $key => $message)
        echo '<div class="col-sm-12" ><div class="text-center alert alert-' . $key . '">' . $message . '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div></div>';
            
    ?> 
            <form method="post" action="#" name="frm_viewassignsubject" id="frm_viewassignsubject" class="frm_viewassignsubject"> 
                <input type="hidden"  class="form-control" readonly="" name="secureKey" value="<?=  base64_encode($menuid)?>" />
                <input type="hidden"  class="form-control" readonly="" name="secureHash" value="<?=Yii::$app->Utility->getHashInsert($menuid)?>" />                
                <input id="_csrf" type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
                <input id="assigngrpidtofaculty" type="hidden" name="viewassignsubject[assign_grp_tofaculty]" value="" />
              <table class="wwFormTable">
                <div class="col-lg-12 mar10">
                  <div id="errmsg" style="color:red"></div>
                </div>
                  <div class="col-lg-12 mar10">
                    
                     <div class="col-lg-2">Department</div>
                  <div class="col-lg-4">
                     <select name="viewassignsubject[department]" id="Assign_Department" class="CY viewassignsubject_HideDisplaydiv">
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
                     <select name="viewassignsubject[course]" id="Assign_Course" class="new_Course_Select viewassignsubject_HideDisplaydiv" >
                       <option value="">Select Course</option>
                     </select>
                  </div>
                   
                </div>
                <div class="col-lg-12 mar10">
                    
                    <div class="col-lg-2">Batch  </div>
                    <div class="col-lg-4">
                        <select name="viewassignsubject[session_yr]" id="actionSelect" class= "new_Batch_Select viewassignsubject_HideDisplaydiv">
                           <option value="">Select Batch</option>
                        </select>
                    </div>
                 
                  
                  
                  <div class="col-lg-2">Semester</div>
                  <div class="col-lg-4">
                     <select name="viewassignsubject[semesterId]" id="semesterId" class="new_Semester_Select viewassignsubject_HideDisplaydiv" >
                        <option value="" >Select Semester</option>
                    </select>
                  </div>
                  
                </div> 
                
                <div class="col-lg-12 mar10">
                 <div class="col-lg-2">Subject Type</div>
                    <div class="col-lg-4">
                        <select  class = "viewassignsubject_HideDisplaydiv1" id="Subject_Type" name="viewassignsubject[Subject_Type]" onchange="getSubject($(this).val(), 'subjectId')" >
                    <option value="">Select Subject Type</option>
                    <?php
                    foreach ($GROUP_FOR as $key=>$val) {                        ?>

                        <option value="<?= $GROUP_FOR_VALUE[$key] ?>"><?= $val?></option>
                    <?php } ?>
                  </select>
                    </div>
                  <div class="col-lg-2">Subject</div>
                  <div class="col-lg-4">
                     <select class = "viewassignsubject_HideDisplaydiv1" name="viewassignsubject[subjectId]" id="subjectId">
                       <option value="">Select Subject</option>
                     </select>
                  </div>
                </div>
                               
                <div class="col-lg-12 mar10"> </div>
                <div class="col-lg-12 mar10">
                  
                  <div class="col-lg-1"></div>
                   <div class="col-lg-3"></div>
                  <div class="col-lg-5">
				  <?php
                  $secureKey =  base64_encode($menuid);
                  $secureHash = Yii::$app->Utility->getHashView($menuid);
                  ?>
      <input name='view_subject' class="btn btn-primary view_subject_btn" type="button" onclick="return validate_ViewSubject()" value="View Assigned Subject" id="view_subject_btn">
     <a class='btn btn-default' href='<?php echo $homeUrl;?>base/umsprivilege/index?secureKey=<?php echo $secureKey;?>&secureHash=<?php echo $secureHash;?>'>Cancel</a>
                  </div>
                </div>
              </table>
            </form>
            
          </div>
        </div>
      </div>
        <div style="padding:29px" id="ViewSubject_Div" class="row marbot70 ViewSubject_Div">
            
        </div>
    </div>
    
    
</div>


<div id="border-bottom">
  <div>
    <div></div>
  </div>
</div>
