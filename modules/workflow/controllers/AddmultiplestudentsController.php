<?php

namespace app\modules\workflow\controllers;
use yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\PuUploadStudentSheet;
class AddmultiplestudentsController extends Controller
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
        $data = '12';
        $securePUData = Yii::$app->Utility->securePUData($data); 
        var_dump($securePUData);
        $validatePUData = Yii::$app->Utility->validatePUData($securePUData); 
        if($validatePUData)
        {        
        var_dump($validatePUData);
        }
        else
        {
        echo "FRAUD DATA";
        }
        die;
        * 
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
        if((isset($uploadstudent['deptInfo']) && !empty($uploadstudent['deptInfo'])) && (isset($uploadstudent['beCourse']) && !empty($uploadstudent['beCourse'])) && (isset($uploadstudent['StartBatch']) && !empty($uploadstudent['StartBatch'])) && (isset($uploadstudent['EndBatch']) && !empty($uploadstudent['EndBatch'])) && (isset($uploadstudent_File['name']['fileUpload']) && !empty($uploadstudent_File['name']['fileUpload'])))
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
return $this->redirect(Yii::$app->homeUrl."workflow/addmultiplestudents/index?secureKey=$secureKey&secureHash=$secureHash");   
}
 $objPHPExcel = \PHPExcel_IOFactory::load($inputFileName);
 $objPHPExcel->setActiveSheetIndex(0);
 $worksheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
