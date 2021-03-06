<?php

namespace app\modules\workflow\controllers;
use yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\PuUploadStudentSheet;
class UpdatemultiplestudentsController extends Controller
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
        /*
         * $data = '12';
         
        $securePUData = Yii::$app->Utility->securePUData($data); 
        var_dump($securePUData);
        
        $validatePUData = Yii::$app->Utility->validatePUData($securePUData); 
        $getPUData = Yii::$app->Utility->getPUData($securePUData);
        
         var_dump($validatePUData);
         var_dump($getPUData);
         
         die;
         */
        
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
//        echo "<pre>";print_r($_POST);
//        print_r($_FILES);
//     die; 
        if((isset($_POST['secureKey']) && !empty($_POST['secureKey'])) && (isset($_POST['secureHash']) && !empty($_POST['secureHash'])))
        {
        $menu_id = $secureKey = base64_decode($_POST['secureKey']);
        $secureHash = Yii::$app->Utility->getHashInsert($secureKey);
        if($secureHash!=$_POST['secureHash'])
        {
        return $this->redirect(Yii::$app->homeUrl);   
        }
        if(isset($_POST['uploadstudent']) && !empty($_POST['uploadstudent']) && isset($_FILES['uploadstudent']) && !empty($_FILES['uploadstudent']))
        {	
        $uploadstudent = $_POST['uploadstudent'];
	$uploadstudent_File = $_FILES['uploadstudent'];
        if((isset($uploadstudent['session_yr']) && !empty($uploadstudent['session_yr'])) && (isset($uploadstudent['deptInfo']) && !empty($uploadstudent['deptInfo'])) && (isset($uploadstudent['beCourse']) && !empty($uploadstudent['beCourse'])) && (isset($uploadstudent['semesterId']) && !empty($uploadstudent['semesterId'])) && (isset($uploadstudent_File['name']['fileUpload']) && !empty($uploadstudent_File['name']['fileUpload'])))
        { 
            
        $secureKey = base64_encode($menu_id);
        $secureHash = Yii::$app->Utility->getHashView($menu_id);
        
        $target_dir = UPLOAD_PATH."/";
       
$target_file = $target_dir . basename($uploadstudent_File["name"]["fileUpload"]);
$image_FileType = pathinfo($target_file,PATHINFO_EXTENSION);

$image_FileSize = $uploadstudent_File["size"]["fileUpload"];
$chk_Size = UPLOAD_SIZE * '1000';


if($image_FileSize <= $chk_Size && ($image_FileType=="xlsx" || $image_FileType=="xls"))
{
  //echo "<pre>";  print_r($uploadstudent_File); die;
 $inputFileName = $uploadstudent_File["tmp_name"]["fileUpload"]; 
 
 $validFile = false;
$types = array('Excel2007', 'Excel5');
foreach ($types as $type)
{
$reader = \PHPExcel_IOFactory::createReader($type);
if ($reader->canRead($inputFileName))
{
$validFile = true;
break;
}
}

if (!$validFile) {
Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Please Check Your Excel Upload File, It Is Not Valid.</strong>');   
return $this->redirect(Yii::$app->homeUrl."workflow/updatemultiplestudents/index?secureKey=$secureKey&secureHash=$secureHash");   
}
 $objPHPExcel = \PHPExcel_IOFactory::load($inputFileName);
 $objPHPExcel->setActiveSheetIndex(0);
 $worksheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
$connection=   Yii::$app->db;
$transaction = $connection->beginTransaction();
$check_DatabaseInsert = $checkExcelLabel = false;
extract($uploadstudent);
foreach($worksheet as $sheetDatas)
    {
    $savedata_PuUploadStudentSheet = array();
    if(empty(trim($sheetDatas['A'])) && empty(trim($sheetDatas['B'])) && empty(trim($sheetDatas['C'])) && empty(trim($sheetDatas['D']))  && empty(trim($sheetDatas['E'])) && empty(trim($sheetDatas['F'])) )
    {
    }
    else
    {
    if(!$checkExcelLabel)
    {
     if($sheetDatas['A']=="NAME" && $sheetDatas['B']=="EMAIL" && $sheetDatas['C']=="FATHER NAME" && $sheetDatas['D']=="MOTHER NAME" && $sheetDatas['E']=="Roll No" && $sheetDatas['F']=="Reg No")    
     {
      $checkExcelLabel = true;  
     }
     else
     {
      Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Please Check Your Excel Upload Sheet Lables.</strong>');   
      return $this->redirect(Yii::$app->homeUrl."workflow/updatemultiplestudents/index?secureKey=$secureKey&secureHash=$secureHash");   
     }
    }
    else
    {
    if(!empty(trim($sheetDatas['A'])) && !empty(trim($sheetDatas['B'])) && !empty(trim($sheetDatas['C'])) && !empty(trim($sheetDatas['D']))  && !empty(trim($sheetDatas['E'])) && !empty(trim($sheetDatas['F'])) )
    {
    $check_DatabaseInsert = true;
    $savedata_PuUploadStudentSheet['student_sheet_name'] = $sheetDatas['A'];
    $savedata_PuUploadStudentSheet['student_sheet_email'] = $sheetDatas['B'];
    $savedata_PuUploadStudentSheet['student_sheet_fname'] = $sheetDatas['C'];
    $savedata_PuUploadStudentSheet['student_sheet_mname'] = $sheetDatas['D'];
    $savedata_PuUploadStudentSheet['student_sheet_rollno'] = $sheetDatas['E'];
    $savedata_PuUploadStudentSheet['student_sheet_regno'] = $sheetDatas['F'];
    $savedata_PuUploadStudentSheet['student_sheet_batch'] = $session_yr;
    $savedata_PuUploadStudentSheet['student_sheet_dept'] = $deptInfo;
    $savedata_PuUploadStudentSheet['student_sheet_course'] = $beCourse;
    $savedata_PuUploadStudentSheet['student_sheet_semester'] = $semesterId;
    $model_PuUploadStudentSheet = new PuUploadStudentSheet();
    $model_PuUploadStudentSheet->attributes = $savedata_PuUploadStudentSheet; 
    if ($model_PuUploadStudentSheet->save())
    {
        
    }
    else
    {
      $transaction->rollback();
      $error = $model_PuUploadStudentSheet->getErrors();                    
      $error_html = Yii::$app->Utility->getErrors($error);
      Yii::$app->session->setFlash($key = 'danger', $message = "<strong>$error_html</strong>");   
    return $this->redirect(Yii::$app->homeUrl."workflow/updatemultiplestudents/index?secureKey=$secureKey&secureHash=$secureHash");      
    }
    
    }
    else
    {
    Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Please Check Your Excel Upload Sheet Data, Some Fields Missing.</strong>');   
    return $this->redirect(Yii::$app->homeUrl."workflow/updatemultiplestudents/index?secureKey=$secureKey&secureHash=$secureHash");      
    }
    }
    }
    
}
if($check_DatabaseInsert)
{
$transaction->commit();

$USP_InsertStudentFromFile = Yii::$app->Utility->USP_InsertStudentFromFile($semesterId, $session_yr, $beCourse, $deptInfo);
//var_dump($USP_InsertStudentFromFile); die;
if($USP_InsertStudentFromFile=="1")
{
Yii::$app->session->setFlash($key = 'success', $message = '<strong>Successfully Uploaded Sheet.</strong>');   
return $this->redirect(Yii::$app->homeUrl."workflow/updatemultiplestudents/index?secureKey=$secureKey&secureHash=$secureHash");
}
else if($USP_InsertStudentFromFile=="2")
{
Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Roll Number Alreday Uploaded.</strong>');   
return $this->redirect(Yii::$app->homeUrl."workflow/updatemultiplestudents/index?secureKey=$secureKey&secureHash=$secureHash");
}
else
{
 Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Unable To Process, Contact Admin.</strong>');   
return $this->redirect(Yii::$app->homeUrl."workflow/updatemultiplestudents/index?secureKey=$secureKey&secureHash=$secureHash");   
}
}
else
{
Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Excel Upload Sheet Data Is Empty. Please Check.</strong>');   
return $this->redirect(Yii::$app->homeUrl."workflow/updatemultiplestudents/index?secureKey=$secureKey&secureHash=$secureHash");          
}

}
else
{
Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Please Check Your Excel Upload Sheet Extension OR Sheet Size.</strong>');   
return $this->redirect(Yii::$app->homeUrl."workflow/updatemultiplestudents/index?secureKey=$secureKey&secureHash=$secureHash");          
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
