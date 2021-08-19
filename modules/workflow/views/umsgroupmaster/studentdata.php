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
    <th>Roll Number</th>
    <th>Registration Number</th>
    <th>Gender</th>
</tr>
</thead>
    <tbody class="list">
        <?php
        $i=1;
        foreach($studentsinfo as $studentdataK=>$studentdataV)
        {
         $Student_Sem_Id = $studentdataV['Student_Sem_Id'];
         $studentname= Yii::$app->Utility->getupperstring($studentdataV['First_Name'].' '.$studentdataV['Last_name']);
         $Roll_Number = $studentdataV['Roll_Number'];
         $Registration_Number = $studentdataV['Registration_Number'];
         $gender = $studentdataV['gender'];
        ?>
      <tr>
        <td class=""><?php echo $i;?></td>
        <td class="" ><input name="StudentToGroups[]" class="assign_student_to_group" type="checkbox" value="<?php echo $Student_Sem_Id;?>"></td>
        <td class=""><?php echo $studentname;?></td>
        <td class=""><?php echo $Roll_Number;?></td>
        <td class=""><?php echo $Registration_Number;?></td>
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
    //"bPaginate": false
         }
                 );
         
         $("#studentView_paginate").hide();

});
</script>