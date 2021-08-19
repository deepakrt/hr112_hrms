<?php

namespace app\modules\employee\controllers;
use yii;
use app\models\EmployeeFamilyDetails;
class InformationController extends \yii\web\Controller
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
    
    public function actionIndex(){
        
        $this->layout = '@app/views/layouts/admin_layout.php';
        $info = Yii::$app->utility->get_employees(Yii::$app->user->identity->employee_code);

        $qualification = Yii::$app->utility->get_qualification(Yii::$app->user->identity->role, Yii::$app->user->identity->employee_code, NULL, "Verified,Unverified,Rejected");
        $family_details = Yii::$app->utility->get_family_details(Yii::$app->user->identity->employee_code);
        $experience_details = Yii::$app->utility->get_experience_details(Yii::$app->user->identity->employee_code);
        $training_details = Yii::$app->utility->get_training_details(Yii::$app->user->identity->employee_code);
        $emp_language_details = Yii::$app->utility->get_language_details(Yii::$app->user->identity->employee_code);
        
        return $this->render('index', ['info'=>$info,'qualification'=>$qualification,'family_details'=>$family_details,'experience_details'=>$experience_details,'training_details'=>$training_details,'emp_language_details'=>$emp_language_details]);
    }
    
    //Add Qualification
    public function actionAddqualification()
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        
        if(isset($_POST['Qualification']) AND !empty($_POST['Qualification'] AND isset($_FILES['Qualification']) AND !empty($_FILES['Qualification']))){
            $post = $_POST['Qualification'];
            $urll=Yii::$app->homeUrl."employee/information?securekey=$menuid&tab=qualification";
            //Upload Files 
            $files = $_FILES['Qualification'];
            $fileResult = $this->uploadFile($files['tmp_name']['document'], $files['name']['document']);
            if(empty($fileResult)){
                Yii::$app->getSession()->setFlash('danger', 'Error Found in document upload. Try again or Contact Admin.');
                return $this->redirect($urll);
            }
            $param_e_id =Yii::$app->user->identity->e_id;
            $param_quali_type = $post['quali_type'];
            $grade = preg_replace('/[^A-Za-z +-]/', '', $post['grade']);
            $percentage = preg_replace('/[^0-9-]/', '', $post['percentage']);
            $cgpa = preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['cgpa']);
            $doc_type = preg_replace('/[^A-Za-z-]/', '', $post['doc_type']);
            $documnt = $fileResult;
            
            if($param_quali_type == 'A'){
                $quali_id = Yii::$app->utility->decryptString($post['quali_id']);
                $discipline = preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['discipline']);
                $institute = preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['institute']);
                $uni_b = preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['uni_b']);
                $passed_on = date('Y-m-d', strtotime($post['passed_on']));
                $address = preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['address']);
                
                $result = Yii::$app->hr_utility->hr_add_qualification(null,$param_e_id,$param_quali_type,$quali_id,null,$discipline,$institute,$uni_b,$address, $passed_on,$grade,$percentage,$cgpa,$doc_type,$documnt,"Unverified");
                
                /*
                 * Add Activities Logs
                 */
                $logs['eq_id']=NULL;
                $logs['employee_code']=$param_e_id;
                $logs['quali_type']=$param_quali_type;
                $logs['qualification_id']=$quali_id;
                $logs['other_quali']=NULL;
                $logs['discipline']=$discipline;
                $logs['Institute']=$institute;
                $logs['univ_board']=$uni_b;
                $logs['address']=$address;
                $logs['passed_on']=$passed_on;
                $logs['grade']=$grade;
                $logs['percentage']=$percentage;
                $logs['CGPA']=$cgpa;
                $logs['doc_type']=$doc_type;
                $logs['docs']=$documnt;
                $logs['status']="Unverified";
                $jsonlogs = json_encode($logs);
                if(empty($result)){
                    Yii::$app->utility->activities_logs('Information', 'employee/information/addqualification', NULL, $jsonlogs, "Error Found. Contact Admin.");
                    Yii::$app->getSession()->setFlash('danger', 'Error Found. Contact Admin.');
                    return $this->redirect($urll);
                }else{
                    Yii::$app->utility->activities_logs('Information', 'employee/information/addqualification', NULL, $jsonlogs, "Qualification added successfully.");
                    Yii::$app->getSession()->setFlash('success', 'Qualification added successfully');
                    return $this->redirect($urll);
                }
            }elseif($post['quali_type'] == 'O'){
                $other_quali = preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['other_quali']);
                $otherpassed_on = date('Y-m-d', strtotime($post['otherpassed_on']));
                $result = Yii::$app->hr_utility->hr_add_qualification(null,$param_e_id,$param_quali_type,null,$other_quali,null,null,null,null, $otherpassed_on,$grade,$percentage,$cgpa,$doc_type,$documnt,"Unverified");
                
                //Logs
                $logs['eq_id']=NULL;
                $logs['employee_code']=$param_e_id;
                $logs['quali_type']=$param_quali_type;
                $logs['qualification_id']=NULL;
                $logs['other_quali']=$other_quali;
                $logs['discipline']=NULL;
                $logs['Institute']=NULL;
                $logs['univ_board']=NULL;
                $logs['address']=NULL;
                $logs['passed_on']=$otherpassed_on;
                $logs['grade']=$grade;
                $logs['percentage']=$percentage;
                $logs['CGPA']=$cgpa;
                $logs['doc_type']=$doc_type;
                $logs['docs']=$documnt;
                $logs['status']="Unverified";
                $jsonlogs = json_encode($logs);
                if(empty($result)){
                    Yii::$app->utility->activities_logs('Information', 'employee/information/addqualification', NULL, $jsonlogs, "Error Found. Contact Admin.");
                    Yii::$app->getSession()->setFlash('danger', 'Error Found. Contact Admin.');
                    return $this->redirect($urll);
                }else{
                    Yii::$app->utility->activities_logs('Information', 'employee/information/addqualification', NULL, $jsonlogs, "Qualification added successfully.");
                    Yii::$app->getSession()->setFlash('success', 'Qualification added successfully');
                    return $this->redirect($urll);
                }
            }else{
                Yii::$app->getSession()->setFlash('danger', 'Invalid Qualification Type Found.');
                return $this->redirect($urll);
            }
        }
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('addqualification');
    }
    public function uploadFile($temPth, $Name)
    {
        $createFolder = getcwd().Employee_Documents.Yii::$app->user->identity->e_id;
        if(!file_exists($createFolder)){
            mkdir($createFolder, 0777, true);
        }
        $info = new \SplFileInfo($Name);
        $ext = $info->getExtension();
        //$createFolder = getcwd().Employee_Documents.$createFolder."/";
        $random_number = mt_rand(100000, 999999);
        $newName = $random_number.strtotime(date('Y-m-d H:i:s')).".$ext";
        $finalName = $createFolder."/".$newName;
        $fileUploadedCheck = false;
        if(move_uploaded_file($temPth,$finalName)){
            chmod($finalName, 0777);
            $fileUploadedCheck = true;
        }

        if(!empty($fileUploadedCheck)){
            $returnName = Employee_Documents.Yii::$app->user->identity->e_id."/".$newName;
        }else{
            $returnName = "";
        }
        return $returnName;
    }

    //Add Member
    public function actionAddmember()
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        if(isset($_POST['EmployeeFamilyDetails']) && !empty($_POST['EmployeeFamilyDetails']))
        {
            $document_type=$documnt=$handicap_type=$handicap_percentage="";
            $urll=Yii::$app->homeUrl."employee/information?securekey=$menuid&tab=family";
            
            // echo "<pre>";print_r($documnt); die;
            // echo "<pre>";print_r($_POST); die;
            $post=$_POST['EmployeeFamilyDetails'];
            if(!empty($post['m_name']) && !empty($post['relation_id']) && !empty($post['marital_status']) && !empty($post['m_dob']))
            {
                $empid=Yii::$app->user->identity->e_id;
                $m_name = trim(preg_replace('/[^A-Za-z0-9 ]/', '', $post['m_name']));
                $relation_id=Yii::$app->utility->decryptString($post['relation_id']);
                $marital_status=Yii::$app->utility->decryptString($post['marital_status']);
                $m_dob = trim(date('Y-m-d', strtotime($post['m_dob'])));
                $handicap = trim(preg_replace('/[^A-Za-z]/', '', $post['handicap']));
                if(isset($handicap) && ($handicap=='Y'))
                {
                    $handicap_type=Yii::$app->utility->decryptString($post['handicate_type']);
                    $handicap_percentage = trim(preg_replace('/[^0-9]/', '', $post['handicap_percentage']));
                }
                $monthly_income = trim(preg_replace('/[^0-9]/', '', $post['monthly_income']));
                
                $contact_detail = trim($post['contact_detail']);
                $nominee = trim($post['nominee']);
                
                $address = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['address']));
                $p_address = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['p_address']));


                $document_type=Yii::$app->utility->decryptString($post['document_type']);
                if(isset($document_type) && !empty($document_type))
                {
                    if(isset($_FILES['EmployeeFamilyDetails']) && !empty($_FILES['EmployeeFamilyDetails']))
                    {
                        $files = $_FILES['EmployeeFamilyDetails'];
                        $fileResult = $this->uploadFile($files['tmp_name']['document_path'], $files['name']['document_path']);
                        if(empty($fileResult))
                        {
                            Yii::$app->getSession()->setFlash('danger', 'Error Found in document upload. Try again or Contact Admin.');
                            return $this->redirect($urll);
                        }
                        $documnt = $fileResult;
                    }
                }
            //                echo "<pre>$marital_status>>>>";print_r($document_type); die;

                $param_ef_id=null;
                $createdate=null;
                $result = Yii::$app->hr_utility->hr_add_update_family($param_ef_id,$empid,$m_name,$relation_id,$marital_status,$m_dob,$handicap,$handicap_type,$handicap_percentage,$monthly_income,$contact_detail,$nominee,$address, $p_address,$document_type,$documnt,"Unverified");
                
                /*
                 * Logs
                 */
                $logs['employee_code']=$empid;
                $logs['m_name']=$m_name;
                $logs['relation_id']=$relation_id;
                $logs['marital_status']=$marital_status;
                $logs['m_dob']=$m_dob;
                $logs['handicap']=$handicap;
                $logs['handicate_type']=$handicap_type;
                $logs['handicap_percentage']=$handicap_percentage;
                $logs['monthly_income']=$monthly_income;
                $logs['contact_detail']=$contact_detail;
                $logs['nominee']=$nominee;
                $logs['address']=$address;
                $logs['p_address']=$p_address;
                $logs['document_type']=$document_type;
                $logs['document_path']=$documnt;
                $logs['status']="Unverified";
                $jsonlogs = json_encode($logs);
                if(empty($result))
                {
                    Yii::$app->utility->activities_logs('Information', 'employee/information/addmember', NULL, $jsonlogs, "Error Found. Contact Admin.");
                    Yii::$app->getSession()->setFlash('danger', 'Error Found. Contact Admin.');
                    return $this->redirect($urll);
                }
                else
                {
                     Yii::$app->utility->activities_logs('Information', 'employee/information/addmember', NULL, $jsonlogs, "Family info added successfully.");
                    Yii::$app->getSession()->setFlash('success', 'Family info added successfully');
                    return $this->redirect($urll);
                }
            }
            else
            {
                Yii::$app->getSession()->setFlash('danger', 'Error Found. Contact Admin.');
                return $this->redirect($urll);
            }
        }
        $this->layout = '@app/views/layouts/admin_layout.php';
        $model = new EmployeeFamilyDetails();
        return $this->render('addmember', ['model'=>$model]);
    }
    

    //Add Experience
    public function actionAddexperience()
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        if(isset($_POST['Employeeexperience']) && !empty($_POST['Employeeexperience']))
        {
            $document_type=$documnt=$handicap_type=$handicap_percentage="";
            $urll=Yii::$app->homeUrl."employee/information?securekey=$menuid&tab=experience";
            
            /*echo "<pre>";print_r($_POST); 
            echo "<pre>";print_r($_FILES); 
            die;*/

            $post=$_POST['Employeeexperience'];
            if(!empty($post['e_name']) && !empty($post['organizationType']) && !empty($post['job_title']) && !empty($post['from']) && !empty($post['till']))
            {
                $empid=Yii::$app->user->identity->e_id;
                $e_name = trim(preg_replace('/[^A-Za-z0-9 ]/', '', $post['e_name']));
                
                $organizationType=Yii::$app->utility->decryptString($post['organizationType']);
                // $job_title=Yii::$app->utility->decryptString($post['job_title']);
                
                $organizationType=$post['organizationType'];                
                $job_title=$post['job_title'];

                $from = trim(date('Y-m-d', strtotime($post['from'])));
                $till = trim(date('Y-m-d', strtotime($post['till'])));
                $employer_address = trim($post['employer_address']);
                $job_description = trim($post['job_description']);
                $document_type = trim($post['document_type']);

                
                // $document_type=Yii::$app->utility->decryptString($post['document_type']);

                if(isset($document_type) && !empty($document_type))
                {
                    if(isset($_FILES['Employeeexperience']) && !empty($_FILES['Employeeexperience']))
                    {
                        $files = $_FILES['Employeeexperience'];
                        $fileResult = $this->uploadFile($files['tmp_name']['document_path'], $files['name']['document_path']);
                        if(empty($fileResult))
                        {
                            Yii::$app->getSession()->setFlash('danger', 'Error Found in document upload. Try again or Contact Admin.');
                            return $this->redirect($urll);
                        }
                        $documnt = $fileResult;
                    }
                }
              
                /*echo "<pre>>>>>";print_r($documnt); 
                echo "<pre>>>>";print_r($document_type); 

                die;*/

                $result = Yii::$app->hr_utility->hr_add_update_experience_detail($empid,$e_name,$organizationType,$job_title,$from,$till,$employer_address,$job_description,$document_type,$documnt,"Unverified");
                
                /*
                 * Logs
                 */
                $logs['employee_code']=$empid;
                $logs['e_name']=$e_name;
                $logs['organizationType']=$organizationType;
                $logs['job_title']=$job_title;
                $logs['from']=$from;
                $logs['till']=$till;
                $logs['employer_address']=$employer_address;
                $logs['job_description']=$job_description;
                $logs['document_type']=$document_type;
                $logs['document_path']=$documnt;
                $logs['status']="Unverified";
                $jsonlogs = json_encode($logs);


                if(empty($result))
                {
                    Yii::$app->utility->activities_logs('Information', 'employee/information/addexperience', NULL, $jsonlogs, "Error Found. Contact Admin.");
                    // Yii::$app->getSession()->setFlash('danger', 'Error Found. Contact Admin.');
                    $col_data['msg'] = 'Error Found. Contact Admin.';
                    // $col_data['red_url'] = $this->redirect($urll);
                    $col_data['data_suc'] = 0;

                    echo json_encode($col_data); die();
                }
                else
                {
                    Yii::$app->utility->activities_logs('Information', 'employee/information/addexperience', NULL, $jsonlogs, "Employee experience detail added successfully.");
                    Yii::$app->getSession()->setFlash('success', 'Employee experience detail added successfully');
                    // $col_data['red_url'] = $this->redirect($urll);

                    $col_data['msg'] = 'Employee experience detail added successfully.';
                    $col_data['red_url'] = $this->redirect($urll);
                    $col_data['data_suc'] = 1;

                    echo json_encode($col_data); die();
                }
            }
            else
            {
                // Yii::$app->getSession()->setFlash('danger', 'Error Found. Contact Admin.');
                // $col_data['red_url'] = $this->redirect($urll);

                $col_data['msg'] = 'Error Found. Contact Admin.';
                // $col_data['red_url'] = $this->redirect($urll);
                $col_data['data_suc'] = 0;
                echo json_encode($col_data); die();
            }
        }
        $this->layout = '@app/views/layouts/admin_layout.php';
        $model = new EmployeeFamilyDetails();
        return $this->render('addexperience', ['model'=>$model]);
    }

    //Add Training Details
    public function actionAddtraining_details()
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        if(isset($_POST['Employeetraining_details']) && !empty($_POST['Employeetraining_details']))
        {
            $document_type=$documnt=$handicap_type=$handicap_percentage="";
            $urll=Yii::$app->homeUrl."employee/information?securekey=$menuid&tab=training_det";
            
            /*echo "<pre>";print_r($_POST); 
            echo "<pre>";print_r($_FILES); 
            die;*/

            $post=$_POST['Employeetraining_details'];
            if(!empty($post['course_name']) && !empty($post['institute_name']) && !empty($post['training_attended']) && !empty($post['from']) && !empty($post['to']))
            {
                $empid=Yii::$app->user->identity->e_id;
                $course_name = trim(preg_replace('/[^A-Za-z0-9 ]/', '', $post['course_name']));
                
                // $institute_name=Yii::$app->utility->decryptString($post['institute_name']);
                // $job_title=Yii::$app->utility->decryptString($post['job_title']);
                
                $institute_name=$post['institute_name'];                
                $institute_address = trim($post['institute_address']);
                
                $training_attended=$post['training_attended'];

                $from = trim(date('Y-m-d', strtotime($post['from'])));
                $to = trim(date('Y-m-d', strtotime($post['to'])));
                $description = trim($post['description']);
                $document_type = trim($post['document_type']);

                
                // $document_type=Yii::$app->utility->decryptString($post['document_type']);

                if(isset($document_type) && !empty($document_type))
                {
                    if(isset($_FILES['Employeetraining_details']) && !empty($_FILES['Employeetraining_details']))
                    {
                        $files = $_FILES['Employeetraining_details'];
                        $fileResult = $this->uploadFile($files['tmp_name']['document_path'], $files['name']['document_path']);
                        if(empty($fileResult))
                        {
                            // Yii::$app->getSession()->setFlash('danger', 'Error Found in document upload. Try again or Contact Admin.');
                            // return $this->redirect($urll);

                            $col_data['msg'] = 'Error Found in document upload. Try again or Contact Admin.';
                            // $col_data['red_url'] = $this->redirect($urll);
                            $col_data['data_suc'] = 0;

                            echo json_encode($col_data); die();
                        }
                        $documnt = $fileResult;
                    }
                }
              
                /*echo "<pre>>>>>";print_r($documnt); 
                echo "<pre>>>>";print_r($document_type); 

                die;*/

                $result = Yii::$app->hr_utility->hr_add_update_training_details($empid,$course_name,$institute_name,$institute_address,$training_attended,$from,$to,$description,$document_type,$documnt,"Unverified");
                
                /*
                 * Logs
                 */
                $logs['employee_code']=$empid;
                $logs['course_name']=$course_name;
                $logs['institute_name']=$institute_name;
                $logs['institute_address']=$institute_address;
                $logs['training_attended']=$training_attended;
                $logs['from']=$from;
                $logs['to']=$to;
                $logs['description']=$description;
                $logs['document_type']=$document_type;
                $logs['document_path']=$documnt;
                $logs['status']="Unverified";
                $jsonlogs = json_encode($logs);


                if(empty($result))
                {
                    Yii::$app->utility->activities_logs('Information', 'employee/information/addtraining_details', NULL, $jsonlogs, "Error Found. Contact Admin.");
                    // Yii::$app->getSession()->setFlash('danger', 'Error Found. Contact Admin.');
                    $col_data['msg'] = 'Error Found. Contact Admin.';
                    // $col_data['red_url'] = $this->redirect($urll);
                    $col_data['data_suc'] = 0;

                    echo json_encode($col_data); die();
                }
                else
                {
                    Yii::$app->utility->activities_logs('Information', 'employee/information/addtraining_details', NULL, $jsonlogs, "Employee training detail added successfully.");
                    Yii::$app->getSession()->setFlash('success', 'Employee training detail added successfully');
                    // $col_data['red_url'] = $this->redirect($urll);

                    $col_data['msg'] = 'Employee training detail added successfully.';
                    $col_data['red_url'] = $this->redirect($urll);
                    $col_data['data_suc'] = 1;

                    echo json_encode($col_data); die();
                }
            }
            else
            {
                // Yii::$app->getSession()->setFlash('danger', 'Error Found. Contact Admin.');
                // $col_data['red_url'] = $this->redirect($urll);

                $col_data['msg'] = 'Error Found. Contact Admin.';
                // $col_data['red_url'] = $this->redirect($urll);
                $col_data['data_suc'] = 0;
                echo json_encode($col_data); die();
            }
        }
        $this->layout = '@app/views/layouts/admin_layout.php';
        $model = new EmployeeFamilyDetails();
        return $this->render('addtraining_details', ['model'=>$model]);
    }


    // addlanguageknown
    public function actionAddlanguage_known()
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        if(isset($_POST['Employee_language_code']) && !empty($_POST['Employee_language_code']))
        {
            $document_type=$documnt=$handicap_type=$handicap_percentage="";
            $urll=Yii::$app->homeUrl."employee/information?securekey=$menuid&tab=language_details";
            
            /*echo "<pre>";print_r($_POST); 
            die;*/
            /*
            echo "<pre>";print_r($_FILES); 
            */

            $post=$_POST['Employee_language_code'];
            if(!empty($_POST['employee_code']))
            {
                $empid=Yii::$app->user->identity->e_id;
                $result = array();
                $languageConCat = array();
                foreach($post as $ky=>$lngID)
                {
                    $mother_tongue = 'N';
                    $read = 'N';
                    $write = 'N';
                    $speak = 'N';

                    if(isset($_POST['chk_mt'.$lngID]))
                    {
                        $mother_tongue = $_POST['chk_mt'.$lngID];
                    }

                    if(isset($_POST['chk_wf'.$lngID]))
                    {
                        $write = $_POST['chk_wf'.$lngID];
                    }
                    if(isset($_POST['chk_rf'.$lngID]))
                    {
                        $read = $_POST['chk_rf'.$lngID];
                    }
                    if(isset($_POST['chk_sf'.$lngID]))
                    {
                        $speak = $_POST['chk_sf'.$lngID];
                    }

                    $languageID = $lngID;

                    $languageConCat[$languageID]["mother_tongue"] = $mother_tongue;
                    $languageConCat[$languageID]["read"] = $read;
                    $languageConCat[$languageID]["write"] = $write;
                    $languageConCat[$languageID]["speak"] = $speak;

                    $result[] = Yii::$app->hr_utility->hr_add_update_language_details($empid,$languageID,$mother_tongue,$read,$write,$speak,"Unverified");
                }             
                                
                /*
                 * Logs
                 */
                $logs['employee_code']=$empid;
                $logs['languages']=$languageConCat;
                $logs['status']="Unverified";
                $jsonlogs = json_encode($logs);


                if(empty($result))
                {
                    Yii::$app->utility->activities_logs('Information', 'employee/information/addlanguage_known', NULL, $jsonlogs, "Error Found. Contact Admin.");
                    // Yii::$app->getSession()->setFlash('danger', 'Error Found. Contact Admin.');
                    $col_data['msg'] = 'Error Found. Contact Admin.';
                    // $col_data['red_url'] = $this->redirect($urll);
                    $col_data['data_suc'] = 0;

                    echo json_encode($col_data); die();
                }
                else
                {
                    Yii::$app->utility->activities_logs('Information', 'employee/information/addlanguage_known', NULL, $jsonlogs, "Employee language details added successfully.");
                    Yii::$app->getSession()->setFlash('success', 'Employee language details added successfully');
                    // $col_data['red_url'] = $this->redirect($urll);

                    $col_data['msg'] = 'Employee language details added successfully.';
                    $col_data['red_url'] = $this->redirect($urll);
                    $col_data['data_suc'] = 1;

                    echo json_encode($col_data); die();
                }
            }
            else
            {
                // Yii::$app->getSession()->setFlash('danger', 'Error Found. Contact Admin.');
                // $col_data['red_url'] = $this->redirect($urll);

                $col_data['msg'] = 'Error Found. Contact Admin.';
                // $col_data['red_url'] = $this->redirect($urll);
                $col_data['data_suc'] = 0;
                echo json_encode($col_data); die();
            }
        }

        // get_language
        $language_details = Yii::$app->utility->get_language();
        $withoutLangID = 'Yes';
        $emp_language_details = Yii::$app->utility->get_language_details(Yii::$app->user->identity->employee_code,$withoutLangID);
        $this->layout = '@app/views/layouts/admin_layout.php';
        $model = new EmployeeFamilyDetails();
        return $this->render('addlanguage_details', ['model'=>$model,'language_details'=>$language_details,'emp_language_details'=>$emp_language_details]);
    }


    public function actionService(){
        $this->layout = '@app/views/layouts/admin_layout.php';
        $e_id = Yii::$app->user->identity->e_id;
        $info = Yii::$app->utility->get_employees($e_id);
        return $this->render('service', ['info'=>$info]);
    }
    public function actionAttendance(){
        $this->layout = '@app/views/layouts/admin_layout.php';
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url=Yii::$app->homeUrl."employee/information/attendance?securekey=$menuid";
        $curMonth = date('m');
        $yr=date('Y');
        $attnMonth = "$yr-$curMonth";
        if(isset($_GET['Calender']) AND !empty($_GET['Calender'])){
            
            $month = Yii::$app->utility->decryptString($_GET['Calender']['month']);
            $year = Yii::$app->utility->decryptString($_GET['Calender']['year']);
            if(empty($month) OR empty($year)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid params value found.');
                return $this->redirect($url);
            }
            $attnMonth = "$year-$month";
            $curMonth = $month;
            $yr=$year;
        }
        $i=0;
        //        echo $attnMonth;
        //        $attendance=array();
        
        $attendance= Yii::$app->hr_utility->hr_get_attendance(Yii::$app->user->identity->role,"Month", NULL, Yii::$app->user->identity->e_id, $attnMonth, "Submitted", NULL);
        $newArray= array();
        $tt = count($attendance);
        $chktt = 0;
        $i=0;
        
        $notMarked = "<span style='font-size:10px;color:#3F9E89;'>Not Marked</span>";
        for($d=1; $d<=31; $d++){
            $d = sprintf("%02d", $d);
            if($tt > 0){
                foreach ($attendance as $key => $value) {
                    $date = date('d', strtotime($value['attendance_date']));
                    $isFind = false;
                    if($date == $d){
                        $newArray[$i]['attendancedate']=date('Y-m-d', strtotime($value['attendance_date']));
                        $att = "";
                        $color = "color:red;";
                        if($value['attendance_mark'] == 'P'){
                            $color = "";
                            $att ="<span style='font-size:12px; $color'>Present</span>";
                        }elseif($value['attendance_mark'] == 'A'){
                            $att ="<span style='font-size:12px;$color'>Absent</span>";
                        }elseif($value['attendance_mark'] == 'L'){
                            $att ="<span style='font-size:12px;$color'>On Leave</span>";
                        }elseif($value['attendance_mark'] == 'FHL'){
                            $att ="<span style='font-size:12px;$color'>First Half Leave</span>";
                        }elseif($value['attendance_mark'] == 'SHL'){
                            $att ="<span style='font-size:12px;$color'>Second Half Leave</span>";
                        }
                        $newArray[$i]['day']= $date;
                        $newArray[$i]['status']=$att;
                        $isFind =true;
                        break;
                    }
                }

                if($isFind != true)
                {
                            $newArray[$i]['attendancedate']="$yr-$curMonth-$d";
                            $newArray[$i]['day']= $d;
                            $newArray[$i]['status']=$notMarked;    
                }
                $i++;
            }else{
                $newArray[$i]['attendancedate']="$yr-$curMonth-$d";
                $newArray[$i]['day']= $d;
                $newArray[$i]['status']=$notMarked;   
                $i++;
            }
        }
        $ttttt = "$yr-$curMonth-01";
        $time = strtotime($ttttt);
        $today = date('j', $time);
        $days = array($today => array(null, null,null));
        $pn = array('&laquo;' => date('n', $time) - 1, '&raquo;' => date('n', $time) + 1);
        return $this->render('attendance',[
            'menuid'=>$menuid, 
            'attendance'=>$newArray, 
            'today'=>$today, 
            'days'=>$days, 
            'pn'=>$pn,
            'curMonth'=>$curMonth,
            'yr'=>$yr,
            'ccctime'=>$time
        ]);
    }



    public function actionGetdeptempdropdown()
    {
        $result = array();
        if (!\Yii::$app->user->isGuest) {
        if(isset($_POST['dept_id']) AND !empty($_POST['dept_id'])){
            $dept_id = Yii::$app->utility->decryptString($_POST['dept_id']);
                        //echo $dept_id; die;
            if(empty($dept_id)){
                $result['Status'] = 'FF';
                $result['Res'] = 'Invalid params value found..';
                echo json_encode($result);
                die;
            }
            $allemps = Yii::$app->inventory->get_dept_emp($dept_id); 
            if(empty($allemps)){
                $result['Status']= 'FF';
                $result['Res']= 'No Employee List Found';
                echo json_encode($result); die;
            }
            $html = "<option value=''>Select Employee</option>";
            foreach($allemps as $emp){
                // $employee_code = base64_decode($emp['employee_code']);
                $employee_code = $emp['employee_code'];
                $Super_Admin_Emp_Code = Super_Admin_Emp_Code;
                if($employee_code == Yii::$app->user->identity->e_id){
                    
                }elseif($employee_code == $Super_Admin_Emp_Code){
                }else{
                    
                    // $employee_code = Yii::$app->utility->encryptString($employee_code);
                    $name = $emp['name'];
                    $html .= "<option value='$employee_code'>$name</option>";
                }
            }
            
            $result['Status']= 'SS';
            $result['Res']= $html;
            echo json_encode($result); die;
        }else{
        //            echo "Asf"; die;
            $result['Status']= 'FF';
            $result['Res']= 'Invalid Params Found';
            echo json_encode($result); die;
        }
        }else{
            $result['Status'] = 'FF';
            $result['Res'] = 'Session TimeOut.';
            echo json_encode($result);
            die;
        }
    }

    public function actionGetdepdsgtempdropdown()
    {
        $result = array();
        if (!\Yii::$app->user->isGuest) {
        if((isset($_POST['dept_id']) AND !empty($_POST['dept_id'])) && (isset($_POST['dsgid']) AND !empty($_POST['dsgid'])) ){
            
            // $dept_id = Yii::$app->utility->decryptString($_POST['dept_id']);

            $dept_id = $_POST['dept_id'];
            $dsg_id = $_POST['dsgid'];
                        //echo $dept_id; die;
            if(empty($dept_id)){
                $result['Status'] = 'FF';
                $result['Res'] = 'Invalid params value found..';
                echo json_encode($result);
                die;
            }

            if(empty($dsg_id)){
                $result['Status'] = 'FF';
                $result['Res'] = 'Invalid params value found..';
                echo json_encode($result);
                die;
            }

            $allemps = Yii::$app->inventory->get_dept_dsg_emp($dept_id,$dsg_id); 
            if(empty($allemps)){
                $result['Status']= 'FF';
                $result['Res']= 'No Employee List Found';
                echo json_encode($result); die;
            }
            $html = "<option value=''>Select Employee</option>";
            foreach($allemps as $emp){
                // $employee_code = base64_decode($emp['employee_code']);
                $employee_code = $emp['employee_code'];
                $Super_Admin_Emp_Code = Super_Admin_Emp_Code;
                if($employee_code == Yii::$app->user->identity->e_id){
                    
                }elseif($employee_code == $Super_Admin_Emp_Code){
                }else{
                    
                    // $employee_code = Yii::$app->utility->encryptString($employee_code);
                    $name = $emp['name'];
                    $html .= "<option value='$employee_code'>$name</option>";
                }
            }
            
            $result['Status']= 'SS';
            $result['Res']= $html;
            echo json_encode($result); die;
        }else{
        //            echo "Asf"; die;
            $result['Status']= 'FF';
            $result['Res']= 'Invalid Params Found';
            echo json_encode($result); die;
        }
        }else{
            $result['Status'] = 'FF';
            $result['Res'] = 'Session TimeOut.';
            echo json_encode($result);
            die;
        }
    }

    public function actionGetdsgdropdown()
    {
        $result = array();      
        if(isset($_POST['dept_id']) AND !empty($_POST['dept_id'])){
            
            // $dept_id = Yii::$app->utility->decryptString($_POST['dept_id']);

            $dept_id = $_POST['dept_id'];

            //echo $dept_id; die;

            if(empty($dept_id)){
                $result['Status'] = 'FF';
                $result['Res'] = 'Invalid params value found..';
                echo json_encode($result);
                die;
            }
            
            $desgsData = Yii::$app->utility->get_designation_by_dptID($dept_id);

            /*echo "<pre>"; print_r($desgsData);
            die();*/


            if(empty($desgsData)){
                $result['Status']= 'FF';
                $result['Res']= 'No Designation List Found';
                echo json_encode($result); die;
            }
            $html = "<option value='-1'>Select Designation</option>";
            if(!empty($desgsData))
            {                
                foreach($desgsData as $d)
                {
                   $html .= "<option value='".$d['desg_id']."'>".$d['desg_name']."</option>";
                }
            }
            
            $result['Status']= 'SS';
            $result['Res']= $html;
            echo json_encode($result); die;
        }else{
        //            echo "Asf"; die;
            $result['Status']= 'FF';
            $result['Res']= 'Invalid Params Found';
            echo json_encode($result); die;
        }
        
    }
    
}
