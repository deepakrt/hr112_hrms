<div class="" style="font-family: arial;">
	<?php  			
	 $tabledata='';
          $s = 1 ;  //$len = count($data);
            foreach($data as $k=>$c) { 
	     //$kk=$k+1;
             $kk=$k+1;   ?>
  	<table id="table" class="display" cellpadding="2" style="width:100%;line-height: 20px;font-family: arial;">
          <thead>
		<tr>
			<td style="text-align:left;vertical-align: top;" width="30%">
			   <img src="<?=Yii::$app->homeUrl?>images/logo_cdac.png" width="70"/>
			</td>
			<td style="text-align:right;" width="70%">
                        <h5 style="font-weight: normal;">Phones : (0172) : 2237052-55, 6619006</h5>
                        <h5 style="font-weight: normal;">Fax No. : (0172) - 2237050-51</h5>
		        <h2 style="text-transform:uppercase;">Center for Development of</h2>
                        <h2 style="text-transform:uppercase">Advanced Computing Mohali</h2>
                        <h6 style="font-weight: normal;">(A Scientific Society of the Ministry of Electronics & Information Technology Govt. of India)</h6>
			<h5>A-34 Industrial Area, Phase 8, Mohali-160 071 (Near Chandigarh)</h5>					
			</td>				
                </tr>
                <tr><td></td></tr><tr><td></td></tr><tr><td></td></tr><tr><td></td></tr><tr><td></td></tr><tr><td></td></tr><tr><td></td></tr>
		<tr><td colspan="2"><b>Ref. Notice Inviting Quotation.</b></td></tr>
                <tr><td></td></tr>
		<tr><td colspan="2">Dear Sir(s),</td></tr>
                <tr><td colspan="2"><?=$c['Supplier_name']?>,</td></tr>
                <tr><td colspan="2"><?=$c['Supplier_address']?>,</td></tr>
                <tr><td colspan="2">Contact No. <?=$c['Phone_no']?>,</td></tr>
                <tr><td></td></tr>
                <tr><td colspan="2" style="font-size:13px">       We are interested in the purchase of the articles mentioned below. Please sent your quotations in double cover, inner cover should be sealed & should indicate (i) Name of the Material (ii) Reference of this letter & (iii) date of opening of quotations. The outer cover should bear only address without any indication that there is a quotation within. Your quotation should reach this office on or before _________ (12 Noon) to be opened at 3:00 p.m. on the same day.</td></tr>	
               <tr><td></td></tr>  
            <tr> 
		<td style="font-size: 14px;"><b>Indent No:</b> <?=$c['Indent_no']?></td>
	     </tr>  
        </thead>
        </table>
	 
     <table id="table" class="display" cellpadding="2" style="width:100%;font-size: 13px;margin: 35px 0 20px 0;" border="1">
        <thead>
            <tr>
		<th align="left" width="5%">Sr No.</th>
                <th align="left" width="20%">Item Name</th>
		<th align="left" width="20%">Description</th>
		<th align="left" width="10%">Quantity</th>
             </tr>
        </thead>
        <tbody>
            <tr><td><?=$s?></td>
            <td><?=$c['Item_name']?></td>
            <td width=30%><?=$c['Item_description']?></td>
            <td><?=$c['Req_Qty']?></td>          
            </tr>          
        </tbody>
    </table>  
	 <div class="form-group col-sm-12" style="width:100%;">
	   <table id="table" class="display" cellpadding="2" style="font-size: 13px;margin: 10px;width:100%; font-family: arial;">
		<tbody>
                  <tr>
			<tr><td><b>Price quoted should be for :-</b></td><td style="text-align:right"><b>Your faithfully</b></td> </tr>
			<tr> <td width="65%">(a)	Free delivery i.e F.O.R. at C-DAC, Mohali.</td> </tr>
			<tr><td>(b)	Please note that rates quoted must include freight , cartage, insurance etc., if any No extra charges for freight, cartage, insurance etc. will be payable by the centre. Octroi  & Sales Tax will be extra payable only if, specified in quotation.</td></tr>
                        <tr><td>(c)	The validity of quotation should be more than 2 months.</td></tr>
                        <tr><td style="text-align:right" colspan="2"><b>Head, Material Management Group</b></td></tr>
                   </tr>			
		    <tr>
                        <tr><td width="70%"><b>Note:-</b>(i) While submitting quotation please take care of instruction overleaf.</td></tr>
			<tr><td style="padding-left:40px">(ii)	Late/Delayed Quotations will not be considered at all.</td></tr>
		    </tr>
                 </tbody>
          </table> 
     </div>
  <newpage>
    <table id="table2" class="display" cellpadding="2" style="font-size: 13px;margin:10px;width:100%; font-family: arial; ">
		<tbody>
                  <tr>
                    <tr><td style="text-align:center;font-size: 16px;" colspan="2"><b>Instructions</b></td></tr>
		         <tr><td colspan="2"> 1.	We are interested in the material either of indigenous manufacture or of foreign make, available 	from ready stock. Any offer to supply on forward delivery basis under supplier own quota 	license will also be considered.</td></tr>
					 
				<tr><td colspan="2">	2.	Specific mention should be made of whether the delivery will be Ex-stock or will have to be 	imported and how much time will b required for delivery after placing of the order.</td></tr>
					
				<tr><td colspan="2">	3.	In case of supply order for the Scientific equipment/apparatus the date of delivery should be 	strictly adhered to otherwise the supply order is liable to be cancelled.</td></tr>
					
				<tr><td colspan="2">	4.	In case of supply order, for stores other than scientific equipment/apparatus, as time is the 	essence to this order, the date of delivery should be strictly adhered to otherwise the Director 	reserves the right not to accept delivery in part or full and to claim liquidated damages 	1% per 	week subject to maximum of 10% of the total value of supply order.</td></tr>
					
				<tr><td colspan="2">	5.	Payment will be made by Crossed/Accounts Payee Cheque / RTGS only after receipt of the 	material in good condition.</td></tr>
					
				<tr><td colspan="2">	6.	The acceptance of the quotation will rest with the Director who does not bind himself to accept to Lowest quotation and reserves the right himself to reject without assigning any reason.</td></tr>
					
				<tr><td colspan="2">	7.	Any dispute arising out of this quote will fall under Mohali Jurisdiction only.</td></tr>
	<tr><td></td></tr><tr><td></td></tr><tr><td></td></tr><tr><td></td></tr>	            
	</tr>
            <tr>
                 <tr><td style="text-align:left;font-size: 16px;" colspan="2"><b>Important Notes:-</b></td></tr>
                    <tr> 
                    <td colspan="2"> 1. If you are on D.G.S & D rate contract, please quote D.G.S & D rate and rate contract number 	
	enclosing a copy thereof.
                      </td>
                     </tr>
                   <tr>
                    <td colspan="2">2. For an offer of imported material, please give full break up of your rates supported by S.T.C. 	formula or your Principal's Invoice / Quotations as the case may be.
                        </td>
                   </tr>
		<tr>
                  <td colspan="2">
		3.If required C-DAC, Mohali can provide Custom Duty / Central Excise Duty exemption certificate 	to the vender.</td></tr>
		<tr><td colspan="2">
		4.Please indicate your Permanent Income Tax Number on your Performa Invoice/Bill.
                </td></tr>
		<tr><td></td></tr><tr><td></td></tr><tr><td></td></tr>		     
          </tr>	
                 	
	 </tbody>
        </table> 
<newpage>
<?php } ?>			  
 </div>
