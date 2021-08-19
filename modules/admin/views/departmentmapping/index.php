<?php
$this->title = "Employee Department Mapping";
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

<div class="col-sm-12">
<table id="dataTableShow" class="display" style="width:100%">
	<thead>
		<tr>
			<th>Sr.</th>
			<th>Name</th>
			<th>Designation</th>
			<th>Department</th>
			<th>Emp. Type</th>
            <th>Phone</th>
			<th>Status</th>
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
			$empltype="-";
			if($l['employment_type'] == 'R'){
				$empltype="Regular";
			}elseif($l['employment_type'] == 'C'){
				$empltype="Contract";
			}
            
            $dp_is_active = 'Active';   

            $dp_is_active .= ' ('.$l['dp_is_active'].')';   
            
			//$encry = Yii::$app->utility->encryptStringUrl($l['e_id']);
			$encry = Yii::$app->utility->encryptString($l['employee_code']);
			$editUrl = Yii::$app->homeUrl."admin/manageemployees/updateemployee?securekey=$menuid&empid=$encry";
			?>
			<tr>
			<td id="<?=$l['employee_code']?>"><?=$i?></td>
			<td><?=$name.' ('.$l['employee_code'].')';?></td>
			<td><?=$desg_name?></td>
			<td><?=$l['dept_name']?></td>
			<td><?=$empltype?></td>
            <td><?=$l['phone']?></td>
			<td><?=$dp_is_active?></td>
			<td style="padding: 0;">
                        <button type="button" class="btn btn-info btn-sm btn-xs getdetails" id="<?=$l['employee_code']?>" style="color:#fff">Edit</button>
                        </td>
			</tr>	
			<div id="empinfo">
			    
			</div>
		<?php $i++;	}
		}
		?>
	</tbody>
	<tfoot>
		<th>Sr.</th>
			<th>Name</th>
			<th>Designation</th>
			<th>Department</th>
			<th>Emp. Type</th>
			<th>Phone</th>
			<th>Action</th>
	</tfoot>
</table>
</div>
<!-- Modal -->
<div class="modal fade" id="addnewdeptmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Assign New Department</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <label><span class="hindishow12">विभाग</span> Department</label>
                        <select class="form-control form-control-sm" id='department'>
                            <option value="">Select</option>
                            <?php 
                            $dept = Yii::$app->utility->get_dept(NULL);
                            foreach($dept as $d){
                                echo "<option value='$d[dept_id]'>$d[dept_name]</option>";
                            }
                            ?>
                        </select>
                        <hr>
                    </div>
                    <div class="col-sm-12">
                        <label><span class="hindishow12">भूमिका</span> Role</label>
                        <select class="form-control form-control-sm" id='roleid'>
                            <option value="">Select</option>
                            <?php 
                            $role= Yii::$app->utility->get_roles(NULL);
                            foreach($role as $d){
                                echo "<option value='$d[role_id]'>$d[role]</option>";
                            }
                            ?>
                        </select>
                        <hr>
                    </div>
                    <div class="col-sm-12 text-center">
                        <button type="button" class="btn btn-success btn-sm" onclick="submitNewDept()">Submit</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<script>
var BASE_URL='<?=Yii::$app->homeUrl?>';
var securekey='<?=$menuid?>';
//function getempdept(){

    <?php
        if(isset($_GET['type']))
        {
            if($_GET['type'] == 'error')
            {
                ?>
                    getEmpData("<?=$_GET['employee_code']?>");
                <?php
            }
        }
    ?>
$(function(){

    $( "body" ).on( "click", ".getdetails", function() { 
        hideError();
        //alert($('.data_code').attr("id"));
       //alert($(this).attr('id'));
        var emp_code = $(this).attr('id');
       // $("#emp_code").val(emp_code);
        
        if(!emp_code){
            showError("Enter Employee Code");
            return false;
        }

        getEmpData(emp_code);      
    });
});


function getEmpData(emp_code)
{
    var _csrf = $('#_csrf').val();
    var menuid = $("#menuid").val();
    var url = BASEURL+"admin/departmentmapping/getempinfo?securekey="+securekey;
    $("#empinfo").html('');
    showLoader();
    $.ajax({
        type: "POST",
        data:{
            emp_code:emp_code,
            securekey:menuid,
            _csrf:_csrf
        },
        url: url,
        success: function(data){
        //            $("#emp_code").val('');
            hideLoader();
            if(data){
                var ht = $.parseJSON(data);
                var status = ht.Status;
                var res = ht.Res;
                if(status == 'SS'){
        //                    alert(res);
                    $("#empinfo").html(res);
                    return false;
                }else{
                    showError(res); 
                    return false;
                }
            }else{
                return false;
            }
        }
    });
}

</script>
