<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

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
  <!--   <div class="progress">
  <div class="progress-bar" role="progressbar" style="width: 24%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">User</div>
  <div class="progress-bar bg-success" role="progressbar" style="width: 25%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100">FLA</div>
  <div class="progress-bar bg-info" role="progressbar" style="width: 25%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">SLA</div>
  <div class="progress-bar " role="progressbar" style="width: 25%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">Complete</div>
</div> -->
     <?php 
     if ($model->status==1) 
{
 if ($model->request_status==1) 
{
 ?> <strong class="danger" style="color: red">Status: Request Resubmitted for Edit Grievance  </strong> <?php
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
elseif ($model->status==4) { ?> <strong class="danger" style="color: red">Status: Rejected </strong> <?php }

elseif($model->status==6)
{  ?> <strong class="danger" style="color: red">Status: Withdraw  </strong> <?php
  }   
?>



<?php
if($model->status!=6)
{
  

if($model->status==0) {
  ?>
          <form method="post" action="<?=Yii::$app->homeUrl?>employee/grievance/grievancesubmit?securekey=<?=$menuid.'&id='.$appid ?>">
        <a style="padding: 6px" class="btn btn-outline-success btn-sm btn-xs" href="<?=Yii::$app->homeUrl?>employee/grievance/update?securekey=<?=$menuid.'&id='.$appid ?>">Edit</a>
         <button type="submit" name="save" value="Submit" class="btn btn-outline-success btn-sm checkform sl">Submit</button>
          </form>
    <?php }
 elseif($model->request_status==2 ) { ?>
        <form method="post" action="<?=Yii::$app->homeUrl?>employee/grievance/resubmit?securekey=<?=$menuid.'&id='.$appid ?>">
       <div class="col-sm-12">  <a style="padding: 6px" class="btn btn-outline-success btn-sm btn-xs" href="<?=Yii::$app->homeUrl?>employee/grievance/update?securekey=<?=$menuid.'&id='.$appid ?>">Edit</a>
         <button type="submit" name="resubmit" value="Submit" class="btn btn-outline-success btn-sm checkform sl">Submit</button>
          </form>
       </div>
  <?php  } else 
  {
    
      $cuudate=date('Y-m-d H:i:s');
        $datetime1 = date_create($cuudate); 
 
 $datetime2 = date_create($sdate); 
  $interval = date_diff($datetime1, $datetime2); 
    $day=$interval->format('%R%a');
    if(($day<10) && ($model->request_status==0) && ($model->status!=4) && ($model->status!=3))  { ?>
         <form method="post" id="cmt" action="<?=Yii::$app->homeUrl?>employee/grievance/requestchange?securekey=<?=$menuid.'&id='.$appid ?>">
        <div class="col-sm-12 ">
            <textarea class="form-control form-control-sm" rows="4" name="request_comment" placeholder="Reason for change"></textarea>           
        </div>
    <div class="col-sm-12">
       <button type="submit" name="save" value="Submit" class="btn btn-outline-success btn-sm checkform sl">Request for changing</button>
   </div>
 </form>
  <?php  }
}
    ?>
<?php  
if($model->status!=6 && $model->status!=0 ) {
  ?>
  <form method="post" action="<?=Yii::$app->homeUrl?>employee/grievance/withdraw?securekey=<?=$menuid.'&id='.$appid ?>">
          <div class="col-sm-12">
         <button type="submit" name="Withdraw" value="Submit" class="btn btn-outline-success btn-sm checkform sl">Withdraw</button>
          </form> </div>
        <?php  }  }?>
</div>
