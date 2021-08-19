<?php
$this->title = 'eAkadamik WebApp';
echo \app\components\Topbarwidget::widget(array('menuid'=>$menuid));
$homeUrl = Yii::$app->homeUrl;
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
            <h3 class="panel-title"> College Master </h3>
          </div>
            <span id='widgetversion_1_0_error_block' >
<div class="alert alert-error error_main" id="error_main" style="display:none">  
<ul id="widget_error_main_inner" >
</ul>
</div>         
</span>
          <div class="panel-body">
            <div style="font-weight:bold ;padding:10px;"> Enter College Details</div>
             <?php
        foreach (Yii::$app->session->getAllFlashes() as $key => $message)
        echo '<div class="col-sm-12" ><div class="text-center alert alert-' . $key . '">' . $message . '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div></div>';
            
    ?> 
            <form method="post" action="<?php echo $homeUrl?>workflow/umscollegemaster/insert" name="frm" id="frm">
                <input type="hidden"  class="form-control" readonly="" name="secureKey" value="<?=  base64_encode($menuid)?>" />
                <input type="hidden"  class="form-control" readonly="" name="secureHash" value="<?=Yii::$app->Utility->getHashInsert($menuid)?>" />
                <input id="_csrf" type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" >
              <table class="wwFormTable">
                <div class="col-lg-12 mar10">
                  <div id="errmsg" style="color:red"></div>
                </div>
                <div class="col-lg-12 mar10">
                  <div class="col-lg-2">Name<span class="required">*</span></div>
                  <div class="col-lg-4">
                    <input type="text" name="beCollege[collegeName]" maxlength="150" value="" id="CollegeName"/>
                  </div>
                  <div class="col-lg-2">Address<span class="required">*</span></div>
                  <div class="col-lg-4">
                    <input type="text" name="beCollege[collegeAddress]" maxlength="200" value="" id="collegeAddress"/>
                  </div>
                </div>
                <div class="col-lg-12 mar10">
                  <div class="col-lg-2">Email-Id<span class="required">*</span></div>
                  <div class="col-lg-4">
                    <input type="text" name="beCollege[emailId]" maxlength="50" value="" id="emailId"/>
                  </div>
                  <div class="col-lg-2">City<span class="required">*</span></div>
                  <div class="col-lg-4">
                    <input type="text" name="beCollege[city]" maxlength="30" value="" id="collegeCity"/>
                  </div>
                </div>
                <div class="col-lg-12 mar10">
                  <div class="col-lg-2">Contact No.<span class="required">*</span></div>
                  <div class="col-lg-4">
                    <input type="text" name="beCollege[contactNo]" maxlength="10" value="" id="contactNo"/>
                  </div>
                  <div class="col-lg-2">Pin Code<span class="required">*</span></div>
                  <div class="col-lg-4">
                    <input type="text" name="beCollege[pinCode]" maxlength="6" value="" id="pinCode"/>
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
                    <input name='insert' class="btn btn-primary" type="submit" onclick="return validate_ClgMaster()" value="Save" id="frm_0">
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
