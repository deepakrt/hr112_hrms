<?php
$this->title= 'Manage Employees ';
$lists = Yii::$app->utility->get_employees(null);
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
<a style="margin-bottom:10px;" href="<?=Yii::$app->homeUrl?>admin/manageemployees/add?securekey=<?=$menuid?>" class="btn btn-success btn-sm mybtn">Add New Employee</a>
	
</div>
<div class="col-sm-12">
<table id="dataTableShow" class="display" style="width:100%">
	<thead>
		<tr>
			<th>Sr.</th>
			<th>Name</th>
			<th>Designation</th>
			<!--<th>Address</th>
			<th>Date of Birth</th>-->
			<th>Department</th>
			<th>Emp. Type</th>
			<th>Phone</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		if(!empty($lists)){
			$i =1;
			foreach($lists as $l){ 
			$name = ucwords($l['fname'])." ".ucwords($l['lname']);
			$desg_name = $l['desg_name'];
			$adress = $l['address'].", ".$l['city']." -".$l['zip']." (".$l['state'].")";
			//$dob = date('d-m-Y', strtotime($l['dob']));
			$empltype="-";
			if($l['employment_type'] == 'R'){
				$empltype="Regular";
			}elseif($l['employment_type'] == 'C'){
				$empltype="Contract";
			}
			//$encry = Yii::$app->utility->encryptStringUrl($l['e_id']);
			$encry = Yii::$app->utility->encryptString($l['employee_code']);
			$viewUrl = Yii::$app->homeUrl."admin/manageemployees/viewemployee?securekey=$menuid&empid=$encry";
			$editUrl = Yii::$app->homeUrl."admin/manageemployees/updateemployee?securekey=$menuid&empid=$encry";
			?>
			<tr>
			<td><?=$i?></td>
			<td><?=$name?></td>
			<td><?=$desg_name?></td>
			<!--td><?php //$dob?></td-->
			<td><?=$l['dept_name']?></td>
			<td><?=$empltype?></td>
			<td><?=$l['phone']?></td>
			<td style="padding: 0;"><a href="<?=$viewUrl?>" class="btn btn-success btn-sm btn-xs">View</a> <a href="<?=$editUrl?>" class="btn btn-info btn-sm btn-xs">Edit</a></td>
			</tr>	
		<?php $i++;	}
		}
		?>
	</tbody>
	<tfoot>
		<th>Sr.</th>
			<th>Name</th>
			<th>Designation</th>
			<!--<th>Address</th>
			<th>Date of Birth</th>-->
			<th>Department</th>
			<th>Emp. Type</th>
			<th>Phone</th>
			<th>Action</th>
	</tfoot>
</table>
</div>
