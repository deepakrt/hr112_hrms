<?php 
$leftMenus = "";
if(isset($_GET['securekey']) AND !empty($_GET['securekey'])){
    $men = Yii::$app->utility->decryptString($_GET['securekey']);
    if(empty($men)){
        header('Location: '.Yii::$app->homeUrl); 
        exit;
    }
    $d = Yii::$app->utility->get_menus_new($men, NULL);
    $leftMenus = Yii::$app->utility->get_left_menu($d['parent']);
}

$session1 = Yii::$app->session;
$leftActive = $session1->get('activelmenu');
$leftActive = Yii::$app->utility->decryptString($leftActive);
$userroles = Yii::$app->utility->get_rbac_employee_role();
//echo "<pre>";print_r($leftActive);
$leftst = "";
if(empty(Yii::$app->user->identity->e_id)){
    $leftst = "height:250px;";
}
$prRoles = Yii::$app->utility->pr_get_emp_project_roles(Yii::$app->user->identity->e_id);
//                echo "<pre>"; print_r($prRoles); 
?>
<div class="leftside" style='<?=$leftst?>'>
    <?php
    if(!empty(Yii::$app->user->identity->e_id)){?>
    <div class="col-sm-12 text-center ">
        <img  src="<?=Yii::$app->homeUrl.Yii::$app->user->identity->emp_image;?>" />
        <p><?=Yii::$app->user->identity->fullname;?></p>
        <p><?=Yii::$app->user->identity->desg_name?></p>
        <p>Emp ID : <?=Yii::$app->user->identity->e_id?></p>
        <p>Role : <?=Yii::$app->user->identity->role_name?></p>
		<p><span>
        <form action="<?=Yii::$app->homeUrl?>site/switchrole" method="POST" id="roleform">
            <input type="hidden" id="_csrf" name="_csrf" value="<?= Yii::$app->request->csrfToken; ?>" />
            <select class="form-control form-control-sm text-center switchrole" name="role_id"  style="width: 67%;margin: 5px 0 5px 42px;">
                <option value=""> - - Switch Role - - </option>
                <?php 
                //Project Roles
                
                $counts = count($userroles);
                if($counts > 1){
                    foreach($userroles as $role){
                        $role_id = $role['role_id'];
                        $roleid = Yii::$app->utility->encryptString($role_id);
                        $role = $role['role'];
                        $selected = "";
                        if($role_id == Yii::$app->user->identity->role){
                            $selected = "selected=selected";
                        }
                        if($role_id != Yii::$app->user->identity->role){
                            echo "<option $selected value='$roleid'>$role</option>";
                        }
                    }
                }
                
//                if(!empty($prRoles)){
//                    foreach($prRoles as $p){
//                        $roleid = "";
//                        $roleid = Yii::$app->utility->encryptString($p['role_id']);
//                        $name = $p['role'];
//                        if($p['role_id'] == Yii::$app->user->identity->role){
//                            $selected = "selected=selected";
//                        }
//                        if($p['role_id'] != Yii::$app->user->identity->role){
//                            echo "<option $selected value='$roleid'>$name</option>";
//                        }
//                    }
//                }
                ?>
            </select>
            
        </form>
		</span></p>
    </div>
    <div class="row"><hr style="border:1px solid #f2d9a7; margin-bottom:5px; margin-top:3px;width: 100%;"></div>
    <br>
    <?php }?>
    <div class="col-sm-12 text-center">
        <?php 
		// echo "$menuid<pre>"; print_r($leftMenus);
//        echo$controller=Yii::$app->controller->id;
//        echo Yii::$app->controller->action->id;
        if(!empty($leftMenus)){
            foreach($leftMenus as $leftMenu){
                $cls="";
                $menuid = Yii::$app->utility->encryptString($leftMenu['parent']);
//                $leftmenuid = $leftMenu['menuid']."-".Yii::$app->user->identity->role;
                $leftmenuid = $leftMenu['menuid'];
                $leftmenuid = Yii::$app->utility->encryptString($leftmenuid);
                $url = Yii::$app->homeUrl.$leftMenu['menu_url']."?securekey=$leftmenuid&securekeyl=$menuid";
                if($men == $leftMenu['menuid']){
                    $cls="active";
                }
                $n = $leftMenu['menu_name'];
                echo "<a href='$url' class='btn btn-info btn-sm lftlink $cls'>$n</a>";
            }
        }
        ?>
    </div>
</div>
