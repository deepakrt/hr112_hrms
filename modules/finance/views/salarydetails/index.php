<?php
$this->title ="Salary Details";
?>
<br>
<table id="dataTableShow" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Sr.</th>
            <th>Employee Code</th>
            <th>Employee Name</th>
            <th>Designation</th>
            <th>Department</th>
            <th>Emp. Type</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php 
//    echo "<pre>";print_r($allEmps);
    if(!empty($allEmps)){
        $i=1;
        foreach($allEmps as $allEmp){
            $code = Yii::$app->utility->encryptString($allEmp['employee_code']);
            $viewUrl = Yii::$app->HomeUrl."finance/salarydetails/viewdetail?securekey=$menuid&securecode=$code";
            echo "<tr>
                <td>$i</td>
                <td>".$allEmp['employee_code']."</td>
                <td>".$allEmp['fullname']."</td>
                <td>".$allEmp['desg_name']."</td>
                <td>".$allEmp['dept_name']."</td>
                <td>".$allEmp['employmenttype']."</td>
                <td><a href='$viewUrl' class='linkcolor'>View Detail</a></td>
                </tr>";
            $i++;
        }
    }
    ?>
    </tbody>
    <tfoot>
        <tr>
            <th>Sr.</th>
            <th>Employee Code</th>
            <th>Employee Name</th>
            <th>Designation</th>
            <th>Department</th>
            <th>Emp. Type</th>
            <th></th>
        </tr>
    </tfoot>
</table>