<?php
$this->title = "Add Technology";
use yii\widgets\ActiveForm;
$url =Yii::$app->homeUrl."admin/manageleaves/addnewentry?securekey=$menuid";
?>
<style>
    .col-sm-3{margin-bottom: 15px;}
</style>

<hr>
<input type="hidden" id='menuid' value='<?=$menuid?>' />
<?php $form = ActiveForm::begin(['options'=>['id'=>'addtechnology']]); ?>
<div class="row">
    <div class="col-sm-3">
        <label>Technology Name</label>
         <input type='text'  id="technology_name" name="Technology[technology_name]" class="form-control form-control-sm"  placeholder="Technology Name" />
    </div>
    
    <div class="col-sm-3">
        <label>Technology Code</label>
         <input type='text'  id="technology_code" name="Technology[technology_code]" class="form-control form-control-sm" placeholder="Technology Name" />
    </div>
    
    
    <div class="col-sm-12">
        
        <div class="text-center">
            <button type="submit" class="btn btn-success btn-sm sl" id="addtechnologySubmit">Submit</button>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>
