<?php
//echo "<pre>";print_r(Yii::$app->user->identity->dept_id);
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\EfileMasterCategory;
use app\models\efile_master_project;

$url = Yii::$app->homeUrl."manageproject/ordermaster/update";
?>
<link href="<?=Yii::$app->homeUrl?>css/select2.min.css" rel="stylesheet" />
<script src="<?=Yii::$app->homeUrl?>js/select2.min.js"></script>
<input type="hidden" id="menuid" value="<?=$menuid?>" readonly="" />

<style>
.row.addmorerow {
	width: 90%;
	margin-top: 5px;
}
.cost_category {
	padding: 0 !important;
}
</style>

                    <div class="col-lg-4 prjsession">
                        <?php 
                            $form = ActiveForm::begin(); 
                            $session = Yii::$app->session; 
                        ?>
                            <?= $form->field($model, 'id')->DropDownList(ArrayHelper::map(Yii::$app->projectcls->AllProjects(),'id','projectname'),
                                [
                                    'class' => 'dd_small',
                                    'prompt' => 'All Project',                                
                                    'options' =>
                                        [   
                                            Yii::$app->getRequest()->getQueryParam('id') =>['selected' => FALSE],
                                            $session['prjsession']=>['selected'=>TRUE],
                                        ],
                                    'onchange'=>'
                                        $.post( "'.Yii::$app->urlManager->createUrl('site/plists?id=').'"+$(this).val());'])->Label(FALSE); ?>
                        <?php ActiveForm::end(); ?>
                    </div>                                
                
            