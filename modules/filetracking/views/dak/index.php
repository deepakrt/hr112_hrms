<?php
$this->title= "Create Dak";
use yii\widgets\ActiveForm;
$allDepts= Yii::$app->utility->get_dept(NULL);
$allCategory = Yii::$app->fts_utility->fts_getcategorymaster();
//echo "<pre>";print_r($allCategory);
?>
<style>
    .mb15{
        margin-bottom:15px; 
    }
</style>
<input type="hidden" id="menuid" value="<?=$menuid?>" />
<?php $form = ActiveForm::begin(['id'=>'dakform', 'options' => ['enctype' => 'multipart/form-data']]); ?>
<div class="row">
    <div class="col-sm-4">
        <label>Sent Type</label>
        <div class="btn-group" role="group" aria-label="Basic example">
            <button type="button" class="btn btn-secondary btn-sm whiteborder" id="individual" onclick="senType(1)">Individual</button>
            <button type="button" class="btn btn-secondary btn-sm whiteborder" id="group" onclick="senType(2)">Group</button>
            <button type="button" class="btn btn-secondary btn-sm whiteborder" id="all_emp" onclick="senType(3)">All Employees</button>
            <input type="hidden" name="Dak[sent_type]" id="sent_type" readonly="" />
        </div>
    </div>
    <div class="col-sm-3 in_list" style="display: none;">
        <label>Select Department</label>
        <select class="form-control form-control-sm" id="dak_dept_id" name="Dak[dept_id]">
            <option value="">Select Department</option>
            <?php 
            if(!empty($allDepts)){
                foreach($allDepts as $d){
                    $dept_id = $d['dept_id'];
                    $dept_name = $d['dept_name'];
                    echo "<option value='$dept_id'>$dept_name</option>";
                }
            }
            ?>
        </select>
    </div>
    <div class="col-sm-3 in_list" style="display: none;">
        <label>Select Employee</label>
        <select class="form-control form-control-sm" id="emp_list" name="Dak[emp_code]">
            <option value="">Select Employee</option>
        </select>
    </div>
    
    <div class="col-sm-2 in_group" style="display: none;">
        <label>Is Hierarchical?</label><br>
        <div class="btn-group" role="group" aria-label="Basic example">
            <button type="button" class="btn btn-secondary btn-sm" id="yes_hierry" onclick="Hierarchy(1)">Yes</button>
            <button type="button" class="btn btn-secondary btn-sm" id="no_hierry" onclick="Hierarchy(2)">No</button>
            <input type="hidden" name="Dak[is_hierarchy]" id="is_hierarchy" readonly="" />
        </div>
    </div>
    <div class="col-sm-2 in_group" style="display: none; padding: 0;">
        <label>Select Group</label>
        <select class="form-control form-control-sm" id="group_id" name="Dak[group_id]">
            <option value="">Select Group</option>
        </select>
    </div>
    <div class="col-sm-4 in_group_list" style="display: none;">
    </div>
