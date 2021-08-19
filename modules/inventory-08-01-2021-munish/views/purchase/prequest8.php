<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
//echo "<pre>";print_r(Yii::$app->user->identity);die;
?>                  
<div class="col-sm-12">
    <table id="dataTableShow1" class="display" cellpadding="2" style="width:100%">
        <thead>
            <tr>
                 <th>Voucher No</th>
                <th>Employee</th>
                <th>Project</th>
                <th>Item (Req Qty)</th>
                <th>Approx Cost</th>
				<th>Action</th>
             </tr>
        </thead>
        <tbody>
            <?php 
			$check="disabled='disabled'";
            if(!empty($data)){
				$check="";
            foreach($data as $k=>$c){ 
			$c['item_name']=str_replace('inventory/purchase/viewdoc?file=amF2YXNjcmlwdDo=', '/javascript: class="black" onclick="return false;"',$c['item_name']);
			$c['item_name']=str_replace('/inventory/purchase/viewdoc?file=', Yii::$app->homeUrl.'inventory/purchase/viewdoc?file=',$c['item_name']);?>
            <tr id="row_<?=$c['id']?>">
                 <td  id="voucher_no"><?=$c['voucher_no'].'<hr style="margin: 2px 9px 2px 0px;">'.date('d-m-y',strtotime($c['request_date']));?></td>
                <td id=""><?=$c['fname']?></td>
                <td id=""><?=$c['project']?></td>
                <td id="item_name"><?=$c['item_name'];?></td>
                <td id="approx_cost"><?=$c['approx_cost']?></td>
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
	
	$( "body" ).on( "click", ".getdetails", function() { 
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
	
	 $( "body" ).on( "click", ".apr_rej", function() { 
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