<?php
$homeUrl = Yii::$app->homeUrl;
?>

<div class="dataTables_wrapper no-footer" id="subjectTable_wrapper">
    <table id="studentView" class="display adminlist" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>#</th>
                <th style="padding-left:3px;"><input title="Select All" class="assign_allstudent_to_group" type="checkbox"></th>
                <th>Full Name</th>
                <th>Employee Code</th>
                <th>Gender</th>
            </tr>
        </thead>
        <tbody class="list">
        <?php
            $i=1;

            foreach($employeesinfo as $studentdataK=>$studentdataV)
            {
             $employee_code = $studentdataV['employee_code'];
             $applied_id = $studentdataV['applied_id'];
             // $studentname= Yii::$app->Utility->getupperstring($studentdataV['fname'].' '.$studentdataV['lname']);
             $studentname= ucwords(strtolower($studentdataV['fname'].' '.$studentdataV['lname']));
             $gender = $studentdataV['gender'];

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
            <td class=""><?php echo $i;?></td>
            <td class="" ><input name="StudentToGroups[]" class="assign_student_to_group" type="checkbox" value="<?php echo $applied_id;?>"></td>
            <td class=""><?php echo $studentname;?></td>
            <td class=""><?php echo $employee_code;?></td>
            <td class=""><?php echo $gender;?></td>
         </tr>
    <?php
         $i++;
         }
    ?>
    </table>
</div>
<script>
    $(document).ready(function()
    {
        $('#studentView').DataTable(
        {
             "lengthMenu": [[-1], [ "All"]]
            //"bPaginate": false
        });         
        $("#studentView_paginate").hide();


        /*$(document).ready(function() {
            $('#training_table').DataTable( {
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
            } );
        } );
        */
    });

</script>