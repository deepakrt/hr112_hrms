$(document).ready(function(){
    $('#Opt-bill_date, #pf_subscription_date').css('cursor','pointer');
    $('#Opt-bill_date, #pf_subscription_date').datepicker({
        autoclose:true,
        format: "dd-mm-yyyy",
        endDate: '0d',
    }).click(function(){
        $('.datepicker-days').css('display','block');
    });
    
    $('#opdFnYr').change(function(){
        var menuid = $("#menuid").val();
        var fn = $(this).val();
        var url = BASEURL+"employee/reimbursement/opd?securekey="+menuid+"&fn="+fn;
        window.location.replace(url);
    });
    
    $('.opt_patient').click(function(){
        $("#Opt-dependent_id").attr('disabled','disabled');
        $('#Opt-dependent_id').html('<option value="">-- Select --</option>');
        if($(this).val() == 2){
            var _csrf = $('#_csrf').val();
            var menuid = $("#menuid").val();
            var url = BASEURL+"employee/reimbursement/get_dependent_family?securekey="+menuid;
            $.ajax({
                type: "GET",
                url: url,
                success: function(data){
                    if(data){
                        $("#Opt-dependent_id").removeAttr('disabled');
                        var ht = $.parseJSON(data);
                        var status = ht.Status;
                        var res = ht.Res;
                        $('#Opt-dependent_id').append(res);
//                        if(status == 'SS'){
//                            $('#Opt-dependent_id').append(res);
//                        }else{
//                            $('#Opt-dependent_id').append(res);
//                            //showError(res); 
//                            return false;
//                        }
                    }else{
                        return false;
                    }
                }
            });
        }
    });
    $("#opt_save").click(function(){
        hideError();
        if($('#Opt-Patient1').is(':checked')) { 
            
        }else if($('#Opt-Patient2').is(':checked')) { 
            var dependent_id = $("#Opt-dependent_id").val();
            if(!dependent_id){
                showError("Select Dependent Family Member.");
                return false;
            }
        }else{
            showError("Invalid Patient Type");
            return false;
        }
        
        var bill_no = $.trim($("#Opt-bill_no").val());
        $("#Opt-bill_no").val(bill_no);
        if(!bill_no){
            showError("Enter Bill No.");
            return false;
        }
        var bill_date = $.trim($("#Opt-bill_date").val());
        $("#Opt-bill_date").val(bill_date);
        if(!bill_date){
            showError("Enter Bill Date");
            return false;
        }
        var bill_amount = $.trim($("#Opt-bill_amount").val());
        $("#Opt-bill_amount").val(bill_amount);
        if(!bill_amount){
            showError("Enter Bill Amount");
            return false;
        }
        
        if(bill_amount == '0'){
            showError("Bill amount cannot Zero (0).");
            return false;
        }
        var bill_type = $.trim($("#Opt-bill_type").val());
        if(!bill_type){
            showError("Select Bill Type");
            return false;
        }
        var issuer = $.trim($("#Opt-issuer").val());
        $("#Opt-issuer").val(issuer);
        if(!issuer){
            showError("Enter Issuer Name");
            return false;
        }
        $('#opdform').submit();
    });
    
    $(".deleteclaim").click(function(){
        if(confirm("Claim application as well as all the expense entries filled against application will be deleted. Are you sure?")){ return true; }
        return false;
    });
    
    $('#validfrom, #validtill').css('cursor','pointer');
    $('#validfrom').datepicker({
        autoclose:true,
        format: "dd-mm-yyyy",
        orientation: "top-left",
        endDate: '0d',
    }).on('changeDate', function (selected){
        hideModalError();
        var minDate = new Date(selected.date.valueOf());
        $('#validtill').datepicker('setStartDate', minDate);
        $("#validtill").val('');
    });
    $("#validtill").datepicker({
        autoclose:true,
        format: "dd-mm-yyyy",
        orientation: "top",
//        endDate: '0d',
    }).on('changeDate', function(ev){
    {
        hideModalError();
        var fromdateval= $("#validfrom").val();
        if(fromdateval==''){
            $("#validtill").val('');
            showModalError("Enter Date of Policy Valid From");
            return false;
        }
    }
    });
    $("#addInsurnDetails").click(function(){
        hideError();
        var patient_type = "";
        $("#PatientList").find('input[type=radio]:checked').each(function(){
            patient_type = $(this).val();
        });
        var dependent_id = "";
        $("#pType").val("");
        if(patient_type == '2'){
            $("#pType").val(patient_type);
            dependent_id = $("#ipd-dependent_id").val();
            if(!dependent_id){
                showError("Select Dependent Family Member");
                return false;
            }
            var name = $("#ipd-dependent_id").find(":selected").text();
            $("#membername").html(name);
            $("#dependent_id").val(dependent_id);
        }else{
            $("#pType").val(patient_type);
            $("#membername").html("Self");
        }
    });
    
    $("#saveinsrn").click(function(){
        hideModalError();
        var patient_type = "";
        $("#PatientList").find('input[type=radio]:checked').each(function(){
            patient_type = $(this).val();
        });
        if(!patient_type){
            showModalError("Invalid Patient Type");
            return false;
        }
        patient_type = parseInt(patient_type);
        if(patient_type == '1'){
        }else if(patient_type == '2'){
        }else{
            showModalError("Invalid Patient Type ID");
            return false;
        }
        
        var comname = $.trim($("#comname").val());
        $("#comname").val(comname);
        if(!comname){
            showModalError("Enter Company Name");
            return false;
        }
        var policynumber = $.trim($("#policynumber").val());
        $("#policynumber").val(policynumber);
        if(!policynumber){
            showModalError("Enter Policy Number");
            return false;
        }
        var validfrom = $.trim($("#validfrom").val());
        if(!validfrom){
            showModalError("Select Date of Policy Valid From");
            return false;
        }
        var validtill = $.trim($("#validtill").val());
        if(!validtill){
            showModalError("Select Date of Policy Valid Till");
            return false;
        }
        
        var str = $("#saveinsrnForm :input").serialize();
        var menuid = $("#menuid").val();
        var url = BASEURL+"employee/ipdreimbursement/saveinsurance?securekey="+menuid;
        $.ajax({
                type: "POST",
                url: url,
                data: { info:str},
                success: function(data){
                    if(data){
                        var ht = $.parseJSON(data);
                        var status = ht.Status;
                        var res = ht.Res;
                        if(status == 'SS'){
                            alert("Successfully Added.");
                            $(".close, .claimtype").click();
                            $("#validfrom, #validtill, #policynumber, #pType, #dependent_id, #comname").val("")
                        }else{
                            showModalError(res);
                            return false;
                        }
                    }
                }
            });
    });
    $('#date_of_admission').datepicker({
        autoclose:true,
        format: "dd-mm-yyyy",
        orientation: "top-left",
        endDate: '0d',
    }).on('changeDate', function (selected){
        var minDate = new Date(selected.date.valueOf());
        $('#date_of_discharge').datepicker('setStartDate', minDate);
        $("#date_of_discharge").val('');
    });
    
    $("#date_of_discharge").datepicker({
        autoclose:true,
        format: "dd-mm-yyyy",
        orientation: "top",
        endDate: '0d',
    }).on('changeDate', function(ev){
    {
        var fromdateval= $("#date_of_admission").val();
        if(fromdateval==''){
            $("#date_of_discharge").val('');
            showError("Enter Date of Admission");
            return false;
        }
    }
    });
    
    $('.ipd_patient').click(function(){
        $("#ipd-dependent_id").attr('disabled','disabled');
        $('#ipd-dependent_id').html('<option value="">-- Select --</option>');
        $(".claimtype"). prop("checked", false);
        $("#insuranceinfo").hide();
        $("#insurance_id").html("<option value=''>Select Insurance Details</option>");
        $("#insrn_sanc_amt").val('');
        if($(this).val() == 2){
            var _csrf = $('#_csrf').val();
            var menuid = $("#menuid").val();
            var url = BASEURL+"employee/reimbursement/get_dependent_family?securekey="+menuid;
            $.ajax({
                type: "GET",
                url: url,
                success: function(data){
                    if(data){
                        var ht = $.parseJSON(data);
                        var status = ht.Status;
                        var res = ht.Res;
                        if(status == 'SS'){
                            $('#ipd-dependent_id').append(res);
                            $("#ipd-dependent_id").removeAttr('disabled');
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
    });
    
    $(".claimtype").click(function(){
        var claim_type = $(this).data('key');
        hideError();
        var dependent_id = "";
        $("#insurance_id").html("<option value=''>Select Insurance Details</option>");
        $("#insrn_sanc_amt").val('');
        $("#insuranceinfo").hide();
        if(claim_type == '2'){
            var patient_type = "";
            $("#PatientList").find('input[type=radio]:checked').each(function(){
                patient_type = $(this).val();
            });
            
            if(patient_type =='1' || patient_type == 2){
                if(patient_type == '2'){
                    var dependent_id = $("#ipd-dependent_id").val();
                    if(!dependent_id){
                        $(this).prop("checked", false);
                        showError("Select Dependent Family Member");
                        return false;
                    }
                }
                var menuid = $("#menuid").val();
                var url = BASEURL+"employee/ipdreimbursement/getinsurancedetails?securekey="+menuid+"&pt="+patient_type+"&dependent_id="+dependent_id;
                $.ajax({
                    type: "GET",
                    url: url,
                    success: function(data){
                        if(data){
                            var ht = $.parseJSON(data);
                            var status = ht.Status;
                            var res = ht.Res;
                            $('#insurance_id').html(res);
                            $("#insuranceinfo").show();
                        }
                    }
                });
            }else{
                $(this).prop("checked", false);
                showError("Invalid Patient Value");
                return false;
            }
        }
    });
    
    $('#saveipd').click(function(){
        hideError();
        var patient_type = "";
        $("#PatientList").find('input[type=radio]:checked').each(function(){
            patient_type = $(this).val();
        });
        if(patient_type == '2'){
            var dependent_id = $("#ipd-dependent_id").val();
            if(!dependent_id){
                showError("Select Dependent Family Member");
                return false;
            }
        }
        
        var date_of_admission = $("#date_of_admission").val();
        if(!date_of_admission){
            showError("Enter Date of Admission");
            return false;
        }
        var date_of_discharge = $("#date_of_discharge").val();
        if(!date_of_discharge){
            showError("Enter Date of Discharge");
            return false;
        }
        var admitted_for = $.trim($("#admitted_for").val());
        $("#admitted_for").val(admitted_for);
        if(!admitted_for){
            showError("Enter Admitted For?");
            return false;
        }
        var typeclaim = "";
        var typeclaim = "";
        $("#typeclaim").find('input[type=radio]:checked').each(function(){
            typeclaim = $(this).data('key');
        });
        if(!typeclaim){
            showError("Select Claim Type");
            return false;
        }
        if(typeclaim == '2'){
            var insurance_id = $("#insurance_id").val();
            if(!insurance_id){
                showError("Select Insurance Details");
                return false;
            }
            var insrn_sanc_amt = $.trim($("#insrn_sanc_amt").val());
            $("#insrn_sanc_amt").val(insrn_sanc_amt);
            if(!insrn_sanc_amt){
                showError("Enter Insurance Sanctioned Amount");
                return false;
            }
        }
        
    });
    $(".deleteipdbill").click(function(){
        if(confirm("Are you sure want to delete this bill?")){
            return true;
        }
        return false;
    });
    
    $("#saveipdbill").click(function(){
        hideError();
        var bill_number = $.trim($("#bill_number").val());
        $("#bill_number").val(bill_number);
        if(!bill_number){
            showError("Enter Bill Number.")
            return false;
        }
        var bill_number = $.trim($("#bill_number").val());
        $("#bill_number").val(bill_number);
        if(!bill_number){
            showError("Enter Bill Number.")
            return false;
        }
        var bill_date = $.trim($("#bill_date").val());
        $("#bill_date").val(bill_date);
        if(!bill_date){
            showError("Select Bill Date.")
            return false;
        }
        var bill_amt = $.trim($("#bill_amt").val());
        $("#bill_amt").val(bill_amt);
        if(!bill_amt){
            showError("Enter Bill Amount.")
            return false;
        }
        var issuer = $.trim($("#issuer").val());
        $("#issuer").val(issuer);
        if(!issuer){
            showError("Enter Bill Issuer Name.")
            return false;
        }
        
        $("#ipdbillform").submit();
    });
    
    $(".annclaimmodel").click(function(){
        var name = $(this).attr('data-name');
        var ann_reim_id= $(this).attr('data-key1');
        var fy= $(this).attr('data-key2');
        var samt= $(this).attr('data-samt');
        if(name && ann_reim_id && fy){
            $('#c_title').html(name);
            $('#c_entitle').html(samt);
            $('#c_ari').val(ann_reim_id);
            $('#c_fy').val(fy);
            
            $('#annclaim').modal();
        }else{
            
        }
    });
    
    $('.view_ann_reim').click(function(){
        $('#htmlform').html('');
        hideError();
        var ann_reim_id= $(this).attr('data-key1');
        var fy = $(this).attr('data-key2');
        var menuid = $("#menuid").val();
        var url = BASEURL+"employee/reimbursement/viewannureim?securekey="+menuid+"&key1="+ann_reim_id+"&key2="+fy;
        $.ajax({
            type: "GET",
            url: url,
            success: function(data){
                if(data){
                    var ht = $.parseJSON(data);
                    var status = ht.Status;
                    var res = ht.Res;
                    if(status == 'SS'){
                        $('#htmlform').html(res);
                        $('#viewdetails').modal();
                        return false;
                    }else{
                        showError(res);
                        return false;
                    }
                    
                }
            }
        });
        
    });
    
    /*
    * Generate Salary
    */
    $("#GS_emp_type").change(function(){
        hideError();
        $('#GS_month, #GS_year').prop('selectedIndex',0);
        $('#canteen_allowances').html('');
    });
    $("#GS_month").change(function(){
        hideError();
        $('#GS_year').prop('selectedIndex',0);
        $('#canteen_allowances').html('');
        if(!$("#GS_emp_type").val()){
            $(this).prop('selectedIndex',0);
            showError("Select Employee Type");
            return false;
        }
    });
    $("#GS_year").change(function(){
        hideError();
        $('#canteen_allowances').html('');
        var GS_emp_type = $("#GS_emp_type").val();
        if(!GS_emp_type){
            $(this).prop('selectedIndex',0);
            showError("Select Employee Type");
            return false;
        }
        var GS_month = $("#GS_month").val();
        if(!GS_month){
            $(this).prop('selectedIndex',0);
            showError("Select Select Month");
            return false;
        }
        var GS_yr = $(this).val();
        if(!GS_yr){
            return false;
        }
        var menuid = $("#menuid").val();
        var url = BASEURL+"finance/canteenallowances/getdaysofmonth?securekey="+menuid;
        $.ajax({
            type: "POST",
            url: url,
            data:{
               GS_emp_type:GS_emp_type,
               GS_month:GS_month,
               GS_yr:GS_yr
            },
            success: function(data){
//                    alert(data); return false;
                if(data){
                    var ht = $.parseJSON(data);
                    var status = ht.Status;
                    var res = ht.Res;
                    if(status == 'SS'){
                        $('#canteen_allowances').html(res);
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
    });
    
    $('#geneartesalary').click(function(){
        var Salary_emp_type = $('#Salary_emp_type').val();
        var Salary_month = $('#Salary_month').val();
        var Salary_year = $('#Salary_year').val();
        if(Salary_emp_type && Salary_month && Salary_year){
            showLoader();
        }
    });
    
    
    /*
    * End Generate Salary
    */
    
    $('.itaxfy').change(function(){
        var menuid = $("#menuid").val();
        var fn = $(this).val();
        showLoader();
        var url = BASEURL+"employee/incometaxdetails?securekey="+menuid+"&fn="+fn;
        window.location.replace(url);
    });
    
    $('#incomeslip').click(function(){
        $('#html_view_incomeslip').html('');
        var fn = $(this).data('key');
        if(fn){
            var menuid = $("#menuid").val();
            var _csrf = $("#_csrf").val();
            var url = BASEURL+"employee/incometaxdetails/viewincomeslip?securekey="+menuid;
            $.ajax({
                type: "POST",
                url: url,
                data:{
                    fn:fn,
                    _csrf:_csrf
                },
                success: function(data){
                    if(data){
                        var ht = $.parseJSON(data);
                        var status = ht.Status;
                        var res = ht.Res;
//                        alert(status);
//                        alert(res);
                        if(status == 'SS'){
                            $('#html_view_incomeslip').html(res);
                            $('#viewincomeslip').modal();
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
    });
    
    $(".pfshow").click(function(){
        $('#modelPFview_html').html('');
        var ec = $(this).data('key');
        if(ec){
            var menuid = $("#menuid").val();
            var _csrf = $("#_csrf").val();
            var url = BASEURL+"finance/pfaccounts/getpfsummary?securekey="+menuid;
            $.ajax({
                type: "POST",
                url: url,
                data:{
                    ec:ec,
                    _csrf:_csrf
                },
                success: function(data){
//                    alert(data);
                    if(data){
                        var ht = $.parseJSON(data);
                        var status = ht.Status;
                        var res = ht.Res;
                        if(status == 'SS'){
                            $('#modelPFview_html').html(res);
                            $("#modelPFview").modal();
                            return false;
                        }else{
                            $('.close').click();
                            showError(res); 
                            return false;
                        }
                    }else{
                        return false;
                    }
                }
            });
        }
    });
}); //document end
function con_claim_submit(id){
    hideError();
    var claimamunt = $.trim($('#claimamunt').val());
    $('#claimamunt').val(claimamunt);
    var purpose = $.trim($('#purpose').val());
    $('#purpose').val(purpose);
    
    if(!claimamunt){
        showError("Enter Claim Amount");
        return false;
    }
    if(!purpose){
        showError("Enter Purpose");
        return false;
    }
    if(id == '1'){
        $('#submit_type').val('Draft');
    }else if(id == '2'){
        $('#submit_type').val('Pending');
    }else{
        showError("Fraudulent Data Detected");
        return false;
    }
    
    $("#contingencyform").submit();
}

function claimSanction(val){
    hideError();
    var sanctioned_amt = $.trim($('#sanctioned_amt').val());
    $('#sanctioned_amt').val(sanctioned_amt);
    if(!sanctioned_amt){
        showError("Enter Sanction Amount");
        return false;
    }
    if(val == '1'){
        $('#submit_type').val('In-Process');
    }else if(val == '2'){
        $('#submit_type').val('Sanctioned');
    }else if(val == '3'){
        $('#submit_type').val('Rejected');
    }else{
        showError("Fraudulent Data Detected");
        return false;
    }
    $("#contingencyform").submit();
}