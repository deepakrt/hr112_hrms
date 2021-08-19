<?php
$this->title = "View Resources";
$e = Yii::$app->utility->get_employees($proInfo['manager_emp_id']);

?>
<div class="row">
    <div class="col-sm-12">
        <h5>Project Information:-</h5>
        <table class="table table-bordered">
            <tr>
                <td><label>Project Name : </label> <?=$proInfo['project_name']?></td>
                <td><label>Project Type : </label> <?=$proInfo['project_type']?></td>
                <td><label>Project Cost : </label> Rs. <?=$proInfo['project_cost']?></td>
            </tr>
            <tr>
                <td><label>State Date : </label> <?=date('d-m-Y', strtotime($proInfo['start_date']))?></td>
                <td><label>End Date : </label> <?=date('d-m-Y', strtotime($proInfo['end_date']))?></td>
                <td><label>No. of Working Days : </label> <?=$proInfo['num_working_days']?></td>
            </tr>
            <tr>
                <td><label>Technology : </label> <?=$proInfo['technology_used']?></td>
                <td><label>Project Manager : </label> <?=$e['fullname']?></td>
                <td><label>Department : </label> <?=$e['dept_name']?></td>
            </tr>
        </table>
    </div>
</div>
<hr>
<div class="col-sm-12">
    <table class="table table-bordered ">
        <tr>
            <th>Role Name</th>
            <th>Emp ID</th>
            <th>Emp Name</th>
            <th>Responsibility</th>
            <th></th>
        </tr>
        <?php 
        if(!empty($mems)){
            
            foreach($mems as $m){
                $role = $m['role'];
                $res = $m['responsibility'];
                $team_member = $m['team_member'];
                $e = Yii::$app->utility->get_employees($m['team_member']);
                $team_id = Yii::$app->utility->encryptString($m['team_id']);
                $project_id = Yii::$app->utility->encryptString($m['project_id']);
                $name = $e['fullname'].", ".$e['desg_name'];
                $url = Yii::$app->homeUrl."manageproject/resources/removeresources?securekey=$menuid&key=$team_id&key1=$project_id";
                $url = "<a href='$url' class='removeRecourse' data-key='$role' data-key1='$name'><img src='".Yii::$app->homeUrl."images/del.gif' /></a>";
                echo "<tr>
                    <td>$role</td>
                    <td>$team_member</td>
                    <td>$name</td>
                    <td>$res</td>
                    <td>$url</td>
                </tr>";
            }
        } 
        ?>
    </table>
</div>
<hr>
<div class="text-center">
    <a href="<?=Yii::$app->homeUrl?>manageproject/resources?securekey=<?=$menuid?>" class="btn btn-danger btn-sm">Back</a>
</div>