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
class Finance extends Component {
    
    public function get_emp_yearly_sal($param_employee_code, $status,$param_month=NULL, $param_year=NULL){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `get_emp_yearly_sal`(:param_employee_code, :param_status, :param_month, :param_year)";
	$command = $connection->createCommand($sql); 
        $command->bindValue(':param_employee_code', $param_employee_code);
        $command->bindValue(':param_status', $status);
        $command->bindValue(':param_month', $param_month);
        $command->bindValue(':param_year', $param_year);
        if(!empty($param_month) OR !empty($param_year)){
            $result=$command->queryOne();
        }else{
            $result=$command->queryAll();
        }
        
        $connection->close();
        return $result;       
    }
    
    public function fn_get_da_master(){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `fn_get_da_master`()";
	$command = $connection->createCommand($sql); 
        $result=$command->queryAll();
        $connection->close();
        return $result;       
    }
    public function fn_get_ta_master(){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `fn_get_ta_master`()";
	$command = $connection->createCommand($sql); 
        $result=$command->queryAll();
        $connection->close();
        return $result;       
    }
    public function fn_get_hra_master(){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `fn_get_hra_master`()";
	$command = $connection->createCommand($sql); 
        $result=$command->queryAll();
        $connection->close();
        return $result;       
    }
    public function fn_get_it_slab(){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `fn_get_it_slab`()";
	$command = $connection->createCommand($sql); 
        $result=$command->queryAll();
        $connection->close();
        return $result;       
    }
    
    public function fn_add_update_contingency($param_id, $param_employee_code, $param_claim_number, $param_project_id, $param_purpose, $param_details, $param_claimed_amt, $param_sanctioned_amt, $param_status, $param_action_by, $param_is_active)
    {
        
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `fn_add_update_contingency`(:param_role_id, :param_id, :param_employee_code, :param_claim_number, :param_project_id, :param_purpose, :param_details, :param_claimed_amt, :param_sanctioned_amt, :param_status, :param_action_by, :param_is_active, @Result)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_role_id', Yii::$app->user->identity->role);
        $command->bindValue(':param_id', $param_id);
        $command->bindValue(':param_employee_code', $param_employee_code);
        $command->bindValue(':param_claim_number', $param_claim_number);
        $command->bindValue(':param_project_id', $param_project_id);
        $command->bindValue(':param_purpose', $param_purpose);
        $command->bindValue(':param_details', $param_details);
        $command->bindValue(':param_claimed_amt', $param_claimed_amt);
        $command->bindValue(':param_sanctioned_amt', $param_sanctioned_amt);
        $command->bindValue(':param_status', $param_status);
        $command->bindValue(':param_action_by', $param_action_by);
        $command->bindValue(':param_is_active', $param_is_active);
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
    }
 
    public function fn_get_contingency($param_role_id, $param_claim_id, $param_employee_code, $param_status){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `fn_get_contingency`(:param_role_id, :param_claim_id, :param_employee_code, :param_status)";
	$command = $connection->createCommand($sql); 
        $command->bindValue(':param_role_id', $param_role_id);
        $command->bindValue(':param_claim_id', $param_claim_id);
        $command->bindValue(':param_employee_code', $param_employee_code);
        $command->bindValue(':param_status', $param_status);
        if(!empty($param_claim_id)){
            $result=$command->queryOne();
        }else{
            $result=$command->queryAll();
        }
        
        $connection->close();
        return $result; 
    }
    
    public function fn_add_update_tour_requisition($param_role_id, $param_req_id, $param_e_id, $param_dept_id, $param_project_id, $param_tour_type, $param_tour_location, $param_advance_required, $param_advance_amount, $param_sanctioned_adv_amount, $param_start_date, $param_end_date, $param_purpose, $param_sanctioned_by, $param_status){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `fn_add_update_tour_requisition`(:param_role_id, :param_req_id, :param_e_id, :param_dept_id, :param_project_id, :param_tour_type, :param_tour_location, :param_advance_required, :param_advance_amount, :param_sanctioned_adv_amount, :param_start_date, :param_end_date, :param_purpose, :param_sanctioned_by, :param_status, @Result)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_role_id', $param_role_id);
        $command->bindValue(':param_req_id', $param_req_id);
        $command->bindValue(':param_e_id', $param_e_id);
        $command->bindValue(':param_dept_id', $param_dept_id);
        $command->bindValue(':param_project_id', $param_project_id);
        $command->bindValue(':param_tour_type', $param_tour_type);
        $command->bindValue(':param_tour_location', $param_tour_location);
        $command->bindValue(':param_advance_required', $param_advance_required);
        $command->bindValue(':param_advance_amount', $param_advance_amount);
        $command->bindValue(':param_sanctioned_adv_amount', $param_sanctioned_adv_amount);
        $command->bindValue(':param_start_date', $param_start_date);
        $command->bindValue(':param_end_date', $param_end_date);
        $command->bindValue(':param_purpose', $param_purpose);
        $command->bindValue(':param_sanctioned_by', $param_sanctioned_by);
        $command->bindValue(':param_status', $param_status);
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
    }
    
    public function fn_get_tour_detail($param_status, $param_req_id=NULL, $param_e_id=NULL, $param_role_id=NULL){
        // IF value is null, using for employee
        if(empty($param_role_id)){
            $param_role_id = Yii::$app->user->identity->role;
        }
        
        if(empty($param_e_id)){
            $param_e_id = Yii::$app->user->identity->e_id;
        }
        
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `fn_get_tour_detail`(:param_role_id,:param_e_id,:param_status,:param_req_id)";
	$command = $connection->createCommand($sql); 
        $command->bindValue(':param_role_id', $param_role_id);
        $command->bindValue(':param_e_id', $param_e_id);
        $command->bindValue(':param_status', $param_status);
        $command->bindValue(':param_req_id', $param_req_id);
        if(!empty($param_req_id)){
            $result=$command->queryOne();
        }else{
            $result=$command->queryAll();
        }
        
        $connection->close();
        return $result; 
    }
    
