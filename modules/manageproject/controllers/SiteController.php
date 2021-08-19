<?php

namespace app\modules\manageproject\controllers;

use yii\web\Controller;
use Yii;
use app\models\ProjectList;
use app\modules\manageproject\models\Ordermaster;

class SiteController extends Controller
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
        //$projects = Yii::$app->projects->pr_get_projects(NULL);
        $this->layout = '@app/views/layouts/admin_layout.php';        
        
        $this->view->params['roleasgn'] = Yii::$app->projectcls->GetRole();        
        $role = Yii::$app->user->identity->role;        
        
        
        return $this->render('index', ['menuid'=>$menuid]);
    }
    
    public function actionProjectdetail()
    {
        
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $projects = Yii::$app->projects->pr_get_projects(NULL);
        $this->layout = '@app/views/layouts/admin_layout.php';        
        
        $this->view->params['roleasgn'] = Yii::$app->projectcls->GetRole();        
        $role = Yii::$app->user->identity->role;        
        
        if(Yii::$app->user->id==NULL)
            return $this->render('index');
        
        Yii::$app->pmis_Csuserlog->getUserlog('site/projectdetail', Yii::$app->session->getId(), Yii::$app->user->id);
        
        $model = new Ordermaster();        
        
        $this->view->params['project'] = Yii::$app->projectcls->AllProjects();
        return $this->render('projectdetail', [
            'menuid'=>$menuid,
            'model' => $model]);
    }
    
    public function actionDashboard()
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $projects = Yii::$app->projects->pr_get_projects(NULL);
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('dash_index', ['menuid'=>$menuid, 'projects'=>$projects]);
    }
    
    public function actionDel_project_cat(){
		$project_id = trim(preg_replace('/[^0-9]/', '', $_POST['id']));
 		$added_by = Yii::$app->user->identity->e_id;
 		$result = Yii::$app->projects->del_project_cat($project_id,$added_by);
		echo 1;
		 
		die;
	}
	
    public function actionAdd_project_cat(){
		//echo "<pre>";print_r($_POST); die;
			$start_date = date('Y-m-d', strtotime($_POST['start_date']));
			$end_date 	= date('Y-m-d', strtotime($_POST['end_date']));
			$pc_cat 	= trim(preg_replace('/[^0-9]/', '', $_POST['pc_cat']));
			//echo $amount = trim(preg_replace('/[^0-9]/', '', $_POST['amount']));die;
			$added_by = Yii::$app->user->identity->e_id;
			$project_id = Yii::$app->utility->decryptString($_POST['project_id']);
			$result = Yii::$app->projects->add_update_pur_fund($project_id, $added_by, $pc_cat, $start_date, $end_date, $_POST['amount']);
			if($result == 'DUPLICATE'){
                 echo  "Invalid Request";
            }else{
                 echo $result;
            }
			die;
 	}
	
    public function actionAddnewproject(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."manageproject/projects/addnewproject?securekey=$menuid";
		$model = new ProjectList();
        if(isset($_POST['ProjectList']) AND !empty($_POST['ProjectList'])){
            $approval_doc = NULL;
            // echo "<pre>";print_r($_POST); die;
            
            $post = $_POST['ProjectList'];
            
            $project_name = trim(preg_replace('/[^A-Za-z ]/', '', $post['project_name']));
            $short_name = trim(preg_replace('/[^A-Za-z ]/', '', $post['short_name']));
            $project_type = trim(preg_replace('/[^A-Za-z ]/', '', $post['project_type']));
            
            if($project_type == 'Business' OR $project_type == 'Funded' OR $project_type == 'Mission'){
            }else{
                Yii::$app->getSession()->setFlash('danger', 'Invalid Project Type.');
                return $this->redirect($url);
            }
            $description = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['description']));
            $address = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['address']));
            $contact_person = trim(preg_replace('/[^0-9]/', '', $post['contact_person']));
           // $contact_no = trim(preg_replace('/[^0-9]/', '', $post['contact_no']));
           // $alternate_contact_no = trim(preg_replace('/[^0-9]/', '', $post['alternate_contact_no']));
            $project_cost = trim(preg_replace('/[^0-9]/', '', $post['project_cost']));
           // $num_working_days = trim(preg_replace('/[^0-9]/', '', $post['num_working_days']));
            
            $start_date = date('Y-m-d', strtotime($post['start_date']));
            $end_date = date('Y-m-d', strtotime($post['end_date']));
            
            if($start_date == '1970-01-01' OR $end_date == '1970-01-01'){
                Yii::$app->getSession()->setFlash('danger', 'Invalid Project Start / End Date.');
                return $this->redirect($url);
            }
