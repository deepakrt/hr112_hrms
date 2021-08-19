<?php

namespace app\modules\workflow\controllers;
use yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
class UmssubjectmasterController extends Controller
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
        $beSubject = $_POST['beSubject'];
if( (isset($beSubject['session_yr']) && !empty($beSubject['session_yr'])) && (isset($beSubject['department']) && !empty($beSubject['department'])) && (isset($beSubject['course']) && !empty($beSubject['course'])) && (isset($beSubject['semesterId']) && !empty($beSubject['semesterId'])) && (isset($beSubject['subjectCode']) && !empty($beSubject['subjectCode'])) && (isset($beSubject['subjectName']) && !empty($beSubject['subjectName'])) && (isset($beSubject['subjectDescription']) && !empty($beSubject['subjectDescription'])) && ((isset($beSubject['subjectTypeTheory']) && !empty($beSubject['subjectTypeTheory'])) ||  (isset($beSubject['subjectTypePractical']) && !empty($beSubject['subjectTypePractical']))))
{


if((isset($beSubject['subjectTypeTheory']) && $beSubject['subjectTypeTheory']=="T") && (isset($beSubject['subjectTypePractical']) && $beSubject['subjectTypePractical']=="P") )
$Subject_Type = "TP";    
else if((isset($beSubject['subjectTypeTheory']) && $beSubject['subjectTypeTheory']=="T"))
$Subject_Type = "T"; 
else if((isset($beSubject['subjectTypePractical']) && $beSubject['subjectTypePractical']=="P"))
$Subject_Type = "P"; 
else
$Subject_Type='';
if(empty($Subject_Type))
{
 return $this->redirect(Yii::$app->homeUrl);    
}
$beSubject['subjectTypeFinal'] = $Subject_Type;

            if(isset($beSubject['Elective'])){
                $beSubject['Elective'] = '1';
            }else{
                $beSubject['Elective'] = '0';
            }
            //echo "<pre>o ho";print_r($beSubject);die; 
            $secureKey = base64_encode($menu_id);
            $secureHash = Yii::$app->Utility->getHashView($menu_id);    
            $USP_InsertGroup  = Yii::$app->Utility2->USP_InsertSubject($beSubject); 
//            var_dump($USP_InsertGroup);
//            die($USP_InsertGroup);
            if($USP_InsertGroup=='1'){
                Yii::$app->session->setFlash($key = 'success', $message = '<strong>Succesfully Inserted Subject Master.</strong>');   
                $log_JSON = json_encode($beSubject);
                Yii::$app->Utility2->logEventDetail('Workflow','workflow/umssubjectmaster/insert','Successfully Created Subject Master.',$log_JSON);
            }else if($USP_InsertGroup=='2'){
                Yii::$app->session->setFlash($key = 'warning', $message = '<strong>Already Created Subject Master.</strong>');   
            }else{
                Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Unable To Insert Subject Master, Contact Admin.</strong>');   
            }

            return $this->redirect(Yii::$app->homeUrl."workflow/umssubjectmaster/index?secureKey=$secureKey&secureHash=$secureHash");
           
        }
        else
        {
        Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Invalid Parameters</strong>');
        }
        }
        else
        {
        
        return $this->redirect(Yii::$app->homeUrl);
        }
        $secureKey = base64_encode($menu_id);
        $secureHash = Yii::$app->Utility->getHashView($menu_id);
        return $this->redirect(Yii::$app->homeUrl."workflow/umssubjectmaster/index?secureKey=$secureKey&secureHash=$secureHash"); 
    }
    
    public function actionGetdepartmentlist() 
    {
        if((isset($_POST['_csrf']) && !empty($_POST['_csrf'])) && (isset($_POST['courseId']) && !empty($_POST['courseId'])))
        {
        $courseID=$_POST['courseId'];
        $deptlist  = Yii::$app->Utility->DepartmentlistbyCourseID($courseID); 
        return json_encode($deptlist);
        }
    }
    
    
}
