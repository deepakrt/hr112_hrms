<?php

namespace app\modules\workflow\controllers;
use yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
class AssigncoursetodepartmentController extends Controller
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
                                $chk = Yii::$app->Utility->chkUserAccess();
                                       return $chk;
//                                if(isset(Yii::$app->user->identity) AND !empty(Yii::$app->user->identity)){
//                                    return true;
//                                }else{
//                                    return false;
//                                }
                            }catch (Exception $ex) {
                                throw new Exception(500, $ex);
                            }
                        },
                    ],
                ],
            ]
        ];
    }
    
    public function actionIndex()
    {
    if((isset($_GET['secureKey']) && !empty($_GET['secureKey'])) && (isset($_GET['secureHash']) && !empty($_GET['secureHash'])))
     {
     $secureKey = base64_decode($_GET['secureKey']);
     $secureHash = Yii::$app->Utility->getHashView($secureKey); 
     if($secureHash!=$_GET['secureHash'])
     {
      return $this->redirect(Yii::$app->homeUrl);   
     }     
     return $this->render('index',array('menuid'=>$secureKey));
     }
     else
     {
        return $this->redirect(Yii::$app->homeUrl);
     }
    }
    
	public function actionInsert()
    {
        //echo "<pre>";print_r($_POST); die;
        if((isset($_POST['secureKey']) && !empty($_POST['secureKey'])) && (isset($_POST['secureHash']) && !empty($_POST['secureHash'])))
        {
        $menu_id = $secureKey = base64_decode($_POST['secureKey']);
        $secureHash = Yii::$app->Utility->getHashInsert($secureKey);
        if($secureHash!=$_POST['secureHash'])
        {
        return $this->redirect(Yii::$app->homeUrl);   
        }
        
        if(isset($_POST['course']) && !empty($_POST['course']))
        {
        $course = $_POST['course'];
        if((isset($course['beDepartment']) && !empty($course['beDepartment'])) && (isset($course['beCourse']) && !empty($course['beCourse'])))
        {       
         $USP_InsertDeptCourse  = Yii::$app->Utility1->USP_InsertDeptCourse($course);
		 //echo "<pre>";print_r($USP_InsertDeptCourse); die;
         $secureKey = base64_encode($menu_id);
         $secureHash = Yii::$app->Utility->getHashView($menu_id);
         if($USP_InsertDeptCourse=='1')
         {
         Yii::$app->session->setFlash($key = 'success', $message = '<strong>Succesfully Assign Course To Department </strong>');   
         $log_JSON = json_encode($course);
         Yii::$app->Utility2->logEventDetail('Workflow','workflow/assigncoursetodepartment/insert','Successfully Assign Course To Department',$log_JSON);
         }
         else if($USP_InsertDeptCourse=='2')
         {
         Yii::$app->session->setFlash($key = 'warning', $message = '<strong>Already Assigned Course To Department </strong>');   
         }
         else
         {
         Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Unable To Assign Course To Department, Contact Admin.</strong>');   
         }
         return $this->redirect(Yii::$app->homeUrl."workflow/assigncoursetodepartment/index?secureKey=$secureKey&secureHash=$secureHash");
        }
        else
        {
        //Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Invaid Parameters</strong>');           
        }
        
        }
        else
        {
        //Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Invaid Parameters</strong>');
        }
        }
        return $this->redirect(Yii::$app->homeUrl);
         
    }
	
    
    }
