<?php

namespace app\modules\workflow\controllers;
use yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\PuUploadRegistrationSheet;
class UmsupdateregistrationnoController extends Controller
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
        if(isset($_POST['updateregno']) && !empty($_POST['updateregno']) && isset($_FILES['updateregno']) && !empty($_FILES['updateregno']))
        {	
        $updateregno = $_POST['updateregno'];
	$updateregno_File = $_FILES['updateregno'];
        if((isset($updateregno['deptInfo']) && !empty($updateregno['deptInfo'])) && (isset($updateregno['beCourse']) && !empty($updateregno['beCourse'])) && (isset($updateregno['StartBatch']) && !empty($updateregno['StartBatch'])) && (isset($updateregno['EndBatch']) && !empty($updateregno['EndBatch'])) && (isset($updateregno_File['name']['fileUpload']) && !empty($updateregno_File['name']['fileUpload'])))
        {
            
        $secureKey = base64_encode($menu_id);
        $secureHash = Yii::$app->Utility->getHashView($menu_id);
        
        $target_dir = UPLOAD_PATH."/";
       
$target_file = $target_dir . basename($updateregno_File["name"]["fileUpload"]);
$image_FileType = pathinfo($target_file,PATHINFO_EXTENSION);

$image_FileSize = $updateregno_File["size"]["fileUpload"];
$chk_Size = UPLOAD_SIZE * '1000';


if($image_FileSize <= $chk_Size && ($image_FileType=="xlsx" || $image_FileType=="xls"))
{
  //echo "<pre>";  print_r($updateregno_File); die;
 $inputFileName = $updateregno_File["tmp_name"]["fileUpload"]; 
 
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
return $this->redirect(Yii::$app->homeUrl."workflow/umsupdateregistrationno/index?secureKey=$secureKey&secureHash=$secureHash");   
}
 $objPHPExcel = \PHPExcel_IOFactory::load($inputFileName);
 $objPHPExcel->setActiveSheetIndex(0);
 $worksheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
$connection=   Yii::$app->db;
$transaction = $connection->beginTransaction();
$check_DatabaseInsert = $checkExcelLabel = false;
extract($updateregno);
$session_yr = $StartBatch."-".$EndBatch;
$student_JSONDATA = array();
foreach($worksheet as $sheetDatas)
    {
    $savedata_PuUploadRegistrationSheet = array();
    if(empty(trim($sheetDatas['A'])) && empty(trim($sheetDatas['B'])) && empty(trim($sheetDatas['C'])) && empty(trim($sheetDatas['D']))  && empty(trim($sheetDatas['E'])) && empty(trim($sheetDatas['F'])) )
    {
    }
    else
    {
    if(!$checkExcelLabel)
    {
     if($sheetDatas['A']=="Roll No" && $sheetDatas['B']=="Registration No" && $sheetDatas['C']=="Name" && $sheetDatas['D']=="Father Name" && $sheetDatas['E']=="Mother Name" && $sheetDatas['F']=="Date of Birth")    
     {
      $checkExcelLabel = true;  
     }
     else
     {
      Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Please Check Your Excel Upload Sheet Lables.</strong>');   
      return $this->redirect(Yii::$app->homeUrl."workflow/umsupdateregistrationno/index?secureKey=$secureKey&secureHash=$secureHash");   
     }
    }
    else
    {
    if(!empty(trim($sheetDatas['A'])) && !empty(trim($sheetDatas['B'])) && !empty(trim($sheetDatas['C'])) && !empty(trim($sheetDatas['D']))  && !empty(trim($sheetDatas['E'])) && !empty(trim($sheetDatas['F'])) )
    {
    $check_DatabaseInsert = true;
    $sheetDOB = $sheetDatas['F'];
    $string_pwd = $string = '';    
    $chkDOB = explode(".",$sheetDOB);
    if(count($chkDOB) == "3")
    {
        $month  = $chkDOB[1];
        $day  = $chkDOB[0];
        $year   = $chkDOB[2];
        if($year>80 && $year<=99) $year = "19".$year;
        else $year = "20".$year;        
        if(checkdate($month, $day, $year))
        {
        $student_dobtemp = $day."-".$month."-".$year;
        $student_dob_final =   date("Y-m-d", strtotime($student_dobtemp));
        $string = date("d-m-Y", strtotime($student_dobtemp)); 
        $string_pwd = str_replace('-','', $string);
        $studentpassword = md5($string_pwd);
        
   
    $savedata_PuUploadRegistrationSheet['registration_sheet_roll_no'] = (string) trim($sheetDatas['A']);
    $savedata_PuUploadRegistrationSheet['registration_sheet_reg_no'] = (string) trim($sheetDatas['B']);
    $savedata_PuUploadRegistrationSheet['registration_sheet_name'] = trim($sheetDatas['C']);    
    $savedata_PuUploadRegistrationSheet['registration_sheet_fname'] = trim($sheetDatas['D']);
    $savedata_PuUploadRegistrationSheet['registration_sheet_mname'] = trim($sheetDatas['E']);
    $savedata_PuUploadRegistrationSheet['registration_sheet_dob'] = trim($sheetDatas['F']);
    
    $savedata_PuUploadRegistrationSheet['registration_sheet_batch'] = $session_yr;
    $savedata_PuUploadRegistrationSheet['registration_sheet_dept'] = $deptInfo;
    $savedata_PuUploadRegistrationSheet['registration_sheet_course'] = $beCourse;
    
    $savedata_PuUploadRegistrationSheet['registration_sheet_finalDOB'] = $student_dob_final;
    
    $savedata_PuUploadRegistrationSheet['registration_sheet_password'] = $studentpassword;
    
    $student_JSONDATA[] = $savedata_PuUploadRegistrationSheet;
    
    $model_PuUploadRegistrationSheet = new PuUploadRegistrationSheet();
    $model_PuUploadRegistrationSheet->attributes = $savedata_PuUploadRegistrationSheet; 
    if ($model_PuUploadRegistrationSheet->save())
    {
        
    }
    else
    {
      $transaction->rollback();
      $error = $model_PuUploadRegistrationSheet->getErrors();                    
      $error_html = Yii::$app->Utility->getErrors($error);
      Yii::$app->session->setFlash($key = 'danger', $message = "<strong>$error_html</strong>");   
    return $this->redirect(Yii::$app->homeUrl."workflow/umsupdateregistrationno/index?secureKey=$secureKey&secureHash=$secureHash");      
    }
    }
    else
    {
    $transaction->rollback();
    Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Please check your Date of Birth</strong>');   
    return $this->redirect(Yii::$app->homeUrl."workflow/umsupdateregistrationno/index?secureKey=$secureKey&secureHash=$secureHash");      
    }
    }
    else
    {
    $transaction->rollback();
    Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Please check your Date of Birth field.</strong>');   
    return $this->redirect(Yii::$app->homeUrl."workflow/umsupdateregistrationno/index?secureKey=$secureKey&secureHash=$secureHash");      
    }
    
    }
    else
    {
    Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Please Check Your Excel Upload Sheet Data, Some Fields Missing.</strong>');   
    return $this->redirect(Yii::$app->homeUrl."workflow/umsupdateregistrationno/index?secureKey=$secureKey&secureHash=$secureHash");      
    }
    }
    }
    
}
if($check_DatabaseInsert)
{
$transaction->commit();
$USP_UpdateStudentinfoFromFile = Yii::$app->Utility->USP_UpdateStudentinfoFromFile($session_yr, $beCourse, $deptInfo);
// var_dump($USP_UpdateStudentinfoFromFile); die;
if($USP_UpdateStudentinfoFromFile=="1")
{
Yii::$app->session->setFlash($key = 'success', $message = '<strong>Successfully updated registration number.</strong>');   
$log_JSON = json_encode($student_JSONDATA);
Yii::$app->Utility2->logEventDetail('Workflow','workflow/umsupdateregistrationno/insert','Successfully updated registration number',$log_JSON);
return $this->redirect(Yii::$app->homeUrl."workflow/umsupdateregistrationno/index?secureKey=$secureKey&secureHash=$secureHash");
}
else if($USP_UpdateStudentinfoFromFile=="2")
{
Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Roll Number Alreday Uploaded.</strong>');   
return $this->redirect(Yii::$app->homeUrl."workflow/umsupdateregistrationno/index?secureKey=$secureKey&secureHash=$secureHash");
}
else
{
 Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Unable To Process, Contact Admin.</strong>');   
return $this->redirect(Yii::$app->homeUrl."workflow/umsupdateregistrationno/index?secureKey=$secureKey&secureHash=$secureHash");   
}
}
else
{
Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Excel Upload Sheet Data Is Empty. Please Check.</strong>');   
return $this->redirect(Yii::$app->homeUrl."workflow/umsupdateregistrationno/index?secureKey=$secureKey&secureHash=$secureHash");          
}

}
else
{
Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Please Check Your Excel Upload Sheet Extension OR Sheet Size.</strong>');   
return $this->redirect(Yii::$app->homeUrl."workflow/umsupdateregistrationno/index?secureKey=$secureKey&secureHash=$secureHash");          
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
