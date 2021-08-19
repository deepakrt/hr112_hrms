<?php
$this->title= 'Check Recognition ';

if(Yii::$app->user->identity->role==4){
    $lists = Yii::$app->utility->recognition_list_check(Yii::$app->user->identity->e_id,NULL,NULL);
}
else if(Yii::$app->user->identity->role==2){
    $lists = Yii::$app->utility->recognition_list_check(NULL,Yii::$app->user->identity->e_id,NULL);
}


//$lists = Yii::$app->utility->get_recognitions(null,Yii::$app->user->identity->e_id);
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
<a style="margin-bottom:10px;" href="<?=Yii::$app->homeUrl?>hr/recognition/add?securekey=<?=$menuid?>" class="btn btn-success btn-sm mybtn">Add New Recognition</a>
	
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
			<th>Recognition type</th>
			<th>Employee Name</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		if(!empty($lists)){
			$i =1;
                        $reco_type = array('1' => 'Bonus', '2' => 'Appreciation Letter', '3' => 'Verbal Appreciation');
                        
			foreach($lists as $l){ 
			$name = ucwords($l['name']);
			$des = $l['description'];
			$reco_name = $reco_type[$l['reco_type']];
			$emp_name = $l['fname'];
			//$dob = date('d-m-Y', strtotime($l['dob']));
			
			//$encry = Yii::$app->utility->encryptStringUrl($l['e_id']);
			//$encry = Yii::$app->utility->encryptString($l['id']);
                        $encry = base64_encode($l['id']);
			$viewUrl = Yii::$app->homeUrl."hr/recognition/viewrecognition?securekey=$menuid&recoid=$encry";
			
			?>
			<tr>
			<td><?=$i?></td>
			<td><?=$name?></td>
			<td><?=$des?></td>
			<td><?=$reco_name?></td>
			<td><?=$emp_name?></td>
			<td style="padding: 0;"><a href="<?=$viewUrl?>" class="btn btn-success btn-sm btn-xs">View</a></td>
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