    public function get_tour_type()
    {
        $projects = array('Business','Funded','Mission');
        $i=0;
        $list = array();
        foreach($projects as $key=>$val)
        {
            $id = base64_encode($val);
            $list[$i]['id']=$id;
            $list[$i]['tourtype']=$val;
            $i++;
        }
        return $list;
    }
    
    public function financialYrWithMonthYear($month, $year){
        $currentYr = date('Y');
        if($month > 3 AND $year == $currentYr){
         $year1 = $year+1;
            $fYr = $year."-".$year1;
         }elseif($month <= 12 AND $year != $currentYr){
         $year1= $year+1;
            $fYr = "$year-$year1";
         }elseif($month <= 3 AND $year == $currentYr){
            $year1= $year-1;
            $fYr = "$year1-$year";
        }
        return $fYr;
    }
    
    public function financialYrListFromJoining(){
        $CurrentYr = $yr = date('Y');
        $m = date('m');
//        if($m >= 3){ $CurrentYr = $yr+1; }else{ $CurrentYr = $yr+1;}
        $join = Yii::$app->user->identity->joining_date;
        $yrss = $joinYr = date('Y', strtotime($join));
        $Joinmonth = date('m', strtotime($join));
//        if($Joinmonth >= 3){ $yrss = $joinYr+1; }else{ $yrss = $joinYr-1; }
        $finalList = array();
        
        for($i=$CurrentYr;$i>=$yrss;$i--){
            $ly = $i-1;	
            $fn= $ly."-".$i;
            array_push($finalList,$fn);
        }
        return $finalList;
    }
    
    public function getCurrentFY(){
        $yr = date('Y');
        $m = date('m');
        if($m >= 3){ $CurrentYr = $yr+1; }else{ $CurrentYr = $yr+1;}
        $CurDate = date('d-m-Y');
        $CurYr = date('Y', strtotime($CurDate));
        $Curmonth = date('m', strtotime($CurDate));
        if($Curmonth >= 3){ $yrss = $CurYr+1; }else{ $yrss = $CurYr-1; }
        $fn ="";
        for($i=$CurrentYr;$i>=$yrss;$i--){
            $ly = $i+1;	
            $fn= $i."-".$ly;
        }
        return $fn;
    }
    public function fn_get_bill_type($param_bill_type_id=NULL){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `fn_get_bill_type`(:param_bill_type_id)";
	$command = $connection->createCommand($sql); 
        $command->bindValue(':param_bill_type_id', $param_bill_type_id);
        if(!empty($param_bill_type_id)){
            $result=$command->queryOne();
        }else{
            $result=$command->queryAll();
        }
        $connection->close();
        return $result; 
    }
    public function fn_get_medical_entitlement($param_employee_code, $param_entitle_id){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `fn_get_medical_entitlement`(:param_employee_code, :param_entitle_id)";
	$command = $connection->createCommand($sql); 
        $command->bindValue(':param_employee_code', $param_employee_code);
        $command->bindValue(':param_entitle_id', $param_entitle_id);
        if(!empty($param_entitle_id)){
            $result=$command->queryOne();
        }else{
            $result=$command->queryAll();
        }
        
        $connection->close();
        return $result; 
    }
    public function fn_get_opd_claims($param_opd_id, $param_entitle_id, $param_employee_code, $param_status){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `fn_get_opd_claims`(:param_opd_id, :param_entitle_id, :param_employee_code, :param_status)";
	$command = $connection->createCommand($sql); 
        $command->bindValue(':param_opd_id', $param_opd_id);
        $command->bindValue(':param_entitle_id', $param_entitle_id);
        $command->bindValue(':param_employee_code', $param_employee_code);
        $command->bindValue(':param_status', $param_status);
        if(!empty($param_opd_id)){
            $result=$command->queryOne();
        }else{
            $result=$command->queryAll();
        }
        
        $connection->close();
        return $result; 
    }
    public function fn_get_opd_bill_details($param_opd_id, $param_employee_code, $param_bill_id){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `fn_get_opd_bill_details`(:param_opd_id, :param_employee_code, :param_bill_id)";
	$command = $connection->createCommand($sql); 
        $command->bindValue(':param_opd_id', $param_opd_id);
        $command->bindValue(':param_employee_code', $param_employee_code);
        $command->bindValue(':param_bill_id', $param_bill_id);
        if(!empty($param_bill_id)){
            $result=$command->queryOne();
        }else{
            $result=$command->queryAll();
        }        
        $connection->close();
        return $result; 
    }
    public function fn_get_tour_claim_details($param_claim_id, $param_status, $param_req_id, $e_id=NULL){
        if(empty($e_id)){
            $e_id = Yii::$app->user->identity->e_id;
        }
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `fn_get_tour_claim_details`(:param_role_id, :param_claim_id, :param_employee_code, :param_status, :param_req_id)";
	$command = $connection->createCommand($sql); 
        $command->bindValue(':param_role_id', Yii::$app->user->identity->role);
        $command->bindValue(':param_claim_id', $param_claim_id);
        $command->bindValue(':param_employee_code', $e_id);
        $command->bindValue(':param_status', $param_status);
        $command->bindValue(':param_req_id', $param_req_id);
        if(!empty($param_claim_id)){
            $result=$command->queryOne();
        }else{
            $result=$command->queryAll();
        }        
        $connection->close();
        return $result; 
    }
 
