<?php
$this->title= 'Upload Excel';
$menuid = "";
if(isset($_GET['securekey']) AND !empty($_GET['securekey'])){
    $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
}

$menuid = Yii::$app->utility->encryptString($menuid);
$editUrl = Yii::$app->homeUrl."admin/reward/updatereward?securekey=$menuid";


?>
<style>
    label{ font-weight:bold; font-size: 15px;}
    .con {
    	font-size: 15px;
    }  
    .col-sm-3{margin-bottom: 10px;}
</style>

<div id="exTab1" class="exTab1">	
    <div class="tab-content clearfix">
        <div  id="info">
            <form name="file_upload" id="file_upload" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-sm-4">
                        <label>Upload </label>
                        <p class="con"><input type="file" name="rendom_file" id="rendom_file" /></p>
                    </div>
                </div>
                <hr>
                <input type="submit" name="submit" class="btn btn-success submitBtn" value='Upload' />
                <div class="statusMsg"></div>
            </form>
        </div>

    </div>
</div>

<script type="text/javascript">
    /*function uploadFile()
    {
        $.ajax({
            url: "<?php // echo Yii::$app->homeUrl."admin/upload/uploadData?securekey=$menuid";?>",
            type: 'POST',
            dataType: 'JSON',
            success: function (data) {
                console.log(data);
            }
        });
    }*/


     $("#file_upload").on('submit', function(e){
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: "<?=Yii::$app->homeUrl."admin/upload/uploaddata?securekey=$menuid";?>",
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(){
                /*$('.submitBtn').attr("disabled","disabled");
                $('#file_upload').css("opacity",".5");*/
            },
            success: function(response){ 
                console.log(response);
                $('.statusMsg').html('');
                if(response.sts == 1){
                    $('#file_upload')[0].reset();
                    $('.statusMsg').html('<p class="alert alert-success">'+response.message+'</p>');
                }else{
                    $('.statusMsg').html('<p class="alert alert-danger">'+response.message+'</p>');
                }
                $('#file_upload').css("opacity","");
                $(".submitBtn").removeAttr("disabled");
            }
        });
    });
</script>