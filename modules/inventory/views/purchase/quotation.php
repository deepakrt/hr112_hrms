<!------ Include the above in your HEAD tag ---------->
<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
//echo "<pre>";print_r($data);die;
$role = Yii::$app->user->identity->role;
?>
   
                   
    <div class="col-sm-12">
    <table id="dataTableShow1" class="display" cellpadding="2" style="width:100%">
        <thead>
            <tr>
                <th>Voucher No</th>
                <th>Employee</th>
                <th>Item Name</th>
                 <th>Qty</th>
                <th>Approx Cost</th>
                <th>Project</th>
                <th>Action</th>                               
            </tr>
        </thead>
        <tbody>
            <?php 
			$check="disabled='disabled'";
            if(!empty($data)){
				$check="";$find = array("Y", "N");$replace = array("Yes", "No");
            foreach($data as $k=>$c){ ?>
            <tr id="row_<?=$c['item_id']?>">
               <td id="voucher_no"><?=$c['voucher_no'].'<hr style="margin: 2px 9px 2px 0px;">'.date('d-m-y',strtotime($c['request_date']));?></td>
                <td id=""><?=$c['fname']?></td>
                <td id="item_name">
			<?php if($c['item_doc']=='javascript:'){
					$link='javascript:';
					$target='class="black"';
				}else{
					$target='target="_blank"';
					//$link=Yii::$app->homeUrl.Inventory_Docs.$c['item_doc'];
					$link=Yii::$app->homeUrl.'inventory/purchase/viewdoc?file='.base64_encode($c['item_doc']);
				}
			?>
		<a <?=$target?> href="<?=$link;?>"><?=$c['item_name'];?></a>	 
		</td>                 
                <td id="qty"><?=$c['quantity_required']-$c['qty'];?></td><?php   ?>
                <td id="tot_cost"><?=$c['total_cost']?></td>
                <td id="project"><?php if($c['project']){echo $c['project'];}else{echo "---";}?></td>
                <td align="center">
				<?php if($c['ipurchase_status']!='Completed') { ?>				 
				<img data-toggle="modal" data-target="#myModal3" id="<?=$c['item_id']?>" class="model_<?=$c['item_id']?> getdetails footer-content" title="View" src="<?=Yii::$app->homeUrl?>images/view.png" style="cursor:pointer;width: 25px;"> 
				<?php }else{echo "Completed<br>";}  ?>
				<a target="_blank" href="download?securekey=<?=$menuid?>&id=<?=base64_encode($c['id'])?>"><img src="<?=Yii::$app->homeUrl?>images/pdf.png" title="Download"  style="cursor:pointer;width: 25px;">  </a>
                                <a target="_blank" href="quotationpdf?securekey=<?=$menuid?>&Param_Indent_no=<?=$c['voucher_no']?>"><img src="<?=Yii::$app->homeUrl?>images/pdf.png" title="Quotation Report"  style="cursor:pointer;width: 25px;" id="getqid">  </a>
				</td>
             </tr> 			 
             <?php } } ?>
        </tbody>
    </table>
		    
 <div class="modal fade show" id="myModal3" role="dialog" style="padding-left: 10px;">
    <div class="modal-dialog" style="min-width: 60%;">   
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header" style="background: #DD7869;">
          <h4 class="modal-title">Quotation Form</h4>
          <button type="button" class="close" data-dismiss="modal">×</button>
         
        </div>
        <div class="modal-body" style="border-bottom: 5px solid #DD7869;">
            
        </div>
         
      </div>
      
    </div>
  </div>
<script>
var BASE_URL='<?=Yii::$app->homeUrl?>';
var securekey='<?=$menuid?>';
$(function(){
       //alert($("#q_id123").val())
  	$( "body" ).on( "click", ".getdetails", function() { 
             var id= $(this).attr('id');
		 	var csrftoken=$("#_csrf").val();
			 $.ajax({
					url:BASE_URL+'inventory/purchase/viewquotationitem?securekey='+securekey,
					type:'POST',
					data:{id:id,_csrf:csrftoken},
					datatype:'json',
					success:function(data){
						 $(".modal-body").html(data);
					}
			});
        });
  
  }); 
</script>
