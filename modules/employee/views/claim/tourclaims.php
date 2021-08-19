<?php
$this->title="Tour Claim";
?>
<style>
h6 {
    background: #ccc;
    border: 1px solid;
    padding: 2px;
}
</style>
<div class="col-sm-12">
    <hr>
    <h6><b><i>List of Pending Advances to Claim</i></b></h6>
    <table id="dataTableShow" class="display" style="width:100%">
        <thead>
            <tr>
                <th>No.</th>
                <th>Tour Location</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Type</th>
                <th>Advance </th>
                <th>Claim</th>
            </tr>
        </thead>
        <tbody>
            <?php 
//            echo "<pre>";print_r($lists);
            if(!empty($lists)){
                $i =1;
                foreach($lists as $list){
                    if($list['applied_for_claim'] == 'N'){
                    $req_id = Yii::$app->utility->encryptString($list['req_id']);
            ?>
            <tr>
                <td><?=$i?></td>
                <td><?=$list['city_name']?></td>
                <td><?=date('d-M-y', strtotime($list['start_date']))?></td>
                <td><?=date('d-M-y', strtotime($list['end_date']))?></td>
                <td><?=$list['tour_type']?></td>
                <td>Rs. <?=$list['advance_amount']?></td>
                <td>
                    <a href="<?=Yii::$app->homeUrl?>employee/claim/applytourclaim?securekey=<?=$menuid?>&id=<?=$req_id?>">
                        <img src='<?=Yii::$app->homeUrl?>images/edit.gif'>
                    </a></td>
            </tr>	
            <?php $i++;	
                }
                }
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th>No.</th>
                <th>Tour Location</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Type</th>
                <th>Advance</th>
                <th>Claim</th>
            </tr>
        </tfoot>
    </table>
</div>
<script>
$(document).ready(function() {
    $('#tablesub').DataTable( {
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
    } );
} );
</script>
<div class="col-sm-12">
    <hr>
    <h6><b><i> Submit to Finance</i></b></h6>
    <table id="tablesub" class="display" style="width:100%">
        <thead>
            <tr>
                <th>No.</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Tour Location</th>
                <th>Dept</th>
                <th>Project</th>
                <th>Status</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php 
//            echo "<pre>";print_r($drafts);
            if(!empty($drafts)){
                $i =1;
                foreach($drafts as $list){
                    $claim_id = Yii::$app->utility->encryptString($list['claim_id']);
                    $req_id = Yii::$app->utility->encryptString($list['req_id']);
                    $editUrl = Yii::$app->homeUrl."employee/claim/otherdetails?securekey=$menuid&claimid=$claim_id&reqid=$req_id"
            ?>
            <tr>
                <td><?=$i?></td>
                <td><?=date('d-M-y', strtotime($list['start_date']))?></td>
                <td><?=date('d-M-y', strtotime($list['end_date']))?></td>
                <td><?=$list['city_name']?></td>
                <td><?=$list['dept_name']?></td>
                <td><?=$list['project_name']?></td>
                <td><?=$list['status']?></td>
                <td><a href="<?=$editUrl?>" title="Edit Claim Details"><img src="<?=Yii::$app->homeUrl?>images/edit.gif" /></a></td>
                <td><a href="" title="Submit Claim">Submit</a></td>
            </tr>	
            <?php $i++;	
                }
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th>No.</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Tour Location</th>
                <th>Dept</th>
                <th>Project</th>
                <th>Status</th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</div>
<script>
$(document).ready(function() {
    $('#alltable').DataTable( {
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
    } );
} );
</script>
<div class="col-sm-12">
    <hr>
    <h6><b><i> All Claim Applications</i></b></h6>
    <table id="alltable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>No.</th>
                <th>Location</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Advance</th>
                <th>Claimed</th>
                <th>Sanctioned</th>
                <th>Status</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php 
            //echo "<pre>";print_r($allTours); die;
            if(!empty($allTours)){
                $i =1;
                foreach($allTours as $list){
                    $sanctioned_amount = 0;
                    if(!empty($list['sanctioned_amount'])){
                        $sanctioned_amount = $list['sanctioned_amount'];
                    }
                    $sanctioned_amount = number_format($sanctioned_amount, 2);
                    $claim_id = Yii::$app->utility->encryptString($list['claim_id']);
                    $req_id = Yii::$app->utility->encryptString($list['req_id']);
                    $url = Yii::$app->homeUrl."employee/claim/previewclaim?securekey=$menuid&claimid=$claim_id&reqid=$req_id";
                    $preview = "<a href='$url' class='linkcolor'>Preview</a>";
                    $durl = Yii::$app->homeUrl."employee/claim/downloadclaim?securekey=$menuid&claimid=$claim_id&reqid=$req_id";
		    $durl = "<a href='$durl' target='_blank'><img width='20' src='".Yii::$app->homeUrl."images/pdf.png' /></a>";
            ?>
            <tr>
                <td><?=$i?></td>
                <td><?=$list['city_name']?></td>
                <td><?=date('d-M-y H:i', strtotime($list['start_date']))?></td>
                <td><?=date('d-M-y H:i', strtotime($list['end_date']))?></td>
                <td><?=number_format($list['advance_amount'], 2)?></td>
                <td><?=number_format($list['claimed_amount'],2)?></td>
                <td><?=$sanctioned_amount?></td>
                <td><?=$list['status']?></td>
                <td><?=$preview?></td>
                <td><?=$durl?></td>
            </tr>	
            <?php $i++;	
                }
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th>No.</th>
                <th>Location</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Advance</th>
                <th>Claimed</th>
                <th>Sanctioned</th>
                <th>Status</th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</div>
