<?php
namespace app\components;
use Yii;
use yii\base\Component;
use yii\web\Controller;
use yii\base\InvalidConfigException;
use yii\db\Query;
use yii\web\Session;
use yii\db\mssql\PDO;
use yii\base\Security;
class Inventoryutility extends Component {
	
	public function get_empname($eid){
		$connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `get_employees`(:param_employee_code)";
		$command = $connection->createCommand($sql); 
		$command->bindValue(':param_employee_code', $eid);
        $result=$command->queryOne();
        $connection->close();
		//print_r($result);die;
        return $result['fname'];   
	}
   
	public function get_empdept($eid){
		$connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `get_employees`(:param_employee_code)";
		$command = $connection->createCommand($sql); 
		$command->bindValue(':param_employee_code', $eid);
        $result=$command->queryOne();
        $connection->close();
		// print_r($result);die;
        return $result['dept_name'];   
	}
    public function get_alldept(){
		$connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `get_dept`('')";
		$command = $connection->createCommand($sql); 
        $result=$command->queryAll();
        $connection->close();
        return $result;   
	}
	public function get_all_supplier(){
		$connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `store_get_supplier`()";
		$command = $connection->createCommand($sql); 
        $result=$command->queryAll();
        $connection->close();
        return $result;   
	}
	public function get_dept_emp($deptid){
		$connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `get_dept_emp`(:param_dept)";
		$command = $connection->createCommand($sql); 
		$command->bindValue(':param_dept', $deptid);
        $result=$command->queryAll();
        $connection->close();
        return $result;   
	}
    public function get_groups(){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `store_get_groups`()";
		$command = $connection->createCommand($sql); 
        $result=$command->queryAll();
        $connection->close();
        return $result;       
    }
    public function get_category(){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `store_get_category`()";
		$command = $connection->createCommand($sql); 
        $result=$command->queryAll();
        $connection->close();
        return $result;       
    }
    public function get_cost_centre($deptid){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `store_get_cost_centre`(:param_dept)";
		$command = $connection->createCommand($sql); 
		$command->bindValue(':param_dept', $deptid);
        $result=$command->queryAll();
        $connection->close();
        return $result;       
    }
    public function get_cat_item($cat_code){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `store_get_cat_item`(:cat_code)";
		$command = $connection->createCommand($sql); 
		$command->bindValue(':cat_code', $cat_code);
        $result=$command->queryAll();
        $connection->close();
        return $result;       
    }
    public function get_unit_master(){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `store_get_unit_master`()";
		$command = $connection->createCommand($sql); 
        $result=$command->queryAll();
        $connection->close();
        return $result;       
    }
    
    public function add_issue_request($data){
		extract($data);
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `store_issue_request`(:Division,:Emp_code,:Classification_Code,:Item_Cat_Code,:Item_Code,
				:Item_Type,:Measuring_Unit,:Quantity_Required,:Item_Purpose,:Remarks,:Role,:Flag,:FLA,  @Result)";
		$command=$connection->createCommand($sql); 
		$command->bindValue(':Division', $Division);
		//$command->bindValue(':Cost_Centre_Code', $Cost_Centre_Code);
		$command->bindValue(':Emp_code', $Emp_code);
		$command->bindValue(':Classification_Code', $Classification_Code);
		$command->bindValue(':Item_Cat_Code', $Item_Cat_Code);
		$command->bindValue(':Item_Code', $Item_Code);
		$command->bindValue(':Item_Type', $Item_Type);
		$command->bindValue(':Measuring_Unit', $Measuring_Unit);
		$command->bindValue(':Quantity_Required', $Quantity_Required);
		$command->bindValue(':Item_Purpose', $Item_Purpose);
		$command->bindValue(':Remarks', $Remarks);
		$command->bindValue(':Role', $Role);
		$command->bindValue(':Flag', $Flag);
		$command->bindValue(':FLA', $FLA);
		//$command->bindValue(':Qty_Approved', $Qty_Approved);
		//$command->bindValue(':Approval_Date', $Approval_Date);
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as res;")->queryScalar();
        $connection->close();
        return $valueOut;      
    }
	
