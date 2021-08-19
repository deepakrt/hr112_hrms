<?php

namespace app\modules\workflow\controllers;
use yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
class UmsfacultysubjectassignerController extends Controller
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
        
        if(isset($_POST['beCollege']) && !empty($_POST['beCollege']))
        {
        $beCollege = $_POST['beCollege'];
        if((isset($beCollege['collegeName']) && !empty($beCollege['collegeName'])) && (isset($beCollege['collegeAddress']) && !empty($beCollege['collegeAddress'])) && (isset($beCollege['emailId']) && !empty($beCollege['emailId'])))
        {
        if((isset($beCollege['city']) && !empty($beCollege['city'])) && (isset($beCollege['contactNo']) && !empty($beCollege['contactNo'])) && (isset($beCollege['pinCode']) && !empty($beCollege['pinCode'])))
        {        
         $USP_InsertCollege  = Yii::$app->Utility->USP_InsertCollege($beCollege); 
         $secureKey = base64_encode($menu_id);
         $secureHash = Yii::$app->Utility->getHashView($menu_id);
         if($USP_InsertCollege=='1')
         {
         Yii::$app->session->setFlash($key = 'success', $message = '<strong>Succesfully Inserted College Master.</strong>');   
         }
         else if($USP_InsertCollege=='2')
         {
         Yii::$app->session->setFlash($key = 'warning', $message = '<strong>Already Created College Master.</strong>');   
         }
         else
         {
         Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Unable To Insert College Master, Contact Admin.</strong>');   
         }
         return $this->redirect(Yii::$app->homeUrl."workflow/umscollegemaster/index?secureKey=$secureKey&secureHash=$secureHash");
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
        else
        {
        //Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Invaid Parameters</strong>');
        }
        }
        return $this->redirect(Yii::$app->homeUrl);
         
    }
    
    public function actionGetassignunassignsubjects()
    {
      //  echo "<pre>"; print_r($_POST); die;
     if((isset($_POST['_csrf']) && !empty($_POST['_csrf'])) && (isset($_POST['batch']) && !empty($_POST['batch'])) && (isset($_POST['semesterId']) && !empty($_POST['semesterId'])) && (isset($_POST['dept_id']) && !empty($_POST['dept_id'])) && (isset($_POST['course_id']) && !empty($_POST['course_id'])) && (isset($_POST['subject_val']) && !empty($_POST['subject_val'])))   
     {
        
        extract($_POST);
        $res_result = explode("###", $subject_val);
           $subject_val = $res_result['0']; 
           $Subject_Number = $res_result['1']; 
        //$semesterId = base64_decode($semesterId);
        $html="<option value=''>There is no Subject in DB, Contact Admin. </option>";
       $USP_extractAssignedunassignedsubjects = Yii::$app->Utility->USP_extractAssignedunassignedsubjects($dept_id,$course_id,$batch,$semesterId,$subject_val,$Subject_Type);
       
       //print_r($USP_extractAssignedunassignedsubjects); die;
       if(!empty($USP_extractAssignedunassignedsubjects))
       {  
         $html = $this->renderPartial('facultyinfo', array('facultyinfo'=>$USP_extractAssignedunassignedsubjects));
         $return['STATUS_ID']="000";   
         $return['STATUS_MSG']="SUCCESS";
         $return['STATUS_RESPONSE']=$html;            
       }
       else
       {
        $return['STATUS_ID']="111";   
      $return['STATUS_MSG']="FAILURE";
      $return['STATUS_RESPONSE']="No record found in database.";   
       }
       
     }
     else
     {
      $return['STATUS_ID']="111";   
      $return['STATUS_MSG']="FAILURE";
      $return['STATUS_RESPONSE']="Invalid Request";
     }
     echo json_encode($return); die;   
    }
    
    public function actionUnassignsubjectbyhod()
    {
        $return = array();
        $status_ID = "111";
        $status_MESSAGE = "FAILURE";
        $status_RESPONSE = "In Valid Request";
      //  print_r($_POST); die;
    if((isset($_POST['token']) && !empty($_POST['token'])) )
    {
    $jsonwebtoken = $_POST['token'];
    $verifywebtoken = Yii::$app->Utility->validateWebToken($jsonwebtoken);
    if($verifywebtoken)
    {
    if( (isset($_POST['Batch']) && !empty($_POST['Batch'])) && (isset($_POST['Course']) && !empty($_POST['Course'])) && (isset($_POST['Department']) && !empty($_POST['Department'])) && (isset($_POST['Semester']) && !empty($_POST['Semester'])) && (isset($_POST['Subject_Type']) && !empty($_POST['Subject_Type'])) && (isset($_POST['facultygrpid']) && !empty($_POST['facultygrpid']))  && (isset($_POST['facultyid']) && !empty($_POST['facultyid'])) && (isset($_POST['facultysubjectId']) && !empty($_POST['facultysubjectId'])) )
    {
    extract($_POST); 
    $USP_Unassignesubjects = Yii::$app->Utility->USP_Unassignesubjects($Department,$Course,$Batch,$Semester,$facultysubjectId,$Subject_Type,$facultyid,$facultygrpid);
    //var_dump($USP_Unassignesubjects);die;
    if($USP_Unassignesubjects == "1")
    {
    $status_ID = "000";
    $status_MESSAGE = "SUCCESS";
    $status_RESPONSE = "Subject succesfully Unassigned";
    $log_JSON = json_encode($_POST);
    Yii::$app->Utility2->logEventDetail('Workflow','workflow/Umsfacultysubjectassigne/Unassign',$status_RESPONSE,$log_JSON);
    }
    elseif($USP_Unassignesubjects == "2")
    {
    $status_ID = "111";
    $status_MESSAGE = "SUCCESS";
    $status_RESPONSE = "Subject cannot be unassigned (Attendance/Result data exist)";   
    }
    
    }
    }
    }
            $return['STATUS_ID'] = $status_ID;
            $return['STATUS_MSG'] = $status_MESSAGE;
            $return['STATUS_RESPONSE'] = $status_RESPONSE;
            echo json_encode($return);
            die;
    }
    
    public function actionAssignsubjectbyhod()
    {
        $return = array();
        $status_ID = "111";
        $status_MESSAGE = "FAILURE";
        $status_RESPONSE = "In Valid Request";
        if(isset($_POST['formdata']) && !empty($_POST['formdata']))
        {
        $data = parse_str($_POST['formdata'], $info);
        $_POST = $info;                
    if((isset($_POST['webtoken']) && !empty($_POST['webtoken'])) )
    {
    $jsonwebtoken = $_POST['webtoken'];
    $verifywebtoken = Yii::$app->Utility->validateWebToken($jsonwebtoken);
    if($verifywebtoken)
    {
        if((isset($_POST['beSubject']) && !empty($_POST['beSubject'])) )
    {
      $beSubject = $_POST['beSubject'];      
      
    if( (isset($beSubject['department']) && !empty($beSubject['department'])) && (isset($beSubject['course']) && !empty($beSubject['course'])) && (isset($beSubject['session_yr']) && !empty($beSubject['session_yr'])) && (isset($beSubject['semesterId']) && !empty($beSubject['semesterId'])) && (isset($beSubject['subjectId']) && !empty($beSubject['subjectId'])) && (isset($beSubject['Subject_Type']) && !empty($beSubject['Subject_Type'])) && (isset($beSubject['assign_subject_to_faculty']) && !empty($beSubject['assign_subject_to_faculty'])) && (isset($beSubject['assign_grp_tofaculty']) && !empty($beSubject['assign_grp_tofaculty'])) )
    {
            extract($beSubject);
            $res_result = explode("###", $subjectId);
           $facultysubjectId = $res_result['0']; 
           $subject_number = $res_result['1'];     
           
    $USP_Assignesubjects = Yii::$app->Utility->USP_Assignesubjects($department,$course,$session_yr,$semesterId,$facultysubjectId,$Subject_Type,$assign_subject_to_faculty,$assign_grp_tofaculty);
    //var_dump($USP_Unassignesubjects);die;
    if($USP_Assignesubjects == "1")
    {
    $status_ID = "000";
    $status_MESSAGE = "SUCCESS";
    $status_RESPONSE = "Subject succesfully assigned";
    $log_JSON = json_encode($_POST);
    Yii::$app->Utility2->logEventDetail('Workflow','workflow/Umsfacultysubjectassigne/assign',$status_RESPONSE,$log_JSON);
    }
    elseif($USP_Assignesubjects == "2")
    {
    $status_ID = "111";
    $status_MESSAGE = "SUCCESS";
    $status_RESPONSE = "Subject already assigned";   
    }
    
    }
    }
    }
    }
    }
            $return['STATUS_ID'] = $status_ID;
            $return['STATUS_MSG'] = $status_MESSAGE;
            $return['STATUS_RESPONSE'] = $status_RESPONSE;
            echo json_encode($return);
            die;
    }
    
    }
