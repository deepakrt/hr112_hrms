<?php
$homeUrl = Yii::$app->homeUrl;



// ECHO "<PRE>"; PRINT_R($lists); DIE();
?>


<style>
    /*#table-wrapper {
      position:relative;
    }*/
    .table-scroll {
      /*height:800px;*/
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
<div class="dataTables_wrapper no-footer table-scroll trngdata" id="subjectTable_wrapper">
    <div class="col-md-6" style="float: left;" ></div>
    <table id="employee_data_disptable" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>Sr.</th>
                    <th>Employee Code</th>
                    <th>Name</th>
                    <th>Designation</th>
                    <!--<th>Address</th>
                    <th>Date of Birth</th>-->
                    <th>Department</th>
                    <th>Emp. Type</th>
                    <th>Phone</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if(!empty($lists)){
                    $i =1;
                    foreach($lists as $l){ 
                    $name = ucwords($l['name']);
                    $employee_code = $l['employee_code'];
                    $desg_name = $l['desg_name'];
                                       
                    $empltype = $l['employmenttype']; 
                    
                    /*if($l['employmenttype'] == 'R'){
                        $empltype="Regular";
                    }elseif($l['employmenttype'] == 'C'){
                        $empltype="Contract";
                    }*/

                    //$encry = Yii::$app->utility->encryptStringUrl($l['e_id']);
                    
                    $encry = Yii::$app->utility->encryptString($l['employee_code']);
                    $viewUrl = Yii::$app->homeUrl."admin/manageemployees/viewemployee?securekey=$menuid&empid=$encry";
                    $editUrl = Yii::$app->homeUrl."admin/manageemployees/updateemployee?securekey=$menuid&empid=$encry";
                    ?>
                    <tr>
                    <td><?=$i?></td>
                    <td><?=$employee_code?></td>
                    <td><?=$name?></td>
                    <td><?=$desg_name?></td>
                    <!--td><?php //$dob?></td-->
                    <td><?=$l['dept_name']?></td>
                    <td><?=$empltype?></td>
                    <td><?=$l['phone']?></td>
                    <td style="padding: 0;"><a href="<?=$viewUrl?>" class="btn btn-success btn-sm btn-xs">View</a> <a href="<?=$editUrl?>" class="btn btn-info btn-sm btn-xs">Edit</a></td>
                    </tr>   
                <?php $i++; }
                }
                ?>
            </tbody>
            <tfoot>
                <th>Sr.</th>
                    <th>Name</th>
                    <th>Designation</th>
                    <!--<th>Address</th>
                    <th>Date of Birth</th>-->
                    <th>Department</th>
                    <th>Emp. Type</th>
                    <th>Phone</th>
                    <th>Action</th>
            </tfoot>
        </table> 
</div>

<script>

    $(document).ready(function() {

        
        var table = $('#employee_data_disptable').DataTable( {
            lengthChange: true,
            buttons: [ 'copy', 'excel', 'print' ]
        } );

        table.buttons().container()
            .appendTo( '#subjectTable_wrapper .col-md-6:eq(0)' );
    } );
    $('#exampleModalCenter').modal({backdrop: 'static', keyboard: false});

</script>