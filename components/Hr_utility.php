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
class Hr_utility extends Component 
{
    public function hr_add_leave_app($param_emp_req_id, $param_leave_app_id, $param_employee_code, $param_leave_reason, $param_LTC, $param_address, $param_contact, $param_leave_type, $param_whetherhalfday, $param_req_from_date, $param_req_to_date, $param_total_days, $param_balance_leaves, $param_status)
    {
        $param_flag = rand (9999,100000);
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `hr_add_leave_app`(:param_emp_req_id, :param_leave_app_id, :param_employee_code, :param_leave_reason, :param_LTC, :param_address, :param_contact, :param_leave_type, :param_whetherhalfday, :param_req_from_date, :param_req_to_date, :param_total_days, :param_balance_leaves, :param_flag, :param_status, @Result)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_emp_req_id', $param_emp_req_id);
        $command->bindValue(':param_leave_app_id', $param_leave_app_id);
        $command->bindValue(':param_employee_code', $param_employee_code);
        $command->bindValue(':param_leave_reason', $param_leave_reason);
        $command->bindValue(':param_LTC', $param_LTC);
        $command->bindValue(':param_address', $param_address);
        $command->bindValue(':param_contact', $param_contact); 
        $command->bindValue(':param_leave_type', $param_leave_type); 
        $command->bindValue(':param_whetherhalfday', $param_whetherhalfday); 
        $command->bindValue(':param_req_from_date', $param_req_from_date); 
        $command->bindValue(':param_req_to_date', $param_req_to_date);
        $command->bindValue(':param_total_days', $param_total_days);
        $command->bindValue(':param_balance_leaves', $param_balance_leaves);
        $command->bindValue(':param_flag', $param_flag);
        $command->bindValue(':param_status', $param_status);
        
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
    }
    public function hr_update_emp_leave_application($param_action_type, $param_role_id, $param_leave_app_id, $param_employee_code, $param_status, $param_remarks)
    {
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `hr_update_emp_leave_application`(:param_action_type, :param_role_id, :param_leave_app_id, :param_employee_code, :param_status, :param_action_by, :param_remarks, @Result)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_action_type', $param_action_type);
        $command->bindValue(':param_role_id', $param_role_id);
        $command->bindValue(':param_leave_app_id', $param_leave_app_id);
        $command->bindValue(':param_employee_code', $param_employee_code);
        $command->bindValue(':param_status', $param_status);
        $command->bindValue(':param_action_by', Yii::$app->user->identity->e_id);
        $command->bindValue(':param_remarks', $param_remarks);
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
    }

    public function hr_update_emp_leave_application_forward($param_leave_app_id, $param_employee_code, $param_status, $param_remarks,$param_forward_to)
    {
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `hr_update_emp_leave_application_forward`(:param_leave_app_id, :param_employee_code, :param_status, :param_remarks, :param_forward_to, @Result)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_leave_app_id', $param_leave_app_id);
        $command->bindValue(':param_employee_code', $param_employee_code);
        $command->bindValue(':param_status', $param_status);
        $command->bindValue(':param_remarks', $param_remarks);
        $command->bindValue(':param_forward_to', $param_forward_to);
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
    }

//    public function hr_add_leave_app($param_e_id, $param_leave_reason, $param_LTC, $param_address, $param_contact, $param_leave_type, $param_whetherhalfday, $param_from_date, $param_till_date, $param_total_days)
//    {
//        $connection=   Yii::$app->db;
//        $connection->open();
//        $sql =" CALL `hr_add_leave_app`(:param_e_id, :param_leave_reason,:param_LTC, :param_address, :param_contact, :param_leave_type, :param_whetherhalfday, :param_from_date, :param_till_date, :param_total_days, @Result)";
//        $command = $connection->createCommand($sql); 
//        $command->bindValue(':param_e_id', $param_e_id); 
//        $command->bindValue(':param_leave_reason', $param_leave_reason); 
//        $command->bindValue(':param_LTC', $param_LTC); 
//        $command->bindValue(':param_address', $param_address); 
//        $command->bindValue(':param_contact', $param_contact); 
//        $command->bindValue(':param_leave_type', $param_leave_type); 
//        $command->bindValue(':param_whetherhalfday', $param_whetherhalfday); 
//        $command->bindValue(':param_from_date', $param_from_date); 
//        $command->bindValue(':param_till_date', $param_till_date); 
//        $command->bindValue(':param_total_days', $param_total_days); 
//        $command->execute();
//        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
//        $connection->close();
//        return $valueOut; 
//    }
    
