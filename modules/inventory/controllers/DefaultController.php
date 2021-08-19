<?php

namespace app\modules\inventory\controllers;
use yii;
use yii\web\Controller;
use app\models\Inventory; 
use app\modules\inventory\models\StoreMatReceiptTemp;

class DefaultController extends Controller
{
	public function beforeAction($action){
		$url =Yii::$app->homeUrl;
        if (!\Yii::$app->user->isGuest) {
            if(isset($_GET['securekey']) AND !empty($_GET['securekey'])){
                $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
				
                if(empty($menuid)){ 
 					header("Location: $url",  true,  301 );die;	
					//return $this->redirect(Yii::$app->homeUrl);
				}
                 $chkValid = Yii::$app->utility->validate_url($menuid);
                 if(empty($chkValid)){ return $this->redirect(Yii::$app->homeUrl); }
                return true;
            }else{ return $this->redirect(Yii::$app->homeUrl); }
        }else{
             header("Location: $url");die;
        }
        parent::beforeAction($action);
    }
	
    public function actionIndex()
    {
		$this->view->title = 'Inventory Management';
     	$this->layout = '@app/views/layouts/admin_layout.php';
		$eid = Yii::$app->user->identity->e_id;
        return $this->render('index');
    }
	
	 
	public function actionIrequest()
    {
		 
		//echo "<pre>";print_r(Yii::$app->user->identity);die;
		$menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
		if(isset($_POST) && !empty($_POST))
		{
			// echo "<pre>";print_r($_POST);die;

			$post=$_POST['Inventory'];
			unset($_POST['Inventory']);
			$data['Voucher_No']			=Yii::$app->user->identity->e_id.substr(time(), -5);
			$data['Emp_code']			=Yii::$app->user->identity->e_id;
			$data['Division']			=$post['dept_id'];
			$data['Classification_Code']=$post['group'];
			$data['Item_Cat_Code']		=$post['category'];
			$data['Item_Code']			=$post['item'];
			$data['Item_Type']			=$post['item_type'];
			$data['Item_Type_Id']			=$_POST['data_type_id'];
			$data['Measuring_Unit']		=$post['units'];
			$data['Quantity_Required']	=$post['qty_required'];
			$data['Item_Purpose']		=$post['purpose'];
			$data['Remarks']			=$post['remarks'];
			$data['Flag']				=1;
			$data['Role']				=Yii::$app->user->identity->role;
			$data['FLA']				=Yii::$app->user->identity->authority1;
			//echo "<pre>";print_r($data);die;
			$res=Yii::$app->inventory->add_issue_request($data);
			Yii::$app->getSession()->setFlash('success', 'Request submit successfully!');
			$r_url='rstatus?securekey='.$menuid;
			return $this->redirect($r_url);
			
		}
		$this->view->title = 'Inventory Management: Issue Request';
     	$this->layout = '@app/views/layouts/admin_layout.php';
		$eid = Yii::$app->user->identity->e_id;
		$groups=Yii::$app->inventory->get_groups();
		$category=Yii::$app->inventory->get_category();
		$cost_centre=array();//Yii::$app->inventory->get_cost_centre();
		$unit_master=Yii::$app->inventory->get_unit_master();
 		$model = new Inventory();
        return $this->render('Irequest', ['model'=>$model,'groups'=>$groups,'category'=>$category,'cost_centre'=>$cost_centre,'unit_master'=>$unit_master,'menuid'=>$menuid]);
    }
    public function actionIreturnrequest()
    {
		 
		//echo "<pre>";print_r(Yii::$app->user->identity);die;
		$menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
		if(isset($_POST) && !empty($_POST))
		{
			// echo "<pre>";print_r($_POST);die;

			$post=$_POST['Inventory'];
			unset($_POST['Inventory']);
			$data['Voucher_No']			=Yii::$app->user->identity->e_id.substr(time(), -5);
			$data['Emp_code']			=Yii::$app->user->identity->e_id;
			$data['Division']			=$post['dept_id'];
			$data['Classification_Code']=$post['group'];
			$data['Item_Cat_Code']		=$post['category'];
			$data['Item_Code']			=$post['item'];
			$data['Item_Type']			=$post['item_type'];
			$data['Item_Type_Id']			=$_POST['data_type_id'];
			$data['Measuring_Unit']		=$post['units'];
			$data['Quantity_Required']	=$post['qty_required'];
			$data['Item_Purpose']		=$post['purpose'];
			$data['Remarks']			=$post['remarks'];
			$data['Flag']				=1;
			$data['Role']				=Yii::$app->user->identity->role;
			$data['FLA']				=Yii::$app->user->identity->authority1;
			//echo "<pre>";print_r($data);die;
			$res=Yii::$app->inventory->add_return_request($data);
			Yii::$app->getSession()->setFlash('success', 'Request submit successfully!');
			$r_url='retstatus?securekey='.$menuid;
			return $this->redirect($r_url);
			
		}
		$this->view->title = 'Inventory Management: Initiate Return Request';
     	$this->layout = '@app/views/layouts/admin_layout.php';
		$eid = Yii::$app->user->identity->e_id;
		$groups=Yii::$app->inventory->get_groups();
		$category=Yii::$app->inventory->get_category();
		$cost_centre=array();//Yii::$app->inventory->get_cost_centre();
		$unit_master=Yii::$app->inventory->get_unit_master();
 		$model = new Inventory();
        return $this->render('Ireturnrequest', ['model'=>$model,'groups'=>$groups,'category'=>$category,'cost_centre'=>$cost_centre,'unit_master'=>$unit_master,'menuid'=>$menuid]);
    }
	public function actionGet_cat_code()
    {
		// echo "<pre>";print_r($_POST);die;
		if(!isset($_POST['cat_id']) || empty($_POST['cat_id'])){
			die('0');
		}
		$cat_id=$_POST['cat_id'];
		$ccode=2;
		if(isset($_POST['ccode']) && !empty($_POST['ccode'])){
			$ccode=$_POST['ccode'];
		}
		//die($ccode);
 		$res=Yii::$app->inventory->get_cat_item($cat_id,$ccode);

 		// echo "<pre>"; print_r($res); die();


        $html='<option value="">-- Select --</option>';
		foreach($res as $r){
			$html.='<option alt="'.$r['Item_type'].'" data_type_id="'.$r['Type_id'].'" data-quantity="'.$r['Quantity'].'" label1="'.$r['Measuring_Unit'].'" value="'.$r['ITEM_CODE'].'">'.$r['item_name'].'</option>';
		}
		if(!isset($_POST['page'])){
			echo $html.'<option value="000">Other</option>';
		}else{
			echo $html;
		}
		die;
	}
 	
	
	public function actionGet_dept_cc()
     {
		$dept_id=$_POST['dept_id'];
		if(isset($_POST['cc_id'])){
			$cc_id=$_POST['cc_id'];
		}
		$res=Yii::$app->inventory->get_cost_centre($dept_id);
        $html='<option value="">-- Select --</option>';
		foreach($res as $r){
			$sel="";
			if(isset($_POST['cc_id'])){
				if($cc_id==$r['SUB_DEPT_CODE']){$sel="selected='selected'";}
			}
			$html.='<option '.$sel.' value="'.$r['SUB_DEPT_CODE'].'">'.$r['SUB_DEPT_NAME'].'</option>';
		}
		echo $html;
	}
	