	public function get_issue_request_status($eid){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `store_issue_request_status`(:emp_id)";
		$command = $connection->createCommand($sql); 
		$command->bindValue(':emp_id', $eid); 
        $result=$command->queryAll();
        $connection->close();
        return $result;       
    }
     
	public function get_all_hod(){
		$connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `get_all_hod`()";
		$command = $connection->createCommand($sql); 
        $result=$command->queryAll();
        $connection->close();
        return $result;  
	}
	public function pending_issue_requests($param_role,$param_e_id){
		$connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `store_pending_issue_requests`(:param_role,:param_e_id)";
		$command = $connection->createCommand($sql); 
		$command->bindValue(':param_role', $param_role); 
		$command->bindValue(':param_e_id', $param_e_id); 
		$result=$command->queryAll();
        $connection->close();
        return $result;       
    }
	public function get_request_data($param_role,$param_e_id){
		$connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `store_get_ar_data`(:param_role,:param_e_id)";
		$command = $connection->createCommand($sql); 
		$command->bindValue(':param_role', $param_role); 
		$command->bindValue(':param_e_id', $param_e_id); 
		$result=$command->queryAll();
        $connection->close();
        return $result;       
    }
	
	public function updateqty($role,$voucher_no,$qty){
		$connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `store_request_updateqty`(:param_role,:param_voucher_no,:param_qty,  @Result)";
		$command = $connection->createCommand($sql); 
		$command->bindValue(':param_role', $role); 
		$command->bindValue(':param_voucher_no', $voucher_no); 
		$command->bindValue(':param_qty', $qty); 
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as res;")->queryScalar();
        $connection->close();
        return $valueOut;     
    }
	
	public function apr_rej_irequest($PARAMID,$PARAMHOD_ID,$PARAMrole,$PARAMApproveReject) {
		$connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `store_issue_request_approve_reject`(:PARAMID,:PARAMHOD_ID,:PARAMrole,:PARAMApproveReject,  @Result)";
		$command = $connection->createCommand($sql); 
		$command->bindValue(':PARAMID', $PARAMID); 
		$command->bindValue(':PARAMHOD_ID', $PARAMHOD_ID); 
		$command->bindValue(':PARAMrole', $PARAMrole); 
		$command->bindValue(':PARAMApproveReject', $PARAMApproveReject); 
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as res;")->queryScalar();
        $connection->close();
        return $valueOut;
	}

public function issue_str_item($PARAMID,$PARAMrole) {
		$connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `store_issue_item`(:PARAMID,  @Result)";
		$command = $connection->createCommand($sql); 
		$command->bindValue(':PARAMID', $PARAMID); 
		//$command->bindValue(':PARAMrole', $PARAMrole); 
		$command->execute();
        $valueOut = $connection->createCommand("select @Result as res;")->queryScalar();
        $connection->close();
        return $valueOut;
	}

	 
	public function get_mat_receipt_tmp(){
		$connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `store_get_mat_receipt_tmp`(:PARAM_added_by)";
		$command = $connection->createCommand($sql); 
		$command->bindValue(':PARAM_added_by', Yii::$app->user->identity->e_id); 
		$result=$command->queryAll();
        $connection->close();
        return $result;       
    }
	
	public function delete_mat_receipt_tmp($ID){
		$connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `store_delete_mat_receipt_tmp`(:PARAM_ID)";
		$command = $connection->createCommand($sql); 
		$command->bindValue(':PARAM_ID', $ID);
        $command->execute();
        $connection->close();
        return 1;   
	}