    /*
     * Add Qualification
     */
    public function hr_add_qualification($param_eq_id=NULL,$param_e_id,$param_quali_type,$param_qualification_id,$param_other_quali,$param_discipline,$param_institute,$param_univ_board,$param_address, $param_passed_on,$param_grade,$param_percentage,$param_CGPA,$param_doc_type,$param_docs,$param_status){
        
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `hr_add_qualification`(:param_eq_id,:param_e_id,:param_quali_type,:param_qualification_id,:param_other_quali,:param_discipline,:param_institute,:param_univ_board,:param_address,:param_passed_on,:param_grade,:param_percentage,:param_CGPA,:param_doc_type,:param_docs,:param_status, @Result)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_eq_id', $param_eq_id);
        $command->bindValue(':param_e_id', $param_e_id);
        $command->bindValue(':param_quali_type', $param_quali_type);
        $command->bindValue(':param_qualification_id', $param_qualification_id);
        $command->bindValue(':param_other_quali', $param_other_quali);
        $command->bindValue(':param_discipline', $param_discipline);
        $command->bindValue(':param_institute', $param_institute);
        $command->bindValue(':param_univ_board', $param_univ_board);
        $command->bindValue(':param_address', $param_address);
        $command->bindValue(':param_passed_on', $param_passed_on);
        $command->bindValue(':param_grade', $param_grade);
        $command->bindValue(':param_percentage', $param_percentage);
        $command->bindValue(':param_CGPA', $param_CGPA);
        $command->bindValue(':param_doc_type', $param_doc_type);
        $command->bindValue(':param_docs', $param_docs);
        $command->bindValue(':param_status', $param_status);
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
    }
    public function hr_add_update_family($param_ef_id,$param_e_id,$param_m_name,$param_relation_id,$param_marital_status,$param_m_dob,$param_handicap,$param_handicate_type,$param_handicap_percentage, $param_monthly_income,$param_contact_detail,$param_nominee,$param_address,$param_p_address,$param_document_type,$param_document_path,$param_status)
    {
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `hr_add_update_family`(:param_ef_id,:param_e_id,:param_m_name,:param_relation_id,:param_marital_status,:param_m_dob,:param_handicap,:param_handicate_type,:param_handicap_percentage,:param_monthly_income,:param_contact_detail,:param_nominee,:param_address,:param_p_address,:param_document_type,:param_document_path,:param_status, @Result)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_ef_id', $param_ef_id);
        $command->bindValue(':param_e_id', $param_e_id);
        $command->bindValue(':param_m_name', $param_m_name);
        $command->bindValue(':param_relation_id', $param_relation_id);
        $command->bindValue(':param_marital_status', $param_marital_status);
        $command->bindValue(':param_m_dob', $param_m_dob);
        $command->bindValue(':param_handicap', $param_handicap);
        $command->bindValue(':param_handicate_type', $param_handicate_type);
        $command->bindValue(':param_handicap_percentage', $param_handicap_percentage);
        $command->bindValue(':param_monthly_income', $param_monthly_income);
        $command->bindValue(':param_contact_detail', $param_contact_detail);
        $command->bindValue(':param_nominee', $param_nominee);
        $command->bindValue(':param_address', $param_address);
        $command->bindValue(':param_p_address', $param_p_address);
        $command->bindValue(':param_document_type', $param_document_type);
        $command->bindValue(':param_document_path', $param_document_path);
        $command->bindValue(':param_status', $param_status);
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
    }


    public function hr_add_update_experience_detail($param_empid,$param_e_name,$param_organizationType,$param_job_title,$param_from,$param_till,$param_employer_address,$param_job_description,$param_document_type,$param_document_path,$param_status)
    {
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `hr_add_update_experience_detail`(:param_empid, :param_e_name, :param_organizationType, :param_job_title, :param_from, :param_till, :param_employer_address, :param_job_description,:param_document_type,:param_document_path,:param_status, @Result)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_empid', $param_empid);
        $command->bindValue(':param_e_name', $param_e_name);
        $command->bindValue(':param_organizationType', $param_organizationType);
        $command->bindValue(':param_job_title', $param_job_title);
        $command->bindValue(':param_from', $param_from);
        $command->bindValue(':param_till', $param_till);
        $command->bindValue(':param_employer_address', $param_employer_address);
        $command->bindValue(':param_job_description', $param_job_description);
        $command->bindValue(':param_document_type', $param_document_type);
        $command->bindValue(':param_document_path', $param_document_path);
        $command->bindValue(':param_status', $param_status);
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
    }
    
    public function hr_add_update_training_details($param_empid,$param_course_name,$param_institute_name,$param_institute_address,$param_training_attended,$param_from,$param_to,$param_description,$param_document_type,$param_document_path,$param_status)
    {
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `hr_add_update_training_details`(:param_empid, :param_course_name, :param_institute_name, :param_institute_address, :param_training_attended, :param_from, :param_to, :param_description, :param_document_type, :param_document_path, :param_status, @Result)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_empid', $param_empid);
        $command->bindValue(':param_course_name', $param_course_name);
        $command->bindValue(':param_institute_name', $param_institute_name);
        $command->bindValue(':param_institute_address', $param_institute_address);
        $command->bindValue(':param_training_attended', $param_training_attended);
        $command->bindValue(':param_from', $param_from);
        $command->bindValue(':param_to', $param_to);
        $command->bindValue(':param_description', $param_description);
        $command->bindValue(':param_document_type', $param_document_type);
        $command->bindValue(':param_document_path', $param_document_path);
        $command->bindValue(':param_status', $param_status);
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
    }


    // hr_add_update_language_details
    public function hr_add_update_language_details($param_empid,$param_languageID,$param_mother_tongue,$param_read,$param_write,$param_speak,$param_status)
    {
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `hr_add_update_language_details`(:param_empid, :param_languageID, :param_mother_tongue, :param_read, :param_write, :param_speak, :param_status, @Result)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_empid', $param_empid);
        $command->bindValue(':param_languageID', $param_languageID);
        $command->bindValue(':param_mother_tongue', $param_mother_tongue);
        $command->bindValue(':param_read', $param_read);
        $command->bindValue(':param_write', $param_write);
        $command->bindValue(':param_speak', $param_speak);
        $command->bindValue(':param_status', $param_status);
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
    }
    
    

    public function hr_get_leaves($param_view_type, $param_employee_code, $param_leave_app_id, $param_status){
        //echo "$param_view_type <br>";
       // echo "$param_employee_code <br>";
      // echo "$param_leave_app_id <br>";
     //   echo "$param_status <br>"; die;
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `hr_get_leaves`(:param_view_type, :param_employee_code, :param_leave_app_id, :param_status)";
	$command = $connection->createCommand($sql); 
        $command->bindValue(':param_view_type', $param_view_type);
        $command->bindValue(':param_employee_code', $param_employee_code);
        $command->bindValue(':param_leave_app_id', $param_leave_app_id);
        $command->bindValue(':param_status', $param_status);
//        if(!empty($param_leave_app_id) AND !empty($param_employee_code) AND $param_view_type == 'A'){
        if(!empty($param_leave_app_id) AND !empty($param_employee_code) AND $param_view_type == 'A'){
            $result=$command->queryOne();
        }else{
            $result=$command->queryAll();
        }
        
        $connection->close();

       /* echo '<pre>';
        print_r($result);
        die;*/
        return $result; 
    }
    public function get_quali_list($id=NULL){
        $connection= Yii::$app->db;
        $connection->open();
        $sql =" CALL `get_quali_list`(:param_id)";
	$command = $connection->createCommand($sql); 
        $command->bindValue(':param_id', $id);
        $result=$command->queryAll();
        $connection->close();
        return $result;   
    }
    public function get_relations($id=NULL){
        $connection= Yii::$app->db;
        $connection->open();
        $sql =" CALL `get_relations`(:param_id)";
	$command = $connection->createCommand($sql); 
        $command->bindValue(':param_id', $id);
        $result=$command->queryAll();
        $connection->close();
        return $result;   
    }
    
