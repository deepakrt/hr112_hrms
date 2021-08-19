<?php 
    $this->title="Dashboard";
    $info=Yii::$app->user->identity;
    $service = Yii::$app->utility->get_service_details($info->e_id, "Current");

    /*echo "<pre>";print_r($info); // die;
    echo "<pre>";print_r($service); 
    die;*/
    $authname = '';

    if($info->authority1 != '')
    {
        $authname = Yii::$app->inventory->get_empname($info->authority1);
    }
?>
<h6><b>Personal Information:-</b></h6>
<table class="table table-bordered" style="font-size: 14px;">
    <tr>
        <th>Name of Employee</th>
        <th>Designation</th>
        <th>Department</th>
        <th>Employment Type</th>
         <th>Reporting Authority</th>
    </tr>
    <tr>
        <td><?=ucwords($info->fname)?> <?=ucwords($info->lname)?></td>
        <td><?=$info->desg_name?></td>
        <td><?=$info->dept_name?></td>
        <td><?=$info->employment_type?></td>
        <td><?=$authname;?></td>
    </tr>
</table>
<?php /*
<hr>
<h6><b>Current Service Details:-</b></h6>
<table class="table table-bordered" style="font-size: 14px;">
    <tr>
        <th>Level</th>
        <th>Basic Cons Pay</th>
        <th>HRA</th>
        <th>Total Pay</th>
    </tr>
    <tr>
        <td><?=$service['level']?></td>
        <td><?=$service['basic_cons_pay']?></td>
        <td>0</td>
        <td>0</td>
    </tr>
</table>
*/ ?>