	public function actionGet_dept_emp()
 	{
		$dept_id=$_POST['dept_id'];
		if(isset($_POST['emp_code'])){
			$emp_code=$_POST['emp_code'];
		}
		$res=Yii::$app->inventory->get_dept_emp($dept_id);

		// ECHO "<PRE>"; PRINT_R($res); DIE();
    $html='<option value="">-- Select --</option>';
		foreach($res as $r){
			$sel="";
			if(isset($_POST['emp_code'])){
				if($emp_code==$r['employee_code']){$sel="selected='selected'";}
			}
			$html.='<option '.$sel.' value="'.$r['employee_code'].'">'.ucfirst(strtolower($r['name'])).' ('.$r['employee_code'].')</option>';
		}
		echo $html;die;
	}
	
	public function actionRstatus() 
  {
 		//Yii::$app->session->set('store_role','3');
		//echo "<pre>";print_r(Yii::$app->session);
		//echo Yii::$app->session['store_role'];die;
		$this->view->title = 'Inventory Management: Request Status';
   	$this->layout = '@app/views/layouts/admin_layout.php';
		$eid = Yii::$app->user->identity->e_id;
		$roleID = Yii::$app->user->identity->role;
		if($roleID == 3)
		{
			$data=Yii::$app->inventory->get_issue_request_status($eid);
		}
		else
		{
			$data=Yii::$app->inventory->get_request_data($roleID,$eid);
		}


    return $this->render('rstatus',['data'=>$data]);
  }
  
