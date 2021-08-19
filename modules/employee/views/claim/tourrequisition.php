<?php
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
$this->title="Tour Requisition";

$projectlist = ArrayHelper::map($projectlist, 'id', 'project');
//$centerlist = Yii::$app->hr_utility->get_center_list();
//$centerlist = ArrayHelper::map($centerlist, 'id', 'centername');
$tourtype = ArrayHelper::map($tourtype, 'id', 'tourtype');
$tourlocation = ArrayHelper::map($tourlocation, 'id', 'cityname');

?>
<div class="col-sm-12 text-right">
    <u><a href="<?=Yii::$app->homeUrl?>employee/claim/viewtourrequisition?securekey=<?=$menuid?>" >View All Tour Requisition</a></u><br>
    <u><a href="#drafttour" >View Draft Tour Requisition</a></u>
</div>
<?php $form = ActiveForm::begin(['id'=>'entryslipform']); ?>
<div class="row">
    <div class="col-sm-3">
        <label>For Center</label>
        <input type="text" readonly="" value="Mohali" class="form-control form-control-sm" />
    </div>
    <div class="col-sm-3">
        <label>Department</label>
        <input type="text" readonly="" value="<?=Yii::$app->user->identity->dept_name?>" class="form-control form-control-sm" />
    </div>
</div>
<br>
<input type="hidden" name="HrTourRequisition[req_id]" value="<?=$model->req_id?>" readonly="" />
<div class="row">
    <div class="col-sm-3"><?= $form->field($model, 'project_id')->dropDownList($projectlist, ['prompt'=>'Select Project', 'class'=>'form-control form-control-sm',]); ?></div>
    <div class="col-sm-3"><?= $form->field($model, 'tour_type')->dropDownList($tourtype, ['prompt'=>'Select Tour Type', 'class'=>'form-control form-control-sm',]); ?></div>
    <div class="col-sm-3"><?= $form->field($model, 'tour_location')->dropDownList($tourlocation, ['prompt'=>'Select Tour Location', 'class'=>'form-control form-control-sm',]); ?></div>
</div>
<br>
<div class="row">
    <div class="col-sm-3"><?= $form->field($model, 'advance_required')->radioList(array('Y'=>'Yes','N'=>'No')); ?></div>
    <div class="col-sm-3"><?= $form->field($model, 'advance_amount')->textInput([ 'placeholder'=>'Advance Amount', 'class'=>'form-control form-control-sm',]) ?></div>
    <div class="col-sm-3"><?= $form->field($model, 'start_date')->textInput(['placeholder'=>'Start Date', 'class'=>'form-control form-control-sm','readonly'=>true]) ?></div>
    <div class="col-sm-3"><?= $form->field($model, 'end_date')->textInput(['placeholder'=>'End Date', 'class'=>'form-control form-control-sm','readonly'=>true]) ?></div>
</div>
<div class="row">
    <div class="col-sm-12"><?= $form->field($model, 'purpose')->textArea(['placeholder'=>'Purpose', 'class'=>'form-control form-control-sm']) ?></div>
</div>
<div class="row">
    <div class="col-sm-12 text-center">
        <button type="submit" name="HrTourRequisition[Draft]" value="Draft" class="btn btn-outline-dark btn-sm checkform sl">Save as Draft</button>
        <button type="submit" name="HrTourRequisition[Submit]" value="Submit" class="btn btn-outline-success btn-sm checkform sl">Submit</button>
    </div>
</div>
<?php ActiveForm::end(); ?>
<div id="drafttour" class="row">
    <div class="col-sm-12">
        <hr>
        <h6><b><i>Draft Requisitions</i></b></h6>
        <table id="dataTableShow" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Tour Location</th>
                    <th>Dept</th>
                    <th>Project</th>
                    <th>Type</th>
                    <th>Advance <br>Required</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
                <tbody>
                    <?php 
                    //echo "<pre>";print_r($lists);
                    if(!empty($lists)){
                        $i =1;
                        foreach($lists as $list){
                        $req_id = Yii::$app->utility->encryptString($list['req_id']); 
                        $editUrl = Yii::$app->homeUrl."employee/claim/tourrequisition?securekey=$menuid&req_id=$req_id";
                    ?>
                    <tr>
                        <td><?=$i?></td>
                        <td><?=date('d-M-y', strtotime($list['start_date']))?></td>
                        <td><?=date('d-M-y', strtotime($list['end_date']))?></td>
                        <td><?=$list['city_name']?></td>
                        <td><?=$list['dept_name']?></td>
                        <td><?=$list['project_name']?></td>
                        <td><?=$list['tour_type']?></td>
                        <td>Rs. <?=$list['advance_amount']?></td>
                        <td><?=$list['status']?></td>
                        <td><u><a href="<?=$editUrl?>">Edit</a></u></td>
                    </tr>	
                    <?php $i++;	
                        }
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>No.</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Tour Location</th>
                        <th>Dept</th>
                        <th>Project</th>
                        <th>Type</th>
                        <th>Advance</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </tfoot>
        </table>
    </div>
</div>