  <!------ Include the above in your HEAD tag ---------->
  <!------ Include the above in your HEAD tag ---------->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>


<script type="text/javascript" language="javascript" src="<?=Yii::$app->homeUrl?>/js/forexcel/jszip.js"></script>
<script type="text/javascript" language="javascript" src="<?=Yii::$app->homeUrl?>/js/forexcel/buttons.js"></script>


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
    <div class="dataTables_wrapper no-footer table-scroll trngdata" id="subjectTable_wrapper">
      <div class="col-md-6">
      </div> 
      <table id="dataTableMunish" class="display" style="width:100%">
        <thead>
          <tr>
           
            
            <th> MRN No </th>
            <th> Emp Name </th>
            <th> Item Name(Cat) </th>
            <th> Supllier Name (address) </th>
            <th> Memo Date </th>
            <th> Rate per unit </th>
            <th> Sale tax </th>
            <th> Sale tax per </th>
            <th> Qty Accepted </th>
            <th> Remark </th>
            
          </tr>
        </thead>
        <tbody>
          <?php 
          if(!empty($data)){
          foreach($data as $k=>$c){ ?>
            <tr>
                
                <td id="MRN_No"><?=$c['MRN_No']?></td>
                <td id="emp_name"><?=$c['emp_name']?></td>
                <td id="item_name"><?=$c['item_name'].'('.$c['ITEM_CAT_NAME'].')';?></td>
                <td id="Supplier"><?=$c['Supplier_name'].'<br>('.$c['Supplier_address'].')';?></td>
                 <td id="Memo_Date"><?=date("d-m-Y",strtotime($c['Memo_Date']))?></td>
                <td id="Rate_per_unit"><?=$c['Rate_per_unit']?></td>
                <td id="Sale_tax"><?=$c['Sale_tax']?></td>
                <td id="Sale_tax_per"><?=$c['Sale_tax_per']?></td>
                <td id="Qty_Accepted"><?=$c['Qty_Accepted']?></td>
                <td id="Remark"><?=$c['Remark']?></td>
                
            </tr>   
         <?php } } ?>
        </tbody>
          
      </table>            
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
      var table = $('#dataTableMunish').DataTable( {
          lengthChange: true,
          buttons: [ 'copy', 'excel', 'print' ]
      } );

      table.buttons().container()
          .appendTo( '#subjectTable_wrapper .col-md-6:eq(0)' );
  } );

</script>