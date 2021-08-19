<?php
$this->title = "Manage Roles";
use yii\widgets\ActiveForm;
?> 
<?php ActiveForm::begin()?>
<div class="row">
    <input type="hidden" name="role_id" id="role_id" />
    <div class="col-sm-12"><h6><b>Add New Role</b></h6></div>
    <div class="col-sm-3">
        <label>Role Name</label>
        <input type="text" name="role_name" id="role_name" class="form-control form-control-sm" placeholder="Role Name" required="" />
    </div>
    <div class="col-sm-4">
        <label>Description</label>
        <input type="text" name="desc" id="desc" class="form-control form-control-sm" placeholder="Description" required="" />
    </div>
    <div class="col-sm-3">
        <label>Select Is Active</label>
        <select name="is_active" id="is_active" class="form-control form-control-sm" required="" >
            <option value="Y">Yes</option>
            <option value="N">No</option>
        </select>
    </div>
    <div class="col-sm-2">
        <br>
        <input type="submit" class="btn btn-success btn-sm sl" value="Submit"/>
    </div>
</div>
<hr>
<?php ActiveForm::end()?>
<table id="dataTableShow" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Sr. No.</th>
            <th>Role ID</th>
            <th>Role Name</th>
            <th>Description</th>
            <th>Is Active</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php 
        if(!empty($roles)){
            $i=1;
            foreach($roles as $r){
                $roleid = Yii::$app->utility->encryptString($r['role_id']);
                $delurl = "<a href='javascript:void(0)' data-key='$roleid' data-role='".$r['role']."' data-desc='".$r['desc']."' data-is_active='".$r['is_active']."' class='linkcolor updaterole'>Update</a>";
                ?>
        <tr>
            <td><?=$i?></td>
            <td><?=$r['role_id']?></td>
            <td><?=$r['role']?></td>
            <td><?=$r['desc']?></td>
            <td><?=$r['is_active']?></td>
            <td><?=$delurl?></td>
        </tr>
        <?php
        $i++;
            }
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <th>Sr. No.</th>
            <th>Role ID</th>
            <th>Role Name</th>
            <th>Description</th>
            <th>Is Active</th>
            <th></th>
        </tr>
    </tfoot>
</table>

