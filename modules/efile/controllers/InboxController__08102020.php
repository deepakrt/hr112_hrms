<?php
 
namespace app\modules\efile\controllers;
use Yii;
use app\models\EfileDakGroups;
use app\models\EfileDakGroupMembers;
use app\models\EfileDakMovement;
use app\models\EfileDakGroupMembersRemarks;
use app\models\EfileDakGroupMemberApproval;
use app\models\EfileDakHistory;
use app\models\EfileDak;
use app\models\EfileDakDocs;
use app\models\EfileMasterCategory;
use app\models\EfileDakTemp;
use app\models\HrServiceDetails;
use app\models\RbacEmployeeRole;
use app\models\EfileDakNotes;
 
class InboxController extends \yii\web\Controller
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
       
        die();
    }
	public function actionDownloadgreensheet()
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."efile/inbox?securekey=$menuid";
        if(isset($_GET['fileid']) AND !empty($_GET['fileid'])){
            $fileid = Yii::$app->utility->decryptString($_GET['fileid']);
            if(empty($fileid)){
                Yii::$app->getSession()->setFlash('danger', "Invalid params value found.");
                return $this->redirect($url);
            }
            $fileinfo = Yii::$app->fts_utility->efile_get_dak($fileid, NULL, NULL, NULL);
            if(empty($fileinfo)){
                Yii::$app->getSession()->setFlash('danger', "No Record Found.");
                return $this->redirect($url);
            }
            $fileNotes = Yii::$app->Dakutility->efile_get_dak_notes($fileid);
            if(empty($fileNotes)){
                Yii::$app->getSession()->setFlash('danger', "No Notes found in file.");
                return $this->redirect($url);
            }
            Yii::$app->Dakutility->makenotesfile($fileNotes, $fileinfo);
        }else{
            Yii::$app->getSession()->setFlash('danger', "Invalid params  found.");
            return $this->redirect($url);
        }
    }
    
//    public function actionChairmanfinalremarks(){
//        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
//        $menuid = Yii::$app->utility->encryptString($menuid);
//        $url1 = Yii::$app->homeUrl."efile/inbox?securekey=$menuid";
//        if(isset($_POST['key']) AND !empty($_POST['key']) AND isset($_POST['key1']) AND !empty($_POST['key1']))
//        {
//            $param_file_id = Yii::$app->utility->decryptString($_POST['key']);
//            $param_dak_group_id = Yii::$app->utility->decryptString($_POST['key1']);
//            $movementid = Yii::$app->utility->decryptString($_POST['key2']);
//            $membertype = Yii::$app->utility->decryptString($_POST['membertype']);
//            $param_status=$param_id="";
//           
//            if($_POST['sbmttype']=="D"){
//                $param_status="D";
//            }elseif($_POST['sbmttype']=="S"){
//                $param_status="S";
//            }
//                
//            echo "<pre>";print_r($_POST); die;
//
//           
//        }else{
//            Yii::$app->getSession()->setFlash('danger', "Invalid params  found.");
//            return $this->redirect($url);
//        } 
//    }
    
	 public function actionAddmemberremarks()
    {
	$menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."efile/inbox?securekey=$menuid";
//		echo "<pre>";print_r($_POST); die;
        if(isset($_POST['Remarks']) AND !empty($_POST['Remarks'])){
            $post = $_POST['Remarks'];
            $model = new EfileDakGroupMembersRemarks();
            $file_id = Yii::$app->utility->decryptString($post['key']);
            $movement_id = Yii::$app->utility->decryptString($post['key2']);
            $dak_group_id = Yii::$app->utility->decryptString($post['key1']);
            $membertype = Yii::$app->utility->decryptString($post['membertype']);

            $remarks = Yii::$app->fts_utility->validateHindiString($post['remarks']);
            if(empty($file_id) OR empty($movement_id)  OR empty($dak_group_id) OR empty($membertype) OR empty($remarks)){
                Yii::$app->getSession()->setFlash('danger', "Invalid params value found.");
                return $this->redirect($url);
            }
            $movement = Yii::$app->fts_utility->efile_get_efile_dak_movement(NULL, $movement_id);
            $fileinfo = Yii::$app->fts_utility->efile_get_dak($file_id, NULL, NULL, NULL);
            if(empty($fileinfo) OR empty($movement)){
                Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
                return $this->redirect($url);
            }
            $fileid = Yii::$app->utility->encryptString($file_id);
            $movementid = Yii::$app->utility->encryptString($movement_id);
            $url = Yii::$app->homeUrl."efile/inbox/viewdakwithnoting?securekey=$menuid&key=$fileid&key2=$movementid";
            $remark_id = NULL;
//            echo "$post[remarks_id]"; die;
            if(!empty($post['remarks_id'])){
                $remarks_id = Yii::$app->utility->decryptString($post['remarks_id']);
//                echo "***$remarks_id***<br>";
                if(empty($remarks_id)){
                    Yii::$app->getSession()->setFlash('danger', "Invalid params value found.");
                    return $this->redirect($url);
                }
//                echo $movement['dak_group_id']."<br>";
//                echo $file_id."<br>";
//                echo $remarks_id."<br>";
                $model = EfileDakGroupMembersRemarks::find()->where([ 'dak_group_id'=>$movement['dak_group_id'], 'file_id'=>$file_id, 'id'=>$remarks_id])->one();
               
                if(empty($model)){
                    Yii::$app->getSession()->setFlash('danger', "Invalid remarks id found.");
                    return $this->redirect($url);
                }
                
                $model->last_updated_date = date('Y-m-d H:i:s');
                $model->is_active = "Y";
            }else{
                $model->created_date = date('Y-m-d H:i:s');
            }
            
            $status = "";
            $msg = "";
            $CheckStatus = "";
            if($_POST['submit_type'] == 'D'){
                $status = "D";
                $msg = "Remarks saved as Draft successfully.";
            }elseif($_POST['submit_type'] == 'S'){
                $CheckStatus = $status = "S";
                $msg = "Remarks added successfully.";
                Yii::$app->Dakutility->regarding_group_remarks_email($dak_group_id);
            }elseif($_POST['submit_type'] == 'CHD'){
                $status = "CHD";
                $msg = "Final Remarks added successfully.";
                Yii::$app->Dakutility->regarding_group_remarks_email($dak_group_id);
            }elseif($_POST['submit_type'] == 'CHF'){
                $status = "CHF";
                $msg = "Final Remarks added successfully.";
                Yii::$app->Dakutility->regarding_group_remarks_email($dak_group_id);
            }
			// elseif($_POST['submit_type'] == 'FS'){
                // $status = "CHF";
                // $msg = "Final Comments added successfully.";
            // }
            // echo "$status";die;
            // Check file send to current user or not
            $movement = Yii::$app->fts_utility->efile_get_efile_dak_movement(NULL, $movement_id);
            if(empty($movement)){
                Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
                return $this->redirect($url);
            }
            if($movement['dak_group_id'] != $dak_group_id){
                Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
                return $this->redirect($url);
            }
            
            $model->dak_group_id = $dak_group_id;
            $model->file_id = $file_id;
            $model->employee_code = Yii::$app->user->identity->e_id;
            $model->group_role = $membertype;
            $model->remarks = $remarks;
            $model->status = $status;
            $model->save();
            
            Yii::$app->getSession()->setFlash('success', $msg);
            return $this->redirect($url);
        }else{
                Yii::$app->getSession()->setFlash('danger', "Invalid params  found.");
                return $this->redirect($url);
        }        
    }
	
