<?php

namespace app\modules\admin\controllers;
use app\models\Employee; 
use yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\EmployeeLeavesRequests;

class ManageempleavesController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            
             'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => TRUE,
//                        'roles' => ['@'],
//                        'actions' => ['@'],
                        'denyCallback' => function ($rule, $action) {
                                try {
                                    return $this->redirect($url = \Yii::$app->homeUrl);
                                } catch (Exception $ex) {
                                    throw new Exception(500, $ex);
                                }
                            },
                        'matchCallback' => function($rule, $action){
                            try {
                                if (!\Yii::$app->user->isGuest) {
                                    if(\Yii::$app->user->identity->role != 1){
                                        return false;
                                    }else{
                                        return true;
                                    }
                                }else{
                                    return false;
                                }
                            }catch (Exception $ex) {
                                throw new Exception(500, $ex);
                            }
                        },
                    ],
                ],
            ]
        ];
    }
    
     /*
    * View Employee
    */
    public function actionIndex(){
      
            $employee_leaves = Yii::$app->utility->get_employee_leave_requests();             
           //echo "<pre>";print_r($employee_leaves); die;
            
            $this->layout = '@app/views/layouts/admin_layout.php';
            return $this->render('index', ['employee_leaves'=>$employee_leaves]);
        
    }
    
    public function actionApply(){
      
            $employee_leaves = Yii::$app->utility->get_employee_leave_requests();             
           //echo "<pre>";print_r($employee_leaves); die;
            $model = new EmployeeLeavesRequests();
            $this->layout = '@app/views/layouts/admin_layout.php';
            return $this->render('apply_leave', ['model'=>$model]);
        
    }
    
    public function actionUpdateleavestatus(){
	
	$connection=   Yii::$app->db;
        $connection->open();
       
        foreach($_POST['ids'] as $id){
         $leave_id=$id;
        if(!isset($_POST['remarks']) || empty($_POST['remarks'])){
        $remarks='Approve All';
        }else{
        $remarks=$_POST['remarks'];
        }
        if(!isset($_POST['status']) || empty($_POST['status'])){
        $status='Approved';
        }else{
        $status=$_POST['status'];
        }
        $approved_by=Yii::$app->user->identity->e_id;
        $sql =" CALL `approve_leave`(:leave_id,:status,:remarks,:approved_by, @result)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':leave_id', $leave_id);
        $command->bindValue(':status', $status); 
        $command->bindValue(':remarks', $remarks); 
        $command->bindValue(':approved_by', $approved_by); 
        $command->execute();
        $result = $connection->createCommand("select @Result as ress;")->queryScalar(); 
        }
        $connection->close();
        return $result;
     }
     
}
