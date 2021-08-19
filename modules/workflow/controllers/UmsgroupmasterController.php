<?php

namespace app\modules\workflow\controllers;
use yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
class UmsgroupmasterController extends Controller
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
    public function actionCreategroup()
    {
        
        $response = array();
        $data = parse_str($_POST['formdata'], $info);        
        $_POST =  $info;
        //echo "<pre>"; print_r($_POST); die;
        $return['STATUS_ID']="222";   
        $return['STATUS_MSG']="FAILURE";
        $return['STATUS_RESPONSE']='';
        if((isset($_POST['secureKey']) && !empty($_POST['secureKey'])) && (isset($_POST['secureHash']) && !empty($_POST['secureHash'])))
        {
        $menu_id = $secureKey = base64_decode($_POST['secureKey']);
        $secureHash = Yii::$app->Utility->getHashInsert($secureKey);
        if($secureHash==$_POST['secureHash'])
        {
        if(isset($_POST['beGroupMaster']) && !empty($_POST['beGroupMaster']) && isset($_POST['StudentToGroups']) && !empty($_POST['StudentToGroups']))
        {
                $beGroupMaster = $_POST['beGroupMaster'];  
                
                if(isset($beGroupMaster['session_yr']) AND !empty($beGroupMaster['session_yr']) AND isset($beGroupMaster['deptInfo']) AND !empty($beGroupMaster['deptInfo']) AND isset($beGroupMaster['beCourse']) AND !empty($beGroupMaster['beCourse']) AND isset($beGroupMaster['semesterId']) AND !empty($beGroupMaster['semesterId']))
                    {
                $studentlist='';
                $StudentToGroups = $_POST['StudentToGroups'];
                foreach($StudentToGroups as $StudentToGroupsK=>$StudentToGroupsV)
                {
                    $studentlist.= $StudentToGroupsV.",";
                }
                $studentlist = rtrim($studentlist,',');
                $Subject_Number = $Subject_id = '0';                
                extract($beGroupMaster);
                $is_process = true;
                
                if($Group_For == "ET" || $Group_For == "EP")
                {
                if(isset($Elective_Subject) && !empty($Elective_Subject))
                {
                $res_result = explode("###", $Elective_Subject);
                if(count($res_result) == "2" && (isset($res_result['0']) && !empty($res_result['0'])) && (isset($res_result['1']) && !empty($res_result['1'])) )
                {
                $Subject_Number = $res_result['1']; 
                $Subject_id = $res_result['0']; 
                }
                else
                {
                $is_process = false;   
                }
                }
                else
                {
                $is_process = false;    
                }
                }
                if($is_process)
                {
                $USP_InsertGroup  = Yii::$app->Utility->USP_InsertGroup($semesterId,$session_yr,$deptInfo,$beCourse, $Group_For,$studentlist, $Subject_Number,$Subject_id);
                
                $secureKey = base64_encode($menu_id);
                $secureHash = Yii::$app->Utility->getHashView($menu_id);
                    if($USP_InsertGroup=='1'){
                        //Save Logs
                        $json_data = json_encode($_POST);
                        Yii::$app->Utility2->logEventDetail('Work-Flow', 'workflow/umsgroupmaster/creategroup', 'Successfully Created Group.', $json_data);
        
                        $return['STATUS_ID']="000";   
                        $return['STATUS_MSG']="SUCCESS";                        
                        Yii::$app->session->setFlash($key = 'success', $message = '<strong>Successfully Created Group.</strong>');   
                    }else if($USP_InsertGroup=='2'){
                        $return['STATUS_ID']="111";   
                        $return['STATUS_MSG']="FAILURE";
                        Yii::$app->session->setFlash($key = 'success', $message = '<strong>Group Already Created.</strong>');   
                        
                    }else{
                        $return['STATUS_ID']="111";   
                        $return['STATUS_MSG']="FAILURE";
                        Yii::$app->session->setFlash($key = 'success', $message = '<strong>Unable To Create Group, Contact Admin.</strong>');                        
                    }
                }
                else{
                        $return['STATUS_ID']="111";   
                        $return['STATUS_MSG']="FAILURE";
                        Yii::$app->session->setFlash($key = 'success', $message = '<strong>Unable To Create Group, Contact Admin.</strong>');                        
                    }
                    $return['STATUS_RESPONSE'] = "workflow/umsgroupmaster/index?secureKey=$secureKey&secureHash=$secureHash";
                    
                    echo  json_encode($return); die;
                }
                
            }
           }
        }
        else {}
        echo  json_encode($return); die;
         
    }
    public function actionPopulatestudent()
    {
     //echo "<pre>"; print_r($_POST); die;
     if((isset($_POST['Batch']) && !empty($_POST['Batch'])) && (isset($_POST['Department']) && !empty($_POST['Department'])) && (isset($_POST['Course']) && !empty($_POST['Course'])) && (isset($_POST['Semester']) && !empty($_POST['Semester'])) && (isset($_POST['Group_For']) && !empty($_POST['Group_For'])))
     {
     extract($_POST);
     $Subject_Number = $Subject_id = '0';
     $is_process = true;
     if($Group_For == "ET" || $Group_For == "EP")
     {
     if(isset($Elective_Subject) && !empty($Elective_Subject))
     {
        $res_result = explode("###", $Elective_Subject);
        if(count($res_result) == "2" && (isset($res_result['0']) && !empty($res_result['0'])) && (isset($res_result['1']) && !empty($res_result['1'])) )
        {
        $Subject_Number = $res_result['1']; 
        $Subject_id = $res_result['0']; 
        }
        else
        {
          $is_process = false;   
        }
     }
     else
     {
     $is_process = false;    
     }
     }
     if($is_process)
     {
     $USP_ExtractStudentforGroup  = Yii::$app->Utility->USP_ExtractStudentforGroup($Semester,$Batch,$Course,$Department,$Group_For,$Subject_Number, $Subject_id);     
     if(!empty($USP_ExtractStudentforGroup))
    {
      $html = $this->renderPartial('studentdata', array('studentsinfo'=>$USP_ExtractStudentforGroup));
      $return['STATUS_ID']="000";   
      $return['STATUS_MSG']="SUCCESS";
      $return['STATUS_RESPONSE']=$html;
    }
    else
    {
      $return['STATUS_ID']="111";   
      $return['STATUS_MSG']="SUCCESS";
      $return['STATUS_RESPONSE']="Either Student Does Not Exist OR Groups To All The Existing Students Had Been Allotted.";
    }
     }
     else
     {
      $return['STATUS_ID']="111";   
      $return['STATUS_MSG']="FAILURE";
      $return['STATUS_RESPONSE']="Invalid Request";
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
    
    public function actionExtractelectivesubject()
    {
     //echo "<pre>"; print_r($_POST); die;
     if( (isset($_POST['Department']) && !empty($_POST['Department'])) && (isset($_POST['Course']) && !empty($_POST['Course'])) && (isset($_POST['Semester']) && !empty($_POST['Semester'])) && (isset($_POST['Group_For']) && !empty($_POST['Group_For'])))
     {
     extract($_POST);
     $USP_Extract_ElectiveSubject  = Yii::$app->Utility->USP_Extract_ElectiveSubject($Department, $Course,$Semester,$Group_For,$Batch);     
     if(!empty($USP_Extract_ElectiveSubject))
    {
      $html = $this->renderPartial('electivesubject', array('electivesubject'=>$USP_Extract_ElectiveSubject,'Validate'=>$Validate));
      $return['STATUS_ID']="000";   
      $return['STATUS_MSG']="SUCCESS";
      $return['STATUS_RESPONSE']=$html;
    }
    else
    {
      $return['STATUS_ID']="111";   
      $return['STATUS_MSG']="SUCCESS";
      $return['STATUS_RESPONSE']="No Elective Subject For The Selected Department/Course/Semester";
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
    
    
    }
