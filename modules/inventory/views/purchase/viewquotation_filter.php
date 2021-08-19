  <!------ Include the above in your HEAD tag ---------->
<?php
 use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
//echo "<pre>";print_r($data);die;
?>
 <style>
	    fieldset 
	{
		border: 1px solid #ddd !important;
		margin: 0;
		min-width: 0;
		padding: 10px;       
		position: relative;
		border-radius:4px;
		background-color:#fef7f7;
		padding-left:10px!important;
	}	
	
		legend
		{
			font-size:14px;
			font-weight:bold;
			margin-bottom: 0px; 
			width: 35%; 
			border: 1px solid #ddd;
			border-radius: 4px; 
			padding: 5px 5px 5px 10px; 
			background-color: #f2c3a9;
		}
</style>
<form action="" id="reqform">
 <fieldset>	
     <table id="dataTableShow1" class="display tabledata" cellpadding="2" style="width:100%">
        <thead>
		<tr>
			<th>Sr.</th>			
			<th>Supplier_name</th>			
                        <th>Supplier_address</th>
                        <th>Phone_no</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		if(!empty($lists)){
			$i =1;
			foreach($lists as $l){ 
                        $encry = base64_encode($l['Supplier_Code']);
			$Supplier_name =$l['Supplier_name'];
			$Supplier_address = $l['Supplier_address'];
			$Phone_no = $l['Phone_no'];
			?>
			<tr>
			<td><?=$i?></td>
                        <td><?=$Supplier_name?></td>
                        <td><?=$Supplier_address?></td>
			<td><?=$Phone_no?></td>                     
			</tr>	
		<?php $i++;	}
		}
		?>
	</tbody>
    </table>
	<div class="form-group col-sm-12" style="text-align: center;margin: 16px 0 0 200px;">
	  <label>
        	<button type="button" class="approve btn btn-success">Process</button>        	     	
	</label>
    </div>
 </fieldset>
</form>

<script>
var BASE_URL='<?=Yii::$app->homeUrl?>';
var securekey='<?=$menuid?>';
$(function(){

             $('#cat_name').on('change',function(){  
		      var cat_id= $(this).val();
		      //alert(cat_id);
		     $.ajax({
			url:BASE_URL+'inventory/purchase/viewfilter?securekey='+securekey,
			type:'POST',
			data:{cat_id:cat_id},
			datatype:'json',
			success:function(data){
                                  //alert(data);
				if(data!=0){
				    //$('#dataTablefilter').html(data);
                                      $(".modal-body").html(data); 							                                                        
				}else{
					 alert('Category can not be blank.');
				}
			   }
			 });

    }); 
	}); 
	
</script>
 
 
                   
               
