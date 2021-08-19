<?php
$this->title = "Annual Reimbursement Requests";
?>
<div class="text-right">
    <a href="<?=Yii::$app->homeUrl?>finance/annualreimbursement/viewall?securekey=<?=$menuid?>" class="linkcolor">All Requests</a>
</div>
<hr>
<!--<h6><b><i>Pending Contingency Claims</i></b></h6>-->
<table id="dataTableShow" class="display" style="width:100%">
    <thead>
        <tr>
            <th>No.</th>
            <th>Employee Name</th>
            <th>Department</th>
            <th>Type</th>
            <th>Financial Year</th>
            <th>Amount</th>
            <th>Status</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php 
//        echo "<pre>";print_r($request);
        if(!empty($request)){
            $i =1;
            foreach($request as $r){
                $emp = Yii::$app->utility->get_employees($r['employee_code']);
                
                $arc_id = Yii::$app->utility->encryptString($r['arc_id']); 
                $ec = Yii::$app->utility->encryptString($r['employee_code']); 
                $ann_reim_id = Yii::$app->utility->encryptString($r['ann_reim_id']); 
                $fy = Yii::$app->utility->encryptString($r['financial_year']);
                
                $url = Yii::$app->homeUrl."finance/annualreimbursement/view?securekey=$menuid&key=$arc_id&key1=$ec&key2=$ann_reim_id&key3=$fy";
                $url = "<a href='$url' class='linkcolor'>View & Action</a>";
        ?>
        <tr>
            <td><?=$i?></td>
            <td><?=$emp['fullname'];?></td>
            <td><?=$emp['dept_name'];?></td>
            <td><?=$r['name'];?></td>
            <td><?=$r['financial_year'];?></td>
            <td><?=$r['total_claimed'];?></td>
            <td><?=$r['status'];?></td>
            <td><?=$url?></td>
        </tr>
        <?php $i++;	
            }
        }
        ?>
    </tbody>
    <tfoot>
       <tr>
            <th>No.</th>
            <th>Employee Name</th>
            <th>Department</th>
            <th>Type</th>
            <th>Financial Year</th>
            <th>Amount</th>
            <th>Status</th>
            <th></th>
        </tr>
    </tfoot>
</table>

