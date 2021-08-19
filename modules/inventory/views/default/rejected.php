  <!------ Include the above in your HEAD tag ---------->
<?php
 use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
// echo "<pre>";print_r(Yii::$app->user->identity);die;
?>
<style>
legend {
	background-color: #c5dec5;color: #3F9E89;padding: 0px 6px;font-family: initial;font-size: 21px;
}
</style>
 
	<?php $form = ActiveForm::begin(['method'=>'GET']);?>
 		<fieldset>
	 		<?php if(empty($rm_no)){?>
	 			<legend>No Data Found..</legend>
		 	<?php }else{ ?>
        <legend>Pending MRN No.</legend>
				<div class='row'>						
				 	<div class="form-group col-sm-4 required">
					  <label class="control-label">MRN No</label>
					   <select id="MRN_No" class="form-control form-control-sm" name="MRN_No">
							<option value="">-- Select --</option>
							<?php  foreach($rm_no as $rm){ 
								$sel='';if($rm['MRN_No']==@$_GET['MRN_No']){$sel='selected';}
								echo '<option '.$sel.' value="'.$rm['MRN_No'].'">'.$rm['MRN_No'].'</option>';
							 }  ?>
						</select>
					</div>
					<div class="form-group col-sm-4">
						<label style="margin-top:25px">
				        	<?= Html::submitButton('Get Detail', ['class' => 'btn btn-success']) ?>
				        	
						</label>
				  </div> 	 

				</div> 
		</fieldset>
	<?php ActiveForm::end(); ?>
 
<fieldset>
  <legend> Inspection Details </legend>

  <?php 
	 if(!empty($mrn_data)){
	 	?>
	 		<div class="text-align-right">
 				<a class="btn btn-primary" style="float: right;padding: 6px;margin: 0 10px 0 20px;" href="<?=Yii::$app->homeUrl."".$mrn_data['invoice_file'];?>" target="_blank">View Invoice</a>
 			</div>
	 	<?php
	 }
	?>
				
		<div class="row" style="width:100%;">
		<div class="form-group col-sm-12">
			<table class="table table-bordered">
				      <tbody><tr>
				          <th>#</th>
				          <th>MRN No</th>
				          <th>Receipt Date</th>
				          <th>PO No</th>
				          <th>Indent No</th>
				          <th>Emp Name</th>
				          <th>Action</th>
				      </tr>
							<?php foreach($result as $k=>$receipt){?>
										<tr id="tr_<?=$receipt['ID']?>">
											<td><?=$k+1;?></td>
										
											<td><?=$receipt['MRN_No']?></td>
											<td><?=date('d-M-Y',strtotime($receipt['Receipt_date']))?></td>
											<td><?=$receipt['PO_no']?></td>
											<td><?=$receipt['Indent_no']?></td>
											<td><?=$receipt['emp_name']?></td>
											<td>
											<a href="<?=Yii::$app->homeUrl.'inventory/default/receipt?securekey='.$menuid.'&MRN_No='.$receipt['MRN_No']?>"> 
											<img src="<?=Yii::$app->homeUrl?>images/edit.gif" style="width: 23px;">
											</a>
											<a href="javascript:" onclick="delete_rec(<?=$receipt['ID']?>);"><img src="<?=Yii::$app->homeUrl?>images/del.gif" style="width: 23px;"></a>
											</td>
										</tr>
							<?php } ?>
				    </tbody></table>
		 </div>
		</div>
	 <?php } ?>
</fieldset>
  
                   
               
