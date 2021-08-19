<?php
$this->title ="Pending OPD Claims";
//echo "<pre>";print_r($claims);
?>
<table id="dataTableShow" class="display" style="width:100%">
    <thead>
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
    </thead>
    <tbody>
    <?php 
    if(!empty($claims)){
        $i=1;
        foreach($claims as $claim){
            $opd_id = Yii::$app->utility->encryptString($claim['opd_id']);
            $entitle_id = Yii::$app->utility->encryptString($claim['entitle_id']);
            $employee_code = Yii::$app->utility->encryptString($claim['employee_code']);
            $viewUrl = Yii::$app->homeUrl."finance/reimbursementclaim/viewopdclaims?securekey=$menuid&opd_id=$opd_id&ec=$employee_code&entitle_id=$entitle_id";
//            $viewUrl = "<a href='$viewUrl' title='View Bill-wise Details'><img src='".Yii::$app->homeUrl."images/print.gif' /></a>";
            $viewUrl = "<a href='$viewUrl' class='linkcolor' title='View Bill-wise Details'>View</a>";
            echo "<tr>
                <td>$i</td>
                <td>".$claim['emp_name']."</td>
                <td>".$claim['desg_name']."</td>
                <td>".$claim['dept_name']."</td>
                <td align='left'>". number_format($claim['total_claim'], 2)."</td>
                <td>".date('d-M-Y', strtotime($claim['created_on']))."</td>
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
            <th>Claimed Amount</th>
            <th>Submitted On</th>
            <th>Status</th>
            <th>View Bill Details</th>
        </tr>
    </tfoot>
</table>
