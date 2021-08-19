<?php

/* @var $this yii\web\View */
use frontend\models\Projects;
use frontend\models\Manpower;
use frontend\models\Manpowermapping;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

$this->title = 'Reports Dashboard';
$this->params['breadcrumbs'][] = ['label' => 'Home', 'url' => ['/site/index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="site-clientdetail  row-eq-height">   
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
                    <?php $form = ActiveForm::begin(); ?>
                        <?php if (isset($_SESSION['prjsession'])) {?>
                            <?= $form->field($model, 'id')->DropDownList(ArrayHelper::map($this->params['project'],'id','projectname'),
                                [
                                    'class' => 'dd_small',
                                    'prompt' => 'All Project',
                                    'options' =>
                                            [   
                                                Yii::$app->getRequest()->getQueryParam('id') =>['selected' => FALSE],
                                                $_SESSION['prjsession']=>['selected'=>TRUE],
                                            ],
                                    'onchange'=>'
                                        $.post( "'.Yii::$app->urlManager->createUrl('site/plists?id=').'"+$(this).val());'])->Label(FALSE); ?>
                        <?php } else { ?>
                            <?= $form->field($model, 'id')->DropDownList(ArrayHelper::map($this->params['project'],'id','projectname'),
                                [
                                    'class' => 'dd_small',
                                    'prompt' => 'All Project',
                                    'options' =>
                                            [   
                                                Yii::$app->getRequest()->getQueryParam('id') =>['selected' => FALSE],
                                                //$_SESSION['prjsession']=>['selected'=>TRUE],
                                            ],
                                    'onchange'=>'
                                        $.post( "'.Yii::$app->urlManager->createUrl('site/plists?id=').'"+$(this).val());'])->Label(FALSE); ?>
                        <?php } ?>
                    <?php ActiveForm::end(); ?>
                <?php } ?>
            </div>
        </div>
        <div class="row proposal-detail">
            <div class="col-lg-12">                    
                <!--<p class="record-heading">LIST OF CLIENTS</p> -->
                <?php  echo \Yii::$app->view->render('@app/views/projects/filemaster');?>                
                <!--    <?php //$model = new ClientDetail(); ?>
                <?//= $this->render('/site/projectsummary') ?>   -->                                      
            </div>
        </div>
    </div>
</div>
    