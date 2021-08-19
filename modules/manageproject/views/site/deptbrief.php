<?php

/* @var $this yii\web\View */
use frontend\models\Projects;
use frontend\models\Manpower;
use frontend\models\Manpowermapping;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $this->params['deptname']; 
$this->params['breadcrumbs'][] = ['label' => 'Home', 'url' => ['/site/index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="site-clientdetail row-eq-height" style="overflow: none">
    <div class="col-lg-2 col-sm-2 side-menubar leftbar">
        
            <div class="tools">TOOLS</div>
            <div class="breadcrumb">&nbsp;</div>            
            <?php echo \Yii::$app->view->render('@app/views/layouts/proposalrequest1',['class'=>'leftcollapse']);?>             
        
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
        <div class="row proposal-detail">
            <div class="col-lg-12 container container-table" >
                <div class="col-lg-12 rowtable project-head-sub">
                    <div class="project-information-dashboard-sub text-uppercase">
                            <?=  $this->params['deptname']; ?>
                    </div>
                    <div class="ed-project-dashboard">CENTRE FOR DEVELOPMENT OF ADVANCED COMPUTING</div>                
                </div> 
                <div class="text-center col-md-offset-0 backimgsub">

                        <input id='check' type='checkbox' checked disabled>
                        <label class='sub-ed' for='check'></label>


                        <div class="dashboard-sub toolimg"> 

                            <?php if((Yii::$app->request->get('id') ==3) ||(Yii::$app->request->get('id') ==5)) {?>
                                <i class="icons-dashboard-ed" >
                                    <?php echo Html::a(Html::img(Yii::getAlias('@uploads'). '/Images/egovernance.png',['class'=>'img-responsive-ed']),['/site/hcid', 'id' => 3]);?> 
                                    <span style="margin: -60px 0px 0 -125px; position: absolute">E-GOVERNANCE</span>

                                </i>

                                <i class="icons-dashboard-ed">
                                    <?php echo Html::a(Html::img(Yii::getAlias('@uploads').'/Images/security.png', ['class'=>'img-responsive-ed']),['/site/hcid', 'id' =>5]); ?>
                                    <span style="margin: -60px 0px 0 -180px; position: absolute">NETWORK INFORMATION <br/>SECURITY OPERATIONS</span>
                                </i>
                                
                                <span class="toolsub-dashboard">STD</span>
                            <?php }else if((Yii::$app->request->get('id') ==16)||(Yii::$app->request->get('id') ==15)) {?>
                                <i class="icons-dashboard-ed" >
                                    <?php echo Html::a(Html::img(Yii::getAlias('@uploads'). '/Images/store.png',['class'=>'img-responsive-ed']),['/site/hcid', 'id' => 15]);?> 
                                    <span style="margin: -60px 0px 0 -70px; position: absolute">STORE</span>

                                </i>

                                <i class="icons-dashboard-ed">
                                    <?php echo Html::a(Html::img(Yii::getAlias('@uploads').'/Images/Purchase.png', ['class'=>'img-responsive-ed']),['/site/hcid', 'id' =>16]); ?>
                                    <span style="margin: -60px 0px 0 -100px; position: absolute">PURCHASE</span>
                                </i>
                                
                                <span class="toolsub-dashboard">MMG</span>
                            <?php }else if((Yii::$app->request->get('id') == 8)||(Yii::$app->request->get('id') == 9)||(Yii::$app->request->get('id') == 10)||(Yii::$app->request->get('id') == 11)){ ?>
                            <i class="icons-dashboard-ed">
                                <?php echo Html::a(Html::img(Yii::getAlias('@uploads').'/Images/admin.png', ['class'=>'img-responsive-ed']),['/site/hcid', 'id' =>8]); ?>
                                <span style="margin: -60px 0px 0 -125px; position: absolute">Administration</span>
                            </i>

                            <i class="icons-dashboard-ed">
                                <?php echo Html::a(Html::img(Yii::getAlias('@uploads').'/Images/HR.png', ['class'=>'img-responsive-ed']),['/site/hcid', 'id' => 10]); ?> 
                                <span style="margin: -60px 0px 0 -140px; position: absolute">Human Resource</span>
                            </i> 

                            <i class="icons-dashboard-ed">
                                <?php echo Html::a(Html::img(Yii::getAlias('@uploads').'/Images/Fin.png', ['class'=>'img-responsive-ed']),['/site/hcid', 'id' =>9]); ?>
                                <span style="margin: -70px 0px 0 20px; position: absolute">Finance</span>
                            </i>

                            <i class="icons-dashboard-ed">
                                <?php echo Html::a(Html::img(Yii::getAlias('@uploads').'/Images/library.png', ['class'=>'img-responsive-ed']),['/site/hcid', 'id' =>11]); ?>
                                <span style="margin: -60px 0px 0 35px; position: absolute">Library</span>
                            </i>
                            
                            <span class="toolsub-dashboard">AFD</span>
                            <?php } else if((Yii::$app->request->get('id') == 24)||(Yii::$app->request->get('id') == 23)){ ?>
                            <i class="icons-dashboard-ed">
                                <?php echo Html::a(Html::img(Yii::getAlias('@uploads').'/Images/BDCC.png', ['class'=>'img-responsive-ed']),['/site/hcid', 'id' =>24]); ?>
                                <span style="margin: -90px 0px 0 -125px; position: absolute">Business Development Coordination Cell</span>
                            </i>

                            <i class="icons-dashboard-ed">
                                <?php echo Html::a(Html::img(Yii::getAlias('@uploads').'/Images/AAIA.png', ['class'=>'img-responsive-ed']),['/site/hcid', 'id' => 23]); ?> 
                                <span style="margin: -80px 0px 0 -140px; position: absolute">Applied Artificial Intelligence & Analytics</span>
                            </i> 

                            <!--<i class="icons-dashboard-ed">
                                <?php //echo Html::a(Html::img(Yii::getAlias('@uploads').'/Images/Fin.png', ['class'=>'img-responsive-ed']),['/site/hcid', 'id' =>25]); ?>
                                <span style="margin: -70px 0px 0 20px; position: absolute">Officer on Special Duty</span>
                            </i>-->
                            
                            <span class="toolsub-dashboard">ED</span>
                            <?php } ?>
                            
                            
                        </div> 
                </div>
                
                
                
                
                
                
                
                
                
                
                        <!--    <div class="row vertical-center-row">
                                <div class="col-lg-12 rowtable project-head-ed">
                                    <br/><br/><br/><br/><br/><br/><br/><br/><br/>                                        <!--<div class="project-information-dashboard-ed">PROJECT MANAGEMENT INFORMATION SYSTEM</div>
                                        <div class="ed-project-dashboard">CENTRE FOR DEVELOPMENT OF ADVANCED COMPUTING</div>                

                                </div>
                                

                                <div class="text-center col-md-12 col-md-offset-0">

                                    <input id='check' type='checkbox' checked disabled>
                                    <label class='main' for='check'></label>


                                    <div class="dashboard toolimg"> 

                                        <!--<i class="icons-dashboard" >
                                            <?php echo Html::a(Html::img(Yii::getAlias('@uploads'). '/Images/proposal_P_32.png',['class'=>'img-responsive']),['/site/hcid', 'id' => 3]);?> 
                                            <span style="margin: 5px 10px 0 -30px; position: absolute">STD </span>

                                        </i>

                                        <i class="icons-dashboard">
                                            <?php echo Html::a(Html::img(Yii::getAlias('@uploads').'/Images/projects_P_32.png', ['class'=>'img-responsive']),['/site/hcid', 'id' => 1]); ?> 
                                            <span style="margin: 5px 10px 0 -30px; position: absolute">HCID</span>
                                        </i> 

                                        <i class="icons-dashboard">
                                            <?php echo Html::a(Html::img(Yii::getAlias('@uploads').'/Images/manpower_P_32.png', ['class'=>'img-responsive']),['/site/manpowerdetail']); ?>
                                            <span style="margin: 5px 10px 0 -30px; position: absolute">EECD</span>
                                        </i>

                                        <i class="icons-dashboard">
                                            <?php echo Html::a(Html::img(Yii::getAlias('@uploads').'/Images/capital_P_32.png', ['class'=>'img-responsive']),['/site/capital']); ?>
                                            <span style="margin: 5px 10px 0 -20px; position: absolute">HIED</span>
                                        </i>								

                                        <i class="icons-dashboard">
                                            <?php echo Html::a(Html::img(Yii::getAlias('@uploads').'/Images/financial_P_32.png', ['class'=>'img-responsive']),['/site/financial']); ?>
                                            <span style="margin: 5px 10px 0 -25px; position: absolute">eGovernance</span>
                                        </i>	

                                        <i class="icons-dashboard">
                                            <?php echo Html::a(Html::img(Yii::getAlias('@uploads').'/Images/reports_P_32.png', ['class'=>'img-responsive']),['site/hcid', 'id' => 5]); ?>
                                            <span style="margin: 5px 10px 0 -25px; position: absolute">Network Information Security Operations</span>
                                        </i>

                                        <div>
                                            <span class="tools-dashboard">TOOLS</span>
                                        </div>
                                    </div>



                                </div>
                            </div>     -->       
            </div>
        </div>   
    </div>        
            
    
</div>
