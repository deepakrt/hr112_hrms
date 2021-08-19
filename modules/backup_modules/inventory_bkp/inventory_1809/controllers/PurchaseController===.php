<?php
namespace app\modules\inventory\controllers;
use yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use app\models\Inventory; 
use app\modules\inventory\models\StoreMaterialPurchaseRequest;
use app\modules\inventory\models\StoreMaterialPurchaseRequestItem;

class PurchaseController extends Controller
{
		 
	public function beforeAction($action){
		$url =Yii::$app->homeUrl;
        if (!\Yii::$app->user->isGuest) {
            if(isset($_GET['securekey']) AND !empty($_GET['securekey'])){
                $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
				
                if(empty($menuid)){ 
					//header("Location: $url");die;	
					//return $this->redirect(Yii::$app->homeUrl);
				}

                $chkValid = Yii::$app->utility->validate_url($menuid);
				// die($chkValid);
                if(empty($chkValid)){ //return $this->redirect(Yii::$app->homeUrl); 
									}
                return true;
            }else{ //return $this->redirect(Yii::$app->homeUrl);
			}
        }else{
			//header("Location: $url");die;	 
            
        }
        parent::beforeAction($action);
    }
	
   
    public function actionExl(){
			//require_once getcwd().'/other_files/PHPEXCEL/Classes/PHPExcel.php';
			require_once (getcwd().'/other_files/PHPEXCEL/Classes/PHPExcel/IOFactory.php');
             //$objPHPExcel = new \PHPExcel();  
			  //Use whatever path to an Excel file you need.
 				$inputFileName=getcwd()."/data.xlsx";
			  try {
				$inputFileType = \PHPExcel_IOFactory::identify($inputFileName);
				$objReader = \PHPExcel_IOFactory::createReader($inputFileType);
				$objPHPExcel = $objReader->load($inputFileName);
			  } catch (Exception $e) {
				die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . 
					$e->getMessage());
			  }

