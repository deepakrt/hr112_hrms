<?php
$this->title = 'eAkadamik WebApp';
echo \app\components\Topbarwidget::widget(array('menuid'=>$menuid));
$homeUrl = Yii::$app->homeUrl; 
$USPExtractRole  = Yii::$app->Utility->USPExtractRole();
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
            <h3 class="panel-title"> Assign Activity  </h3>
          </div>
               <span id='widgetversion_1_0_error_block' >
                <div class="alert alert-error error_main" id="error_main" style="display:none">  
                <ul id="widget_error_main_inner" >
                </ul>
                </div>         
                </span>
          <div class="panel-body">
            <div style="font-weight:bold ;padding:10px;">  Enter/Select User Details </div>
                <?php
        foreach (Yii::$app->session->getAllFlashes() as $key => $message)
        echo '<div class="col-sm-12" ><div class="text-center alert alert-' . $key . '">' . $message . '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div></div>';
            
    ?> 
            <div class="main_Assign_Activity">            
                <input type="hidden"  class="form-control" readonly="" name="secureKey" value="<?=  base64_encode($menuid)?>" />
                <input type="hidden"  class="form-control" readonly="" name="secureHash" value="<?=Yii::$app->Utility->getHashInsert($menuid)?>" />
                <input type="hidden" id="_csrf" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
              <div class="col-lg-12 mar10">
                <div class="col-lg-2"></div>
                <div class="col-lg-5"></div>
              </div>
              
               <div class="col-lg-12 mar10">                
                   <div class="col-lg-2">Type of User</div>
                    <div class="col-lg-4">
                        <select name="AssignActivity[TypeofUser]" id="Assign_TypeofUser" >
                            <?php
                            if(!empty($USPExtractRole)){
                                echo '<option value="" >Select Role</option>';
                               foreach($USPExtractRole as $USPExtractRoleK=>$USPExtractRoleV)
                          {
                            $role_id = $USPExtractRoleV['role_id'];
                            $role_name = $USPExtractRoleV['role_name'];                            
                           echo "<option value='$role_id'>$role_name</option>";   
                          }
                            }else{
                                echo "<option value=''>No Roles Found in DB. Contact Admin</option>";
                            }
                            ?>
                        </select>
                    </div>
                    </div>
                    <div class="col-lg-12 mar10">
                   <div class="col-lg-2">Login Name</div>
                    <div class="col-lg-4">
                        <input id="Assign_LoginName" type="text" value="" name="AssignActivity[LoginName]">
                        
                    </div>
                </div>
                
                 <div class="col-lg-12 mar10" style="margin-top: 10px;">
                
                <div class="col-lg-8 col-sm-6 col-md-5">
                <div class="col-lg-4"></div>
                <div class="col-lg-4">
                    OR
                </div>
                </div>
              </div>
                
                
                  <div class="col-lg-12 mar10">
                   
                   <div class="col-lg-2">First Name</div>
                    <div class="col-lg-4">
                        <?php
                        $startBtach = date('Y');
                        ?>
                        <input id="Assign_FirstName" type="text" value="" name="AssignActivity[FirstName]">
                    </div>
                   <div class="col-lg-2">Last Name</div>
                    <div class="col-lg-4">
                    <input id="Assign_LastName" type="text" value="" name="AssignActivity[LastName]">
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
                    <input name='insert' class="btn btn-primary" type="submit" onclick="return validateassignactivity()" value="Search" id="">
                    <a class='btn btn-default' href='<?php echo $homeUrl;?>base/umsprivilege/index?secureKey=<?php echo $secureKey;?>&secureHash=<?php echo $secureHash;?>'>Cancel</a>
                </div>
              </div>
             
           </div>
            <div style="padding:29px" id="html_AssignActivityinfo" class="row">
            
            </div>
            
          </div>
        </div>
      </div>
        <div class="row" style="margin-bottom: 70px;"> </div>
    </div>
    
    
