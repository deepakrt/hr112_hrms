$(document).ready(function(){
    
    $('.aydate, #hrgeneralforms-entry_date, #employee-effected_from, #employeefamilydetails-m_dob, #otherpassed_on, #passed_on').css('cursor','pointer');
    $('.aydate, #employee-effected_from, #employeefamilydetails-m_dob, #otherpassed_on,#passed_on').datepicker({
        autoclose:true,
        format: "dd-mm-yyyy",
        endDate: '0d',
    }).click(function(){
        $('.datepicker-days').css('display','block');
    });
    $("#quali_academic").click(function(){
        $('#quali_academic').removeClass('btn-light')
        $('#quali_academic').addClass('btn-success')
        $('#quali_other').addClass('btn-light')
        $('#quali_other').removeClass('btn-success')
        $("#quali_type").val('A');
        $("#academic_").show();
        $("#other_").hide();
    });
    $("#quali_other").click(function(){
        $('#quali_other').removeClass('btn-light')
        $('#quali_other').addClass('btn-success')
        $('#quali_academic').addClass('btn-light')
        $('#quali_academic').removeClass('btn-success')
        $("#quali_type").val('O');
        $("#academic_").hide();
        $("#other_").show();
    });
    
    //Certificate Validate 
    $(".certi").on("change", function(){
        var id = $(this).attr('id');
        hideError();
        var ext = this.files[0].type;
        var chkext = true;
        if(ext == "image/png"){ 
        }else if(ext == "image/jpeg"){
        }else if(ext == "image/jpg"){
        }else{
            chkext = false;
        }
        if(!chkext){
            $(this).val('');
            showError("Allowed only .jpg, .jpeg, .png only");
            return false;
        }
        var jpeg_magic = '0xFFD8FFE0';
        var pngmagic ="0x89504E47";
        var jpgmagic2 = "0xFFD8FFE1";
        var jpgmagic3 = "1195984440";
        var slice = this.files[0].slice(0,4);     
        var reader = new FileReader();    
        reader.readAsArrayBuffer(slice);
        reader.onload = function(e)
        {
            var buffer = reader.result;        
            var view = new DataView(buffer);    
            var magic = view.getUint32(0, false); 
//            alert(magic);
            if((magic == jpeg_magic) || (magic == pngmagic ) || (magic == jpgmagic2 ) || (magic == jpgmagic3)){
               var fileSize = parseInt(Photo_Sign_Size)*parseInt("1024");
                if(this.files[0].size > fileSize){
                    showError("File size should be less than "+Photo_Sign_Size+"KB");
                    return false;
                }
           }else{
               $(this).val('');
                var error = 'File type / content not supported... !';
                showError(error);
                return false; 
           }
        };
    });
    //validate qualification
    $("#save_quali").click(function(){
        hideError();
        var quali_type = $("#quali_type").val();
        if(quali_type == 'A'){
            var quali_id = $("#quali_id").val();
            if(!quali_id){
                showError("Select Qualification");
                return false;
            }
            var discipline = $("#discipline").val();
            if(!discipline){
                showError("Enter discipline");
                return false;
            }
            var institute = $("#institute").val();
            if(!institute){
                showError("Enter Institute");
                return false;
            }
            var uni_b = $("#uni_b").val();
            if(!uni_b){
                showError("Enter University / Board");
                return false;
            }
            var address = $("#address").val();
            if(!address){
                showError("Enter Institute Address");
                return false;
            }
            var passed_on = $("#passed_on").val();
            if(!passed_on){
                showError("Select Passed On Date");
                return false;
            }
            
            
        }else if(quali_type == 'O'){
            var other_quali = $("#other_quali").val();
            if(!other_quali){
                showError("Enter Qualification");
                return false;
            }
            var otherpassed_on = $("#otherpassed_on").val();
            if(!otherpassed_on){
                showError("Select Passed On Date");
                return false;
            }
        }else{
            showError("Invalid Qualification Type");
            return false;
        }
        
        var grade = $("#grade").val();
        var percentage = $("#percentage").val();
        var cgpa = $("#cgpa").val();
        if(!cgpa && !percentage && !grade){
            showError("Enter Grade / Percentage / C.G.P.A.");
            return false;
        }
        
        var doc_type = $("#doc_type").val();
        if(!doc_type){
            showError("Select Document Type");
            return false;
        }
        var document = $("#document").val();
        if(!document){
            showError("Upload Document");
            return false;
        }
        
        $("#qualiForm").submit();
    });
    
    //show leave balance
    $('#leavebalance').click(function(){
        var id = $(this).val();
        if(id == 'H'){
            $(this).removeClass('btn-secondary');
            $(this).addClass('btn-success');
            $(this).val('S');
            $("#leavebalances").show();
        }else if(id == 'S'){
            $(this).removeClass('btn-success');
            $(this).addClass('btn-secondary');
            $(this).val('H');
            $("#leavebalances").hide();
        }else{
            $("#leavebalances").hide();
        }
    });
    
        
    $("#employeeleavesrequests-leave_type").change(function(){
//        alert($(this).attr('data-key'));
//        alert($(this).find('option:selected').data('key'));
//        return false;
        $("#checkhalfday").val('1');
        $("#show_fullday").hide();
        $("#employeeleavesrequests-whetherhalfday").html('');
        if($(this).val()){
            var id = $(this).find('option:selected').data('key');
            var menuid = $("#menuid").val();
            var _csrf = $("#_csrf").val();
            var url = BASEURL+"employee/leave/checkcanapplyhalfday?securekey="+menuid;
            $.ajax({
                type: "POST",
                url: url,
                data:{
                    lcid:id,
                    _csrf:_csrf
                },
                success: function(data){
                    //alert(data);
                    if(data){
                        var ht = $.parseJSON(data);
                        var status = ht.Status;
                        var res = ht.Res;
                        if(status == 'SS'){
                            if(res){
                                $("#checkhalfday").val('2');
                                $("#show_fullday").show();
                                $("#employeeleavesrequests-whetherhalfday").html(res);
                            }
                        }else{
                            $("#show_fullday").hide();
                            $("#employeeleavesrequests-whetherhalfday").html('');
                            showError(res); 
                            return false;
                        }
                    }else{
                        return false;
                    }
                }
            });
        }
//        if($(this).find(":selected").text() == 'Casual Leave (CL)'){
//            $("#show_fullday").show();
//        }else{
//            $("#show_fullday").hide();
//        }
    });
    
    $("#employeefamilydetails-handicap").change(function(){
        
        var id = $(this).val();
        
        $('#employeefamilydetails-handicate_type,#employeefamilydetails-handicap_percentage ').val('');
        if(id == 'Y'){
            $("#employeefamilydetails-handicate_type").removeAttr('disabled');
            $("#employeefamilydetails-handicap_percentage").removeAttr('disabled');
        }else if(id == 'N'){
            $("#employeefamilydetails-handicate_type").attr('disabled','disabled');
            $("#employeefamilydetails-handicap_percentage").attr('disabled','disabled');
        }
    });
    
    $('#sameasaddress').click(function(){
        hideError();
       if($(this).is(':checked')){
           var add = $.trim($("#employeefamilydetails-address").val());
           if(!add){
                showError("Enter Home Address");
                return false;
           }
           $("#employeefamilydetails-address").val(add);
           $("#employeefamilydetails-p_address").val(add);
       }else{
           $("#employeefamilydetails-p_address").val('');
       }
    });
    
    $("#hrgeneralforms-entry_date").datepicker
    ({
        autoclose:true,
        format: "dd-mm-yyyy",
        orientation: "top",
        endDate: '0d',
    });
    
    $('#hrgeneralforms-reason').on('change', function() 
    {
        var thisvalue = $(this).find("option:selected").text();
        if(thisvalue=="Other")
        {
            $(".othrresn").show();
        }
        else
        {
            $(".othrresn").hide();
            $("#otherreason").val('');
        }
    });
    
    $('#hrtourrequisition-start_date').datepicker
    ({
        autoclose:true,
        format: "dd-mm-yyyy",
        orientation: "top-left",
    }).on('changeDate', function (selected) 
    {
        hideError();
        var minDate = new Date(selected.date.valueOf());
        $('#hrtourrequisition-end_date').datepicker('setStartDate', minDate);
        $("#hrtourrequisition-end_date").val('');
    });

    $("#hrtourrequisition-end_date").datepicker
    ({
        autoclose:true,
        format: "dd-mm-yyyy",
        orientation: "top",
    }).on('changeDate', function(ev){
    {
        var fromdateval= $("#hrtourrequisition-start_date").val();
        if(fromdateval=='')
        {
            $("#hrtourrequisition-end_date").val('');
            showError("Please enter start date first");
            return false;
        }
    }
    });
   
   $('#submit_tour_header').click(function(){
        hideError();
        var tour_location = $("#hrtourrequisition-tour_location").val();
        if(!tour_location){
            showError("Select Location");
            return false;
        }
        var start_hh = $("#start_hh").val();
        if(!start_hh){
            showError("Select Hours of Start Date");
            return false;
        }
        var start_mm = $("#start_mm").val();
        if(!start_mm){
            showError("Select Minutes of Start Date");
            return false;
        }
        var end_hh = $("#end_hh").val();
        if(!end_hh){
            showError("Select Hours of End Date");
            return false;
        }
        var end_mi = $("#end_mi").val();
        if(!end_mi){
            showError("Select Minutes of End Date");
            return false;
        }
        
        var start_date = $("#hrtourrequisition-start_date").val();
        var end_date = $("#hrtourrequisition-end_date").val();
        if(start_date == end_date){
            if(start_hh > end_hh){
                showError("Invalid End date Hours compare to start date.");
                return false;
            }
        }
                
//        var project_id = $("#hrtourrequisition-project_id").val();
//        if(!project_id){
//            showError("Select Project Name");
//            return false;
//        }
//        var tour_type = $("#hrtourrequisition-tour_type").val();
//        if(!tour_type){
//            showError("Select Tour Type");
//            return false;
//        }
        var purpose = $.trim($("#hrtourrequisition-purpose").val());
        $("#hrtourrequisition-purpose").val(purpose);
        if(!purpose){
            showError("Enter Purpose");
            return false;
        }
      $(this).submit();
   });
    
    $(".deletehalt").click(function(){
        if(!confirm("Are you sure want to delete this detail?")){
            return false;
        }
        return true;
    });
    $("#c_start_date_hh, #c_start_date_mm").change(function(){
        var c_start_date = $("#c_start_date").val();
        if(!c_start_date){
            $(this).prop('selectedIndex',0);
            showError("Select Conveyance Start Date ");
            return false;
        }
    });
    $("#c_end_date_hh, #c_end_date_mm").change(function(){
        hideError();
        var c_start_date = $("#c_start_date").val();
        if(!c_start_date){
            $(this).prop('selectedIndex',0);
            showError("Select Conveyance Start Date ");
            return false;
        }
        
        var c_end_date = $("#c_end_date").val();
        if(!c_end_date){
            $(this).prop('selectedIndex',0);
            showError("Select Conveyance End Date ");
            return false;
        }
        
        if(c_start_date == c_end_date){
            var c_start_date_hh = parseInt($("#c_start_date_hh").val());
            var c_end_date_hh = parseInt($("#c_end_date_hh").val());
            if(c_end_date_hh < c_start_date_hh){
                $(this).prop('selectedIndex',0);
                showError("End hours time less cannot less then start time.")
                return false;
            }
        }
    });
    
    $("#j_end_date_hh, #j_end_date_mm").change(function(){
        hideError();
        var c_start_date = $("#j_start_date").val();
        if(!c_start_date){
            $(this).prop('selectedIndex',0);
            showError("Select Journey Start Date ");
            return false;
        }
        
        var c_end_date = $("#j_end_date").val();
        if(!c_end_date){
            $(this).prop('selectedIndex',0);
            showError("Select Journey End Date ");
            return false;
        }
        
        if(c_start_date == c_end_date){
            var c_start_date_hh = parseInt($("#j_start_date_hh").val());
            var c_end_date_hh = parseInt($("#j_end_date_hh").val());
            if(c_end_date_hh < c_start_date_hh){
                $(this).prop('selectedIndex',0);
                showError("End hours time less cannot less then start time.")
                return false;
            }
            if(c_start_date_hh == c_end_date_hh){
                var j_end_date_mm = $("#j_end_date_mm").val();
                if(j_end_date_mm){
                    var j_start_date_mm = $("#j_start_date_mm").val();
                    if(j_end_date_mm < j_start_date_mm){
                        $(this).prop('selectedIndex',0);
                        showError("End minutes time less cannot less then start time.")
                        return false;
                    }
                }
            }
            
        }
    });
    
    $("#entrymintus").change(function(){
        var entry_time = parseInt($("#hrgeneralforms-entry_time").val());
        var entrymintus = parseInt($("#entrymintus").val());
        var exit_time = parseInt($("#hrgeneralforms-exit_time").val());
        var exitmintus = parseInt($("#exitmintus").val());

        if(entry_time && entrymintus && exit_time && exitmintus){
            if(entry_time == exit_time){
                if(exitmintus < entrymintus){
                    $(this).prop('selectedIndex',0);
                    showError("Invalid Exit Time Minutes");
                    return false;
                }
            }else{
                if(exit_time < entry_time){
                    $(this).prop('selectedIndex',0);
                    showError("Invalid Exit Time Hours");
                    return false;
                }
            }
        }
    });
    
    $("#entrymintus").change(function(){
        hideError();
        var entry_time = $("#hrgeneralforms-entry_time").val();
        if(!entry_time){
            $(this).prop('selectedIndex',0);
            showError("Select Entry Time Hours");
            return false;
        }
        var entry_time = parseInt($("#hrgeneralforms-entry_time").val());
        var entrymintus = parseInt($("#entrymintus").val());
        var exit_time = parseInt($("#hrgeneralforms-exit_time").val());
        var exitmintus = parseInt($("#exitmintus").val());

        if(entry_time && entrymintus && exit_time && exitmintus){
            if(entry_time == exit_time){
                if(exitmintus < entrymintus){
                    $(this).prop('selectedIndex',0);
                    showError("Invalid Exit Time Minutes");
                    return false;
                }
            }else{
                if(exit_time < entry_time){
                    $(this).prop('selectedIndex',0);
                    showError("Invalid Exit Time Hours");
                    return false;
                }
            }
        }
    });
    $("#exit_time").change(function(){
        hideError();
        var entry_time = $("#hrgeneralforms-entry_time").val();
        if(!entry_time){
            $(this).prop('selectedIndex',0);
            showError("Select Entry Time Hours");
            return false;
        }
        var entrymintus = $("#entrymintus").val();
        if(!entrymintus){
            $(this).prop('selectedIndex',0);
            showError("Select Entry Time Minutes");
            return false;
        }
        var entry_time = parseInt($("#hrgeneralforms-entry_time").val());
        var entrymintus = parseInt($("#entrymintus").val());
        var exit_time = parseInt($("#hrgeneralforms-exit_time").val());
        var exitmintus = parseInt($("#exitmintus").val());

        if(entry_time && entrymintus && exit_time && exitmintus){
            if(entry_time == exit_time){
                if(exitmintus < entrymintus){
                    $(this).prop('selectedIndex',0);
                    showError("Invalid Exit Time Minutes");
                    return false;
                }
            }else{
                if(exit_time < entry_time){
                    $(this).prop('selectedIndex',0);
                    showError("Invalid Exit Time Hours");
                    return false;
                }
            }
        }
    });
    $("#exitmintus").change(function(){
        hideError();
        var entry_time = $("#hrgeneralforms-entry_time").val();
        if(!entry_time){
            $(this).prop('selectedIndex',0);
            showError("Select Entry Time Hours");
            return false;
        }
        var entrymintus = $("#entrymintus").val();
        if(!entrymintus){
            $(this).prop('selectedIndex',0);
            showError("Select Entry Time Minutes");
            return false;
        }
        var exit_time = $("#hrgeneralforms-exit_time").val();
        if(!exit_time){
            $(this).prop('selectedIndex',0);
            showError("Select Exit Time Hours");
            return false;
        }
        var entry_time = parseInt($("#hrgeneralforms-entry_time").val());
        var entrymintus = parseInt($("#entrymintus").val());
        var exit_time = parseInt($("#hrgeneralforms-exit_time").val());
        var exitmintus = parseInt($("#exitmintus").val());

        if(entry_time && entrymintus && exit_time && exitmintus){
            if(entry_time == exit_time){
//                alert("exitmintus "+exitmintus);
//                alert("entrymintus "+entrymintus);
                if(exitmintus < entrymintus){
//                    alert("INNNNN");
                    $(this).prop('selectedIndex',0);
                    showError("Invalid Exit Time Minutes");
                    return false;
                }
            }else{
                if(exit_time < entry_time){
                    $(this).prop('selectedIndex',0);
                    showError("Invalid Exit Time Hours");
                    return false;
                }
            }
        }
    });
    
    $("#submit-eduform").click(function(){
        hideError();
        var ischeck = 0;
        $("#childdetail").find('input[type=checkbox]:checked').each(function(){
            ischeck = parseInt(ischeck)+parseInt('1');
        });
        if(ischeck == 0){
            showError("Select Checkbox");
            return false;
        }
        var formcount = 0;
        $("#childdetail").find('input[type=checkbox]:checked').each(function(){
            var id = $(this).attr('data-key');
            var std = $("#std-"+id).val();
            if(!std){
                showError("Select Std.");
                return false;
            }
            
            var school = $.trim($("#school-"+id).val());
            $("#school-"+id).val(school);
            if(!school){
                showError("Enter School Name.");
                return false;
            }
            var ay_start = $("#ay_start-"+id).val();
            if(!ay_start){
                showError("Enter AY Start Date.");
                return false;
            }
            
            var ay_end = $("#ay_end-"+id).val();
            if(!ay_end){
                showError("Enter AY End Date.");
                return false;
            }
            if(std && school && ay_start && ay_end){
                formcount = parseInt(formcount)+parseInt('1');
            }
        });
        if(formcount == ischeck){
            $('#ceform').submit();
        }
    });
    $("#ceaFnYr").change(function(){
        var menuid = $("#menuid").val();
        var ceaFnYr = $(this).val();
        var url = BASEURL+"employee/reimbursement/ceas?securekey="+menuid+"&ceaFnYr="+ceaFnYr;
        window.location.replace(url);
        return false;
    });
    $('.applyclaim').click(function(){
        hideError();
        var menuid = $("#menuid").val();
        var id = $(this).attr('data-key');
        var claim_type_ = $('#claim_type_'+id).val();
        if(!claim_type_){
            showError("Select Claim Type");
            return false;
        }
        var ea_id = $('#claim_type_'+id).attr('data-id');
        var fy = $('#claim_type_'+id).attr('data-fy');
        var url = BASEURL+"employee/reimbursement/claimcea?securekey="+menuid+"&ea_id="+ea_id+"&fy="+fy+"&ct="+claim_type_;
        window.location.replace(url);
        return false;
    });
    
    $('.viewceaapp').click(function(){
        var id = $(this).attr('data-key');
        var ea_id = $('#ea_id-'+id).val();
        var fy = $('#fy-'+id).val();
        var menuid = $('#menuid').val();

        $("#apphtml").html('');
        var url = BASEURL+"employee/reimbursement/viewceaapp?securekey="+menuid+"&ea_id="+ea_id+"&fy="+fy;
        $.ajax({
            type: "GET",
            url: url,
            success: function(data){
//                alert(data);
                if(data){
                    var ht = $.parseJSON(data);
                    var status = ht.Status;
                    var res = ht.Res;
//                    alert(status);
//                    alert(res);
                    if(status == 'SS'){
                        $("#apphtml").html(res);
                        $("#mviewceaapp").modal();
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
    
    $('.checktapptype').click(function(){
        var id = $(this).attr('data-type');
        if(id == 'E'){
            if(confirm("You are about to discard all leave entries from this leave application. Are you sure?")){
                return true;
            }
        }else if(id == 'A'){
            if(confirm("You are about to delete entire leave application including application details & leave entries. Are you sure?")){
                return true;
            }
        }else if(id == 'S'){
            if(confirm("Are you sure to send leave application for approval?")){
                return true;
            }
        }
        return false;
    });
    
    $('.hrviewleavedetails').click(function(){
        $("#modal_contentdata").html('');
        var leaveappid = $(this).attr('data-key');
        var ec = $(this).attr('data-key1');
        var menuid = $('#menuid').val();
        if(leaveappid && menuid){
            var url = BASEURL+"hr/viewapprovedleave/viewleaverequests?securekey="+menuid+"&key="+leaveappid+"&key1="+ec;
            $.ajax({
                type: "GET",
                url: url,
                success: function(data){
                    if(data){
                        var ht = $.parseJSON(data);
                        var status = ht.Status;
                        var res = ht.Res;
                        if(status == 'SS'){
                            $("#modal_contentdata").html(res);
                            $("#leavedata").modal();
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
    });
    
    $('.viewleavedetails').click(function(){
        $("#modal_contentdata").html('');
        var leaveappid = $(this).attr('data-key');
        var menuid = $('#menuid').val();
        if(leaveappid && menuid){
            var url = BASEURL+"employee/leave/viewleaverequests?securekey="+menuid+"&key="+leaveappid;
            $.ajax({
                type: "GET",
                url: url,
                success: function(data){
                    if(data){
                        var ht = $.parseJSON(data);
                        var status = ht.Status;
                        var res = ht.Res;
                        if(status == 'SS'){
                            $("#modal_contentdata").html(res);
                            $("#leavedata").modal();
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
    });
    
    $('#attendence_date').css('cursor','pointer');
    $('#attendence_date').datepicker({
        autoclose:true,
        format: "dd-mm-yyyy",
        endDate: '0d',
        daysOfWeekDisabled: [0,7]
    }).on('changeDate', function(ev){
        $("#emp_atte_info_btn, #emp_atte_info").html('');
        var menuid = $('#menuid').val();
        var attendate = $('#attendence_date').val();
        var url = BASEURL+"hr/markattendance/getattendence?securekey="+menuid;
        $.ajax({
            type: "POST",
            url: url,
            data:{
                attendate:attendate
            },
            success: function(data){
                if(data){
                    var ht = $.parseJSON(data);
                    var status = ht.Status;
                    var res = ht.Res;
                    var Res_btn = ht.Res_btn;
                    if(status == 'SS'){
                        $("#emp_atte_info").html(res);
                        $("#emp_atte_info_btn").html(Res_btn);
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
});

function qualifmodal(id){
    $("#acdamik, #other_qu").hide();
    var fullname_ = $('#fullname_'+id).val();
    var desg_name_ = $('#desg_name_'+id).val();
    var dept_name_ = $('#dept_name_'+id).val();
    var employee_code_ = $('#employee_code_'+id).val();
    var quali_type_ = $('#quali_type_'+id).val();
    var qualilfi_ = $('#qualilfi_'+id).val();
    var other_quali_ = $('#other_quali_'+id).val();
    var discipline_ = $('#discipline_'+id).val();
    var Institute_ = $('#Institute_'+id).val();
    var univ_board_ = $('#univ_board_'+id).val();
    var address_ = $('#address_'+id).val();
    var passed_on_ = $('#passed_on_'+id).val();
    var grade_ = $('#grade_'+id).val();
    var percentage_ = $('#percentage_'+id).val();
    var CGPA_ = $('#CGPA_'+id).val();
    var doc_type_ = $('#doc_type_'+id).val();
    var doc_ = $('#doc_'+id).val();
    var status_ = $('#status_'+id).val();
    var submiton_ = $('#submiton_'+id).val();
    
    $("#ec, #en, #dpt,#qua,#otherqua,#dis,#ins,#uni,#add,#pass,#grade,#per,#cgpa,#subon,#sts,#doc_type,#doc").html('');
    $('#ec').html(employee_code_);
    $('#en').html(fullname_+", "+desg_name_);    
    $('#dpt').html(dept_name_);
    $("#qua").html(qualilfi_);
    $("#otherqua").html(other_quali_);
    $("#dis").html(discipline_);
    $("#ins").html(Institute_);
    $("#uni").html(univ_board_);
    $("#add").html(address_);
    $("#pass, #other_pass").html(passed_on_);
    $("#grade, #other_grade").html(grade_);
    $("#per, #other_per").html(percentage_);
    $("#cgpa, #other_cgpa").html(CGPA_);
    $("#subon, #other_subon").html(submiton_);
    $("#sts, #other_sts").html(status_);
    $("#doc_type, #other_doc_type").html(doc_type_);
    $("#doc, #other_doc").html(doc_);
    
    if(quali_type_ == 'A'){
        $("#acdamik").show();
    }else if(quali_type_ == 'O'){
        $("#other_qu").show();
    }
    $('#qualifmodal').modal();
}

function viewLeaveCard(id){
    
    var fromto = $("#fromto_"+id).html();
    var applied_date_ = $("#applied_date_"+id).html();
    var leaves_ = $("#leaves_"+id).html();
    var fullname_ = $("#fullname_"+id).html();
    var e_id_ = $("#e_id_"+id).val();
    var dept_name_ = $("#dept_name_"+id).val();
    var totaldays = $("#totaldays_"+id).val();
    var leave_reason = $("#leave_reason_"+id).val();
    
    var htm = "<tr><td colspan='2'><b>Applied Date : </b>"+applied_date_+"</td></tr><tr><td><b>Name : </b>"+fullname_+"</td><td><b>Department : </b>"+dept_name_+"</td></tr>";
    htm = htm+"<tr><td colspan='2'><b>Leave From : </b>"+fromto+"</td></tr>";
    htm = htm+"<tr><td colspan='2'><b>Reason : </b>"+leave_reason+"</td></tr>";
    htm = htm+"<tr><td colspan='2'><b>Total Days : </b>"+totaldays+" Day(s)</td></tr>";
    $("#leavedata_tr").html(htm);
    $("#leavedata").modal();
}

function changeCalender(cType){
    if(cType == 'C'){
        $(".attndnc-horizontal, .attndnc-vertical").hide();
        $(".attndnc-calender").show();
        $("#view-calender").addClass("btn-success");
        $("#view-horizontal, #view-vertical").removeClass("btn-success");
        $("#view-horizontal, #view-vertical").addClass("btn-secondary");
    }else if(cType == 'H'){
        $(".attndnc-calender, .attndnc-vertical").hide();
        $(".attndnc-horizontal").show();
        $("#view-horizontal").addClass("btn-success");
        $("#view-calender, #view-vertical").removeClass("btn-success");
        $("#view-calender, #view-vertical").addClass("btn-secondary");
    }else if(cType == 'V'){
        $(".attndnc-calender, .attndnc-horizontal").hide();
        $(".attndnc-vertical").show();
        $("#view-vertical").addClass("btn-success");
        $("#view-calender, #view-horizontal").removeClass("btn-success");
        $("#view-calender, #view-horizontal").addClass("btn-secondary");
    }
}