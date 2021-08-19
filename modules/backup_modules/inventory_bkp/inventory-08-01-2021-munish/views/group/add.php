  <!------ Include the above in your HEAD tag ---------->
<link href="<?=Yii::$app->homeUrl?>css/select2.min.css" rel="stylesheet" />
<script src="<?=Yii::$app->homeUrl?>js/select2.min.js"></script>
<?php
 use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
//echo "<pre>";print_r(Yii::$app->user->identity);die;
?>

 <?php $form = ActiveForm::begin(); ?>
        <div class='row'>
 		 		
		 <div class="col-sm-12"><?= $form->field($model, 'CLASSIFICATION_NAME')->textInput(['placeholder'=>'Group Name', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>

		</div>

    
        <div class="form-group">
			<div class="help-block qmsj"></div>
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary sub_btn']) ?>
        </div>
    <?php ActiveForm::end(); ?>

                   
               
