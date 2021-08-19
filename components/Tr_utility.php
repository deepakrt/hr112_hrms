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
class Tr_utility extends Component 
{
       

    public function tr_get_technologies($Param_technology_id=NULL){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL tr_get_technologies(:Param_technology_id)";
        
        $command = $connection->createCommand($sql); 
       $command->bindValue(':Param_technology_id', $Param_technology_id);
        
      

            $result=$command->queryAll();
       
        
        $connection->close();
        return $result; 
    }

public function tr_get_courses($Param_course_id=NULL){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL tr_get_courses(:Param_course_id)";
        
        $command = $connection->createCommand($sql); 
       $command->bindValue(':Param_course_id', $Param_course_id);
        
      

            $result=$command->queryAll();
       
        
        $connection->close();
        return $result; 
    }

    public function tr_get_training_applied_status($Param_tpm_id,$Param_employee_code){
        
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL tr_get_training_applied_status(:Param_tpm_id,:Param_employee_code,@Result)";
        
        $command = $connection->createCommand($sql); 
        $command->bindValue(':Param_tpm_id', $Param_tpm_id);
        $command->bindValue(':Param_employee_code', $Param_employee_code);
        
      

        $result=$command->queryOne();
       
        
        $connection->close();
        return $result; 
    }

    
    public function tr_get_departments($Param_department_id=NULL){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL tr_get_departments(:Param_department_id)";
        
        $command = $connection->createCommand($sql); 
       $command->bindValue(':Param_department_id', $Param_department_id);
        
      

            $result=$command->queryAll();
       
        
        $connection->close();
        return $result; 
    }

public function tr_get_trainers($Param_trainer_id=NULL){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL tr_get_trainers(:Param_trainer_id)";
        
        $command = $connection->createCommand($sql); 
       $command->bindValue(':Param_trainer_id', $Param_trainer_id);
        
      

            $result=$command->queryAll();
       
        
        $connection->close();
        return $result; 
    }

    public function tr_get_trainingprograms($Param_tpm_id=NULL){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL tr_get_trainingprograms(:Param_tpm_id)";
        
        $command = $connection->createCommand($sql); 
       $command->bindValue(':Param_tpm_id', $Param_tpm_id);
        
      

            $result=$command->queryAll();
       
        
        $connection->close();
        return $result; 
    }
     public function tr_get_trainingprogram_role($Param_role_id=NULL){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL tr_get_trainingprogram_role(:Param_role_id)";
        
        $command = $connection->createCommand($sql); 
       $command->bindValue(':Param_role_id', $Param_role_id);
        
      

            $result=$command->queryAll();
       
        
        $connection->close();
        return $result; 
    }

    
    public function tr_apply_trainingprogram($Param_tpm_id, $Param_employee_code,$Param_status,$Param_action_type,$Param_applied_id=0){

        $connection= Yii::$app->db;
        $connection->open();


        $sql =" CALL `tr_add_update_training_applied`(:Param_tpm_id,:Param_employee_code,:Param_status,:Param_action_type, :Param_applied_id , @Result )";
        
        $command = $connection->createCommand($sql); 
        $command->bindValue(':Param_tpm_id', $Param_tpm_id);
        $command->bindValue(':Param_employee_code', $Param_employee_code);
        $command->bindValue(':Param_status', $Param_status);
        $command->bindValue(':Param_action_type', $Param_action_type);

        if($Param_action_type == 'ADD')
        {
            $Param_applied_id = 0;
        }
        $command->bindValue(':Param_applied_id', $Param_applied_id);
        
        
        
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 


    }
    
    public function tr_get_training_by_applied_id_action($Param_tpm_id, $Param_employee_code, $Param_applied_id)
    {

        $connection= Yii::$app->db;
        $connection->open();
        //var_dump($Param_employee_code); die;

        $sql =" CALL `tr_get_training_by_applied_id_action`(:Param_tpm_id,:Param_employee_code,:Param_applied_id )";
        
        $command = $connection->createCommand($sql); 
        
        $command->bindValue(':Param_tpm_id', $Param_tpm_id);
        $command->bindValue(':Param_employee_code', $Param_employee_code);
        $command->bindValue(':Param_applied_id', $Param_applied_id);
        
        
        $result=$command->queryOne();
         // $result=$command->queryAll();
       
        
        $connection->close();
        // die();

        return $result; 
    }


// tr_get_all_training_applied_action

    public function tr_get_all_training_applied_action( $Param_employee_code)
    {
        $connection= Yii::$app->db;
        $connection->open();

        $sql =" CALL `tr_get_training_applied_action`(:Param_employee_code,:Param_reporting, @Result )";
        
        $command = $connection->createCommand($sql); 
        
        $command->bindValue(':Param_employee_code', $Param_employee_code);
        $command->bindValue(':Param_reporting', $Param_reporting);
        
        $result=$command->queryAll();
       
        $connection->close();
        return $result; 
    }