</div>
<hr>
<h6><b><u>File Information</u></b></h6>
<div class="row">
    <div class="col-sm-8 mb15">
        <label>File Reference No.</label>
        <input type="text" id="file_refrence_no" name="Dak[file_refrence_no]" class="form-control form-control-sm" placeholder="Reference No." required="" />
    </div>
    <div class="col-sm-4 mb15">
        <label>Dated</label>
        <input type="text" id="file_date" name="Dak[file_date]" class="form-control form-control-sm" placeholder="Dated" readonly="" />
    </div>
    <div class="col-sm-12 mb15">
        <label>Subject</label>
        <input type="text" id="subject" name="Dak[subject]" class="form-control form-control-sm" placeholder="Subject" required=""/>
    </div>
    <div class="col-sm-3 mb15">
        <label>Category</label>
        <select id="category" name="Dak[category]" class="form-control form-control-sm" required="">
            <option value="">Select Category</option>
            <?php 
            if(!empty($allCategory)){
                foreach($allCategory as $all){
                    $fts_category_id = Yii::$app->utility->encryptString($all['fts_category_id']);
                    $cat_name = $all['cat_name'];
                    echo "<option value='$fts_category_id'>$cat_name</option>";
                }
            }
            ?>
        </select>
    </div>
    <div class="col-sm-3 mb15">
        <label>Access Level</label>
        <select id="access_level" name="Dak[access_level]" class="form-control form-control-sm" required="">
            <option value="">Select Access Level</option>
            <option value="<?=Yii::$app->utility->encryptString('W')?>">Read / Write</option>
            <option value="<?=Yii::$app->utility->encryptString('R')?>">Read Only</option>            
        </select>
    </div>
    <div class="col-sm-3 mb15">
        <label>Priority</label>
        <select id="priority" name="Dak[priority]" class="form-control form-control-sm" required="">
            <option value="<?=Yii::$app->utility->encryptString('Normal')?>">Normal</option>
            <option value="<?=Yii::$app->utility->encryptString('Moderate')?>">Moderate</option>
            <option value="<?=Yii::$app->utility->encryptString('High')?>">High</option>
        </select>
    </div>
    <div class="col-sm-3 mb15" >
        <label>Is Confidential?</label>
        <select id="is_confidential" name="Dak[is_confidential]" class="form-control form-control-sm" required="">
            <option value="<?=Yii::$app->utility->encryptString('Y')?>">Yes</option>
            <option selected="" value="<?=Yii::$app->utility->encryptString('N')?>">No</option>
        </select>
    </div>
    <div class="col-sm-12 mb15">
        <label>Meta Tags or Keywords</label>
        <input type="text" id="meta_keywords" name="Dak[meta_keywords]" class="form-control form-control-sm" placeholder="Meta Tags or Keywords" required=""/>
    </div>
    <div class="col-sm-6 mb15">
        <label>Short Summary of File</label>
        <textarea id="summary" name="Dak[summary]" class="form-control form-control-sm" placeholder="Short Summary of File" required="" rows="6"></textarea>
    </div>
    <div class="col-sm-6 mb15">
        <label>File Remarks (If any)</label>
        <textarea id="remarks" name="Dak[remarks]" class="form-control form-control-sm" placeholder="Remarks" required="" rows="6"></textarea>
    </div>
</div>
<input type="hidden" name="Dak[submit_type]" id="submit_type" readonly="" />
<h6><b><u>Upload File</u></b></h6>
<div class="row">
    <div class="col-sm-3 mb15">
        <label>File Type</label>
        <select id="doc_type" name="Dak[doc_type]" class="form-control form-control-sm" required="">
            <option data-key="1" value="<?=Yii::$app->utility->encryptString('PDF')?>">PDF</option>
        </select>
    </div>
    <div class="col-sm-4 mb15">
        <label>Browse File</label>
        <input type="file" id="docs_path" name="docs_path" class="form-control form-control-sm fts_pdf" accept=".pdf" required=""/>
        <span style="color: red;font-size: 12px;">File size cannot be more then <?=FTS_Doc_Size?> MB</span>
    </div>
</div>
<h6><b><u>Dispatch Details</u></b></h6>
<div class="row">
    <div class="col-sm-8 mb15">
        <label>Dispatch Number</label>
        <input type="text" id="despatch_num" name="Dak[despatch_num]" class="form-control form-control-sm" placeholder="Despatch Number" required=""/>
    </div>
    <div class="col-sm-4 mb15">
        <label>Dispatch Date</label>
        <input type="text" id="despatch_date" name="Dak[despatch_date]" class="form-control form-control-sm" placeholder="Despatch Date" readonly=""/>
    </div>
    <div class="col-sm-12 text-center mb15">
        <button type="button" class="btn btn-secondary btn-sm daksubmit" data-key="D">Save as Draft</button>
        <button type="button" class="btn btn-success btn-sm daksubmit" data-key="F">Final Submit</button>
        <a href="" class="btn btn-danger btn-sm">Cancel</a>
        
    </div>
</div>

<?php ActiveForm::end(); ?>