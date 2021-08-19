<script>
//    $(document).ready(function(){
//        $("#clientContact :input").prop("disabled", true);
//        $("#ccn").prop("disabled", false);
//        $("#ccp").prop("disabled", false);
//        $("#cce").prop("disabled", false);
//        $("#addc").prop("disabled", false);
//        $("#manpowermapping-manpowerid").prop("disabled", false);
//        $("#manpowermapping-salary").prop("disabled", false);
//        $("#manpowermapping-sactionpost").prop("disabled", false);
//        $("#addm").prop("disabled", false);
//    });
    
    $(document).ready(function () {
        $('body').on('beforeSubmit', 'form#clientContact', function () {
            
            if($("#manpowermapping-manpowerid").val()!=='' && $("#manpowermapping-salary").val() == ''){
                alert("Fill salary Percentage!"); 
                return false;
            }
            
            if($("#manpowermapping-manpowerid").val()!=='' && $("#manpowermapping-sactionpost").val() == ''){
                alert("Fill Sanctioned Post!"); 
                return false;
            }            
        });
    });
</script>
  
<?php $this->title = "Add Project Information";?>

<?php  
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\EfileMasterCategory;
use app\models\efile_master_project;

$url = Yii::$app->homeUrl."manageproject/ordermaster/addclientcontact";
?>
<link href="<?=Yii::$app->homeUrl?>css/select2.min.css" rel="stylesheet" />
<script src="<?=Yii::$app->homeUrl?>js/select2.min.js"></script>
<input type="hidden" id="menuid" value="<?=$menuid?>" readonly="" />

<style>
.row.addmorerow {
	width: 90%;
	margin-top: 5px;
}
.cost_category {
	padding: 0 !important;
}
</style>
	 
<?php if(isset($_GET['view'])) {?>

<?php }

$Prid = Yii::$app->utility->encryptString($model->id); ?>

<?php $form = ActiveForm::begin(['action'=>'', 'id'=>'clientContact', 'options' => ['enctype' => 'multipart/form-data']]); ?>

<style>
    .col-sm-12{
        margin-bottom: 10px;
    }
</style>

<?php
    $depts = Yii::$app->utility->get_dept(NULL);
    $depts = ArrayHelper::map($depts, 'dept_id', 'dept_name');
    $manager_emp_id = "";
    $pr_cats = Yii::$app->projects->get_pr_cat();
    $Prid = Yii::$app->utility->encryptString($_GET['key']);    
    $cid = Yii::$app->utility->encryptString($_GET['key']);    
    $add_cbd = Yii::$app->homeUrl."manageproject/ordermaster/addclientcontact?securekey=$menuid&key=$Prid&key1=$cid";    
?>

<input type='hidden' name='key' value='<?=$menuid?>' readonly />
<input type='hidden' name='key1' value='<?=$Prid?>' readonly />
<input type='hidden' name='key2' value='<?=$cid?>' readonly />
<input type="hidden" id="enterpcb" name="ClientContact[enterpcb]" readonly value="">
<style>
legend {
	background-color: #c5dec5;color: #3F9E89;padding: 0px 6px;font-family: initial;font-size: 21px;
}
</style>
<fieldset>
    <legend>Project Information</legend>
    <div class="col-sm-12 form-control">
        <div class="row">
            <div class="col-sm-12">
                <b>Project Name:</b> <?=$model->projectname;?>
            </div>
            <div class="col-sm-4">
                <b>Start Date:</b> <?=date('d-m-Y',strtotime($model->orderdate));?>
            </div>
            <div class="col-sm-4">
                <b>Completion Date:</b> <?=date('d-m-Y',strtotime($modelp->end_date ));?>
            </div>
            <div class="col-sm-4">
                <b>Cost:</b> <?=$model->amount;?>        
            </div>
        </div>
    </div>
</fieldset>

