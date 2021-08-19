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
<div class="container">
    <div class="row breadcrumb text-right">
        <?php $form = \yii\widgets\ActiveForm::begin(['action' =>['cdacdept/create'], 'id' => 'city-form', 'method' => 'post','fieldConfig' => ['labelOptions' => ['class' => 'client-form-label'], 'inputOptions' => ['class' => 'client-form-input'],]]); ?>
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
                <?php  \yii\widgets\ActiveForm::end();?>
    </div>
    <div class="row">
        <div class="col-lg-12 container container-table mainrow" >
            <div class="col-lg-12 rowtable project-head-ed">
                <div class="project-information-dashboard-ed">PROJECT MANAGEMENT INFORMATION SYSTEM</div>
                <div class="ed-project-dashboard">CENTRE FOR DEVELOPMENT OF ADVANCED COMPUTING</div>                
            </div>                    
                    
            <div class="text-center col-md-offset-0 backimg">

                        <input id='check' type='checkbox' checked disabled>
                        <label class='main-ed' for='check'></label>


                        <div class="dashboard-ed toolimg"> 

                            <i class="icons-dashboard-ed" >
                                <?php echo Html::a(Html::img(Yii::getAlias('@uploads'). '/Images/STD.png',['class'=>'img-responsive-ed']),['/site/hcid', 'id' => 3]);?> 
                                <span style="margin: -55px 0px 0 -60px; position: absolute">STD </span>

                            </i>
                            
                            <i class="icons-dashboard-ed">
                                <?php echo Html::a(Html::img(Yii::getAlias('@uploads').'/Images/cstd.png', ['class'=>'img-responsive-ed']),['/site/hcid', 'id' =>21]); ?>
                                <span style="margin: -80px 0px 0 -40px; position: absolute">CSTD</span>
                            </i>
                            
                            <i class="icons-dashboard-ed">
                                <?php echo Html::a(Html::img(Yii::getAlias('@uploads').'/Images/hied.png', ['class'=>'img-responsive-ed']),['/site/hcid', 'id' =>22]); ?>
                                <span style="margin: -70px 0px 0 20px; position: absolute">HIED</span>
                            </i>

                            <i class="icons-dashboard-ed">
                                <?php echo Html::a(Html::img(Yii::getAlias('@uploads').'/Images/hcid.png', ['class'=>'img-responsive-ed']),['/site/hcid', 'id' => 1]); ?> 
                                <span style="margin: -70px 0px 0 20px; position: absolute">HCID</span>
                            </i> 

                            <i class="icons-dashboard-ed">
                                <?php echo Html::a(Html::img(Yii::getAlias('@uploads').'/Images/eecd.png', ['class'=>'img-responsive-ed']),['/site/hcid', 'id' =>20]); ?>
                                <span style="margin: -70px 0px 0 20px; position: absolute">EECD</span>
                            </i>

                            <i class="icons-dashboard-ed">
                                <?php echo Html::a(Html::img(Yii::getAlias('@uploads').'/Images/AAIA.png', ['class'=>'img-responsive-ed']),['/site/hcid', 'id' =>23]); ?>
                                <span style="margin: -60px 0px 0 35px; position: absolute">AAIA</span>
                            </i>

                            <i class="icons-dashboard-ed">
                                <?php echo Html::a(Html::img(Yii::getAlias('@uploads').'/Images/acsd.png', ['class'=>'img-responsive-ed']),['/site/hcid', 'id' =>19]); ?>
                                <span style="margin: 10px 0px 0 20px; position: absolute">ACSD</span>
                            </i>
                            
                            <i class="icons-dashboard-ed">
                                <?php echo Html::a(Html::img(Yii::getAlias('@uploads').'/Images/etd.png', ['class'=>'img-responsive-ed']),['/site/hcid', 'id' =>18]); ?>
                                <span style="margin: 10px 0px 0 20px; position: absolute">ETD</span>
                            </i>                            
                           
                        </div> 
                        
                        <label class='main-ed1' for='check'></label>

                        <div class="dashboard-ed1 toolimg"> 
                            
                            <i class="icons-dashboard-ed">
                                <?php echo Html::a(Html::img(Yii::getAlias('@uploads').'/Images/BDCC.png', ['class'=>'img-responsive-ed']),['/site/hcid', 'id' =>24]); ?>
                                <span style="margin: 10px 0px 0 -50px; position: absolute">BDCC</span>
                            </i>
                            
                            <i class="icons-dashboard-ed">
                                <?php echo Html::a(Html::img(Yii::getAlias('@uploads').'/Images/mtd.png', ['class'=>'img-responsive-ed']),['/site/hcid', 'id' =>17]); ?>
                                <span style="margin: 10px 0px 0 -50px; position: absolute">MTD</span>
                            </i>
                            
                            <i class="icons-dashboard-ed">
                                <?php echo Html::a(Html::img(Yii::getAlias('@uploads').'/Images/mmg.png', ['class'=>'img-responsive-ed']),['/site/hcid', 'id' =>16]); ?>
                                <span style="margin: 10px 0px 0 20px; position: absolute">MMG</span>
                            </i>
                            
                            <i class="icons-dashboard-ed">
                                <?php echo Html::a(Html::img(Yii::getAlias('@uploads').'/Images/AFD.png', ['class'=>'img-responsive-ed']),['/site/hcid', 'id' =>8]); ?>
                                <span style="margin: 10px 0px 0 20px; position: absolute">AFD</span>
                            </i>
                           
                        </div> 
                        
                        
                        <span><?php echo Html::a('C-DAC',['/site/manpowercdac'], ['class' => 'toolsub-Edashboard']); ?></span>

                    </div>
                
        </div>
    </div>
</div>

