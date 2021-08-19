<?php
$homeUrl = Yii::$app->homeUrl;
?>
<div class="dataTables_wrapper no-footer" id="subjectTable_wrapper">
        <table id="uploadview" class="display adminlist" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Login Name</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>View Activities</th>
                </tr>
            </thead>
            <tbody>
             <?php
				$i=1;
				foreach($activityinfodetails as $activityinfoK=>$activityinfoV)
				{
				 $Login_Name= $activityinfoV['email_id'];
				 $First_Name= Yii::$app->Utility->getupperstring($activityinfoV['first_name']);
                                 $Full_Name= Yii::$app->Utility->getupperstring($activityinfoV['name']);
                                 $Last_Name= Yii::$app->Utility->getupperstring($activityinfoV['last_name']);
                                 $RollName= Yii::$app->Utility->getupperstring($activityinfoV['RollName']);
				 
                                 
				?>
                <tr>
                    <td><?php echo $i;?></td>
                    <td><?php echo $Login_Name;?></td>
                    <td><?php echo $First_Name;?></td>
                    <td><?php echo $Last_Name;?></td>                    
                    <td>
                    <?php echo "<form action= '".$homeUrl."workflow/assignactivities/viewassignactivity?secureKey=$secureKey&secureHash=$secureHash' method='POST'>"; ?>
                    <input type='hidden' name='<?php echo Yii::$app->request->csrfParam; ?>' value='<?php echo Yii::$app->request->csrfToken; ?>' />
                     
                     <input type='hidden' name='View[username]' value='<?php echo $Login_Name; ?>' />
                     <input type='hidden' name='View[Roleid]' value='<?php echo $role_id; ?>' />                    
                     <input type='hidden' name='View[Full_Name]' value='<?php echo $Full_Name; ?>' />
                     <input type='hidden' name='View[RollName]' value='<?php echo $RollName; ?>' />
                      
                     <input type='submit' class='btn btn-primary' value='View'>
                     </form> 
                     
                   </td>
                </tr>
                <?php
				$i++;
				}
				?>
            </tbody>
        </table>
      </div>
<script>
$(document).ready(function() {
    $('#uploadview').DataTable( {
        //"scrollY": 500,
        "scrollX": true
    } );
} );

</script>
     