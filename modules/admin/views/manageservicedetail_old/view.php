<?php
$this->title= 'View Service Details';
//echo "<pre>";print_r($servicedetail);
?>
<b>Personal Information</b>
<table class="table table-bordered">
    <tr>
        <td>Emp ID</td>
        <td><?=$info['employee_code']?></td>
        <td>Name</td>
        <td><?=$info['fullname']?></td>
    </tr>
    <tr>
        <td>Designation</td>
        <td><?=$info['desg_name']?></td>
        <td>Department</td>
        <td><?=$info['dept_name']?></td>
    </tr>
</table>
<b> Basic Rate Change Info:</b>
<table class="table table-bordered">
    <tr>
        <th>Date of Change</th>
        <th>Financial Eff From</th>
        <th>Staff Type</th>
        <th>Cons. Pay</th>
        <th>HRA</th>
        <th>Total Pay</th>
        <th>Reason</th>
    </tr>
    <?php 
    foreach($servicedetail as $s){
        $joining_date = date('d-m-Y', strtotime($s['joining_date']));
        $emptype = $s['employment_type'];
        $basic_cons_pay = $s['basic_cons_pay'];
        $reason = $s['reason'];
        echo "<tr>
            <td>$joining_date</td>
            <td>$joining_date</td>
            <td>$emptype</td>
            <td>$basic_cons_pay</td>
            <td>0</td>
            <td>$basic_cons_pay</td>
            <td>$reason</td>
        </tr>";
    }
    $url = Yii::$app->homeUrl."admin/manageservicedetail?securekey=$menuid";
    ?>
</table>
<div class="text-center">
    <a href="<?=$url?>" class="btn btn-danger btn-sm">Back</a>
</div>