
<div class="row">
<script>
$(document).ready(function() {
    $('#leavestable').DataTable( {
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
    } );
    
     $(".approvedbtn").click(function(){
            var all_e_id = [];
            $.each($("input[name='sport']:checked"), function(){            
                all_e_id.push($(this).val());

            });
          // all_e_id= all_e_id.join(",")
        if(all_e_id==''){ alert('No Row Selected');return false;}
          var _csrf = $.trim($("#_csrf").val());
        
        var url = BASEURL+"admin/manageempleaves/updateleavestatus";
	jQuery.ajax({
            type: 'POST',
            dataType: "json",
            data: 
            {
                ids: all_e_id, _csrf:_csrf,
            },
            url: url,
            success: function (data) 
            {
                alert('Updated Successfully');
                $.each($("input[name='sport']:checked"), function(){            
                $(".pending_"+$(this).val()).remove();
                });
            }
        });
        });
 
} );

 function checkAll(ele) {
     var checkboxes = document.getElementsByTagName('input');
     if (ele.checked) {
         for (var i = 0; i < checkboxes.length; i++) {
             if (checkboxes[i].type == 'checkbox') {
                 checkboxes[i].checked = true;
             }
         }
     } else {
         for (var i = 0; i < checkboxes.length; i++) {
             console.log(i)
             if (checkboxes[i].type == 'checkbox') {
                 checkboxes[i].checked = false;
             }
         }
     }
 }
       
 
</script>
    <input type="hidden" name="_csrf" id="_csrf" value="<?= Yii::$app->request->csrfToken; ?>" />
<table id="leavestable" class="display" style="width:100%">
	<thead>
		<tr>
		<th><input onclick="checkAll(this)" type='checkbox'></th>
		
		<th>Emp Id</th><th>Name</th>
		<th>Type</th>
		 <th>From</th>
		<th>Till</th><th>Contact No</th>
		<th>Applied Date</th><th>Action</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		if(!empty($employee_leaves)){$i =1;
			foreach($employee_leaves as $el){  
			  if($el['status']=='Pending'){ ?>
			<tr class="pending_<?=$el['emp_leave_id']?>"><td><input value="<?=$el['emp_leave_id']?>" name="sport" type='checkbox'></td>
			 
			<td><?=$el['e_id']?></td>
			<td><?=$el['ename']?></td>
			<td><?=$el['desc']?></td>
			<td><?=$el['from']?></td>
 			<td><?=$el['till']?></td>
			 <td><?=$el['contact_no']?></td>
			 <td><?=date('d-M-y',strtotime($el['applied_date']));?></td>
			 <th>View</th>
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
<div class="col-sm-12 text-center">
<a href="#" class="btn btn-success btn-sm approvedbtn">Approve Selected</a>
</div>
 </div>
