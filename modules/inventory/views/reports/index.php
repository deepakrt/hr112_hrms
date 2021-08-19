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
  <div class="dataTables_wrapper no-footer table-scroll trngdata" id="subjectTable_wrapper">
    <div class="col-md-6">
    </div>    
    <table id="dataTableShowdata" class="display" cellspacing="0" style="width:100%">
      <thead>
        <tr>               
          <th>Sr. No.</th>
          <th>Item Code</th>
          <th>Item Name</th>
          <!-- <th>Item Type</th> -->
          <th>Category</th>
          <th>Quantity</th>
          <th>Measuring Unit</th>
          <th>Updated On</th>              
        </tr>
      </thead>
      <tbody>
        <?php 
          // echo "<pre>"; print_r($data); die();

          $prv = 1;
          if(!empty($data)){
          foreach($data as $k=>$c){ ?>
          <tr>
              
              <td id="prv"><?=$prv?></td>
              <td id="ITEM_CODE"><?=$c['ITEM_CODE']?></td>
              <td id="item_name"><?=$c['item_name']?></td>
              <!-- <td id="Item_type"><?php // $c['Item_type']?></td> -->
              <td id="ITEM_CAT_NAME"><?=$c['ITEM_CAT_NAME']?></td>
              <td id="Quantity"><?=$c['Quantity']?></td>
              <td id="Measuring_Unit"><?=$c['Unit_Name']?></td>
              <td id="modify_date"><?=date('d-m-Y',strtotime($c['modify_date']));?></td>
              
          </tr>   
        <?php $prv++; } } ?>
      </tbody>            
    </table>       
  </div>
</div>



<script>
    $(document).ready(function() {
        var table = $('#dataTableShowdata').DataTable( {
            lengthChange: true,
            buttons: [ 'copy', 'excel', 'print' ]
        } );

        table.buttons().container()
            .appendTo( '#subjectTable_wrapper .col-md-6:eq(0)' );
    } );

</script>