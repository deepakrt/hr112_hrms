<?php
$this->title = "Edit Draft";
use yii\widgets\ActiveForm;
$allDepts= Yii::$app->utility->get_dept(NULL);
$allCategory = Yii::$app->fts_utility->fts_getcategorymaster();
$filepath = Yii::$app->homeUrl.$dakDetails['docs_path'];
//echo "<pre>";print_r($dakDetails); die;
?>
<style>
    .mb15{
        margin-bottom:15px; 
    }
    .processlist li{
        display: inline-block;
        width: 100%;
        line-height: 19px;
        padding-bottom: 10px;
        border-bottom: 1px dotted;
    }
</style>
<input type="hidden" id="menuid" value="<?=$menuid?>" />
<?php $form = ActiveForm::begin(['action'=>Yii::$app->homeUrl."filetracking/dak/savedraft?securekey=$menuid", 'id'=>'draftdakform', 'options' => ['enctype' => 'multipart/form-data']]); ?>
<div class="col-sm-12 text-right">
    <input type="hidden" id="sentdetailchange" name="Editdraft[sentdetailchange]" value="" />
    <button type="button" class="btn btn-danger btn-sm btn-xs" id="changesenttype" value="1">Click to Change Sent Type</button>
</div>
<!--For New Sent Type-->
<div class="row" id="newsenttype" style="display:none;" >
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

<!--Already Saved-->
<div class="row" id="senttypedetails">
    <?php 
    if(!empty($dakDetails['send_to_group'])){ 
        echo '<input type="hidden" name="Editdraft[sent_type]" value="2" readonly="" />';
        echo '<input type="hidden" name="Editdraft[is_hierarchy]" value="'.Yii::$app->utility->encryptString($dakDetails['is_hierarchical']).'" readonly="" />';
        if($dakDetails['is_hierarchical'] == 'Y'){
//            echo "<pre>";print_r($grpProcess); 
    ?>
    
    <div class="col-sm-2">
        <label>Sent Type</label>
        <input type="text" class="form-control form-control-sm" value="Group" readonly="" />
    </div>
    <div class="col-sm-2">
        <label>Is Hierarchical?</label>
        <input type="text" class="form-control form-control-sm" value="Yes" readonly="" />
    </div>
    <div class="col-sm-3">
        <label>Group Name</label>
        <input type="text" class="form-control form-control-sm" value="<?=$grpProcess[0]['group_name']?>" readonly="" />
    </div>
    <div class="col-sm-5">
        <p><b>Hierarchy</b></p>
        <ul class="processlist">
            <?php 
            foreach($grpProcess as $p){
                $role = $p['role'];
                echo "<li>- $role</li>";
            }
            ?>
        </ul>
    </div>
    <?php }elseif($dakDetails['is_hierarchical'] == 'N'){ 
       
        ?>
        <div class="col-sm-2">
        <label>Sent Type</label>
        <input type="text" class="form-control form-control-sm" value="Group" readonly="" />
    </div>
    <div class="col-sm-2">
        <label>Is Hierarchical?</label>
        <input type="text" class="form-control form-control-sm" value="No" readonly="" />
    </div>
    <div class="col-sm-3">
        <label>Group Name</label>
        <input type="text" class="form-control form-control-sm" value="<?=$grpMembers[0]['group_name']?>" readonly="" />
    </div>
    <div class="col-sm-5">
        <p><b>Committee Members</b></p>
        <table class="table table-hover">
            <?php 
            foreach($grpMembers as $m){
                
                echo "<tr>";
                echo "<td>".$m['emp_name'].", ".$m['desg_name']."</td>";
                echo "</tr>";
            }
            ?>
        </table>
<!--        <ul class="processlist">
            <?php 
//            foreach($grpMembers as $m){
//                $role = $p['role'];
//                echo "<li>- $role</li>";
//            }
            ?>
        </ul>-->
    </div>
        
    <?php }
    }elseif(!empty($dakDetails['send_to_emp'])){
        $empD = Yii::$app->utility->get_employees($dakDetails['send_to_emp']);
        $fullname = $empD['fullname'].", ".$empD['desg_name'];
        $dept_name = $empD['dept_name']; ?>
        
    <div class="col-sm-3">
        <input type="hidden" name="Editdraft[sent_type]" value="1" readonly="" />
        <input type="hidden" name="Editdraft[employee_code]" value="<?=Yii::$app->utility->encryptString($empD['employee_code'])?>" readonly="" />

        <label>Sent Type</label>
        <input type="text" class="form-control form-control-sm" value="Individual" readonly="" />
    </div>  
    <div class="col-sm-3">
        <label>Department</label>
        <input type="text" class="form-control form-control-sm" value="<?=$dept_name?>" readonly="" />
    </div>
    <div class="col-sm-4">
        <label>Employee Name</label>
        <input type="text" class="form-control form-control-sm" value="<?=$fullname?>" readonly="" />
    </div>
    <?php }else{
        echo '<input type="hidden" name="Editdraft[sent_type]" value="2" readonly="" />';
    }
    ?>
    
