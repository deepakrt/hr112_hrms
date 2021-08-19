<?php
namespace app\modules\efile\controllers;
use yii\web\Controller;
use Yii;
use app\models\FileDak; 
use app\models\EfileDak; 
use app\models\EmployeeNew; 
use app\models\EfileMasterCategory;
use app\models\EfileMasterProject;
use app\models\EfileDakHistory;
use app\models\EfileDakReceived;
use app\models\EfileDakGroupMembers;
use app\models\EfileDakGroupMembersRemarks;
use app\models\EfileDakGroupMemberApproval;
use app\models\EfileDakGroups;
use app\models\EfileDakTemp;
use app\models\HrServiceDetails;
use app\models\EfileDakMovement;

class DakcommonController extends Controller
{
    
    public function actionGetfwdhtml(){
		$result = array();
		if (!\Yii::$app->user->isGuest) {
			if(isset($_POST['val']) AND !empty($_POST['val']) AND isset($_POST['dak_id']) AND !empty($_POST['dak_id']) ){
				$type = Yii::$app->utility->decryptString($_POST['val']);
				$dak_id = Yii::$app->utility->decryptString($_POST['dak_id']);
				if(empty($type) OR empty($dak_id)){
					$result['Status'] = 'FF';
					$result['Res'] = 'Invalid params value found';
					echo json_encode($result);
					die;
				}
				
				if($type == 'Forward' OR $type == 'Return'){
				}else{
					$result['Status'] = 'FF';
					$result['Res'] = 'Invalid type found';
					echo json_encode($result);
					die;
				}
				
				// echo "<pre>";print_r($allDepts); die;
				$html = "";
				if($type == 'Forward'){
					// <button type='button' class='btn btn-secondary btn-sm whiteborder' id='all_emp' onclick='senType(3)'>All Employees</button>
					$allDepts= Yii::$app->utility->get_dept(NULL);
					$html = "<div class='row'>
					<div class='col-sm-4'>
						<label>Sent To </label><br>
						<div class='btn-group' role='group' aria-label='Basic example'>
							<button type='button' class='btn btn-secondary btn-sm whiteborder' id='individual' onclick='senType(1)'>Individual</button>
							<button type='button' class='btn btn-secondary btn-sm whiteborder' id='group' onclick='senType(2)'>Group</button>
							
							<input type='hidden' name='Fwddak[sent_type]' id='sent_type' readonly='' />
						</div>
					</div>
					<div class='col-sm-3 in_list' style='display: none;'>
						<label>Select Department</label>
						<select class='form-control form-control-sm' id='dak_dept_id' name='Fwddak[dept_id]'>
							<option value=''>Select Department</option>";
					$list="";
					// echo "<pre>";print_r($allDepts); die;
					if(!empty($allDepts)){
						foreach($allDepts as $d){
							// echo "<pre>";print_r($d['dept_id']); die;
							$dept_id ="";
							$dept_id = $d['dept_id'];
							$dept_name = $d['dept_name'];
							$list .= "<option value='$dept_id'>$dept_name</option>";
						}
					}
							
					$html .="$list</select>
					</div>
					<div class='col-sm-3 in_list' style='display: none;'>
						<label>Select Employee</label>
						<select class='form-control form-control-sm' id='emp_list' name='Fwddak[emp_code]'>
							<option value=''>Select Employee</option>
						</select>
					</div>
					
					<div class='col-sm-2 in_group' style='display: none;'>
						<label>Is Hierarchical?</label><br>
						<div class='btn-group' role='group' aria-label='Basic example'>
							<button type='button' class='btn btn-secondary btn-sm' id='yes_hierry' onclick='Hierarchy(1)'>Yes</button>
							<button type='button' class='btn btn-secondary btn-sm' id='no_hierry' onclick='Hierarchy(2)'>No</button>
							<input type='hidden' name='Fwddak[is_hierarchy]' id='is_hierarchy' readonly='' />
						</div>
					</div>
					<div class='col-sm-2 in_group' style='display: none; padding: 0;'>
						<label>Select Group / Committee</label>
						<select class='form-control form-control-sm' id='group_id' name='Fwddak[group_id]'>
							<option value=''>Select Group / Committee</option>
						</select>
					</div>
					<div class='col-sm-4 in_group_list' style='display: none;'>
					</div>
				</div>
				<hr>";
				}elseif($type == 'Return'){
					$info = FileDak::find()->where(['is_active' => 'Y', 'dak_id'=>$dak_id])->one();
					if(empty($info)){
						$result['Status'] = 'FF';
						$result['Res'] = 'No Record found';
						echo json_encode($result);
						die;
					}
					$empinfo = Yii::$app->utility->get_employees($info->send_from);
					if(empty($empinfo)){
						$result['Status'] = 'FF';
						$result['Res'] = 'No Record found';
						echo json_encode($result);
						die;
					}
					// echo "****<pre>";print_r($empinfo); die;
					$html = "<div class='row'>
						<div class='col-sm-12'>
							<h6><b>Dak will forward for correction to $empinfo[fname], $empinfo[desg_name], Department : $empinfo[dept_name]</b></h6>
						</div>
					</div>";
				}
				$result['Status'] = 'SS';
				$result['Res'] = $html;
				echo json_encode($result);
				die;

				
			}else{
				$result['Status'] = 'FF';
				$result['Res'] = 'Invalid params found';
				echo json_encode($result);
				die;
			}
		}else{
			$result['Status'] = 'FF';
			$result['Res'] = 'Session TimeOut.';
			echo json_encode($result);
			die;
		}
	}
	
