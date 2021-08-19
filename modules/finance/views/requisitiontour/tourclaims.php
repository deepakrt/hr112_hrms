<?php
$this->title = "Tour Claims";
$lists = Yii::$app->finance->fn_get_tour_claim_details(NULL, "Submitted,In-Process", NULL);
//echo "<pre>";print_r($lists);
?>
<table id="dataTableShow" class="display" style="width:100%">
    <thead>
        <tr>
            <th>No.</th>
            <th>Employee Name</th>
            <th>Start Date & <br>End Date</th>
            <th>Tour Location</th>
            <th>Dept</th>
            <th>Project</th>
            <th>Advance <br>Required</th>
            <th>Claimed</th>
            <th>Sanctioned</th>
            <th>Status</th>
            <th></th>
        </tr>
    </thead>
        <tbody>
            <?php 
//            echo "<pre>";print_r($lists);
            if(!empty($lists)){
                $i =1;
                foreach($lists as $list){
                $claim_id = Yii::$app->utility->encryptString($list['claim_id']); 
                $req_id = Yii::$app->utility->encryptString($list['req_id']); 
                $e_id = Yii::$app->utility->encryptString($list['employee_code']); 
                $viewUrl = Yii::$app->homeUrl."finance/requisitiontour/viewclaimdetails?securekey=$menuid&claimid=$claim_id&reqid=$req_id&e_id=$e_id";
                $advamt = $sncAmt = 0;
                if(!empty($list['sanctioned_amount'])){
                    $sncAmt =$list['sanctioned_amount'];
                }
                if(!empty($list['advance_amount'])){
                    $advamt =$list['advance_amount'];
                }
                $sncAmt = number_format($sncAmt, 2);
                $advamt = number_format($advamt, 2);
            ?>
            <tr>
                <td><?=$i?></td>
                <td><?=$list['fullname']?> (<?=$list['employee_code']?>)</td>
                <td><?=date('d-M-y', strtotime($list['end_date']))?> to <br> <?=date('d-M-y', strtotime($list['start_date']))?></td>
                <td><?=$list['city_name']?></td>
                <td><?=$list['dept_name']?></td>
                <td><?=$list['project_name']?></td>
                <td><?=$advamt?></td>
                <td><?=$list['claimed_amount']?></td>
                <td><?=$sncAmt?></td>
                <td><?=$list['status']?></td>
                <td><u><a href="<?=$viewUrl?>" class="linkcolor">View & Action</a></u></td>
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
                <th>Start Date & <br>End Date</th>
                <th>Tour Location</th>
                <th>Dept</th>
                <th>Project</th>
                <th>Advance <br>Required</th>
                <th>Claimed</th>
                <th>Sanctioned</th>
                <th>Status</th>
                <th></th>
            </tr>
        </tfoot>
</table>