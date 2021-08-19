<?php
$allDepts= Yii::$app->utility->get_dept(NULL);
// echo \app\components\Topbarwidget::widget(array('menuid'=>$menuid));
$homeUrl = Yii::$app->homeUrl; 
// $USPExtractRole  = Yii::$app->Utility->USPExtractRole();

$this->title = "Manage Roles";

?> 

 <style type="text/css">
    #table_detail
    {
        width:100%;
        text-align:left;
        border-collapse: collapse;
        color:#2E2E2E;
        border:#A4A4A4;
    }
    #table_detail tr:hover
    {
        background-color:#F2F2F2;
    }
    #table_detail .hidden_row
    {
        display:none;
    }
</style>
<hr>
<div class="col-sm-12">
     <div class='row' id='dak_btn_individual_html' style='padding-top: 10px;'>
        <div class='col-sm-3'>
            <label><span class="hindishow12">विभाग / </span> Department</label>
            <select class="form-control form-control-sm" onchange="get_dept_emp_lists('indi_dept_id', 'indi_emp_code')" id="indi_dept_id" name='indi_dept_id'>
                <option value="">Select Department</option>
                <?php 
                if(!empty($allDepts)){
                    foreach($allDepts as $d){
                        $dept_id = Yii::$app->utility->encryptString($d['dept_id']);
                        $dept_name = $d['dept_name'];
                        echo "<option value='$dept_id'>$dept_name</option>";
                    }
                }
                ?>
            </select>
        </div>
        <div class='col-sm-3'>
            <label><span class="hindishow12">कर्मचारी / </span> Employee</label>
            <select class="form-control form-control-sm showcchtml" id='indi_emp_code' name='indi_emp_code' onchange="get_role_list_of_emp(this.value)">
                <option value="">Select Employee</option>
            </select>
        </div>       
    </div>
</div>
<br><br>
<div class="col-sm-12" id="all_roles_div_main" style="display:none;">
     <div class='row'>
        <table border="1" id="table_detail" align="center" cellpadding="3">
            <thead>
                <tr>           
                    <th>Role ID</th>
                    <th>Role Name</th>
                    <th>Description</th>
                    <th>Is Active</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="all_roles"></tbody>
        </table>
    </div>
</div>
<script type="text/javascript">


    // Getdeptempdropdown

    function get_dept_emp_lists(deptid, emp_dropdown_id){
        var dept_id = $('#'+deptid).find(":selected").val();
        hideError();
        // alert(emp_dropdown_id); return false;
        var id = "#"+emp_dropdown_id;
        
        $(id).html("<option value=''>Select Employee</option>");
        
        if(!dept_id){
            $("#"+deptid).prop('selectedIndex',0);
            return false;
        }
        
        var _csrf = $('#_csrf').val(); 
        // return false;
        var menuid = $("#menuid").val();
        var url = BASEURL+"employee/information/getdeptempdropdown";
        $.ajax({
            type: "POST",
            data :{
                _csrf:_csrf,
                dept_id:dept_id,
                
            },
            url: url,
            success: function(data){
                if(data){
                    var ht = $.parseJSON(data);
                    var status = ht.Status;
                    var res = ht.Res;
                    if(status == 'SS'){
                        if(res){        
                            $(id).html(res);
                            return false;
                        }
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
    
    function get_role_list_of_emp(parm)
    {
        $('#all_roles_div_main').hide();
        $('#all_roles').html("");

        $.ajax({
                url: "<?=Yii::$app->homeUrl."admin/manageroles/get_role_list?securekey=$menuid";?>",
                type: 'POST',
                data: { parm:parm },
                dataType: 'JSON',
                success: function (data) 
                {
                    // alert(data);
                    // $('#datashow').html(data);
                    $('#all_roles_div_main').show();
                    $('#all_roles').html("");
                    $('#all_roles').html(data.rolesData);                    
                }
            });
    }

    function hr_assign_unassign_role(idn)
    {
        // debugger;
        var ck = $('#row_chk_' + idn).is(":checked");
        // indi_emp_code
        var emp = $('#indi_emp_code').val();

        var ckval = 0;
        if(ck)
        {
            ckval = 1;
        }

        $.ajax({
                url: "<?=Yii::$app->homeUrl."admin/manageroles/update_role_list?securekey=$menuid";?>",
                type: 'POST',
                data: { parm:ckval,emp_code:emp,role_id:idn },
                dataType: 'JSON',
                success: function (data) 
                {
                    if(data.checkStatus == 111)
                    {
                        swal('Done!','Record Updated Successfully.','success');
                    }   
                    else
                    {
                        swal('Note!','Record Not Updated.','warning');
                    }
                }
            });
    }
</script>