    public function hr_get_master_leave_type($param_type_id){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `hr_get_master_leave_type`(:param_type_id)";
	$command = $connection->createCommand($sql); 
        $command->bindValue(':param_type_id', $param_type_id);
        if(!empty($param_type_id)){
            $result=$command->queryOne();
        }else{
            $result=$command->queryAll();
        }
        
        $connection->close();
        return $result; 
    }

    public function hr_get_master_holiday_type($param_type_id){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `hr_get_master_holiday_type`(:param_type_id)";
    $command = $connection->createCommand($sql); 
        $command->bindValue(':param_type_id', $param_type_id);
        if(!empty($param_type_id)){
            $result=$command->queryOne();
        }else{
            $result=$command->queryAll();
        }
        
        $connection->close();
        return $result; 
    }

    public function hr_check_holidays($param_year,$param_month,$param_holiday_date,$param_holiday_type,$param_name){


        $connection=   Yii::$app->db;
        $connection->open();
        $sql ="CALL `hr_check_holidays`(:Param_H_year, :Param_H_month, :Param_H_Date, :Param_Hl_id, :Param_name,  @Result)";
    $command = $connection->createCommand($sql); 
        $command->bindValue(':Param_H_year', $param_year);
        $command->bindValue(':Param_H_month', $param_month);
        $command->bindValue(':Param_H_Date', $param_holiday_date);
        $command->bindValue(':Param_Hl_id', $param_holiday_type);
         $command->bindValue(':Param_name', $param_name);
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();

        return $valueOut; 
        
    }

    
    
    public function get_handicate_type(){
        $handicate_type = array('Blindness','Low-vision','Leprosy-cured','Hearing impairment','Loco Motor Disability','Mental Retardation','Mental illness');
        $i=0;
        $list = array();
        foreach($handicate_type as $key=>$val){
            $id = Yii::$app->utility->encryptString($val);
            $list[$i]['id']=$id;
            $list[$i]['type']=$val;
            $i++;
        }
        return $list;
    }
    
    public function get_document_type(){
        $type = array('Marriage Certificate', 'Birth Certificate', 'Voter ID','PAN Card','Aadhar Card','Driving License','Death Certificate', 'Self Declaration', 'Handicap Certificate');
        $i=0;
        $list = array();
        foreach($type as $key=>$val){
            $id = Yii::$app->utility->encryptString($val);
            $list[$i]['id']=$id;
            $list[$i]['type']=$val;
            $i++;
        }
        return $list;
    }
    
    public function get_marital_status(){
        $type = array('Unmarried','Married','Divorcee','Widow','Widower');
        $i=0;
        $list = array();
        foreach($type as $key=>$val){
            $id = Yii::$app->utility->encryptString($val);
            $list[$i]['id']=$id;
            $list[$i]['type']=$val;
            $i++;
        }
        return $list;
    }
    public function hr_get_card_leave_details($param_leave_type){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `hr_get_card_leave_details`(:param_e_id, :param_leave_type)";
	$command = $connection->createCommand($sql); 
        $command->bindValue(':param_e_id', Yii::$app->user->identity->e_id);
        $command->bindValue(':param_leave_type', $param_leave_type);
        $result=$command->queryAll();
        $connection->close();
        return $result; 
    }
    public function get_reason_entryslip(){
        $type = array('Forgot to Swipe','Forgot to bring I-Card','I-Card Sent Renewal','I-Card not issued','Lost I-Card','Other');
        $i=0;
        $list = array();
        foreach($type as $key=>$val){
            $id = Yii::$app->utility->encryptString($val);
            $list[$i]['id']=$id;
            $list[$i]['type']=$val;
            $i++;
        }
        return $list;
    }
    
    
    
    public function fetchstaftype($employment_type)
    {
        if($employment_type=='R')
        {
            $staftype="Regular";
        }
        elseif($employment_type=='C')
        {
            $staftype="CONS";
        }
        else 
        {
            $staftype="-";
        }
        return $staftype;
    }


    public function hr_get_appraise_list_data($param_dept_id,$param_employment_type)
    {
        $param_map_id = $param_auth_type = NULL;
        if(Yii::$app->user->identity->role == '4'){
            $param_auth_type = "A1";
            $param_map_id= Yii::$app->user->identity->e_id;
        }elseif(Yii::$app->user->identity->role == '2'){
            $param_auth_type = "A2";
            $param_map_id= Yii::$app->user->identity->e_id;
        }
        
                
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `hr_get_appraise_list_data`(:param_auth_type, :param_map_id,:param_dept_id,:param_employment_type)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_auth_type', $param_auth_type);
        $command->bindValue(':param_map_id', $param_map_id);
        $command->bindValue(':param_dept_id', $param_dept_id);
        $command->bindValue(':param_employment_type', $param_employment_type);
        $result=$command->queryAll();
        $connection->close();
        return $result; 
    }
    
