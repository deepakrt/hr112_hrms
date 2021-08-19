  <!------ Include the above in your HEAD tag ---------->
<?php
 use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
 //echo "<pre>";print_r($data);die;
?>
<style>
legend {
	background-color: #c5dec5;color: #3F9E89;padding: 0px 6px;font-family: initial;font-size: 21px;
}
</style>
 
			<?php $form = ActiveForm::begin();?>
	 <fieldset>
        <legend>Voucher No: <?=$data['voucher_no']?> </legend>
<div class='row'>						
 	 
	<div class="form-group col-sm-4">
		<input type='hidden' name='id' id='id' value='<?=$data['id']?>'>
		<input type='hidden' name='vno' id='vno' value='<?=$data['voucher_no']?>'>
		<label class="control-label">fname: </label>
		<span class="control-label"><?=$data['fname']?></span>
	</div>
	<div class="form-group col-sm-4">
		<label class="control-label">Dept Name: </label>
		<span class="control-label"><?=$data['dept_name']?></span>
	</div>
	<div class="form-group col-sm-4">
		<label class="control-label">Request Date: </label>
		<span class="control-label"><?=date('d-M-Y',strtotime($data['request_date']));?></span>
	</div>
	<div class="form-group col-sm-4">
		<label class="control-label">Item Name: </label>
		<span class="control-label"><?=$data['item_name']?></span>
	</div>
	<div class="form-group col-sm-12">
		<label class="control-label">Item Specification: </label>
		<span class="control-label"><?=$data['item_specification']?></span>
	</div>
	<div class="form-group col-sm-4">
		<label class="control-label">quantity_required: </label>
		<span class="control-label"><?=$data['quantity_required']?></span>
	</div>
	<div class="form-group col-sm-4">
		<label class="control-label">Approx Cost: </label>
		<span class="control-label"><?=$data['approx_cost']?></span>
	</div>
	<div class="form-group col-sm-4">
		<label class="control-label">Item Purpose: </label>
		<span class="control-label"><?=$data['item_purpose']?></span>
	</div>
	<div class="form-group col-sm-4">
		<label class="control-label">Description: </label>
		<span class="control-label"><?=$data['remarks']?></span>
	</div>
	<div class="form-group col-sm-12">
		<label class="control-label"> <input required type='checkbox' name='funds_available'></label>
		<span class="control-label" style="font-size: 18px;margin: 8px;">Funds are available in <b> <?=$data['project']?></b> Project</span>
		
	</div>
	<div class="form-group col-sm-10">
		<label class="control-label">Project Head: </label>
		<span class="control-label"><input type='text' required name='project_head' class='form-control' value=''></span>
	</div>
	
	 
	 
	  
	<div class="form-group col-sm-4">
		<label class="control-label"> Remarks: </label>
		<span class="control-label"><textarea   style="background:#fff;" required rows='3' name='rreason' id='rreason'></textarea></span>
	</div>
	
	
	<div class="form-group col-sm-12">
		<label style="margin-top:25px">
        	<?= Html::submitButton('Submit', ['class' => 'btn btn-success']) ?>
			<?= \yii\helpers\Html::a( 'Back', Yii::$app->request->referrer, ['class' => 'btn btn-info']); ?>
		</label>
    </div> 	 

	</div>  </fieldset>
  <?php ActiveForm::end(); ?>
<script>
	
$(function(){
 		$('#ysno').on('click', function (e) {
				$("#cmember, #rreason").val('');
			 if($('#ysno').is(":checked")){
				$("#cmember, #rreason").attr('disabled',false);$("#rreason").focus();
			 	$("#cmember, #rreason").css("background", "");
			 }else{
			 	$("#cmember, #rreason").attr('disabled',true);
			 	$("#cmember, #rreason").css("background", "#ccc");
			}
		});
	
		$('#qty_accepted').on('keyup blur change', function (e) {
				var qtyr=$('#QtyR').val();
				var qtys=$('#qty_accepted').val();
				if(qtyr=='' || qtyr<0){qtyr=0;$('#QtyR').val(0);}
				if(qtys=='' || qtys<=0){qtys=1;$('#qty_accepted').val(1);}
				qtyr=parseInt(qtyr);
				qtys=parseInt(qtys);
				if(qtyr < qtys){alert('Accepted Qty. cannot be greater than Qty. Received');$('#qty_accepted').val(qtyr);return false;}
				var qtyr=eval(qtyr)-eval(qtys);
				if(qtyr<0){qtyr=0;}
 				$('#QtyS').val(qtyr);

			 });
});			 
			 
			 
</script>
 
 
                   
               
