<?php

namespace app\modules\workflow\controllers;
use yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
class UmstestfController extends Controller
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
        
        if(isset($_POST['beDepartment']) && !empty($_POST['beDepartment']))
        {
        $beDepartment = $_POST['beDepartment'];
        if((isset($beDepartment['departmentName']) && !empty($beDepartment['departmentName'])) && (isset($beDepartment['departmentDescription']) && !empty($beDepartment['departmentDescription'])))
        {       
         $USP_InsertDepartment  = Yii::$app->Test->USP_test($beDepartment);
         $secureKey = base64_encode($menu_id);
         $secureHash = Yii::$app->Utility->getHashView($menu_id);
         if($USP_InsertDepartment=='1')
         {
         Yii::$app->session->setFlash($key = 'success', $message = '<strong>Succesfully Inserted Department.</strong>');   
         $log_JSON = json_encode($beDepartment);
         Yii::$app->Utility2->logEventDetail('Workflow','workflow/umstestf/insert','Successfully inserted Department Master',$log_JSON);
         }
		 else if($USP_InsertDepartment=='2')
         {
         Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Already Exist Department.</strong>');   
         }
         else
         {
         Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Unable To Insert Department Master, Contact Admin.</strong>');   
         }
         return $this->redirect(Yii::$app->homeUrl."workflow/umstestf/index?secureKey=$secureKey&secureHash=$secureHash");
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
	 
     if( (isset($_POST['_csrf']) && !empty($_POST['_csrf'])) && (isset($_POST['Dept_id']) && !empty($_POST['Dept_id'])) &&(isset($_POST['Department_Name']) && !empty($_POST['Department_Name'])) && (isset($_POST['Department_Description']) && !empty($_POST['Department_Description']))  )
     {
		 
             $Dept_id = $_POST['Dept_id'];		 
	        $Department_Name = $_POST['Department_Name'];
            $Department_Description = $_POST['Department_Description'];
           
			
	
	

      $USP_UpdateStudentInfo  = Yii::$app->Test->USP_UpdateStudentInfo($Dept_id,$Department_Name,$Department_Description);
	  
	   Yii::$app->session->setFlash($key = 'success', $message = '<strong>Succesfully Update Student Info.</strong>'); 
	   $log_JSON = json_encode($_POST);
          Yii::$app->Utility2->logEventDetail('Workflow','workflow/umstestf/update','Successfully Update Student Info',$log_JSON);
	  return $this->redirect(Yii::$app->homeUrl."workflow/umstestf/index?secureKey=$secureKey&secureHash=$secureHash");
      }
	 }
    else
	{
	}
	 $url = Yii::$app->homeUrl."base/umsprivilege/index?secureKey=$secureKey&secureHash=$secureHash";
          $this->redirect($url);
          // echo "<pre>";print_r($_POST);die;
   
 	}
	

public function actionDelete()
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
	 
     if( (isset($_POST['_csrf']) && !empty($_POST['_csrf'])) && (isset($_POST['Dept_id']) && !empty($_POST['Dept_id'])) &&(isset($_POST['Department_Name']) && !empty($_POST['Department_Name'])) && (isset($_POST['Department_Description']) && !empty($_POST['Department_Description']))  )
     {
		 
             $Dept_id = $_POST['Dept_id'];		 
	        $Department_Name = $_POST['Department_Name'];
            $Department_Description = $_POST['Department_Description'];
           
			
	
	

      $USP_DeleteStudentInfo  = Yii::$app->Test->USP_DeleteStudentInfo($Dept_id,$Department_Name,$Department_Description);
	  
	   Yii::$app->session->setFlash($key = 'success', $message = '<strong>Succesfully DELETED Student Info.</strong>'); 
	   $log_JSON = json_encode($_POST);
          Yii::$app->Utility2->logEventDetail('Workflow','workflow/umstestf/update','Successfully Update Student Info',$log_JSON);
	  return $this->redirect(Yii::$app->homeUrl."workflow/umstestf/index?secureKey=$secureKey&secureHash=$secureHash");
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
