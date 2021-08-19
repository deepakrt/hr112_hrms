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

<div class="col-md-12">
    <div class="col-md-6" style="float: left;" ></div>
    <div class="col-md-6" >
        <label>Filter District Wise
        <select class="form-control form-control-sm text-center" name="dist_id" id="dist_id" onchange="show_data_inmodel('<?=$venueID?>','<?=$courseID?>',this.value)">
            <option>Select District</option>
        </select></label>
    </div>
</div>
<div class="dataTables_wrapper no-footer table-scroll trngdata" id="subjectTable_wrapper">
    <div class="col-md-6" style="float: left;" ></div>
    <table id="studentView" class="display adminlist" cellspacing="0" width="100%">
        <thead>
            <tr class="headrow">
                <th>#</th>
                <th>Employee Code</th>
                <th>Full Name</th>
                <th>Gender</th>
                <!-- <th>Programme Code</th> -->
                <th>Course Name</th>
                <!-- <th>Technology Name</th> -->
                <th>District name</th>
                <th>Group Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody class="list">
        <?php
            $i=1;

            /*echo "<pre>"; print_r($employeestrainingdata);
            die();*/

            foreach($employeestrainingdata as $studentdataK=>$studentdataV)
            {
             $employee_code = $studentdataV['employee_code'];
             $applied_id = $studentdataV['applied_id'];
             $programme_code = $studentdataV['Programme_code'];
             $course_name = $studentdataV['course_name'];
             $district_name = $studentdataV['district_name'];
             $technology_name = $studentdataV['technology_name'];
             $grp_nm = $studentdataV['grp_nm'];
             $start_date = $studentdataV['start_date'];
             $end_date = $studentdataV['end_date'];
             $start_time = $studentdataV['start_time'];
             $end_time = $studentdataV['end_time'];
             $status = $studentdataV['status'];

             // $studentname= Yii::$app->Utility->getupperstring($studentdataV['fname'].' '.$studentdataV['lname']);
             $studentname = ucwords(strtolower($studentdataV['fname'].' '.$studentdataV['lname']));
             $status = ucwords(strtolower($status));
             $gender = $studentdataV['gender'];
             $belt_no = $studentdataV['belt_no'];

             if($gender == 'M')
             {
                $gender = 'Male';
             }
             elseif($gender == 'F')
             {
                $gender = 'Female';
             }
        ?>
          <tr>
            <td class=""><?=$i;?></td>
            <td class=""><?=$employee_code;?></td>
            <td class=""><?=$studentname;?></td>
            <td class=""><?=$gender;?></td>
            <!-- <td class="" ><?=$programme_code;?></td> -->
            <td class="" ><?=$course_name;?></td>
            <!-- <td class="" ><?=$technology_name;?></td> -->
            <td class="" ><?=$district_name;?></td>
            <td class="" ><?=$grp_nm;?></td>
            <td class="" ><?=$start_date;?></td>
            <td class="" ><?=$end_date;?></td>
            <td class="" ><?=$start_time;?></td>
            <td class="" ><?=$end_time;?></td>
            <td class="statusClass" ><?=$status;?></td>
         </tr>
    <?php
         $i++;
         }
    ?>
    </table>   
</div>

<script>

    $(document).ready(function() {

        getDIstVenuWise(<?=$venueID?>,<?=$dist_id?>);

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

        var table = $('#studentView').DataTable( {
            lengthChange: true,
            buttons: [ 'copy', 'excel', 'print' ]
        } );

        table.buttons().container()
            .appendTo( '#subjectTable_wrapper .col-md-6:eq(0)' );
    } );
    $('#exampleModalCenter').modal({backdrop: 'static', keyboard: false});

</script>