<fieldset>
    <legend>Add Client Contact</legend>
    <div class="col-sm-12 form-control">  
        <div class="row">
            <div class="col-sm-3">
                <?= $form->field($modelc, 'name')->textInput(['id'=>'ccn', 'placeholder'=>$modelp->getAttributeLabel('name'), 'maxlength' => true])->label(FALSE) ?>
            </div>
            <div class="col-sm-3">
                <?= $form->field($modelc, 'phone')->textInput(['id'=>'ccp', 'placeholder'=>$modelp->getAttributeLabel('phone'), 'maxlength' => true])->label(FALSE) ?>
            </div>    
            <div class="col-sm-3">
                <?= $form->field($modelc, 'email')->textInput(['id'=>'cce', 'placeholder'=>$modelp->getAttributeLabel('email'), 'maxlength' => true])->label(FALSE) ?>
            </div>
            <div class="col-sm-3">        
                <button type="submit" name="Submit" value="Submit" id="addc" class="primary btn-info">Add</button>
            </div> 
            <?php if(!empty($modelcc)){?>
                <div class="col-sm-12">
                    <hr>
                    <table id="dataTableShow2" class="display table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Contact Person</th>
                                <th>Contact Number</th>
                                <th>E-Mail</th>                        
                            </tr>
                        </thead>
                            <tbody>
                                <?php     
                                $lists='';

                                    $i =1;
                                    foreach($modelcc as $p){   ?>
                                            <tr>
                                                <td><?=$i?></td>
                                                <td><?=$p['name']?></td>
                                                <td><?=$p['phone']?></td>
                                                <td><?=$p['email']?></td>
                                            </tr>
                                        <?php $i++; 
                                    }

                                ?>
                            </tbody>

                    </table>
                </div>
            <?php }?>
        </div>
    </div>
</fieldset>

<fieldset>
    <legend>Map Manpower with Project</legend>
    <div class="col-sm-12 form-control">  
        <div class="row">
            <!--<div class="col-sm-4"><?//= $form->field($modelm, 'deleted')->dropDownList($depts, ['prompt' => 'Select Department', 'class'=>'js-example-basic-multiple form-control form-control-sm', 'title'=>'Select Department'])->label('Select Department') ?></div>
            <div class="col-sm-4">
                <?//=$form->field($modelm,'manpowerid')->dropDownList(['' => '--Select--'],['class'=>'js-example-basic-multiple form-control form-control-sm'])->label('Employee')?>
            </div>-->
            <div class="col-sm-3">    
                <?php $allEmps = Yii::$app->utility->get_employees();?>
                <?= $form->field($modelm, 'manpowerid')->DropDownList(ArrayHelper::map($allEmps,'employee_code','fullname', 'dept_name'),
                                ['prompt'=>'Please select', 'class'=>'form-control form-control-sm'])->label(FALSE)?>  
            </div>
            <div class="col-sm-3">
                <?= $form->field($modelm, 'salary')->textInput(['maxlength' => true, 'placeholder'=>$modelm->getAttributeLabel('salary'), 'class'=>'form-control form-control-sm'])->label(FALSE) ?>
            </div>
            <div class="col-sm-3">
                <?= $form->field($modelm, 'sactionpost')->textInput(['maxlength' => true, 'placeholder'=>$modelm->getAttributeLabel('sactionpost'), 'class'=>'form-control form-control-sm'])->label(FALSE) ?>
            </div>
            <div class="col-sm-3">        
                <button type="submit" name="Submit" value="Submit" id="addm" class="primary btn-info">Add</button>
            </div> 
            <?php if(!empty($modelmm)){   ?>
                <div class="col-sm-12">
                    <h6><b><u>Manpower Mapping</u></b></h6>
                </div>
                <div class="col-sm-12">
                    <hr>
                    <table id="dataTableShow2" class="display table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Employee</th>                        
                                <th>Sectioned Post</th>
                                <th>Salary</th>
                            </tr>
                        </thead>
                            <tbody>
                                <?php     
                                $lists='';

                                    $i =1;
                                    foreach($modelmm as $p){  
                                        $emp = Yii::$app->utility->get_employees($p['manpowerid']);
                                        if($emp['fullname']!=NULL){?>
                                            <tr>
                                                <td><?=$i?></td>
                                                <td><?=$emp['fullname']?></td>                                        
                                                <td><?=$p['sactionpost']?></td>
                                                <td><?=$p['salary']?> %</td>
                                            </tr>
                                        <?php $i++; 
                                        }
                                    }

                                ?>
                            </tbody>

                    </table>
                </div>
            <?php } ?>
        </div>
    </div>
