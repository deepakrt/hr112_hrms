<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Html\ListGroup;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\grid\GridView;
//use dosamigos\grid\GridView;
use yii\web\JsExpression;
use yii\db\Expression;
use yii\helpers\Url;
use frontend\models\Billmaster;
use frontend\models\ProjectsSearch;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

$this->title = 'Finance Dashboard';
$this->params['breadcrumbs'][] = ['label' => 'Home', 'url' => ['/site/index']];
$this->params['breadcrumbs'][] = $this->title;
?>



<div class="site-clientdetail">    
    <div class="row-eq-height">
    
        <div class="col-lg-2 col-sm-2 side-menubar leftbar">
            <?php if ($_SESSION['userrole'] == 'admin' ||$_SESSION['userrole'] == 'editor' ) {?>
                <div class="tools">TOOLS</div>
            <?php } else { ?>
                <div class="tools">FINANCE</div>
            <?php }  ?>    
            
            <div class="row breadcrumb">&nbsp;</div>      
            <?php  echo \Yii::$app->view->render('@app/views/layouts/proposalrequest1');?>            
        </div>
        <div class="col-lg-10  right-header"> 
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
            <div class="row proposal-detail ">
                <div class="col-lg-12">
                    <p class="record-heading">Cost Matrix</p>
                    <?php 
                        $searchModel = new ProjectsSearch();
                        $dataProvider1 = $searchModel->search(Yii::$app->request->queryParams);
                        
                        //$dataProvider1->pagination->pageSize = 10;
                    ?>
                    <div>
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
                        'tableOptions' => ['class' => 'table table-responsive table-bordered table-striped table-hover',
                            'id' => 'financialdetail'],
                        'layout' => "{items}\n{pager}",
                        'showFooter'=>FALSE,
                        //'showHeader' => FALSE,
                        'showOnEmpty'=>true,
                        'columns' => [
                            //['class' => 'yii\grid\SerialColumn'],
                            [
                                'class' => 'yii\grid\DataColumn',
                                'header' => 'Project Name',
                                'format' => 'raw',
                                'value' => function ($dataProvider) {      
                                    if($dataProvider->getProjects()->exists() && $dataProvider->projects->getOrderdetail()->exists()){
                                        return Html::a($dataProvider->projects->orderdetail->projectname, ['/projects/costmatrix', 'id' => $dataProvider->projects->id]);
                                    } else {
                                        return '';
                                    }
                                },
                            ],  
                            [
                                'class' => 'yii\grid\DataColumn',
                                'header' => 'Status',                                    
                                'format' => 'raw',
                                'value' => function ($dataProvider) {      
                                    if($dataProvider->getProjects()->exists() && $dataProvider->projects->getOrderdetail()->exists() && $dataProvider->projects->actualcompletiondate != NULL){
                                        //return 'Completed on: '. $dataProvider->projects->actualcompletiondate;
                                        return 'Completed';
                                    } else {
                                        return '';
                                    }
                                        
                                },
                            ],
                            //['class' => 'yii\grid\ActionColumn',                                
                        ],
                    ]); ?>  
                    </div>
                </div>
            </div>
            <div class="col-lg-12 thumbnail text-center">        
                <h3>
                    <!--<?php $model = new Billmaster(); ?>
                    <?//= Html::a('Bill-Payment Matrix',['/billmaster/billpaymentmatrix', ['model' => $model,]])?>-->
                </h3>
            </div>     
        </div>        
            
    </div>
    
</div>
