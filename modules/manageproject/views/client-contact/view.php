<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\DetailView;
use frontend\models\ClientDetail;
use frontend\models\ClientDetailSearch;
use frontend\models\ClientContact;


/* @var $this yii\web\View */
/* @var $model app\models\ClientContact */
$this->title = 'Client Contact';
$this->params['breadcrumbs'][] = ['label' => 'Home', 'url' => ['/site/index']];
if ($_SESSION['userrole'] == 'admin' || $_SESSION['userrole'] == 'editor' || $_SESSION['userrole'] == 'premium') {
    $this->params['breadcrumbs'][] = ['label' => 'Proposal Request Dashboard', 'url' => ['site/proposalrequest']];
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
         
        <div class="client-contact-view">        

           <p>
                <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
               <?php if (Yii::$app->user->can('deleteClientContact')): ?>

                        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                'method' => 'post',
                            ],
                        ]) ?>

                    <?php endif ?>                
            </p>
            
            <?= DetailView::widget([
                'model' => $model, 
                'attributes' => [
                    //'id',
                    //'clientid',                     
                    /*[
                        'label' => 'Department Name',
                        'attribute' => 'deptName.deptName',
                    ],
                     * 
                     */
                    [
                        'label'  => 'Department Name',
                        'value' => function($data){     
                            if($data->getDeptName()->exists())
                                return  $data->deptName->deptName;
                            else
                                return '-';
                        }
                    ],

                    //'name',
                    [
                        'label' => 'Conatct Person Name',
                        'attribute' => 'name',
                    ],
                    'phone',
                    'mobile',
                    'email:email',
                    'remarks',
                    //'updatedon',
                    //'userid',
                ],
            ]) ?>
           
        </div>
            
    </div>    
    
</div>
