<?php

namespace app\modules\workflow\controllers;
use yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
class UmsfacultymasterController extends Controller
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
        
        if(isset($_POST['beFaculty']) && !empty($_POST['beFaculty']))
        {
        $beFaculty = $_POST['beFaculty'];
        if((isset($_POST['_csrf']) && !empty($_POST['_csrf'])) && (isset($beFaculty['Role_id']) && !empty($beFaculty['Role_id'])) && (isset($beFaculty['firstName']) && !empty($beFaculty['firstName'])) && (isset($beFaculty['lastName']) && !empty($beFaculty['lastName'])))
        {
        if((isset($beFaculty['gender']) && !empty($beFaculty['gender'])) && (isset($beFaculty['dob']) && !empty($beFaculty['dob']))  && (isset($beFaculty['contactNo']) && !empty($beFaculty['contactNo'])) && (isset($beFaculty['address']) && !empty($beFaculty['address'])))
        {
         if((isset($beFaculty['beDepartment']) && !empty($beFaculty['beDepartment'])) && (isset($beFaculty['empCode']) && !empty($beFaculty['empCode']))  && (isset($beFaculty['designation']) && !empty($beFaculty['designation'])) && (isset($beFaculty['date_of_joining']) && !empty($beFaculty['date_of_joining'])))
        {
            
            $USP_InsertFaculty  = Yii::$app->Utility1->USP_InsertFaculty($beFaculty); 
         
            
         $secureKey = base64_encode($menu_id);
         $secureHash = Yii::$app->Utility->getHashView($menu_id);
         if($USP_InsertFaculty=='1')
         {
             //Save Logs
            $json_data = json_encode($beFaculty);
            Yii::$app->Utility2->logEventDetail('Work-Flow', 'workflow/umsfacultymaster/insert', 'Succesfully Inserted Faculty Master.', $json_data);
            
          Yii::$app->session->setFlash($key = 'success', $message = '<strong>Succesfully Inserted Faculty Master.</strong>');   
         }
         else if($USP_InsertFaculty=='2')
         {
         Yii::$app->session->setFlash($key = 'warning', $message = '<strong>Already Created Faculty Master.</strong>');   
         }
         else
         {
          Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Unable To Insert Faculty Master, Contact Admin.</strong>');   
         }
         return $this->redirect(Yii::$app->homeUrl."workflow/umsfacultymaster/index?secureKey=$secureKey&secureHash=$secureHash");
        }
        else
        {
            
        }
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
        else
        {
        //Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Invaid Parameters</strong>');
        }
        }
        return $this->redirect(Yii::$app->homeUrl);
         
    
	}
	
	
    }
