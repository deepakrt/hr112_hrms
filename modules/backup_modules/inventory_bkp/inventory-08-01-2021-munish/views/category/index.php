<?php
$this->title= 'Manage Category';
$lists = Yii::$app->inventory->get_category();
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
<a style="margin-bottom:10px;" href="<?=Yii::$app->homeUrl?>inventory/category/add?securekey=<?=$menuid?>" class="btn btn-success btn-sm mybtn">Add New Item</a>
	
</div>
<div class="col-sm-12">
<table id="dataTableShow" class="display" style="width:100%">
	<thead>
		<tr>
			<th>Sr.</th>	
			<th>Category Name</th>
			<!--<th>Action</th>-->
		</tr>
	</thead>
	<tbody>
		<?php 
		if(!empty($lists)){
			$i =1;
			foreach($lists as $l){ 
                        $encry = base64_encode($l['ITEM_CAT_CODE']);
			$ITEM_CAT_NAME =$l['ITEM_CAT_NAME'];
			?>
			<tr>
			<td><?=$i?></td>
			<td><?=$ITEM_CAT_NAME?></td>			
			</tr>	
		<?php $i++;	}
		}
		?>
	</tbody>
	<tfoot>
		       <th>Sr.</th>
			<th>Category Name</th>
			<!--<th>Action</th>-->
	</tfoot>
</table>
</div>
