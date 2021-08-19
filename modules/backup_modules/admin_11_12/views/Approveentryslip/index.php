<?php
use yii\widgets\ActiveForm;
$this->title= 'Approve / Reject Extra Duty Application';
//$emplist = Yii::$app->hr_utility->hr_get_appraise_list();
//echo "<pre>";print_r($emplist);
?>
<br>
<!--<table id="dataTableShow" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Sr. No.</th>
            <th>Emp. Code</th>
            <th>Emp. Name</th>
            <th>Designation</th>
            <th>Date of Birth</th>
            <th>Email</th>
            <th>Contact</th>
            <th>Joining Date</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        if(!empty($emplist)){
            $i=1;
            foreach($emplist as $emp){
                $employee_code = $emp['employee_code'];
                $fulname = $emp['fulname'];
                $desg_name = $emp['desg_name'];
                $username = Yii::$app->utility->replaceSpecialChar($emp['username']);
                $phone = $emp['phone'].", ".$emp['emergency_phone'];
                $joining_date = date('d-M-Y', strtotime($emp['joining_date']));
                $dob = date('d-m-Y', strtotime($emp['dob'])); 
                ?>
        <tr>
            <td><?=$i?></td>
            <td><?=$employee_code?></td>
            <td><?=$fulname?></td>
            <td><?=$desg_name?></td>
            <td><?=$dob?></td>
            <td><?=$username?></td>
            <td><?=$joining_date?></td>
            <td><?=$dob?></td>
        </tr>
        <?php
        $i++;
            }
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <th>Sr. No.</th>
            <th>Emp. Code</th>
            <th>Emp. Name</th>
            <th>Designation</th>
            <th>Date of Birth</th>
            <th>Email</th>
            <th>Contact</th>
            <th>Joining Date</th>
        </tr>
    </tfoot>
</table>-->