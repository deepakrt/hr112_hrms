<?php

namespace app\modules\workflow\controllers;
use yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
class UploadnoticeController extends Controller
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
//        echo "<pre>";print_r($_POST); die;
        
        
        if((isset($_POST['secureKey']) && !empty($_POST['secureKey'])) && (isset($_POST['secureHash']) && !empty($_POST['secureHash'])))
        {
        $menu_id = $secureKey = base64_decode($_POST['secureKey']);
        $secureHash = Yii::$app->Utility->getHashInsert($secureKey);
        
        if($secureHash!=$_POST['secureHash'])
        {
        return $this->redirect(Yii::$app->homeUrl);   
        }
        if(isset($_POST['benotice']) && !empty($_POST['benotice']))
        {
         $benotice = $_POST['benotice'];         
         if(isset($benotice['title']) && !empty($benotice['title']) && isset($benotice['date']) && !empty($benotice['date']) && isset($benotice['address']) && !empty($benotice['address']))   
         {
            $USP_UploadNotice  = Yii::$app->Utility1->USP_UploadNotice($benotice);
            $secureKey = base64_encode($menu_id);
            $secureHash = Yii::$app->Utility->getHashView($menu_id);
         if($USP_UploadNotice=='1')
         {
         Yii::$app->session->setFlash($key = 'success', $message = '<strong>Succesfully Inserted Notice.</strong>');   
         $log_JSON = json_encode($benotice);
          Yii::$app->Utility2->logEventDetail('Workflow','workflow/uploadnotice/insert','Successfully Inserted Notice',$log_JSON);
         }
		 /*else if($USP_UploadNotice=='2')
         {
         Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Already Exist Notice.</strong>');   
         }*/
         else
         {
          Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Unable To Insert Notice, Contact Admin.</strong>');   
         }
         return $this->redirect(Yii::$app->homeUrl."workflow/uploadnotice/index?secureKey=$secureKey&secureHash=$secureHash");
         }
         else
         {
         //Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Invaid Parameters</strong>');  
         }
         }
         else
         {
         // Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Invaid Parameters</strong>');   
         }
         }       
        
        return $this->redirect(Yii::$app->homeUrl);        
        
    
         
    
	}
	
	
    }
