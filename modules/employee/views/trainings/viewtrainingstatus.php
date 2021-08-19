<?php
    $this->title= 'Training Programmes Status';

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
<hr>
<table id="dataTableShow" class="display" style="width:100%">
    <thead>        
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
        print_r($tpms);

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

            $url =Yii::$app->homeUrl."employee/trainings/trainingstatusview?securekey=$menuid&emp_code=$tmp_employee_code&tpm_id=$tpm_id&applied_id=$applied_id";
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