    public function hr_get_appraise_list()
    {
        $param_map_id = $param_auth_type = NULL;
        if(Yii::$app->user->identity->role == '4'){
            $param_auth_type = "A1";
            $param_map_id= Yii::$app->user->identity->e_id;
        }elseif(Yii::$app->user->identity->role == '2'){
            $param_auth_type = "A2";
            $param_map_id= Yii::$app->user->identity->e_id;
        }
        
                
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `hr_get_appraise_list`(:param_auth_type, :param_map_id)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_auth_type', $param_auth_type);
        $command->bindValue(':param_map_id', $param_map_id);
        $result=$command->queryAll();
        $connection->close();
        return $result; 
    }
    
    public function get_employee_leaves($param_e_id){

        $connection=   Yii::$app->db;
        $connection->open();

        // SET @run_balqty :=0
        Yii::$app->db->createCommand("SET @run_balqty :=null;")->execute();

        $sql =" CALL `hr_get_employee_leaves`(:param_e_id)";
	    $command = $connection->createCommand($sql); 
        $command->bindValue(':param_e_id', $param_e_id);
        $result=$command->queryAll();
        /*echo '<pre>';
        print_r($result);
        die;*/
        $connection->close();
        return $result;       
    }
    
    public function hr_get_leave_request($param_emp_code_list,$param_status)
    {
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `hr_get_leave_request`(:param_emp_code_list,:param_status)";
	    $command = $connection->createCommand($sql); 
        $command->bindValue(':param_emp_code_list', $param_emp_code_list);
        $command->bindValue(':param_status', $param_status);
        $result=$command->queryAll();
        $connection->close();
        return $result; 
    }


    public function hr_get_leave_requests($param_emp_code,$param_status)
    {
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `hr_get_leave_requests`(:param_emp_code,:param_status)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_emp_code', $param_emp_code);
        $command->bindValue(':param_status', $param_status);
        $result=$command->queryAll();
        $connection->close();
        return $result; 
    }

    //    public function hr_get_leave_request($param_auth_type,$param_map_id,$param_status)
    //    {
    //        $connection=   Yii::$app->db;
    //        $connection->open();
    //        $sql =" CALL `hr_get_leave_request`(:param_auth_type, :param_map_id,:param_status)";
    //	$command = $connection->createCommand($sql); 
    //        $command->bindValue(':param_auth_type', $param_auth_type);
    //        $command->bindValue(':param_map_id', $param_map_id);
    //        $command->bindValue(':param_status', $param_status);
    //        $result=$command->queryAll();
    //        $connection->close();
    //        return $result; 
    //    }
    //    public function hr_get_updated_leaves(){
    //        $connection=   Yii::$app->db;
    //        $connection->open();
    //        $sql =" CALL `hr_get_updated_leaves`(:param_role_id, :param_map_id)";
    //	$command = $connection->createCommand($sql); 
    //        $command->bindValue(':param_role_id', Yii::$app->user->identity->role);
    //        $command->bindValue(':param_map_id', Yii::$app->user->identity->e_id);
    //        $result=$command->queryAll();
    //        $connection->close();
    //        return $result; 
    //    }
    
    public function hr_approve_leave($param_role_id, $param_emp_leave_id, $param_leave_type,$param_e_id,$param_from, $param_till, $param_totaldays, $param_remarks, $param_approved_by,$param_status)
    {
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `hr_approve_leave`(:param_role_id,:param_emp_leave_id,:param_leave_type,:param_e_id,:param_from, :param_till, :param_totaldays, :param_remarks,:param_approved_by,:param_status, @Result)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_role_id', $param_role_id);
        $command->bindValue(':param_emp_leave_id', $param_emp_leave_id);
        $command->bindValue(':param_leave_type', $param_leave_type);
        $command->bindValue(':param_e_id', $param_e_id);
        $command->bindValue(':param_from', $param_from);
        $command->bindValue(':param_till', $param_till);
        $command->bindValue(':param_totaldays', $param_totaldays);
        $command->bindValue(':param_remarks', $param_remarks);
        $command->bindValue(':param_approved_by', $param_approved_by);
        $command->bindValue(':param_status', $param_status);
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
    }

    public function hr_add_update_general_form($param_id,$param_e_id, $param_type, $param_entry_date,$param_entry_time,$param_exit_time, $param_reason, $param_other_reason, $param_status, $param_approved_by,$param_approved_on)
    {
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `hr_add_update_general_form`(:param_id,:param_e_id,:param_type,:param_entry_date,:param_entry_time,:param_exit_time, :param_reason, :param_other_reason, :param_status,:param_approved_by,:param_approved_on, @Result)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_id', $param_id);
        $command->bindValue(':param_e_id', $param_e_id);
        $command->bindValue(':param_type', $param_type);
        $command->bindValue(':param_entry_date', $param_entry_date);
        $command->bindValue(':param_entry_time', $param_entry_time);
        $command->bindValue(':param_exit_time', $param_exit_time);
        $command->bindValue(':param_reason', $param_reason);
        $command->bindValue(':param_other_reason', $param_other_reason);
        $command->bindValue(':param_status', $param_status);
        $command->bindValue(':param_approved_by', $param_approved_by);
        $command->bindValue(':param_approved_on', $param_approved_on);
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
    }
       
    public function hr_view_general_form_detail($param_auth_type,$param_e_id,$param_status,$param_type)
    {
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `hr_view_general_form_detail`(:param_auth_type, :param_map_id,:param_status,:param_type)";
	$command = $connection->createCommand($sql); 
        $command->bindValue(':param_auth_type', $param_auth_type);
        $command->bindValue(':param_map_id',$param_e_id);
        $command->bindValue(':param_status', $param_status);
        $command->bindValue(':param_type', $param_type);
        $result=$command->queryAll();
        $connection->close();
        return $result; 
    }
               
    public function hr_get_project_list()
    {
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `hr_get_project_list`()";
	$command = $connection->createCommand($sql); 
        $result=$command->queryAll();
        $connection->close();
        
        $list = array();
        if(!empty($result)){
            $i=0;
            foreach($result as $val){
                $id = base64_encode($val['project_id']);
                $list[$i]['id']=$id;
                $list[$i]['project']=$val['project_name'];
                $i++;
            }
        }
        
        return $list;
    }
    public function get_center_list()
    {
        $projects = array('1'=>'Mohali');
        $i=0;
        $list = array();
        foreach($projects as $key=>$val)
        {
            $id = Yii::$app->utility->encryptString($val);
            $list[$i]['id']=$id;
            $list[$i]['centername']=$val;
            $i++;
        }
        return $list;
    }
    
