<?php
$this->title= 'Manage Designation ';
$lists = Yii::$app->utility->get_designation(null);
// echo "<pre>";print_r($lists); die;
?>
<div class="col-sm-12 text-right">
	<a style="margin-bottom:10px;" href="<?=Yii::$app->homeUrl?>admin/managedesignation/add?securekey=<?=$menuid?>" class="btn btn-success btn-sm mybtn">Add New Designation</a>
	
</div>
<div class="col-sm-12">
<table id="dataTableShow" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Sr. No.</th>
            <th>Designation</th>
            <th>Description</th>
            <th>Is active</th>
            <th>Edit</th>
        </tr>
    </thead>
	<tbody>
		<?php 
		if(!empty($lists)){
			$i =1;
			foreach($lists as $l){ 
			$encry = base64_encode($l['desg_id']);
			$editUrl = Yii::$app->homeUrl."admin/managedesignation/updatedesignation?securekey=$menuid&key=$encry";
                        if($l['is_active'] == 'Y'){
                            $is = "Yes";
                        }elseif($l['is_active'] == 'N'){
                            $is = "No";
                        }else{
                            $is = "-";
                        }
			?>
			<tr>
			<td><?=$i?></td>
			<td><?=$l['desg_name']?></td>
			<td><?=$l['desg_desc']?></td>
			<td><?=$is?></td>
			<td><a href="<?=$editUrl?>" class="btn btn-info btn-sm btn-xs">Edit</a></td>
			</tr>	
		<?php $i++;	}
		}
		?>
	</tbody>
	<tfoot>
            <th>Sr. No.</th>
            <th>Designation</th>
            <th>Description</th>
            <th>Is active</th>
            <th>Edit</th>
	</tfoot>
</table>
</div>
