$(document).ready(function (){
    
    $('#outbox').DataTable( {
        //"order": [[ 1, "desc" ]]
    } );
    
    $(".fts_image_multiple").on("change", function(e){
  
        hideError();
        $.each(this.files, function( index, value ) {
            var ext = value.type;

            var chkext = true;
            // if(ext == "image/png"){ 
//            // }else 
            if(ext == "image/jpeg"){
            }else 
            if(ext == "image/jpg"){
            }else{
                chkext = false;
            }
            if(!chkext){
                $("#fts_image_multiple").val('');
                showError("Allowed only .jpg, .jpeg only");
                return false;
            }
            var jpeg_magic = '0xFFD8FFE0';
            // var pngmagic ="0x89504E47";
            var jpgmagic2 = "0xFFD8FFE1";
            var jpgmagic3 = "1195984440";
            var slice = value.slice(0,4);     
            var reader = new FileReader();    
            reader.readAsArrayBuffer(slice);
            reader.onload = function(e)
            {
                var buffer = reader.result;        
                var view = new DataView(buffer);    
                var magic = view.getUint32(0, false); 
				// || (magic == pngmagic )
                if((magic == jpeg_magic) || (magic == jpgmagic2 ) || (magic == jpgmagic3)){
                   
                    var form_data = new FormData();
                    form_data.append('file', value);
                    showLoader();
                    var url = BASEURL+"efile/dakcommon/checkvalidimage";                
                    $.ajax({
                        type:"POST",
                        url:url,
                        contentType: false,
                        cache: false,
                        processData:false,
                        data:form_data,
                        
                        success: function(data){
                            hideLoader();
                            if(data){
                                var ht = $.parseJSON(data);
                                var status = ht.Status;
                                var res = ht.Res;
                                if(status == 'FF'){
                                    $(".fts_image_multiple").val('');
                                    showError(res); 
                                    return false;
                                }
                            }
                        }//success
                    });//ajax end

                }else{
                    $("#fts_image_multiple").val('');
                    var error = 'File type / content not supported... !';
                    showError(error);
                    return false; 
                }
            };
        });
    });
    $(".fts_pdf").on("change", function(){
        hideError();
        var id = $(this).attr('id');
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
        reader.onload = function(e)
        {
            
            var buffer = reader.result;  
            var view = new DataView(buffer);    
            var magic = view.getUint32(0, false); 
//            alert(magic);
            if((magic == pdf_magic) || (magic == pdf_magic1 )){
                var maxfileSize = parseInt(FTS_Doc_Size)*parseInt("1024");
                file_size = file_size / 1024;
                file_size =  file_size.toFixed(0);
                if(file_size > maxfileSize){
                    $(this).val('');
                    showError("File size should be less than "+FTS_Doc_Size+"MB");
                    return false;
                }
                
                var file_data = $(this).prop("files")[0];
                var form_data = new FormData();
                form_data.append('file', file_data);
                showLoader();
                var url = BASEURL+"efile/dakcommon/checkvalidpdf";                
                $.ajax({
                    type:"POST",
                    url:url,
                    contentType: false,
                    cache: false,
                    processData:false,
                    data:form_data,
                    success: function(data){
                        hideLoader();
                        if(data){
                            
                            var ht = $.parseJSON(data);
                            var status = ht.Status;
                            var res = ht.Res;
                            if(status == 'FF'){
                                $(".fts_pdf").val('');
                                showError(res); 
                                return false;
                            }
                        }
                    }//success
                });//ajax end
                                
           }else{
               $(this).val('');
                var error = 'File type / content not supported... !';
                showError(error);
                return false; 
           }
        }
    });
    $('#file_date, #despatch_date, #efiledak-reference_date, #response_date').css('cursor','pointer');
    
    $('#file_date').datepicker({
        autoclose:true,
        format: "dd-mm-yyyy",
        endDate: '0d',
    }).click(function(selected){
        var minDate = new Date(selected.date.valueOf());
        
        $('#despatch_date').datepicker('setStartDate', minDate);
        $("#despatch_date").val('');
        $('.datepicker-days').css('display','block');
    });
    $('#despatch_date').datepicker({
        autoclose:true,
        format: "dd-mm-yyyy",
        endDate: '0d',
    }).click(function(){
        $('.datepicker-days').css('display','block');
    });
    $('#efiledak-reference_date').datepicker({
        autoclose:true,
        format: "dd-mm-yyyy",
        endDate: '0d',
    }).click(function(){
        $('.datepicker-days').css('display','block');
    });
	$('#response_date').datepicker({
        autoclose:true,
        format: "dd-mm-yyyy",
        startDate: '0d',
    }).click(function(){
        $('.datepicker-days').css('display','block');
    });
    
    $(".edit_group").click(function(){
        $("#group_id, #group_name, #group_description").val("");
        var id = $(this).attr('data-key');
        var group_id = $("#group_id_"+id).val();
        var group_name = $("#group_name_"+id).html();
        var group_desc = $("#group_desc_"+id).html();
        var is_active = $("#is_active_"+id).html();
        var is_hierarchical = $("#is_hierarchical_"+id).val();
        $("#group_id").val(group_id);
        $("#group_name").val(group_name);
        $("#group_description").val(group_desc);
        var htm = "<option value=''>Select Hierarchical</option><option value='Y'>Yes</option><option selected='selected' value='N'>No</option>";
       
        if(is_hierarchical == 'Y'){
            htm = "<option value=''>Select Hierarchical</option><option selected='selected' value='Y'>Yes</option><option value='N'>No</option>";    
        }
        $("#is_hierarchical").html(htm);
        $('html,body').animate({scrollTop:0},500);
    });
    
    $("#group_id").change(function(){
        hideError();
        $("#group_emp_list").html("<li>Select Department First</li>");
        $("#get_groups_emp").prop('selectedIndex',0);
    });
    $("#get_groups_emp").change(function(){
        hideError();
        $("#group_emp_list").html("<li>Select Department First</li>");
        var dept_id = $(this).val();
        var group_id = $("#group_id").val();
        if(!group_id){
            showError("Select Group");
            return false;
        }
        if(dept_id){
            var menuid = $("#menuid").val();
            var url = BASEURL+"fts/groupmaster/getdeptemp?securekey="+menuid+"&dept_id="+dept_id;
            $.ajax({
                type: "GET",
                url: url,
                success: function(data){
//                    alert(data);
                    if(data){
                        var ht = $.parseJSON(data);
                        var status = ht.Status;
                        var res = ht.Res;
                        if(status == 'SS'){
                            if(res){
                                $("#group_emp_list").html(res);
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
    });
    
    $(".viewgroupmembers").click(function(){
        var group_id = $(this).attr('data-key');
        var id = $(this).attr('data-id');
        $("#table_viewmember").html("");
        var title = $("#group_name_"+id).html()+" Members";
        $('#viewmemtitle').html(title);
        if(group_id){
            var menuid = $("#menuid").val();
            var url = BASEURL+"fts/groupmaster/getgroupmembers?securekey="+menuid+"&group_id="+group_id;
            $.ajax({
                type: "GET",
                url: url,
                success: function(data){
                    
                    if(data){
                        var ht = $.parseJSON(data);
                        var status = ht.Status;
                        var res = ht.Res;
                        if(status == 'SS'){
                            if(res){
                                $("#groupmemberslist").modal();
                                $("#table_viewmember").html(res);
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
    });
    
    $("#edit_category").click(function(){
        $("#group_id, #group_name, #group_description").val("");
        var id = $(this).attr('data-key');
        var cat_id = $("#cat_id_"+id).val();
        var cat_name = $("#cat_name_"+id).html();
        var description = $("#description_"+id).html();
        $("#fts_category_id").val(cat_id);
        $("#cat_name").val(cat_name);
        $("#cat_description").val(description);
    });
    
    $('#add_process_role').click(function(){
        hideError();
        
        
        var role_id = $('#role_id').val();
        if(!role_id){
            showError("Select Role");
            return false;
        }
        var role_name = $('#role_id').find(":selected").text();
        var order_number = $.trim($("#order_number").val());
        $("#order_number").val(order_number);
        if(!order_number){
            showError("Enter Order Number");
            return false;
        }else{
            if(!$.isNumeric(order_number)){
                showError("Order Number Should Be in Number Only");
                return false;
            }
        }
        var totalLi = $('ul#group_emp_list li').length;
        if(totalLi > 0){
            if(totalLi >= order_number){
                showError("Order Number Already Entered");
                return false;
            }
        }
        var del_id = Math.floor(Math.random()*(1000-99+1)+99);
//        alert(del_id);
        //<p><a href='javascript:void(0)' class='deleteli'  data-key='"+del_id+"'>Delete</a></p>
        var htt = "<li id=del_li_'"+del_id+"'><b>Role Name : </b>"+role_name+"<input type='hidden' value='"+role_id+"' readonly='' name='GroupProcessMember[role_id][]'/><br><b>Order Number : </b>"+order_number+"<input type='hidden' value='"+order_number+"' readonly='' name='GroupProcessMember[order_number][]'/></li>";
        $("#group_emp_list").append(htt);
        $("#list").show();
        $("#process_list_submit").html("<input type='submit' class='btn btn-success btn-sm' value='Submit Process' /><a href='' class='btn btn-danger btn-sm'>Cancel</a>");
        $("#role_id").prop('selectedIndex',0);
        $("#order_number").val('');
//        var menuid = $("#menuid").val();
//        var group_id = $("#group_id").val();
//        var url = BASEURL+"fts/groupmaster/checkroleexits?securekey="+menuid+"&role_id="+role_id+"&group_id="+group_id;
//        $.ajax({
//            type: "GET",
//            url: url,
//            success: function(data){
//
//                if(data){
//                    var ht = $.parseJSON(data);
//                    var status = ht.Status;
//                    var res = ht.Res;
//                    if(status == 'SS'){
//                        if(res == 'Yes'){
//                            $("#role_id").prop('selectedIndex',0);
//                            showError("Role Already Exits in the Group")
//                            return false;
//                        }else if(res == 'No'){
//                            var htt = "<li><b>Role Name :</b>"+role_name+"<input type='hidden' value='"+role_id+"' readonly='' name='GroupProcessMember[role_id][]'/><br><b>Order Number : </b>"+order_number+"</li>";
//                            $("#group_emp_list").append(htt);
//                            $("#list").show();
//                            $("#process_list_submit").html("<input type='submit' class='btn btn-success btn-sm' value='Submit Process' />");
//                            $("#role_id").prop('selectedIndex',0);
//                            $("#order_number").val('');
//                            return false;
////                            alert(role_name);
//                        }
//                    }else{
//                        showError(res); 
//                        return false;
//                    }
//                }else{
//                    return false;
//                }
//            }
//        });
    })
    
    $('body').on('click','.viewgroupprocess',function(){
        var id = $(this).attr('data-id');
        $("#table_viewprocess").html("");
        var title = $("#group_name_"+id).html()+" Process";
        $('#viewprocesstitle').html(title);
        var group_id = $(this).attr('data-key');
        if(group_id){
            var menuid = $("#menuid").val();
            var url = BASEURL+"fts/groupmaster/getgroupprocess?securekey="+menuid+"&group_id="+group_id;
            $.ajax({
                type: "GET",
                url: url,
                success: function(data){
                    
                    if(data){
                        var ht = $.parseJSON(data);
                        var status = ht.Status;
                        var res = ht.Res;
                        if(status == 'SS'){
                            if(res){
                                $("#groupprocesslist").modal();
                                $("#table_viewprocess").html(res);
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
    })
    
    $('.deleteprocessentry').click(function(){
		swal({
		  title: "Are You Sure want to Delete This Entry?",
		  text: '',
		  icon: BASEURL+'images/logo_efile.png',
		  buttons: [
			'No, cancel it!',
			'Yes, I am sure!'
		  ],
		  dangerMode: false,
		}).then(function(isConfirm) {
			if (isConfirm) {
				showLoader();
				return true;
			}else{
				return false;
			}
		});
    });
    $('#fwdback').click(function(){
		
		swal({
		  title: "Are You Sure want to forward back to sender? After forward back you cannot update File.",
		  text: '',
		  icon: BASEURL+'images/logo_efile.png',
		  buttons: [
			'No, cancel it!',
			'Yes, I am sure!'
		  ],
		  dangerMode: false,
		}).then(function(isConfirm) {
			if (isConfirm) {
				showLoader();
				return true;
			}else{
				return false;
			}
		});
        
    });
    $('.saveassbmt').click(function(){
		$("#grp_remarks_submit_type").val('');
		var memberremarks = $.trim($("#memberremarks").val());
		$("#memberremarks").val(memberremarks);
		if(memberremarks){
			var submit_type = $(this).val();
			if(submit_type == 'S'){
				swal({
				  title: "Are you sure want to forward your remarks, once submitted cannot update remarks?",
				  text: '',
				  icon: BASEURL+'images/logo_efile.png',
				  buttons: [
					'No, cancel it!',
					'Yes, I am sure!'
				  ],
				  dangerMode: false,
				}).then(function(isConfirm) {
					if (isConfirm) {
						$("#grp_remarks_submit_type").val(submit_type);
						$("#grpremarkform").submit();
						showLoader();
						return true;
					}else{
						return false;
					}
				});
			}else{
				$("#grp_remarks_submit_type").val(submit_type);
				$("#grpremarkform").submit();
				showLoader();
				return true;
			}
		}else{
			return false;
		}
	});
    $('.final_comment_submit').click(function(){
		var status_ = $(this).val();
		// alert(status_);
		// return false;
		$("#input_final").val('');
		var final_comment_input = $.trim($("#final_comment_input").val());
		$("#final_comment_input").val(final_comment_input);
		var msg = "";
		if(status_ == 'CHD'){
			msg = "Final Draft input / comments will show all the members. Want to submit?";
		}else if(status_ == 'CHF'){
			msg = "Are you sure want to forward to members for Agree / Disagree, once submitted cannot update ?";
		}
		if(final_comment_input){
			swal({
			  title: msg,
			  text: '',
			  icon: BASEURL+'images/logo_efile.png',
			  buttons: [
				'No, cancel it!',
				'Yes, I am sure!'
			  ],
			  dangerMode: false,
			}).then(function(isConfirm) {
				if (isConfirm) {
					$("#input_final").val(status_);
					$("#finalcommentform").submit();
					showLoader();
					return true;
				}else{
					return false;
				}
			});
		}else{
			return false;
		}
	});
    
    $('#agreedisagree').click(function(){
		swal({
		  title: "Decision once taken cannot be changed after submission.",
		  text: '',
		  icon: BASEURL+'images/logo_efile.png',
		  buttons: [
			'No, cancel it!',
			'Yes, I am sure!'
		  ],
		  dangerMode: false,
		}).then(function(isConfirm) {
			if (isConfirm) {
				showLoader();
				$("#members_acceptance_form").submit();
				return true;
			}else{
				return false;
			}
		});
		
    });
    
    $('#fwdtomem').click(function(){
		swal({
		  title: "Are you sure want to forward your remarks to all members for approval?",
		  text: '',
		  icon: BASEURL+'images/logo_efile.png',
		  buttons: [
			'No, cancel it!',
			'Yes, I am sure!'
		  ],
		  dangerMode: false,
		}).then(function(isConfirm) {
			if (isConfirm) {
				showLoader();
				return true;
			}else{
				return false;
			}
		});
    });
    
    // $("#dak_dept_id").change(function(){
	$('body').on('change','#dak_dept_id',function(){
        hideError();
		
        // var dak_type = $("#dak_type").val();
		// var dak_id = "";
		// if(dak_type == 'F'){
			// dak_id = $("#dak_id").val();;
		// }
        var sent_type = $("#sent_type").val();
        if(!sent_type){
            showError('Select Sent Type');
            return false;
        }
        if(sent_type != '1'){
            showError('Select Invalid Sent Type');
            return false;
        }
        var dept_id = $(this).val();
        if(!$.isNumeric(dept_id)){
            showError("Invalid Department ID");
            return false;
        }
        $("#emp_list").html('<option value="">Select Employee</option>');
        var menuid = $("#menuid").val();
        var url = BASEURL+"filetracking/dakfwd/getdeptemp";
        $.ajax({
            type: "POST",
			data :{
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
                            $("#emp_list").html(res);
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
    });
	
	$('body').on('change','#dept_emp',function(){
        hideError();
		$("#dept_emp_list").html('');
        var dept_id = $(this).val();
        if(!dept_id){
			return false;
		}
		var _csrf = $('#_csrf').val();
        
		
        var menuid = $("#menuid").val();
        var url = BASEURL+"efile/dakcommon/getdeptemp";
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
                            $("#dept_emp_list").html(res);
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
    });
	// $('body').on('change','#create_member_dept_emp',function(){
        // hideError();
		
        // var dept_id = $(this).val();
        // if(!$.isNumeric(dept_id)){
            // showError("Invalid Department ID");
            // return false;
        // }
		
    // });
    $('body').on('change','#group_id',function(){
        hideError();
        var group_id = $(this).val();
        var is_hy = $('#is_hierarchy').val();
        $('.in_group_list').hide();
        if(group_id && is_hy){
            var menuid = $("#menuid").val();
            var url = BASEURL+"filetracking/dak/getgrouplist?securekey="+menuid+"&group_id="+group_id+"&is_hy="+is_hy;
            $.ajax({
                type: "GET",
                url: url,
                success: function(data){
                    if(data){
                        var ht = $.parseJSON(data);
                        var status = ht.Status;
                        var res = ht.Res;
                        if(status == 'SS'){
                            if(res){
                                $('.in_group_list').html(res);
                                $('.in_group_list').show();
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
        }else{
            showError("Invalid Group ID.");
            return false;
        }
    });
    
    $('#doc_type').change(function(){
        $('#docs_path').removeAttr('accept');
        if($(this).val()){
            var typp = $(this).find(':selected').attr('data-key');
            if(typp == '1'){
                $('#docs_path').attr('accept','.pdf');
            }else if(typp == '2'){
                $('#docs_path').attr('accept','.jpg, .png, .jpeg');
            }
        }
    });
    
    /*$('.daksubmit').click(function(){
        hideError();
        $('#submit_type').val('');
        var submitType = $(this).attr('data-key');
        if(submitType == 'D' || submitType == 'F'){
            var sent_type = $('#sent_type').val();
            if(!sent_type){
                showError("Select Sent Type");
                return false;
            }
            if(sent_type == '1'){
                var dak_dept_id = $('#dak_dept_id').val();
                if(!dak_dept_id){
                    showError("Select Department");
                    return false;
                }
                if(!$.isNumeric(dak_dept_id)){
                    showError("Invalid Department ID");
                    return false;
                }
                var emp_list = $("#emp_list").val();
                if(!emp_list){
                    showError("Select Employee");
                    return false;
                }
            }else if(sent_type == '2'){
                var is_hierarchy = $('#is_hierarchy').val();
                if(!is_hierarchy){
                    showError("Select Is Hierarchical?");
                    return false;
                }

                if(is_hierarchy == 'Y' || is_hierarchy == 'N'){
                }else{
                    showError("Invalid Hierarchical");
                    return false;
                }
                var group_id = $('#group_id').val();
                if(!group_id){
                    showError("Select Group");
                    return false;
                }
            }else if(sent_type == '3'){
            }else{
                showError("Invalid Sent Type");
                return false;
            }
            
            // var file_refrence_no = $.trim($('#file_refrence_no').val());
            // $('#file_refrence_no').val(file_refrence_no);
            // if(!file_refrence_no){
                // showError("Enter Reference Number.");
                // return false;
            // }
            // var file_date = $.trim($('#file_date').val());
            // $('#file_date').val(file_date);
            // if(!file_date){
                // showError("Enter File Reference Date.");
                // return false;
            // }
            var subject = $.trim($('#subject').val());
            $('#subject').val(subject);
            if(!subject){
                showError("Enter Subject.");
                return false;
            }
            var category = $.trim($('#category').val());
            $('#category').val(category);
            if(!category){
                showError("Select Category.");
                return false;
            }
            var access_level = $.trim($('#access_level').val());
            $('#access_level').val(access_level);
            if(!access_level){
                showError("Select Access Level.");
                return false;
            }
            var priority = $.trim($('#priority').val());
            $('#priority').val(priority);
            if(!priority){
                showError("Select Priority.");
                return false;
            }
            var is_confidential = $.trim($('#is_confidential').val());
            $('#is_confidential').val(is_confidential);
            if(!priority){
                showError("Select Is Confidential?");
                return false;
            }
            
            var summary = $.trim($('#summary').val());
            $('#summary').val(summary);
            if(!summary){
                showError("Enter Short Summary of File.");
                return false;
            }
            
            var docs_path = $("#docs_path").val();
            if(!docs_path){
                showError("Browse File Document.");
                return false;
            }
            if(submitType == 'F'){
                // var despatch_num = $.trim($("#despatch_num").val());
                // $("#despatch_num").val(despatch_num);
                // if(!despatch_num){
                    // showError("Enter Dispatch Number.");
                    // return false;
                // }
                // var despatch_date = $.trim($("#despatch_date").val());
                // $("#despatch_date").val(despatch_date);
                // if(!despatch_date){
                    // showError("Enter Dispatch Date.");
                    // return false;
                // }
                
                if(confirm("Once you submit Dak cannot edit, are you sure want to final submit?")){
                    $('#submit_type').val('F');
                    // $("#dakform").submit();
                    // return true;
                }
                
            }else if(submitType == 'D'){
                $('#submit_type').val('D');
            }
            // $("#dakform").submit();
        }else{
            showError("Invalid Submit ID");
            return false;
        }
        
    });
    
    $('.draftdaksubmit').click(function(){
        hideError();
        $('#submit_type').val('');
        var submitType = $(this).attr('data-key');
        if(submitType == 'D' || submitType == 'F'){
            var sentdetailchange = $('#sentdetailchange').val();
            if(sentdetailchange == 'Y'){
                var sent_type = $('#sent_type').val();
                if(!sent_type){
                    showError("Select Sent Type");
                    return false;
                }
                if(sent_type == '1'){
                    var dak_dept_id = $('#dak_dept_id').val();
                    if(!dak_dept_id){
                        showError("Select Department");
                        return false;
                    }
                    if(!$.isNumeric(dak_dept_id)){
                        showError("Invalid Department ID");
                        return false;
                    }
                    var emp_list = $("#emp_list").val();
                    if(!emp_list){
                        showError("Select Employee");
                        return false;
                    }
                }else if(sent_type == '2'){
                    var is_hierarchy = $('#is_hierarchy').val();
                    if(!is_hierarchy){
                        showError("Select Is Hierarchical?");
                        return false;
                    }

                    if(is_hierarchy == 'Y' || is_hierarchy == 'N'){
                    }else{
                        showError("Invalid Hierarchical");
                        return false;
                    }
                    var group_id = $('#group_id').val();
                    if(!group_id){
                        showError("Select Group");
                        return false;
                    }
                }else if(sent_type == '3'){
                }else{
                    showError("Invalid Sent Type");
                    return false;
                }
            }
            var file_refrence_no = $.trim($('#file_refrence_no').val());
            $('#file_refrence_no').val(file_refrence_no);
            if(!file_refrence_no){
                showError("Enter Reference Number.");
                return false;
            }
            var file_date = $.trim($('#file_date').val());
            $('#file_date').val(file_date);
            if(!file_date){
                showError("Enter File Reference Date.");
                return false;
            }
            var subject = $.trim($('#subject').val());
            $('#subject').val(subject);
            if(!subject){
                showError("Enter Subject.");
                return false;
            }
            var category = $.trim($('#category').val());
            $('#category').val(category);
            if(!category){
                showError("Select Category.");
                return false;
            }
            var access_level = $.trim($('#access_level').val());
            $('#access_level').val(access_level);
            if(!access_level){
                showError("Select Access Level.");
                return false;
            }
            var priority = $.trim($('#priority').val());
            $('#priority').val(priority);
            if(!priority){
                showError("Select Priority.");
                return false;
            }
            var is_confidential = $.trim($('#is_confidential').val());
            $('#is_confidential').val(is_confidential);
            if(!priority){
                showError("Select Is Confidential?");
                return false;
            }
            
            var summary = $.trim($('#summary').val());
            $('#summary').val(summary);
            if(!summary){
                showError("Enter Short Summary of File.");
                return false;
            }
            
            var isdocchange = $("#isdocchange").val();
            if(isdocchange == 'Y'){
                var docs_path = $("#docs_path").val();
                if(!docs_path){
                    showError("Browse File Document.");
                    return false;
                }
            }
            
            if(submitType == 'F'){
                var despatch_num = $.trim($("#despatch_num").val());
                $("#despatch_num").val(despatch_num);
                if(!despatch_num){
                    showError("Enter Dispatch Number.");
                    return false;
                }
                var despatch_date = $.trim($("#despatch_date").val());
                $("#despatch_date").val(despatch_date);
                if(!despatch_date){
                    showError("Enter Dispatch Date.");
                    return false;
                }
                
                if(confirm("Once you submit Dak cannot edit, are you sure want to final submit?")){
                    $('#submit_type').val('F');
                    $("#draftdakform").submit();
                    return true;
                }else{
                    return false;
                }
                
            }else if(submitType == 'D'){
                $('#submit_type').val('D');
            }
            $("#draftdakform").submit();
            
        }else{
            showError("Invalid Submit ID");
            return false;
        }
        
    });
	*/
    $('#changedoc').click(function(){
        $("#browsefile").show();
        $("#showfile").remove();
        $("#isdocchange").val('Y');
    });
    $("#changesenttype").click(function(){
        var vall = $(this).val();
        if(vall == '1'){
            $("#newsenttype").show();
            $('#senttypedetails').hide();
            $(this).val('2');
            $('#sentdetailchange').val('Y');
        }else if(vall == '2'){
            $("#newsenttype").hide();
            $('#senttypedetails').show();
            $(this).val('1');
            $('#sentdetailchange').val('');
        }
    });
    
    $('#clickviewnote').click(function(){
        var menuid = $("#menuid").val();
        var dak_id = $("#dak_id").val();
        var url = BASEURL+"filetracking/dak/getnotes?securekey="+menuid+"&dak_id="+dak_id;
        $.ajax({
            type: "GET",
            url: url,
            success: function(data){
                if(data){
                    var ht = $.parseJSON(data);
                    var status = ht.Status;
                    var res = ht.Res;
                    if(status == 'SS'){
                        $("#viewnotehtml").html(res);
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
        $('#viewnote').modal();
    });
	
	$("#fwd_dak_other").change(function(){
		
		var val = $(this).find(":selected").val();
		var dak_id = $("#dak_id").val();
		
		$("#fwd_html").html('');
		if(val){
			var url = BASEURL+"filetracking/dakfwd/getfwdhtml";
			$.ajax({
				type: "POST",
				data : {
					val:val,
					dak_id:dak_id,
				},
				url: url,
				success: function(data){
				   // alert(data); return false;
					if(data){
						var ht = $.parseJSON(data);
						var status = ht.Status;
						var res = ht.Res;
						if(status == 'SS'){
							if(res){
								$("#fwd_html").html(res);
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
	});
	
	$(".selectsenttype").click(function(){
		var val = $(this).data('key');
		$("#dak_btn_individual_html").hide();
		$("#dak_btn_group_html").hide();
		$("#dept_emp_list, #create_member_dept_emp_list").html('');
		$("#dept_emp").prop('selectedIndex',0);
		$("#forward_type").val('');
		$("#btn_existing_group").removeClass('btn-secondary');
		$("#btn_existing_group").addClass('btn-secondary');
		$("#btn_create_group").removeClass('btn-success');
		$("#btn_create_group").addClass('btn-secondary');
		$("#showexistinggroup").hide();
		$("#group_type").val('');
		if(val == 'I'){
			$("#dak_btn_individual").removeClass('btn-secondary');
			$("#dak_btn_individual").addClass('btn-success');
			$("#dak_btn_group, #dak_btn_allemp").removeClass('btn-success');
			$("#dak_btn_group, #dak_btn_allemp").addClass('btn-secondary');
			$("#dak_btn_individual_html").show();
			$("#forward_type").val('I');
		}
		else if(val == 'G'){
			$("#dak_btn_individual, #dak_btn_allemp").addClass('btn-secondary');
			$("#dak_btn_individual, #dak_btn_allemp").removeClass('btn-success');
			$("#dak_btn_group").addClass('btn-success');
			$("#dak_btn_group").removeClass('btn-secondary');
			$("#dak_btn_group_html").show();
			$("#forward_type").val('G');
		}
		else if(val == 'A'){
			$("#dak_btn_individual, #dak_btn_group").addClass('btn-secondary');
			$("#dak_btn_individual, #dak_btn_group").removeClass('btn-success');
			$("#dak_btn_allemp").addClass('btn-success');
			$("#dak_btn_allemp").removeClass('btn-secondary');
			$("#forward_type").val('A');
		}
	});
	$(".existgrp").click(function(){
		var val = $(this).data('key');
		$("#showexistinggroup").hide();
		$("#creategroup").hide();
		$("#create_member_dept_emp_list").html('');
		$("#create_member_dept_emp").prop('selectedIndex',0);
		$("#group_type").val('');
		if(val == 'E'){
			$("#btn_existing_group").removeClass('btn-secondary');
			$("#btn_existing_group").addClass('btn-success');
			$("#btn_create_group").removeClass('btn-success');
			$("#btn_create_group").addClass('btn-secondary');
			$("#showexistinggroup").show();
			$("#group_type").val('E');
		}
		else if(val == 'C'){
                        showLoader();
			$("#btn_existing_group").addClass('btn-secondary');
			$("#btn_existing_group").removeClass('btn-success');
			$("#btn_create_group").addClass('btn-success');
			$("#btn_create_group").removeClass('btn-secondary');
			$("#creategroup").show();
			$("#group_type").val('C');
			
			var _csrf = $('#_csrf').val();
			$("#create_member_dept_emp_list").html('');
			var url = BASEURL+"efile/dakcommon/getdeptempforgroup";
			$.ajax({
				type: "POST",
				data :{
					_csrf:_csrf,
				},
				url: url,
				success: function(data){
                                    hideLoader();
					if(data){
						var ht = $.parseJSON(data);
						var status = ht.Status;
						var res = ht.Res;
						if(status == 'SS'){
							if(res){		
								$("#create_member_dept_emp_list").html(res);
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
		
	});
	
	$("#efiledak-file_category_id").change(function(){
		
            $("#project_list").hide();
            $("#efiledak-file_project_id ").removeAttr('required');
            $("#efiledak-file_project_id ").html("<option value=''>Select Project</option>");
            var val = $(this).find(":selected").val();
            if(val){
                showLoader();
                var url = BASEURL+"efile/dakcommon/get_project_list";
                $.ajax({
                    type: "POST",
                    data : {
                        category_id:val
                    },
                    url: url,
                    success: function(data){
                        hideLoader();
                   // alert(data); return false;
                        if(data){
                            var ht = $.parseJSON(data);
                            var status = ht.Status;
                            var res = ht.Res;
                            if(status == 'SS'){
                                if(res){
                                    $("#efiledak-file_project_id ").attr('required', true);
                                    $("#project_list").show();
                                    $("#efiledak-file_project_id").html(res);
                                    return false;
                                }
                            }else if(status == 'FF'){

                                showError(res); 
                                return false;
                            }else{

                                return false;
                            }
                        }else{
                            return false;
                        }
                    }
                });
            }
	});
	
	
	$("#efile_doc_type").change(function(){
		var val = $(this).find(":selected").data('key');
		$("#pdf_docs_path").val('');
		$("#fts_image_multiple").val('');
		$("#pdf_file_html, #image_file_html").hide();
		$("#pdf_docs_path, #fts_image_multiple").removeAttr('required');
                $("#file_html_label").hide();
		if(val == '1'){
			$("#pdf_docs_path").attr('required', true);
			
			$("#pdf_file_html").show();
                        $("#file_html_label").show();
		}else if(val == '2'){
			$("#fts_image_multiple").attr('required', true);
			$("#image_file_html").show();
                        $("#file_html_label").show();
		}
	});
	$('body').on('click','.grplistemp',function(){
		var key = $(this).data('key');
		var dept_id= $(this).data('key1');
		
        if($(this).prop('checked') == true){
			var emp_code = $(this).val();
			var name = $("#name_"+key).html();
			
			var htm = "<li id='li_"+key+"'> <input type='hidden' name='finalGrpEmpDept[]' value='"+dept_id+"' readonly  /> <input type='hidden' name='finalGrpEmp[]' value='"+emp_code+"' readonly='' /> "+name+" <button type='button' class='btn btn-danger btn-xs' onclick='removeli("+key+")'>Remove</button></li>";
			// alert(htme
			// var old = $("#grp_emp_for_final").html();
			// alert(old);
			$("#grp_emp_for_final").append(htm);
		}else{
			$("#li_"+key).remove();
		}
	});
	
	$(".viewnote").click(function(){
		var val = $(this).data('key');
		var note = $("#fullnote_"+val).html();
		$("#show_full_note").html('');
		$("#show_full_note").html(note);
		$("#viewfullnote").modal();
	});
	
	$(".returnfile").click(function(){
		if(confirm("Are you sure want send the file?")){
			return true;
		}else{
			return false;
		}
	});
	
	$("#finalbox").click(function(){
            $("#final_draft").show();
        });
	$('#scansubmitbtn').click(function(){
		swal({
		  title: "Are you sure want to forward? once submitted cannot update?",
		  text: '',
		  icon: BASEURL+'images/logo_efile.png',
		  buttons: [
			'No, cancel it!',
			'Yes, I am sure!'
		  ],
		  dangerMode: false,
		}).then(function(isConfirm) {
			if (isConfirm) {
				$("#scandocform").submit();
				showLoader();
				return true;
			}else{
				return false;
			}
		});
    });
	
	$(".notesubmitbtn").click(function(){
		var checksubject= $("#checksubject").val();
		
		if(checksubject == 'Y'){
			var note_subject = $.trim($("#note_subject").val());
			$("#note_subject").val(note_subject);
			var note_comment = $.trim($("#note_comment").val());
			$("#note_comment").val(note_comment);
			
			if(note_subject && note_comment){
				showLoader();
			}
		}else{
			var note_comment = $.trim($("#note_comment").val());
			$("#note_comment").val(note_comment);
			
			if(note_comment){
				showLoader();
			}
		}
		
	});
        $("#request_scan_emp_code").change(function(){
            var val = $(this).find(":selected").val();
            if(val){
                $("#note_fwd_view").hide();
            }else{
                $("#note_fwd_view").show();
            }
        });
        
}); // document ready end
function removeli(key){
	$("#li_"+key).remove();
}

function validateRemarks(){
	hideError();
	var file_remarks = $.trim($("#file_remarks").val());
	$("#file_remarks").val(file_remarks);
	
	var efile_doc_type = $('#efile_doc_type').find(":selected").val();
	
	if(!file_remarks && !efile_doc_type){
		showError("Enter File Remarks OR Browse File");
		return false;
	}
	
	if(efile_doc_type){
		var pdf_docs_path = $("#pdf_docs_path").val();
		var fts_image_multiple = $("#fts_image_multiple").val();
		if(!pdf_docs_path && !fts_image_multiple){
			showError("Browse File");
			return false;
		}
	}
	swal({
	  title: "Are you sure want to Add Remarks / Append content in document?",
	  text: '',
	  icon: BASEURL+'images/logo_efile.png',
	  buttons: [
		'No, cancel it!',
		'Yes, I am sure!'
	  ],
	  dangerMode: false,
	}).then(function(isConfirm) {
		if (isConfirm) {
			$("#fileremarksform").submit();
			showLoader();
		}else{
			return false;
		}
	});
	
}

function get_dept_emp_list_ul(deptid, show_emp_list_id){
		hideError();
		$("#"+show_emp_list_id).html('');
        var dept_id = $("#"+deptid).val();
        if(!dept_id){
			return false;
		}
		var _csrf = $('#_csrf').val();
        
		
        var menuid = $("#menuid").val();
        var url = BASEURL+"efile/dakcommon/getdeptempulforgrp";
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
                            $("#"+show_emp_list_id).html(res);
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
function savenewproject(){

	var project_name = $.trim($("#project_name").val());
	$("#project_name").val(project_name);
	
	if(!project_name){
		alert("Enter Project Name");
		return false;
	}
	var url = BASEURL+"efile/dakcommon/add_new_project";
		$.ajax({
			type: "POST",
			data : {
				project_name:project_name
			},
			url: url,
			success: function(data){
				hideLoader();
			   // alert(data); return false;
				if(data){
					var ht = $.parseJSON(data);
					var status = ht.Status;
					var res = ht.Res;
					if(status == 'SS'){
						if(res){
							$("#efiledak-file_project_id").html(res);
							$('.close').click();
							return false;
						}
					}else if(status == 'FF'){
						showError(res); 
						return false;
					}else{
						return false;
					}
				}else{
					return false;
				}
			}
		});
	
}
function forwardOption(val){
	$("#forwardHtml").hide();
	$("#forward_type, #group_type").val('');
	$(".selectsenttype, .existgrp").removeClass('btn-success');
	$(".selectsenttype, .existgrp").addClass('btn-secondary');
	$("#dak_btn_individual_html, #dak_btn_group_html, #showexistinggroup, #creategroup").hide();
	$("#dept_emp_list, #create_member_dept_emp_list").html('');
	$("#timeboundhtml").hide();
	$("#forwardsubmitbtn").hide();
        $("#btn_fwd_submit").html('Submit');
	if(val == 'Y'){
                $("#btn_fwd_submit").html('Forward');
		$("#btn_show_no").removeClass('btn-success');
		$("#btn_show_no").addClass('btn-secondary');
		$("#btn_show_yes").addClass('btn-success');
		$("#btn_show_yes").removeClass('btn-secondary');
		$("#forward_dak").val('Y');
		$("#forwardHtml").show();
		$("#timeboundhtml").show();
		$("#forwardsubmitbtn").show();
	}else if(val == 'N'){
		$("#btn_show_yes").removeClass('btn-success');
		$("#btn_show_yes").addClass('btn-secondary');
		$("#btn_show_no").addClass('btn-success');
		$("#btn_show_no").removeClass('btn-secondary');
		$("#forward_dak").val('Y');
	}
}
// function dak_fwd_type(val){
	
	// $("#dak_return").removeClass('btn-success');
	// $("#dak_return").addClass('btn-secondary');
	// $("#dak_fwd").removeClass('btn-success');
	// $("#dak_fwd").addClass('btn-secondary');
	// $("#dakfwdtype, #fwd_note").val('');
	// $("#view_fwd_note").hide();
	// if(val == 'F'){
		// $("#dak_return").removeClass('btn-success');
		// $("#dak_return").addClass('btn-secondary');
		// $("#dak_fwd").addClass('btn-success');
		// $("#dak_fwd").removeClass('btn-secondary');
		// $("#dakfwdtype").val('F');
	// }else if(val == 'R'){
		// $("#dak_return").addClass('btn-success');
		// $("#dak_return").removeClass('btn-secondary');
		// $("#dak_fwd").removeClass('btn-success');
		// $("#dak_fwd").addClass('btn-secondary');
		// $("#dakfwdtype").val('R');
	// }
	// $("#view_fwd_note").show();
// }  

function get_dept_emp_list(deptid, emp_dropdown_id){
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
	var url = BASEURL+"efile/dakcommon/getdeptempdropdown";
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
function senType(id){
    hideError();
    $(".in_group_list").hide();
    $(".in_group_list").html('');
    $('#yes_hierry, #no_hierry').removeClass('btn-success');
    $('#yes_hierry, #no_hierry').addClass('btn-secondary');
    $('.in_list, .in_group').hide();
    $("#group_id").html('<option value="">Select Group</option>');
    $("#emp_list").html('<option value="">Select Employee</option>');
    $("#dak_dept_id").prop('selectedIndex',0);
    if(id == '1'){
        $('.in_list').show();
        $('#individual').removeClass('btn-secondary');
        $('#individual').addClass('btn-success');
        $('#group, #all_emp').removeClass('btn-success');
        $('#group, #all_emp').addClass('btn-secondary');
        $('#sent_type').val(id);
    }else if(id == '2'){
        $('.in_group').show();
        $('#group').removeClass('btn-secondary');
        $('#group').addClass('btn-success');
        $('#individual, #all_emp').removeClass('btn-success');
        $('#individual,#all_emp').addClass('btn-secondary');
        $('#sent_type').val(id);
    }else if(id == '3'){
        $('#all_emp').removeClass('btn-secondary');
        $('#all_emp').addClass('btn-success');
        $('#individual, #group').removeClass('btn-success');
        $('#individual,#group').addClass('btn-secondary');
        $('#sent_type').val(id);
    }else{
        $('#individual,#group, #all_emp').removeClass('btn-success');
        $('#individual,#group, #all_emp').addClass('btn-secondary');
        $('#sent_type').val('');
        showError('Invalid Sent Type.');
        return false;
    }
}

function Hierarchy(id){
    $(".in_group_list").hide();
    $(".in_group_list").html('');
    $("#group_id").html('<option value="">Select Group</option>');
    $('#is_hierarchy').val('');
    var sent_type = $("#sent_type").val();
    if(sent_type == '2'){
        var typee = '';
        if(id == '1'){
            $('#yes_hierry').removeClass('btn-secondary');
            $('#yes_hierry').addClass('btn-success');
            $('#no_hierry').removeClass('btn-success');
            $('#no_hierry').addClass('btn-secondary');
            $('#is_hierarchy').val('Y');
            typee = 'Y';
        }else if(id == '2'){
            $('#no_hierry').removeClass('btn-secondary');
            $('#no_hierry').addClass('btn-success');
            $('#yes_hierry').removeClass('btn-success');
            $('#yes_hierry').addClass('btn-secondary');
            $('#is_hierarchy').val('N');
            typee = 'N';
        }else{
            $('#individual,#group, #all_emp').removeClass('btn-success');
            $('#individual,#group, #all_emp').addClass('btn-secondary');
            $('#sent_type').val('');
            showError('Invalid Hierarchy ID.');
            return false;
        }
        if(typee){
            
            var menuid = $("#menuid").val();
            var url = BASEURL+"filetracking/dak/getgroups?securekey="+menuid+"&typee="+typee;
            $.ajax({
                type: "GET",
                url: url,
                success: function(data){
//                    alert(data);
                    if(data){
                        var ht = $.parseJSON(data);
                        var status = ht.Status;
                        var res = ht.Res;
                        if(status == 'SS'){
                            if(res){
                                $("#group_id").html(res);
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
    }    
}

function validateInitaiteFileForm(){
    hideError();
    var initiate_type = $.trim($("#initiate_type").val());
    if(initiate_type == 'N' || initiate_type == 'F'){
        
    }else{
        showError("Select Initiate Type");
        return false;
    }
    if(initiate_type == 'F'){
        var reference_num = $.trim($("#efiledak-reference_num").val());
        $("#efiledak-reference_num").val(reference_num);

        if(!reference_num){
            showError("Enter Reference No.");
            return false;
        }
        var subject = $.trim($("#efiledak-subject").val());
        $("#efiledak-subject").val(subject);
        if(!subject){
            showError("Enter Subject");
            return false;
        }
    }
    var file_category_id = $("#efiledak-file_category_id").find(":selected").val();
    
    if(!file_category_id){
        showError("Select  File Category");
        return false;
    }
    
    var project = $("#efiledak-file_category_id").find(":selected").data('key');
    if(project == 'Y'){
        var file_project_id = $("#efiledak-file_project_id").find(":selected").val();
        if(!file_project_id){
            showError("Select Project");
            return false;
        }
    }
    var access_level = $("#efiledak-access_level").find(":selected").val();
    if(!access_level){
        showError("Select Access Level");
        return false;
    }
    var priority = $("#efiledak-priority").find(":selected").val();
    if(!priority){
        showError("Select Priority of File");
        return false;
    }
    var is_confidential = $("#efiledak-is_confidential").find(":selected").val();
    if(!is_confidential){
        showError("Select Is Confidential?");
        return false;
    }
    
    var meta_keywords = $.trim($("#efiledak-meta_keywords").val());
    $("#efiledak-meta_keywords").val(meta_keywords);
    var summary = $.trim($("#efiledak-summary").val());
    $("#efiledak-summary").val(summary);
    var remarks = $.trim($("#efiledak-remarks").val());
    $("#efiledak-remarks").val(remarks);
    var note_subject = $.trim($("#note_subject").val());
    $("#note_subject").val(note_subject);
    var note_comment = $.trim($("#note_comment").val());
    $("#note_comment").val(note_comment);
    var file_remarks = $.trim($("#file_remarks").val());
    $("#file_remarks").val(file_remarks);
    var efile_doc_type = $("#efile_doc_type").find(":selected").val();
    
    
    var request_scan = $.trim($("#request_scan").val());
    $("#request_scan").val(request_scan)
    
    if(request_scan == 'Y'){
        showError("Unselect the Assigned Employee for scan.");
        return false;
    }
    
    
    if(note_subject){
    }else if(file_remarks){
    }else if(efile_doc_type){}else{
        showError("Required Add Comment OR Upload File");
        return false;
    }
    
    if(note_subject){
        if(!note_comment){
            showError("Enter Note Comments");
            return false;
        }
    }
    if(note_comment){
        if(!note_subject){
            showError("Enter Note Subject");
            return false;
        }
    }
    var pdf_docs_path = $("#pdf_docs_path").val();
    var fts_image_multiple = $("#fts_image_multiple").val();
    if(efile_doc_type){
        if(pdf_docs_path){
        }else if(fts_image_multiple){
        }else{
            showError("Browse File (PDF OR Image).");
            return false;
        }
    }
    if(pdf_docs_path){
        if(!efile_doc_type){
            showError("Select Upload File Type ");
            return false;
        }
    }else if(fts_image_multiple){
        if(!efile_doc_type){
            showError("Select Upload File Type ");
            return false;
        }
    }
    
    var forward_dak = $("#forward_dak").val();
    if(forward_dak == 'Y'){
        var forward_type = $("#forward_type").val();
        if(!forward_type){
            showError("Select Forward To");
            return false;
        }
        
        if(forward_type == 'I'){
            var indi_emp_code = $("#indi_emp_code").find(":selected").val();
            if(!indi_emp_code){
                showError("Select Employee for Forward");
                return false;
            }
            
            $("#dakform").submit();
            showLoader();
        }else if(forward_type == 'G'){
            var group_type = $("#group_type").val();
            if(!group_type){
                showError("Select Create New Group / Committee");
                return false;
            }
            if(group_type == 'C'){
                var group_name = $.trim($("#group_name").val());
                $("#group_name").val(group_name);
                if(!group_name){
                    showError("Enter Group / Committee Name");
                    return false;
                }
                var chairman_dept_id = $("#chairman_dept_id").find(":selected").val();
                var group_chairman_emp_code = $("#group_chairman_emp_code").find(":selected").val();
                if(!chairman_dept_id || !group_chairman_emp_code){
                    showError("Select Group / Committee Chairman");
                    return false;
                }
                var convenor_dept_id = $("#convenor_dept_id").find(":selected").val();
                var group_convenor_emp_code = $("#group_convenor_emp_code").find(":selected").val();
                if(!convenor_dept_id || !group_convenor_emp_code){
                    showError("Select Group / Committee Convenor");
                    return false;
                }
                var grp_emp_for_final = $("#grp_emp_for_final").html();
                if(grp_emp_for_final == ''){
                    showError("Select Atleast one member for Group / Committee");
                    return false;
                }
                $("#dakform").submit();
                showLoader();
                
            }else{
                showError("Invalid Select Group.");
                return false;
            }
        }else if(forward_type == 'A'){
            $("#dakform").submit();
            showLoader();
        }else{
            showError("Invalid Forward To Type.");
            return false;
        }
    }else{
        $("#dakform").submit();
        showLoader();
    }
}
function validateReceivedFileForm(){
    hideError();
    var reference_num = $.trim($("#efiledak-reference_num").val());
    $("#efiledak-reference_num").val(reference_num);

    if(!reference_num){
        showError("Enter Reference No.");
        return false;
    }
    var subject = $.trim($("#efiledak-subject").val());
    $("#efiledak-subject").val(subject);
    if(!subject){
        showError("Enter Subject");
        return false;
    }
    var file_category_id = $("#efiledak-file_category_id").find(":selected").val();
    if(!file_category_id){
        showError("Select  File Category");
        return false;
    }
    
    var project = $("#efiledak-file_category_id").find(":selected").data('key');
    if(project == 'Y'){
        var file_project_id = $("#efiledak-file_project_id").find(":selected").val();
        if(!file_project_id){
            showError("Select Project");
            return false;
        }
    }
    var access_level = $("#efiledak-access_level").find(":selected").val();
    if(!access_level){
        showError("Select Access Level");
        return false;
    }
    var priority = $("#efiledak-priority").find(":selected").val();
    if(!priority){
        showError("Select Priority of File");
        return false;
    }
    var is_confidential = $("#efiledak-is_confidential").find(":selected").val();
    if(!is_confidential){
        showError("Select Is Confidential?");
        return false;
    }
    
    var meta_keywords = $.trim($("#efiledak-meta_keywords").val());
    $("#efiledak-meta_keywords").val(meta_keywords);
    var summary = $.trim($("#efiledak-summary").val());
    $("#efiledak-summary").val(summary);
    var remarks = $.trim($("#efiledak-remarks").val());
    $("#efiledak-remarks").val(remarks);
    var note_subject = $.trim($("#note_subject").val());
    $("#note_subject").val(note_subject);
    var note_comment = $.trim($("#note_comment").val());
    $("#note_comment").val(note_comment);
    var file_remarks = $.trim($("#file_remarks").val());
    $("#file_remarks").val(file_remarks);
    var efile_doc_type = $("#efile_doc_type").find(":selected").val();
    
    var request_scan = $.trim($("#request_scan").val());
    $("#request_scan").val(request_scan)
    
    if(request_scan == 'Y'){
        showError("Unselect the Assigned Employee for scan.");
        return false;
    }
    
    if(note_subject){
    }else if(file_remarks){
    }else if(efile_doc_type){}else{
        showError("Required Add Comment OR Upload File");
        return false;
    }
    
    if(note_subject){
        if(!note_comment){
            showError("Enter Note Comments");
            return false;
        }
    }
    if(note_comment){
        if(!note_subject){
            showError("Enter Note Subject");
            return false;
        }
    }
    var pdf_docs_path = $("#pdf_docs_path").val();
    var fts_image_multiple = $("#fts_image_multiple").val();
    if(efile_doc_type){
        if(pdf_docs_path){
        }else if(fts_image_multiple){
        }else{
            showError("Browse File (PDF OR Image).");
            return false;
        }
    }
    if(pdf_docs_path){
        if(!efile_doc_type){
            showError("Select Upload File Type ");
            return false;
        }
    }else if(fts_image_multiple){
        if(!efile_doc_type){
            showError("Select Upload File Type ");
            return false;
        }
    }
    
    var forward_dak = $("#forward_dak").val();
    if(forward_dak == 'Y'){
        var forward_type = $("#forward_type").val();
        if(!forward_type){
            showError("Select Forward To");
            return false;
        }
        
        if(forward_type == 'I'){
            var indi_emp_code = $("#indi_emp_code").find(":selected").val();
            if(!indi_emp_code){
                showError("Select Employee for Forward");
                return false;
            }
            
            $("#dakform").submit();
            showLoader();
        }else if(forward_type == 'G'){
            var group_type = $("#group_type").val();
            if(!group_type){
                showError("Select Create New Group / Committee");
                return false;
            }
            if(group_type == 'C'){
                var group_name = $.trim($("#group_name").val());
                $("#group_name").val(group_name);
                if(!group_name){
                    showError("Enter Group / Committee Name");
                    return false;
                }
                var chairman_dept_id = $("#chairman_dept_id").find(":selected").val();
                var group_chairman_emp_code = $("#group_chairman_emp_code").find(":selected").val();
                if(!chairman_dept_id || !group_chairman_emp_code){
                    showError("Select Group / Committee Chairman");
                    return false;
                }
                var convenor_dept_id = $("#convenor_dept_id").find(":selected").val();
                var group_convenor_emp_code = $("#group_convenor_emp_code").find(":selected").val();
                if(!convenor_dept_id || !group_convenor_emp_code){
                    showError("Select Group / Committee Convenor");
                    return false;
                }
                var grp_emp_for_final = $("#grp_emp_for_final").html();
                if(grp_emp_for_final == ''){
                    showError("Select Atleast one member for Group / Committee");
                    return false;
                }
                $("#dakform").submit();
                showLoader();
                
            }else{
                showError("Invalid Select Group.");
                return false;
            }
        }else if(forward_type == 'A'){
            $("#dakform").submit();
            showLoader();
        }else{
            showError("Invalid Forward To Type.");
            return false;
        }
    }else{
        $("#dakform").submit();
        showLoader();
    }
}
function sendforscan(){
    hideError();
    var initiate_type = $.trim($("#initiate_type").val());
    if(initiate_type == 'N' || initiate_type == 'F'){
        
    }else{
        showError("Select Initiate Type");
        return false;
    }
    if(initiate_type == 'F'){
        var reference_num = $.trim($("#efiledak-reference_num").val());
        $("#efiledak-reference_num").val(reference_num);

        if(!reference_num){
            showError("Enter Reference No.");
            return false;
        }
        var subject = $.trim($("#efiledak-subject").val());
        $("#efiledak-subject").val(subject);
        if(!subject){
            showError("Enter Subject");
            return false;
        }
    }
    var file_category_id = $("#efiledak-file_category_id").find(":selected").val();
    if(!file_category_id){
        showError("Select  File Category");
        return false;
    }
    
    var project = $("#efiledak-file_category_id").find(":selected").data('key');
    if(project == 'Y'){
        var file_project_id = $("#efiledak-file_project_id").find(":selected").val();
        if(!file_project_id){
            showError("Select Project");
            return false;
        }
    }
    var access_level = $("#efiledak-access_level").find(":selected").val();
    if(!access_level){
        showError("Select Access Level");
        return false;
    }
    var priority = $("#efiledak-priority").find(":selected").val();
    if(!priority){
        showError("Select Priority of File");
        return false;
    }
    
    var is_confidential = $("#efiledak-is_confidential").find(":selected").val();
    if(!is_confidential){
        showError("Select Is Confidential?");
        return false;
    }
    
    var meta_keywords = $.trim($("#efiledak-meta_keywords").val());
    $("#efiledak-meta_keywords").val(meta_keywords);
    var summary = $.trim($("#efiledak-summary").val());
    $("#efiledak-summary").val(summary);
    var remarks = $.trim($("#efiledak-remarks").val());
    $("#efiledak-remarks").val(remarks);
    var note_subject = $.trim($("#note_subject").val());
    $("#note_subject").val(note_subject);
    var note_comment = $.trim($("#note_comment").val());
    $("#note_comment").val(note_comment);
    var file_remarks = $.trim($("#file_remarks").val());
    $("#file_remarks").val(file_remarks);
    var request_scan_emp_code = $("#request_scan_emp_code").find(":selected").val();
    if(!request_scan_emp_code){
        showError("Select Employee for Scan Request");
        return false;
    }
    
    swal({
      title: "Are you sure want to send for scan? ",
      text: '',
      icon: BASEURL+'images/logo_efile.png',
      buttons: [
            'No, cancel it!',
            'Yes, I am sure!'
      ],
      dangerMode: false,
    }).then(function(isConfirm) {
            if (isConfirm) {
                    $("#request_scan").val('Y');
                    $("#dakform").submit();
                    showLoader();
            }else{
                    return false;
            }
    });
}

function validateForwardFileForm(){
	hideError();
	var forward_dak = $("#forward_dak").val();
    if(forward_dak == 'Y'){
        var forward_type = $("#forward_type").val();
        if(!forward_type){
            showError("Select Forward To");
            return false;
        }
        
        if(forward_type == 'I'){
            var indi_emp_code = $("#indi_emp_code").find(":selected").val();
            if(!indi_emp_code){
                showError("Select Employee for Forward");
                return false;
            }
            
            $("#fwrdform").submit();
            showLoader();
        }else if(forward_type == 'G'){
            var group_type = $("#group_type").val();
            if(!group_type){
                showError("Select Create New Group / Committee");
                return false;
            }
            if(group_type == 'C'){
                var group_name = $.trim($("#group_name").val());
                $("#group_name").val(group_name);
                if(!group_name){
                    showError("Enter Group / Committee Name");
                    return false;
                }
                var chairman_dept_id = $("#chairman_dept_id").find(":selected").val();
                var group_chairman_emp_code = $("#group_chairman_emp_code").find(":selected").val();
                if(!chairman_dept_id || !group_chairman_emp_code){
                    showError("Select Group / Committee Chairman");
                    return false;
                }
                var convenor_dept_id = $("#convenor_dept_id").find(":selected").val();
                var group_convenor_emp_code = $("#group_convenor_emp_code").find(":selected").val();
                if(!convenor_dept_id || !group_convenor_emp_code){
                    showError("Select Group / Committee Convenor");
                    return false;
                }
                var grp_emp_for_final = $("#grp_emp_for_final").html();
                if(grp_emp_for_final == ''){
                    showError("Select Atleast one member for Group / Committee");
                    return false;
                }
                $("#fwrdform").submit();
                showLoader();
                
            }else{
                showError("Invalid Select Group.");
                return false;
            }
        }else if(forward_type == 'A'){
            $("#fwrdform").submit();
            showLoader();
        }else{
            showError("Invalid Forward To Type.");
            return false;
        }
    }
}

function change_initiate_type(type){
    if(type == 'F'){
        $("#efiledak-reference_num").val('');
        $("#initiate_file").removeClass('btn-secondary');
        $("#initiate_file").addClass('btn-success');
        $("#initiate_note").removeClass('btn-success');
        $("#initiate_note").addClass('btn-secondary');
        $(".hideref").show();
        $("#initiate_type").val('F');
    }else if(type == 'N'){
        $("#initiate_note").removeClass('btn-secondary');
        $("#initiate_note").addClass('btn-success');
        $("#initiate_file").removeClass('btn-success');
        $("#initiate_file").addClass('btn-secondary');
        $(".hideref").hide();
        $("#initiate_type").val('N');
    }
}