    public function hr_get_city_list()
    {
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `hr_get_city_list`()";
	$command = $connection->createCommand($sql); 
        $result=$command->queryAll();
        $connection->close();
        
        $list = array();
        if(!empty($result)){
            $i=0;
            foreach($result as $val){
                $id = base64_encode($val['city_id']);
                $list[$i]['id']=$id;
                $list[$i]['cityname']=$val['city_name'];
                $i++;
            }
        }
        return $list;
    }
    
    
    
    public function hr_get_leaves_chart($param_lc_id=NULL){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `hr_get_leaves_chart`(:param_lc_id)";
	$command = $connection->createCommand($sql);
        $command->bindValue(':param_lc_id', $param_lc_id);
        if(!empty($param_lc_id)){
            $result=$command->queryOne();
        }else{
            $result=$command->queryAll();
        }
        
        $connection->close();
        return $result;       
    }

     public function hr_get_holiday_list($ParamH_year=NULL){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `hr_get_holiday_list`(:ParamH_year)";
//echo $ParamH_year;
	$command = $connection->createCommand($sql);
        $command->bindValue(':ParamH_year', $ParamH_year);
        if(empty($ParamH_year)){
            $result=$command->queryOne();
        }else{
            $result=$command->queryAll();
        }
        
        $connection->close();
        return $result;       
    }
    
    public function hr_add_update_leaves_detail_chart($param_id, $param_session_year, $param_session_type, $param_leave_type, $param_total_leaves, $param_pending_leaves, $param_balance_leaves, $param_remarks, $param_employee_code, $param_emp_type, $param_leaves_chart_id)
    {
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `hr_add_update_leaves_detail_chart`(:param_id, :param_session_year, :param_session_type, :param_leave_type, :param_total_leaves, :param_pending_leaves, :param_balance_leaves, :param_remarks, :param_employee_code, :param_emp_type, :param_leaves_chart_id, @Result)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_id', $param_id);
        $command->bindValue(':param_session_year', $param_session_year);
        $command->bindValue(':param_session_type', $param_session_type);
        $command->bindValue(':param_leave_type', $param_leave_type);
        $command->bindValue(':param_total_leaves', $param_total_leaves);
        $command->bindValue(':param_pending_leaves', $param_pending_leaves);
        $command->bindValue(':param_balance_leaves', $param_balance_leaves);
        $command->bindValue(':param_remarks', $param_remarks);
        $command->bindValue(':param_employee_code', $param_employee_code);
        $command->bindValue(':param_emp_type', $param_emp_type);
        $command->bindValue(':param_leaves_chart_id', $param_leaves_chart_id);
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
    }
    public function hr_add_update_master_leave_type($param_lt_id, $param_label, $param_desc)
    {
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `hr_add_update_master_leave_type`(:param_lt_id, :param_label, :param_desc, @Result)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_lt_id', $param_lt_id);
        $command->bindValue(':param_label', $param_label);
        $command->bindValue(':param_desc', $param_desc);
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
    }

