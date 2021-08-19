<?php
use yii\widgets\ActiveForm;
$this->title= 'New Menu Map';
$roles = Yii::$app->utility->get_master_roles();
$masterMenu = Yii::$app->utility->get_master_menu(NULL, NULL);
//echo "<pre>";print_r($masterMenu);
?>
<style>
    #menulist {
	list-style: none;
	padding: 0;
	margin-top: 10px;
    }
    #menulist li {
	display: inline-block;
	width: 49%;
	margin-bottom: 10px;
    }
</style>
<div class="row">
    <div class="col-sm-12 text-right">
        <a href="<?=Yii::$app->homeUrl?>admin/menus/?securekey=<?=$menuid?>" class="btn btn-info btn-sm">View Mapped Menus</a>
    </div>
</div>
<hr>
<input type="hidden" id="cur_menu_type" readonly="" />
<?php $form = ActiveForm::begin(['options' => ['id' => 'menusearch', ]]); ?>
<input type="hidden" name="securekey" id="menuid" value="<?=$menuid?>" readonly="" />
<div class="row">
    <div class="col-sm-3">
        <label>Select Role</label>
        <select class="form-control form-control-sm" name="Assign[role_id]" id="assign_role">
            <option value="">Select Role</option>
            <?php if(!empty($roles)){
                foreach($roles as $role){
                    if($role['is_active'] == 'Y'){
                        $id = Yii::$app->utility->encryptString($role['role_id']);
                        $role = $role['role'];
                        echo "<option value='$id'>$role</option>";
                    }
                    
                }
            }?>
        </select>
    </div>
    <div class="col-sm-3">
        <label>Select Top Menu</label>
        <select class="form-control form-control-sm" id="top_menuid">
            <option value="">Select Top Menu</option>
            <?php 
            if(!empty($masterMenu)){
                foreach($masterMenu as $m){
                    if($m['is_active'] == 'Y' AND $m['menu_type'] == 'T'){
                        $mid = Yii::$app->utility->encryptString($m['menuid']);
                        $menu_name = $m['menu_name'];
                        $menu_dsc = $m['menu_dsc'];
                        echo "<option title='$menu_dsc' value='$mid'>$menu_name</option>";
                    }
                }
            }
            ?>
            
        </select>
    </div>
<!--    <div class="col-sm-3">
        <label>Select Menu Type</label>
        <select class="form-control form-control-sm" name="Assign[menu_type]" id="assign_menu_type">
            <option value="">Select Menu Type</option>
            <option value="<?=Yii::$app->utility->encryptString("T")?>">Top</option>
            <option value="<?=Yii::$app->utility->encryptString("L")?>">Left</option>
        </select>
    </div>
    <div class="col-sm-3" id="main_menu_view" style="display: none;">
        <label>Select Main Menu</label>
        <select class="form-control form-control-sm" name="Assign[main_menu]" id="assign_main_list"></select>

    </div>-->
    <div id="showmenulist" class="col-sm-12" style="display: none;" >
        <br>
        <h6><b>Menu List</b></h6>
        <ul id="menulist">
        </ul>
        <div id="assignsubmitbtn" class="text-center"></div>
    </div>
    
</div>
<?php ActiveForm::end(); ?>