//            if(strtotime($post['end_date']) >= strtotime($post['start_date'])){
//                Yii::$app->getSession()->setFlash('danger', 'Invalid Project Start / End Date.');
//                return $this->redirect($url);
//            }
            
//            $duration_month = trim(preg_replace('/[^0-9]/', '', $post['duration_month']));
            //$num_manpower = trim(preg_replace('/[^0-9]/', '', $post['num_manpower']));
            $manager_dept = trim(preg_replace('/[^0-9]/', '', $post['manager_dept']));
            //$technology_used = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['technology_used']));
            
//            $manager_emp_id = Yii::$app->utility->decryptString($post['manager_emp_id']);
//            
//            if(empty($manager_emp_id)){
//                Yii::$app->getSession()->setFlash('danger', 'Invalid Project Manager ID.');
//                return $this->redirect($url);
//            }
//            $chkemp = Yii::$app->utility->get_employees($manager_emp_id);
//            if(empty($chkemp)){
//                Yii::$app->getSession()->setFlash('danger', 'Invalid Project Manager ID.');
//                return $this->redirect($url);
//            }
            
            /* if(isset($_FILES['ProjectList']['tmp_name']['approval_doc']) AND !empty($_FILES['ProjectList']['tmp_name']['approval_doc'])){
                $chkPdf = Yii::$app->utility->validatePdfFile($_FILES['ProjectList']['tmp_name']['approval_doc']);
                if(empty($chkPdf)){
                    Yii::$app->getSession()->setFlash('danger', 'Invalid PDF File. Upload Valid File.');
                    return $this->redirect($url);
                }
                
                $approval_doc = $this->uploadFile($_FILES['ProjectList']['tmp_name']['approval_doc'], $_FILES['ProjectList']['name']['approval_doc'], $project_name);
                if(empty($approval_doc)){
                    Yii::$app->getSession()->setFlash('danger', 'File not uploaded, try again.');
                    return $this->redirect($url);
                }
            } */
            $contact_no = NULL;
            $alternate_contact_no = NULL;
            $num_working_days = NULL;
            $approval_doc = NULL;
            $num_manpower = NULL;
            $technology_used = NULL;
            $duration_month = NULL;
            $result = Yii::$app->projects->pr_add_update_project_detail("I", NULL, $project_name, $short_name, $project_type, $description, $address, $contact_person, $contact_no, $alternate_contact_no, $project_cost, $start_date, $end_date, $num_working_days, $duration_month, $num_manpower, $technology_used, $manager_dept, $approval_doc, "Started", 'Y');
            
            if($result > '0'){
                $class = "success";
                $msg = "Project Added Successfully";
				if($post['enterpcb']==1){
					$msg = "Project Added Successfully, Please add Project Cost Breakdown below.";
				}
            }else{
                $class = "danger";
                $msg = "Project didn't Added. Contact Admin.";
            }
            /*
             * Logs
             */
            $logs['action_type']="I";
            $logs['project_id']=$result;
            $logs['project_name']=$project_name;
            $logs['short_name']=$short_name;
            $logs['description']=$description;
            $logs['address']=$address;
            $logs['contact_person']=$contact_person;
            $logs['contact_no']=$contact_no;
            $logs['alternate_contact_no']=$alternate_contact_no;
            $logs['project_cost']=$project_cost;
            $logs['start_date']=$start_date;
            $logs['end_date']=$end_date;
            $logs['num_working_days']=$num_working_days;
            $logs['duration_month']=$duration_month;
            $logs['num_manpower']=$num_manpower;
            $logs['technology_used']=$technology_used;
            $logs['manager_dept']=$manager_dept;
