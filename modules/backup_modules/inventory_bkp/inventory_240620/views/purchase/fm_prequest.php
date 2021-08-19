<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
//echo "<pre>";print_r(Yii::$app->user->identity);die;
$role = Yii::$app->user->identity->role;
?>                  
<div class="col-sm-12">
    <table id="dataTableShow1" class="display" cellpadding="2" style="width:100%">
        <thead>
            <tr>
                 <th>Voucher No</th>
                <th>Employee</th>
                <th>Project</th>
                <th>Item (Req Qty)</th>
				<?php if($role>=6){ ?>
                <th>Available(Qty)</th><?php } ?>
                <th>Approx Cost </th>
				<th>Action</th>
             </tr>
        </thead>
        <tbody>
            <?php 
			$check="disabled='disabled'";
            if(!empty($data)){
				$check="";
            foreach($data as $k=>$c){  
			$c['item_name']=str_replace('inventory_docspath/javascript:', 'javascript:',$c['item_name']);
			$c['item_name']=str_replace('inventory_docspath', Yii::$app->homeUrl.Inventory_Docs,$c['item_name']);
			$find = array("Y", "N");$replace = array("Yes", "No");
			?>
            <tr id="row_<?=$c['id']?>">
                <td id="voucher_no"><?=$c['voucher_no'].'<hr style="margin: 2px 9px 2px 0px;">'.date('d-m-y',strtotime($c['request_date']));?></td>
                <td id=""><?=$c['fname']?></td>
                <td id=""><?php if($c['project'] != '') {echo $c['project']; }else{ echo '---';} ?></td>
                <td id="item_name"><?=$c['item_name'];?></td>
				<?php if($role>=6){ ?>
                 <td id="qty"><?=str_replace($find, $replace,$c['qty_avail_ll']);?></td><?php } ?>
                <td id="tot_cost"><?=$c['tot_cost']?></td>
				<td align="center" data-toggle="modal" data-target="#myModal3"><p id="<?=$c['id']?>" class="model_<?=$c['id']?> getdetails footer-content"><img title="View" src="<?=Yii::$app->homeUrl?>images/view.png" style="cursor:pointer;width: 23px;">
					<?php /*$param='securekey='.$menuid.'&id='.$c['id'] ?>
					<a href="<?=Yii::$app->homeUrl?>inventory/purchase/viewreq?<?=$param?>"><img title="View" src="<?=Yii::$app->homeUrl?>images/view.png" style="width: 23px;"></a> <?php */ ?>
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
         <div class="modal-header" style="background: #DD7869;height: 25px;padding: 0 8px 0 0;">
          <h4 class="modal-title"> </h4>
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
	
	  $(".apr_rej").click(function(){
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
	 }); 
</script>