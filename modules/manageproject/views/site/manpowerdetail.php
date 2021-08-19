<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Html\listGroup;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\grid\GridView;
//use dosamigos\grid\GridView;
use yii\web\JsExpression;
use yii\db\Expression;
use yii\helpers\Url;
use frontend\models\Manpower;
use frontend\models\ManpowerSearch;
use frontend\models\Manpowermapping;
use frontend\models\ManpowermappingSearch;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

$this->title = 'Manpower Dashboard';
$this->params['breadcrumbs'][] = ['label' => 'Home', 'url' => ['/site/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-clientdetail row-eq-height ">       
    <div class="col-lg-2 col-sm-2 side-menubar leftbar">
        <div class="tools">TOOLS</div>
        <div class="row breadcrumb">&nbsp;</div>            
        <?php echo \Yii::$app->view->render('@app/views/layouts/proposalrequest1');?>         
    </div>
    <div class="col-lg-10 col-sm-10 text-justify">
        <div class="row content-bar text-justify">
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
        <div class="col-lg-12 proposal-detail  rowtable">
            <div class="col-lg-6 rowtable">
                <p class="record-heading">TEAM MEMBERS</p>
                <?php $model = new Manpower(); ?>
                <?= $this->render('/manpower/index', ['model' => $model,]) ?>                         
            </div>
            <div class="col-lg-6 mintable">
                <p class="record-heading">PROJECT LEADS</p>
                <div style="">
                    <?php 
                        $searchModel = new ManpowermappingSearch();
                        $dataProvider = $searchModel->searchmap(Yii::$app->request->queryParams);
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
                            'tableOptions' => ['class' => 'table table-responsive table-bordered table-striped table-hover fixed_headers_md'],
                                //'id' => 'manpowerwork'],
                            'layout' => "{items}\n{pager}",
                            'showFooter'=>FALSE,
                            'columns' => [
                                [
                                    'class' => 'yii\grid\DataColumn',
                                    'format' => 'raw',
                                    'label' => 'PROJECT',
                                    'value' => function ($dataProvider) {
                                        return $dataProvider->ordermaster->projectname;
                                    },
                                ],
                                [
                                    'class' => 'yii\grid\DataColumn',
                                    'label' => 'MANPOWER',
                                    'value' => function ($dataProvider) {
                                        return $dataProvider->ordermaster->investigator->lead->name;
                                    },
                                ],
                                //['class' => 'yii\grid\ActionColumn'],
                            ],
                        ]); ?>
                </div>
            </div>
        </div>
        <div class="col-lg-12 proposal-detail  rowtable">  
                <p class="record-heading">CONTRACT INFORMATION</p>
                <!--<div class="thumbnail text-center">  
                    <h3>Contract Information</h3>
                    <p>-->
                        <?php 
                            $searchModel = new ManpowerSearch();
                            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                            $dataProvider->query->andWhere(['emptype' => 'Contractual']);
                        ?>
                <div class="projects-dashboard1">
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
                            'tableOptions' => ['class' => 'table table-responsive table-bordered table-striped table-hover fixed_headers_md'],
                                //'id' => 'manpowercontact'],
                            'layout' => "{items}\n{pager}",
                            'showFooter'=>FALSE, 
                            'rowOptions'=>function($model){
                                $time = new \DateTime('now');
                                $today = $time->format('d-m-Y');
                                $newdate = strtotime ( $today );
                                
                                if($model->dor ==0000-00-00)
                                {
                                    return ['class' => ''];
                                }
                                elseif($newdate > strtotime ('+7 day' , strtotime ($model->dor ))  ){
                                    return ['class' => ''];
                                }
                                else
                                {
                                    return ['class' => ''];
                                }
                            },
                            'columns' => [
                                //['class' => 'yii\grid\SerialColumn'],

                                //'activeuser',
                                //'deleted',
                                //'id',
                                //'sessionid',
                                //'updatedon',
                                // 'name',
                                [
                                    'class' => 'yii\grid\DataColumn',
                                    //'header' => '<h3 class="text-center">Due Renewals</h3>',
                                    //'attribute' => 'some_title',
                                    'format' => 'raw',
                                    'label' => 'Employee Name',
                                    'value' => function ($dataProvider) {                      
                                        return Html::a($dataProvider->name, ['/manpower/view', 'id' => $dataProvider->id]);
                                    },
                                ],
                                [
                                    'class' => 'yii\grid\DataColumn',
                                    //'header' => '<h3 class="text-center">Due Renewals</h3>',
                                    //'attribute' => 'some_title',
                                    'format' => 'raw',
                                    'label' => 'Emp ID',
                                    'value' => function ($dataProvider){                      
                                        return $dataProvider->empcode;
                                    },
                                ],
                                [
                                    'class' => 'yii\grid\DataColumn',
                                    //'header' => '<h3 class="text-center">Due Renewals</h3>',
                                    //'attribute' => 'some_title',
                                    'format' => 'raw',
                                    'label' => 'Joining Date',
                                    //'format'=>['date', 'php:d-m-Y'],
                                    'value' => function ($dataProvider) {                      
                                        return Yii::$app->formatter->asDate($dataProvider->doj);
                                    },
                                ],
                                //'doj',
                                //'dor',
                                [
                                    'class' => 'yii\grid\DataColumn',
                                    'header' => 'Date of Renewal',
                                    'label' => 'Date of Renewal',
                                    'attribute' => 'dor',
                                    'value' => function ($dataProvider) {                      
                                        return Yii::$app->formatter->asDate($dataProvider->dor);
                                    },
                                    //'format'=>['date', 'php:d-m-Y'],
                                    //'headerOptions' => ['style'=>'text-align:center'],
                                ],
                                // 'dob',
                                // 'designationid',
                                // 'email:email',
                                // 'phone',
                                // 'salary',
                                // 'technologyid',
                                // 'qualification',
                                // 'totalexperience',
                                // 'cdacexperience',
                                // 'doresign',

                                //['class' => 'yii\grid\ActionColumn'],
                            ],
                        ]); ?>
                </div>
                <!--    </p>
                </div>      -->
            </div>     
        </div>        
            
    
    
</div>