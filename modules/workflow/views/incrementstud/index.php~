<?php
$this->title = 'eAkadamik WebApp';
echo \app\components\Topbarwidget::widget(array('menuid'=>$menuid));
$homeUrl = Yii::$app->homeUrl;
//$batch_session=Yii::$app->Utility2->USP_ExtractSession();
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
            <h3 class="panel-title"> Promote Student </h3>
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
            <form method="post" action="<?php echo $homeUrl?>workflow/incrementstud/promote" name="frm" id="frm">
                <input type="hidden"  class="form-control" readonly="" name="secureKey" value="<?=  base64_encode($menuid)?>" />
                <input type="hidden"  class="form-control" readonly="" name="secureHash" value="<?=Yii::$app->Utility->getHashInsert($menuid)?>" />
                <input id="_csrf" type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
              <table class="wwFormTable">
                <div class="col-lg-12 mar10">
                  <div id="errmsg" style="color:red"></div>
                </div>
                
                  
                  <div class="col-lg-12 mar10">
                    <div class="col-lg-2">Department</div>
                    <div class="col-lg-4">
                        <select name="Promote[deptInfo]" id="Assign_Department" class="CY">                           
                            <?php                          
                            if(!empty($departments)){
                                echo "<option value='' >Select Department</option>";
                                foreach ($departments as $departments) {
                                    $id= $departments['Department_Id'];
                                    $name= $departments['Department_Name'];
                                    echo "<option value='$id'>$name</option>";
                                }
                            }
                            else{
                                echo '<option value="">No Records Found in DB, Contact Admin</option>';
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="col-lg-2">Course</div>
                    <div class="col-lg-4">
                        <select name="Promote[beCourse]" id="Assign_Course" class="new_Course_Select" >
                            <option value="">Select Course</option>
                        </select>
                    </div>
                    
                </div>
                  
                  
                    <div class="col-lg-12 mar10">
                    <div class="col-lg-2">Batch</div>
                    <div class="col-lg-4">
                        <select name="Promote[session_yr]" id="actionSelect" class="new_Batch_Select">
                            <option value="">Select Batch</option>
                        </select>
                    </div>
                </div>
                  
                  <div class="col-lg-12 mar10">
                    <div class="col-lg-2">Semester Promoted From</div>
                    <div class="col-lg-4">
                        <select name="Promote[semester_promoted_from]" class="PromoteYes new_Semester_Select" id="semesterId">
                            <option value="" >Select Semester</option>
                        </select>
                    </div>
                    
                    <div class="col-lg-2">Semester Promoted To</div>
                    <div class="col-lg-4">
                        <input maxlength="1" readonly='readonly' type="text" id="semester_promoted_to" name="Promote[semester_promoted_to]" >                        
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
                    <input name='insert' class="btn btn-primary" type="submit" onclick="return validate_PromoteStudentMaster()" value="Save" id="frm_0">
      <a class='btn btn-default' href='<?php echo $homeUrl;?>base/umsprivilege/index?secureKey=<?php echo $secureKey;?>&secureHash=<?php echo $secureHash;?>'>Cancel</a>
                  </div>
                </div>
              </table>
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
