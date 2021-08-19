<?php
use yii\widgets\ActiveForm;
$this->title= 'Pending Tour Requisitions';
?>
<div class="text-right">
    <a href="<?=Yii::$app->homeUrl?>finance/requisitiontour/allrequisition?securekey=<?=$menuid?>" class="linkcolor">View All Tour Requisitions</a>
</div>
<div class="col-sm-12">
    <hr>
    <table id="dataTableShow" class="display" style="width:100%">
        <thead>
            <tr>
                <th>No.</th>
                <th>Employee Name</th>
                <th>Start Date & <br>End Date</th>
                <th>Tour Location</th>
                <th>Dept</th>
                <th>Project</th>
                <th>Type</th>
                <th>Advance <br>Required</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
            <tbody>
                <?php 
               // echo "<pre>";print_r($lists);
                if(!empty($lists)){
                    $i =1;
                    foreach($lists as $list){
                    $req_id = Yii::$app->utility->encryptString($list['req_id']); 
                    $e_id = Yii::$app->utility->encryptString($list['employee_code']); 
                    $viewUrl = Yii::$app->homeUrl."finance/requisitiontour/view?securekey=$menuid&req_id=$req_id&e_id=$e_id";
                ?>
                <tr>
                    <td><?=$i?></td>
                    <td><?=$list['fullname']?> (<?=$list['employee_code']?>)</td>
                    <td><?=date('d-M-y', strtotime($list['end_date']))?> to <br> <?=date('d-M-y', strtotime($list['start_date']))?></td>
                    <td><?=$list['city_name']?></td>
                    <td><?=$list['dept_name']?></td>
                    <td><?=$list['project_name']?></td>
                    <td><?=$list['tour_type']?></td>
                    <td>Rs. <?=$list['advance_amount']?></td>
                    <td><?=$list['status']?></td>
                    <td><u><a href="<?=$viewUrl?>" class="linkcolor">View</a></u></td>
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
                    <th>Start Date &<br> End Date</th>
                    <th>Tour Location</th>
                    <th>Dept</th>
                    <th>Project</th>
                    <th>Type</th>
                    <th>Advance</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </tfoot>
    </table>
</div>