//            $logs['manager_emp_id']=$manager_emp_id;
            $logs['approval_doc']=$approval_doc;
            $logs['result']=$result;
            $jsonlogs = json_encode($logs);
            
            Yii::$app->utility->activities_logs("ProjectManagement", NULL, NULL, $jsonlogs, $msg);
            Yii::$app->getSession()->setFlash($class, $msg);
			
            if($post['enterpcb']==1){
			$projects_id =$result;
			$projects_id = Yii::$app->utility->encryptString($projects_id);			
			   $url = Yii::$app->homeUrl."manageproject/projects/addnewproject?securekey=$menuid&key=$projects_id";
 			return $this->redirect($url);
			}else{
				return $this->redirect($url);
			}
            
 //            echo "$approval_doc<pre>";print_r($_POST); 
//            die;
        }elseif(isset($_GET['key']) && !empty($_GET['key'])){
  			$projects_id = Yii::$app->utility->decryptString($_GET['key']);	 
			if(empty($projects_id)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid params value found.');
				$url = Yii::$app->homeUrl."manageproject/projects?securekey=$menuid";
                return $this->redirect($url);
            }
            $pro = Yii::$app->projects->pr_get_projects($projects_id);
            if(empty($pro)){
				$url = Yii::$app->homeUrl."manageproject/projects?securekey=$menuid";
                Yii::$app->getSession()->setFlash('danger', 'Project Details Not Found.');
                return $this->redirect($url);
            }			
			 
            extract($pro);
            $model = new ProjectList();
            $model->project_id = $project_id;
            $model->project_name = $project_name;
            $model->short_name = $short_name;
            $model->project_type = $project_type;
            $model->description = $description;
            $model->address = $address;
            $model->contact_person = $contact_person;
            $model->project_cost = $project_cost;
            $model->start_date = date('d-m-Y', strtotime($start_date));
            $model->end_date = date('d-m-Y', strtotime($end_date));
            $model->manager_dept = $manager_dept;
            $model->approval_doc = $approval_doc;
            $model->last_updated_on = $last_updated_on;
            $model->created_on = $created_on;
            $model->status = $status;
            $model->is_active = $is_active;
		}
        
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('addnewproject', ['menuid'=>$menuid, 'model'=>$model]);
    }
    
    public function actionUpdateproject(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."manageproject/projects?securekey=$menuid";
        if(isset($_POST['ProjectList']) AND !empty($_POST['ProjectList'])){
            $post = $_POST['ProjectList'];

            $project_name = trim(preg_replace('/[^A-Za-z ]/', '', $post['project_name']));
            $short_name = trim(preg_replace('/[^A-Za-z ]/', '', $post['short_name']));
            $project_type = trim(preg_replace('/[^A-Za-z ]/', '', $post['project_type']));
            
            $project_id = Yii::$app->utility->decryptString($post['key_encript']);
            
            if(empty($project_id)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid params value found.');
                return $this->redirect($url);
            }
            $pro = Yii::$app->projects->pr_get_projects($project_id);
            if(empty($pro)){
                Yii::$app->getSession()->setFlash('danger', 'Project Details Not Found.');
                return $this->redirect($url);
            }
//            $Prid = Yii::$app->utility->encryptString($pro['project_id']);
//            $status111 = Yii::$app->utility->encryptString($pro['status']);
//            $url = Yii::$app->homeUrl."manageproject/projects/updateproject?securekey=$menuid&key=$Prid&key1=$status111";
            
            if($project_type == 'Business' OR $project_type == 'Funded' OR $project_type == 'Mission'){
            }else{
                Yii::$app->getSession()->setFlash('danger', 'Invalid Project Type.');
                return $this->redirect($url);
            }
            $description = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['description']));
            $address = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['address']));
            $contact_person = trim(preg_replace('/[^0-9]/', '', $post['contact_person']));
           // $contact_no = trim(preg_replace('/[^0-9]/', '', $post['contact_no']));
            //$alternate_contact_no = trim(preg_replace('/[^0-9]/', '', $post['alternate_contact_no']));
            $project_cost = trim(preg_replace('/[^0-9]/', '', $post['project_cost']));
           // $num_working_days = trim(preg_replace('/[^0-9]/', '', $post['num_working_days']));
            //$num_manpower = trim(preg_replace('/[^0-9]/', '', $post['num_manpower']));
            //$technology_used = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['technology_used']));
            
            $manager_dept = trim(preg_replace('/[^0-9]/', '', $post['manager_dept']));
            