</fieldset>

<fieldset>
    <legend>Capital Breakup</legend>
    <div class="col-sm-12 form-control">
	<label class="control-label" for="project-Cost_Breakdown" style="color: #000;">Project Expenditure Breakdown (Optional) </label>
        <?php 
	$project_id=$modelp->orderid;
	$added_by = Yii::$app->user->identity->e_id;
	$pr_cats = Yii::$app->projects->get_pr_cat();
	$allres = Yii::$app->projects->get_pur_fund($project_id, $added_by);
        
	?>
	<?php if(!empty($allres)){
            foreach($allres as $k=>$res){ ?>
            <div class="row" style="width: 90%;">
                <div class="col-sm-3">
                    <input type="text" disabled class="form-control form-control-sm" title="Project Fund" value="<?= date('d-m-Y', strtotime($res['date_from']));?>">
                </div>
                <div class="col-sm-3">
                    <input type="text" disabled class="form-control form-control-sm" title="Project Fund" value="<?= date('d-m-Y', strtotime($res['date_to']));?>">
                </div>
                <div class="col-sm-3">
                    <select disabled class="form-control form-control-sm cost_category" name="" id="">
                        <?php foreach($pr_cats as $cat){
                                $selected='';
                                if($cat['id']==$res['fund_category']){$selected="selected='selected'";}
                                ?> 
                            <option <?=$selected;?> value="<?=$cat['id']?>"><?=$cat['name']?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-sm-3">
                    <input type="text" disabled class="form-control form-control-sm" title="Project Fund" value="<?=$res['amount'];?>">
                </div><span id='ssssss'>
                <?php 
                $curdateTime=strtotime(date('Y-m-d H:i:s'));
                $endTime = strtotime("+1 day", strtotime($res['added_date']));

                if($curdateTime < $endTime){ ?>
                    <button type="button" id="<?=$res['id_pf']?>" class="btn btn-danger add_btn1_p6" onclick="removethis(this)" style="margin: 2px -21px 0 -6px;float: right;height: 25px;padding: 0 4px 0 5px;">X</button> <?php } ?></span>
            </div>
	<?php }        
        } ?>
        <div class="gggg">
            <div class="row0 row addmorerow" style="width: 90%;">
                <div class="col-sm-3">
                    <input type="text" id="project_start_date" class="adddata form-control form-control-sm project_start_date" name="Project[start_date]" readonly="" title="Start Date" placeholder="Start Date" autocomplete="off" style="cursor: pointer;">
                </div>
                <div class="col-sm-3">
                    <input type="text" id="project_end_date" class="adddata form-control form-control-sm project_end_date" name="Project[end_date]" readonly="" title="End Date" placeholder="End Date" autocomplete="off" style="cursor: pointer;">
                </div>
                <div class="col-sm-3">
                    <select class="adddata form-control form-control-sm cost_category" name="Project[cost_category]" id="cost_category">
                        <option value="">Select Category</option>
                         <?php foreach($pr_cats as $cat){ ?> 
                        <option value="<?=$cat['id']?>"><?=$cat['name']?></option>
                       <?php } ?>
                    </select>
                </div>
                <div class="col-sm-3">
                    <input type="text" id="project_fund" class="adddata form-control form-control-sm project_fund" name="Project[project_fund]" title="Amount" placeholder="Amount" autocomplete="off">
                </div>
                <span id='ssssss0'></span>
            </div>
	</div>
	<input style="float: right;margin: -30px 39px 7px;cursor: pointer;" type="button" id="addmore" class="primary btn-info" value="Add +">
    </div>
</fieldset>    
<?php ActiveForm::end(); ?>    

<link href="<?=Yii::$app->homeUrl?>css/bootstrap-datepicker.css" rel="stylesheet">
<script src="<?=Yii::$app->homeUrl?>js/bootstrap-datepicker.js"></script>
<script>
var menuid='<?=$menuid?>';
var _csrf = $('#_csrf').val();

<?php /*/if(!empty($model->deleted)){ ?>
        //var dept_id='<?//=$model->deleted?>';
	//var emp_code= '<?//=$model->manpowerid';*/?>
	var pstart_date= '<?=date('d-m-Y',strtotime($model->orderdate))?>';
	var pend_date= '<?=date('d-m-Y',strtotime($modelp->end_date ))?>';
	$('#project_start_date').val(pstart_date);
 	  // getdeptemp(dept_id,emp_code);
<?php //}  ?> 
function removethis(dis){
	var id=$(dis).attr('id');
 	if(!confirm('Are You sure to delete?')){return false;}
	$.ajax({
		url:BASEURL+'manageproject/projects/del_project_cat?securekey='+menuid,
		type:'POST',
		data:{id:id,_csrf:_csrf},
		datatype:'json',
		success:function(data){
			if(data==1){
				$(dis).parent().parent().fadeOut(300).remove();
			}
		}
	  });
}
$(function(){
 $("#addmore").click(function () {
     
 	var project_id='<?=Yii::$app->utility->encryptString($model->id);?>';
	var start_date=$('#project_start_date').val();
        var end_date=$('#project_end_date').val();
        var pc_cat=$('#cost_category').val();
        var pc_cat_text=$('#cost_category :selected').text();
        var amount=$('#project_fund').val();
		 
		if(start_date==''){
			showError("Please select Project Start Date");return false;
		}
		if(end_date==''){
			showError("Please select Project End Date");return false;
		}
		if(pc_cat==''){
			showError("Please select Category");return false;
		}
		if(amount==''){
			showError("Please enter Project Fund");return false;
		}
		var intRegex = /^\d+$/;
		var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;

		var str = $('#myTextBox').val();
		if(intRegex.test(amount) || floatRegex.test(amount)) {
		   
		}else{
			alert('Invalid Amount');
			return false;
			}
    
	$.ajax({
		url:BASEURL+'manageproject/ordermaster/add_project_cat?securekey='+menuid,
		type:'POST',
		data:{project_id:project_id,start_date:start_date,end_date:end_date,pc_cat:pc_cat,amount:amount,_csrf:_csrf},
		datatype:'json',
		success:function(data){
			if(data=='Invalid Request'){
				showError(data); 
				return false;
			}else{
 				html='<div class="row" style="width: 90%;"><div class="col-sm-3"><input type="text" disabled class="form-control form-control-sm" value="'+start_date+'"></div><div class="col-sm-3"><input type="text" disabled class="form-control form-control-sm"  value="'+end_date+'"></div><div class="col-sm-3"><select disabled class="form-control form-control-sm cost_category"><option value="'+pc_cat+'">'+pc_cat_text+'</option></select></div><div class="col-sm-3"><input type="text" disabled class="form-control form-control-sm" value="'+amount+'"></div><span id="ssssss"><button type="button" id="'+data+'" class="btn btn-danger add_btn1_p6" onclick="removethis(this)" style="margin: 2px -21px 0 -6px;float: right;height: 25px;padding: 0 4px 0 5px;">X</button></span></div>';
				$( html ).insertBefore( ".gggg" );
				$('#project_start_date').val(pstart_date);
				$('#project_end_date').val('');
				$('#cost_category').val('');
				$('#project_fund').val('');
   	 
	 }
		}
	  });
	 
  }); 
  });
 </script>