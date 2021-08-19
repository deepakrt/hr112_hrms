<div class="" style="font-family: freeserif;">
	<?php 
 			$check=$total_approx_cost="";
			$tabledata='';
            foreach($data as $k=>$c){ 
			if($c['is_active']=='Y'){
			$qty_buy=$c['quantity_required']-$c['qty'];
			$approx_cost=$qty_buy*$c['approx_cost'];
			$total_approx_cost=$total_approx_cost+$approx_cost;
			$kk=$k+1;
            $tabledata.="<tr><td style='border: 1px solid #ccc;'>".$kk."</td><td style='border: 1px solid #ccc;'>".$c['item_name']."</td><td style='border: 1px solid #ccc;'>".$qty_buy."</td><td style='border: 1px solid #ccc;'>".$c['approx_cost']."</td><td style='border: 1px solid #ccc;' width=30%>".substr($c['item_specification'],50)."</td><td style='border: 1px solid #ccc;text-align:right;'>".$approx_cost."</td></tr>";
			} }
	$gst=$total_approx_cost*18/100;
	$total_approx_cost=$total_approx_cost+$gst;
		 $tabledata.="<tr><td style='border: 1px solid #ccc;'> </td><td style='border: 1px solid #ccc;'> </td><td style='border: 1px solid #ccc;'> </td><td style='border: 1px solid #ccc;'></td><td style='border: 1px solid #ccc;' width=30%>&nbsp;  </td><td style='border: 1px solid #ccc;'> </td></tr>";
		 $tabledata.="<tr><td style='border: 1px solid #ccc;'> </td><td style='border: 1px solid #ccc;'> </td><td style='border: 1px solid #ccc;'> </td><td style='border: 1px solid #ccc;'></td><td style='border: 1px solid #ccc;' width=30%>&nbsp;  </td><td style='border: 1px solid #ccc;'> </td></tr>";
		 $tabledata.="<tr><td style='border: 1px solid #ccc;'> </td><td style='border: 1px solid #ccc;'> </td><td style='border: 1px solid #ccc;'> </td><td style='border: 1px solid #ccc;'></td><td style='border: 1px solid #ccc;text-align:right;' width=30%>GST 18%  </td><td style='border: 1px solid #ccc;text-align:right;'>".$gst."</td></tr>";
		 $tabledata.="<tr><td style='border: 1px solid #ccc;'> </td><td style='border: 1px solid #ccc;'> </td><td style='border: 1px solid #ccc;'> </td><td style='border: 1px solid #ccc;'> </td><td style='border: 1px solid #ccc;text-align:right;' width=30%><b>Total </td><td style='border: 1px solid #ccc;text-align:right;'>".$total_approx_cost."</td></tr>";
	?>
  		<table id="table" class="display" cellpadding="2" style="width:100%;line-height: 20px;font-family: freeserif;">
        <thead>
		<tr>
				<td style="text-align:center;vertical-align: bottom;">
				 	<img src="<?=Yii::$app->homeUrl?>images/sb.png" width="100"/>
				</td>
 				<td width="70%" style="text-align:center;">
					<h2>प्रगत संगणक विकास केंद्र (सी-डैक )</h2>
				<h3>Center for Development of Advanced Computing (C-DAC)</h3>
				
						 
				</td>
 				<td style="text-align:center;vertical-align: top;">
				 <img src="<?=Yii::$app->homeUrl?>images/logo_cdac.png" width="70"/>
				</td>
				  
				 
             </tr>
			  <tr><td style="text-align:center;vertical-align: top;" colspan='3'>
				  <h4>(इलेक्ट्रॉनिक्स और सूचना प्रौद्योगिकी मंत्रालय (Meity) भारत सरकार का एक वैज्ञानिक संस्था) </h4>
				<h4>(A Scientific Society of the Ministry of Electronics & Information Technology (Meity), Govt of India)</h4>
					<br><h3><b>FINANCIAL APPROVAL FOR PURCHASE OF ARTICLES </b></h3></td></tr>
			  <tr><td></td></tr>
			  <tr><td></td></tr>
			  <tr><td></td></tr>
			  <tr><td></td></tr>
			  <tr><td></td></tr>
			 <tr><td></td></tr>
             <tr><td></td></tr> <tr><td></td></tr>
        </thead>
        </table>
	 
     <table id="" class="table table-border" border='1' cellspacing='0' cellpadding="1" style="width:100%;font-size: 13px;margin: 35px 0 20px 0;">
        <thead>
            <tr>
				<th style='border: 1px solid #ccc;' align="left" width="5%">Sl No.</th>
				<th style='border: 1px solid #ccc;' align="left" width="20%">Articles</th>
				<th style='border: 1px solid #ccc;' align="left" width="10%">Qty </th>
 				<th style='border: 1px solid #ccc;' align="left" width="15%">Rate</th>
				<th style='border: 1px solid #ccc;' align="left" width="25%">Name of the Firm</th>
				<th style='border: 1px solid #ccc;' align="left" width="25%">Amount</th>
				 
             </tr>
        </thead>
        <tbody>
            <?=$tabledata;?>
        </tbody>
    </table>  
	 <div class="form-group col-sm-12" style="width:100%;padding: 70px 0 0 0;">
		<table id="table" class="display" cellpadding="2" style="font-size: 13px;margin: 10px;width:100%">
        <thead>
		<tr> <td colspan='2'>Payment Terms:- </td> </tr>
		<tr> <td colspan='2'>1. Quotation were invited - The last date of receipt of quotations was ................................................................</td></tr>
		<tr> <td colspan='2'>2. A comparative statement fo quotations, may please be seen at page No ........................................................ </td> </tr>
		<tr> <td colspan='2'>3. The particulars of previous purchase are given on the indent from at page No ..............................................</td></tr>
			<tr><td></td></tr>
			<tr><td></td></tr>
		<tr> <td colspan='2'>BASIS OF PURCHASE :- 1. Lowest quotation 2. Quality/ Availablity 3. Only Quoting firm 4. Sole Manufacturers/Distributors. 5. Running rate contract with DGS&D 6. Government undertaking 7. Proprietary items as per justification given at Page No ..........................................................................................................<br><br> Head Material Management Group & Executive Director. Competent Authority may please approve and placing of orders valued at approximately <b>Rs <?=$total_approx_cost?></b> (Rupees <?=Yii::$app->inventory->get_amount_in_words($total_approx_cost)?>) Saction the 
			above expendityre to budget sub head <b> <?php if($maindata['project']!=''){ echo $maindata['project'];}else{echo "N/A";}?> </b> Plan/Non plan/Trg/R&D/Sponsored Project/Business Development Project.  </td> </tr>
			
		 
		 
			<tr><td></td></tr><tr><td></td></tr><tr><td></td></tr><tr><td></td></tr>
		<tr> <td>Purchase Executive/Asstt. Purchase</td>
			<td style="text-align:right;vertical-align: top;">Senior Purchase Officer</td> </tr><tr><td></td></tr><tr><td></td></tr>
		<tr> <td>Head MMG </td><td style="text-align:right;vertical-align: top;">Manager (Finance)<br>(Pre-Audited)</td> </tr><tr><td></td></tr><tr><td></td></tr>
		<tr><td style="text-align:center;vertical-align: top;" colspan='2'>Executive Director</td>
		</tr>  
        </thead>
        </table> 
    </div>
		  
		 
		  
 </div>