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
            <h3 class="panel-title"> Course Master </h3>
          </div>
                 <span id='widgetversion_1_0_error_block' >
<div class="alert alert-error error_main" id="error_main" style="display:none">  
<ul id="widget_error_main_inner" >
</ul>
</div>         
</span>
          <div class="panel-body">
            <div style="font-weight:bold ;padding:10px;"> Enter Course Details</div>
                 <?php
        foreach (Yii::$app->session->getAllFlashes() as $key => $message)
        echo '<div class="col-sm-12" ><div class="text-center alert alert-' . $key . '">' . $message . '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div></div>';
            
    ?>
            <form method="post" action="<?php echo $homeUrl?>workflow/umsmasters/insert" name="frm" id="frm">
                <input type="hidden"  class="form-control" readonly="" name="secureKey" value="<?=  base64_encode($menuid)?>" />
                <input type="hidden"  class="form-control" readonly="" name="secureHash" value="<?=Yii::$app->Utility->getHashInsert($menuid)?>" />
                <input id="_csrf" type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
              <div class="col-lg-12 mar10">
                <div style="color:red" id="errmsg"></div>
              </div>
              <div class="col-lg-12 mar10">
                <div class="col-lg-2">Name<span class="required">*</span></div>
                <div class="col-lg-4">
                  <input type="text" id="CourseName" value="" name="beCourse[courseName]">
                </div>
                <div class="col-lg-3">No of Semesters<span class="required">*</span></div>
                <div class="col-lg-2">
                  <input class="PUIntegerOnly" maxlength='1' type="text" id="noOfSemester" value="" name="beCourse[numberOfSemester]">
                </div>
              </div>
              <div class="col-lg-12 mar10">
                <div class="col-lg-2">Description<span class="required">*</span></div>
                <div class="col-lg-4">
                  <input type="text" id="Description" value="" name="beCourse[courseDescription]">
                </div>
              </div>
              <div class="col-lg-12 mar10"></div>
              <div class="col-lg-12 mar10">
                <div class="col-lg-1"></div>
                <div class="col-lg-3"></div>
                <div class="col-lg-1"></div>
                <div class="col-lg-3">
                     <?php
                      $secureKey =  base64_encode($menuid);
                      $secureHash = Yii::$app->Utility->getHashView($menuid);
                      ?>
                 <input name='insert' class="btn btn-primary" type="submit" onclick="return validate_CourseMaster()" value="Save" id="frm_0">
                 <a class='btn btn-default' href='<?php echo $homeUrl;?>base/umsprivilege/index?secureKey=<?php echo $secureKey;?>&secureHash=<?php echo $secureHash;?>'>Cancel</a>
                </div>
              </div>
           </form>
           <div class="col-lg-12 mar10"></div>
           <div class="col-lg-12 mar10"> 
          <table id="courseviewinfof" class="display adminlist" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>No of Semester</th>
                    <th>Description</th>
                    <th>Edit Record</th>
                </tr>
            </thead>
            <tbody>
             <?php
			    $courseview  = Yii::$app->Utility->USP_ExtractCourse(0);
			   //echo "<pre>"; print_r($courseview); die;
			   $secureKey =  base64_encode($menuid);
			   $secureHash = Yii::$app->Utility->getHashView($menuid);
				$i=1;
				foreach($courseview as $courseviews=>$courseviewss)
				{
				$Course_Name= $courseviewss['Course_Name'];
				$Number_Of_Semesters= $courseviewss['Number_Of_Semesters'];
				$Course_Description= $courseviewss['Course_Description'];
				?>
                 <tr>
                    <td><?php echo $i;?></td>
                    <td><?php echo $Course_Name;?></td>
                    <td><?php echo $Number_Of_Semesters;?></td>
                    <td><?php echo $Course_Description;?></td>
                    <td><input class="btn btn-primary" value="Update" type="submit"></td>
                </tr>
                <?php
				$i++;
				}
				?>
            </tbody>
        </table>
			<script>
            $(document).ready(function() {
                $('#courseviewinfof').DataTable( {
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

