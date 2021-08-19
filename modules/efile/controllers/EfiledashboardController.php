<?php
namespace app\modules\efile\controllers;
use Yii;
use app\models\EfileDakGroups;
use app\models\EfileDakGroupMembers;
use app\models\EfileDakMovement;
use app\models\EfileDakGroupMembersRemarks;
use app\models\EfileDakGroupMemberApproval;
use app\models\HrDeptMapping;

class EfiledashboardController extends \yii\web\Controller
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
    public function actionDownloadfile()
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        if(isset($_GET['fileid']) AND !empty($_GET['fileid']))
        {
            $fileid = Yii::$app->utility->decryptString($_GET['fileid']);
            Yii::$app->Dakutility->makefilefromnotesanddocs($fileid);
        }
    }
    public function actionDownloadgreensheet()
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        if(isset($_GET['fileid']) AND !empty($_GET['fileid']))
        {
            $fileid = Yii::$app->utility->decryptString($_GET['fileid']);
            Yii::$app->Dakutility->makenotesfile($fileid);
        }
    }

    public function actionIndex()
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $this->layout = '@app/views/layouts/admin_layout.php';
        if(Yii::$app->user->identity->role == '2' OR Yii::$app->user->identity->role == '7' OR Yii::$app->user->identity->role == '19'){
            $id = Yii::$app->user->identity->e_id;
            $role = Yii::$app->user->identity->role;
            $lists = array();
            $deptlist = "";
            if($role == '2'){
                $depts = HrDeptMapping::find()->where( 'employee_code="'.$id.'" and is_active="Y" and role_id ="'.$role.'"')->asArray()->all();
                if(!empty($depts)){
                    $i=0;
                    foreach($depts as $d){
                        $deptlist .="$d[dept_id],";
                    }
                }
            }elseif($role == '7' OR $role == '19'){
                $depts = Yii::$app->utility->get_dept(NULL);
                if(!empty($depts)){
                    $i=0;
                    foreach($depts as $d){
                        $deptlist .="$d[dept_id],";
                    }
                }
            }
            $deptlist = rtrim($deptlist, ",");
            if(!empty($deptlist)){
                $lists = Yii::$app->Dakutility->efile_dashboard_get_dak($deptlist);
            }
//            echo "$deptlist<pre>";print_r($lists); 
//            die;
//            $param_employee_code=Yii::$app->user->identity->e_id;
//            $emproles = Yii::$app->Dakutility->get_rbac_employee_rolefordashboard($param_employee_code);
//            $emplist=$lists="";
//            foreach ($emproles as $key => $value) 
//            {
//                if($value["role_id"]==2)
//                {
//                    $efile_get_hod_emps= Yii::$app->Dakutility->efile_get_hod_emps($param_employee_code);
//                    foreach ($efile_get_hod_emps as $h => $emp) 
//                    {
//                        $emplist=$emp["dept_emp_list"];
//                    }
//                    $lists = Yii::$app->Dakutility->efile_dashboard_get_dak($emplist);
//                }
//                else if($value["role_id"]==7 || $value["role_id"]==19)
//                {
//                    $lists = Yii::$app->Dakutility->efile_dashboard_get_dak(NULL);
//                }
//                else
//                {
//                    $lists = Yii::$app->Dakutility->efile_dashboard_get_dak($param_employee_code);
//                }
//            }
    //    }
//        echo "<pre>";print_r($lists); die;
       
//        echo "<pre>";print_r($lists); die;
            return $this->render('index', ['menuid'=>$menuid, 'lists'=>$lists]);
    
        }else{
            return $this->redirect(Yii::$app->homeUrl);
        }
    }
	
    public function actionViewfiledetail()
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."efile/inbox?securekey=$menuid";
        if(isset($_GET['key']) AND !empty($_GET['key']) AND isset($_GET['key2']) AND !empty($_GET['key2']))
        {
            $file_id = Yii::$app->utility->decryptString($_GET['key']);
            $movement_id = Yii::$app->utility->decryptString($_GET['key2']);
            
            if(empty($file_id) OR empty($movement_id))
            {
                Yii::$app->getSession()->setFlash('danger', "Invalid params value found.");
                return $this->redirect($url);
            }

            $fileinfo = Yii::$app->fts_utility->efile_get_dak($file_id, NULL, NULL, NULL);
            if(empty($fileinfo))
            {
                Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
                return $this->redirect($url);
            }
            $receiptInfo = "";

            if(!empty($fileinfo['rec_id']))
            {
                $receiptInfo = Yii::$app->fts_utility->efile_get_dak_received($fileinfo['rec_id'], NULL);
            }
            $this->layout = '@app/views/layouts/filewithnoting_layout.php';
            return $this->render('viewfiledetail', ['menuid'=>$menuid, 'fileinfo'=>$fileinfo, 'receiptInfo'=>$receiptInfo]);
    }
        else
        {
            Yii::$app->getSession()->setFlash('danger', "Invalid params  found.");
            return $this->redirect($url);
        }
    }

}
