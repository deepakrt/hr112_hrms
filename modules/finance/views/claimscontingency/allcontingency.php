<?php
$this->title ="All Contingency Claims";
?>
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
            <th>Sanctioned</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        if(!empty($info)){
            $i=1;
            foreach($info as $d){
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
            <td><?=  number_format($d['claimed_amt'], 2)?></td>
            <td><?=  number_format($d['sanctioned_amt'], 2)?></td>
        </tr>
        <?php
            }
        }
        ?>
    </tbody>
    <tfoot>
       <tr>
            <tr>
            <th>No.</th>
            <th>Employee Name</th>
            <th>Department</th>
            <th>Claim Date</th>
            <th>Purpose</th>
            <th>Project</th>
            <th>Claimed</th>
            <th>Sanctioned</th>
        </tr>
        </tr>
    </tfoot>
</table>
<div class="text-center">
    <br>
    <?php $Url = Yii::$app->homeUrl."finance/claimscontingency?securekey=$menuid";?>
    <a href="<?=$Url?>" class="btn btn-danger btn-sm">Back</a>
</div>

