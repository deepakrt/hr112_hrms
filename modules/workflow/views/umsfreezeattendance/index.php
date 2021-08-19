<?php
$this->title = 'eAkadamik WebApp';
echo \app\components\Topbarwidget::widget(array('menuid'=>$menuid));
$homeUrl = Yii::$app->homeUrl;
//$batch_session=Yii::$app->Utility->USP_ExtractSession(0);
$sessions=Yii::$app->Utility->USP_ExtractSession(0);
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
Freeze/De-Freeze Attendance
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
    <form method="post" action="<?php echo $homeUrl?>workflow/umsfreezeattendance/process" name="frm" id="frm">
               <input type="hidden"  class="form-control" readonly="" id="secureKey" name="secureKey" value="<?=  base64_encode($menuid)?>" />
                <input type="hidden"  class="form-control" readonly=""  id="secureHash" name="secureHash" value="<?=Yii::$app->Utility->getHashView($menuid)?>" />
                <input id='_csrf' type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
                <div class="col-lg-12 mar10">
                  <div id="errmsg" style="color:red"></div>
                </div>
                
                <div class="col-lg-12 mar10">
                 <div class="col-lg-2">Batch </div>
                 <div class="col-lg-4">
                  <select name="freeze[Batch]" id="actionSelect" class="new_Batch_Select">
                      <?php
                        if(!empty($sessions)){
                            echo '<option value="" >Select Batch</option>';
                            foreach ($sessions as $sessions) {                               
                                $name= $sessions['Batch'];
                                echo "<option value='$name'>$name</option>";
                            }
                        }else{
                            echo "<option value=''>No Records Found in DB. Contact Admin</option>";
                        }
                        ?>
                    </select>
                 </div>
                 
                  <div class="col-lg-2">Semester</div>
                 <div class="col-lg-4">
                  <select name="freeze[semesterId]" id="semesterId" class="new_Semester_Select">
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
                    <input name='freeze[Freeze]' class="btn btn-primary" type="submit" onclick="return validateFreezeAttendance()" value="Freeze" id="frm_0">
                    <input name='freeze[Freeze]' class="btn btn-warning" type="submit" onclick="return validateFreezeAttendance()" value="DeFreeze" id="frm_0">
                    
                    <a class='btn btn-default' href='<?php echo $homeUrl;?>base/umsprivilege/index?secureKey=<?php echo $secureKey;?>&secureHash=<?php echo $secureHash;?>'>Cancel</a>
                    
                </div>
              </div>
         
               
                
              
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