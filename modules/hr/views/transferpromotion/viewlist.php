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
     $emp_id=Yii::$app->user->identity->e_id;
      $role=Yii::$app->user->identity->role;
 
 if($role==2)
 {
   $employees = Yii::$app->utility->get_transfer_promotion_details(null,$emp_id,null);   
 }
 elseif ($role==4) {
     $employees = Yii::$app->utility->get_transfer_promotion_details($emp_id,null,null);  
 }

//  echo "<pre>";
// print_r($employees); die;
//   echo "<pre>";

}
?>

    

<div class="col-sm-12">
<table id="dataTableShow1" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Sr. No.</th>
            <th>Title</th>
            <th>Request_for</th>
            <th>Employee</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php  $count=1;
        if(!empty($employees)){
            $i =1;
            
            foreach($employees as $l){ 
                  //elseif($l['request_for']==2) { echo "Promotion"; } elseif($l['request_for']==3) { echo "Suspension" ;}
          
            $editUrl = "";
           // $encry = Yii::$app->utility->encryptString($l['employee_code']);
           // $encry =$l['id'];
             $encry = Yii::$app->utility->encryptString($l['id']);
            $viewUrl = Yii::$app->homeUrl."hr/transferpromotion/view?securekey=$menuid&id=$encry";                        
            ?>
            <tr>
            <td><?=$i?> <?php //echo $count; ?></td>
            <td><?=$l['title']?></td>
            <td><?php if($l['request_for']=="1"){ echo "Transfer" ; } elseif($l['request_for']==2) { echo "Promotion"; } elseif($l['request_for']==3) { echo "Suspension" ;} ?></td>
            <td><?=$l['action_emp']?></td>
           <td><?php if($l['status']=="1"){ echo "submitted" ; } elseif($l['status']==2) { echo "Accepted"; } elseif($l['status']==3) { echo "Rejected" ;} elseif($l['status']==4) { echo "Resubmitted" ;} ?></td> 
            <td><a href="<?=$viewUrl?>" class="btn btn-success btn-sm btn-xs">View</a> </td>
            </tr>   
        <?php $i++; $count++;  }
        }
        ?>
    </tbody>
  
</table>
</div>
<div></div>

