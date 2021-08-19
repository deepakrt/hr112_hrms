<?php
    use yii\widgets\ActiveForm;
    use yii\helpers\ArrayHelper;
    $this->title = "Add Training Attended Details";
    $menuid = "";
    if(isset($_GET['securekey']) AND !empty($_GET['securekey'])){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
    }
    if(empty($menuid)){
        header('Location: '.Yii::$app->homeUrl); 
        exit;
    }
    $menuid = Yii::$app->utility->encryptString($menuid);
    $list = array();
?>
<style>
    .col-sm-12.text-right {
        position: absolute;
        top: 2px;
        right: 5px;
    }
</style>

<div class="row">
    <div class="col-sm-12 text-right" >
        <a href="<?=Yii::$app->homeUrl?>employee/information?securekey=<?=$menuid?>&tab=training_det" class="btn btn-primary btn-sm"><- Back</a>
        </a>
    </div>

    <div class="col-sm-12">
        <form id="exp_form" name="exp_form" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group field-employeetraining_details-course_name required">
                        <label class="control-label" for="employeetraining_details-course_name">Course Name *</label>
                        <input type="text" id="employeetraining_details-course_name" class="form-control form-control-sm" name="Employeetraining_details[course_name]" maxlength="200" placeholder="Course Name" aria-required="true">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group field-employeetraining_details-institute_name required">
                        <label class="control-label" for="employeetraining_details-institute_name">Institute Name *</label>
                        <input type="text" id="employeetraining_details-institute_name" class="form-control form-control-sm" name="Employeetraining_details[institute_name]" maxlength="200" placeholder="Institute Name" aria-required="true">
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group field-employeefamilydetails-institute_address has-success">
                        <label class="control-label" for="employeefamilydetails-institute_address">Institute Address *</label>
                        <textarea id="employeefamilydetails-institute_address" class="form-control form-control-sm" name="Employeetraining_details[institute_address]" maxlength="255" placeholder="Institute Address" aria-invalid="false"></textarea>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group field-employeetraining_details-training_attended required">
                        <label class="control-label" for="employeetraining_details-training_attended">Training Attended *</label>
                        <select name="Employeetraining_details[training_attended]" id="employeetraining_details-training_attended" class="form-control form-control-sm" aria-required="true">
                            <option value="Before Joining">Before Joining</option>                    
                            <option value="After Joining">After Joining</option>                    
                        </select>
                    </div>
                </div>
                <!-- <div class="col-sm-3">
                    <div class="form-group field-employeetraining_details-job_title required">
                        <label class="control-label" for="employeetraining_details-job_title">Job Title *</label>
                        <input type="text" id="employeetraining_details-job_title" class="form-control form-control-sm" name="Employeetraining_details[job_title]" maxlength="200" placeholder="Job Title" aria-required="true">
                    </div>
                </div> -->

                <div class="col-sm-6">
                    <div class="form-group field-employeetraining_details-from required">
                        <label class="control-label" for="employeetraining_details-from">From *</label>
                        <input type="text" id="employeetraining_details-from" class="form-control form-control-sm" name="Employeetraining_details[from]" readonly="" placeholder="DD/MM/YYYY" aria-required="true" style="cursor: pointer;" />
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group field-employeetraining_details-to required">
                        <label class="control-label" for="employeetraining_details-to">To *</label>
                        <input type="text" id="employeetraining_details-to" class="form-control form-control-sm" name="Employeetraining_details[to]" readonly="" placeholder="DD/MM/YYYY" aria-required="true" style="cursor: pointer;" />
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="form-group field-employeefamilydetails-description has-success">
                        <label class="control-label" for="employeefamilydetails-description">Description</label>
                        <textarea id="employeefamilydetails-description" class="form-control form-control-sm" name="Employeetraining_details[description]" maxlength="255" placeholder="Description" aria-invalid="false"></textarea>

                    </div>
                </div>

                <div class="col-sm-6"><div class="form-group field-employeetraining_details-document_type">
                    <label class="control-label" for="employeetraining_details-document_type">Document Type</label>
                    <select id="employeetraining_details-document_type" class="form-control form-control-sm" name="Employeetraining_details[document_type]">
                        <option value="">Select Document Type</option>
                        <option value="Training Certificate">Training Certificate</option>                
                        <option value="Other">Other</option>                
                    </select>

                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group field-employeetraining_details-document_path">
                        <label class="control-label" for="employeetraining_details-document_path">Browse File (Only .pdf allowed)</label>
                        <input type="file" id="employeetraining_details-document_path" class="form-control form-control-sm " name="Employeetraining_details[document_path]" maxlength="100" placeholder="DD/MM/YYYY" accept=".pdf" onchange="validate_file();">
                    </div>
                </div>



                <div class="col-sm-12 text-center">
                    <button type="submit" id="" class="btn btn-success btn-sm sl">Save</button>
                    <a href="<?=Yii::$app->homeUrl?>employee/information?securekey=<?=$menuid?>&tab=training_det" class="btn btn-danger btn-sm">Cancel</a>
                 </div>
            </div>
        </form>
    </div>
