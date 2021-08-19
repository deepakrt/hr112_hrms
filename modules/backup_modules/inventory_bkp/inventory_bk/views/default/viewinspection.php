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
        <legend>MRN No: <?=$data['MRN_No']?> </legend>
<div class='row'>						
 	 
	<div class="form-group col-sm-3">
		<input type='hidden' name='rno' id='rno' value='<?=$data['Accessid']?>'>
		<input type='hidden' name='MRN_No' id='mrno' value='<?=$data['MRN_No']?>'>
		<label class="control-label">Group: </label>
		<span class="control-label"><?=$data['CLASSIFICATION_NAME']?></span>
	</div>
	<div class="form-group col-sm-3">
		<label class="control-label">Category: </label>
		<span class="control-label"><?=$data['ITEM_CAT_NAME']?></span>
	</div>
	<div class="form-group col-sm-3">
		<label class="control-label">Item (Units): </label>
		<span class="control-label"><?=$data['item_name']?></span>
	</div>
	<div class="form-group col-sm-3">
		<label class="control-label">Item Type: </label>
		<span class="control-label"><?=$data['Item_type']?></span>
	</div>
	<div class="form-group col-sm-3">
		<label class="control-label">Bill No: </label>
		<span class="control-label"><?=$data['PO_no']?></span>
	</div>
	<div class="form-group col-sm-3">
		<label class="control-label">Indent No: </label>
		<span class="control-label"><?=$data['Indent_no']?></span>
	</div>
	<div class="form-group col-sm-4">
		<label class="control-label">Employee: </label>
		<span class="control-label"><?=$data['employee']?></span>
	</div>
	 
	<div class="form-group col-sm-3">
		<label class="control-label">Qty Received: </label>
		<span class="control-label"><input type='text' readonly id='QtyR' value='<?=$data['QtyR']?>'></span>
	</div>
	<div class="form-group col-sm-3">
		<label class="control-label"> Qty. Accepted: </label>
		<span class="control-label"><input type='number' name='qty_accepted' required id='qty_accepted' value='<?=$data['QtyR']?>'></span>
	</div>
	<div class="form-group col-sm-3">
		<label class="control-label">Qty. Rejected: </label>
		<span class="control-label"><input type='text' readonly id='QtyS' name="QtyRej" value='0'></span>
	</div>
	
	<div class="form-group col-sm-12">
		<label class="control-label">Description: </label>
		<span class="control-label"><?=$data['Description']?></span>
	</div>
	<div class="form-group col-sm-3">
		<label class="control-label">Inspection Date: </label>
		<span class="control-label"><input type='date' name='insp_date' required value='<?=date('Y-m-d')?>'></span>
	</div>
	 <div class="form-group col-sm-12">
		<label class="control-label">Inspected by Commitee: </label>
		<span class="control-label"><input type='checkbox' value="Yes" id='ysno' name="ysno"></span>
	</div>
	 <div class="form-group col-sm-4">
		<label class="control-label">Committee Member: </label>
		<span class="control-label"><textarea disabled style="background:#ccc;" required rows='3' name='cmember' id='cmember'></textarea></span>
	</div>
	<div class="form-group col-sm-4">
		<label class="control-label"> Remarks: </label>
		<span class="control-label"><textarea disabled style="background:#ccc;" required rows='3' name='rreason' id='rreason'></textarea></span>
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
 
 
                   
               