  public function actionRetstatus() 
  {
 		//Yii::$app->session->set('store_role','3');
		//echo "<pre>";print_r(Yii::$app->session);
		//echo Yii::$app->session['store_role'];die;
		$this->view->title = 'Inventory Management: Item Return Status';
   	$this->layout = '@app/views/layouts/admin_layout.php';
		$eid = Yii::$app->user->identity->e_id;
		$roleID = Yii::$app->user->identity->role;
		if($roleID == 3)
		{
			$data=Yii::$app->inventory->get_return_request_status($eid);
		}
		else
		{
			$data=Yii::$app->inventory->get_returnitem_data($roleID,$eid);
		}


    return $this->render('retstatus',['data'=>$data]);
  }
	
	public function actionUpdateqty() {
		$role = Yii::$app->user->identity->role;
		echo Yii::$app->inventory->updateqty($role,$_POST['voucherno'],$_POST['qty']);
	}
	public function actionPrequest() 
  {
		// echo "<pre>";print_r(Yii::$app->user->identity);die;
 		$this->view->title = 'Inventory Management: Pending Request';
     	$this->layout = '@app/views/layouts/admin_layout.php';
		$menuid = Yii::$app->utility->decryptString($_GET['securekey']);
    $menuid = Yii::$app->utility->encryptString($menuid);
		
		$e_id = Yii::$app->user->identity->e_id;
		$role = Yii::$app->user->identity->role;
		// echo "<pre>";print_r($role.'----'.$e_id); die;

		$data = Yii::$app->inventory->get_pending_issue_requests($role,$e_id);
		
		// echo "<pre>";print_r($data); die;

		$allhod=Yii::$app->inventory->get_emp_by_role(2);
        return $this->render('prequest',['data'=>$data,'allhod'=>$allhod,'menuid'=>$menuid]);
    }
	
	public function actionReqstatus() 
    {
 		$this->view->title = 'Inventory Management: Requests Status';
     	$this->layout = '@app/views/layouts/admin_layout.php';
		$eid = Yii::$app->user->identity->e_id;
		$data=Yii::$app->inventory->get_issue_request_status(NULL);
        return $this->render('allrequeststatus',['data'=>$data]);
    }
	public function actionCapreqstatus() 
    {
 		$this->view->title = 'Inventory Management: Capital Stores Requests Status';
     	$this->layout = '@app/views/layouts/admin_layout.php';
		$eid = Yii::$app->user->identity->e_id;
		$data=Yii::$app->inventory->get_capital_issue_request_status();
        return $this->render('allrequeststatus',['data'=>$data]);
    }
	
	public function actionApr_rej_irequest()
	{
		// echo "<pre>"; print_r($_POST); die();

		$PARAMID=$_POST['v_nos'];
		$PARAMHOD_ID=$_POST['auth_id'];

		$PARAM_forward=$_POST['forward'];
		$PARAMrole=Yii::$app->user->identity->role;
		$PARAMApproveReject=$_POST['status'];
		/*if($PARAMHOD_ID==Yii::$app->user->identity->e_id && $PARAMApproveReject==1){
			$PARAMrole=2;
		}*/
		echo Yii::$app->inventory->apr_rej_irequest($PARAMID,$PARAMHOD_ID,$PARAMrole,$PARAMApproveReject,$PARAM_forward);
	}
	
