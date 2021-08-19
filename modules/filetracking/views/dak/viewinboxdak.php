<?php
$this->title="Inbox : View Inbox Dak Details";
use yii\widgets\ActiveForm;
//echo "<pre>";print_r($dakDetails); die; 
$fileDownload = Yii::$app->homeUrl.$dakDetails['docs_path'];
?> 
<style>
    .sentlist{
        text-align: justify;
        height: 50px;
        overflow: auto;
        border: 1px solid #ED865D;
        padding: 5px;
        font-size: 12px;
    }
    h6{
        font-size: 13px;
    }
</style>
<input type="hidden" value="<?=Yii::$app->utility->encryptString($dakDetails['dak_id'])?>" id="dak_id" />
<input type="hidden" value="<?=$menuid?>" id="menuid" />
<div class="row">
    <div class="col-sm-6">
        <p><b>Ticket No. : </b><?=$dakDetails['ticket_number']?></p>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-sm-12">
        <p style="text-align: justify"><b>Subject : </b><?=$dakDetails['subject']?></p>
    </div>
</div>
<hr>
<div class="row">
    <?php 
    if(!empty($dakDetails['send_to_group'])){ 
        if($dakDetails['is_hierarchical'] == 'Y'){
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
    </div>
        
    <?php }
    }elseif(!empty($dakDetails['send_to_emp'])){
        $empD = Yii::$app->utility->get_employees($dakDetails['send_to_emp']);
        $fullname = $empD['fullname'].", ".$empD['desg_name'];
        $dept_name = $empD['dept_name']; ?>
        
    <div class="col-sm-3">
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
    <?php }elseif(empty($dakDetails['send_to_emp'] AND empty($dakDetails['send_to_group']))){
        $empList = Yii::$app->fts_utility->fts_dak_sent_emplist($dakDetails['dak_id']);
        //echo "<pre>";print_r($empList);
        $emaillist="";
        $sentdate = "-";
        if(!empty($empList)){
            
            foreach($empList as $emp){
                $sentdate = date('d-m-Y H:i:s', strtotime($emp['sent_date']));
                $email_id = $emp['email_id'];
                $emp_name = trim($emp['emp_name']);
                $emaillist .= "&lt;$email_id&gt; $emp_name, ";
            }
        } ?>
    <div class="col-sm-12">
        <div class="text-right">
            <h6><b>Sent Date: </b><?=$sentdate?></h6>
        </div>
        <h6><b>Sent To:</b></h6>
        <p class="sentlist"><?=$emaillist?></p>
    </div>
    <?php }
    ?>
</div>
<?php 
if($dakDetails['access_level'] == 'W'){
?>
<hr>
<?php 
$form = ActiveForm::begin(['action'=>Yii::$app->homeUrl."filetracking/dak/addnote?securekey=$menuid", 'id'=>'draftdakform', 'options' => ['enctype' => 'multipart/form-data']]); ?>
<input type="hidden" value="<?=Yii::$app->utility->encryptString($dakDetails['dak_id'])?>" name="Note[dak_id]" />
<input type="hidden" value="<?=Yii::$app->utility->encryptString("I")?>" name="Note[viewtype]" />
<div class="row">
    <div class="col-sm-12">
        <div class="alert alert-danger">
            <b>Note:</b> Once you submit the note, you cannot edit or delete.
        </div>
    </div>
    <div class="col-sm-12">
        <label>Add New Note</label>
        <textarea class="form-control form-control-sm" placeholder="Add Note" oninvalid="this.setCustomValidity('Note cannot empty')" rows="7" name="Note[newnote]" required=""></textarea>
    </div>
    <div class="col-sm-3">
        <label>Document Type</label>
        <select class="form-control form-control-sm" name="Note[docu_type]">
            <option data-key="1" value="<?=Yii::$app->utility->encryptString('PDF')?>">PDF</option>
        </select>
    </div>
    <div class="col-sm-6">
        <label>Attachment (If any)</label>
        <input type="file" class="form-control form-control-sm" name="notefile" accept=".pdf" />
        <span style="color: red;font-size: 12px;">File size cannot be more then <?=FTS_Doc_Size?> MB</span>
    </div>
    <div class="col-sm-3">
        <br>
        <input type="submit" class="btn btn-primary btn-sm sl" value="Submit"/>
    </div>
</div>
<?php ActiveForm::end(); ?>
<hr>
<div class="row">
    <div class="col-sm-12 text-right">
        <a href="javascript:void(0)" id="clickviewnote" class="btn btn-secondary btn-sm">View Previous Notes</a>
        <a target="_blank" href="<?=$fileDownload?>" class="btn btn-success btn-sm">Download File</a>
        <a target="_blank" href="<?=Yii::$app->homeUrl?>filetracking/dak/downloaddak?securekey=<?=$menuid?>&dak_id=<?=Yii::$app->utility->encryptString($dakDetails['dak_id'])?>" title="Download File with Notes" class="btn btn-success btn-sm">Download File with Notes</a>
    </div>
</div>
<?php 
}else{ ?>
<hr>
<div class="row">
    <div class="col-sm-12 text-right">
        <a target="_blank" href="<?=$fileDownload?>" class="btn btn-success btn-sm">Download File</a>
    </div>
</div>    
<?php }
?>

