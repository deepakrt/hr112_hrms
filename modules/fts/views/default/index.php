<?php
$this->title="Dak Details of All Employees";
$allEmps = Yii::$app->utility->get_employees();

$Super_Admin_Emp_Code = Super_Admin_Emp_Code;
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
            <th>Total Dak Received</th>
            <th>Total Dak Sent</th>
        </tr>
    </thead>
    <tbody>
    <?php 
//    echo "<pre>";print_r($allEmps); die('111');
    if(!empty($allEmps)){
        $i=1;
        foreach($allEmps as $allEmp){
            if($Super_Admin_Emp_Code != $allEmp['employee_code']){
                $rec = $sent = 0;
                $dakrec = Yii::$app->fts_utility->fts_get_dak('I', $allEmp['employee_code']);
                $daksent = Yii::$app->fts_utility->fts_get_dak('O', $allEmp['employee_code']);
//                echo "<pre>";print_r($daksent); die('111');
                if(!empty($daksent)){ $sent = count($daksent); }
                if(!empty($dakrec)){ $rec = count($dakrec); }
                
                
//                $code = Yii::$app->utility->encryptString($allEmp['employee_code']);
//                $viewUrl = Yii::$app->HomeUrl."fts/default/empdakdetail?securekey=$menuid&securecode=$code";
                echo "<tr>
                    <td>$i</td>
                    <td>".$allEmp['employee_code']."</td>
                    <td>".$allEmp['fullname']."</td>
                    <td>".$allEmp['desg_name']."</td>
                    <td>".$allEmp['dept_name']."</td>
                    <td align='center'>".$rec."</td>
                    <td align='center'>".$sent."</td>
                    </tr>";
                $i++;
            }
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
            <th>Total Dak Received</th>
            <th>Total Dak Sent</th>
        </tr>
    </tfoot>
</table>