<?php

namespace app\modules\fts\controllers;

use Yii;
use yii\web\Controller;
class GroupmasterController extends Controller
{
    public function beforeAction($action){
        if(isset($_GET['securekey']) AND !empty($_GET['securekey'])){
            $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
            if(empty($menuid)){ return $this->redirect(Yii::$app->homeUrl); }
            
            $chkValid = Yii::$app->utility->validate_url($menuid);
            if(empty($chkValid)){ return $this->redirect(Yii::$app->homeUrl); }
            return true;
        }else{ return $this->redirect(Yii::$app->homeUrl); }
        parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."fts/groupmaster?securekey=$menuid";
        if(isset($_POST['Group']) AND !empty($_POST['Group'])){
            $post = $_POST['Group'];
            $group_name = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['group_name']));
            $group_description = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['group_description']));
            $is_hierarchical = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['is_hierarchical']));
            $group_id = NULL;
            $msg = "Group Created Successfully.";
            if(!empty($post['group_id'])){
                $group_id = Yii::$app->utility->decryptString($post['group_id']);
                if(empty($group_id)){
                    Yii::$app->getSession()->setFlash('danger', "Invalid Params Value Found."); 
                    return $this->redirect($url);
                }
                $msg = "Group Updated Successfully.";
            }
            $result = Yii::$app->fts_utility->fts_add_update_group_master($group_id, $group_name, $group_description, "Y", $is_hierarchical);
            if($result == '1'){
                Yii::$app->getSession()->setFlash('success', $msg); 
                return $this->redirect($url);
            }elseif($result == '2'){
                Yii::$app->getSession()->setFlash('success', $msg); 
                return $this->redirect($url);
            }else{
                Yii::$app->getSession()->setFlash('danger', "Group not created or updated. contact admin."); 
                return $this->redirect($url);
            }
        }
        $allGroups = Yii::$app->fts_utility->fts_getgroupmaster(NULL);
        
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('index', ['menuid'=>$menuid, 'allGroups'=>$allGroups]);
    }
    
    public function actionGroupmembers(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."fts/groupmaster/?securekey=$menuid";
        if(isset($_POST['GroupMember']) AND !empty($_POST['GroupMember'])){
            $post = $_POST['GroupMember'];
            $group_id = Yii::$app->utility->decryptString($post['group_id']);
            $dept_id = Yii::$app->utility->decryptString($post['dept_id']);
            if(empty($group_id) OR empty($dept_id)){
                Yii::$app->getSession()->setFlash('danger', "Invalid Params Value Found."); 
                return $this->redirect($url);
            }
            $groupid = Yii::$app->utility->encryptString($group_id);
            $url = Yii::$app->homeUrl."fts/groupmaster/groupmembers/?securekey=$menuid&group_id=$groupid";
            if(!isset($post['emp_code'])){
                Yii::$app->getSession()->setFlash('danger', "Select Atleast One Employee."); 
                return $this->redirect($url);
            }
            $emps = $post['emp_code'];
            $empLists = "";
            $i=0;
            foreach($emps as $emp){
                $emp = Yii::$app->utility->decryptString($emp);
                if(empty($emp)){
                    Yii::$app->getSession()->setFlash('danger', "Invalid Employee Code."); 
                    return $this->redirect($url);
                }
                $empLists[$i] = $emp;
                $i++;
            }
            $noentrd = "";
            foreach($empLists as $e){
                $result = Yii::$app->fts_utility->fts_add_update_group_members(NULL, $group_id, $e);
                //die($result);
                if($result == '4'){
                    $noentrd = "$e,";
                }
            }
            if(empty($noentrd)){
                $msg = "Members added in Group Successfully.";
            }else{
                $msg = "$noentrd Members Already in Group";
            }
//            die($url);
            Yii::$app->getSession()->setFlash('success', $msg);
            return $this->redirect($url);
        }
        
        if(isset($_GET['group_id']) AND !empty($_GET['group_id'])){
            $group_id = Yii::$app->utility->decryptString($_GET['group_id']);
            $chkgroup= Yii::$app->fts_utility->fts_getgroupmaster($group_id);
            if(empty($chkgroup)){
                Yii::$app->getSession()->setFlash('danger', "Invalid Group ID."); 
                return $this->redirect($url);
            }
            $group_name = $chkgroup['group_name'];
            $this->layout = '@app/views/layouts/admin_layout.php';
            
            return $this->render('groupmembers', ['menuid'=>$menuid, 'group_id'=>$group_id, 'group_name'=>$group_name]);
        }
        Yii::$app->getSession()->setFlash('danger', "Invalid Params Found."); 
        return $this->redirect($url);
    }
    
    public function actionGroupprocess(){
//        echo "<pre>";print_r($_GET);die;
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."fts/groupmaster/?securekey=$menuid";
        if(isset($_POST['GroupProcessMember']) AND !empty($_POST['GroupProcessMember']) AND isset($_POST['group_id']) AND !empty($_POST['group_id'])){
            $group_id = Yii::$app->utility->decryptString($_POST['group_id']);
            if(empty($group_id)){
                Yii::$app->getSession()->setFlash('danger', "Invalid Group ID."); 
                return $this->redirect($url);
            }
            $chkgroup= Yii::$app->fts_utility->fts_getgroupmaster($group_id);
            if(empty($chkgroup)){
                Yii::$app->getSession()->setFlash('danger', "No Record Found in the group."); 
                return $this->redirect($url);
            }
            $role_ids = $_POST['GroupProcessMember']['role_id'];
            $order_number = $_POST['GroupProcessMember']['order_number'];
            
            $list="";
            $i=0;
            foreach($role_ids as $key=>$val){
                $role_id = Yii::$app->utility->decryptString($val);
                $ordr_num = $order_number[$key];
                if(!empty($role_id) AND !empty($ordr_num)){
                    $list[$i]['role_id']=$role_id;
                    $list[$i]['ordr_num']=$ordr_num;
                    $i++;
                }
            }
            if(empty($list)){
                Yii::$app->getSession()->setFlash('danger', "No Role ID / Order Number Found."); 
                return $this->redirect($url);
            }
            foreach($list as $l){
                Yii::$app->fts_utility->fts_add_update_group_process(NULL, $group_id, $l['role_id'], $l['ordr_num']);
            }
            Yii::$app->getSession()->setFlash('success', "Group Process Added Successfully."); 
            return $this->redirect($url);
        }
        
        
        if(isset($_GET['group_id']) AND !empty($_GET['group_id'])){
            $group_id = Yii::$app->utility->decryptString($_GET['group_id']);
            if(empty($group_id)){
                Yii::$app->getSession()->setFlash('danger', "Invalid Group ID."); 
                return $this->redirect($url);
            }
            $chkgroup= Yii::$app->fts_utility->fts_getgroupmaster($group_id);
            if(empty($chkgroup)){
                Yii::$app->getSession()->setFlash('danger', "No Record Found."); 
                return $this->redirect($url);
            }
            $this->layout = '@app/views/layouts/admin_layout.php';
            
            $group_name = $chkgroup['group_name'];
            return $this->render('groupprocess', ['menuid'=>$menuid, 'group_id'=>$group_id, 'group_name'=>$group_name]);
        }
        Yii::$app->getSession()->setFlash('danger', "Invalid Params Found."); 
        return $this->redirect($url);
    }
    
    public function actionDeleteprocessentry(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."fts/groupmaster/?securekey=$menuid";
        if(isset($_GET['hy_id']) AND !empty($_GET['hy_id']) AND isset($_GET['group_id']) AND !empty($_GET['group_id']) AND isset($_GET['role_id']) AND !empty($_GET['role_id'])){
            $hy_id = Yii::$app->utility->decryptString($_GET['hy_id']);
            $group_id = Yii::$app->utility->decryptString($_GET['group_id']);
            $role_id = Yii::$app->utility->decryptString($_GET['role_id']);
            if(empty($hy_id) OR empty($group_id) OR empty($role_id)){
                Yii::$app->getSession()->setFlash('danger', "Invalid Params Value Found."); 
                return $this->redirect($url);
            }
            
            $result = Yii::$app->fts_utility->fts_add_update_group_process($hy_id, $group_id, $role_id, NULL);
            
            $grupid = Yii::$app->utility->encryptString($group_id);
            $url = Yii::$app->homeUrl."fts/groupmaster/groupprocess/?securekey=$menuid&group_id=$grupid";
            
            if($result == '2'){
                Yii::$app->getSession()->setFlash('success', "Process Entry Deleted Successfully."); 
                return $this->redirect($url);
            }else{
                Yii::$app->getSession()->setFlash('danger', "Process Entry Not Deleted."); 
                return $this->redirect($url);
            }
        }else{
            Yii::$app->getSession()->setFlash('danger', "Invalid Params Found."); 
            return $this->redirect($url);
        }
    }
    public function actionGetdeptemp(){
        if(isset($_GET['dept_id']) AND !empty($_GET['dept_id'])){
            $dept_id = Yii::$app->utility->decryptString($_GET['dept_id']);
            if(empty($dept_id)){
                $result['Status']= 'FF';
                $result['Res']= 'Invalid Dept ID';
                echo json_encode($result); die;
            }
            $emps = Yii::$app->utility->get_dept_emp($dept_id);
            $html="<li>No Employee Found</li>";
            if(!empty($emps)){
                $html="";
                foreach($emps as $e){
                    $employeecode = base64_decode($e['employee_code']);
                    $employee_code = Yii::$app->utility->encryptString($employeecode);
                    $name = $e['name'];
                    $html .="<li><input type='checkbox' name='GroupMember[emp_code][]' value='$employee_code' />&nbsp;$name ($employeecode)</li>";
                }
            }
            $result['Status']= 'SS';
            $result['Res']= $html;
            echo json_encode($result); die;
            echo "<pre>";print_r($emps); die;
        }else{
            $result['Status']= 'FF';
            $result['Res']= 'Invalid Params Found';
            echo json_encode($result); die;
        }
    }
    
    public function actionGetgroupmembers(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        if(isset($_GET['group_id']) AND !empty($_GET['group_id'])){
            $group_id = Yii::$app->utility->decryptString($_GET['group_id']);
            if(empty($group_id)){
                $result['Status']= 'FF';
                $result['Res']= 'Invalid Group ID';
                echo json_encode($result); die;
            }
          
            $members = Yii::$app->fts_utility->fts_get_group_members($group_id);
            $html = "<tr>
                        <th>Employee Code</th>
                        <th>Employee Name</th>
                        <th>Designation</th>
                        <th>Department</th>
                        <th></th>
                    </tr>";
            if(!empty($members)){
                foreach($members as $m){
                    $id = Yii::$app->utility->encryptString($m['id']);
                    $employee_code = Yii::$app->utility->encryptString($m['employee_code']);
                    $delUrl = Yii::$app->homeUrl."fts/groupmaster/delgrpmember?securekey=$menuid&id=$id&ec=$employee_code";
                    $delUrl ="<a href='$delUrl' class='delgrpmember'><img src='".Yii::$app->homeUrl."images/del.gif' /></a>";
                    $code = $m['employee_code'];
                    $emp_name = $m['emp_name'];
                    $desg_name = $m['desg_name'];
                    $dept_name = $m['dept_name'];
                    $html .= "<tr>
                        <td>$code</td>
                        <td>$emp_name</td>
                        <td>$desg_name</td>
                        <td>$dept_name</td>
                        <td>$delUrl</td>
                    </tr>";
                }
            }else{
                $html .= "<tr><td colspan='5' align='center'>Members Not Assigned</td></tr>";
                
            }
            $result['Status']= 'SS';
            $result['Res']= $html;
            echo json_encode($result); die;
        }else{
            $result['Status']= 'FF';
            $result['Res']= 'Invalid Params Found';
            echo json_encode($result); die;
        }
    }
    
    public function actionGetgroupprocess(){
        if(isset($_GET['group_id']) AND !empty($_GET['group_id'])){
            $group_id = Yii::$app->utility->decryptString($_GET['group_id']);
            if(empty($group_id)){
                $result['Status']= 'FF';
                $result['Res']= 'Invalid Group ID';
                echo json_encode($result); die;
            }
          
            $process = Yii::$app->fts_utility->fts_get_group_process($group_id);
            $html = "<tr>
                        <th>Role Name</th>
                        <th>Order Number</th>
                    </tr>";
            if(!empty($process)){
                foreach($process as $m){
                   
                    $role = $m['role'];
                    $order_number = $m['order_number'];
                    $html .= "<tr>
                        <td>$role</td>
                        <td>$order_number</td>
                    </tr>";
                }
            }else{
                $html .= "<tr><td colspan='2' align='center'>Process Not Found</td></tr>";
                
            }
            $result['Status']= 'SS';
            $result['Res']= $html;
            echo json_encode($result); die;
        }else{
            $result['Status']= 'FF';
            $result['Res']= 'Invalid Params Found';
            echo json_encode($result); die;
        }
    }
    
