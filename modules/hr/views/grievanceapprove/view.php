<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Grievance */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Grievances', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="grievance-view">

   <?php $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid); 
     $appid=$model->id;
     $sdate=$model->sdate;
     $role=Yii::$app->user->identity->role; 
     
// Calculates the difference between DateTime objects 
 
     ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title:ntext',
            'description:ntext',
            'complaint_type',
         
            'sdate',
            'lastupdate',
            'createdby',
            'docketno',
            [
         'attribute' => 'filename',
         'label' => 'File',
         'value' => function ($model) { if(!empty($model->filename)){
              return Html::a('Download The File', Yii::$app->homeUrl.'other_files/Grievance_doc/'.$model->filename,['target'=>'_blank']); }
              else { return Html::a('N.A') ; }
          },
          'format' => 'raw',
         ],
         'authority1_comment',
         'authority2_comment',
         
        ],
    ]) ?>
 <!--    <div class="progress">
  <div class="progress-bar" role="progressbar" style="width: 24%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">User</div>
  <div class="progress-bar bg-success" role="progressbar" style="width: 25%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100">FLA</div>
  <div class="progress-bar bg-info" role="progressbar" style="width: 25%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">SLA</div>
  <div class="progress-bar " role="progressbar" style="width: 25%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">Complete</div>
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
elseif($model->status==5){
?> <strong class="danger" style="color: red">Status: Back to employee </strong> <?php }
elseif($model->status==6)
{  ?> <strong class="danger" style="color: red">Status: Withdraw  </strong> <?php
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
    ?> <strong class="danger" style="color: red">Status: Sent to FLA  </strong> <?php   }
}
elseif($model->status==5){
?> <strong class="danger" style="color: red">Status: Back to employee </strong> <?php }
elseif($model->status==6)
{  ?> <strong class="danger" style="color: red">Status: Withdraw  </strong> <?php
  }
}
   
?>





    <?php
     
    

if($role==4) {
  if($model->status==1)
  {
   $url =  'grievanceapprove/forword?securekey=' . $menuid.'&id='.$model->id ; 
   $form = ActiveForm::begin([ 'action' => [$url], 'method' => 'post' ]);

  ?>

      
         <div class="form-group">
<label class="control-label" for="grievance-description">Comment</label>
 <textarea class="form-control" name="comment" rows="4" cols="50"></textarea>
 <?php if($model->request_status==0 || $model->request_status==3) { ?>
    <button type="submit" name="Forword" value="Submit" class="btn btn-outline-success btn-sm checkform sl">Forword</button> <?php } ?>
   <?php if($model->request_status==1) { ?>
    <button type="submit" name="Backtoemp" value="Submit" class="btn btn-outline-success btn-sm checkform sl">Back to Employee</button>
 <?php } ?>

</div>
          
    <?php 
 ActiveForm::end(); 
}

elseif ($model->status==4) { ?> <strong class="danger" style="color: red">Status: Rejected </strong> <?php }

 }      
    elseif($role==2) 
  {
    if($model->status==2)
         { 
            $urlaccp =  'grievanceapprove/forword?securekey=' . $menuid.'&id='.$model->id ; 
            $form = ActiveForm::begin([ 'action' => [$urlaccp], 'method' => 'post' ]);
          ?> 

<div class="form-group">
<label class="control-label" for="grievance-description">Comment</label>
 <textarea class="form-control" name="comment" rows="4" cols="50"></textarea>
 
<input type="hidden" name="sts">
<button type="submit" name="Accept" value="Submit" class="btn btn-outline-success btn-sm checkform sl">Accept</button>
<button type="submit" name="Reject" value="Submit" class="btn btn-outline-success btn-sm checkform sl">Reject</button>
<?php if($model->request_status==1) { ?>
    <button type="submit" name="Backtoemp" value="Submit" class="btn btn-outline-success btn-sm checkform sl">Back to Employee</button>
 <?php } ?>

</div>
        

    <?php  ActiveForm::end(); 


     }
     elseif ($model->status==4) { ?> <strong class="danger" style="color: red">Status: Rejected </strong> <?php }

     } ?>    


 

</div>
