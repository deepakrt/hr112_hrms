<?php

namespace app\modules\workflow\controllers;
use yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
class UmsfreezeattendanceController extends Controller
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
             
         }
         if( (isset($_POST['_csrf']) && !empty($_POST['_csrf'])) && (isset($_POST['freeze']) && !empty($_POST['freeze'])) )
             {
             
             $freeze = $_POST['freeze'];
             if( (isset($freeze['Freeze']) && !empty($freeze['Freeze'])) && (isset($freeze['Batch']) && !empty($freeze['Batch'])) && (isset($freeze['semesterId']) && !empty($freeze['semesterId']))  )
             {
              $actionArray = array("Freeze","DeFreeze");
             
              extract($freeze);
              if (in_array($Freeze, $actionArray))
  {             {
                //echo"<pre>123";print_r($freeze); die; 
                if($Freeze=="Freeze")
                $actionVal = "F";
                else $actionVal = "D";
              $USP_FreezeDeFreezeAttendance  = Yii::$app->Utility1->USP_FreezeDeFreezeAttendance($Batch ,$semesterId, $actionVal);
              //var_dump($USP_FreezeDeFreezeAttendance); die;   
              $actionDisplay = $Freeze."d";
               if($USP_FreezeDeFreezeAttendance=='1')
         {
          Yii::$app->session->setFlash($key = 'success', $message = "<strong>Succesfully $actionDisplay</strong>");   
          $log_JSON = json_encode($freeze);
          Yii::$app->Utility2->logEventDetail('Workflow','workflow/umsfreezeattendance/process',"Succesfully $actionDisplay",$log_JSON);
         }
        else
        {
        Yii::$app->session->setFlash($key = 'danger', $message = "<strong>Unable To $actionDisplay, Contact Admin.</strong>");   
        }
        return $this->redirect(Yii::$app->homeUrl."workflow/umsfreezeattendance/index?secureKey=$secureKey&secureHash=$secureHash");
              }
  }         
             }
         }
             
         return $this->redirect(Yii::$app->homeUrl);  
    }
	
	}
