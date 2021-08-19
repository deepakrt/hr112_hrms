<?php
$this->title="List Leaves Type";
$types = Yii::$app->hr_utility->hr_get_master_leave_type(NULL);
use yii\widgets\ActiveForm;
?>
<?php $form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-sm-4">
        <label>Label</label>
        <input type="text" name="LeaveType[label]" class="form-control form-control-sm" placeholder="Enter Label" required="" />
    </div>
    <div class="col-sm-6">
        <label>Description</label>
        <input type="text" name="LeaveType[description]" class="form-control form-control-sm" placeholder="Enter Description" required="" />
    </div>
    <div class="col-sm-2">
        <br>
        <input type="submit" class="btn btn-success btn-sm sl" value="Add Leave Type" />
    </div>
</div>
<?php ActiveForm::end(); ?>
<hr>
<div class="row">
    <div class="col-sm-12">
        <table id="dataTableShow" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>Sr.</th>
                    <th>Label</th>
                    <th>Description</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if(!empty($types)){
                    $i=1;
                    foreach($types as $type){
                        $lt_id = Yii::$app->utility->encryptString($type['lt_id']); 
                        $delUrl=Yii::$app->homeUrl."admin/manageleaves/deleteleavestype?securekey=$menuid&ltid=$lt_id";
                        $delUrl = "<a href='$delUrl' class='deltype'><img src='".Yii::$app->homeUrl."images/del.gif' /></a>";
                    ?>

                <tr>
                    <td><?=$i?></td>
                    <td><?=$type['label']?></td>
                    <td><?=$type['desc']?></td>
                    <td><?=$delUrl?></td>
                </tr>
                <?php  $i++;  }
                }
                ?>
            </tbody>
            <tfoot>
                <th>Sr.</th>
                <th>Label</th>
                <th>Description</th>
                <th>Delete</th>
            </tfoot>
        </table>
    </div>
</div>
    
<script>
    $(document).ready(function(){
        $('.deltype').click(function(){
            if(confirm('Are you sure want to Delete Selected Type?')){
                return true;
            }
            return false;;
        });
    });
</script>