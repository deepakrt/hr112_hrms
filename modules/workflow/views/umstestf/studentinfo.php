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
            <form method="post" action="<?php echo $homeUrl?>workflow/umstestf/update" name="frm" id="frm">
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
                <input type="hidden" name="Dept_id" value="<?php echo $data['Dept_id'];?>" />
                  <input type="text"  id="frm_student_firstName" value="<?php echo $data['Department_Name'];?>" maxlength="20" size="22" name="Department_Name">
                </div>
                <div class="col-lg-2">Last Name</div>
                <div class="col-lg-4">
                  <input type="text"  id="frm_student_lastName" value="<?php echo $data['Department_Description'];?>" maxlength="20" size="22" name="Department_Description">
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
                    <input name='update' class="btn btn-primary" type="submit" onclick="" value="Update" id="frm_0">
                    
                    <a class='btn btn-default' href='<?php echo $homeUrl;?>workflow/viewregisteredstudent/index?secureKey=<?php echo $secureKey;?>&secureHash=<?php echo $secureHash;?>'>Cancel</a>
                </div>
              </div>
             
            </form>
            
          </div>
        </div>
      </div>
        <div class="row" style="margin-bottom: 70px;"> </div>
    </div>
    
    
