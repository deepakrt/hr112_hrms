<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Appraisal */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Appraisals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
 $role=Yii::$app->user->identity->role; 
?>
<div class="appraisal-view">





   <!--  <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p> -->

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [

            
            'title:ntext',
           [
         'attribute' => 'document',
         'label' => 'File',
         'value' => function ($model) { if(!empty($model->document)){
              return Html::a('Download The File', Yii::$app->homeUrl.'other_files/Appraisal_doc/'.$model->document,['target'=>'_blank']); }
              else { return Html::a('N.A') ; }
          },
          'format' => 'raw',
         ],
            'job_description:ntext',   
             'achievement:ntext',        
            'sdate',
            'uploadedby',
            'lastupdate',
            'feedback',
            'rating',
        ],
    ]) ?>
 <!--    <div class="row">
        <div class="col-sm-12">Title</div>
        <div class="col-sm-12"></div>
        <div class="col-sm-12"></div>
        <div class="col-sm-12"></div>
        <div class="col-sm-12"></div>
        


    </div> -->



   <?php

if($role==4)
{
if($model->status==2)
{
    if($model->request_status==0 || $model->request_status==3 )
    {
?> <strong class="danger" style="color: red">Status: Sent to SLA  </strong> <?php
    }
    elseif($model->request_status==1)
    {
?> <strong class="danger" style="color: red">Status:  Request Resubmitted for Edit form  </strong> <?php
    }
 
}
elseif($model->status==3)
{
    if($model->request_status==1)
    {
?> <strong class="danger" style="color: red">Status:  Request Resubmitted for Edit form  </strong> <?php
    }
    elseif($model->request_status==0 || $model->request_status==3)
    {
?> <strong class="danger" style="color: red">Status: Accepted </strong> <?php

    }
}
}
elseif($role==2)
{
if($model->status==3)
{
    if($model->request_status==0 || $model->request_status==3 )
    {
        ?> <strong class="danger" style="color: red">Status: Accepted </strong> <?php
    }
}
elseif($model->status==1)
{
  if($model->request_status==0 || $model->request_status==3 )  
  {
    ?> <strong class="danger" style="color: red">Status: Sent to FLA  </strong> <?php
  }
}
}

     $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
    $menuid = Yii::$app->utility->encryptString($menuid); 
    $appid=$model->id;
    
    $url =  'appraisalapprove/appupdbyauth?securekey=' . $menuid.'&id='.$appid ;
    $url1 = Yii::$app->homeUrl.'hr/appraisalapprove/apprevoke?securekey=' . $menuid.'&id='.$appid ;

 $form = ActiveForm::begin([ 'action' => [$url], 'method' => 'post' ]);
  if($model->request_status!=2) {
 if($role==4)
 {

    if($model->status==1)
    { ?>
        <div class="row">
    <div class="col-sm-6">
    <?=  $form->field($model, 'rating')->dropDownList(['1' => '1 star', '2' => '2 star', '3' => '4 star', '4' => '4 star', '5' => '5 star'],['class'=>'form-control form-control-sm']); ?>
    </div> <div class="col-sm-6"></div>
    <div class="col-sm-12">
    <?= $form->field($model, 'feedback')->textarea(['rows' => 6]) ?>
    </div>
    <div class="col-sm-12">
<?php if($model->request_status==0 || $model->request_status==3) { ?>
    <button type="submit" name="save" value="Submit" class="btn btn-outline-success btn-sm checkform sl">Forword</button> <?php } ?>
   <?php if($model->request_status==1) { ?>
    <a class="btn btn-outline-success btn-sm checkform sl" href="<?=$url1?>" class="btn btn-success btn-sm mybtn" title="Revoke">Back to Employee</a>
 <?php } ?>
    </div>
    </div> <?php 
    }

 }
 elseif($role==2)
 {
     if ($model->status==2)
     { ?> <button type="submit" name="save" value="Submit" class="btn btn-outline-success btn-sm checkform sl">Accept</button> <?php } 
     elseif ($model->status==3) {
        if($model->request_status==1)
        { ?>
 
     <a class="btn btn-outline-success btn-sm checkform sl" href="<?=$url1?>" class="btn btn-success btn-sm mybtn" title="Revoke">Back to Employee</a>
 <?php }
        }
 }



}
else
{ ?> <strong class="danger" style="color: red">Status: Sent to Employee </strong> <?php }
 
  
 ActiveForm::end(); ?>
</div>
