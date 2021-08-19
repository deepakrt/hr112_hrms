<?php

/* @var $this yii\web\View */
use app\modules\manageproject\models\Projectdetail;
use app\modules\manageproject\models\Manpower;
use app\modules\manageproject\models\Manpowermapping;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

$this->title = 'Project Dashboard';
$this->params['breadcrumbs'][] = ['label' => 'Home', 'url' => ['/site/index']];
//$this->params['breadcrumbs'][] = $this->title;

//$this->params['breadcrumbs'][] = ['label' => 'Dashboard', 'url' => ['dashboard']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="site-clientdetail row-eq-height">
    
    <div class="col-lg-10 right-header">
        
        <div class="row proposal-detail">
            <div class="col-lg-8 text-left mintable form-height">                    
                <?php
                    //$model = new Manpowermapping();
                ?>
                <?= $this->render('/projectdetail/dashboard', ['model' => $model,]) ?>
            </div>
            <div class="col-lg-4 form-height">
                <?php $model = new Manpower(); ?>
                <?//= $this->render('/manpower/salary', ['model' => $model,]) ?>                    
            </div>
        </div>   
        </div>        
            
    
</div>