</div>
<input type="hidden" name="Editdraft[dak_id]" value="<?=Yii::$app->utility->encryptString($dakDetails['dak_id'])?>" readonly="" />
<input type="hidden" name="Editdraft[is_hierarchical]" value="<?=Yii::$app->utility->encryptString($dakDetails['is_hierarchical'])?>" readonly="" />
        <input type="hidden" name="Editdraft[send_to_group]" value="<?=Yii::$app->utility->encryptString($dakDetails['send_to_group'])?>" readonly="" />
        <input type="hidden" name="Editdraft[emp_code]" value="<?=Yii::$app->utility->encryptString($dakDetails['send_to_emp'])?>" readonly="" />
<hr>
<h6><b><u>File Information</u></b></h6>
<div class="row">
    <div class="col-sm-8 mb15">
        <label>File Reference No.</label>
        <input type="text" id="file_refrence_no" name="Editdraft[file_refrence_no]" class="form-control form-control-sm" placeholder="Reference No." value="<?=$dakDetails['file_refrence_no']?>" required="" />
    </div>
    <div class="col-sm-4 mb15">
        <label>Dated</label>
        <input type="text" id="file_date" name="Editdraft[file_date]" class="form-control form-control-sm" placeholder="Dated" readonly="" value="<?=date('d-m-Y', strtotime($dakDetails['file_date']))?>" />
    </div>
    <div class="col-sm-12 mb15">
        <label>Subject</label>
        <input type="text" id="subject" value="<?=$dakDetails['subject']?>" name="Editdraft[subject]" class="form-control form-control-sm" placeholder="Subject" required=""/>
    </div>
    <div class="col-sm-3 mb15">
        <label>Category</label>
        <select id="category" name="Editdraft[category]" class="form-control form-control-sm" required="">
            <option value="">Select Category</option>
            <?php 
            if(!empty($allCategory)){
                foreach($allCategory as $all){
                    $selected="";
                    if($dakDetails['category'] == $all['fts_category_id']){
                        $selected="selected='selected'";
                    }
                    $fts_category_id = Yii::$app->utility->encryptString($all['fts_category_id']);
                    $cat_name = $all['cat_name'];
                    echo "<option $selected value='$fts_category_id'>$cat_name</option>";
                }
            }
            ?>
        </select>
    </div>
    <div class="col-sm-3 mb15">
        <label>Access Level</label>
        <?php 
        $read = $write= "";
        if($dakDetails['access_level'] == 'R'){
            $read = "selected='selected'";
        }elseif($dakDetails['access_level'] == 'W'){
            $write = "selected='selected'";
        }
        ?>
        <select id="access_level" name="Editdraft[access_level]" class="form-control form-control-sm" required="">
            <option value="">Select Access Level</option>
            <option <?=$read?> value="<?=Yii::$app->utility->encryptString('R')?>">Read</option>
            <option <?=$write?> value="<?=Yii::$app->utility->encryptString('W')?>">Read / Write</option>
        </select>
    </div>
    <div class="col-sm-3 mb15">
        <label>Priority</label>
         <?php 
        $Normal = $Moderate = $High= "";
        if($dakDetails['priority'] == 'Normal'){
            $Normal = "selected='selected'";
        }elseif($dakDetails['priority'] == 'Moderate'){
            $Moderate = "selected='selected'";
        }elseif($dakDetails['priority'] == 'High'){
            $High = "selected='selected'";
        }
        ?>
        <select id="priority" name="Editdraft[priority]" class="form-control form-control-sm" required="">
            <option <?=$Normal?> value="<?=Yii::$app->utility->encryptString('Normal')?>">Normal</option>
            <option <?=$Moderate?> value="<?=Yii::$app->utility->encryptString('Moderate')?>">Moderate</option>
            <option <?=$High?> value="<?=Yii::$app->utility->encryptString('High')?>">High</option>
        </select>
    </div>
    <div class="col-sm-3 mb15" >
        <label>Is Confidential?</label>
        <?php 
        $yes = $no= "";
        if($dakDetails['is_confidential'] == 'Y'){
            $yes = "selected='selected'";
        }elseif($dakDetails['is_confidential'] == 'N'){
            $no = "selected='selected'";
        }
        ?>
        <select id="is_confidential" name="Editdraft[is_confidential]" class="form-control form-control-sm" required="">
            <option <?=$yes?> value="<?=Yii::$app->utility->encryptString('Y')?>">Yes</option>
            <option <?=$no?> value="<?=Yii::$app->utility->encryptString('N')?>">No</option>
        </select>
    </div>
    <div class="col-sm-12 mb15">
        <label>Meta Tags or Keywords</label>
        <input type="text" id="meta_keywords" value="<?=$dakDetails['meta_keywords']?>" name="Editdraft[meta_keywords]" class="form-control form-control-sm" placeholder="Meta Tags or Keywords" required=""/>
    </div>
    <div class="col-sm-6 mb15">
        <label>Short Summary of File</label>
        <textarea id="summary" name="Editdraft[summary]" class="form-control form-control-sm" placeholder="Short Summary of File" required="" rows="6"><?=$dakDetails['summary']?></textarea>
    </div>
    <div class="col-sm-6 mb15">
        <label>File Remarks (If any)</label>
        <textarea id="remarks" name="Editdraft[remarks]" class="form-control form-control-sm" placeholder="Remarks" required="" rows="6"><?=$dakDetails['remarks']?></textarea>
    </div>
