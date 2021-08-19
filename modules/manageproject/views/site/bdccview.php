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
use frontend\facade\edudata;

$this->title = 'Business Development Cell';
$this->params['breadcrumbs'][] = $this->title; 
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
            <div class="col-lg-4 prjsession">&nbsp;
                <?php /* $form = \yii\widgets\ActiveForm::begin(['action' =>['cdacdept/create'], 'id' => 'city-form', 'method' => 'post','fieldConfig' => ['labelOptions' => ['class' => 'client-form-label'], 'inputOptions' => ['class' => 'client-form-input'],]]); ?>
                        <?= yii\helpers\Html::dropDownList('id1', null, $this->params['roleasgn'],
                            [
                                'prompt'=>'--Switch Role--',
                                'class' => 'text-centre',
                                'options' => [$_SESSION['userrole']=>array('selected'=>true)],
                                'onchange'=>'
                                    $.post( "'.Yii::$app->urlManager->createUrl('cdacdept/userrole?id=').'"+$(this).val(), function( data ) {
                                        $("div#subbody" ).html( data );
                                    });
                            ']); ?>
                <?php  \yii\widgets\ActiveForm::end();*/?>
            </div>
        </div>        
        <div class="mintable">        
            <p class="record-heading">
                NEW PROPOSALS
                <span style="float:right"></span>
            </p> 
            <?php 
                $searchModel = new frontend\models\ProposalInitSearch();
                $dataProvider = $searchModel->searchNewProposals(Yii::$app->request->queryParams);
            ?>
            <?= $this->render('/proposal-init/index', 
                    [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                    ]) ?>
            
            
        </div> 
        <div class="mintable">        
            <p class="record-heading">
                IN-PROCESS PROPOSALS
                <span style="float:right"></span>
            </p> 
            <?php 
                $searchModel = new frontend\models\ProposalInitSearch();
                $dataProvider = $searchModel->searchReviewedProposals(Yii::$app->request->queryParams);
            ?>
            <?= $this->render('/proposal-init/index', 
                    [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                    ]) ?>
            
            
        </div> 
    </div>
</div>