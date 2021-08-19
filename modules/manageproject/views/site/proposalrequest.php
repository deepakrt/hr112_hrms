<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Html\ListGroup;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use frontend\models\ClientDetail;
use frontend\models\ClientContact;
use yii\grid\GridView;
//use dosamigos\grid\GridView;
use yii\web\JsExpression;
use frontend\models\PremeetingsSearch;
use yii\db\Expression;
use yii\helpers\Url;
use frontend\models\ProposalSearch;
use yii\data\Pagination ;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use frontend\models\Cdacdept;

    $this->title = 'Pre-Project Dashboard';
    $this->params['breadcrumbs'][] = ['label' => 'Home', 'url' => ['/site/index']];
    $this->params['breadcrumbs'][] = $this->title;

?>
<!--<script language="javascript">    
   jQuery( document ).ready(function($) {
        //$("#d1").css({"height": screen.height+"px"});
            $(".row-eq-height").css("height", screen.height-205);
           });               
</script>-->
    
<div id="d1" class="site-clientdetail  row-eq-height">        
    <div class="col-lg-2 col-sm-2 side-menubar leftbar">
            <div class="tools">TOOLS</div>
            <div class="row breadcrumb text-center">&nbsp;</div>            
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
        <div class="row">
                    <div class="col-lg-6 mintable">        
                        <p class="record-heading">
                            PROPOSALS SUBMITTED TO BDCC FOR APPROVAL
                        </p> 
                        <?php 
                            $searchModel = new frontend\models\ProposalInitSearch();
                            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                        ?>
                        <?= $this->render('/proposal-init/index', 
                                [
                                    'searchModel' => $searchModel,
                                    'dataProvider' => $dataProvider,
                                ]) ?>                        
                    </div>                    
                    <div class="col-lg-6 ">                    
                        <p class="record-heading">MEETINGS</p>
                        
                            <?php                                 
                                $searchModel = new frontend\models\SchedulemeetingSearch();
                                $dataProvider1 = $searchModel->search(Yii::$app->request->queryParams);
                                
                                //$dataProvider1->pagination->pageSize = 10;
                            ?>
                            <?= GridView::widget([
                                /*'behaviors' => [
                                [
                                        'class' => '\dosamigos\grid\behaviors\FloatHeaderBehavior',
                                        'clientOptions' => [ // ... plugin options
                                            'floatContainerClass' => 'white',
                                            'top' => 5
                                        ],
                                        'clientEvents' => [
                                            //'floatThead' => new JsExpression("function(e, isFloated, $container){ console.log('...'); }")
                                        ]
                                    ]
                                ],*/
                                'dataProvider' => $dataProvider1,
                                //'filterModel' => $searchModel,
                                //'layout' => "{items}\n{pager}",
                                'tableOptions' => ['class' => 'table table-responsive table-bordered table-striped table-hover fixed_headers_md'],
                                    //'id' => 'table3',],
                                'layout' => "{items}\n{pager}",
                                'showFooter'=>FALSE,
                                //'showHeader' => FALSE,        
                                'showOnEmpty'=>true,
                                'columns' => [
                                    [
                                        'class' => 'yii\grid\DataColumn',
                                        'header' => 'Client',
                                        'format' => 'raw',
                                        'value' => function ($dataProvider) {                      
                                            //return Html::a($dataProvider->clientDept->deptName, ['/client-detail/view', 'id' => $dataProvider->clientid]);
                                            return $dataProvider->clientDept->deptName;
                                        },
                                    ],
                                    [
                                        'class' => 'yii\grid\DataColumn',
                                        'header' => 'Date of Meeting',
                                        'label' => 'Date of Meeting',
                                        'format' => 'raw',
                                        'attribute' => 'date',
                                        'value' => function ($dataProvider) {                      
                                            return Yii::$app->formatter->asDate($dataProvider->date). '<br/>'
                                                    .Html::a('View Schedule', ['/schedulemeeting/view', 'id' => $dataProvider->id], ['class' => 'btn btn-warning btn-xs']) . '       ' 
                                                    . Html::a('Fill Details', ['/meetings/create', 'id' => $dataProvider->id], ['class' => 'btn btn-info btn-xs']);
                                        },                                        
                                        //'format'=>['date', 'php:d-m-Y'],
                                    
                                    ],
                                ],
                            ]); ?>       
                        
                    </div> 
        </div>
        <div  class="proposal-detail">       
            <?php  echo \Yii::$app->view->render('@app/views/proposal/expiringproposal');?>            
        </div>   
        
    </div>  
    
    
        
</div>
