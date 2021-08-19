<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $searchModel app\models\GrievancetypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Grievancetypes';
$this->params['breadcrumbs'][] = $this->title;
$lists = Yii::$app->utility->get_grievance_type(null);
?>
<div class="col-sm-12 text-right">
    <a style="margin-bottom:10px;" href="<?=Yii::$app->homeUrl?>admin/grievancetype/create?securekey=<?=$menuid?>" class="btn btn-success btn-sm mybtn">Add New Grivence Type</a>
    
</div>
<div class="col-sm-12">
<table id="dataTableShow" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Sr. No.</th>
            <th>Name</th>
            <th>Description</th>
            <th>Is active</th>
            <th>Edit</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        if(!empty($lists)){
            $i =1;
            foreach($lists as $l){ 
            $encry = ($l['id']);
            $editUrl = Yii::$app->homeUrl."admin/grievancetype/update?securekey=$menuid&id=$encry";
                        if($l['is_active'] == 'Y'){
                            $is = "Yes";
                        }elseif($l['is_active'] == 'N'){
                            $is = "No";
                        }else{
                            $is = "-";
                        }
            ?>
            <tr>
            <td><?=$i?></td>
            <td><?=$l['title']?></td>
            <td><?=$l['description']?></td>
            <td><?=$is?></td>
            <td><a href="<?=$editUrl?>" class="btn btn-info btn-sm btn-xs">Edit</a></td>
            </tr>   
        <?php $i++; }
        }
        ?>
    </tbody>
  
</table>
</div>
