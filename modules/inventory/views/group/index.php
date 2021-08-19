<?php
$this->title= 'Manage Group';
$lists = Yii::$app->inventory->get_groups();
//Inventoryutility
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
<a style="margin-bottom:10px;" href="<?=Yii::$app->homeUrl?>inventory/group/add?securekey=<?=$menuid?>" class="btn btn-success btn-sm mybtn">Add New Group</a>
	
</div>
<div class="col-sm-12">
<table id="dataTableShow" class="display" style="width:100%">
	<thead>
		<tr>
			<th>Sr.</th>	
			<th>Group Name</th>
			<th>Active(Y/N)</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		if(!empty($lists)){
			$i =1;
			foreach($lists as $l){ 
                        $encry = base64_encode($l['CLASSIFICATION_CODE']);
			$CLASSIFICATION_NAME =$l['CLASSIFICATION_NAME'];
                        $is_active =$l['is_active'];
			?>
			<tr>
			<td><?=$i?></td>
			<td><?=$CLASSIFICATION_NAME?></td>
                        <td><?=$is_active?></td>
			</tr>	
		<?php $i++;	}
		}
		?>
	</tbody>
	<tfoot>
		       <th>Sr.</th>
			<th>Group Name</th>
			<th>Active(Y/N)</th>
	</tfoot>
</table>
</div>
