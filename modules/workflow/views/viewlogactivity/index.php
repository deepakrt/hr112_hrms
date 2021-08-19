<?php
$this->title = 'eAkadamik WebApp';
echo \app\components\Topbarwidget::widget(array('menuid'=>$menuid));
$homeUrl = Yii::$app->homeUrl;
$departments=Yii::$app->Utility->USP_ExtractDepartment();
$secureKey = base64_encode($menuid);
$secureHash = Yii::$app->Utility->getHashView($menuid); 

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
            <h3 class="panel-title"> View Log Activity </h3>
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
                <div class="col-lg-12 mar10">
                    <div class="col-lg-2">Select Role</div>
                    <div class="col-lg-4">
                        <select name="logs[role_id]" id="roleid">
                            <option value="">All</option>
                            <option value="2">Branch Administrator</option>
                            <option value="3">Faculty</option>
                            <option value="4">Branch Assistant</option>
                            <option value="5">Result Unit</option>
                            <option value="6">Student</option>
                        </select>
                    </div>
                    
                    <div class="col-lg-2">Department</div>
                    <div class="col-lg-4">
                        <select name="logs[department_id]" id="department_id">
                            <?php
                            if(!empty($departments)){
                                echo '<option value="" >All</option>';
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
                </div> 
              <div class="col-lg-12 mar10">
                    <div class="col-lg-2">Log Date</div>
                    <div class="col-lg-4">
                        <input type="text" id="log_date" name="logs[log_date]" placeholder="Log Date" readonly="" style="cursor: pointer;" />
                    </div>
                    <div class="col-lg-2">Username</div>
                    <div class="col-lg-4">
                        <input type="text" id="username" name="logs[username]" placeholder="Username" />
                    </div>
              </div>
              <div class="col-lg-12 mar10 text-center">
                  <button type="button" onclick="return validateLogs()" class="btn btn-default">View Log</button>
                  <a href="<?=Yii::$app->homeUrl?>workflow/viewlogactivity/index?secureKey=<?=$secureKey?>&secureHash=<?=$secureHash?>" class="btn btn-default">Cancel</a>
              </div>
          </div>
        </div>
      </div>
    </div>
</div>
