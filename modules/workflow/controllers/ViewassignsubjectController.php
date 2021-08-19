<?php
namespace app\modules\workflow\controllers;
use yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
class ViewassignsubjectController extends Controller
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
    
    
    public function actionGetassignunassignlist()
    {
       // echo "<pre>"; print_r($_POST); die;
        $return['STATUS_ID']="111";   
      $return['STATUS_MSG']="FAILURE";
      $return['STATUS_RESPONSE']="Invalid Request";
     if((isset($_POST['_csrf']) && !empty($_POST['_csrf'])) && (isset($_POST['semesterId']) && !empty($_POST['semesterId'])) && (isset($_POST['dept_id']) && !empty($_POST['dept_id'])) && (isset($_POST['course_id']) && !empty($_POST['course_id'])) && (isset($_POST['subjectId']) && !empty($_POST['subjectId'])) && (isset($_POST['SubjectType']) && !empty($_POST['SubjectType'])))   
     {
       //echo "<pre>inside"; print_r($_POST); die;
        extract($_POST);
        //if($subject_val=="ALL") $subject_val=0;
       // $semesterId = base64_decode($semesterId);
           $res_result = explode("###", $subjectId);
           $subject_val = $res_result['0']; 
           $Subject_Number = $res_result['1']; 
        $html="<option value=''>There is no Subject in DB, Contact Admin. </option>";
       $USP_displayAssignedSubjectlist = Yii::$app->Utility->USP_displayAssignedSubjectlist($dept_id,$course_id,$batch,$semesterId,$SubjectType,$subject_val);
       //echo "<pre>"; print_r($USP_displayAssignedSubjectlist); die;       
       if(!empty($USP_displayAssignedSubjectlist))
       {
       $html = $this->renderPartial('facultylist', array('facultylist'=>$USP_displayAssignedSubjectlist));
      $return['STATUS_ID']="000";   
      $return['STATUS_MSG']="SUCCESS";
      $return['STATUS_RESPONSE']=$html;
       }
       else
       {
        $return['STATUS_ID']="111";   
        $return['STATUS_MSG']="SUCCESS";
        $return['STATUS_RESPONSE']="No Record Found.";
       }
     }
     echo json_encode($return); die;
    }
    
    public function actionViewstudentlist()
    {
        
    $return = array();
    $return['STATUS_ID']="111";   
    $return['STATUS_MSG']="FAILURE";
    $return['STATUS_RESPONSE']="Invalid Request/Fraudulent data detected";
    if((isset($_POST['formdata']) && !empty($_POST['formdata'])))   
    {
        
        $response = array();
        $data = parse_str($_POST['formdata'], $info); 
        $_POST = $info;
        //echo "<pre>"; print_r($_POST); die;
        if(isset($_POST['viewassignsubject']) && !empty($_POST['viewassignsubject']))
        {
        $beSubject = $_POST['viewassignsubject'];
        if((isset($beSubject['assign_grp_tofaculty']) && !empty($beSubject['assign_grp_tofaculty'])) && (isset($beSubject['session_yr']) && !empty($beSubject['session_yr'])) && (isset($beSubject['department']) && !empty($beSubject['department'])) && (isset($beSubject['course']) && !empty($beSubject['course'])) && (isset($beSubject['semesterId']) && !empty($beSubject['semesterId'])) && (isset($beSubject['subjectId']) && !empty($beSubject['subjectId'])))
        {
        extract($beSubject);
        $res_result = explode("###", $subjectId);
        $subjectId = $res_result['0'];
        $Subject_Number = $res_result['1']; 
        $grp_lists =  $beSubject['assign_grp_tofaculty'];
        //echo "<pre>$grp_lists"; print_r($beSubject); die;
        
        $USP_ExtractstudentforAssignSubjectGroup = Yii::$app->Utility->USP_ExtractstudentforAssignSubjectGroup($session_yr, $department,$course,$semesterId,$subjectId,$Subject_Type,$grp_lists);
        //echo "<pre>$grp_lists"; print_r($USP_ExtractstudentforAssignSubjectGroup); die;
        if(!empty($USP_ExtractstudentforAssignSubjectGroup))
        {
        $html =  $this->renderPartial('studentlists',array('studentsinfo'=>$USP_ExtractstudentforAssignSubjectGroup));
        $return['STATUS_ID']="000";   
        $return['STATUS_MSG']="SUCCESS";
        $return['STATUS_RESPONSE']=$html;
        }
        else
        {
        $return['STATUS_ID']="111";   
        $return['STATUS_MSG']="SUCCESS";
        $return['STATUS_RESPONSE']="<strong>No Records Found.</strong>";
        }
        }}}
    
    echo json_encode($return); die; 
    
    }
    
    
    
    }
