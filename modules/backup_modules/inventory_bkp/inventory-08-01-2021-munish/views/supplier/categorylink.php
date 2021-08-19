<?php
//$this->title= 'Supplier';
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
$category=Yii::$app->inventory->get_category();
//echo "<pre>";print_r($data);die;
?>
<form action="" id="reqform">
 <fieldset>	
     <table id="table" class="display supplycat" cellpadding="2" style="width:100%">
        <thead>
            <tr>
                 <th>Already Linked Categories</th>                
             </tr>
        </thead>
        <tbody> 
          
           <?php foreach($data as $k=>$c){ ?>              
            <tr> 
                <input type="text" id="supplier_code" value="<?=$c['Supplier_Code']?>" style="display:none"/>                       	    
	    </tr>
            <tr> 
                <td>&#9989;  <b><?=$c['ITEM_CAT_NAME'];?></b>  </td>                         	    
	    </tr>
            <?php } ?>         
	    <tr style="border-top:1px solid #999;">
	    <td>
                <label style="float:left; width:100%">Select Category (if want to add more)</label>
                <?php foreach($category as $r){ 
			$sel="";
			if(isset($_POST['cat_name'])){
			if($_POST['cat_name']==$r['ITEM_CAT_CODE']) { $sel="selected='selected'";} }
			echo '<label><input type="checkbox" name="Supplier[Category][]" value="'.$r['ITEM_CAT_CODE'].'"> '.$r['ITEM_CAT_NAME'].'</label>';
		 } ?>
                
                 </td>
	    </tr> 
            <tr>
            <td><div class="col-sm-12 text-center">
		<button type="button" class="btn btn-success btn-sm sl" id="linkcat">Submit</button>
		<a href="<?=Yii::$app->homeUrl?>inventory/supplier?securekey=<?=$menuid?>" class="btn btn-danger btn-sm">Cancel</a>
	        </div>
            </td>
            </tr> 				              
        </tbody>
    </table>
	
 </fieldset>
</form>

<script>
var BASE_URL='<?=Yii::$app->homeUrl?>';
var securekey='<?=$menuid?>';
$(document).ready(function() { 
             //var supplier_code = $("#supplier_code").val();
             //alert(supplier_code);
$('body').on('click','#linkcat', function (){		
			var ids = [];
			$.each($("input[name='Supplier[Category][]']:checked"), function(){
			    ids.push($(this).val());
			});
            var ITEM_CAT_CODE= ids.join(",");
 		 	if(ITEM_CAT_CODE==''){
			 	alert('Please select any Record');
				return false;
			}
	//alert(ITEM_CAT_CODE);
 
             var supplier_code = $("#supplier_code").val();

              var csrftoken=$("#_csrf").val();
			 $.ajax({
				url:BASE_URL+'inventory/supplier/suppliercat_mapping?securekey='+securekey,
				type:'POST',
				data:{supplier_code:supplier_code,ITEM_CAT_CODE:ITEM_CAT_CODE,_csrf:csrftoken},
				datatype:'json',
				success:function(data){
				    //alert(data);
				//var ht = $.parseJSON(data);
					//var status = ht.Status;
					//var res = ht.Res;
					alert('Updated Successfully');
					window.location.reload();
				}
				});
			});
}); 
</script>
