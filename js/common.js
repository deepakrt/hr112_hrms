$(document).ready(function() {
    $(".pdf_file").on("change", function(){
        hideError();
        var ext = this.files[0].type;
        var file_size = this.files[0].size;
        var chkext = true;
        if(ext == "application/pdf"){ 
        }else if(ext == "data:binary/octet-stream"){
        }else if(ext == "data:application/x-download"){
        }else{
            chkext = false;
        }
        if(!chkext){
            $(this).val('');
            showError("Only .pdf Allowed");
            return false;
        }
        var pdf_magic = "0x25504446";
        var pdf_magic1 = "626017350";
        
        var slice = this.files[0].slice(0,4);     
        var reader = new FileReader();    
        reader.readAsArrayBuffer(slice);
        var oFile = document.getElementById('docs_path').files[0];
        reader.onload = function(e)
        {
            
            var buffer = reader.result;  
            var view = new DataView(buffer);    
            var magic = view.getUint32(0, false); 
            if((magic == pdf_magic) || (magic == pdf_magic1 )){
                var maxfileSize = parseInt(PDF_File_Size)*parseInt("1024");
                file_size = file_size / 1024;
                file_size =  file_size.toFixed(0);
                if(file_size > maxfileSize){
                    $(this).val('');
                    showError("File size should be less than "+PDF_File_Size+"MB");
                    return false;
                }
           }else{
                $(this).val('');
                var error = 'File type / content not supported... !';
                showError(error);
                return false; 
           }
        }
        reader.readAsDataURL(oFile);
    });
     //$('#dataTableShow').DataTable();
	$('#dataTableShow').DataTable( {
        "order": [[ 1, "desc" ]]
    } );
	$('#dataTableShow1').DataTable({
        "order": [[ 0, "desc" ]]
    });
	
	 $('#dataTableShowP').DataTable({
        dom: 'Blfrtip',
		 buttons: [
            {
                extend: 'print',
                autoPrint: true,
                text: 'Print',
                exportOptions: {
                  rows: function ( idx, data, node ) {
                    var dt = new $.fn.dataTable.Api('#dataTableShowP' );
                    var selected = dt.rows( { selected: true } ).indexes().toArray();
                   
                    if( selected.length === 0 || $.inArray(idx, selected) !== -1)
                      return true;
              

                    return false;
                }
              }
            }
        ],
        select: true
    });
	
    $('#employee-dob').css('cursor','pointer');
    $('#employee-dob').datepicker({
        autoclose:true,
        format: "dd-mm-yyyy",
        endDate: '-15y',
    }).click(function(){
        $('.datepicker-days').css('display','block');
    });
	$('.date_picker_tilltoday').datepicker({
        autoclose:true,
        format: "dd-mm-yyyy",endDate: '0',
    }).click(function(){
        $('.datepicker-days').css('display','block');
    });
	$('.date_picker').datepicker({
        autoclose:true,
        format: "dd-mm-yyyy",
    }).click(function(){
        $('.datepicker-days').css('display','block');
    });
    $('#effected_date').css('cursor','pointer');
    $('#effected_date').datepicker({
        autoclose:true,
        format: "dd-mm-yyyy",
        endDate: '0',
    }).click(function(){
        $('.datepicker-days').css('display','block');
    });
    $('#employee-joining_date').css('cursor','pointer');
    $('#employee-joining_date').datepicker({
        autoclose:true,
        format: "dd-mm-yyyy",
        endDate: '0d',
    }).click(function(){
        $('.datepicker-days').css('display','block');
    });
    $(".PhotoSign").on("change", function(){
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
               $("#"+id).val('');
                var error = 'File type / content not supported... !';
                showError(error);
                return false; 
           }
        };
    });
    $("#search_menu").click(function(){
        hideError();
        var search_role = $("#search_role").val();
        var menu_type = $("#menu_type").val();
        var empcode = $.trim($("#empcode").val());
        $("#empcode").val(empcode);
        if(!search_role){
            showError("Select Role");
            return false;
        }
        if(!menu_type){
            showError("Select Menu Type");
            return false;
        }
        $("#menusearch").submit();
    });
    
    $("#top_menuid").change(function(){
        hideError();
        var selectedmenuid = $(this).val();
        var role_id = $("#assign_role").val();
        if(!role_id){
            $(this).prop('selectedIndex',0);
            showError("Select Role");
            return false;
        }
        
        $("#assignsubmitbtn").html('');
        $("#menulist").html('');
        $("#showmenulist").hide();
        if(selectedmenuid && role_id){
            var _csrf = $('#_csrf').val();
            var menuid = $("#menuid").val();
            var url = BASEURL+"admin/menus/getsubmenulist?securekey="+menuid+"&key="+selectedmenuid+"&key2="+role_id;
            $.ajax({
                type: "GET",
                url: url,
                success: function(data){
//  =                  alert(data); return false;
                    if(data){
                        var ht = $.parseJSON(data);
                        var status = ht.Status;
                        var res = ht.Res;
                        if(status == 'SS'){
                            $("#assignsubmitbtn").html('<br><button type="submit" class="btn btn-success btn-sm" >Submit</button><a href="" class="btn btn-danger btn-sm">Cancel</a>');
                            $("#menulist").html(res);
                            $("#showmenulist").show();
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
    $("#assign_role").change(function(){
        hideError();
        $("#showmenulist, #main_menu_view").hide();
        $("#menulist, #assign_main_list").html('');
        $("#top_menuid").prop('selectedIndex',0);
    });
    
//    $("#assign_menu_type").change(function(){
//        hideError();
//        $("#showmenulist, #main_menu_view").hide();
//        $("#menulist, #assign_main_list").html('');
//        
//        var selectedmenuid = $(this).val();
//        var role_id = $("#assign_role").val();
//        if(!role_id){
//            $(this).prop('selectedIndex',0);
//            showError("Select Role");
//            return false;
//        }
//        if(selectedmenuid && role_id){
//            var _csrf = $('#_csrf').val();
//            var menuid = $("#menuid").val();
//            var url = BASEURL+"admin/menus/checkmenutype?securekey="+menuid+"&key="+selectedmenuid+"&key2="+role_id;
//            $.ajax({
//                type: "GET",
//                url: url,
//                success: function(data){
//                    if(data){
//                        var ht = $.parseJSON(data);
//                        var status = ht.Status;
//                        var res = ht.Res;
//                        var MenuType = ht.MenuType;
//                        if(status == 'SS'){
//                            if(MenuType == 'T'){
//                                $("#menulist").html(res);
//                                $("#showmenulist").show();
//                                $("#cur_menu_type").val("T");
//                                return false;
//                            }else if(MenuType == 'L'){
//                                $("#cur_menu_type").val("L");
//                                $("#assign_main_list").html(res);
//                                $("#main_menu_view").show();
//                                return false;
//                            }
//                        }else{
//                            showError(res); 
//                            return false;
//                        }
//                    }else{
//                        return false;
//                    }
//                }
//            });
//        }  
//    });
    
//    $("#assign_menu").change(function(){
//        var selectedmenuid = $(this).val();
//        if(selectedmenuid){
//            var _csrf = $('#_csrf').val();
//            var menuid = $("#menuid").val();
//            var url = BASEURL+"admin/menus/getsubmenu?securekey="+menuid+"&key="+selectedmenuid;
//            $.ajax({
//                type: "GET",
//                url: url,
//                success: function(data){
////                    alert(data);
//                    if(data){
//                        var ht = $.parseJSON(data);
//                        var status = ht.Status;
//                        var res = ht.Res;
//                        if(status == 'SS'){
//                            $('#assign_submenu').html(res);
//                        }else{
//                            showError(res); 
//                            return false;
//                        }
//                    }else{
//                        return false;
//                    }
//                }
//            });
//        }        
//    });
    
    $('.authemp').change(function(){
        hideError();
        var deptid = $(this).val();
        if(deptid){
            $('#employee-authority1').html('<option value="">Select Reporting Authority</option>');
            $('#employee-authority2').html('<option value="">Select Head of Department</option>');
            var _csrf = $('#_csrf').val();
            var menuid = $("#menuid").val();
            var deptid = $(this).val();
            var url = BASEURL+"admin/manageemployees/getdeptemp?securekey="+menuid+"&deptid="+deptid;
            $.ajax({
                type: "GET",
                url: url,
                success: function(data){
                    if(data){
                        var ht = $.parseJSON(data);
                        var status = ht.Status;
                        var res = ht.Res;
                        if(status == 'SS'){
                            $('#employee-authority1, #employee-authority2').append(res);
                        }else{
                            $(this).prop('selectedIndex',0);
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
    
    $('body').on('click','.viewleavedetail',function(){
        var idd = $(this).data('srno');
        var code = $('#code_'+idd).html();
        var empcode = $('#empcode_'+idd).val();
        var fullname = $('#fullname_'+idd).html();
        var desg_name = $('#desg_name_'+idd).html();
        $('#employee_code').html(code);
        $('#employee_name').html(fullname);
        $('#designation').html(desg_name);
         var _csrf = $('#_csrf').val();
            var menuid = $("#menuid").val();
            var deptid = $(this).val();
            var url = BASEURL+"admin/manageleaves/getleavedetail?securekey="+menuid+"&code="+empcode;
            $.ajax({
                type: "GET",
                url: url,
                success: function(data){
                    $('#leaveinfo').html('');
                    if(data){
                        var ht = $.parseJSON(data);
                        var status = ht.Status;
                        var res = ht.Res;
                        if(status == 'SS'){
                            $('#leaveinfo').html(res);
                            $("#viewleavedetails").modal();
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


    $('body').on('click','.add_more_leave',function(){ 
        var idd = $(this).data('srno');
  
        //var code = $('#session_year_'+idd).html();
       
   var code = $('#leave_id_'+idd).val();
   
       // var fullname = $('#fullname_'+idd).html();
       // var desg_name = $('#desg_name_'+idd).html();
      //  $('#employee_code').html(code);
      //  $('#employee_name').html(fullname);
      //  $('#designation').html(desg_name);
         var _csrf = $('#_csrf').val();
            var menuid = $("#menuid").val();
            //var deptid = $(this).val();
            var url = BASEURL+"admin/manageleaves/addmoreleaves?securekey="+menuid+"&code="+code;
            $.ajax({
                type: "GET",
                url: url,
                success: function(data){
                    $('#leaveinfo').html('');
                    if(data){
                        var ht = $.parseJSON(data);
                        var status = ht.Status;
                        var res = ht.Res;
                        if(status == 'SS'){
                            $('#leaveinfo').html(res);
                            $("#viewleavedetails").modal();
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
    
    $('#updateservicedee').click(function(){
        $('#updateservice').show();
    });
    $('#submit_password').click(function(){
        hideError();
        var current_password = $.trim($('#current_password').val());
        $('#current_password').val(current_password);
        var new_password = $.trim($('#new_password').val());
        $('#new_password').val(new_password);
        var confirm_password = $.trim($('#confirm_password').val());
        $('#confirm_password').val(confirm_password);
        
        if(!current_password){
            showError("Enter Current Password");
            return false;
        }
        if(!new_password){
            showError("Enter New Password");
            return false;
        }
        if(!confirm_password){
            showError("Enter Confirm Password");
            return false;
        }
        if(new_password != confirm_password){
            showError("Enter Confirm Password not matched");
            return false;
        }
        $('#changepassword').submit();
    });
    
    $("#submit_newmenu").click(function(){
//        hideError();
//        var assign_role = $("#assign_role").val();
//        var assign_menu = $("#assign_menu").val();
//        var assign_emp_code = $.trim($("#assign_emp_code").val());
//        $("#assign_emp_code").val(assign_emp_code);
//        
//        if(!assign_role){
//            showError("Select Role");
//            return false;
//        }
//        if(!assign_menu){
//            showError("Select Menu");
//            return false;
//        }
        
        $("#menusearch").submit();
    });
    
    $("#savemenu").click(function(){
        hideError();
        var menu_name = $.trim($("#menu_name").val());
        $("#menu_name").val(menu_name);
        if(!menu_name){
            showError("Enter Menu Name");
            return false;
        }
        var menu_dsc = $.trim($("#menu_dsc").val());
        $("#menu_dsc").val(menu_dsc);
        
        var menu_url = $.trim($("#menu_url").val());
        $("#menu_url").val(menu_url);
        if(!menu_dsc){
            showError("Enter Menu Url");
            return false;
        }
        var menu_type = $.trim($("#menu_type").val());        
        if(!menu_type){
            showError("Enter Menu Type");
            return false;
        }
        if(menu_type == 'L'){
            var parent_menu = $("#parent_menu").val();
            if(!parent_menu){
                showError("Select Parent Menu");
                return false;
            }
        }
        var order_number = $.trim($("#order_number").val());
        $("#order_number").val(order_number);
        
        $("#addmenu").submit();
    });
    
    $('#menu_type').change(function(){
        if($(this).val() == 'L'){
            $("#parent_menu").removeAttr('disabled');
        }else{
            $('#parent_menu').prop('selectedIndex', 0);
            $("#parent_menu").attr('disabled',true);
        }
    });
    var previous;
    $('.switchrole').on('focus', function () {
           previous = $(this).val();
		    
    }).change(function(){
        var name = $(this).find(":selected").text();
        var val = $(this).find(":selected").val();
		 if(val==''){return false;}
		 
		swal({
				  title: "Swtich Role as "+name,
				  text: '',
				  icon: BASEURL+'images/logo.png',
				  buttons: [
					'No, cancel it!',
					'Yes, I am sure!'
				  ],
				  dangerMode: false,
				}).then(function(isConfirm) {
			    if (isConfirm) {
 						$("#roleform").submit();
				}else{
 					$('.switchrole').val('');
				}
				
         });
        return false;
    });
    
    $("#assign_leave_year").change(function(){
        hideError();
        $(".assignemptype, #sessiontype").prop('selectedIndex',0);
        $(".leavetype").html("<option value=''>Select Leave Type</option>");
        $("#leave_count, #leave_count_enc, #carry_fwd, #carry_fwd_enc, #can_encash, #can_encash_enc").val("");
    });
    $("#sessiontype").change(function(){
        hideError();
        $(".assignemptype").prop('selectedIndex',0);
        $(".leavetype").html("<option value=''>Select Leave Type</option>");
        $("#leave_count, #leave_count_enc, #carry_fwd, #carry_fwd_enc, #can_encash, #can_encash_enc").val("");
    });
    $(".assignemptype").change(function(){
        hideError();
        $(".leavetype").html("<option value=''>Select Leave Type</option>");
       $("#leave_count, #leave_count_enc, #carry_fwd, #carry_fwd_enc, #can_encash, #can_encash_enc").val("");
        var assign_leave_year = $("#assign_leave_year").val();
        if(!assign_leave_year){
            showError("Select Session Year");
            return false;
        }
        var sessiontype = $("#sessiontype").val();
        if(!sessiontype){
            showError("Select Session Type");
            return false;
        }
        var vall = $(this).val();
        if(vall == 'R'){
        }else if(vall == 'C'){
        }else{
            showError("Invalid Employee Type");
            return false;
        }
        
        var menuid = $("#menuid").val();
        var url = BASEURL+"admin/manageleaves/gethrleavechart?securekey="+menuid+"&emptype="+vall+"&year="+assign_leave_year+"&sessiontype="+sessiontype;
        $.ajax({
            type: "GET",
            url: url,
            success: function(data){
//                alert(data);
                $('.leavetype').html('');
                if(data){
                    var ht = $.parseJSON(data);
                    var status = ht.Status;
                    var res = ht.leaveType;
//                    alert(status);
//                    alert(res);
                    if(status == 'SS'){
                        $('.leavetype').html(res);
                        $("#leave_count, #leave_count_enc, #carry_fwd, #carry_fwd_enc, #can_encash, #can_encash_enc").val("");
                    }else{
                        showError(ht.Msg); 
                        return false;
                    }
                }else{
                    return false;
                }
            }
        });
    });
    
    $(".leavetype").change(function(){
        var vall = $(this).val();
        if(vall){
            var assign_leave_year = $("#assign_leave_year").val();
            var assignemptype = $("#assignemptype").val();
            var sessiontype = $("#sessiontype").val();
            
            if(assignemptype == 'R'){
            }else if(assignemptype == 'C'){
            }else{
                showError("Invalid Employee Type");
                return false;
            }
            var menuid = $("#menuid").val();
            var url = BASEURL+"admin/manageleaves/getleavechartdetailsbyid?securekey="+menuid+"&id="+vall+"&assign_leave_year="+assign_leave_year+"&assignemptype="+assignemptype+"&sessiontype="+sessiontype;
            $.ajax({
                type: "GET",
                url: url,
                success: function(data){
                   if(data){
                        var ht = $.parseJSON(data);               
//                        alert(ht.Status);
                        if(ht.Status == "SS"){
                            $("#leave_for").val(ht.leave_for);
                            $("#leave_for_enc").val(ht.leave_for_enc);
                            $("#leave_count").val(ht.leave_count);
                            $("#leave_count_enc").val(ht.leave_count_enc);
                            $("#year").val(ht.year);
                            $("#year_enc").val(ht.year_enc);
                            $("#session_type").val(ht.session_type);
                            $("#session_type_enc").val(ht.session_type_enc);
                            $("#carry_fwd").val(ht.carry_fwd);
                            $("#carry_fwd_enc").val(ht.carry_fwd_enc);
                            $("#can_encash").val(ht.can_encash);
                            $("#can_encash_enc").val(ht.can_encash_enc);
                            $("#leave_chart_id").val(ht.leave_chart_id);
//                            alert(ht.carry_fwd);
//                            alert(ht.can_encash);
                            return false;
                        }else{
//                            alert("IN");
                            showError(ht.Res);
                            return false;
                        }
                    }else{
                        return false;
                    }
                }
            });
        }else{
            $("#leave_chart_id, #can_encash_enc, #can_encash, #carry_fwd_enc, #carry_fwd, #session_type_enc, #session_type, #year_enc, #year, #leave_for, #leave_for_enc, #leave_count, #leave_count_enc").val('');
            
        }
    });
    
    $(".assignleaveto").change(function(){
        $("#emp_code").val("");
        $("#entremp").hide();
        $("#assignleaveto").val("1");
        if($(this).val() == '2'){
           $("#entremp").show(); 
           $("#assignleaveto").val("2");
        }
    });
    
//    $("#submitassignleave").click(function(){
//        hideError();
////        var leave_count_enc = $("#leave_count_enc").val();
////        if(!leave_count_enc){
////            showError("Invalid Total Leaves");
////            return false;
////        }
////        var year_enc = $("#year_enc").val();
////        if(!year_enc){
////            showError("Invalid Year");
////            return false;
////        }
////        var session_type_enc = $("#session_type_enc").val();
////        if(!session_type_enc){
////            showError("Invalid Session Type");
////            return false;
////        }
////        var assignleaveto =  $("#assignleaveto").val();
////        if(assignleaveto == '2'){
////            var emp_code = $.trim($("#emp_code").val());
////            $("#emp_code").val(emp_code);
////            if(!emp_code){
////                showError("Enter Employee Code");
////                return false;
////            }
////        }
//        $("#assignleaveadmin").submit();
//    });
    
    $("#assignleavetoemp").change(function(){
        hideError();
        $("#session_year, #session_year_enc, #session_type, #session_type_enc, #leave_count, #leave_count_enc, #lc_id").val("");
        var lc_id = $(this).find(':selected').attr('data-key');
        var leaveid = $(this).val();
        if(!leaveid){
            return false;
        }
        var menuid = $("#menuid").val();
        var url = BASEURL+"admin/manageleaves/assignleavetoemployee?securekey="+menuid+"&lc_id="+lc_id+"&leaveid="+leaveid;
        $.ajax({
            type: "GET",
            url: url,
            success: function(data){
               if(data){
                    var ht = $.parseJSON(data);               
//                    alert(ht.Status);
                    if(ht.Status == "SS"){
                        $("#session_year").val(ht.session_year);
                        $("#session_year_enc").val(ht.session_year_enc);
                        $("#session_type").val(ht.session_type);
                        $("#session_type_enc").val(ht.session_type_enc);
                        $("#leave_count").val(ht.leave_count);
                        $("#leave_count_enc").val(ht.leave_count_enc);
                        $("#lc_id").val(ht.lc_id);
                        return false;
                    }else{
                        showError(ht.Res);
                        return false;
                    }
                }else{
                    return false;
                }
            }
        });
    });
    
   $('.deleteallow1').click(function(){
        if(confirm("Are you sure want Delete?")){
            return true;
        }
       return false;
   });
    $('body').on('click','.updaterole',function(){
        var rid = $(this).attr('data-key');
        var role = $(this).attr('data-role');
        var desc = $(this).attr('data-desc');
        var is_active = $(this).attr('data-is_active');
        $("#is_active").html('');
        if(is_active == 'Y'){
            var htm = '<option selected="selected" value="Y">Yes</option><option value="N">No</option>';
            $("#is_active").html(htm);
        }else if(is_active == 'N'){
            var htm = '<option value="Y">Yes</option><option selected="selected"  value="N">No</option>';
            $("#is_active").html(htm);
        }
        $('#role_id').val(rid);
        $('#role_name').val(role);
        $('#desc').val(desc);
        $('html,body').animate({scrollTop:0},500);
        return false;
    });
    
    $('body').on('click','.changebtnmenustatus',function(){
        var val = $(this).val();
        var id = $(this).attr('data-id');
        var selectedstatus = $(this).attr('data-status');
        
        if(val == 'Y'){
            $('#show_'+id).removeClass('btn-light');
            $('#show_'+id).addClass('btn-success');
            $('#hide_'+id).addClass('btn-light');
            $('#hide_'+id).removeClass('btn-success');
            $('#status_'+id).val(selectedstatus);
            return false;
        }else if(val == 'N'){
            $('#show_'+id).addClass('btn-light');
            $('#show_'+id).removeClass('btn-success');
            $('#hide_'+id).removeClass('btn-light');
            $('#hide_'+id).addClass('btn-success');
            $('#status_'+id).val(selectedstatus);
            return false;
        }else{
            $('#status_'+id).val('');
            return false;
        }
    });
    
    $('.sl').click(function(){
        
    });
	
	
	$('body').on('click','#quotation_report', function (){		
             var q_id = $("#q_id123").val();
             //alert(q_id);
              var csrftoken=$("#_csrf").val();
			 $.ajax({
					url:BASE_URL+'inventory/purchase/quotationpdf?securekey='+securekey,
					type:'POST',
					data:{q_id:q_id,_csrf:csrftoken},
					datatype:'json',
					success:function(data){
					//alert(data);
					//var ht = $.parseJSON(data);
						//var status = ht.Status;
						//var res = ht.Res;
						//alert('Updated Successfully');
						//window.location.reload();
					}
				});
});
$('body').on('click','#send_detail', function (){		
			var ids = [];
			$.each($("input[name='vvalues']:checked"), function(){
			    ids.push($(this).val());
			});
            var scodes= ids.join(",");
 		 	if(scodes==''){
			 	alert('Please select any Record');
				return false;
			}
		 //alert(scodes);
             var q_id = $("#q_id123").val();
             //alert(q_id);
              var csrftoken=$("#_csrf").val();
			 $.ajax({
					url:BASE_URL+'inventory/purchase/quotation_mapping?securekey='+securekey,
					type:'POST',
					data:{q_id:q_id,scodes:scodes,_csrf:csrftoken},
					datatype:'json',
					success:function(data){
					//alert(data);
					//var ht = $.parseJSON(data);
						//var status = ht.Status;
						//var res = ht.Res;
						alert('Updated Successfully');
						window.location.reload();
					}
					});
			});
}); //end document ready

function showError(error){
    $("#display_success").hide();
    $("#display_error").show();
    $("#display_error_message").html(error);
    $('html,body').animate({scrollTop:0},500);
    return false;
}
function hideError(){
    $("#display_error").hide();
    $("#display_success").hide();
    $("#display_error_message").html("");
    return false;
}

function showModalError(error){
    $("#display_modal_error").show();
    $("#display_modal_error_message").html(error);
    return false;
}
function hideModalError(){
    $("#display_modal_error").hide();
    $("#display_modal_error_message").html("");
    return false;
}

/*
 * 
 * Allow Numbers only
 */
function allowOnlyNumber(e){
    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        return false;
    }
    return true
}

/*
 * Allow Chracter only
 */

function allowOnlyChracter(event){
    var inputValue = event.which;
    if(!(inputValue == 47) && !(inputValue >= 65 && inputValue <= 120) && event.which != 8 &&(inputValue != 32 && inputValue != 0 && inputValue != 121 && inputValue != 122)){ 
		event.preventDefault(); 
        return false;
    }
    if(inputValue == 96){
            event.preventDefault(); 
    }
    return true;
}

function showLoader(){
    $("#loading").show();
}
function hideLoader(){
    $("#loading").hide();
}
function sho_hid(){
	 $(".leftmenudiv").toggle();
	 if ($(".maincontentdiv").hasClass( "col-sm-9" ) ) {
		$(".maincontentdiv").addClass("col-sm-15");
		$(".maincontentdiv").removeClass("col-sm-9");
		$(".ex_coll").html("Collapse-View");
	 }else{
		$(".maincontentdiv").addClass("col-sm-9");
		$(".maincontentdiv").removeClass("col-sm-15");
		$(".ex_coll").html("Expand-View");
	 }

}