     public function hr_add_update_holidays_chart($param_year,$param_holiday_month,$param_holiday_date,$param_hl_id,$param_name){

       /* echo ''.$param_hl_id.'<br>'.
        $param_holiday_month.'<br>'.
        $param_holiday_date.'<br>'.
        $param_year.'<br>'.
        
        die;*/
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `Hr_insert_Holidy_list`(:Param_H_year, :Param_H_month, :Param_H_Date, :Param_Hl_id, :Param_name,  @Result)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':Param_H_year', $param_year);
        $command->bindValue(':Param_H_month', $param_holiday_month);
        $command->bindValue(':Param_H_Date', $param_holiday_date);
        $command->bindValue(':Param_Hl_id', $param_hl_id);
        $command->bindValue(':Param_name', $param_name);
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
    }

    public function hr_add_update_leaves_chart($param_lc_id, $param_master_leave_type,$param_leave_for, $param_can_apply_half_day, $param_leave_count, $param_emp_type, $param_year, $param_session_type, $param_carry_fwd, $param_can_encashment){
/*
        echo ''.$param_lc_id.'<br>'.
        $param_master_leave_type.'<br>'.
        $param_leave_for.'<br>'.
        $param_can_apply_half_day.'<br>'.
        $param_leave_count.'<br>'.
        $param_emp_type.'<br>'.
        $param_year.'<br>'.
        $param_session_type.'<br>'.
        $param_carry_fwd.'<br>'.
        $param_can_encashment;





        die;*/
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `hr_add_update_leaves_chart`(:param_lc_id, :param_master_leave_type, :param_leave_for, :param_can_apply_half_day, :param_leave_count, :param_emp_type, :param_year,  :param_session_type, :param_carry_fwd, :param_can_encashment,  @Result)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_lc_id', $param_lc_id);
        $command->bindValue(':param_master_leave_type', $param_master_leave_type);
        $command->bindValue(':param_leave_for', $param_leave_for);
        $command->bindValue(':param_can_apply_half_day', $param_can_apply_half_day);
        $command->bindValue(':param_leave_count', $param_leave_count);
        $command->bindValue(':param_emp_type', $param_emp_type);
        $command->bindValue(':param_year', $param_year);
        $command->bindValue(':param_session_type', $param_session_type);
        $command->bindValue(':param_carry_fwd', $param_carry_fwd);
        $command->bindValue(':param_can_encashment', $param_can_encashment);
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
    }
       
    public function hr_get_leaves_detail_chart($param_session_year, $param_session_type, $param_leave_type_id, $param_employee_code=NULL, $param_emp_type, $param_is_active=NULL){
        if(empty($param_is_active)){
            $param_is_active = 'Y';
        }
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `hr_get_leaves_detail_chart`(:param_session_year, :param_session_type, :param_leave_type_id, :param_employee_code, :param_emp_type, :param_is_active)";
	$command = $connection->createCommand($sql); 
        $command->bindValue(':param_session_year', $param_session_year);
        $command->bindValue(':param_session_type', $param_session_type);
        $command->bindValue(':param_leave_type_id',$param_leave_type_id);
        $command->bindValue(':param_employee_code',$param_employee_code);
        $command->bindValue(':param_emp_type',$param_emp_type);
        $command->bindValue(':param_is_active',$param_is_active);
        if(!empty($param_employee_code)){
            $result=$command->queryOne();
        }else{
            $result=$command->queryAll();
        }
        
        $connection->close();
        return $result;  
    }
    
    public function hr_add_leave_card_details($param_leave_type, $param_entry_type, $param_from_date, $param_credit, $param_leave, $param_balance, $param_remarks, $param_updated_by, $param_employee_code, $param_status, $param_emp_leave_id=NULL, $param_to_date=NULL){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `hr_add_leave_card_details`(:param_leave_type, :param_entry_type, :param_from_date, :param_credit, :param_leave, :param_balance, :param_remarks, :param_updated_by, :param_employee_code, :param_status, :param_emp_leave_id, :param_to_date, @Result)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_leave_type', $param_leave_type);
        $command->bindValue(':param_entry_type', $param_entry_type);
        $command->bindValue(':param_from_date', $param_from_date);
        $command->bindValue(':param_credit', $param_credit);
        $command->bindValue(':param_leave', $param_leave);
        $command->bindValue(':param_balance', $param_balance);
        $command->bindValue(':param_remarks', $param_remarks);
        $command->bindValue(':param_updated_by', $param_updated_by);
        $command->bindValue(':param_employee_code', $param_employee_code);
        $command->bindValue(':param_status', $param_status);
        $command->bindValue(':param_emp_leave_id', $param_emp_leave_id);
        $command->bindValue(':param_to_date', $param_to_date);
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
    }
    public function hr_get_emp_for_assign_leave($param_gender,$param_emp_type){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `hr_get_emp_for_assign_leave`(:param_gender, :param_emp_type)";
	$command = $connection->createCommand($sql); 
        $command->bindValue(':param_gender', $param_gender);
        $command->bindValue(':param_emp_type',$param_emp_type);
        $result=$command->queryAll();
        $connection->close();
        return $result; 
    }
    
    public function generate_calendar($attendance, $year, $month, $days = array(), $day_name_length = 4, $month_href = NULL, $first_day = 0, $pn = array()){
    $first_of_month = gmmktime(0, 0, 0, $month, 1, $year);
    $day_names = array(); //generate all the day names according to the current locale
    for ($n = 0, $t = (3 + $first_day) * 86400; $n < 7; $n++, $t+=86400) //January 4, 1970 was a Sunday
        $day_names[$n] = ucfirst(gmstrftime('%A', $t)); //%A means full textual day name

    list($month, $year, $month_name, $weekday) = explode(',', gmstrftime('%m, %Y, %B, %w', $first_of_month));
    $weekday = ($weekday + 7 - $first_day) % 7; //adjust for $first_day
    $title   = htmlentities(ucfirst($month_name)) . $year;  //note that some locales don't capitalize month and day names

    @list($p, $pl) = each($pn); @list($n, $nl) = each($pn); //previous and next links, if applicable
    if($p) $p = '<span class="calendar-prev">' . ($pl ? '<a href="' . @htmlspecialchars($pl) . '">' . $p . '</a>' : $p) . '</span>&nbsp;';
    if($n) $n = '&nbsp;<span class="calendar-next">' . ($nl ? '<a href="' . @htmlspecialchars($nl) . '">' . $n . '</a>' : $n) . '</span>';
//    $calendar = "<div class=\"mini_calendar\">\n<table>" . "\n" . 
//        '<caption class="calendar-month">' . $p . ($month_href ? '<a href="' . htmlspecialchars($month_href) . '">' . $title . '</a>' : $title) . $n . "</caption>\n<tr>";
$calendar = "<div class=\"mini_calendar\">\n<table>" . "\n\n<tr>";
    if($day_name_length)
    {   //if the day names should be shown ($day_name_length > 0)
        //if day_name_length is >3, the full name of the day will be printed
        foreach($day_names as $d)
            // $calendar  .= '<th abbr="' . htmlentities($d) . '">' . htmlentities($day_name_length < 4 ? substr($d,0,$day_name_length) : $d) . '</th>';
            $calendar  .= '<th class="cheader" abbr="' . htmlentities($d) . '">'.substr($d,0,3).'</th>';
        $calendar  .= "</tr>\n<tr>";
    }

    if($weekday > 0) 
    {
        for ($i = 0; $i < $weekday; $i++) 
        {
            $calendar  .= '<td>&nbsp;</td>'; //initial 'empty' days
        }
    }
	$jjj=0;
    for($day = 1, $days_in_month = gmdate('t',$first_of_month); $day <= $days_in_month; $day++, $weekday++)
    {
        if($weekday == 7)
        {
            $weekday   = 0; //start a new week
            $calendar  .= "</tr>\n<tr>";
        }
	$nm = $attendance[$jjj]['day'];
        $status = $attendance[$jjj]['status'];
        $d1 = strtolower(date('D', strtotime($attendance[$jjj]['attendancedate'])));
        
        if($d1 == 'sun'){
            $status = "";
            $nm = "<span style='color:lightgrey'>".$attendance[$jjj]['day']."</span>";
        }
        $calendar  .= "<td align='center'><b>$nm</b> <br> $status </td>";
	$jjj++;
    }
    if($weekday != 7) $calendar  .= '<td id="emptydays" colspan="' . (7-$weekday) . '">&nbsp;</td>'; //remaining "empty" days

    return $calendar . "</tr>\n</table>\n</div>\n";
    }
    public function hr_add_update_CEA_child_details($param_ea_id, $param_employee_code, $param_ef_id, $param_class_std, $param_school_name, $param_ay_start, $param_ay_end, $param_financial_year){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `hr_add_update_CEA_child_details`(:param_ea_id, :param_employee_code, :param_ef_id, :param_class_std, :param_school_name, :param_ay_start, :param_ay_end, :param_financial_year, @Result)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_ea_id', $param_ea_id);
        $command->bindValue(':param_employee_code', $param_employee_code);
        $command->bindValue(':param_ef_id', $param_ef_id);
        $command->bindValue(':param_class_std', $param_class_std);
        $command->bindValue(':param_school_name', $param_school_name);
        $command->bindValue(':param_ay_start', $param_ay_start);
        $command->bindValue(':param_ay_end', $param_ay_end);
        $command->bindValue(':param_financial_year', $param_financial_year);
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
    }
    public function hr_get_CEA_child_details($param_employee_code,$param_ef_id, $param_financial_year, $param_ea_id){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `hr_get_CEA_child_details`(:param_employee_code, :param_ef_id, :param_financial_year, :param_ea_id)";
	$command = $connection->createCommand($sql); 
        $command->bindValue(':param_employee_code', $param_employee_code);
        $command->bindValue(':param_ef_id',$param_ef_id);
        $command->bindValue(':param_financial_year',$param_financial_year);
        $command->bindValue(':param_ea_id',$param_ea_id);
        if(!empty($param_ef_id) OR !empty($param_ea_id)){
            $result=$command->queryOne();
        }else{
            $result=$command->queryAll();
        }
        $connection->close();
        return $result; 
    }
    public function hr_get_edu_allowance_claim($param_ea_id, $param_employee_code,$param_financial_year, $param_status){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `hr_get_edu_allowance_claim`(:param_ea_id, :param_employee_code, :param_financial_year, :param_status)";
	$command = $connection->createCommand($sql); 
        $command->bindValue(':param_ea_id', $param_ea_id);
        $command->bindValue(':param_employee_code', $param_employee_code);
        $command->bindValue(':param_financial_year',$param_financial_year);
        $command->bindValue(':param_status',$param_status);
        if(!empty($param_ea_id) AND !empty($param_employee_code) AND !empty($param_financial_year)){
            $result=$command->queryOne();
        }else{
            $result=$command->queryAll();
        }
        $connection->close();
        return $result; 
    }
     public function hr_add_update_edu_allowance_claim($param_action_type, $param_id, $param_ea_id, $param_employee_code, $param_financial_year, $param_books_amount, $param_shoes_amount, $param_notebooks, $param_uniform_amount, $param_tuition_fees, $param_hostel_fees, $param_claim_type, $param_doc_type, $param_doc_path, $param_emp_remarks, $param_sanc_books_amount, $param_sanc_shoes_amount, $param_sanc_notebooks, $param_sanc_uniform_amount, $param_sanc_tuition_fees, $param_sanc_hostel_fees, $param_status, $param_hr_remarks, $param_finance_remarks, $param_total_sanc_amt=NULL, $param_hr_approved_by=NULL, $param_finance_approved_by=NULL){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `hr_add_update_edu_allowance_claim`(:param_action_type, :param_id, :param_ea_id, :param_employee_code, :param_financial_year, :param_books_amount, :param_shoes_amount, :param_notebooks, :param_uniform_amount, :param_tuition_fees, :param_hostel_fees, :param_claim_type, :param_doc_type, :param_doc_path, :param_emp_remarks, :param_sanc_books_amount, :param_sanc_shoes_amount, :param_sanc_notebooks, :param_sanc_uniform_amount, :param_sanc_tuition_fees, :param_sanc_hostel_fees, :param_status, :param_hr_approved_by, :param_hr_remarks, :param_finance_approved_by, :param_finance_remarks, :param_total_sanc_amt, @Result)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_action_type', $param_action_type);
        $command->bindValue(':param_id', $param_id);
        $command->bindValue(':param_ea_id', $param_ea_id);
        $command->bindValue(':param_employee_code', $param_employee_code);
        $command->bindValue(':param_financial_year', $param_financial_year);
        $command->bindValue(':param_books_amount', $param_books_amount);
        $command->bindValue(':param_shoes_amount', $param_shoes_amount);
        $command->bindValue(':param_notebooks', $param_notebooks);
        $command->bindValue(':param_uniform_amount', $param_uniform_amount);
        $command->bindValue(':param_tuition_fees', $param_tuition_fees);
        $command->bindValue(':param_hostel_fees', $param_hostel_fees);
        $command->bindValue(':param_claim_type', $param_claim_type);
        $command->bindValue(':param_doc_type', $param_doc_type);
        $command->bindValue(':param_doc_path', $param_doc_path);
        $command->bindValue(':param_emp_remarks', $param_emp_remarks);
        $command->bindValue(':param_sanc_books_amount', $param_sanc_books_amount);
        $command->bindValue(':param_sanc_shoes_amount', $param_sanc_shoes_amount);
        $command->bindValue(':param_sanc_notebooks', $param_sanc_notebooks);
        $command->bindValue(':param_sanc_uniform_amount', $param_sanc_uniform_amount);
        $command->bindValue(':param_sanc_tuition_fees', $param_sanc_tuition_fees);
        $command->bindValue(':param_sanc_hostel_fees', $param_sanc_hostel_fees);
        $command->bindValue(':param_status', $param_status);
        $command->bindValue(':param_hr_approved_by', $param_hr_approved_by);
        $command->bindValue(':param_hr_remarks', $param_hr_remarks);
        $command->bindValue(':param_finance_approved_by', $param_finance_approved_by);
        $command->bindValue(':param_finance_remarks', $param_finance_remarks);
        $command->bindValue(':param_total_sanc_amt', $param_total_sanc_amt);
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
     }
     
     /*
      * Function use when HR will update leave
      */
     public function hr_update_emp_leave_chart($param_action_type, $param_employee_code, $param_leave_type, $param_pending_leaves, $param_balance_leaves)
    {
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `hr_update_emp_leave_chart`(:param_action_type, :param_employee_code, :param_leave_type, :param_pending_leaves, :param_balance_leaves, @Result)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_action_type', $param_action_type);
        $command->bindValue(':param_employee_code', $param_employee_code);
        $command->bindValue(':param_leave_type', $param_leave_type);
        $command->bindValue(':param_pending_leaves', $param_pending_leaves);
        $command->bindValue(':param_balance_leaves', $param_balance_leaves);
        
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
    }
    
    public function hr_add_update_attendance($param_attid, $param_employee_code,$param_attendance_date, $param_attendance_mark, $param_status){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `hr_add_update_attendance`(:param_role_id, :param_attid, :param_employee_code, :param_attendance_date, :param_attendance_mark, :param_updated_by, :param_status, @Result)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_role_id', Yii::$app->user->identity->role);
        $command->bindValue(':param_attid', $param_attid);
        $command->bindValue(':param_employee_code', $param_employee_code);
        $command->bindValue(':param_attendance_date', $param_attendance_date);
        $command->bindValue(':param_attendance_mark', $param_attendance_mark);
        $command->bindValue(':param_updated_by', Yii::$app->user->identity->e_id);
        $command->bindValue(':param_status', $param_status);
        
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
    }
    

    public function hr_get_biometric_attendance($param_view_type,$param_employee_code,$param_attendate){ 

  

      /*  echo '-'.$param_view_type.'<br>';
        echo '-'.$param_employee_code.'<br>';
        echo '-'.$param_attendate.'<br>';
        die;*/


        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `hr_get_biometric_attendance`(:param_view_type, :param_employee_code,  :param_attendate, @Result)";

        $command = $connection->createCommand($sql); 
        
        $command->bindValue(':param_view_type', $param_view_type);
        $command->bindValue(':param_employee_code', $param_employee_code);
        $command->bindValue(':param_attendate', $param_attendate);
        
        
        $result=$command->queryAll();
        
        $connection->close();
        
        return $result; 
    }

    public function hr_get_manage_biometric_attendance($param_view_type,$param_employee_code,$param_attendate1,$param_attendate2){ 

  

      /*  echo '-'.$param_view_type.'<br>';
        echo '-'.$param_employee_code.'<br>';
        echo '-'.$param_attendate.'<br>';
        die;*/


        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `hr_get_manage_biometric_attendance`(:param_view_type, :param_employee_code,  :param_attendate1, :param_attendate2, @Result)";

        $command = $connection->createCommand($sql); 
        
        $command->bindValue(':param_view_type', $param_view_type);
        $command->bindValue(':param_employee_code', $param_employee_code);
        $command->bindValue(':param_attendate1', $param_attendate1);
        $command->bindValue(':param_attendate2', $param_attendate2);
        
        
        $result=$command->queryAll();
        
        $connection->close();
        
        return $result; 
    }



    public function hr_get_attendance($param_role_id,$param_view_type, $param_attid, $param_employee_code, $param_attendate, $param_status, $param_created_by){ 

          /*      echo '-'.$param_role_id.'<br>';

            echo '-'.$param_view_type.'<br>';
            echo '-'.$param_attid.'<br>';
            echo '-'.$param_employee_code.'<br>';
            echo '-'.$param_attendate.'<br>';
            echo '-'.$param_status.'<br>';
            echo $param_created_by.'<br>';
            die;*/



        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `hr_get_attendance`(:param_role_id, :param_view_type, :param_attid, :param_employee_code, :param_attendate, :param_status, :param_created_by)";

	   $command = $connection->createCommand($sql); 
        $command->bindValue(':param_role_id', $param_role_id);
        $command->bindValue(':param_view_type', $param_view_type);
        $command->bindValue(':param_attid', $param_attid);
        $command->bindValue(':param_employee_code', $param_employee_code);
        $command->bindValue(':param_attendate', $param_attendate);
        $command->bindValue(':param_status', $param_status);
        $command->bindValue(':param_created_by', $param_created_by);
        
        if(!empty($param_attid) AND !empty($param_employee_code)){
            $result=$command->queryOne();
        }elseif($param_view_type == 'Day' AND !empty($param_employee_code)){
            $result=$command->queryOne();
        }else{
            $result=$command->queryAll();
        }
        $connection->close();
        return $result; 
    }


    public function hr_get_attendance_date_dept_wise($param_role_id,$param_view_type, $param_attid, $param_employee_code, $param_attendate, $param_status, $param_created_by,$param_dept_id,$param_employment_type){ 

        $connection = Yii::$app->db;
        $connection->open();
        $sql = "CALL `hr_get_attendance_date_dept_wise`(:param_role_id, :param_view_type, :param_attid, :param_employee_code, :param_attendate, :param_status, :param_created_by, :param_dept_id, :param_employment_type)";

        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_role_id', $param_role_id);
        $command->bindValue(':param_view_type', $param_view_type);
        $command->bindValue(':param_attid', $param_attid);
        $command->bindValue(':param_employee_code', $param_employee_code);
        $command->bindValue(':param_attendate', $param_attendate);
        $command->bindValue(':param_status', $param_status);
        $command->bindValue(':param_created_by', $param_created_by);
        $command->bindValue(':param_dept_id', $param_dept_id);
        $command->bindValue(':param_employment_type', $param_employment_type);
        
        if(!empty($param_attid) AND !empty($param_employee_code)){
            $result=$command->queryOne();
        }elseif($param_view_type == 'Day' AND !empty($param_employee_code)){
            $result=$command->queryOne();
        }else{
            $result=$command->queryAll();
        }
        $connection->close();
        return $result; 
    }
}