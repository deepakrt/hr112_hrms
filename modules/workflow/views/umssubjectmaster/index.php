<?php
$this->title = 'eAkadamik WebApp';
echo \app\components\Topbarwidget::widget(array('menuid'=>$menuid));
$homeUrl = Yii::$app->homeUrl;
$this->title = "Subject Master ";
//$batchSessions=Yii::$app->Utility2->USP_ExtractSession();
$departments=Yii::$app->Utility->USP_ExtractDepartment();
//echo "<pre>";print_r($departments);
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
    <div class="col-md-12 mar30 marbot70">
        <div class="panel panel-default">
          <div class="panel-heading respantit">
            <h3 class="panel-title"> Create Subject With Scheme </h3>
          </div>
            <span id='widgetversion_1_0_error_block' >
              <div class="alert alert-error error_main" id="error_main" style="display:none">  
                <ul id="widget_error_main_inner" >
                </ul>
              </div>         
            </span>
  <div class="panel-body">
    <div style="font-weight:bold ;padding:10px;">Enter Subject Details</div>      	
		
   <?php
        foreach (Yii::$app->session->getAllFlashes() as $key => $message)
        echo '<div class="col-sm-12" ><div class="text-center alert alert-' . $key . '">' . $message . '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div></div>';
            
    ?>       
    <form id="frm" name="frm" action="<?=$homeUrl?>workflow/umssubjectmaster/insert" method="POST">
        <input id='_csrf' type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
        <input type="hidden"  class="form-control" readonly="" name="secureKey" value="<?=  base64_encode($menuid)?>" />
                <input type="hidden"  class="form-control" readonly="" name="secureHash" value="<?=Yii::$app->Utility->getHashInsert($menuid)?>" />
              <div class="col-lg-12 mar10">
                <div id="errmsg" style="color:red"></div>
              </div>
                
              <div class="col-lg-12 mar10">
              <div class="col-lg-2">Department<span class="required">*</span></div>
              <div class="col-lg-4">
                <select name="beSubject[department]" id="Assign_Department" class="CY new_Dept_Select">
                    
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
                  <select name="beSubject[course]" id="Assign_Course" class="new_Course_Select">
                            <option value="">Select Course</option>
                  </select>
                </div>
             </div>
              
              <div class="col-lg-12 mar10">
                  <div class="col-lg-2">Batch<span class="required">*</span>  </div>
                    <div class="col-lg-4">
                        <select name="beSubject[session_yr]" id="actionSelect" class="new_Batch_Select" >
                            <option value="">Select Batch</option>
                        </select>
                    </div>
                <div class="col-lg-2">Semester<span class="required">*</span></div>
                <div class="col-lg-4">
                  <select name="beSubject[semesterId]" id="semesterId" class="new_Semester_Select">
                        <option value="" >Select Semester</option>
                    </select>
                </div>
                
              </div>
              <div class="col-lg-12 mar10">
                  <div class="col-lg-2">Subject Code<span class="required">*</span></div>
                <div class="col-lg-4">
                    <input type="text" name="beSubject[subjectCode]" value="" id="subjectCode">
                </div>
                <div class="col-lg-2">Subject Name<span class="required">*</span></div>
                <div class="col-lg-4">
                    <input type="text" name="beSubject[subjectName]" value="" id="subjectName" class="frm-txtbox">
                </div>
                
              </div>
                 <div class="col-lg-12 mar10">
                   <div class="col-lg-2">Description<span class="required">*</span></div>
                <div class="col-lg-4">
                  <textarea style="width:100%; height:40px;" name="beSubject[subjectDescription]" cols="20" rows="3" id="description"></textarea>
                </div> 
                </div>
                
              <div class="col-lg-12 mar10">
                <div class="col-lg-2">Subject Type<span class="required">*</span></div>
                <div class="col-lg-4">
                  <input type="checkbox" name="beSubject[subjectTypeTheory]" id="subjectTypeTheory" value="T">
                  <span style="vertical-align:top">&nbsp;Theory &nbsp; </span>
                  <input type="checkbox" name="beSubject[subjectTypePractical]" id="subjectTypePractical" value="P">
                  <span style="vertical-align:top">&nbsp;Practical </span>
                </div>
                
                <div class="col-lg-2">Is Elective</div>
                <div class="col-lg-4">
                  <input type="checkbox" name="beSubject[Elective]" id="isElective" value="">
                  <span style="vertical-align:top">&nbsp;Is Elective</span>
                </div>
              </div>
                
               <div class="col-lg-12 mar10" style="font-weight:bold ;padding:10px;">Enter Scheme Details</div>
              <div>
                    
                </div>
              <div class="col-lg-12 mar10">
                <div class="col-lg-3">Lectures /week<span class="required">*</span></div>
                <div class="col-lg-2">
                    <input maxlength='2' type="text" name="beSubject[subjectLecture]" value="0" id="subjectLecture" class="PUIntegerOnly frm-txtbox">
                </div>
                <div class="col-lg-1"></div>
                <div class="col-lg-3">Tutorials /week<span class="required">*</span></div>
                <div class="col-lg-2">
                  <input maxlength='2' type="text" name="beSubject[subjectTutorial]" value="0" id="subjectTutorial" class="PUIntegerOnly frm-txtbox">
                </div>
              </div>
              <div class="col-lg-12 mar10">
                <div class="col-lg-3">Internal Marks<span class="required">*</span></div>
                <div class="col-lg-2">
                    <input maxlength='3' type="text" name="beSubject[subjectInternalMarks]" value="0" id="subjectInternalMarks" class="PUIntegerOnly frm-txtbox">
                </div>
                <div class="col-lg-1"></div>
                <div class="col-lg-3">External Marks<span class="required">*</span></div>
                <div class="col-lg-2">
                  <input maxlength='3' type="text" name="beSubject[subjectExternalMarks]" value="0" id="subjectExternalMarks" class="PUIntegerOnly frm-txtbox">
                </div>
              </div>
              <div class="col-lg-12 mar10">
                <div class="col-lg-3">Practicals /week<span class="required">*</span></div>
                <div class="col-lg-2">
                    <input maxlength='2' type="text" name="beSubject[subjectPractical]" value="0" id="subjectPractical" class="PUIntegerOnly frm-txtbox">
                </div>
                <div class="col-lg-1"></div>
                <div class="col-lg-3">Practical Marks<span class="required">*</span></div>
                <div class="col-lg-2">
                  <input maxlength='3' type="text" name="beSubject[practicalMarks]" value="0" id="practicalMarks" class="PUIntegerOnly frm-txtbox">
                </div>
              </div>
              <div class="col-lg-12 mar10">
                <div class="col-lg-3">Theory Credits<span class="required">*</span></div>
                <div class="col-lg-2">
                    <input maxlength='2' type="text" name="beSubject[subjectCredit]" value="0" id="subjectCredit" class="PUIntegerOnly frm-txtbox">
                </div>
                <div class="col-lg-1"></div>
                <div class="col-lg-3">Practical Credits<span class="required">*</span></div>
                <div class="col-lg-2">
                  <input maxlength='2' type="text" name="beSubject[practicalCredit]" value="0" id="practicalCredit" class="PUIntegerOnly frm-txtbox">
                </div>
              </div>
              
              
              <div class="col-lg-12 mar10"></div>
              <div class="col-lg-12 mar10">
                <div class="col-lg-2"></div>
                <div class="col-lg-4">
                    <?php
                      $secureKey =  base64_encode($menuid);
                      $secureHash = Yii::$app->Utility->getHashView($menuid);
                      ?>
                    <input name='insert' class="btn btn-primary" type="submit" onclick="return validateSubjectMaster()" value="Save" id="">
                    <input type='reset' class="btn btn-default" />
      <a class='btn btn-default' href='<?php echo $homeUrl;?>base/umsprivilege/index?secureKey=<?php echo $secureKey;?>&secureHash=<?php echo $secureHash;?>'>Cancel</a>
                </div>
              </div>
            </form>
          <div class="col-lg-12 mar10"></div><div class="col-lg-12 mar10"> 
          <table id="subjectinfof" class="display adminlist" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Subject</th>
                    <th>Subject Code</th>
                    <th>Subject Type</th>
                    <th>Subject Batch</th>
                    <th>Department Name</th>
                    <th>Course Name</th>
                    <th>Semester</th>
                    <th>Description</th>
                    <th>Elective</th>
                    <th>Edit Record</th>
                </tr>
            </thead>
            <tbody>
             <?php
			    $subjectview  = Yii::$app->Utility1->USP_ExtractSubjectAll();
			   //echo "<pre>"; print_r($subjectview); die;
			   $secureKey =  base64_encode($menuid);
			   $secureHash = Yii::$app->Utility->getHashView($menuid);
				$i=1;
				foreach($subjectview as $subjectviews=>$subjectviewss)
				{
				$Subject_Name= $subjectviewss['Subject_Name'];
				$Subject_Code= $subjectviewss['Subject_Code'];
				$Subject_Type= $subjectviewss['Subject_Type'];
				$Semester_Id= $subjectviewss['Semester_Id'];
				$Subject_Description = $subjectviewss['Subject_Description'];
				$session_year = $subjectviewss['session_year'];
				$Department_Name = $subjectviewss['Department_Name'];
				$Course_Name = $subjectviewss['Course_Name'];
				$elective = $subjectviewss['elective'];
				?>
                 <tr style="text-align:center">
                    <td><?php echo $i;?></td>
                    <td><?php echo $Subject_Name;?></td>
                    <td><?php echo $Subject_Code;?></td>
                    <td><?php echo $Subject_Type;?></td>
                    <td><?php echo $session_year;?></td>
                    <td><?php echo $Department_Name;?></td>
                    <td><?php echo $Course_Name;?></td>
                    <td><?php echo $Semester_Id;?></td>
                    <td><?php echo $Subject_Description;?></td>
                    <td><?php echo $elective;?></td>
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
                $('#subjectinfof').DataTable( {
                    "scrollY": 500,
                    "scrollX": true
                } );
            } );
            
            </script>
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