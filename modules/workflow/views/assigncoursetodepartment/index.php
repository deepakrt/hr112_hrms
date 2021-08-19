<?php
$this->title = 'eAkadamik WebApp';
echo \app\components\Topbarwidget::widget(array('menuid'=>$menuid));
$homeUrl = Yii::$app->homeUrl;
$departments=Yii::$app->Utility->USP_ExtractDepartment();
$course=Yii::$app->Utility->USP_ExtractCourse(0);
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
            <h3 class="panel-title">  Assign Course </h3>
          </div>
            <span id='widgetversion_1_0_error_block' >
                <div class="alert alert-error error_main" id="error_main" style="display:none">  
                <ul id="widget_error_main_inner" >
                </ul>
                </div>         
            </span>
          <div class="panel-body">
            <div style="font-weight:bold ;padding:10px;"> Select Details </div>
			   <?php
                foreach (Yii::$app->session->getAllFlashes() as $key => $message)
                echo '<div class="col-sm-12" ><div class="text-center alert alert-' . $key . '">' . $message . '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div></div>';
                ?> 
          <form method="post" action="<?php echo $homeUrl?>/workflow/assigncoursetodepartment/insert" name="frm" id="frm">
              <input type="hidden"  class="form-control" readonly="" name="secureKey" id="secureKey" value="<?=  base64_encode($menuid)?>" />
    <input type="hidden"  class="form-control" readonly="" name="secureHash" id="secureHash" value="<?=Yii::$app->Utility->getHashInsert($menuid)?>" />
    <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
              <div class="col-lg-12 mar10">
                <div class="col-lg-2">Department<span class="required">*</span></div>
                <div class="col-lg-4">
                  <select id="Department" name="course[beDepartment]">
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
                <div class="col-lg-2">Course<span class="required">*</span></div>
                <div class="col-lg-4">
                <select id="Course" name="course[beCourse]">
                     <?php
                    if(!empty($course)){
                        echo '<option value="" >Select Course</option>';
                        foreach ($course as $courses) {
                            $id= $courses['Course_Id'];
                            $name= $courses['Course_Name'];
                            echo "<option value='$id'>$name</option>";
                        }
                    }else{
                        echo "<option value=''>No Records Found in DB. Contact Admin</option>";
                    }
                    ?>
                  </select>
              </div>
             </div>
             
              <div class="col-lg-12 mar20">
                  <div class="col-lg-1"></div>
                  <div class="col-lg-2"></div>
                  <div class="col-lg-1"></div>
                  <div class="col-lg-5">
                  <?php
                      $secureKey =  base64_encode($menuid);
                      $secureHash = Yii::$app->Utility->getHashView($menuid);
                      ?>
                    <input name='insert' class="btn btn-primary" type="submit" onclick="return validateassigncourse()" value="Assign Course" id="">
                    <a class='btn btn-default' href='<?php echo $homeUrl;?>base/umsprivilege/index?secureKey=<?php echo $secureKey;?>&secureHash=<?php echo $secureHash;?>'>Cancel</a>
                </div>
              </div>
           </form>
           
           <div class="col-lg-12 mar10"></div>
           <div class="col-lg-12 mar10"> 
          <table id="depcourseview" class="display adminlist" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Department Name</th>
                    <th>Course Name</th>
                </tr>
            </thead>
            <tbody>
             <?php
			    $depcourseview  = Yii::$app->Utility1->USP_ExtractdeptCourseAll();
			   //echo "<pre>"; print_r($depcourseview); die;
			   $secureKey =  base64_encode($menuid);
			   $secureHash = Yii::$app->Utility->getHashView($menuid);
				$i=1;
				foreach($depcourseview as $depcourseview=>$deptocourseview)
				{
				$Department_Name= $deptocourseview['Department_Name'];
				$Course_Name= $deptocourseview['Course_Name'];
				?>
                 <tr>
                    <td><?php echo $i;?></td>
                    <td><?php echo $Department_Name;?></td>
                    <td><?php echo $Course_Name;?></td>
                </tr>
                <?php
				$i++;
				}
				?>
            </tbody>
        </table>
			<script>
            $(document).ready(function() {
                $('#depcourseview').DataTable( {
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
    
    
</div>


<div id="border-bottom">
  <div>
    <div></div>
  </div>
</div>
