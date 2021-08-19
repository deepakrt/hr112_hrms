<?php
namespace app\modules\admin\controllers;
use app\models\RewardMaster; 
use yii;

class UploadController extends \yii\web\Controller
{
    public function beforeAction($action){
        if (!\Yii::$app->user->isGuest) {
            if(isset($_GET['securekey']) AND !empty($_GET['securekey'])){
                $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
                if(empty($menuid)){ return $this->redirect(Yii::$app->homeUrl); }
                $chkValid = Yii::$app->utility->validate_url($menuid);
                if(empty($chkValid)){ return $this->redirect(Yii::$app->homeUrl); }
                return true;
            }else{ return $this->redirect(Yii::$app->homeUrl); }
        }else{
            return $this->redirect(Yii::$app->homeUrl);
        }
        parent::beforeAction($action);
    }
    
    public function actionIndex()
    {

        if(isset($_FILES) && !empty($_FILES))
        {
            echo "<pre>";
            print_r($_POST);
            echo "</pre>";
            echo "<pre>";
                print_r($_FILES);
            echo "</pre>";

            var_dump(include './PHPExcel/Classes/PHPExcel/IOFactory.php');

            $inputFileName = $_FILES['rendom_file']['tmp_name'];
            //var_dump($inputFileName);
            $objPHPExcel = \PHPExcel_IOFactory::load($inputFileName);
            //var_dump($objPHPExcel);
            $worksheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);;
            echo "<pre>"; print_r($worksheet);
            die;

        }
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);

        // $menuid = '';
        // echo "fgfdg fdg fdgf";
         // die();

        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('upload_rendm_exl', ['menuid'=>$menuid]);
    }

    public function actionUpload()
    {
        $model = new UploadForm();

       /* if (Yii::$app->request->isPost) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if ($model->upload()) {
                // file is uploaded successfully
                return;
            }
        }*/

        return $this->render('upload', ['model' => $model]);
    }

    public function clean_strdata($string) {
       $string = str_replace(' ', '_', $string); // Replaces all spaces with hyphens.
       $string = str_replace('-', '_', $string); // Replaces all spaces with hyphens.

       return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }
    
    public function actionUploaddata()
    {
        /*echo "<pre>";
            print_r($_POST);
        echo "</pre>";
        echo "<pre>";
            print_r($_FILES);
        echo "</pre>";*/

        // die();

        
        include './PHPExcel/Classes/PHPExcel/IOFactory.php';

        $inputFileName = $_FILES['rendom_file']['tmp_name'];
        //var_dump($inputFileName);
        $objPHPExcel = \PHPExcel_IOFactory::load($inputFileName);
        // echo "<pre>"; print_r($objPHPExcel);die('test123');
        $objPHPExcel->setActiveSheetIndex(0);
        echo "<pre>"; print_r($objPHPExcel->getActiveSheet()->toArray(null, true, true, true));die('test');
        $worksheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
        $employee_list = $otherRoles = array();
        $j= $i=0;

        $sheetCount = count($worksheet);


        // echo "====".$sheetCount; // die();

        $headArr = array();

        for ($i = 1; $i <= 1; $i ++) 
        {
            $innercnt = count($worksheet[$i]);
            foreach($worksheet[$i] as $key=>$data)
            {
                if($data != '')
                {
                    $rmvspclchar = $this->clean_strdata($data);
                    $headArr[$key] = strtolower($rmvspclchar).' VARCHAR(200) NOT NULL';
                    $headArrCheck[$key] = "'".strtolower($rmvspclchar)."'";
                }
            }
        } 

        $empArr = array();



        for ($prv = $i; $prv <= $sheetCount; $prv ++) 
        {
            $innercnt = count($worksheet[$prv]);
            
            foreach($headArrCheck as $ky=>$empVal)
            {
                if($worksheet[$prv][$ky] != '')
                {
                    $empArr[$prv][$empVal] = trim($worksheet[$prv][$ky]);
                }
            }
        }

        $connection =   Yii::$app->db;
        $connection->open();

        /*echo "<pre>";
         print_r($empArr);
        die();*/

        $tblColumnNames = implode(',',$headArr);
        $tblColumnNamesChk = implode(',',$headArrCheck);
        $ctDate = date('Ymd_his');
        $tblName = 'random_table_'.$ctDate;

        // $sql1212 = "DROP TABLE ".$tblName; 
        // $command=$connection->createCommand($sql1212); 
        // $command->execute();

        $sql = "CREATE TABLE ".$tblName." (".$tblColumnNames.")"; 

                // die();
        $command=$connection->createCommand($sql); 
        $command->execute();

        $connection->close();

        $empArrRe = array_values($empArr);
        $countempArrRe = count($empArrRe);

        for ($rpv = 0; $rpv < $countempArrRe; $rpv ++) 
        {
            $sql2 = '';
            $headNameconcat = '';
            $empValueConcat = '';
            foreach($headArrCheck as $kye=>$empVal)
            {
                $empvalct = NULL;
                if(isset($empArrRe[$rpv][$empVal]))
                {
                    if($empArrRe[$rpv][$empVal] != '')
                    {
                        $empvalct = $empArrRe[$rpv][$empVal];
                    }
                }

                $headNameconcat .= $empVal.',';
                $empValueConcat .= '"'.$empvalct.'",';
            }

            $headNameconcat = str_replace("'","`", rtrim($headNameconcat, ','));
            $empValueConcat = rtrim($empValueConcat, ',');

            $commandRun = '';
            $connection->open();
            $sql2 = "INSERT INTO ".$tblName." (".$headNameconcat.") VALUES (".$empValueConcat.")";
            $commandRun = $connection->createCommand($sql2); 
            $commandRun->execute();
            $connection->close();
        }

        // $command->execute();
        // Yii::$app->db->createCommand()->batchInsert($tblName, $headArrCheck, $pasArr)->execute();

        
        $arrData['sts'] = '000';
        $arrData['message'] = 'update records.';

        echo json_encode($arrData);   
        die();

    }

    public function bkp()
    {
        // If form is submitted 
        if(isset($_POST['name']) || isset($_POST['email']) || isset($_POST['file']))
        { 
            // Get the submitted form data 
            $name = $_POST['name']; 
            $email = $_POST['email']; 
             
            // Check whether submitted data is not empty 
            if(!empty($name) && !empty($email)){ 
                // Validate email 
                if(filter_var($email, FILTER_VALIDATE_EMAIL) === false){ 
                    $response['message'] = 'Please enter a valid email.'; 
                }else{ 
                    $uploadStatus = 1; 
                     
                    // Upload file 
                    $uploadedFile = ''; 
                    if(!empty($_FILES["file"]["name"])){ 
                         
                        // File path config 
                        $fileName = basename($_FILES["file"]["name"]); 
                        $targetFilePath = $uploadDir . $fileName; 
                        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
                         
                        // Allow certain file formats 
                        /*$allowTypes = array('pdf', 'doc', 'docx', 'jpg', 'png', 'jpeg'); 
                        if(in_array($fileType, $allowTypes)){ */
                            // Upload file to the server 
                            if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){ 
                                $uploadedFile = $fileName; 
                            }else{ 
                                $uploadStatus = 0; 
                                $response['message'] = 'Sorry, there was an error uploading your file.'; 
                            } 
                        /*}else{ 
                            $uploadStatus = 0; 
                            $response['message'] = 'Sorry, only PDF, DOC, JPG, JPEG, & PNG files are allowed to upload.'; 
                        } */
                    } 
                     
                    if($uploadStatus == 1){ 
                        // Include the database config file 
                        include_once 'dbConfig.php'; 
                         
                        // Insert form data in the database 
                        $insert = $db->query("INSERT INTO form_data (name,email,file_name) VALUES ('".$name."','".$email."','".$uploadedFile."')"); 
                         
                        if($insert){ 
                            $response['status'] = 1; 
                            $response['message'] = 'Form data submitted successfully!'; 
                        } 
                    } 
                } 
            }else{ 
                 $response['message'] = 'Please fill all the mandatory fields (name and email).'; 
            } 
        }
    }
}