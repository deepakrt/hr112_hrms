<?php
$homeUrl = Yii::$app->homeUrl;
?>


<style>
    /*#table-wrapper {
      position:relative;
    }*/
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
<div class="dataTables_wrapper no-footer table-scroll trngdata" id="subjectTable_wrapper">
    <div class="col-md-6" style="float: left;" ></div>
    <table id="dataTableShow" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Sr.</th>
            <th>Emp ID</th>
            <th>Name</th>
            <th>Designation</th>
            <th>Dept</th>
            <th>Attn. Date</th>
            <th>Attendance</th>
            <th></th>              
        </tr>
    </thead>
    <tbody>
        <?php if(!empty($chkAtten)){ 
            $i=1;
            foreach($chkAtten as $a){
                if($a['attendance_mark'] == 'P'){
                    $att ="Present";
                }elseif($a['attendance_mark'] == 'A'){
                    $att ="Absent";
                }elseif($a['attendance_mark'] == 'L'){
                    $att ="On Leave";
                }elseif($a['attendance_mark'] == 'FHL'){
                    $att ="First Half Leave";
                }elseif($a['attendance_mark'] == 'SHL'){
                    $att ="Second Half Leave";
                }
                // $emp = Yii::$app->utility->get_employees($a['employee_code']);
            //                echo "<pre>";print_r($emp);die;
                echo "<tr>
                    <td>$i</td>
                    <td>".$a['employee_code']."</td>
                    <td>".$a['fname']."</td>
                    <td>".$a['desg_name']."</td>
                    <td>".$a['dept_name']."</td>
                    <td>".date('d-M-Y', strtotime($a['attendance_date']))."</td>
                    <td>$att</td>
                    <td></td>
                 </tr>";
                $i++;
            }
             ?>
            
        <?php  }?>
    </tbody>
    <tfoot>
        <tr>
            <th>Sr.</th>
            <th>Emp ID</th>
            <th>Name</th>
            <th>Designation</th>
            <th>Dept</th>
            <th>Attn. Date</th>
            <th>Attendance</th>
            <th></th>              
        </tr>
    </tfoot>
</table>


  
</div>

<script>

    $(document).ready(function() {

      

        function getDIstVenuWise(venuID,distId)
        {
            $('#dist_id').html('');

            $.ajax({
                url: "<?php echo Yii::$app->homeUrl."employee/trainings/getdistrict_venue?securekey=$menuid";?>",
                type: 'POST',
                data: { venueID:venuID},
                dataType: 'JSON',
                success: function (data) 
                {
                    $('#dist_id').html(data.district_data);

                    if(distId != null && distId != '')
                    {
                        $('select[name^="dist_id"] option[value="'+distId+'"]').attr("selected","selected");
                    }
                }
            });
        } 

        var table = $('#dataTableShow').DataTable( {
            lengthChange: true,
            buttons: [ 'copy', 'excel', 'print' ]
        } );

        table.buttons().container()
            .appendTo( '#subjectTable_wrapper .col-md-6:eq(0)' );
    } );
    $('#exampleModalCenter').modal({backdrop: 'static', keyboard: false});

</script>