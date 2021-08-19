<?php
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\EfileMasterCategory;
use app\models\efile_master_project;
$category = EfileMasterCategory::find()->where(['is_active' => "Y"])->asArray()->all();
$catlist = array();

if(!empty($category)){
	$listt = array();
	$i=0;
	foreach($category as $c){
		$file_category_id = Yii::$app->utility->encryptString($c['file_category_id']);
		$listt[$i]['file_category_id'] = $file_category_id;
		$listt[$i]['name'] = $c['name'];
		$i++;
	}
	$catlist = ArrayHelper::map($listt, 'file_category_id', 'name');
}
$efile_action_type = efile_action_type;
// echo "<pre>";print_r(Yii::$app->user->identity);die;
$actionList = array();
$i=0;
foreach($efile_action_type as $a){
	$actionid = Yii::$app->utility->encryptString($a);
	$actionList[$i]['actionid'] = $actionid;
	$actionList[$i]['name'] = $a;
	$i++;
}
$actionlist = ArrayHelper::map($actionList, 'actionid', 'name');
$access_level = Yii::$app->fts_utility->get_efile_access_level("G", "");
$access_level = ArrayHelper::map($access_level, 'id', 'name');
$priority = Yii::$app->fts_utility->get_efile_priority("G", "");
$priority = ArrayHelper::map($priority, 'id', 'name');
$is_confidential = Yii::$app->fts_utility->get_efile_check_yes_no("G", "");
$is_confidential = ArrayHelper::map($is_confidential, 'id', 'name');
// echo "<pre>";print_r($recieveddak);
$url = Yii::$app->homeUrl."efile/dakcommon/filemovement";

$rec_id = NULL;
if(!empty($recieveddak)){
	$rec_id = Yii::$app->utility->encryptString($recieveddak['rec_id']);
}
$form = ActiveForm::begin(['action'=>$url, 'id'=>'dakform', 'options' => ['enctype' => 'multipart/form-data']]); ?>
<h6><b><u>File Information</u></b></h6>
<input type='hidden' name='key' value='<?=$menuid?>' readonly />

<input type='hidden' name='rec_id' value='<?=$rec_id?>' readonly />