<hr>
<div class="row">
    <div class="col-sm-9">
        <label>Dispatch No.</label>
        <input type="text" class="form-control form-control-sm" value="<?=$dakDetails['despatch_num']?>" readonly="" />
    </div>
    <div class="col-sm-3">
        <label>Dispatch Date</label>
        <input type="text" class="form-control form-control-sm" value="<?=date('d-m-Y', strtotime($dakDetails['despatch_date']))?>" readonly="" />
    </div>
</div>
<hr>
<div class="row">
    <div class="col-sm-9" style="margin-bottom: 10px;">
        <label>File Reference No.</label>
        <input type="text" class="form-control form-control-sm" value="<?=$dakDetails['file_refrence_no']?>" readonly="" />
    </div>
    <div class="col-sm-3" style="margin-bottom: 10px;">
        <label>Dated</label>
        <input type="text" class="form-control form-control-sm" value="<?=date('d-m-Y', strtotime($dakDetails['file_date']))?>" readonly="" />
    </div>
    <hr>
    <div class="col-sm-3">
        <p><b>Category : </b> <?=$dakDetails['cat_name']?></p>
    </div>
    <div class="col-sm-3">
        <p><b>Access Level : </b> <?php
        if($dakDetails['access_level'] == "R"){
            echo "Read Only";
        }elseif($dakDetails['access_level'] == "W"){
            echo "Read / Write";
        }else{
            echo "-";
        }
        ?></p>
    </div>
    <div class="col-sm-3">
        <p><b>Priority : </b> <?php
        if($dakDetails['priority'] == "Normal"){
            echo "Normal";
        }elseif($dakDetails['priority'] == "Moderate"){
            echo "<span style='color:#ED865D'>Moderate</span>";
        }elseif($dakDetails['priority'] == "High"){
            echo "<span style='color:red'>High</span>";
        }else{
            echo "-";
        }
        ?></p>
    </div>
    <div class="col-sm-3">
        <p><b>Is Confidential? : </b> <?php
        if($dakDetails['is_confidential'] == "N"){
            echo "No";
        }elseif($dakDetails['is_confidential'] == "Y"){
            echo "<span style='color:red;'>Yes</span>";
        }else{
            echo "-";
        }
        ?></p>
    </div>
    <hr>
    <div class="col-sm-12">
        <p style="text-align: justify"><b>Meta Tags or Keywords : </b><?=$dakDetails['meta_keywords']?></p>
    </div>
    <div class="col-sm-12">
        <label>Summary</label>
        <p style="text-align: justify"><?=$dakDetails['summary']?></p>
    </div>
    <div class="col-sm-12">
        <br>
        <label>Remarks</label>
        <p style="text-align: justify"><?=$dakDetails['remarks']?></p>
    </div>
</div>
<div class="modal fade" id="viewnote" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">View Notes</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div id="viewnotehtml"></div>
        </div>
        
    </div>
  </div>
</div>