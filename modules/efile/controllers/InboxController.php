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
		if(isset($_GET['fileid']) AND !empty($_GET['fileid']))
		{
			$fileid = Yii::$app->utility->decryptString($_GET['fileid']);
			Yii::$app->Dakutility->makenotesfile($fileid);
		}
       
        die();
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
            }elseif($_POST['submit_type'] == 'CHD'){
                $status = "CHD";
                $msg = "Final  Remarks added successfully.";
            }elseif($_POST['submit_type'] == 'CHF'){
                $status = "CHF";
                $msg = "Final Remarks added successfully.";
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
			
			$note_comment = Yii::$app->fts_utility->validateHindiString($post['note_comment']);
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
			// echo "$actionType $status $note_id<pre>";print_r($post);die;
			$result = Yii::$app->fts_utility->elif_add_efile_dak_notes($actionType, $file_id, $note_comment, $file_attach, $note_subject, $status, $note_id, $content_type);
			
			if(!empty($result)){
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
			
			$file_attach = "N";
			if(!empty($post['doc_type'])){
				$doc_type = Yii::$app->utility->decryptString($post['doc_type']);
				if(empty($doc_type)){
					Yii::$app->getSession()->setFlash('danger', "Invalid params value found.");
					return $this->redirect($url);
				}
				$file_attach = "Y";
			}
			// echo "$file_attach"; die;
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
			if($file_attach == 'Y'){
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
				}
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

					}elseif($upload_Tye == 'Image'){
						$doc_path = Yii::$app->fts_utility->uploadImageTopdf($docFile, $FTS_Documents);
						if(empty($doc_path)){
							Yii::$app->getSession()->setFlash('danger', "File document didn\'t uploaded. Contact Admin.");
							return $this->redirect($url);
						}
						$uploadDocs['doc_path'] = $doc_path;
						$uploadDocs['doc_ext_type'] = "PDF";
					}
					Yii::$app->fts_utility->efile_dak_docs($file_id, "File", $note_id, NULL, $uploadDocs['doc_ext_type'], $uploadDocs['doc_path']); 
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

                    

                            $group_id = NULL;
                    if($_POST['forward_dak'] == 'Y'){
                            if($_POST['forward_type'] == 'I'){
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
                                                    $j=1;
                                                    foreach($members as $m){
                                                            $fwd_emp_list[$j]['employee_code'] = $m['employee_code'];
                                                            $fwd_emp_list[$j]['reply_status'] = "N";

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
                                                    $group_members[$g]['employee_code']=$employee_code;
                                                    $group_members[$g]['emp_dept_id']=$emp_dept_id;
                                                    $group_members[$g]['group_role']="M";
                                                    $g++;
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
                            // echo "***<pre>";print_r($fwd_emp_list); die;
                            
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

                            /*
                             * Update Old movement
                             */
                            $old_id =   "";
                            if($movement['fwd_to'] == 'G'){
                                    $old_id = Yii::$app->utility->decryptString($_POST['old_id']);

                                    if(empty($old_id)){
                                            Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
                                            return $this->redirect($url);
                                    }
                                    $EfileDakGroupMembersd = EfileDakGroupMembers::find()->where(['dak_group_id' =>$old_id])->all();
                                    if(!empty($EfileDakGroupMembersd))
                                    {
                                            foreach ($EfileDakGroupMembersd as $key => $valuem) 
                                            {
                                                    $file_idm = Yii::$app->utility->decryptString($post['key']);
                                                    $EfileDakMovement = EfileDakMovement::find()->where(['file_id' =>$file_idm,'dak_group_id' =>$old_id,'fwd_emp_code'=>$valuem->employee_code])->one();
                                                    $EfileDakMovement->is_active = "N";
                                                    $EfileDakMovement->save();
                                            }
                                    }

                            }elseif($movement['fwd_to'] == 'E'){
                                    $old_id = Yii::$app->utility->decryptString($_POST['old_id']);
                                    if(empty($old_id)){
                                            Yii::$app->getSession()->setFlash('danger', "No File Record Found.");
                                            return $this->redirect($url);
                                    }
                                    if(!empty($old_id)){
                                            $EfileDakMovement = EfileDakMovement::find()->where(['id' =>$old_id])->one();
                                            $EfileDakMovement->is_active = "N";
                                            $EfileDakMovement->save();
                                    }
                            }
                            /*
                             * End 
                             */
                            
                            //Email Configuration
                            Yii::$app->Dakutility->sendEmailwithAttachmenttouser($file_id, $fwd_to, $fwd_emp_list, Yii::$app->user->identity->e_id);
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

			}elseif($upload_Tye == 'Image'){
				$doc_path = Yii::$app->fts_utility->uploadImageTopdf($docFile, $FTS_Documents);
				if(empty($doc_path)){
					Yii::$app->getSession()->setFlash('danger', "File document didn\'t uploaded. Contact Admin.");
					return $this->redirect($url);
				}
				$uploadDocs['doc_path'] = $doc_path;
				$uploadDocs['doc_ext_type'] = "PDF";
			}
			
			
			
			$fileModel = EfileDak::find()->where(['file_id'=>$file_id, 'is_active'=>'Y'])->one();
			$fileModel->status = "Open";
			
			$m = EfileDakMovement::find()->where([ 'id'=>$movement['id'], 'file_id'=>$file_id])->one();
			$m->is_active = "N";
			
			
			$modelMove = new EfileDakMovement();
			$modelMove->file_id = $file_id;
			$modelMove->fwd_to = "E";
			$modelMove->dak_group_id = NULL;
			$modelMove->fwd_emp_code = $movement['fwd_by'];
			$modelMove->is_time_bound = $movement['is_time_bound'];
			$modelMove->fwd_file_type = $movement['fwd_file_type'];
			$modelMove->response_date = $movement['response_date'];
			$modelMove->is_reply_required = $movement['is_reply_required'];
			$modelMove->reply_status = "N";
			$modelMove->fwd_by = Yii::$app->user->identity->e_id;
			$modelMove->fwd_date = date('Y-m-d H:i:s');
			$modelMove->is_active = "Y";
			
			
			
			$docs = new EfileDakDocs();
			$docs->file_id = $file_id;
			$docs->attach_with = "File";
			$docs->doc_ext_type = $uploadDocs['doc_ext_type'];
			$docs->docs_path = $uploadDocs['doc_path'];
			$docs->added_by = Yii::$app->user->identity->e_id;
			$docs->created_date = date('Y-m-d H:i:s');
			$docs->is_active = "Y";
			
			
			$historyModel = new EfileDakHistory();
			$historyModel->file_id = $file_id;
			$historyModel->fwd_to = "E";
			$historyModel->dak_group_id = NULL;
			$historyModel->fwd_emp_code = $movement['fwd_by'];
			$historyModel->fwd_by = Yii::$app->user->identity->e_id;
			$historyModel->created_date = date('Y-m-d H:i:s');
			$historyModel->is_active = "Y";
			
			$fileModel->save(); //Update status of file scan to open
			
			$m->save(); //old movement
			$historyModel->save(); //history
			
			$modelMove->save(); //new entry in movement table
			$docs->save(); // save documents
			
			Yii::$app->getSession()->setFlash('success', "File forwarded successfully..");
			return $this->redirect($url);
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
	
}
