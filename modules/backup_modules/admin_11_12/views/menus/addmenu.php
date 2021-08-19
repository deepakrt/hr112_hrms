<?php
use yii\widgets\ActiveForm;
$this->title = "Add Menu";
$masterMenu = Yii::$app->utility->get_master_menu(NULL, NULL);
?>
<?php $form = ActiveForm::begin(['options' => ['id' => 'addmenu', ]]); ?>
<div class="row">
    <div class="col-sm-3">
        <label>Menu Name</label>
        <input type="text" name="Menu[menu_name]" id="menu_name" class="form-control form-control-sm" placeholder="Menu Name" required="" />
    </div>
    <div class="col-sm-3">
        <label>Menu Description</label>
        <input type="text" name="Menu[menu_dsc]" id="menu_dsc" class="form-control form-control-sm" placeholder="Menu Description" />
    </div>
    <div class="col-sm-6">
        <label>Menu Url <span style="color:red;font-size: 12px;">Ex: employee/addemployee</span></label>
        <input type="text" name="Menu[menu_url]" onkeypress="return allowOnlyChracter(event)" id="menu_url" class="form-control form-control-sm" placeholder="Menu Url" required="" />
    </div>
</div>
<br>
<div class="row">
    
    <div class="col-sm-3">
        <label>Menu Type</label>
        <select name="Menu[menu_type]" id="menu_type" class="form-control form-control-sm" required="">
            <option value="">Select Menu Type</option>
            <option value="T">Top Menu</option>
            <option value="L">Left Menu</option>
        </select>
    </div>
    <div class="col-sm-3">
        <label>Parent Menu</label>
        <select name="Menu[parent_menu]" id="parent_menu" class="form-control form-control-sm" disabled="" >
            <option value="">Select Parent Menu</option>
            <?php 
            foreach($masterMenu as $menu){
                if($menu['is_active'] == 'Y'){
                    $id = $menu['menuid'];
                    $menu_name = $menu['menu_name'];
                    if($menu['menu_type'] == 'T'){
                        echo "<option value='$id'>$menu_name</option>";
                    }
                }
            }
            ?>
        </select>
    </div>
    <div class="col-sm-3">
        <label>Order Number</label>
        <input type="text" maxlength="2" onkeypress="return allowOnlyNumber(event)" name="Menu[order_number]" id="order_number" class="form-control form-control-sm" placeholder="Order Number" />
    </div>
    <div class="col-sm-12 text-center">
        <br>
        <button type="button" class="btn btn-success btn-sm" id="savemenu">Submit</button>
        <a href="" class="btn btn-danger btn-sm">Reset</a>
    </div>
</div>
<?php ActiveForm::end(); ?>

