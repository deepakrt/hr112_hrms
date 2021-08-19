<?php
$this->title= 'Training Programmes';


?>

<hr>
<table id="dataTableShow" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Sr.</th>
            <th>Course Name</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Timings</th>
            <th>Department</th>
                 
           <th>Status</th>
                    
        </tr>
    </thead>
    <tbody>
    <?php
    if(!empty($tpms)){

        $i=1;
        foreach($tpms as $tpm){
            
            $tpm_id = $tpm['tpm_id'];
            $employee_code = Yii::$app->user->identity->employee_code;
            $statuses =  Yii::$app->tr_utility->tr_get_training_applied_status($tpm_id,$employee_code);
           
         $url =Yii::$app->homeUrl."employee/trainings/applyfortraining?securekey=$menuid&tpm_id=".$tpm_id;
            $updtd ="-";
            if(!empty($tpm['created_date'])){
                $updtd = date('d-m-Y H:i:s', strtotime($tpm['created_date']));
            }
            $notact = "";
            $is_active = "Yes";
            if($tpm['active'] == '0'){
                $is_active = "<span>No</span>";
                $notact = "style='background-color:#f7e2dd;'";
            }
        ?>
        <tr <?php echo $notact;?> >
           <td><?php echo $i;?></td>
           <td <?php echo $notact;?>><?php echo $tpm['course_name'];?></td>
            
           <td><?php echo date('d-M-Y', strtotime($tpm['start_date']));?></td>
           <td><?php echo date('d-M-Y', strtotime($tpm['end_date']));?></td>
           <td><?php echo date('H:i A', strtotime($tpm['start_time'])).' - '.date('H:i A', strtotime($tpm['end_time']));?>  </td>
              
            <td><?php echo $tpm['department_name'];?></td>
                          
            <td>
                <?php
                    if(isset($statuses['status'])){?>
                        <?=strtoupper($statuses['status']);?>
                    <?php 
                        }
                        else
                        {
                    ?>
                         <a href="<?php echo $url;?>">Apply</a>
                    <?php
                        }
                    ?>

                    <?php 
                    /*echo "<pre>";
                     print_r($statuses);
                    echo "</pre>";*/
                     ?>
                    <?php /*if(isset($statuses[0]['status'])=='applied'){?>
                    APPLIED
                    <?php } elseif(isset($statuses[0]['status'])=='normal'){?>
                        <a href="<?php echo $url;?>">Apply</a>
                    <?php }
                     elseif(isset($statuses[0]['status'])=='approved'){?>
                        APPLIED AND APPROVED
                    <?php }
                    elseif(isset($statuses[0]['status'])=='Completed'){?>
                        Completed
                    <?php }
                    elseif(isset($statuses[0]['status'])=='rejected'){?>
                        REJECTED
                    <?php }else{?>
                         <a href="<?php echo $url;?>">Apply</a>
                     <?php }*/  ?>
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
            <th>Course Name</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Timings</th>
            <th>Department</th>
                  <th>Status</th>  
            
                      
        </tr>
    </tfoot>
</table>