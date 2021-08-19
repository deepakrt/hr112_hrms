<?php
$this->title= 'Training Programmes';
$url =Yii::$app->homeUrl."admin/managetrainings/addtrainingprogram?securekey=$menuid";
?>
<div class="text-right">
    <a href="<?=$url?>" class="btn btn-success btn-sm mybtn">Add New Entry</a>
</div>
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
                 
            <th>Is Active</th>            
            <th>Added On</th>  
            <th>Action</th>          
        </tr>
    </thead>
    <tbody>
    <?php
    if(!empty($tpms)){

        $i=1;
        foreach($tpms as $tpm){
            
            $tpm_id = $tpm['tpm_id'];

           $delete_url =Yii::$app->homeUrl."admin/managetrainings/removetrainingprogram?securekey=$menuid&tpm_id=$tpm_id&check=delete";
           $activate_url = Yii::$app->homeUrl."admin/managetrainings/updatetrainingprogram?securekey=$menuid&tpm_id=$tpm_id&check=activate";//Yii::$app->homeUrl."admin/managetrainings/activatetrainingprogram?securekey=$menuid&tpm_id=$tpm_id";
           $edit_url =Yii::$app->homeUrl."admin/managetrainings/updatetrainingprogram?securekey=$menuid&tpm_id=$tpm_id";
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
             <td><?php echo date('H:i A', strtotime($tpm['start_time'])).' - '.date('H:i A', strtotime($tpm['end_time']));?></td>
              
               <td><?php echo $tpm['department_name'];?></td>
               

            <td><?=$is_active?></td>
                

  <td><?php echo date('d-m-Y', strtotime($tpm['created_date']));?></td>
  <td>
                    <?php  if($tpm['active'] == '0'){?>

                        <a title="Activate this record" class="linkcolor" href="<?php echo $activate_url;?>">Activate</a>

                    <?php }else{?>
                    <a title="Delete this record" class="linkcolor deleteallow1" href="<?php echo $delete_url;?>">DeActivate</a>
                <?php }?>
                <a title="Edit this record" class="linkcolor" href="<?php echo $edit_url;?>">Edit</a>
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
                    
            <th>Is Active</th>            
            <th>Added On</th>  
            <th>Action</th>                 
        </tr>
    </tfoot>
</table>