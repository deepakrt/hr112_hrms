<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use frontend\models\ClientContact;
use frontend\models\ClientContactSearch;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ClientContactSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

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
        <div class="clientdetail-create">
                           
            <p class="record-heading">Client Contact List</p>
            <?php $form = ActiveForm::begin(['action' =>['clientcontact/index'], 'id' => 'client-contact-index', 'method' => 'post',]);
                $model = new frontend\models\ClientContact;
                $searchModel = new ClientContactSearch();
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);                
            ?>  
            
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'tableOptions' => ['class' => 'table table-responsive table-bordered table-striped table-hover fixed_headersl'],
                'layout' => "{items}\n{pager}",
                //'layout' => "{items}\n{pager}",
                'showFooter'=>FALSE,
                'showHeader' => FALSE,        
                'showOnEmpty'=>true,
                //'emptyCell'=>'-',
                'columns' => [
                    //['class' => 'yii\grid\SerialColumn'],

                    //'id',
                    //'clientid',
                    //'name',
                    //'phone',
                    //'mobile',
                    // 'email:email',
                    // 'remarks',
                    // 'updatedon',
                    // 'userid',
                    [
                        'label'=>'Custom Link',
                        'format'=>'raw',                
                        'value' => function($data){                                        
                            return Html::a($data->name, ['/client-contact/view', 'id' => $data->id]);
                        }
                    ],
                    //['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
            <?php ActiveForm::end(); ?>
           
        </div>
            
    </div> 
    
</div>
            
      