</div>
<h6><b><u>Upload File</u></b></h6>
<div class="row">
    <div class="col-sm-3 mb15">
        <label>File Type</label>
        <select id="doc_type" name="Editdraft[doc_type]" class="form-control form-control-sm" required="">
            <option data-key="1" value="<?=Yii::$app->utility->encryptString('PDF')?>">PDF</option>
        </select>
    </div>
    <div class="col-sm-4 mb15">
        <input type="hidden" id="isdocchange" name="Editdraft[isdocchange]" value="" readonly="" />
        <div id="showfile">
            <a href="<?=$filepath?>" target="_blank"><img src="<?=Yii::$app->homeUrl?>images/pdf_preview.png" width="80" height="80" /></a> <a href="javascript::void(0)" id="changedoc" class="btn btn-danger btn-sm btn-xs">Change Document</a>
            
        </div>
        <div id="browsefile" style="display: none;">
        <label>Browse File</label>
        <input type="file" id="docs_path" name="docs_path" class="form-control form-control-sm fts_pdf" accept=".pdf" required=""/>
        <span style="color: red;font-size: 12px;">File Size Should be less then <?=FTS_Doc_Size?> MB</span>
        </div>
    </div>
</div>
<input type="hidden" name="Editdraft[olddocspath]" value="<?=Yii::$app->utility->encryptString($dakDetails['docs_path'])?>" />
<input type="hidden" name="Editdraft[dak_docs_id]" value="<?=Yii::$app->utility->encryptString($dakDetails['dak_docs_id'])?>" />
<h6><b><u>Dispatch Details</u></b></h6>
<div class="row">
    <div class="col-sm-8 mb15">
        <label>Dispatch Number</label>
        <input type="text" id="despatch_num" value="<?=$dakDetails['despatch_num']?>" name="Editdraft[despatch_num]" class="form-control form-control-sm" placeholder="Despatch Number" required=""/>
    </div>
    <div class="col-sm-4 mb15">
        <label>Dispatch Date</label>
        <?php 
        $disDate = "";
        if(!empty($dakDetails['despatch_date'])){
            $disDate = date('d-m-Y', strtotime($dakDetails['despatch_date']));
        }
        ?>
        <input type="text" id="despatch_date" value="<?=$disDate?>" name="Editdraft[despatch_date]" class="form-control form-control-sm" placeholder="Despatch Date" readonly=""/>
    </div>
    <div class="col-sm-12 text-center mb15">
        <button type="button" class="btn btn-secondary btn-sm draftdaksubmit" data-key="D">Save as Draft</button>
        <button type="button" class="btn btn-success btn-sm draftdaksubmit" data-key="F">Final Submit</button>
        <a href="" class="btn btn-danger btn-sm">Cancel</a>
        
    </div>
</div>
<input type="hidden" name="Editdraft[submit_type]" id="submit_type" readonly="" />
<?php ActiveForm::end(); ?> 