//	public function actionFinalremarks(){
//		$menuid = Yii::$app->utility->decryptString($_GET['securekey']);
//        $menuid = Yii::$app->utility->encryptString($menuid);
//		$url = Yii::$app->homeUrl."efile/inbox?securekey=$menuid";
//		
//		if(isset($_POST['Chairman']) AND !empty($_POST['Chairman'])){
//			$post = $_POST['Chairman'];
//			
//			$file_id = Yii::$app->utility->decryptString($post['key']);
//			$movement_id = Yii::$app->utility->decryptString($post['key2']);
//			$dak_group_id = Yii::$app->utility->decryptString($post['key1']);
//			$membertype = Yii::$app->utility->decryptString($post['membertype']);
//			
//			$remarks = Yii::$app->fts_utility->onlyChracterNumbers($post['remarks']);
//			if(empty($file_id) OR empty($movement_id)  OR empty($dak_group_id) OR empty($membertype) OR empty($remarks)){
//			
//				Yii::$app->getSession()->setFlash('danger', "Invalid params value found.");
//				return $this->redirect($url);
//			}
//			$remark_id = NULL;
//			if(!empty($post['remarks_id'])){
//				$remarks_id = Yii::$app->utility->decryptString($post['remarks_id']);
//				if(empty($remarks_id)){
//					Yii::$app->getSession()->setFlash('danger', "Invalid params value found.");
//					return $this->redirect($url);
//				}
//			}
//			
//			$fileid = Yii::$app->utility->encryptString($file_id);
//			$movementid = Yii::$app->utility->encryptString($movement_id);
//			$url = Yii::$app->homeUrl."efile/inbox/viewdakwithnoting?securekey=$menuid&key=$fileid&key2=$movementid";
//			
//			
//			$fileinfo = Yii::$app->fts_utility->efile_get_dak($file_id, NULL, NULL, NULL);
//			if(empty($fileinfo)){
//                            Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
//                            return $this->redirect($url);
//			}
//			$status = "";
//			$msg = "";
//			$CheckStatus = "";
//			if($post['submit'] == 'D'){
//				$status = "D";
//				$msg = "Remarks saved as Draft successfully.";
//			}elseif($post['submit'] == 'S'){
//				$CheckStatus = $status = "S";
//				$msg = "Remarks forwarded to all members for approval.";
//			}
//			// Check file send to current user or not
//			$movement = Yii::$app->fts_utility->efile_get_efile_dak_movement(NULL, $movement_id);
//			if(empty($movement)){
//				Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
//				return $this->redirect($url);
//			}
//			
//			if($movement['dak_group_id'] != $dak_group_id){
//				Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
//				return $this->redirect($url);
//			}
//			
//			if($membertype !='CH'){
//				Yii::$app->getSession()->setFlash('danger', "You are not authorized.");
//				return $this->redirect($url);
//			}
//			
//			$checExits = EfileDakGroupMembersRemarks::find()->where([
//				'dak_group_id'=>$dak_group_id, 
//				'file_id'=>$file_id, 
//				'employee_code'=>Yii::$app->user->identity->e_id, 
//				'group_role'=>'CH', 
//				'is_active' => 'Y',
//				'status'=>$CheckStatus
//			])->one();
//			
//			if(!empty($checExits)){
//				Yii::$app->getSession()->setFlash('danger', "Remarks already updated.");
//				return $this->redirect($url);
//			}
//			$model = new EfileDakGroupMembersRemarks();
//			if(!empty($remarks_id)){
//				$model = EfileDakGroupMembersRemarks::find()->where([
//					'id'=>$remarks_id, 
//					'dak_group_id'=>$dak_group_id, 
//					'file_id'=>$file_id, 
//					'employee_code'=>Yii::$app->user->identity->e_id, 
//					'group_role'=>'CH', 
//					'is_active' => 'Y'
//				])->one();
//			}
//			
//			
//			$model->dak_group_id = $dak_group_id;
//			$model->file_id = $file_id;
//			$model->employee_code = Yii::$app->user->identity->e_id;
//			$model->group_role = "CH";
//			$model->remarks = $remarks;
//			$model->status = $status;
//			$model->created_date = date('Y-m-d H:i:s');
//			$model->is_active = "Y";
//			
//			// echo "<pre>";print_r($model); die;
//			$model->save();
//			
//			Yii::$app->getSession()->setFlash('success', "$msg");
//			return $this->redirect($url);
//			echo "<pre>";print_r($model);die;
//			
//		}else{
//			Yii::$app->getSession()->setFlash('danger', "Invalid params  found.");
//			return $this->redirect($url);
//		}
//	}
	
    public function actionMembers_acceptance(){
		$menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
		$url = Yii::$app->homeUrl."efile/inbox?securekey=$menuid";
		
		if(isset($_POST['Chairman']) AND !empty($_POST['Chairman'])){
			$post = $_POST['Chairman'];
			
			$file_id = Yii::$app->utility->decryptString($post['key']);
			$movement_id = Yii::$app->utility->decryptString($post['key2']);
			$dak_group_id = Yii::$app->utility->decryptString($post['key1']);
			$remarks_final_status = Yii::$app->utility->decryptString($post['remarks_final_status']);
			
			if(empty($file_id) OR empty($movement_id)  OR empty($dak_group_id) OR empty($remarks_final_status)){
			
				Yii::$app->getSession()->setFlash('danger', "Invalid params value found.");
				return $this->redirect($url);
			}
			
			$fileid = Yii::$app->utility->encryptString($file_id);
			$movementid = Yii::$app->utility->encryptString($movement_id);
			$url = Yii::$app->homeUrl."efile/inbox/viewdakwithnoting?securekey=$menuid&key=$fileid&key2=$movementid";
			
			
			$fileinfo = Yii::$app->fts_utility->efile_get_dak($file_id, NULL, NULL, NULL);
			if(empty($fileinfo)){
				Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
				return $this->redirect($url);
			}
			
			// Check file send to current user or not
			$movement = Yii::$app->fts_utility->efile_get_efile_dak_movement(NULL, $movement_id);
			if(empty($movement)){
				Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
				return $this->redirect($url);
			}
			
			if($movement['dak_group_id'] != $dak_group_id){
				Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
				return $this->redirect($url);
			}
			
			$check = EfileDakGroupMemberApproval::find()->where([
					'dak_group_id'=>$dak_group_id, 
					'file_id'=>$file_id, 
					'employee_code'=>Yii::$app->user->identity->e_id, 
					'is_active' => 'Y'
				])->one();
			
			if(!empty($check)){
				Yii::$app->getSession()->setFlash('danger', "You have submitted your decision.");
				return $this->redirect($url);
			}
			$model = new EfileDakGroupMemberApproval();
			$model->dak_group_id = $dak_group_id;
			$model->file_id = $file_id;
			$model->employee_code = Yii::$app->user->identity->e_id;
			$model->remarks_final_status = $remarks_final_status;
			$model->created_date = date('Y-m-d H:i:s');
			$model->is_active = "Y";
			
			$model->save();
			Yii::$app->getSession()->setFlash('success', "Group decision updated successfully.");
			return $this->redirect($url);
			// echo "<pre>";print_r($post);
			// die("OK");
		}else{
			Yii::$app->getSession()->setFlash('danger', "Invalid params  found.");
			return $this->redirect($url);
		}
	}
    public function actionIndex()
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $this->layout = '@app/views/layouts/admin_layout.php';
        $lists = Yii::$app->fts_utility->efile_get_efile_dak_movement(Yii::$app->user->identity->e_id, NULL);
        // echo "<pre>";print_r($lists); die;
        return $this->render('index', ['menuid'=>$menuid, 'lists'=>$lists]);
    }
	
    public function actionViewdakwithnoting()
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."efile/inbox?securekey=$menuid";
        
        if(isset($_GET['key']) AND !empty($_GET['key']) AND isset($_GET['key2']) AND !empty($_GET['key2'])){
			$file_id = Yii::$app->utility->decryptString($_GET['key']);
			$movement_id = Yii::$app->utility->decryptString($_GET['key2']);
			
			if(empty($file_id) OR empty($movement_id)){
				Yii::$app->getSession()->setFlash('danger', "Invalid params value found.");
				return $this->redirect($url);
			}
			
			$fileinfo = Yii::$app->fts_utility->efile_get_dak($file_id, NULL, NULL, NULL);
			if(empty($fileinfo)){
				Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
				return $this->redirect($url);
			}
			if($fileinfo['is_active'] == 'N'){
				
				Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
				return $this->redirect($url);
			}
			
			if($fileinfo['status'] != 'Open'){
				
				Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
				return $this->redirect($url);
			}
			// echo "<pre>";print_r($fileinfo);die;
			$receiptInfo = "";
			
			// Check file send to current user or not
			$movement = Yii::$app->fts_utility->efile_get_efile_dak_movement(NULL, $movement_id);
			if(empty($movement)){
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
			if(!empty($fileinfo['rec_id'])){
				$receiptInfo = Yii::$app->fts_utility->efile_get_dak_received($fileinfo['rec_id'], NULL);
			}
			$this->layout = '@app/views/layouts/filewithnoting_layout.php';
			return $this->render('viewdakwithnoting', ['menuid'=>$menuid, 'fileinfo'=>$fileinfo, 'movement'=>$movement, 'receiptInfo'=>$receiptInfo]);
			
		}else{
			Yii::$app->getSession()->setFlash('danger', "Invalid params  found.");
			return $this->redirect($url);
		}
	}
	
	public function actionForwardbacktosender(){
		$menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
		$url = Yii::$app->homeUrl."efile/inbox?securekey=$menuid";
		if(isset($_GET['key']) AND !empty($_GET['key']) AND isset($_GET['key2']) AND !empty($_GET['key2'])){
			$file_id = Yii::$app->utility->decryptString($_GET['key']);
			$movement_id = Yii::$app->utility->decryptString($_GET['key2']);
			
			if(empty($file_id) OR empty($movement_id)){
				Yii::$app->getSession()->setFlash('danger', "Invalid params value found.");
				return $this->redirect($url);
			}
			
			$fileinfo = Yii::$app->fts_utility->efile_get_dak($file_id, NULL, NULL, NULL);
			if(empty($fileinfo)){
				Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
				return $this->redirect($url);
			}
			$receiptInfo = "";
			
			// Check file send to current user or not
			$movement = Yii::$app->fts_utility->efile_get_efile_dak_movement(NULL, $movement_id);
			if(empty($movement)){
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
			
			 $UpdateModel = EfileDakMovement::find()->where(['id'=>$movement['id'], 'fwd_emp_code'=>$movement['fwd_emp_code'], 'is_active' => "Y"])->one();
			 
			 $UpdateModel->status = "Return";
			 $UpdateModel->reply_status = "Y";
			 
			 
			 $NewModel = new EfileDakMovement();
			 
			  $NewModel->file_id = $file_id;
			  $NewModel->fwd_to = "E";
			  $NewModel->dak_group_id = NULL;
			  $NewModel->fwd_emp_code = $movement['fwd_by'];
			  $NewModel->is_time_bound = "N";
			  $NewModel->fwd_file_type = $movement['fwd_file_type'];
			  $NewModel->response_date = NULL;
			  $NewModel->status = "Return";
			  $NewModel->is_reply_required = "Y";
			  $NewModel->reply_status = "N";
			  $NewModel->fwd_by = Yii::$app->user->identity->e_id;
			  $NewModel->fwd_date = date('Y-m-d H:i:s');
			  $NewModel->is_active = "Y";
			  
			  $UpdateModel->save();
			  $NewModel->save();
			  
			Yii::$app->getSession()->setFlash('success', "File forwarded successfully.");
			return $this->redirect($url);
		}else{
			Yii::$app->getSession()->setFlash('danger', "Invalid params  found.");
			return $this->redirect($url);
		}
	}
	
	public function actionAddnewnote(){
		$menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
		$url = Yii::$app->homeUrl."efile/inbox?securekey=$menuid";
		
		if(isset($_POST['Newnote']) AND !empty($_POST['Newnote'])){
			$post = $_POST['Newnote'];
			$note_id = NULL;
			$content_type = "N";
			$file_id = Yii::$app->utility->decryptString($post['key']);
			$movement_id = Yii::$app->utility->decryptString($post['key1']);
			if(!empty($post['key2'])){
				$note_id = Yii::$app->utility->decryptString($post['key2']);
				if(empty($note_id)){
					Yii::$app->getSession()->setFlash('danger', "Invalid params value found.");
					return $this->redirect($url);
				}
			}
			
//			$note_comment = Yii::$app->fts_utility->validateHindiString($post['note_comment']);
			$note_comment = Yii::$app->fts_utility->validateHindiString($post['note_comment']);
                        
//                        echo "<pre>";print_r($post); die;
			if(empty($file_id) OR empty($movement_id)  OR empty($note_comment)){
				Yii::$app->getSession()->setFlash('danger', "Invalid params value found.");
				return $this->redirect($url);
			}
			
			
			$fileid = Yii::$app->utility->encryptString($file_id);
			$movementid = Yii::$app->utility->encryptString($movement_id);
			$url = Yii::$app->homeUrl."efile/inbox/viewdakwithnoting?securekey=$menuid&key=$fileid&key2=$movementid";
			
			$file_attach = "N";
			
			$fileinfo = Yii::$app->fts_utility->efile_get_dak($file_id, NULL, NULL, NULL);
			if(empty($fileinfo)){
				Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
				return $this->redirect($url);
			}
			$status = "S";
			if($_POST['submit_type'] == 'D'){
				$status = "D";
			}
			
			// Check file send to current user or not
			$movement = Yii::$app->fts_utility->efile_get_efile_dak_movement(NULL, $movement_id);
			if(empty($movement)){
				Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
				return $this->redirect($url);
			}
			if($movement['fwd_emp_code'] != Yii::$app->user->identity->e_id){
				Yii::$app->getSession()->setFlash('danger', "You are not authorized to view this file.");
				return $this->redirect($url);
			}
			if($fileinfo['is_active'] == 'N'){
				Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
				return $this->redirect($url);
			}
			if($fileinfo['status'] != 'Open'){
				Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
				return $this->redirect($url);
			}
			if($movement['is_active'] == 'N'){
				Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
				return $this->redirect($url);
			}
			$note_subject = NULL;
			
			if(isset($_POST['note_subject']) AND !empty($_POST['note_subject'])){
				$note_subject = Yii::$app->fts_utility->validateHindiString($_POST['note_subject']);
			}
			$actionType = "A";
			if(!empty($note_id)){
				$actionType = "U";
			}
                        $Param_file_fwd_type = $movement['fwd_to'];
                        $group_id = NULL;
			// echo "$actionType $status $note_id<pre>";print_r($post);die;
			$result = Yii::$app->fts_utility->elif_add_efile_dak_notes($actionType, $file_id, $note_comment, $file_attach, $note_subject, $status, $note_id, $content_type, $Param_file_fwd_type, $group_id);
			
			if(!empty($result)){
                                $draft_file_model = EfileDakTemp::find()->where(['file_id'=>$file_id])->one();
                                if(!empty($draft_file_model)){
                                    $draft_file_model->note_subject = NULL;
                                    $draft_file_model->note_comment = NULL;
                                    $draft_file_model->save();
                                }
				$msg = "Comment added successfully.";
				if($status == 'D'){
					$msg = "Comment added as Draft successfully.";
				}
				Yii::$app->getSession()->setFlash('success', $msg);
				return $this->redirect($url);
			}else{
				Yii::$app->getSession()->setFlash('danger', "Comment on greensheet didn\'t added. Contact Admin.");
				return $this->redirect($url);
			}
			
		}else{
			Yii::$app->getSession()->setFlash('danger', "Invalid params found.");
			return $this->redirect($url);
		}
	}
	
	public function actionAddnewremarks(){
            $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
            $menuid = Yii::$app->utility->encryptString($menuid);
            $url = Yii::$app->homeUrl."efile/inbox?securekey=$menuid";
		
            if(isset($_POST['Remarks']) AND !empty($_POST['Remarks'])){
                    $post = $_POST['Remarks'];

                    $content_type = "F";
                    $file_id = Yii::$app->utility->decryptString($post['key']);
                    $movement_id = Yii::$app->utility->decryptString($post['key1']);
                    $file_remarks = NULL;
                    if(!empty($post['file_remarks'])){
                        $file_remarks = Yii::$app->fts_utility->validateHindiString($post['file_remarks']);
                    }

                    if(empty($file_id) OR empty($movement_id)){
                        Yii::$app->getSession()->setFlash('danger', "Invalid params value found.");
                        return $this->redirect($url);
                    }

                    $fileid = Yii::$app->utility->encryptString($file_id);
                    $movementid = Yii::$app->utility->encryptString($movement_id);
                    $url = Yii::$app->homeUrl."efile/inbox/viewdakwithnoting?securekey=$menuid&key=$fileid&key2=$movementid";

                    $file_attach = "N";

                    $fileinfo = Yii::$app->fts_utility->efile_get_dak($file_id, NULL, NULL, NULL);
                    if(empty($fileinfo)){
                        Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
                        return $this->redirect($url);
                    }

                    // Check file send to current user or not
                    $movement = Yii::$app->fts_utility->efile_get_efile_dak_movement(NULL, $movement_id);
                    if(empty($movement)){
                        Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
                        return $this->redirect($url);
                    }
                    if($movement['fwd_emp_code'] != Yii::$app->user->identity->e_id){
                        Yii::$app->getSession()->setFlash('danger', "You are not authorized to view this file.");
                        return $this->redirect($url);
                    }
                    if($fileinfo['is_active'] == 'N'){
                        Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
                        return $this->redirect($url);
                    }
                    if($fileinfo['status'] != 'Open'){
                        Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
                        return $this->redirect($url);
                    }
                    if($movement['is_active'] == 'N'){
                        Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
                        return $this->redirect($url);
                    }

                    $is_protected = $file_attach = "N";
					$doc_title = $doc_password = NULL;
                    if(!empty($post['doc_type'])){
                        $doc_type = Yii::$app->utility->decryptString($post['doc_type']);
                        if(empty($doc_type)){
                            Yii::$app->getSession()->setFlash('danger', "Invalid params value found.");
                            return $this->redirect($url);
                        }
                        $file_attach = "Y";
						
						$doc_title = Yii::$app->fts_utility->validateHindiString($_POST['doc_title']);
						if(empty($doc_title)){
                            Yii::$app->getSession()->setFlash('danger', "Required Document Title.");
                            return $this->redirect($url);
                        }
						$is_protected = Yii::$app->utility->decryptString($_POST['is_protected']);
						if(empty($is_protected)){
                            Yii::$app->getSession()->setFlash('danger', "Invalid params value found.");
                            return $this->redirect($url);
                        }
						if($is_protected == 'Y' OR $is_protected == 'N'){
							if($is_protected == 'Y'){
								$doc_password = trim($_POST['file_password']);
								if(empty($doc_password)){
									Yii::$app->getSession()->setFlash('danger', "Document Password cannot empty.");
									return $this->redirect($url);
								}
								$doc_password = \md5($doc_password);
							}
						}else{
							Yii::$app->getSession()->setFlash('danger', "Invalid Select value of password Protected.");
                            return $this->redirect($url);
						}
						
						
						
                    }
                    // echo "<pre>";print_r($_POST); die;
                    if(empty($file_remarks) AND $file_attach == 'N'){
                        Yii::$app->getSession()->setFlash('danger', "Enter File Remarks OR Browse File.");
                        return $this->redirect($url);
                    }

                    $msg = "Remarks / File added successfully.";
                    if(!empty($file_remarks) AND $file_attach == 'Y'){
                        $msg = "Remarks and File added successfully.";
                    }elseif(!empty($file_remarks) AND $file_attach == 'N'){
                        $msg = "Remarks added successfully.";
                    }elseif(empty($file_remarks) AND $file_attach == 'Y'){
                        $msg = "File added successfully.";
                    }

                    $upload_Tye = "";
                    $docFile = array();
                    $proposal_action = NULL;
                    if($file_attach == 'Y'){
                        $attach_with = "File";
                        if($fileinfo['action_type'] == '5'){
                            $filedocs = Yii::$app->Dakutility->efile_get_dak_docs($fileinfo['file_id'],NULL);
                            if(!empty($filedocs)){
                                $proposal_action = Yii::$app->utility->decryptString($post['proposal_action']);
                                if(empty($proposal_action)){
                                    Yii::$app->getSession()->setFlash('danger', "Invalid Proposal Action.");
                                    return $this->redirect($url);
                                }
                            }
                            
                            $attach_with = "Proposal";
                        }
                        
                        
                        
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
                    }
					// elseif($doc_type == 'Image'){
                        // if(isset($_FILES['image_path']['tmp_name'][0]) AND !empty($_FILES['image_path']['tmp_name'][0])){
                            // $upload_Tye = "Image";
                            // $image_type = $_FILES['image_path']['type'];
                            // $image_tmp_name = $_FILES['image_path']['tmp_name'];
                            // $image_size = $_FILES['image_path']['size'];
                            // $image_name = $_FILES['image_path']['name'];

                            // $i=0;
                            // foreach($image_type as $key=>$type){
                                // $error = "";
                                // $chk1 = $chk = "";
                                // $chk = Yii::$app->fts_utility->validateImage($type, $image_tmp_name[$key]);
                                // if(empty($chk)){
                                    // $error = "Upload Valid Images of File";
                                // }
                                // $chk1 = Yii::$app->fts_utility->validateImageSize($image_size[$key]);
                                // if(empty($chk1)){
                                    // $error .= "Each image size should be less then ".FTS_Image_Size."MB";;
                                // }
                                // if(!empty($error)){
                                        // Yii::$app->getSession()->setFlash('danger', $error); 
                                        // return $this->redirect($url);
                                // }
                                // $data = file_get_contents($image_tmp_name[$key]);
                                // $base64 = 'data:image/' . $image_type[$key] . ';base64,' . base64_encode($data);
                                // $docFile[$i]['doc_ext_type']= 'Image';
                                // $docFile[$i]['tmp_name']= $image_tmp_name[$key];
                                // $docFile[$i]['file_name']= $image_name[$key];
                                // $docFile[$i]['base64']= $base64;
                                // $i++;
                            // }
                        // }else{
                            // Yii::$app->getSession()->setFlash('danger', "Upload sccaned images of File.");
                            // return $this->redirect($url);
                        // }
                    // }
                }

                $status = "S";
                $note_id = $note_subject = NULL;
                $content_type = "R";

                if(!empty($file_remarks)){
                        Yii::$app->fts_utility->elif_add_efile_dak_notes("A", $file_id, $file_remarks, $file_attach, $note_subject, $status, $note_id, $content_type);
                }


                // if(!empty($note_id)){

                if($file_attach == 'Y'){
                    $FTS_Documents = FTS_Documents;
                    if($upload_Tye == 'PDF'){
                        $doc_path = Yii::$app->fts_utility->uploadFile($docFile['tmp_name'], $docFile['file_name'], $FTS_Documents);
                        if(empty($doc_path)){
                            Yii::$app->getSession()->setFlash('danger', "File document didn\'t uploaded. Contact Admin.");
                            return $this->redirect($url);
                        }

                        $uploadDocs['doc_path'] = $doc_path;
                        $uploadDocs['doc_ext_type'] = "PDF";

                    }
//                    elseif($upload_Tye == 'Image'){
//                        $doc_path = Yii::$app->fts_utility->uploadImageTopdf($docFile, $FTS_Documents);
//                        if(empty($doc_path)){
//                            Yii::$app->getSession()->setFlash('danger', "File document didn\'t uploaded. Contact Admin.");
//                            return $this->redirect($url);
//                        }
//                        $uploadDocs['doc_path'] = $doc_path;
//                        $uploadDocs['doc_ext_type'] = "PDF";
//                    }
                    // Inaction old proposal files
                    if($proposal_action == 'O'){
                        $model = EfileDakDocs::find()->where(['file_id'=>$file_id, 'attach_with'=>'Proposal'])->all();
                        if(!empty($model)){
                            foreach($model as $p){
                                $p->is_active = "N";
                                $p->save();
                            }
                        }
                        
                        $alltags = Yii::$app->fts_utility->efile_get_dak_tags($file_id, 'Active', NULL);
                        if(!empty($alltags)){
                            foreach($alltags as $a){
                                if($a['tag_on'] == 'P'){
                                    $Param_is_active = $Param_tag_content = $Param_page_number = NULL;
                                    Yii::$app->fts_utility->efile_add_update_dak_tags("D", $a['tag_id'], $file_id, $Param_page_number, $Param_tag_content, $Param_is_active);
                                }
                            }
                        }
                    }
                    
                    Yii::$app->fts_utility->efile_dak_docs($file_id, $attach_with, NULL, NULL, $uploadDocs['doc_ext_type'], $uploadDocs['doc_path'], $doc_title, $is_protected, $doc_password); 
                }
                
                $draft_file_model = EfileDakTemp::find()->where(['file_id'=>$file_id])->one();
                if(!empty($draft_file_model)){
                    $draft_file_model->file_remarks = NULL;
                    $draft_file_model->save();
                }
                Yii::$app->getSession()->setFlash('success', $msg);
                return $this->redirect($url);
                    // }else{
                            // Yii::$app->getSession()->setFlash('danger', "Remarks / File didn\'t added. Contact Admin.");
                            // return $this->redirect($url);
                    // }

            }else{
                    Yii::$app->getSession()->setFlash('danger', "Invalid params found.");
                    return $this->redirect($url);
            }
	}
	
	public function actionForwarddaktoother(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."efile/inbox?securekey=$menuid";
		
            if(isset($_POST['Forward']) AND !empty($_POST['Forward'])){
                $post = $_POST['Forward'];

                $file_id = Yii::$app->utility->decryptString($post['key']);
                $movement_id = Yii::$app->utility->decryptString($post['key1']);


                if(empty($file_id) OR empty($movement_id)){

                    Yii::$app->getSession()->setFlash('danger', "Invalid params value found.");
                    return $this->redirect($url);
                }

                $fileid = Yii::$app->utility->encryptString($file_id);
                $movementid = Yii::$app->utility->encryptString($movement_id);
                $url = Yii::$app->homeUrl."efile/inbox/viewdakwithnoting?securekey=$menuid&key=$fileid&key2=$movementid";
                $fileinfo = Yii::$app->fts_utility->efile_get_dak($file_id, NULL, NULL, NULL);
                if(empty($fileinfo)){
                    Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
                    return $this->redirect($url);
                }
                $receiptInfo = "";

                // Check file send to current user or not
                $movement = Yii::$app->fts_utility->efile_get_efile_dak_movement(NULL, $movement_id);
                if(empty($movement)){
                    Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
                    return $this->redirect($url);
                }
                if($movement['fwd_emp_code'] != Yii::$app->user->identity->e_id){
                    Yii::$app->getSession()->setFlash('danger', "You are not authorized to view this file.");
                    return $this->redirect($url);
                }
                if($fileinfo['is_active'] == 'N'){
                    Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
                    return $this->redirect($url);
                }
                if($fileinfo['status'] != 'Open'){
                    Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
                    return $this->redirect($url);
                }
                if($movement['is_active'] == 'N'){
                    Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
                    return $this->redirect($url);
                }
                // echo "<pre>";print_r($movement); die;
                $response_date = $movement['response_date'];
                $is_time_bound = $movement['is_time_bound'];
                
                $old_id = Yii::$app->utility->decryptString($_POST['old_id']);

                if(empty($old_id)){
                    Yii::$app->getSession()->setFlash('danger', "Invalid Movement ID.");
                    return $this->redirect($url);
                }
//                                $notes = EfileDakNotes::find()->where(['file_id' => $fileinfo['file_id'], 'status'=>'S', 'content_type'=>'N', 'is_active'=>'Y'])->asArray()->all();

                $note_subject = $note_id = NULL;
//                                echo "<pre>";print_r($post); die;
                $AddNote = "N";
//                echo "<pre>";print_r($post); 
                if(!empty($post['ED_Note'])){
                    
                    $note_comment = Yii::$app->fts_utility->validateHindiString($post['fwd_note_comment']);
                    if(!empty($note_comment)){
                        $note_comment = $note_comment.$post['ED_Note'];
                    }else{
                        $note_comment = $post['ED_Note'];
                    }
                    $AddNote = "Y";
                }else{
                    if($post['is_new_note'] == 'Y'){
                        if(!empty($post['fwd_note_comment'])){
                            $note_subject = Yii::$app->fts_utility->validateHindiString($post['fwd_note_subject']);
                            $note_comment = Yii::$app->fts_utility->validateHindiString($post['fwd_note_comment']);
                            $msg = "";
                            if(!empty($note_subject)){
                                if(empty($note_comment)){
                                    Yii::$app->getSession()->setFlash('danger', "Required Note Comments.");
                                    return $this->redirect($url);
                                }
                            }
                            if(!empty($note_comment)){
                                if(empty($note_subject)){
                                    Yii::$app->getSession()->setFlash('danger', "Required Note Subject.");
                                    return $this->redirect($url);
                                }
                            }
                            $AddNote = "Y";
                        }

                    }elseif($post['is_new_note'] == 'N' AND $post['note_required'] == 'Y'){
                       $note_comment = Yii::$app->fts_utility->validateHindiString($post['fwd_note_comment']);
                       if(empty($note_comment)){
                            Yii::$app->getSession()->setFlash('danger', "Required Note Comments.");
                            return $this->redirect($url);
                        }
                        $AddNote = "Y";
                    }elseif($post['is_new_note'] == 'N' AND $post['note_required'] == 'N'){}else{
                        Yii::$app->getSession()->setFlash('danger', "Invalid Note Found.");
                        return $this->redirect($url);
                    }
                }
//                echo $note_comment; die;
//                echo "*****$AddNote******";
                $actionType = "A";
                $status = "S";
                $file_attach = "N";
                $content_type = "N";
                $Param_file_fwd_type = $movement['fwd_to'];
                $group_id = $movement['dak_group_id'];
                /*
                * Is Hirarchy Select ****************************************************************
                */
                $fwd_emp_list = array();
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



                    

                $draft_file_model = EfileDakTemp::find()->where(['file_id'=>$file_id])->one();
                if(!empty($draft_file_model)){
                    $draft_file_model->note_subject = NULL;
                    $draft_file_model->note_comment = NULL;
                    $draft_file_model->save();
                }
                /*
                * End Save Note 
                */      
                
                   $is_reply_required = "Y";
                   $reply_status = "N";
                   $fwd_to = "E";
                   $Param_file_doc_info = NULL;
                   Yii::$app->fts_utility->efile_add_update_efile_dak_movement("A", NULL, $file_id, $fwd_to, $group_id, $hirarchy->authority1, $is_time_bound, "FileGreenSheet", $response_date, NULL, $is_reply_required, $reply_status, Yii::$app->user->identity->e_id, "N", $fla_info->dept_id, $Param_file_doc_info);
                   /*
                    * Save Note 
                    */
                    if($AddNote == "Y"){
                        Yii::$app->fts_utility->elif_add_efile_dak_notes($actionType, $file_id, $note_comment, $file_attach, $note_subject, $status, $note_id, $content_type, $Param_file_fwd_type, $group_id);
                    }
                   /*
                    * Update Old movement
                    */

                   if($movement['fwd_to'] == 'G'){
                         $EfileDakGroupMembersd = EfileDakGroupMembers::find()->where(['dak_group_id' =>$old_id])->all();
                       if(!empty($EfileDakGroupMembersd))
                       {
                           foreach ($EfileDakGroupMembersd as $key => $valuem) 
                           {
                               $EfileDakMovement = "";
                               $EfileDakMovement = EfileDakMovement::find()->where(['file_id' =>$file_id,'dak_group_id' =>$old_id,'fwd_emp_code'=>$valuem->employee_code, 'fwd_to'=>'G'])->all();
                               if(!empty($EfileDakMovement)){
                                   // Inactive one by one 
                                   foreach($EfileDakMovement as $e){
                                       $UpdateMove = EfileDakMovement::find()->where(['id' =>$e->id])->one();
                                       $UpdateMove->is_active = "N";
                                       $UpdateMove->save();
                                   }
                               }
                           }
                       }

                   }elseif($movement['fwd_to'] == 'E'){
                        if(!empty($old_id)){
                            $EfileDakMovement = EfileDakMovement::find()->where(['id' =>$old_id])->one();
                            $EfileDakMovement->is_active = "N";
                            $EfileDakMovement->save();
                        }
                   }
                   /*
                    * End 
                    */

                   $dak_group_id = NULL;
                   $fwd_emp_code =$hirarchy->authority1;
                   $emp_dept_id=$fla_info->dept_id;
                    $fileinfo = Yii::$app->fts_utility->efile_get_dak($file_id, NULL, NULL, NULL);
                    
                    Yii::$app->fts_utility->efile_add_update_dak_history("A", NULL, $file_id, $fileinfo['action_type'], $fwd_to, $dak_group_id, $fwd_emp_code, $emp_dept_id);
//                   $historyModel = new EfileDakHistory();
//                   $historyModel->file_id = $file_id;
//                   $historyModel->fwd_to = $fwd_to;
//                   $historyModel->action_id = $fileinfo['action_type'];
//                   $historyModel->dak_group_id = $dak_group_id;
//                   $historyModel->fwd_emp_code = $fwd_emp_code;
//                   $historyModel->fwd_emp_dept_id = $emp_dept_id;
//                   $historyModel->fwd_by = Yii::$app->user->identity->e_id;
//                   $historyModel->created_date = date('Y-m-d H:i:s');
//                   $historyModel->is_active = "Y";
//                   $historyModel->save();

                    EfileDakTemp::deleteAll(['file_id'=>$file_id ]);
                   //Email Configuration

                        $fwd_emp_list[0]['employee_code'] = $fwd_emp_code;
                   Yii::$app->Dakutility->sendEmailwithAttachmenttouser($file_id, $fwd_to, $fwd_emp_list, Yii::$app->user->identity->e_id);

                   $url = Yii::$app->homeUrl."efile/inbox?securekey=$menuid";
                        Yii::$app->getSession()->setFlash('success', "File Forwarded Successfully.");
                        return $this->redirect($url);
           }
           /*
            * END Hirarchy **************************************************
            */
//           echo "<pre>";print_r($_POST); die;
           $cc_emp_list = array();
            // die($_POST['forward_type']." 2");
            if($_POST['forward_dak'] == 'Y'){
                if($_POST['forward_type'] == 'MMG'){
                    $group_id = NULL;
                    $fwd_to = "E";
                    $headMMG = RbacEmployeeRole::find()->where(['role_id'=>'16', 'is_active'=>'Y'])->one();
                    if(empty($headMMG)){
                        Yii::$app->getSession()->setFlash('danger', "Head MMG Info Not Found.");
                        return $this->redirect($url);
                    }
                    $emp_name = Yii::$app->utility->get_employees($headMMG->employee_code);
                    $fwd_emp_list[0]['employee_code'] = $headMMG->employee_code;
                    $fwd_emp_list[0]['emp_dept_id'] = $emp_name['dept_id'];
                    $fwd_emp_list[0]['reply_status'] = "N";
                    $fwd_emp_list[0]['is_reply_required'] = "Y";
                }elseif($_POST['forward_type'] == 'H'){
                    $group_id = NULL;
                    $fwd_to = "E";
                    $hod_emp_code = Yii::$app->utility->decryptString($_POST['hod_emp_code']);
                    
                    if(empty($hod_emp_code)){
                        Yii::$app->getSession()->setFlash('danger', "Invalid HOD Employee Code.");
                        return $this->redirect($url);
                    }
                    $emp_name = Yii::$app->utility->get_employees($hod_emp_code);
                    $fwd_emp_list[0]['employee_code'] = $hod_emp_code;
                    $fwd_emp_list[0]['emp_dept_id'] = $emp_name['dept_id'];
                    $fwd_emp_list[0]['reply_status'] = "N";
                    $fwd_emp_list[0]['is_reply_required'] = "Y";
                }elseif($_POST['forward_type'] == 'I'){
                    $group_id = NULL;
                    $fwd_to = "E";
                    $indi_emp_code = Yii::$app->utility->decryptString($_POST['indi_emp_code']);
                    $fwd_emp_dept_id =  Yii::$app->utility->decryptString($_POST['indi_dept_id']);
                    if(empty($indi_emp_code) OR empty($fwd_emp_dept_id)){
                                    Yii::$app->getSession()->setFlash('danger', "Invalid Employee Code.");
                                    return $this->redirect($url);
                    }

                    $fwd_emp_list[0]['employee_code'] = $indi_emp_code;
                    $fwd_emp_list[0]['emp_dept_id'] = $fwd_emp_dept_id;
                    $fwd_emp_list[0]['reply_status'] = "N";
                    $fwd_emp_list[0]['is_reply_required'] = "Y";
                    
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
                }elseif($_POST['forward_type'] == 'G'){
                    if($_POST['group_type'] == 'E' OR $_POST['group_type'] == 'C'){

                    }else{
                        Yii::$app->getSession()->setFlash('danger', "Invalid Group Type.");
                        return $this->redirect($url);
                    }
                    // echo "<pre>";print_r($_POST['ExitGroup']);
// die;
                    $fwd_to = "G";
                    if($_POST['group_type'] == 'E'){
                        if(empty($_POST['ExitGroup'])){
                            Yii::$app->getSession()->setFlash('danger', "Select Any exits group.");
                            return $this->redirect($url);
                        }

                        $groups = $_POST['ExitGroup'];
                        foreach($groups as $grp){

                            $grp = Yii::$app->utility->decryptString($grp);

                            $groupInfo = EfileDakGroups::find()->where(['dak_group_id'=>$grp, 'file_id' => $file_id, 'is_active'=>'Y'])->asArray()->one();
                            if(empty($groupInfo)){
                                Yii::$app->getSession()->setFlash('danger', "Invalid Group ID.");
                                return $this->redirect($url);
                            }

                            $members = EfileDakGroupMembers::find()->where(['dak_group_id' => $grp, 'is_active'=>'Y'])->asArray()->all();
                            $j=0;
                            $group_id = $grp;
                            // echo "<pre>"; print_r($members); die;
                            foreach($members as $m){
                                $fwd_emp_list[$j]['employee_code'] = $m['employee_code'];
                                $fwd_emp_list[$j]['reply_status'] = "N";
                                $fwd_emp_list[$j]['emp_dept_id'] = $m['emp_dept_id'];
                                if($m['group_role'] == 'CH'){
                                    $fwd_emp_list[$j]['is_reply_required'] = "Y";	
                                    $j++;
                                }else{
                                    $fwd_emp_list[$j]['is_reply_required'] = "N";	
                                    $j++;
                                }
                            }
                        }


                    }elseif($_POST['group_type'] == 'C'){
                        $group_name = Yii::$app->fts_utility->validateHindiString($_POST['group_name']);
                        if(empty($group_name)){

                            Yii::$app->getSession()->setFlash('danger', "Require Group Name.");
                            return $this->redirect($url);
                        }

                        $gruop = $_POST['Group'];
                        $group_chairman_emp_code = Yii::$app->utility->decryptString($gruop['group_chairman_emp_code']);
                        $group_chairman_dept_id = Yii::$app->utility->decryptString($gruop['chairman_dept_id']);
                        $group_convenor_emp_code = Yii::$app->utility->decryptString($gruop['group_convenor_emp_code']);
                        $group_convenor_dept_id = Yii::$app->utility->decryptString($gruop['convenor_dept_id']);
                        if(empty($group_chairman_emp_code) OR empty($group_convenor_emp_code) OR empty($group_chairman_dept_id) OR empty($group_convenor_dept_id)){

                            foreach($uploadDocs as $u){ Yii::$app->fts_utility->removefile($u['doc_path']); }

                            Yii::$app->getSession()->setFlash('danger', "Invalid Emp Code of Group Chairman / Convenor Member.");
                            return $this->redirect($url);
                        }

                        if($group_chairman_emp_code == $group_convenor_emp_code){

                            Yii::$app->getSession()->setFlash('danger', "Group Chairman / Convenor Member cannot be same.");
                            return $this->redirect($url);
                        }


                        $group_members[0]['employee_code']=$group_chairman_emp_code;
                        $group_members[0]['emp_dept_id']=$group_chairman_dept_id;
                        $group_members[0]['group_role']="CH";
                        $group_members[1]['employee_code']=$group_convenor_emp_code;
                        $group_members[1]['emp_dept_id']=$group_convenor_dept_id;
                        $group_members[1]['group_role']="C";

                        $finalGrpEmp = $_POST['finalGrpEmp'];
                        $finalGrpEmpDept = $_POST['finalGrpEmpDept'];
                        if(empty($finalGrpEmp) OR empty($finalGrpEmpDept)){

                            Yii::$app->getSession()->setFlash('danger', "Select Members for Group.");
                            return $this->redirect($url);
                        }

                        $g=2;
                        foreach($finalGrpEmp as $key=>$m){
                            $employee_code = "";
                            $employee_code = Yii::$app->utility->decryptString($m);
                            $emp_dept_id = Yii::$app->utility->decryptString($finalGrpEmpDept[$key]);
                            if(empty($employee_code) OR empty($emp_dept_id)){

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

                            Yii::$app->getSession()->setFlash('danger', "Group ID not found.");
                            return $this->redirect($url);
                        }
                        $dak_group_id = $group_id;

                        $fwd_emp_list = array();

                        $j=0;
                        // echo "***<pre>";print_r($group_members);
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
                    }else{
                        Yii::$app->getSession()->setFlash('danger', "Invalid Forward Group / Commitee To Type.");
                        return $this->redirect($url);
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
                                $fwd_emp_list[$j]['emp_dept_id'] = $a['dept_id'];
                                $fwd_emp_list[$j]['is_reply_required'] = "N";
                                $j++;
                            }
                        }
                    }
                }else{
                    Yii::$app->getSession()->setFlash('danger', "Invalid Forward Type.");
                    return $this->redirect($url);
                }
//                echo $movement['fwd_to']."<br>";
//                echo $group_id."<br>";
//                 echo "***<pre>";print_r($fwd_emp_list); die;
                /*
                * Save Note 
                */
//                echo "$AddNote"; 
               // echo "<pre>";print_r($fwd_emp_list); die;
//                die;
                

                 /*
                 * End Save Note 
                 */
                
               
                $Param_file_doc_info = NULL;
                foreach($fwd_emp_list as $f){
                        Yii::$app->fts_utility->efile_add_update_efile_dak_movement("A", NULL, $file_id, $fwd_to, $group_id, $f['employee_code'], $is_time_bound, "FileGreenSheet", $response_date, NULL, $f['is_reply_required'], $f['reply_status'], Yii::$app->user->identity->e_id, "N", $f['emp_dept_id'], $Param_file_doc_info);
                }
				if($AddNote == "Y"){
                    $groupid = NULL;
                    if($movement['fwd_to'] == 'G'){
                        $groupid =  $movement['dak_group_id'];;
                    }
                    Yii::$app->fts_utility->elif_add_efile_dak_notes($actionType, $file_id, $note_comment, $file_attach, $note_subject, $status, $note_id, $content_type, $Param_file_fwd_type, $groupid);
                }
                if($fwd_to == 'E' OR $fwd_to == 'CC'){
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
                $action_id = $fileinfo['action_type'];
                Yii::$app->fts_utility->efile_add_update_dak_history("A", NULL, $file_id, $action_id, $fwd_to, $dak_group_id, $fwd_emp_code, $emp_dept_id);
//                $historyModel = new EfileDakHistory();
//                $historyModel->file_id = $file_id;
//                $historyModel->fwd_to = $fwd_to;
//                $historyModel->action_id = $action_id;
//                $historyModel->dak_group_id = $dak_group_id;
//                $historyModel->fwd_emp_code = $fwd_emp_code;
//                $historyModel->fwd_emp_dept_id = $emp_dept_id;
//                $historyModel->fwd_by = Yii::$app->user->identity->e_id;
//                $historyModel->created_date = date('Y-m-d H:i:s');
//                $historyModel->is_active = "Y";
//                $historyModel->save();

                /*
                * Update Old movement
                */

               if($movement['fwd_to'] == 'G'){
                     $EfileDakGroupMembersd = EfileDakGroupMembers::find()->where(['dak_group_id' =>$old_id])->all();
                   if(!empty($EfileDakGroupMembersd))
                   {
                       foreach ($EfileDakGroupMembersd as $key => $valuem) 
                       {
                           $EfileDakMovement = "";
                           $EfileDakMovement = EfileDakMovement::find()->where(['file_id' =>$file_id,'dak_group_id' =>$old_id,'fwd_emp_code'=>$valuem->employee_code, 'fwd_to'=>'G'])->all();
                           if(!empty($EfileDakMovement)){
                               // Inactive one by one 
                               foreach($EfileDakMovement as $e){
                                   $UpdateMove = EfileDakMovement::find()->where(['id' =>$e->id])->one();
                                   $UpdateMove->is_active = "N";
                                   $UpdateMove->save();
                               }
                           }
                       }
                   }

               }elseif($movement['fwd_to'] == 'E'  OR $movement['fwd_to'] == 'CC'){
                    if(!empty($old_id)){
                        $EfileDakMovement = EfileDakMovement::find()->where(['id' =>$old_id])->one();
                        $EfileDakMovement->is_active = "N";
                        $EfileDakMovement->save();
                    }
               }
               /*
                * End 
                */


                EfileDakTemp::deleteAll(['file_id'=>$file_id ]);


                //Email Configuration
                Yii::$app->Dakutility->sendEmailwithAttachmenttouser($file_id, $fwd_to, $fwd_emp_list, Yii::$app->user->identity->e_id);
                if(!empty($cc_emp_list)){
                    $Param_file_doc_info = array();
                    $notes = EfileDakNotes::find()->select('GROUP_CONCAT(noteid) as note_ids')->where(['file_id'=>$file_id, 'is_active'=>'Y'])->asArray()->one();
                    $docs = EfileDakDocs::find()->select('GROUP_CONCAT(dakdocs_id) as doc_ids')->where(['file_id'=>$file_id, 'is_active'=>'Y'])->asArray()->one();
                    $History = EfileDakHistory::find()->select('GROUP_CONCAT(file_history_id) as history_ids')->where(['file_id'=>$file_id, 'is_active'=>'Y'])->asArray()->one();
                    
                    $Param_file_doc_info['notes'] = $notes['note_ids']; 
                    $Param_file_doc_info['docs'] = $docs['doc_ids']; 
                    $Param_file_doc_info['history'] = $History['history_ids'];
                    $Param_file_doc_info = json_encode($Param_file_doc_info);
                    $fwd_to = "CC";
                    foreach($cc_emp_list as $c){
                        Yii::$app->fts_utility->efile_add_update_efile_dak_movement("A", NULL, $file_id, $fwd_to, $group_id, $c['employee_code'], $is_time_bound, "FileGreenSheet", $response_date, NULL, $c['is_reply_required'], $c['reply_status'], Yii::$app->user->identity->e_id, "N", $c['emp_dept_id'], $Param_file_doc_info);
                        
                        Yii::$app->fts_utility->efile_add_update_dak_history("A", NULL, $file_id, $action_id, $fwd_to, $group_id, $c['employee_code'], $c['emp_dept_id']);
                    } //end
                    
                    Yii::$app->Dakutility->sendEmailwithAttachmenttouser($file_id, $fwd_to, $cc_emp_list, Yii::$app->user->identity->e_id);
                }
                $url = Yii::$app->homeUrl."efile/inbox?securekey=$menuid";
                Yii::$app->getSession()->setFlash('success', "File Forwarded Successfully.");
                return $this->redirect($url);
            }else{
                Yii::$app->getSession()->setFlash('danger', "Invalid Forward Dak Type.");
                return $this->redirect($url);
            }

            }else{
                Yii::$app->getSession()->setFlash('danger', "Invalid params found.");
                return $this->redirect($url);
            }	
	}
        public function actionScandocupload(){
		$menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."efile/inbox?securekey=$menuid";
		
		if(isset($_POST['Scan']) AND !empty($_POST['Scan'])){
			$post = $_POST['Scan'];
			$file_id = Yii::$app->utility->decryptString($post['key']);
			$movement_id = Yii::$app->utility->decryptString($post['key1']);
			
			if(empty($file_id) OR empty($movement_id)){
				Yii::$app->getSession()->setFlash('danger', "Invalid params value found.");
				return $this->redirect($url);
			}
			
			$fileinfo = Yii::$app->fts_utility->efile_get_dak($file_id, NULL, NULL, NULL);
			if(empty($fileinfo)){
				Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
				return $this->redirect($url);
			}
			if($fileinfo['status'] != 'Scan'){
				Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
				return $this->redirect($url);
			}
			// Check file send to current user or not
			$movement = Yii::$app->fts_utility->efile_get_efile_dak_movement(NULL, $movement_id);
			
			// echo "<pre>";print_r($movement);die;
			if(empty($movement)){
				Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
				return $this->redirect($url);
			}
			if($movement['is_active'] == 'N'){
				Yii::$app->getSession()->setFlash('danger', "File already forwarded.");
				return $this->redirect($url);
			}
			if($movement['fwd_emp_code'] != Yii::$app->user->identity->e_id){
				Yii::$app->getSession()->setFlash('danger', "You are not authorized to view this file.");
				return $this->redirect($url);
			}
			
			$doc_type = Yii::$app->utility->decryptString($post['doc_type']);
			$upload_Tye = "";
            $docFile = array();
            if($doc_type == 'PDF' OR $doc_type == 'Image'){
                if($doc_type == 'PDF'){
                    if(isset($_FILES['pdf_path']['tmp_name']) AND !empty($_FILES['pdf_path']['tmp_name'])){
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
                }
            }else{
				Yii::$app->getSession()->setFlash('danger', "Invalid document type found.");
				return $this->redirect($url);
            }

			
			/*
			* Enter in Dak Table
			*/
			
			if(empty($docFile)){
				Yii::$app->getSession()->setFlash('danger', "Documents list not found.");
				return $this->redirect($url);
			}

			$uploadDocs = array();
			$k=0;
			$FTS_Documents = FTS_Documents;


			$doc_path="";
			if($upload_Tye == 'PDF'){
				$doc_path = Yii::$app->fts_utility->uploadFile($docFile['tmp_name'], $docFile['file_name'], $FTS_Documents);
				if(empty($doc_path)){
					Yii::$app->getSession()->setFlash('danger', "File document didn\'t uploaded. Contact Admin.");
					return $this->redirect($url);
				}

				$uploadDocs['doc_path'] = $doc_path;
				$uploadDocs['doc_ext_type'] = "PDF";

			}
//                        elseif($upload_Tye == 'Image'){
//				$doc_path = Yii::$app->fts_utility->uploadImageTopdf($docFile, $FTS_Documents);
//				if(empty($doc_path)){
//					Yii::$app->getSession()->setFlash('danger', "File document didn\'t uploaded. Contact Admin.");
//					return $this->redirect($url);
//				}
//				$uploadDocs['doc_path'] = $doc_path;
//				$uploadDocs['doc_ext_type'] = "PDF";
//			}
			
			
			$docs = new EfileDakDocs();
			$docs->file_id = $file_id;
			$docs->attach_with = "File";
			$docs->doc_ext_type = $uploadDocs['doc_ext_type'];
			$docs->docs_path = $uploadDocs['doc_path'];
			$docs->added_by = Yii::$app->user->identity->e_id;
			$docs->added_by_dept_id = Yii::$app->user->identity->dept_id;
			$docs->created_date = date('Y-m-d H:i:s');
			$docs->is_active = "Y";
			
			if($docs->save()){
				$fileModel = EfileDak::find()->where(['file_id'=>$file_id, 'is_active'=>'Y'])->one();
				$fileModel->status = "Open";
				$fileModel->save(); //Update status of file scan to open
				
				$m = EfileDakMovement::find()->where([ 'id'=>$movement['id'], 'file_id'=>$file_id])->one();
				$m->is_active = "N";
				$m->save(); //old movement
				
				$info = Yii::$app->utility->get_employees($movement['fwd_by']);
				// echo "Info <pre>";print_r($info);die;
				$modelMove = new EfileDakMovement();
				$modelMove->file_id = $file_id;
				$modelMove->fwd_to = "E";
				$modelMove->dak_group_id = NULL;
				$modelMove->fwd_emp_code = $movement['fwd_by'];
				$modelMove->fwd_emp_dept_id = $info['dept_id'];
				$modelMove->is_time_bound = $movement['is_time_bound'];
				$modelMove->fwd_file_type = $movement['fwd_file_type'];
				$modelMove->response_date = $movement['response_date'];
				$modelMove->is_reply_required = $movement['is_reply_required'];
				$modelMove->reply_status = "N";
				$modelMove->fwd_by = Yii::$app->user->identity->e_id;
				$modelMove->fwd_date = date('Y-m-d H:i:s');
				$modelMove->is_active = "Y";
				// echo "<pre>";print_r($modelMove);
				// if(!$modelMove->validate()){
					// echo "<pre>";print_r($modelMove->getErrors());
					// die;
				// }else{
					// die("OKKKKK");
				// }
				$modelMove->save(); //new entry in movement table
				$emps = Yii::$app->utility->get_employees($movement['fwd_by']);
                                Yii::$app->fts_utility->efile_add_update_dak_history("A", NULL, $file_id, NULL, "E", NULL, $movement['fwd_by'], $emps['dept_id']);
                                
//				$historyModel = new EfileDakHistory();
//				$historyModel->file_id = $file_id;
//				$historyModel->fwd_to = "E";
//				$historyModel->dak_group_id = NULL;
//				$historyModel->fwd_emp_code = $movement['fwd_by'];
//				$historyModel->fwd_by = Yii::$app->user->identity->e_id;
//				$historyModel->created_date = date('Y-m-d H:i:s');
//				$historyModel->is_active = "Y";
//				$historyModel->save(); //history
				
				$fwd_emp_list = array();
				$fwd_emp_list[0]['employee_code'] = $movement['fwd_by'];
				//die("OKKK");
				//Email Configuration
				Yii::$app->Dakutility->sendEmailwithAttachmenttouser($file_id, "E", $fwd_emp_list, Yii::$app->user->identity->e_id);
				Yii::$app->getSession()->setFlash('success', "File forwarded successfully..");
				return $this->redirect($url);
			}else{
				Yii::$app->fts_utility->removefile($uploadDocs['doc_path']);
				Yii::$app->getSession()->setFlash('danger', "File didn't Updated. Contact Admin.");
				return $this->redirect($url);
			}
		}
		
		
        if(isset($_GET['key']) AND !empty($_GET['key']) AND isset($_GET['key2']) AND !empty($_GET['key2'])){
			$file_id = Yii::$app->utility->decryptString($_GET['key']);
			$movement_id = Yii::$app->utility->decryptString($_GET['key2']);
			
			if(empty($file_id) OR empty($movement_id)){
				Yii::$app->getSession()->setFlash('danger', "Invalid params value found.");
				return $this->redirect($url);
			}
			
			$fileinfo = Yii::$app->fts_utility->efile_get_dak($file_id, NULL, NULL, NULL);
			if(empty($fileinfo)){
				Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
				return $this->redirect($url);
			}
			if($fileinfo['status'] != 'Scan'){
				Yii::$app->getSession()->setFlash('danger', "File already forwarded.");
				return $this->redirect($url);
			}
			
			$receiptInfo = "";
			
			// Check file send to current user or not
			$movement = Yii::$app->fts_utility->efile_get_efile_dak_movement(NULL, $movement_id);
			if(empty($movement)){
				Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
				return $this->redirect($url);
			}
			if($movement['is_active'] == 'N'){
				Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
				return $this->redirect($url);
			}
			if($movement['fwd_emp_code'] != Yii::$app->user->identity->e_id){
				Yii::$app->getSession()->setFlash('danger', "You are not authorized to view this file.");
				return $this->redirect($url);
			}
			$receiptInfo = array();
			if(!empty($fileinfo['rec_id'])){
				$receiptInfo = Yii::$app->fts_utility->efile_get_dak_received($fileinfo['rec_id'], NULL);
			}
			$this->layout = '@app/views/layouts/admin_layout.php';
			return $this->render('scandocupload', ['menuid'=>$menuid, 'fileinfo'=>$fileinfo, 'movement'=>$movement, 'receiptInfo'=>$receiptInfo]);
		}else{
			Yii::$app->getSession()->setFlash('danger', "Invalid params found.");
			return $this->redirect($url);
		}	
			
	}
        
    public function actionUpdatefileinfo(){
        $result = array();
        if (\Yii::$app->user->isGuest) {
            $result['Status'] = "FF";
            $result['Res'] = "Session TimeOut. Re-login please.";
            echo json_encode($result);
            die;
        }
        
        if(isset($_POST['update_key']) AND !empty($_POST['update_key']) AND isset($_POST['category_id']) AND !empty($_POST['category_id'])  AND isset($_POST['priority']) AND !empty($_POST['priority']) AND isset($_POST['forward_for']) AND !empty($_POST['forward_for'])){
            $post = $_POST;
            $file_id = Yii::$app->utility->decryptString($post['update_key']);
            $category_id = Yii::$app->utility->decryptString($post['category_id']);
            $priority = Yii::$app->utility->decryptString($post['priority']);
            $forward_for = Yii::$app->utility->decryptString($post['forward_for']);
            
            if(empty($file_id) OR empty($category_id) OR empty($priority) OR empty($forward_for)){
                $result['Status'] = "FF";
                $result['Res'] = "Invalid params value found.";
                echo json_encode($result);
                die;
            }
            $category = EfileMasterCategory::find()->where(['file_category_id'=>$category_id, 'is_active' => "Y"])->asArray()->one();
            if(empty($category)){
                $result['Status'] = "FF";
                $result['Res'] = "Invalid Category Found.";
                echo json_encode($result); die;
            }
            $project_id = NULL;
            if($category['related_to_project'] == 'Y'){
                $project_id = Yii::$app->utility->decryptString($post['project_id']);
                if(empty($project_id)){
                    $result['Status'] = "FF";
                    $result['Res'] = "Select Project.";
                    echo json_encode($result); die;
                }
            }
            
            $model = EfileDak::find()->where(['file_id'=>$file_id, 'is_active'=>'Y'])->one();
            $model_ = EfileDak::find()->where(['file_id'=>$file_id, 'is_active'=>'Y'])->asArray()->one();
            
            $model->file_category_id = $category_id;
            $model->file_project_id = $project_id;
            $model->action_type = $forward_for;
            $model->priority = $priority;
            $model->last_updated = date('Y-m-d H:i:s');
            $model->save();
            
            $logs = array();
            $logs['old_data'] = json_encode($model_);
            $logs['file_category_id'] = $category_id;
            $logs['file_project_id'] = $project_id;
            $logs['action_type'] = $forward_for;
            $logs['priority'] = $priority;
            $logs['last_updated'] = date('Y-m-d H:i:s');;
            $param_data_json = json_encode($logs);
            
            Yii::$app->utility->activities_logs("eFileDak", NULL, NULL, $param_data_json, "File Info Updated Successfully");
            
            Yii::$app->getSession()->setFlash('success', "Successfully Updated.");
            
            $result['Status'] = "SS";
            $result['Res'] = "Successfully Updated.";
            echo json_encode($result); die;
//            echo "<pre>";print_r($model);die;
            
        }else{
            $result['Status'] = "FF";
            $result['Res'] = "Invalid params found.";
            echo json_encode($result);
            die;
        }
    }
    
    public function actionAdd_new_tag(){
        $result = array();
        if(isset($_POST['tag_key']) AND !empty($_POST['tag_key']) AND isset($_POST['page_number']) AND !empty($_POST['page_number'])  AND isset($_POST['tag_content']) AND !empty($_POST['tag_content'])){
            $post = $_POST;
            $file_id = Yii::$app->utility->decryptString($post['tag_key']);
            $page_number = Yii::$app->fts_utility->onlyNumber($post['page_number']);
            $tag_content = Yii::$app->fts_utility->validateHindiString($post['tag_content']);
            
            if(empty($file_id) OR empty($page_number) OR empty($tag_content)){
                $result['Status'] = "FF";
                $result['Res'] = "Invalid params value found.";
                echo json_encode($result);
                die;
            }
            
            $result_ = Yii::$app->fts_utility->efile_add_update_dak_tags("A", NULL, $file_id, $page_number, $tag_content, "Y");
            
            if($result_ == '1'){
                $result['Status'] = "SS";
                Yii::$app->getSession()->setFlash('success', "Tag Added Successfully.");
                $result['Res'] = "Tag Added Successfully.";
                echo json_encode($result);
                die;
            }else{
                $result['Status'] = "FF";
                $result['Res'] = "Tag not saved. Contact Admin.";
                echo json_encode($result);
                die;
            }
            
        }else{
            $result['Status'] = "FF";
            $result['Res'] = "Invalid params found.";
            echo json_encode($result);
            die;
        }
    }
    
    public function actionRemovefiletag(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."efile/inbox?securekey=$menuid";
        
        if(isset($_GET['key']) AND !empty($_GET['key']) AND isset($_GET['key1']) AND !empty($_GET['key1']) AND isset($_GET['key2']) AND !empty($_GET['key2'])){
            $file_id = Yii::$app->utility->decryptString($_GET['key']);
            $tag_id = Yii::$app->utility->decryptString($_GET['key1']);
            $movement_id = Yii::$app->utility->decryptString($_GET['key2']);
            
            $movement = Yii::$app->fts_utility->efile_get_efile_dak_movement(NULL, $movement_id);
            if(empty($movement)){
                Yii::$app->getSession()->setFlash('danger', "You are not authorized to remove tag.");
                return $this->redirect($url);
            }
            if($movement['file_id'] == 'N'){
                Yii::$app->getSession()->setFlash('danger', "You are not authorized to remove tag.");
                return $this->redirect($url);
            }
            if($movement['file_id'] != $file_id){
                Yii::$app->getSession()->setFlash('danger', "Invalid params found.");
                return $this->redirect($url);
            }
            $Param_is_active = $Param_tag_content = $Param_page_number = NULL;
            $result = Yii::$app->fts_utility->efile_add_update_dak_tags("D", $tag_id, $file_id, $Param_page_number, $Param_tag_content, $Param_is_active);
            
            $f_id = Yii::$app->utility->encryptString($file_id);
            $m_id = Yii::$app->utility->encryptString($movement['id']);
            
            $url = Yii::$app->homeUrl."efile/inbox/viewdakwithnoting?securekey=$menuid&key=$f_id&key2=$m_id";
            
            if($result == '2'){
                Yii::$app->getSession()->setFlash('success', "Tag Removed Successfully.");
                return $this->redirect($url);
            }else{
                Yii::$app->getSession()->setFlash('danger', "Tag didn't Removed. Contact Admin.");
                return $this->redirect($url);
            }
        }else{
            Yii::$app->getSession()->setFlash('danger', "Invalid params found.");
            return $this->redirect($url);
        }
        
    }
    
    public function actionClosefile(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."efile/inbox?securekey=$menuid";
        if(isset($_GET['key']) AND !empty($_GET['key'])){
            $file_id = Yii::$app->utility->decryptString($_GET['key']);
            if(empty($file_id)){
                Yii::$app->getSession()->setFlash('danger', "Invalid params value found.");
                return $this->redirect($url);
            }
            $fileinfo = Yii::$app->fts_utility->efile_get_dak($file_id, NULL, NULL, NULL);
            if(empty($fileinfo)){
                Yii::$app->getSession()->setFlash('danger', "No Record Found.");
                return $this->redirect($url);
            }
            
             // if(($fileinfo['file_category_id'] == '2' AND Yii::$app->user->identity->e_id == '200017') OR ($fileinfo['file_category_id'] == '1' AND Yii::$app->user->identity->e_id == '200043') OR (Yii::$app->user->identity->role == '2')){ 
             if((Yii::$app->user->identity->e_id == '200017') OR (Yii::$app->user->identity->e_id == '200043') OR (Yii::$app->user->identity->role == '2')){ 
                $model = EfileDak::find()->where(['file_id'=>$file_id])->one();
                if($model->status == "Closed"){
                    Yii::$app->getSession()->setFlash('danger', "File Already Closed.");
                    return $this->redirect($url);
                }
                 
                $model->action_type = $fileinfo['action_type'];
                $model->status = "Closed";
                $model->file_close_by = Yii::$app->user->identity->e_id;
                $model->last_updated = date('Y-m-d H:i:s');
//                if(!$model->validate()){
//                    echo "<pre>";print_r($model->getErrors());
//                    die('');
//                }
                $model->save();
                Yii::$app->getSession()->setFlash('success', "File Closed Successfully.");
                return $this->redirect($url);
                
            }else{
                Yii::$app->getSession()->setFlash('danger', "You are not authorized to close file. Contact Admin.");
                return $this->redirect($url);
            }
        }else{
            Yii::$app->getSession()->setFlash('danger', "Invalid params found.");
            return $this->redirect($url);
        }
        
        $fileinfo = Yii::$app->fts_utility->efile_get_dak($file_id, NULL, NULL, NULL);
    }
	
	public function actionOpenprotecteddocument(){
		$result = array();
		if(isset($_POST['doc_id']) AND !empty($_POST['doc_id'])AND (isset($_POST['doc_password']) AND !empty($_POST['doc_password']))){
			$doc_id = Yii::$app->utility->decryptString($_POST['doc_id']);
			$password = trim($_POST['doc_password']);
			if(empty($doc_id) OR empty($password)){
				$result['Status'] = "FF";
				$result['Res'] = "Invalid Params Values Found";
				echo json_encode($result); die;
			}
			$password = \md5($password);
			$model = EfileDakDocs::find()->where(['dakdocs_id'=>$doc_id, 'is_protected'=>'Y', 'is_active'=>'Y', 'doc_password'=>$password])->one();
			
			if(empty($model)){
				$result['Status'] = "FF";
				$result['Res'] = "No Record Found.";
				echo json_encode($result); die;
			}
			
			$model = EfileDakDocs::find()->where(['dakdocs_id'=>$doc_id, 'doc_password'=>$password])->one();
			
			if(empty($model)){
				$result['Status'] = "FF";
				$result['Res'] = "Invalid Password.";
				echo json_encode($result); die;
			}
			
			$result['Status'] = "SS";
			$result['Res'] = "Success";
			$path = Yii::$app->fts_utility->getdocumentpath($model->docs_path);
			$result['path'] = $path;
			echo json_encode($result); die;
			
			// echo "<pre>";print_r($model);die;
		}else{
			$result['Status'] = "FF";
			$result['Res'] = "Invalid Params Found";
			echo json_encode($result); die;
		}
		
	}
    public function actionForwardtoheadmmg(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."efile/inbox?securekey=$menuid";
        if(isset($_POST['Forward']) AND !empty($_POST['Forward'])){
            $post = $_POST['Forward'];              
            $file_id = Yii::$app->utility->decryptString($post['key']);
            $movement_id = Yii::$app->utility->decryptString($post['key1']);

            $old_id = Yii::$app->utility->decryptString($_POST['old_id']);
            if(empty($file_id) OR empty($movement_id) OR empty($old_id)){
                Yii::$app->getSession()->setFlash('danger', "Invalid params value found.");
                return $this->redirect($url);
            }

            $fileid = Yii::$app->utility->encryptString($file_id);
            $movementid = Yii::$app->utility->encryptString($movement_id);
            $url = Yii::$app->homeUrl."efile/inbox/viewdakwithnoting?securekey=$menuid&key=$fileid&key2=$movementid";
            $fileinfo = Yii::$app->fts_utility->efile_get_dak($file_id, NULL, NULL, NULL);
            if(empty($fileinfo)){
                Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
                return $this->redirect($url);
            }
            if(Yii::$app->user->identity->e_id == '343252' AND $fileinfo['file_category_id'] == '2'){
            }else{
                Yii::$app->getSession()->setFlash('danger', "You are not authorized to access this action.");
                return $this->redirect($url);
            }
            // Check file send to current user or not
            $movement = Yii::$app->fts_utility->efile_get_efile_dak_movement(NULL, $movement_id);
            if(empty($movement)){
                Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
                return $this->redirect($url);
            }
            if($movement['fwd_emp_code'] != Yii::$app->user->identity->e_id){
                Yii::$app->getSession()->setFlash('danger', "You are not authorized to view this file.");
                return $this->redirect($url);
            }
            if($fileinfo['is_active'] == 'N'){
                Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
                return $this->redirect($url);
            }
            if($fileinfo['status'] != 'Open'){
                Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
                return $this->redirect($url);
            }
            if($movement['is_active'] == 'N'){
                Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
                return $this->redirect($url);
            }
            $headMMG = RbacEmployeeRole::find()->where(['role_id'=>'16', 'is_active'=>'Y'])->one();
            if(empty($headMMG)){
                Yii::$app->getSession()->setFlash('danger', "Head MMG Info Not Found.");
                return $this->redirect($url);
            }
            
            $note_comment = Yii::$app->fts_utility->validateHindiString($post['fwd_note_comment']);
            if(empty($note_comment)){
                Yii::$app->getSession()->setFlash('danger', "Required Comment on Note Sheet.");
                return $this->redirect($url);
            }
            $actionType = "A";
            $note_id = $note_subject = NULL;
            $file_attach = "N";
            $status = "S";
            $content_type = "N";
            $Param_file_fwd_type = $movement['fwd_to'];
            $group_id = NULL;
Yii::$app->fts_utility->elif_add_efile_dak_notes($actionType, $file_id, $note_comment, $file_attach, $note_subject, $status, $note_id, $content_type, $Param_file_fwd_type, $group_id);

            $EfileDakMovement = EfileDakMovement::find()->where(['id' =>$old_id])->one();
            $EfileDakMovement->is_active = "N";
            $EfileDakMovement->save();
            // echo "<pre>";print_r($movement); die;
            $response_date = $movement['response_date'];
            $is_time_bound = $movement['is_time_bound'];
            $is_reply_required = "Y";
            $reply_status = "N";
            $fwd_to = "E";
            $dept_id = "6";
            $Param_file_doc_info = NULL;
            Yii::$app->fts_utility->efile_add_update_efile_dak_movement("A", NULL, $file_id, $fwd_to, $group_id, $headMMG->employee_code, $is_time_bound, "FileGreenSheet", $response_date, NULL, $is_reply_required, $reply_status, Yii::$app->user->identity->e_id, "N", $dept_id, $Param_file_doc_info);

            $historyModel = new EfileDakHistory();
            $historyModel->file_id = $file_id;
            $historyModel->fwd_to = $fwd_to;
            $historyModel->action_id = $fileinfo['action_type'];
            $historyModel->dak_group_id = $group_id;
            $historyModel->fwd_emp_code = $headMMG->employee_code;
            $historyModel->fwd_emp_dept_id = $dept_id;
            $historyModel->fwd_by = Yii::$app->user->identity->e_id;
            $historyModel->created_date = date('Y-m-d H:i:s');
            $historyModel->is_active = "Y";
            $historyModel->save();
            
            EfileDakTemp::deleteAll(['file_id'=>$file_id ]);
            $fwd_emp_list = array();
            $fwd_emp_list[0]['employee_code'] = $headMMG->employee_code;			
            //Email Configuration
            Yii::$app->Dakutility->sendEmailwithAttachmenttouser($file_id, $fwd_to, $fwd_emp_list, Yii::$app->user->identity->e_id);
            $url = Yii::$app->homeUrl."efile/inbox?securekey=$menuid";
            Yii::$app->getSession()->setFlash('success', "File Forwarded to Head MMG Successfully.");
            return $this->redirect($url);
        }else{
            Yii::$app->getSession()->setFlash('danger', "Invalid Params Found.");
            return $this->redirect($url);
        }
        
//        echo "<pre>";print_r($_POST['Forward']); 
    }
    
    public function actionMovetoccfiles(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."efile/inbox?securekey=$menuid";
        
        if(isset($_GET['key']) AND !empty($_GET['key']) AND isset($_GET['key1']) AND !empty($_GET['key1'])){
            $file_id = Yii::$app->utility->decryptString($_GET['key']);
            $movement_id = Yii::$app->utility->decryptString($_GET['key1']);

            $fileid = Yii::$app->utility->encryptString($file_id);
            $movementid = Yii::$app->utility->encryptString($movement_id);
            $url = Yii::$app->homeUrl."efile/inbox/viewdakwithnoting?securekey=$menuid&key=$fileid&key2=$movementid";
            $fileinfo = Yii::$app->fts_utility->efile_get_dak($file_id, NULL, NULL, NULL);
            if(empty($fileinfo)){
                Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
                return $this->redirect($url);
            }
            
            // Check file send to current user or not
            $movement = Yii::$app->fts_utility->efile_get_efile_dak_movement(NULL, $movement_id);
            if(empty($movement)){
                Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
                return $this->redirect($url);
            }
            if($movement['fwd_emp_code'] != Yii::$app->user->identity->e_id){
                Yii::$app->getSession()->setFlash('danger', "You are not authorized to view this file.");
                return $this->redirect($url);
            }
            if($fileinfo['is_active'] == 'N'){
                Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
                return $this->redirect($url);
            }
            if($fileinfo['status'] != 'Open'){
                Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
                return $this->redirect($url);
            }
            if($movement['is_active'] == 'N'){
                Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
                return $this->redirect($url);
            }
            
            $result = Yii::$app->fts_utility->efile_add_update_cc_dak("A", $file_id, $movement_id);
            $url = Yii::$app->homeUrl."efile/inbox?securekey=$menuid";
            if($result == '1'){
                Yii::$app->getSession()->setFlash('success', "File Moved Successfully.");
                return $this->redirect($url);
            }else{
                Yii::$app->getSession()->setFlash('danger', "File didn't Moved. Contact Admin");
                return $this->redirect($url);
            }
//            echo "DONE<pre>";print_r($_GET); die();
            
        }else{
            Yii::$app->getSession()->setFlash('danger', "Invalid Params Found.");
            return $this->redirect($url);
        }
        
    }
}