<div class="row">
	<div class="col-sm-8"><?= $form->field($model, 'reference_num')->textInput([ 'placeholder'=>$model->getAttributeLabel('reference_num'), 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>
	<div class="col-sm-4"><?= $form->field($model, 'reference_date')->textInput(['readonly'=>true, 'placeholder'=>$model->getAttributeLabel('reference_date'), 'class'=>'form-control form-control-sm', 'maxlength' => true, 'value'=>date('d-m-Y')]) ?></div>
	<div class="col-sm-12"><?= $form->field($model, 'subject')->textInput(['placeholder'=>$model->getAttributeLabel('subject'), 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>
	
	<div class="col-sm-6"><?= $form->field($model, 'file_category_id')->dropDownList($catlist, ['class'=>'form-control form-control-sm', 'prompt' => 'Select File Category']) ?></div>
	
	<div class="col-sm-6" id='project_list' style='display:none;'>
            <?= $form->field($model, 'file_project_id')->dropDownList([], ['class'=>'form-control form-control-sm', 'prompt' => 'Select Project']) ?>
<!--		<div class='row'>
			<div class='col-sm-10'>
				<?php //$form->field($model, 'file_project_id')->dropDownList([], ['class'=>'form-control form-control-sm', 'prompt' => 'Select Project']) ?>
			</div>
			<div class='col-sm-2'>
			<div class='text-right'><br><button type='button' class='btn btn-success btn-xs' data-toggle="modal" data-target="#addProjectModal"><img src="<?=Yii::$app->homeUrl?>images/details_open.png"/></button></div>
			</div>
		</div>-->
        
    </div>
	<div class="col-sm-6">
        <?= $form->field($model, 'action_type')->dropDownList($actionlist, ['class'=>'form-control form-control-sm', 'prompt' => 'Select Action']) ?>
    </div>
	<div class="col-sm-6">
        <?= $form->field($model, 'access_level')->dropDownList($access_level, ['class'=>'form-control form-control-sm', 'prompt' => 'Select Access Level']) ?>
    </div>
	<div class="col-sm-6">
        <?= $form->field($model, 'priority')->dropDownList($priority, ['class'=>'form-control form-control-sm', 'prompt' => 'Select Priority']) ?>
    </div>
	<div class="col-sm-6">
        <?= $form->field($model, 'is_confidential')->dropDownList($is_confidential, ['class'=>'form-control form-control-sm', 'prompt' => 'Select Is Confidential?']) ?>
    </div>
	<div class="col-sm-12"><?= $form->field($model, 'meta_keywords')->textInput([ 'placeholder'=>$model->getAttributeLabel('meta_keywords'), 'class'=>'form-control form-control-sm', 'maxlength' => true]) ?></div>
	<div class="col-sm-6"><?= $form->field($model, 'summary')->textarea([ 'placeholder'=>$model->getAttributeLabel('summary'), 'class'=>'form-control form-control-sm', 'rows'=>'6', 'maxlength' => true]) ?></div>
    <div class="col-sm-6"><?= $form->field($model, 'remarks')->textarea([ 'placeholder'=>$model->getAttributeLabel('remarks'), 'class'=>'form-control form-control-sm', 'rows'=>'6', 'maxlength' => true]) ?></div>
    
    
</div>

<div class="row">
	<div class="col-sm-12">
		<h6><b><u>Upload File</u></b></h6>
	</div>
    <div class="col-sm-6 mb15">
        <label>Upload File Type</label>
        <select id="efile_doc_type" name="EfileDak[doc_type]" class="form-control form-control-sm" required="">
            <option data-key="1" value="<?=Yii::$app->utility->encryptString('PDF')?>">PDF</option>
            <option data-key="2" value="<?=Yii::$app->utility->encryptString('Image')?>">Image</option>
        </select>
    </div>
    <div class="col-sm-6">
        <label>Browse File</label>
        <span id='pdf_file_html'>
            <input type="file" id="pdf_docs_path" name="pdf_path" class="form-control form-control-sm fts_pdf" accept=".pdf"/>
            <span style="color: red;font-size: 12px;">File size cannot be more then <?=FTS_Doc_Size?> MB</span>
        </span>
        <span id='image_file_html' style='display:none;'>
            <input type="file" id="fts_image_multiple" name="image_path[]" class="form-control form-control-sm fts_image_multiple" accept=".jpg,.png, .jpeg" multiple  />
            <span style="color: red;font-size: 12px;">You can select multiple Images.<br>Each image size cannot be more then <?=FTS_Image_Size?> MB</span>
        </span>
    </div>
</div>

<?php 
	// if(Yii::$app->user->identity->role == '7' OR Yii::$app->user->identity->role == '19'){ 
	if(empty($recieveddak)){
		echo \Yii::$app->view->renderFile(Yii::getAlias('@app') . '/modules/efile/forwardto_html.php', ['file_id'=>'', 'movement'=>'', 'menuid'=>$menuid]);
	?>
	
	<?php }else{?>
	<div class='row'>
		<div class="col-sm-12 text-center">
		<br>
			<input type='hidden' name='forward_dak' id='forward_dak' value='N' readonly />
			<button type="submit" class="btn btn-success btn-sm" >Submit</button>
			<a href="<?=Yii::$app->homeUrl?>efile/receiveddak?securekey=<?=$menuid?>" class="btn btn-danger btn-sm">Back</a>
		</div>
	</div>
	<?php }
	?>

<?php ActiveForm::end(); ?>

<div class="modal fade" id="addProjectModal"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Add New Project</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class='row'>
					<div class='col-sm-12'>
						<label>Project Name</label>
						<input type='text' placeholder='Enter Project Name' class='form-control form-control-sm' id='project_name' />
					</div>
					<div class='col-sm-12'>
						<br>
						<button type='button' class='btn btn-success btn-sm' onclick="savenewproject()">Submit</button>
						<a href='' class='btn btn-danger btn-sm'>Cancel</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>