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
<div class="container">
	<fieldset>
		<form action="" method="post">
     <div class='row'>	
		 
		<div class="form-group col-sm-4">
		  <label class="control-label">Select Group </label>
		   <select id="group_name" class="js-example-basic-multiple  form-control form-control-sm" name="group_name">

				<?php foreach($groups as $r){ 
					$sel="";
					if(isset($_POST['group_name'])){
					if($_POST['group_name']==$r['CLASSIFICATION_CODE']){$sel="selected='selected'";}}
					echo '<option '.$sel.' value="'.$r['CLASSIFICATION_CODE'].'">'.$r['CLASSIFICATION_NAME'].'</option>';
				 } ?>
			</select> 
		</div>

		<div class="form-group col-sm-4">
		  <label class="control-label">Select Category </label>
		   <select onchange="form.submit()" id="cat_name" class="js-example-basic-multiple  form-control form-control-sm" name="cat_name">
				<option value="">-- Select --</option>
				<?php foreach($category as $r){ 
					$sel="";
					if(isset($_POST['cat_name'])){
					if($_POST['cat_name']==$r['ITEM_CAT_CODE']){$sel="selected='selected'";}}
					echo '<option '.$sel.' value="'.$r['ITEM_CAT_CODE'].'">'.$r['ITEM_CAT_NAME'].'</option>';
				 } ?>
			</select> 
		</div>
	</div>
</form>
</fieldset>
    <div class="row">
        <div class="col-md-12">
           <table id="dataTableShow" class="display" style="width:100%">
        <thead>
            <tr>
               
                
                <th>Item Code</th>
                <th>Item Name</th>
                <th>Item Type</th>
                <th>Quantity</th>
                <th>Measuring Unit</th>
                
            </tr>
        </thead>
        <tbody>
            <?php 
            if(!empty($data)){
            foreach($data as $k=>$c){ ?>
            <tr>
                
                <td id="ITEM_CODE"><?=$c['ITEM_CODE']?></td>
                <td id="item_name"><?=$c['item_name']?></td>
                <td id="Item_type"><?=$c['Item_type']?></td>
                <td id="Quantity"><?=$c['Quantity']?></td>
                <td id="Measuring_Unit"><?=$c['Measuring_Unit']?></td>
                
            </tr>   
             <?php } } ?>
        </tbody>
        
    </table>
                 
           
        </div>
    </div>
</div>
