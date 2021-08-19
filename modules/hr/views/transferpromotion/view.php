<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model app\models\Transferpromotion */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Transferpromotions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
  $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
    $menuid = Yii::$app->utility->encryptString($menuid); 
\yii\web\YiiAsset::register($this);
 $appid=$model->id;
  $role=Yii::$app->user->identity->role; 
?>
<div class="transferpromotion-view">   
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [            
            'title:ntext',
            'remarks:ntext',
            'sdate',            
            'createdby',            
            'action_emp',
            'is_active',
        ],
    ]) ?>

</div>
<?php 
if ($role==2) 
{
if ($model->status==2) {
    ?> <strong class="danger" style="color: red">Status: Accepted  </strong> <?php 
   
} elseif ($model->status==3) {
    ?> <strong class="danger" style="color: red">Status: Rejected  </strong> <?php 
}
}
elseif ($role==4) {
 if ($model->status==1) 
{
    ?> <strong class="danger" style="color: red">Status: Request submitted</strong> <?php
}
   elseif ($model->status==2) 
{
    ?> <strong class="danger" style="color: red">Status: Accepted  </strong> <?php 
}
 elseif ($model->status==4) 
{
    ?> <strong class="danger" style="color: red">Status: Resubmitted  </strong> <?php 
}

}

?>

<?php 
 $url =  'transferpromotion/status?securekey=' . $menuid.'&id='.$appid ;
$form = ActiveForm::begin([ 'action' => [$url], 'method' => 'post' ]); 
if($role==2)
{

 ?>
<div class="col-sm-12">
     <div class="form-group">
        <?php if($model->status==1 || $model->status==4) { ?>
       <input name="Accepted" type="submit" class="btn btn-success btn-sm sl" value="Accepted" /> 
        <input name="Rejected" type="submit" class="btn btn-success btn-sm sl" value="Rejected" />
         </div> 
    <?php }
} elseif($role==4) 
    { 
        if($model->status==3) { ?>
        <input name="Reapply" type="submit" class="btn btn-success btn-sm sl" value="Reapply" /> 
    <?php } } ?>
   
</div>
 <?php ActiveForm::end();  ?>
