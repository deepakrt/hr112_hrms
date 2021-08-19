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
            <h3 class="panel-title"> Grade Master </h3>
          </div>
           <span id='widgetversion_1_0_error_block' >
                <div class="alert alert-error error_main" id="error_main" style="display:none">  
                <ul id="widget_error_main_inner" >
                </ul>
                </div>         
            </span>
   <div class="panel-body">
    <div style="font-weight:bold ;padding:10px;">Enter Grade Details</div>      	
		
   <?php
        foreach (Yii::$app->session->getAllFlashes() as $key => $message)
        echo '<div class="col-sm-12" ><div class="text-center alert alert-' . $key . '">' . $message . '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div></div>';
            
    ?> 
        <form method="post"  action="<?php echo $homeUrl?>/workflow/grademasteraction/insert" name="frm" id="frm">
            <input type="hidden"  class="form-control" readonly="" name="secureKey" value="<?=  base64_encode($menuid)?>" />
            <input type="hidden"  class="form-control" readonly="" name="secureHash" value="<?=Yii::$app->Utility->getHashInsert($menuid)?>" />
            <input type="hidden" id="_csrf1" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>"/>
          <div class="row mar10">
            <div class="col-lg-12 mar10">
              <div style="font-weight:300;" class="col-lg-2">Name<span class="required">*</span></div>
              <div class="col-lg-4">
               <input type="text" id="gradeName" value="" maxlength="50" name="beGrade[gradeName]">
               </div>
               <div style="font-weight:300;" class="col-lg-2">Points<span class="required">*</span></div>
              <div class="col-lg-4"> 
                <input type="text" id="gradePoint" value="" maxlength="2" name="beGrade[gradePoint]"></div>
              </div>
            
            <div class="col-lg-12 mar10">
                <div style="font-weight:300;" class="col-lg-2">Description<span class="required">*</span></div>
                <div class="col-lg-10"> 
                <input type="text" id="gradeDescription" name="beGrade[gradeDescription]" value="">
                </div>
            </div>
            
            <div class="col-lg-12 mar10">
                <div style="font-weight:300;" class="col-lg-2">Session Start Year<span class="required">*</span>
                </div>
                <div class="col-lg-4"> 
                <input type="text" id="startYear" value="" name="beGrade[sessionStartYear]"></div>
                <div style="font-weight:300;" class="col-lg-2">Session End Year<span class="required">*</span></div>
                <div class="col-lg-4"> 
                <input type="text" id="endYear" value="" name="beGrade[sessionEndYear]"></div>
            </div>
            
            <div class="col-lg-12 mar10">
                <div style="font-weight:300;" class="col-lg-2">Percentage Start Marks<span class="required">*</span></div>
                <div class="col-lg-4">
                <input type="text" id="startMarks" value="" name="beGrade[percentageStartmarks]"></div>
                <div style="font-weight:300;" class="col-lg-2">Percentage End Marks<span class="required">*</span>
                </div>
                <div class="col-lg-4"> 
                    
                <input type="text" id="endMarks" value="" name="beGrade[percentageEndmarks]"></div>
            </div>
            <div class="col-md-12">

        <div class="col-md-2"></div>
        <div class="col-md-8">
            <input type="submit" onclick="return validate_GradeMaster()" value="Save" id="frm_0" class="btn btn-primary">
            <?php echo "<a class='btn btn-default' href= $homeUrl>Cancel</a>"; ?>
        </div>
       
</div></form>
<div class="col-lg-12 mar10"></div><div class="col-lg-12 mar10"> 
          <table id="gradeviewinfof" class="display adminlist" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Grade Name</th>
                    <th>Grade Point</th>
                    <th>Grade Description</th>
                    <th>Session Start Year</th>
                    <th>Session End Year</th>
                    <th>Percentage Start Marks</th>
                    <th>Percentage End Marks</th>
                    <th>Edit Record</th>
                </tr>
            </thead>
            <tbody>
             <?php
			    $gradeview  = Yii::$app->Utility2->USP_ViewGradeScheme();
			   //echo "<pre>"; print_r($courseview); die;
			   $secureKey =  base64_encode($menuid);
			   $secureHash = Yii::$app->Utility->getHashView($menuid);
				$i=1;
				foreach($gradeview as $gradeviews=>$gradeviewss)
				{
				$grade_name= $gradeviewss['grade_name'];
				$grade_point= $gradeviewss['grade_point'];
				$grade_description= $gradeviewss['grade_description'];
				
				$percentageEndmarks= $gradeviewss['percentageEndmarks'];
				$percentageStartmarks= $gradeviewss['percentageStartmarks'];
				
				$session_end_year= $gradeviewss['session_end_year'];
				$session_start_year= $gradeviewss['session_start_year'];
				?>
                 <tr style="text-align:center">
                    <td><?php echo $i;?></td>
                    <td><?php echo $grade_name;?></td>
                    <td><?php echo $grade_point;?></td>
                    <td><?php echo $grade_description;?></td>
                    <td><?php echo $session_start_year;?></td>
                    <td><?php echo $session_end_year;?></td>
                    <td><?php echo $percentageStartmarks;?></td>
                    <td><?php echo $percentageEndmarks;?></td>
                    <td><input class="btn btn-primary" value="Edit" type="submit"></td>
                </tr>
                <?php
				$i++;
				}
				?>
            </tbody>
        </table>
			<script>
            $(document).ready(function() {
                $('#gradeviewinfof').DataTable( {
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