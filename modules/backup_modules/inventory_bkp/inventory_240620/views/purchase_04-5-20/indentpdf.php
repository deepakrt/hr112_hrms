<div class="" style="font-family: freeserif;">
	<?php 
 			$check=$total_approx_cost="";
			$tabledata='';
            foreach($data as $k=>$c){ 
			$qty_buy=$c['quantity_required']-$c['qty'];
			$approx_cost=$qty_buy*$c['approx_cost'];
			$total_approx_cost=$total_approx_cost+$approx_cost;
			$kk=$k+1;
            $tabledata.="<tr><td>".$kk."</td><td>".$c['item_name']."</td><td>".$qty_buy."</td><td>".$approx_cost."</td><td width=30%>".substr($c['item_specification'],50)."</td><td>".$c['purpose']."</td></tr>";
              }   ?>
  		<table id="table" class="display" cellpadding="2" style="width:100%;line-height: 20px;font-family: freeserif;">
        <thead>
		<tr>
				<td style="text-align:center;vertical-align: top;">
				 	<!--img src="<?=Yii::$app->homeUrl?>images/sb.png" width="80"/-->
				</td>
 				<td style="text-align:center;"><h2>प्रगत संगणक विकास केंद्र</h2>
					<h5>ए -३४  औद्योगिक क्षेत्र, फेज 8, मोहाली-160071 (चंडीगढ़), पंजाब, भारत</h5>
					<h3>Center for Development of Advanced Computing</h3>
					<h5>A-34 Industrial Area, Phase VIII, Mohali (Chandigarh)</h5>
						<?php if($total_approx_cost<25000){ ?>
					<h5><b>REQUISITION FOR PURCHASE OF ITEM</b></h5>
						<?php }else{ ?>
					<h5>मांग पत्र /<b>INDENT</b></h5>
						<?php } ?>
				</td>
 				<td style="text-align:center;vertical-align: top;">
				 <!--img src="<?=Yii::$app->homeUrl?>images/logo_cdac.png" width="60"/-->
				</td>
				  
				 
             </tr>
			  <tr><td></td></tr>
			  <tr><td></td></tr>
			  <tr><td></td></tr>
			  <tr><td></td></tr>
			  <tr><td></td></tr>
			  <tr><td></td></tr>
			 <tr><td></td></tr>
            <tr> 
				<td style="font-size: 14px;"><b>क्रमांक /Sr No:</b> <?=$maindata['voucher_no']?></td>
 				<td style="font-size: 14px;"><b>परियोजना /Project:</b> <?php if($maindata['flag']=='9' && $maindata['project']!=''){ echo $maindata['project'];}else{echo "N/A";}?></td>
 				<td style="font-size: 14px;"><b>दिनांक /Date:</b> <?=date('d-m-Y',strtotime($maindata['request_date']));?></td>
			  </tr>  <tr><td></td></tr> <tr><td></td></tr>
        </thead>
        </table>
	 
     <table id="table" class="display" cellpadding="2" style="width:100%;font-size: 13px;margin: 35px 0 20px 0;">
        <thead>
            <tr>
				<th align="left" width="5%">#</th>
				<th align="left" width="20%">Item Name</th>
				<th align="left" width="10%">Req Qty </th>
 				<th align="left" width="15%">Approx Cost</th>
				<th align="left" width="25%">Item Specification</th>
				<th align="left" width="25%">Purpose</th>
				 
             </tr>
        </thead>
        <tbody>
            <?=$tabledata;?>
        </tbody>
    </table>  
	 <div class="form-group col-sm-12" style="width:100%;padding: 70px 0 0 0;">
		<table id="table" class="display" cellpadding="2" style="font-size: 13px;margin: 10px;width:100%">
        <thead>
		<tr> <td><b>Approximate Total Cost:</b> <?=$total_approx_cost?>/-</td> </tr>
		<tr> <td width="65%"><b>In Words:</b> <?=Yii::$app->inventory->get_amount_in_words($total_approx_cost)?>.</td>
		<?php if($maindata['flag']=='9' || $maindata['flag']=='11'){ ?>
					<td align="left"> <b> Approved by StoreInc</td>
				<?php } ?>
		</tr>
		<?php $dept=" ( ".Yii::$app->inventory->get_empdept($maindata['emp_code'])." )";?>
		<tr><td><b>Indented By:</b> <?=$maindata['fname'].$dept?></td>
		<?php if($maindata['flag']=='9' && $maindata['project']!=''){ ?>
			<td align="left"> <b> Approved by Finance Manager</td>
			<?php } ?></tr>
		<tr><td><b>Recomended By (HOD):</b> <?php echo Yii::$app->inventory->get_empname($maindata['HOD_ID']);?></td> 
			<td align="left"><b><?=$maindata['Status']?></b>
			<?php if($maindata['CH_remarks']!=''){ ?><b>Remarks: </b><?php echo $maindata['CH_remarks'];}  ?></td>
		</tr>
			<?php if($maindata['project']){ ?>
		<tr>
			<td>Funds are available in Project <b><?=$maindata['project']?></b>.</td>
		</tr>
		<tr>
				<td style="display: flex;">
				<input type="hidden" id="req_id" name="req_id" value='<?=$maindata['id']?>'/>
				<b>Project Heads:</b> <?php  echo $maindata['project_head'];   ?>
			    </td>
		</tr>  <?php } ?>
        </thead>
        </table> 
    </div>
		  
		 
		  
 </div>