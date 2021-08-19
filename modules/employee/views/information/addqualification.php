<link href="<?=Yii::$app->homeUrl?>css/bootstrap-datepicker.css" rel="stylesheet">
<script src="<?=Yii::$app->homeUrl?>js/bootstrap-datepicker.js"></script>
<?php
$menuid = "";
if(isset($_GET['securekey']) AND !empty($_GET['securekey'])){
    $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
}
if(empty($menuid)){
    header('Location: '.Yii::$app->homeUrl); 
    exit;
}
$menuid = Yii::$app->utility->encryptString($menuid);
$this->title="Add Qualification";
$quaLists = Yii::$app->hr_utility->get_quali_list(null);
?>
<br>
<form id="qualiForm" method="POST" enctype="multipart/form-data">
    <input type="hidden" id="_csrf" name="_csrf" value="<?= Yii::$app->request->csrfToken; ?>" />
    <div class="col-sm-12">
        <button type="button" value="A" id="quali_academic" class="btn btn-success btn-sm btnxs">Academic</button>
        <button type="button" value="O" id="quali_other" class="btn btn-light btn-sm btnxs">Other</button>
        <input type="hidden" readonly="" id="quali_type" name="Qualification[quali_type]" value="A" />
        <input type="hidden" readonly="" id="menuid" name="Qualification[menuid]" value="<?=$menuid?>" />
    </div>
    <hr>
    <span id="academic_">
        <div class="row">
            <div class="col-sm-3">
                <label>Qualifications</label>
                <select class="form-control form-control-sm" id="quali_id" name="Qualification[quali_id]">
                    <option value="">Select Qualification</option>
                    <?php 
                    if(!empty($quaLists)){
                        foreach($quaLists as $quaList){
                            $id = Yii::$app->utility->encryptString($quaList['qualification_id']);
                            $n = $quaList['qualification_level'];
                            echo "<option value='$id'>$n</option>";
                        }
                    }
                    ?>
                </select> 
            </div>
            <div class="col-sm-3">
                <label>Discipline</label>
                <input type="text" class="form-control form-control-sm" id="discipline" name="Qualification[discipline]" placeholder="Discipline" />
            </div>
            <div class="col-sm-3">
                <label>Institute</label>
                <input type="text" class="form-control form-control-sm" id="institute" name="Qualification[institute]" placeholder="Institute" />
            </div>
            <div class="col-sm-3">
                <label>University / Board</label>
                <input type="text" class="form-control form-control-sm" id="uni_b" name="Qualification[uni_b]" placeholder="University / Board" />
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-sm-9">
                <label>Institute Address</label>
                <textarea class="form-control form-control-sm" id="address" name="Qualification[address]" placeholder="Institute Address"></textarea>
            </div>
            <div class="col-sm-3">
                <label>Passed On</label>
                <input type="text" class="form-control form-control-sm" id="passed_on" name="Qualification[passed_on]" placeholder="DD/MM/YYYY" readonly="" />
            </div>
        </div>
    </span>
    <span id="other_" style="display: none;">
        <div class="row">
            <div class="col-sm-9">
                <label>Qualification</label>
                <textarea class="form-control form-control-sm" id="other_quali" name="Qualification[other_quali]" placeholder="Qualification"></textarea>
            </div>
            <div class="col-sm-3">
                <label>Passed On</label>
                <input type="text" class="form-control form-control-sm" id="otherpassed_on" name="Qualification[otherpassed_on]" placeholder="DD/MM/YYYY" readonly="" />
            </div>
        </div>
    </span>
    <div class="row">
        <div class="col-sm-2">
            <label>Grade</label>
            <input type="text" class="form-control form-control-sm" id="grade" name="Qualification[grade]" placeholder="Grade" />
        </div>
        <div class="col-sm-2">
            <label>Percentage</label>
            <input type="text" class="form-control form-control-sm" id="percentage" name="Qualification[percentage]" placeholder="Percentage" />
        </div>
        <div class="col-sm-2">
            <label>C.G.P.A.</label>
            <input type="text" class="form-control form-control-sm" id="cgpa" name="Qualification[cgpa]" placeholder="C.G.P.A." />
        </div>
        <div class="col-sm-3">
            <label>Document Type</label>
            <select class="form-control form-control-sm" id="doc_type" name="Qualification[doc_type]">
                <option value="">Document Type</option>
                <option value="certificate">Certificate</option>
                <option value="Marksheet">Mark Sheet</option>
            </select>
        </div>
        <div class="col-sm-3">
            <label>Upload Document</label>
            <input type="file" class="form-control form-control-sm certi" accept=".jpeg,.jpg,.png" id="document" name="Qualification[document]"  />
            <label style="color:red;font-size:11px;">File Size should be less then 500KB</label>
        </div>
    </div>
    <div class="col-sm-12 text-center">
        <br>
        <button type="button" id="save_quali" class="btn btn-success btn-sm">Save</button>
        <a href="<?=Yii::$app->homeUrl?>employee/information?securekey=<?=$menuid?>&tab=qualification" class="btn btn-danger btn-sm">Cancel</a>
    </div>
</form>