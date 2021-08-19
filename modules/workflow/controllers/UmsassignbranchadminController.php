<?php

namespace app\modules\workflow\controllers;
use yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
class UmsassignbranchadminController extends Controller
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
    
    public function actionViewcurrentbranchadmin()
    {
        $return = array();
    $return['STATUS_ID']="111";   
    $return['STATUS_MSG']="FAILURE";
    $return['STATUS_RESPONSE']="Invalid Request/Fraudulent data detected";
       // print_r($_POST);die;
     if((isset($_POST['_csrf']) && !empty($_POST['_csrf'])) && (isset($_POST['dept']) && !empty($_POST['dept'])))
     {
     $department = $_POST['dept'];
     $USP_ViewCurrentBAdmin = Yii::$app->Utility->USP_ViewCurrentBAdmin($department);
     //echo "<pre>"; print_r($USP_ViewCurrentBAdmin); die;
     if(!empty($USP_ViewCurrentBAdmin))
        {
        $html =  $this->renderPartial('branchadminlists',array('info'=>$USP_ViewCurrentBAdmin));
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
         
         
         if( (isset($_POST['_csrf']) && !empty($_POST['_csrf'])) && (isset($_POST['AssignBAdmin']) && !empty($_POST['AssignBAdmin'])) )
             {
             
             $AssignBAdmin = $_POST['AssignBAdmin'];
             if( (isset($AssignBAdmin['Department']) && !empty($AssignBAdmin['Department'])) && (isset($AssignBAdmin['Assign']) && !empty($AssignBAdmin['Assign']))  )
             {             
              $actionArray = array("Assign");
             
              //extract($AssignBAdmin);
              $Assign = $AssignBAdmin['Assign'];
              if (in_array($Assign, $actionArray))
                {             
              $Department = $AssignBAdmin['Department'];
                            
              if(isset($AssignBAdmin['Other_Department_Check']) && (isset($AssignBAdmin['Other_Department']) && !empty($AssignBAdmin['Other_Department'])) && (isset($AssignBAdmin['Other_Department_Faculty']) && !empty($AssignBAdmin['Other_Department_Faculty'])) )
              {
              $Faculty = $AssignBAdmin['Other_Department_Faculty'];    
              }
              else if(isset($AssignBAdmin['Faculty']) && !empty($AssignBAdmin['Faculty']))
              {
                  $Faculty = $AssignBAdmin['Faculty'];    
              }
                  else
              {
               return $this->redirect(Yii::$app->homeUrl);     
              }
              
                $USP_AssignBranchAdmin = Yii::$app->Utility->USP_AssignBranchAdmin($Department, $Faculty);
                //echo "<pre>"; print_r($USP_AssignBranchAdmin); die;
               if($USP_AssignBranchAdmin=='1')
         {
          Yii::$app->session->setFlash($key = 'success', $message = "<strong>Succesfully Changed/Assigned Branch Admin Role</strong>");   
          $log_JSON = json_encode($AssignBAdmin);
          Yii::$app->Utility2->logEventDetail('Workflow','workflow/umsassignbranchadmin/process',"Succesfully Changed/Assigned Branch Admin Role",$log_JSON);
         }
        else
        {
        Yii::$app->session->setFlash($key = 'danger', $message = "<strong>Unable To Change/Assign Branch Admin Role, Contact Admin.</strong>");   
        Yii::$app->Utility2->logEventDetail('Workflow','workflow/umsassignbranchadmin/process',"Unable To Change/Assign Branch Admin Role, Contact Admin.",$log_JSON);
        }
        return $this->redirect(Yii::$app->homeUrl."workflow/umsassignbranchadmin/index?secureKey=$secureKey&secureHash=$secureHash");
              }
             }
             }
         }
             
         return $this->redirect(Yii::$app->homeUrl);  
    }
	
	}