			  $sheet = $objPHPExcel->getSheet(0);
			  $highestRow = $sheet->getHighestRow();
			  $highestColumn = 'O';$sheet->getHighestColumn();
			  $row=1;
			  $keys = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, false);
			  echo '<pre>';  print_r($keys);
			  for ($row = 2; $row <= $highestRow; $row++) { 
				$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, false);
				    
				  
				$fdata[]=$rowData[0];
				//Prints out data in each row.
				//Replace this with whatever you want to do with the data.
			  }
		
				 
			$connection=   Yii::$app->db;
        	$connection->open();
		foreach($fdata as $kk=>$data){
			
			//echo '<pre>==';
			$save['Voucher_No']	=trim($data['2']);
			if(!empty($data['3'])){
				$dddd=explode('-',$data['3']);
				if(strlen($dddd[2])==2){$dddd[2]='20'.$dddd[2];}
				$data['3']=$dddd['0'].'-'.$dddd[1].'-'.$dddd[2];
				$save['rdate']		=date('Y-m-d',strtotime(trim($data['3'])));
			}
			$save['empid']		=trim($data['11']);
			$save['item_code']	=trim($data['14']);
			$save['item_name']	=trim($data['5']);
			$save['description']=trim($data['6']).' '.trim($data['10']);  //+Remarks
			$save['qty']		=trim($data['7']);
			$save['detail']		=trim($data['8']).' '.trim($data['9']);
 			$save['class_code'] =trim($data['12']);
			$save['cat_code']	=trim($data['13']);
		 	//print_r($save); 		  
			 extract($save);
		 
			/*$sql="INSERT INTO `store_capital_material_issue_request` (`Voucher_No`, `Issue_Request_Date`, `Division`, `Cost_Centre_Code`, `Emp_code`, `Classification_Code`, `Item_Cat_Code`, `Item_Code`, `Item_name`, `Item_Type`, `Measuring_Unit`, `Quantity_Required`, `Item_Purpose`, `Remarks`, `Role`, `Flag`, `FLA`, `HOD_ID`, `Qty_Approved_FLA`, `Qty_Approved_HOD`, `Qty_Approved_STORE`, `Approval_Date`, `deleted`) VALUES ($Voucher_No, '$rdate', NULL, NULL, $empid, $class_code, $cat_code, $item_code, :item_name, 'Non-Consumable', NULL, $qty, :detail, :description, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2);";
			$command=$connection->createCommand($sql); 
 			$command->bindValue(':item_name', $item_name);
 			$command->bindValue(':detail', $detail);
			$command->bindValue(':description', $description);
		 	$command->execute();*/
		}
			$connection->close();
		
 
				echo '</pre>';die;
	}
	
	
    public function actionIndex()
    {
		 
		$this->view->title = 'Inventory Management: Purchase Request';
     	$this->layout = '@app/views/layouts/admin_layout.php';
			// echo "<pre>"; print_r($_POST);die;
		 
 		$model = new StoreMaterialPurchaseRequest();
		//echo "<pre>";print_r(Yii::$app->user->identity);die;
		$menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
		if(isset($_POST) && !empty($_POST)){
			$post=$_POST['StoreMaterialPurchaseRequest'];
			// echo "<pre>";print_r($post);  
			unset($_POST['StoreMaterialPurchaseRequest']);
			//var_dump();die;
			//$data['voucher_no']			=Yii::$app->user->identity->e_id.substr(time(), -5);
			$old_voucher_no=StoreMaterialPurchaseRequest::find('qvoucher_no')->orderBy('voucher_no DESC')->limit(1)->one();
		  //echo "<pre>";print_r($post);  die;
			$voucher_no=10001;
			if($old_voucher_no!=NULL){
				//var_dump($old_voucher_no['voucher_no']);die;
				$voucher_no=$old_voucher_no['voucher_no']+1;
			}
			$data['voucher_no']			=  $voucher_no;
			$req_type='U25';
			if($post['item_name']>25000){
				$req_type='G25';
			}
			$data['req_type']			=$req_type;
			$data['emp_code']			=Yii::$app->user->identity->e_id;
			$data['division']			=Yii::$app->user->identity->dept_id;
			$data['request_date']		=date('Y-m-d H:i:s');
			if($post['underproject']==1){
				$data['project_id']		=$post['project'];
				$data['project']		=$post['project_names'];
			}else{
				$data['project']		=NULL;
			}
			$data['remarks']			=$post['remarks'];
			 
			$data['flag']				=1;
			$data['role']				=Yii::$app->user->identity->role;
			$data['FLA']				=Yii::$app->user->identity->authority1;
			//  echo "<pre>";print_r($data);die;
			if ($model->load($data,'') && $model->validate()) {
				if($model->save()){
					$req_id = Yii::$app->db->getLastInsertID();
					if($req_id){
						Yii::$app->inventory->update_req_id_with_item($req_id);
					}else{
						die('Request Fail');
					}
 					Yii::$app->getSession()->setFlash('success', 'Request submit successfully!');
					$r_url='purchase/rstatus?securekey='.$menuid;
					return $this->redirect($r_url);
				} 
			}else{
			 $errors = $model->errors; echo "<pre>=="; print_r($errors);die;
			}
			
		}
		$eid = Yii::$app->user->identity->e_id;
		$groups=Yii::$app->inventory->get_groups();
		$category=Yii::$app->inventory->get_category();
		$cost_centre=array();//Yii::$app->inventory->get_cost_centre();
		$unit_master=Yii::$app->inventory->get_unit_master();
		$itemdata=Yii::$app->inventory->get_pur_temp_item();
		 //echo "<pre>";print_r($itemdata);die;
        return $this->render('index', ['model'=>$model,'groups'=>$groups,'category'=>$category,'cost_centre'=>$cost_centre,'unit_master'=>$unit_master,'menuid'=>$menuid,'itemdata'=>$itemdata]);
    }
	
	
	
	public function actionShowdoc() {
			print_r($_GET);die;
			 
	}
	
	public function actionAdd_item() {
		//   echo "<pre>";print_r($_POST); print_r($_FILES);die;
		$model = new StoreMaterialPurchaseRequestItem();
		 
		$post['emp_id']=Yii::$app->user->identity->e_id;
		if(isset($_FILES) && !empty($_FILES['item_doc']['name'])){
			$filepath=  time().'_'.Yii::$app->user->identity->e_id.'.pdf';
			if(move_uploaded_file($_FILES['item_doc']['tmp_name'],Yii::$app->basePath .'/'.Inventory_Docs .$filepath)){
 			}
		$post['item_doc']=$filepath;
		}
		$_POST=$_POST['StoreMaterialPurchaseRequest'];
		$post['req_id']=0;
		$post['id_item']=$_POST['item_name'];
		$post['item_name']=$_POST['item_names'];
		$post['item_specification']=$_POST['item_specification'];
		$post['quantity_required']=$_POST['quantity_required'];
		$post['approx_cost']=$_POST['approx_cost'];
		$post['purpose']=$_POST['item_purpose'];
		$post['created_date']=date('Y-m-d');
		 // echo "<pre>";print_r($post); 
		if ($model->load($post,'') && $model->save()) {
			//$data=Yii::$app->inventory->get_p_request_items();
			echo 1;die;
		}else{
		 	//echo "<pre>";print_r($model->Errors);  
			$error='';
			foreach($model->Errors as $k=>$err){
				$error.=$err[0].'<br>';
			}
			echo $error;die;
		}
	}
	
	public function actionRstatus() 
    {
 		//echo "<pre>";print_r(Yii::$app->session);die;
		$this->view->title = 'Inventory Management: Purchase Request Status';
     	$this->layout = '@app/views/layouts/admin_layout.php';
		$eid = Yii::$app->user->identity->e_id;
		$data=Yii::$app->inventory->get_purchase_request_status($eid);
        return $this->render('rstatus',['data'=>$data]);
    }
	
	public function actionViewitem() {
		$id=$_REQUEST['id'];
		$menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
		$data=Yii::$app->inventory->view_purchase_item($id);
		echo $this->renderPartial('view_item',['data'=>$data,'menuid'=>$menuid]);die;
	}
	public function actionForward_prequest() {
		if(isset($_POST['v_nos']) && !empty($_POST['v_nos']) && isset($_POST['purchase_empid']) && !empty($_POST['purchase_empid'])){
			$itemids=$_POST['v_nos'];
			$forward_to=$_POST['purchase_empid'];
			return Yii::$app->inventory->purchase_items_forward($itemids,$forward_to);
		}else{
			return 0;
		}
	}
	
	public function actionPrequest() 
    {
 		$this->view->title = 'Inventory Management: Purchase Pending Request';
     	$this->layout = '@app/views/layouts/admin_layout.php';
		$menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
		$e_id = Yii::$app->user->identity->e_id;
		$role = Yii::$app->user->identity->role;
		if($role=='17'){  // 17 SPO 
			$purchase_emp=Yii::$app->inventory->get_emp_by_role(21);
			$data=Yii::$app->inventory->pending_purchase_items(NULL);
			return $this->render('prequest17',['data'=>$data,'purchase_emp'=>$purchase_emp,'menuid'=>$menuid]);
		}elseif($role=='21'){  // 21 Purchase-SPO 
			$data=Yii::$app->inventory->pending_purchase_items($e_id);
			return $this->render('prequest21',['data'=>$data,'menuid'=>$menuid]);
		}else{
			$data=Yii::$app->inventory->pending_purchase_requests($role,$e_id);
			if($role=='6'){  // 6 FM
				return $this->render('fm_prequest',['data'=>$data,'menuid'=>$menuid]);
			}elseif($role=='7' || $role=='16'){  // 7 Director  16 MMG
				return $this->render('prequest7',['data'=>$data,'menuid'=>$menuid]);
			}elseif($role=='8'){  // 8 Store Inc
				return $this->render('prequest8',['data'=>$data,'menuid'=>$menuid]);
			}else{
			$allhod=Yii::$app->inventory->get_emp_by_role(2);//echo "<pre>";print_r($data);die;
			return $this->render('prequest',['data'=>$data,'allhod'=>$allhod,'menuid'=>$menuid]);
			}
		}
        
    }
	
	public function actionUpdate_pur_req_heads() {
		//echo "<pre>";print_r($_POST);die;
		$role = Yii::$app->user->identity->role;
  		$req_id	=$_POST['req_id'];
		$project_head=NULL;
		$project_funds=NULL;
		if(!isset($_POST['rejected']) && isset($_POST['project_head']) && isset($_POST['project_funds'])){
			$project_head=$_POST['project_head'];
			$project_funds=$_POST['project_funds'];
			$flag=11;
 		}else{
			$flag=12;
 		}
		if($role==7){  //Director
			if(isset($_POST['rejected'])){
				$flag=8;
			}else{
				$flag=9;
			}
		}
		if($role==16){ //Head MMG
			$flag=14;
		}
		 
		Yii::$app->inventory->update_pur_req_FM_CH($flag,$req_id,$project_head,$project_funds);
		echo 1;
		 
	}
	
	public function actionUpdate_item_pur_req() {
			parse_str($_POST['data'], $data);
			 //echo "<pre>";print_r($data); die;
			extract($data);
			if($purc_status=='Order-Declined'){
				$ipurchase_mod=null;$remarks=null;
			}
			 
			if(!isset($remarks)){
				$remarks=null;
			}
			Yii::$app->inventory->update_item_pur_req($item_id,$req_id,$purc_status,$ipurchase_mod,$remarks);
	}
	
	public function actionUpdate_pur_req() {
		if(!isset($_POST['rejected'])){
		parse_str($_POST['data'], $data);
	  //echo "<pre>";print_r($data); die;
			$_POST=$data;
		$j=count($data['item_id']);
		$mainstatus='';
		$reqstatus=4;
		for($i=0;$i<$j;$i++){
				$item_id	=$data['item_id'][$i];
				$QtyR		=$data['QtyR'][$i];
				$avail_qty	=$data['avail_qty'][$i];
				$req_id		=$data['req_id'][$i];
				if($avail_qty>0){
					$itemstatus='Y';
				if($avail_qty>=$QtyR){
					 if($mainstatus!='N'){
						$mainstatus='Y';
					 }
				}else{
 					$mainstatus='N';
				}
				}else{
					$itemstatus='N';
					$mainstatus='N';
				}
				//  echo $mainstatus."<pre>".$itemstatus.'--'.$avail_qty.'--'.$item_id;print_r($data); 
			Yii::$app->inventory->update_pur_req_temp($item_id,$avail_qty,$itemstatus);
			//Yii::$app->inventory->update_pur_req_temp($status,$avail_qty,$item_id,$mainstatus,$req_id);
				//	die;
 			}
			//die;
		}else{
			$mainstatus='N';
			$reqstatus=7;
			$req_id	=$_POST['req_id'];
		}
		if($mainstatus=='Y'){
			 $reqstatus=13;
		}
		Yii::$app->inventory->update_pur_req($mainstatus,$req_id,$reqstatus);
		echo 1;
		//echo Yii::$app->inventory->update_pur_req();
	}
	public function actionViewreq() 
    {
 		$this->view->title = 'Inventory Management: View Purchase Pending Request';
     	$this->layout = '@app/views/layouts/admin_layout.php';
		$menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
		if(isset($_POST) && !empty($_POST)){
			//$res=Yii::$app->inventory->view_purchase_requests($id);
		}
		$id=$_REQUEST['id'];
 		$data=Yii::$app->inventory->view_purchase_requests($id);
		$role = Yii::$app->user->identity->role;
 		$allhod=Yii::$app->inventory->get_emp_by_role(2); 
		if($role=='8'){ // 8 Store INC
			$maindata=Yii::$app->inventory->purchase_request_view($id);
			
			if($maindata['flag']>7){
				echo $this->renderPartial('view_prequest_6n7',['maindata'=>$maindata,'data'=>$data,'menuid'=>$menuid]);die;
			}else{
				echo $this->renderPartial('view_prequest8',['data'=>$data,'menuid'=>$menuid]);die;
			}
		}elseif($role=='6' || $role=='7' || $role=='16' || $role=='17'){ // 6 FM   7 Director  16 MMG 17 sr. purchase
			$e_id = Yii::$app->user->identity->e_id;
			$maindata=Yii::$app->inventory->pending_purchase_requests($role,$e_id,$id);
			// echo "<pre>";print_r($maindata);die;
			echo $this->renderPartial('view_prequest_6n7',['maindata'=>$maindata,'data'=>$data,'menuid'=>$menuid]);die;
		}elseif($role=='2' || $role=='4' || $role=='3'){ // 2 HOD   4 FLA  3 EMP
			$e_id = Yii::$app->user->identity->e_id;
			$maindata=Yii::$app->inventory->purchase_request_view($id);
			echo $this->renderPartial('view_prequest_6n7',['maindata'=>$maindata,'data'=>$data,'menuid'=>$menuid]);die;
		}else{
			return $this->render('view_prequest',['data'=>$data,'menuid'=>$menuid]);
		}
    }
	
	public function actionRemoveitem() {
			$id=$_POST['item_id'];
			Yii::$app->inventory->remove_purchase_item($id);
			echo 1;die;
	}
	
	public function actionApr_rej_prequest() {
		$PARAMID=$_POST['v_nos'];
		$PARAMHOD_ID=$_POST['auth_id'];
		$PARAMrole=Yii::$app->user->identity->role;
		$PARAMApproveReject=$_POST['status'];
		if($PARAMHOD_ID==Yii::$app->user->identity->e_id && $PARAMApproveReject==1){
 			$PARAMrole=2;
		}
		echo Yii::$app->inventory->apr_rej_prequest($PARAMID,$PARAMHOD_ID,$PARAMrole,$PARAMApproveReject);
	}
	
	public function actionArequest() 
    {
 		$this->view->title = 'Inventory Management: Purchase Approved/Rejected Requests';
      	$this->layout = '@app/views/layouts/admin_layout.php';
		$role = Yii::$app->user->identity->role;
		$eid = Yii::$app->user->identity->e_id;
		$data=Yii::$app->inventory->get_p_request_data($role,$eid);
		//  echo "<pre>";print_r($data);die;
		return $this->render('rstatus',['data'=>$data]);
    }
	public function actionDownload() 
    {
			error_reporting(0);
			$path = getcwd(). "/mpdff/mpdf.php"; 
			require_once $path;
			$mpdf = new \mPDF('utf-8', 'A4');
			$ll = '{PAGENO}';//str_replace('{PAGECNT}', $mpdf->getPageCount(), $html);
            $f1="<div class='row'><div align='right'>".$ll."</div></div>";
            $mpdf->setAutoBottomMargin = 'stretch';  
            $mpdf->SetHeader("<div class='row'><div align='right'></div></div>");    
            $mpdf->SetFooter($f1);    
			$e_id = Yii::$app->user->identity->e_id;
			$role = Yii::$app->user->identity->role;
			$id= base64_decode($_REQUEST['id']);
			$data=Yii::$app->inventory->view_purchase_requests($id);
			$maindata=Yii::$app->inventory->pending_purchase_requests($role,$e_id,$id);
			$this->view->title = 'Inventory Management: Indent-';
			$content=$this->renderPartial('indentpdf',['maindata'=>$maindata,'data'=>$data]);
   			$mpdf->WriteHTML($content, 2);
 			$name='Indent-'.time();
			$mpdf->Output("$name.pdf", 'I');die;
    }
	 
	 
}