	public function actionIssue_str_item() {
		$PARAMID=$_POST['v_nos'];
		
		$PARAMrole=Yii::$app->user->identity->role;

		// echo $PARAMID.'----------'.$PARAMrole; die();
		echo Yii::$app->inventory->issue_str_item($PARAMID,$PARAMrole);
	}
	
	public function actionArequest() 
    {
		// echo "<pre>";print_r(Yii::$app->user->identity);die;
 		$this->view->title = 'Inventory Management: Approved/Rejected Requests';
      		$this->layout = '@app/views/layouts/admin_layout.php';
		$role = Yii::$app->user->identity->role;
		$eid = Yii::$app->user->identity->e_id;
		$data=Yii::$app->inventory->get_request_data($role,$eid);
		return $this->render('rstatus',['data'=>$data]);
    }
	
	public function actionJrequest()
    {
		$this->view->title = 'Inventory Management';
     	$this->layout = '@app/views/layouts/admin_layout.php';
		$eid = Yii::$app->user->identity->e_id;
        return $this->render('index');
    }
	
	public function actionJstatus()
    {
		$this->view->title = 'Inventory Management';
     	$this->layout = '@app/views/layouts/admin_layout.php';
		$eid = Yii::$app->user->identity->e_id;
        return $this->render('index');
    }
	
	public function actionDelete_mat_rec(){
		$res=Yii::$app->inventory->delete_mat_receipt_tmp($_POST['id']);
		echo $res;die;
	}
	
	public function actionAalloted()
    {
		$this->view->title = 'Inventory Management';
     	$this->layout = '@app/views/layouts/admin_layout.php';
		$eid = Yii::$app->user->identity->e_id;
        return $this->render('index');
    }
	
