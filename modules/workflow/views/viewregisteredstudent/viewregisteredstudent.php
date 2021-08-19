<?php
$homeUrl = Yii::$app->homeUrl;
?>
        <table id="rstudentviewinfof" class="display adminlist" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Roll No</th>
                    <th>Student Name</th>
                    <th>DOB</th>
                    <th>Father's Name</th>                    
                    <th>Update</th>
                    <th>Activate/De-Activate</th>
                </tr>
            </thead>
            <tbody>
             <?php
				$i=1;
				foreach($viewregisteredstudentinfo as $viewregisteredstudentinfoK=>$viewregisteredstudentinfoV)
				{
				$first_name= $viewregisteredstudentinfoV['first_name'];
				$last_name = $viewregisteredstudentinfoV['last_name'];
				$studentname= Yii::$app->Utility->getupperstring($viewregisteredstudentinfoV['first_name'].' '.$viewregisteredstudentinfoV['last_name']);
				 $Roll_Number = $viewregisteredstudentinfoV['Roll_Number'];
				 
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
				 $email_id = $viewregisteredstudentinfoV['email_id'];
				 $father_email = $viewregisteredstudentinfoV['father_email'];
				 
				 $Course_Name = $viewregisteredstudentinfoV['Course_Name'];
				 $Department_Name = $viewregisteredstudentinfoV['Department_Name'];
				 $Course_Id = $viewregisteredstudentinfoV['Course_Id'];
				 $Department_Id = $viewregisteredstudentinfoV['Department_Id'];
				 
				 $Registration_Number = $viewregisteredstudentinfoV['Registration_Number'];
				 $College_Id = $viewregisteredstudentinfoV['College_Id'];
				 $address = $viewregisteredstudentinfoV['address'];
				 $gender = $viewregisteredstudentinfoV['gender'];
                                 $Category = $viewregisteredstudentinfoV['category'];
                                 $SubCategory = $viewregisteredstudentinfoV['subcategory'];
				 $Blood_group = $viewregisteredstudentinfoV['Blood_group'];
				 $contact_no = $viewregisteredstudentinfoV['contact_no'];
				 $father_dob = $viewregisteredstudentinfoV['father_dob'];
				 $Batch = $viewregisteredstudentinfoV['Batch'];
                                 $activestatus = $viewregisteredstudentinfoV['activestatus'];
                                 $activestatus_class = '';
                                 if(strtolower($activestatus) == 'deactivate') $activestatus_class = 'btn-danger';
                                 else if(strtolower($activestatus) == 'activate') $activestatus_class = 'btn-success';
				?>
                 <tr>
                    <td><?php echo $i;?></td>
                    <td><?php echo $Roll_Number;?></td>
                    <td><?php echo $studentname;?></td>
                    <td><?php echo $dob;?></td>
                    <td><?php echo $father_name;?></td>
                    
                    <td>
                    <?php
                    $class_serialize = "Serialize_$i";
                    echo "<form class= '$class_serialize' action='$homeUrl"."workflow/viewregisteredstudent/viewinfo?secureKey=$secureKey&secureHash=$secureHash' method='POST'>"; ?>
                    <input type='hidden' name='<?php echo Yii::$app->request->csrfParam; ?>' value='<?php echo Yii::$app->request->csrfToken; ?>' />
                     
                     <input type='hidden' name='Roll_Number' value='<?php echo $Roll_Number; ?>' />
                     <input type='hidden' name='first_name' value='<?php echo $first_name; ?>' />
                     <input type='hidden' name='last_name' value='<?php echo $last_name; ?>' />
                     <input type='hidden' name='dob' value='<?php echo $dob; ?>' />
                     <input type='hidden' name='father_name' value='<?php echo $father_name; ?>' />
                     <input type='hidden' name='mother_name' value='<?php echo $mother_name; ?>' />
                     <input type='hidden' name='email_id' value='<?php echo $email_id; ?>' />
                     <input type='hidden' name='father_email' value='<?php echo $father_email; ?>' />
                     
                     <input type='hidden' name='Course_Name' value='<?php echo $Course_Name; ?>' />
                     <input type='hidden' name='Department_Name' value='<?php echo $Department_Name; ?>' />
                     <input type='hidden' name='Course_Id' value='<?php echo $Course_Id; ?>' />
                     <input type='hidden' name='Department_Id' value='<?php echo $Department_Id; ?>' />
                     <input type='hidden' name='College_Id' value='<?php echo $College_Id; ?>' />
                     <input type='hidden' name='Registration_Number' value='<?php echo $Registration_Number; ?>' />
                     <input type='hidden' name='address' value='<?php echo $address; ?>' />
                     <input type='hidden' name='gender' value='<?php echo $gender; ?>' />
                     <input type='hidden' name='category' value='<?php echo $Category; ?>' />
                     <input type='hidden' name='subcategory' value='<?php echo $SubCategory; ?>' />
                     <input type='hidden' name='Blood_group' value='<?php echo $Blood_group; ?>' />
                     <input type='hidden' name='contact_no' value='<?php echo $contact_no; ?>' />
                     <input type='hidden' name='father_dob' value='<?php echo $father_dob; ?>' />
                     <input type='hidden' name='Batch' value='<?php echo $Batch; ?>' />
                     <input type='hidden' name='Semester' value='<?php echo $Semester; ?>' />
                     <input type='hidden' name='activate_decativate' value='<?php echo $activestatus; ?>' />
                     <input type='hidden' name='secureKey' value='<?php echo $secureKey; ?>' />
                     <input type='hidden' name='secureHash' value='<?php echo $secureHash; ?>' />
                     
                     <input type='submit' class='btn btn-primary' value='Update Info'></form> 
                    </td>
                    <td>
                     <input class="activate_decativate_action btn <?php echo $activestatus_class;?>" type="button"  value="<?php echo $activestatus;?>" id="<?php echo $class_serialize;?>">   
                    </td>
                </tr>
                <?php
				$i++;
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
     