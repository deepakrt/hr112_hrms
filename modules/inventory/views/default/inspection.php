  <!------ Include the above in your HEAD tag ---------->
<?php
 use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
//echo "<pre>";print_r(Yii::$app->user->identity);die;
// echo "<pre>";print_r($mrn_data);die;
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

	</div>  </fieldset>
   <?php ActiveForm::end(); ?>
 
<fieldset>
        <legend> Inspection Details </legend>
<div class="row">
<div class="form-group col-sm-12">
	<table class="table table-bordered">
        <tbody><tr>
            <th>#</th>
            <th>Group</th>
            <th>Category</th>
            <th>Item (Units)</th>
            <th>PO no</th>
            <th>Indent No</th>
            <th>Emp. Name</th>
            <!--th> Qty. Received</th-->
             <th> Qty. Accepted</th>
            <!--th> Qty. Rejected</th-->
             <th>Action</th>
        </tr>
<?php  if(!empty($result)){
			foreach($result as $k=>$res){?>
			<tr>
				<td><?=$k+1;?></td>
				<td><?=$res['CLASSIFICATION_NAME']?></td>
				<td><?=$res['ITEM_CAT_NAME']?></td>
				<td><?=$res['item_name'].' ('.$res['Measuring_Unit'].')';?></td>
				<td><?=$res['PO_no']?></td>
				<td><?=$res['Indent_no']?></td>
				<td><?=$res['employee']?></td>
				 
				<td><?=$res['QtyR']?></td>
				 
 				<td>
					<?php $param='securekey='.$menuid.'&MRN_No='.$res['MRN_No'].'&rno='.$res['Accessid'] ?>
					<a href="<?=Yii::$app->homeUrl?>inventory/default/viewinspection?<?=$param?>"><img src="<?=Yii::$app->homeUrl?>images/view.png" style="width: 23px;"></a>
				</td>
			</tr>
	<?php }}else{  ?>
			<tr><td colspan="9">No Record found.</td></tr>
			<?php }  ?>
                    </tbody></table>
 </div>
</div>
	 <?php } ?>
</fieldset>