	public function actionReceipt()
	{
		if(!isset($_GET['test'])){
			//die('Under development...');
		}
		
		$this->view->title = 'Inventory Management: Receipt';
   	$this->layout = '@app/views/layouts/admin_layout.php';
		$menuid = Yii::$app->utility->decryptString($_GET['securekey']);
    $menuid = Yii::$app->utility->encryptString($menuid);

    $url = Yii::$app->homeUrl."inventory/default/receipt?securekey=$menuid";
		
		if(isset($_POST) && !empty($_POST)){
	 		// echo Yii::$app->user->identity->e_id. "<pre>=======".FTS_Documents;print_r($_FILES);print_r($_POST);die;
			if(isset($_POST['Finalize']))
			{
				$doc_path = '';
				if(isset($_FILES['invoice_file']))
				{

			 		$upload_Tye = "Image";
	        $image_type = $_FILES['invoice_file']['type'];
	        $image_tmp_name = $_FILES['invoice_file']['tmp_name'];
	        $image_size = $_FILES['invoice_file']['size'];
	        $image_name = $_FILES['invoice_file']['name'];

	        $error = "";
					$chk1 = $chk = "";

	        $chk = Yii::$app->fts_utility->validateImage($image_type, $image_tmp_name);
					if(empty($chk)){
							$error = "Upload Valid Images of File";
					}
					$chk1 = Yii::$app->fts_utility->validateImageSize($image_size);
					if(empty($chk1)){
							$error .= "Each image size should be less then ".FTS_Image_Size."MB";;
					}
					if(!empty($error)){
							Yii::$app->getSession()->setFlash('danger', $error); 
							return $this->redirect($url);
					}
					$data = file_get_contents($image_tmp_name);
					$docFile = array();
					$prv = 0;
					$base64 = 'data:image/' . $image_type . ';base64,' . base64_encode($data);
					$docFile['doc_ext_type']= 'Image';
					$docFile['tmp_name']= $image_tmp_name;
					$docFile['file_name']= $image_name;
					$docFile['base64']= $base64;

					$FTS_Documents = '/other_files/invoice_documents'; // FTS_Documents;

					// $doc_path = Yii::$app->fts_utility->uploadImageTopdf($docFile, $FTS_Documents);
					$doc_path = Yii::$app->fts_utility->uploadFileMunish($docFile['tmp_name'], $docFile['file_name'], $FTS_Documents);
					if(empty($doc_path)){
						Yii::$app->getSession()->setFlash('danger', "File document didn\'t uploaded. Contact Admin.");
						return $this->redirect($url);
					}
					$doc_path = $doc_path;
				}
				
				$res=Yii::$app->inventory->submit_mat_receipts($doc_path);
			
				Yii::$app->getSession()->setFlash('info', 'Receipt Finalized successfully!');
				$r_url='receipt?securekey='.$menuid;
				return $this->redirect($r_url);
			}else{
				$res=Yii::$app->inventory->insert_material_receipt($_POST['StoreMatReceiptTemp']);
				if($res){
					Yii::$app->getSession()->setFlash('info', 'Receipt added successfully!');
					$r_url='receipt?securekey='.$menuid;
					//return $this->redirect($r_url);
				}
			} 
		}
		$model = new StoreMatReceiptTemp();
		if(isset($_POST) && !empty($_POST)){
			$model->load($_POST);
		}
		$eid = Yii::$app->user->identity->e_id;
		$groups=Yii::$app->inventory->get_groups();
		$depts=Yii::$app->inventory->get_alldept();
		$category=Yii::$app->inventory->get_category();
		//$cost_centre=Yii::$app->inventory->get_cost_centre();
		$unit_master=Yii::$app->inventory->get_unit_master();
		$suppliers=Yii::$app->inventory->get_all_supplier();
		$tmp_mat_receipt=Yii::$app->inventory->get_mat_receipt_tmp();
		$new_mrn=Yii::$app->inventory->get_new_mrn_no();
        

        return $this->render('receipt',['model'=>$model,'depts'=>$depts,'groups'=>$groups,'category'=>$category,
										'suppliers'=>$suppliers,'unit_master'=>$unit_master,
										'tmp_mat_receipt'=>$tmp_mat_receipt,'menuid'=>$menuid,'new_mrn'=>$new_mrn]);
  }

	
	public function actionInspection()
  {
		
		$this->view->title = 'Inventory Management: Inspection';
     	$this->layout = '@app/views/layouts/admin_layout.php';
		$menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
		$result=array();
		$mrn_data = array();
		if(isset($_GET['MRN_No']) && !empty($_GET['MRN_No'])){
			$result=Yii::$app->inventory->get_mrn_records($_GET['MRN_No']);

			// echo '--'; echo $_GET['MRN_No']; die();
			/*			$mrn_data = Yii::$app->inventory->get_records_by_mrn($_GET['MRN_No']);
			$mrn_data = array_shift($mrn_data);*/
		}
 		$rm_no=Yii::$app->inventory->get_new_rm_no(1);
        return $this->render('inspection',['rm_no'=>$rm_no,'result'=>$result,'mrn_data'=>$mrn_data,'menuid'=>$menuid]);
    }
	
