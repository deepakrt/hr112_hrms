<?php
$this->title ="Pending IPD Claims";
?>
<table id="dataTableShow" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Sr.</th>
            <th>Emp Name</th>
            <th>Designation</th>
            <th>Department</th>
            <th>Claimed Amount</th>
            <th>Submitted On</th>
            <th>Status</th>
            <th>View Bill Details</th>
        </tr>
    </thead>
    <tbody>
    <?php 
    if(!empty($allClaims)){
        $i=1;
//        echo "<pre>";print_r($allClaims); die;
        foreach($allClaims as $claim){
            $ipd_id = Yii::$app->utility->encryptString($claim['ipd_id']);
            $employee_code = Yii::$app->utility->encryptString($claim['employee_code']);
            $viewUrl = Yii::$app->homeUrl."finance/ipdclaims/ipdclaimaction?securekey=$menuid&ipd_id=$ipd_id&ec=$employee_code";
            $viewUrl = "<a href='$viewUrl' class='linkcolor' title='View Bill-wise Details'>View</a>";
            echo "<tr>
                <td>$i</td>
                <td>".$claim['name']."</td>
                <td>".$claim['desg_name']."</td>
                <td>".$claim['dept_name']."</td>
                <td align='left'>". number_format($claim['total_claimed_amt'], 2)."</td>
                <td>".date('d-M-Y', strtotime($claim['claimed_on']))."</td>
                <td>".$claim['status']."</td>
                <td>$viewUrl</td>
                </tr>";
            $i++;
        }
    }
    ?>
    </tbody>
    <tfoot>
        <tr>
            <th>Sr.</th>
            <th>Emp Name</th>
            <th>Designation</th>
            <th>Department</th>
            <th>Claim Amount</th>
            <th>Submitted On</th>
            <th>Status</th>
            <th>View Bill Details</th>
        </tr>
    </tfoot>
</table>