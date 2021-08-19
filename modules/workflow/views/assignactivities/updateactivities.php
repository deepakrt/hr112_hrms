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
     <div style="margin-top:30px; " class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-heading respantit">
            <h3 class="panel-title"> Assign Faculty Roles for <?php echo $FullName;?></h3>
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
            <div class="main_Assign_Activity"> 
              <?php echo "<form action= '".$homeUrl."workflow/assignactivities/updateassignactivity' method='POST'>"; ?>  
                
                <input type="hidden"  class="form-control" readonly="" name="secureKey" value="<?=  base64_encode($menuid)?>" />
                <input type="hidden"  class="form-control" readonly="" name="secureHash" value="<?=Yii::$app->Utility->getHashUpdate($menuid)?>" />
                <input type="hidden"  class="form-control" readonly="" name="Assign[username]" value="<?=$username?>" />
                 <input type="hidden"  class="form-control" readonly="" name="Assign[Roleid]" value="<?=$Roleid?>" />
                <input type="hidden" id="_csrf" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
                
               <div class="col-lg-12 mar10">
                   <div class="col-lg-4">Name:</div>
                    <div class="col-lg-4">
                        <?php echo $FullName;?>
                        
                    </div>
                </div>
                <div class="col-lg-12 mar10">
                   <div class="col-lg-4">Login User Name::</div>
                    <div class="col-lg-4">
                        <?php echo $username;?>
                        
                    </div>
                </div>
                <div class="col-lg-12 mar10">
                   <div class="col-lg-4">Role:</div>
                    <div class="col-lg-4">
                        <?php echo $RollName;?>
                        
                    </div>
                </div>
                 
                <div class="dataTables_wrapper no-footer" id="subjectTable_wrapper">
        <table id="uploadview" class="display adminlist" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Select to assign</th>
                    <th>Activity Name</th>
                 
                </tr>
            </thead>
            <tbody>
             <?php
             //echo "<pre>";print_r($ExtractAssignedRoles); die;
             
				$i=1;
				foreach($ExtractAssignedRoles as $ExtractAssignedRolesK=>$ExtractAssignedRolesV)
				{
				 $submenu_id= $ExtractAssignedRolesV['submenu_id'];
				 $submenu_name= Yii::$app->Utility->getupperstring($ExtractAssignedRolesV['submenu_name']);
                                 $Role_Id= $ExtractAssignedRolesV['Role_Id'];
                                 if(empty($Role_Id))
                                 {
                                     $checkbox='';
                                 }
                                 else
                                 {
                                   $checkbox="checked='checked'";  
                                 }
                                 
				 
                                 
				?>
                <tr>
                    <td><?php echo $i;?></td>
                    <td>
                      <input <?php echo $checkbox?> type="checkbox" value="<?php echo $submenu_id;?>" name="Assign[Menu][]" />  
                      <input type="hidden" value="<?php echo $submenu_id;?>" name="Assign[All][]" />  
                    </td>
                    <td><?php echo $submenu_name;?></td>
                                      
                        
                </tr>
                <?php
				$i++;
				}
				?>
            </tbody>
        </table>
      </div>
                
                 <div class="col-lg-12 mar10">
                  <div class="col-lg-1"></div>
                  <div class="col-lg-3"></div>
                  <div class="col-lg-1"></div>
                  <div class="col-lg-7">
                
                  <?php
                      $secureKey =  base64_encode($menuid);
                      $secureHash = Yii::$app->Utility->getHashView($menuid);
                      ?>
                    <input name='insert' class="btn btn-primary" type="submit" value="Update Assign" id="">
                    <a class='btn btn-default' href='<?php echo $homeUrl;?>workflow/assignactivities/index?secureKey=<?php echo $secureKey;?>&secureHash=<?php echo $secureHash;?>'>Cancel</a>
                </div>
              </div>
<script>
$(document).ready(function() {
    $('#uploadview').DataTable( {
        //"scrollY": 500,
        "scrollX": true
    } );
} );

</script>
             
           </div>
            
          </div>
            
        </div>
      </div>
        
    </div>
    
    
