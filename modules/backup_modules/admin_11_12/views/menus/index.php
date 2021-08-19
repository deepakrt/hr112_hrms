<?php
use yii\widgets\ActiveForm;
$this->title= 'All Mapped Menus';
?>
<div class="row">
    <div class="col-sm-12 text-right">
        <a href="<?=Yii::$app->homeUrl?>admin/menus/mastermenus?securekey=<?=$menuid?>" class="btn btn-outline-info btn-sm">View All Menus</a>
        <a href="<?=Yii::$app->homeUrl?>admin/menus/newmapping?securekey=<?=$menuid?>" class="btn btn-outline-success btn-sm">Click to Assign Menu</a>
    </div>
</div>
<hr>
<h6><b>List of Mapped Menus</b></h6>
<table id="dataTableShow" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Sr.</th>
            <th>Role Name</th>
            <th>Description</th>
            <th>Is Active</th>
            <th>View Menus</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $data = Yii::$app->utility->get_master_roles();
        if(!empty($data)){
            $i=1;
            foreach($data as $d){
                $role_id = Yii::$app->utility->encryptString($d['role_id']);
                $role = $d['role'];
                $desc = $d['desc'];
                $is_active = $d['is_active'];
                $url = Yii::$app->homeUrl."admin/menus/viewmenus?securekey=$menuid&key=$role_id";
                $url = "<a href='$url' class='linkcolor'>View Menus</a>";
                echo "<tr>
                    <td>$i</td>
                    <td>$role</td>
                    <td>$desc</td>
                    <td>$is_active</td>
                    <td>$url</td>
                </tr>";
                $i++;
            }
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <th>Sr.</th>
            <th>Role Name</th>
            <th>Description</th>
            <th>Is Active</th>
            <th>View Menus</th>
        </tr>
    </tfoot>
</table>