    public function tr_get_training_applied_action( $Param_employee_code,$Param_reporting)
    {
        $connection= Yii::$app->db;
        $connection->open();

        $sql =" CALL `tr_get_training_applied_action`(:Param_employee_code,:Param_reporting, @Result )";
        
        $command = $connection->createCommand($sql); 
        
        $command->bindValue(':Param_employee_code', $Param_employee_code);
        $command->bindValue(':Param_reporting', $Param_reporting);
        
        
        $result=$command->queryAll();
       
        $connection->close();
        return $result; 


    }
    

    public function tr_get_training_venue_action($Param_one)
    {
        $connection= Yii::$app->db;
        $connection->open();

        $sql =" CALL `trng_summary_report`(:Param_one, @Result )";

        $command = $connection->createCommand($sql); 
        $command->bindValue(':Param_one', $Param_one);
        
        $result=$command->queryAll();
       
        $connection->close();
        return $result; 


    }

    public function trng_Consolidated_report()
    {
        $connection= Yii::$app->db;
        $connection->open();

        $sql =" CALL `trng_Consolidated_report`(@Result )";
        
        $command = $connection->createCommand($sql);
        $result=$command->queryAll();
       
        $connection->close();
        return $result; 


    }


    public function tr_add_update_technology($Param_technology_id, $Param_action_type, $Param_technology_name, $Param_technology_code){
         $connection= Yii::$app->db;
        $connection->open();

        //Param_technology_id


        $sql =" CALL `tr_add_update_technology`(:Param_technology_id,:Param_action_type,:Param_technology_name, :Param_technology_code, @Result )";
        
        $command = $connection->createCommand($sql); 
        $command->bindValue(':Param_technology_name', $Param_technology_name);
        $command->bindValue(':Param_technology_code', $Param_technology_code);
        $command->bindValue(':Param_action_type', $Param_action_type);
        $command->bindValue(':Param_technology_id', $Param_technology_id);

        
        
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
    }

    public function tr_add_update_trainingprogram($Param_action_type , $Param_tpmid, $Param_course_id, $Param_technology_id, $Param_program_code,$Param_startDate,$Param_endDate, $Param_startTime, $Param_endTime, $Param_training_fees, $Param_installment, $Param_trainer_id, $Param_trainer_amt, $Param_department_id, $Param_seats, $Param_role_id)
    {
         $connection= Yii::$app->db;
        $connection->open();

        //Param_technology_id


        $sql =" CALL `tr_add_update_training_program`(:Param_action_type , :Param_tpmid, :Param_course_id, :Param_technology_id, :Param_program_code,:Param_startDate,:Param_endDate, :Param_startTime, :Param_endTime, :Param_training_fees, :Param_installment, :Param_trainer_id, :Param_trainer_amt, :Param_department_id, :Param_seats, :Param_role_id, @Result )";
        
        $command = $connection->createCommand($sql); 
        $command->bindValue(':Param_action_type', $Param_action_type);
        $command->bindValue(':Param_tpmid', $Param_tpmid);
        $command->bindValue(':Param_course_id', $Param_course_id);
        $command->bindValue(':Param_technology_id', $Param_technology_id);

         $command->bindValue(':Param_program_code', $Param_program_code);
        $command->bindValue(':Param_startDate', $Param_startDate);

        $command->bindValue(':Param_endDate', $Param_endDate);
        $command->bindValue(':Param_startTime', $Param_startTime);
         $command->bindValue(':Param_endTime', $Param_endTime);

        $command->bindValue(':Param_training_fees', $Param_training_fees);
        $command->bindValue(':Param_installment', $Param_installment);
        $command->bindValue(':Param_trainer_id', $Param_trainer_id);
         $command->bindValue(':Param_trainer_amt', $Param_trainer_amt);
        $command->bindValue(':Param_department_id', $Param_department_id);

        $command->bindValue(':Param_seats', $Param_seats);
        $command->bindValue(':Param_role_id', $Param_role_id);       
      
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
    }


    public function trng_get_consolidated_report()
    {
        $connection = Yii::$app->db;
        $connection->open();
        
        $sql = "CALL trng_Consolidated_report(@Result)";

        $command = $connection->createCommand($sql);

        $result = $command->queryAll();
       
        $connection->close();
        return $result; 
    }
    public function trng_get_consolidated_report2()
    {
        $connection = Yii::$app->db;
        $connection->open();
        
        $sql = "CALL trng_Consolidated_report2(@Result)";

        $command = $connection->createCommand($sql);

        $result = $command->queryAll();
       
        $connection->close();
        return $result; 
    }

