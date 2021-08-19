<?php
    use yii\widgets\ActiveForm;
    use yii\helpers\ArrayHelper;
    $this->title = "Add Language Details";
    $menuid = "";
    if(isset($_GET['securekey']) AND !empty($_GET['securekey'])){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
    }
    if(empty($menuid)){
        header('Location: '.Yii::$app->homeUrl); 
        exit;
    }
    $menuid = Yii::$app->utility->encryptString($menuid);
    $e_id = Yii::$app->user->identity->e_id;
    $list = array();


    function munishSearchForArray($id, $array) {
       foreach ($array as $key => $val) {
           if ($val['language_id'] === $id) {
               return $val;
           }
       }
       return null;
    }
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
        <a href="<?=Yii::$app->homeUrl?>employee/information?securekey=<?=$menuid?>&tab=language_details" class="btn btn-primary btn-sm"><- Back</a>
    </div>

    <div class="col-sm-12">
        <form id="exp_form" name="exp_form" method="post" enctype="multipart/form-data">
                <input type="hidden" name="employee_code" value="<?=$e_id?>" />
                <table id="table_detail" cellpadding="10" border="1" align="center" style="width:100%;">
                    <tbody>
                        <tr>
                            <th>Select </th>
                            <th>Language</th>
                            <th>Mother-Tongue</th>
                            <th>Can Read</th>
                            <th>Can Write</th>
                            <th>Can Speak</th>
                        </tr>

                        <?php
                            // $id = munishSearchForArray('2', $emp_language_details);


                            // echo "<pre>"; print_r($id); echo "</pre>";
                            // echo "<pre>"; print_r($emp_language_details); echo "</pre>";

                            if(!empty($language_details))
                            {
                                foreach($language_details as $lngData)
                                {
                                    $lngData = (object)$lngData;
                                    $langID = $lngData->id;

                                    $lngchk = '';
                                    $chk_mt = '';
                                    $chk_rf = '';
                                    $chk_wf = '';
                                    $chk_sf = '';

                                    $chk_all_disable = ' disabled="true" ';
                                    

                                    if(!empty($emp_language_details))
                                    {
                                        $langData = munishSearchForArray($langID, $emp_language_details);

                                        if(!empty($langData))
                                        {
                                            $langData = (object)$langData;

                                            $lngchk = ' checked="checked" ';

                                            if($langData->mother_tongue == 'Y')
                                            {   
                                                $chk_mt = ' checked="checked" ';
                                            }

                                            if($langData->read == 'Y')
                                            {   
                                                $chk_rf = ' checked="checked" ';
                                            }

                                            if($langData->write == 'Y')
                                            {   
                                                $chk_wf = ' checked="checked" ';
                                            }

                                            if($langData->speak == 'Y')
                                            {   
                                                $chk_sf = ' checked="checked" ';
                                            }

                                            $chk_all_disable = '';                                        
                                        }
                                    }

                                    ?>
                                    <tr class="row<?=$langID?>">
                                        <td><input type="checkbox" <?=$lngchk;?> name="Employee_language_code[]" id="chk_language_code<?=$langID?>" onclick="enableDisableChks(<?=$langID?>);" value="<?=$langID?>"></td>
                                        <td id="id_language_name_r"><?=$lngData->language_name;?></td>
                                        <td><input type="checkbox" name="chk_mt<?=$langID?>" id="chk_mt<?=$langID?>" <?=$chk_mt;?> value="Y" <?=$chk_all_disable;?> /></td> 
                                        <td><input type="checkbox" name="chk_rf<?=$langID?>" id="chk_rf<?=$langID?>" <?=$chk_rf;?> value="Y" <?=$chk_all_disable;?> /></td> 
                                        <td><input type="checkbox" name="chk_wf<?=$langID?>" id="chk_wf<?=$langID?>" <?=$chk_wf;?> value="Y" <?=$chk_all_disable;?> /></td> 
                                        <td><input type="checkbox" name="chk_sf<?=$langID?>" id="chk_sf<?=$langID?>" <?=$chk_sf;?> value="Y" <?=$chk_all_disable;?> /></td> 
                                    </tr>
                                    
                                    <?php
                                }
                            }
                        ?>
                                    
                        </tbody>
                     </table>
                       
                    </div>
                    
                    <div class="col-sm-12 text-center" id="allbtnscls">
                        <button type="submit" id="" class="btn btn-success btn-sm sl">Save</button>
                        <a href="<?=Yii::$app->homeUrl?>employee/information?securekey=<?=$menuid?>&tab=language_details" class="btn btn-danger btn-sm">Cancel</a>
                     </div>
                </div>
        </form>
    </div>
</div>
<script>

  function enableDisableChks(rwid)
  {
    
    if($('#chk_language_code'+rwid).is(':checked'))
    {
        // alert($('#chk_language_code'+rwid).is(':checked'));
    
        $('#chk_mt'+rwid).prop('disabled',false);
        $('#chk_rf'+rwid).prop('disabled',false);
        $('#chk_wf'+rwid).prop('disabled',false);
        $('#chk_sf'+rwid).prop('disabled',false);
    }
    else
    {
        $('#chk_mt'+rwid).prop('disabled',true);
        $('#chk_rf'+rwid).prop('disabled',true);
        $('#chk_wf'+rwid).prop('disabled',true);
        $('#chk_sf'+rwid).prop('disabled',true);    
    }
  }

  $( function() {
    $( "#employeeexperience-from" ).datepicker();
    $( "#employeeexperience-till" ).datepicker();
  });


  // action="/ersshar/employee/information/addlanguage_known?securekey=$menuid"


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
                url: "<?php echo Yii::$app->homeUrl."employee/information/addlanguage_known?securekey=$menuid";?>",
                type: 'POST',
                data: formData,
                dataType: 'JSON',
                success: function (data) 
                {
                    if(data.data_suc == 1)
                    {
                        $('#exp_form').html('');
                        $('#exp_form').hide();
                        $('#allbtnscls').html('PLease wait....');
                        swal('Done.',data.msg,'success');

                        console.log(data.red_url);
                        setTimeout(function(){ 
                            window.location.assign('<?=Yii::$app->homeUrl?>employee/information?securekey=<?=$menuid?>&tab=language_details');
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

        $.post("<?php // echo Yii::$app->homeUrl."employee/information/addlanguage_known?securekey=$menuid";?>", formData, function(data) {
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