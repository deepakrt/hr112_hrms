<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use frontend\models\ProjectsSearch;
use yii\data\ActiveDataProvider;
//use dosamigos\grid\GridView;
use yii\web\JsExpression;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */


$this->title = 'Project Summary';
$this->params['breadcrumbs'][] = ['label' => 'Home', 'url' => ['/site/index']];
//$this->params['breadcrumbs'][] = ['label' => 'Project Reports', 'url' => ['/site/reports']];
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
        <div class="site-projectsummary">
            <?php if (Yii::$app->user->can('premium')) {?>              
                
                    <p class="record-heading">List of projects</p>                
            
                    <?php $form = ActiveForm::begin(['action' =>['site/projectsummary'], 'id' => 'site-projectsummary', 'method' => 'post',]);                    
                        $searchModel = new ProjectsSearch();
                        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                        ?>     
                    <div class="form-height">
                        
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
                        
                        'dataProvider' => $dataProvider,
                        //'filterModel' => $searchModel,
                        
                        'tableOptions' => ['class' => 'table table-responsive table-bordered table-striped table-hover fixed_headers1'],
                            //'id' => 'projectsummary'],
                        'layout' => "{items}\n{pager}",
                        'columns' => [
                            //['class' => 'yii\grid\SerialColumn'],

                            //'id',
                            //'activeuser',
                            //'orderid',
                            [
                                'class' => 'yii\grid\DataColumn',
                                'header' => 'Project Name',                                
                                'format' => 'raw',                                
                                'value' => function ($dataProvider) {   
                                    if($dataProvider->getProjects()->exists() && $dataProvider->projects->project->getOrderdetail()->exists()){
                                        return Html::a($dataProvider->projects->project->orderdetail->projectname, ['/projects/view', 'id' => $dataProvider->projects->id]);
                                    } else {
                                        return '';
                                    }
                                },
                            ],                             
                            [
                                'label' => 'Funding',
                                //'attribute' => 'projectType.type', 
                                'value' =>function ($dataProvider) {                      
                                    if($dataProvider->getProjects()->exists()){
                                        return $dataProvider->projects->project->projectType->type;
                                    }else {
                                        return '';
                                    }
                                },
                            ],
                            [
                                'class' => 'yii\grid\DataColumn',
                                'header' => 'Funding <br/> Agency',                                
                                'format' => 'raw',
                                'value' => function ($dataProvider) {  
                                    if($dataProvider->getProjects()->exists() && $dataProvider->projects->project->orderdetail->getClientdetail()->exists()){
                                        return Html::a($dataProvider->projects->project->orderdetail->clientdetail->deptName, ['/ordermaster/view', 'id' => $dataProvider->projects->project->orderid]);
                                    } else {
                                        return '';
                                    }
                                    
                                },
                            ],
                            [
                                'class' => 'yii\grid\DataColumn',
                                'header' => 'Proposal <br/> Number',                                
                                'format' => 'raw',
                                'contentOptions' => [
                                    'style'=>'max-width:100px; overflow: auto; word-wrap: break-word;'
                                    ],
                                'value' => function ($dataProvider) {   
                                    if($dataProvider->getProjects()->exists()){
                                        return Html::a($dataProvider->projects->project1->orderdetail1->proposal->proposalnumber, ['/proposal/view', 'id' => $dataProvider->projects->project1->orderdetail1->proposal->id]);
                                    }else {
                                        return '';
                                    }
                                },
                            ],
                            [
                                /*'label' => 'Project Number',
                                'attribute' => 'projectrefno',  
                                'contentOptions' => [
                                    'style'=>'max-width:100px; overflow: auto; word-wrap: break-word;'
                                    ],*/
                                'class' => 'yii\grid\DataColumn',
                                'header' => 'Project <br/> Number',                                
                                'format' => 'raw',
                                'value' =>function ($dataProvider) {
                                    if($dataProvider->getProjects()->exists())
                                        return $dataProvider->projects->projectrefno;
                                    else {
                                        return '';
                                    }
                                },
                            ],           
                            [
                                'label' => 'Status',
                                'attribute' => 'status', 
                                'contentOptions' => [
                                    'style'=>'max-width:100px; overflow: auto; word-wrap: break-word;'
                                    ],
                                'value' =>function ($dataProvider) {   
                                    if($dataProvider->getProjects()->exists())
                                        return $dataProvider->projects->status;
                                    else {
                                        return '';
                                    }
                                },
                            ], 
                            [
                                'class' => 'yii\grid\DataColumn',
                                'header' => 'Cost (App.)',                                
                                'format' => 'raw',
                                'value' => function ($dataProvider) {      
                                    if($dataProvider->getProjects()->exists())
                                        return Html::a(Yii::$app->formatter->asCurrency($dataProvider->projects->project->orderdetail->amount /100000) .' Lakhs', ['/ordermaster/view', 'id' => $dataProvider->projects->project->orderdetail->id]);
                                    else {
                                        return '';
                                    }
                                },
                            ],
                            [
                                'label' => 'Start Date',
                                'attribute' => 'projectstartdate',   
                                //'format'=>['date', 'php:d-m-Y'],
                                'value' =>function ($dataProvider) {
                                    if($dataProvider->getProjects()->exists())
                                        return Yii::$app->formatter->asDate($dataProvider->projects->project->projectstartdate);
                                    else {
                                        return '';
                                    }
                                },
                            ], 
                            [
                                /*'label' => 'Completion Date',
                                'attribute' => 'expectedenddate', */
                                'class' => 'yii\grid\DataColumn',
                                'header' => 'Completion <br/> Date',                                
                                'format' => 'raw',
                                //'format'=>['date', 'php:d-m-Y'],
                                'value' =>function ($dataProvider) {                      
                                    if($dataProvider->getProjects()->exists())
                                        return Yii::$app->formatter->asDate($dataProvider->projects->project->expectedenddate);
                                    else {
                                        return '';
                                    }
                                },
                            ], 
                            //'projectrefno',                            
                            //'projecttypeid',
                            // 'investigatorid',
                            // 'coinvestigatorid',
                            //'projectstartdate',
                            //'expectedenddate',
                            // 'milestoneid',
                            // 'objectives',
                            // 'technologyid',
                            // 'databaseused',
                            // 'manpowerid',
                            // 'finaloutcome',
                            // 'completionreport',
                            // 'appreciationcert',
                            // 'actualcompletiondate',
                            // 'referenceid',
                            // 'deleted',
                            // 'remarks',
                            // 'sessionid',
                            // 'updatedon',

                            //['class' => 'yii\grid\ActionColumn'],
                        ],
                    ]); ?>
                    </div>
                    <?php ActiveForm::end(); ?>
              
            <?php }?>
            

    
        </div>
    </div>
</div>
