<h3>Leave Details:</h3>
<div class="row">
<script>
$(document).ready(function() {
    $('#leavestable').DataTable( {
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
    } );
} );
</script>
<table id="leavestable" class="display" style="width:100%">
	<thead>
		<tr>
		<th>Sr.</th>
		<th>Leave Type</th>
		<th>Available</th>
		 <th>Pending</th>
		<th>Balance</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		if(!empty($employee_leaves)){$i =1;
			foreach($employee_leaves as $el){ 
                            $avail = $el['balance_leaves'];
                            $pending = $el['pending_leaves'];
                            $bal = $avail-$pending;
                            ?>
			 
			<tr>
			<td><?=$i?></td>
			<td><?=$el['desc']?></td>
			<td><?=$avail?></td>
			<td><?=$pending?></td>
 			<td><?=$bal?></td>
			 
			</tr>	
		<?php $i++;	}
		}
		?>
	</tbody>
	<tfoot>
		<tr>
		<th>Sr.</th>
		<th>Leave Type</th>
		<th>Total</th>
		 <th>Balance</th>
		<th>Year</th>
		</tr>
	</tfoot>
</table>
 </div>