	public function actionVreceipt()
    {
		
		$this->view->title = 'Inventory Management: Verify Receipt';
     	$this->layout = '@app/views/layouts/admin_layout.php';
		$menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
		$result=array();
		$mrn_data=array();
		if(isset($_GET['MRN_No']) && !empty($_GET['MRN_No'])){
			$result = Yii::$app->inventory->get_mrn_records($_GET['MRN_No'],NULL,2);
			// $result = Yii::$app->inventory->get_records_by_mrn($_GET['MRN_No']);
			// get_records_by_mrn

			$mrn_data = Yii::$app->inventory->get_records_by_mrn($_GET['MRN_No']);
			$mrn_data = array_shift($mrn_data);

		}
 		$rm_no=Yii::$app->inventory->get_new_rm_no(2);
        return $this->render('verify_receipt',['rm_no'=>$rm_no,'result'=>$result,'mrn_data'=>$mrn_data,'menuid'=>$menuid]);
    }
	
	
	
	
	public function actionEditreceipt()
    {
		
		$this->view->title = 'Inventory Management: Update/Verify Receipt';
     	$this->layout = '@app/views/layouts/admin_layout.php';
		$menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
		if(isset($_POST) && !empty($_POST)){
			 
				//$res=Yii::$app->inventory->update_material_receipt($_POST['StoreMatReceiptTemp']);
			if(isset($_POST['Reject']))
			{
			//$mrn=	 $_POST['StoreMatReceiptTemp']['MRN_No'];
		   $res=Yii::$app->inventory->definalize_material_receipt($_POST['StoreMatReceiptTemp']['MRN_No']);
			}
			else
			{
				$res=Yii::$app->inventory->update_material_receipt($_POST['StoreMatReceiptTemp']);
				
			}
				if($res){
					Yii::$app->getSession()->setFlash('info', 'Receipt Updated successfully!');
					$r_url='vreceipt?securekey='.$menuid.'&MRN_No='.$_GET['MRN_No'];
					return $this->redirect($r_url);
				}
			 
		}
		$model = new StoreMatReceiptTemp();
		$eid = Yii::$app->user->identity->e_id;
		$groups=Yii::$app->inventory->get_groups();
		$depts=Yii::$app->inventory->get_alldept();
		$category=Yii::$app->inventory->get_category();
		$cost_centre=array();//Yii::$app->inventory->get_cost_centre();
		$unit_master=Yii::$app->inventory->get_unit_master();
		$suppliers=Yii::$app->inventory->get_all_supplier();
		$receipt_detail['StoreMatReceiptTemp']=Yii::$app->inventory->get_mat_receipt_detail($_GET['MRN_No'],$_GET['rno']);
		//  echo "<pre>";print_r($receipt_detail);die;
		$receipt_detail['StoreMatReceiptTemp']['ID']=$receipt_detail['StoreMatReceiptTemp']['Accessid'];
		$receipt_detail['StoreMatReceiptTemp']['PO_Date']=date('d-m-Y',strtotime($receipt_detail['StoreMatReceiptTemp']['PO_Date']));
		$receipt_detail['StoreMatReceiptTemp']['Memo_Date']=date('d-m-Y',strtotime($receipt_detail['StoreMatReceiptTemp']['Memo_Date']));
		
		$model->load($receipt_detail);
		// echo "<pre>";print_r($model);die;
		$btn='Update/Verify';
		return $this->render('edit',['model'=>$model,'depts'=>$depts,'groups'=>$groups,'category'=>$category,
										'cost_centre'=>$cost_centre,'suppliers'=>$suppliers,'unit_master'=>$unit_master,
										'data'=>$receipt_detail,'menuid'=>$menuid,'sub_btn'=>$btn]);
	}
	
