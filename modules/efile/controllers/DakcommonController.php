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
            $note_comment = Yii::$app->fts_utility->validateHindiString($post['note_comment']);
            if(!empty($_POST['rec_id'])){
                $rec_id = Yii::$app->utility->decryptString($_POST['rec_id']);
                if(empty($rec_id )){
                    Yii::$app->getSession()->setFlash('danger', "Invalid params value found.");
                    return $this->redirect($url);
                }
                $file_type = "R";
            }else{
                $initiate_type = $_POST['initiate_type'];
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
                
                
                
                $sent_for_scan = "Y";

                // Save file
                $file_id = Yii::$app->fts_utility->efile_add_update_efile_dak("A", NULL, $rec_id, $file_type, $reference_num, $reference_date, $subject, $file_category_id, $file_project_id, $action_type, $access_level, $priority, $is_confidential, $meta_keywords, $remarks, $summary, "Scan", $sent_for_scan, Yii::$app->user->identity->dept_id);

                if(!empty($file_id)){
                    if(!empty($rec_id)){
                        $rec = EfileDakReceived::find()->where(['is_active' => 'Y', 'rec_id'=>$rec_id])->one();;
                        if(!empty($rec)){
                            $rec->status = "Received";
                            $rec->save();
                            }
                        }
                        
                        $empInfo = Yii::$app->utility->get_employees($scanEmpCode);
                        
                        Yii::$app->fts_utility->efile_add_update_efile_dak_movement("A", NULL, $file_id, "E", NULL, $scanEmpCode, "N", "FileGreenSheet", $response_date, NULL, "Y", "N", Yii::$app->user->identity->e_id, "N", $empInfo['dept_id']);

                        $historyModel = new EfileDakHistory();
                        $historyModel->file_id = $file_id;
                        $historyModel->fwd_to = "E";
                        $historyModel->dak_group_id = NULL;
                        $historyModel->fwd_emp_code = $scanEmpCode;
                        $historyModel->fwd_emp_dept_id = $empInfo['dept_id'];
                        $historyModel->fwd_by = Yii::$app->user->identity->e_id;
                        $historyModel->created_date = date('Y-m-d H:i:s');
                        $historyModel->is_active = "Y";
                        $historyModel->save();
                        
                        // For Scan Email 
                        $fwd_emp_list = array();
                        $fwd_emp_list[0]['employee_code'] = $scanEmpCode;
                        Yii::$app->Dakutility->sendEmailwithAttachmenttouser($file_id, "E", $fwd_emp_list, Yii::$app->user->identity->e_id);
                        
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
			
            $file_remarks = Yii::$app->fts_utility->validateHindiString($post['file_remarks']);
			// echo "<pre>";print_r($post);die;
            $uploaddoc = 'N';
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
                        }else{
                            Yii::$app->getSession()->setFlash('danger', "Upload sccaned PDF File.");
                            return $this->redirect($url);
                        }
                    }elseif($doc_type == 'Image'){
                        if(isset($_FILES['image_path']['tmp_name'][0]) AND !empty($_FILES['image_path']['tmp_name'][0])){
                            $upload_Tye = "Image";
                            $image_type = $_FILES['image_path']['type'];
                            $image_tmp_name = $_FILES['image_path']['tmp_name'];
                            $image_size = $_FILES['image_path']['size'];
                            $image_name = $_FILES['image_path']['name'];

                            $i=0;
                            foreach($image_type as $key=>$type){
                                $error = "";
                                $chk1 = $chk = "";
                                $chk = Yii::$app->fts_utility->validateImage($type, $image_tmp_name[$key]);
                                if(empty($chk)){
                                    $error = "Upload Valid Images of File";
                                }
                                $chk1 = Yii::$app->fts_utility->validateImageSize($image_size[$key]);
                                if(empty($chk1)){
                                    $error .= "Each image size should be less then ".FTS_Image_Size."MB";;
                                }
                                if(!empty($error)){
                                    Yii::$app->getSession()->setFlash('danger', $error); 
                                    return $this->redirect($url);
                                }
                                $data = file_get_contents($image_tmp_name[$key]);
                                $base64 = 'data:image/' . $image_type[$key] . ';base64,' . base64_encode($data);
                                $docFile[$i]['doc_ext_type']= 'Image';
                                $docFile[$i]['tmp_name']= $image_tmp_name[$key];
                                $docFile[$i]['file_name']= $image_name[$key];
                                $docFile[$i]['base64']= $base64;
                                $i++;
                            }
                        }else{
                            Yii::$app->getSession()->setFlash('danger', "Upload sccaned images of File.");
                            return $this->redirect($url);
                        }
                    }else{
                        Yii::$app->getSession()->setFlash('danger', "Invalid document type found.");
                        return $this->redirect($url);
                    }
                }
            }
            if($uploaddoc == 'N' AND $noteStatus == 'N'){
                    Yii::$app->getSession()->setFlash('danger', "Required Add Note Comment OR Upload File.");
                    return $this->redirect($url);
            }
			 // echo "---------<pre>";print_r($_POST);
			 // die("OK");
			
			// =======If Fwd
            if($_POST['forward_dak'] == 'Y'){
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

                }elseif($upload_Tye == 'Image'){
                    $doc_path = Yii::$app->fts_utility->uploadImageTopdf($docFile, $FTS_Documents);
                    if(empty($doc_path)){
                            Yii::$app->getSession()->setFlash('danger', "File document didn\'t uploaded. Contact Admin.");
                            return $this->redirect($url);
                    }
                    $uploadDocs['doc_path'] = $doc_path;
                    $uploadDocs['doc_ext_type'] = "PDF";
                }
            } //end if uploaddoc = Y
            // Save file
            $file_id = Yii::$app->fts_utility->efile_add_update_efile_dak("A", NULL, $rec_id, $file_type, $reference_num, $reference_date, $subject, $file_category_id, $file_project_id, $action_type, $access_level, $priority, $is_confidential, $meta_keywords, $remarks, $summary, "Open", $sent_for_scan, Yii::$app->user->identity->dept_id);

            if(empty($file_id)){
                if(!empty($uploadDocs['doc_path'])){
                    Yii::$app->fts_utility->removefile($uploadDocs['doc_path']);
                }

                Yii::$app->getSession()->setFlash('danger', "Dak didn\'t Created. Contact Admin.");
                return $this->redirect($url);
            }


            $i=0;
