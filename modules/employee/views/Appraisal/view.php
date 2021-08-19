<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Appraisal */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Appraisals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="appraisal-view">

<?php $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid); 
     $appid=$model->id;
     $sdate=$model->sdate;
     
// Calculates the difference between DateTime objects 
 
     ?>
 <!--  <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>  -->

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
            'request_comment:ntext',
            'feedback',
            'rating',
       
        ],
    ]) ?>

     <?php 
     if ($model->status==1) 
{
 if ($model->request_status==1) 
{
 ?> <strong class="danger" style="color: red">Status: Request Resubmitted for Edit form  </strong> <?php
}
elseif($model->request_status==0) {
 ?> <strong class="danger" style="color: red">Status: Sent to FLA  </strong> <?php
}
elseif($model->request_status==3) {
 ?> <strong class="danger" style="color: red">Status: Sent to FLA  </strong> <?php
}
}
elseif ($model->status==2) 
{
  if ($model->request_status==1) 

{
 ?> <strong class="danger" style="color: red">Status: Request Resubmitted for Edit form  </strong> <?php
}
elseif ($model->request_status==0 || $model->request_status==3 ) 
{
  ?> <strong class="danger" style="color: red">Status: Sent to SLA  </strong> <?php
  }

}
elseif($model->status==3)
{
   if($model->request_status==0 || $model->request_status==3)
   {
     ?> <strong class="danger" style="color: red">Status: Accepted  </strong> <?php }
  
   elseif($model->request_status==1 )
   { ?> <strong class="danger" style="color: red">Status: Request For Resubmit  </strong> <?php }
 
}

     
?>


     
    
        <?php 


          if ($model->status==0) {
           

         ?>
          <form method="post" action="<?=Yii::$app->homeUrl?>employee/appraisal/appubmit?securekey=<?=$menuid.'&id='.$appid ?>">
        <a style="padding: 6px" class="btn btn-outline-success btn-sm btn-xs" href="<?=Yii::$app->homeUrl?>employee/appraisal/update?securekey=<?=$menuid.'&id='.$appid ?>">Edit</a>
         <button type="submit" name="save" value="Submit" class="btn btn-outline-success btn-sm checkform sl">Submit</button>
          </form>
    <?php }
    elseif ($model->request_status==2 ) { ?>
        <form method="post" action="<?=Yii::$app->homeUrl?>employee/appraisal/appubmit?securekey=<?=$menuid.'&id='.$appid ?>">
            <a style="padding: 6px" class="btn btn-outline-success btn-sm btn-xs" href="<?=Yii::$app->homeUrl?>employee/appraisal/update?securekey=<?=$menuid.'&id='.$appid ?>">Edit</a>
        <a style="padding: 6px" class="btn btn-outline-success btn-sm btn-xs" href="<?=Yii::$app->homeUrl?>employee/appraisal/updaterevoke?securekey=<?=$menuid.'&id='.$appid ?>">Submit</a>
         
          </form>
       
  <?php  } 
    else
    {

     $cuudate=date('Y-m-d H:i:s');
        $datetime1 = date_create($cuudate); 
 $datetime2 = date_create($sdate); 
  $interval = date_diff($datetime1, $datetime2); 
    $day=$interval->format('%R%a');
    if($day<10 && $model->request_status==0)  { ?>
         <form method="post" id="cmt" action="<?=Yii::$app->homeUrl?>employee/appraisal/requestchange?securekey=<?=$menuid.'&id='.$appid ?>">
        <div class="col-sm-12 ">
            <textarea class="form-control form-control-sm" rows="4" name="request_comment" placeholder="Reason for change"></textarea>
            
    
    </div>
    <div class="col-sm-12">
       <button type="submit" name="save" value="Submit" class="btn btn-outline-success btn-sm checkform sl">Request for changing</button>
   </div>
 </form>
    <?php }
}
    ?>


</div>