	public function submit_mat_receipts(){
		$connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `store_submit_mat_receipts`(:Param_added_by)";
		$command = $connection->createCommand($sql); 
		$command->bindValue(':Param_added_by', Yii::$app->user->identity->e_id);
        $command->execute();
        $connection->close();
        return 1;   
	}
	 public function insert_material_receipt($data){
	 //echo "<pre>==";print_r($data);die; 
		extract($data);
		 if($Supplier_Code=='other'){
		 $Supplier_Code=NULL;
		 }
		 if($ITEM_CODE=='000'){
		 $ITEM_CODE=NULL;
		 }
		 if(!isset($ID)){$ID=NULL;}
		$PO_Date=date('Y-m-d',strtotime($PO_Date));
		$Memo_Date=date('Y-m-d',strtotime($Memo_Date));
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `store_insert_material_receipt`(:PARAM_temp_id,:PARAM_MRN_No,:PARAM_PO_no, :PARAM_PO_Date, :PARAM_Indent_no, :PARAM_Dept_code, :PARAM_Cost_code, :PARAM_Emp_code, :PARAM_added_by, :PARAM_Supplier_Code,:PARAM_Supplier_name, :PARAM_Supplier_address, :PARAM_Supplier_phone_no, :PARAM_Memo_no , :PARAM_Memo_Date , :PARAM_Receipt_mode , :PARAM_Consignment_no, :PARAM_Vehicle_no, :PARAM_CLASSIFICATION_CODE, :PARAM_ITEM_CAT_CODE, :PARAM_Remark, :PARAM_Description, :PARAM_SED, :PARAM_Octroi, :PARAM_Discount, :PARAM_Packing_Forword, :PARAM_Insurance, :PARAM_Cartage, :PARAM_Edu_Cess, :PARAM_QtyS, :PARAM_ED, :PARAM_Surcharge, :PARAM_Sale_tax_per, :PARAM_Sale_tax, :PARAM_Rate_per_unit, :PARAM_Measuring_Unit, :PARAM_QtyR, :PARAM_QtyO, :PARAM_ITEM_CODE, :PARAM_item_name, :PARAM_item_type,:PARAM_item_unit,  @Result)";
		$command=$connection->createCommand($sql); 
		$command->bindValue(':PARAM_temp_id', $ID);
		$command->bindValue(':PARAM_MRN_No', $MRN_No);
		$command->bindValue(':PARAM_PO_no', $PO_no);
		$command->bindValue(':PARAM_PO_Date', $PO_Date);
		$command->bindValue(':PARAM_Indent_no', $Indent_no);
		$command->bindValue(':PARAM_Dept_code', $Dept_code);
		$command->bindValue(':PARAM_Cost_code', $Cost_Centre_Code);
		$command->bindValue(':PARAM_Emp_code', $Emp_code);
		$command->bindValue(':PARAM_added_by', Yii::$app->user->identity->e_id);
		$command->bindValue(':PARAM_Supplier_Code', $Supplier_Code);
		 if(!isset($supplier_name)){$supplier_name=NULL;}
		 if(!isset($address)){$address=NULL;}
		 if(!isset($phoneno)){$phoneno=NULL;}
		$command->bindValue(':PARAM_Supplier_name', $supplier_name);
		$command->bindValue(':PARAM_Supplier_address', $address);
		$command->bindValue(':PARAM_Supplier_phone_no', $phoneno);
		 
		$command->bindValue(':PARAM_Memo_no', $Memo_no);
		$command->bindValue(':PARAM_Memo_Date', $Memo_Date);
		$command->bindValue(':PARAM_Receipt_mode', $Receipt_mode);
		$command->bindValue(':PARAM_Consignment_no', $Consignment_no);
		$command->bindValue(':PARAM_Vehicle_no', $Vehicle_no);
		$command->bindValue(':PARAM_CLASSIFICATION_CODE', $CLASSIFICATION_CODE);
		$command->bindValue(':PARAM_ITEM_CAT_CODE', $ITEM_CAT_CODE);
		$command->bindValue(':PARAM_Remark', $Remark);
		$command->bindValue(':PARAM_Description', $Description);
		$command->bindValue(':PARAM_SED', $SED);
		$command->bindValue(':PARAM_Octroi', $Octroi);
		$command->bindValue(':PARAM_Discount', $Discount);
		$command->bindValue(':PARAM_Packing_Forword', $Packing_Forword);
		$command->bindValue(':PARAM_Insurance', $Insurance);
		$command->bindValue(':PARAM_Cartage', $Cartage);
		$command->bindValue(':PARAM_Edu_Cess', $Edu_Cess);
		$command->bindValue(':PARAM_QtyS', $QtyS);
		$command->bindValue(':PARAM_ED', $ED);
		$command->bindValue(':PARAM_Surcharge', $Surcharge);
		$command->bindValue(':PARAM_Sale_tax_per', $Sale_tax_per);
		$command->bindValue(':PARAM_Sale_tax', $Sale_tax);
		$command->bindValue(':PARAM_Rate_per_unit', $Rate_per_unit);
		$command->bindValue(':PARAM_Measuring_Unit', $Measuring_Unit);
		$command->bindValue(':PARAM_QtyR', $QtyR);
		$command->bindValue(':PARAM_QtyO', $QtyO);
		$command->bindValue(':PARAM_ITEM_CODE', $ITEM_CODE);
		 if(!isset($item_name)){$item_name=NULL;}
		 if(!isset($item_type)){$item_type=NULL;}
		 if(!isset($units)){$units=NULL;}
		$command->bindValue(':PARAM_item_name', $item_name);
		$command->bindValue(':PARAM_item_type', $item_type);
		$command->bindValue(':PARAM_item_unit', $Measuring_Unit);
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as res;")->queryScalar();
        $connection->close();
        return $valueOut;      
    }
	