//            $manager_emp_id = Yii::$app->utility->decryptString($post['manager_emp_id']);
//            
//            if(empty($manager_emp_id)){
//                Yii::$app->getSession()->setFlash('danger', 'Invalid Project Manager ID.');
//                return $this->redirect($url);
//            }
//            $chkemp = Yii::$app->utility->get_employees($manager_emp_id);
//            if(empty($chkemp)){
//                Yii::$app->getSession()->setFlash('danger', 'Invalid Project Manager ID.');
//                return $this->redirect($url);
//            }
            
            $status = $post['status'];
            if($status == 'Started' OR $status == 'InProcess' OR $status == 'Completed' ){
            }else{
                Yii::$app->getSession()->setFlash('danger', 'Invalid Status value.');
                return $this->redirect($url);
            }
            $isActive = $post['is_active'];
            if($isActive == 'Y' OR $isActive == 'N'){
            }else{
                Yii::$app->getSession()->setFlash('danger', 'Invalid Project Active value.');
                return $this->redirect($url);
            }
           //  echo "<pre>";print_r($post); die;
            /* $doc_path_key = Yii::$app->utility->decryptString($post['doc_path_key']);
            if(empty($doc_path_key)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid old file path Key.');
                return $this->redirect($url);
            }
            
            if($post['doc_path_key'] == 'Y'){
                if(!empty($post['doc_path'])){
                    $approval_doc = Yii::$app->utility->decryptString($post['doc_path']);
                    if(empty($approval_doc)){
                        Yii::$app->getSession()->setFlash('danger', 'Invalid params value found.');
                        return $this->redirect($url);
                    }
                }
            }else{
                $approval_doc = NULL;
            } */
            
            /* if($post['doc_path1'] == 'Y'){
                if(empty($post['doc_path'])){
                    Yii::$app->getSession()->setFlash('danger', 'Invalid old file path.');
                    return $this->redirect($url);
                }else{
                    $old_doc_path = Yii::$app->utility->decryptString($post['doc_path']);
                    if(empty($old_doc_path)){
                        Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid old file path.');
                        return $this->redirect($url);
                    }
                }
                if(isset($_FILES['ProjectList']['tmp_name']['approval_doc']) AND !empty($_FILES['ProjectList']['tmp_name']['approval_doc'])){
                    $chkPdf = Yii::$app->utility->validatePdfFile($_FILES['ProjectList']['tmp_name']['approval_doc']);
                    if(empty($chkPdf)){
                        Yii::$app->getSession()->setFlash('danger', 'Invalid PDF File. Upload Valid File.');
                        return $this->redirect($url);
                    }

                    $approval_doc = $this->uploadFile($_FILES['ProjectList']['tmp_name']['approval_doc'], $_FILES['ProjectList']['name']['approval_doc'], $project_name);
                    if(empty($approval_doc)){
                        Yii::$app->getSession()->setFlash('danger', 'File not uploaded, try again.');
                        return $this->redirect($url);
                    }
                    
                    $oldpdf = getcwd().$old_doc_path;
                    @unlink($oldpdf);
                }else{
                    Yii::$app->getSession()->setFlash('danger', 'File not uploaded, try again.');
                    return $this->redirect($url);
                }
            }elseif($post['doc_path1'] == 'N'){
                if(isset($_FILES['ProjectList']['tmp_name']['approval_doc']) AND !empty($_FILES['ProjectList']['tmp_name']['approval_doc'])){
                    $chkPdf = Yii::$app->utility->validatePdfFile($_FILES['ProjectList']['tmp_name']['approval_doc']);
                    if(empty($chkPdf)){
                        Yii::$app->getSession()->setFlash('danger', 'Invalid PDF File. Upload Valid File.');
                        return $this->redirect($url);
                    }

                    $approval_doc = $this->uploadFile($_FILES['ProjectList']['tmp_name']['approval_doc'], $_FILES['ProjectList']['name']['approval_doc'], $project_name);
                    if(empty($approval_doc)){
                        Yii::$app->getSession()->setFlash('danger', 'File not uploaded, try again.');
                        return $this->redirect($url);
                    }
                    
                }
            } */
             $contact_no = NULL;
            $alternate_contact_no = NULL;
            $num_working_days = NULL;
            $approval_doc = NULL;
            $num_manpower = NULL;
            $technology_used = NULL;
            $duration_month = NULL;
            
            $manager_emp_id = NULL;
            $start_date= $end_date=$duration_month = NULL;
            $result = Yii::$app->projects->pr_add_update_project_detail("U", $project_id, $project_name, $short_name, $project_type, $description, $address, $contact_person, $contact_no, $alternate_contact_no, $project_cost, $start_date, $end_date, $num_working_days, $duration_month, $num_manpower, $technology_used, $manager_dept, $approval_doc, $status, $isActive);
