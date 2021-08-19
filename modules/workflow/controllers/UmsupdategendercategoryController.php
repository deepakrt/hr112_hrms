<?php

namespace app\modules\workflow\controllers;
use yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\PuUploadGenderCategory;
class UmsupdategendercategoryController extends Controller
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
    public function actionUpdate()
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
        if(isset($_POST['UpdateGenderCategory']) && !empty($_POST['UpdateGenderCategory']) && isset($_FILES['UpdateGenderCategory']) && !empty($_FILES['UpdateGenderCategory']))
        {	
        $UpdateGenderCategory = $_POST['UpdateGenderCategory'];
	$UpdateGenderCategory_File = $_FILES['UpdateGenderCategory'];
        if((isset($UpdateGenderCategory['deptInfo']) && !empty($UpdateGenderCategory['deptInfo'])) && (isset($UpdateGenderCategory['beCourse']) && !empty($UpdateGenderCategory['beCourse'])) && (isset($UpdateGenderCategory['session_yr']) && !empty($UpdateGenderCategory['session_yr'])) && (isset($UpdateGenderCategory_File['name']['fileUpload']) && !empty($UpdateGenderCategory_File['name']['fileUpload'])))
        {
            
        $secureKey = base64_encode($menu_id);
        $secureHash = Yii::$app->Utility->getHashView($menu_id);
        
        $target_dir = UPLOAD_PATH."/";
       
$target_file = $target_dir . basename($UpdateGenderCategory_File["name"]["fileUpload"]);
$image_FileType = pathinfo($target_file,PATHINFO_EXTENSION);

$image_FileSize = $UpdateGenderCategory_File["size"]["fileUpload"];
$chk_Size = UPLOAD_SIZE * '1000';


if($image_FileSize <= $chk_Size && ($image_FileType=="xlsx" || $image_FileType=="xls"))
{
  //echo "<pre>";  print_r($UpdateGenderCategory_File); die;
 $inputFileName = $UpdateGenderCategory_File["tmp_name"]["fileUpload"]; 
 
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
return $this->redirect(Yii::$app->homeUrl."workflow/umsupdategendercategory/index?secureKey=$secureKey&secureHash=$secureHash");   
}
 $objPHPExcel = \PHPExcel_IOFactory::load($inputFileName);
 $objPHPExcel->setActiveSheetIndex(0);
 $worksheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
$connection=   Yii::$app->db;
$transaction = $connection->beginTransaction();
$check_DatabaseInsert = $checkExcelLabel = false;
extract($UpdateGenderCategory);
$student_JSONDATA = array();
foreach($worksheet as $sheetDatas)
    {
    $savedata_PuUploadGenderCategory = array();
    if(empty(trim($sheetDatas['A'])) && empty(trim($sheetDatas['B'])) && empty(trim($sheetDatas['C'])) )
    {
    }
    else
    {
    if(!$checkExcelLabel)
    {
     if($sheetDatas['A']=="Roll No" && $sheetDatas['B']=="Gender" && $sheetDatas['C']=="Category" && $sheetDatas['D']=="Sub Category" )
     {
      $checkExcelLabel = true;  
     }
     else
     {
      Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Please Check Your Excel Upload Sheet Lables.</strong>');   
      return $this->redirect(Yii::$app->homeUrl."workflow/umsupdategendercategory/index?secureKey=$secureKey&secureHash=$secureHash");   
     }
    }
    else
    {
    if(!empty(trim($sheetDatas['A'])) && !empty(trim($sheetDatas['B'])) && !empty(trim($sheetDatas['C'])) )
    {
    $check_DatabaseInsert = true;
    $Roll_No  = trim($sheetDatas['A']);
    $Gender  = trim($sheetDatas['B']);
    $Gender = Yii::$app->Utility->removeMultipleSpaces($Gender);
    if(Yii::$app->Utility->validGender($Gender))
    {
    $Category  = trim($sheetDatas['C']);
    $Category = Yii::$app->Utility->removeMultipleSpaces($Category);
    if($Category_Id = Yii::$app->Utility->validateCategoryByName($Category,1))
    {
    $SubCategory_Id = NULL;
    $SubCategory  = trim($sheetDatas['D']);
    $SubCategory = Yii::$app->Utility->removeMultipleSpaces($SubCategory);
    
    if(!empty($SubCategory))
    {
    if(!($SubCategory_Id = Yii::$app->Utility->validateCategoryByName($SubCategory,2)))
    {
    $transaction->rollback();
    Yii::$app->session->setFlash($key = 'danger', $message = "<strong>Please check your Sub Category field for Roll No ($Roll_No).</strong>");   
    return $this->redirect(Yii::$app->homeUrl."workflow/umsupdategendercategory/index?secureKey=$secureKey&secureHash=$secureHash");   
    }        
    }
    
    $savedata_PuUploadGenderCategory['student_sheet_rollno'] = (string) trim($sheetDatas['A']);
    $savedata_PuUploadGenderCategory['student_sheet_gender'] = $Gender;
    $savedata_PuUploadGenderCategory['student_sheet_category'] = $Category_Id; 
    $savedata_PuUploadGenderCategory['student_sheet_subcategory'] = $SubCategory_Id;
    
    $savedata_PuUploadGenderCategory['student_sheet_batch'] = $session_yr;
    $savedata_PuUploadGenderCategory['student_sheet_dept'] = $deptInfo;
    $savedata_PuUploadGenderCategory['student_sheet_course'] = $beCourse;
    
    
    $student_JSONDATA[] = $savedata_PuUploadGenderCategory;
    
    $model_PuUploadGenderCategory = new PuUploadGenderCategory();
    $model_PuUploadGenderCategory->attributes = $savedata_PuUploadGenderCategory; 
    if ($model_PuUploadGenderCategory->save())
    {
        
    }
    else
    {
      $transaction->rollback();
      $error = $model_PuUploadGenderCategory->getErrors();                    
      $error_html = Yii::$app->Utility->getErrors($error);
      Yii::$app->session->setFlash($key = 'danger', $message = "<strong>$error_html</strong>");   
    return $this->redirect(Yii::$app->homeUrl."workflow/umsupdategendercategory/index?secureKey=$secureKey&secureHash=$secureHash");      
    }
    }
    else
    {
    $transaction->rollback();
    Yii::$app->session->setFlash($key = 'danger', $message = "<strong>Please check your Category field for Roll No ($Roll_No).</strong>");   
    return $this->redirect(Yii::$app->homeUrl."workflow/umsupdategendercategory/index?secureKey=$secureKey&secureHash=$secureHash");      
    }
    }
    else
    {
    $transaction->rollback();
    Yii::$app->session->setFlash($key = 'danger', $message = "<strong>Please check your Gender field for Roll No ($Roll_No).</strong>");   
    return $this->redirect(Yii::$app->homeUrl."workflow/umsupdategendercategory/index?secureKey=$secureKey&secureHash=$secureHash");      
    }
    
    }
    else
    {
    Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Please Check Your Excel Upload Sheet Data, Some Fields Missing.</strong>');   
    return $this->redirect(Yii::$app->homeUrl."workflow/umsupdategendercategory/index?secureKey=$secureKey&secureHash=$secureHash");      
    }
    }
    }
    
}

if($check_DatabaseInsert)
{
$transaction->commit();
//var_dump($check_DatabaseInsert); die;
$USP_UpdateGenderCategoryFromFile = Yii::$app->Utility->USP_UpdateGenderCategoryFromFile($session_yr, $beCourse, $deptInfo);
// var_dump($USP_UpdateStudentinfoFromFile); die;
if($USP_UpdateGenderCategoryFromFile=="1")
{
Yii::$app->session->setFlash($key = 'success', $message = '<strong>Successfully updated Gender and Category.</strong>');   
$log_JSON = json_encode($student_JSONDATA);
Yii::$app->Utility2->logEventDetail('Workflow','workflow/umsupdategendercategory/insert','Successfully updated Gender and Category',$log_JSON);
return $this->redirect(Yii::$app->homeUrl."workflow/umsupdategendercategory/index?secureKey=$secureKey&secureHash=$secureHash");
}
else if($USP_UpdateGenderCategoryFromFile=="2")
{
Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Roll Number Alreday Uploaded.</strong>');   
return $this->redirect(Yii::$app->homeUrl."workflow/umsupdategendercategory/index?secureKey=$secureKey&secureHash=$secureHash");
}
else
{
 Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Unable To Process, Contact Admin.</strong>');   
return $this->redirect(Yii::$app->homeUrl."workflow/umsupdategendercategory/index?secureKey=$secureKey&secureHash=$secureHash");   
}
}
else
{
Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Excel Upload Sheet Data Is Empty. Please Check.</strong>');   
return $this->redirect(Yii::$app->homeUrl."workflow/umsupdategendercategory/index?secureKey=$secureKey&secureHash=$secureHash");          
}

}
else
{
Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Please Check Your Excel Upload Sheet Extension OR Sheet Size.</strong>');   
return $this->redirect(Yii::$app->homeUrl."workflow/umsupdategendercategory/index?secureKey=$secureKey&secureHash=$secureHash");          
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