// die($file_remarks);
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
                    Yii::$app->fts_utility->efile_dak_docs($file_id, "File", NULL, NULL, $uploadDocs['doc_ext_type'], $uploadDocs['doc_path']); 
                }
                if($noteStatus == 'Y'){
                    $Param_noteid = NULL;
                    Yii::$app->fts_utility->elif_add_efile_dak_notes("A", $file_id, $note_comment, "N", $note_subject, "S", $Param_noteid, "N");
                }
                if(!empty($file_remarks)){
                    $note_subject = $Param_noteid = NULL;
                    Yii::$app->fts_utility->elif_add_efile_dak_notes("A", $file_id, $file_remarks, "N", $note_subject, "S", $Param_noteid, "R");
                }


                Yii::$app->fts_utility->efile_add_update_efile_dak_movement("A", NULL, $file_id, "E", NULL, Yii::$app->user->identity->e_id, "N", "FileGreenSheet", $response_date, NULL, "Y", "N", $fwd_by, $is_initiate_file, Yii::$app->user->identity->dept_id);

                Yii::$app->getSession()->setFlash('success', "File Added Successfully. Go to your Inbox");
                return $this->redirect($url);
            }elseif($_POST['forward_dak'] == 'Y'){
                $fwd_emp_list = array();
                if($_POST['forward_type'] == 'I'){
                    $fwd_to = "E";
                    $indi_emp_code = Yii::$app->utility->decryptString($_POST['indi_emp_code']);
                    $fwd_emp_dept_id =  Yii::$app->utility->decryptString($_POST['indi_dept_id']);
                    if(empty($indi_emp_code)){
                        // remove file
                        Yii::$app->fts_utility->efile_add_update_efile_dak("R", $file_id, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
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

                }elseif($_POST['forward_type'] == 'G'){
                    $group_name = Yii::$app->fts_utility->validateHindiString($_POST['group_name']);
                    if(empty($group_name)){
                        // remove file
                        Yii::$app->fts_utility->efile_add_update_efile_dak("R", $file_id, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
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
                        Yii::$app->fts_utility->efile_add_update_efile_dak("R", $file_id, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
                        if(!empty($uploadDocs['doc_path'])){
                                Yii::$app->fts_utility->removefile($uploadDocs['doc_path']);
                        }

                        Yii::$app->getSession()->setFlash('danger', "Invalid Emp Code of Group Chairman / Convenor Member.");
                        return $this->redirect($url);
                    }

                    if($group_chairman_emp_code == $group_convenor_emp_code){
                        // remove file
                        Yii::$app->fts_utility->efile_add_update_efile_dak("R", $file_id, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
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
                        Yii::$app->fts_utility->efile_add_update_efile_dak("R", $file_id, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
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
                                Yii::$app->fts_utility->efile_add_update_efile_dak("R", $file_id, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
                                if(!empty($uploadDocs['doc_path'])){
                                        Yii::$app->fts_utility->removefile($uploadDocs['doc_path']);
                                }
                                Yii::$app->getSession()->setFlash('danger', "Invalid Emp Code of Group Members.");
                                return $this->redirect($url);
                        }
                        $group_members[$g]['employee_code']=$employee_code;
                        $group_members[$g]['emp_dept_id']=$emp_dept_id;
                        $group_members[$g]['group_role']="M";
                        $g++;
                    }

                    $group_id = Yii::$app->fts_utility->efile_add_update_dak_groups("A", NULL, $file_id, $group_name, NULL);

                    if(empty($group_id)){
                        // remove file
                        Yii::$app->fts_utility->efile_add_update_efile_dak("R", $file_id, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

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
                Yii::$app->fts_utility->efile_add_update_efile_dak_movement("A", NULL, $file_id, $fwd_to, $group_id, $f['employee_code'], $is_time_bound, "FileGreenSheet", $response_date, NULL, $f['is_reply_required'], $f['reply_status'], Yii::$app->user->identity->e_id, "N", $f['emp_dept_id']);
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

                $historyModel = new EfileDakHistory();
                $historyModel->file_id = $file_id;
                $historyModel->fwd_to = $fwd_to;
                $historyModel->dak_group_id = $dak_group_id;
                $historyModel->fwd_emp_code = $fwd_emp_code;
                $historyModel->fwd_emp_dept_id = $emp_dept_id;
                $historyModel->fwd_by = Yii::$app->user->identity->e_id;
                $historyModel->created_date = date('Y-m-d H:i:s');
                $historyModel->is_active = "Y";
                $historyModel->save();

                if($uploaddoc == 'Y'){
                    Yii::$app->fts_utility->efile_dak_docs($file_id, "File", NULL, NULL, $uploadDocs['doc_ext_type'], $uploadDocs['doc_path']); 
                }
                if($noteStatus == 'Y'){
                    $Param_noteid = NULL;
                    Yii::$app->fts_utility->elif_add_efile_dak_notes("A", $file_id, $note_comment, "N", $note_subject, "S", $Param_noteid, "N");
                }
                if(!empty($file_remarks)){
                    $note_subject = $Param_noteid = NULL;
                    Yii::$app->fts_utility->elif_add_efile_dak_notes("A", $file_id, $file_remarks, "N", $note_subject, "S", $Param_noteid, "R");
                }
                
                //Email Configuration
                Yii::$app->Dakutility->sendEmailwithAttachmenttouser($file_id, $fwd_to, $fwd_emp_list, Yii::$app->user->identity->e_id);
                
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
				$html .="</tr>
					<tr>
						<td><b>Is confidential?</b><br> ".Yii::$app->fts_utility->showYesNo($fileinfo['is_confidential'])."</td>
						<td><b>Priority</b><br> $fileinfo[priority]</td>
						<td><b>Action Type</b><br> $fileinfo[action_type]</td>
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
            $pdf_type = $_FILES['file']['type'];
            $pdf_tmp_name = $_FILES['file']['tmp_name'];
            $pdf_size = $_FILES['file']['size'];
            $pdf_name = $_FILES['file']['name'];
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
	
    // public function actionIndex(){
        // $url = Yii::$app->homeUrl;
		
		// if(isset($_POST['key']) AND !empty($_POST['key']) AND isset($_POST['key1']) AND !empty($_POST['key1']) AND isset($_POST['fwd_type']) AND !empty($_POST['fwd_type']) AND isset($_POST['fwd_note']) AND !empty($_POST['fwd_note']) AND isset($_POST['key2']) AND !empty($_POST['key2'])){
			// $dak_id = Yii::$app->utility->decryptString($_POST['key']);
			// $status = Yii::$app->utility->decryptString($_POST['key1']);
			// $menuid = Yii::$app->utility->decryptString($_POST['key2']);
			// $fwd_type = Yii::$app->fts_utility->onlyCharacter($_POST['fwd_type']);
			// $fwd_note = Yii::$app->fts_utility->validateHindiString($_POST['fwd_note']);
			// echo "**** $fwd_type<pre>";print_r($_POST); die;
			// if(empty($dak_id) OR empty($status) OR empty($menuid) OR empty($fwd_type) OR empty($fwd_note)){
				// die("1");
				// Yii::$app->getSession()->setFlash('danger', "invalid params value found."); 
				// return $this->redirect($url);
			// }
			// echo "$fwd_type <br>";
			// if($fwd_type == 'R' OR $fwd_type == 'F'){
				// if($fwd_type == 'R'){
					
				// }
			// }else{
				// Yii::$app->getSession()->setFlash('danger', "invalid params value found."); 
				// return $this->redirect($url);
			// }
			
			// $url = Yii::$app->homeUrl."filetracking/dak/inbox?securekey=$menuid";
			// $info = FileDak::find()->where(['is_active' => 'Y', 'dak_id'=>$dak_id, 'status'=>$status])->one();
			
			// if(empty($info)){
				// Yii::$app->getSession()->setFlash('danger', "No Record Found."); 
				// return $this->redirect($url);
			// }
			
			
			
			// echo "**** $fwd_type<pre>";print_r($info); die;
		// }else{
			// Yii::$app->getSession()->setFlash('danger', 'invalid params found.'); 
			// return $this->redirect($url);
		// }
        
    // }
}
 ?>