//            die("$result-----");
            if($result == '2'){
                $class = "success";
                $msg = "Project Updated Successfully";
            }else{
                $class = "danger";
                $msg = "Project didn't Updated. Contact Admin.";
            }
            $pro = json_encode($pro);
            /*
             * Logs
             */
            $logs['action_type']="U";
            $logs['old_data']=$pro;
            $logs['project_id']=$project_id;
            $logs['project_name']=$project_name;
            $logs['short_name']=$short_name;
            $logs['description']=$description;
            $logs['address']=$address;
            $logs['contact_person']=$contact_person;
            $logs['contact_no']=$contact_no;
            $logs['alternate_contact_no']=$alternate_contact_no;
            $logs['project_cost']=$project_cost;
            $logs['start_date']=$start_date;
            $logs['end_date']=$end_date;
            $logs['num_working_days']=$num_working_days;
            $logs['duration_month']=$duration_month;
            $logs['num_manpower']=$num_manpower;
            $logs['technology_used']=$technology_used;
            $logs['manager_dept']=$manager_dept;
//            $logs['manager_emp_id']=$manager_emp_id;
            $logs['approval_doc']=$approval_doc;
            $logs['result']=$result;
            $jsonlogs = json_encode($logs);
            
            Yii::$app->utility->activities_logs("ProjectManagement", NULL, NULL, $jsonlogs, $msg);
            Yii::$app->getSession()->setFlash($class, $msg);
            return $this->redirect($url);
            
        }elseif(isset($_GET['key']) AND !empty($_GET['key']) AND isset($_GET['key1']) AND !empty($_GET['key1'])){
            $projects_id = Yii::$app->utility->decryptString($_GET['key']);
            $status = Yii::$app->utility->decryptString($_GET['key1']);
            if(empty($projects_id)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid params value found.');
                return $this->redirect($url);
            }
            $pro = Yii::$app->projects->pr_get_projects($projects_id);
            if(empty($pro)){
                Yii::$app->getSession()->setFlash('danger', 'Project Details Not Found.');
                return $this->redirect($url);
            }
            extract($pro);
            $model = new ProjectList();
            $model->project_id = $project_id;
            $model->project_name = $project_name;
            $model->short_name = $short_name;
            $model->project_type = $project_type;
            $model->description = $description;
            $model->address = $address;
            $model->contact_person = $contact_person;
            $model->contact_no = $contact_no;
            $model->alternate_contact_no = $alternate_contact_no;
            $model->project_cost = $project_cost;
            $model->start_date = date('d-m-Y', strtotime($start_date));
            $model->end_date = date('d-m-Y', strtotime($end_date));
            $model->num_working_days = $num_working_days;
            $model->num_manpower = $num_manpower;
            $model->technology_used = $technology_used;
            $model->manager_dept = $manager_dept;
//            $model->manager_emp_id = $manager_emp_id;
            $model->approval_doc = $approval_doc;
            $model->last_updated_on = $last_updated_on;
            $model->created_on = $created_on;
            $model->status = $status;
            $model->is_active = $is_active;
            
            $this->layout = '@app/views/layouts/admin_layout.php';
            return $this->render('updateproject', ['menuid'=>$menuid, 'model'=>$model]);
//            echo "$project_id<pre>";print_r($pro);
//            echo "$project_id<pre>";print_r($model);
//            die;
        }else{
            Yii::$app->getSession()->setFlash('danger', 'Invalid params found.');
            return $this->redirect($url);
        }
        
    }
    public function uploadFile($temPth, $Name, $projectName){
        $projectName = str_replace(' ', '_', $projectName);
        
        $info = new \SplFileInfo($Name);
        $ext = $info->getExtension();
        $Project_Documents = Project_Documents;
        $createFolder = getcwd().$Project_Documents;
        $random_number = $projectName."_".mt_rand(100000, 999999);
        $newName = $random_number.strtotime(date('Y-m-d H:i:s')).".$ext";
        $finalName = $createFolder.$newName;
        $fileUploadedCheck = false;
        if(move_uploaded_file($temPth,$finalName)){
            chmod($finalName, 0777);
            $fileUploadedCheck = true;
        }

        if(!empty($fileUploadedCheck)){
            $returnName = Project_Documents.$newName;
        }else{
            $returnName = "";
        }
        return $returnName;
    }
    
