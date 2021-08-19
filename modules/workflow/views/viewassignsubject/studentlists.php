<style>
   thead th {
     vertical-align: top !important;
    
}
</style>
   
<table id="studentView" class="table-bordered table table-hover">
<thead>
<tr>
    <th>#</th> 
    <th>Roll No</th>
    <th>Registration No</th>
    <th>Student Name</th>
    <th>Group Name</th>    
</tr>
</thead>
    <tbody class="list">
        <?php
        $i =1;
        foreach($studentsinfo as $studentdataK=>$studentdataV)
        {
         $Roll_Number= $studentdataV['Roll_Number'];
         $Registration_Number = $studentdataV['Registration_Number'];
         $studentname = Yii::$app->Utility->getupperstring($studentdataV['First_Name'])." ".Yii::$app->Utility->getupperstring($studentdataV['Last_name']);
         $grpname = $studentdataV['grpname'];
         
        ?>
      <tr>
        <td class=""><?php echo $i;?></td>
        <td class=""><?php echo $Roll_Number;?></td>
        <td class=""><?php echo $Registration_Number;?></td>          
        <td class=""><?php echo $studentname;?></td>
        <td class=""><?php echo $grpname;?></td>
        
        
     </tr>
<?php
$i++;
}
?>

</table>              


