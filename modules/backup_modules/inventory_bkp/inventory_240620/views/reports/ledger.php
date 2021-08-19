  <!------ Include the above in your HEAD tag ---------->
  <!------ Include the above in your HEAD tag ---------->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
<style>
.btn.btn-lg.col-xs-5.col-md-5 {
  font-family: Roboto;
  font-weight: bolder;
  margin: 1px;
}.row {
  opacity: 0.88;
}
	table{
	font-family: initial;
	}
</style>
<?php
$menuid = "";
if(isset($_GET['securekey']) AND !empty($_GET['securekey']))
{
    $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
}
if(empty($menuid))
{
    header('Location: '.Yii::$app->homeUrl); 
    exit;
}
$menuid = Yii::$app->utility->encryptString($menuid);
?>
<div class="container"><a href="#" class="ex_coll btn-info" style="float: right;padding: 6px;margin: 0 0 0 20px;" onclick="sho_hid()">Expand-View</a> 
	<fieldset>
		<form action="" method="post">
     <div class='row'>	
		 
		 

		<div class="form-group col-sm-5">
		  <label class="control-label">Select Category </label>
		   <select id="cat_name" class="js-example-basic-multiple  form-control form-control-sm" name="cat_name">
				<option value="">-- Select --</option>
				<?php foreach($category as $r){ 
					$sel="";
					if(isset($_POST['cat_name'])){if($_POST['cat_name']==$r['ITEM_CAT_CODE']){$sel="selected='selected'";}}
					echo '<option '.$sel.' value="'.$r['ITEM_CAT_CODE'].'">'.$r['ITEM_CAT_NAME'].'</option>';
				 } ?>
			</select> 
		</div>
		 
		 <div class="form-group col-sm-5">
		  <label class="control-label">Select Item </label>
		   <select onchange="form.submit()" id="cat_items" class="js-example-basic-multiple  form-control form-control-sm" name="item_code">
				<option value="">-- Select --</option>
			   <?php foreach($items as $item){ 
					$sel="";
					if(isset($_POST['item_code'])){if($_POST['item_code']==$item['ITEM_CODE']){$sel="selected='selected'";}}
					echo '<option '.$sel.' value="'.$item['ITEM_CODE'].'">'.$item['item_name'].'</option>';
				 } ?>
 			</select> 
		</div>
	</div>
</form>
</fieldset>
    <div class="row">
        <div class="col-md-12" style="overflow: auto;">
			 <?php if(!empty($data)){ ?>
			<span style="font-size: 18px;" class="control-label">Showing Result for: <b><?=$data[0]['item_name'].' ('.$data[0]['ITEM_CAT_NAME'].')';?></b></span>
			<?php } ?>
           <table id="dataTableShow" class="display" style="width:100%">
        <thead>
            <tr>
               
                
                 <th>Emp Name</th>
                <th>Item Name(Cat)</th>
                <th>Supllier Name(address)</th>
                <th>Transaction Date</th>
                <th>Receipt Qty</th>
                <th>Issued Qty</th>
                <th>Balance Qty</th>
                 <th>Description</th>
                
            </tr>
        </thead>
        <tbody>
            <?php 
            if(!empty($data)){
            foreach($data as $k=>$c){ ?>
            <tr>
                
                 <td id="<?=$c['Access_id']?>"><?=$c['emp_name']?></td>
                <td id="item_name"><?=$c['item_name'];?></td>
                <td id="Supplier"><?php if($c['Supplier_name']){ echo $c['Supplier_name'].'<br>('.$c['Supplier_address'].')';}?></td>
                 <td id="Transaction_Date"><?=date("d-m-Y",strtotime($c['Transaction_Date']))?></td>
                <td id="Receipt_Qty"><?=$c['Receipt_Qty']?></td>
                <td id="Issued_Qty"><?=$c['Issued_Qty']?></td>
                <td id="Balance_Qty"><?=$c['Balance_Qty']?></td>
                 <td id="Description"><?=$c['Description']?></td>
                
            </tr>   
             <?php } } ?>
        </tbody>
     </table>
    </div>
    </div>
</div>
<script>
var BASE_URL='<?=Yii::$app->homeUrl?>';
var securekey='<?=$menuid?>';
$(function(){
 		  $('#cat_name').change(function(){
				var cat_id= $(this).val();
				$.ajax({
					url:BASE_URL+'inventory/default/get_cat_code?securekey='+securekey,
					type:'POST',
					data:{cat_id:cat_id},
					datatype:'json',
					success:function(data){
						$('#cat_items').html(data);
 					}
			    });
			}); 
		}); 
</script>