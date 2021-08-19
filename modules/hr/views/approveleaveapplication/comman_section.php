<?php
    $homeUrl = Yii::$app->homeUrl;
?>
<style>
    .table-scroll {
      height:700px;
      overflow:auto;  
      margin-top:20px;
    }

    #subjectTable_wrapper table {
      width:100%;
    }
    .statusClass
    {
        font-weight: bold;
    }
</style>

<script type="text/javascript" language="javascript" src="<?=Yii::$app->homeUrl?>/js/forexcel/jszip.js"></script>
<script type="text/javascript" language="javascript" src="<?=Yii::$app->homeUrl?>/js/forexcel/buttons.js"></script>

<?php
    // echo "<pre>"; print_r($chkAtten); // die();
?>
<div class="" id="_wrapper">
    <div class="col-sm-4" style="float:left;">
        <label>Department</label>
        <select class="form-control form-control-sm" name="forward_to[dept_id]" id="dept_id" required="required" onchange="dptChange(this.value)">
            <option value="-1">Select Department</option>
            <?php 
            if(!empty($depts)){
                foreach($depts as $d){
                    echo "<option value='$d[dept_id]'>$d[dept_name]</option>";
                }
            }
            ?>
        </select>
        <span id="dpts_error" style="display: none;">Please Select Department.</span>
    </div>
    <div class="col-sm-4" style="float:left;">
        <label>Designation</label>
        <select class="form-control form-control-sm" id="desg_id" name="forward_to[designation_id]" required="required" onchange="dsgChange(this.value)">
            <option value="-1">Select Designation</option>
        </select>
        <span id="dsg_error" style="display: none;">Please Select Designation.</span>
    </div>
        <div class="col-sm-4" style="float:left;">
        <label>Employees</label>
        <select class="form-control form-control-sm" id="employee_Data" name="forward_to[employee_Data]" required="required" onchange="empChange(this.value)">
            <option value="-1">Select Employee</option>
        </select>
        <span id="emp_error" style="display: none;">Please Select employee.</span>
    </div>
</div>

<script>
    function dptChange(dptID)
    {
        if(dptID != -1)
        {
            $('#dpts_error').hide();

            var dept_id = dptID;
            var menuid = $('#menuid').val();


            // getdeptempdropdown
            var url = BASEURL+"employee/information/getdsgdropdown?securekey="+menuid;

            $.ajax({
                type: "POST",
                url: url,
                dataType: 'JSON',
                data:{ dept_id:dept_id },
                success: function(data)
                {
                    // console.log(data.Status);

                    if(data.Status == 'SS')
                    {
                        $('#desg_id').html(data.Res);
                    }

                    // stopLoader();
                }
            });
        }
        else
        {
            $('#dpts_error').show();
            $('#dpts_error').css('color','Red');
            $('#desg_id').html('<option value="-1">Select Designation</option>');
        }

        $('#employee_Data').html('<option value="-1">Select Employee</option>');
    }

    function empChange(empID)
    {
        if(empID == -1 || empID == '' )
        {
            $('#emp_error').show();
            $('#emp_error').css('color','Red');
        }
        else
        {
           $('#emp_error').hide();
        }
    }

    function dsgChange(dsgID)
    {
        if(dsgID != -1 && dsgID != '' )
        {
            $('#dsg_error').hide();

            var dept_id = $('#dept_id').val();
            var menuid = $('#menuid').val();


            // getdeptempdropdown
            var url = BASEURL+"employee/information/getdepdsgtempdropdown?securekey="+menuid;

            $.ajax({
                type: "POST",
                url: url,
                dataType: 'JSON',
                data:{ dept_id:dept_id,dsgid:dsgID },
                success: function(data)
                {
                    // console.log(data.Status);

                    if(data.Status == 'SS')
                    {
                        $('#employee_Data').html(data.Res);
                    }

                    // stopLoader();
                }
            });

        }
        else
        {
            $('#dsg_error').show();                
            $('#dpts_error').css('color','Red');
        }
        $('#employee_Data').html('<option value="-1">Select Employee</option>');
    }

    function changeStatusAction(e)
    {
        $('#for_forward_leave').html('');

        var selectedVal = $(e).find(':selected').attr('data');
        var menuid = $('#menuid').val();

        if(selectedVal == 'Forward')
        {
            $('#for_forward_leave').css({"display": "block"});

            var url = BASEURL+"hr/approveleaveapplication/get_comman_section?securekey="+menuid;
     
            // console.log(attendate+"=====employment_type="+employment_type+"======"+dept_id);

            $.ajax({
                type: "POST",
                url: url,
                dataType: 'JSON',
                data:{ keyval:selectedVal },
                success: function(data){

                    console.log(data.render_data);


                    $('#for_forward_leave').html(data.render_data);

                    // stopLoader();
                }
            });
        }
        else
        {
            $('#for_forward_leave').css({"display": "none"});
            // $('#for_forward_leave').css({"background-color": "yellow", "font-size": "200%"});
        }
    }
</script>