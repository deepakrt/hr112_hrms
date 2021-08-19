<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PoliciesGuidelinesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Policies Guidelines';
$this->params['breadcrumbs'][] = $this->title;
$lists = Yii::$app->utility->get_policies_gui(null);
// echo "<pre>";
// print_r($lists);
// echo "</pre>";
?>
<div class="col-sm-12 text-right">
    <a style="margin-bottom:10px;" href="<?=Yii::$app->homeUrl?>admin/policiesguidelines/create?securekey=<?=$menuid?>" class="btn btn-success btn-sm mybtn">Add New Polices </a>
    
</div>
<div class="col-sm-12">
<table id="dataTableShow1" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Sr. No.</th>
            <th>Name</th>
            <th>Title</th>
            <th>Document</th>
            <th>Is active</th>
            <th>Edit</th>
        </tr>
    </thead>
    <tbody>
        <?php  $count=1;
        if(!empty($lists)){
            $i =1;
            foreach($lists as $l){ 
                  $document = ($l['document']);
             $encry = ($l['id']);
            $editUrl = Yii::$app->homeUrl."admin/policiesguidelines/update?securekey=$menuid&id=$encry";
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
            <td><?=$l['title']?></td>
            <td><a target="_blank" href="<?=Yii::$app->homeUrl?>other_files/Polices_doc/<?=$document?>"> <?=$document?> </a></td>
            <td><?=$is?></td>
            <td><a href="<?=$editUrl?>" class="btn btn-info btn-sm btn-xs">Edit</a></td>
            </tr>   
        <?php $i++; $count++;  }
        }
        ?>
    </tbody>
  
</table>
</div>

