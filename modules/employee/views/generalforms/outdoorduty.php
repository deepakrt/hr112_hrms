<?php
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$this->title="OutDoor Duty Slip";

$menuid = "";
if(isset($_GET['securekey']) AND !empty($_GET['securekey']))
{
    $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
}
if(empty($menuid))
{
    header('Location: '.Yii::$app->homeUrl); 
    exit;
}
$menuid = Yii::$app->utility->encryptString($menuid);
$hours= range('00', '23');
$minutes= range('00', '59'); 
//$reasons = Yii::$app->hr_utility->get_reason_entryslip();
//$reasons = ArrayHelper::map($reasons, 'id', 'type');
?>
<br>
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data','class' => 'form-horizontal',]]); ?>
    <div class="row">
        <div class="col-sm-6">
         <?= $form->field($model, 'entry_date')->textInput(['placeholder'=>'DD/MM/YYYY', 'class'=>'form-control form-control-sm', 'maxlength' => true, 'readonly'=>true]) ?>   
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-3">
        <?= $form->field($model, 'entry_time')->dropDownList($hours, ['prompt'=>'Select Hours', 'class'=>'form-control form-control-sm']); ?>
        </div>
        <div class="col-sm-3">
        <?php
            echo Html::label($content = 'Minutes', $for = 'custom_district');
        ?>
            <select id="entrymintus" class="form-control form-control-sm" name="entrymintus" >
            <?php
            foreach ($minutes as $value) 
            {
                $value = sprintf("%02d", $value);
                echo "<option value='$value'>$value</option>";
            }
            ?>
            </select>
            
         </div>   
        <div class="col-sm-3"><?= $form->field($model, 'exit_time')->dropDownList($hours, ['prompt'=>'Select Hours', 'class'=>'form-control form-control-sm']); ?></div>
        <div class="col-sm-3">
        <?php
            echo Html::label($content = 'Minutes', $for = 'custom_district');
        ?>
            <select id="exitmintus" class="form-control form-control-sm" name="exitmintus" >
            <?php
            foreach ($minutes as $value) 
            {
                $value = sprintf("%02d", $value);
                echo "<option value='$value'>$value</option>";
            }
            ?>
            </select>
        </div>
    </div>
    <div class="row" >
        <div class="col-sm-6">
        <?= $form->field($model, 'reason')->textarea([ 'class'=>'form-control form-control-sm']); ?>
        </div>
        <div class="col-sm-6">
            
        </div>
    </div>
    <div class="col-sm-12 text-center">
        <button type="submit" id="" class="btn btn-success btn-sm sl">Submit</button>
        <a href="<?=Yii::$app->homeUrl?>employee/information?securekey=<?=$menuid?>&tab=family" class="btn btn-danger btn-sm">Cancel</a>
     </div>
<?php ActiveForm::end(); ?>
<hr>
<script>
$(document).ready(function() 
{
    $('#ottdrentry').DataTable({
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
    });
} );
</script>
<div class="row">
    <br>
    <div class="text-left">
        <b><h6>Recent Applications:</h6></b>
    </div>
    <br>
    <table class="display" style="width:100%" id="ottdrentry">
        <thead>
            <th>No</th>
            <th>Application Date</th>
            <th>Submitted Date</th>
            <th>In Time </th>
            <th>Out Time</th>
            <th>Reason</th>
            <th>Status</th>
        </thead>
        <tbody>
        <?php 
        $param_type="Outdoor Duty";
        $param_auth_type="E";
        $param_status=null;
        $param_e_id=Yii::$app->user->identity->e_id;
        $info=Yii::$app->hr_utility->hr_view_general_form_detail($param_auth_type,$param_e_id,$param_status,$param_type);
        if(!empty($info))
        {
            $i=1;
            foreach($info as $key=>$infoval)
            { 
                $No= $i;
                $entry_date= $infoval['entry_date'];
                $entry_date=date("d-M-Y", strtotime($entry_date));
                $submitted_on= $infoval['submitted_on'];
                $submitted_on=date("d-M-Y", strtotime($submitted_on));
                $entry_time= $infoval['entry_time'];
                $reason= $infoval['reason'];
                $exit_time= $infoval['exit_time'];
                $status= $infoval['status'];
                echo "
                    <tr>
                        <td>$No</td>
                        <td>$entry_date</td>
                        <td>$submitted_on</td>
                        <td>$entry_time</td>
                        <td>$exit_time</td>
                        <td>$reason</td>
                        <td>$status</td>
                        </tr>";
                $i++;
            }
        }
        ?>
            </tbody>
    </table>   
</div>