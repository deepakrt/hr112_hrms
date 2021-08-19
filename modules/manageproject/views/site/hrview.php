<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\UserIdentity;
use frontend\models\Manpowermapping;
use frontend\models\ClientDetail;
use frontend\models\ProposalSearch;
use frontend\models\ClientDetailSearch;
use frontend\models\Manpower;
use frontend\models\Task;
use frontend\models\ManpowermappingSearch;
use frontend\models\MeetingsSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use common\models\User;
use common\rbac\models\Role;
use yii\rbac\CheckAccessInterface;


/* @var $this yii\web\View */
$this->title = Yii::t('app', Yii::$app->name);
?>
<div class="row-eq-height">
    <div class="col-lg-2 col-sm-2 side-menubar leftbar">
        <div class="tools">TOOLS</div>
        <div class="row breadcrumb">&nbsp;</div>            
        <?php echo \Yii::$app->view->render('@app/views/layouts/proposalrequest1');?> 
    </div>    
    <div class="col-lg-10 right-header">
        <div class="row content-bar text-justify" >            
            <?php echo \Yii::$app->view->render('@app/views/layouts/mainbar');?>
        </div>
        <div class="row breadcrumb">
            <div class="col-lg-8" style="padding: 0px; margin: 0px;">
                <?php  echo \Yii::$app->view->render('@app/views/layouts/breadcrumb');?>
            </div>
            <div class="col-lg-4 prjsession"></div>
        </div>    
        <div class="det-form">    
            <div class="row">
                <?php for($i=0; $i<sizeof($this->params['dept']); $i++){ ?>
                    <div class="box1 col-lg-3">
                        <p class="record-heading" style="height: 60px">
                            <?=$this->params['dept'][$i]->deptname; ?>
                        </p>
                        <b>Total: </b> <?=Yii::$app->projectcls->SelectTotalEmp($this->params['dept'][$i]->deptname)['total'];  ?><br/>
                        <b>Regular: </b> <?=Yii::$app->projectcls->SelectTotalEmp($this->params['dept'][$i]->deptname)['regular'];  ?><br/>
                        <b>Contractual: </b> <?=Yii::$app->projectcls->SelectTotalEmp($this->params['dept'][$i]->deptname)['contractual'];  ?><br/>
                        <b>Grade Based: </b> <?=Yii::$app->projectcls->SelectTotalEmp($this->params['dept'][$i]->deptname)['grade'];  ?><br/>
                    </div>
                <?php } ?>
            </div>    
            <div style="height: 20px;"></div>
            <div class="row">
                <div class="col-lg-6">
                    <p class="record-heading">EXPIRING CONTRACTS</p>
                    <?php 
                        $searchModel = new frontend\models\ManpowerSearch();
                        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                        $dataProvider->query->andWhere(['emptype' => 'Contractual'])->andWhere(['<=', 'dor', ((new \DateTime('now'))->format('Y-m-d'))])->orderBy('dor');
                    ?>
                    <div class="projects-dashboard1">
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'tableOptions' => ['class' => 'table table-responsive table-bordered table-striped table-hover fixed_headers_md'],
                            'layout' => "{items}\n{pager}",
                            'showFooter'=>FALSE, 
                            'rowOptions'=>function($model){
                                $time = new \DateTime('now');
                                $today = $time->format('d-m-Y');
                                $newdate = strtotime ( $today );

                                if($model->dor ==0000-00-00)
                                {
                                    return ['class' => ''];
                                }elseif($newdate > strtotime ('+7 day' , strtotime ($model->dor ))  ){
                                    return ['class' => ''];
                                }else{
                                    return ['class' => ''];
                                }
                            },
                            'columns' => [
                                [
                                    'class' => 'yii\grid\DataColumn',
                                    'format' => 'raw',
                                    'label' => 'Employee Name',
                                    'value' => function ($dataProvider) {                      
                                        return Html::a($dataProvider->name, ['/manpower/view', 'id' => $dataProvider->id]);
                                    },
                                ],
                                [
                                    'class' => 'yii\grid\DataColumn',                                    
                                    'format' => 'raw',
                                    'label' => 'Emp ID',
                                    'value' => function ($dataProvider){                      
                                        return $dataProvider->empcode;
                                    },
                                ],
                                [
                                    'class' => 'yii\grid\DataColumn',
                                    'header' => 'Date of Renewal',
                                    'label' => 'Date of Renewal',
                                    'attribute' => 'dor',
                                    'value' => function ($dataProvider) {                      
                                        return Yii::$app->formatter->asDate($dataProvider->dor);
                                    },                                   
                                ],
                            ],
                        ]); ?>
                    </div>
                </div>
                <div class="col-lg-6">
                    <p class="record-heading">BIRTHDAYS</p>
                    <?php 
                        $searchModel = new frontend\models\ManpowerSearch();
                        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                        $dataProvider->query->andWhere(['Like', 'dob', ((new \DateTime('now'))->format('Y-m-d'))])->orderBy('empcode');
                    ?>
                    <div class="projects-dashboard1">
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'tableOptions' => ['class' => 'table table-responsive table-bordered table-striped table-hover fixed_headers_md'],
                            'layout' => "{items}\n{pager}",
                            'showFooter'=>FALSE, 
                            'rowOptions'=>function($model){
                                $time = new \DateTime('now');
                                $today = $time->format('d-m-Y');
                                $newdate = strtotime ( $today );

                                if($model->dor ==0000-00-00)
                                {
                                    return ['class' => ''];
                                }elseif($newdate > strtotime ('+7 day' , strtotime ($model->dor ))  ){
                                    return ['class' => ''];
                                }else{
                                    return ['class' => ''];
                                }
                            },
                            'columns' => [
                                [
                                    'class' => 'yii\grid\DataColumn',
                                    'format' => 'raw',
                                    'label' => 'Employee Name',
                                    'value' => function ($dataProvider) {                      
                                        return Html::a($dataProvider->name, ['/manpower/view', 'id' => $dataProvider->id]);
                                    },
                                ],
                                [
                                    'class' => 'yii\grid\DataColumn',                                    
                                    'format' => 'raw',
                                    'label' => 'Emp ID',
                                    'value' => function ($dataProvider){                      
                                        return $dataProvider->empcode;
                                    },
                                ],
                                [
                                    'class' => 'yii\grid\DataColumn',
                                    'header' => 'Date of Renewal',
                                    'label' => 'Department',
                                    'attribute' => 'dor',
                                    'value' => function ($dataProvider) {                      
                                        return $dataProvider->cdacdeptid;
                                    },                                   
                                ],
                            ],
                        ]); ?>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>