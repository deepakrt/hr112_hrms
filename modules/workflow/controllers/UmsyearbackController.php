<?php

namespace app\modules\workflow\controllers;
use yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
class UmsyearbackController extends Controller
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
    
    public function actionGetstudentinfo()
    {
        
        $return = array();
        $return['STATUS_ID']="111";   
      $return['STATUS_MSG']="FAILURE";
      $return['STATUS_RESPONSE']="InValid Request";
        
        if( (isset($_POST['dept_id']) && !empty($_POST['dept_id'])) && (isset($_POST['courseid']) && !empty($_POST['courseid'])) && (isset($_POST['semesterId']) && !empty($_POST['semesterId'])) && (isset($_POST['Batch']) && !empty($_POST['Batch'])) && (isset($_POST['Roll_Number']) && !empty($_POST['Roll_Number']))  )
        {
          
            extract($_POST);
     $USP_ExtractYearBack_Student  = Yii::$app->Utility1->USP_ExtractYearBack_Student($courseid, $dept_id, $Batch ,$semesterId, $Roll_Number);
     //echo "<pre>"; print_r($USP_ExtractYearBack_Student); die;
     if(!empty($USP_ExtractYearBack_Student))
    {      
      $html = $this->renderPartial('studentinfo', array('studentinfo'=>$USP_ExtractYearBack_Student,'secureKey'=>$secureKey,'secureHash'=>$secureHash,'Semester'=>$semesterId));
      $return['STATUS_ID']="000";   
      $return['STATUS_MSG']="SUCCESS";
      $return['STATUS_RESPONSE']=$html;
    }
    else
    {
      $return['STATUS_ID']="111";   
      $return['STATUS_MSG']="SUCCESS";
      $return['STATUS_RESPONSE']="No Student Records Found in DataBase";
    }
        //echo"<pre>123";print_r($_POST); die;
            
        }
        echo json_encode($return); die;
    }
    public function actionProcess()
    {
     
      if(isset($_POST['secureKey']) AND !empty($_POST['secureKey']) AND isset($_POST['secureHash']) AND !empty($_POST['secureHash']))
	 {
            
            $menuid = base64_decode($_POST['secureKey']);
            $secureHash = Yii::$app->Utility->getHashView($menuid);
            if($secureHash!=$_POST['secureHash'])
			{
                return $this->redirect(Yii::$app->homeUrl);   
            } 
			$secureKey =  base64_encode($menuid);
            $secureHash = Yii::$app->Utility->getHashView($menuid);
             
         }
         if( (isset($_POST['_csrf']) && !empty($_POST['_csrf'])) && (isset($_POST['yearback']) && !empty($_POST['yearback'])) )
             {
             
             $yearback = $_POST['yearback'];
             if( (isset($yearback['dept_id']) && !empty($yearback['dept_id'])) && (isset($yearback['course']) && !empty($yearback['course'])) && (isset($yearback['session_yr']) && !empty($yearback['session_yr'])) && (isset($yearback['semesterId']) && !empty($yearback['semesterId'])) && (isset($yearback['RollNo']) && !empty($yearback['RollNo'])) && (isset($yearback['session_yrTo']) && !empty($yearback['session_yrTo'])) && (isset($yearback['semesterIdTo']) && !empty($yearback['semesterIdTo'])) )
             {
              //echo"<pre>123";print_r($yearback); die; 
              extract($yearback);
              $USP_ExtractYearBack_Student  = Yii::$app->Utility1->USP_ExtractYearBack_Student($course, $dept_id, $session_yr ,$semesterId, $RollNo);
              if(!empty($USP_ExtractYearBack_Student))
              {
               $USP_InsertYearBack_Student =   Yii::$app->Utility1->USP_InsertYearBack_Student($dept_id, $course, $semesterId, $session_yr, $session_yrTo, $RollNo,$semesterIdTo); 
               //var_dump($USP_InsertYearBack_Student); die;
               if($USP_InsertYearBack_Student=='1')
         {
          Yii::$app->session->setFlash($key = 'success', $message = '<strong>Succesfully Added Student To Year Back</strong>');   
          $log_JSON = json_encode($yearback);
          Yii::$app->Utility2->logEventDetail('Workflow','workflow/umsyearback/process','Succesfully Added Student To Year Back',$log_JSON);
         }
        else
        {
        Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Unable To Add Student To Year Back, Contact Admin.</strong>');   
        }
        return $this->redirect(Yii::$app->homeUrl."workflow/umsyearback/index?secureKey=$secureKey&secureHash=$secureHash");
              }
             }
         }
         return $this->redirect(Yii::$app->homeUrl);  
    }
	
	}
