<?php
$homeUrl = Yii::$app->homeUrl;
?>

<div class="dataTables_wrapper no-footer" id="subjectTable_wrapper">
<table id="studentView" class="display adminlist" cellspacing="0" width="100%">
<thead>
<tr>
    <th>#</th>
    <th>Full Name</th>
    <th>Roll Number</th>
    <th>Registration Number</th>
    <th>Existing Group Name</th>
    
</tr>
</thead>
    <tbody class="list">
        <?php
        $i =1;
        foreach($studentsinfo as $studentdataK=>$studentdataV)
        {
         $studentname= Yii::$app->Utility->getupperstring($studentdataV['First_Name'].' '.$studentdataV['Last_name']);
         $Roll_Number = $studentdataV['Roll_Number'];
         $Registration_Number = $studentdataV['Registration_Number'];
         $grp_id = $studentdataV['grp_id'];
         $grp_name = $studentdataV['GrpName'];
        ?>
      <tr>
        <td class=""><?php echo $i;?></td>
        <td class=""><?php echo $studentname;?></td>
        <td class=""><?php echo $Roll_Number;?></td>
        <td class=""><?php echo $Registration_Number;?></td>
        <td class=""><?php echo $grp_name;?>
        <input type ="hidden" value ="<?php echo $grp_id;?>" name="beGroupMaster[Grp_Id]" />
        </td>
        
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
 $('#studentView').DataTable();

});
</script>