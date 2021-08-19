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

<div class="">
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
                <div class="col-lg-4 prjsession">&nbsp;</div>
            </div> 
            <div class="mintable">
                <p class="record-heading">Projects Details</p>

                    <?php 
                        $query = Manpowermapping::find()-> where(['manpowerid'=>Yii::$app->user->identity->manpowerid]);
                        $dataProvider = new ActiveDataProvider([
                            'query' => $query,
                            'pagination' => [
                                'pageSize' => 10,
                            ],
                            'sort' => [
                                'attributes' => ['projectname'],
                            ],
                        ]);
                    ?> 
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        //'filterModel' => $searchModel,
                        'showHeader' => FALSE, 
                        //'layout' => "{items}\n{pager}",
                        'tableOptions' => ['class' => 'table table-responsive table-bordered table-striped table-hover'],
                        'layout' => "{items}\n{pager}",
                        'columns' => [                         
                            [
                                //'class' => 'yii\grid\DataColumn',
                                //'header' => 'List of Projects',                
                                'format' => 'raw',
                                'value' => function ($dataProvider) {
                                    $txt ='';
                                    if($dataProvider->getOrderdetail()->exists() && Yii::$app->projectcls->ProjectDetails1($dataProvider->orderdetail->id) !=NULL){
                                        if($dataProvider->getOrderdetail()->exists()){
                                            $txt .= Html::a($dataProvider->orderdetail->projectname, ['/projects/view', 'id' => Yii::$app->projectcls->ProjectDetails1($dataProvider->orderdetail->id)->id]);
                                        }

                                        if($dataProvider->getOrdertype()->exists() && $dataProvider->ordertype->getProjectType()->exists()){
                                            $txt .=' ('.$dataProvider->ordertype->projectType->type.'),</br>';
                                        }

                                        if($dataProvider->getOrderdetail()->exists() && $dataProvider->orderdetail->getClientdetail()->exists()){
                                            $txt .=Html::a($dataProvider->orderdetail->clientdetail->deptName, ['/client-detail/view', 'id' => $dataProvider->orderdetail->clientdetail->id,  'o'=>$dataProvider->orderdetail->id]);
                                        }
                                    }
                                    return $txt;                                                 
                                },
                            ],                        
                            [           
                                //'class' => 'yii\grid\DataColumn',
                                //'header' => 'Team Lead',    
                                'format' => 'raw',
                                'value' => function ($dataProvider) {
                                    $txt = '';
                                    if($dataProvider->getOrderdetail()->exists() && Yii::$app->projectcls->ProjectDetails1($dataProvider->orderdetail->id) !=NULL){
                                        if(!empty($dataProvider->getOrderdetail())){
                                            //$txt .=Html::a('Detail of Meetings', ['meetings/index', 'id' => $dataProvider->mapmeetings->member->id]);
                                            $txt .=Html::a('Detail of Meetings', ['meetings/index', 'id' => $dataProvider->orderdetail->id]);
                                            $txt .= '</br></br>' .Html::a('Report Bug' , ['/bugmaster/create', 'id' => $dataProvider->orderdetail->id]).'</br>';
                                            $d = Yii::$app->projectcls->SelectBugmasterCnt($dataProvider->orderdetail->id);
                                            $d += Yii::$app->projectcls->SelectBugmasterCntR($dataProvider->orderdetail->id);
                                            if($d >0){
                                                $txt .= '</br>' .Html::a('View Bugs'.'<span class="badge">'.$d .'</span>' , ['/bugmaster/index', 'id' => $dataProvider->orderdetail->id]);
                                            } else {
                                                $txt .= '</br>' .Html::a('View Bugs' , ['/bugmaster/index', 'id' => $dataProvider->orderdetail->id]);
                                            }
                                            //$txt .= '</br>' .Html::a('View Bugs' , ['/bugmaster/index', 'id' => $dataProvider->orderdetail->id]);
                                        }

                                        if(!empty($dataProvider->getOrderdetail())){
                                            if(!Yii::$app->projectcls->MilestoneDetailsOrder(Yii::$app->request->get('id')) == NULL){
                                                $txt .='</br></br>'.Html::a('Milestones', ['milestone/project', 'id' => $dataProvider->orderdetail->id]);
                                            }
                                            $txt .= '</br></br>'.Html::a('Reminders', ['reminders/index', 'id'=>$dataProvider->orderdetail->id])
                                                    .'</br></br>'.Html::a('GIGW/Security Audits', ['auditmaster/index', 'id'=>$dataProvider->orderdetail->id]);
                                            if(Yii::$app->projectcls->SelectCapitalmaster(Yii::$app->projectcls->ProjectDetails1($dataProvider->orderdetail->id)->id)!=null){
                                                $txt .='</br></br>'.Html::a('Capital Details', ['/capitalmaster/index', 'id' => Yii::$app->projectcls->ProjectDetails1($dataProvider->orderdetail->id)->id]);
                                            } else{
                                                $txt .='</br></br>Capital Details';
                                            }
                                                        
                                            if(Yii::$app->projectcls->Projectwithorder($dataProvider->orderdetail->id) !=NULL)
                                                $txt .='</br></br>'.Html::a('Purchase Details', ['/capitalpurchase/index', 'id' => Yii::$app->projectcls->Projectwithorder($dataProvider->orderdetail->id)->id]);
                                        }
                                    }
                                                 
                                    return $txt; //Html::a('Assigned task', ['/taskassign/index'])
                                    //.'</br></br>'.
                                },
                            ],                                                 
                        ],
                    ]); ?>
            </div>
        </div>
    </div>
    <div class="nillmargin ">
        <div class="col-lg-2 col-sm-2  left-header outerfooter">
            <footer class="footer">
                <p>&copy; <?= Yii::t('app', Yii::$app->name) ?> <?= date('Y') ?></p>       
            </footer>
        </div>
        <!--<div class="col-lg-10  right-header "></div>-->
        <div class="col-lg-10 col-sm-10 "></div>
    </div>
</div>

