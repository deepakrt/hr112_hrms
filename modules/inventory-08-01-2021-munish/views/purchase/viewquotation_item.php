  <!------ Include the above in your HEAD tag ---------->
<?php
 use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
 //echo "<pre>";print_r($data);die;
$category=Yii::$app->inventory->get_category();
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
     <table id="table" class="display" cellpadding="2" style="width:100%">
        <thead>
            <tr>
                 <th>Voucher No</th>                
                 <th>Item Name</th>
                 <th>Required Qty</th>
             </tr>
        </thead>
        <tbody>
            <?php foreach($data as $k=>$c){ ?>           
            <tr> 
                <td width="25%" id="voucher_getno"><?=$c['voucher_no'];?></td>             
                <td width="30%" id="item_getname"><?=$c['item_name'];?></td>
                <td width="20%" id="quantity_required"><?=$c['quantity_required']-$c['qty']?></td>                	    
	    </tr>
            <?php } ?>
	    <tr>
		 <td colspan="3"><label style="float:left; width:100%">Descripation</label>
                 <textarea rows="3" cols="50" id="Item_description" class="form-control" name="Item_description"></textarea></td>
	    </tr>  				              
        </tbody>
    </table>
	<div class="form-group col-sm-12" style="text-align: center;margin: 16px 0 0 200px;">
	  <label>
        	<button type="button" class="process btn btn-success">Process</button>        	     	
	</label>
      
    </div> 
   <div id="errormessage" style="color:red"></div>
     <img src="<?=Yii::$app->homeUrl?>images/pdf.png" title="Quotation Report"  style="cursor:pointer;width: 25px;" id="quotation_report"> 
 </fieldset>
</form>
 <div style="display:none" id="getdata">
  <form action="" method="post">
     <div class='row'>
         
               <input type="text" id="q_id123" name="q_id" value="" style="display:none"/>		 		
		<div class="form-group col-sm-4">
		  <label class="control-label">Select Category </label>

                      <select id="cat_name" class="js-example-basic-multiple  form-control form-control-sm" name="cat_name">
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
<div class="col-sm-12">
    <div class="showdata" style="padding-left: 10px;">
    <button type='button' value='1' class="btn btn-success" id='send_detail'>Forward</button>
      
    </div>
  </div>

</div>
<script>
var BASE_URL='<?=Yii::$app->homeUrl?>';
var securekey='<?=$menuid?>';
$(function(){
 		 $('.process').on('click', function () {      
			    var voucher_no= $("#voucher_getno").text();
			    var item_id= $("#item_getname").text();
			    var quantity_required=$("#quantity_required").text();
                            var Item_description=$("#Item_description").val();
                               //alert(voucher_no + item_name + quantity_required + Item_description);
 			 var csrftoken=$("#_csrf").val();
  				$.ajax({
						url:BASE_URL+'inventory/purchase/update_quotation_form?securekey='+securekey,
						type:'POST',
						data:{voucher_no:voucher_no,item_id:item_id,quantity_required:quantity_required,Item_description:Item_description,_csrf:csrftoken},
//data:{data:$("#reqform").serialize(),_csrf:csrftoken},						
                                                datatype:'json',
						success:function(data) {
							var res = data;
                                                         // alert(res+">>");
							 $("#q_id123").val(res);
                                                         $("#getdata").css("display","block");
	
                                                        //alert($("#q_id123").val());
							//swal("Updated Successfully! ", "", "success")
							//.then((value) => {
							  //window.location.reload(); 
							//});
 						},
					    error: function (jqXhr, textStatus, errorMessage) {
						    $('#errormessage').append('Quotation Form Aleady Exists for this Item');
					    }
                                          
				});
                                
			 });

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
                                      $(".showdata").html(data); 							                                                        
				}else{
					 alert('Category can not be blank.');
				}
			   }
			 });

    });
  
      
}); 
	
function validate(form) {

     if(!confirm('Do you really want to submit the form?')){
		 return false;
	 }
     
}
</script>
 
 
                   
               