    public function trng_get_consolidated_report_OLD()
    {
        $connection = Yii::$app->db;
        $connection->open();
        
        $sql = "SELECT 
                    tv.id AS venueID,
                    COALESCE(Venue, 'total_all_venues') AS Venue,    
                    district_code,
                    COALESCE(district_name, 'total_emp_dist_wise') AS district_name,
                    COUNT(employee_code) cntemployee_code
                FROM
                    trng_venues as tv
                LEFT JOIN trng_venues_district as tvd on tv.id = tvd.venue_id
                LEFT JOIN trng_applied as ta on tvd.district_code = ta.District_id

                GROUP BY 
                    Venue asc,    
                    district_name
                WITH ROLLUP";

        $command = $connection->createCommand($sql);

        $result = $command->queryAll();
       
        $connection->close();
        return $result; 
    }

    public function get_trngdata_venue_wise($venueID,$course_id,$stateid,$dist_id,$tech_id)
    {
        $connection = Yii::$app->db;
        $connection->open();
        // $sql ="";
        
        /*$sql ="SELECT 
                    ta.applied_id, ta.employee_code, emp.fname, emp.lname, emp.gender, emp.belt_no, emp.belt_no, tpm.Programme_code, tcm.course_name, tct.technology_name, tbm.grp_nm, tpm.start_date, tpm.end_date, tpm.start_time, tpm.end_time, ta.status
                     FROM
                    trng_applied as ta,employee as emp, trng_training_program_master as tpm, trng_course_master as tcm, trng_course_technology as tct, training_batch_master as tbm
                where
                        ta.tpm_id = tpm.tpm_id 
                    and ta.employee_code = emp.employee_code
                    and tpm.course_id = tcm.course_id  
                    and tpm.technology_id = tct.technology_id   
                    and ta.grp_id = tbm.grp_id   
                    and ta.venue_id = $venueID
                    and tbm.venue_id = $venueID
                    and ta.District_id = $dst_code
                    and ta.is_active  = 1
                order by 
                        ta.applied_id asc";*/

        $concat = '';

        if($dist_id != null && $dist_id != '')
        {
            $concat = ' and ta.District_id ='. $dist_id;
            $concat .= ' and tvd.district_code ='. $dist_id;
            // and tvd.district_code
        }

         $sql = "SELECT 
                      ta.applied_id, ta.employee_code, emp.fname, emp.lname, emp.gender, emp.belt_no, emp.belt_no, tvd.district_name, tpm.Programme_code, tcm.course_name, tct.technology_name, tbm.grp_nm, tpm.start_date, tpm.end_date, tpm.start_time, tpm.end_time, ta.status
                FROM
                  trng_applied as ta,trng_venues_district as tvd,employee as emp, trng_training_program_master as tpm, trng_course_master as tcm, trng_course_technology as tct, training_batch_master as tbm
                where
                  ta.tpm_id = tpm.tpm_id 
                  and ta.venue_id = tvd.venue_id
                  and ta.District_id = tvd.district_code
                  and ta.employee_code = emp.employee_code
                  and tpm.course_id = tcm.course_id                    
                  and tpm.technology_id = tct.technology_id   
                  and ta.grp_id = tbm.grp_id  
                  and ta.venue_id = $venueID
                  and tbm.venue_id = $venueID
                  and ta.course_id = $course_id
                  and tpm.course_id = $course_id 
                  and ta.course_id = $course_id 
                  and tcm.course_id = $course_id 

                  and ta.is_active  = 1 and tpm.technology_id=$tech_id
                  ".$concat."
                order by 
                    ta.applied_id asc";

        // echo "<pre>".$sql; die();

        $command = $connection->createCommand($sql); 
        $valueOut = $command->queryAll();
        $connection->close();

        /*$sql = "CALL `USP_InsertGroup`(:PARAMdepartment_Id,:PARAMCourse_Id,:PARAMGroupType,:PARAM_tpm_Id,:PARAM_venue_id,:PARAMstudentId_for_group,:PARAM_District_id,@Result)";
 
        $command = $connection->createCommand($sql); 
        $command->bindValue(':PARAMdepartment_Id', $PARAMdepartment_Id); 
        $command->bindValue(':PARAMCourse_Id', $PARAMCourse_Id); 
        $command->bindValue(':PARAMGroupType', $PARAMGroupType); 
        $command->bindValue(':PARAM_tpm_Id', $PARAM_tpm_Id); 
        $command->bindValue(':PARAM_venue_id', $PARAM_venue_id); 
        $command->bindValue(':PARAMstudentId_for_group', $PARAMstudentId_for_group); 
        $command->bindValue(':PARAM_District_id', $PARAM_District_id); 
        $command->execute();

        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();*/

        /*echo "<pre>"; 
            print_r($valueOut);
        die();*/

        return $valueOut; 
    }



    public function getall_departments1()
    {
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `get_dept`(NULL)";
        // $sql =" select * from master_department where is_active='Y'";
        $command = $connection->createCommand($sql); 
        $valueOut = $command->queryAll();
        $connection->close();
        return $valueOut; 
        
    }
    
}