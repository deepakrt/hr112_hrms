<?php
$this->title= 'Manage Unit';
$lists = Yii::$app->inventory->get_unit_master();
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
<a style="margin-bottom:10px;" href="<?=Yii::$app->homeUrl?>inventory/unit/add?securekey=<?=$menuid?>" class="btn btn-success btn-sm mybtn">Add New Unit</a>
	
</div>
<div class="col-sm-12">
<table id="dataTableShow" class="display" style="width:100%">
	<thead>
		<tr>
			<th>Sr.</th>	
			<th>Unit Name</th>
			<!--<th>Action</th>-->
		</tr>
	</thead>
	<tbody>
		<?php 
		if(!empty($lists)){
			$i =1;
			foreach($lists as $l){ 
                        $encry = base64_encode($l['Unit_id']);
			$Unit_Name =$l['Unit_Name'];
			$editUrl = Yii::$app->homeUrl."inventory/unit/update?securekey=$menuid&empid=$encry";
			?>
			<tr>
			<td><?=$i?></td>
			<td><?=$Unit_Name?></td>
			<!--<td style="padding: 0;"><a href="<?=$editUrl?>" class="btn btn-info btn-sm btn-xs">Edit</a></td>-->
			</tr>	
		<?php $i++;	}
		}
		?>
	</tbody>
	<tfoot>
		       <th>Sr.</th>
			<th>Unit Name</th>
			<!--<th>Action</th>-->
	</tfoot>
</table>
</div>
