<?php

namespace app\modules\admin\controllers;
use yii;
use app\models\HrDeptMapping;
class DepartmentmappingController extends \yii\web\Controller
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
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('index', ['menuid'=>$menuid]);
    }
    
    public function actionGetempinfo(){
        $result = array();
        if(isset($_POST['emp_code']) AND !empty($_POST['emp_code'])){
            $empinfo = Yii::$app->utility->get_employees($_POST['emp_code']);
            if(empty($empinfo)){
                $result['Status']='FF';
                $result['Res']='No Record Found.';
                echo json_encode($result); die;
            }
            $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
            $menuid = Yii::$app->utility->encryptString($menuid);
            $html = "";
            $eid = Yii::$app->utility->encryptString($empinfo['employee_code']);
            $html = "<div class='row'>
                <input type='hidden' id='empcode' value='$eid' />
                <div class='col-sm-12'>
                    <h6><b>Information</b></h6>
                    <table class='table table-bordered'>
                        <tr>
                            <th>Employee Code</th>
                            <th>Employee Name</th>
                            <th>Designation</th>
                        </tr>
                        <tr>
                            <td>$empinfo[employee_code]</td>
                            <td><span class='hindishow12'>$empinfo[name_hindi]</span> / $empinfo[fullname]</td>
                            <td><span class='hindishow12'>$empinfo[desg_name_hindi]</span> / $empinfo[desg_name]</td>
                        </tr>
                    </table>
                </div>
                <div class='col-sm-12'>
                    <div class='text-right'><button type='button' class='btn btn-info btn-xs' onclick='addnewdepartment()'>Add New Department</button></div>
                    <h6><b>Assigned Departments</b></h6>
                    <table class='table table-bordered' id='assigndepts'>
                        <tr>
                            <th>Department Name</th>
                            <th>Assigned Role</th>
                            <th>Is Active (Status)</th>
                            <th>Action</th>
                        </tr>";
                $alldept = HrDeptMapping::find()->where(['employee_code'=>$empinfo['employee_code']])->asArray()->all();
                $deptHtml = "";
                if(!empty($alldept)){
                    foreach($alldept as $a){
                        $dept_map_id = Yii::$app->utility->encryptString($a['dept_map_id']);
                        $randNum = rand(1000,100000);
                        $dept = "";
                        $dept = Yii::$app->utility->get_dept($a['dept_id']);
                        if($a['is_active'] == 'Y'){
                            $active = Yii::$app->utility->encryptString('N');
                            $u = Yii::$app->homeUrl."admin/departmentmapping/removeempdept?securekey=$menuid&key=$active&key1=$eid&key2=$dept_map_id";
                            $btn = "<a href='$u' class='btn btn-danger btn-xs'>Disable</a>";
//                            $btn = "<button type='button' id='remove_$randNum' data-key='$active' data-key1='$eid' class='btn btn-danger btn-xs' onclick='activedeptrole($randNum)'></button>";
                        }else{
                            $active = Yii::$app->utility->encryptString('Y');
                            $u = Yii::$app->homeUrl."admin/departmentmapping/removeempdept?securekey=$menuid&key=$active&key1=$eid&key2=$dept_map_id";
                            $btn = "<a href='$u' class='btn btn-success btn-xs'>Enable</a>";
//                            $btn = "<button id='remove_$randNum' type='button' data-key='$active' data-key1='$eid' class='btn btn-success btn-xs' onclick='activedeptrole($randNum)'>Active</button>";
                        }
                        $role = "";
                        $role = Yii::$app->utility->get_roles($a['role_id']);
                        $deptHtml .= "<tr>
                                <td>$dept[dept_name]</td>
                                <td>$role[role]</td>
                                <td>$a[is_active]</td>
                                <td>$btn</td>
                                </tr>";
                    }
                }
//                echo "<pre>";print_r($alldept); die;
                    $html .="$deptHtml</table>
                </div>
            </div>";
            $result['Status']='SS';
            $result['Res']=$html;
            echo json_encode($result); die;
                    
        }else{
            $result['Status']='FF';
            $result['Res']='Invalid params found';
            echo json_encode($result); die;
        }
    }
    
    public function actionAssignnewdepartment(){
        if(isset($_POST['department']) AND !empty($_POST['department']) AND isset($_POST['roleid']) AND !empty($_POST['roleid']) AND isset($_POST['emp_code']) AND !empty($_POST['emp_code'])){
            $dept = $_POST['department'];
            $roleid = $_POST['roleid'];
            $empcode = Yii::$app->utility->decryptString($_POST['emp_code']);
            
            $checkdept = HrDeptMapping::find()->where(['employee_code'=>$empcode, 'dept_id'=>$dept, 'role_id'=>$roleid])->one();
            if(!empty($checkdept)){
                $result['Status']='FF';
                $result['Res']='Department and Role Already Assigned.';
                echo json_encode($result); die;
            }
            $model = new HrDeptMapping();
            $model->employee_code = $empcode;
            $model->dept_id = $dept;
            $model->role_id = $roleid;
            $model->created_date = date('Y-m-d H:i:s');
            $model->updated_by = Yii::$app->user->identity->e_id;
            $model->is_active = "Y";
           
            $model->save();
            $html = "";
            $html .=" <tr>
                        <th>Department Name</th>
                        <th>Assigned Role</th>
                        <th>Is Active</th>
                        <th></th>
                    </tr>";
            $alldept = HrDeptMapping::find()->where(['employee_code'=>$empcode])->asArray()->all();
			// echo "<pre>";print_r($alldept); die;
            $deptHtml = "";
            if(!empty($alldept)){
                $eid = Yii::$app->utility->encryptString($empcode);
                foreach($alldept as $a){
                    $dept_map_id = Yii::$app->utility->encryptString($a['dept_map_id']);
                    $randNum = rand(1000,100000);
                    $dept = "";
                    $dept = Yii::$app->utility->get_dept($a['dept_id']);
                    if($a['is_active'] == 'Y'){
                        $active = Yii::$app->utility->encryptString('N');
                        $btn = "<button type='button' id='remove_$randNum' data-key='$active' data-key1='$eid' class='btn btn-danger btn-xs' onclick='activedeptrole($randNum)'>De-active</button>";
                    }else{
                        $active = Yii::$app->utility->encryptString('Y');
                        $btn = "<button id='remove_$randNum' type='button' data-key='$active' data-key1='$eid' class='btn btn-success btn-xs' onclick='activedeptrole($randNum)'>Active</button>";
                    }
                    $role = "";
                    $role = Yii::$app->utility->get_roles($a['role_id']);
                    $deptHtml .= "<tr>
                            <td>$dept[dept_name]</td>
                            <td>$role[role]</td>
                            <td>$a[is_active]</td>
                            <td>$btn</td>
                            </tr>";
                }
            }
            // echo "<pre>";print_r($alldept); die;
            $html .="$deptHtml</table>
                </div>
            </div>";
            $result['Status']='SS';
            $result['Res']=$html;
            echo json_encode($result); die;
            
        }else{
            $result['Status']='FF';
            $result['Res']='Invalid params found';
            echo json_encode($result); die;
        }
    }
    
    public function actionRemoveempdept()
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."admin/departmentmapping?securekey=$menuid";

        if(isset($_GET['key']) AND !empty($_GET['key']) AND isset($_GET['key1']) AND !empty($_GET['key1']) AND isset($_GET['key2']) AND !empty($_GET['key2']))
        {
            $active = Yii::$app->utility->decryptString($_GET['key']);
            $eid = Yii::$app->utility->decryptString($_GET['key1']);
            $dept_map_id = Yii::$app->utility->decryptString($_GET['key2']);

            if($active == 'N')
            {
                $connection= Yii::$app->db;
                $connection->open();

                $sql =" SELECT * FROM `hr_dept_mapping` where employee_code='".$eid."' and is_active = 'Y'";
                
                $command = $connection->createCommand($sql); 
                $result=$command->queryAll();
                $connection->close();

                if(count($result)>1)
                {
                    $model = HrDeptMapping::find()->where(['employee_code'=>$eid, 'dept_map_id'=>$dept_map_id])->one();
                    $model->is_active = $active;
                    $model->save();
                    

                    $connection = Yii::$app->db;
                    $connection->open();

                    $sql =" SELECT * FROM `hr_dept_mapping` where employee_code='".$eid."' and is_active = 'Y' order by created_date DESC LIMIT 1";
                    
                    $command = $connection->createCommand($sql); 
                    $resultData=$command->queryOne();
                    $connection->close();

                    // echo ""; print_r($resultData);
                    // die();

                    if(isset($resultData['dept_id']))
                    {
                        $dept_id = $resultData['dept_id'];

                        Yii::$app->db->createCommand()
                        ->update('hr_service_details', ['dept_id' => $dept_id], ['employee_code'=>$eid])
                        ->execute();

                       // ->getRawSql();
                    }

                    Yii::$app->getSession()->setFlash('success', 'Updated Successfully.');
                    $url .= '&type=error&employee_code='.$eid;
                }
                else
                {
                    Yii::$app->getSession()->setFlash('warning', 'You cannot disable the employee department. Atleast one department required for each employee.');

                    $url .= '&type=error&employee_code='.$eid;
                }
            }
            else
            {
                $model = HrDeptMapping::find()->where(['employee_code'=>$eid, 'dept_map_id'=>$dept_map_id])->one();
                $model->is_active = $active;
                $model->save();

                $connection = Yii::$app->db;
                $connection->open();

                $sql =" SELECT * FROM `hr_dept_mapping` where employee_code='".$eid."' and dept_map_id = ".$dept_map_id." and is_active = 'Y'";
                    
                $command = $connection->createCommand($sql); 
                $resultData2=$command->queryOne();
                

                if(isset($resultData2['dept_id']))
                {
                    $dept_id = $resultData2['dept_id'];

                    $sql1 =" update `hr_service_details` set dept_id=".$dept_id." where employee_code='".$eid."'";

                    $command = $connection->createCommand($sql1); 
                    $command->execute();
                    $connection->close();

                }
                Yii::$app->getSession()->setFlash('success', 'Updated Successfully.');

                 $url .= '&type=error&employee_code='.$eid;
            }
            
            return $this->redirect($url);
        }else{
            Yii::$app->getSession()->setFlash('danger', 'Invalid parasm found.');
            return $this->redirect($url);
        }
    }
}
