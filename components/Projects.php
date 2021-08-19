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
class Projects extends Component {
    
    public function pr_get_projects($param_dept_id=NULL,$param_project_id=NULL){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `pr_get_projects`(:param_dept_id,:param_project_id)";
		$command = $connection->createCommand($sql); 
        $command->bindValue(':param_dept_id', $param_dept_id);
        $command->bindValue(':param_project_id', $param_project_id);
        if(!empty($param_project_id)){
            $result=$command->queryOne();
        }else{
            $result=$command->queryAll();
        }
        
        $connection->close();
        return $result;       
    }
	
	
	public function get_project_tasks($taskid=NULL){
		if(Yii::$app->session->get('projects_id')){
			$projects_id=Yii::$app->session->get('projects_id'); 
		}
        $connection=Yii::$app->db;
        $connection->open();
        $sql =" CALL `pr_get_project_tasks`(:projectid,:taskid)";
		$command = $connection->createCommand($sql); 
		$command->bindValue(':projectid', $projects_id);
		$command->bindValue(':taskid', $taskid);
        $result=$command->queryAll();
        $connection->close();
        return $result;   
      }
	public function get_project_technology(){
        $connection=Yii::$app->db;
        $connection->open();
        $sql =" CALL `get_project_technology`()";
		$command = $connection->createCommand($sql); 
        $result=$command->queryAll();
        $connection->close();
        return $result;   
      }
	public function update_project_technology($project_id,$ids=NULL){
        $connection=Yii::$app->db;
        $connection->open();
        $sql =" CALL `pr_update_project_technology`(:project_id,:ids)";
		$command = $connection->createCommand($sql); 
		$command->bindValue(':project_id', $project_id);
		$command->bindValue(':ids', $ids);
        $result=$command->queryAll();
        $connection->close();
        return $result;   
      }
	
	public function get_manpower($param_project_id){
        $connection=Yii::$app->db;
        $connection->open();
        $sql =" CALL `store_get_project_manpower`(:param_project_id)";
		$command = $connection->createCommand($sql); 
        $command->bindValue(':param_project_id', $param_project_id);
        $result=$command->queryAll();
        $connection->close();
        return $result;   
      }
	
	public function get_pur_fund($param_project_id, $added_by, $action='GET'){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `store_get_project_fund`(:param_project_id, :added_by, :action)";
		$command = $connection->createCommand($sql); 
        $command->bindValue(':param_project_id', $param_project_id);
        $command->bindValue(':added_by', $added_by);
        $command->bindValue(':action', $action);
        $result=$command->queryAll();
        $connection->close();
        return $result;   
      }
	