	public function update_material_receipt($data){
		// echo "<pre>==";print_r($data);die; 
		extract($data);
		if($Supplier_Code=='other'){
		 $Supplier_Code=NULL;
		 }
		$PO_Date=date('Y-m-d',strtotime($PO_Date));
		$Memo_Date=date('Y-m-d',strtotime($Memo_Date));
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `store_update_mat_receipt`(:PARAM_accessid,:PARAM_PO_no, :PARAM_PO_Date, :PARAM_Indent_no, :PARAM_Dept_code, :PARAM_Cost_code, :PARAM_Emp_code, :PARAM_added_by, :PARAM_Supplier_Code, :PARAM_Memo_no , :PARAM_Memo_Date , :PARAM_Receipt_mode , :PARAM_Consignment_no, :PARAM_Vehicle_no, :PARAM_CLASSIFICATION_CODE, :PARAM_ITEM_CAT_CODE, :PARAM_Remark, :PARAM_Description, :PARAM_SED, :PARAM_Octroi, :PARAM_Discount, :PARAM_Packing_Forword, :PARAM_Insurance, :PARAM_Cartage, :PARAM_Edu_Cess, :PARAM_QtyS, :PARAM_ED, :PARAM_Surcharge, :PARAM_Sale_tax_per, :PARAM_Sale_tax, :PARAM_Rate_per_unit, :PARAM_Measuring_Unit, :PARAM_QtyR, :PARAM_QtyO, :PARAM_ITEM_CODE,  @Result)";
		$command=$connection->createCommand($sql); 
		$command->bindValue(':PARAM_accessid', $ID);
		$command->bindValue(':PARAM_PO_no', $PO_no);
		$command->bindValue(':PARAM_PO_Date', $PO_Date);
		$command->bindValue(':PARAM_Indent_no', $Indent_no);
		$command->bindValue(':PARAM_Dept_code', $Dept_code);
		$command->bindValue(':PARAM_Cost_code', $Cost_Centre_Code);
		$command->bindValue(':PARAM_Emp_code', $Emp_code);
		$command->bindValue(':PARAM_added_by', Yii::$app->user->identity->e_id);
		$command->bindValue(':PARAM_Supplier_Code', $Supplier_Code);
		/*if(!isset($supplier_name)){$supplier_name=NULL;}
		 if(!isset($address)){$address=NULL;}
		 if(!isset($phoneno)){$phoneno=NULL;}
		$command->bindValue(':PARAM_Supplier_name', $supplier_name);
		$command->bindValue(':PARAM_Supplier_address', $address);
		$command->bindValue(':PARAM_Supplier_phone_no', $phoneno);*/
		
		$command->bindValue(':PARAM_Memo_no', $Memo_no);
		$command->bindValue(':PARAM_Memo_Date', $Memo_Date);
		$command->bindValue(':PARAM_Receipt_mode', $Receipt_mode);
		$command->bindValue(':PARAM_Consignment_no', $Consignment_no);
		$command->bindValue(':PARAM_Vehicle_no', $Vehicle_no);
		$command->bindValue(':PARAM_CLASSIFICATION_CODE', $CLASSIFICATION_CODE);
		$command->bindValue(':PARAM_ITEM_CAT_CODE', $ITEM_CAT_CODE);
		$command->bindValue(':PARAM_Remark', $Remark);
		$command->bindValue(':PARAM_Description', $Description);
		$command->bindValue(':PARAM_SED', $SED);
		$command->bindValue(':PARAM_Octroi', $Octroi);
		$command->bindValue(':PARAM_Discount', $Discount);
		$command->bindValue(':PARAM_Packing_Forword', $Packing_Forword);
		$command->bindValue(':PARAM_Insurance', $Insurance);
		$command->bindValue(':PARAM_Cartage', $Cartage);
		$command->bindValue(':PARAM_Edu_Cess', $Edu_Cess);
		$command->bindValue(':PARAM_QtyS', $QtyS);
		$command->bindValue(':PARAM_ED', $ED);
		$command->bindValue(':PARAM_Surcharge', $Surcharge);
		$command->bindValue(':PARAM_Sale_tax_per', $Sale_tax_per);
		$command->bindValue(':PARAM_Sale_tax', $Sale_tax);
		$command->bindValue(':PARAM_Rate_per_unit', $Rate_per_unit);
		$command->bindValue(':PARAM_Measuring_Unit', $Measuring_Unit);
		$command->bindValue(':PARAM_QtyR', $QtyR);
		$command->bindValue(':PARAM_QtyO', $QtyO);
		$command->bindValue(':PARAM_ITEM_CODE', $ITEM_CODE);
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as res;")->queryScalar();
        $connection->close();
        return $valueOut;      
    }
	
