<?php
    $this->title= 'Create Training Group';

use yii\widgets\ActiveForm;
    /*
        $con = mysqli_connect("localhost","root","","erss112");

        // Check connection
        if (mysqli_connect_errno()) {
          echo "Failed to connect to MySQL: " . mysqli_connect_error();
          exit();
        }
        else
        {
         echo "connected to MySQL: ";
         
        // CALL `gt_emp`(@p0);
         $result = mysqli_query($con, "CALL gt_emp('100057',)");

        $row = mysqli_fetch_array($result);
        echo "<pre>"; print_r($row);
          exit();   
        }
    */
?>
<style>
    .action_div
    {
        width: 100%;
    }

    .select_action_one
    {
        width: 50%;
    }

    .dataTables_empty{
        color: red;
    }   
</style>
<div class="panel-body">
    <div style="font-weight:bold ;padding:2px;"> Select Details</div>
    <hr>

    <div class="row col-sm-12">
        <div class="col-sm-6">
             <label for="employee_id">Employee Id:</label>
             <input type="text" name="Tpm[employee_id]" class="form-control" id="employee_id">
        </div>
        <?php
            $current_employee_code = Yii::$app->user->identity->employee_code;
            $current_employee_role_name = Yii::$app->user->identity->role_name;
            
            $url = Yii::$app->homeUrl."employee/trainings/trainingrequestaction?securekey=$menuid";
            $url1 = Yii::$app->homeUrl."employee/trainings/trainingrequestaction?securekey=$menuid";
       ?>
        <div class="col-sm-6">
            <label for="department_id">Department:</label>
            <select name="Tpm[deptInfo]" id="department_id" class="CY new_Dept_Select form-control" onchange="actionOnChangeDepartment(this.value)">
                <?php
                if(!empty($departments)){
                    echo '<option value="" >Select Department</option>';
                    foreach ($departments as $department) {
                        $id = $department['dept_id'];
                        $name= $department['dept_name'];
                        echo "<option value='$id'>$name</option>";
                    }
                }else{
                    echo "<option value=''>No Records Found in DB. Contact Admin</option>";
                }
                ?>
            </select>
            <span id="depVald" style="display: none;">Please Select Department.</span>
        </div>
        <div class="col-sm-6">
            <label for="assign_Course">Course:</label>
            <select name="Tpm[beCourse]" id="assign_Course" class="new_Course_Select form-control" >
                <option value="">Select Course</option>

                <?php
                    if(!empty($course_master)){
                        foreach ($course_master as $coursemaster) {
                            $id = $coursemaster['course_id'];
                            $name= $coursemaster['course_name'];
                            echo "<option value='$id'>$name</option>";
                        }
                    }else{
                        echo "<option value=''>No Records Found in DB. Contact Admin</option>";
                    }                
                ?>
            </select>
        </div>  

                      <div class="col-sm-6">
            <!-- <label for="district_name">Start/End Date Time:</label> -->
            <label for="program_code">Technology Name:</label>
            <select name="Tpm[technology_id]" id="tech_id" class="form-control form-control-sm" onchange="actionOnChangetech(this.value)">
            <option value="">Select Technology</option>
                            <option value="1">ERSS 112 Traning-I</option>
                             <option value="2">ERSS 112 Traning-II</option>
                     </select>
                     </select>
            </select>
        </div>

        <div class="col-sm-6">
            <!-- <label for="district_name">Start/End Date Time:</label> -->
            <label for="program_code">Training Program:</label>
            <select name="Tpm[beprogram_code]" id="start_end_date_time" class="new_Course_Select form-control" onchange="datetimeonchange(this)">
                <option>Select</option>
            </select>
        </div>

        <div class="col-sm-6" id="datetimed" style="display:none;">
            <label for="start_date_endate_disp">Start/End Date Time:</label>
            <input type="text" id="start_date_endate_disp" value="" class="form-control" readonly="readonly">            
        </div>    

        <div class="col-sm-6">
            <label for="trg_venue">Training Location:</label>
            <select class="new_Semester_Select PopulateStudent form-control" name="Tpm[trg_venue]"  id="trg_venue" onchange="actionOnChangeVanue(this.value)">
                <option>Select Location</option>
                <?php
                if(!empty($trgvenues)){
                    foreach ($trgvenues as $trg_venue) {
                        $id = $trg_venue['id'];
                        $name= $trg_venue['Venue'];
                        echo "<option value='$id'>$name</option>";
                    }
                }else{
                    echo "<option value=''>No Records Found in DB. Contact Admin</option>";
                }

                // course_master
                ?>
            </select>
        </div>              
        <div class="col-sm-6">
            <label for="district_name">District:</label>
            <select name="Tpm[beDistrictid]" id="district_name" class="new_Course_Select form-control" onchange="actionOnChangeDist(this.value)">
                <option value="">Select district</option>                
            </select>
        </div> 
         <div class="col-sm-6">
            <label for="status">Status:</label>
            <select name="Tpm[status]" id="status" class="new_Course_Select form-control" >
                <option value="">Select Status</option>   
                <option value="Completed">Completed</option>              
            </select>
        </div> 
        <div class="col-sm-6">
            <input style="margin-top: 15px;"  name="save" class="btn btn-primary" type="button"  value="Save"  onclick="return Insertemployee()"  />
        </div>       
    </div> 

    <div class="row col-sm-12" id="groupDataCreateDiv" style="display:none;">
        <!-- <div class="col-sm-6">
            <label for="group_name">Group Name:</label>
            <input type="text" name="group_name" class="form-control" id="group_name" value="" />
        </div> -->  
        <div class="col-sm-12" style="padding:20px;">
            <label for="assign_group_data"></label>
            <input style="display: none;" name="insert" class="btn btn-primary Assign_group" type="button" onclick="return CreateTrainingGroup()" value="Create Batch" id="assign_group_data" />
        </div>
    </div>
    <div class="row col-sm-12">
        <div style="display:none;width:100%;padding:29px" id="studentinfo" class="row marbot70">
        </div>
    </div>
    <br>    
