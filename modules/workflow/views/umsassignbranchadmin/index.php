<?php
$this->title = 'eAkadamik WebApp';
echo \app\components\Topbarwidget::widget(array('menuid'=>$menuid));
$homeUrl = Yii::$app->homeUrl;
//$batch_session=Yii::$app->Utility->USP_ExtractSession(0);
$departments1=Yii::$app->Utility1->USP_ExtractDepartmentAll();
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
View/Assign Branch Admin Role
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
    <form method="post" action="<?php echo $homeUrl?>workflow/umsassignbranchadmin/process" name="frm" id="frm">
               <input type="hidden"  class="form-control" readonly="" id="secureKey" name="secureKey" value="<?=  base64_encode($menuid)?>" />
                <input type="hidden"  class="form-control" readonly=""  id="secureHash" name="secureHash" value="<?=Yii::$app->Utility->getHashView($menuid)?>" />
                <input id='_csrf' type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
                                
                <div class="col-lg-12 mar10">
            <div class="text-left alert alert-warning">
            <strong>After assigning the new Branch Admin role/rights, All the role/rights from the previous Branch Admin Faculty will automatically revoked.</strong>
            </div>
                  
            </div>
                
                <div class="col-lg-12 mar10">
                 <div class="col-lg-2">Department </div>
                 <div class="col-lg-4">
                  <select name="AssignBAdmin[Department]" id="Assign_Faculty_Department" class="Assign_Faculty_Department">
                      <?php
                        if(!empty($departments1)){
                            echo '<option value="" >Select Department</option>';
                            foreach ($departments1 as $departmentsV1) {
                                $id= $departmentsV1['Department_Id'];
                                $name= $departmentsV1['Department_Name'];
                                echo "<option value='$id'>$name</option>";
                            }
                        }else{
                            echo "<option value=''>No Records Found in DB. Contact Admin</option>";
                        }
                        ?>
                    </select>
                 </div>
                 
                  <div class="col-lg-2">Faculty</div>
                 <div class="col-lg-4">
                  <select name="AssignBAdmin[Faculty]" id="assign_subject_to_faculty" class="assign_subject_to_faculty">
                        <option value="" >Select Faculty</option>
                    </select>
                 </div>
                </div>
                
                <div class="col-lg-12 mar10">
                    
                 <div class="col-lg-2">Other Department Faculty</div>
                    <div class="col-lg-">
                  <input type="checkbox" value="" name="AssignBAdmin[Other_Department_Check]" class="other_dept_Badmin_checkbox" style="width:5%" title="Click to view Other Department List">
                 </div>
                </div>
                
                <div style="display:none" class="col-lg-12 mar10 Other_Department_BAdmin_Div">
                 <hr style="margin:0px">
                 <div style="font-weight:bold ;padding:10px;"> Select Other Department Faculty</div>
                 <div class="col-lg-12 mar10">
                 <div class="col-lg-2">Department </div>
                 <div class="col-lg-4">
                  <select name="AssignBAdmin[Other_Department]" id="Assign_Badmin_Other_Department" class="Assign_Badmin_Other_Department">
                      <?php
                        if(!empty($departments1)){
                            echo '<option value="" >Select Department</option>';
                            foreach ($departments1 as $departmentsV1) {
                                $id= $departmentsV1['Department_Id'];
                                $name= $departmentsV1['Department_Name'];
                                echo "<option value='$id'>$name</option>";
                            }
                        }else{
                            echo "<option value=''>No Records Found in DB. Contact Admin</option>";
                        }
                        ?>
                    </select>
                 </div>
                 
                  <div class="col-lg-2">Faculty</div>
                 <div class="col-lg-4">
                  <select name="AssignBAdmin[Other_Department_Faculty]" id="assign_Other_Department_Faculty" class="assign_Other_Department_Faculty">
                        <option value="" >Select Faculty</option>
                    </select>
                 </div>
                </div>
                </div>
                <div class="col-lg-12 mar10">
                    
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
                    <input name='AssignBAdmin[View]' class="btn btn-primary" type="button" onclick="return validateAssignBAdmin(this)" value="View" id="frm_0">
                    <input name='AssignBAdmin[Assign]' class="btn btn-warning" type="submit" onclick="return validateAssignBAdmin(this)" value="Assign" id="frm_0">
                    
                    <a class='btn btn-default' href='<?php echo $homeUrl;?>base/umsprivilege/index?secureKey=<?php echo $secureKey;?>&secureHash=<?php echo $secureHash;?>'>Cancel</a>
                    
                </div>
              </div>
         
               
                
              
           </form>          
            
            <div id="ViewCurrentBadmin_div_info" class="mar17"> 
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