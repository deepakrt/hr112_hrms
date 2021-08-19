<?php
$homeUrl = Yii::$app->homeUrl;
?>

<div class="dataTables_wrapper no-footer" id="subjectTable_wrapper">
<table id="facultyView" class="display adminlist" cellspacing="0" width="100%">
<thead>
<tr>
    <th>#</th>    
    <th>Subject</th>    
    <th>Group</th>
    <th>Faculty Name</th>
    <th>View Student</th>
    
</tr>
</thead>
    <tbody class="list">
        <?php
        $i=1;
        foreach($facultylist as $facultylistK=>$facultylistV)
        {         
         $Subject_Name = $facultylistV['Subject_Name'];
         $GrpName = trim($facultylistV['GrpName']);
         $grp_id = $facultylistV['grp_id'];         
         $faculty_id = $facultylistV['facultyId'];
         $viewGroup_btn = '--';
         $faculty_name = Yii::$app->Utility->getupperstring($facultylistV['faculty_name']);
         
         if($GrpName !="--")
         {          
         $viewGroup_btn = "<input id='viewdetail$i'class='btn btn-primary viewstudentgrouplistsfaculty' type='button' value='View Students'>";         
         $viewGroup_btn.="<input type ='hidden' id ='grpid$i' value ='$grp_id' />";         
         }
        ?>
      <tr>
        <td class=""><?php echo $i;?></td>        
        <td class=""><?php echo $Subject_Name;?></td>
        <td class=""><?php echo $GrpName;?></td>
        <td class=""><?php echo $faculty_name;?></td>
        <td class=""><?php echo $viewGroup_btn; ?>
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
 $('#facultyView').DataTable();

});
</script>