</div>

<script type="text/javascript">
    $('body').on('click','.assign_student_to_group',function()
    {   
        var chkDisplay=false;    
        $("#subjectTable_wrapper table input[type=checkbox]").each(function ()
        {
            if($(this).prop("checked"))
            {
                chkDisplay=true; 
                return false;
            }   
        });

        if(chkDisplay)
        {
            $("#groupDataCreateDiv").show();
            $("#assign_group_data").show();
        }
        else
        {
          $("#groupDataCreateDiv").hide();
          $("#assign_group_data").hide();  
        }
    });

    $('body').on('click','.assign_allstudent_to_group',function()
    {
        if($(".assign_allstudent_to_group").is(':checked'))
        {    
            $("#groupDataCreateDiv").show();
            $("#subjectTable_wrapper table input[type=checkbox]").each(function ()
            {
                $("#assign_group_data").show();
                $(this).prop("checked", true);
            });
        }
        else
        {
            $("#groupDataCreateDiv").hide();

            $("#assign_group_data").hide();
            $("#subjectTable_wrapper table input[type=checkbox]").each(function () {
                $(this).prop("checked", false);
            });
        }

    });

        function actionHideContent()
        {
            startLoader();
            $('#studentinfo').hide();
            $('#studentinfo').html('');               
            stopLoader();
        }

        function actionOnChangeDepartment(department_id)
        {
            $('#start_date_endate_disp').val('');
            $('#assign_Course').prop('selectedIndex',0);
            $('#district_name').prop('selectedIndex',0);  
            $('#start_end_date_time').html('<option>Select</option>');  
            $('#district_name').html('<option>Select district</option>');
         
            actionHideContent();
        }        

        function actionOnChangetech(crsID)
        {
            $('#start_date_endate_disp').val('');
            $('#district_name').prop('selectedIndex',0);     
            $('#start_end_date_time').html('<option>Select</option>');   
            $('#district_name').html('<option>Select district</option>');  
            actionHideContent();
            // start_end_date_time

            var department_id = $('#department_id').val();   
             var course_id = $('#assign_Course').val();   
            
            actionHideContent();

            if(department_id != '' && crsID != '')
            {
                startLoader();
                 $.ajax({
                    url: "<?php echo Yii::$app->homeUrl."employee/trainings/gettraningprogramfortraining?securekey=$menuid";?>",
                    type: 'POST',
                    data: { department_id:department_id,course_id:course_id,crsID:crsID},
                    dataType: 'JSON',
                    success: function (data) 
                    {
                        // alert(data);
                        // $('#datashow').html(data);
                        
                        $('#start_end_date_time').html(data.traning_tpm_data);
                        
                        stopLoader();
                        
                    }
                });
            }
            else
            {
                $('#depVald').fadeOut(100);
            }
        }

        function actionOnChangeDist(district_name)
        {
            var department_id = $('#department_id').val();     
            var assign_Course = $('#assign_Course').val();     
            var start_end_date_time = $('#start_end_date_time').val();  
             var tech_id = $('#tech_id').val(); 
           // alert(start_end_date_time)  ; 

            actionHideContent();

            if(department_id != '' && district_name != '' && assign_Course != '')
            {
                startLoader();
                 $.ajax({
                    url: "<?php echo Yii::$app->homeUrl."employee/trainings/getemployeefortraining?securekey=$menuid";?>",
                    type: 'POST',
                    data: { department_id:department_id,district_name:district_name,assign_Course:assign_Course,start_end_date_time:start_end_date_time,tech_id:tech_id },
                    dataType: 'JSON',
                    success: function (data) 
                    {
                        // alert(data);
                        // $('#datashow').html(data);
                        
                        $('#studentinfo').html(data.traning_data);
                        $('#studentinfo').show();

                        $("#groupDataCreateDiv").hide();
                        $("#assign_group_data").hide();

                        stopLoader();
                        
                    }
                });
            }
        }

        function actionOnChangeVanue(vanueID)
        {
            actionHideContent();

            $('#district_name').html('<option>Select district</option>');

            if(vanueID != '')
            {
                startLoader();
                 $.ajax({
                    url: "<?php echo Yii::$app->homeUrl."employee/trainings/getdistrict_venue?securekey=$menuid";?>",
                    type: 'POST',
                    data: { vanueID:vanueID },
                    dataType: 'JSON',
                    success: function (data) 
                    {
                        // alert(data);
                        // $('#datashow').html(data);
                        
                        $('#district_name').html(data.district_data);
                        $('#studentinfo').hide();

                        $("#groupDataCreateDiv").hide();
                        $("#assign_group_data").hide();

                        stopLoader();
                        
                    }
                });
            }
        }
        function Insertemployee() 
        { 

             var dept_id = $('#department_id').val();     
            var course_Id = $('#assign_Course').val();     
             var tech_id = $('#tech_id').val();
            var venu_id = $('#trg_venue').val();
            var district_id = $('#district_name').val();
            var emp_id = $('#employee_id').val(); 
            var tmp_id = $('#start_end_date_time').val(); 
            var status =$('#status').val();
            
           


            var url = "<?php echo Yii::$app->homeUrl."employee/trainings/insertemployee?securekey=$menuid";?>";
            // BASEURL + "creategroup";
            $.ajax({
                type: "POST",
                url: url,
                dataType: "json",
                data: {                    
                    dept_id:dept_id,
                    course_Id:course_Id,
                    venu_id:venu_id,
                    status:status,
                    district_id:district_id,
                    emp_id:emp_id,
                    tmp_id:tmp_id,
                    tech_id:tech_id
                },
                success: function (data) { 
                   
                    stopLoader();

                    if(data == '1')
                    {
                        //$('#studentinfo').hide();
                       // $('#groupDataCreateDiv').hide();
                       // $('#studentinfo').html('');
                        swal("Done!success");
                    }
                    else
                    {
                        swal("Employee already exists");                        
                    }
                }
            });
        }

        function CreateTrainingGroup()
        {
            startLoader();
            var department_id = $('#department_id').val();     
            var assign_Course = $('#assign_Course').val();     
            var start_end_date_time = $('#start_end_date_time').val();  
            var trg_venue_id = $('#trg_venue').val();
            var district_name = $('#district_name').val();
            
            appliedArr = [];
            $("#subjectTable_wrapper table input[type=checkbox]").each(function ()
            {
                if($(this).prop("checked"))
                {
                    appliedArr.push($(this).val()); 
                }   
            });


            var url = "<?php echo Yii::$app->homeUrl."employee/trainings/createtraininggroup?securekey=$menuid";?>";
            // BASEURL + "creategroup";
            $.ajax({
                type: "POST",
                url: url,
                dataType: "json",
                data: {
                    appliedarr: appliedArr,
                    department_id:department_id,
                    assign_Course:assign_Course,
                    start_end_date_time:start_end_date_time,
                    trg_venue_id:trg_venue_id,
                    district_name:district_name
                },
                success: function (data) {
                   
                    stopLoader();

                    if(data.status == '111')
                    {
                        $('#studentinfo').hide();
                        $('#groupDataCreateDiv').hide();
                        $('#studentinfo').html('');
                        swal("Done!",data.message_show,"success");
                    }
                    else
                    {
                        swal("Note!",data.message_show,"warning");                        
                    }
                }
            });
            
        }

         function InsertEmployee1()
        {
           // startLoader();
            var dept_id = $('#department_id').val();     
            var course_Id = $('#assign_Course').val();     
             var tech_id = $('#tech_id').val();
            var venu_id = $('#trg_venue').val();
            var district_id = $('#district_name').val();
            var emp_id = $('#employee_id').val(); 
            var tmp_id = $('#start_end_date_time').val(); 
            var status =$('#status').val();
            
           


            var url = "<?php echo Yii::$app->homeUrl."employee/trainings/insertemployee1?securekey=$menuid";?>";
            // BASEURL + "creategroup";
            $.ajax({
                type: "POST",
                url: url,
                dataType: "json",
                data: {                    
                    dept_id:dept_id,
                    course_Id:course_Id,
                    venu_id:venu_id,
                    status:status,
                    district_id:district_id,
                    emp_id:emp_id,
                    tmp_id:tmp_id,
                    tech_id:tech_id
                },
                success: function (data) {
                   
                    stopLoader();

                    if(data.status == '111')
                    {
                        $('#studentinfo').hide();
                        $('#groupDataCreateDiv').hide();
                        $('#studentinfo').html('');
                        swal("Done!",data.message_show,"success");
                    }
                    else
                    {
                        swal("Note!",data.message_show,"warning");                        
                    }
                }
            });
            
        }

        function startLoader()
        {
           $("#loading").show();
        }
         
        function stopLoader()
        {
            $("#loading").hide();
        }

        function datetimeonchange(gtid)
        {
            $('#start_date_endate_disp').val('');
            // $('#start_date_endate_disp selectedIndex').attr('datedisp')

            var datetime = $(gtid).find(':selected').attr('datedisp');
            $('#start_date_endate_disp').val(datetime)
            $('#datetimed').show()
        }
    </script>