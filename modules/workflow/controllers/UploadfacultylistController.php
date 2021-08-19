<?php

namespace app\modules\workflow\controllers;
use yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\PuUploadFacultySheet;
class UploadfacultylistController extends Controller
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
        if(isset($_POST['uploadfaculty']) && !empty($_POST['uploadfaculty']) && isset($_FILES['uploadfaculty']) && !empty($_FILES['uploadfaculty']))
        {	
        $uploadfaculty = $_POST['uploadfaculty'];
	$uploadfaculty_File = $_FILES['uploadfaculty'];
        if((isset($uploadfaculty['deptInfo']) && !empty($uploadfaculty['deptInfo'])) && (isset($uploadfaculty_File['name']['fileUpload']) && !empty($uploadfaculty_File['name']['fileUpload'])))
        {
//        echo "<pre>";print_r($_POST);
//        print_r($_FILES);
//        die;    
        $secureKey = base64_encode($menu_id);
        $secureHash = Yii::$app->Utility->getHashView($menu_id);
        
        $target_dir = UPLOAD_PATH."/";
       
$target_file = $target_dir . basename($uploadfaculty_File["name"]["fileUpload"]);
$image_FileType = pathinfo($target_file,PATHINFO_EXTENSION);

$image_FileSize = $uploadfaculty_File["size"]["fileUpload"];
$chk_Size = UPLOAD_SIZE * '1000';


if($image_FileSize <= $chk_Size && ($image_FileType=="xlsx" || $image_FileType=="xls"))
{
  //echo "<pre>";  print_r($uploadfaculty_File); die;
 $inputFileName = $uploadfaculty_File["tmp_name"]["fileUpload"]; 
 
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
return $this->redirect(Yii::$app->homeUrl."workflow/uploadfacultylist/index?secureKey=$secureKey&secureHash=$secureHash");   
}
 $objPHPExcel = \PHPExcel_IOFactory::load($inputFileName);
 $objPHPExcel->setActiveSheetIndex(0);
 $worksheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
$connection=   Yii::$app->db;
$transaction = $connection->beginTransaction();
$check_DatabaseInsert = $checkExcelLabel = false;
extract($uploadfaculty);
//echo "<pre>";print_r($uploadfaculty); die;
$faculty_JSONDATA = array();

foreach($worksheet as $sheetDatas)
    {
    $savedata_PuUploadFacultySheet = array();
    if(empty(trim($sheetDatas['A'])) && empty(trim($sheetDatas['B'])) && empty(trim($sheetDatas['C'])) && empty(trim($sheetDatas['D']))  && empty(trim($sheetDatas['E'])) )
    {
    }
    else
    {
    if(!$checkExcelLabel)
    {
     if($sheetDatas['A']=="NAME" && $sheetDatas['B']=="EMAIL" && $sheetDatas['C']=="EMPCODE" && $sheetDatas['D']=="MOBILE" && $sheetDatas['E']=="DESIGNATION" )    
     {
      $checkExcelLabel = true;  
     }
     else
     {
      Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Please Check Your Excel Upload Sheet Lables.</strong>');   
      return $this->redirect(Yii::$app->homeUrl."workflow/uploadfacultylist/index?secureKey=$secureKey&secureHash=$secureHash");   
     }
    }
    else
    {
    if(!empty(trim($sheetDatas['A'])) && !empty(trim($sheetDatas['B'])) && !empty(trim($sheetDatas['C'])) && !empty(trim($sheetDatas['D']))  && !empty(trim($sheetDatas['E'])) )
    {
    $check_DatabaseInsert = true;
    $savedata_PuUploadFacultySheet['faculty_sheet_name'] = (string) $sheetDatas['A'];
    $savedata_PuUploadFacultySheet['faculty_sheet_email'] = (string) $sheetDatas['B'];
    $savedata_PuUploadFacultySheet['faculty_sheet_empcode'] = (string) $sheetDatas['C'];
    $savedata_PuUploadFacultySheet['faculty_sheet_mobile'] = (string) $sheetDatas['D'];
    $savedata_PuUploadFacultySheet['faculty_sheet_designation'] = (string) $sheetDatas['E'];        
    $savedata_PuUploadFacultySheet['faculty_sheet_dept'] = $deptInfo;
    $savedata_PuUploadFacultySheet['faculty_sheet_password'] = md5(trim($sheetDatas['D']));
    
    $faculty_JSONDATA[] = $savedata_PuUploadFacultySheet;
    $model_PuUploadFacultySheet = new PuUploadFacultySheet();
    $model_PuUploadFacultySheet->attributes = $savedata_PuUploadFacultySheet; 
    if ($model_PuUploadFacultySheet->save())
    {
        
    }
    else
    {
      $transaction->rollback();
      $error = $model_PuUploadFacultySheet->getErrors();                    
      $error_html = Yii::$app->Utility->getErrors($error);
      Yii::$app->session->setFlash($key = 'danger', $message = "<strong>$error_html</strong>");   
    return $this->redirect(Yii::$app->homeUrl."workflow/uploadfacultylist/index?secureKey=$secureKey&secureHash=$secureHash");      
    }
    
    }
    else
    {
    Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Please Check Your Excel Upload Sheet Data, Some Fields Missing.</strong>');   
    return $this->redirect(Yii::$app->homeUrl."workflow/uploadfacultylist/index?secureKey=$secureKey&secureHash=$secureHash");      
    }
    }
    }
    
}
if($check_DatabaseInsert)
{
$transaction->commit();
$USP_InsertfacultyFromFile = Yii::$app->Utility->USP_InsertfacultyFromFile();
//var_dump($USP_InsertfacultyFromFile); die;
if($USP_InsertfacultyFromFile=="1")
{
Yii::$app->session->setFlash($key = 'success', $message = '<strong>Successfully Uploaded Sheet.</strong>');   
$log_JSON = json_encode($faculty_JSONDATA);
Yii::$app->Utility2->logEventDetail('Workflow','workflow/uploadfacultylist/insert','Successfully Uploaded Faculty Sheet',$log_JSON);
return $this->redirect(Yii::$app->homeUrl."workflow/uploadfacultylist/index?secureKey=$secureKey&secureHash=$secureHash");
}
else if($USP_InsertfacultyFromFile=="2")
{
Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Faculty Alreday Uploaded.</strong>');   
return $this->redirect(Yii::$app->homeUrl."workflow/uploadfacultylist/index?secureKey=$secureKey&secureHash=$secureHash");
}
else
{
 Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Unable To Process, Contact Admin.</strong>');   
return $this->redirect(Yii::$app->homeUrl."workflow/uploadfacultylist/index?secureKey=$secureKey&secureHash=$secureHash");   
}
}
else
{
Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Excel Upload Sheet Data Is Empty. Please Check.</strong>');   
return $this->redirect(Yii::$app->homeUrl."workflow/uploadfacultylist/index?secureKey=$secureKey&secureHash=$secureHash");          
}

}
else
{
Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Please Check Your Excel Upload Sheet Extension OR Sheet Size.</strong>');   
return $this->redirect(Yii::$app->homeUrl."workflow/uploadfacultylist/index?secureKey=$secureKey&secureHash=$secureHash");          
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