    public function get_new_rm_no($flag){
		$connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `store_get_new_rm_no`(:PARAM_flag)";
		$command = $connection->createCommand($sql); 
		$command->bindValue(':PARAM_flag', $flag);
        $result=$command->queryAll();
        $connection->close();
        return $result;   
	}
	
	 public function get_new_mrn_no(){
		$connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `store_get_new_rm_no`(NULL)";
		$command = $connection->createCommand($sql); 
		$result=$command->queryOne();
        $connection->close();
        return $result;   
	}
	
	
	 public function get_mrn_records($mrno,$rno=NULL,$flag=1){
		$connection= Yii::$app->db;
        $connection->open();
        $sql =" CALL `store_get_mrn_records`(:PARAM_MRN_no,:PARAM_accessid,:PARAM_flag)";
		$command = $connection->createCommand($sql); 
		$command->bindValue(':PARAM_MRN_no', $mrno);
		$command->bindValue(':PARAM_accessid', $rno);
		$command->bindValue(':PARAM_flag', $flag);
        $result=$command->queryAll();
        $connection->close();
        return $result;   
	}
	
	 public function get_mat_receipt_detail($mrno,$rno=NULL){
		$connection= Yii::$app->db;
        $connection->open();
        $sql =" CALL `store_get_mat_receipt_detail`(:PARAM_id,:PARAM_MRN_no)";
		$command = $connection->createCommand($sql); 
		$command->bindValue(':PARAM_MRN_no', $mrno);
		$command->bindValue(':PARAM_id', $rno);
        $result=$command->queryOne();
        $connection->close();
        return $result;   
	}
	
	 public function get_mat_receipt_tmp_detail($mrno,$rno=NULL){
		$connection= Yii::$app->db;
        $connection->open();
        $sql =" CALL `store_get_mat_receipt_tmp_detail`(:PARAM_id,:PARAM_MRN_no)";
		$command = $connection->createCommand($sql); 
		$command->bindValue(':PARAM_MRN_no', $mrno);
		$command->bindValue(':PARAM_id', $rno);
        $result=$command->queryOne();
        $connection->close();
        return $result;   
	}
	
	 public function get_mat_records($mrno,$rno=NULL){
		$connection= Yii::$app->db;
        $connection->open();
        $sql =" CALL `store_get_mat_records`(:PARAM_MRN_no,:PARAM_accessid)";
		$command = $connection->createCommand($sql); 
		$command->bindValue(':PARAM_MRN_no', $mrno);
		$command->bindValue(':PARAM_accessid', $rno);
        $result=$command->queryAll();
        $connection->close();
        return $result;   
	}
	
