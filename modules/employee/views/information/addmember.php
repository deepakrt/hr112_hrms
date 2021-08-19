<?php
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
$this->title = "Add Member";
$menuid = "";
if(isset($_GET['securekey']) AND !empty($_GET['securekey'])){
    $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
}
if(empty($menuid)){
    header('Location: '.Yii::$app->homeUrl); 
    exit;
}
$menuid = Yii::$app->utility->encryptString($menuid);
$relations = Yii::$app->hr_utility->get_relations();
$list = array();
if(!empty($relations)){
    $i=0;
    foreach($relations as $relation){
        $id = Yii::$app->utility->encryptString($relation['relation_id']);
        $list[$i]['relation_id']=$id;
        $list[$i]['relation_name']=$relation['relation_name'];
        $i++;
    }
}
$relations = ArrayHelper::map($list, 'relation_id', 'relation_name');
$handi_type = Yii::$app->hr_utility->get_handicate_type();
$handi_type = ArrayHelper::map($handi_type, 'id', 'type');
$document_type = Yii::$app->hr_utility->get_document_type();
$document_type = ArrayHelper::map($document_type, 'id', 'type');
$marital_status = Yii::$app->hr_utility->get_marital_status();
$marital_status = ArrayHelper::map($marital_status, 'id', 'type');
?>

<div class="col-sm-12 text-right">
</div>

<style>
    .col-sm-12.text-right {
        position: absolute;
        top: 2px;
        right: 5px;
    }
</style>

<div class="row">
    <div class="col-sm-12 text-right" >
        <a href="<?=Yii::$app->homeUrl?>employee/information?securekey=<?=$menuid?>&tab=family" class="btn btn-primary btn-sm"><- Back</a>
    </div>

    <div class="col-sm-12">

        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
            <div class="row">
                <div class="col-sm-3"><?= $form->field($model, 'm_name')->textInput(['placeholder'=>'Member Name', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>
                <div class="col-sm-3"><?= $form->field($model, 'relation_id')->dropDownList($relations, ['prompt'=>'Select Relation', 'class'=>'form-control form-control-sm']); ?></div>
                <div class="col-sm-3"><?= $form->field($model, 'marital_status')->dropDownList($marital_status, ['prompt'=>'Select Marital Status', 'class'=>'form-control form-control-sm']); ?></div>
                <div class="col-sm-3"><?= $form->field($model, 'm_dob')->textInput(['placeholder'=>'DD/MM/YYYY', 'class'=>'form-control form-control-sm', 'maxlength' => true, 'readonly'=>true]) ?></div>
                <div class="col-sm-3"><?= $form->field($model, 'handicap')->dropDownList(['N' => 'No','Y' => 'Yes', ], [ 'class'=>'form-control form-control-sm']); ?></div>
                <div class="col-sm-3"><?= $form->field($model, 'handicate_type')->dropDownList($handi_type, ['prompt'=>'Select Handicap Type', 'class'=>'form-control form-control-sm', 'disabled'=>true]); ?></div>
                <div class="col-sm-3"><?= $form->field($model, 'handicap_percentage')->textInput(['placeholder'=>'Handicap %age', 'class'=>'form-control form-control-sm', 'maxlength' => 3, 'disabled'=>true ]) ?></div>
                <div class="col-sm-3"><?= $form->field($model, 'monthly_income')->textInput(['placeholder'=>'Monthly Income', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>

                <div class="col-sm-6">
                    <div class="form-group field-employeefamilydetails-contact_detail has-success">
                        <label class="control-label" for="employeefamilydetails-contact_detail">Contact Detail</label>
                        <input type="text" id="employeefamilydetails-contact_detail" class="form-control form-control-sm" name="EmployeeFamilyDetails[contact_detail]" placeholder="Contact Detail" aria-invalid="false">

                        <div class="help-block"></div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group field-employeefamilydetails-nominee required">
                        <label class="control-label" for="employeefamilydetails-nominee">Nominees</label>
                        <select id="employeefamilydetails-nominee" class="form-control form-control-sm" name="EmployeeFamilyDetails[nominee]" aria-required="true">
                            <option value="">Select Nominee</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>                
                        </select>

                        <div class="help-block"></div>
                    </div>
                </div>

                <div class="col-sm-6"><div class="form-group field-employeefamilydetails-address has-success">
                    <label class="control-label" for="employeefamilydetails-address">Permanent Address</label>
                    <textarea id="employeefamilydetails-address" class="form-control form-control-sm" name="EmployeeFamilyDetails[address]" maxlength="255" placeholder="Address" aria-invalid="false"></textarea>

                    <div class="help-block"></div>
                </div></div>
                <div class="col-sm-6">
                    <div class="form-group field-employeefamilydetails-p_address has-success">
                        <label class="control-label" for="employeefamilydetails-p_address">Correspond address</label>
                        <textarea id="employeefamilydetails-p_address" class="form-control form-control-sm" name="EmployeeFamilyDetails[p_address]" maxlength="255" placeholder="Address" aria-invalid="false"></textarea>
                        <div class="help-block"></div>
                    </div>
                </div>

                <!-- <div class="col-sm-6"> -->
                        <?php //  $form->field($model, 'address')->textArea(['placeholder'=>'Address', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?>
                <!-- </div> -->
                <!-- <div class="col-sm-6"><?php // $form->field($model, 'p_address')->textArea(['placeholder'=>'Address', 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div> -->

                <div class="col-sm-6"></div>
                <div class="col-sm-6"><input type="checkbox" id="sameasaddress" style="margin-bottom: 15px; " /> Same as home address</div>
                 <div class="col-sm-6"><?= $form->field($model, 'document_type')->dropDownList($document_type, ['prompt'=>'Select Document Type', 'class'=>'form-control form-control-sm']); ?></div>
                 <div class="col-sm-6"><?= $form->field($model, 'document_path')->fileInput(['placeholder'=>'DD/MM/YYYY','accept'=>'.jpg,.jpeg,.png', 'class'=>'form-control form-control-sm certi', 'maxlength' => true]) ?></div>
                 <div class="col-sm-12 text-center">
                    <button type="submit" id="" class="btn btn-success btn-sm sl">Save</button>
                    <a href="<?=Yii::$app->homeUrl?>employee/information?securekey=<?=$menuid?>&tab=family" class="btn btn-danger btn-sm">Cancel</a>
                 </div>
            </div>
        <?php ActiveForm::end(); ?>

    </div>
</div>
