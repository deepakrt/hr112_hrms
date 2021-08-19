<?php
use yii\widgets\ActiveForm;
$this->title= 'Manage Logins';
$menuid = "";
if(isset($_GET['securekey']) AND !empty($_GET['securekey'])){
    $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
}
if(empty($menuid)){
    header('Location: '.Yii::$app->homeUrl); exit;
}
$menuid = Yii::$app->utility->encryptString($menuid);
$lists = Yii::$app->utility->get_rbac_employee();
//echo "<pre>";print_r($lists); die;
?>
<br>
<table id="dataTableShow" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Sr. No.</th>
            <th>Emp. Code</th>
            <th>Emp. Name</th>
            <th>Department</th>
            <th>Username</th>
            <th>Role</th>
            <th>Is active</th>
            <th></th>
        </tr>
    </thead>
	<tbody>
	    <?php 
            if(!empty($lists)){
                $i=1;
                foreach($lists as $list){
                    $employee_code = $list['employee_code'];
                    $fullname = $list['fullname'];
                    $desg_name = $list['desg_name'];
                    $dept_name = $list['dept_name'];
                    $username = Yii::$app->utility->replaceSpecialChar($list['username']);
                    $role = $list['role'];
                    $is_active = "-";
                    $map_id = Yii::$app->utility->encryptString($list['map_id']);
                    if($list['is_active'] == 'Y'){ 
                        $status = Yii::$app->utility->encryptString("N");
                        $is_active = "Yes";
                        $link= Yii::$app->homeUrl."admin/manageelogins/edit?securekey=$menuid&map_key=$map_id&status=$status";
                        $link = "<a class='inactiveemp' href='$link'><img src='".Yii::$app->homeUrl."images/inactive.png' width=25px /></a>";
                    }elseif($list['is_active'] == 'N'){
                        $status = Yii::$app->utility->encryptString("Y");
                        $is_active = "No";
                        $link= Yii::$app->homeUrl."admin/manageelogins/edit?securekey=$menuid&map_key=$map_id&status=$status";
                        $link = "<a class='activeemp' href='$link'><img src='".Yii::$app->homeUrl."images/active.png' width=25px /></a>";
                    }
                ?>
            <tr>
                <td><?=$i?></td>
                <td><?=$employee_code?></td>
                <td><?=$fullname?>, <?=$desg_name?></td>
                
                <td><?=$dept_name?></td>
                <td><?=$username?></td>
                <td><?=$role?></td>
                <td><?=$is_active?></td>
                <td><?=$link?></td>
            </tr>
            <?php 
            $i++;
                }
            }
            ?>
	</tbody>
	<tfoot>
            <th>Sr. No.</th>
            <th>Employee Code</th>
            <th>Employee Name</th>
            <th>Designation</th>
            <th>Department</th>
            <th>Username</th>
            <th>Role</th>
            <th>Is active</th>
            <th></th>
	</tfoot>
</table>