<?php
use yii\widgets\ActiveForm;
$this->title= 'My Appraise';
$emplist = Yii::$app->hr_utility->hr_get_appraise_list();
?>
<br>
<table id="dataTableShow" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Sr. No.</th>
            <th>Emp. Code</th>
            <th>Emp. Name</th>
            <th>Date of Birth</th>
            <th>Email</th>
            <th>Contact</th>
            <th>Joining Date</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php 
        if(!empty($emplist)){
            $i=1;
            foreach($emplist as $emp){
                $e_id = Yii::$app->utility->encryptString($emp['employee_code']);
                $viewUrl = Yii::$app->homeUrl."admin/myappraise/viewemployee?securekey=$menuid&eid=$e_id";
                $employee_code = $emp['employee_code'];
                $fulname = $emp['fullname'].", ".$emp['desg_name'];
                $username = Yii::$app->utility->replaceSpecialChar($emp['email_id']);
                $phone = $emp['phone'].", ".$emp['emergency_phone'];
                $joining_date = date('d-M-Y', strtotime($emp['joining_date']));
                $dob = date('d-M-Y', strtotime($emp['dob'])); 
                ?>
        <tr>
            <td><?=$i?></td>
            <td><?=$employee_code?></td>
            <td><?=$fulname?></td>
            <td><?=$dob?></td>
            <td><?=$username?></td>
            <td><?=$phone?></td>
            <td><?=$joining_date?></td>
            <td><a href="<?=$viewUrl?>" class="btn btn-light btn-sm btnxs">View</a></td>
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
            <th>Date of Birth</th>
            <th>Email</th>
            <th>Contact</th>
            <th>Joining Date</th>
            <th></th>
        </tr>
    </tfoot>
</table>