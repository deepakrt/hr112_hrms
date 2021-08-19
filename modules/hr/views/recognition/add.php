<?php
$this->title= 'Add New Recognition';
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<link href="<?=Yii::$app->homeUrl?>css/select2.min.css" rel="stylesheet" />
<script src="<?=Yii::$app->homeUrl?>js/select2.min.js"></script>
<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
<div class="row">
    <div class="col-sm-8"><?= $form->field($model, 'name')->textInput(['placeholder'=>'Name', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>
    <div class="col-sm-8"><?= $form->field($model, 'description')->textArea(['placeholder'=>'Description', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>
    <div class="col-sm-8"><?= $form->field($model, 'reco_type')->dropDownList([ '1' => 'Bonus', '2' => 'Appreciation Letter', '3' => 'Verbal Appreciation',], [ 'class'=>'form-control form-control-sm', 'prompt' => 'Select Type']) ?></div>
     <div class="col-sm-8"><?= $form->field($model, 'from_department')->textInput(['placeholder'=>'Department Name', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>
      <div class="col-sm-8"><?= $form->field($model, 'from_type')->dropDownList([ '1' => 'Internal', '2' => 'External',], [ 'class'=>'form-control form-control-sm', 'prompt' => 'Recognition From']) ?></div>
   
    
    
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
