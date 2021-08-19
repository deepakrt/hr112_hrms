<?php

namespace app\modules\admin\controllers;
use app\models\Employee; 
use yii;
class ManageemployeesController extends \yii\web\Controller
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
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('index', ['menuid'=>$menuid]);
    }
       public function actionGetemp(){ 
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        //$url = Yii::$app->homeUrl."admin/manageemployees/add?securekey=$menuid";
        $emp_code=$_POST['empcode'];
         $info = Yii::$app->utility->get_employees_by_empcode($emp_code);
         if(!empty($info))
         {
           return  1 ;  
         }
         else
         {
             return  2 ; 
         }
        

    }
   
    public function actionAdd(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."admin/manageemployees/add?securekey=$menuid";
        $this->layout = '@app/views/layouts/admin_layout.php';
        
        if(isset($_POST['Employee']) AND !empty($_POST['Employee']))
        {
            $post = $_POST['Employee']; 
            
            /*echo "<pre>"; 
                print_r($_POST); 
            echo "</pre>"; 
            die();*/
            
            $fname =  trim(preg_replace('/[^A-Za-z ]/', '', $post['fname']));
            $lname =  trim(preg_replace('/[^A-Za-z ]/', '', $post['lname']));
            $gender = trim(preg_replace('/[^A-Za-z ]/', '', $post['gender']));
            $contact = trim(preg_replace('/[^0-9]/', '', $post['contact']));
            $dob1 = trim(preg_replace('/[^0-9-]/', '', $post['dob']));
            $dob = date('Y-m-d', strtotime($dob1));
            $emergency_contact = trim(preg_replace('/[^0-9]/', '', $post['emergency_contact']));
            $employment_type =  trim(preg_replace('/[^A-Za-z]/', '', $post['employment_type']));
            
            $joining_date1 =  trim(preg_replace('/[^0-9-]/', '', $post['joining_date']));
            $joining_date = date('Y-m-d', strtotime($joining_date1));
            $address = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['address']));
            $employee_id = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['employee_id']));
            $city =  trim(preg_replace('/[^A-Za-z]/', '', $post['city']));
            $state =  trim(preg_replace('/[^A-Za-z]/', '', $post['state']));
            $zip =  trim(preg_replace('/[^0-9]/', '', $post['zip']));
            $contact1 =  NULL; // trim(preg_replace('/[^0-9]/', '', $post['contact1']));
           
            $p_address = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['p_address']));
            $p_city =  trim(preg_replace('/[^A-Za-z]/', '', $post['p_city']));
            $p_state =  trim(preg_replace('/[^A-Za-z]/', '', $post['p_state']));
            
            $p_zip =  trim(preg_replace('/[^0-9]/', '', $post['p_zip']));
            $contact2 =  NULL; // trim(preg_replace('/[^0-9]/', '', $post['contact2']));
            
            $dept_id = base64_decode($post['dept_id']);
            $dept_id =  trim(preg_replace('/[^0-9]/', '', $dept_id));

            $desg_id =  base64_decode($post['desg_id']);
            $desg_id =  trim(preg_replace('/[^0-9]/', '', $desg_id));
            

            $marital_status =  base64_decode($post['marital_status']);
            $blood_group =  base64_decode($post['blood_group']);
            $authority1 =  $post['authority1']; // base64_decode($post['authority1']);
            $authority2 =  $post['authority2']; // base64_decode($post['authority2']);

            $religion =  trim($post['religion']);

            $category = '';

            if($post['category'] != 'Select Category')
            {
                $category =  trim($post['category']);
            }
            
            $caste =  trim($post['caste']);
            $passport_detail =  trim($post['passport_detail']);

            $emplevel = Yii::$app->utility->decryptString($post['emplevel']);
            
            $pan_number =  trim(preg_replace('/[^A-Za-z0-9]/', '', $post['pan_number']));
            
            if(empty($dept_id) OR empty($desg_id)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected.'); 
                return $this->redirect($url);
            }
            $email = $post['personal_email'];

            if($email != '')
            {
	            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	                Yii::$app->getSession()->setFlash('danger', 'Invalid Email'); 
	                return $this->redirect($url);
	            }            	
            }
            else
            {
            	$email = NULL;
            }
            $emp_signature = $emp_image = null;
            if(isset($_FILES['Employee']['tmp_name']['emp_image']) AND !empty($_FILES['Employee']['tmp_name']['emp_image']) AND isset($_FILES['Employee']['name']['emp_image']) AND !empty($_FILES['Employee']['name']['emp_image'])){
                $tmp_path = $_FILES['Employee']['tmp_name']['emp_image'];
                $name = $_FILES['Employee']['name']['emp_image'];
                $emp_image = $this->uploadFile($tmp_path, $name);
                if(empty($emp_image)){
                    Yii::$app->getSession()->setFlash('danger', 'Employee Image not uploaded, try again or contact admin.'); 
                    return $this->redirect($url);
                }
            }
            if(isset($_FILES['Employee']['tmp_name']['emp_signature']) AND !empty($_FILES['Employee']['tmp_name']['emp_signature']) AND isset($_FILES['Employee']['name']['emp_signature']) AND !empty($_FILES['Employee']['name']['emp_signature'])){
                $tmp_path = $_FILES['Employee']['tmp_name']['emp_signature'];
                $name = $_FILES['Employee']['name']['emp_signature'];
                $emp_signature = $this->uploadFile($tmp_path, $name);
                if(empty($emp_signature)){
                    Yii::$app->getSession()->setFlash('danger', 'Employee signature not uploaded, try again or contact admin.'); 
                    return $this->redirect($url);
                }
            }
            
            //$effected_from = date('Y-m-d', strtotime($post['effected_from']));
            $effected_from = $joining_date;
            $month = date('m', strtotime($effected_from));
            $yr = date('Y', strtotime($effected_from));;
            if($month >= 3){
                $yrss = $yr+1;
                $financial_year = $yr."-".$yrss;
            }else{
                $yrss = $yr-1;
                $financial_year = $yrss."-".$yr;
            }
            $grade_pay_scale = NULL; // trim(preg_replace('/[^0-9-]/', '', $post['grade_pay_scale']));
            $basic_cons_pay = trim(preg_replace('/[^0-9-]/', '', $post['basic_cons_pay']));
            $Default_Password = Default_Password;
            $password = \md5($Default_Password);
            
            $result = Yii::$app->utility->add_employee($employee_id, $email, $password,$dept_id, $desg_id, $fname, $lname, $gender, $dob, $contact, $emergency_contact, $address, $city, $state, $zip, $contact1, $p_address, $p_city, $p_state, "India", $p_zip, $contact2, $joining_date, $employment_type, $marital_status, $authority1,$authority2, $effected_from, $financial_year,$grade_pay_scale, $emplevel, $basic_cons_pay, $blood_group, $emp_image, $emp_signature, $pan_number,$religion,$caste,$passport_detail,$category);
 

         
            /*
             * Logs 
             */
            $logs['employee_id'] = $employee_id;
            $logs['email'] = $email;
            $logs['dept_id'] = $dept_id;
            $logs['desg_id'] = $desg_id;
            $logs['fname'] = $fname;
            $logs['lname'] = $lname;
            $logs['gender'] = $gender;
            $logs['dob'] = $dob;
            $logs['contact'] = $contact;
            $logs['emergency_contact'] = $emergency_contact;
            $logs['correspondence_address'] = "$address, $city, $state - $zip, India";
            $logs['correspondence_contact'] = $contact1;
            $logs['permanent_address'] = "$p_address, $p_city, $p_state - $p_zip, India";
            $logs['permanent_contact'] = $contact2;
            $logs['pan_number'] = $pan_number;
            $logs['joining_date'] = $joining_date;
            $logs['employment_type'] = $employment_type;
            $logs['marital_status'] = $marital_status;
            $logs['authority1'] = $authority1;
            $logs['authority2'] = $authority2;
            $logs['effected_from'] = $effected_from;
            $logs['financial_year'] = $financial_year;
            $logs['grade_pay_scale'] = $grade_pay_scale;
            $logs['emplevel'] = $emplevel;
            $logs['basic_cons_pay'] = $basic_cons_pay;
            $logs['blood_group'] = $blood_group;
            $logs['emp_image'] = $emp_image;
            $logs['emp_signature'] = $emp_signature;
            $jsonlogs = json_encode($logs);
            
            if($result == 1){
                Yii::$app->utility->activities_logs("Master Data", NULL, $employee_id, $jsonlogs, "Employee Add Successfully.");
                
                //Add Leaves Details 
                $getLeaveChart = Yii::$app->hr_utility->hr_get_leaves_chart();

                // echo "<pre>"; print_r($getLeaveChart);
                 // die();

                // echo 
                $curYr = date('Y');
                $curMonth = date('m');
                if(!empty($getLeaveChart)){
                    $LeaveEntry = array();
                    $i=0;
                    
                    if($curMonth > 6){
                        $session_type = 'SHY';
                    }else{
                        $session_type = "FHY";
                    }
                    foreach($getLeaveChart as $leave){


                        /*echo "<pre>"; print_r($leave);

                        die();     */


                        if($employment_type == $leave['emp_type'] AND $curYr == $leave['year']){
                             
                            if($employment_type == $leave['emp_type']){
                                if($leave['leave_for'] == $gender){
                                    if($leave['session_type'] == $session_type OR $leave['session_type'] == 'Y'){
                                        $LeaveEntry[$i]['leave_type'] = $leave['leave_type'];
                                        $LeaveEntry[$i]['leave_count'] = $leave['leave_count'];
                                        $LeaveEntry[$i]['label'] = $leave['label'];
                                        $LeaveEntry[$i]['session_type'] = $leave['session_type'];
                                        $LeaveEntry[$i]['session_year'] = $leave['year'];
                                        $LeaveEntry[$i]['leave_chart_id'] = $leave['leave_chart_id'];
                                    }
                                }elseif($leave['leave_for'] == "A"){

                                    if(($leave['session_type'] == $session_type) OR ($leave['session_type'] == 'Y'))
                                    {

                                        // echo "<pre>================="; print_r($leave['leave_type']); die();
                                        
                                        $LeaveEntry[$i]['leave_type'] = $leave['leave_type'];
                                        $LeaveEntry[$i]['leave_count'] = $leave['leave_count'];
                                        $LeaveEntry[$i]['label'] = $leave['label'];
                                        $LeaveEntry[$i]['session_type'] = $leave['session_type'];
                                        $LeaveEntry[$i]['session_year'] = $leave['year'];
                                        $LeaveEntry[$i]['leave_chart_id'] = $leave['lc_id'];
                                        $LeaveEntry[$i]['emp_type'] = $leave['emp_type'];
                                    }
                                }
                            }
                        }
                        $i++;
                    }
                               // echo "<pre>";print_r($LeaveEntry); die;


                    if(!empty($LeaveEntry)){
                        foreach($LeaveEntry as $L){
                            $leave_type = $L['leave_type'];
                            $leave_count = $L['leave_count'];
                            $result = Yii::$app->hr_utility->hr_add_update_leaves_detail_chart(NULL, $L['session_year'], $L['session_type'], $L['leave_type'], $L['leave_count'], '0', $L['leave_count'], "Leaves Assigned By HR", $employee_id, $L['emp_type'],$L['leave_chart_id']);

                            Yii::$app->hr_utility->hr_add_leave_card_details($L['leave_type'], "Accrual", $joining_date, NULL, $L['leave_count'], $L['leave_count'], "New Joining", Yii::$app->user->identity->e_id, $employee_id, "Approved");
                            
                            /*
                             * Logs
                             */
                            $logs['entry_type'] = "Accrual";
                            $logs['employee_id'] = $employee_id;
                            $logs['leave_chart_id'] = $L['leave_chart_id'];
                            $logs['session_year'] = $L['session_year'];
                            $logs['session_type'] = $L['session_type'];
                            $logs['leave_type'] = $L['leave_type'];
                            $logs['total_leave'] = $L['leave_count'];
                            $logs['from'] = $joining_date;
                            $logs['remarks'] = "New Joining";
                            $jsonlogs = json_encode($logs);
                            
                            Yii::$app->utility->activities_logs("Master Data", NULL, $employee_id, $jsonlogs, "Leaves Assigned to Employee.");
                        }
                    }
                    
                }
                //Add Entitlement

                if($basic_cons_pay == '')
                {
                	$basic_cons_pay = 0;
                }
                $CurFnYr = Yii::$app->finance->getCurrentFY();
                Yii::$app->finance->fn_add_medical_entitlement(NULL, $employee_id, $CurFnYr, $basic_cons_pay, "0");
                
                $logs['employee_code'] = $employee_id;
                $logs['session_year'] = $CurFnYr;
                $logs['yearly_entitlement'] = $basic_cons_pay;
                $jsonlogs = json_encode($logs);            
                Yii::$app->utility->activities_logs("Master Data", NULL, $employee_id, $jsonlogs, "Medical Entitlement Assigned to Employee.");
                            
                Yii::$app->getSession()->setFlash('success', 'Employee added successfully.');
                return $this->redirect($url);
            }elseif($result == 3){
                
                Yii::$app->utility->activities_logs("Master Data", NULL, $employee_id, $jsonlogs, "Employee / email already exits.");
                
                Yii::$app->getSession()->setFlash('danger', 'Employee / email already exits.');
                return $this->redirect($url);
            }else{
                Yii::$app->utility->activities_logs("Master Data", NULL, $employee_id, $jsonlogs, "Employee cannot added. Contact Admin.");
                Yii::$app->getSession()->setFlash('danger', 'Employee cannot added. Contact Admin');
                return $this->redirect($url);
            }
            //echo "<pre>";print_r($result); die;
        }
        $model = new Employee();
        return $this->render('add', ['model'=>$model, 'menuid'=>$menuid]);
    }
        
    public function actionGetdeptemp() {
        if(isset($_GET['deptid']) AND !empty($_GET['deptid'])){
            $deptid = base64_decode($_GET['deptid']);
            if(!is_numeric($deptid)){
                $result['Status'] = 'FF';
                $result['Res'] = 'Invalid Department ID';
                echo json_encode($result); die;
            }
            
            // echo '-----'.$deptid; die();

            $res = Yii::$app->utility->getDeptEmp($deptid);
            if(empty($res)){
                $result['Status'] = 'FF';
                $result['Res'] = 'Reporting Authority / HOD list not found';
                echo json_encode($result); die;
            }
            $list = "";
            foreach($res as $re){
                $list = $list."<option value='".$re['employee_code']."'>".$re['name']."</option>";
            }
            $result['Status'] = 'SS';
            $result['Res'] = $list;
            echo json_encode($result); die;
        }
    }
    
    public function uploadFile($temPth, $Name){
        $info = new \SplFileInfo($Name);
        $ext = $info->getExtension();
        $Employees_Photo_Sign = Employees_Photo_Sign;
        $createFolder = getcwd().$Employees_Photo_Sign;
        $random_number = mt_rand(100000, 999999);
        $newName = $random_number.strtotime(date('Y-m-d H:i:s')).".$ext";
        $finalName = $createFolder.$newName;
        $fileUploadedCheck = false;
        if(move_uploaded_file($temPth,$finalName)){
            chmod($finalName, 0777);
            $fileUploadedCheck = true;
        }

        if(!empty($fileUploadedCheck)){
            $returnName = Employees_Photo_Sign.$newName;
        }else{
            $returnName = "";
        }
        return $returnName;
    }
	
    /*
    * View Employee
    */
    public function actionViewemployee(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        if(isset($_GET['securekey']) AND !empty($_GET['securekey']) AND isset($_GET['empid']) AND !empty($_GET['empid'])){
            $securekey = Yii::$app->utility->decryptString($_GET['securekey']);
            $e_id = Yii::$app->utility->decryptString($_GET['empid']);
            if(empty($securekey) OR empty($e_id)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected');
                return $this->redirect(Yii::$app->homeUrl);
            }
            $info = Yii::$app->utility->get_employees($e_id);
            if(empty($info)){
                Yii::$app->getSession()->setFlash('danger', 'No Record found');
                return $this->redirect(Yii::$app->homeUrl."admin/manageemployees?securekey=".$menuid);
            }
            $qualification = Yii::$app->utility->get_qualification('3', $e_id, NULL, NULL);
            $family_details = Yii::$app->utility->get_family_details($e_id);  
            $employee_leaves = Yii::$app->utility->get_employee_leaves($e_id);
            $this->layout = '@app/views/layouts/admin_layout.php';
            return $this->render('viewemployee', ['info'=>$info,'qualification'=>$qualification,'family_details'=>$family_details,'employee_leaves'=>$employee_leaves]);
        }else{
            return $this->redirect(Yii::$app->homeUrl."admin/manageemployees?securekey=".$menuid);
        }
    }
    
     /*
    * verify Employee documents
    */
    public function actionVerifydocs(){
        if(isset($_GET['key']) AND !empty($_GET['key'])){
            $e_id = base64_decode($_GET['key']);
            $eq_id = base64_decode($_GET['type']);
            if($_GET['status']==0){$status='Verified';}else{$status='Unverified';}
            $info = Yii::$app->utility->verify_qualification($eq_id,$e_id,$status);
        } 
        return $this->redirect(Yii::$app->homeUrl."admin/manageemployees/viewemployee?key=".$_GET['key']."&tab=qualification");
         
    }
    
      /*
    * verify Employee family member
    */
    public function actionVerify_fmember(){
        if(isset($_GET['key']) AND !empty($_GET['key'])){
            $e_id = base64_decode($_GET['key']);
            $ef_id = base64_decode($_GET['type']);
            if($_GET['status']==0){$status='Verified';}else{$status='Unverified';}
            $info = Yii::$app->utility->verify_family_member($ef_id,$e_id,$status);
        } 
        return $this->redirect(Yii::$app->homeUrl."admin/manageemployees/viewemployee?key=".$_GET['key']."&tab=family");
         
    }
    
    /*
    * View of Update Employee
    */
    public function actionUpdateemployee()
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        //        echo "<pre>";print_r($_GET);die;
        if(isset($_GET['securekey']) AND !empty($_GET['securekey']) AND isset($_GET['empid']) AND !empty($_GET['empid']))
        {
            $securekey = Yii::$app->utility->decryptString($_GET['securekey']);
            $e_id = Yii::$app->utility->decryptString($_GET['empid']);
            
            if(empty($securekey) OR empty($e_id)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected');
                return $this->redirect(Yii::$app->homeUrl);
            }
            
            $info = Yii::$app->utility->get_employees($e_id);
                       // echo "<pre>"; print_r($info); die;
            if(empty($info)){
                Yii::$app->getSession()->setFlash('danger', 'No Record Found');
                $menuid = Yii::$app->utility->encryptString($securekey);
                $url = Yii::$app->homeUrl."admin/manageemployees?securekey=$menuid";
                return $this->redirect($url);
            }
            //            echo "<pre>";print_r($info); die;
            $model = new Employee();
            $model->e_id = $info['e_id'];
            $model->employee_code = $info['employee_code'];
            $model->personal_email = $info['email_id'];
            $model->fname = $info['fname'];
            $model->lname = $info['lname'];
            $model->name_hindi = $info['name_hindi'];
            $model->gender = $info['gender'];
            $model->dob = date('d-m-Y', strtotime($info['dob']));
            $model->contact = $info['phone'];
            $model->emergency_contact = $info['emergency_phone'];
            $model->address = $info['address'];
            $model->city = $info['city'];
            $model->state = $info['state'];
            $model->zip = $info['zip'];
            $model->contact1 = $info['contact'];
            $model->p_address = $info['p_address'];
            $model->pan_number = $info['pan_number'];
            $model->p_city = $info['p_city'];
            $model->p_state = $info['p_state'];
            $model->p_zip = $info['p_zip'];
            $model->contact2 = $info['p_contact'];
            $model->joining_date = date('d-m-Y', strtotime($info['joining_date']));
            $model->employment_type = $info['employment_type'];
            $model->marital_status = base64_encode($info['marital_status']);
            $model->blood_group = base64_encode($info['blood_group']);
            $model->is_active = $info['is_active'];
            $model->emp_image = $info['emp_image'];
            $model->emp_signature = $info['emp_signature'];
            $model->religion = $info['religion'];
            $model->caste = $info['caste'];
            $model->passport_detail = $info['passport_detail'];
            $model->category_id = $info['category_id'];

            $model->dept_id = base64_encode($info['dept_id']);
            $model->desg_id = base64_encode($info['desg_id']);
            $model->authority1 = base64_encode($info['authority1']);
            $model->authority2 = base64_encode($info['authority2']);
            $model->created_date = date('d-m-Y H:i:s', strtotime($info['created_date']));
            
            $auth_emps1 = array();
            $auth_emps2 = array();

            /*if(!empty($info['dept_id']))
            {
                $auth_emps1 = Yii::$app->utility->get_dept_emp($info['dept_id']);
                $auth_emps2 = $auth_emps1; // Yii::$app->utility->get_dept_emp($info['dept_id']);
            }*/
            
            $this->layout = '@app/views/layouts/admin_layout.php';
            return $this->render('updateemployee', ['model'=>$model, 'auth_emps1'=>$auth_emps1, 'auth_emps2'=>$auth_emps2]);
        }else{
            return $this->redirect(Yii::$app->homeUrl."admin/manageemployees?securekey=$menuid");
        }
    }
    
    /*
     * Update Employee
     */
    
    public function actionUpdate2()
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."admin/manageemployees/?securekey=$menuid";
        if(isset($_POST['Employee']) AND !empty($_POST['Employee'])){
            //$url = Yii::$app->homeUrl."admin/manageemployees?securekey=$menuid";

            // echo "<pre>"; print_r($_POST);

           //  die();

            $post = $_POST['Employee'];
            $employee_code = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['employee_code']));
            $fname =  trim(preg_replace('/[^A-Za-z ]/', '', $post['fname']));
            $lname =  trim(preg_replace('/[^A-Za-z ]/', '', $post['lname']));
            $name_hindi =  trim($post['name_hindi']);
            $gender = trim(preg_replace('/[^A-Za-z ]/', '', $post['gender']));
            $contact = trim(preg_replace('/[^0-9]/', '', $post['contact']));
            $dob1 = trim(preg_replace('/[^0-9-]/', '', $post['dob']));
            $dob = date('Y-m-d', strtotime($dob1));
            $emergency_contact = trim(preg_replace('/[^0-9]/', '', $post['emergency_contact']));
            $marital_status = base64_decode($post['marital_status']);
            $marital_status =  trim(preg_replace('/[^A-Za-z]/', '', $marital_status));
            $blood_group = base64_decode($post['blood_group']);
            $blood_group =  trim(preg_replace('/[^A-Za-z + -]/', '', $blood_group));

            $address = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['address']));           
            $city =  trim(preg_replace('/[^A-Za-z]/', '', $post['city']));
            $state =  trim(preg_replace('/[^A-Za-z]/', '', $post['state']));
            $zip =  trim(preg_replace('/[^0-9]/', '', $post['zip']));
            // $contact1 =  trim(preg_replace('/[^0-9]/', '', $post['contact1']));
            $contact1 =  NULL;

            $p_address = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['p_address']));
            $p_city =  trim(preg_replace('/[^A-Za-z]/', '', $post['p_city']));
            $p_state =  trim(preg_replace('/[^A-Za-z]/', '', $post['p_state']));
            $p_zip =  trim(preg_replace('/[^0-9]/', '', $post['p_zip']));
            // $contact2 =  trim(preg_replace('/[^0-9]/', '', $post['contact2']));
            $contact2 =  NULL;


            $e_id = Yii::$app->utility->decryptString($post['e_id']);
            $emp_image = Yii::$app->utility->decryptString($post['old_image']);
            $emp_signature = Yii::$app->utility->decryptString($post['old_sign']);
            $email = $post['personal_email'];


            $religion =  trim($post['religion']);
            $category =  trim(base64_decode($post['category']));
            $caste =  trim($post['caste']);
            $passport_detail =  trim($post['passport_detail']);
            
            $pan_number =  trim(preg_replace('/[^A-Za-z0-9]/', '', $post['pan_number']));

            if(isset($_FILES['Employee']['tmp_name']['emp_image']) AND !empty($_FILES['Employee']['tmp_name']['emp_image']) AND isset($_FILES['Employee']['name']['emp_image']) AND !empty($_FILES['Employee']['name']['emp_image'])){
                
                if($emp_image != '')
                {
                    $oldimg = getcwd().$emp_image;
                    if(!unlink($oldimg)){
                        Yii::$app->getSession()->setFlash('danger', 'Failed to delete old image. Contact Admin');
                        $this->redirect($url);
                        return false;
                    }
                }
                $tmp_path = $_FILES['Employee']['tmp_name']['emp_image'];
                $name = $_FILES['Employee']['name']['emp_image'];
                $emp_image = $this->uploadFile($tmp_path, $name);
                
                if(empty($emp_image)){
                    Yii::$app->getSession()->setFlash('danger', 'Employee Image not uploaded, try again or contact admin.'); 
                    $this->redirect($url);
                }
            }
            if(isset($_FILES['Employee']['tmp_name']['emp_signature']) AND !empty($_FILES['Employee']['tmp_name']['emp_signature']) AND isset($_FILES['Employee']['name']['emp_signature']) AND !empty($_FILES['Employee']['name']['emp_signature'])){
                
                if($emp_signature != '')
                {
                    $oldsign = getcwd().$emp_signature;
                    if(!unlink($oldsign)){
                        Yii::$app->getSession()->setFlash('danger', 'Failed to delete Old signature. Contact Admin');
                        $this->redirect($url);
                        return false;
                    }
                }
                $tmp_path = $_FILES['Employee']['tmp_name']['emp_signature'];
                $name = $_FILES['Employee']['name']['emp_signature'];
                $emp_signature = $this->uploadFile($tmp_path, $name);
                
                if(empty($emp_signature)){
                    Yii::$app->getSession()->setFlash('danger', 'Employee signature not uploaded, try again or contact admin.'); 
                    $this->redirect($url);
                }
            }
            
            //            die("asaaa");
            
            $result = Yii::$app->utility->update_employee($employee_code,$email,$fname, $lname, $name_hindi, $gender, $dob, $contact, $emergency_contact, $address, $city, $state, $zip, $contact1, $p_address, $p_city, $p_state, $p_zip, $contact2, $pan_number, $marital_status, $blood_group, $emp_image, $emp_signature,$religion,$category,$caste,$passport_detail);
            
            // die($result);
            if($result == 1){
                Yii::$app->getSession()->setFlash('success', 'Employee updated successfully.');
                //echo "<pre>";print_r($url);die;
                return $this->redirect(Yii::$app->homeUrl."admin/manageemployees?securekey=".$menuid); 
                //return $this->redirect($url);
            }else{
                Yii::$app->getSession()->setFlash('danger', 'Employee not added / updated. Contact Admin');
                return $this->redirect($url);
            }
            
        }
    }

    public function actionUpdate()
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."admin/manageemployees/?securekey=$menuid";
        if(isset($_POST['Employee']) AND !empty($_POST['Employee'])){
            //$url = Yii::$app->homeUrl."admin/manageemployees?securekey=$menuid";

            // echo "<pre>"; print_r($_POST);

           //  die();

            $post = $_POST['Employee'];
            $employee_code = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['employee_code']));
            $fname =  trim(preg_replace('/[^A-Za-z ]/', '', $post['fname']));
            $lname =  trim(preg_replace('/[^A-Za-z ]/', '', $post['lname']));
            $name_hindi =  trim($post['name_hindi']);
            $gender = trim(preg_replace('/[^A-Za-z ]/', '', $post['gender']));
            $contact = trim(preg_replace('/[^0-9]/', '', $post['contact']));
            $dob1 = trim(preg_replace('/[^0-9-]/', '', $post['dob']));
            $dob = date('Y-m-d', strtotime($dob1));
            $emergency_contact = trim(preg_replace('/[^0-9]/', '', $post['emergency_contact']));
            $marital_status = base64_decode($post['marital_status']);
            $marital_status =  trim(preg_replace('/[^A-Za-z]/', '', $marital_status));
            $blood_group = base64_decode($post['blood_group']);
            $blood_group =  trim(preg_replace('/[^A-Za-z + -]/', '', $blood_group));

            $address = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['address']));           
            $city =  trim(preg_replace('/[^A-Za-z]/', '', $post['city']));
            $state =  trim(preg_replace('/[^A-Za-z]/', '', $post['state']));
            $zip =  trim(preg_replace('/[^0-9]/', '', $post['zip']));
            // $contact1 =  trim(preg_replace('/[^0-9]/', '', $post['contact1']));
            $contact1 =  NULL;

            $p_address = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['p_address']));
            $p_city =  trim(preg_replace('/[^A-Za-z]/', '', $post['p_city']));
            $p_state =  trim(preg_replace('/[^A-Za-z]/', '', $post['p_state']));
            $p_zip =  trim(preg_replace('/[^0-9]/', '', $post['p_zip']));
            // $contact2 =  trim(preg_replace('/[^0-9]/', '', $post['contact2']));
            $contact2 =  NULL;


            $e_id = Yii::$app->utility->decryptString($post['e_id']);
            $emp_image = Yii::$app->utility->decryptString($post['old_image']);
            $emp_signature = Yii::$app->utility->decryptString($post['old_sign']);
            $email = $post['personal_email'];


            $religion =  trim($post['religion']);
            $category =  trim(base64_decode($post['category']));
            $caste =  trim($post['caste']);
            $passport_detail =  trim($post['passport_detail']);
            
            $pan_number =  trim(preg_replace('/[^A-Za-z0-9]/', '', $post['pan_number']));

            if(isset($_FILES['Employee']['tmp_name']['emp_image']) AND !empty($_FILES['Employee']['tmp_name']['emp_image']) AND isset($_FILES['Employee']['name']['emp_image']) AND !empty($_FILES['Employee']['name']['emp_image'])){
                
                if($emp_image != '')
                {
                    $oldimg = getcwd().$emp_image;
                    if(!unlink($oldimg)){
                        Yii::$app->getSession()->setFlash('danger', 'Failed to delete old image. Contact Admin');
                        // $this->redirect($url);
                        // return false;

                        $col_data['msg'] = 'Failed to delete old image. Contact Admin';
                        // $col_data['red_url'] = $this->redirect($urll);
                        $col_data['data_suc'] = 0;

                        echo json_encode($col_data); die();
                    }
                }
                $tmp_path = $_FILES['Employee']['tmp_name']['emp_image'];
                $name = $_FILES['Employee']['name']['emp_image'];
                $emp_image = $this->uploadFile($tmp_path, $name);
                
                if(empty($emp_image)){
                    Yii::$app->getSession()->setFlash('danger', 'Employee Image not uploaded, try again or contact admin.'); 
                    // $this->redirect($url);

                    $col_data['msg'] = 'Employee Image not uploaded, try again or contact admin.';
                    // $col_data['red_url'] = $this->redirect($urll);
                    $col_data['data_suc'] = 0;

                    echo json_encode($col_data); die();
                }
            }
            if(isset($_FILES['Employee']['tmp_name']['emp_signature']) AND !empty($_FILES['Employee']['tmp_name']['emp_signature']) AND isset($_FILES['Employee']['name']['emp_signature']) AND !empty($_FILES['Employee']['name']['emp_signature'])){
                
                if($emp_signature != '')
                {
                    $oldsign = getcwd().$emp_signature;
                    if(!unlink($oldsign)){
                        Yii::$app->getSession()->setFlash('danger', 'Failed to delete Old signature. Contact Admin');
                        // $this->redirect($url);
                        // return false;

                        $col_data['msg'] = 'Failed to delete Old signature. Contact Admin.';
                        // $col_data['red_url'] = $this->redirect($urll);
                        $col_data['data_suc'] = 0;

                        echo json_encode($col_data); die();
                    }
                }
                $tmp_path = $_FILES['Employee']['tmp_name']['emp_signature'];
                $name = $_FILES['Employee']['name']['emp_signature'];
                $emp_signature = $this->uploadFile($tmp_path, $name);
                
                if(empty($emp_signature)){
                    // Yii::$app->getSession()->setFlash('danger', 'Employee signature not uploaded, try again or contact admin.'); 
                    // $this->redirect($url);

                    $col_data['msg'] = 'Employee signature not uploaded, try again or contact admin.';
                    // $col_data['red_url'] = $this->redirect($urll);
                    $col_data['data_suc'] = 0;

                    echo json_encode($col_data); die();
                }
            }
            
            //            die("asaaa");
            
            $result = Yii::$app->utility->update_employee($employee_code,$email,$fname, $lname, $name_hindi, $gender, $dob, $contact, $emergency_contact, $address, $city, $state, $zip, $contact1, $p_address, $p_city, $p_state, $p_zip, $contact2, $pan_number, $marital_status, $blood_group, $emp_image, $emp_signature,$religion,$category,$caste,$passport_detail);
            
            // die($result);
            if($result == 1){
                // Yii::$app->getSession()->setFlash('success', 'Employee updated successfully.');
                //echo "<pre>";print_r($url);die;
                // return $this->redirect(Yii::$app->homeUrl."admin/manageemployees?securekey=".$menuid); 
                //return $this->redirect($url);


                // Yii::$app->utility->activities_logs('Information', 'employee/information/addexperience', NULL, $jsonlogs, "Error Found. Contact Admin.");
                Yii::$app->getSession()->setFlash('success', 'Employee updated successfully.');
                $col_data['msg'] = 'Employee updated successfully.';
                // $col_data['red_url'] = $this->redirect($urll);
                $col_data['data_suc'] = 1;

                echo json_encode($col_data); die();

            }else{
                // Yii::$app->getSession()->setFlash('danger', 'Employee not added / updated. Contact Admin');
                // return $this->redirect($url);

                $col_data['msg'] = 'Employee not added / updated. Contact Admin.';
                // $col_data['red_url'] = $this->redirect($urll);
                $col_data['data_suc'] = 0;

                echo json_encode($col_data); die();
            }
            
        }
    }



    public function actionGetemppless_by_department()
    {
        $result = array();
        if (!\Yii::$app->user->isGuest) {
        if(isset($_POST['dept_id']) AND !empty($_POST['dept_id'])){
            // $dept_id = Yii::$app->utility->decryptString($_POST['dept_id']);
            $dept_id = $_POST['dept_id'];

            $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
            $menuid = Yii::$app->utility->encryptString($menuid);

                        //echo $dept_id; die;
            if(empty($dept_id)){
                $result['Status'] = 'FF';
                $result['Res'] = 'Invalid params value found..';
                echo json_encode($result);
                die;
            }
            $lists = Yii::$app->inventory->get_dept_emp($dept_id); 

            // ECHO "<PRE>"; PRINT_R($lists); DIE();

            /*if(empty($lists)){
                $result['Status']= 'FF';
                $result['Res']= 'No Employee List Found';
                echo json_encode($result); die;
            }*/

            // employees_details_department_wise

            $collectData['menuid'] = $menuid;
            $collectData['lists'] = $lists;



            $html = $this->renderPartial('employees_details_department_wise', $collectData);
            $concat = '';

            $result['render_data'] = $html;
            
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

}
