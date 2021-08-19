$(document).ready(function() {
    $("#sameAddress").change(function(){
        if($(this).prop('checked') == true){
            hideError();
            var employeeaddress = $.trim($("#employee-address").val());
            $("#employee-address").val(employeeaddress);
            var employeecity = $.trim($("#employee-city").val());
            $("#employee-city").val(employeecity);
            var employeestate = $.trim($("#employee-state").val());
            $("#employee-state").val(employeestate);
            var employeezip = $.trim($("#employee-zip").val());
            $("#employee-zip").val(employeezip);
            var employeecontact1 = $.trim($("#employee-contact1").val());
            $("#employeecontact1").val(employeecontact1);
            if(employeeaddress && employeecity && employeestate && employeezip){
                $("#employee-p_address").val(employeeaddress);
                $("#employee-p_address").attr('readonly',true);
                $("#employee-p_city").val(employeecity);
                $("#employee-p_city").attr('readonly',true);
                $("#employee-p_state").val(employeestate);
                $("#employee-p_state").attr('readonly',true);
                $("#employee-p_zip").val(employeezip);
                $("#employee-p_zip").attr('readonly',true);
                $("#employee-contact2").val(employeecontact1);
                $("#employee-contact2").attr('readonly',true);
            }else{
                $(this).prop('checked', false);
                showError("Enter Correspondence Address");
                return false;
            }
            
        }else{
            $("#employee-p_address").val('');
            $("#employee-p_address").removeAttr('readonly');
            $("#employee-p_city").val('');
            $("#employee-p_city").removeAttr('readonly');
            $("#employee-p_state").val('');
            $("#employee-p_state").removeAttr('readonly');
            $("#employee-p_zip").val('');
            $("#employee-p_zip").removeAttr('readonly');
            $("#employee-contact2").val('');
            $("#employee-contact2").removeAttr('readonly');
        }
    });
});

function leaveApproveCheck(id){
    if($("#leaveval_"+id).prop('checked') == true){
        $('#leaveid_'+id).val('');
        $('#leaveid_'+id).val($("#leaveval_"+id).val());
        $("#r_leaveval_"+id).attr('disabled','disabled');
        $("#is_approved_"+id).val('Y');
        $('#r_leaveval_'+id).prop('checked', false);
    }else{
        $('#leaveid_'+id).val('');
        $("#r_leaveval_"+id).removeAttr('disabled');
        $("#is_approved_"+id).val('N');
    }
}
function leaveRejectCheck(id){
    if($("#r_leaveval_"+id).prop('checked') == true){
        $('#leaveid_'+id).val('');
        $('#leaveid_'+id).val($("#leaveval_"+id).val());
        $("#leaveval_"+id).attr('disabled','disabled');
        $("#is_rejected_"+id).val('Y');
        $('#leaveval_'+id).prop('checked', false);
    }else{
        $('#leaveid_'+id).val('');
        $("#leaveval_"+id).removeAttr('disabled');
        $("#is_rejected_"+id).val('N');
    }
}
//function validateApprovedLeave(){
//    hideError();   
//    var selected = [];
//    $('#dataTableShow input:checked').each(function() {
//        selected.push($(this).attr('value'));
//    });
//    if($.isEmptyObject(selected)){
//        showError("Select application to approve");
//        return false;
//    }
//    $('#leavereq').submit();
//}

function entrySlipCheck(id, val){
    if(val == 'A'){
        $('#reject_'+id).prop('checked', false);
        $('#is_approved_'+id).val('N');
        $('#is_rejected_'+id).val('N');
        if($("#approve_"+id).prop('checked') == true){
           $('#leave_id_'+id).val($("#approve_"+id).val());
           $('#is_approved_'+id).val('Y');
        }
    }else if(val == 'R'){
        $('#approve_'+id).prop('checked', false);
        $('#is_approved_'+id).val('N');
        $('#is_rejected_'+id).val('N');
        if($("#reject_"+id).prop('checked') == true){
            $('#leave_id_'+id).val($("#reject_"+id).val());
            $('#is_rejected_'+id).val('Y');
        }
    }
}

function validateEntrySlipLeave(){
    hideError();   
    var selected = [];
    $('#dataTableShow input:checked').each(function() {
        selected.push($(this).attr('value'));
    });
    if($.isEmptyObject(selected)){
        showError("Select application to approve");
        return false;
    }
    $('#entryslipform').submit();
}

function viewModelfamily(id){
    $("#mname").html($("#m_name_"+id).val());
    $("#rwe").html($("#relation_name_"+id).val());
    $("#ms").html($("#marital_status_"+id).val());
    $("#dob").html($("#m_dob_"+id).val());
    $("#ih").html($("#handicap_"+id).val());
    $("#ht").html($("#handicate_type_"+id).val());
    $("#hp").html($("#handicap_percentage_"+id).val());
    $("#mi").html($("#monthly_income_"+id).val());
    $("#ha").html($("#address_"+id).val());
    $("#pa").html($("#p_address_"+id).val());
    $("#dt").html($("#document_type_"+id).val());
    var htm = "<a href=''><img src='"+$("#document_path_"+id).val()+"' width='100' /></a>";
    $("#doc").html(htm);
    $("#viewModelfamily").modal();
}

function addnewdepartment(){
     $("#department, #roleid").prop('selectedIndex',0);
    $("#addnewdeptmodal").modal();
}

function submitNewDept(){
    var department = $("#department").find(":selected").val();
    var roleid = $("#roleid").find(":selected").val();
    var empcode = $("#empcode").val();
    if(!department || !roleid || !empcode){
        swal({
            text: "Select New Department and Role",
            icon: "error",
        });
        return false;
    }

    var _csrf = $('#_csrf').val();
    var menuid = $("#menuid").val();
    var url = BASEURL+"admin/departmentmapping/assignnewdepartment?securekey="+menuid;
    showLoader();
    $("#assigndepts").html('');
    $.ajax({
        type: "POST",
        data:{
            emp_code:empcode,
            department:department,
            roleid:roleid,
            _csrf:_csrf
        },
        url: url,
        success: function(data){
            hideLoader();
            if(data){
                var ht = $.parseJSON(data);
                var status = ht.Status;
                var res = ht.Res;
                if(status == 'SS'){
                    swal({
                        text: "Department / Role Assigned Successfully.",
                        icon: "success",
                    });
                    $(".close").click();
                    showLoader();
                    location.reload(); 
//                    $("#assigndepts").appaned(res);
                    return false;
                }else{
                    swal({
                        text: res,
                        icon: "error",
                    });
                    return false;
                }
            }else{
                return false;
            }
        }
    });
}

function activedeptrole(val){
    var isactive = $("#remove_"+val).data('key');
    var eid = $("#remove_"+val).data('key1');
    
    if(!isactive || !eid){
        swal({
            text: "Param missing. contact admin.",
            icon: "error",
        });
        return false;
    }
    alert(isactive);
    alert(eid);
    
}