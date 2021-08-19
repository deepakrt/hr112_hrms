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

$this->title = 'Education and Traning Division';
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
            <div class="col-lg-4 prjsession"></div>
        </div>        
        <div class="mintable">        
            <p class="record-heading">
                SUMMER TRAININGS                             
                <span style="float:right"></span>
            </p> 
            <table class="table table-responsive table-bordered table-striped table-hover fixed_headers_md">
                <thead class="font-weight-bold">
                    <td>Course</td>
                    <td>Technology</td>
                    <td>Start Date</td>
                    <td>End Date</td>
                    <td>Duration</td>
                    <td>Fees</td>
                    <td>No of Seats</td>
                    <td>Occupancy</td>
                </thead>
                <?php if(edudata::getCourses('Summer Training')  != NULL){
                foreach (edudata::getCourses('Summer Training') as $training) {?>
                    <tr>
                        <td><?=$training['technology_code'] ?></td>
                        <td><?=$training['technology_name'] ?></td>
                        <td><?=Yii::$app->formatter->asDate($training['start_date']) ?></td>
                        <td><?=Yii::$app->formatter->asDate($training['end_date']) ?></td>
                        <td><?=Yii::$app->formatter->asTime($training['start_time']) .' - '. Yii::$app->formatter->asTime($training['end_time']) ?></td>                                        
                        <td><?=Yii::$app->formatter->asCurrency($training['fees']) ?> <br/> (Installment :  <?=$training['installment'] ?>)</td>
                        <td><?=$training['no_of_seats'] ?></td>
                        <td></td>
                    </tr>
                <?php } 
                }?>                                    
            </table>
        </div> 
        <div class="mintable">        
            <p class="record-heading">
                INDUSTRIAL TRAININGS                             
                <span style="float:right"></span>
            </p> 
            <table class="table table-responsive table-bordered table-striped table-hover fixed_headers_md">
                <thead class="font-weight-bold">
                    <td>Course</td>
                    <td>Technology</td>
                    <td>Start Date</td>
                    <td>End Date</td>
                    <td>Duration</td>
                    <td>Fees</td>
                    <td>No of Seats</td>
                    <td>Occupancy</td>
                </thead>
                <?php  if(edudata::getCourses('Industrial Training')  != NULL){
                foreach (edudata::getCourses('Industrial Training') as $training) {?>
                    <tr>
                        <td><?=$training['technology_code'] ?></td>
                        <td><?=$training['technology_name'] ?></td>
                        <td><?=Yii::$app->formatter->asDate($training['start_date']) ?></td>
                        <td><?=Yii::$app->formatter->asDate($training['end_date']) ?></td>
                        <td><?=Yii::$app->formatter->asTime($training['start_time']) .' - '. Yii::$app->formatter->asTime($training['end_time']) ?></td>                                        
                        <td><?=Yii::$app->formatter->asCurrency($training['fees'], 'INR') ?> <br/> (Installment :  <?=$training['installment'] ?>)</td>
                        <td><?=$training['no_of_seats'] ?></td>
                        <td></td>
                    </tr>
                <?php } 
                }?>
            </table>
        </div>
    </div>
</div>