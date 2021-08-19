<?php

namespace app\modules\workflow\controllers;
use yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
class ViewregisteredstudentController extends Controller
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
	
	public function actionViewinfo()
    {
//	echo "<pre>";print_r($_POST);die();
    if((isset($_GET['secureKey']) && !empty($_GET['secureKey'])) && (isset($_GET['secureHash'])))
     {
		 
     $secureKey = base64_decode($_GET['secureKey']);
     $secureHash = Yii::$app->Utility->getHashView($secureKey);  
	 
     if($secureHash!=$_GET['secureHash'])
     {
      return $this->redirect(Yii::$app->homeUrl);   
     }
     //if( (isset($_POST['Roll_Number']) && !empty($_POST['Roll_Number']))  && (isset($_POST['email_id']) && !empty($_POST['email_id']))  && (isset($_POST['email_id']) && !empty($_POST['email_id'])) && (isset($_POST['father_name']) && !empty($_POST['father_name'])) && (isset($_POST['first_name']) && !empty($_POST['first_name'])) )
     //{
     return $this->render('studentinfo',array('menuid'=>$secureKey,'data'=>$_POST));
     //}
     //else
     //{
       // return $this->redirect(Yii::$app->homeUrl);
     //}
     }
     else
     {
        return $this->redirect(Yii::$app->homeUrl);
     }
    }
	
	
    public function actionGetstudentreglist()
    {
        //echo "<pre>";print_r($_POST); die;
		
        if((isset($_POST['_csrf']) && !empty($_POST['_csrf'])) && (isset($_POST['Batch']) && !empty($_POST['Batch'])) && (isset($_POST['Department']) && !empty($_POST['Department'])) && (isset($_POST['Course']) && !empty($_POST['Course'])) && (isset($_POST['Semester']) && !empty($_POST['Semester']))) 
     {
     extract($_POST);
     $USP_ExtractRegisteredStudent  = Yii::$app->Utility1->USP_ExtractRegisteredStudent($Course, $Department, $Batch , $Semester);
     //echo "<pre>"; print_r($USP_ExtractRegisteredStudent); die;
     if(!empty($USP_ExtractRegisteredStudent))
    {      
      $html = $this->renderPartial('viewregisteredstudent', array('viewregisteredstudentinfo'=>$USP_ExtractRegisteredStudent,'secureKey'=>$secureKey,'secureHash'=>$secureHash,'Semester'=>$Semester));
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
    }
     else
     {
      $return['STATUS_ID']="111";   
      $return['STATUS_MSG']="FAILURE";
      $return['STATUS_RESPONSE']="Invalid Request";
     }
     echo json_encode($return); die;
    }
	
	 public function actionUpdate()
    {
	  
     if(isset($_POST['secureKey']) AND !empty($_POST['secureKey']) AND isset($_POST['secureHash']) AND !empty($_POST['secureHash']))
	 {
            
            $menuid = base64_decode($_POST['secureKey']);
            $secureHash = Yii::$app->Utility->getHashInsert($menuid);
            if($secureHash!=$_POST['secureHash'])
			{
                return $this->redirect(Yii::$app->homeUrl);   
            } 
			$secureKey =  base64_encode($menuid);
            $secureHash = Yii::$app->Utility->getHashView($menuid);
	 
     if( (isset($_POST['_csrf']) && !empty($_POST['_csrf'])) && (isset($_POST['first_name']) && !empty($_POST['first_name'])) && (isset($_POST['Course_Id']) && !empty($_POST['Course_Id'])) && (isset($_POST['Department_Id']) && !empty($_POST['Department_Id'])) && (isset($_POST['Roll_Number']) && !empty($_POST['Roll_Number'])) && (isset($_POST['Registration_Number']) && !empty($_POST['Registration_Number'])) )
     {
	        $Registration_Number = $_POST['Registration_Number'];
            $Roll_Number = $_POST['Roll_Number'];
            $Course_Id = $_POST['Course_Id'];
            $Department_Id = $_POST['Department_Id'];
            $address = $_POST['address'];
            $gender = $_POST['gender'];
            $category = $_POST['category'];
            $subcategory = $_POST['subcategory'];
            if(empty($subcategory)) $subcategory = NULL;
            $Blood_group = $_POST['Blood_group'];
            $contact_no = $_POST['contact_no'];
            $dob = date('Y-m-d H:i:s', strtotime($_POST['dob']));
            $email_id = $_POST['email_id'];
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
			$College_Id = $_POST['College_Id'];
            $father_name = $_POST['father_name'];
            $mother_name = $_POST['mother_name'];
            $father_dob = date('Y-m-d H:i:s', strtotime($_POST['father_dob']));
            $father_email = $_POST['father_email'];
            $Batch = $_POST['Batch'];
			
	if((isset($_POST['change_studentDep']) && !empty($_POST['change_studentDep'])))
	{
            
            if((isset($_POST['Department_IdTo']) && !empty($_POST['Department_IdTo'])) && (isset($_POST['Semester_IdTo']) && !empty($_POST['Semester_IdTo'])))
            {
            $Department_IdTo = $_POST['Department_IdTo'];
            $Semester_IdTo = $_POST['Semester_IdTo'];
            $USP_ShiftStudent  = Yii::$app->Utility1->USP_ShiftStudent($Registration_Number,$Roll_Number,$Course_Id,$Department_Id,$address,$gender,$Blood_group,$contact_no,$dob,$email_id,$first_name,$last_name,$College_Id,$father_name,$mother_name,$father_dob,$father_email,$Batch,$Department_IdTo,$Semester_IdTo,$category,$subcategory);
            //echo "<pre>";print_r($USP_ShiftStudent);die();
	if($USP_ShiftStudent=='1')
         {
          Yii::$app->session->setFlash($key = 'success', $message = '<strong>Succesfully Shifted Student </strong>');   
          $log_JSON = json_encode($_POST);
          Yii::$app->Utility2->logEventDetail('Workflow','workflow/viewregisteredstudent/update','Successfully Shifted Student',$log_JSON);
         }
        else
        {
        Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Unable To Shift Student, Contact Admin.</strong>');   
        }
            }
            else
            {
             Yii::$app->session->setFlash($key = 'warning', $message = '<strong>Please Provide Shift Department </strong>');    
            }
	   return $this->redirect(Yii::$app->homeUrl."workflow/viewregisteredstudent/index?secureKey=$secureKey&secureHash=$secureHash");
	}
	else
	{
      $USP_UpdateStudentInfo  = Yii::$app->Utility1->USP_UpdateStudentInfo($Registration_Number,$Roll_Number,$Course_Id,$Department_Id,$address,$gender,$Blood_group,$contact_no,$dob,$email_id,$first_name,$last_name,$College_Id,$father_name,$mother_name,$father_dob,$father_email,$Batch,$category,$subcategory);
	  
	   Yii::$app->session->setFlash($key = 'success', $message = '<strong>Succesfully Update Student Info.</strong>'); 
	   $log_JSON = json_encode($_POST);
          Yii::$app->Utility2->logEventDetail('Workflow','workflow/viewregisteredstudent/update','Successfully Update Student Info',$log_JSON);
	  return $this->redirect(Yii::$app->homeUrl."workflow/viewregisteredstudent/index?secureKey=$secureKey&secureHash=$secureHash");
      }
	 }
    else
	{
	}
	 $url = Yii::$app->homeUrl."base/umsprivilege/index?secureKey=$secureKey&secureHash=$secureHash";
          $this->redirect($url);
          // echo "<pre>";print_r($_POST);die;
     }
 	}
        
       public function actionActivatedeactivate()
       {
        $response = array();
        $return['STATUS_ID']="222";   
        $return['STATUS_MSG']="FAILURE";
        $return['STATUS_RESPONSE']='';
        if(isset($_POST['formdata']) && !empty($_POST['formdata']))
        {
        $data = parse_str($_POST['formdata'], $info);        
        $_POST =  $info;
        if( (isset($_POST['Course_Id']) && !empty($_POST['Course_Id'])) && (isset($_POST['Department_Id']) && !empty($_POST['Department_Id'])) && (isset($_POST['Batch']) && !empty($_POST['Batch'])) && (isset($_POST['Semester']) && !empty($_POST['Semester'])) && (isset($_POST['Roll_Number']) && !empty($_POST['Roll_Number'])) && (isset($_POST['activate_decativate']) && !empty($_POST['activate_decativate'])))
        {
        //echo "<pre>ki";print_r($_POST);die;
        extract($_POST);
        $USP_Activete_DeactivateStudent  = Yii::$app->Utility->USP_Activete_DeactivateStudent($Course_Id, $Department_Id, $Batch,$Semester, $Roll_Number, $activate_decativate );          
         if($USP_Activete_DeactivateStudent=='1'){
                        $return['STATUS_ID']="000";   
                        $return['STATUS_MSG']="SUCCESS";                        
                        Yii::$app->session->setFlash($key = 'success', $message = "<strong>Successfully $activate_decativate"."d Student.</strong>");   
                        
                        $log_JSON = json_encode($_POST);
                        Yii::$app->Utility2->logEventDetail('Workflow','workflow/viewregisteredstudent/activated OR Deactivated',"Successfully $activate_decativate"."d Student.",$log_JSON);
                    }else if($USP_Activete_DeactivateStudent=='2'){
                        $return['STATUS_ID']="111";   
                        $return['STATUS_MSG']="FAILURE";
                        Yii::$app->session->setFlash($key = 'success', $message = '<strong>Unable To Update, Contact Admin.</strong>');   
                        
                    }else{
                        $return['STATUS_ID']="111";   
                        $return['STATUS_MSG']="FAILURE";
                        Yii::$app->session->setFlash($key = 'success', $message = '<strong>Unable To Process, Contact Admin.</strong>');                        
                    }
                    $return['STATUS_RESPONSE'] = "workflow/viewregisteredstudent/index?secureKey=$secureKey&secureHash=$secureHash";
                    
                    echo  json_encode($return); die;
        }
       }
        echo json_encode($return);
       }  
        
    }
