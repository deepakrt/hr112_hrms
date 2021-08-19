<?php
use yii\widgets\ActiveForm;
$this->title = "Add DA Entry";
ActiveForm::begin();

?>

<div class="row">
    <div class="col-sm-3">
        <label>Select Period</label>
        <select class="form-control form-control-sm" name="DA[month]" required="">
            <option value="">Select Period</option>
            <option value="<?=Yii::$app->utility->encryptString('1')?>">01-<?=date('Y')?></option>
            <option value="<?=Yii::$app->utility->encryptString('2')?>">07-<?=date('Y')?></option>
        </select>
    </div>
    <div class="col-sm-3">
        <label>Percentage</label>
        <input type="number" class="form-control form-control-sm" name="DA[percentage]" required="" placeholder="Percentage" />
    </div>
    <div class="col-sm-12 ">
        <br>
        <button type="submit" class="btn btn-success btn-sm">Submit</button>
        <a href="<?=Yii::$app->homeUrl?>finance/damaster?securekey=<?=$menuid?>" class="btn btn-danger btn-sm">Cancel</a>
    </div>
</div>
<?php ActiveForm::end();?>