	public function update_mat_records($data){
 		extract($data);
         $connection=Yii::$app->db;
        $connection->open();
        $sql =" CALL `store_update_mat_inspection`(:PARAM_MRN_No, :PARAM_accessid,:PARAM_Qty_Accepted,  @Result)";
		$command=$connection->createCommand($sql); 
		$command->bindValue(':PARAM_MRN_No', $MRN_No);
		$command->bindValue(':PARAM_accessid', $rno);
 		$command->bindValue(':PARAM_Qty_Accepted', $qty_accepted);
 
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as res;")->queryScalar();
        $connection->close();
        return $valueOut; 
	}
	
	public function update_mat_records_by_store($data){
		extract($data);
		$insp_date=date('Y-m-d',strtotime($insp_date));
        $connection=Yii::$app->db;
        $connection->open();
        $sql =" CALL `store_update_mat_inspection_bystore`(:PARAM_MRN_No, :PARAM_accessid, :PARAM_Inspection_Date, :PARAM_Rejection_Reason, :PARAM_Qty_Accepted, :PARAM_Qty_Rejected, :PARAM_Committee_member , :PARAM_Inspected_by_comitee,  @Result)";
		$command=$connection->createCommand($sql); 
		$command->bindValue(':PARAM_MRN_No', $MRN_No);
		$command->bindValue(':PARAM_accessid', $rno);
		$command->bindValue(':PARAM_Inspection_Date', $insp_date);
		$command->bindValue(':PARAM_Qty_Accepted', $qty_accepted);
		$command->bindValue(':PARAM_Qty_Rejected', $QtyRej);
		$command->bindValue(':PARAM_Rejection_Reason', $rreason);
		$command->bindValue(':PARAM_Committee_member', $cmember);
		$command->bindValue(':PARAM_Inspected_by_comitee', $ysno);
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as res;")->queryScalar();
        $connection->close();
        return $valueOut;      
    }

     /******************************************* Purchase *******************************************************/
	
	public function get_amount_in_words($number){
 				   $no = floor($number);
				   $point = round($number - $no, 2) * 100;
				   $hundred = null;
				   $digits_1 = strlen($no);
				   $i = 0;
				   $str = array();
				   $words = array('0' => '', '1' => 'one', '2' => 'two',
					'3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
					'7' => 'seven', '8' => 'eight', '9' => 'nine',
					'10' => 'ten', '11' => 'eleven', '12' => 'twelve',
					'13' => 'thirteen', '14' => 'fourteen',
					'15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
					'18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
					'30' => 'thirty', '40' => 'forty', '50' => 'fifty',
					'60' => 'sixty', '70' => 'seventy',
					'80' => 'eighty', '90' => 'ninety');
				   $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
				   while ($i < $digits_1) {
					 $divider = ($i == 2) ? 10 : 100;
					 $number = floor($no % $divider);
					 $no = floor($no / $divider);
					 $i += ($divider == 10) ? 1 : 2;
					 if ($number) {
						$plural = (($counter = count($str)) && $number > 9) ? 's' : null;
						$hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
						$str [] = ($number < 21) ? $words[$number] .
							" " . $digits[$counter] . $plural . " " . $hundred
							:
							$words[floor($number / 10) * 10]
							. " " . $words[$number % 10] . " "
							. $digits[$counter] . $plural . " " . $hundred;
					 } else $str[] = null;
				  }
				  $str = array_reverse($str);
				  $result = implode('', $str);
				  $points = ($point) ?
					" " . $words[$point / 10] . " " . 
						  $words[$point = $point % 10] : '';
		if($points){$points=$points . " Paise";}
				  $return= $result . "Rupees  ".$points;	
		return Ucfirst($return).' Only';
		
	}
	
	public function get_purchase_request_status($eid){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `store_purchase_request_status`(:emp_id)";
		$command = $connection->createCommand($sql); 
		$command->bindValue(':emp_id', $eid); 
        $result=$command->queryAll();
        $connection->close();
        return $result;       
    }
	
	public function pending_purchase_requests($param_role,$param_e_id,$req_id=NULL){
		$connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `store_pending_purchase_requests`(:param_role,:param_e_id,:req_id)";
		$command = $connection->createCommand($sql); 
		$command->bindValue(':param_role', $param_role); 
		$command->bindValue(':param_e_id', $param_e_id); 
		$command->bindValue(':req_id', $req_id); 
		if($req_id)
		$result=$command->queryOne();
		else
		$result=$command->queryAll();
        
		$connection->close();
        return $result;       
    }
	public function view_purchase_requests($id){
		$connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `store_view_purchase_requests`(:param_req_id,NULL)";
		$command = $connection->createCommand($sql); 
		$command->bindValue(':param_req_id', $id); 
 		$result=$command->queryAll();
        $connection->close();
        return $result; 
	}
	