        public function actionChecksessionactive(){
            $result = array();
            if (!\Yii::$app->user->isGuest) {
                $result['Status'] = 'SS';
                $result['Res'] = 'Session Active.';
                echo json_encode($result);
                die;
            }else{
                $result['Status'] = 'FF';
                $result['Res'] = 'Session TimeOut.';
                echo json_encode($result);
                die;
            }
        }
	public function actionGetdeptemp(){
		$result = array();
		if (!\Yii::$app->user->isGuest) {
        if(isset($_POST['dept_id']) AND !empty($_POST['dept_id'])){
			$dept_id = Yii::$app->utility->decryptString($_POST['dept_id']);
			if(empty($dept_id)){
				$result['Status'] = 'FF';
				$result['Res'] = 'Invalid params value found..';
				echo json_encode($result);
				die;
			}
            $allemps = Yii::$app->utility->get_dept_emp($dept_id);
            if(empty($allemps)){
                $result['Status']= 'FF';
                $result['Res']= 'No Employee List Found';
                echo json_encode($result); die;
            }
            $html = "<h6 class='text-center'><b>Employee List</b></h6>
				<ul  class='dept_emp_list'>";
				// echo "<pre>";print_r($allemps);die;
            foreach($allemps as $emp){
				
                $employee_code = base64_decode($emp['employee_code']);
                $Super_Admin_Emp_Code = Super_Admin_Emp_Code;
                if($employee_code == Yii::$app->user->identity->e_id){
                    
                }elseif($employee_code == $Super_Admin_Emp_Code){
                }else{
					
                    $employee_code = Yii::$app->utility->encryptString($employee_code);
                    $name = $emp['name'];
                    $html .= "<li><input type='checkbox' value='$employee_code' name='fwd_emp_list[]' class='savefordak' /> $name</li>";
                    
                }
            }
			$html .= "</ul><div id='savefwdlist'></div>";
            $result['Status']= 'SS';
            $result['Res']= $html;
            echo json_encode($result); die;
        }else{
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
	public function actionGetdeptempulforgrp(){
		$result = array();
		if (!\Yii::$app->user->isGuest) {
        if(isset($_POST['dept_id']) AND !empty($_POST['dept_id'])){
			$dept_id = Yii::$app->utility->decryptString($_POST['dept_id']);
			if(empty($dept_id)){
				$result['Status'] = 'FF';
				$result['Res'] = 'Invalid params value found..';
				echo json_encode($result);
				die;
			}
            $allemps = Yii::$app->utility->get_dept_emp($dept_id);
            if(empty($allemps)){
                $result['Status']= 'FF';
                $result['Res']= 'No Employee List Found';
                echo json_encode($result); die;
            }
            $html = "<h6 class='text-center'><b>Employee List</b></h6>
				<ul  class='dept_emp_list'>";
				// echo "<pre>";print_r($allemps);die;
            foreach($allemps as $emp){
				$id = rand(100, 1000);
                $employee_code = base64_decode($emp['employee_code']);
                $Super_Admin_Emp_Code = Super_Admin_Emp_Code;
                if($employee_code == Yii::$app->user->identity->e_id){
                    
                }elseif($employee_code == $Super_Admin_Emp_Code){
                }else{
					
                    $employee_code = Yii::$app->utility->encryptString($employee_code);
                    $deptid = Yii::$app->utility->encryptString($dept_id);
                    $name = $emp['name'];
                    $html .= "<li><input type='checkbox' value='$employee_code' name='emp_list_grp[]' class='grplistemp' data-key='$id' id='empid_$id' data-key1='$deptid' /> <span id='name_$id'>$name</span></li>";
                    
                }
            }
			$html .= "</ul>";
            $result['Status']= 'SS';
            $result['Res']= $html;
            echo json_encode($result); die;
        }else{
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
	public function actionGetdeptempforgroup(){
		$result = array();
		if (!\Yii::$app->user->isGuest) {
			$allDepts= Yii::$app->utility->get_dept(NULL);
			$chairman_dept_id = "'chairman_dept_id'";
			$group_chairman_emp_code = "'group_chairman_emp_code'";
            $html = "
				<div class='row'>
					<div class='col-sm-4'><br><b><span>समूह / समिति का नाम</span><br>Group / Committee Name</b></div>
					<div class='col-sm-8'><br><input type='text' id='group_name' class='form-control form-control-sm' name='group_name' required placeholder='समूह / समिति का नाम / Group / Committee Name' /></div>
					
					<div class='col-sm-12'><hr></div>
					<div class='col-sm-4'><br><b>समूह / समिति के अध्यक्ष <br>Chairman of Group / Committee</b></div>
					<div class='col-sm-4'>
						<label>विभाग / Department</label>
						<select class='form-control form-control-sm' onchange='get_dept_emp_list(\"chairman_dept_id\", \"group_chairman_emp_code\")' id='chairman_dept_id' name='Group[chairman_dept_id]' required=''>
							<option value=''>Select Department</option>";
							foreach($allDepts as $d){
								$dept_id = Yii::$app->utility->encryptString($d['dept_id']);
								$dept_name = $d['dept_name'];
								$html .= "<option value='$dept_id'>$dept_name</option>";
							}
			$html .= "</select>
					</div>
					<div class='col-sm-4'>
						<label>अध्यक्ष / Chairman</label>
						<select class='form-control form-control-sm' id= 'group_chairman_emp_code' name='Group[group_chairman_emp_code]' required=''>
							<option value=''>Select Chairman</option>
						</select>
					</div>
					<div class='col-sm-1'></div>
					<div class='col-sm-12'><hr></div>
					
					
					<div class='col-sm-4'><br><b>समूह / समिति का संयोजक<br>Convenor of Group / Committee</b></div>
					<div class='col-sm-4'>
						<label>विभाग / Department</label>
						<select class='form-control form-control-sm' onchange='get_dept_emp_list(\"convenor_dept_id\", \"group_convenor_emp_code\")' id='convenor_dept_id' name='Group[convenor_dept_id]' required=''>
							<option value=''>Select Department</option>";
							foreach($allDepts as $d){
								$dept_id = Yii::$app->utility->encryptString($d['dept_id']);
								$dept_name = $d['dept_name'];
								$html .= "<option value='$dept_id'>$dept_name</option>";
							}
			$html .= "</select>
					</div>
					<div class='col-sm-4'>
						<label>संयोजक / Convenor</label>
						<select class='form-control form-control-sm' id= 'group_convenor_emp_code' name='Group[group_convenor_emp_code]' required=''>
							<option value=''>Select Convenor</option>
						</select>
					</div>
					<div class='col-sm-1'></div>
					<div class='col-sm-12'><hr></div>
					<div class='col-sm-4'><br><b>समूह / समिति के सदस्य <br>Group / Committee Members</b></div>
					<div class='col-sm-4'>
						<label>विभाग / Department</label>
						<select class='form-control form-control-sm' onchange='get_dept_emp_list_ul(\"grp_emp_dept\", \"grp_emp_dept_for_select\")' id='grp_emp_dept'  required=''>
							<option value=''>Select Department</option>";
							foreach($allDepts as $d){
								$dept_id = Yii::$app->utility->encryptString($d['dept_id']);
								$dept_name = $d['dept_name'];
								$html .= "<option value='$dept_id'>$dept_name</option>";
							}
			$html .= "</select>
						<br>
					</div>
					<div class='col-sm-12' id='grp_emp_dept_for_select'>
					</div>
					<div class='col-sm-12'>
					<br>
						<hr class='hrline'>
						<h6><b>समूह / समिति के लिए अंतिम सदस्यों की सूची / List of Final Members for Group / Committee</b></h6>
						<ul id='grp_emp_for_final'></ul>
					</div>
				</div>
			";
            
            $result['Status']= 'SS';
            $result['Res']= $html;
            echo json_encode($result); die;
        
		}else{
			$result['Status'] = 'FF';
			$result['Res'] = 'Session TimeOut.';
			echo json_encode($result);
			die;
		}
    }
	public function actionGetdeptempdropdown(){
		$result = array();
		if (!\Yii::$app->user->isGuest) {
        if(isset($_POST['dept_id']) AND !empty($_POST['dept_id'])){
			$dept_id = Yii::$app->utility->decryptString($_POST['dept_id']);
                        //echo $dept_id; die;
			if(empty($dept_id)){
				$result['Status'] = 'FF';
				$result['Res'] = 'Invalid params value found..';
				echo json_encode($result);
				die;
			}
            $allemps = Yii::$app->utility->get_dept_emp($dept_id);
            if(empty($allemps)){
                $result['Status']= 'FF';
                $result['Res']= 'No Employee List Found';
                echo json_encode($result); die;
            }
            $html = "<option value=''>Select Employee</option>";
            foreach($allemps as $emp){
                $employee_code = base64_decode($emp['employee_code']);
                $Super_Admin_Emp_Code = Super_Admin_Emp_Code;
                if($employee_code == Yii::$app->user->identity->e_id){
                    
                }elseif($employee_code == $Super_Admin_Emp_Code){
                }else{
					
                    $employee_code = Yii::$app->utility->encryptString($employee_code);
                    $name = $emp['name'];
                    $html .= "<option value='$employee_code'>$name</option>";
                }
            }
			
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
	public function actionGet_project_list(){
		$result = array();
		if (!\Yii::$app->user->isGuest) {
        if(isset($_POST['category_id']) AND !empty($_POST['category_id'])){
            $category_id = Yii::$app->utility->decryptString($_POST['category_id']);
            if(empty($category_id)){
                $result['Status']= 'FF';
                $result['Res']= 'No Record Found';
                echo json_encode($result); die;
            }
            $html = "<option value=''>Select Project</option>";
            $category = EfileMasterCategory::find()->where(['file_category_id' => $category_id])->asArray()->one();
            if(empty($category)){
                    $result['Status'] = 'FF';
                    $result['Res'] = 'No Record Found.';
                    echo json_encode($result);
                    die;
            }
			// echo "<pre>";print_r($category);die;
            if($category['related_to_project'] == 'Y'){
//				$html = "<option value=''>Select Project</option>";
//            $projects = EfileMasterProject::find()->where(['is_active' => "Y"])->asArray()->all();
            $projects = Yii::$app->hr_utility->hr_get_project_list();
//            echo "<pre>";print_r($projects);die;
            if(!empty($projects)){
                
                foreach($projects as $p){
                    $id =  base64_decode($p['id']);
                    $file_project_id = Yii::$app->utility->encryptString($id);
                    $project_name = $p['project'];
                    $html .= "<option value='$file_project_id'>$project_name</option>";
                }
            }
            $result['Status']= 'SS';
            $result['Res']= $html;
            echo json_encode($result); die;
            }	
			
            
        }else{
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
	
	public function actionAdd_new_project(){
		$result = array();
		if (!\Yii::$app->user->isGuest) {
		if(isset($_POST['project_name']) AND !empty($_POST['project_name'])){
			
			$projectname = Yii::$app->fts_utility->validateHindiString($_POST['project_name']);
			if(empty($projectname)){
				$result['Status']= 'FF';
				$result['Res']= 'Invalid Params values Found';
				echo json_encode($result); die;
			}
			$project = EfileMasterProject::find()->where(['is_active' => "Y"])->asArray()->all();
			$check = "";
			if(!empty($project)){
				foreach($project as $p){
					if($p['project_name'] == $projectname){
						$check = "Y";
					}
				}
			}
			if(!empty($check)){
				$result['Status']= 'FF';
				$result['Res']= 'Project Name Already Exits.';
				echo json_encode($result); die;
			}
			$result = Yii::$app->fts_utility->elib_add_update_project("A", NULL, $projectname);
			$logs = array();
			$logs['action_type']="A";
			$logs['projectname']=$projectname;
			$logsjson = json_encode($logs);
			
			if($result == '1'){
				Yii::$app->utility->activities_logs("eFileProject", NULL, NULL, $logsjson, "Project Added Successfully.");
				
				$project = EfileMasterProject::find()->where(['is_active' => "Y"])->asArray()->all();
				$html = "<option value=''>Select Project</option>";
				foreach($project as $p){
					$id = Yii::$app->utility->encryptString($p['file_project_id']);
					$name = $p['project_name'];
					$selected = "";
					if($name == $projectname){
						$selected = "selected='selected'";
					}
					$html .= "<option $selected value='$id'>$name</option>"; 
				}
				$result = array();
				$result['Status'] = 'SS';
				$result['Res'] = $html;
				echo json_encode($result); die;
			}else{
				Yii::$app->utility->activities_logs("eFileProject", NULL, NULL, $logsjson, "New Project not added.");
				$result['Status']= 'FF';
				$result['Res']= 'New Project not added.';
				echo json_encode($result); die;
			}
			
			
		}else{
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
	
    public function actionFilemovement(){
        $url = Yii::$app->homeUrl;
        if(isset($_POST['EfileDak']) AND !empty($_POST['EfileDak'])){
            $menuid = Yii::$app->utility->decryptString($_POST['key']);
            if(empty($menuid)){
                Yii::$app->getSession()->setFlash('danger', "Invalid Menu found.");
                return $this->redirect($url);
            }
            $checkUrl = Yii::$app->utility->get_master_menu($menuid, NULL);
            if(empty($checkUrl)){
                Yii::$app->getSession()->setFlash('danger', "Invalid Menu found.");
                return $this->redirect($url);
            }
            $menuid = Yii::$app->utility->encryptString($menuid);
            $url = Yii::$app->homeUrl.$checkUrl['menu_url']."?securekey=$menuid";
            $randum_number = $_POST['randum_number'];
            
            
            // ==================================================================
           
            
            $post = $_POST['EfileDak'];
            $file_category_id = Yii::$app->utility->decryptString($post['file_category_id']);
            $action_type = Yii::$app->utility->decryptString($post['action_type']);
            $access_level = Yii::$app->utility->decryptString($post['access_level']);
            $priority = Yii::$app->utility->decryptString($post['priority']);
            $is_confidential = Yii::$app->utility->decryptString($post['is_confidential']);

            if(empty($file_category_id)  OR empty($action_type) OR empty($access_level) OR empty($priority) OR empty($is_confidential)){
                Yii::$app->getSession()->setFlash('danger', "Invalid params value found.");
                return $this->redirect($url);
            }
            $is_time_bound = "N";
            $response_date =  $group_id = $dak_group_id = $fwd_to = NULL;
            $group_members = $fwd_emp_list = array();
            $reference_num = Yii::$app->fts_utility->validateHindiString($post['reference_num']);
            $reference_date = date('Y-m-d', strtotime($post['reference_date']));
            if($reference_date == '1970-01-01'){
                Yii::$app->getSession()->setFlash('danger', "Invalid Reference Date.");
                return $this->redirect($url);
            }
            $file_type = "N";
            $rec_id = NULL;
            $subject = Yii::$app->fts_utility->validateHindiString($post['subject']);
            $note_subject = Yii::$app->fts_utility->validateHindiString($post['note_subject']);
//            $note_comment = Yii::$app->fts_utility->validateHindiString($post['note_comment']);
            $note_comment = trim($post['note_comment']);
            if(!empty($_POST['rec_id'])){
                $rec_id = Yii::$app->utility->decryptString($_POST['rec_id']);
                if(empty($rec_id )){
                    Yii::$app->getSession()->setFlash('danger', "Invalid params value found.");
                    return $this->redirect($url);
                }
                $file_type = "R";
                $initiate_type = "F";
                
            }else{
                $initiate_type = $_POST['initiate_type'];
                
                if($initiate_type == 'F' OR $initiate_type == 'N' OR $initiate_type == 'P'){
                }else{
                    Yii::$app->getSession()->setFlash('danger', "Invalid Initiate Type.");
                    return $this->redirect($url);
                }
                
                if($initiate_type == 'N'){
                    $totalFiles = EfileDak::find()->where(['is_active' => 'Y'])->asArray()->all();
                    $totalFiles = count($totalFiles);
                    $totalFiles = $totalFiles+1;
                    $reference_num = "CDAC/Note/".date('Y')."/$totalFiles";
                    $reference_date = date('Y-m-d');
                    if(empty($note_subject) OR empty($note_comment)){
                        Yii::$app->getSession()->setFlash('danger', "Required Note Subject and Note Content.");
                        return $this->redirect($url);
                    }
                    $subject = $note_subject;
                }
            }
            
            
            // Check Required Project ID
            $category = EfileMasterCategory::find()->where(['file_category_id' => $file_category_id])->asArray()->one();
            $file_project_id = NULL;
            if($category['related_to_project'] == 'Y'){
                $file_project_id = Yii::$app->utility->decryptString($post['file_project_id']);
                if(empty($file_project_id)){
                    Yii::$app->getSession()->setFlash('danger', "Select any project.");
                    return $this->redirect($url);
                }
            }
            
            
            $meta_keywords = Yii::$app->fts_utility->validateHindiString($post['meta_keywords']);
            $summary = Yii::$app->fts_utility->validateHindiString($post['summary']);
            $remarks = Yii::$app->fts_utility->validateHindiString($post['remarks']);
            $Param_is_file_protected = "N";
	    $Param_file_doc_info = $voucher_number = $voucher_path = $Param_file_password = NULL;	
            if(empty($reference_num) OR empty($subject) OR empty($file_category_id) OR empty($action_type) OR empty($access_level) OR empty($priority) OR empty($is_confidential)){
                Yii::$app->getSession()->setFlash('danger', "Select all requried fields.");
                return $this->redirect($url);
            }
            $request_scan = $post['request_scan'];
            
            //Send file for scan=================================
		
            $sent_for_scan = "N";
            if(!empty($post['request_scan_emp_code']) AND $request_scan == 'Y'){
                $scanEmpCode = Yii::$app->utility->decryptString($post['request_scan_emp_code']);
                if(empty($scanEmpCode)){
                    Yii::$app->getSession()->setFlash('danger', "Invalid Employee Selected for scan. Contact Admin.");
                    return $this->redirect($url);
                }
                
//                die("$request_scan");	
                
                $sent_for_scan = "Y";

                // Save file
                $file_id = Yii::$app->fts_utility->efile_add_update_efile_dak("A", NULL, $rec_id, $file_type, $reference_num, $reference_date, $subject, $file_category_id, $file_project_id, $action_type, $access_level, $priority, $is_confidential, $meta_keywords, $remarks, $summary, "Scan", $sent_for_scan, Yii::$app->user->identity->dept_id, $initiate_type, $Param_is_file_protected, $Param_file_password, $voucher_number);

                if(!empty($file_id)){
                    if(!empty($rec_id)){
                        $rec = EfileDakReceived::find()->where(['is_active' => 'Y', 'rec_id'=>$rec_id])->one();;
                        if(!empty($rec)){
                            $rec->status = "Received";
                            $rec->save();
                            }
                        }
                        
                        $empInfo = Yii::$app->utility->get_employees($scanEmpCode);
                        
                        Yii::$app->fts_utility->efile_add_update_efile_dak_movement("A", NULL, $file_id, "E", NULL, $scanEmpCode, "N", "FileGreenSheet", $response_date, NULL, "Y", "N", Yii::$app->user->identity->e_id, "N", $empInfo['dept_id'], $Param_file_doc_info);
                        
                        $dak_group_id = $action_id = NULL;
                        Yii::$app->fts_utility->efile_add_update_dak_history("A", NULL, $file_id, $action_id, "E", $dak_group_id, $scanEmpCode, $empInfo['dept_id']);
                        
                        // For Scan Email 
                        $fwd_emp_list = array();
                        $fwd_emp_list[0]['employee_code'] = $scanEmpCode;
                        Yii::$app->Dakutility->sendEmailwithAttachmenttouser($file_id, "E", $fwd_emp_list, Yii::$app->user->identity->e_id);
                         
                        if(!empty($rec_id)){
                            EfileDakTemp::deleteAll(['employee_code'=>Yii::$app->user->identity->e_id, 'rec_id'=>$rec_id]);
                        }else{
                            EfileDakTemp::deleteAll(['file_id' => null, 'employee_code'=>Yii::$app->user->identity->e_id]);
                        }
                        
                        Yii::$app->getSession()->setFlash('success', "File has been fowarded for scan successfully.");
                        return $this->redirect($url);
                }else{
                    Yii::$app->getSession()->setFlash('danger', "File not saved. Contact Admin.");
                    return $this->redirect($url);
                }

            } //end if Send file for scan
			
            
            $noteStatus = "N";
            if(!empty($note_comment)){
                if(empty($note_subject)){
                    Yii::$app->getSession()->setFlash('danger', "Enter Note Subject.");
                    return $this->redirect($url);
                }
                $noteStatus = "Y";
            }
            if(!empty($note_subject)){
                if(empty($note_comment)){
                    Yii::$app->getSession()->setFlash('danger', "Enter Note Comment.");
                    return $this->redirect($url);
                }
                $noteStatus = "Y";
            }
            // echo $noteStatus; die;
            $upload_Tye = "";
            $docFile = array();
            $doc_title = $is_protected = $file_password= "";	
            $protect_type = "N";	
            $file_remarks = Yii::$app->fts_utility->validateHindiString($post['file_remarks']);
            $uploaddoc = 'N';
            
            if(!empty($_POST['voucher_number']) AND !empty($_POST['voucher_path'])){
                $voucher_number = Yii::$app->utility->decryptString($_POST['voucher_number']);
                $voucher_path = Yii::$app->utility->decryptString($_POST['voucher_path']);
                
                if(empty($voucher_number) OR empty($voucher_path)){
                    Yii::$app->getSession()->setFlash('danger', "Invalid Voucher Number.");
                    return $this->redirect($url);
                }
//                echo "<pre>";print_r($voucher_path);die;
                $checkVoucher = EfileDak::find()->where(['voucher_number'=>$voucher_number, 'is_active' => 'Y'])->asArray()->one();
                if(!empty($checkVoucher)){
                    Yii::$app->getSession()->setFlash('danger', "Note Already Initiated againt Voucher Number: $voucher_number.");
                    return $this->redirect($url);
                }
//                $uploaddoc = 'Y';
                
            }
            
            
            if(empty($_POST['voucher_number']) AND empty($_POST['voucher_path'])){
            if(!empty($post['doc_type'])){
                $doc_type = Yii::$app->utility->decryptString($post['doc_type']);
                    // echo "$doc_type";
                if(empty($doc_type)){
                    Yii::$app->getSession()->setFlash('danger', "Invalid Document Type.");
                    return $this->redirect($url);
                }else{
                    $uploaddoc = 'Y';
                    if($doc_type == 'PDF'){
                        if(isset($_FILES['pdf_path']) AND !empty($_FILES['pdf_path'])){
                            $is_protected = Yii::$app->utility->decryptString($_POST['is_protected']);
                            if(empty($is_protected)){
                                Yii::$app->getSession()->setFlash('danger', "Invalid value of File protect.");
                                return $this->redirect($url);
                            }
                            
                            
                            $doc_title = Yii::$app->fts_utility->validateHindiString($_POST['doc_title']);
                            if(empty($doc_title)){
                                Yii::$app->getSession()->setFlash('danger', "Required Document Title.");
                                return $this->redirect($url);
                            }
                            if($is_protected == 'Y'){
                                $protect_type = "Y";
                                $file_password = trim($_POST['file_password']);
                                
                                
                                if(empty($file_password)){
                                    Yii::$app->getSession()->setFlash('danger', "Enter Password of file.");
                                    return $this->redirect($url);
                                }
                                $file_password = \md5($file_password);
                            }
                            $upload_Tye = "PDF";
                            $pdf_type = $_FILES['pdf_path']['type'];
                            $pdf_tmp_name = $_FILES['pdf_path']['tmp_name'];
                            $pdf_size = $_FILES['pdf_path']['size'];
                            $pdf_name = $_FILES['pdf_path']['name'];
                            $chk1 = $chk = "";
                            // die($pdf_type);
                            $chk = Yii::$app->fts_utility->validatePdfFileType($pdf_type);
                            $chk2 = Yii::$app->fts_utility->validatePdfFileSize($pdf_size);
                            $chk1 = Yii::$app->fts_utility->validatePdfFile($pdf_tmp_name);
                            $error = "";
                            if(empty($chk) OR empty($chk1)){ $error = "Upload Valid PDF File"; }
                            if(empty($chk2)){ $error = "PDF file size should be less then ".FTS_Doc_Size."MB"; }

                            if(!empty($error)){
                                    Yii::$app->getSession()->setFlash('danger', $error); 
                                    return $this->redirect($url);
                            }

                            $docFile['doc_ext_type']= 'PDF';
                            $docFile['tmp_name']= $pdf_tmp_name;
                            $docFile['file_name']= $pdf_name;
                            $docFile['doc_title']= $doc_title;
                            $docFile['is_protected']= $protect_type;
                            $docFile['file_password']= $file_password;
                        }else{
                            Yii::$app->getSession()->setFlash('danger', "Upload sccaned PDF File.");
                            return $this->redirect($url);
                        }
                    }else{
                        Yii::$app->getSession()->setFlash('danger', "Invalid document type found.");
                        return $this->redirect($url);
                    }
                }
            }
            } // if voucher empty
            if($uploaddoc == 'N' AND $noteStatus == 'N'){
                    Yii::$app->getSession()->setFlash('danger', "Required Add Note Comment OR Upload File.");
                    return $this->redirect($url);
            }
			// =======If Fwd
            
            if(($_POST['forward_dak'] == 'Y') OR (isset($_POST['is_hierarchy']) AND !empty($_POST['is_hierarchy']))){
                $is_time_bound = Yii::$app->utility->decryptString($_POST['is_time_bound']);
                if(empty($is_time_bound)){
                    Yii::$app->getSession()->setFlash('danger', "Invalid params value found.");
                    return $this->redirect($url);
                }

                if($is_time_bound == 'Y'){
                    $response_date = date('Y-m-d', strtotime($_POST['response_date']));
                    if($response_date == '1970-01-01'){
                        Yii::$app->getSession()->setFlash('danger', "Invalid Response Date.");
                        return $this->redirect($url);
                    }
                }
            }
            // =======If Fwd end
			
            
            /*
            * Enter in Dak Table
            */
            if($uploaddoc == 'Y'){
                if(empty($docFile)){
                    Yii::$app->getSession()->setFlash('danger', "Documents list not found.");
                    return $this->redirect($url);
                }
            }
            $uploadDocs = array();
            $k=0;
            $FTS_Documents = FTS_Documents;
			
//echo "<pre>";print_r($docFile);die;
            $doc_path="";
            if($uploaddoc == 'Y'){
                if($upload_Tye == 'PDF'){
                    $doc_path = Yii::$app->fts_utility->uploadFile($docFile['tmp_name'], $docFile['file_name'], $FTS_Documents);
                    if(empty($doc_path)){
                        Yii::$app->getSession()->setFlash('danger', "File document didn\'t uploaded. Contact Admin.");
                        return $this->redirect($url);
                    }
                    
                    $uploadDocs['doc_path'] = $doc_path;
                    $uploadDocs['doc_ext_type'] = "PDF";
                    $uploadDocs['doc_title']= $docFile['doc_title'];
                    $uploadDocs['is_protected']= $docFile['is_protected'];
                    $uploadDocs['file_password']= $docFile['file_password'];
                }
//                elseif($upload_Tye == 'Image'){
//                    $doc_path = Yii::$app->fts_utility->uploadImageTopdf($docFile, $FTS_Documents);
//                    if(empty($doc_path)){
//                            Yii::$app->getSession()->setFlash('danger', "File document didn\'t uploaded. Contact Admin.");
//                            return $this->redirect($url);
//                    }
//                    $uploadDocs['doc_path'] = $doc_path;
//                    $uploadDocs['doc_ext_type'] = "PDF";
//                }
            } //end if uploaddoc = Y
            
            if(!empty($rec_id)){
                $checkEnrty = EfileDak::find()->where(['rec_id'=>$rec_id])->all();
                if(!empty($checkEnrty)){
                    if(!empty($uploadDocs['doc_path'])){
                        Yii::$app->fts_utility->removefile($uploadDocs['doc_path']);
                    }
                    Yii::$app->getSession()->setFlash('success', "File Added Successfully. Go to your Inbox");
                    return $this->redirect($url);
                }
                
            }
            
            
            // Save file
            $file_id = Yii::$app->fts_utility->efile_add_update_efile_dak("A", NULL, $rec_id, $file_type, $reference_num, $reference_date, $subject, $file_category_id, $file_project_id, $action_type, $access_level, $priority, $is_confidential, $meta_keywords, $remarks, $summary, "Open", $sent_for_scan, Yii::$app->user->identity->dept_id, $initiate_type, $Param_is_file_protected, $Param_file_password, $voucher_number);

            if(empty($file_id)){
                if(!empty($uploadDocs['doc_path'])){
                    Yii::$app->fts_utility->removefile($uploadDocs['doc_path']);
                }

                Yii::$app->getSession()->setFlash('danger', "Dak didn\'t Created. Contact Admin.");
                return $this->redirect($url);
            }
            if(!empty($voucher_number) OR !empty($voucher_path)){
                $uploaddoc = 'Y';
                 $uploadDocs['doc_path'] = $voucher_path;
                $uploadDocs['doc_ext_type'] = "PDF";
                $uploadDocs['doc_title']= "Attachment of Voucher No. $voucher_number";
                $uploadDocs['is_protected']= $protect_type;
                $uploadDocs['file_password']= $file_password;
            }  
            
            /*
             * Is Hirarchy Select
             */
            if(isset($_POST['is_hierarchy']) AND !empty($_POST['is_hierarchy'])){
                $hirarchy = HrServiceDetails::find()->where(['employee_code'=>Yii::$app->user->identity->e_id, 'is_active'=>'Y'])->one();
                if(empty($hirarchy)){
                    Yii::$app->getSession()->setFlash('danger', "FLA Record Found.");
                    return $this->redirect($url);
                }
                $fla_info = HrServiceDetails::find()->where(['employee_code'=>$hirarchy->authority1])->one();
                if(empty($fla_info)){
                    Yii::$app->getSession()->setFlash('danger', "FLA Record Found.");
                    return $this->redirect($url);
                }
                if($uploaddoc == 'Y'){
                    Yii::$app->fts_utility->efile_dak_docs($file_id, "File", NULL, NULL, $uploadDocs['doc_ext_type'], $uploadDocs['doc_path'], $uploadDocs['doc_title'], $uploadDocs['is_protected'], $uploadDocs['file_password']); 
                }
                if($noteStatus == 'Y'){
                    $Param_noteid = NULL;
                    Yii::$app->fts_utility->elif_add_efile_dak_notes("A", $file_id, $note_comment, "N", $note_subject, "S", $Param_noteid, "N");
                }
                if(!empty($file_remarks)){
                    $note_subject = $Param_noteid = NULL;
                    Yii::$app->fts_utility->elif_add_efile_dak_notes("A", $file_id, $file_remarks, "N", $note_subject, "S", $Param_noteid, "R");
                }
                $is_reply_required = "Y";
                $reply_status = "N";
                $fwd_to = "E";
                Yii::$app->fts_utility->efile_add_update_efile_dak_movement("A", NULL, $file_id, $fwd_to, $group_id, $hirarchy->authority1, $is_time_bound, "FileGreenSheet", $response_date, NULL, $is_reply_required, $reply_status, Yii::$app->user->identity->e_id, "N", $fla_info->dept_id, $Param_file_doc_info);
//                die("IN");
                
                if(!empty($rec_id)){
                    EfileDakTemp::deleteAll(['employee_code'=>Yii::$app->user->identity->e_id, 'rec_id'=>$rec_id]);
                }else{
                    EfileDakTemp::deleteAll(['file_id' => null, 'employee_code'=>Yii::$app->user->identity->e_id]);
                }
                        
                $dak_group_id = NULL;
                $fwd_emp_code =$hirarchy->authority1;
                $emp_dept_id=$fla_info->dept_id;
		
                Yii::$app->fts_utility->efile_add_update_dak_history("A", NULL, $file_id, $action_type, $fwd_to, $dak_group_id, $fwd_emp_code, $emp_dept_id);
                
//                $historyModel = new EfileDakHistory();
//                $historyModel->file_id = $file_id;
//                $historyModel->fwd_to = $fwd_to;
//                $historyModel->action_id = $action_type;
//                $historyModel->dak_group_id = $dak_group_id;
//                $historyModel->fwd_emp_code = $fwd_emp_code;
//                $historyModel->fwd_emp_dept_id = $emp_dept_id;
//                $historyModel->fwd_by = Yii::$app->user->identity->e_id;
//                $historyModel->created_date = date('Y-m-d H:i:s');
//                $historyModel->is_active = "Y";
//                $historyModel->save();

                //Email Configuration
                    $fwd_emp_list = array();
                 $fwd_emp_list[0]['employee_code'] = $fwd_emp_code;
                Yii::$app->Dakutility->sendEmailwithAttachmenttouser($file_id, $fwd_to, $fwd_emp_list, Yii::$app->user->identity->e_id);
                
                Yii::$app->getSession()->setFlash('success', "File Forwarded Successfully.");
                return $this->redirect($url);
                
            }
//            echo "<pre>";print_r($_POST); die;
            /*
             * End Is Hirarchy Select
             */
            
            $i=0;
            
            if($_POST['forward_dak'] == 'N'){
                $fwd_by = Yii::$app->user->identity->e_id;
                $is_initiate_file = "Y";
                if(!empty($rec_id)){
                    $rec = EfileDakReceived::find()->where(['is_active' => 'Y', 'rec_id'=>$rec_id])->one();;
                    if(!empty($rec)){
                        $rec->status = "Received";
                        $rec->save();
                        $fwd_by = $rec->forwaded_by;
                    }
                    $is_initiate_file = "N";
                }
                if($uploaddoc == 'Y'){
                    Yii::$app->fts_utility->efile_dak_docs($file_id, "File", NULL, NULL, $uploadDocs['doc_ext_type'], $uploadDocs['doc_path'], $uploadDocs['doc_title'], $uploadDocs['is_protected'], $uploadDocs['file_password']);
                }
                if($noteStatus == 'Y'){
                    $Param_noteid = NULL;
                    Yii::$app->fts_utility->elif_add_efile_dak_notes("A", $file_id, $note_comment, "N", $note_subject, "S", $Param_noteid, "N");
                }
                if(!empty($file_remarks)){
                    $note_subject = $Param_noteid = NULL;
                    Yii::$app->fts_utility->elif_add_efile_dak_notes("A", $file_id, $file_remarks, "N", $note_subject, "S", $Param_noteid, "R");
                }


                Yii::$app->fts_utility->efile_add_update_efile_dak_movement("A", NULL, $file_id, "E", NULL, Yii::$app->user->identity->e_id, "N", "FileGreenSheet", $response_date, NULL, "Y", "N", $fwd_by, $is_initiate_file, Yii::$app->user->identity->dept_id, $Param_file_doc_info);
                if(!empty($rec_id)){
                    EfileDakTemp::deleteAll(['employee_code'=>Yii::$app->user->identity->e_id, 'rec_id'=>$rec_id]);
                }else{
                    EfileDakTemp::deleteAll(['file_id' => null, 'employee_code'=>Yii::$app->user->identity->e_id]);
                }
                Yii::$app->getSession()->setFlash('success', "File Added Successfully. Go to your Inbox");
                return $this->redirect($url);
//                die('File Added Successfully. Go to your Inbox');
            }elseif($_POST['forward_dak'] == 'Y'){
                
                $fwd_emp_list = array();
                if($_POST['forward_type'] == 'I'){
                    $fwd_to = "E";
                    $indi_emp_code = Yii::$app->utility->decryptString($_POST['indi_emp_code']);
                    $fwd_emp_dept_id =  Yii::$app->utility->decryptString($_POST['indi_dept_id']);
                    if(empty($indi_emp_code)){
                        // remove file
                        $Param_rec_id = $Param_file_type = $Param_reference_num = $Param_reference_date = $Param_subject = $Param_file_category_id = $Param_file_project_id = $Param_action_type = $Param_access_level = $Param_priority = $Param_is_confidential = $Param_meta_keywords = $Param_remarks = $Param_summary = $Param_status = $Param_sent_for_scan = $Param_emp_dept_id = $Param_initiate_type = $Param_is_file_protected = $Param_file_password = $Param_voucher_number =NULL;
                        Yii::$app->fts_utility->efile_add_update_efile_dak("R", $file_id, $Param_rec_id, $Param_file_type, $Param_reference_num, $Param_reference_date, $Param_subject, $Param_file_category_id, $Param_file_project_id, $Param_action_type, $Param_access_level, $Param_priority, $Param_is_confidential, $Param_meta_keywords, $Param_remarks, $Param_summary, $Param_status, $Param_sent_for_scan, $Param_emp_dept_id, $Param_initiate_type, $Param_is_file_protected, $Param_file_password, $Param_voucher_number);
                        if(!empty($uploadDocs['doc_path'])){
                            Yii::$app->fts_utility->removefile($uploadDocs['doc_path']);
                        }

                        Yii::$app->getSession()->setFlash('danger', "Invalid Employee Code.");
                        return $this->redirect($url);
                    }
                    
                    $fwd_emp_list[0]['employee_code'] = $indi_emp_code;
                    $fwd_emp_list[0]['emp_dept_id'] = $fwd_emp_dept_id;
                    $fwd_emp_list[0]['reply_status'] = "N";
                    $fwd_emp_list[0]['is_reply_required'] = "Y";
                    
                    $cc_emp_list = array();
                    if(isset($_POST['file_cc']) AND !empty($_POST['file_cc'])){
                        $ccemp = $_POST['ccemp'];
                        $employee_code = $ccemp['employee_code'];
                        $employee_dept_id = $ccemp['employee_dept_id'];
                        $jj=0;
                        if(!empty($employee_code) AND !empty($employee_dept_id)){
                            foreach($employee_code as $key=>$val){
                                $eid= Yii::$app->utility->decryptString($val);
                                $did= Yii::$app->utility->decryptString($employee_dept_id[$key]);

                                if(empty($eid) OR empty($did)){
                                    Yii::$app->getSession()->setFlash('danger', "CC Employee ID or Department ID Invalid.");
                                    return $this->redirect($url);
                                }
                                if($indi_emp_code != $eid){
                                    $cc_emp_list[$jj]['employee_code'] = $eid;
                                    $cc_emp_list[$jj]['emp_dept_id'] = $did;
                                    $cc_emp_list[$jj]['reply_status'] = "N";
                                    $cc_emp_list[$jj]['is_reply_required'] = "Y";
                                    $cc_emp_list[$jj]['file_doc_info'] = NULL;
                                    $jj++;
                                }
                            }
                        }
                    }
//                    
//                    echo "<pre>";print_r($cc_emp_list);
//                    die();
                }elseif($_POST['forward_type'] == 'G'){
                    $group_name = Yii::$app->fts_utility->validateHindiString($_POST['group_name']);
                    if(empty($group_name)){
                        // remove file
                       $Param_rec_id = $Param_file_type = $Param_reference_num = $Param_reference_date = $Param_subject = $Param_file_category_id = $Param_file_project_id = $Param_action_type = $Param_access_level = $Param_priority = $Param_is_confidential = $Param_meta_keywords = $Param_remarks = $Param_summary = $Param_status = $Param_sent_for_scan = $Param_emp_dept_id = $Param_initiate_type  = $Param_is_file_protected = $Param_file_password = $Param_voucher_number=NULL;
                        Yii::$app->fts_utility->efile_add_update_efile_dak("R", $file_id, $Param_rec_id, $Param_file_type, $Param_reference_num, $Param_reference_date, $Param_subject, $Param_file_category_id, $Param_file_project_id, $Param_action_type, $Param_access_level, $Param_priority, $Param_is_confidential, $Param_meta_keywords, $Param_remarks, $Param_summary, $Param_status, $Param_sent_for_scan, $Param_emp_dept_id, $Param_initiate_type, $Param_is_file_protected, $Param_file_password, $Param_voucher_number);
                        if(!empty($uploadDocs['doc_path'])){
                                Yii::$app->fts_utility->removefile($uploadDocs['doc_path']);
                        }

                        Yii::$app->getSession()->setFlash('danger', "Require Group Name.");
                        return $this->redirect($url);
                    }
                    $gruop = $_POST['Group'];
                    
                    $group_chairman_emp_code = Yii::$app->utility->decryptString($gruop['group_chairman_emp_code']);
                    $group_chairman_dept_id = Yii::$app->utility->decryptString($gruop['chairman_dept_id']);
                    $group_convenor_emp_code = Yii::$app->utility->decryptString($gruop['group_convenor_emp_code']);
                    $group_convenor_dept_id = Yii::$app->utility->decryptString($gruop['convenor_dept_id']);
                    
                    if(empty($group_chairman_emp_code) OR empty($group_convenor_emp_code) OR empty($group_chairman_dept_id) OR empty($group_convenor_dept_id)){
                        // remove file
                        $Param_rec_id = $Param_file_type = $Param_reference_num = $Param_reference_date = $Param_subject = $Param_file_category_id = $Param_file_project_id = $Param_action_type = $Param_access_level = $Param_priority = $Param_is_confidential = $Param_meta_keywords = $Param_remarks = $Param_summary = $Param_status = $Param_sent_for_scan = $Param_emp_dept_id = $Param_initiate_type = $Param_is_file_protected = $Param_file_password = $Param_voucher_number=NULL;
                        Yii::$app->fts_utility->efile_add_update_efile_dak("R", $file_id, $Param_rec_id, $Param_file_type, $Param_reference_num, $Param_reference_date, $Param_subject, $Param_file_category_id, $Param_file_project_id, $Param_action_type, $Param_access_level, $Param_priority, $Param_is_confidential, $Param_meta_keywords, $Param_remarks, $Param_summary, $Param_status, $Param_sent_for_scan, $Param_emp_dept_id, $Param_initiate_type, $Param_is_file_protected, $Param_file_password, $Param_voucher_number);
                        if(!empty($uploadDocs['doc_path'])){
                                Yii::$app->fts_utility->removefile($uploadDocs['doc_path']);
                        }

                        Yii::$app->getSession()->setFlash('danger', "Invalid Emp Code of Group Chairman / Convenor Member.");
                        return $this->redirect($url);
                    }

                    if($group_chairman_emp_code == $group_convenor_emp_code){
                        // remove file
                        $Param_rec_id = $Param_file_type = $Param_reference_num = $Param_reference_date = $Param_subject = $Param_file_category_id = $Param_file_project_id = $Param_action_type = $Param_access_level = $Param_priority = $Param_is_confidential = $Param_meta_keywords = $Param_remarks = $Param_summary = $Param_status = $Param_sent_for_scan = $Param_emp_dept_id = $Param_initiate_type = $Param_is_file_protected = $Param_file_password = $Param_voucher_number=NULL;
                        Yii::$app->fts_utility->efile_add_update_efile_dak("R", $file_id, $Param_rec_id, $Param_file_type, $Param_reference_num, $Param_reference_date, $Param_subject, $Param_file_category_id, $Param_file_project_id, $Param_action_type, $Param_access_level, $Param_priority, $Param_is_confidential, $Param_meta_keywords, $Param_remarks, $Param_summary, $Param_status, $Param_sent_for_scan, $Param_emp_dept_id, $Param_initiate_type, $Param_is_file_protected, $Param_file_password, $Param_voucher_number);
                        if(!empty($uploadDocs['doc_path'])){
                                Yii::$app->fts_utility->removefile($uploadDocs['doc_path']);
                        }

                        Yii::$app->getSession()->setFlash('danger', "Group Chairman / Convenor Member cannot be same.");
                        return $this->redirect($url);
                    }

                    $fwd_to = "G";
                    $group_members = array();
                    $group_members[0]['employee_code']=$group_chairman_emp_code;
                    $group_members[0]['emp_dept_id']=$group_chairman_dept_id;
                    $group_members[0]['group_role']="CH";
                    $group_members[1]['employee_code']=$group_convenor_emp_code;
                    $group_members[1]['emp_dept_id']=$group_convenor_dept_id;
                    $group_members[1]['group_role']="C";

                    $finalGrpEmp = $_POST['finalGrpEmp'];
                    $finalGrpEmpDept = $_POST['finalGrpEmpDept'];
                    if(empty($finalGrpEmp) or empty($finalGrpEmpDept)){
                        // remove file
                        $Param_rec_id = $Param_file_type = $Param_reference_num = $Param_reference_date = $Param_subject = $Param_file_category_id = $Param_file_project_id = $Param_action_type = $Param_access_level = $Param_priority = $Param_is_confidential = $Param_meta_keywords = $Param_remarks = $Param_summary = $Param_status = $Param_sent_for_scan = $Param_emp_dept_id = $Param_initiate_type  = $Param_is_file_protected = $Param_file_password = $Param_voucher_number=NULL;
                        Yii::$app->fts_utility->efile_add_update_efile_dak("R", $file_id, $Param_rec_id, $Param_file_type, $Param_reference_num, $Param_reference_date, $Param_subject, $Param_file_category_id, $Param_file_project_id, $Param_action_type, $Param_access_level, $Param_priority, $Param_is_confidential, $Param_meta_keywords, $Param_remarks, $Param_summary, $Param_status, $Param_sent_for_scan, $Param_emp_dept_id, $Param_initiate_type, $Param_is_file_protected, $Param_file_password, $Param_voucher_number);
                        if(!empty($uploadDocs['doc_path'])){
                                Yii::$app->fts_utility->removefile($uploadDocs['doc_path']);
                        }
                        Yii::$app->getSession()->setFlash('danger', "Select Members for Group.");
                        return $this->redirect($url);
                    }

                    $g=2;
                    foreach($finalGrpEmp as $key=>$m){
                        $employee_code = "";
                        $employee_code = Yii::$app->utility->decryptString($m);
                        $emp_dept_id = Yii::$app->utility->decryptString($finalGrpEmpDept[$key]);
                        if(empty($employee_code) OR empty($emp_dept_id)){
                                // remove file
                                $Param_rec_id = $Param_file_type = $Param_reference_num = $Param_reference_date = $Param_subject = $Param_file_category_id = $Param_file_project_id = $Param_action_type = $Param_access_level = $Param_priority = $Param_is_confidential = $Param_meta_keywords = $Param_remarks = $Param_summary = $Param_status = $Param_sent_for_scan = $Param_emp_dept_id = $Param_initiate_type = $Param_is_file_protected = $Param_file_password = $Param_voucher_number=NULL;
                        Yii::$app->fts_utility->efile_add_update_efile_dak("R", $file_id, $Param_rec_id, $Param_file_type, $Param_reference_num, $Param_reference_date, $Param_subject, $Param_file_category_id, $Param_file_project_id, $Param_action_type, $Param_access_level, $Param_priority, $Param_is_confidential, $Param_meta_keywords, $Param_remarks, $Param_summary, $Param_status, $Param_sent_for_scan, $Param_emp_dept_id, $Param_initiate_type, $Param_is_file_protected, $Param_file_password, $Param_voucher_number);
                                if(!empty($uploadDocs['doc_path'])){
                                        Yii::$app->fts_utility->removefile($uploadDocs['doc_path']);
                                }
                                Yii::$app->getSession()->setFlash('danger', "Invalid Emp Code of Group Members.");
                                return $this->redirect($url);
                        }
                        if(($group_chairman_emp_code == $employee_code) OR ($group_convenor_emp_code == $employee_code)){
                        }else{
                            $group_members[$g]['employee_code']=$employee_code;
                            $group_members[$g]['emp_dept_id']=$emp_dept_id;
                            $group_members[$g]['group_role']="M";
                            $g++;
                        }
                        
                    }

                    $group_id = Yii::$app->fts_utility->efile_add_update_dak_groups("A", NULL, $file_id, $group_name, NULL);

                    if(empty($group_id)){
                        // remove file
                        $Param_rec_id = $Param_file_type = $Param_reference_num = $Param_reference_date = $Param_subject = $Param_file_category_id = $Param_file_project_id = $Param_action_type = $Param_access_level = $Param_priority = $Param_is_confidential = $Param_meta_keywords = $Param_remarks = $Param_summary = $Param_status = $Param_sent_for_scan = $Param_emp_dept_id = $Param_initiate_type = $Param_is_file_protected = $Param_file_password = $Param_voucher_number =NULL;
                        Yii::$app->fts_utility->efile_add_update_efile_dak("R", $file_id, $Param_rec_id, $Param_file_type, $Param_reference_num, $Param_reference_date, $Param_subject, $Param_file_category_id, $Param_file_project_id, $Param_action_type, $Param_access_level, $Param_priority, $Param_is_confidential, $Param_meta_keywords, $Param_remarks, $Param_summary, $Param_status, $Param_sent_for_scan, $Param_emp_dept_id, $Param_initiate_type, $Param_is_file_protected, $Param_file_password, $Param_voucher_number);

                        if(!empty($uploadDocs['doc_path'])){
                                Yii::$app->fts_utility->removefile($uploadDocs['doc_path']);
                        }


                        Yii::$app->getSession()->setFlash('danger', "Group ID not found.");
                        return $this->redirect($url);
                    }
                    $dak_group_id = $group_id;

                    $fwd_emp_list = array();
//                    echo "<pre>";print_r($group_members); die;
                    $j=0;
                    foreach($group_members as $g){
                        Yii::$app->fts_utility->efile_add_update_efile_dak_group_members("A", NULL, $group_id, $g['employee_code'], $g['group_role'], $g['emp_dept_id']);
                        $fwd_emp_list[$j]['employee_code'] = $g['employee_code'];
                        $fwd_emp_list[$j]['emp_dept_id'] = $g['emp_dept_id'];
                        $fwd_emp_list[$j]['reply_status'] = "N";

                        if($g['group_role'] == 'CH'){
                                        // die("sdfsfd");
                            $fwd_emp_list[$j]['is_reply_required'] = "Y";	
                            $j++;
                        }else{
                            $fwd_emp_list[$j]['is_reply_required'] = "N";	
                            $j++;
                        }
                    }
                }elseif($_POST['forward_type'] == 'A'){
                    $fwd_to = "A";
                    $j = 0;
                    $allemps = Yii::$app->utility->get_employees(NULL);
                    foreach($allemps as $a){
                        if($a['is_active'] == 'Y'){
                            if($a['employee_code'] == Super_Admin_Emp_Code){
                            }elseif($a['employee_code'] == Yii::$app->user->identity->e_id){
                            }else{
                                $fwd_emp_list[$j]['employee_code'] = $a['employee_code'];
                                $fwd_emp_list[$j]['emp_dept_id'] = $a['dept_id'];
                                $fwd_emp_list[$j]['reply_status'] = "N";
                                $fwd_emp_list[$j]['is_reply_required'] = "N";
                                $j++;
                            }
                        }
                    }
            }else{
                Yii::$app->getSession()->setFlash('danger', "Invalid Forward Type.");
                return $this->redirect($url);
            }
//	    echo "<pre>"; print_r($fwd_emp_list); die;
            foreach($fwd_emp_list as $f){
                Yii::$app->fts_utility->efile_add_update_efile_dak_movement("A", NULL, $file_id, $fwd_to, $group_id, $f['employee_code'], $is_time_bound, "FileGreenSheet", $response_date, NULL, $f['is_reply_required'], $f['reply_status'], Yii::$app->user->identity->e_id, "N", $f['emp_dept_id'], $Param_file_doc_info);
            }
            
            if($fwd_to == 'E'){
                $dak_group_id = NULL;
                $fwd_emp_code =$fwd_emp_list[0]['employee_code'];
                $emp_dept_id=$fwd_emp_list[0]['emp_dept_id'];
            }elseif($fwd_to == 'G'){
                $dak_group_id = $group_id;
                $emp_dept_id = $fwd_emp_code =NULL;
            }elseif($fwd_to == 'A'){
                $dak_group_id = NULL;
                $emp_dept_id = $fwd_emp_code =NULL;
            }
            $fileinfo = Yii::$app->fts_utility->efile_get_dak($file_id, NULL, NULL, NULL);
            $action_id = $fileinfo['action_type'];
            Yii::$app->fts_utility->efile_add_update_dak_history("A", NULL, $file_id, $action_id, $fwd_to, $dak_group_id, $fwd_emp_code, $emp_dept_id);
            
                if($uploaddoc == 'Y'){
                    Yii::$app->fts_utility->efile_dak_docs($file_id, "File", NULL, NULL, $uploadDocs['doc_ext_type'], $uploadDocs['doc_path'], $uploadDocs['doc_title'], $uploadDocs['is_protected'], $uploadDocs['file_password']);
                }
                if($noteStatus == 'Y'){
                    $Param_noteid = NULL;
                    Yii::$app->fts_utility->elif_add_efile_dak_notes("A", $file_id, $note_comment, "N", $note_subject, "S", $Param_noteid, "N");
                }
                if(!empty($file_remarks)){
                    $note_subject = $Param_noteid = NULL;
                    Yii::$app->fts_utility->elif_add_efile_dak_notes("A", $file_id, $file_remarks, "N", $note_subject, "S", $Param_noteid, "R");
                }
                
                if(!empty($rec_id)){
                    EfileDakTemp::deleteAll(['employee_code'=>Yii::$app->user->identity->e_id, 'rec_id'=>$rec_id]);
                }else{
                    EfileDakTemp::deleteAll(['file_id' => null, 'employee_code'=>Yii::$app->user->identity->e_id]);
                }
                
                //Email Configuration
                Yii::$app->Dakutility->sendEmailwithAttachmenttouser($file_id, $fwd_to, $fwd_emp_list, Yii::$app->user->identity->e_id);
                
                if(!empty($cc_emp_list)){
                    $fwd_to = "CC";
                    foreach($cc_emp_list as $c){
                        Yii::$app->fts_utility->efile_add_update_efile_dak_movement("A", NULL, $file_id, $fwd_to, $group_id, $c['employee_code'], $is_time_bound, "FileGreenSheet", $response_date, NULL, $c['is_reply_required'], $c['reply_status'], Yii::$app->user->identity->e_id, "N", $c['emp_dept_id'], $Param_file_doc_info);
                        
                        Yii::$app->fts_utility->efile_add_update_dak_history("A", NULL, $file_id, $action_id, $fwd_to, $group_id, $c['employee_code'], $c['emp_dept_id']);
                    } //end
                    
                    Yii::$app->Dakutility->sendEmailwithAttachmenttouser($file_id, $fwd_to, $cc_emp_list, Yii::$app->user->identity->e_id);
                }
                Yii::$app->getSession()->setFlash('success', "File Forwarded Successfully.");
                return $this->redirect($url);
            }else{
                Yii::$app->getSession()->setFlash('danger', "Invalid Forward Type.");
                return $this->redirect($url);
            }

        }else{
                Yii::$app->getSession()->setFlash('danger', "Invalid params found.");
                return $this->redirect($url);
        }
    }
	
    public function actionDownloadgrpremarks(){
	$url = Yii::$app->homeUrl;
	if(isset($_GET['securekey']) AND !empty($_GET['securekey']) AND isset($_GET['key']) AND !empty($_GET['key']) AND isset($_GET['key1']) AND !empty($_GET['key1']) AND isset($_GET['key2']) AND !empty($_GET['key2'])){
	
		$menuid = Yii::$app->utility->decryptString($_GET['securekey']);
		$file_id = Yii::$app->utility->decryptString($_GET['key']);
		$movement_id = Yii::$app->utility->decryptString($_GET['key1']);
		$dak_group_id = Yii::$app->utility->decryptString($_GET['key2']);
		
		if(empty($menuid) OR empty($file_id) OR empty($movement_id) OR empty($dak_group_id)){
			Yii::$app->getSession()->setFlash('danger', "Invalid params values found.");
			return $this->redirect($url);	
		}
		$movement = Yii::$app->fts_utility->efile_get_efile_dak_movement(NULL, $movement_id);
		$fileinfo = Yii::$app->fts_utility->efile_get_dak($file_id, NULL, NULL, NULL);
		if(empty($fileinfo) OR empty($movement)){
			Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
			return $this->redirect($url);
		}
		if($fileinfo['is_active'] == 'N'){
			Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
			return $this->redirect($url);
		}
		if($movement['is_active'] == 'N'){
			Yii::$app->getSession()->setFlash('danger', "File has been forwarded.");
			return $this->redirect($url);
		}
		if($movement['fwd_emp_code'] != Yii::$app->user->identity->e_id){
			Yii::$app->getSession()->setFlash('danger', "You are not authorized to view this file.");
			return $this->redirect($url);
		}
		
		if($fileinfo['status'] != 'Open'){
			Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
			return $this->redirect($url);
		}
		$fileid = Yii::$app->utility->encryptString($file_id);
		$movementid = Yii::$app->utility->encryptString($movement_id);
		$url = Yii::$app->homeUrl."efile/inbox/viewdakwithnoting?securekey=$menuid&key=$fileid&key2=$movementid";
		 
		if($movement['dak_group_id'] != $dak_group_id){
		
			Yii::$app->getSession()->setFlash('danger', "Invalid Group Found.");
			return $this->redirect($url);
		}
		$html = "<div class='row'>
			<div class='col-sm-12'>";
				if(!empty($receiptInfo)){ 
				$recNo = $receiptInfo['dak_number']." Dated ".date('d-m-Y', strtotime($receiptInfo['rec_date']));
				$dist = Yii::$app->fts_utility->get_master_districts($receiptInfo['org_district'], NULL);
				$address = $receiptInfo['org_address'];
				if(!empty($dist)){
					$address .= " Distt. $dist[district_name], $dist[state_name]";
				}
				
				$empinfo = Yii::$app->utility->get_employees($receiptInfo['dak_fwd_to']);
				$fwdto = $empinfo['fullname'].", ".$empinfo['desg_name'];
				
				$html .="<h4 class='text-center'><b>Receipt Details:-</b></h4>
					<table class='table table-bordered'>
						<tr>
							<td><b>Receipt No. & Date</b><br>$recNo</td>
							<td><b>Received From </b><br> $receiptInfo[rec_from]</td>
							<td><b>Address </b><br> $address</td>
							<td><b>Received Mode</b><br> $receiptInfo[mode_of_rec]</td>
						</tr>
						<tr>
							<td><b>Summary</b><br> $receiptInfo[dak_summary]</td>
							<td><b>Remarks</b><br> $receiptInfo[dak_remarks]</td>
							<td><b>Forward On</b><br> ".date('d-m-Y', strtotime($receiptInfo['forwarded_date']))."</td>
							<td><b>Forwarded To</b><br> $fwdto</td>
						</tr>
						
					</table>
				<hr class='hrline'>";
				}
				
				$html .="<h3 class='text-center'><b>File Details:-</b></h3>";
				
				$refNo = $fileinfo['reference_num']."<br>Date ".date('d-m-Y', strtotime($fileinfo['reference_date']));
				$cat = EfileMasterCategory::find()->where(['file_category_id' => $fileinfo['file_category_id']])->asArray()->one();
				
				$project = EfileMasterProject::find()->where(['file_project_id' => $fileinfo['file_project_id']])->asArray()->one();
				
				$access_lavel = Yii::$app->fts_utility->get_efile_access_level("G", $fileinfo['access_level']);
				$html .="<table class='table table-bordered hindi' style='font-size:12px;'>
					<tr>
						<td><b>Status</b><br> $fileinfo[status]</td>
						<td><b>Ref. No. & Date</b><br>$refNo</td>
						<td><b>Category</b><br> $cat[name]</td>
					";
				if(!empty($project)){ 
					$html .="<td><b>Project Name</b><br> $project[project_name]</td>";
				}
                                $actiontype = Yii::$app->fts_utility->efile_get_actions($fileinfo['action_type'], NULL);
                                $action_type="";
                                if(empty($actiontype)){
                                    $action_type = $actiontype['action_name'];
                                }
                                
				$html .="</tr>
					<tr>
						<td><b>Is confidential?</b><br> ".Yii::$app->fts_utility->showYesNo($fileinfo['is_confidential'])."</td>
						<td><b>Priority</b><br> $fileinfo[priority]</td>
						<td><b>Action Type</b><br> $action_type</td>
						<td><b>Access Level</b><br> $access_lavel</td>
					</tr>";
					
				if(!empty($fileinfo['summary'])) {
				$html .="<tr>
						<td colspan='4' class='text-justify'><b>Subject:</b> $fileinfo[subject]</td>
					</tr>";
				} 
				if(!empty($fileinfo['remarks'])) { 
					$html .="<tr>
						<td colspan='4' class='text-justify'><b>Remarks:</b> $fileinfo[remarks]</td>
					</tr>";
				} 
				$html .="</table>
			</div>
		</div>";
		$grpInfo = EfileDakGroups::find()->where(['dak_group_id' =>$movement['dak_group_id']])->asArray()->one();
        $gmem = Yii::$app->utility->get_employees($grpInfo['created_by']);
        $gmem = $gmem['fullname'].", ".$gmem['desg_name']." ($gmem[dept_name])";
        $grpDt = date('d-M, Y', strtotime($grpInfo['created_date']));
		$grpHtml = "<h6 class='text-left hindi'><b style='color:red'>Group / Committee Name : </b><b>$grpInfo[group_name]</b></h6>
                <h6 class='text-left'><b>Created By : $gmem on $grpDt</b> </h6>";
		$members = EfileDakGroupMembers::find()->where(['dak_group_id' =>$movement['dak_group_id'], 'is_active'=>'Y'])->asArray()->all();
		$i=1;
		$group_role = "";
		$grpMem = "";
		foreach($members as $m){
			if($m['employee_code'] == Yii::$app->user->identity->e_id){
				$group_role = $m['group_role'];
			}
			$memberInfo = Yii::$app->utility->get_employees($m['employee_code']);
			$memberInfo = $memberInfo['fullname'].", ".$memberInfo['desg_name']." ($memberInfo[dept_name])";
			if($m['group_role'] == 'CH'){
				$grpMem .="<tr>
					<td>$i</td>
					<td class='hindi'>$memberInfo</td>
					<td class='hindi'>Chairman</td>
				</tr>";
				$i++;
			}elseif($m['group_role'] == 'M'){
				$grpMem .="<tr>
					<td>$i</td>
					<td class='hindi'>$memberInfo</td>
					<td class='hindi'>Member</td>
				</tr>";
				$i++;
			}
		}
		$i = $i;
		foreach($members as $m){
			$memberInfo = Yii::$app->utility->get_employees($m['employee_code']);
			$memberInfo = $memberInfo['fullname'].", ".$memberInfo['desg_name']." ($memberInfo[dept_name])";
			if($m['group_role'] == 'C'){
				$grpMem .="<tr>
					<td>$i</td>
					<td class='hindi'>$memberInfo</td>
					<td  class='hindi'>Convenor</td>
				</tr>";
				$i++;
			}
		}
		$html .="<hr class='hrline'>$grpHtml<h4 class='text-left'><b style='color:red'>Group / Committee Members </b></h4>
		<table class='table table-bordered'><tr><th>Sr. No.</th><th>Member Name</th><th></th></tr>$grpMem</table>
		";
		
		$allremartks = EfileDakGroupMembersRemarks::find()->where([
			'dak_group_id'=>$movement['dak_group_id'], 
			'file_id'=>$movement['file_id'], 
			'is_active' => 'Y',
		])->all();
		if(!empty($allremartks)){
			$i=1;
			$grpRemarks="";
			foreach($allremartks as $m){
				$role = "Member";
				if($m['group_role'] == 'CH'){
					$role = "Chairman";
				}elseif($m['group_role'] == 'C'){
					$role = "Convenor";
				}
				$memberInfo = Yii::$app->utility->get_employees($m['employee_code']);
				$Group_Role = "";
				$memberInfo = $memberInfo['fullname'].", ".$memberInfo['desg_name']." ($role)";
				$date = date('d-m-Y H:i:s', strtotime($m['created_date']));
				$grpRemarks .= "
					<tr>
						<td>$i</td>
						<td class='hindi'><u><b>$memberInfo inputs dated $date</b></u><br>$m[remarks]</td>
					</tr>";
				$i++;
			}
			$html .="<hr class='hrline'><h4 class='text-center hindi'><b>Group / Committee (Remarks / Comments) </b></h4>
				<table class='table table-bordered'><tr><th>Sr. No.</th><th>Member Name</th></tr>$grpRemarks</table>
			";
			$finalRemark = EfileDakGroupMembersRemarks::find()->where([
				'dak_group_id'=>$movement['dak_group_id'], 
				'file_id'=>$movement['file_id'], 
				'is_active' => 'Y',
				'status' => 'CHF',
			])->asArray()->one(); 
			if(!empty($finalRemark)){
				$html .="<hr class='hrline'><h4 class='text-center hindi'><b>Final Input / Decision </b></h4>
				<p class='text-justify hindi'>$finalRemark[remarks]</p>
				";
			}
			
			if($group_role == 'CH'){
				$checkMemberResponse = EfileDakGroupMemberApproval::find()->where([
					'dak_group_id'=>$movement['dak_group_id'], 
					'file_id'=>$movement['file_id'], 
					'is_active' => 'Y',
				])->one();
				if(!empty($checkMemberResponse)){
					$i=1;
					$Response = "";
					foreach($members as $m){
						if($m['employee_code'] == Yii::$app->user->identity->e_id){
							$group_role = $m['group_role'];
						}
						$memberInfo = Yii::$app->utility->get_employees($m['employee_code']);
						$memberInfo = $memberInfo['fullname'].", ".$memberInfo['desg_name']." ($memberInfo[dept_name])";

						if($m['group_role'] == 'M'){
							$dis = EfileDakGroupMemberApproval::find()->where(['dak_group_id'=>$movement['dak_group_id'], 'file_id'=>$movement['file_id'], 'is_active' => 'Y', 'employee_code'=>$m['employee_code']])->one();
							if(!empty($dis)){
								$dis = $dis->remarks_final_status;
							}
							
						  
							$Response .="<tr>
								<td>$i</td>
								<td class='hindi'>$memberInfo (Member)</td>
								<td class='hindi'>$dis</td>
							</tr>";
							$i++;
						}
					}
					$i = $i;
					foreach($members as $m){
						$memberInfo = Yii::$app->utility->get_employees($m['employee_code']);
						$memberInfo = $memberInfo['fullname'].", ".$memberInfo['desg_name']." ($memberInfo[dept_name])";
						if($m['group_role'] == 'C'){
							$dis = EfileDakGroupMemberApproval::find()->where(['dak_group_id'=>$movement['dak_group_id'], 'file_id'=>$movement['file_id'], 'is_active' => 'Y', 'employee_code'=>$m['employee_code']])->one();
							if(!empty($dis)){
								$dis = $dis->remarks_final_status;
							}
							$Response .="<tr>
								<td>$i</td>
								<td class='hindi'>$memberInfo (Convenor)</td>
								<td class='hindi'>$dis</td>
							</tr>";
							$i++;
						}
					}
					$ResponseHtml ="<hr class='hrline hindi'><h4 class='text-left'><b style='color:red'>Members Agree / Disgree with Chairman's remarks</b></h4><table class='table table-bordered'><tr><th>Sr. No.</th><th>Member Name</th><th></th></tr>'.$Response.'</table>";
					$html .=$ResponseHtml;
				}
			}
			
		}
		// echo $html; die;
		// echo "<pre>";print_r($checkMemberResponse);
		// echo "<pre>";print_r($html);
		// die;
            
		 $margin_left = 15;
                $margin_right = 15;
                $margin_top = 10;
                $margin_bottom = 10;
                $mpdfConfig = array(
                'mode' => 'utf-8', 
                'format' => 'A4',    // format - A4, for example, default ''
                'default_font_size' => 0,     // font size - default 0
                'default_font' => '',    // default font family
                'margin_left' =>$margin_left,    	// 15 margin_left
                'margin_right' => $margin_right,    	// 15 margin right
                'mgt' => $margin_top,     // 16 margin top
                'mgb' => $margin_bottom,    	// margin bottom
                'margin_header' =>  $margin_top,     // 9 margin header
                'margin_footer' =>$margin_bottom,
                'orientation' => ''  	// L - landscape, P - portrait
                );
                $mpdf = new \Mpdf\Mpdf($mpdfConfig);
        $stylesheet = file_get_contents("./css/mpdf_csss.css"); // external css
        $mpdf->WriteHTML($stylesheet, 1);
	       $deplLogo=getcwd()."/images/cdac.jpeg";
        //$deplLogo=Yii::$app->homeUrl."images/logo_cdac.png";
        $swachhbharatabhiyan=getcwd()."/images/swacchbharatlogo.jpeg";
        $header = "<div class='headerdiv'>
				<div class='headerdivLeft'><img src='$deplLogo' class='logo' /></div>
				<div class='headerdivcenter'>
					<div class='headerDetail'>
					<h4 class='hindi' style='text-align:center;'>प्रगत संगणन विकास केंद्र, मोहाली</h4>
					<h4 style='text-align:center;'>CENTRE FOR DEVELOPMENT OF ADVANCED COMPUTING</h4>
					<h6 style='text-align:center;'>MOHALI</h6>
					</div>
				</div>
				<div class='headerdivright'><img src='$swachhbharatabhiyan' class='logo' /></div><hr></div>";
        
		// echo "$header $html";die;
//        $mpdf->SetImportUse();
        $i=1;
        $DepartmentName = "C-DAC, Mohali";
        $d= date('d-m-Y H:i:s');
        $footer = "<table class='mpdf_width'>
                <tr>
                <td class='footerDept'>$DepartmentName</td>
                <td class='footerPage'>Page {PAGENO} of {nbpg}</td>
                <td class='footerDate' align='right'>Printed on : $d</td>
                </tr>
                </table>
                ";
		$mpdf->SetFooter($footer);
        $mpdf->WriteHTML($header.$html);
        $printDt = date('d-m-Y H:i:s');
        $name = "group_remarks_sheet".date('Y_m_d_H_i_s').".pdf";
        $file = $mpdf->Output($name, 'I');
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header("Cache-Control: max-age=0");
        readfile($file);
            		// echo "<pre>";print_r($movement);
		// die("OKKK");
            
	
	}else{
				Yii::$app->getSession()->setFlash('danger', "Invalid params found.");
			 return $this->redirect($url);
	
	}

	}
	
    public function actionCheckvalidpdf(){
        if(isset($_FILES['file']['tmp_name']) AND !empty($_FILES['file']['tmp_name'])){
//            echo "<pre>"; print_r($_FILES); die;
            $pdf_type = $_FILES['file']['type'];
            $pdf_tmp_name = $_FILES['file']['tmp_name'];
            $pdf_size = $_FILES['file']['size'];
            $pdf_name = $_FILES['file']['name'];
            $chk = substr_count($pdf_name, '.');
            if($chk > 1 OR $chk == 0){
                $result = array();
                $result['Status'] = "FF";
                $result['Res'] = "File name not valid.";
                echo json_encode($result);
                die;
            }
            
            $chk1 = $chk = "";
            // die($pdf_type);
            $chk = Yii::$app->fts_utility->validatePdfFileType($pdf_type);
            $chk2 = Yii::$app->fts_utility->validatePdfFileSize($pdf_size);
            $chk1 = Yii::$app->fts_utility->validatePdfFile($pdf_tmp_name);
            $error = "";
            if(empty($chk) OR empty($chk1)){ $error = "Upload Valid PDF File"; }
            if(empty($chk2)){ $error = "PDF file size should be less then ".FTS_Doc_Size."MB"; }

            if(!empty($error)){
                $result = array();
                $result['Status'] = "FF";
                $result['Res'] = $error;
                echo json_encode($result);
                die;
            }
            
        }else{
            $result = array();
            $result['Status'] = "FF";
            $result['Res'] = "Invalid file params found.";
            echo json_encode($result);
            die;
        }
        
    }
    public function actionCheckvalidimage(){

        if(isset($_FILES['file']['tmp_name']) AND !empty($_FILES['file']['tmp_name'])){
            $image_type = $_FILES['file']['type'];
            $image_tmp_name = $_FILES['file']['tmp_name'];
            $image_size = $_FILES['file']['size'];
            $image_name = $_FILES['file']['name'];
            $error = $chk1 = $chk = "";
            $chk = Yii::$app->fts_utility->validateImage($image_type, $image_tmp_name);
            if(empty($chk)){
                $error = "Upload Valid Images of File";
            }
            $chk1 = Yii::$app->fts_utility->validateImageSize($image_size);
            if(empty($chk1)){
                $error .= "Each image size should be less then ".FTS_Image_Size."MB";
            }
            if(!empty($error)){
                $result = array();
                $result['Status'] = "FF";
                $result['Res'] = $error;
                echo json_encode($result);
                die;
            }
            
        }else{
            $result = array();
            $result['Status'] = "FF";
            $result['Res'] = "Invalid file params found.";
            echo json_encode($result);
            die;
        }
        
    }
    
    public function actionView_dakfile(){
        
        if (!\Yii::$app->user->isGuest) {
            if(isset($_POST['file_id']) AND !empty($_POST['file_id']) AND isset($_POST['user_id']) AND !empty($_POST['user_id'])){
//                echo "<pre>";print_r($_POST);
                $fileid = Yii::$app->utility->decryptString($_POST['file_id']);
                $user_id = Yii::$app->utility->decryptString($_POST['user_id']);
//                echo "$file_id <br>";
//                echo "$user_id <br>";
//                die;
                if(empty($fileid) OR empty($user_id)){
                    $result = array();
                    $result['Status'] = "FF";
                    $result['Res'] = "Invalid params value found.";
                    echo json_encode($result);
                    die;
                }
                
                if($user_id != Yii::$app->user->identity->map_id){
                    $result = array();
                    $result['Status'] = "FF";
                    $result['Res'] = "You are not authorized to view file.";
                    echo json_encode($result);
                    die;
                }
                
                $filedocs = Yii::$app->Dakutility->efile_get_dak_docs($fileid,NULL);
                if(empty($filedocs)){
                    $result = array();
                    $result['Status'] = "FF";
                    $result['Res'] = "No File Found.";
                    echo json_encode($result);
                    die;
                }
//		echo "<pre>";print_R($filedocs); die;
                $createFolder = getcwd().FTS_Documents;
                if(!file_exists($createFolder))
                {
                    mkdir($createFolder, 0777, true);
                }
                $finalPath = $createFolder."temp_folder/".$fileid.".pdf";
                $outputName=FTS_Documents."temp_folder/".$fileid.".pdf";
                if(!empty($filedocs))
                {
                    $cmd = "gs -q -dNOPAUSE -dBATCH -dAutoRotatePages=1 -sPAPERSIZE=legal -sDEVICE=pdfwrite -sOutputFile=$finalPath ";
                    foreach($filedocs  as $key=>$value)
                    {
                        if($value['is_active']=='Y'){
                            $path = getcwd().$value['docs_path'];
                            if(!empty($path))
                            {
                                $ext= explode(".", $path);
                                $ext=$ext[1];
                                $chkext=$value["doc_ext_type"];
                                if($ext=="pdf" || $ext=="PDF" || $chkext=="PDF") 
                                {        
                                    $cmd .= $path." ";
                                    $result = shell_exec($cmd);
                                }
                            }
                        }
                    }
                }
           
                $margin_left = 10;
                $margin_right = 10;
                $margin_top = 5;
                $margin_bottom = 5;
                $mpdfConfig = array(
                        'mode' => 'utf-8', 
                        'format' => 'A4',    // format - A4, for example, default ''
                        'default_font_size' => 0,     // font size - default 0
                        'default_font' => '',    // default font family
                        'margin_left' =>$margin_left,    	// 15 margin_left
                        'margin_right' => $margin_right,    	// 15 margin right
                        'mgt' => $margin_top,     // 16 margin top
                        'mgb' => $margin_bottom,    	// margin bottom
                        'margin_header' =>  $margin_top,     // 9 margin header
                        'margin_footer' =>$margin_bottom,
                        'orientation' => ''  	// L - landscape, P - portrait
                );
                $mpdf = new \Mpdf\Mpdf($mpdfConfig);
                $stylesheet = file_get_contents("./css/mpdf_csss.css"); // external css
                $mpdf->WriteHTML($stylesheet, 1);
        
                $path = getcwd().$outputName;
                $name = Yii::$app->user->identity->fullname.", ".Yii::$app->user->identity->desg_name." (".Yii::$app->user->identity->e_id.")";
                $printdate = date('d-M-Y H:i:s');
               $footerHtml ="<div style='width:49%;float:left;text-align:left;color:lightgrey;'>$name</div><div style='width:49%;float:right;color:lightgrey;'>Printed Date: $printdate</div><div style='clear:both;'></div>";
                $mpdf->SetFooter($footerHtml);
                
                $pagecount = $mpdf->SetSourceFile($path);
                for ($i=1; $i<=$pagecount; $i++){
                    $tplId = $mpdf->ImportPage($i);	
        //            $tplId = $mpdf->ImportPage($pageNo);
                    $size = $mpdf->GetTemplateSize($tplId);
        //            echo "<pre>";print_r($size); die;
                    $w=220;
                    if($size['width'] > $size['height']){
                        $w=220;
                    }
                    $mpdf->AddPageByArray([
                    'orientation' => $size['width'] > $size['height'] ? 'L' : 'P'
                    ]);
                    $mpdf->UseTemplate($tplId,0,0,$w);
                    //$mpdf->UseTemplate($import_page);

        //            if ($i <= $pagecount){
        //                if($i != $pagecount){
        //                    $mpdf->AddPage();
        //                }
        //            }
                }
//                for ($i=1; $i<=$pagecount; $i++){
//                    $import_page = $mpdf->ImportPage($i);	
//                    $mpdf->UseTemplate($import_page);
//                    if ($i <= $pagecount){
//                        if($i != $pagecount){
//                            $mpdf->AddPage();
//                        }
//                    }
//                }
                $rand_num = rand(1000,100000);
                $current_date = strtotime(date('Y-m-d H:i:s')).$rand_num;
                $FileName = $current_date."_download_document.pdf";
                $FilePath = Yii::$app->basePath."/assets/";
//                echo $FileName; die;
                $mpdf->Output($FilePath.$FileName, 'F');
                $result = array();
                $result['Status'] = "SS";
                $result['Res'] = $FileName;
                echo json_encode($result);
                die;
                
            }else{
                $result = array();
                $result['Status'] = "FF";
                $result['Res'] = "Invalid params found.";
                echo json_encode($result);
                die;
            }
        }else{
            $result = array();
            $result['Status'] = "FF";
            $result['Res'] = "Session Time Out.";
            echo json_encode($result);
            die;
        }
    }
    public function actionDownload_dakfile()
    {
       // echo "<pre>";print_r($_REQUEST); die;
        if(isset($_GET['filename']) && !empty($_GET['filename']))
        {
            $fileName = $_GET['filename'];
            $FilePath = Yii::$app->basePath."/assets/";
            $file = $FilePath.$fileName;
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="'.$fileName.'"');
            header("Cache-Control: max-age=0");
            readfile($file);
            unlink($file);
            die;
    }
        $return = array();
        $return['STATUS_ID']="111";   
        $return['STATUS_MSG']="FAILURE";
        $return['STATUS_RESPONSE']="Invalid Request";
        echo json_encode($return); die;  
    }
    
    public function actionRemoveview_dakfile()
    {
//        echo "<pre>";print_r($_GET); die;
        if(isset($_POST['path']) && !empty($_POST['path']))
        {
            $fileName = $_POST['path'];
            Yii::$app->fts_utility->removefile($fileName);
        }
    }
    
    public function actionDak_draft(){
        
        if(isset($_POST['formdata']) AND !empty($_POST['formdata'])){
            $post = array();
            parse_str($_POST['formdata'],$post);
            
            
            $randum_number = $post['randum_number'];
//            echo "<pre>";print_r($post); die;
            $model = EfileDakTemp::find()->where(['temp_id'=>$randum_number, 'employee_code'=>Yii::$app->user->identity->e_id])->one();
            
            if(empty($model)){
                $model = new EfileDakTemp();
                $model->temp_id = $randum_number;
            }
            
            $file_type = "N";
            if(!empty($post['rec_id'])){
                $file_type = "R";
            }
            $file_category_id = Yii::$app->utility->decryptString($post['EfileDak']['file_category_id']);
            $file_project_id = Yii::$app->utility->decryptString($post['EfileDak']['file_project_id']);
            $action_type = Yii::$app->utility->decryptString($post['EfileDak']['action_type']);
            $access_level = Yii::$app->utility->decryptString($post['EfileDak']['access_level']);
            $priority = Yii::$app->utility->decryptString($post['EfileDak']['priority']);
            $is_confidential = Yii::$app->utility->decryptString($post['EfileDak']['is_confidential']);
            
            $rec_id = Yii::$app->utility->decryptString($post['rec_id']);
            $model->employee_code = Yii::$app->user->identity->e_id;
            $model->rec_id = $rec_id;
            $model->file_type = $file_type;
            $model->initiate_type = $post['initiate_type'];
            $model->reference_num = $post['EfileDak']['reference_num'];
            $model->reference_date = date('Y-m-d', strtotime($post['EfileDak']['reference_date']));
            $model->subject = $post['EfileDak']['subject'];
            $model->file_category_id = $file_category_id;
            $model->file_project_id = $file_project_id;
            $model->action_type = $action_type;
            $model->access_level = $access_level;
            $model->priority = $priority;
            $model->is_confidential = $is_confidential;
            $model->meta_keywords = $post['EfileDak']['meta_keywords'];
            $model->remarks = $post['EfileDak']['remarks'];
            $model->summary = $post['EfileDak']['summary'];
            $model->note_subject = $post['EfileDak']['note_subject'];
            $model->note_comment = $post['EfileDak']['note_comment'];
            $model->file_remarks = $post['EfileDak']['file_remarks'];
//            echo "<pre>";print_r($model);
             if(!$model->validate()){
//                    echo "<pre>";print_r($model->getErrors());
//                    die;
                $result = array();
                $result['Status'] = "FF";
                $result['Res'] = "Model validation Error.";
                echo json_encode($result); die;
            }else{
                $model->save();
            
                $result = array();
                $result['Status'] = "SS";
                $result['Res'] = "Data saved temporary.";
                echo json_encode($result); die;
            }
            //echo "<pre>"; print_r($model); die;
        }
        
    }
    
    
    public function actionFile_draft(){
        if(isset($_POST['Newnote_key']) AND !empty($_POST['Newnote_key']) AND isset($_POST['note_subject']) AND isset($_POST['note_comment']) AND isset($_POST['file_remarks'])){
            
            $file_id = Yii::$app->utility->decryptString($_POST['Newnote_key']);
            if(!empty($file_id)){
                $check = EfileDakMovement::find()->where(['file_id'=>$file_id, 'fwd_emp_code'=>Yii::$app->user->identity->e_id, 'is_active'=>'Y'])->one();
                if(!empty($check)){
                    $fileinfo = Yii::$app->fts_utility->efile_get_dak($file_id, NULL, NULL, NULL);
                    if(!empty($fileinfo)){
                        $model = EfileDakTemp::find()->where(['file_id'=>$file_id, 'employee_code'=>Yii::$app->user->identity->e_id])->one();
                        if(empty($model)){
                            $model = new EfileDakTemp();
                        }
                        $note_subject = Yii::$app->fts_utility->validateHindiString($_POST['note_subject']);
                        $note_comment = trim($_POST['note_comment']);
                        $file_remarks = Yii::$app->fts_utility->validateHindiString($_POST['file_remarks']);
                        $model->employee_code = Yii::$app->user->identity->e_id;
                        $model->file_id = $file_id;
                        $model->note_subject = $note_subject;
                        $model->note_comment = $note_comment;
                        $model->note_comment = $note_comment;
                        $model->file_remarks = $file_remarks;

                        $model->save();
                        $result = array();
                        $result['Status'] = "SS";
                        $result['Res'] = "Data saved temporary.";
                        echo json_encode($result); die;
                    }
                }
            }
        }
    }
    
    public function actionAddnewgroupmember(){
        if (\Yii::$app->user->isGuest) {
            $result = array();
            $result['Status'] = "FF";
            $result['Res'] = "Session TimeOut. Re-login Please.";
            echo json_encode($result); die;
        }
        if(isset($_POST['formdata']) AND !empty($_POST['formdata'])){
            $post = array();
            parse_str($_POST['formdata'],$post);
            $file_id = Yii::$app->utility->decryptString($post['key']);
            $dak_group_id = Yii::$app->utility->decryptString($post['key1']);
            $add_mem_dept_id = Yii::$app->utility->decryptString($post['add_mem_dept_id']);
            $add_mem_emp_code = Yii::$app->utility->decryptString($post['add_mem_emp_code']);
            if(empty($file_id) OR empty($dak_group_id) OR empty($add_mem_dept_id) OR empty($add_mem_emp_code)){
                $result = array();
                $result['Status'] = "FF";
                $result['Res'] = "Invalid Params Value Found.";
                echo json_encode($result); die;
            }
            
            $fileinfo = Yii::$app->fts_utility->efile_get_dak($file_id, NULL, NULL, NULL);
            if(empty($fileinfo)){
                $result = array();
                $result['Status'] = "FF";
                $result['Res'] = "No File Record Found.";
                echo json_encode($result); die;
            }
            if($fileinfo['is_active'] == 'N'){
                $result = array();
                $result['Status'] = "FF";
                $result['Res'] = "No File Record Found.";
                echo json_encode($result); die;
            }
            
            if($fileinfo['status'] != 'Open'){
                $result = array();
                $result['Status'] = "FF";
                $result['Res'] = "No File Record Found.";
                echo json_encode($result); die;
            }
            
            $allMembers = EfileDakGroupMembers::find()->where(['dak_group_id'=>$dak_group_id, 'is_active'=>'Y'])->all();
            if(!empty($allMembers)){
                $msg = "";
                foreach($allMembers as $a){
                    if($a->employee_code == $add_mem_emp_code){
                        $msg = "Selected Member Already Added in Group / Committee";
                    }
                }
                if(!empty($msg)){
                    $result = array();
                    $result['Status'] = "FF";
                    $result['Res'] = $msg;
                    echo json_encode($result); die;
                }
            }

            $model = new EfileDakGroupMembers();
            $model->dak_group_id = $dak_group_id;
            $model->employee_code = $add_mem_emp_code;
            $model->emp_dept_id = $add_mem_dept_id;
            $model->group_role = "M";
            $model->created_date = date('Y-m-d H:i:s');
            $model->is_active = "Y";
            $model->save();
            $Param_file_doc_info = NULL;
            Yii::$app->fts_utility->efile_add_update_efile_dak_movement("A", NULL, $file_id, "G", $dak_group_id, $add_mem_emp_code, "N", "FileGreenSheet", NULL, NULL, "N", "N", Yii::$app->user->identity->e_id, "N", $add_mem_dept_id, $Param_file_doc_info);
            
            $fwd_emp_list = array();
            $fwd_emp_list[0]['employee_code'] = $add_mem_dept_id;
            Yii::$app->Dakutility->sendEmailwithAttachmenttouser($file_id, "E", $fwd_emp_list, Yii::$app->user->identity->e_id);
            
            $result = array();
            $result['Status'] = "SS";
            $result['Res'] = "Member Added Successfully.";
            echo json_encode($result); die;
            //echo "<pre>";print_r($post); die;
        }else{
            $result = array();
            $result['Status'] = "FF";
            $result['Res'] = "Invalid Params Found.";
            echo json_encode($result); die;
        }
    }
	
	public function actionUrlencryption(){
		if(isset($_GET['key']) AND !empty($_GET['key']) AND isset($_GET['key1']) AND !empty($_GET['key1']) AND isset($_GET['key2']) AND !empty($_GET['key2'])){
            $file = Yii::$app->utility->decryptString($_GET['key']);
            $ext = Yii::$app->utility->decryptString($_GET['key1']);
            $mime_type = Yii::$app->utility->decryptString($_GET['key2']);
            if(empty($file) OR empty($ext) OR empty($mime_type)){
				echo "<br><br><div style='text-align:center; background:red; color:#fff; font-size:28px; padding:15%;'>e-File Open Error<br><br>Invalid Param Value Found. Contact Admin.</div>";
				die;
            }
			$file = substr($file,1);
            $FileName = mt_rand().".$ext";
            header("Content-Type: $mime_type");
            header("Content-Disposition: inline; filename=$FileName");           
            header("Cache-Control: max-age=0");
            readfile($file);
        }else{
			echo "<br><br><div style='text-align:center; background:red; color:#fff; font-size:28px; padding:15%;'>e-File Open Error<br><br>Invalid Param Found. Contact Admin.</div>";
				die;
		}
        
    }
    
    public function actionGetempforcopyto(){
        $result = array();
        if (!\Yii::$app->user->isGuest) {
        if(isset($_POST['dept_id']) AND !empty($_POST['dept_id'])){
            $dept_id = Yii::$app->utility->decryptString($_POST['dept_id']);
            if(empty($dept_id)){
                $result['Status'] = 'FF';
                $result['Res'] = 'Invalid params value found..';
                echo json_encode($result);
                die;
            }
            $allemps = Yii::$app->utility->get_dept_emp($dept_id);
            if(empty($allemps)){
                $result['Status']= 'FF';
                $result['Res']= 'No Employee List Found';
                echo json_encode($result); die;
            }
            $html = "<b>List of Employees</b><hr style='margin:0px;'>";
            $did = Yii::$app->utility->encryptString($dept_id);
//            echo "<pre>";print_r($allemps); die;
            foreach($allemps as $emp){
                $employee_code = base64_decode($emp['employee_code']);
                $Super_Admin_Emp_Code = Super_Admin_Emp_Code;
                if($employee_code == Yii::$app->user->identity->e_id){
                }elseif($employee_code == $Super_Admin_Emp_Code){
                }else{
                    $rand = $employee_code;
                    $employee_code = Yii::$app->utility->encryptString($employee_code);
                    
                    $name = $emp['name'];
                    $html .= "<li><input type='checkbox' class='selectecc' data-id='$rand' data-key='$employee_code' data-key1='$did' data-key2='$name' />$name</li>";
                }
            }
            $result['Status']= 'SS';
            $result['Res']= $html;
            echo json_encode($result); die;
        }
        }
    }
}
