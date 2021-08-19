<?php
$homeUrl = Yii::$app->homeUrl;
?>

<div class="dataTables_wrapper no-footer" id="subjectTable_wrapper">
<table id="facultyView" class="display adminlist" cellspacing="0" width="100%">
<thead>
<tr>
    <th>#</th>    
    <th>Faculty Name</th>    
    <th>Login Id</th>    
    
</tr>
</thead>
    <tbody class="list">
        <?php
        $i=1;
        foreach($info as $facultylistK=>$facultylistV)
        {
         $faculty_id = $facultylistV['email_id'];
         
         $faculty_name = Yii::$app->Utility->getupperstring($facultylistV['full_name']);
         
        ?>
      <tr>
        <td class=""><?php echo $i;?></td>                    
        <td class=""><?php echo $faculty_name;?></td>
        <td class=""><?php echo $faculty_id;?></td>    
        
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