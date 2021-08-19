<?php

namespace app\modules\admin\controllers;
use app\models\RewardMaster; 
use yii;
class RewardController extends \yii\web\Controller
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
    public function actionAdd(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."admin/manageemployees/add?securekey=$menuid";
        $this->layout = '@app/views/layouts/admin_layout.php';
         if(isset($_POST['RewardMaster']) AND !empty($_POST['RewardMaster'])){

            $post = $_POST['RewardMaster'];
            $dept_name = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['name']));
            $dept_desc = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['description']));
            $reward_type_id = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['reward_type_id']));
            $reward_sub_cat = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['reward_sub_cat']));
            $isActive = trim(preg_replace('/[^A-Za-z]/', '', $post['is_active']));
            if(!empty($dept_name) AND !empty($dept_desc) AND !empty($isActive) AND !empty($reward_type_id) AND !empty($reward_sub_cat)){
                
                $created_by = Yii::$app->user->identity->e_id;
                $result = Yii::$app->utility->add_update_reward(null, $dept_name,$dept_desc,$isActive,$reward_type_id,$reward_sub_cat,$created_by);
                if($result == '1'){
                    Yii::$app->getSession()->setFlash('success', 'Reward added successfully');
                    return $this->redirect(Yii::$app->homeUrl."admin/reward?securekey=".$menuid);
                }
            }else{
                Yii::$app->getSession()->setFlash('danger', 'Invalid / Empty params found.');
                return $this->redirect(Yii::$app->homeUrl."admin/reward?securekey=".$menuid);
            }
        }
        $category= Yii::$app->utility->get_reward_category();
        $model = new RewardMaster();
        return $this->render('add', ['model'=>$model, 'menuid'=>$menuid,'category'=>$category]);
    }
    public function actionUpdatereward(){
        
        //die('herer');
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        if(isset($_GET['rewardid']) AND !empty($_GET['rewardid'])){
            $id = base64_decode($_GET['rewardid']);
            $info = Yii::$app->utility->get_rewards($id);
            if(empty($info)){
                Yii::$app->getSession()->setFlash('danger', 'No Record found');
                return $this->redirect(Yii::$app->homeUrl."admin/managedepartment?securekey=".$menuid);
            }
            
            if(isset($_POST['RewardMaster']) AND !empty($_POST['RewardMaster'])){
                $post = $_POST['Department'];
                $dept_id = Yii::$app->utility->decryptString($post['dept_id']);
                $dept_name = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['dept_name']));
                $dept_desc = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['dept_desc']));
                $isActive = trim(preg_replace('/[^A-Za-z]/', '', $post['is_active']));
                
                if(!empty($dept_id) AND !empty($dept_name) AND !empty($dept_desc) AND !empty($isActive)){
                    $result = Yii::$app->utility->add_update_dept($dept_id, $dept_name,$dept_desc,$isActive);
                    if($result == '2'){
                        Yii::$app->getSession()->setFlash('success', 'Department updated successfully');
                        return $this->redirect(Yii::$app->homeUrl."admin/managedepartment?securekey=".$menuid);
                    }
                }else{
                    Yii::$app->getSession()->setFlash('danger', 'Invalid / Empty params found.');
                    return $this->redirect(Yii::$app->homeUrl."admin/managedepartment?securekey=".$menuid);
                }
                
            }
             $category= Yii::$app->utility->get_reward_category();
            $model = new RewardMaster();
            $model->id = $info['id'];
            $model->name = $info['name'];
            $model->description = $info['description'];
            $model->reward_type_id = $info['reward_type_id'];
            $model->reward_sub_cat = $info['reward_sub_cat'];
            $model->is_active = $info['is_active'];
            $this->layout = '@app/views/layouts/admin_layout.php';
            
            return $this->render('updatereward', ['model'=>$model, 'menuid'=>$menuid,'category'=>$category]);
        }else{
            return $this->redirect(Yii::$app->homeUrl."managedepartment?securekey=".$menuid);
        }
    }
    
    
    public function actionGetdeptemp() {
        if(isset($_GET['deptid']) AND !empty($_GET['deptid'])){
            $deptid = base64_decode($_GET['deptid']);
            if(!is_numeric($deptid)){
                $result['Status'] = 'FF';
                $result['Res'] = 'Invalid Department ID';
                echo json_encode($result); die;
            }
            
            $res = Yii::$app->utility->get_dept_emp($deptid);
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
    public function actionViewreward(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        if(isset($_GET['securekey']) AND !empty($_GET['securekey']) AND isset($_GET['rewardid']) AND !empty($_GET['rewardid'])){
            $securekey = Yii::$app->utility->decryptString($_GET['securekey']);
            $e_id = base64_decode($_GET['rewardid']);
            
            if(empty($securekey) OR empty($e_id)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected');
                return $this->redirect(Yii::$app->homeUrl);
            }
           // die('sdsf');
            $info = Yii::$app->utility->get_rewards($e_id);
            
            if(empty($info)){
                Yii::$app->getSession()->setFlash('danger', 'No Record found');
                return $this->redirect(Yii::$app->homeUrl."admin/reward?securekey=".$menuid);
            }
           
            $this->layout = '@app/views/layouts/admin_layout.php';
            return $this->render('viewreward', ['info'=>$info]);
        }else{
            die('hererere');
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
    public function actionUpdateemployee(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
//        echo "<pre>";print_r($_GET);die;
        if(isset($_GET['securekey']) AND !empty($_GET['securekey']) AND isset($_GET['empid']) AND !empty($_GET['empid'])){
            $securekey = Yii::$app->utility->decryptString($_GET['securekey']);
            $e_id = Yii::$app->utility->decryptString($_GET['empid']);
            
            if(empty($securekey) OR empty($e_id)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected');
                return $this->redirect(Yii::$app->homeUrl);
            }
            
            $info = Yii::$app->utility->get_employees($e_id);
//            echo "<pre>"; print_r($info); die;
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
            $model->dept_id = base64_encode($info['dept_id']);
            $model->desg_id = base64_encode($info['desg_id']);
            $model->authority1 = base64_encode($info['authority1']);
            $model->authority2 = base64_encode($info['authority2']);
            $model->created_date = date('d-m-Y H:i:s', strtotime($info['created_date']));
            
            $auth_emps1 = Yii::$app->utility->get_dept_emp($info['dept_id']);
            $auth_emps2=Yii::$app->utility->get_dept_emp($info['dept_id']);
            
            $this->layout = '@app/views/layouts/admin_layout.php';
            return $this->render('updateemployee', ['model'=>$model, 'auth_emps1'=>$auth_emps1, 'auth_emps2'=>$auth_emps2]);
        }else{
            return $this->redirect(Yii::$app->homeUrl."admin/manageemployees?securekey=$menuid");
        }
    }
    
    /*
     * Update Employee
     */
    
    public function actionUpdate(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        if(isset($_POST['Employee']) AND !empty($_POST['Employee'])){
            $url = Yii::$app->homeUrl."admin/manageemployees?securekey=$menuid";
            $post = $_POST['Employee'];
            $employee_code = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['employee_code']));
            $fname =  trim(preg_replace('/[^A-Za-z ]/', '', $post['fname']));
            $lname =  trim(preg_replace('/[^A-Za-z ]/', '', $post['lname']));
            $gender = trim(preg_replace('/[^A-Za-z ]/', '', $post['gender']));
            $contact = trim(preg_replace('/[^0-9]/', '', $post['contact']));
            $dob1 = trim(preg_replace('/[^0-9-]/', '', $post['dob']));
            $dob = date('Y-m-d', strtotime($dob1));
            $emergency_contact = trim(preg_replace('/[^0-9]/', '', $post['emergency_contact']));
            $employment_type =  trim(preg_replace('/[^A-Za-z]/', '', $post['employment_type']));
            $marital_status = base64_decode($post['marital_status']);
            $marital_status =  trim(preg_replace('/[^A-Za-z]/', '', $marital_status));
            $blood_group = base64_decode($post['blood_group']);
            $blood_group =  trim(preg_replace('/[^A-Za-z + -]/', '', $blood_group));
            $joining_date1 =  trim(preg_replace('/[^0-9-]/', '', $post['joining_date']));
            $joining_date = date('Y-m-d', strtotime($joining_date1));
            $address = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['address']));
            
            $city =  trim(preg_replace('/[^A-Za-z]/', '', $post['city']));
            $state =  trim(preg_replace('/[^A-Za-z]/', '', $post['state']));
            $zip =  trim(preg_replace('/[^0-9]/', '', $post['zip']));
            $contact1 =  trim(preg_replace('/[^0-9]/', '', $post['contact1']));
            $p_address = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['p_address']));
            $p_city =  trim(preg_replace('/[^A-Za-z]/', '', $post['p_city']));
            $p_state =  trim(preg_replace('/[^A-Za-z]/', '', $post['p_state']));
            $p_zip =  trim(preg_replace('/[^0-9]/', '', $post['p_zip']));
            $contact2 =  trim(preg_replace('/[^0-9]/', '', $post['contact2']));
            $dept_id =  trim(preg_replace('/[^0-9]/', '', $post['dept_id']));
            $desg_id =  trim(preg_replace('/[^0-9]/', '', $post['desg_id']));
            $authority1 = base64_decode($post['authority1']);
            $authority1 =  trim(preg_replace('/[^0-9]/', '', $authority1));
            $authority2 = base64_decode($post['authority2']);
            $authority2 =  trim(preg_replace('/[^0-9]/', '', $authority2));
            //echo "<pre>";print_r($authority2);die;
            $e_id = Yii::$app->utility->decryptString($post['e_id']);
            $emp_image = Yii::$app->utility->decryptString($post['old_image']);
            $emp_signature = Yii::$app->utility->decryptString($post['old_sign']);
            $email = Yii::$app->utility->decryptString($post['old_email']);
            if(isset($_FILES['Employee']['tmp_name']['emp_image']) AND !empty($_FILES['Employee']['tmp_name']['emp_image']) AND isset($_FILES['Employee']['name']['emp_image']) AND !empty($_FILES['Employee']['name']['emp_image'])){
                $oldimg = getcwd().$emp_image;
                if(!unlink($oldimg)){
                    Yii::$app->getSession()->setFlash('danger', 'Failed to delete old image. Contact Admin');
                    $this->redirect($url);
                    return false;
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
                $oldsign = getcwd().$emp_signature;
                if(!unlink($oldsign)){
                    Yii::$app->getSession()->setFlash('danger', 'Failed to delete Old signature. Contact Admin');
                    $this->redirect($url);
                    return false;
                }
                $tmp_path = $_FILES['Employee']['tmp_name']['emp_signature'];
                $name = $_FILES['Employee']['name']['emp_signature'];
                $emp_signature = $this->uploadFile($tmp_path, $name);
                
                if(empty($emp_signature)){
                    Yii::$app->getSession()->setFlash('danger', 'Employee signature not uploaded, try again or contact admin.'); 
                    $this->redirect($url);
                }
            }
            
            $password = $role_id = null;
//            echo $blood_group;
//            die("asaaa");
            
            $result = Yii::$app->utility->add_update_employee($e_id,$employee_code,$email,$password,$role_id,$dept_id, $desg_id,$fname, $lname, $gender, $dob, $contact, $emergency_contact, $address, $city, $state, $zip, $contact1, $p_address, $p_city, $p_state, "India", $p_zip, $contact2, $joining_date, $employment_type, $marital_status, $authority1,$authority2,$blood_group, $emp_image, $emp_signature);
            
//            die($result);
            if($result == 2){
                Yii::$app->getSession()->setFlash('success', 'Employee updated successfully.');
                return $this->redirect($url);
            }else{
                Yii::$app->getSession()->setFlash('danger', 'Employee not added / updated. Contact Admin');
                return $this->redirect($url);
            }
            
        }
    }
    
    public function actionGet_subcat_code() {
        //echo "<pre>";print_r($_POST);die;
        if (!isset($_POST['cat_id']) || empty($_POST['cat_id'])) {
            die('0');
        }
        $cat_id = $_POST['cat_id'];
        $subcat = $_POST['subcat'];
        
        $res = Yii::$app->utility->get_cat_item($cat_id);
       // echo "<pre>";print_r($res);die;
        $html = '<option value="">-- Select --</option>';
        foreach ($res as $r) {
            if($r['id']==$subcat){ $sel = 'selected';}else{ $sel ='';}
            
            $html .= '<option alt="' . $r['name'] . '" label="' . $r['name'] . '" value="' . $r['id'] . '" '.$sel.'>' . $r['name'] . '</option>';
        }

            echo $html;
       
        die;
    }


}
