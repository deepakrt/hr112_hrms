<?php
namespace app\modules\admin\controllers;
use yii;
class ManagerolesController extends \yii\web\Controller
{
    public function beforeAction($action){
        if (!\Yii::$app->user->isGuest){
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
        
        $url = Yii::$app->homeUrl."admin/manageroles?securekey=$menuid";
       
        if(isset($_POST['role_id']) AND isset($_POST['role_name']) AND !empty($_POST['role_name']) AND isset($_POST['desc']) AND !empty($_POST['desc']) AND isset($_POST['is_active']) AND !empty($_POST['is_active'])){
             
            $role_id = NULL;
            if(!empty($_POST['role_id'])){
                $role_id = Yii::$app->utility->decryptString($_POST['role_id']);
                if(empty($role_id)){
                     Yii::$app->getSession()->setFlash('success', "Invalid Role ID"); 
                     return $this->redirect($url);
                }
            }
            $role_name = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $_POST['role_name']));
            $desc = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $_POST['desc']));
            $is_active = trim(preg_replace('/[^A-Z-]/', '', $_POST['is_active']));
            $result = Yii::$app->utility->add_update_master_role($role_id, $role_name, $desc, $is_active);
            /*
             * Logs
             */
            $logs['role_id']=$role_id;
            $logs['role_name']=$role_name;
            $logs['desc']=$desc;
            $logs['is_active']=$is_active;
            $jsonlogs = json_encode($logs);
            if($result == '1'){
                $msg = "Role Added Successfully.";
                Yii::$app->getSession()->setFlash('success', $msg); 
            }elseif($result == '2'){
                $msg = "Role Updated Successfully.";
                Yii::$app->getSession()->setFlash('success', $msg); 
            }elseif($result == '3'){
                $msg = "Role Already Exits.";
                Yii::$app->getSession()->setFlash('danger', $msg); 
            }else{
                $msg = "Role Not Added.";
                Yii::$app->getSession()->setFlash('danger', $msg); 
            }
            Yii::$app->utility->activities_logs("Master Data", NULL, NULL, $jsonlogs, $msg);
            return $this->redirect($url);
        }
        $this->layout = '@app/views/layouts/admin_layout.php';
        $roles = Yii::$app->utility->get_master_roles();
        return $this->render('index', ['menuid'=>$menuid,'roles'=>$roles]);
    }

    // assign_role

    public function actionAssign_role()
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $this->layout = '@app/views/layouts/admin_layout.php';
        // $url = Yii::$app->homeUrl."admin/manageroles?securekey=$menuid";
        $roles = Yii::$app->utility->get_master_roles();
        $data = array();
        return $this->render('assign_role', ['menuid'=>$menuid,'roles'=>$roles]);
    }

    public function actionGet_role_list()
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);


        // echo "<pre>"; print_r($_POST); die();
        $parmVal = $_POST['parm'];
        // echo '---'.$val = Yii::$app->utility->decryptString($parmVal);die();
        $val = $parmVal;

        $emp_roles = Yii::$app->utility->get_employee_roles_list($val);
        // echo "<pre>"; print_r($emp_roles); die();

        $roles = Yii::$app->utility->get_master_active_roles();
        $htmlCon = '';

        if(!empty($roles))
        {
            $prv=1;
            foreach($roles as $r)
            {

                $roleid = Yii::$app->utility->encryptString($r['role_id']);
                $role_id_c = $r['role_id'];
                $getRc = $this->munishSearch($emp_roles, 'role_id', $role_id_c);

                $chek = "  ";
                if(!empty($getRc))
                {
                    $getRcd = (object)array_shift($getRc);
                    $emprlID = $getRcd->role_id;

                    $chek = " checked='checked' ";

                    /*echo "<pre>"; 
                    print_r($getRc);
                    // die();
                    echo "<pre>";*/ 
                }

                $delurl = "<input type='checkbox' class='check' ".$chek." id='row_chk_".$role_id_c."' onClick='hr_assign_unassign_role(".$role_id_c.")' />";

                $htmlCon .= "<tr>
                                <td>".$r['role_id']."</td>
                                <td>".$r['role']."</td>
                                <td>".$r['desc']."</td>
                                <td>".$r['is_active']."</td>
                                <td>".$delurl."</td>
                            </tr>";
                $prv++;
            }
        }
        else
        {
            $htmlCon .= "<tr>
                            <td colspan='5'>No Data Found.</td>
                        </tr>";
        }   
         
        // die();
        $data['rolesData'] = $htmlCon; 
        $data['emp_roles'] = $emp_roles; 

        echo json_encode($data);
        die();        
    }

    function actionUpdate_role_list()
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);

        // echo "<pre>"; print_r($_POST); die();

        $parmVal = $_POST['emp_code'];
        $checkVal = $_POST['parm'];
        $roleidVal = $_POST['role_id'];

        // $emp_code = Yii::$app->utility->decryptString($parmVal);
        $emp_code = $parmVal;

        $result = Yii::$app->utility->hr_assign_unassign_role($emp_code,$roleidVal,$checkVal);
 
        if($result == 1)
        {
            $status = 111;
        }
        else
        {
            $status = 000;
        }

        $data['checkStatus'] = $status;

        echo json_encode($data);
        die();        
    }

    // recursion  
    function munishSearch($array, $key, $value)
    {
        $results = array();

        if (is_array($array)) {
            if (isset($array[$key]) && $array[$key] == $value) {
                $results[] = $array;
            }

            foreach ($array as $subarray) {
                $results = array_merge($results, $this->munishSearch($subarray, $key, $value));
            }
        }

        return $results;
    }
}