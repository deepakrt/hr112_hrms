<!------ Include the above in your HEAD tag ---------->
<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
//echo "<pre>";print_r($data);die;
$role = Yii::$app->user->identity->role;
?>
   
                   
               <div class="col-sm-12">
    <table id="dataTableShow" class="display" cellpadding="2" style="width:100%">
        <thead>
            <tr>
                
                <th>Voucher No</th>
                <th>Employee</th>
                <th>Item Name</th>
                 <th>Qty</th>
                <th>Approx Cost</th>
                <th>Project</th>
                <th>Received On</th>
                <th width="15%">Action</th>
                
                
            </tr>
        </thead>
        <tbody>
            <?php 
			$check="disabled='disabled'";
            if(!empty($data)){
				$check="";$find = array("Y", "N");$replace = array("Yes", "No");
            foreach($data as $k=>$c){ 
			/* $c['item_name']=str_replace('inventory_docspath/javascript:', 'javascript:',$c['item_name']);
			$c['item_name']=str_replace('inventory_docspath', Yii::$app->homeUrl.Inventory_Docs,$c['item_name']); */?>
            <tr id="row_<?=$c['item_id']?>">
                 
                <td id="voucher_no"><?=$c['voucher_no'].'<hr style="margin: 2px 9px 2px 0px;">'.date('d-m-y',strtotime($c['request_date']));?></td>
                <td id=""><?=$c['fname']?></td>
                <td id="item_name">
				<?php if($c['item_doc']=='javascript:'){
						$link='javascript:';
						$target='class="black"';
					}else{
						$target='target="_blank"';
						$link=Yii::$app->homeUrl.'inventory/purchase/viewdoc?file='.base64_encode($c['item_doc']);
						//$link=Yii::$app->homeUrl.Inventory_Docs.$c['item_doc'];
					}
				?>
				<a <?=$target?> href="<?=$link;?>"><?=$c['item_name'];?></a>
			 
				</td>
                 
                 <td id="qty"><?=$c['quantity_required']-$c['qty'];?></td><?php   ?>
                <td id="tot_cost"><?=$c['total_cost']?></td>
                <td id="project"><?php if($c['project']){echo $c['project'];}else{echo "---";}?></td>
				 <td id="tot_cost"><?php if($c['forward_time']){ echo date('d-m-y',strtotime($c['forward_time']));}?></td>
                <td align="center">
				<?php if($c['ipurchase_status']!='Completed'){ ?>
				 
				<img data-toggle="modal" data-target="#myModal3" id="<?=$c['item_id']?>" class="model_<?=$c['item_id']?> getdetails footer-content" title="View" src="<?=Yii::$app->homeUrl?>images/view.png" style="cursor:pointer;width: 25px;"> 
				<?php }   ?>
				<a target="_blank" href="download?securekey=<?=$menuid?>&id=<?=base64_encode($c['id'])?>"><img src="<?=Yii::$app->homeUrl?>images/pdf.png" title="Download"  style="cursor:pointer;width: 25px;">  </a>
					<?php if($c['ipurchase_status']!='default'){echo '</br>'.$c['ipurchase_status'].' ('.$c['ipurchase_mod'].')';} ?>
				</td>
             </tr> 
			 
             <?php } } ?>
        </tbody>
    </table>
		   
		</div>
		<div class="modal fade show" id="myModal3" role="dialog" style="padding-left: 10px;">
    <div class="modal-dialog" style="min-width: 60%;">
    
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header" style="background: #DD7869;">
          <h4 class="modal-title">View Request</h4>
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
	
 		$( "body" ).on( "click", ".getdetails", function() { 
             var id= $(this).attr('id');
		 	var csrftoken=$("#_csrf").val();
			 $.ajax({
					url:BASE_URL+'inventory/purchase/viewitem?securekey='+securekey,
					type:'POST',
					data:{id:id,_csrf:csrftoken},
					datatype:'json',
					success:function(data){
						 $(".modal-body").html(data);
					}
			});
        });
		 
		  
	
	 $(".apr_rej").click(function(){
		
			var ids = [];
			$.each($("input[name='vvalues']:checked"), function(){
			    ids.push($(this).val());
				  <?php if(Yii::$app->user->identity->role==8){ ?>
				//alert(ids);
				<?php }?>
			});
            var v_nos= ids.join(",");
 		 	if(v_nos==''){
			 	alert('Please select any Record');
			}
				//return false;
            var auth_id= $("#hod_id").val();
            var status= $(this).val();
		 	var csrftoken=$("#_csrf").val();
			 $.ajax({
					url:BASE_URL+'inventory/purchase/apr_rej_prequest?securekey='+securekey,
					type:'POST',
					data:{v_nos:v_nos,auth_id:auth_id,status:status,_csrf:csrftoken},
					datatype:'json',
					success:function(data){
						alert('Updated Successfully');
						window.location.reload();
					}
			});
        });
	 $(".issue").click(function(){
		
			var ids = [];
			$.each($("input[name='vvalues']:checked"), function(){
			    ids.push($(this).val());
			});
            var v_nos= ids.join(",");
 		 	if(v_nos==''){
			 	alert('Please select any Record');
				return false;
			}
		 //alert(v_nos);
            var csrftoken=$("#_csrf").val();
			 $.ajax({
					url:BASE_URL+'inventory/purchase/purchase_str_item?securekey='+securekey,
					type:'POST',
					data:{v_nos:v_nos,_csrf:csrftoken},
					datatype:'json',
					success:function(data){
						alert('Updated Successfully');
						window.location.reload();
					}
			});
        });
	 
	$("#checkAll").click(function(){
    	$('input:checkbox').not(this).prop('checked', this.checked);
	});
     
	
		}); 
</script>