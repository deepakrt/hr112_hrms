<?php
$this->title = "View Menus";
$role_name ="";

$roles = Yii::$app->utility->get_master_roles($role_id);
//echo "<pre>";print_r($allmenus); die;
?>
<style>
    .display{font-size: 14px;}
</style>
<h6><b><?=$roles['role']?> Menus</b></h6>
<table id="dataTableShow" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Sr.</th>
            <th>Top Menu Name</th>
            <th>Menu Name</th>
            <th>Description</th>
            <th>Menu Url</th>
            <th>Order</th>
            <!--<th></th>-->
        </tr>
    </thead>
    <tbody>
        <?php 
        
        if(!empty($allmenus)){
            $i=1;
//            echo "<pre>";print_r($allmenus);
            $role_id = Yii::$app->utility->encryptString($role_id);
            foreach($allmenus as $d){
                if(!empty($d['parent'])){
                    $l_menuid = Yii::$app->utility->encryptString($d['menuid']);
                    $topmenu = Yii::$app->utility->get_menus_new($d['parent'], NULL);
                    $topmenu = $topmenu['menu_name'];
                    $menu_name = $d['menu_name'];
                    $menu_dsc = $d['menu_dsc'];
                    $menu_url = $d['menu_url'];
                    $order = $d['order'];
                    if($d['is_active'] == 'N'){
                        $key1 = Yii::$app->utility->encryptString('Y');
                        $url = Yii::$app->homeUrl."admin/menus/updatemenustatus?securekey=$menuid&key=$role_id&key1=$key1";
                        $url = "<a href='$url' class='linkcolor' title='Click to Active Menu'><img style='width: 20px;' src='".Yii::$app->homeUrl."images/checkmark.png' /></a>";
                    }else{
                        $key1 = Yii::$app->utility->encryptString('N');
                        $url = Yii::$app->homeUrl."admin/menus/updatemenustatus?securekey=$menuid&key=$role_id&key1=$key1";
                        $url = "<a href='$url' class='linkcolor' title='Click to In-Active Menu'><img style='width: 20px;' src='".Yii::$app->homeUrl."images/del.gif' /></a>";
                        
                    }
                    echo "<tr>
                        <td>$i</td>
                        <td><b>$topmenu</b></td>
                        <td>$menu_name</td>
                        <td>$menu_dsc</td>
                        <td>$menu_url</td>
                        <td>$order</td>
                        
                    </tr>";
                    $i++;
                }            
            }
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <th>Sr.</th>
            <th>Top Menu Name</th>
            <th>Menu Name</th>
            <th>Description</th>
            <th>Menu Url</th>
            <th>Order</th>
            <!--<th></th>-->
        </tr>
    </tfoot>
</table>


