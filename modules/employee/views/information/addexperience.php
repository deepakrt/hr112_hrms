<?php
    use yii\widgets\ActiveForm;
    use yii\helpers\ArrayHelper;
    $this->title = "Add Experience";
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
        <a href="<?=Yii::$app->homeUrl?>employee/information?securekey=<?=$menuid?>&tab=experience" class="btn btn-primary btn-sm"><- Back</a>
    </div>

    <div class="col-sm-12">


        <form id="exp_form" name="exp_form" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group field-employeeexperience-e_name required">
                        <label class="control-label" for="employeeexperience-e_name">Employer Name *</label>
                        <input type="text" id="employeeexperience-e_name" class="form-control form-control-sm" name="Employeeexperience[e_name]" maxlength="200" placeholder="Employer Name" aria-required="true">
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group field-employeeexperience-organizationType required">
                        <label class="control-label" for="employeeexperience-organizationType">Employer Type *</label>
                        <select name="Employeeexperience[organizationType]" id="employeeexperience-organizationType" class="form-control form-control-sm" aria-required="true">
                            <option value="Autonomous Body">Autonomous Body</option>
                            <option value="Central Government">Central Government</option>
                            <option value="Multi national Company">Multi national Company</option>
                            <option value="Private">Private</option>
                            <option value="Public Sector Unit">Public Sector Unit</option>
                            <option value="Semi Government">Semi Government</option>
                            <option value="State Government">State Government</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group field-employeeexperience-job_title required">
                        <label class="control-label" for="employeeexperience-job_title">Job Title *</label>
                        <input type="text" id="employeeexperience-job_title" class="form-control form-control-sm" name="Employeeexperience[job_title]" maxlength="200" placeholder="Job Title" aria-required="true">
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group field-employeeexperience-from required">
                        <label class="control-label" for="employeeexperience-from">From *</label>
                        <input type="text" id="employeeexperience-from" class="form-control form-control-sm" name="Employeeexperience[from]" readonly="" placeholder="DD/MM/YYYY" aria-required="true" style="cursor: pointer;" />
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group field-employeeexperience-till required">
                        <label class="control-label" for="employeeexperience-till">To *</label>
                        <input type="text" id="employeeexperience-till" class="form-control form-control-sm" name="Employeeexperience[till]" readonly="" placeholder="DD/MM/YYYY" aria-required="true" style="cursor: pointer;" />
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group field-employeefamilydetails-employer_address has-success">
                        <label class="control-label" for="employeefamilydetails-employer_address">Employer Address *</label>
                        <textarea id="employeefamilydetails-employer_address" class="form-control form-control-sm" name="Employeeexperience[employer_address]" maxlength="255" placeholder="Employer Address" aria-invalid="false"></textarea>

                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group field-employeefamilydetails-job_description has-success">
                        <label class="control-label" for="employeefamilydetails-job_description">Job Description *</label>
                        <textarea id="employeefamilydetails-job_description" class="form-control form-control-sm" name="Employeeexperience[job_description]" maxlength="255" placeholder="Job Description" aria-invalid="false"></textarea>

                    </div>
                </div>

                <div class="col-sm-6"><div class="form-group field-employeeexperience-document_type">
                    <label class="control-label" for="employeeexperience-document_type">Document Type</label>
                    <select id="employeeexperience-document_type" class="form-control form-control-sm" name="Employeeexperience[document_type]">
                        <option value="">Select Document Type</option>
                        <option value="Experience Certificate">Experience Certificate</option>                
                    </select>

                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group field-employeeexperience-document_path">
                        <label class="control-label" for="employeeexperience-document_path">Browse File (Only .pdf allowed)</label>
                        <input type="file" id="employeeexperience-document_path" class="form-control form-control-sm " name="Employeeexperience[document_path]" maxlength="100" placeholder="DD/MM/YYYY" accept=".pdf" onchange="validate_file();">
                    </div>
                </div>



                <div class="col-sm-12 text-center">
                    <button type="submit" id="" class="btn btn-success btn-sm sl">Save</button>
                    <a href="<?=Yii::$app->homeUrl?>employee/information?securekey=<?=$menuid?>&tab=experience" class="btn btn-danger btn-sm">Cancel</a>
                 </div>
            </div>
        </form>
    </div>
</div>
<script>
  $( function() {
    $( "#employeeexperience-from" ).datepicker();
    $( "#employeeexperience-till" ).datepicker();
  });


  // action="/ersshar/employee/information/addexperience?securekey=$menuid"


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
                url: "<?php echo Yii::$app->homeUrl."employee/information/addexperience?securekey=$menuid";?>",
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

                        console.log(data.red_url);
                        setTimeout(function(){ 
                            // window.location.replace(data.red_url);
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

        $.post("<?php // echo Yii::$app->homeUrl."employee/information/addexperience?securekey=$menuid";?>", formData, function(data) {
            alert(data);
        });
    });*/


    function validate_file(){ 
        var file_err = 'file_err';
        var upload_cv = $('#employeeexperience-document_path');
        var file = $('#employeeexperience-document_path')[0].files[0]
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

    $("#employeeexperience-document_pathddfdfdfd").on("change", function(){
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