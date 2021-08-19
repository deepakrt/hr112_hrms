<?php

namespace app\modules\manageproject\controllers;

use yii\web\Controller;
use Yii;
use yii\widgets\ActiveForm;
use app\models\ProjectList;
use app\models\PrManpower;
use app\models\PrProjectTasks;
class ProjectsController extends Controller
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
		$dept_id=Yii::$app->user->identity->dept_id;
		if(Yii::$app->user->identity->role==1){
			$dept_id=NULL;
		}
        $projects = Yii::$app->projects->pr_get_projects($dept_id,NULL);
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('index', ['menuid'=>$menuid, 'projects'=>$projects]);
    }
	
	 public function actionPview()
    {
		  $this->view->title='Projects Detail';
		$menuid = Yii::$app->utility->decryptString($_GET['securekey']);
		$menuid = Yii::$app->utility->encryptString($menuid);
		$this->layout = '@app/views/layouts/admin_layout.php';
		return $this->render('graphdetail', ['menuid'=>$menuid]); 
 	 }
	 public function actionGetplist()
    {
	  	header('Content-Type: application/json');
		$dept_id=Yii::$app->user->identity->dept_id;
		$projects = Yii::$app->projects->pr_get_projects($dept_id,NULL);
		foreach($projects as $k=>$emp){  
			$data[$k]['project_id']= $emp['project_id'];
			$data[$k]['short_project_name']=substr($emp['project_name'],0,10);
			$data[$k]['project_name']=$emp['project_name'];
			$data[$k]['project_cost']=$emp['project_cost'];
		}
		//echo "<pre>==";print_r($data);die;
		echo json_encode($data); die;
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
		echo 1;	die;
	}
	 protected function findModel($id)
		{        
			if (($model = PrManpower::findOne($id)) !== null) {
				return $model;
			} else {
				throw new NotFoundHttpException('The requested page does not exist.');
			}
 		}
	
	public function actionDel_manpower(){
		$mp_id = trim(preg_replace('/[^0-9]/', '', $_POST['id']));
		$this->findModel($mp_id)->delete();
  		//$result = Yii::$app->projects->del_manpower($project_id,$added_by);findModel
		echo 1;	die;
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
	
    public function actionAdd_pro_tech(){
 		if(isset($_POST['project_id']) && !empty($_POST['project_id']) && isset($_POST['project_id'])){
			$project_id	= Yii::$app->utility->decryptString($_POST['project_id']);
			$_POST['ids']=implode($_POST['ids'],',');

			$project_id	= trim(preg_replace('/[^0-9]/', '', $project_id));
			$ids		= trim(preg_replace('/[^0-9,]/', '', $_POST['ids']));
			//echo $ids."<pre>";print_r($_POST); die;
			if($ids==''){$ids=NULL;}
			Yii::$app->projects->update_project_technology($project_id,$ids);
			echo "Updated Successfully";
		}else{
			echo "Invalid Request";
		}
	}
	
    public function actionAdd_manpower(){//echo "<pre>";print_r($_POST); die;
			$model = new PrManpower();
 			$data['project_id'] 	= Yii::$app->utility->decryptString($_POST['project_id']);
 			$data['emp_id'] 		= trim(preg_replace('/[^0-9]/', '', $_POST['emp_id']));
 			$data['emp_name'] 		= trim($_POST['empname']);
 			$data['salary'] 		= trim(preg_replace('/[^0-9]/', '', $_POST['salary']));
 			$data['working_as'] 	= trim($_POST['post']);
 			$data['working_on'] 	= trim($_POST['work']);
			//echo $amount = trim(preg_replace('/[^0-9]/', '', $_POST['amount']));die;
			$data['added_by'] = Yii::$app->user->identity->e_id;
			$res=0;
 			if ($model->load($data,'') && $model->validate()) {
				if($model->save()){
					$res = Yii::$app->db->getLastInsertID();
				}
			}else{
			print_r( $model->errors);
			}
			die($res);
	}
	public function actionTasks(){
		 $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
         $menuid = Yii::$app->utility->encryptString($menuid);
		  
		 $this->view->title='All Tasks';
		 if(!isset($_GET['key'])){
		 	if(Yii::$app->session->get('projects_id')){
				$_GET['key']= Yii::$app->utility->encryptString(Yii::$app->session->get('projects_id')); 
		 	}
		 }
		 if(isset($_GET['key'])){
		 	$projects_id = Yii::$app->utility->decryptString($_GET['key']);
			Yii::$app->session->set('projects_id',$projects_id);
			if(empty($projects_id)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid params value found.');
				$url = Yii::$app->homeUrl."manageproject/projects/tasks?securekey=$menuid";
                return $this->redirect($url);
            }
 			$alltasks = Yii::$app->projects->get_project_tasks();
              
         	$this->layout = '@app/views/layouts/admin_layout.php';
        	return $this->render('task', ['menuid'=>$menuid, 'alltasks'=>$alltasks]);
		 }else{
	        $this->layout = '@app/views/layouts/admin_layout.php';
    	    return $this->render('projectlist', ['menuid'=>$menuid]);
   		 }
	}
	public function actionAddtask(){
		 $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
         $menuid = Yii::$app->utility->encryptString($menuid);
		  
		 $this->view->title='Add Task';
		 if(!isset($_GET['key'])){
		 	if(Yii::$app->session->get('projects_id')){
				$_GET['key']= Yii::$app->utility->encryptString(Yii::$app->session->get('projects_id')); 
		 	}
		 }
		 if(isset($_GET['key'])){
		 	$projects_id = Yii::$app->utility->decryptString($_GET['key']);
			Yii::$app->session->set('projects_id',$projects_id);
			if(empty($projects_id)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid params value found.');
				$url = Yii::$app->homeUrl."manageproject/projects/tasks?securekey=$menuid";
                return $this->redirect($url);
            }
			$model = new PrProjectTasks();
			 if(Yii::$app->request->post('PrProjectTasks')){
 			 $post=Yii::$app->request->post('PrProjectTasks');
			 $post['project_id']=Yii::$app->session->get('projects_id');
			 $post['assigned_by'] = Yii::$app->user->identity->e_id;
			 $post['progress'] = 0;
			 $post['created_on'] = date('Y-m-d H:i:s');
			 $post['start_date'] = date('Y-m-d',strtotime($post['start_date']));
			 $post['task_end_date_fla'] = date('Y-m-d',strtotime($post['task_end_date_fla']));
 	 		 //echo  "<pre>";print_r($post);  
			
				if ($model->load($post,'') && $model->save()) {
					echo $url = Yii::$app->homeUrl."manageproject/projects/tasks?securekey=$menuid";
					Yii::$app->getSession()->setFlash('success', 'Task Added Successfully.');
					return $this->redirect($url);die;
				}else{
				 	print_r($model->errors);die;
				}
			}
              
         	$this->layout = '@app/views/layouts/admin_layout.php';
        	return $this->render('addtask', ['menuid'=>$menuid, 'model'=>$model]);
		 }else{
	        $this->layout = '@app/views/layouts/admin_layout.php';
    	    return $this->render('projectlist', ['menuid'=>$menuid]);
   		 }
	}
	
	public function actionViewtask(){
		$menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
		  
		$this->view->title='View Task Details';
		
		$projects_id = Yii::$app->session->get('projects_id');
		if(empty($projects_id)){
			Yii::$app->getSession()->setFlash('danger', 'Invalid params value found.');
			$url = Yii::$app->homeUrl."manageproject/projects/tasks?securekey=$menuid";
			return $this->redirect($url);
		}
		if(isset($_GET['key'])){
		 	$task_id = Yii::$app->utility->decryptString($_GET['key']);
			if(empty($task_id)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid params value found.');
				$url = Yii::$app->homeUrl."manageproject/projects/tasks?securekey=$menuid";
                return $this->redirect($url);
            }}
		$model = new PrProjectTasks();
		if(Yii::$app->request->post('PrProjectTasks')){
			$post=Yii::$app->request->post('PrProjectTasks');
			$post['project_id']=Yii::$app->session->get('projects_id');
			$post['assigned_by'] = Yii::$app->user->identity->e_id;
			$post['progress'] = 0;
			$post['created_on'] = date('Y-m-d H:i:s');
			$post['start_date'] = date('Y-m-d',strtotime($post['start_date']));
			$post['task_end_date_fla'] = date('Y-m-d',strtotime($post['task_end_date_fla']));
			//echo  "<pre>";print_r($post);  
			if ($model->load($post,'') && $model->save()) {
				echo $url = Yii::$app->homeUrl."manageproject/projects/tasks?securekey=$menuid";
				Yii::$app->getSession()->setFlash('success', 'Task Added Successfully.');
				return $this->redirect($url);die;
			}else{
				print_r($model->errors);die;
			}
		}

		$this->layout = '@app/views/layouts/admin_layout.php';
		return $this->render('addtask', ['menuid'=>$menuid, 'model'=>$model]);
	}
	
    public function actionAddprojectdetails(){
		 $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
         $menuid = Yii::$app->utility->encryptString($menuid);
		 $this->view->title='Add Project Details';
		 if(!isset($_GET['key'])){
		 	if(Yii::$app->session->get('projects_id')){
				$_GET['key']= Yii::$app->utility->encryptString(Yii::$app->session->get('projects_id')); 
		 	}
		 }
		 if(isset($_GET['key'])){
 		 	$projects_id = Yii::$app->utility->decryptString($_GET['key']);
			Yii::$app->session->set('projects_id',$projects_id);
			if(empty($projects_id)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid params value found.');
				$url = Yii::$app->homeUrl."manageproject/projects?securekey=$menuid";
                return $this->redirect($url);
            }
            $pro = Yii::$app->projects->pr_get_projects(NULL,$projects_id);
            if(empty($pro)){
				$url = Yii::$app->homeUrl."manageproject/projects?securekey=$menuid";
                Yii::$app->getSession()->setFlash('danger', 'Project Details Not Found.');
                return $this->redirect($url);
            }
			 
            extract($pro);
            $model = new ProjectList();
			
			$model->projectrefno = $projectrefno;
            $model->proposal_no = $proposal_no;
            $model->proposal_submission_date = date('d-m-Y', strtotime($proposal_submission_date));
            $model->order_num = $order_num;
            $model->funding_agency = $funding_agency;
            $model->objectives = $objectives;
            $model->filenumber = $filenumber;
            $model->technology_used = $technology_used;
			
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
        	
		
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('addprojectdetails', ['menuid'=>$menuid, 'model'=>$model]);
		}else{
			 
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('projectlist', ['menuid'=>$menuid]);
		
		}
	}
	
    public function actionAddnewproject(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
		 $this->view->title='Add New Project';
        $url = Yii::$app->homeUrl."manageproject/projects/addnewproject?securekey=$menuid";
		$model = new ProjectList();
        if(isset($_POST['ProjectList']) AND !empty($_POST['ProjectList'])){
            $approval_doc = NULL;
            // echo "<pre>";print_r($_POST); die;
            
            $post = $_POST['ProjectList'];
            
            $project_name = trim(preg_replace('/[^A-Za-z0-9- ]/', '', $post['project_name']));
            $short_name = trim(preg_replace('/[^A-Za-z ]/', '', $post['short_name']));
            $project_type = trim(preg_replace('/[^A-Za-z ]/', '', $post['project_type']));
            
            if($project_type == 'Business' OR $project_type == 'Funded' OR $project_type == 'Mission'){
            }else{
                Yii::$app->getSession()->setFlash('danger', 'Invalid Project Type.');
                return $this->redirect($url);
            }
			
				
            $description = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['description']));
			
			$projectrefno = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['projectrefno']));
            $proposal_no = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['proposal_no']));
            $order_num = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['order_num']));
            $funding_agency = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['funding_agency']));
            $objectives = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['objectives']));
            $filenumber = trim($post['filenumber']);
			
			
            $address = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['address']));
            $contact_person = trim(preg_replace('/[^0-9]/', '', $post['contact_person']));
           // $contact_no = trim(preg_replace('/[^0-9]/', '', $post['contact_no']));
           // $alternate_contact_no = trim(preg_replace('/[^0-9]/', '', $post['alternate_contact_no']));
            $project_cost = trim(preg_replace('/[^0-9]/', '', $post['project_cost']));
           // $num_working_days = trim(preg_replace('/[^0-9]/', '', $post['num_working_days']));
            $proposal_submission_date = date('Y-m-d', strtotime($post['proposal_submission_date']));
             
            if($proposal_submission_date == '1970-01-01'){
                Yii::$app->getSession()->setFlash('danger', 'Invalid Project Start / End Date.');
                return $this->redirect($url);
            }
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
            /* 
			last param    	proposal_submission_date
			projectrefno   contact_no
			proposal_no    alternate_contact_no
			order_num      num_working_days
			funding_agency  num_manpower
			objectives      technology_used
			filenumber		 duration_month */ 
            $approval_doc = NULL;
            $result = Yii::$app->projects->pr_add_update_project_detail("I", NULL, $project_name, $short_name, $project_type, $description, $address, $contact_person, $projectrefno, $proposal_no, $project_cost, $start_date, $end_date, $order_num, $filenumber, $funding_agency, $objectives, $manager_dept, $approval_doc, "Started", 'Y',$proposal_submission_date);
            
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
            $logs['projectrefno']=$projectrefno;
            $logs['proposal_no']=$proposal_no;
            $logs['project_cost']=$project_cost;
            $logs['start_date']=$start_date;
            $logs['end_date']=$end_date;
            $logs['order_num']=$order_num;
            $logs['filenumber']=$filenumber;
            $logs['funding_agency']=$funding_agency;
            $logs['objectives']=$objectives;
            $logs['manager_dept']=$manager_dept;
            $logs['proposal_submission_date']=$proposal_submission_date;
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
            $pro = Yii::$app->projects->pr_get_projects(NULL,$projects_id);
            if(empty($pro)){
				$url = Yii::$app->homeUrl."manageproject/projects?securekey=$menuid";
                Yii::$app->getSession()->setFlash('danger', 'Project Details Not Found.');
                return $this->redirect($url);
            }			
			 
            extract($pro);
            $model = new ProjectList();
			
			$model->projectrefno = $projectrefno;
            $model->proposal_no = $proposal_no;
            $model->proposal_submission_date = date('d-m-Y', strtotime($proposal_submission_date));
            $model->order_num = $order_num;
            $model->funding_agency = $funding_agency;
            $model->objectives = $objectives;
            $model->filenumber = $filenumber;
			
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
        	$this->view->title='Add Project Details';
		}
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('addnewproject', ['menuid'=>$menuid, 'model'=>$model]);
    }
    
    public function actionUpdateproject(){
		if(isset($_GET['view'])){
		$this->view->title='View Project Details';
		}else{
		$this->view->title='Edit Project Details';
		}
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."manageproject/projects?securekey=$menuid";
        if(isset($_POST['ProjectList']) AND !empty($_POST['ProjectList'])){
            $post = $_POST['ProjectList'];

            $project_name = trim(preg_replace('/[^A-Za-z0-9- ]/', '', $post['project_name']));
            $short_name = trim(preg_replace('/[^A-Za-z ]/', '', $post['short_name']));
            $project_type = trim(preg_replace('/[^A-Za-z ]/', '', $post['project_type']));
            
            $project_id = Yii::$app->utility->decryptString($post['key_encript']);
            
            if(empty($project_id)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid params value found.');
                return $this->redirect($url);
            }
            $pro = Yii::$app->projects->pr_get_projects(NULL,$project_id);
            if(empty($pro)){
                Yii::$app->getSession()->setFlash('danger', 'Project Details Not Found.');
                return $this->redirect($url);
            }
//            $Prid = Yii::$app->utility->encryptString($pro['project_id']);
//            $status111 = Yii::$app->utility->encryptString($pro['status']);
//            $url = Yii::$app->homeUrl."manageproject/projects/updateproject?securekey=$menuid&key=$Prid&key1=$status111";
            /*
			$model->projectrefno = $projectrefno;
            $model->proposal_no = $proposal_no;
            $model->proposal_submission_date = date('d-m-Y', strtotime($proposal_submission_date));
            $model->order_num = $order_num;
          --  $model->funding_agency = $funding_agency;
           -- $model->objectives = $objectives;
          --  $model->filenumber = $filenumber;
			*/
            if($project_type == 'Business' OR $project_type == 'Funded' OR $project_type == 'Mission'){
            }else{
                Yii::$app->getSession()->setFlash('danger', 'Invalid Project Type.');
                return $this->redirect($url);
            }
            $description = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['description']));
            $projectrefno = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['projectrefno']));
            $proposal_no = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['proposal_no']));
            $order_num = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['order_num']));
            $funding_agency = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['funding_agency']));
            $objectives = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['objectives']));
            $filenumber = trim($post['filenumber']);
			
            $address = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['address']));
            $contact_person = trim(preg_replace('/[^0-9]/', '', $post['contact_person']));
            $project_cost = trim(preg_replace('/[^0-9]/', '', $post['project_cost']));
               
            $manager_dept = trim(preg_replace('/[^0-9]/', '', $post['manager_dept']));
             
            
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
			$proposal_submission_date = date('Y-m-d', strtotime($post['proposal_submission_date']));
             
            if($proposal_submission_date == '1970-01-01'){
                Yii::$app->getSession()->setFlash('danger', 'Invalid Project Start / End Date.');
                return $this->redirect($url);
            }
		/* replace param
			new 	with 	old 
			last param    	proposal_submission_date
			$projectrefno  contact_no
			$proposal_no   alternate_contact_no
			$order_num    num_working_days
			$funding_agency   num_manpower
			$objectives    technology_used
			$filenumber duration_month 
			
            $contact_no = NULL;
            $alternate_contact_no = NULL;
            $num_working_days = NULL;
            $approval_doc = NULL;
            $num_manpower = NULL;
            $technology_used = NULL;
            $duration_month = NULL;*/
             $approval_doc = NULL;
             $start_date= $end_date = NULL;
            $result = Yii::$app->projects->pr_add_update_project_detail("U", $project_id, $project_name, $short_name, $project_type, $description, $address, $contact_person, $projectrefno, $proposal_no, $project_cost, $start_date, $end_date, $order_num, $filenumber, $funding_agency, $objectives, $manager_dept, $approval_doc, $status, $isActive, $proposal_submission_date);
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
            $logs['projectrefno']=$projectrefno;
            $logs['proposal_no']=$proposal_no;
            $logs['project_cost']=$project_cost;
            $logs['start_date']=$start_date;
            $logs['end_date']=$end_date;
            $logs['order_num']=$order_num;
            $logs['filenumber']=$filenumber;
            $logs['funding_agency']=$funding_agency;
            $logs['objectives']=$objectives;
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
            $pro = Yii::$app->projects->pr_get_projects(NULL,$projects_id);
            if(empty($pro)){
                Yii::$app->getSession()->setFlash('danger', 'Project Details Not Found.');
                return $this->redirect($url);
            }
            extract($pro);
            $model = new ProjectList();
            $model->projectrefno = $projectrefno;
            $model->proposal_no = $proposal_no;
            $model->proposal_submission_date = date('d-m-Y', strtotime($proposal_submission_date));
            $model->order_num = $order_num;
            $model->funding_agency = $funding_agency;
            $model->objectives = $objectives;
            $model->filenumber = $filenumber;
			
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
