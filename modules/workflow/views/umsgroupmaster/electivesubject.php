<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$validate_Function = '';
if($Validate == "Y")
{
 $validate_Function = "PopulateStudent('N')";   
}
else if($Validate == "N")
{
 $validate_Function = "EditGroupList('N')";    
}
else
{
    
}
?>

                    <div class="col-lg-2">Elective Subjects</div>
                    <div class="col-lg-4">
                        <select  onchange="<?php echo $validate_Function;?>" id="Elective_Subject" name="beGroupMaster[Elective_Subject]" >
                    <option value="">Select Elective Subject</option>
                    <?php
                    $i= 1;
                    foreach ($electivesubject as $key=>$val) {
                        $Subject_Name = $val['Subject_Name'];
                        $Subject_Id = $val['Subject_Id']."###".$i;
                        $i++;
                        echo "<option value='$Subject_Id'>".Yii::$app->Utility->getupperstring($Subject_Name)."</option>"
                        ?>
                        
                    <?php } ?>
                  </select>
                    </div>
               



