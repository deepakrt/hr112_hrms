<?php
namespace app\controllers;
use Yii;
class CommonController extends \yii\web\Controller{
    
    public function actionGet_districts(){
		if(isset($_POST['state_id']) AND !empty($_POST['state_id'])){
			$state_id = Yii::$app->utility->decryptString($_POST['state_id']);
			if(empty($state_id)){
				$result['Status'] = 'FF';
				$result['Res'] = 'Invalid params value found';
				echo json_encode($result);
				die;
			}
			$districts = Yii::$app->Dakutility->get_master_districts(NULL, $state_id);
			
			$html = "<option value=''>Select District</option>";
			if(!empty($districts)){
				foreach($districts as $d){
					$distt_id = Yii::$app->utility->encryptString($d['distt_id']);
					$district_name = ucwords($d['district_name']);
					$html .="<option value='$distt_id'>$district_name</option>";
				}
			}else{
				$html .="<option value='' disabled>No District Found</option>";
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
	}
    public function actionGet_designation_emp_type(){
		if(isset($_POST['emp_type']) AND !empty($_POST['emp_type'])){
			$emp_type = Yii::$app->utility->decryptString($_POST['emp_type']);
			if(empty($emp_type)){
				$result['Status'] = 'FF';
				$result['Res'] = 'Invalid params value found';
				echo json_encode($result);
				die;
			}
			if($emp_type == 'R' OR $emp_type == 'C' OR $emp_type == 'O'){
				
			}else{
				$result['Status'] = 'FF';
				$result['Res'] = 'Invalid Employee Type';
				echo json_encode($result);
				die;
			}
			
			$designations = Yii::$app->utility->get_designation(NULL);
			
			$html = "<option value=''>Select Designation</option>";
			if(!empty($designations)){
				$list = "";
				foreach($designations as $d){
					if($emp_type == $d['emp_type']){
						if($d['desg_id'] != '1'){
							$desg_id = Yii::$app->utility->encryptString($d['desg_id']);
							$desg_name = ucwords($d['desg_name']);
							$list .="<option value='$desg_id'>$desg_name</option>";
						}
					}
				}
				if(empty($list)){
					$html .="<option value='' disabled>No Record Found</option>";
				}else{
					$html .=$list;
				}
			}else{
				$html .="<option value='' disabled>No Record Found</option>";
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
	}
    
}