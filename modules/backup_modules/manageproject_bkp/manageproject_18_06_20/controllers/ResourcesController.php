<?php

namespace app\modules\manageproject\controllers;

use yii\web\Controller;
use Yii;
use app\models\ProjectList;
class ResourcesController extends Controller
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
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $projects = Yii::$app->projects->pr_get_projects(NULL);
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('index', ['menuid'=>$menuid, 'projects'=>$projects]);
    }
    
    public function actionAddresources(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."manageproject/resources?securekey=$menuid";
        
        if(isset($_POST['R']) AND !empty($_POST['R'])){
            
            $post = $_POST['R'];
            $role_id = Yii::$app->utility->decryptString($post['role_id']);
            $project_id = Yii::$app->utility->decryptString($post['project_id']);
            $responsibility = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['responsibility']));
            
            if(empty($role_id) OR empty($project_id)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid params value found.');
                return $this->redirect($url);
            }
            $pid = Yii::$app->utility->encryptString($project_id);
            $url = Yii::$app->homeUrl."manageproject/resources/addresources?securekey=$menuid&key=$pid";
            $mem = $post['dept_mem_id'];
            if(empty($mem)){
                Yii::$app->getSession()->setFlash('danger', 'Select Members.');
                return $this->redirect($url);
            }
            $list = array();
            $i=0;
            foreach($mem as $m){
                $id = Yii::$app->utility->decryptString($m);
                if(empty($id)){
                    Yii::$app->getSession()->setFlash('danger', 'Invalid Member Emp ID.');
                    return $this->redirect($url);
                }
                $e = Yii::$app->utility->get_employees($id);
                if(empty($e)){
                    Yii::$app->getSession()->setFlash('danger', 'Invalid Member Emp ID.');
                    return $this->redirect($url);
                }
                //Check Member already exits
                $check = Yii::$app->projects->pr_get_resources(NULL, $id, $role_id, NULL);
                $ex = "";
                if(!empty($check)){
                    foreach($check as $c){
                        if($c['project_id'] == $project_id AND $c['team_member'] == $id AND $c['role_id'] == $role_id){
                            $ex .= "$id, ";
                        }
                    }
                    if(!empty($ex)){
                        Yii::$app->getSession()->setFlash('danger', "$ex Member(s) already exits");
                        return $this->redirect($url);
                    }
                }
                $list[$i]=$id;
                $i++;
            }
            
            if(!empty($list)){
                foreach($list as $empID){
                    Yii::$app->projects->pr_add_update_resources('A', NULL, $project_id, $empID, $role_id, $responsibility);
                }
                //Logs
                $logs = array();
                $logs['action_type'] = 'A';
                $logs['team_id'] = NULL;
                $logs['project_id'] = $project_id;
                $logs['role_id'] = $role_id;
                $logs['responsibility'] = $responsibility;
                $logs['emp_id'] = json_encode($list);
                $jsonlogs = json_encode($logs);
                $msg = "Project Resources Added Successfully";
                
                Yii::$app->utility->activities_logs("ProjectManagement", NULL, NULL, $jsonlogs, $msg);
                
                Yii::$app->getSession()->setFlash('success', $msg);
                return $this->redirect($url);
                
            }else{
                Yii::$app->getSession()->setFlash('danger', "No Members found.");
                return $this->redirect($url);
            }
        }
//        echo "<pre>";print_r($_GET); die;
        if(isset($_GET['key']) AND !empty($_GET['key'])){
            $projects_id = Yii::$app->utility->decryptString($_GET['key']);
//            $status = Yii::$app->utility->decryptString($_GET['key1']);
            if(empty($projects_id)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid params value found.');
                return $this->redirect($url);
            }
            $pro = Yii::$app->projects->pr_get_projects($projects_id);
            if(empty($pro)){
                Yii::$app->getSession()->setFlash('danger', 'Project Details Not Found.');
                return $this->redirect($url);
            }
            $projects_id = Yii::$app->utility->encryptString($projects_id);
            $this->layout = '@app/views/layouts/admin_layout.php';
            return $this->render('addresources', ['menuid'=>$menuid, 'projects_id'=>$projects_id, 'proInfo'=>$pro]);
        }else{
            Yii::$app->getSession()->setFlash('danger', 'Invalid params found.');
            return $this->redirect($url);
        }
    }
    public function actionViewresources(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."manageproject/resources?securekey=$menuid";
        if(isset($_GET['key']) AND !empty($_GET['key'])){
            $projects_id = Yii::$app->utility->decryptString($_GET['key']);
//            $status = Yii::$app->utility->decryptString($_GET['key1']);
            if(empty($projects_id)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid params value found.');
                return $this->redirect($url);
            }
            $pro = Yii::$app->projects->pr_get_projects($projects_id);
            if(empty($pro)){
                Yii::$app->getSession()->setFlash('danger', 'Project Details Not Found.');
                return $this->redirect($url);
            }
            $mems = Yii::$app->projects->pr_get_resources($projects_id, NULL, NULL, NULL);
            $this->layout = '@app/views/layouts/admin_layout.php';
            return $this->render('viewresources', ['menuid'=>$menuid, 'proInfo'=>$pro, 'projects_id'=>$projects_id, 'mems'=>$mems]);
        }else{
            Yii::$app->getSession()->setFlash('danger', 'Invalid params found.');
            return $this->redirect($url);
        }
    }
    
    public function actionRemoveresources() {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."manageproject/resources?securekey=$menuid";
        if(isset($_GET['key']) AND !empty($_GET['key'])){
            $team_id = Yii::$app->utility->decryptString($_GET['key']);
            $project_id = Yii::$app->utility->decryptString($_GET['key1']);
            if(empty($team_id) OR empty($project_id)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid params value found.');
                return $this->redirect($url);
            }
            $pid = Yii::$app->utility->encryptString($project_id);
            $url = Yii::$app->homeUrl."manageproject/resources/viewresources?securekey=$menuid&key=$pid";
            $mems = Yii::$app->projects->pr_get_resources(NULL, NULL, NULL, $team_id);
            if(empty($mems)){
                Yii::$app->getSession()->setFlash('danger', 'No Record Found.');
                return $this->redirect($url);
            }
            $result = Yii::$app->projects->pr_add_update_resources("D", $team_id, NULL, $mems['team_member'], NULL, NULL);
            if($result == '2'){
                $key = "success";
                $msg = "Employee Deleted from Project Successfully.";
            }else{
                $key = "danger";
                $msg = "Employee did not Deleted from Project.";
            }
            $logs = array();
            $logs['acton_type'] ='D';
            $logs['team_id'] =$team_id;
            $logs['record'] =  json_encode($mems);
            $jsonlogs = json_encode($logs);
            
            Yii::$app->utility->activities_logs("ProjectManagement", NULL, NULL, $jsonlogs, $msg);
            Yii::$app->getSession()->setFlash($key, $msg);
            return $this->redirect($url);
            
        }else{
            Yii::$app->getSession()->setFlash('danger', 'Invalid params found.');
            return $this->redirect($url);
        }
    }
}
