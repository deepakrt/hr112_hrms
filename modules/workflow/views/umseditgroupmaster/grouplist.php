<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="col-lg-2 RemoveUG1">Change/Assign Group</div>
                    <div class="col-lg-4 RemoveUG2">
                    <select  id="beGroupMaster_Groups_To" name="beGroupMaster[Groups_To]" >
                    <option value="">Select Group</option>
                    <?php
                    $i= 1;
                    foreach ($grouplistinfo as $key=>$val) {
                        $GrpName = $val['GrpName'];
                        $Groups_Id = $val['Groups'];
                       
                        echo "<option value='$Groups_Id'>$GrpName</option>"
                        ?>
                        
                    <?php } ?>
                  </select>
                    </div>
<div class="col-lg-4" id="EDIT_GROUP_ACTION"></div>

