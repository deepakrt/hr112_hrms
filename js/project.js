var _csrf = $('#_csrf').val();
var menuid = $("#menuid").val();
var pstart_date = '';
var pend_date = '';
$(document).ready(function(){
	if ( $("select").hasClass("js-example-basic-multiple") ) {
		$('.js-example-basic-multiple').select2();
	}
		
    $('.projectlist-start_date, .projectlist-end_date').css('cursor','pointer');
    $('.projectlist-start_date').datepicker({
        autoclose:true,
        format: "dd-mm-yyyy",
        orientation: "top-left",
    }).on('changeDate', function (selected){
        hideError();
        var minDate = new Date(selected.date.valueOf());
        $('.projectlist-end_date').datepicker('setStartDate', minDate);
        $('.project_start_date').datepicker('setStartDate', minDate);
        $(".projectlist-end_date").val('');
    });

    $(".projectlist-end_date").datepicker({
        autoclose:true,
        format: "dd-mm-yyyy",
        orientation: "top",
    }).on('changeDate', function(ev){
        {
            var fromdateval= $(".projectlist-start_date").val();
            if(fromdateval==''){
                $(".projectlist-end_date").val('');
                showError("Please enter Project Start Date First1.");
                return false;
            }
        }
    });
	
	$("#project_start_date").datepicker({
        autoclose:true,
		startDate:pstart_date,
		endDate:pend_date,
        format: "dd-mm-yyyy",
        orientation: "top-left",
    }).on('changeDate', function(e){hideError();
			$('#project_start_date').css('color','#495057');
			$('#project_end_date').attr('disabled',false);
             if(pstart_date=='' || pend_date==''){
                $(this).val('');
                showError("Please enter Project Start & End Date First.");
                return false;
            }
			$('#project_end_date').val($('#project_start_date').val());
			$(this).parent().next().find('input').datepicker('setStartDate', e.date).datepicker('setEndDate', pend_date).focus();
		});
     $("#project_end_date").datepicker({
        autoclose:true,
        format: "dd-mm-yyyy",
        orientation: "top", 
    }).on('changeDate', function(ev){
        {
            var fromdateval= $(this).parent().prev().find('input').val();
             if(fromdateval==''){
                $(this).val('');
				$(this).parent().prev().find('input').focus();
                 showError("Please enter Project Start Date First.");
                return false;
            }
        }
    });
	
    $('#changeProjectFile').click(function(){
        $('#pdfview').hide();
        $('#pdfadd').show();
        $('#doc_path1').val('Y');
    });
    $('#resetProjectFile').click(function(){
        $('#projectlist-approval_doc').val('');
        $('#pdfview').show();
        $('#pdfadd').hide();
        $('#doc_path1').val('N');
    });
    
	 
	
    $('.projectlist-manager_dept').change(function(){
        hideError();
        var dept_id = $(this).val();
        
        if(dept_id){
            if(!$.isNumeric(dept_id)){
                showError("Invalid Dept ID");
                return false;
            }
			$.ajax({
						url:BASEURL+'inventory/default/get_dept_emp?securekey='+menuid,
						type:'POST',
						data:{dept_id:dept_id,_csrf:_csrf},
						datatype:'json',
						success:function(data){
							$('.projectlist-contact_person').html(data);
 						}
					  });
           /*  var param_manager_emp_id = $("#param_manager_emp_id").val();
            getProjectManager(dept_id, param_manager_emp_id); */
        }
    });
    
    $('.removeRecourse').click(function(){
        var name = $(this).data('key1');
        var role = $(this).data('key');
        var html = "Are you sure want to remove "+name+" as "+role+" from project?";
        if(confirm(html)){
            return true;
        }
        return false;
        
    });
	
	/*$('#w0').on('submit', function(event){
		       
		var project_name=$('#projectlist-project_name').val();
		var description=$('#projectlist-description').val();
		var project_type=$('#projectlist-project_type').val();
		var start_date=$('#projectlist-start_date').val();
        var end_date=$('#projectlist-end_date').val();
        var amount=$('#projectlist-project_cost').val();
        var enterpcb=$('#enterpcb').val();
        var address=$('#projectlist-address').val();
		var error=$(".help-block").html();
       if(project_name!='' && description!='' && project_type!='' && address!='' && start_date!='' && end_date!='' && amount!='' && error=='' && enterpcb==''){
		    event.preventDefault();
  			swal({
				  title: "Do You Want to Add Project Cost Breakdown",
				  text: '',
 				  buttons: [
					'No, I don"t want to add!',
					'Yes, I want to add!'
				  ],
				  dangerMode: false,
				}).then(function(isConfirm) {
			    if (isConfirm) {
 					$('#enterpcb').val('1');
				}else{
 					$('#enterpcb').val('0');
				}
				 $("#w0").submit();
         });
		} 
	  });*/
	
});
 /* function add_proj_fund(){
	 var menuid = $("#menuid").val();var _csrf = $('#_csrf').val();
		 
		var start_date=$('#project_start_date').val();
        var end_date=$('#project_end_date').val();
        var pc_cat=$('#cost_category').val();
        var amount=$('#project-fund').val();
		if(start_date=='' || end_date=='' || pc_cat=='' || amount==''){
			return false;
		}
        hideError();
 			
	} */
 
  
 function getdeptemp(dept_id,emp_code){
	
  					$.ajax({
						url:BASEURL+'inventory/default/get_dept_emp?securekey='+menuid,
						type:'POST',
						data:{dept_id:dept_id,emp_code:emp_code,_csrf:_csrf},
						datatype:'json',
						success:function(data){
							$('#projectlist-contact_person').html(data);
 						}
					  });
			}
//function getProjectManager(dept_id, param_manager_emp_id){
//    var _csrf = $('#_csrf').val();
//    var menuid = $("#menuid").val();
//
//    var url = BASEURL+"manageproject/projects/get_dept_member?securekey="+menuid;
//    $.ajax({
//        type: "POST",
//        url: url,
//        data:{
//            dept_id:dept_id,
//            param_manager_emp_id:param_manager_emp_id,
//        },
//        success: function(data){
////                    alert(data);
//            if(data){
//                var ht = $.parseJSON(data);
//                var status = ht.Status;
//                var res = ht.Res;
//                if(status == 'SS'){
//                    $('#projectlist-manager_emp_id').html(res);
//                }else{
//                    showError(res); 
//                    return false;
//                }
//            }else{
//                return false;
//            }
//        }
//    });
//}