</div>
<script>
  $( function() {
    $( "#employeetraining_details-from" ).datepicker();
    $( "#employeetraining_details-to" ).datepicker();
  });


    $("#exp_form").submit(function(e) {
        // actionHideContent();

         e.preventDefault(); // avoid to execute the actual submit of the form.

        // var form = $(this);
        // var url = form.attr('action');

        e.preventDefault();    
        var formData = new FormData(this);


        var formd = $('#exp_form');

            // startLoader();
             $.ajax({
                url: "<?php echo Yii::$app->homeUrl."employee/information/addtraining_details?securekey=$menuid";?>",
                type: 'POST',
                data: formData,
                dataType: 'JSON',
                success: function (data) 
                {
                    if(data.data_suc == 1)
                    {
                        $('#exp_form').html('');
                        $('#exp_form').hide();
                        swal('Done.',data.msg,'success');

                        // console.log('============'+data.red_url);
                        setTimeout(function(){ 
                            // window.location.replace(data.red_url);
                            // window.location.assign(data.red_url);
                            window.location.assign('<?=Yii::$app->homeUrl?>employee/information?securekey=<?=$menuid?>&tab=training_det');
                        }, 3000);                        
                    }
                    else
                    {
                        swal('Warning!',data.msg,'error');
                    }
                },
                cache: false,
                contentType: false,
                processData: false
            });
        
    });


   /* $("form#exp_form").submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);    

        $.post("<?php // echo Yii::$app->homeUrl."employee/information/addtraining_details?securekey=$menuid";?>", formData, function(data) {
            alert(data);
        });
    });*/


    function validate_file(){ 
        var file_err = 'file_err';
        var upload_cv = $('#employeetraining_details-document_path');
        var file = $('#employeetraining_details-document_path')[0].files[0]
        //hide previous error
        $("#"+file_err).html("");
        
        if(file == undefined){
            upload_cv.parent().after('<span id='+file_err+'><p class="text-danger"><i class="fa fa-times" aria-hidden="true"></i> Please upload Exp. Cert.(.pdf) File</p></span>');
            return false;
        }else{
              $("#"+file_err).html("");
        }
        console.log(file.size);
        var fileType    = file.type; // holds the file types
        var match       = ["application/pdf","application/vnd.openxmlformats-officedocument.wordprocessingml.document"]; // defined the file types
        var fileSize    = file.size; // holds the file size
        var maxSize     = 2*1024*1024; // defined the file max size

         // Checking the Valid Image file types  
        if(!((fileType==match[0]) || (fileType==match[1])))
        {
            upload_cv.val("");
            upload_cv.parent().after('<span id='+file_err+'><p class="text-danger"><i class="fa fa-times" aria-hidden="true"></i> Please select a valid (.pdf) file.</p></span>');
            return false;
        }else{
              $("#"+file_err).html("");
        }
         // Checking the defined image size
        if(fileSize > maxSize)
        {
            upload_cv.val("");
            upload_cv.parent().after('<span id='+file_err+'><p class="text-danger"><i class="fa fa-times" aria-hidden="true"></i> Please select a file less than 2mb of size.</p></span>');
            return false;
        }else{
            $("#"+file_err).html("");
        }
    }

    $("#employeetraining_details-document_pathddfdfdfd").on("change", function(){
        // hideError();
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

    function checkfilesizeofpdf(id) 
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
        
        if(ext == "application/pdf")
        {
        }
        else
        {
            chkext = false;
        }
        
        if(!chkext)
        {
            $("#"+id).val("");
            showError("Only .pdf files are allowed");
            return false;
        }
    }
</script>