//    public function actionGet_dept_member(){
//        if(isset($_POST['dept_id']) AND !empty($_POST['dept_id'])){
//            $dept_id = trim(preg_replace('/[^0-9]/', '', $_POST['dept_id']));
//            $manager_emp_id= "";
//            if(isset($_POST['param_manager_emp_id']) AND !empty($_POST['param_manager_emp_id'])){
//                $manager_emp_id = Yii::$app->utility->decryptString($_POST['param_manager_emp_id']);
//                if(empty($manager_emp_id)){
//                    $result['Status']= 'FF';
//                    $result['Res']= 'Invalid param value found';
//                    echo json_encode($result);
//                    die;
//                }
//            }
//            
//            $depts = Yii::$app->utility->getDeptEmp($dept_id);
//            $html = "<option value=''>Select Project Manager</option>";
//            if(!empty($depts)){
//                
//                foreach($depts as $d){
//                    $empid = Yii::$app->utility->encryptString($d['employee_code']);
//                    $name = $d['name'].", ".$d['desg_name']." (".$d['employee_code'].")";
//                    $selected="";
//                    if($manager_emp_id == $d['employee_code']){
//                        $selected="selected=selected";
//                    }
//                    $html .= "<option value='$empid' $selected>$name</option>";
//                }
//            }else{
//                $html .= "<option value='' disabled='disabled'>No Employee Found</option>";
//            }
//            $result['Status']= 'SS';
//            $result['Res']= $html;
//            echo json_encode($result);
//            die; 
//        }
//        $result['Status']= 'FF';
//        $result['Res']= 'Invalid param found';
//        echo json_encode($result);
//        die;       
//    }
}
