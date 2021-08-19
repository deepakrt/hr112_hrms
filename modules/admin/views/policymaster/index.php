<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PolicyMasterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Policy Masters';
$this->params['breadcrumbs'][] = $this->title;
$lists = Yii::$app->utility->get_policy_master(null);
?>
<div class="col-sm-12 text-right">
    <a style="margin-bottom:10px;" href="<?=Yii::$app->homeUrl?>admin/policymaster/create?securekey=<?=$menuid?>" class="btn btn-success btn-sm mybtn">Add New Polices </a>
    
</div>
<div class="col-sm-12">
<table id="dataTableShow" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Sr. No.</th>
            <th>Name</th>
           
            <th>Is active</th>
            <th>Edit</th>
        </tr>
    </thead>
    <tbody>
        <?php  $count=1;
        if(!empty($lists)){
            $i =1;
            foreach($lists as $l){ 
                  
            $encry = ($l['id']);
            $editUrl = Yii::$app->homeUrl."admin/policymaster/update?securekey=$menuid&id=$encry";
                        if($l['is_active'] == 'Y'){
                            $is = "Yes";
                        }elseif($l['is_active'] == 'N'){
                            $is = "No";
                        }else{
                            $is = "-";
                        }
            ?>
            <tr>
            <td><?=$i?> <?php //echo $count; ?></td>
            <td><?=$l['police_name']?></td>
            
            <td><?=$is?></td>
            <td><a href="<?=$editUrl?>" class="btn btn-info btn-sm btn-xs">Edit</a></td>
            </tr>   
        <?php $i++; $count++;  }
        }
        ?>
    </tbody>
  
</table>
</div>