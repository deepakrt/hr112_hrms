<?php
$this->title = 'eAkadamik WebApp';
echo \app\components\Topbarwidget::widget(array('menuid'=>$menuid));
$homeUrl = Yii::$app->homeUrl; 
//$batchSessions=Yii::$app->Utility->USP_ExtractSession('0');
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
     <div style="margin-top:30px; " class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-heading respantit">
            <h3 class="panel-title"> Upload Student Details </h3>
          </div>
               <span id='widgetversion_1_0_error_block' >
                <div class="alert alert-error error_main" id="error_main" style="display:none">  
                <ul id="widget_error_main_inner" >
                </ul>
                </div>         
                </span>
          <div class="panel-body">
            <div style="font-weight:bold ;padding:10px;">  Enter Details </div>
                <?php
        foreach (Yii::$app->session->getAllFlashes() as $key => $message)
        echo '<div class="col-sm-12" ><div class="text-center alert alert-' . $key . '">' . $message . '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div></div>';
            
    ?> 
            <form enctype="multipart/form-data" method="post" action="<?php echo $homeUrl?>workflow/addmultiplestudents/insert" name="frm" id="frm">
                <input type="hidden"  class="form-control" readonly="" name="secureKey" value="<?=  base64_encode($menuid)?>" />
                <input type="hidden"  class="form-control" readonly="" name="secureHash" value="<?=Yii::$app->Utility->getHashInsert($menuid)?>" />
                <input type="hidden" id="_csrf" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
              <div class="col-lg-12 mar10">
                <div class="col-lg-2"></div>
                <div class="col-lg-5"></div>
              </div>
              
               <div class="col-lg-12 mar10">                
                   <div class="col-lg-2">Department</div>
                    <div class="col-lg-4">
                        <select name="uploadstudent[deptInfo]" id="Assign_Department" class="CY">
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
                        <select name="uploadstudent[beCourse]" id="Assign_Course" onchange="getEndBatch()">
                            <option value="">Select Course</option>
                        </select>
                    </div>
                </div>
                  <div class="col-lg-12 mar10">
                   
                   <div class="col-lg-2">Start Batch</div>
                    <div class="col-lg-4">
                        <?php
                        $startBtach = date('Y');
                        ?>
                        <input id="uploadstudent_StartBatch" type="text" value="<?php echo $startBtach;?>" name="uploadstudent[StartBatch]" onchange="getEndBatch()">
                    </div>
                   <div class="col-lg-2">End Batch</div>
                    <div class="col-lg-4">
                    <input id="uploadstudent_EndBatch" type="text" readonly="readonly" value="" name="uploadstudent[EndBatch]">    
                    </div>
                   </div>            
                          
              
              <div class="col-lg-12 mar10" style="margin-top: 10px;">
                <div class="col-lg-2"></div>
                <div class="col-lg-5" style="font-weight: 300;"><a href="<?php echo $homeUrl;?>Formats/Multiple_Student_Upload_Format_WithReg.xlsx">Download Excel Sheet(XLSX) Format</a></div>
              </div>
              
              <div class="col-lg-12 mar10" style="margin-top: 10px;">
                <div class="col-lg-2">Upload Sheet</div>
                <div class="col-lg-4">
                  <input type="file" name="uploadstudent[fileUpload]" size="40" value="" id="frm_fileUpload">                  
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
                    <input name='insert' class="btn btn-primary" type="submit" onclick="return validateupdatemultiplestudents()" value="Save" id="">
                    <a class='btn btn-default' href='<?php echo $homeUrl;?>base/umsprivilege/index?secureKey=<?php echo $secureKey;?>&secureHash=<?php echo $secureHash;?>'>Cancel</a>
                </div>
              </div>
             
            </form>
            
          </div>
        </div>
      </div>
        <div class="row" style="margin-bottom: 70px;"> </div>
    </div>
    
    
