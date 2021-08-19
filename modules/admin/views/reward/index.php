<?php
$this->title= 'Manage Rewards ';
$lists = Yii::$app->utility->get_rewards(null);
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
<a style="margin-bottom:10px;" href="<?=Yii::$app->homeUrl?>admin/reward/add?securekey=<?=$menuid?>" class="btn btn-success btn-sm mybtn">Add New Reward</a>
	
</div>
<div class="col-sm-12">
<table id="dataTableShow" class="display" style="width:100%">
	<thead>
		<tr>
			<th>Sr.</th>
			<th>Name</th>
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
			//$dob = date('d-m-Y', strtotime($l['dob']));
			
			//$encry = Yii::$app->utility->encryptStringUrl($l['e_id']);
			//$encry = Yii::$app->utility->encryptString($l['id']);
                        $encry = base64_encode($l['id']);
			$viewUrl = Yii::$app->homeUrl."admin/reward/viewreward?securekey=$menuid&rewardid=$encry";
			$editUrl = Yii::$app->homeUrl."admin/reward/updatereward?securekey=$menuid&rewardid=$encry";
			?>
			<tr>
			<td><?=$i?></td>
			<td><?=$name?></td>
			<td><?=$des?></td>
			<td><?=$created_by?></td>
			<td style="padding: 0;"><a href="<?=$viewUrl?>" class="btn btn-success btn-sm btn-xs">View</a> <a href="<?=$editUrl?>" class="btn btn-info btn-sm btn-xs">Edit</a></td>
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
