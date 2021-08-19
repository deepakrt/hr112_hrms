<?php
$this->title = 'Group Process of '.$group_name;
use yii\widgets\ActiveForm;
$roleList = Yii::$app->utility->get_roles(NULL);
$proessList = Yii::$app->fts_utility->fts_get_group_process($group_id);

?>
<input type="hidden" id="menuid" value="<?=$menuid?>" />
<style>
    #group_emp_list{height: 120px;}
    .deleteli{color:red;font-weight: bold;text-align: right;}
</style>
<div class="row">
    <div class="col-sm-12">
        <h6><b><u>Add Process</u></b></h6>
    </div>
    <div class="col-sm-4">
        <label>Select Role</label>
        <select name="GroupProcess[role_id]" id="role_id" class="form-control form-control-sm">
            <option value="">Select Role</option>
            <?php
            if(!empty($roleList)){
                foreach($roleList as $r){
                    if($r['role_id'] != '1'){
                        $role_id = Yii::$app->utility->encryptString($r['role_id']);
                        $role = $r['role'];
                        echo "<option value='$role_id'>$role</option>";
                    }
                }
            }
            ?>
        </select>
    </div>
    <div class="col-sm-3">
        <label>Order Number</label>
        <input type="number" placeholder="Order Number" min="1" max="10" name="GroupProcess[order_number]" id="order_number" class="form-control form-control-sm" />
    </div>
    <div class="col-sm-3">
        <br>
        <button type='button' class="btn btn-primary btn-sm" id='add_process_role'>Add</button>
    </div>
</div>
<?php $form = ActiveForm::begin();
$groupid = Yii::$app->utility->encryptString($group_id);
?> 
<input type="hidden" id="group_id" name="group_id" value='<?=$groupid?>'  />
<div class="row" id='list' style="display: none;">
    <div class="col-sm-12">
        <br>
        <h6><b><u>Process List</u></b></h6>
        <ul id="group_emp_list"></ul>
        
        
    </div>
    <div class="col-sm-12 text-center">
        <br><br>
            <span id='process_list_submit'></span>
        </div>
</div>
<?php ActiveForm::end();

if(!empty($proessList)){
?>
<hr>

<h6><b><u>Process Assigned</u></b></h6>
<ul class="processlist">
<?php
foreach($proessList as $process){
    $hy_id = Yii::$app->utility->encryptString($process['hy_id']);
    $group_id = Yii::$app->utility->encryptString($process['group_id']);
    $role_id = Yii::$app->utility->encryptString($process['role_id']);
    $url = Yii::$app->homeUrl."fts/groupmaster/deleteprocessentry?securekey=$menuid&hy_id=$hy_id&group_id=$group_id&role_id=$role_id";
    $role = $process['role'];
    $order_number = $process['order_number'];
    echo "<li><b>Role Name : </b>$role</li>";
    echo "<li><b>Order Number : </b>$order_number</li>";
    echo "<li><a href='$url' class='btn btn-danger btn-sm btn-xs deleteprocessentry'>Delete Entry</a></li>";
}
echo "</ul>";

echo "<div class='text-center'><a href='".Yii::$app->homeUrl."fts/groupmaster?securekey=$menuid' class='btn btn-danger btn-sm'>Back</a></div>";
}
?> 

