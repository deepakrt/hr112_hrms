<?php
$homeUrl = Yii::$app->homeUrl;
$session = Yii::$app->session;
             $session->open();
             $accessToken = $session['accessWebToken'];
             $session->close();
?>

<div class="dataTables_wrapper no-footer" id="subjectTable_wrapper">
    <?php
    echo "<input type ='hidden' id ='unassignjsonwebtoken' value ='$accessToken' />";
    ?>
<table id="facultyView" class="display adminlist" cellspacing="0" width="100%">
<thead>
<tr>
    <th>#</th>    
    <th>Faculty Name</th>
    <th>Group</th>
    <th>Status</th>
    <th>View Student</th>    
</tr>
</thead>
    <tbody class="list">
        <?php
        $i=1;
             
        foreach($facultyinfo as $facultyinfoK=>$facultyinfoV)
        {
         $fname= Yii::$app->Utility->getupperstring($facultyinfoV['fname']);
         $GrpName = $facultyinfoV['GrpName'];
         $status = $facultyinfoV['status'];
         $class = $status."subjectbyhod";
         $faculty_id = $facultyinfoV['faculty_id'];
         $grp_id = $facultyinfoV['grp_id'];
         $subjectId = $facultyinfoV['subjectId'];
         
         $status_btn = "<input id='$status$i'class='btn btn-primary $class' type='button' value='$status'>";
         
         $viewGroup_btn = "<input id='viewdetail$i'class='btn btn-primary viewstudentgrouplists' type='button' value='View Students'>";
        ?>
      <tr>
        <td class=""><?php echo $i;?></td>
        <td class=""><?php echo $fname;?></td>
        <td class=""><?php echo $GrpName;?></td>
        <td class=""><?php echo $status_btn;
        echo "<input type ='hidden' id ='facultyid$i' value ='$faculty_id' />";
        echo "<input type ='hidden' id ='grpid$i' value ='$grp_id' />";
        echo "<input type ='hidden' id ='subjectId$i' value ='$subjectId' />";        
        
        
             ?>
        </td>
        <td class=""><?php echo $viewGroup_btn;
        echo "<input type ='hidden' id ='facultyid$i' value ='$faculty_id' />";
        echo "<input type ='hidden' id ='grpid$i' value ='$grp_id' />";
        echo "<input type ='hidden' id ='subjectId$i' value ='$subjectId' />";        
        
        
             ?>
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
 $('#facultyView').DataTable();

});
</script>