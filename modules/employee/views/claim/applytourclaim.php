<?php
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
$this->title="Apply Tour Claim";
$btnsubmit = "Submit";
$bkbtn = $startHH = $startMM = $endHH = $endMM = "";
if(!empty($istourHeader)){
    $this->title="Update Tour Claim Header";
    $btnsubmit = "Update";
    $startHH = date('H', strtotime($lists['start_date']));
    $startMM = date('i', strtotime($lists['start_date']));
    $endHH = date('H', strtotime($lists['end_date']));
    $endMM = date('i', strtotime($lists['end_date']));
    $url = Yii::$app->homeUrl."employee/claim/otherdetails?securekey=$menuid&claimid=$claimid&reqid=$reqid";
    $bkbtn = "<a href='$url' title='Go back to claim' class='btn btn-outline-danger btn-sm'>Go Back</a>";
}
$projectlist = ArrayHelper::map($data['projectlist'], 'id', 'project');
$tourtype = ArrayHelper::map($data['tourtype'], 'id', 'tourtype');
$tourlocation = ArrayHelper::map($data['tourlocation'], 'id', 'cityname');
//echo "<pre>";print_r($lists);
?>
<div class="col-sm-12">
    <h6><b><i>Fill Tour Details</i></b></h6><hr>
    <?php $form = ActiveForm::begin(); ?>
    <input type="hidden" name="HrTourRequisition[claim_id]" value="<?=$claimid?>" readonly="" />
    <?php $model->req_id=Yii::$app->utility->encryptString($lists['req_id']);?>
    <?= $form->field($model, 'req_id')->hiddenInput(['placeholder'=>'Start Date', 'class'=>'form-control form-control-sm','readonly'=>true])->label(false) ?>
    <div class="row">
        <div class="col-sm-2"><label>Location</label></div>
        <div class="col-sm-4">
		<?php $model->tour_location=base64_encode($lists['tour_location']);?>
            <?= $form->field($model, 'tour_location')->dropDownList($tourlocation, ['prompt'=>'Select Tour Location', 'class'=>'form-control form-control-sm',])->label(false); ?>
        </div>
        <div class="col-sm-2"><label>Advance</label></div>
        <div class="col-sm-4">
            <?php if(@$lists['advance_required']=='Y'){$r="";}else{$r="readonly";}?>
            <input <?=$r?> value="<?=$lists['advance_amount']?>" class="form-control form-control-sm" type="text">
        </div>
   </div>  
   <div class="row">
        <div class="col-sm-2"><label>Start Date HH24:MI</label></div>
        <div class="col-sm-2">
		<?php $model->start_date=date('d-m-Y',strtotime($lists['start_date']));?>
            <?= $form->field($model, 'start_date')->textInput(['placeholder'=>'Start Date', 'class'=>'form-control form-control-sm','readonly'=>true])->label(false) ?>
        </div> 
        <div class="col-sm-1">    
            <select name='HrTourRequisition[start_hh]' id="start_hh" class='form-control form-control-sm required' required="" style="width:172%;padding:4px;margin: 0px 0px 0px -22px;">
                <option value=''>HH</option>
                <?php for($i=0;$i<=23;$i++){
                    $selected = "";
                    if($startHH == $i){
                        $selected = "selected=''";
                    }
                    ?>
                <option <?=$selected?> value='<?=sprintf("%02d", $i);?>'><?=sprintf("%02d", $i);?></option>
                <?php }?>
            </select>
        </div> 
        <div class="col-sm-1">        
            <select name='HrTourRequisition[start_mi]' id="start_mm" class='form-control form-control-sm required' required="" style="width:172%;padding:4px;margin: 0px 0px 0px -22px;">
                <option value=''>MI</option>
                <?php for($i=0;$i<=59;$i++){ 
                    $selected = "";
                    if($startMM == $i){
                        $selected = "selected=''";
                    }
                    ?>
                <option <?=$selected?> value='<?=sprintf("%02d", $i);?>'><?=sprintf("%02d", $i);?></option>
                    <?php $i=$i+4;}?>
            </select>
        </div>
        <div class="col-sm-2"><label>End Date HH24:MI </label></div>
        <div class="col-sm-2">
            <?php $model->end_date=date('d-m-Y',strtotime($lists['end_date']));?>
            <?= $form->field($model, 'end_date')->textInput(['placeholder'=>'End Date', 'class'=>'form-control form-control-sm datetimepicker','readonly'=>true])->label(false) ?>
            
        </div> 
        <div class="col-sm-1">    
            <select name='HrTourRequisition[end_hh]' id="end_hh" class='form-control form-control-sm required' required="" style="width:172%;padding:4px;margin: 0px 0px 0px -22px;">
                <option value=''>HH</option>
                <?php for($i=0;$i<=23;$i++){ 
                    $selected = "";
                    if($endHH == $i){
                        $selected = "selected=''";
                    }
                    ?>
                <option <?=$selected?> value='<?=sprintf("%02d", $i);?>'><?=sprintf("%02d", $i);?></option>
                <?php }?>
            </select>
        </div> 
        <div class="col-sm-1">        
            <select name='HrTourRequisition[end_mi]' id="end_mi" class='form-control form-control-sm required' required="" style="width:172%;padding:4px;margin: 0px 0px 0px -22px;">
                <option value=''>MI</option>
                <?php for($i=0;$i<=59;$i++){ 
                    $selected = "";
                    if($endMM == $i){
                        $selected = "selected=''";
                    }
                    ?>
                <option <?=$selected?> value='<?=sprintf("%02d", $i);?>'><?=sprintf("%02d", $i);?></option>
                <?php $i=$i+4;}?>
            </select>
        </div>
   </div>  
   <div class="row">
        <div class="col-sm-2"><label>Centre</label></div>
        <div class="col-sm-4">
            <input readonly="" value="Mohali" class="form-control form-control-sm" type="text">
        </div>
        <div class="col-sm-2"><label>Group</label></div>
        <div class="col-sm-4">
            <input readonly="" value="<?=Yii::$app->user->identity->dept_name?>" class="form-control form-control-sm" type="text"><br>
        </div>
    </div>  
   <div class="row">
        <div class="col-sm-2"><label>Project</label></div>
        <div class="col-sm-4">
             <?php $model->project_id=base64_encode($lists['project_id']);?>
           <?php // $form->field($model, 'project_id')->dropDownList($projectlist, ['disabled'=>true, 'prompt'=>'Select Project', 'class'=>'form-control form-control-sm',])->label(false); ?>
            <input type="text" class="form-control form-control-sm" readonly="" value="<?=$lists['project_name']?>" />
            <input type="hidden" class="form-control form-control-sm" name="HrTourRequisition[project_id]" readonly="" value="<?=Yii::$app->utility->encryptString($lists['project_id'])?>" />
        </div>
        <div class="col-sm-2"><label>Tour Type </label></div>
        <div class="col-sm-4">
		<?php $model->tour_type=base64_encode($lists['tour_type']);?>
           <?php // $form->field($model, 'tour_type')->dropDownList($tourtype, ['prompt'=>'Select Tour Type', 'class'=>'form-control form-control-sm',])->label(false); ?>
            <input type="text" class="form-control form-control-sm" readonly="" value="<?=$lists['tour_type']?>" />
            <input type="hidden" class="form-control form-control-sm" name="HrTourRequisition[tour_type]" readonly="" value="<?=Yii::$app->utility->encryptString($lists['tour_type'])?>" />
        </div>
    </div>  
    <div class="row">
        <div class="col-sm-2"><label>Purpose</label></div>
        <div class="col-sm-10">
            <?php $model->purpose=$lists['purpose'];?>
            <?= $form->field($model, 'purpose')->textArea(['placeholder'=>'Purpose', 'class'=>'form-control form-control-sm'])->label(false) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 text-center">
            <button type="submit" name="submit" value="Submit" id="submit_tour_header" class="btn btn-outline-success btn-sm sl"><?=$btnsubmit?></button>
            <?=$bkbtn?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>