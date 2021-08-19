<?php
$homeUrl = Yii::$app->homeUrl;
?>
        <table id="rstudentviewinfof" class="display adminlist" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Roll No</th>
                    <th>Registration No</th>
                    <th>Student Name</th> 
                    <th>Batch</th>
                    <th>Semester</th>
                    <th>Father's Name</th>                                        
                    <th>Mother's Name</th>                                        
                </tr>
            </thead>
            <tbody>
                <?php
                                $i=1;
				foreach($studentinfo as $viewregisteredstudentinfoK=>$viewregisteredstudentinfoV)
				{
                                $first_name= $viewregisteredstudentinfoV['first_name'];
				$last_name = $viewregisteredstudentinfoV['last_name'];
				$studentname= Yii::$app->Utility->getupperstring($viewregisteredstudentinfoV['first_name'].' '.$viewregisteredstudentinfoV['last_name']);
				 $Roll_Number = $viewregisteredstudentinfoV['Roll_Number'];
				 $Registration_Number = $viewregisteredstudentinfoV['Registration_Number'];
				 
				 if($viewregisteredstudentinfoV['dob'])
				 {
				 $dob =  date("d-m-Y", strtotime($viewregisteredstudentinfoV['dob']));
				 }
				 else
				 {
					 $dob = '';
				 }
                                 $father_name = $viewregisteredstudentinfoV['father_name'];
				 $mother_name = $viewregisteredstudentinfoV['mother_name'];
				 $Batch = $viewregisteredstudentinfoV['Batch'];
                                
                                
                                ?>
                <tr>
                    <td><?php echo $i;?></td>
                    <td><?php echo $Roll_Number;?></td>
                    <td><?php echo $Registration_Number;?></td>
                    <td><?php echo $studentname;?></td>
                    <td><?php echo $Batch;?></td>
                    <td><?php echo $Semester;?></td>
                    <td><?php echo $father_name;?></td>
                    <td><?php echo $mother_name;?></td>
                  </tr>  
                  <?php
                  }
                  ?>
                    
                
                
                            </tbody>
        </table>
<script>
$(document).ready(function() {
    $('#rstudentviewinfof').DataTable( {
        //"scrollY": 500,
        "scrollX": true
    } );
} );

</script>