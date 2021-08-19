 
<?php
echo \app\components\Topbarwidget::widget(array('menuid'=>$menuid)); 
echo \app\components\Sidebarwidget::widget(array('menuid'=>$menuid));
$homeUrl = Yii::$app->homeUrl;

//$action_url = Yii::$app->getrequest()->getPathInfo();



?>

<div  style="margin-top:7%" class="col-lg-9">
              <div class="panel panel-default">
   <div class="panel-heading respantit">
      <h3 class="panel-title">
       Assign Branch to College 
      </h3>
   </div>
<span id='widgetversion_1_0_error_block' >
<div class="alert alert-error error_main" id="error_main" style="display:none">  
<ul id="widget_error_main_inner" >
</ul>
</div>         
</span>
   <div class="panel-body">
    <div style="font-weight:bold ;padding:10px;">  Select Details</div>      	
		
   <?php
        foreach (Yii::$app->session->getAllFlashes() as $key => $message)
        echo '<div class="col-sm-12" ><div class="text-center alert alert-' . $key . '">' . $message . '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div></div>';
            
    ?> 
          <form method="post" action="<?php echo $homeUrl?>/workflow/umsmasters/insert" name="frm" id="frm">
              <input type="hidden"  class="form-control" readonly="" name="secureKey" value="<?=  base64_encode($menuid)?>" />
    <input type="hidden"  class="form-control" readonly="" name="secureHash" value="<?=Yii::$app->Utility->getHashInsert($menuid)?>" />
    <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />

    <?php 
    $college=array('UIET'=>'UIET');
    $course=array('B.E.'=>'B.E.','M.E.'=>'M.E.','M.Tech.'=>'M.Tech.');
    ?>
    <div style="margin-top: 10px;" class="col-lg-12">
      	<div style="color:red" id="errmsg"></div>
						
						

</div>
  

    <div style="margin-top:10px;" class="col-lg-12"></div>
     
 <div style="margin-top:10px;" class="col-lg-12">
         
      <div style="font-weight:300;" class="col-lg-2">College Name</div>
      <div class="col-lg-4"> 
          <select style="height:30px;width:100%;background:#fff;color:#999999;font-family:Roboto;font-size: 16px;" id="college" name="college">     
                                                <option value="select" >Select College</option>
                                                <?php
                                                foreach ($college as $key1=>$val1) {
                                                    ?>
                                                
                                                    <option value="<?= $key1 ?>"><?= $val1?></option>
                                                <?php } ?>
                                                                </select>
          <input type="hidden" id="college_name" value="">
      </div>
      
    
     
    
        
      <div style="font-weight:300;" class="col-lg-2">Course</div>
      <div class="col-lg-4">
             <select style="height:30px;width:100%;background:#fff;color:#999999;font-family:Roboto;font-size: 16px;" id="course" name="course">     
                                                <option value="select" >Select Course</option>
                                                <?php
                                                foreach ($course as $key3=>$val3) {
                                                    ?>
                                                
                                                    <option value="<?= $key3 ?>"><?= $val3?></option>
                                                <?php } ?>
                                                                </select>    
          <input type="hidden" id="course_name" value="">   
      </div>
      
      </div>
        <div style="margin-top:10px;" class="col-lg-12"></div>
     
      </div>
         <div class="col-lg-1"></div>
      <div class="col-lg-3"></div>
      <div class="col-lg-1"></div>
      <div class="col-lg-3">
      <!--<input name='insert' class="btn btn-primary" type="submit" onclick="return validate_StudentMaster()" value="Save" id="frm_0">-->
       <?php // echo "<a class='btn btn-default' href= $homeUrl>Cancel</a>"; ?>
       </div>
       
   </div><table class="wwFormTable">
</table></form>
</div>
    <script>
        $(document).ready(function ()
{
     var college = $.trim($("#college").val());
     var course = $.trim($("#course").val());

   $('#college').change(function () {
       
        var college = $(this).val();
        $('#college_name').val(college);
      
    });
 $('#course').change(function () {
       
        var course = $(this).val();
        $('#course_name').val(college);
      
      
    });

    
    }); 
   

        </script>