
<style>
legend {
	background-color: #c5dec5;color: #3F9E89;padding: 0px 6px;font-family: initial;font-size: 21px;
}
</style>
<div class="col-sm-12 text-right">
<a style="margin-bottom:10px;" href="<?=Yii::$app->homeUrl?>inventory/comparison/report?securekey=<?=$menuid?>" class="btn btn-success btn-sm mybtn">Add New ComparisonReport</a>
	
</div>
 
		<div class="col-sm-12">
<table id="dataTableShow" class="display" style="width:100%">	<thead>
		<tr>
			<th>Sr.</th>
			<th>Item Name</th>			
			<th>Supplier Name</th>
						
          <!--   <th>Category</th>  --> 
			
			<th style="width: 100px !important;">Action</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		// echo "<pre>";
		// print_r($lists);
		// echo "<pre>";
		if(!empty($lists))
		{
			$i =1;
			foreach($lists as $l)
			{ ?>
				<tr>
	<td><?=$i ?></td>
		<td><?=$l['item_name']?></td>
	<td><?=$l['Supplier_name']?></td>
	

	<td><button type="button" class="btn btn-primary btn-sm btn-xs" data-toggle="modal" data-target=".exampleModalCenter" onclick="getItemDetails('<?=$l['ITEM_CODE']?>','<?=$l['item_name']?>');">View</button>		
		<button onclick="editItemDetails('<?=$l['id']?>');" class="btn btn-primary btn-sm btn-xs">Edit</button>
	</td>
	
	
	
	</tr>

			<?php $i++; } 
		}

				?>
	
	</tbody>
	
</table>
</div>

<div class="modal fade exampleModalCenter" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalLongTitle"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="modalDataDivDisp">       
      </div>
    </div>
  </div>
</div>
<script>

	$(document).ready(function() {
	    $('table.display').dataTable();
	} );
	var BASE_URL='<?=Yii::$app->homeUrl?>';
	var securekey='<?=$menuid?>';
	function startLoader()
	{
	   $("#loading").show();
	}
	 
	function stopLoader()
	{
	    $("#loading").hide();
	}


	function editItemDetails(id)
	{
	    $('#modalDataDivDisp').html('');
	    if(id != '')
	    {

	        

  				window.location.assign("<?php echo Yii::$app->homeUrl."inventory/comparison/edit?securekey=$menuid";?>&id="+id);
	    }
	}

	function getItemDetails(itemcode,itemname)
	{
	   $('#modalDataDivDisp').html('');
	    if(itemcode != '')
	    { 

	        // var clf_name = $('#clf_name'+itemcode).html();
	        // var itmc_name = $('#itmc_name'+itemcode).html();
	        // var itc_name = $('#itc_name'+itemcode).html(); // itc_code21070001
	        // var itc_code = $('#itc_code'+itemcode).html(); // itc_code21070001

	        $('#modalLongTitle').html('');
	        $('#modalLongTitle').html(' <b>Item Name:</b> '+itemname);

		       //  startLoader();
	         $.ajax({
	            url: "<?php echo Yii::$app->homeUrl."inventory/comparison/supplier?securekey=$menuid";?>",
	            type: 'POST',
	            data: {itemcode:itemcode},
	            dataType: 'JSON',

	            success: function (data) 
	            {
	            	// console.log(data);

	                $('#modalDataDivDisp').html(data.result);
	               // stopLoader();
	            },
	            error: function(error) {
	            	console.error(error);
	            }
	        });
	    }
	}
</script>