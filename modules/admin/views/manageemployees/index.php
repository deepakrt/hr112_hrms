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

	$depts = Yii::$app->utility->get_dept(null);

	/*echo "<pre>"; print_r($lists);
	die();*/
?>
<input type="hidden" id="menuid" value="<?=$menuid?>" />
<div class="row" style="margin-bottom: 10px;">
	<div class="col-sm-6" style="">
		<div class="col-sm-6" style="float:left;">
			<label class="control-label" for="department_data">Department</label>
			<select class="form-control form-control-sm" name="forward_to[dept_id]" id="dept_id" required="required" onchange="dptChange(this.value)">
        <option value="-1">Select Department</option>
        <?php 
	        if(!empty($depts)){
            foreach($depts as $d){
              echo "<option value='$d[dept_id]'>$d[dept_name]</option>";
            }
	        }
        ?>
	    </select>
      <span id="dpts_error" style="display: none;">Please Select Department.</span>
		</div>
		<!-- <div class="col-sm-6" style="float:left;">
      <label>Designation</label>
      <select class="form-control form-control-sm" id="desg_id" name="forward_to[designation_id]" required="required" onchange="dsgChange(this.value)">
          <option value="-1">Select Designation</option>
      </select>
      <span id="dsg_error" style="display: none;">Please Select Designation.</span>
    </div> -->
	</div>
	<div class="col-sm-6 text-right">
		<a style="margin-bottom:10px;" href="<?=Yii::$app->homeUrl?>admin/manageemployees/add?securekey=<?=$menuid?>" class="btn btn-success btn-sm mybtn">Add New Employee</a>	
	</div>
</div>
<div class="row">
	<div class="col-sm-12" id="employee_data_disp">
		<table id="dataTableShow" class="display" style="width:100%">
			<thead>
				<tr>
					<th>Sr.</th>
					<th>Employee Code</th>
					<th>Name</th>
					<th>Designation</th>
					<!--<th>Address</th>
					<th>Date of Birth</th>-->
					<th>Department</th>
					<th>Location</th>
					<th>Belt No</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				if(!empty($lists)){
					$i =1;
					foreach($lists as $l){ 
					$name = ucwords($l['fname'])." ".ucwords($l['lname']);
					$employee_code = $l['employee_code'];
					$desg_name = $l['desg_name'];
					$adress = $l['address'].", ".$l['city']." -".$l['zip']." (".$l['state'].")";
					//$dob = date('d-m-Y', strtotime($l['dob']));
					$empltype="-";
					// if($l['employment_type'] == 'R'){
					// 	$empltype="Regular";
					// }elseif($l['employment_type'] == 'C'){
					// 	$empltype="Contract";
					// }
					//$encry = Yii::$app->utility->encryptStringUrl($l['e_id']);
					$encry = Yii::$app->utility->encryptString($l['employee_code']);
					$viewUrl = Yii::$app->homeUrl."admin/manageemployees/viewemployee?securekey=$menuid&empid=$encry";
					$editUrl = Yii::$app->homeUrl."admin/manageemployees/updateemployee?securekey=$menuid&empid=$encry";
					?>
					<tr>
					<td><?=$i?></td>
					<td><?=$employee_code?></td>
					<td><?=$name?></td>
					<td><?=$desg_name?></td>
					<!--td><?php //$dob?></td-->
					<td><?=$l['dept_name']?></td>
					<td><?=$l['city']?></td>
					<td><?=$l['belt_no']?></td>
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
</div>


<script type="text/javascript">
	
	function dptChange(dptID)
  {
  	startLoader();

    if(dptID != -1)
    {
        $('#employee_data_disp').hide();
        

        var dept_id = dptID;
        var menuid = $('#menuid').val();


        // getdeptempdropdown
        var url = BASEURL+"admin/manageemployees/getemppless_by_department?securekey="+menuid;

        $.ajax({
            type: "POST",
            url: url,
            dataType: 'JSON',
            data:{ dept_id:dept_id },
            success: function(data)
            {
                // console.log(data.Status);

                if(data.Status == 'SS')
                {
                	$('#employee_data_disp').show();
                  $('#employee_data_disp').html(data.Res);
                }

                stopLoader();
            }
        });
    }
    else
    {
    		stopLoader();
        $('#employee_data_disp').show();
        /*$('#employee_data_disp').css('color','Red');
        $('#employee_data_disp').html('No data exist.');*/
    }
  }


  function startLoader()
  {
     $("#loading").show();
  }
   
  function stopLoader()
  {
      $("#loading").hide();
  }

</script>