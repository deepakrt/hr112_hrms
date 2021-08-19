<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AppraisalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Appraisals';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php 
 $emp_id=Yii::$app->user->identity->e_id; 
  $emp_role=Yii::$app->user->identity->role; 
 if($emp_role=='4')
 {
 $lists = Yii::$app->utility->get_appraisal_details($emp_id,null,null); 
}
elseif ($emp_role=='2') {

 $lists = Yii::$app->utility->get_appraisal_details(null,$emp_id,null); 

    
}
$menuid = "";
if(isset($_GET['securekey']) AND !empty($_GET['securekey'])){
    $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
    if(empty($menuid)){
        header('Location: '.Yii::$app->homeUrl); 
        exit;
    }
    $menuid = Yii::$app->utility->encryptString($menuid);
    //Yii::$app->user->identity->role;
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
                <th>Uploaded By</th>
               
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
                $document = ucwords($l['document']);
                $sdate = $l['sdate']; 
                $Uploadedby = $l['uploadedby'];     
                $appid = ($l['id']);               
               
              //  $viewUrl = Yii::$app->homeUrl."admin/manageemployees/viewemployee?securekey=$menuid&empid=$encry";
             //   $editUrl = Yii::$app->homeUrl."admin/manageemployees/updateemployee?securekey=$menuid&empid=$encry";
                ?>
                <tr>
                <td><?=$i?></td>
                <td><?=$title?></td>
                <td><a class="danger" target="_blank" href="<?=Yii::$app->homeUrl?>other_files/Appraisal_doc/<?=$document?>"><?=$document?></a></td>
                <td><?=$sdate?></td>
              
               <td><?=$Uploadedby?></td>
                <td style="padding: 0;"  >
                    <?php
                    if($emp_role==4)
                    {
                     if($l['status']=='1')
                    
                    { ?>
        <a  title="View and Forword"  href="<?=Yii::$app->homeUrl?>hr/appraisalapprove/view?securekey=<?=$menuid.'&id='.$appid ?>"><img width='25' src='<?=Yii::$app->homeUrl?>images/details_open.png'></a>     

                     <?php } elseif ($l['status']=='2' || $l['status']=='3') { ?>
                        <a title="view"   href="<?=Yii::$app->homeUrl?>hr/appraisalapprove/view?securekey=<?=$menuid.'&id='.$appid ?>"><img width='25' src='<?=Yii::$app->homeUrl?>images/vieww.png'></a> 
                    <?php } else { }

                }
                else
                { ?>
                    <a title="view"   href="<?=Yii::$app->homeUrl?>hr/appraisalapprove/view?securekey=<?=$menuid.'&id='.$appid ?>"><img width='25' src='<?=Yii::$app->homeUrl?>images/vieww.png'></a> 

               <?php }


                     ?>
                   
                    
                </td>
                 
                </tr>   
            <?php $i++; }
        } ?>
    </tbody>
</table>
</div>

</div>
<?php //die; ?>