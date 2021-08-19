<?php

namespace app\modules\workflow\controllers;
use yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
class UmsstudentmasterController extends Controller
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
        if(isset($_POST['student']) && !empty($_POST['student']))
        {
        $student = $_POST['student'];
        if((isset($student['firstName']) && !empty($student['firstName'])) && (isset($student['gender']) && !empty($student['gender'])) && (isset($student['student_dob']) && !empty($student['student_dob'])) && (isset($student['contactNumber']) && !empty($student['contactNumber'])))
        {
        if((isset($student['bloodGroup']) && !empty($student['bloodGroup'])) && (isset($student['emailId']) && !empty($student['emailId'])) && (isset($student['address']) && !empty($student['address'])))
        {
        if((isset($student['StudentType']) && !empty($student['StudentType'])) && (isset($student['beDepartment']) && !empty($student['beDepartment'])) && (isset($student['beCourse']) && !empty($student['beCourse'])) && (isset($student['rollnumber']) && !empty($student['rollnumber'])) && (isset($student['registrationNumber']) && !empty($student['registrationNumber'])) && (isset($student['registrationNumber']) && !empty($student['registrationNumber'])) && (isset($student['startSession']) && !empty($student['startSession']))&& (isset($student['endSession']) && !empty($student['endSession'])))
        {
        $USP_InsertStudent  = Yii::$app->Utility->USP_InsertStudent($student);
         if($USP_InsertStudent=='1')
         {
          Yii::$app->session->setFlash($key = 'success', $message = '<strong>Succesfully Inserted Student Master.</strong>');   
          $log_JSON = json_encode($student);
          Yii::$app->Utility2->logEventDetail('Workflow','workflow/umsstudentmaster/insert','Successfully Created Student',$log_JSON);
         }
         else if($USP_InsertStudent=='2')
         {
         Yii::$app->session->setFlash($key = 'warning', $message = '<strong>Already Created Student Master.</strong>');   
         }
         else
         {
          Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Unable To Insert Student Master, Contact Admin.</strong>');   
         }
        }
        else
         {
         Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Invaid Parameters</strong>');
         }
        }
        else
        {
        Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Invaid Parameters</strong>');
        }
        }
        else
        {
        Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Invaid Parameters</strong>');
        }
        }        
        else
        {
       // Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Invaid Parameters</strong>');
        }
        }
        else
        {
        return $this->redirect(Yii::$app->homeUrl);
        }
        $secureKey = base64_encode($menu_id);
        $secureHash = Yii::$app->Utility->getHashView($menu_id);
        return $this->redirect(Yii::$app->homeUrl."workflow/umsstudentmaster/index?secureKey=$secureKey&secureHash=$secureHash"); 
    }
    
    public function actionGetdepartmentbyclgid()
    {
    if((isset($_POST['_csrf']) && !empty($_POST['_csrf'])) && (isset($_POST['clgId']) && !empty($_POST['clgId'])))   
     {
        $clgId = $_POST['clgId'];
        $html="<option value=''>There is no Department in DB, Contact Admin. </option>";
       $USP_ExtractCollegeDept = Yii::$app->Utility->USP_ExtractCollegeDept($clgId);
       if(!empty($USP_ExtractCollegeDept))
       {
        $html="<option value=''>Select Department</option>";
        foreach($USP_ExtractCollegeDept as $USP_ExtractCollegeDeptK=>$USP_ExtractCollegeDeptV)   
        {
         $department_id = $USP_ExtractCollegeDeptV['department_id'];
         $Department_Name = $USP_ExtractCollegeDeptV['Department_Name'];
         $html.="<option value='$department_id'>$Department_Name</option>";   
        }
       }
       $return['STATUS_ID']="000";   
       $return['STATUS_MSG']="SUCCESS";
       $return['STATUS_RESPONSE']=$html;
     }
     else
     {
      $return['STATUS_ID']="111";   
      $return['STATUS_MSG']="FAILURE";
      $return['STATUS_RESPONSE']="Invalid Request";
     }
     echo json_encode($return); die;
    }
    }
