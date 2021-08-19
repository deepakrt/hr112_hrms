<?php
$this->title = "Annual Reimbursement";
//echo "<pre>";print_r($details);
?>
<div class="row">
    <div class="col-sm-6 text-left">
        <a style="margin-bottom:10px;" href="<?=Yii::$app->homeUrl?>admin/annual_reimbursement/reimtypelist?securekey=<?=$menuid?>" class="linkcolor">Reimbursement Type List</a>
    </div>
    <div class="col-sm-6 text-right">
        <a style="margin-bottom:10px;" href="<?=Yii::$app->homeUrl?>admin/annual_reimbursement/addentitlement?securekey=<?=$menuid?>" class="btn btn-success btn-sm mybtn">Add Entitlement</a>
    </div>
</div>

<div class="col-sm-12">
<table id="dataTableShow" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Sr. No.</th>
            <th>Type</th>
            <th>Designation</th>
            <th>Emp. Type</th>
            <th>Financial Year</th>
            <th>Amount</th>
            <th></th>
        </tr>
    </thead>
	<tbody>
            <?php 
            if(!empty($details)){
                $i=1;
                foreach($details as $d){
                    $ann_reim_id = Yii::$app->utility->encryptString($d['ann_reim_id']); 
                    $emp_type="";
                    if($d['emp_type']=='R'){
                        $emp_type="Regular";
                    }elseif($d['emp_type']=='C'){
                        $emp_type="Contractual";
                    }
            ?>
            <tr>
                <td><?=$i?></td>
                <td><?=$d['name']?></td>
                <td><?=$d['desg_name']?></td>
                <td><?=$emp_type?></td>
                <td><?=$d['financial_yr']?></td>
                <td><?=$d['sanc_amt']?></td>
            </tr>        
            <?php $i++; }
            }
            ?>
	</tbody>
	<tfoot>
            <tr>
                <th>Sr. No.</th>
                <th>Type</th>
                <th>Designation</th>
                <th>Emp. Type</th>
                <th>Financial Year</th>
                <th>Amount</th>
                <th></th>
            </tr>
	</tfoot>
</table>
</div>
