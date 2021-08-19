<?php
namespace app\components\facade;
use \PDO;
use Yii;
   class Project{
        public static function pmis_get_projects($param_project_id){
            $connection=   Yii::$app->db;
            $connection->open();
            $sql =" CALL `pmis_get_projects`( :param_dept_id, :param_project_id)";

            $command = $connection->createCommand($sql); 
            $command->bindValue(':param_project_id', $param_project_id, PDO::PARAM_INT);
            $command->bindValue(':param_dept_id', Yii::$app->user->identity->dept_id,PDO::PARAM_INT);

            if(!empty($param_project_id)){
                $result=$command->queryOne();
            }else{
                $result=$command->queryAll();
            }

            $connection->close();
            return $result;       
        }
        
        public static function pr_get_proposals($param_proposal_id, $param_proposal_status){
            $connection=   Yii::$app->db;
            $connection->open();
            
            $sql =" CALL `pmis_get_proposals`( :param_dept_id, :param_proposal_id, :param_proposal_status)";

            $command = $connection->createCommand($sql); 
            $command->bindValue(':param_proposal_id', $param_proposal_id, PDO::PARAM_INT);
            $command->bindValue(':param_dept_id', Yii::$app->user->identity->dept_id,PDO::PARAM_INT);
            $command->bindValue(':param_proposal_status', $param_proposal_status);

            if(!empty($param_proposal_id)){
                $result=$command->queryOne();
            }else{
                $result=$command->queryAll();
            }

            $connection->close();
            return $result;  
        }
    
        public static function pr_add_update_proposal_status($param_proposal_id, $param_proposal_status){
            $connection=   Yii::$app->db;
            $connection->open();
            
            $sql =" CALL `pmis_update_proposal_status`(:param_proposal_id, :param_proposal_status)";

            $command = $connection->createCommand($sql); 
            $command->bindValue(':param_proposal_id', $param_proposal_id, PDO::PARAM_INT);            
            $command->bindValue(':param_proposal_status', $param_proposal_status);

            $command->execute();
            $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
            $connection->close();
            return $valueOut;
        }
        
        public static function pmis_get_audit($param_project_id){
            $connection=   Yii::$app->db;
            $connection->open();
            $sql =" CALL `pmis_get_audit`(:param_project_id)";

            $command = $connection->createCommand($sql); 
            $command->bindValue(':param_project_id', $param_project_id, PDO::PARAM_INT);            

            $result=$command->queryAll();            

            $connection->close();
            return $result;   
        }
   }
?>