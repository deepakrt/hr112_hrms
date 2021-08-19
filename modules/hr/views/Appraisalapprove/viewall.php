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
$info=Yii::$app->user->identity;
echo "<pre>";
print_r($info);
echo "</pre>";

  $fla=Yii::$app->user->identity->e_id; 
//$lists = Yii::$app->utility->get_appraisal_details(""); 
 $lists = Yii::$app->utility->get_appraisal_details($fla,null); 
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
<!-- <a style="margin-bottom:10px;" href="<?=Yii::$app->homeUrl?>employee/appraisal/create?securekey=<?=$menuid?>" class="btn btn-success btn-sm mybtn">Add New Appraisal</a> -->
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
                $Uploadedby = $l['uplodatedby'];     
                $appid = $l['id'];               
               
              //  $viewUrl = Yii::$app->homeUrl."admin/manageemployees/viewemployee?securekey=$menuid&empid=$encry";
             //   $editUrl = Yii::$app->homeUrl."admin/manageemployees/updateemployee?securekey=$menuid&empid=$encry";
                ?>
                <tr>
                <td><?=$i?></td>
                <td><?=$title?></td>
                <td><?=$document?></td>
                <td><?=$sdate?></td>
              
               <td><?=$Uploadedby?></td>
                <td style="padding: 0;" class="btn btn-success btn-sm btn-xs"><a href="<?=Yii::$app->homeUrl?>employee/appraisal/view?securekey=<?=$menuid.'&id='.$appid ?>">View</a> </td>
                </tr>	
            <?php $i++;	}
        } ?>
	</tbody>
</table>
</div>

</div>
