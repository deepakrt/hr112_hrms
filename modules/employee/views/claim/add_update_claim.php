<?php
use yii\widgets\ActiveForm;
if(empty($id)){
    $this->title = "Start New Claim";
}else{
    $this->title = "Update Claim ";
}

?>
<?php $form = ActiveForm::begin(['id'=>'contingencyform']); ?>
<input type="hidden" readonly="" name="Contingency[id]" value="<?=$id?>" />
<div class="row">
    <div class="col-sm-3">
        <label>Enter Claim Amount</label>
        <input type="text" class="form-control form-control-sm" name="Contingency[amount]" id="claimamunt" onkeypress="return allowOnlyNumber(event)" placeholder="Claim Amount" value="<?=$claimed_amt?>" maxlength="4" />
    </div>
    <div class="col-sm-3">
        <label>Select Project</label>
        <select class="form-control form-control-sm" name="Contingency[project]" id="project">
            <option value="">N.A.</option>
            <?php 
            if(!empty($projectlist)){
                foreach($projectlist as $project){
                    $id = $project['id'];
                    $selected="";
                    if($project_id == $id){
                        $selected="selected=selected";
                    }
                    $n = $project['project'];
                    echo "<option $selected value='$id'>$n</option>";
                }
            }
            ?>
        </select>
        <br>
    </div>
    
    <div class="col-sm-6">
        <label>Purpose</label>
        <textarea class="form-control form-control-sm" name="Contingency[purpose]" placeholder="Purpose" id="purpose"><?=$purpose?></textarea>
    </div>
    <div class="col-sm-12">
        <label>Claim Details</label>
        <textarea class="form-control form-control-sm" name="Contingency[details]" placeholder="Claim Details" id="details"><?=$details?></textarea>
    </div>
    <input type="hidden" readonly="" name="Contingency[submit_type]" id="submit_type" />
    <div class="col-sm-12 text-center">
        <br>
        <button type="button" class="btn btn-primary btn-sm" onclick="con_claim_submit('1')">Save</button>
        <button type="button" class="btn btn-success btn-sm" onclick="con_claim_submit('2')">Submit</button>
        <a href="<?=Yii::$app->homeUrl?>employee/claim/contingencyclaim?securekey=<?=$menuid?>" class="btn btn-danger btn-sm">Cancel</a>
    </div>
</div>
<?php ActiveForm::end(); ?>

