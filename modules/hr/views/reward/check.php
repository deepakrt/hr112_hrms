<?php
$this->title= 'Rewards List ';
//echo "<pre>";print_r(Yii::$app->user->identity->role);die;
if(Yii::$app->user->identity->role==4){
    $lists = Yii::$app->utility->reward_list_for_approval(Yii::$app->user->identity->e_id,NULL,NULL);
}
else if(Yii::$app->user->identity->role==2){
    $lists = Yii::$app->utility->reward_list_for_approval(NULL,Yii::$app->user->identity->e_id,NULL);
}
//echo "<pre>";print_r($lists);die;
$menuid = "";
if(isset($_GET['securekey']) AND !empty($_GET['securekey'])){
    $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
    if(empty($menuid)){
        header('Location: '.Yii::$app->homeUrl); 
        exit;
    }
    $menuid = Yii::$app->utility->encryptString($menuid);
}
?>
<div class="col-sm-12 text-right">

	
</div>
<div class="col-sm-12">
<table id="dataTableShow" class="display" style="width:100%">
	<thead>
		<tr>
			<th>Sr.</th>
			<th>Employee Name</th>
			<th>Reward Name</th>
			<th>Description</th>
			<!--<th>Address</th>
			<th>Date of Birth</th>-->
			<th>Created by</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		if(!empty($lists)){
			$i =1;
			foreach($lists as $l){ 
			$name = ucwords($l['name']);
			$des = $l['description'];
			$created_by = $l['created_by'];
			$emp_name = $l['fname'];
			//$dob = date('d-m-Y', strtotime($l['dob']));
			
			//$encry = Yii::$app->utility->encryptStringUrl($l['e_id']);
			//$encry = Yii::$app->utility->encryptString($l['id']);
                        $encry = base64_encode($l['apply_id']);
			$viewUrl = Yii::$app->homeUrl."hr/reward/checkdetail?securekey=$menuid&rewardapplyid=$encry";
			
			?>
			<tr>
			<td><?=$i?></td>
			<td><?=$emp_name?></td>
			<td><?=$name?></td>
			<td><?=$des?></td>
			<td><?=$created_by?></td>
			<td style="padding: 0;"><a href="<?=$viewUrl?>" class="btn btn-success btn-sm btn-xs">Check Details</a></td>
			</tr>	
		<?php $i++;	}
		}
		?>
	</tbody>
	<tfoot>
		<th>Sr.</th>
			<th>Name</th>
			<th>Description</th>
			<!--<th>Address</th>
			<th>Date of Birth</th>-->
			<th>Created by</th>
			<th>Action</th>
	</tfoot>
</table>
</div>