	 public function del_project_cat($param_project_id, $added_by, $action='DELETE'){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `store_get_project_fund`(:param_project_id, :added_by, :action)";
		$command = $connection->createCommand($sql); 
        $command->bindValue(':param_project_id', $param_project_id);
        $command->bindValue(':added_by', $added_by);
        $command->bindValue(':action', $action);
        $result=$command->execute();
        $connection->close();
        return $result;   
      }
	  public function get_pr_cat(){
		$connection=   Yii::$app->db;
        $connection->open();
        $sql ="SELECT * FROM pr_project_category where is_active='Y'";
		$command = $connection->createCommand($sql); 
 		$result=$command->queryAll();
        $connection->close();
        return $result;       
    }
	  public function add_update_pur_fund($param_project_id, $added_by, $pc_cat, $start_date, $end_date, $param_amount){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `store_add_project_fund_div`(:param_project_id, :added_by, :pc_cat, :start_date, :end_date, :param_amount, @`result`)";
		$command = $connection->createCommand($sql); 
        $command->bindValue(':param_project_id', $param_project_id);
        $command->bindValue(':added_by', $added_by);
        $command->bindValue(':pc_cat', $pc_cat);
        $command->bindValue(':start_date', $start_date);
        $command->bindValue(':end_date', $end_date);
        $command->bindValue(':param_amount', $param_amount);
        $command->execute();
        $valueOut = $connection->createCommand("select @result as res;")->queryScalar();
        $connection->close();
        return $valueOut;
      }
     /* public function store_project_fund_div($param_action, $param_id_pf=NULL, $param_project_id, $pc_cat,  $added_by,$start_date, $end_date, $param_amount){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `store_get_project_fund_div`(:param_action,:param_id_pf, :param_project_id, :pc_cat, :added_by,:start_date, :end_date, :param_amount, @`Result`)";
		$command = $connection->createCommand($sql); 
        $command->bindValue(':param_action', $param_action);
        $command->bindValue(':param_id_pf', $param_id_pf);
        $command->bindValue(':param_project_id', $param_project_id);
        $command->bindValue(':pc_cat', $pc_cat);
        $command->bindValue(':added_by', $added_by);
        $command->bindValue(':start_date', $start_date);
        $command->bindValue(':end_date', $end_date);
        $command->bindValue(':param_amount', $param_amount);
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
      } 
    		last param    	proposal_submission_date
			projectrefno   contact_no
			proposal_no    alternate_contact_no
			order_num      num_working_days
			funding_agency  num_manpower
			objectives      technology_used
			filenumber		 duration_month  */
    public function pr_add_update_project_detail($param_action, $param_project_id, $param_project_name, $param_short_name, $param_project_type, $param_description, $param_address, $param_contact_person, $projectrefno, $proposal_no, $param_project_cost, $param_start_date, $param_end_date, $order_num, $filenumber, $funding_agency, $objectives, $param_manager_dept, $param_approval_doc, $param_status, $param_is_active,$proposal_submission_date){
         $param_approval_doc=NULL;
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `pr_add_update_project_detail`(:param_action, :param_project_id, :param_project_name, :param_short_name, :param_project_type, :param_description, :param_address, :param_contact_person, :param_projectrefno, :param_proposal_no, :param_project_cost, :param_start_date, :param_end_date, :param_order_num, :param_filenumber, :param_funding_agency, :param_objectives, :param_manager_dept, :param_approval_doc, :param_updated_by, :param_status, :param_is_active, :param_proposal_submission_date, @`result`)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_action', $param_action);
        $command->bindValue(':param_project_id', $param_project_id);
        $command->bindValue(':param_project_name', $param_project_name);
        $command->bindValue(':param_short_name', $param_short_name);
        $command->bindValue(':param_project_type', $param_project_type);
        $command->bindValue(':param_description', $param_description);
        $command->bindValue(':param_address', $param_address);
        $command->bindValue(':param_projectrefno', $projectrefno);
        $command->bindValue(':param_proposal_no', $proposal_no);
        $command->bindValue(':param_project_cost', $param_project_cost);
        $command->bindValue(':param_start_date', $param_start_date);
        $command->bindValue(':param_end_date', $param_end_date);
        $command->bindValue(':param_order_num', $order_num);
        $command->bindValue(':param_filenumber', $filenumber);
        $command->bindValue(':param_funding_agency', $funding_agency);
        $command->bindValue(':param_objectives', $objectives);
        $command->bindValue(':param_manager_dept', $param_manager_dept);
        $command->bindValue(':param_approval_doc', $param_approval_doc);
        $command->bindValue(':param_contact_person', $param_contact_person);
        $command->bindValue(':param_updated_by', Yii::$app->user->identity->e_id);
        $command->bindValue(':param_status', $param_status);
        $command->bindValue(':param_is_active', $param_is_active);
        $command->bindValue(':param_proposal_submission_date', $proposal_submission_date);
        $command->execute();
        $valueOut = $connection->createCommand("select @result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
    }
    
    public function pr_get_resources($param_project_id, $Param_team_member, $Param_role_id, $Param_team_id){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `pr_get_resources`(:param_project_id, :Param_team_member, :Param_role_id, :Param_team_id)";
	$command = $connection->createCommand($sql); 
        $command->bindValue(':param_project_id', $param_project_id);
        $command->bindValue(':Param_team_member', $Param_team_member);
        $command->bindValue(':Param_role_id', $Param_role_id);
        $command->bindValue(':Param_team_id', $Param_team_id);
        if(!empty($Param_team_id)){
            $result=$command->queryOne();
        }else{
            $result=$command->queryAll();
        }
        
        $connection->close();
        return $result;       
    }
    
    public function pr_add_update_resources($Param_action_type, $Param_team_id, $Param_project_id, $Param_team_member, $Param_role_id, $Param_responsibility){
       
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `pr_add_update_resources`(:Param_action_type, :Param_team_id, :Param_project_id, :Param_team_member, :Param_role_id, :Param_responsibility, :Param_updated_by, @`result`)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':Param_action_type', $Param_action_type);
        $command->bindValue(':Param_team_id', $Param_team_id);
        $command->bindValue(':Param_project_id', $Param_project_id);
        $command->bindValue(':Param_team_member', $Param_team_member);
        $command->bindValue(':Param_role_id', $Param_role_id);
        $command->bindValue(':Param_responsibility', $Param_responsibility);
        $command->bindValue(':Param_updated_by', Yii::$app->user->identity->e_id);
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
    }
}