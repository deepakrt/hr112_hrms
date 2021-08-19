<?php

namespace app\modules\workflow\controllers;
use yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
class UmseditgroupmasterController extends Controller
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
   
    public function actionPopulategroups()
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
     $USP_ExtractGroupforEditUpdate  = Yii::$app->Utility->USP_ExtractGroupforEditUpdate($Batch,$Department,$Course,$Semester,$Subject_id, $Group_For,$Subject_Number);     
     //echo "<pre>";print_r($USP_ExtractGroupforEditUpdate); die;
     if(!empty($USP_ExtractGroupforEditUpdate))
    {
      $html = $this->renderPartial('grouplist', array('grouplistinfo'=>$USP_ExtractGroupforEditUpdate));
      $return['STATUS_ID']="000";   
      $return['STATUS_MSG']="SUCCESS";
      $return['STATUS_RESPONSE']=$html;
    }
    else
    {
      $return['STATUS_ID']="111";   
      $return['STATUS_MSG']="SUCCESS";
      $return['STATUS_RESPONSE']="Group Does Not Exist/Created ";
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
    
    public function actionGetstudentinfo()
    {
        $response = array();
        $data = parse_str($_POST['formdata'], $info);        
        $_POST =  $info;
        
        $return['STATUS_ID']="111";   
        $return['STATUS_MSG']="FAILURE";
        $return['STATUS_RESPONSE']='Invalid Request';
        if((isset($_POST['secureKey']) && !empty($_POST['secureKey'])) && (isset($_POST['secureHash']) && !empty($_POST['secureHash'])))
        {
         
        $menu_id = $secureKey = base64_decode($_POST['secureKey']);
        $secureHash = Yii::$app->Utility->getHashInsert($secureKey);
        if($secureHash==$_POST['secureHash'])
        {
        
        if(isset($_POST['beGroupMaster']) && !empty($_POST['beGroupMaster']))
        {
        $beGroupMaster = $_POST['beGroupMaster']; 
        if(isset($beGroupMaster['RollNo']) AND !empty($beGroupMaster['RollNo']) AND isset($beGroupMaster['session_yr']) AND !empty($beGroupMaster['session_yr']) AND isset($beGroupMaster['deptInfo']) AND !empty($beGroupMaster['deptInfo']) AND isset($beGroupMaster['beCourse']) AND !empty($beGroupMaster['beCourse']) AND isset($beGroupMaster['semesterId']) AND !empty($beGroupMaster['semesterId']))
        {
        extract($beGroupMaster);
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
         $PARAMAction = '';
         if($action == "S")
         {
          $btn_Label = "Change Group";  
          $message_dispaly = "Either Student Against The Roll Number Does Not Exist OR Group Not Assigned/Created. ";
         }
         else if($action == "D")
         {
          $btn_Label = "Assign Group"; 
          $PARAMAction = "AG";
          $message_dispaly = "Either Student Against The Roll Number Does Not Exist OR Group Already Assigned. ";
         }
        else 
        {
            $btn_Label = "Un-Group";  
            $PARAMAction = "UG";
            $message_dispaly = "Either Student Against The Roll Number Does Not Exist OR Group Already Assigned. ";
        }
        $USP_ExtractStudentforGroupEditUpdate  = Yii::$app->Utility->USP_ExtractStudentforGroupEditUpdate($deptInfo, $beCourse, $semesterId,$session_yr, $Group_For, $Subject_Number,$Subject_id,$RollNo,$PARAMAction);      
        //echo "<pre>";print_r($USP_ExtractStudentforGroupEditUpdate); die;
     if(!empty($USP_ExtractStudentforGroupEditUpdate))
    {
      $btn_label = "<input class='btn btn-primary Final_Edit_Action' type='button' value ='$btn_Label' id='Final_Edit_Action'>";
      $html = $this->renderPartial('studentdata', array('studentsinfo'=>$USP_ExtractStudentforGroupEditUpdate,'btn_Label'=>$btn_Label));
      $return['STATUS_ID']="000";   
      $return['STATUS_MSG']="SUCCESS";
      $return['STATUS_RESPONSE']=$html;
      $return['BTN_LABEL']=$btn_label;
    }
    else
    {
      $return['STATUS_ID']="111";   
      $return['STATUS_MSG']="SUCCESS";
      $return['STATUS_RESPONSE']="$message_dispaly";
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
     
     
     }
     else
     {
      $return['STATUS_ID']="111";   
      $return['STATUS_MSG']="FAILURE";
      $return['STATUS_RESPONSE']="Invalid Request";
     }
        echo  json_encode($return); die;
    }
    
    public function actionUpdategroupinfo()
    {
        
        $response = array();
        $data = parse_str($_POST['formdata'], $info);        
        $_POST =  $info;
       // echo "<pre>";print_r($_POST); die;
        $return['STATUS_ID']="111";   
        $return['STATUS_MSG']="FAILURE";
        $return['STATUS_RESPONSE']=''; 
        
        if((isset($_POST['secureKey']) && !empty($_POST['secureKey'])) && (isset($_POST['secureHash']) && !empty($_POST['secureHash'])))
        {
         
        $menu_id = $secureKey = base64_decode($_POST['secureKey']);
        $secureHash = Yii::$app->Utility->getHashInsert($secureKey);
        if($secureHash==$_POST['secureHash'])
        {
        
        if(isset($_POST['beGroupMaster']) && !empty($_POST['beGroupMaster']))
        {
        $beGroupMaster = $_POST['beGroupMaster']; 
        if(isset($beGroupMaster['RollNo']) AND !empty($beGroupMaster['RollNo']) AND isset($beGroupMaster['session_yr']) AND !empty($beGroupMaster['session_yr']) AND isset($beGroupMaster['deptInfo']) AND !empty($beGroupMaster['deptInfo']) AND isset($beGroupMaster['beCourse']) AND !empty($beGroupMaster['beCourse']) AND isset($beGroupMaster['semesterId']) AND !empty($beGroupMaster['semesterId']))
        {
        extract($beGroupMaster);
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
         $Old_GroupId= NULL;
         $PARAMAction = '';
         if($action == "S")
         {
          $btn_Label = "Changed the Group";  
          $Old_GroupId = $Grp_Id;
         }
         else if($action == "D")
         {
          $btn_Label = "Assigned The Group";  
          $PARAMAction = "AG";
         }
         
        else if($action == "U")
        {
            $btn_Label = "Un-Group";  
            $PARAMAction = "UG";
            $Groups_To = NULL;
            $Old_GroupId = $Grp_Id;
        }
        else 
        {            
        }
        
        $secureKey = base64_encode($menu_id);
        $secureHash = Yii::$app->Utility->getHashView($menu_id);        
        $USP_EditUpdateStudentGroup  = Yii::$app->Utility->USP_EditUpdateStudentGroup($deptInfo, $beCourse, $semesterId,$session_yr, $Group_For, $Subject_Number,$Subject_id,$RollNo,$PARAMAction,$Groups_To,$Old_GroupId);      
         
     if($USP_EditUpdateStudentGroup =='1'){
            //Save Logs
            $json_data = json_encode($beGroupMaster);
            Yii::$app->Utility2->logEventDetail('Work-Flow', 'workflow/umsgroupmaster/EditGroup', "Successfully $btn_Label.", $json_data);
                $return['STATUS_ID']="000";   
                $return['STATUS_MSG']="SUCCESS";                        
                Yii::$app->session->setFlash($key = 'success', $message = "<strong>Successfully $btn_Label </strong>");   
        }
        else if($USP_EditUpdateStudentGroup =='2')
        {
            $return['STATUS_ID']="111";   
            $return['STATUS_MSG']="FAILURE";
            Yii::$app->session->setFlash($key = 'danger', $message = "<strong>Unable To $btn_Label , Result/Attendance Exist,Contact Admin.</strong>");                        
        }
        else{
            $return['STATUS_ID']="111";   
            $return['STATUS_MSG']="FAILURE";
            Yii::$app->session->setFlash($key = 'danger', $message = "<strong>Unable To Update Group, Contact Admin.</strong>");                        
        }
            $return['STATUS_RESPONSE'] = "workflow/umseditgroupmaster/index?secureKey=$secureKey&secureHash=$secureHash";

            echo  json_encode($return); die;
    
        }
                }
                }  
                }
                }
                    echo  json_encode($return); die;
    }
    
    }
