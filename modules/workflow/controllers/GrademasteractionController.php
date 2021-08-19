<?php

namespace app\modules\workflow\controllers;
use yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
class GrademasteractionController extends Controller
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
       
        
        if((isset($_POST['secureKey']) && !empty($_POST['secureKey'])) && (isset($_POST['secureHash']) && !empty($_POST['secureHash'])))
        {
        $menu_id = $secureKey = base64_decode($_POST['secureKey']);
        $secureHash = Yii::$app->Utility->getHashInsert($secureKey);
        
        if($secureHash!=$_POST['secureHash'])
        {
        return $this->redirect(Yii::$app->homeUrl);   
        }
        if(isset($_POST['beGrade']) && !empty($_POST['beGrade']))
        {
         $beGrade = $_POST['beGrade'];         
         if(isset($beGrade['gradeName']) && !empty($beGrade['gradeName']) && isset($beGrade['gradePoint']) && !empty($beGrade['gradePoint']) && isset($beGrade['gradeDescription']) && !empty($beGrade['gradeDescription']) && isset($beGrade['sessionStartYear']) && !empty($beGrade['sessionStartYear']) && isset($beGrade['sessionEndYear']) && !empty($beGrade['sessionEndYear']) && isset($beGrade['percentageStartmarks']) && !empty($beGrade['percentageStartmarks']) && isset($beGrade['percentageEndmarks']) && !empty($beGrade['percentageEndmarks']))   
         {
          $USP_InsertGrade  = Yii::$app->Utility1->USP_InsertGrade($beGrade);
		  
          $secureKey = base64_encode($menu_id);
          $secureHash = Yii::$app->Utility->getHashView($menu_id);
         if($USP_InsertGrade)
         {
         Yii::$app->session->setFlash($key = 'success', $message = '<strong>Succesfully Inserted Grade Master.</strong>');   
         
          $log_JSON = json_encode($beGrade);
          Yii::$app->Utility2->logEventDetail('Workflow','workflow/grademasteraction/insert','Successfully Inserted Grade Master',$log_JSON);
         }
         else
         {
          Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Unable To Insert Grade Master, Contact Admin.</strong>');   
         }
         return $this->redirect(Yii::$app->homeUrl."workflow/grademasteraction/index?secureKey=$secureKey&secureHash=$secureHash");
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
