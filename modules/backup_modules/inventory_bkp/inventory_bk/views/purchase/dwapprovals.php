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
<style>
	 .dt-buttons {
	float: right;
	margin-left: 27px;
	}.buttons-print{background: #ccc;cursor:pointer;}
	</style>
                   
               <div class="col-sm-12">
    <table id="dataTableShowP" class="display" style="width:100%">
        <thead>
            <tr>
               
                <th>Voucher No</th>
                <th>Employee</th>
                <th>Item (Req Qty)</th>
                <th>Available(Qty)</th> 
                <th>Cost per Item</th>
                <th>Project</th>
                <th>Status</th>
				 
				<th> </th>
				 
                
            </tr>
        </thead>
        <tbody>
            <?php  // echo "<pre>";print_r($data);die;
            if(!empty($data)){ $find = array("Y", "N");$replace = array("Yes", "No");
            foreach($data as $k=>$c){ 
			$c['item_name']=str_replace('inventory/purchase/viewdoc?file=amF2YXNjcmlwdDo=', '/javascript: class="black" onclick="return false;"',$c['item_name']);
			$c['item_name']=str_replace('/inventory/purchase/viewdoc?file=', Yii::$app->homeUrl.'inventory/purchase/viewdoc?file=',$c['item_name']);?>
            <tr>
                
                <td align="center">
				<p id="<?=$c['id']?>" class="model_<?=$c['id']?> footer-content">
				<?=$c['voucher_no'].'<hr style="margin: 2px 9px 2px 0px;">'.date('d-m-y',strtotime($c['request_date']));?>
				</p>
				</td>
                <td id="Employee"><?=$c['fname']?></td>
                <td id="item_name"><?=$c['item_name'];?></td>
				<?php  $avail='No';if($c['qty_avail']=='Y'){$avail='Yes ('.$c['qty'].')';}?>
                 <td id="qty"><?=str_replace($find, $replace,$c['qty_avail_ll']);?></td><?php //} ?>
                <td id="approx_cost"><?=$c['approx_cost']?></td>
                <td id="project"><?php if($c['project']){echo $c['project'];}else{echo "---";}?></td>
				<td align="center"> <p id="<?=$c['id']?>"> <?php  echo $c['Status']; ?> </p></td>
				
				<td>
				&nbsp;&nbsp;&nbsp;<a target="_blank" href="download?securekey=<?=$menuid?>&id=<?=base64_encode($c['id'])?>">
					<img src="<?=Yii::$app->homeUrl?>images/pdf.png" title="Download"  style="cursor:pointer;width: 25px;">  </a>
				</td>
				
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