//    public function actionCheckroleexits(){
//        if(isset($_GET['role_id']) AND !empty($_GET['role_id']) AND isset($_GET['group_id']) AND !empty($_GET['group_id'])){
//            $role_id = Yii::$app->utility->decryptString($_GET['role_id']);
//            $group_id = Yii::$app->utility->decryptString($_GET['group_id']);
//            if(empty($role_id) OR empty($group_id)){
//                $result['Status']='FF';
//                $result['Res']='Invalid Params Value Found';
//                echo json_encode($result); die;
//            }
//            $group_hierarchy = Yii::$app->fts_utility->fts_get_group_hierarchy($group_id);
//            $result['Status']='SS';
//            $result['Res']='No';
//            if(!empty($group_hierarchy)){
//                $isExits = false;
//                foreach ($group_hierarchy as $g){
//                    if($g['role_id'] == $role_id){
//                        $isExits = true;
//                    }
//                }
//                
//                if(!empty($isExits)){
//                    $result['Res']='Yes';
//                }
//            }
//            echo json_encode($result); die;
//            
//        }else{
//            $result['Status']='FF';
//            $result['Res']='Invalid Params Found';
//            echo json_encode($result); die;
//            
//        }
//    }
    /**
     * Displays a single FtsGroupMaster model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
    $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
//	public function actionGetdeptemployees()
//    {
//		if($_GET['dept_ids']){
//			$Did=$_GET['dept_ids'];
//		} 
//		$members=$sel='';
//		if(isset($_GET['members'])){
//			$members=explode(",",$_GET['members']);
//		}
//		$depts=Yii::$app->fts_utility->fts_deptemployees($Did);		 
//			$html = '<option disabled> Multiple Options</option>';
//			foreach ($depts as $td) {
//				if(!empty($members)){
//					$sel='';
//					foreach($members as $mm){
//						if($mm==$td['employee_code']){$sel='selected="selected"';} 
//					}
//				}
//				$html .= "<option $sel value='" . $td['employee_code'] . "'>" . $td['name'] . "</option>";
//			}
//			echo $html;die;
//		 
//    }
    /**
     * Creates a new FtsGroupMaster model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FtsGroupMaster();
		if(isset($_POST['FtsGroupMaster']['group_name'])){
			
			//echo "<pre>==="; print_r($_POST);exit;
			$group_name = $model::find()->select('group_name')->where('group_name = :group_name', [':group_name'=>$_POST['FtsGroupMaster']['group_name']])->one();
			if(empty($group_name)){
			if ($model->load(Yii::$app->request->post()) && $model->save()) {
				$depts=implode(",",$_POST['FtsGroupMaster']['departments']);
				if(isset($_POST['FtsGroupMaster']['members'])){
					foreach($_POST['FtsGroupMaster']['members'] as $member){
						$modell = new FtsGroupMembers();
						$data['group_id']=$model->group_id;
						$data['dept_id']=$depts;
						$data['e_id']=$member;
						if ($modell->load($data,'')) {
							 $modell->save();
						}
					}
				}
				return $this->redirect(['view', 'id' => $model->group_id]);
			}  
			}else{
				$model->load(Yii::$app->request->post());
				Yii::$app->session->setFlash("danger", "Duplicate Gruop Name, Please Check and try again");
			}
		}
			$this->layout = '@app/views/layouts/admin_layout.php';
            return $this->render('create', [
                'model' => $model,
            ]);
         
    }

    /**
     * Updates an existing FtsGroupMaster model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
			if(isset($_POST['FtsGroupMaster']['group_name'])){
			//echo "<pre>==="; print_r($_POST);exit;
			$group_name = $model::find()->select('group_name')
			->where('group_id != :group_id', [':group_id'=>$id])
			->andWhere('group_name = :group_name', [':group_name'=>$_POST['FtsGroupMaster']['group_name']])
			->one();
			if(empty($group_name)){
			if ($model->load(Yii::$app->request->post()) && $model->save()) {
				$depts=implode(",",$_POST['FtsGroupMaster']['departments']);
				if(isset($_POST['FtsGroupMaster']['members'])){
					foreach($_POST['FtsGroupMaster']['members'] as $member){
						$modell = new FtsGroupMembers();
						$data['group_id']=$model->group_id;
						$data['dept_id']=$depts;
						$data['e_id']=$member;
						if ($modell->load($data,'')) {
							 try{
								$modell->save();
							 }catch (\Exception $e) {
								// print_r($e); die;
							 }
						}
					}
				}
				return $this->redirect(['view', 'id' => $model->group_id]);
			}  
			}else{
				$model->load(Yii::$app->request->post());
				Yii::$app->session->setFlash("danger", "Duplicate Gruop Name, Please Check and try again");
			}
		}
			$departments=FtsGroupMembers::find()->select('dept_id,e_id')->where('group_id = :group_id', [':group_id'=>$id])->All();
			$_POST['FtsGroupMaster']['departments']= explode(",",$departments[0]['dept_id']);
			foreach($departments as $k=>$member){
				$_POST['FtsGroupMaster']['members'][]=$member['e_id'];
			}
			 
			// echo "<pre>==="; print_r($_POST);exit;
			$this->layout = '@app/views/layouts/admin_layout.php';
            return $this->render('update', [
                'model' => $model,
            ]);
            
    }

    /**
     * Deletes an existing FtsGroupMaster model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the FtsGroupMaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FtsGroupMaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FtsGroupMaster::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
