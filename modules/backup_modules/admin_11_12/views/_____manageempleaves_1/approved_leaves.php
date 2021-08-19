
<div class="row">
<script>
$(document).ready(function() {
    $('#approved_leaves').DataTable( {
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
    } );
} );
</script>
<table id="approved_leaves" class="display" style="width:100%">
	<thead>
		<tr>
		 
		<th>Emp Id</th><th>Name</th>
		<th>Type</th>
		 <th>From</th>
		<th>Till</th> 
		<th>Applied Date</th><th>Status</th>  <th>Action Date</th> 
		</tr>
	</thead>
	<tbody>
		<?php 
		if(!empty($employee_leaves)){$i =1;
			foreach($employee_leaves as $el){ 
			 if($el['status']=='Approved'){ ?>
			<tr> 
			 
			<td><?=$el['e_id']?></td>
			<td><?=$el['ename']?></td>
			<td><?=$el['desc']?></td>
			<td><?=$el['from']?></td>
 			<td><?=$el['till']?></td>
			<td><?=date('d-M-y',strtotime($el['applied_date']));?></td>
			<td><?=$el['status']?></td>
			<td><?=date('d-M-y',strtotime($el['action_date']));?></td>
			</tr>	
		<?php $i++;	}}
		}
		?>
	</tbody>
	<!--tfoot>
		<tr>
		<th> </th><th>Sr.</th>
		<th>Emp Id</th><th>Name</th>
		<th>Leave Type</th>
		 <th>From</th>
		<th>Till</th><th>Contact No</th>
		<th>Applied Date</th>
		</tr>
	</tfoot-->
</table>
 </div>
