<?php

namespace app\modules\workflow\controllers;
use yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
class UmsassigncourseController extends Controller
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
        
        if(isset($_POST['Assign']) && !empty($_POST['Assign']))
        {
        $Assign = $_POST['Assign'];
        if((isset($Assign['Department']) && !empty($Assign['Department'])) && (isset($Assign['Course']) && !empty($Assign['Course'])))
        {                
         $USP_InsertDeptCourse  = Yii::$app->Utility->USP_InsertDeptCourse($Assign);         
         $secureKey = base64_encode($menu_id);
         $secureHash = Yii::$app->Utility->getHashView($menu_id);
         if($USP_InsertDeptCourse=='1')
         {
         Yii::$app->session->setFlash($key = 'success', $message = '<strong>Succesfully Assign Course To Department </strong>');   
         }
         else if($USP_InsertDeptCourse=='2')
         {
         Yii::$app->session->setFlash($key = 'warning', $message = '<strong>Already Assigned Course To Department </strong>');   
         }
         else
         {
         Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Unable To Assign Course To Department, Contact Admin.</strong>');   
         }
         return $this->redirect(Yii::$app->homeUrl."workflow/umsassigncourse/index?secureKey=$secureKey&secureHash=$secureHash");
           
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
    public function actionGetcoursebydeptid()
    {
     if((isset($_POST['_csrf']) && !empty($_POST['_csrf'])) && (isset($_POST['dept_id']) && !empty($_POST['dept_id'])))   
     {
        $deptid = $_POST['dept_id'];
        $html="<option value=''>There is no course in DB, Contact Admin. </option>";
       $USP_ExtractCourse = Yii::$app->Utility->USP_ExtractCourse($deptid);
       if(!empty($USP_ExtractCourse))
       {
        $html="<option value=''>Select Course</option>";
        foreach($USP_ExtractCourse as $USP_ExtractCourseK=>$USP_ExtractCourseV)   
        {
         $Course_Id = $USP_ExtractCourseV['Course_Id'];
         $Course_Name = $USP_ExtractCourseV['Course_Name'];
         $html.="<option value='$Course_Id'>$Course_Name</option>";   
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
    
    public function actionGetbatchbycourseid()
    {
     if((isset($_POST['_csrf']) && !empty($_POST['_csrf'])) && (isset($_POST['course_id']) && !empty($_POST['course_id'])))   
     {
        $courseid = $_POST['course_id'];
        $html="<option value=''>There is no batch in DB, Contact Admin. </option>";
       $USP_ExtractSession = Yii::$app->Utility->USP_ExtractSession($courseid);
       if(!empty($USP_ExtractSession))
       {
        $html="<option value=''>Select Batch</option>";
        foreach($USP_ExtractSession as $USP_ExtractSessionK=>$USP_ExtractSessionV)   
        {
         $Batch_Id = $USP_ExtractSessionV['Batch'];         
         $html.="<option value='$Batch_Id'>$Batch_Id</option>";   
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
    
    public function actionGetsemesterbybatch()
    {
     if((isset($_POST['_csrf']) && !empty($_POST['_csrf'])) && (isset($_POST['batch_id']) && !empty($_POST['batch_id'])))   
     {
        $batchid = $_POST['batch_id'];
        $html="<option value=''>There is no semester in DB, Contact Admin. </option>";
       $USP_ExtractSemester = Yii::$app->Utility->USP_ExtractSemester($batchid);       
       if(!empty($USP_ExtractSemester))
       {
        $html="<option value=''>Select Semester</option>";
        for($i=1; $i<=$USP_ExtractSemester; $i++)   
        {                
         $html.="<option value='$i'>$i</option>";   
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
