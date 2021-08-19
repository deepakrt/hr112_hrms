<?php
$this->title= 'Leaves Chart';
$url =Yii::$app->homeUrl."admin/manageleaves/addnewentry?securekey=$menuid";
?>
<div class="text-right">
    <a href="<?=$url?>" class="btn btn-success btn-sm mybtn">Add New Entry</a>
</div>
<hr>
<table id="dataTableShow" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Sr.</th>
            <th>Emp. Type</th>
            <th>Year</th>
            <th>Leave Type</th>
            <th>Session Type</th>
            <th>Total Leaves</th>            
            <th>Is Active</th>            
            <th>Last Updated</th>            
            <th>Added On</th>            
        </tr>
    </thead>
    <tbody>
    <?php
    if(!empty($leaves)){
        $i=1;
        foreach($leaves as $leave){
//            $lc_id = Yii::$app->utility->encryptString($leave['lc_id']);
//            $viewUrl = Yii::$app->HomeUrl."admin/manageservicedetail/view?securekey=$menuid&securecode=$code";
            $employee_type = $leave['employee_type'];
            $year = $leave['year'];
            $desc = $leave['desc'];
            $sessiontype = $leave['sessiontype'];
            $leave_count = $leave['leave_count'];
            $updtd ="-";
            if(!empty($leave['last_updated'])){
                $updtd = date('d-m-Y H:i:s', strtotime($leave['last_updated']));
            }
            $notact = "";
            $is_active = "Yes";
            if($leave['is_active'] == 'N'){
                $is_active = "<span >No</span>";
                $notact = "style='background-color:#f7e2dd;'";
            }
        ?>
        <tr <?=$notact?> >
            <td><?=$i?></td>
            <td><?=$employee_type?></td>
            <td><?=$year?></td>
            <td><?=$desc?></td>
            <td><?=$sessiontype?></td>
            <td><?=$leave_count?></td>
            <td><?=$is_active?></td>
            <td><?=$updtd?></td>
            <td><?=date('d-m-Y H:i:s', strtotime($leave['created_date']));?></td>
                
        </tr>
        <?php $i++;
        }
    }
    ?>
    </tbody>
    <tfoot>
        <tr>
            <th>Sr.</th>
            <th>Emp. Type</th>
            <th>Year</th>
            <th>Leave Type</th>
            <th>Session Type</th>
            <th>Total Leaves</th>            
            <th>Is Active</th>            
            <th>Last Updated</th>            
            <th>Added On</th>            
        </tr>
    </tfoot>
</table>