    public function fn_add_opd_claims($param_bill_id, $param_opd_id, $param_entitle_id, $param_employee_code, $param_status, $param_patient_type, $param_dependent_id, $param_bill_num, $param_bill_date, $param_bill_amt, $param_bill_type, $param_bill_issuer){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `fn_add_opd_claims`(:param_bill_id, :param_opd_id, :param_entitle_id, :param_employee_code, :param_status, :param_patient_type, :param_dependent_id, :param_bill_num, :param_bill_date, :param_bill_amt, :param_bill_type, :param_bill_issuer, @Last_opd_id)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_bill_id', $param_bill_id);
        $command->bindValue(':param_opd_id', $param_opd_id);
        $command->bindValue(':param_entitle_id', $param_entitle_id);
        $command->bindValue(':param_employee_code', $param_employee_code);
        $command->bindValue(':param_status', $param_status);
        $command->bindValue(':param_patient_type', $param_patient_type);
        $command->bindValue(':param_dependent_id', $param_dependent_id);
        $command->bindValue(':param_bill_num', $param_bill_num);
        $command->bindValue(':param_bill_date', $param_bill_date);
        $command->bindValue(':param_bill_amt', $param_bill_amt);
        $command->bindValue(':param_bill_type', $param_bill_type);
        $command->bindValue(':param_bill_issuer', $param_bill_issuer);
        $command->execute();
        $valueOut = $connection->createCommand("select @Last_opd_id as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
    }
    
    public function fn_delete_opd_bill($param_entitle_id, $param_opd_id, $param_billid, $param_employee_code, $param_delete_type){
        // param_delete_type should be Bill or Claim
        
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `fn_delete_opd_bill`(:param_entitle_id, :param_opd_id, :param_billid, :param_employee_code, :param_delete_type, @Result)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_entitle_id', $param_entitle_id);
        $command->bindValue(':param_opd_id', $param_opd_id);
        $command->bindValue(':param_billid', $param_billid);
        $command->bindValue(':param_employee_code', $param_employee_code);
        $command->bindValue(':param_delete_type', $param_delete_type);
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
    }
    public function fn_update_opd_claims($param_type, $param_sanc_amt, $param_action_by, $param_bill_id, $param_opd_id, $param_emp_code, $param_total_sanc_amt, $param_status){
        // param_delete_type should be Bill or Claim
        
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `fn_update_opd_claims`(:param_type, :param_sanc_amt, :param_action_by, :param_bill_id, :param_opd_id, :param_emp_code, :param_total_sanc_amt, :param_status, @Result)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_type', $param_type);
        $command->bindValue(':param_sanc_amt', $param_sanc_amt);
        $command->bindValue(':param_action_by', $param_action_by);
        $command->bindValue(':param_bill_id', $param_bill_id);
        $command->bindValue(':param_opd_id', $param_opd_id);
        $command->bindValue(':param_emp_code', $param_emp_code);
        $command->bindValue(':param_total_sanc_amt', $param_total_sanc_amt);
        $command->bindValue(':param_status', $param_status);
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
    }
    public function fn_add_medical_entitlement($param_entitle_id, $param_employee_code, $param_session_year, $param_total_entitlement, $param_utilized){
        
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `fn_add_medical_entitlement`(:param_entitle_id, :param_employee_code, :param_session_year, :param_total_entitlement, :param_utilized, :param_action_by, @Result)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_entitle_id', $param_entitle_id);
        $command->bindValue(':param_employee_code', $param_employee_code);
        $command->bindValue(':param_session_year', $param_session_year);
        $command->bindValue(':param_total_entitlement', $param_total_entitlement);
        $command->bindValue(':param_utilized', $param_utilized);
        $command->bindValue(':param_action_by', Yii::$app->user->identity->e_id);
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
    }
    public function fn_add_update_tour_claim_header($param_claim_id,$param_req_id, $param_employee_code, $param_project_id, $param_dept_id, $param_start_date, $param_end_date, $param_location, $param_purpose, $param_claimed_amt, $param_sanctioned_amt, $param_sanctioned_by, $param_status){
        $param_role_id = Yii::$app->user->identity->role;
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `fn_add_update_tour_claim_header`(:param_role_id, :param_claim_id, :param_req_id, :param_employee_code, :param_project_id, :param_dept_id, :param_start_date, :param_end_date, :param_location, :param_purpose, :param_claimed_amt, :param_sanctioned_amt, :param_sanctioned_by, :param_status, @Result)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_role_id', $param_role_id);
        $command->bindValue(':param_claim_id', $param_claim_id);
        $command->bindValue(':param_req_id', $param_req_id);
        $command->bindValue(':param_employee_code', $param_employee_code);
        $command->bindValue(':param_project_id', $param_project_id);
        $command->bindValue(':param_dept_id', $param_dept_id);
        $command->bindValue(':param_start_date', $param_start_date);
        $command->bindValue(':param_end_date', $param_end_date);
        $command->bindValue(':param_location', $param_location);
        $command->bindValue(':param_purpose', $param_purpose);
        $command->bindValue(':param_claimed_amt', $param_claimed_amt);
        $command->bindValue(':param_sanctioned_amt', $param_sanctioned_amt);
        $command->bindValue(':param_sanctioned_by', $param_sanctioned_by);
        $command->bindValue(':param_status', $param_status);
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
    }
    public function fn_add_update_tour_halt_details($param_th_id, $param_claim_id, $param_req_id, $param_start_date, $param_end_date, $param_city, $param_stay, $param_charges, $param_sanc_charges, $param_comp = NULL, $e_id=NULL){
        if(empty($e_id)){
            $e_id = Yii::$app->user->identity->e_id;
        }
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `fn_add_update_tour_halt_details`(:param_role_id, :param_th_id, :param_claim_id, :param_req_id, :param_employee_code, :param_start_date, :param_end_date, :param_city, :param_stay, :param_charges, :param_sanc_charges, :param_action_by, :param_comp, @Result)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_role_id', Yii::$app->user->identity->role);
        $command->bindValue(':param_th_id', $param_th_id);
        $command->bindValue(':param_claim_id', $param_claim_id);
        $command->bindValue(':param_req_id', $param_req_id);
        $command->bindValue(':param_employee_code', $e_id);
        $command->bindValue(':param_start_date', $param_start_date);
        $command->bindValue(':param_end_date', $param_end_date);
        $command->bindValue(':param_city', $param_city);
        $command->bindValue(':param_stay', $param_stay);
        $command->bindValue(':param_charges', $param_charges);
        $command->bindValue(':param_sanc_charges', $param_sanc_charges);
        $command->bindValue(':param_action_by', Yii::$app->user->identity->e_id);
        $command->bindValue(':param_comp', $param_comp);
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
    }
    public function fn_get_claim_halt_details($param_claim_id, $param_req_id, $param_employee_code){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `fn_get_claim_halt_details`(:param_claim_id, :param_req_id, :param_employee_code)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_claim_id', $param_claim_id);
        $command->bindValue(':param_req_id', $param_req_id);
        $command->bindValue(':param_employee_code', $param_employee_code);
        $result=$command->queryAll();
        $connection->close();
        return $result; 
    }
    
    public function fn_add_update_claim_conveyance($param_tc_id, $param_claim_id, $param_req_id, $param_start_date, $param_end_date, $param_place_from, $param_place_to, $param_mode, $param_distance, $param_amount, $param_sntd_amount, $e_id= NULL){
        if(empty($e_id)){
            $e_id = Yii::$app->user->identity->e_id;
        }
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `fn_add_update_claim_conveyance`(:param_role_id, :param_tc_id, :param_claim_id, :param_req_id, :param_employee_code, :param_start_date, :param_end_date, :param_place_from, :param_place_to, :param_mode, :param_distance, :param_amount, :param_sntd_amount, :param_action_by, @Result)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_role_id', Yii::$app->user->identity->role);
        $command->bindValue(':param_tc_id', $param_tc_id);
        $command->bindValue(':param_claim_id', $param_claim_id);
        $command->bindValue(':param_req_id', $param_req_id);
        $command->bindValue(':param_employee_code', $e_id);
        $command->bindValue(':param_start_date', $param_start_date);
        $command->bindValue(':param_end_date', $param_end_date);
        $command->bindValue(':param_place_from', $param_place_from);
        $command->bindValue(':param_place_to', $param_place_to);
        $command->bindValue(':param_mode', $param_mode);
        $command->bindValue(':param_distance', $param_distance);
        $command->bindValue(':param_amount', $param_amount);
        $command->bindValue(':param_sntd_amount', $param_sntd_amount);
        $command->bindValue(':param_action_by', Yii::$app->user->identity->e_id);
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
    }
    
    public function fn_get_claim_conveyance_details($param_claim_id, $param_req_id, $param_employee_code){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `fn_get_claim_conveyance_details`(:param_claim_id, :param_req_id, :param_employee_code)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_claim_id', $param_claim_id);
        $command->bindValue(':param_req_id', $param_req_id);
        $command->bindValue(':param_employee_code', $param_employee_code);
        $result=$command->queryAll();
        $connection->close();
        return $result; 
    }
       
    public function fn_add_update_claim_journey($param_j_id, $param_claim_id, $param_req_id, $param_start_date, $param_end_date, $param_place_from, $param_place_to, $param_t_class, $param_greater_500Km, $param_greater_8Hrs, $param_ticket, $param_sanc_ticket, $param_amount, $param_sanc_amount, $param_incentive, $param_sanc_incentive, $e_id = NULL){
        if(empty($e_id)){
            $e_id = Yii::$app->user->identity->e_id;
        }
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `fn_add_update_claim_journey`(:param_role_id, :param_j_id, :param_claim_id, :param_req_id, :param_employee_code, :param_start_date, :param_end_date, :param_place_from, :param_place_to, :param_t_class, :param_greater_500Km, :param_greater_8Hrs, :param_ticket, :param_sanc_ticket, :param_amount, :param_sanc_amount, :param_incentive, :param_sanc_incentive, :param_action_by, @Result)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_role_id', Yii::$app->user->identity->role);
        $command->bindValue(':param_j_id', $param_j_id);
        $command->bindValue(':param_claim_id', $param_claim_id);
        $command->bindValue(':param_req_id', $param_req_id);
        $command->bindValue(':param_employee_code', $e_id);
        $command->bindValue(':param_start_date', $param_start_date);
        $command->bindValue(':param_end_date', $param_end_date);
        $command->bindValue(':param_place_from', $param_place_from);
        $command->bindValue(':param_place_to', $param_place_to);
        $command->bindValue(':param_t_class', $param_t_class);
        $command->bindValue(':param_greater_500Km', $param_greater_500Km);
        $command->bindValue(':param_greater_8Hrs', $param_greater_8Hrs);
        $command->bindValue(':param_ticket', $param_ticket);
        $command->bindValue(':param_sanc_ticket', $param_sanc_ticket);
        $command->bindValue(':param_amount', $param_amount);
        $command->bindValue(':param_sanc_amount', $param_sanc_amount);
        $command->bindValue(':param_incentive', $param_incentive);
        $command->bindValue(':param_sanc_incentive', $param_sanc_incentive);
        $command->bindValue(':param_action_by', Yii::$app->user->identity->e_id);
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
    }
    
    public function fn_get_claim_journey_details($param_claim_id, $param_req_id, $param_employee_code){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `fn_get_claim_journey_details`(:param_claim_id, :param_req_id, :param_employee_code)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_claim_id', $param_claim_id);
        $command->bindValue(':param_req_id', $param_req_id);
        $command->bindValue(':param_employee_code', $param_employee_code);
        $result=$command->queryAll();
        $connection->close();
        return $result; 
    }
    
    public function fn_add_update_claim_food($param_tf_id, $param_claim_id, $param_req_id, $param_purpose, $param_amount, $param_bill_date, $param_sanctnd_amt, $e_id=NULL){
        if(empty($e_id)){
            $e_id = Yii::$app->user->identity->e_id;
        }
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `fn_add_update_claim_food`(:param_role_id, :param_tf_id, :param_claim_id, :param_req_id, :param_employee_code, :param_purpose, :param_amount, :param_bill_date, :param_sanctnd_amt, :param_action_by, @Result)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_role_id', Yii::$app->user->identity->role);
        $command->bindValue(':param_tf_id', $param_tf_id);
        $command->bindValue(':param_claim_id', $param_claim_id);
        $command->bindValue(':param_req_id', $param_req_id);
        $command->bindValue(':param_employee_code', $e_id);
        $command->bindValue(':param_purpose', $param_purpose);
        $command->bindValue(':param_amount', $param_amount);
        $command->bindValue(':param_bill_date', $param_bill_date);
        $command->bindValue(':param_sanctnd_amt', $param_sanctnd_amt);
        $command->bindValue(':param_action_by', Yii::$app->user->identity->e_id);
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
    }
    
    public function fn_get_claim_food_details($param_claim_id, $param_req_id, $param_employee_code){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `fn_get_claim_food_details`(:param_claim_id, :param_req_id, :param_employee_code)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_claim_id', $param_claim_id);
        $command->bindValue(':param_req_id', $param_req_id);
        $command->bindValue(':param_employee_code', $param_employee_code);
        $result=$command->queryAll();
        $connection->close();
        return $result; 
    }
    public function fn_get_emp_insurance($param_depndnt_id, $param_patient_type, $param_emp_code){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `fn_get_emp_insurance`(:param_depndnt_id, :param_patient_type, :param_emp_code)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_depndnt_id', $param_depndnt_id);
        $command->bindValue(':param_patient_type', $param_patient_type);
        $command->bindValue(':param_emp_code', $param_emp_code);
        $result=$command->queryAll();
        $connection->close();
        return $result; 
    }
    
    public function fn_add_update_emp_insurance($param_id, $param_employee_code, $param_patient_type, $param_dependent_id, $param_company_name, $param_policy_number, $param_valid_from, $param_valid_till){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `fn_add_update_emp_insurance`(:param_id, :param_employee_code, :param_patient_type, :param_dependent_id, :param_company_name, :param_policy_number, :param_valid_from, :param_valid_till, :param_amount, @Result)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_id', $param_id);
        $command->bindValue(':param_employee_code', $param_employee_code);
        $command->bindValue(':param_patient_type', $param_patient_type);
        $command->bindValue(':param_dependent_id', $param_dependent_id);
        $command->bindValue(':param_company_name', $param_company_name);
        $command->bindValue(':param_policy_number', $param_policy_number);
        $command->bindValue(':param_valid_from', $param_valid_from);
        $command->bindValue(':param_valid_till', $param_valid_till);
        $command->bindValue(':param_amount', NULL);
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
    }
    public function fn_add_update_ipd_claims($param_ipd_id, $param_employee_code, $param_fn_year, $param_patient_type, $param_dependent_id, $param_date_of_admission, $param_date_of_discharge, $param_admitted_for, $param_claim_type, $param_insurance_id, $param_insrn_sanc_amt, $param_total_clmd_amt, $param_total_sanc_amt, $param_status, $param_remarks){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `fn_add_update_ipd_claims`(:param_role_id, :param_ipd_id, :param_employee_code, :param_fn_year, :param_patient_type, :param_dependent_id, :param_date_of_admission, :param_date_of_discharge, :param_admitted_for, :param_claim_type, :param_insurance_id, :param_insrn_sanc_amt, :param_total_clmd_amt, :param_total_sanc_amt, :param_status, :param_action_by, :param_remarks, @Result)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_role_id', Yii::$app->user->identity->role);
        $command->bindValue(':param_ipd_id', $param_ipd_id);
        $command->bindValue(':param_employee_code', $param_employee_code);
        $command->bindValue(':param_fn_year', $param_fn_year);
        $command->bindValue(':param_patient_type', $param_patient_type);
        $command->bindValue(':param_dependent_id', $param_dependent_id);
        $command->bindValue(':param_date_of_admission', $param_date_of_admission);
        $command->bindValue(':param_date_of_discharge', $param_date_of_discharge);
        $command->bindValue(':param_admitted_for', $param_admitted_for);
        $command->bindValue(':param_claim_type', $param_claim_type);
        $command->bindValue(':param_insurance_id', $param_insurance_id);
        $command->bindValue(':param_insrn_sanc_amt', $param_insrn_sanc_amt);
        $command->bindValue(':param_total_clmd_amt', $param_total_clmd_amt);
        $command->bindValue(':param_total_sanc_amt', $param_total_sanc_amt);
        $command->bindValue(':param_status', $param_status);
        $command->bindValue(':param_action_by', Yii::$app->user->identity->e_id);
        $command->bindValue(':param_remarks', $param_remarks);
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
    }
    
    public function fn_get_ipd_claims($param_ipd_id, $param_employee_code, $param_status){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `fn_get_ipd_claims`(:param_role_id, :param_ipd_id, :param_employee_code, :param_status)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_role_id', Yii::$app->user->identity->role);
        $command->bindValue(':param_ipd_id', $param_ipd_id);
        $command->bindValue(':param_employee_code', $param_employee_code);
        $command->bindValue(':param_status', $param_status);
        if(!empty($param_ipd_id)){
            $result=$command->queryOne();
        }else{
            $result=$command->queryAll();
        }        
        $connection->close();
        return $result; 
    }
    
    public function fn_add_update_ipd_details($param_action_type, $param_billid, $param_employee_code, $param_ipd_id, $param_bill_number, $param_bill_date, $param_issuer, $param_bill_amt, $param_sanc_amt){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `fn_add_update_ipd_details`(:param_role_id, :param_action_type, :param_billid, :param_employee_code, :param_ipd_id, :param_bill_number, :param_bill_date, :param_issuer, :param_bill_amt, :param_sanc_amt, :param_action_by, @Result)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_role_id', Yii::$app->user->identity->role);
        $command->bindValue(':param_action_type', $param_action_type);
        $command->bindValue(':param_billid', $param_billid);
        $command->bindValue(':param_employee_code', $param_employee_code);
        $command->bindValue(':param_ipd_id', $param_ipd_id);
        $command->bindValue(':param_bill_number', $param_bill_number);
        $command->bindValue(':param_bill_date', $param_bill_date);
        $command->bindValue(':param_issuer', $param_issuer);
        $command->bindValue(':param_bill_amt', $param_bill_amt);
        $command->bindValue(':param_sanc_amt', $param_sanc_amt);
        $command->bindValue(':param_action_by', Yii::$app->user->identity->e_id);
        
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
    }
    
    public function fn_get_ipd_details($param_employee_code, $param_ipd_id){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `fn_get_ipd_details`(:param_employee_code, :param_ipd_id)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_employee_code', $param_employee_code);
        $command->bindValue(':param_ipd_id', $param_ipd_id);
        $result=$command->queryAll();
        $connection->close();
        return $result; 
    }
        
    public function add_update_reim_type($param_reim_type_id, $param_name){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `add_update_reim_type`(:param_reim_type_id, :param_name, @Result)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_reim_type_id', $param_reim_type_id);
        $command->bindValue(':param_name', $param_name);
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut;
    }
    public function get_reim_type(){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `get_reim_type`()";
        $command = $connection->createCommand($sql); 
        $result=$command->queryAll();
        $connection->close();
        return $result; 
    }
    public function add_update_reim_master($param_ann_reim_id, $param_reim_type_id, $param_designation_id, $param_emp_type, $param_financial_yr, $param_sanc_amt){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `add_update_reim_master`(:param_ann_reim_id, :param_reim_type_id, :param_designation_id, :param_emp_type, :param_financial_yr, :param_sanc_amt, :param_added_by, @Result)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_ann_reim_id', $param_ann_reim_id);
        $command->bindValue(':param_reim_type_id', $param_reim_type_id);
        $command->bindValue(':param_designation_id', $param_designation_id);
        $command->bindValue(':param_emp_type', $param_emp_type);
        $command->bindValue(':param_financial_yr', $param_financial_yr);
        $command->bindValue(':param_sanc_amt', $param_sanc_amt);
        $command->bindValue(':param_added_by', Yii::$app->user->identity->e_id);
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut;
    }
    public function get_ann_reim_master($param_ann_reim_id=NULL){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `get_ann_reim_master`(:param_ann_reim_id)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_ann_reim_id', $param_ann_reim_id);
        if(!empty($param_ann_reim_id)){
            $result=$command->queryOne();
        }else{
            $result=$command->queryAll();
        }        
        $connection->close();
        return $result; 
    }
       
    public function fn_add_update_ann_reim_claim($param_arc_id, $param_employee_code, $param_ann_reim_id, $param_financial_year, $param_other_detail, $param_apr_month_amt, $param_may_month_amt, $param_june_month_amt, $param_july_month_amt, $param_aug_month_amt, $param_sept_month_amt, $param_oct_month_amt, $param_nov_month_amt, $param_dec_month_amt, $param_jan_month_amt, $param_feb_month_amt, $param_mar_month_amt, $param_total_claimed, $param_doc_path, $param_sanc_claimed, $param_sanc_by, $param_sanc_remarks, $param_status){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `fn_add_update_ann_reim_claim`(:param_arc_id, :param_employee_code, :param_ann_reim_id, :param_financial_year, :param_other_detail, :param_apr_month_amt, :param_may_month_amt, :param_june_month_amt, :param_july_month_amt, :param_aug_month_amt, :param_sept_month_amt, :param_oct_month_amt, :param_nov_month_amt, :param_dec_month_amt, :param_jan_month_amt, :param_feb_month_amt, :param_mar_month_amt, :param_total_claimed, :param_doc_path, :param_sanc_claimed, :param_sanc_by, :param_sanc_remarks, :param_status, @Result)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_arc_id', $param_arc_id);
        $command->bindValue(':param_employee_code', $param_employee_code);
        $command->bindValue(':param_ann_reim_id', $param_ann_reim_id);
        $command->bindValue(':param_financial_year', $param_financial_year);
        $command->bindValue(':param_other_detail', $param_other_detail);
        $command->bindValue(':param_apr_month_amt', $param_apr_month_amt);
        $command->bindValue(':param_may_month_amt', $param_may_month_amt);
        $command->bindValue(':param_june_month_amt', $param_june_month_amt);
        $command->bindValue(':param_july_month_amt', $param_july_month_amt);
        $command->bindValue(':param_aug_month_amt', $param_aug_month_amt);
        $command->bindValue(':param_sept_month_amt', $param_sept_month_amt);
        $command->bindValue(':param_oct_month_amt', $param_oct_month_amt);
        $command->bindValue(':param_nov_month_amt', $param_nov_month_amt);
        $command->bindValue(':param_dec_month_amt', $param_dec_month_amt);
        $command->bindValue(':param_jan_month_amt', $param_jan_month_amt);
        $command->bindValue(':param_feb_month_amt', $param_feb_month_amt);
        $command->bindValue(':param_mar_month_amt', $param_mar_month_amt);
        $command->bindValue(':param_total_claimed', $param_total_claimed);
        $command->bindValue(':param_doc_path', $param_doc_path);
        $command->bindValue(':param_sanc_claimed', $param_sanc_claimed);
        $command->bindValue(':param_sanc_by', $param_sanc_by);
        $command->bindValue(':param_sanc_remarks', $param_sanc_remarks);
        $command->bindValue(':param_status', $param_status);
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut;
    }
    public function fn_get_ann_reim_claim($param_arc_id, $param_employee_code, $param_financial_year, $param_status, $param_ann_reim_id){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `fn_get_ann_reim_claim`(:param_arc_id, :param_employee_code, :param_financial_year, :param_status, :param_ann_reim_id)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_arc_id', $param_arc_id);
        $command->bindValue(':param_employee_code', $param_employee_code);
        $command->bindValue(':param_financial_year', $param_financial_year);
        $command->bindValue(':param_status', $param_status);
        $command->bindValue(':param_ann_reim_id', $param_ann_reim_id);
        if(!empty($param_arc_id) OR !empty($param_ann_reim_id)){
            $result=$command->queryOne();
        }else{
            $result=$command->queryAll();
        }        
        $connection->close();
        return $result; 
    }
    
    public function fn_add_update_damaster($param_da_id, $param_month_year, $param_da_percentage, $param_effected_from, $param_financial_year){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `fn_add_update_damaster`(:param_da_id, :param_month_year, :param_da_percentage, :param_effected_from, :param_financial_year, :param_updated_by, @Result)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_da_id', $param_da_id);
        $command->bindValue(':param_month_year', $param_month_year);
        $command->bindValue(':param_da_percentage', $param_da_percentage);
        $command->bindValue(':param_effected_from', $param_effected_from);
        $command->bindValue(':param_financial_year', $param_financial_year);
        $command->bindValue(':param_updated_by', Yii::$app->user->identity->employee_code);
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut;
    }
    
    public function fn_check_salary_status($param_month, $param_year, $param_status){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `fn_check_salary_status`(:param_month, :param_year, :param_status)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_month', $param_month);
        $command->bindValue(':param_year', $param_year);
        $command->bindValue(':param_status', $param_status);
        $result=$command->queryAll();
        $connection->close();
        return $result; 
    }
    
    public function fn_update_canteen_allowances($param_employee_code, $param_salMonth, $param_salYear, $param_allowance_canteen, $param_working_days){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `fn_update_canteen_allowances`(:param_employee_code, :param_salMonth, :param_salYear, :param_allowance_canteen, :param_working_days, :param_last_updated_by, @Result)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_employee_code', $param_employee_code);
        $command->bindValue(':param_salMonth', $param_salMonth);
        $command->bindValue(':param_salYear', $param_salYear);
        $command->bindValue(':param_allowance_canteen', $param_allowance_canteen);
        $command->bindValue(':param_working_days', $param_working_days);
        $command->bindValue(':param_last_updated_by', Yii::$app->user->identity->employee_code);
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut;
    }
    public function fn_update_salary($param_action, $param_employee_code, $param_month, $param_year){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `fn_update_salary`(:param_action, :param_employee_code, :param_month, :param_year, :param_last_updated_by, @Result)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_action', $param_action);
        $command->bindValue(':param_employee_code', $param_employee_code);
        $command->bindValue(':param_month', $param_month);
        $command->bindValue(':param_year', $param_year);
        $command->bindValue(':param_last_updated_by', Yii::$app->user->identity->employee_code);
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut;
    } 
    
//    public function fn_generate_salary($param_action_type, $param_employee_code, $param_month, $param_year){
//        $connection=   Yii::$app->db;
//        $connection->open();
//        $sql =" CALL `fn_generate_salary`(:param_action_type, :param_employee_code, :param_month, :param_year,:param_last_updated_by, @Result)";
//        $command = $connection->createCommand($sql); 
//        $command->bindValue(':param_action_type', $param_action_type);
//        $command->bindValue(':param_employee_code', $param_employee_code);
//        $command->bindValue(':param_month', $param_month);
//        $command->bindValue(':param_year', $param_year);
//        $command->bindValue(':param_last_updated_by', Yii::$app->user->identity->employee_code);
//        $command->execute();
//        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
//        $connection->close();
//        return $valueOut;
//    }
    public function fn_update_emp_salary($param_employee_code, $param_month, $param_year, $param_allowance_da_arrear, $param_allowance_ta_arrear, $param_ded_pf_on_arrear, $param_ded_incomeTax, $param_ded_lfee, $param_ded_club, $param_ded_GSLI, $param_ded_BenevolentFund, $param_child_edu, $param_other_income, $param_perq_lease, $param_perq_medical_reimbursement, $param_perq_interest, $param_hra_exemption, $param_transport_exemption, $param_child_education_allowance_exemption, $param_other_income_reported_by_employee, $param_income_from_house_property, $param_previous_employer_income, $param_professional_tax, $param_loss_on_house_property){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `fn_update_emp_salary`(:param_employee_code, :param_month, :param_year, :param_allowance_da_arrear, :param_allowance_ta_arrear, :param_ded_pf_on_arrear, :param_ded_incomeTax, :param_ded_lfee, :param_ded_club, :param_ded_GSLI, :param_ded_BenevolentFund, :param_child_edu, :param_other_income, :param_perq_lease, :param_perq_medical_reimbursement, :param_perq_interest, :param_hra_exemption, :param_transport_exemption, :param_child_education_allowance_exemption, :param_other_income_reported_by_employee, :param_income_from_house_property, :param_previous_employer_income, :param_professional_tax, :param_loss_on_house_property, :param_last_updated_by, @Result)";
        $command = $connection->createCommand($sql);
        $command->bindValue(':param_employee_code', $param_employee_code);
        $command->bindValue(':param_month', $param_month);
        $command->bindValue(':param_year', $param_year);
        $command->bindValue(':param_allowance_da_arrear', $param_allowance_da_arrear);
        $command->bindValue(':param_allowance_ta_arrear', $param_allowance_ta_arrear);
        $command->bindValue(':param_ded_pf_on_arrear', $param_ded_pf_on_arrear);
        $command->bindValue(':param_ded_incomeTax', $param_ded_incomeTax);
        $command->bindValue(':param_ded_lfee', $param_ded_lfee);
        $command->bindValue(':param_ded_club', $param_ded_club);
        $command->bindValue(':param_ded_GSLI', $param_ded_GSLI);
        $command->bindValue(':param_ded_BenevolentFund', $param_ded_BenevolentFund);
        $command->bindValue(':param_child_edu', $param_child_edu);
        $command->bindValue(':param_other_income', $param_other_income);
        $command->bindValue(':param_perq_lease', $param_perq_lease);
        $command->bindValue(':param_perq_medical_reimbursement', $param_perq_medical_reimbursement);
        $command->bindValue(':param_perq_interest', $param_perq_interest);
        $command->bindValue(':param_hra_exemption', $param_hra_exemption);
        $command->bindValue(':param_transport_exemption', $param_transport_exemption);
        $command->bindValue(':param_child_education_allowance_exemption', $param_child_education_allowance_exemption);
        $command->bindValue(':param_other_income_reported_by_employee', $param_other_income_reported_by_employee);
        $command->bindValue(':param_income_from_house_property', $param_income_from_house_property);
        $command->bindValue(':param_previous_employer_income', $param_previous_employer_income);
        $command->bindValue(':param_professional_tax', $param_professional_tax);
        $command->bindValue(':param_loss_on_house_property', $param_loss_on_house_property);
        $command->bindValue(':param_last_updated_by', Yii::$app->user->identity->employee_code);
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut;
    }
    
    public function fn_display_incomeTax($param_action_type, $param_employee_code, $param_financial_year){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `fn_display_incomeTax`(:param_action_type, :param_employee_code, :param_financial_year)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_action_type', $param_action_type);
        $command->bindValue(':param_employee_code', $param_employee_code);
        $command->bindValue(':param_financial_year', $param_financial_year);
        if($param_action_type == 'Short'){
            $result=$command->queryOne();
        }else{
            $result=$command->queryAll();
        }
        
        $connection->close();
        return $result; 
    }
    
    public function pf_get_accounts($param_emp_code){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `pf_get_accounts`(:param_emp_code)";
	$command = $connection->createCommand($sql); 
        $command->bindValue(':param_emp_code', $param_emp_code);
        if(!empty($param_emp_code) OR !empty($param_emp_code)){
            $result=$command->queryOne();
        }else{
            $result=$command->queryAll();
        }
        
        $connection->close();
        return $result;       
    }
    
    /*
     * Check Yes or No params
     */
    
    public function checkYesNo($value) {
        $result = false;
        if($value == 'Y'){
            $result = true;
        }elseif($value == 'N'){
            $result = true;
        }
        return $result;
    }
    public function pf_add_update_account($param_pfid, $param_employee_code, $param_uan_number, $param_pf_number, $param_subscription_date, $param_fpf_account, $param_vpf_deduct, $param_is_eligible_fpf, $param_is_active){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `pf_add_update_account`(:param_pfid, :param_employee_code, :param_uan_number, :param_pf_number, :param_subscription_date, :param_fpf_account, :param_vpf_deduct, :param_is_eligible_fpf, :param_is_active, :param_updated_by, @Result)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_pfid', $param_pfid);
        $command->bindValue(':param_employee_code', $param_employee_code);
        $command->bindValue(':param_uan_number', $param_uan_number);
        $command->bindValue(':param_pf_number', $param_pf_number);
        $command->bindValue(':param_subscription_date', $param_subscription_date);
        $command->bindValue(':param_fpf_account', $param_fpf_account);
        $command->bindValue(':param_vpf_deduct', $param_vpf_deduct);
        $command->bindValue(':param_is_eligible_fpf', $param_is_eligible_fpf);
        $command->bindValue(':param_is_active', $param_is_active);
        $command->bindValue(':param_updated_by', Yii::$app->user->identity->employee_code);
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut;
    }
    public function pf_generate_pf($param_financial_year, $param_pf_month, $param_pf_year, $param_status){
        /*
         * $param_status will be Projected or Paid
         */
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `pf_generate_pf`(:param_financial_year, :param_pf_month, :param_pf_year, :param_status, :param_updated_by, @Result)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_financial_year', $param_financial_year);
        $command->bindValue(':param_pf_month', $param_pf_month);
        $command->bindValue(':param_pf_year', $param_pf_year);
        $command->bindValue(':param_status', $param_status);
        $command->bindValue(':param_updated_by', Yii::$app->user->identity->employee_code);
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut;
    }
    
    public function pf_get_monthwise_details($param_financial_year, $param_pf_month, $param_pf_year, $param_employee_code, $param_status){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `pf_get_monthwise_details`(:param_financial_year, :param_pf_month, :param_pf_year, :param_employee_code, :param_status)";
	$command = $connection->createCommand($sql); 
        $command->bindValue(':param_financial_year', $param_financial_year);
        $command->bindValue(':param_pf_month', $param_pf_month);
        $command->bindValue(':param_pf_year', $param_pf_year);
        $command->bindValue(':param_employee_code', $param_employee_code);
        $command->bindValue(':param_status', $param_status);
        if(!empty($param_pf_month) AND !empty($param_pf_year) AND !empty($param_employee_code)){
            $result=$command->queryOne();
        }else{
            $result=$command->queryAll();
        }
        $connection->close();
        return $result;       
    }
    public function pf_get_fy_iwse_emp_details($param_employee_code){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `pf_get_fy_iwse_emp_details`(:param_employee_code)";
	$command = $connection->createCommand($sql); 
        $command->bindValue(':param_employee_code', $param_employee_code);
        $result=$command->queryAll();
        $connection->close();
        return $result;       
    }
}