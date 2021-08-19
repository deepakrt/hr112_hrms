<?php
    $this->title= 'Training Programmes Requests';

    $current_employee_role_name = Yii::$app->user->identity->role_name;
    if($current_employee_role_name == 'FLA')
    {
        $approved='Recommended';
        $button = 'Recommend';
    }
    else
    {
        $approved='Approved';
        $button='Approve';
    }

?>

<table id="dataTableShow" class="display" style="width:100%">
    <thead>
        <tr>
            <th colspan="2">
                Select All &nbsp;&nbsp;<input type="checkbox" id="selectAll" />
            </th>
            
            <th></th>
            <th></th>
            <th></th>
                 
            <th colspan="2">
                <button onClick="changeMasterFun(1)" name="appr_t" value="appr_t" class="btn btn-success btn-sm sl"><?=$button?></button>
                &nbsp;&nbsp;
                <button onClick="changeMasterFun(2)" name="appr_r" value="appr_r" class="btn btn-success btn-sm sl">Reject</button>
                <br>
            </th>
                    
        </tr>
        <tr>
            <th>Sr.</th>
            <th>Employee name</th>
            <th>Course Name</th>
            <th>Dates</th>
            
            <th>Timings</th>
            
            <th>Department</th>
                 
           <th>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php
        /*echo '<pre>';
        print_r(Yii::$app->user->identity);

        die();*/

    if(!empty($tpms)){

        $i=1;
        foreach($tpms as $tpm)
        {
            
            $tpm_id = $tpm['tpm_id'];
            $applied_id = $tpm['applied_id'];
            $status = $tpm['status'];
            // status
            $employee_code = Yii::$app->user->identity->employee_code;
            // $statuses =  Yii::$app->tr_utility->tr_get_training_applied_status($tpm_id,$tpm['employee_code']);
            $tmp_employee_code = $tpm['employee_code'];


            $tmp_employee_code = Yii::$app->utility->encryptString($tmp_employee_code); 
            $tpm_id = Yii::$app->utility->encryptString($tpm_id); 
            $applied_id = Yii::$app->utility->encryptString($applied_id); 

            $url =Yii::$app->homeUrl."employee/trainings/trainingview?securekey=$menuid&emp_code=$tmp_employee_code&tpm_id=$tpm_id&applied_id=$applied_id";
            $updtd ="-";
            if(!empty($tpm['created_date'])){
                $updtd = date('d-m-Y H:i:s', strtotime($tpm['created_date']));
            }
            $notact = "";
            $is_active = "Yes";
            if($tpm['is_active'] == '0'){
                $is_active = "<span>No</span>";
                $notact = "style='background-color:#f7e2dd;'";
            }
        ?>
        <tr <?php echo $notact;?> >
            <td>
                <input type="checkbox" class="all_ckeck_box" id="row_<?=$applied_id;?>" value="<?=$tmp_employee_code.'_'.$applied_id.'_'.$tpm_id;?>" />
                <?php echo $i;?>
            </td>
            <td><?php echo $tpm['Name_eng'];?></td>
            <td <?php echo $notact;?>><?php echo $tpm['course_name'];?></td>
            
            <td><?php echo date('d-M-Y', strtotime($tpm['start_date'])).' - '.date('d-M-Y', strtotime($tpm['end_date']));?></td>
           
            <td><?php echo date('H:i A', strtotime($tpm['start_time'])).' - '.date('H:i A', strtotime($tpm['end_time']));?>  </td>
              
            <td><?php echo $tpm['dept_name'];?></td>
                          
            <td>
                <a href="<?php echo $url;?>">
                    <img width="25" src="/ersshar/images/vieww.png">
                    <?=ucfirst($status);?>
                </a>
            </td>

        </tr>
        <?php $i++;
        }
    }
    ?>
    </tbody>
    <tfoot>
        <tr>
            <th>Sr.</th>
             <th>Employee Name</th>
            <th>Course Name</th>
            <th>Dates</th>
            
            <th>Timings</th>
            <th>Department</th>
            <th>Action</th>
        </tr>
    </tfoot>
</table>

<div id="datashow">
</div>

<script type="text/javascript">
    $('#selectAll').click(function (e) {
        $(this).closest('table').find('td input:checkbox').prop('checked', this.checked);
    });

    function changeMasterFun(param,txtval)
    {
        var array = []
        var checkboxes = document.querySelectorAll('input[type=checkbox]:checked')

        for (var i = 0; i < checkboxes.length; i++) {
            if(checkboxes[i].value != "on")
            {
                array.push(checkboxes[i].value)
            }  
        }

        // $('#datashow').html(array);
        

        if(array.length > 0)
        {
             
            $.ajax({
                url: "<?=Yii::$app->homeUrl."employee/trainings/trainingmultirequestaction?securekey=$menuid";?>",
                type: 'POST',
                data: { combian_data:array,parameter:param },
                dataType: 'JSON',
                success: function (data) 
                {
                    // alert(data);
                    // $('#datashow').html(data);
                    
                    if(data.STATUS_ID == '000')
                    {
                        $('#dataTableShow_wrapper').html("");
                        swal("Done!",data.STATUS_MESSAGE,"success");
                        $('#dataTableShow_wrapper').html("<h2>"+data.STATUS_MESSAGE+"</h2>");

                        setTimeout(function(){ 
                            location.reload();
                        }, 3000);
                    }
                    else
                    {
                        swal("Warning!",data.STATUS_MESSAGE,"error");
                    }
                }
            });
           
        }
        else
        {
            swal("Warning!","Please select atleast one record.","warning");
        }
    }
</script>