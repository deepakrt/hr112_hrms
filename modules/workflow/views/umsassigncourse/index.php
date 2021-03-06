<?php
$this->title = 'eAkadamik WebApp';
echo \app\components\Topbarwidget::widget(array('menuid'=>$menuid));
$homeUrl = Yii::$app->homeUrl;
$USP_ExtractDepartment = Yii::$app->Utility->USP_ExtractDepartment();
//echo "<pre>";print_r($USP_ExtractDepartment); die;

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
            <h3 class="panel-title"> Assign Course To Department </h3>
          </div>
            <span id='widgetversion_1_0_error_block' >
<div class="alert alert-error error_main" id="error_main" style="display:none">  
<ul id="widget_error_main_inner" >
</ul>
</div>         
</span>
          <div class="panel-body">
              <?php
              if(!empty($USP_ExtractDepartment))
              {
              ?>
            <div style="font-weight:bold ;padding:10px;"> Enter Details</div>
             <?php
        foreach (Yii::$app->session->getAllFlashes() as $key => $message)
        echo '<div class="col-sm-12" ><div class="text-center alert alert-' . $key . '">' . $message . '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div></div>';
            
    ?> 
            <form method="post" action="<?php echo $homeUrl?>workflow/umsassigncourse/insert" name="frm" id="frm">
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
                      <select name="Assign[Department]" class="CY" id="Assign_Department" > 
                          <option value=''>Select Department</option>
                          <?php
                          foreach($USP_ExtractDepartment as $USP_ExtractDepartmentK=>$USP_ExtractDepartmentV)
                          {
                              $dept_name = $USP_ExtractDepartmentV['Department_Name'];
                              $dept_value = $USP_ExtractDepartmentV['Department_Id'];
                              echo "<option value='$dept_value'>$dept_name</option>";
                          }
                          ?>
                      </select>
                  </div>
                  <div class="col-lg-2">Course</div>
                  <div class="col-lg-4">
                      <select name="Assign[Course]" id="Assign_Course" >
                          <option value=''>Select Course</option>
                      </select>  
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
                    <input name='insert' class="btn btn-primary" type="submit" onclick="return validate_AssignCourse()" value="Save" id="frm_0">
      <a class='btn btn-default' href='<?php echo $homeUrl;?>base/umsprivilege/index?secureKey=<?php echo $secureKey;?>&secureHash=<?php echo $secureHash;?>'>Cancel</a>
                  </div>
                </div>
              </table>
            </form>
            <?php
              }
              else
              {
            ?>
            <div class="alert alert-danger">
            Department Is Empty, Contact Admin.
            </div>
              <?php }
              ?>
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
