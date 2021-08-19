<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\GrievanceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Grievances';
$this->params['breadcrumbs'][] = $this->title;
if(isset($_GET['securekey']) AND !empty($_GET['securekey'])){
    $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
    if(empty($menuid)){
        header('Location: '.Yii::$app->homeUrl); 
       
    }
    $menuid = Yii::$app->utility->encryptString($menuid);
   $emp_id=Yii::$app->user->identity->e_id; 
  $emp_role=Yii::$app->user->identity->role; 
    if($emp_role=='4') {
 $lists = Yii::$app->utility->get_grievance_details($emp_id,null,null); 
}
elseif ($emp_role=='2') {
 $lists = Yii::$app->utility->get_grievance_details(null,$emp_id,null); 
   
}
   

}
?>

<div class="col-sm-12 text-right">

</div>
   <div class="col-sm-12">
<table id="dataTableShow" class="display" style="width:100%">
    <thead>
            <tr>
                <th>Sr.No.</th>
                <th>Title</th>
                <th>Document</th>
                <th>Date</th>
                <th>Emp Code</th>               
                <th>Action</th>
            </tr>
    </thead>
    <tbody>
            <?php 
            if(!empty($lists))
            {
                $i =1;
                foreach($lists as $key=> $l){ 
                $title = ucwords($l['title']);
                $document = ucwords($l['filename']);
                $sdate = $l['sdate']; 
                $Uploadedby = $l['createdby'];     
                $appid = $l['id'];               
               
              //  $viewUrl = Yii::$app->homeUrl."admin/manageemployees/viewemployee?securekey=$menuid&empid=$encry";
             //   $editUrl = Yii::$app->homeUrl."admin/manageemployees/updateemployee?securekey=$menuid&empid=$encry";
                ?>
                <tr>
                <td><?=$i?></td>
                <td><?=$title?></td>
                <td> <a target="_blank" href="<?=Yii::$app->homeUrl?>other_files/Appraisal_doc/<?=$document?>"> <?=$document?> </a></td>
                <td><?=$sdate?></td>
              
               <td><?=$Uploadedby?></td>
                <td style="padding: 0;" >
                    <?php if($l['status']==0) {?>
<a class="btn btn-outline-danger" href="<?=Yii::$app->homeUrl?>hr/grievanceapprove/view?securekey=<?=$menuid.'&id='.$appid ?>">Preview</a>&nbsp;&nbsp;<?php
                    } else { ?><a class="btn btn-outline-success" href="<?=Yii::$app->homeUrl?>hr/grievanceapprove/view?securekey=<?=$menuid.'&id='.$appid ?>">View</a><?php } ?>

                     </td>
                </tr>   
            <?php $i++; }
        } ?>
    </tbody>
</table>
</div>

</div>
