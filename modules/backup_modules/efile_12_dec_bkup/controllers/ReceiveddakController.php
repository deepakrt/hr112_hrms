<?php
namespace app\modules\efile\controllers;
use yii\web\Controller;
use Yii;
use app\models\EfileDak;
use app\models\EfileMasterCategory;
class ReceiveddakController extends Controller
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
        // $url = Yii::$app->homeUrl."filetracking/dak/draft?securekey=$menuid";
        $this->layout = '@app/views/layouts/admin_layout.php';
		
        $lists = Yii::$app->fts_utility->efile_get_dak_received(NULL, Yii::$app->user->identity->e_id);
		// echo "<pre>";print_r($lists); die;
        return $this->render('index', ['menuid'=>$menuid,'lists'=>$lists]);
    }
    public function actionViewrecieveddak(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."efile/receiveddak/index?securekey=$menuid";
		// echo "<pre>";print_r($_POST);
		// echo "<pre>";print_r($_GET);
		// echo "<pre>";print_r($_FILES);
		// die;
		/*
		if(isset($_POST['EfileDak']) AND !empty($_POST['EfileDak'])){
			$key = Yii::$app->utility->decryptString($_POST['key']);
			
			$post = $_POST['EfileDak'];
			
			$file_category_id = Yii::$app->utility->decryptString($post['file_category_id']);
			
			$action_type = Yii::$app->utility->decryptString($post['action_type']);
			$access_level = Yii::$app->utility->decryptString($post['access_level']);
			$priority = Yii::$app->utility->decryptString($post['priority']);
			$is_confidential = Yii::$app->utility->decryptString($post['is_confidential']);
			$doc_type = Yii::$app->utility->decryptString($post['doc_type']);
			$is_time_bound = Yii::$app->utility->decryptString($_POST['is_time_bound']);
			
			
			if(empty($file_category_id)  OR empty($action_type) OR empty($access_level) OR empty($priority) OR empty($is_confidential) OR empty($doc_type) OR empty($is_time_bound)){
		
				Yii::$app->getSession()->setFlash('danger', "Invalid params value found.");
				return $this->redirect($url);
			}
			$response_date = NULL;
			if($is_time_bound == 'Y'){
				$response_date = date('Y-m-d', strtotime($_POST['response_date']));
				if($response_date == '1970-01-01'){
					Yii::$app->getSession()->setFlash('danger', "Invalid Response Date.");
					return $this->redirect($url);
				}
			}
			
			$file_type = "N";
			$rec_id = NULL;
			if(!empty($_POST['rec_id'])){
				$rec_id = Yii::$app->utility->decryptString($_POST['rec_id']);
				if(empty($rec_id )){
					Yii::$app->getSession()->setFlash('danger', "Invalid params value found.");
					return $this->redirect($url);
				}
				$file_type = "R";
			}
			
			// Check Required Project ID
			$category = EfileMasterCategory::find()->where(['file_category_id' => $file_category_id])->asArray()->one();
			$file_project_id = NULL;
			if($category['related_to_project'] == 'Y'){
				$file_project_id = Yii::$app->utility->decryptString($post['file_project_id']);
				if(empty($file_project_id)){
					die("project id");
					Yii::$app->getSession()->setFlash('danger', "Select Upload project.");
					return $this->redirect($url);
				}
			}
			$docFile = array();
			if($doc_type == 'PDF' OR $doc_type == 'Image'){
				if($doc_type == 'PDF'){
					if(isset($_FILES['pdf_path']) AND !empty($_FILES['pdf_path'])){
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
						
						$docFile[0]['doc_ext_type']= 'PDF';
						$docFile[0]['tmp_name']= $pdf_tmp_name;
						$docFile[0]['file_name']= $pdf_name;
					}else{
						Yii::$app->getSession()->setFlash('danger', "Upload sccaned PDF File.");
						return $this->redirect($url);
					}
				}elseif($doc_type == 'Image'){
					
					if(isset($_FILES['image_path']['tmp_name'][0]) AND !empty($_FILES['image_path']['tmp_name'][0])){
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
							$docFile[$i]['doc_ext_type']= 'Image';
							$docFile[$i]['tmp_name']= $image_tmp_name[$key];
							$docFile[$i]['file_name']= $image_name[$key];
							$i++;
						}
					}else{
						Yii::$app->getSession()->setFlash('danger', "Upload sccaned images of File.");
						return $this->redirect($url);
					}
				}
			}else{
				Yii::$app->getSession()->setFlash('danger', "Invalid document type found.");
				return $this->redirect($url);
			}
			
			$reference_num = Yii::$app->fts_utility->onlyChracterNumbers($post['reference_num']);
			$subject = Yii::$app->fts_utility->onlyChracterNumbers($post['subject']);
			$meta_keywords = Yii::$app->fts_utility->onlyChracterNumbers($post['meta_keywords']);
			$summary = Yii::$app->fts_utility->onlyChracterNumbers($post['summary']);
			$remarks = Yii::$app->fts_utility->onlyChracterNumbers($post['remarks']);
			$reference_date = date('Y-m-d', strtotime($post['reference_date']));
			
			if($reference_date == '1970-01-01'){
				Yii::$app->getSession()->setFlash('danger', "Invalid Reference Date.");
				return $this->redirect($url);
			}
			
			// Enter in Dak Table
			
			
			if(empty($docFile)){
				Yii::$app->getSession()->setFlash('danger', "Documents list not found.");
				return $this->redirect($url);
			}
			
			$uploadDocs = array();
			$k=0;
			$FTS_Documents = FTS_Documents;
			foreach($docFile as $d){
				$doc_path="";
				$doc_path = Yii::$app->fts_utility->uploadFile($d['tmp_name'], $d['file_name'], $FTS_Documents);
				if(empty($doc_path)){
					Yii::$app->getSession()->setFlash('danger', "File document didn\'t uploaded. Contact Admin.");
					return $this->redirect($url);
				}
				$uploadDocs[$k]['doc_path'] = $doc_path;
				$uploadDocs[$k]['doc_ext_type'] = $d['doc_ext_type'];
				$k++;
			}
			
			$file_id = Yii::$app->fts_utility->efile_add_update_efile_dak("A", NULL, $rec_id, $file_type, $reference_num, $reference_date, $subject, $file_category_id, $file_project_id, $action_type, $access_level, $priority, $is_confidential, $meta_keywords, $remarks, $summary, "Open");
			
			if(empty($file_id)){
				
				foreach($uploadDocs as $u){ Yii::$app->fts_utility->removefile($u['doc_path']); }
				
				Yii::$app->getSession()->setFlash('danger', "Dak didn\'t Created. Contact Admin.");
				return $this->redirect($url);
			}
			
			$group_id = $dak_group_id = $fwd_to = NULL;
			$group_members = $fwd_emp_list = array();
			$i=0;
			
			if($_POST['forward_dak'] == 'N'){
				Yii::$app->getSession()->setFlash('success', "File Added Successfully.");
				return $this->redirect($url);
			}elseif($_POST['forward_dak'] == 'Y'){
				if($_POST['forward_type'] == 'I'){
					$fwd_to = "E";
					$indi_emp_code = Yii::$app->utility->decryptString($_POST['indi_emp_code']);
					
					if(empty($indi_emp_code)){
						// remove file
						Yii::$app->fts_utility->efile_add_update_efile_dak("R", $file_id, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
						foreach($uploadDocs as $u){ Yii::$app->fts_utility->removefile($u['doc_path']); }
						
						Yii::$app->getSession()->setFlash('danger', "Invalid Employee Code.");
						return $this->redirect($url);
					}
					
					$fwd_emp_list[0]['employee_code'] = $indi_emp_code;
					$fwd_emp_list[0]['reply_status'] = "N";
					$fwd_emp_list[0]['is_reply_required'] = "Y";
				}elseif($_POST['forward_type'] == 'G'){
					
					$group_name = Yii::$app->fts_utility->onlyChracterNumbers($_POST['group_name']);
					if(empty($group_name)){
						// remove file
						Yii::$app->fts_utility->efile_add_update_efile_dak("R", $file_id, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
						foreach($uploadDocs as $u){ Yii::$app->fts_utility->removefile($u['doc_path']); }
						
						Yii::$app->getSession()->setFlash('danger', "Require Group Name.");
						return $this->redirect($url);
					}
					
					
					
					$gruop = $_POST['Group'];
					$group_chairman_emp_code = Yii::$app->utility->decryptString($gruop['group_chairman_emp_code']);
					$group_convenor_emp_code = Yii::$app->utility->decryptString($gruop['group_convenor_emp_code']);
					if(empty($group_chairman_emp_code) OR empty($group_convenor_emp_code)){
						// remove file
						Yii::$app->fts_utility->efile_add_update_efile_dak("R", $file_id, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
						foreach($uploadDocs as $u){ Yii::$app->fts_utility->removefile($u['doc_path']); }
						
						Yii::$app->getSession()->setFlash('danger', "Invalid Emp Code of Group Chairman / Convenor Member.");
						return $this->redirect($url);
					}
					
					if($group_chairman_emp_code == $group_convenor_emp_code){
						// remove file
						Yii::$app->fts_utility->efile_add_update_efile_dak("R", $file_id, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
						foreach($uploadDocs as $u){ Yii::$app->fts_utility->removefile($u['doc_path']); }
						
						Yii::$app->getSession()->setFlash('danger', "Group Chairman / Convenor Member cannot be same.");
						return $this->redirect($url);
					}
					
					$fwd_to = "G";
					$group_members[0]['employee_code']=$group_chairman_emp_code;
					$group_members[0]['group_role']="CH";
					$group_members[1]['employee_code']=$group_convenor_emp_code;
					$group_members[1]['group_role']="C";
					
					$finalGrpEmp = $_POST['finalGrpEmp'];
					
					if(empty($finalGrpEmp)){
						// remove file
						Yii::$app->fts_utility->efile_add_update_efile_dak("R", $file_id, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
						foreach($uploadDocs as $u){ Yii::$app->fts_utility->removefile($u['doc_path']); }
						Yii::$app->getSession()->setFlash('danger', "Select Members for Group.");
						return $this->redirect($url);
					}
					
					$g=2;
					foreach($finalGrpEmp as $m){
						$employee_code = "";
						$employee_code = Yii::$app->utility->decryptString($m);
						if(empty($employee_code)){
							// remove file
							Yii::$app->fts_utility->efile_add_update_efile_dak("R", $file_id, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
							foreach($uploadDocs as $u){ Yii::$app->fts_utility->removefile($u['doc_path']); }
							Yii::$app->getSession()->setFlash('danger', "Invalid Emp Code of Group Members.");
							return $this->redirect($url);
						}
						$group_members[$g]['employee_code']=$employee_code;
						$group_members[$g]['group_role']="M";
						$g++;
					}
					
					$group_id = Yii::$app->fts_utility->efile_add_update_dak_groups("A", NULL, $file_id, $group_name, NULL);
					
					if(empty($group_id)){
						// remove file
						Yii::$app->fts_utility->efile_add_update_efile_dak("R", $file_id, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
						foreach($uploadDocs as $u){ Yii::$app->fts_utility->removefile($u['doc_path']); }
						Yii::$app->getSession()->setFlash('danger', "Group ID not found.");
						return $this->redirect($url);
					}
					$dak_group_id = $group_id;
					
					$fwd_emp_list = array();
					$fwd_emp_list[0]['employee_code'] = $group_chairman_emp_code;
					
					$fwd_emp_list[1]['employee_code'] = $group_convenor_emp_code;
					$j=2;
					foreach($group_members as $g){
						Yii::$app->fts_utility->efile_add_update_efile_dak_group_members("A", NULL, $group_id, $g['employee_code'], $g['group_role']);
						$fwd_emp_list[$j]['employee_code'] = $g['employee_code'];
						$fwd_emp_list[$j]['reply_status'] = "N";
						if($g['group_role'] == 'CH'){
							$fwd_emp_list[$j]['is_reply_required'] = "Y";	
						}else{
							$fwd_emp_list[$j]['is_reply_required'] = "N";	
						}
						
						$j++;
						
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
								$fwd_emp_list[$j]['reply_status'] = "N";
								$fwd_emp_list[$j]['is_reply_required'] = "N";
								$j++;
							}
						}
					}
				}
				
				foreach($fwd_emp_list as $f){
					Yii::$app->fts_utility->efile_add_update_efile_dak_movement("A", NULL, $file_id, $fwd_to, $group_id, $f['employee_code'], $is_time_bound, "FileGreenSheet", $response_date, NULL, $f['is_reply_required'], $f['reply_status']);
				}
				
				foreach($uploadDocs as $u){ 
					Yii::$app->fts_utility->efile_dak_docs($file_id, "File", NULL, NULL, $u['doc_ext_type'], $u['doc_path']); 
				}
				
				Yii::$app->getSession()->setFlash('success', "File Sent Successfully.");
				return $this->redirect($url);
			}else{
				Yii::$app->getSession()->setFlash('danger', "Invalid Forward Type.");
				return $this->redirect($url);
			}
		}
		*/
		if(isset($_GET['key']) AND !empty($_GET['key'])){
			$rec_id = Yii::$app->utility->decryptString($_GET['key']);
			if(empty($rec_id)){
				Yii::$app->getSession()->setFlash('danger', "Invalid params value found");
				return $this->redirect($url);
			}
			$check = Yii::$app->fts_utility->efile_get_dak(NULL, $rec_id, NULL, NULL);
			if(!empty($check)){
				Yii::$app->getSession()->setFlash('danger', "Dak Already Recieved. Check previous daks.");
				return $this->redirect($url);
			}
			
			$recieveddak = Yii::$app->fts_utility->efile_get_dak_received($rec_id, NULL);
			if(empty($recieveddak)){
				Yii::$app->getSession()->setFlash('danger', "No Record Found.");
				return $this->redirect($url);
			}
			$model = new EfileDak();
			// echo "<pre>";print_r($recieveddak); die;
			$this->layout = '@app/views/layouts/admin_layout.php';
			return $this->render('viewrecieveddak', ['menuid'=>$menuid, 'recieveddak'=>$recieveddak, 'model'=>$model]);
			
		}else{
			Yii::$app->getSession()->setFlash('danger', "Invalid params found");
			return $this->redirect($url);
		}
		
    }
    
   }