	public function apr_rej_prequest($PARAMID,$PARAMHOD_ID,$PARAMrole,$PARAMApproveReject) {
		$connection=   Yii::$app->db;
        $connection->open();
		$eid =Yii::$app->user->identity->e_id;
        $sql =" CALL `store_purchase_request_approve_reject`(:PARAMID,:PARAMHOD_ID,:PARAMrole,:PARAMApproveReject,:PARAMEID,  @Result)";
		$command = $connection->createCommand($sql); 
		$command->bindValue(':PARAMID', $PARAMID); 
		$command->bindValue(':PARAMHOD_ID', $PARAMHOD_ID); 
		$command->bindValue(':PARAMrole', $PARAMrole); 
		$command->bindValue(':PARAMApproveReject', $PARAMApproveReject); 
		$command->bindValue(':PARAMEID', $eid); 
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as res;")->queryScalar();
        $connection->close();
		foreach(explode(",",$PARAMID) as $id){
			Yii::$app->inventory->store_purchase_request_logs($id,$PARAMApproveReject,$_POST);
		}
        return $valueOut;
	}
	
	public function get_p_request_data($param_role,$param_e_id){
		$connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `store_purchase_ar_data`(:param_role,:param_e_id)";
		$command = $connection->createCommand($sql); 
		$command->bindValue(':param_role', $param_role); 
		$command->bindValue(':param_e_id', $param_e_id); 
		$result=$command->queryAll();
        $connection->close();
        return $result;       
    }
	
	public function get_p_request_items(){
			
			$connection=   Yii::$app->db;
			$connection->open();
			$eid=Yii::$app->user->identity->e_id;
			$sql =" CALL `store_view_purchase_requests`(NULL,:param_emp_id)";
			$command = $connection->createCommand($sql); 
			$command->bindValue(':param_emp_id', $eid); 
			$result=$command->execute();
			$connection->close();
			return $result; 
	}
	
	public function update_req_id_with_item($reqid){
		
			$connection=   Yii::$app->db;
			$connection->open();
			$eid=Yii::$app->user->identity->e_id;
			$sql =" CALL `store_update_preq_id_with_item`(:param_emp_id,:param_req_id,NULL,NULL,NULL)";
			$command = $connection->createCommand($sql); 
			$command->bindValue(':param_emp_id', $eid); 
			$command->bindValue(':param_req_id', $reqid); 
			$result=$command->execute();
			$connection->close();
			
	}
	
	public function update_pur_req_FM_CH($flag,$req_id,$p_heads=NULL,$remarks=NULL){
			$connection=   Yii::$app->db;
			$connection->open();
			$e_id=Yii::$app->user->identity->e_id;
			$role=Yii::$app->user->identity->role;
			if($role!=6){
				if($role==17){
					$purc_status=$_POST['purc_status'];
					$mod='';
					if($purc_status=='Order-Declined'){$mod=',purchase_remarks=NULL ';}
					if(isset($_POST['purchase_mod']) && !empty($_POST['purchase_mod']) ){
						$mod=',purchase_mod="'.$_POST['purchase_mod'].'"';
						
						if($_POST['purchase_mod']!='Gem' && $_POST['purchase_mod']!='CPP'){
						if(isset($_POST['remarks']) && !empty($_POST['remarks']) ){
							$mod.=',purchase_remarks="'.trim($_POST['remarks']).'"';
							}
						}
					}
					
 					$sql="UPDATE `store_material_purchase_request` SET purchase_status='".$purc_status."' $mod where id=".$req_id; 
				}else{
					$chremarks=NULL;
					if(isset($_POST['chremarks']) && !empty($_POST['chremarks'])){
						$chremarks=trim($_POST['chremarks']);
					}
					$sql="UPDATE `store_material_purchase_request` SET flag=$flag, CH_remarks='$chremarks' , approved_by_CH='$e_id' where id=".$req_id;
				}
			}else{
			$sql="UPDATE `store_material_purchase_request` SET flag=$flag, approved_by_FM='$e_id', project_head='".$p_heads."' where id=".$req_id;
				
			}
			$command = $connection->createCommand($sql); 
			$result=$command->execute();
 			$connection->close();
			
			Yii::$app->inventory->store_purchase_request_logs($req_id,NULL,$_POST,$flag);
			return $result;
	}
	public function update_pur_req($mainstatus,$req_id,$flag){
			$connection=   Yii::$app->db;
			$connection->open();
			$e_id=Yii::$app->user->identity->e_id;
			$sql="UPDATE `store_material_purchase_request` SET flag=$flag, Approved_by_storeinc='$e_id', available_in_store='".$mainstatus."' where id=".$req_id;
			$command = $connection->createCommand($sql); 
			$result=$command->execute();
			$connection->close();
			Yii::$app->inventory->store_purchase_request_logs($req_id,NULL,$_POST,$flag);
			return $result;
	}
		
