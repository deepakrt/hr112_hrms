<?php

namespace app\modules\workflow\controllers;
use yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
class IncrementstudController extends Controller
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
    public function actionPromote()
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
        
        if(isset($_POST['Promote']) && !empty($_POST['Promote']))
        {
        $Promote = $_POST['Promote'];
        if((isset($Promote['session_yr']) && !empty($Promote['session_yr'])) && (isset($Promote['deptInfo']) && !empty($Promote['deptInfo'])) && (isset($Promote['beCourse']) && !empty($Promote['beCourse'])))
        {
        if((isset($Promote['semester_promoted_from']) && !empty($Promote['semester_promoted_from'])) && (isset($Promote['semester_promoted_to']) && !empty($Promote['semester_promoted_to'])))
        {        
         $USP_PromoteStudent  = Yii::$app->Utility->USP_PromoteStudent($Promote); 
         //var_dump($USP_PromoteStudent);die;
         $secureKey = base64_encode($menu_id);
         $secureHash = Yii::$app->Utility->getHashView($menu_id);
         if($USP_PromoteStudent=='1')
         {
         $log_JSON = json_encode($Promote);
         Yii::$app->Utility2->logEventDetail('Workflow','workflow/incrementstud/promote','Successfully Promoted',$log_JSON);
         Yii::$app->session->setFlash($key = 'success', $message = '<strong>Succesfully Promoted. </strong>');   
         }
         else if($USP_PromoteStudent=='2')
         {
         Yii::$app->session->setFlash($key = 'warning', $message = '<strong>Already Promoted.</strong>');   
         }
         else if($USP_PromoteStudent=='3')
         {
         Yii::$app->session->setFlash($key = 'warning', $message = '<strong>Already Promoted.</strong>');   
         }
         else if($USP_PromoteStudent=='4')
         {
         Yii::$app->session->setFlash($key = 'warning', $message = '<strong>MIsMatch Batch With Course.</strong>');   
         }
         else
         {
         Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Unable To Promote, Contact Admin.</strong>');   
         }
         return $this->redirect(Yii::$app->homeUrl."workflow/incrementstud/index?secureKey=$secureKey&secureHash=$secureHash");
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
