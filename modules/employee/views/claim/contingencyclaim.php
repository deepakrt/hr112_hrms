<?php
$this->title = "Contingency Claim";
$recentApps = Yii::$app->finance->fn_get_contingency('3', NULL, Yii::$app->user->identity->e_id, "Sanctioned,Rejected");
$DraftApps = Yii::$app->finance->fn_get_contingency('3', NULL, Yii::$app->user->identity->e_id, "Draft,Revoked");
$pendingApps = Yii::$app->finance->fn_get_contingency('3', NULL, Yii::$app->user->identity->e_id, "Pending,In-Process");
//echo "<pre>";print_r($data); die;
?>
<style>
    .display{font-size: 12px;}
</style>
<div class="text-right">
    <a href="<?=Yii::$app->homeUrl?>employee/claim/submitclaim?securekey=<?=$menuid?>" class="linkcolor">Start New Claim [Advance Not Taken]</a>
</div>
<h6><b><u> Claims To Submit: </u></b></h6>
<table id="dataTableShow2" class="display" style="width:100%">
    <thead>
        <tr>
            <th>No.</th>
            <th>Purpose</th>
            <th>Project</th>
            <th>Details</th>
            <th>Advance</th>
            <th>Claimed</th>
            <th>Status</th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php 
        if(!empty($DraftApps)){
            $i =1;
            foreach($DraftApps as $d){
            $req_id = Yii::$app->utility->encryptString($d['id']); 
            $employee_code = Yii::$app->utility->encryptString($d['employee_code']); 
            $editUrl = Yii::$app->homeUrl."employee/claim/submitclaim?securekey=$menuid&id=$req_id&code=$employee_code";
            $deleteUrl = Yii::$app->homeUrl."employee/claim/deleteapp?securekey=$menuid&id=$req_id&code=$employee_code";
            $project="N.A.";
            if(!empty($d['project_name'])){
                $project=$d['project_name'];
            }
        ?>
        <tr>
            <td><?=$i?></td>
            <td><?=$d['purpose']?></td>
            <td><?=$project?></td>
            <td><?=$d['details']?></td>
            <td><?=$d['advanced']?></td>
            <td><?=$d['claimed_amt']?></td>
            <td><?=$d['status']?></td>
            <td><a href="<?=$editUrl?>" class="linkcolor">Edit</a></td>
            <td><a href="<?=$deleteUrl?>" class="danger-link">Delete</a></td>
        </tr>
        <?php $i++;	
            }
        }
        ?>
    </tbody>
    <tfoot>
       <tr>
            <th>No.</th>
            <th>Purpose</th>
            <th>Project</th>
            <th>Details</th>
            <th>Advance</th>
            <th>Claimed</th>
            <th>Status</th>
            <th></th>
            <th></th>
        </tr>
    </tfoot>
</table>
<hr>

<h6><b><u> Pending Claims: </u></b></h6>
<table id="dataTableShow1" class="display" style="width:100%">
    <thead>
        <tr>
            <th>No.</th>
            <th>Claim Date</th>
            <th>Purpose</th>
            <th>Project</th>
            <th>Details</th>
            <th>Advance</th>
            <th>Claimed</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        if(!empty($pendingApps)){
            $i =1;
            foreach($pendingApps as $d){
            $req_id = Yii::$app->utility->encryptString($d['id']); 
            $employee_code = Yii::$app->utility->encryptString($d['employee_code']); 
//            $editUrl = Yii::$app->homeUrl."employee/claim/submitclaim?securekey=$menuid&id=$req_id&code=$employee_code";
//            $deleteUrl = Yii::$app->homeUrl."employee/claim/deleteapp?securekey=$menuid&id=$req_id&code=$employee_code";
            $project="N.A.";
            if(!empty($d['project_name'])){
                $project=$d['project_name'];
            }
        ?>
        <tr>
            <td><?=$i?></td>
            <td><?=date('d/m/Y', strtotime($d['submitted_on']))?></td>
            <td><?=$d['purpose']?></td>
            <td><?=$project?></td>
            <td><?=$d['details']?></td>
            <td><?=$d['advanced']?></td>
            <td><?=$d['claimed_amt']?></td>
            <td><?=$d['status']?></td>
        </tr>
        <?php $i++;	
            }
        }
        ?>
    </tbody>
    <tfoot>
       <tr>
            <th>No.</th>
            <th>Claim Date</th>
            <th>Purpose</th>
            <th>Project</th>
            <th>Details</th>
            <th>Advance</th>
            <th>Claimed</th>
            <th>Status</th>
        </tr>
    </tfoot>
</table>

<hr>
<h6><b><u>Recent Applications:</u></b></h6>
<table id="dataTableShow" class="display" style="width:100%">
    <thead>
        <tr>
            <th>No.</th>
            <th>Claim Date</th>
            <th>Purpose</th>
            <th>Project</th>
            <th>Details</th>
            <th>Advance</th>
            <th>Claimed</th>
            <th>Sanctioned</th>
            <th>Status</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php 
        if(!empty($recentApps)){
            $i =1;
            foreach($recentApps as $d){
            $req_id = Yii::$app->utility->encryptString($d['id']); 
            $employee_code = Yii::$app->utility->encryptString($d['employee_code']); 
            $project="N.A.";
            if(!empty($d['project_name'])){
                $project=$d['project_name'];
            }
            $durl = Yii::$app->homeUrl."employee/claim/downloadconticlaim?securekey=$menuid&req_id=$req_id";
            $durl = "<a href='$durl' target='_blank'><img width='20' src='".Yii::$app->homeUrl."images/pdf.png' /></a>";
        ?>
        <tr>
            <td><?=$i?></td>
            <td><?=date('d/m/Y', strtotime($d['submitted_on']))?></td>
            <td><?=$d['purpose']?></td>
            <td><?=$project?></td>
            <td><?=$d['details']?></td>
            <td><?=$d['advanced']?></td>
            <td><?=$d['claimed_amt']?></td>
            <td><?=$d['sanctioned_amt']?></td>
            <td><?=$d['status']?></td>
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
            <th>Claim Date</th>
            <th>Purpose</th>
            <th>Project</th>
            <th>Details</th>
            <th>Advance</th>
            <th>Claimed</th>
            <th>Sanctioned</th>
            <th>Status</th>
            <th></th>
        </tr>
    </tfoot>
</table>

<script>
$(document).ready(function(){
    $('#dataTableShow1, #dataTableShow2').DataTable();
});
</script>