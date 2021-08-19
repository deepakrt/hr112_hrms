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
<table class="container">
    
    <tr class="">
        <td class="text-center" >
            <div class="col-lg-12 container container-table" >                                    
                    <div class="col-lg-12 rowtable">
                        <div class="col-lg-4 toprow"></div>
                        <div class="col-lg-7 project-head">
                            <div class="project-information-dashboard"></div>
                            <div class="multilingual-project-dashboard"></div>                
                        </div>
                    </div>
                    
                    <div class="text-center col-md-11 col-md-offset-0">
                        
                        <input id='check' type='checkbox' checked disabled>
                        <label class='main' for='check'></label>


                        <div class="dashboard toolimg" > 

                            <i class="icons-dashboard">
                                <?php //echo Html::a(Html::img(Yii::$app->homeUrl. 'images/pmis/projects_P_32.png', ['class'=>'img-responsive']),["projectdetail/index?securekey=$menuid"]); ?> 
                                <?php echo Html::a(Html::img(Yii::$app->homeUrl. 'images/pmis/projects_P_32.png', ['class'=>'img-responsive']),['manageproject/ordermaster/index?securekey='.$menuid]); ?> 
                                <span style="margin: 5px 10px 0 -30px; position: absolute">PROJECTS</span>
                            </i> 

                            <i class="icons-dashboard">
                                <?php echo Html::a(Html::img(Yii::$app->homeUrl. 'images/pmis/capital_P_32.png', ['class'=>'img-responsive']),["projects?securekey=$menuid"]); ?>
                                <span style="margin: 5px 10px 0 -20px; position: absolute">CAPITAL	</span>
                            </i>									

                            <i class="icons-dashboard">
                                <?php echo Html::a(Html::img(Yii::$app->homeUrl. 'images/pmis/financial_P_32.png', ['class'=>'img-responsive']),['/site/financial']); ?>
                                <span style="margin: 5px 10px 0 -25px; position: absolute">FINANCIAL</span>
                            </i>	

                            <i class="icons-dashboard">
                                <?php echo Html::a(Html::img(Yii::$app->homeUrl. 'images/pmis/reports_P_32.png', ['class'=>'img-responsive']),['site/projectsummary']); ?>
                                <span style="margin: 5px 10px 0 -25px; position: absolute">REPORTS</span>
                            </i>

                            <div>
                                <span class="tools-dashboard">TOOLS</span>
                            </div>
                        </div>

                        <input id='check' type='checkbox' checked disabled>
                        <label class='main-alert' for='check'></label>


                        <div class="dashboardalert" > 

                            <i class="icons-dashboard" >
                                <?php /*$p1 = Yii::$app->projectcls->AlertProposalCount();?>
                                <?php 
                                    if($p1 >0){
                                        echo Html::a('<span class="badge">'.$p1.'</span>'. Html::img(Yii::getAlias('@uploads'). '/Images/proposal_P_32w.png',['class'=>'img-responsive']),['proposal/alert']);
                                    } else {
                                        echo Html::a(Html::img(Yii::getAlias('images'). '/pmis/proposal_P_32w.png',['class'=>'img-responsive']),['proposal/alert']);
                                    }*/
                                echo Html::a(Html::img(Yii::$app->homeUrl. 'images/pmis/proposal_P_32w.png',['class'=>'img-responsive']),['proposal/alert']);
                                ?>                                    
                                <span style="margin: 5px 10px 0 -30px; position: absolute">PRE-PROJECT</span>                                    
                            </i>

                            <i class="icons-dashboard">                                
                                <?php /*$m2 = Yii::$app->projectcls->AlertPrjCompletionCert()->count() + Yii::$app->projectcls->AlertPrjApreciationCert()->count();?>
                                <?php 
                                    if($m2 >0){
                                        echo Html::a('<span class="badge">'.$m2.'</span>'. Html::img(Yii::getAlias('@uploads').'/Images/projects_P_32w.png', ['class'=>'img-responsive']),['projects/alert']); 
                                    } else {
                                        echo Html::a(Html::img(Yii::getAlias('images').'/pmis/projects_P_32w.png', ['class'=>'img-responsive']),['projects/alert']); 
                                    }*/ 
                                echo Html::a(Html::img(Yii::$app->homeUrl. 'images/pmis/projects_P_32w.png', ['class'=>'img-responsive']),['projects/alert']); 
                                ?> 
                                <!--<span class = "badge"><?php //echo Yii::$app->projectcls->AlertPrjCompletionCert()->count() + Yii::$app->projectcls->AlertPrjApreciationCert()->count();?></span>-->
                                <span>PROJECTS </span>
                            </i>

                            <i class="icons-dashboard">
                                <?php /*$m1 = Yii::$app->projectcls->AlertManpowerProfileCount();?>
                                <?php 
                                    if($m1 >0){
                                        echo Html::a('<span class="badge">'.$m1.'</span>'. Html::img(Yii::getAlias('@uploads').'/Images/projects_P_32w.png', ['class'=>'img-responsive']),['manpower/alert']); 
                                    } else {
                                        echo Html::a(Html::img(Yii::getAlias('images').'/pmis/projects_P_32w.png', ['class'=>'img-responsive']),['manpower/alert']); 
                                    }*/ 
                                echo Html::a(Html::img(Yii::$app->homeUrl. 'images/pmis/projects_P_32w.png', ['class'=>'img-responsive']),['manpower/alert']); 
                                ?> 
                                <?php //echo Html::a('<span class="badge"></span>'. Html::img(Yii::getAlias('@uploads').'/Images/manpower_P_32w.png', ['class'=>'img-responsive']),['#']); ?>
                                <span>MANPOWER </span>
                            </i>

                            <i class="icons-dashboard">
                                <?php //echo Html::a('<span class="badge"></span>'. Html::img(Yii::getAlias('@uploads').'/Images/capital_P_32w.png', ['class'=>'img-responsive']),['#']); ?>
                                <?php /*$c1 = Yii::$app->projectcls->AlertCapital()->count();?>
                                <?php 
                                    if($c1 >0){
                                        echo Html::a('<span class="badge">'.$c1.'</span>'. Html::img(Yii::getAlias('@uploads').'/Images/capital_P_32w.png', ['class'=>'img-responsive']),['capitalmaster/alert']); 
                                    } else {
                                        echo Html::a(Html::img(Yii::getAlias('images').'/pmis/capital_P_32w.png', ['class'=>'img-responsive']),['capitalmaster/alert']); 
                                    }*/ 
                                echo Html::a(Html::img(Yii::$app->homeUrl. 'images/pmis/capital_P_32w.png', ['class'=>'img-responsive']),['capitalmaster/alert']); 
                                ?> 
                                <span style="margin: 5px 10px 0 -20px; position: absolute">CAPITAL</span>
                            </i>									

                            <i class="icons-dashboard">
                                <?php /*$b1 = Yii::$app->projectcls->AlertBill()->count();?>
                                <?php 
                                    if($b1 >0){
                                        echo Html::a('<span class="badge">'.$b1.'</span>'. Html::img(Yii::getAlias('@uploads').'/Images/financial_P_32w.png', ['class'=>'img-responsive']),['billmaster/alert']); 
                                    } else {
                                        echo Html::a(Html::img(Yii::$app->homeUrl. 'images/pmis/financial_P_32w.png', ['class'=>'img-responsive']),['billmaster/alert']); 
                                    } */
                                echo Html::a(Html::img(Yii::$app->homeUrl. 'images/pmis/financial_P_32w.png', ['class'=>'img-responsive']),['billmaster/alert']); 
                                ?> 
                                <!--<?php //echo Html::a('<span class="badge">'.$b1.'</span>'. Html::img(Yii::getAlias('@uploads').'/Images/financial_P_32w.png', ['class'=>'img-responsive']),['billmaster/alert']); ?>-->
                                <span style="margin: 5px 10px 0 -25px; position: absolute">FINANCIAL</span>
                            </i>	

                            <div>
                                <span class="tools-dashboardalert">ALERT</span>
                            </div>
                        </div>

                    </div>


                </div>            
            </div>
        </td>
    </tr>
    <!--<tr>
        <td>
            <div class="col-lg-12">
                        <div class="col-lg-4"></div>
                        <div class="col-lg-7">                
                            <div class="icons-dashboardD text-right">
                                    <?php echo Html::a('ASSIGNED TASK', ['/taskassign/index']);?>
                                    <?php echo '</br></br>'.Html::a('PROJECT PROGRESS', ['/dailyprogress/index']);?>
                                    <?php echo '</br></br>'.Html::a('CORRESPONDENCE REGISTER', ['/dispatch/index']);?>
                            </div>
                        </div>
                    </div>
        </td>
    </tr>-->
</table>