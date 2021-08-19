<?php
$this->title = "List of Reimbursement Type";
use yii\widgets\ActiveForm;
?>

<?php ActiveForm::begin()?>
<div class="row">
    <div class="col-sm-4">
        <label>Type Name</label>
        <input type="text" name="type_name" class="form-control form-control-sm" placeholder="Type Name" required="" />
    </div>
    <div class="col-sm-4">
        <br>
        <input type="submit" class="btn btn-success btn-sm sl" value="Submit"  />
    </div>
</div>
<hr>
<?php ActiveForm::end()?>

<div class="col-sm-12">
    <table id="dataTableShow" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Sr. No.</th>
                <th>Type</th>
                <th></th>
            </tr>
        </thead>
            <tbody>
                <?php 
                if(!empty($records)){
                    $i =1;
                    foreach($records as $r){
                        $reim_type_id = Yii::$app->utility->encryptString($r['reim_type_id']);
                        $delUrl = Yii::$app->homeUrl."admin/annual_reimbursement/delreimtype?securekey=$menuid&key1=$reim_type_id";
                        $delUrl = "<a href='$delUrl'><img src='".Yii::$app->homeUrl."images/del.gif' /></a>";
                        $name = $r['name'];
                        echo "<tr>
                            <td>$i</td>
                            <td>$name</td>
                            <td>$delUrl</td>
                        </tr>";
                        $i++;
                    }
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Sr. No.</th>
                    <th>Type</th>
                    <th></th>
                </tr>
            </tfoot>
    </table>
    <div class="text-center"><a style="margin-bottom:10px;" href="<?=Yii::$app->homeUrl?>admin/annual_reimbursement?securekey=<?=$menuid?>" class="btn btn-danger btn-sm">Back</a></div>
</div>
