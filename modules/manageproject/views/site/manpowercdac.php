<?php

/* @var $this yii\web\View */
use frontend\models\Projects;
use frontend\models\Manpower;
use frontend\models\Manpowermapping;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'C-DAC Mohali Manpower';
$this->params['breadcrumbs'][] = ['label' => 'Dashboard', 'url' => ['/site/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-clientdetail row-eq-height">
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
            <div class="col-lg-4 prjsession">
                <div class="alert-info" role="alert">
                    <?= Yii::$app->session->getFlash('success'); ?>
                </div>                
            </div>
        </div>
        <div class="row ">            
            <div class="col-lg-12 ">
                <div class="col-lg-12 text-left client-form-label" style="background-color: d6e0df;  margin-bottom: 10px;"> 
                    
                    <div style="font-size:12px; margin: 5px 5px 15px 5px;">
                        <?php for($k=0; $k<sizeof($this->params['dept']); $k++){?>
                        <div class="col-lg-1" style="margin-left: 5px">
                                
                            <div class="row"><strong><?php echo Yii::$app->projectcls->SelectShortDept($this->params['dept'][$k]->deptname)[0]->shortname ?> : <?=Yii::$app->projectcls->SelectTotalEmp($this->params['dept'][$k]->deptname)['total'];?></strong></div>
                                                                    
                                <div class="row"  style="font-size: smaller;">
                                    Regular : <?=Yii::$app->projectcls->SelectTotalEmp($this->params['dept'][$k]->deptname)['regular']; ?>
                                </div>
                                <div class="row"  style="font-size: smaller;">
                                    Contractual : <?=Yii::$app->projectcls->SelectTotalEmp($this->params['dept'][$k]->deptname)['contractual']; ?>                                    
                                </div>
                                <div class="row"  style="font-size: smaller;">
                                    Grade Based : <?=Yii::$app->projectcls->SelectTotalEmp($this->params['dept'][$k]->deptname)['grade']; ?>
                                </div>
                                                       
                            </div>
                        <?php } ?>
                        
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <?php
                                $model = new Manpower();
                                $searchModel = new frontend\models\ManpowerSearch();
                                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                        ?>
                        <?= $this->render('/manpower/manpowercdac', [
                                'model' => $model,
                                'searchModel' => $searchModel,
                                'dataProvider' => $dataProvider,   
                        ]) ?>                            
                        
                   
                    </div>
                </div>                        
            </div>
        </div>   
        </div>        
            
    
</div>
