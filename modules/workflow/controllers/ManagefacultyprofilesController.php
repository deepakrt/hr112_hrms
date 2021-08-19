<?php

namespace app\modules\workflow\controllers;
use yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
class ManagefacultyprofilesController extends Controller
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
	
	public function actionViewfacinfo()
    {
	//echo "<pre>";print_r($_POST);die();
    if((isset($_GET['secureKey']) && !empty($_GET['secureKey'])) && (isset($_GET['secureHash'])))
     {
		 
     $secureKey = base64_decode($_GET['secureKey']);
     $secureHash = Yii::$app->Utility->getHashView($secureKey);  
	 
     if($secureHash!=$_GET['secureHash'])
     {
      //return $this->redirect(Yii::$app->homeUrl);   
     }
     return $this->render('facultyprofile',array('menuid'=>$secureKey,'data'=>$_POST));
     }
     else
     {
        return $this->redirect(Yii::$app->homeUrl);
     }
    }
	
	
    
	
	 public function actionUpdate()
    {
	//echo "<pre>";print_r($_POST);die();
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
	 
     if( (isset($_POST['_csrf']) && !empty($_POST['_csrf'])) && (isset($_POST['first_name']) && !empty($_POST['first_name'])) && (isset($_POST['Department_Id']) && !empty($_POST['Department_Id']))  )
     {
	        $faculty_id = $_POST['faculty_id'];
            $address = $_POST['address'];
            $contact_no = $_POST['contact_no'];
            $date_of_joining = date('Y-m-d H:i:s', strtotime($_POST['date_of_joining']));
            $dob = date('Y-m-d H:i:s', strtotime($_POST['dob']));
            $email_id = $_POST['email_id'];
            $emp_code = $_POST['emp_code'];
            
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
			$gender = $_POST['gender'];
            $father_name = $_POST['father_name'];
            $mother_name = $_POST['mother_name'];
            $father_dob = date('Y-m-d H:i:s', strtotime($_POST['father_dob']));
            $father_email = $_POST['father_email'];
            $Department_Id= $_POST['Department_Id'];
            $designation = $_POST['designation'];
			
      $USP_UpdateFacultyInfo  = Yii::$app->Utility1->USP_UpdateFacultyInfo($faculty_id,$address,$contact_no,$date_of_joining,$dob,$email_id,$emp_code,$first_name,$gender,$last_name,$Department_Id,$designation,$father_dob,$father_email,$father_name,$mother_name);
	
      //save logs
        $json_data = json_encode($_POST);
        Yii::$app->Utility2->logEventDetail('Work-Flow', 'workflow/managefacultyprofiles/update', 'Succesfully Update Faculty Info.', $json_data);
      
		 //echo "<pre>dsfdfd"; print_r($USP_UpdateFacultyInfo); die;
		 
          Yii::$app->session->setFlash($key = 'success', $message = '<strong>Succesfully Update Faculty Info.</strong>');   
		  
	  return $this->redirect(Yii::$app->homeUrl."workflow/managefacultyprofiles/index?secureKey=$secureKey&secureHash=$secureHash");
     }
	 
     else
     {
        //return $this->redirect(Yii::$app->homeUrl);
     }
	 $url = Yii::$app->homeUrl."base/umsprivilege/index?secureKey=$secureKey&secureHash=$secureHash";
            $this->redirect($url);
//            echo "<pre>";print_r($_POST);die;
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
        //echo "<pre>ki";print_r($_POST);die;
        if( (isset($_POST['faculty_id']) && !empty($_POST['faculty_id'])) && (isset($_POST['Department_Id']) && !empty($_POST['Department_Id'])) && (isset($_POST['email_id']) && !empty($_POST['email_id'])) && (isset($_POST['activate_decativate']) && !empty($_POST['activate_decativate'])))
        {
        //echo "<pre>ki";print_r($_POST);die;
        extract($_POST);
        $USP_Activete_DeactivateStudent  = Yii::$app->Utility2->USP_Activete_DeactivateFaculty($faculty_id, $Department_Id, $email_id, $activate_decativate );          
         if($USP_Activete_DeactivateStudent=='1'){
                        $return['STATUS_ID']="000";   
                        $return['STATUS_MSG']="SUCCESS";                        
                        Yii::$app->session->setFlash($key = 'success', $message = "<strong>Successfully $activate_decativate"."d Faculty.</strong>");   
                        
                        $log_JSON = json_encode($_POST);
                        Yii::$app->Utility2->logEventDetail('Workflow','workflow/managefacultyprofiles/activated OR Deactivated',"Successfully $activate_decativate"."d Faculty.",$log_JSON);
                    }else if($USP_Activete_DeactivateStudent=='2'){
                        $return['STATUS_ID']="111";   
                        $return['STATUS_MSG']="FAILURE";
                        Yii::$app->session->setFlash($key = 'success', $message = '<strong>Unable To Update, Contact Admin.</strong>');   
                        
                    }else{
                        $return['STATUS_ID']="111";   
                        $return['STATUS_MSG']="FAILURE";
                        Yii::$app->session->setFlash($key = 'success', $message = '<strong>Unable To Process, Contact Admin.</strong>');                        
                    }
                    $return['STATUS_RESPONSE'] = "workflow/managefacultyprofiles/index?secureKey=$secureKey&secureHash=$secureHash";
                    
                    echo  json_encode($return); die;
        }
       }
        echo json_encode($return);
       }
       
    }
