<!------ Include the above in your HEAD tag ---------->
<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
//echo "<pre>";print_r(Yii::$app->user->identity);die;
$menuid = Yii::$app->utility->decryptString($_GET['securekey']);
$menuid = Yii::$app->utility->encryptString($menuid);
$role = Yii::$app->user->identity->role;
?>

                   
               <div class="col-sm-12">
    <table id="dataTableShow" class="display" style="width:100%">
        <thead>
            <tr>
               
                <th>Voucher No</th>
                <th>Employee</th>
                <th>Item (Req Qty)</th>
				<?php //if($role>6){ ?>
                <th>Available(Qty)</th><?php //} ?>
                <th>Cost per Item</th>
                <th>Project</th>
                <th>Status</th>
				<?php if($role=='17'){ ?>
				<th>Download</th>
				<?php } ?>
                
            </tr>
        </thead>
        <tbody>
            <?php  //echo "<pre>";print_r($data);die;
            if(!empty($data)){ $find = array("Y", "N");$replace = array("Yes", "No");
            foreach($data as $k=>$c){ 
			$c['item_name']=str_replace('inventory_docspath/javascript:', 'javascript: onclick="return false;"',$c['item_name']);
			$c['item_name']=str_replace('inventory_docspath', Yii::$app->homeUrl.Inventory_Docs,$c['item_name']);?>
            <tr>
                
                <td id="Voucher_No"><?=$c['voucher_no'].'<hr style="margin: 2px 9px 2px 0px;">'.date('d-m-y',strtotime($c['request_date']));?></td>
                <td id="Employee"><?=$c['fname']?></td>
                <td id="item_name"><?=$c['item_name'];?></td>
				<?php //if($role>6){ 
				$avail='No';if($c['qty_avail']=='Y'){$avail='Yes ('.$c['qty'].')';}?>
                 <td id="qty"><?=str_replace($find, $replace,$c['qty_avail_ll']);?></td><?php //} ?>
                <td id="approx_cost"><?=$c['approx_cost']?></td>
                <td id="project"><?php if($c['project']){echo $c['project'];}else{echo "---";}?></td>
				<td align="center" <?php if($c['flag']>7){ ?>data-toggle="modal" data-target="#myModal3"<?php } ?>>
				<?php if($c['purchase_status']!='default') { ?>
        	        <?=$c['purchase_status'];?> 
	            <?php }else{ ?>
                <?=$c['Status']?> 
    	    	    <?php } 
					//if($role==7 || $role==16 || $role==17){  
					if($c['flag']>7){ ?>
                <p id="<?=$c['id']?>" class="model_<?=$c['id']?> getdetails footer-content"><img title="View" src="<?=Yii::$app->homeUrl?>images/view.png" style="cursor:pointer;width: 23px;"></p> <?php } ?>
				</td>
				<?php if($role=='17'){ ?>
				<td>
				&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="download?securekey=<?=$menuid?>&id=<?=base64_encode($c['id'])?>"><img src="<?=Yii::$app->homeUrl?>images/pdf.png" title="Download"  style="cursor:pointer;width: 25px;">  </a>
				</td>
				<?php } ?>
            </tr>   
             <?php } } ?>
        </tbody>
        
    </table>
</div><?php //if($role==7 || $role==16 || $role==17){
//if($c['flag']>7){ 	?>
<div class="modal fade show" id="myModal3" role="dialog" style="padding-left: 10px;">
    <div class="modal-dialog" style="min-width: 60%;">
    
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header" style="background: #DD7869;">
          <h4 class="modal-title">View Item</h4>
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
	
	$(".getdetails").click(function(){
             var id= $(this).attr('id');
		 	var csrftoken=$("#_csrf").val();
			 $.ajax({
					url:BASE_URL+'inventory/purchase/viewreq?securekey='+securekey,
					type:'POST',
					data:{id:id,_csrf:csrftoken},
					datatype:'json',
					success:function(data){
						 $(".modal-body").html(data);
					}
			});
        });
        });
</script> <?php //}  ?>