<?php
$this->title ="Contingency Claims";
//echo "<pre>";print_r($claimApps);die;
?>
<div class="text-right">
    <a href="<?=Yii::$app->homeUrl?>finance/claimscontingency/allcontingency?securekey=<?=$menuid?>" class="linkcolor">View All Contingency Claims</a>
</div>
<hr>
<h6><b><i>Pending Contingency Claims</i></b></h6>
<table id="dataTableShow" class="display" style="width:100%">
    <thead>
        <tr>
            <th>No.</th>
            <th>Employee Name</th>
            <th>Department</th>
            <th>Claim Date</th>
            <th>Purpose</th>
            <th>Project</th>
            <th>Claimed</th>
            <th>Status</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php 
        if(!empty($claimApps)){
            $i =1;
            foreach($claimApps as $d){
            $req_id = Yii::$app->utility->encryptString($d['id']); 
            $employee_code = Yii::$app->utility->encryptString($d['employee_code']); 
            $viewUrl = Yii::$app->homeUrl."finance/claimscontingency/view?securekey=$menuid&id=$req_id&code=$employee_code";
            $project="N.A.";
            if(!empty($d['project_name'])){
                $project=$d['project_name'];
            }
            $name = $d['fullname'].", <br>".$d['desg_name'];
        ?>
        <tr>
            <td><?=$i?></td>
            <td><?=$name?></td>
            <td><?=$d['dept_name']?></td>
            <td><?=date('d/m/Y', strtotime($d['submitted_on']))?></td>
            <td><?=$d['purpose']?></td>
            <td><?=$project?></td>
            <td><?=$d['claimed_amt']?></td>
            <td><?=$d['status']?></td>
            <td><a href="<?=$viewUrl?>" class="linkcolor">View / Action</a></td>
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
            <th>Claim Date</th>
            <th>Purpose</th>
            <th>Project</th>
            <th>Claimed</th>
            <th>Status</th>
            <th></th>
        </tr>
    </tfoot>
</table>