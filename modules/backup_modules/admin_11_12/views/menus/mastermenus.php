<?php
$this->title = "Menus Master";
?>
<div class="row">
    <div class="col-sm-12 text-right">
        <a href="<?=Yii::$app->homeUrl?>admin/menus/addmenu?securekey=<?=$menuid?>" class="btn btn-outline-info btn-sm">Add Menu</a>
        <a href="<?=Yii::$app->homeUrl?>admin/menus/newmapping?securekey=<?=$menuid?>" class="btn btn-outline-success btn-sm">Click to Assign Menu</a>
    </div>
</div>
<hr>
<h6><b>List of Mapped Menus</b></h6>
<table id="dataTableShow" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Sr.</th>
            <th>Menu Name</th>
            <th>Menu Url</th>
            <th>Description</th>
            <th>Menu Type</th>
            <th>Is Active</th>
            <th>Edit</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        if(!empty($menus)){
            $i=1;
            foreach($menus as $d){
                $master_menuid = Yii::$app->utility->encryptString($d['menuid']);
                $menu_type = "";
                if($d['menu_type'] == 'T'){
                    $menu_type = "Top";
                }elseif($d['menu_type'] == 'L'){
                    $menu_type = "Left";
                }
                $url = Yii::$app->homeUrl."admin/menus/updatemastermenu?securekey=$menuid&key=$master_menuid";
                $url = "<a href='$url' class='linkcolor'><img src='".Yii::$app->homeUrl."images/edit.gif' /></a>";
                echo "<tr>
                    <td>$i</td>
                    <td>".$d['menu_name']."</td>
                    <td>".$d['menu_url']."</td>
                    <td>".$d['menu_dsc']."</td>
                    <td>$menu_type</td>
                    <td>".$d['is_active']."</td>
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
            <th>Menu Name</th>
            <th>Menu Url</th>
            <th>Description</th>
            <th>Menu Type</th>
            <th>Is Active</th>
            <th>Edit</th>
        </tr>
    </tfoot>
</table>
