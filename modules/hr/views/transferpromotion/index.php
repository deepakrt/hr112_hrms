<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TransferpromotiontSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Transferpromotions';
$this->params['breadcrumbs'][] = $this->title;
if(isset($_GET['securekey']) AND !empty($_GET['securekey'])){
    $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
    if(empty($menuid)){
        header('Location: '.Yii::$app->homeUrl); 
       
    }
    $menuid = Yii::$app->utility->encryptString($menuid);
$policylists = Yii::$app->utility->get_policies_gui(3);

 //$doc=$lists['0']['document'];
  $emp_id=Yii::$app->user->identity->e_id; 
 $employees = Yii::$app->utility->get_employee_list($emp_id);
 $cudate= date("Y-m-d");
}
?>

<div class="col-sm-12 "><?php foreach($policylists as $policy) { if($policy['valid_upto']>=$cudate) { $doc= $policy['document'] ?>
<a style="margin-bottom:10px; color:red;" target="_blank" href="<?=Yii::$app->homeUrl?>other_files/Polices_doc/<?=$doc?>"><strong class="danger">Transfer Promotion and Suspension</a> <?php } } ?></strong> 
</div>
    

<div class="col-sm-12">
<table id="dataTableShow1" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Sr. No.</th>
            <th>Name</th>
            <th>Degignation</th>
            <th>Is active</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php  $count=1;
        if(!empty($employees)){
            $i =1;
            
            foreach($employees as $l){ 
                  
          
            $editUrl = "";
            $encry = Yii::$app->utility->encryptString($l['employee_code']);
         
            $viewUrl = Yii::$app->homeUrl."hr/transferpromotion/viewemployee?securekey=$menuid&empid=$encry";
                        
            ?>
            <tr>
            <td><?=$i?> <?php //echo $count; ?></td>
            <td><?=$l['fname']?></td>
            <td><?=$l['desg_name']?></td>
            <td></td>
            <td><a href="<?=$viewUrl?>" class="btn btn-success btn-sm btn-xs">View</a> </td>
            </tr>   
        <?php $i++; $count++;  }
        }
        ?>
    </tbody>
  
</table>
</div>

