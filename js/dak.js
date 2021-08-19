$(document).ready(function()
{
    $('[data-toggle="tooltip"]').tooltip();
    $("#saveasdrf").click(function()
    {
        $('#sbmttype').val('D');
    });
    $("#saveassbmt").click(function()
    {
        $('#sbmttype').val('S');
    });
    $('#viewrecdata,#viewdisdata,#viewdadhboard').DataTable(
    {
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
    });
    $('.dakrecpdate,.dispdate').css('cursor','pointer');
    $('.dakrecpdate,.dispdate').datepicker({
        autoclose:true,
        format: "dd-mm-yyyy",
        endDate: '0d',
    }).click(function(){
        $('.datepicker-days').css('display','block');
    });
    $('#postal_date').css('cursor','pointer');
    $('#postal_date').datepicker({
        autoclose:true,
        format: "dd-mm-yyyy",
        endDate: '0d',
    }).click(function(){
        $('.datepicker-days').css('display','block');
    });
    
    $("#viewdakreceipt").click(function()
    {
        $('#viewdaktype').val('viewdakdispatch');
        $('#viewdakreceipt').removeClass('btn-primary');
        $('#viewdakreceipt').addClass('btn-success');
        $('#viewdakdispatch').removeClass('btn-primary btn-success');
        $('#viewdakdispatch').addClass('btn-primary');
        $('.viewdakdishtml').hide();
        $('.viewdakrechtml').show();
    });
    $("#viewdakdispatch").click(function()
    {
        $('#viewdaktype').val('viewdakdispatch');
        $('#viewdakdispatch').removeClass('btn-primary');
        $('#viewdakdispatch').addClass('btn-success');
        $('#viewdakreceipt').removeClass('btn-primary btn-success');
        $('#viewdakreceipt').addClass('btn-primary');
        $('.viewdakrechtml').hide();
        $('.viewdakdishtml').show();
    });
    
    
    $("#dakreceipt").click(function()
    {
        $('#daktype').val('dakreceipt');
        $('#dakreceipt').removeClass('btn-primary');
        $('#dakreceipt').addClass('btn-success');
        $('#dakdispatch').removeClass('btn-primary btn-success');
        $('#dakdispatch').addClass('btn-primary');
        $('.dakdishtml').hide();
        $('.dakrechtml').show();
    });
    $("#dakdispatch").click(function()
    {
        $('#daktype').val('dakdispatch');
        $('#dakdispatch').removeClass('btn-primary');
        $('#dakdispatch').addClass('btn-success');
        $('#dakreceipt').removeClass('btn-primary btn-success');
        $('#dakreceipt').addClass('btn-primary');
        $('.dakrechtml').hide();
        $('.dakdishtml').show();
    });
    $("#dakreceiptormsave").click(function()
    {
        hideError();
        var rece_mode = $("#rece_mode").val();
        if(!rece_mode)
        {
            swal({
                text: "Please Select Mode of Received",
                icon: "error",
            });
            return false;
        }
        
        var recpt_language = $("#recpt_language").val();
        if(!recpt_language)
        {
            swal({
                text: "Please Select Language",
                icon: "error",
            });
            return false;
        }
        
        var dakno = $("#dakno").val();
        if(!dakno)
        {
            swal({
                text: "Receipt number can not be blank",
                icon: "error",
            });
            return false;
        }
        var receiptdate = $("#receiptdate").val();
        if(!receiptdate)
        {
            swal({
                text: "Please Select Receipt Date",
                icon: "error",
            });
            return false;
        }
        var receiptfrom = $("#receiptfrom").val();
        if(!receiptfrom)
        {
            swal({
                text: "Please enter Recieved From(Person Name/Designation)",
                icon: "error",
            });
            return false;
        }
        var orgname = $("#orgname").val();
        if(!orgname)
        {
            swal({
                text: "Please enter Organization Address",
                icon: "error",
            });
            return false;
        }
        var c_state_select = $("#state_id_rec").val();
        if(!c_state_select)
        {
            swal({
                text: "Please select state",
                icon: "error",
            });
            return false;
        }
        var c_district_select = $("#district_id_rec").val();
        if(!c_district_select)
        {
            swal({
                text: "Please select district",
                icon: "error",
            });
            return false;
        }
//        var recsummary = $("#recsummary").val();
//        if(!recsummary)
//        {
//            swal({
//                text: "Please enter Short Summary of Dak ",
//                icon: "error",
//            });
//            return false;
//        }
        var dept_emp_dropdown = $("#dept_emp_dropdown").val();
        if(!dept_emp_dropdown)
        {
            swal({
                text: "Please Select Department",
                icon: "error",
            });
            return false;
        }
        var dept_emp_list_dropdown = $("#dept_emp_list_dropdown").val();
        if(!dept_emp_list_dropdown)
        {
            swal({
                text: "Please Select Employee",
                icon: "error",
            });
            return false;
        }
        
        if(confirm("Please Check all the field before submit the form,Once Submit no updation are allowed."))
        {
           $("#dakreceiptorm").submit();
            showLoader();
           return true;
        }
        else{
            return false;
        }
        
    });
    $("#dakdispatchtormsave").click(function()
    {
        hideError();
        var dipatch_mode = $("#dipatch_mode").val();
        if(!dipatch_mode)
        {
            swal({
                text: "Please Select Mode of Dispatch",
                icon: "error",
            });
            return false;
        }
        var dispatch_language = $("#dispatch_language").val();
        if(!dispatch_language)
        {
            swal({
                text: "Please Select Entry Language",
                icon: "error",
            });
            return false;
        }
        
        var daknodispatch = $("#daknodispatch").val();
        if(!daknodispatch)
        {
            swal({
                text: "Please Dispatch Number can not be blank",
                icon: "error",
            });
            return false;
        }
        var dispdate = $("#dispdate").val();
        if(!dispdate)
        {
            swal({
                text: "Please Select Dispatch Date",
                icon: "error",
            });
            return false;
        }
        var old_serial_number = $("#serial_number").val();
        var disptchfor = $("#disptchfor"+old_serial_number).val();
        var disporgadd = $("#disporgadd"+old_serial_number).val();
        var state_id_disval = $("#state_id_dis"+old_serial_number).val();
        var district_id_disval = $("#district_id_dis"+old_serial_number).val();
        if(!disptchfor)
        {
            swal({
                    text: "Please enter Dispatch To(Person Name/Designation)",
                    icon: "error",
                });
            return false;
        }
        if(!state_id_disval)
        {
            swal({
                    text: "Please Select State",
                    icon: "error",
                });
            return false;
        }
        if(!district_id_disval)
        {
            swal({
                    text: "Please Select District",
                    icon: "error",
                });
            return false;
        }
        if(!disporgadd)
        {
            swal({
                    text: "Please enter Organization Address",
                    icon: "error",
                });
            return false;
        }
//        var dissummary = $("#dissummary").val();
//        if(!dissummary)
//        {
//            swal({
//                text: "Please enter Short Summary of Dak ",
//                icon: "error",
//            });
//            return false;
//        }
        
        
        var dept_emp_dropdown_disptach = $("#dept_emp_dropdown_disptach").val();
        if(!dept_emp_dropdown_disptach)
        {
            swal({
                text: "Please Select Department",
                icon: "error",
            });
            return false;
        }
        var dept_emp_list_disptach = $("#dept_emp_list_disptach").val();
        if(!dept_emp_list_disptach)
        {
            swal({
                text: "Please Select Employee",
                icon: "error",
            });
            return false;
        }
        if(confirm("Please Check all the field before submit the form,Once Submit no updation are allowed.")){
            $("#dakdispatchtorm").submit();
            showLoader();
            return true;
        }else{
            return false;
        }
        
        
        
    });
    $('#state_id_rec').change(function()
    {
        var state_id = $(this).find(":selected").val();
        if(state_id)
        {
            $("#district_id_rec").html("<option value=''>Select District</option>");
            var url = BASEURL+"common/get_districts";
            var _csrf = $("#_csrf").val();
            $.ajax({
                type: "POST",
                data :
                {
                    'state_id':state_id,
                    '_csrf':_csrf
                },
                url: url,
                success: function(data)
                {
                    if(data)
                    {
                        var ht = $.parseJSON(data);
                        var status = ht.Status;
                        var res = ht.Res;
                        if(status == 'SS')
                        {
                            $("#district_id_rec").html(res);
                            return false;
                        }
                        else
                        {
                            showError(res); 
                            return false;
                        }
                    }
                    else
                    {
                        return false;
                    }
                }
            });
		}
    });
   
    
});
function checkfilesizeofmultiple(id) 
{
    var file_size =  $("#"+id)[0].files[0].size;
    file_size = file_size / 1024;
    if(file_size > 1024)
    {
        $("#"+id).val("");
        showError("Please upload 1 MB file ");
        return false;
    }
    var ext = $("#"+id)[0].files[0].type;
    var chkext = true;
    if(ext == "image/png")
    { 
    }
    else if(ext == "image/jpeg")
    {
    }
    else if(ext == "image/jpg")
    {
    }
    else if(ext == "application/pdf")
    {
    }
    else
    {
        chkext = false;
    }
    
    if(!chkext)
    {
        $("#"+id).val("");
        showError("Only .jpg, .jpeg,.png,.pdf files are allowed");
        return false;
    }
}
function getdispatchdist(id)
{
    var state_id = $('#state_id_dis'+id).val();
    if(state_id)
    {
        $("#district_id_dis"+id).html("<option value=''>Select District</option>");
        var url = BASEURL+"common/get_districts";
        var _csrf = $("#_csrf").val();
        $.ajax({
            type: "POST",
            data :
            {
                'state_id':state_id,
                '_csrf':_csrf
            },
            url: url,
            success: function(data)
            {
                if(data)
                {
                    var ht = $.parseJSON(data);
                    var status = ht.Status;
                    var res = ht.Res;
                    if(status == 'SS')
                    {
                        $("#district_id_dis"+id).html(res);
                        return false;
                    }
                    else
                    {
                        showError(res); 
                        return false;
                    }
                }
                else
                {
                    return false;
                }
            }
        });
		}
}
function addmorreaddress()
{
    var old_serial_number = $("#serial_number").val();
    var disptchfor = $("#disptchfor"+old_serial_number).val();
    var disporgadd = $("#disporgadd"+old_serial_number).val();
    var state_id_dishtml = $("#state_id_dis"+old_serial_number).html();
    var state_id_disval = $("#state_id_dis"+old_serial_number).val();
    var district_id_disval = $("#district_id_dis"+old_serial_number).val();
    
    if(!disptchfor)
    {
        swal({
                text: "Please enter Dispatch To(Person Name/Designation)",
                icon: "error",
            });
        return false;
    }
    if(!state_id_disval)
    {
        swal({
                text: "Please Select State",
                icon: "error",
            });
        return false;
    }
    if(!district_id_disval)
    {
        swal({
                text: "Please Select District",
                icon: "error",
            });
        return false;
    }
    if(!disporgadd)
    {
        swal({
                text: "Please enter Organization Address",
                icon: "error",
            });
        return false;
    }
    old_serial_number=parseInt(old_serial_number) + parseInt('1');
    var html='<div class="row" id="addressdiv'+old_serial_number+'"><div class="col-sm-6 mb15"><label>डिस्पैच (नाम और पदनाम) / Dispatch To(Person Name/Designation)</label>';
    html += '<input type="text" class="form-control form-control-sm " id="disptchfor'+old_serial_number+'" name="disptchfor[]" required=""  placeholder="Dispatch To(Person Name/Designation)"/>';
    html += '</div>';
    html += '<div class="col-sm-6 mb15"><label>संगठन राज्य / Organization State</label>'; 
    html += '<select class="form-control form-control-sm" name="state_id_dis[]" id="state_id_dis'+old_serial_number+'" onchange="return getdispatchdist('+old_serial_number+')">'; 
    html += '<option value="">Select State</option></select></div>';
    html += '<div class="col-sm-6 mb15"><label>संगठन जिला / Organization District</label>'; 
    html += '<select class="form-control form-control-sm" name="district_id_dis[]" id="district_id_dis'+old_serial_number+'" >'; 
    html += '<option value="">Select District</option></select></div>';
    html += '<div class="col-sm-5 mb15"><label>संगठन का पता / Organization Address</label>'; 
    html += '<textarea id="disporgadd'+old_serial_number+'" name="disporgadd[]" class="form-control form-control-sm" placeholder="Organization Address"></textarea>';
    html += '</div>'; 
    html += '<div class="col-sm-1 mb15"><label></label>';   
    html += '<button data-toggle="tooltip" title="Remove Organization address" class="btn btn-sm" id="removedivaddress" onclick="return removedivaddresshtml('+old_serial_number+')"><img src='+BASEURL+'images/details_close.png></button>';
    html += '</div>'; 
    $('#appendhtmlforaddress').append(html);
    $("#state_id_dis"+old_serial_number).html(state_id_dishtml);
    $("#serial_number").val(old_serial_number);
    return false;
}
function removedivaddresshtml(divid)
{
    $('#addressdiv'+divid).remove();
    var old_serial_number = $("#serial_number").val();
    var currentSerialNumber=parseInt(old_serial_number) - parseInt('1');
    $("#serial_number").val(currentSerialNumber);
}

function postalform(){
    var postal_amount = $.trim($("#postal_amount").val());
    $("#postal_amount").val(postal_amount);
    if(!postal_amount){
        swal({
            text: "Enter Postal Amount",
            icon: "error",
        });
        return false;
    }
    
    var postal_date = $.trim($("#postal_date").val());
    $("#postal_amount").val(postal_amount);
    if(!postal_amount){
        swal({
            text: "Select Postal Date",
            icon: "error",
        });
        return false;
    }
    
    $("#postal_form").submit();
    showLoader();
}