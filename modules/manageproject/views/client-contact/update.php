<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\ClientContact */

$this->title = 'Client Contact';
$this->params['breadcrumbs'][] = ['label' => 'Home', 'url' => ['/site/index']];
if ($_SESSION['userrole'] == 'admin' || $_SESSION['userrole'] == 'editor' || $_SESSION['userrole'] == 'premium') {
    $this->params['breadcrumbs'][] = ['label' => 'Prestart Dashboard', 'url' => ['site/proposalrequest']];
}
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row-eq-height">
    <div class="col-lg-2 col-sm-2 side-menubar leftbar">
        <div class="tools">TOOLS</div>
        <div class="row breadcrumb">&nbsp;</div>            
        <?php  echo \Yii::$app->view->render('@app/views/layouts/proposalrequest1');?> <br/><br/>            
    </div>    
    <div class="col-lg-10 right-header">
        <div class="row content-bar text-justify" >
            <?php echo \Yii::$app->view->render('@app/views/layouts/mainbar');?>
        </div>
        <div class="row breadcrumb">
            <div class="col-lg-8" style="padding: 0px; margin: 0px;">
                <?php  echo \Yii::$app->view->render('@app/views/layouts/breadcrumb');?>
            </div>
            <div class="col-lg-4 prjsession">
                <?php if ($_SESSION['userrole'] == 'admin' || $_SESSION['userrole'] == 'editor' || $_SESSION['userrole'] == 'premium') {?>
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
                <?php } ?>
            </div>
        </div> 
        <div class="client-contact-update">

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>

        </div>
    </div>
</div>