	public function actionEdit()
    {
		
		$this->view->title = 'Inventory Management: Edit Receipt';
     	$this->layout = '@app/views/layouts/admin_layout.php';
		$menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
		if(isset($_POST) && !empty($_POST)){
			$_POST['StoreMatReceiptTemp']['MRN_No']=$_GET['MRN_No'];
			 //echo "<pre>";print_r($_POST['StoreMatReceiptTemp']);die;
				$res=Yii::$app->inventory->insert_material_receipt($_POST['StoreMatReceiptTemp']);
				if($res){
					Yii::$app->getSession()->setFlash('info', 'Receipt Updated successfully!!');
					$r_url='receipt?securekey='.$menuid;
					return $this->redirect($r_url);
				}
			 
		}
		
		$model = new StoreMatReceiptTemp();
		$eid = Yii::$app->user->identity->e_id;
		$groups=Yii::$app->inventory->get_groups();
		$depts=Yii::$app->inventory->get_alldept();
		$category=Yii::$app->inventory->get_category();
		$cost_centre=array();//Yii::$app->inventory->get_cost_centre();
		$unit_master=Yii::$app->inventory->get_unit_master();
		$suppliers=Yii::$app->inventory->get_all_supplier();
		$receipt_detail['StoreMatReceiptTemp']=Yii::$app->inventory->get_mat_receipt_tmp_detail($_GET['MRN_No'],$_GET['rno']);
		// echo "<pre>";print_r($receipt_detail);
		$receipt_detail['StoreMatReceiptTemp']['PO_Date']=date('d-m-Y',strtotime($receipt_detail['StoreMatReceiptTemp']['PO_Date']));
		$receipt_detail['StoreMatReceiptTemp']['Memo_Date']=date('d-m-Y',strtotime($receipt_detail['StoreMatReceiptTemp']['Memo_Date']));
		$model->load($receipt_detail);
		//  echo "<pre>";print_r($model);die;
		$btn='Update';
		return $this->render('edit',['model'=>$model,'depts'=>$depts,'groups'=>$groups,'category'=>$category,
										'cost_centre'=>$cost_centre,'suppliers'=>$suppliers,'unit_master'=>$unit_master,
										'data'=>$receipt_detail,'menuid'=>$menuid,'sub_btn'=>$btn]);
	}
	public function actionViewinspection()
    {
		
		// echo "<pre>==";print_r($_GET);die; 
		$this->view->title = 'Inventory Management: View Inspection';
     	$this->layout = '@app/views/layouts/admin_layout.php';
		$menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
					//return $this->redirect('inspection?MRN_No='.$_GET['MRN_No']);
		$result[0]=array();
		
		if(isset($_POST['rno']) && !empty($_POST['rno'])){
			if(!isset($_POST['ysno'])){
				$_POST['ysno']='No';
				$_POST['rreason']=NULL;
				$_POST['cmember']=NULL;
			}
			
		$res=Yii::$app->inventory->update_mat_records_by_store($_POST);
		if($res){
				Yii::$app->getSession()->setFlash('info', 'Updated successfully!');
				return $this->redirect('inspection?securekey='.$menuid.'&MRN_No='.$_GET['MRN_No']);
			}
		}
		if(isset($_GET['rno']) && !empty($_GET['rno'])){
			$result=Yii::$app->inventory->get_mrn_records($_GET['MRN_No'],$_GET['rno']);
			 //echo "<pre>==";print_r($result);die; 
			if(empty($result)){
				return $this->redirect('inspection?securekey='.$menuid.'&MRN_No='.$_GET['MRN_No']);
				}
		}
 		return $this->render('viewinspection',['data'=>$result[0],'menuid'=>$menuid]);
    }
	
	public function actionVerifyinspection(){
			$this->view->title = 'Inventory Management: Verify Inspection';
			$this->layout = '@app/views/layouts/admin_layout.php';
			$menuid = Yii::$app->utility->decryptString($_GET['securekey']);
			$menuid = Yii::$app->utility->encryptString($menuid);
			$result=array();
			if(isset($_GET['MRN_No']) && !empty($_GET['MRN_No'])){
				$result=Yii::$app->inventory->get_mrn_records($_GET['MRN_No'],null,3);
			}
			$rm_no=Yii::$app->inventory->get_new_rm_no(3);
			return $this->render('verifyinspection',['rm_no'=>$rm_no,'result'=>$result,'menuid'=>$menuid]);
   
	}
	
	public function actionViewinspectioninc()
    {
		
		  //echo "<pre>==";print_r($_POST);die; 
		$this->view->title = 'Inventory Management: View Inspection';
     	$this->layout = '@app/views/layouts/admin_layout.php';
		$menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
					//return $this->redirect('inspection?MRN_No='.$_GET['MRN_No']);
		$result[0]=array();
		
		if(isset($_POST['rno']) && !empty($_POST['rno'])){
 			
			$res=Yii::$app->inventory->update_mat_records($_POST);
			if($res){
					Yii::$app->getSession()->setFlash('info', 'Updated successfully!');
					return $this->redirect('verifyinspection?securekey='.$menuid.'&MRN_No='.$_GET['MRN_No']);
				}
		}
		if(isset($_GET['rno']) && !empty($_GET['rno'])){
			$result=Yii::$app->inventory->get_mrn_records($_GET['MRN_No'],$_GET['rno'],3);
			 //echo "<pre>==";print_r($result);die; 
			if(empty($result)){
				return $this->redirect('verifyinspection?securekey='.$menuid.'&MRN_No='.$_GET['MRN_No']);
				}
		}
 		return $this->render('viewinspectioninc',['data'=>$result[0],'menuid'=>$menuid]);
    }
	
	public function actionResponse(){
		echo 1;
	}
	 
}
