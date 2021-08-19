<?php
$this->title="Leave Details";
$lcards = Yii::$app->hr_utility->get_employee_leaves(Yii::$app->user->identity->e_id);
?>
<div class="col-sm-12">
<!--    <br>
    <div class="text-right">
        <?php 
//        if(!empty($lcards)){
        ?>
        <a href="<?=Yii::$app->homeUrl?>employee/leave/applyforleave?securekey=<?=$menuid?>" class="btn btn-success btn-sm mybtn">Apply for Leave</a>
        <?php 
//        }
        ?>
    </div>
    <br>-->
    <table class="table table-bordered">
        <tr>
            <th>Leave Type</th>
            <th>Balance</th>
            <th>Pending</th>
            <th>Available</th>
            <th>Leave Card</th>
        </tr>
        <?php 
//        echo "<pre>";print_r($lcards);
        if(!empty($lcards)){
            foreach($lcards as $lcard){                
                $desc= $lcard['desc'];
                $pending= $lcard['pending_leaves'];
                $balance = $lcard['balance_leaves']+$pending;
                $avail = number_format($lcard['balance_leaves'],1);
                $balance = number_format($balance,1);
                $lt_id = Yii::$app->utility->encryptString($lcard['lt_id']);
                $view=Yii::$app->homeUrl."employee/leave/viewleavecard?securekey=$menuid&key=$lt_id";
                echo "
                    <tr>
                        <td>$desc</td>
                        <td>$balance</td>
                        <td>$pending</td>
                        <td>$avail</td>
                        <td><a href='$view' target='_blank'><img src=".Yii::$app->homeUrl.'images/view.png'." style='width: 23px;'/></a></td>
                    </tr>
                ";
            }
        }else{
            echo "<tr><td colspan='5' align='center'><b>No Leaves Record Found</b></td></tr>";
        }
        ?>
    </table>
</div>
<?php 
if(!empty($lcards)){
    
$leaves = Yii::$app->hr_utility->hr_get_leaves('A', Yii::$app->user->identity->e_id, NULL, "Draft");

if(!empty($leaves)){   ?>
<div class="col-sm-12">
    <h6><b>Draft Applications:-</b></h6>
    <table class="table table-bordered">
        <tr>
            <th>From Date</th>
            <th>Till Date</th>
            <th>App. Date</th>
            <th>Leave Types</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
        <?php 
        foreach($leaves as $l){
            $to = $from = "-";
            if(!empty($l['leave_from'])){
                $from = date('d-m-Y', strtotime($l['leave_from']));
                $to = date('d-m-Y', strtotime($l['leave_to']));
            }
            
            $applied_date = date('d-m-Y', strtotime($l['applied_date']));
            $req = Yii::$app->hr_utility->hr_get_leaves('R', Yii::$app->user->identity->e_id, $l['leave_app_id'], "Draft");
//            echo "<pre>";print_r($req);die;
            $ltype = "";
            if(!empty($req)){
                foreach($req as $r){
                    $ltype .= $r['label'].'['.$r['totaldays'].'],';
                }
                $ltype = rtrim($ltype, ',');
            }
            $appid = Yii::$app->utility->encryptString($l['leave_app_id']);
            $editUrl = Yii::$app->homeUrl."employee/leave/applyforleave?securekey=$menuid&key=$appid";
            $editUrl = "<a href='$editUrl'><img src='".Yii::$app->homeUrl."images/edit.gif' /></a>";
            $A = Yii::$app->utility->encryptString('A');
            $deleteUrl = Yii::$app->homeUrl."employee/leave/applicationaction?securekey=$menuid&key=$appid&action=$A";
            $deleteUrl = "<a href='$deleteUrl' class='checktapptype' data-type='A' title='Delete Leave Application'><img src='".Yii::$app->homeUrl."images/del.gif' /></a>";
            echo "<tr>
                <td>$from</td>
                <td>$to</td>
                <td>$applied_date</td>
                <td>$ltype</td>
                <td>$editUrl</td>
                <td>$deleteUrl</td>
            </tr>";
        }
        ?>
    </table> 
</div>   
<?php }

}
?>