<?php

namespace app\modules\manageproject\controllers;

use app\models\ProjectList;
use Yii;
use yii\web\Controller;
use app\modules\manageproject\models\Projectdetail;
use app\modules\manageproject\models\Proposal;
use app\modules\manageproject\models\Ordermaster;
use app\modules\manageproject\models\Clientdetail;
use app\modules\manageproject\models\Manpowermapping;
use app\modules\manageproject\models\OrdermasterSearch;
use app\modules\manageproject\models\ClientContact;
use yii\web\NotFoundHttpException;
use app\modules\manageproject\facade\Csuserlog;


/**
 * OrdermasterController implements the CRUD actions for Ordermaster model.
 */
class OrdermasterController extends Controller
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
        $projects = Yii::$app->pmis_project->pmis_get_projects(NULL);        
        $this->layout = '@app/views/layouts/admin_layout.php';     
        
        
        //Csuserlog::getUserlog('projectdetail/index', Yii::$app->session->getId(), Yii::$app->user->id);
        return $this->render('index', [
             'menuid'=>$menuid, 
             'projects'=>$projects                
        ]);
                    
    }
    
    public function actionAudit()
    {        
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        //$projects = Yii::$app->pmis_project->pmis_get_projects(NULL);        
        $this->layout = '@app/views/layouts/admin_layout.php';     
        
        $projects = $_GET['key'];
                    
        $audits = Yii::$app->pmis_project->pmis_get_audit(Yii::$app->utility->decryptString($_GET['key']));
                
        return $this->render('audit', [
             'menuid'=>$menuid, 
             'projects'=>$projects,
            'audits'=>$audits
        ]);
                    
    }
    
    public function actionAddaudit()
    {        
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        //$projects = Yii::$app->pmis_project->pmis_get_projects(NULL);        
        $this->layout = '@app/views/layouts/admin_layout.php';     
        
        print_r("<pre>");
        print_r($_GET);
        die();
        $projects = $_GET['key'];
                    
        $audits = Yii::$app->pmis_project->pmis_get_audit(Yii::$app->utility->decryptString($_GET['key']));
        
//            print_r("<pre>");
//            print_r($audits );
//            die();
        
        return $this->render('audit', [
             'menuid'=>$menuid, 
             'projects'=>$projects,
            'audits'=>$audits
        ]);
                    
    }
    
    public function actionUpdate()
    {        
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."manageproject/ordermaster/update?securekey=$menuid";
        
        if(isset($_POST['Ordermaster']) AND !empty($_POST['Ordermaster']) and isset($_POST['Projectdetail']) AND !empty($_POST['Projectdetail'])){
            $post = $_POST['Ordermaster'];
            $post1 = $_POST['Projectdetail'];
            
                $model = $this->findModel(Yii::$app->utility->decryptString($_POST['pid']));
                //$modelp = new Projectdetail();
                $modelp = $this->findModelp(Yii::$app->utility->decryptString($_POST['pid']));
                
                $model->activeuser = trim(preg_replace('/[^0-9]/', '',Yii::$app->user->identity->e_id));
                $model->sessionid = Yii::$app->user->identity->accessToken;
                $model->cdacdeptid = trim(preg_replace('/[^0-9]/', '', Yii::$app->user->identity->dept_id));
                $model->amount = trim(preg_replace('/[^0-9]/', '', $post['amount']));
                $model->clientid = '';
                $model->fundingagency =  trim(preg_replace("/[^\w\space\pL\-()\/]/", '', $post['fundingagency']));
                $model->number =  trim(preg_replace("/[^\w\space\pL\-()\/]/", '', $post['number']));
                $model->orderdate = date('Y-m-d', strtotime($post1['start_date']));
                
                $model->projectname=trim(preg_replace("/[^\w\space\pL\-()\/]/", '', $post['projectname']));
                $model->proposalid='';
                $model->proposalno = trim(preg_replace("/[^\w\space\pL\-()\/]/", '',$post['proposalno']));
                $model->proposalsubmissiondate=date('Y-m-d', strtotime($post['proposalsubmissiondate']));
                $model->flag=1;
                
               
                $modelp->manager_dept = Yii::$app->user->identity->dept_id;                
                $modelp->start_date = date('Y-m-d', strtotime($post1['start_date']));                
                $modelp->project_type = $post1['project_type'];                
                if($post1['actualcompletiondate']!=NULL){                
                    $modelp->actualcompletiondate = date('Y-m-d', strtotime($post1['actualcompletiondate']));
                }
                $modelp->appreciationcert = $post1['appreciationcert'];
                $modelp->completionreport = $post1['completionreport'];
                $modelp->end_date = date('Y-m-d', strtotime($post1['end_date']));
                $modelp->filenumber = trim(preg_replace("/[^\w\space\pL\-()\/]/", '', $post1['filenumber']));
                $modelp->finaloutcome = trim(preg_replace("/[^\w\space\pL\-()\/]/", '',$post1['finaloutcome']));
                //$modelp->milestoneid =0;
                $modelp->objectives = trim(preg_replace("/[^\w\space\pL\-()\/]/", '', $post1['objectives']));
                $modelp->orderid = 0;
                $modelp->projectrefno = trim(preg_replace("/[^\w\space\pL\-()\/]/", '', $post1['projectrefno']));                
                $modelp->reference_projectid = $post1['reference_projectid'];
                $modelp->description = trim(preg_replace("/[^\w\space\pL\-()\/]/", '', $post1['description']));
                $modelp->status = 'Started';
                $modelp->project_name =  $model->projectname;
                $modelp->updated_by = Yii::$app->user->identity->e_id;
                $modelp->contact_person = Yii::$app->user->identity->e_id;; 
                $modelp->project_cost = $model->amount; 
                $modelp->created_on = date("Y-m-d H:i:s");
                
             
                if($model->validate() && $modelp->validate()){
                    $model->save();
                    
                    $modelp->orderid = $model->id;
                    $modelp->save();
                    $class = "success";                    
                    $msg = "Project Added Successfully, Please add Manpower Mapping.";	
                    $result = 1;
                }else{
                    $class = "danger";
                    $msg = "Project didn't Added. Contact Admin.";
                }
                
            
            /*$result = Yii::$app->projects->pr_add_update_project_detail("I", NULL, $project_name, $short_name, $project_type, $description, $address, $contact_person, $contact_no, $alternate_contact_no, $project_cost, $start_date, $end_date, $num_working_days, $duration_month, $num_manpower, $technology_used, $manager_dept, $approval_doc, "Started", 'Y');
            
            if($result > '0'){
                $class = "success";
                $msg = "Project Added Successfully";
				if($post['enterpcb']==1){
					$msg = "Project Added Successfully, Please add Project Cost Breakdown below.";
				}
            }else{
                $class = "danger";
                $msg = "Project didn't Added. Contact Admin.";
            }*/
            
            /*
             * Logs
             */
                
            $logs['action_type']="I";
            
            $logs['activeuser']=$model->activeuser;
            $logs['sessionid']=$model->sessionid;
            $logs['cdacdeptid']=$model->cdacdeptid;
            $logs['status']=$modelp->status;
            $logs['description']=$modelp->description;            
            $logs['reference_projectid']=$modelp->reference_projectid;
            
            $logs['activeuser'] = $model->activeuser;
            $logs['sessionid'] = $model->sessionid;
                $logs['cdacdeptid'] = $model->cdacdeptid ;
                $logs['amount'] = $model->amount ;
                $logs['clientid'] = $model->clientid ;
                $logs['fundingagency'] = $model->fundingagency;
                $logs['number'] = $model->number ;
                $logs['orderdate'] = $model->orderdate ;
                
                $logs['projectname'] = $model->projectname;
                $logs['proposalid'] = $model->proposalid;
                
                
                $logs['manager_dept'] = $modelp->manager_dept ;                
                $logs['start_date'] = $modelp->start_date ;                
                //$logs['projecttypeid'] = $modelp->projecttypeid ;                
                $logs['actualcompletiondate'] = $modelp->actualcompletiondate;
                $logs['appreciationcert'] = $modelp->appreciationcert ;
                $logs['completionreport'] = $modelp->completionreport ;
                $logs['end_date'] = $modelp->end_date ;
                $logs['filenumber'] = $modelp->filenumber ;
                $logs['finaloutcome'] = $modelp->finaloutcome ;
                //$logs['milestoneid'] = $modelp->milestoneid ;
                $logs['objectives'] = $modelp->objectives ;
                $logs['orderid'] = $modelp->orderid ;
                $logs['projectrefno'] = $modelp->projectrefno ; 
                       
            $logs['result']=$result;
            $jsonlogs = json_encode($logs);
            
            Yii::$app->utility->activities_logs("pmis_Proposal", NULL, NULL, $jsonlogs, $msg);
            Yii::$app->getSession()->setFlash($class, $msg);
            
            $modelmm = $this->findModelm($model->id);
            
            $modelcc = $this->findModelc($model->id);            
            
            $this->view->params['project'] = Ordermaster::find()->where(['cdacdeptid' => Yii::$app->user->identity->dept_id, 'deleted' => 0])->all();        
            $this->view->params['allprojects'] = Yii::$app->projectcls->AllProjects();

            empty($_POST['Ordermaster']);            
            empty($_POST['Projectdetail']);
            
            if($result==1){
                return $this->redirect($url."&key=".$_POST['pid']); 
            }
            
        }elseif(isset($_GET['key']) AND !empty($_GET['key'])){
            $projects_id = Yii::$app->utility->decryptString($_GET['key']);
            //$status = Yii::$app->utility->decryptString($_GET['key1']);
            
            if(empty($projects_id)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid params value found.');
                return $this->redirect($url);
            }            
            
            $pro = Yii::$app->pmis_project->pmis_get_projects($projects_id);
            if(empty($pro)){
                Yii::$app->getSession()->setFlash('danger', 'Project Details Not Found.');
                return $this->redirect($url);
            }            

            $model = $this->findModel($projects_id);  
            
            //$modelp = new Projectdetail();
        
            $modelp = $this->findModelp($projects_id);
            
            $modelmm = $this->findModelm($model->id);
            
            $modelcc = $this->findModelc($model->id);
            $this->view->params['allprojects'] = Yii::$app->projectcls->AllProjects();
             
        }
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('update', ['menuid'=>$menuid, 'model'=>$model, 'modelp'=>$modelp, 'modelmm'=>$modelmm, 'modelcc'=>$modelcc]);            
    }
    
    public function actionCreate()
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."manageproject/ordermaster/create?securekey=$menuid";
                
        if(isset($_POST['Ordermaster']) AND !empty($_POST['Ordermaster']) and isset($_POST['Projectdetail']) AND !empty($_POST['Projectdetail'])){
            $post = $_POST['Ordermaster'];
            $post1 = $_POST['Projectdetail'];
            
            
            //if(isset($_GET['key']) AND !empty($_GET['key']) ){                
                //$proposal_id = Yii::$app->utility->decryptString($_GET['key']);
                
                $model = new Ordermaster();
                $modelp = new Projectdetail();
                 
                $model->activeuser = trim(preg_replace('/[^0-9]/', '',Yii::$app->user->identity->e_id));
                $model->sessionid = Yii::$app->user->identity->accessToken;
                $model->cdacdeptid = trim(preg_replace('/[^0-9]/', '', Yii::$app->user->identity->dept_id));
                $model->amount = trim(preg_replace('/[^0-9]/', '', $post['amount']));
                $model->clientid = '';
                $model->fundingagency =  trim(preg_replace("/[^\w\space\pL\-()\/]/", '', $post['fundingagency']));
                $model->number =  trim(preg_replace("/[^\w\space\pL\-()\/]/", '', $post['number']));
                $model->orderdate = date('Y-m-d', strtotime($post1['start_date']));
                
                $model->projectname=trim(preg_replace('/[^A-Za-z ]/', '', $post['projectname']));
                $model->proposalid='';
                $model->proposalno = trim(preg_replace("/[^\w\space\pL\-()\/]/", '',$post['proposalno']));
                $model->proposalsubmissiondate=date('Y-m-d', strtotime($post['proposalsubmissiondate']));
                $model->flag=1;
                
               
                $modelp->manager_dept = Yii::$app->user->identity->dept_id;                
                $modelp->start_date = date('Y-m-d', strtotime($post1['start_date']));                
                $modelp->project_type = $post1['project_type'];                
                if($post1['actualcompletiondate']!=NULL){                
                    $modelp->actualcompletiondate = date('Y-m-d', strtotime($post1['actualcompletiondate']));
                }
                $modelp->appreciationcert = $post1['appreciationcert'];
                $modelp->completionreport = $post1['completionreport'];
                $modelp->end_date = date('Y-m-d', strtotime($post1['end_date']));
                $modelp->filenumber = trim(preg_replace("/[^\w\space\pL\-()\/]/", '', $post1['filenumber']));
                $modelp->finaloutcome = trim(preg_replace("/[^\w\space\pL\-()\/]/", '',$post1['finaloutcome']));
                //$modelp->milestoneid =0;
                $modelp->objectives = trim(preg_replace("/[^\w\space\pL\-()\/]/", '', $post1['objectives']));
                $modelp->orderid = 0;
                $modelp->projectrefno = trim(preg_replace("/[^\w\space\pL\-()\/]/", '', $post1['projectrefno']));                
                $modelp->reference_projectid = $post1['reference_projectid'];
                $modelp->description = trim(preg_replace("/[^\w\space\pL\-()\/]/", '', $post1['description']));
                $modelp->status = 'Started';
                $modelp->project_name =  $model->projectname;
                $modelp->updated_by = Yii::$app->user->identity->e_id;
                $modelp->contact_person = Yii::$app->user->identity->e_id;; 
                $modelp->project_cost = $model->amount; 
                $modelp->created_on = date("Y-m-d H:i:s");
                                
               
                if($model->validate() && $modelp->validate()){
                    $model->save();
                    
                    $modelp->orderid = $model->id;
                    
                    $modelp->save();
                    $class = "success";                    
                    $msg = "Project Added Successfully, Please add Manpower Mapping.";	
                    $result = 1;
                }else{
                    $result = 0;
                    $class = "danger";
                    $msg = "Project didn't Added. Contact Admin.";
                }
                
                
            //}
            
            /*$result = Yii::$app->projects->pr_add_update_project_detail("I", NULL, $project_name, $short_name, $project_type, $description, $address, $contact_person, $contact_no, $alternate_contact_no, $project_cost, $start_date, $end_date, $num_working_days, $duration_month, $num_manpower, $technology_used, $manager_dept, $approval_doc, "Started", 'Y');
            
            if($result > '0'){
                $class = "success";
                $msg = "Project Added Successfully";
				if($post['enterpcb']==1){
					$msg = "Project Added Successfully, Please add Project Cost Breakdown below.";
				}
            }else{
                $class = "danger";
                $msg = "Project didn't Added. Contact Admin.";
            }*/
            
            /*
             * Logs
             */
                
            $logs['action_type']="I";
            
            $logs['activeuser']=$model->activeuser;
            $logs['sessionid']=$model->sessionid;
            $logs['cdacdeptid']=$model->cdacdeptid;
            $logs['status']=$modelp->status;
            $logs['description']=$modelp->description;            
            $logs['reference_projectid']=$modelp->reference_projectid;
            
            $logs['activeuser'] = $model->activeuser;
            $logs['sessionid'] = $model->sessionid;
                $logs['cdacdeptid'] = $model->cdacdeptid ;
                $logs['amount'] = $model->amount ;
                $logs['clientid'] = $model->clientid ;
                $logs['fundingagency'] = $model->fundingagency;
                $logs['number'] = $model->number ;
                $logs['orderdate'] = $model->orderdate ;
                
                $logs['projectname'] = $model->projectname;
                $logs['proposalid'] = $model->proposalid;
                
                
                $logs['manager_dept'] = $modelp->manager_dept ;                
                $logs['start_date'] = $modelp->start_date ;                
                             
                $logs['actualcompletiondate'] = $modelp->actualcompletiondate;
                $logs['appreciationcert'] = $modelp->appreciationcert ;
                $logs['completionreport'] = $modelp->completionreport ;
                $logs['end_date'] = $modelp->end_date ;
                $logs['filenumber'] = $modelp->filenumber ;
                $logs['finaloutcome'] = $modelp->finaloutcome ;
                //$logs['milestoneid'] = $modelp->milestoneid ;
                $logs['objectives'] = $modelp->objectives ;
                $logs['orderid'] = $modelp->orderid ;
                $logs['projectrefno'] = $modelp->projectrefno ; 
                       
            $logs['result']=$result;
            $jsonlogs = json_encode($logs);
            
            Yii::$app->utility->activities_logs("pmis_Proposal", NULL, NULL, $jsonlogs, $msg);
            Yii::$app->getSession()->setFlash($class, $msg);
            
            $this->view->params['project'] = Ordermaster::find()->where(['cdacdeptid' => Yii::$app->user->identity->dept_id, 'deleted' => 0])->all();        
            $this->view->params['allprojects'] = Yii::$app->projectcls->AllProjects();

            if($result==1){
                return $this->redirect($url); 
            }
        }else{
            
            $model = new Ordermaster();
            $modelp = new Projectdetail();
            
            if(isset($_GET['id'])){
                $model->clientid = $_GET['id'];                
            }
            
            $this->view->params['project'] = Ordermaster::find()->where(['cdacdeptid' => Yii::$app->user->identity->dept_id, 'deleted' => 0])->all();        
            $this->view->params['allprojects'] = Yii::$app->projectcls->AllProjects();

        }
        
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('create', ['menuid'=>$menuid, 'model'=>$model, 'modelp'=>$modelp]);        
    }
    
    
    public function actionAddclientcontact()
    {        
         
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $Prid = $_GET['key'];
        $cid = $_GET['key1'];
        $url = Yii::$app->homeUrl."manageproject/ordermaster/addclientcontact?securekey=$menuid&key=$Prid&key1=$cid";
        $urli = Yii::$app->homeUrl."manageproject/ordermaster/index?securekey=$menuid";
        
           
        if(isset($_POST['ClientContact']) AND !empty($_POST['ClientContact']) AND isset($_POST['Manpowermapping']) AND !empty($_POST['Manpowermapping']) AND isset($_GET['key']) AND !empty($_GET['key']) AND isset($_GET['key1']) AND !empty($_GET['key1']) AND (empty($_POST['ClientContact']['enterpcb']))){
            $post = $_POST['ClientContact'];            
            $postm = $_POST['Manpowermapping'];
            
            $id = Yii::$app->utility->decryptString($_GET['key']);
            $client = Yii::$app->utility->decryptString($_GET['key1']);
                        
            if(isset($post['name']) AND !empty($post['name']) ){                
                $modelc = new ClientContact();
                
                $modelc->userid = Yii::$app->user->identity->e_id;
                $modelc->sessionid = Yii::$app->user->identity->accessToken;
                $modelc->cdacdeptid = Yii::$app->user->identity->dept_id;
                $modelc->orderid = $id;
                $modelc->clientid = '';
                $modelc->email = $post['email'];
                $modelc->mobile = '';
                $modelc->name = trim(preg_replace("/[^\w\space\pL\-()\/]/", '', $post['name']));
                
                $modelc->phone = trim(preg_replace('/[^0-9]/', '',$post['phone']));
                $modelc->remarks = '';  
                                
                if($modelc->validate()){                    
                    $modelc->save();
                    $class = "success";                    
                    $msg = "Client Contact Added Successfully.";
                    
                    $result = 1;
                }else{
                    $class = "danger";
                    $msg = "Client Contact didn't Added. Contact Admin.";
                }
            
                /*$result = Yii::$app->projects->pr_add_update_project_detail("I", NULL, $project_name, $short_name, $project_type, $description, $address, $contact_person, $contact_no, $alternate_contact_no, $project_cost, $start_date, $end_date, $num_working_days, $duration_month, $num_manpower, $technology_used, $manager_dept, $approval_doc, "Started", 'Y');

                if($result > '0'){
                    $class = "success";
                    $msg = "Project Added Successfully";
                                    if($post['enterpcb']==1){
                                            $msg = "Project Added Successfully, Please add Project Cost Breakdown below.";
                                    }
                }else{
                    $class = "danger";
                    $msg = "Project didn't Added. Contact Admin.";
                }*/

                /*
                 * Logs
                 */
                $logs['action_type']="I";
                $logs['userid']=$modelc->userid;            
                $logs['sessionid']=$modelc->sessionid;
                $logs['cdacdeptid']=$modelc->cdacdeptid;
                $logs['clientid']=$modelc->clientid;
                $logs['email']=$modelc->email;
                $logs['mobile']=$modelc->mobile;
                $logs['name']=$modelc->name;
                $logs['phone']=$modelc->phone;
                $logs['remarks']=$modelc->remarks;            

                $logs['result']=$result;
                $jsonlogs = json_encode($logs);

                Yii::$app->utility->activities_logs("pmis_ClientContact", NULL, NULL, $jsonlogs, $msg);
                Yii::$app->getSession()->setFlash($class, $msg);
                                
                
                       
            }else if(isset($postm['manpowerid']) AND !empty($postm['manpowerid']) ){
                $modelm = new Manpowermapping();
                $order = $this->findModel($id);
                
                $modelm->activeuser = Yii::$app->user->identity->e_id;
                $modelm->sessionid = Yii::$app->user->identity->accessToken;
                                
                $modelm->mandays = 0;
                $modelm->manpowerid = $postm['manpowerid'];
                $modelm->orderid = $id;
                //$modelm->sactionpost = trim(preg_replace('/^[a-zA-Z0-9 \s]/', '', $postm['sactionpost']));
                $modelm->sactionpost = trim(preg_replace("/[^\w\space\pL]/", "", $postm['sactionpost']));
                //$modelm->sactionpost = trim($postm['sactionpost']);
                
                $modelm->salary = trim(preg_replace('/[^0-9]/', '',$postm['salary']));
                $modelm->workstartdate = date('Y-m-d', strtotime($order['orderdate']));  
                                
                if($modelm->validate()){                     
                    $modelm->save();
                    $class = "success";                    
                    $msg = "Manpower Added Successfully.";	
                    $result = 1;
                }else{
                    $class = "danger";
                    $msg = "Manpower didn't Added. Contact Admin.";
                }
            
                /*$result = Yii::$app->projects->pr_add_update_project_detail("I", NULL, $project_name, $short_name, $project_type, $description, $address, $contact_person, $contact_no, $alternate_contact_no, $project_cost, $start_date, $end_date, $num_working_days, $duration_month, $num_manpower, $technology_used, $manager_dept, $approval_doc, "Started", 'Y');

                if($result > '0'){
                    $class = "success";
                    $msg = "Project Added Successfully";
                                    if($post['enterpcb']==1){
                                            $msg = "Project Added Successfully, Please add Project Cost Breakdown below.";
                                    }
                }else{
                    $class = "danger";
                    $msg = "Project didn't Added. Contact Admin.";
                }*/

                /*
                 * Logs
                 */                
                $logs['action_type']="I";
                $logs['workstartdate']=$modelm->workstartdate;            
                $logs['sessionid']=$modelm->sessionid;
                $logs['activeuser']=$modelm->activeuser;
                $logs['salary']=$modelm->salary;
                $logs['sactionpost']=$modelm->sactionpost;
                $logs['orderid']=$modelm->orderid;
                $logs['manpowerid']=$modelm->manpowerid;
                $logs['mandays']=$modelm->mandays;
                           

                $logs['result']=$result;
                $jsonlogs = json_encode($logs);

                Yii::$app->utility->activities_logs("pmis_Manpowermapping", NULL, NULL, $jsonlogs, $msg);
                Yii::$app->getSession()->setFlash($class, $msg);
                
            }            
                      
            $model = $this->findModel($id);      
        
            $modelp = $this->findModelp($id);
            
            $modelcc = $this->findModelc($id);
            $modelmm = $this->findModelm($id);            
            
            $modelc = new ClientContact();
            $modelm = new Manpowermapping(); 
              
            $this->view->params['project'] = Ordermaster::find()->where(['cdacdeptid' => Yii::$app->user->identity->dept_id, 'deleted' => 0])->all();        
            $this->view->params['allprojects'] = Yii::$app->projectcls->AllProjects();

            if($result==1){
                return $this->redirect($url); 
            }
            
        }elseif(isset($_GET['key']) AND !empty($_GET['key']) AND isset($_GET['key1']) AND !empty($_GET['key1'])){
            //key = orderid
            //key1 = clientid
            
            $id = Yii::$app->utility->decryptString($_GET['key']);
            $clientid = Yii::$app->utility->decryptString($_GET['key1']);
            
            $_GET['view'] =1;
            
            if(empty($id)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid params value found.');
                return $this->redirect($urli);
            }
            
            $model = $this->findModel($id);      
        
            $modelp = $this->findModelp($id);            
            
            $modelcc = $this->findModelc($id);
            $modelmm = $this->findModelm($id);            
                        
            $modelc = new ClientContact();
            $modelm = new Manpowermapping(); 
            
            $this->view->params['project'] = Ordermaster::find()->where(['cdacdeptid' => Yii::$app->user->identity->dept_id, 'deleted' => 0])->all();        
            $this->view->params['allprojects'] = Yii::$app->projectcls->AllProjects();

        }
        
        unset($_POST);
        empty($_POST);
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('addclientcontact', ['menuid'=>$menuid, 'model'=>$model, 'modelp'=>$modelp, 'modelc'=>$modelc, 'modelcc'=>$modelcc, 'modelm'=>$modelm, 'modelmm'=>$modelmm]);
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
			
 	}
       
    public function actionLists($id) {        
         $countPosts = Ordermaster::find()
              ->where(['clientid' => $id])
              ->count();
         $posts = Ordermaster::find()
              ->where(['clientid' => $id])
              ->orderBy('id DESC')
              ->all();
         if($countPosts>0) {
             echo "<option>Please Select</option>";
              foreach($posts as $post){
                   echo "<option value='".$post->id."'>".$post->projectname."</option>";
              }
         }
         else{
             echo "<option>Please Select</option>";
             echo "<option>-</option>";             
         }
    }

    
    protected function findModel($id)
    {
        if (($model = Ordermaster::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }  
    
    protected function findModelp($id)
    {
        if (($model = Projectdetail::findOne(['orderid' => $id])) !== null) {
            return $model;
        } else {
            $model = new Projectdetail();
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }  
    
    protected function findModelc($id)
    {
        if (($model = ClientContact::findall(['orderid' => $id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }  
    
    protected function findModelm($id)
    {
        if (($model = Manpowermapping::findall(['orderid' => $id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }  
}
