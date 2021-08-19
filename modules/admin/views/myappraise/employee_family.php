<h3>Family Details:</h3>
<div class="row">
<table id="dataTableShow" class="display" style="width:100%">
	<thead>
            <tr>
		<th>Sr.</th>
		<th>Member Name</th>
		<th>Relation</th>
		<th>Date of Birth</th>
		<th>Marital Status</th>
		<th>Document</th>
		<th>Status</th>
                <?php if(Yii::$app->user->identity->role == '5' OR Yii::$app->user->identity->role == '1'){?>
		<th>Action Date</th>
                <?php } ?>
            </tr>
	</thead>
	<tbody>
		<?php 
                //echo "<pre>";print_r($family_details);
		if(!empty($family_details)){$i =1;
			foreach($family_details as $fd){ 
			 
			if(empty($fd['document_path'])){
				$doc="Not Uploaded";
			}else{
				$doc = '<a target="_blank" style="text-decoration: underline;" href="'.Yii::$app->request->baseUrl.$fd['document_path'].'">View</a>';
			}
                        $class="";
			if($fd['status'] == 'Verified'){
				$class="btn-success";
			}elseif($fd['status'] == 'Unverified'){
				$class="btn-danger";
			}elseif($fd['status'] == 'Rejected'){
				$class="btn-danger";
			}
                        $modified_date="";
                        if(!empty($fd['modified_date'])){
                            $modified_date = date('d-M-Y', strtotime($fd['modified_date']));
                        }
			
			$encry = base64_encode($fd['e_id']);
			$ef_id = base64_encode($fd['ef_id']);
			if($fd['status']=='Verified'){$status=1;}else{$status=0;}
	$verify_link = Yii::$app->homeUrl."admin/manageemployees/verify_fmember?key=$encry&type=$ef_id&status=$status&tab=qualification";
			 
			?>
			<tr>
			<td><?=$i?></td>
			<td><?=ucwords($fd['m_name'])?></td>
			<td><?=$fd['relation_name']?></td>
			<td><?=date('d-M-Y', strtotime($fd['m_dob']))?></td>
 			<td><?=$fd['marital_status']?></td>
 			<td><?=$doc?></td>
 			<td style="padding: 0;">
			  <a href="javascript:void(0)" class='btn <?=$class?> btn-sm btn-xs'><?=$fd['status']?></a>
			</td>
                        <?php if(Yii::$app->user->identity->role == '5' OR Yii::$app->user->identity->role == '1'){?>
                        <td><?=$modified_date?></td>
                        <?php } ?>
			</tr>	
		<?php $i++;	}
		}
		?>
	</tbody>
	<tfoot>
		<tr>
		<th>Sr.</th>
		<th>Member Name</th>
		<th>Relation</th>
		<th>Date of Birth</th>
		<th>Marital Status</th>
		<th>Document</th>
		<th>Status</th>
                <?php if(Yii::$app->user->identity->role == '5' OR Yii::$app->user->identity->role == '1'){?>
                <th>Action Date</th>
                <?php } ?>
		</tr>
	</tfoot>
</table>
 </div>