	public function store_purchase_request_logs($req_id,$PARAMApproveReject,$params,$flag=NULL){
			//echo "<pre>==";print_r($params);
			if(isset($params['_csrf'])){unset($params['_csrf']);}
			if(isset($params['req_id'])){unset($params['req_id']);}
			if(isset($params['v_nos'])){unset($params['v_nos']);}
			if(isset($params['auth_id'])){unset($params['auth_id']);}
			// echo "<pre>==";print_r($params);die; 
			$params=json_encode($params);
			$connection=   Yii::$app->db;
			$connection->open();
			$eid=Yii::$app->user->identity->e_id;
			$role=Yii::$app->user->identity->role;
			$sql =" CALL `store_material_purchase_request_logs`(:user_id,:v_roleid,:req_id,:PARAMApproveReject,:params,:flag)";
			$command = $connection->createCommand($sql); 
			$command->bindValue(':user_id', $eid); 
			$command->bindValue(':v_roleid', $role); 
			$command->bindValue(':req_id', $req_id); 
			$command->bindValue(':PARAMApproveReject', $PARAMApproveReject); 
			$command->bindValue(':params', $params); 
			$command->bindValue(':flag', $flag); 
			$result=$command->execute();
			$connection->close();
			return $result;
			
	}
	
	public function update_pur_req_temp($status,$avail_qty=NULL,$item_id){
			$connection=   Yii::$app->db;
			$connection->open();
			$eid=Yii::$app->user->identity->e_id;
			$sql =" CALL `store_update_preq_id_with_item`(NULL,NULL,:PARAM_qty_avail,:PARAM_qty,:PARAM_item_id)";
			$command = $connection->createCommand($sql); 
			$command->bindValue(':PARAM_qty_avail', $status); 
			$command->bindValue(':PARAM_qty', $avail_qty); 
			$command->bindValue(':PARAM_item_id', $item_id); 
			$result=$command->execute();
			$connection->close();
			return $result;
			
	}
	
	public function get_pur_temp_item(){
		 
			$connection=   Yii::$app->db;
			$connection->open();
			$eid=Yii::$app->user->identity->e_id;
			$reqid=0;
			$sql =" CALL `store_view_purchase_requests`(:param_req_id,:param_emp_id)";
			$command = $connection->createCommand($sql); 
			$command->bindValue(':param_req_id', $reqid); 
			$command->bindValue(':param_emp_id', $eid); 
			$result=$command->queryAll();
			$connection->close();
 			$html='';
			/*   $menuid = Yii::$app->utility->decryptString($_GET['securekey']); 
			$menuid = Yii::$app->utility->encryptString($menuid);
 			foreach($result as $k=>$res){
					$html.='<tr>';
					$html.='<td>'.($k+1).'</td>';
					$html.='<td>'.$res['item_name'].'</td>';
					$html.='<td>'.$res['item_specification'].'</td>';
					$html.='<td>'.$res['purpose'].'</td>';
					$html.='<td>'.$res['quantity_required'].'</td>';
					$html.='<td>'.$res['approx_cost'].'</td>';
					$html.='<td><a target="_blank" href="'.Yii::$app->homeUrl.'inventory/view?securekey='.$menuid.'">Doc</a></td>';
					$html.='</tr>';
				} */
		  
			return $result;
		}    
}
