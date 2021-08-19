<?php
$this->title= 'Add New Reward';
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<link href="<?=Yii::$app->homeUrl?>css/select2.min.css" rel="stylesheet" />
<script src="<?=Yii::$app->homeUrl?>js/select2.min.js"></script>
<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
<div class="row">
    <div class="col-sm-8"><?= $form->field($model, 'name')->textInput(['placeholder'=>'Reward Name', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>
    <div class="col-sm-8"><?= $form->field($model, 'description')->textArea(['placeholder'=>'Description', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>

    <div class="form-group col-sm-8 field-inventory-category required has-success" data-select2-id="8">
        <label class="control-label" for="inventory-category">Category</label>
        <select id="reward_type_id" class="js-example-basic-multiple form-control form-control-sm" name="RewardMaster[reward_type_id]">
            <option value="">--Select--</option>
            <?php foreach ($category as $cat) { ?>
                <option value="<?= $cat['id']; ?>"><?= $cat['name']; ?></option>
            <?php } ?>
        </select>
    </div>
    <?= $form->field($model, 'reward_sub_cat', ['options' => ['class' => 'form-group col-sm-8']])->dropDownList(['' => '--Select--'], ['class' => 'js-example-basic-multiple form-control form-control-sm']); ?>
    
    
    <div class="col-sm-8"><?= $form->field($model, 'is_active')->dropDownList([ 'Y' => 'Yes', 'N' => 'No',], ['class'=>'form-control form-control-sm', 'prompt' => 'Select Is Active']) ?></div>
    <div class="col-sm-12 text-center">
        <button type="submit" class="btn btn-success btn-sm sl">Submit</button>
        <a href="<?=Yii::$app->homeUrl?>admin/reward?securekey=<?=$menuid?>" class="btn btn-danger btn-sm">Cancel</a>
    </div>
</div>
<?php ActiveForm::end(); ?>

<script>
    var BASE_URL='<?=Yii::$app->homeUrl?>';
    var securekey='<?=$menuid?>';
    $(function(){
       
            $('#reward_type_id').change(function(){
                    var cat_id= $(this).val();
                 
                    if(cat_id==''){$('#reward_sub_cat').html('');return false;}
                        $.ajax({
                                url:BASE_URL+'admin/reward/get_subcat_code?securekey='+securekey,
                                type:'POST',
                                data:{cat_id:cat_id},
                                datatype:'json',
                                success:function(data){
                                  
                                        if(data!=0){
                                                $('#rewardmaster-reward_sub_cat').html(data);
                                        }else{
                                                 alert('Category can not be blank.');
                                        }
                                }
                              });
                    });
            }); 

</script>