$connection=   Yii::$app->db;
$transaction = $connection->beginTransaction();
$check_DatabaseInsert = $checkExcelLabel = false;
extract($uploadstudent);
$session_yr = $StartBatch."-".$EndBatch;
$student_JSONDATA = array();
foreach($worksheet as $sheetDatas)
    {
    $Gender_Final = $Category_Final = $SubCategory_Final = $EmailID_Final = NULL;
    $savedata_PuUploadStudentSheet = array();
    
    if(empty(trim($sheetDatas['A'])) && empty(trim($sheetDatas['B'])) && empty(trim($sheetDatas['C'])) && empty(trim($sheetDatas['D']))  && empty(trim($sheetDatas['E'])) && empty(trim($sheetDatas['F'])) )
    {
    }
    else
    {
    if(!$checkExcelLabel)
    {
     if($sheetDatas['A']=="NAME" && $sheetDatas['B']=="FATHER NAME" && $sheetDatas['C']=="MOTHER NAME" && $sheetDatas['D']=="Roll No" && $sheetDatas['E']=="Reg No" && $sheetDatas['F']=="Gender" && $sheetDatas['G']=="Category" && $sheetDatas['H']=="Sub Category" && $sheetDatas['I']=="EMAIL")    
     {
      $checkExcelLabel = true;  
     }
     else
     {
      Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Please Check Your Excel Upload Sheet Lables.</strong>');   
      return $this->redirect(Yii::$app->homeUrl."workflow/addmultiplestudents/index?secureKey=$secureKey&secureHash=$secureHash");   
     }
    }
    else
    {
    if(!empty(trim($sheetDatas['A'])) && !empty(trim($sheetDatas['B'])) && !empty(trim($sheetDatas['C'])) && !empty(trim($sheetDatas['D']))  && !empty(trim($sheetDatas['E'])) )
    {
    $Roll_No = $sheetDatas['D'];
    $check_DatabaseInsert = true;
    
    $studentpassword = md5($Roll_No);
    
    
    $Gender  = trim($sheetDatas['F']);
    $Gender = Yii::$app->Utility->removeMultipleSpaces($Gender);
   //var_dump($Gender);
    if(!empty($Gender))
    {
        
    if(!Yii::$app->Utility->validGender($Gender))
    {
    Yii::$app->session->setFlash($key = 'danger', $message = "<strong>Invalid Gender for Rollno ($Roll_No).</strong>");   
    return $this->redirect(Yii::$app->homeUrl."workflow/addmultiplestudents/index?secureKey=$secureKey&secureHash=$secureHash");         
    }
    $Gender_Final = $Gender;
    }
    
    $Category  = trim($sheetDatas['G']);
    $Category = Yii::$app->Utility->removeMultipleSpaces($Category);
    
    if(!empty($Category))
    {
    if(!($Category_Id = Yii::$app->Utility->validateCategoryByName($Category,1)))
    {
    Yii::$app->session->setFlash($key = 'danger', $message = "<strong>Invalid Category for Rollno ($Roll_No).</strong>");   
    return $this->redirect(Yii::$app->homeUrl."workflow/addmultiplestudents/index?secureKey=$secureKey&secureHash=$secureHash");             
    }
    $Category_Final = $Category_Id;
    }
    
    $SubCategory  = trim($sheetDatas['H']);
    $SubCategory = Yii::$app->Utility->removeMultipleSpaces($SubCategory);
    
    if(!empty($SubCategory))
    {
    if(!($SubCategory_Id = Yii::$app->Utility->validateCategoryByName($SubCategory,2)))
    {
    Yii::$app->session->setFlash($key = 'danger', $message = "<strong>Invalid SubCategory for Rollno ($Roll_No).</strong>");   
    return $this->redirect(Yii::$app->homeUrl."workflow/addmultiplestudents/index?secureKey=$secureKey&secureHash=$secureHash");             
    }
    $SubCategory_Final = $SubCategory_Id;
    }
    
    $EmailID  = trim($sheetDatas['I']);
    $EmailID = Yii::$app->Utility->removeMultipleSpaces($EmailID);
    
    if(!empty($EmailID))
    {   
    $EmailID_Final = $EmailID;
    }
    
    $savedata_PuUploadStudentSheet['student_sheet_name'] = (string) trim($sheetDatas['A']);    
    $savedata_PuUploadStudentSheet['student_sheet_fname'] = (string) trim($sheetDatas['B']);
    $savedata_PuUploadStudentSheet['student_sheet_mname'] = (string) trim($sheetDatas['C']);
    $savedata_PuUploadStudentSheet['student_sheet_rollno'] = (string) trim($sheetDatas['D']);
    $savedata_PuUploadStudentSheet['student_sheet_regno'] = (string) trim($sheetDatas['E']);
    $savedata_PuUploadStudentSheet['student_sheet_batch'] = $session_yr;
    $savedata_PuUploadStudentSheet['student_sheet_dept'] = $deptInfo;
    $savedata_PuUploadStudentSheet['student_sheet_course'] = $beCourse;
    
    $savedata_PuUploadStudentSheet['student_sheet_gender'] = $Gender_Final;
    $savedata_PuUploadStudentSheet['student_sheet_category'] = $Category_Final;    
    $savedata_PuUploadStudentSheet['student_sheet_subcategory'] = $SubCategory_Final;
    $savedata_PuUploadStudentSheet['student_sheet_pasword'] = $studentpassword;
    $savedata_PuUploadStudentSheet['student_sheet_email'] = $EmailID_Final;
    
    $student_JSONDATA[] = $savedata_PuUploadStudentSheet;
    
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
    return $this->redirect(Yii::$app->homeUrl."workflow/addmultiplestudents/index?secureKey=$secureKey&secureHash=$secureHash");      
    }
    
    }
    else
    {
    Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Please Check Your Excel Upload Sheet Data, Some Fields Missing.</strong>');   
    return $this->redirect(Yii::$app->homeUrl."workflow/addmultiplestudents/index?secureKey=$secureKey&secureHash=$secureHash");      
    }
    }
    }
    
}
if($check_DatabaseInsert)
{
$transaction->commit();
//var_dump($check_DatabaseInsert); die;
$USP_InsertStudentFromFile = Yii::$app->Utility->USP_InsertStudentFromFile($session_yr, $beCourse, $deptInfo);
//var_dump($USP_InsertStudentFromFile); die;
if($USP_InsertStudentFromFile=="1")
{
Yii::$app->session->setFlash($key = 'success', $message = '<strong>Successfully Uploaded Sheet.</strong>');   
$log_JSON = json_encode($student_JSONDATA);
Yii::$app->Utility2->logEventDetail('Workflow','workflow/addmultiplestudents/insert','Successfully Uploaded Student Sheet',$log_JSON);
return $this->redirect(Yii::$app->homeUrl."workflow/addmultiplestudents/index?secureKey=$secureKey&secureHash=$secureHash");
}
else if($USP_InsertStudentFromFile=="2")
{
Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Roll Number Alreday Uploaded.</strong>');   
return $this->redirect(Yii::$app->homeUrl."workflow/addmultiplestudents/index?secureKey=$secureKey&secureHash=$secureHash");
}
else
{
 Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Unable To Process, Contact Admin.</strong>');   
return $this->redirect(Yii::$app->homeUrl."workflow/addmultiplestudents/index?secureKey=$secureKey&secureHash=$secureHash");   
}
}
else
{
Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Excel Upload Sheet Data Is Empty. Please Check.</strong>');   
return $this->redirect(Yii::$app->homeUrl."workflow/addmultiplestudents/index?secureKey=$secureKey&secureHash=$secureHash");          
}

}
else
{
Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Please Check Your Excel Upload Sheet Extension OR Sheet Size.</strong>');   
return $this->redirect(Yii::$app->homeUrl."workflow/addmultiplestudents/index?secureKey=$secureKey&secureHash=$secureHash");          
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
