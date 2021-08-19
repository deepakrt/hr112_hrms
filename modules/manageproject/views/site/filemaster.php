<?php

use yii\helpers\Html;
use yii\grid\GridView;
//use dosamigos\grid\GridView;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use frontend\models\ProjectsSearch;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */

$this->title = 'File Master';
$this->params['breadcrumbs'][] = ['label' => 'Home', 'url' => ['/site/index']];
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
        </div> 
        <div class="site-clientdetail ">   
            <p class="record-heading">MASTER LIST OF PROJECT FILES</p>    
            <div class="form-height">
                            <?php $form = ActiveForm::begin(['action' =>['projects/index'], 'id' => 'projects-index', 'method' => 'post',]);                    
                                $searchModel = new ProjectsSearch();
                                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);                        
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
                                'dataProvider' => $dataProvider,
                                //'filterModel' => $searchModel,
                                //'layout' => "{items}\n{pager}",
                                'tableOptions' => ['class' => 'table table-responsive table-bordered table-striped table-hover fixed_headers'],
                                'layout' => "{items}\n{pager}",
                                'columns' => [
                                    [
                                        'class' => 'yii\grid\SerialColumn',
                                        'header' => 'Sr. No.',
                                    ],

                                    //'id',
                                    //'activeuser',
                                    //'orderid',
                                    [
                                        'class' => 'yii\grid\DataColumn',
                                        'header' => 'Primary Head',
                                        'format' => 'raw',
                                        'value' => function ($dataProvider) {  
                                            if($dataProvider->getProjects()->exists() && $dataProvider->projects->getProject()->exists())
                                                return Html::a($dataProvider->projects->project->orderdetail->projectname, ['/projects/view', 'id' => $dataProvider->projects->id]);
                                            else
                                                return '';
                                            //return Html::a($dataProvider->project->orderdetail->projectname, ['/projects/view', 'id' => $dataProvider->id]);
                                        },
                                    ],
                                    [
                                        'class' => 'yii\grid\DataColumn',
                                        'header' => 'Year',
                                        'format' => 'raw',
                                        'value' => function ($dataProvider) {  
                                            if($dataProvider->getProjects()->exists()){
                                                $dt1 = new \DateTime( $dataProvider->projects->projectstartdate );
                                                return  (int) $dt1->format( 'Y' );
                                                //return   Yii::$app->formatter->asDate($dataProvider->projects->projectstartdate);
                                            }else
                                                return '';
                                        },
                                    ], 
                                    [
                                        'class' => 'yii\grid\DataColumn',
                                        'header' => 'Unit/ Division',
                                        'format' => 'raw',
                                        'value' => function ($dataProvider) {  
                                            if($dataProvider->getProjects()->exists()){
                                                return $dataProvider->projects->cdacdept->subdept;
                                            }else
                                                return '';
                                        },
                                    ], 
                                    [
                                        'class' => 'yii\grid\DataColumn',
                                        'header' => 'File Number',
                                        'format' => 'raw',
                                        'value' => function ($dataProvider) { 
                                            if($dataProvider->getProjects()->exists() && $dataProvider->projects->getProject()->exists())
                                                return $dataProvider->projects->project->filenumber;
                                            else
                                                return '';
                                            //return $dataProvider->project->filenumber;
                                        },
                                    ], 
                                    [
                                        'class' => 'yii\grid\DataColumn',
                                        'header' => 'Maintained By',
                                        'format' => 'raw',
                                        'value' => function ($dataProvider) {  
                                            if($dataProvider->getProjects()->exists()){                  
                                                try{
                                                    return Yii::$app->projectcls->SelectManpower(Yii::$app->projectcls->SelectInvestigator($dataProvider->projects->orderid)[0]->coinvestigator)[0]->name;
                                                }  catch(Exception $e) {}
                                            }else {
                                                return '';
                                            }
                                        },
                                    ],
                                    [
                                        'class' => 'yii\grid\DataColumn',
                                        'header' => 'Retention period',
                                        'format' => 'raw',
                                        'value' => function ($dataProvider) {  
                                            if($dataProvider->getProjects()->exists())
                                                return '3 Years after completion';
                                             else
                                                return '';
                                        },
                                    ],
                                      
                                    //['class' => 'yii\grid\ActionColumn'],
                                ],
                            ]); ?>
            </div>
                            <?php ActiveForm::end(); ?>
        </div>
           
            
    </div>
    
</div>
