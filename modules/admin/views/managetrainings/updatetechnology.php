<?php
$this->title = "Update Technology";
use yii\widgets\ActiveForm;
$url =Yii::$app->homeUrl."admin/managetrainings/updatetechnology?securekey=$menuid";
?>
<style>
    .col-sm-3{margin-bottom: 15px;}
</style>

<hr>

<?php $form = ActiveForm::begin(['options'=>['id'=>'updatetechnology']]); ?>
<?php 



foreach($technologies as $technology){


    ?>
<input type="hidden" id='menuid' value='<?=$menuid?>' />
<div class="row">
    <div class="col-sm-3">
        <label>Technology Name</label>
         <input type='text'  id="technology_name" name="Technology[technology_name]" class="form-control form-control-sm"  placeholder="Technology Name" value="<?php echo $technology['technology_name'];?>" />
    </div>
    
    <div class="col-sm-3">
        <label>Technology Code</label>
         <input type='text'  id="technology_code" name="Technology[technology_code]" class="form-control form-control-sm" placeholder="Technology Name" value="<?php echo $technology['technology_code'];?>" />
    </div>
    
    
    <div class="col-sm-12">
        
        <div class="text-center">
            <button type="submit" class="btn btn-success btn-sm sl" id="updatetechnologySubmit">Submit</button>
        </div>
    </div>
</div>
<?php } ?>
<?php ActiveForm::end(); ?>
