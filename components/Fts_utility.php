<?php
namespace app\components;
use Yii;
use yii\base\Component;
use yii\web\Controller;
use yii\base\InvalidConfigException;
use yii\db\Query;
use yii\web\Session;
use yii\db\mssql\PDO;
use yii\base\Security;
use app\models\efile_master_project;
use app\models\EfileDak;
use app\models\RbacEmployeeRole;

class Fts_utility extends Component 
{
	public function efile_get_hod_emps($Param_emp_code){
        $connection= Yii::$app->db;
        $connection->open();
        $sql =" CALL `efile_get_hod_emps`(:Param_emp_code)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':Param_emp_code', $Param_emp_code);
		$result=$command->queryOne();
        $connection->close();
        return $result;   
    }
    public function showefiledashboard(){
        if(Yii::$app->user->identity->role == '2' OR Yii::$app->user->identity->role == '7' OR Yii::$app->user->identity->role == '19'){
        $emplists = RbacEmployeeRole::find()->where( 'is_active="Y" and role_id IN (2,7,19)')->asArray()->all();
        $emp_lists = array();
        $i=0;
        foreach($emplists as $emp){
            if($emp['role_id'] == '2'){
                $emp_lists[$i]['employee_code']=$emp['employee_code'];
                $emp_lists[$i]['display_type']="D";
                $emp_lists[$i]['dept_id']="D";
            }elseif($emp['role_id'] == '7'){
                $emp_lists[$i]['employee_code']=$emp['employee_code'];
                $emp_lists[$i]['display_type']="ED";
            }elseif($emp['role_id'] == '19'){
                $emp_lists[$i]['employee_code']=$emp['employee_code'];
                $emp_lists[$i]['display_type']="EDO";
            }
            $i++;
        }

        $return = array();
        $display_type = "";
        $list = array();
        foreach($emp_lists as $e){
            if($e['employee_code'] == Yii::$app->user->identity->e_id){
                if($e['display_type'] == 'D'){
                    $emps = Yii::$app->fts_utility->efile_get_hod_emps($e['employee_code']);
                    
                    $display_type = "eFile Dashboard (Head of Department)";
                    $sql = '';
                    if(!empty($emps)){
                        $l = $emps['dept_emp_list'];

                        if(!empty($l))                           
                        $sql = "SELECT * FROM efile_dak where `emp_code` IN ($l) AND is_active='Y' ORDER BY `file_id` DESC";

                    }
                }elseif($e['display_type'] == 'EDO'){
                    $display_type = "eFile Dashboard (Director Office)";
                    $sql = "SELECT * FROM efile_dak where is_active='Y' ORDER BY `file_id` DESC";
                }elseif($e['display_type'] == 'ED'){
                    $display_type = "eFile Dashboard (Executive Director)";
                    $sql = "SELECT * FROM efile_dak where is_active='Y' ORDER BY `file_id` DESC";
                }
                if(!empty($sql))
                $list = EfileDak::findBySql($sql)->asArray()->all();
            }
        }

        $return['display_type'] = $display_type;
        $return['list'] = $list;
        return $return;
        }
    }
	public function makefilefromdocs($fileid)
    {
        require_once './mpdf/mpdf.php';
        $mpdf = new \mPDF();
        $stylesheet = file_get_contents("./css/mpdf_csss.css"); // external css
        $mpdf->WriteHTML($stylesheet, 1);
        $url = Yii::$app->homeUrl;
        $appNum = "CDAC/M/1742";
        $appdt = date('d-M-Y');
        $header="<div class='mpdfheader'>No. $appNum Dated : $appdt</div>";
        $mpdf->SetHTMLHeader($header);
        $filedocs = Yii::$app->Dakutility->efile_get_dak_docs($fileid);
        $mpdf->SetImportUse();
        $i=1;
        $DepartmentName = "CDAC";
        $d= date('d-m-Y H:i:s');
        $footer = "<table class='mpdf_width'>
                <tr>
                <td class='footerDept'>$DepartmentName</td>
                <td class='footerPage'>Page {PAGENO} of {nbpg}</td>
                <td class='footerDate' align='right'>Printed on : $d</td>
                </tr>
                </table>
                ";
        if(!empty($filedocs))
        {
            $mpdf->SetHTMLFooter($footer);
            $mpdf->AddPage();
            foreach($filedocs  as $key=>$value)
            {
                $path = getcwd().$value['docs_path'];
                if(!empty($path))
                {
                    $ext= explode(".", $path);
                    $ext=$ext[1];
                    $chkext=$value["doc_ext_type"];
                    if($ext=="pdf" || $ext=="PDF" || $chkext=="PDF")
                    {        
                        $mpdf->SetImportUse();
//                        $f=getcwd()."/"."other_files/DAK_RECEIVED/341814/4322701588236586.pdf";
                        $pagecount = $mpdf->SetSourceFile($path);
                        for ($i=1; $i<=$pagecount; $i++)
                        {
                            $import_page = $mpdf->ImportPage($i);
                            $mpdf->UseTemplate($import_page);
                            if ($i < $pagecount)
                            {
                                $mpdf->AddPage();
                            }
                        }
                    }
                    else
                    {
                        $html="<div ><img  src='$path' /></div>";
                        $mpdf->WriteHTML($html);
                        $mpdf->AddPage();
                    }
                }
            }
        }
        $createFolder = getcwd().FILE_MOVEMENT;
        if(!file_exists($createFolder))
        {
            mkdir($createFolder, 0777, true);
        }
        $finalPath = $createFolder.$fileid.".pdf";
        $returnPath=FILE_MOVEMENT.$fileid.".pdf";
        $returnName=$mpdf->Output($finalPath, "F");
        return $returnPath;

    }

    public function validatePdfFileType($file_type){		
        if($file_type == 'application/pdf' OR $file_type == 'data:binary/octet-stream' OR $file_type == 'data:application/x-download'){
            return true;
        }else{
            return false;
        }
    }
    public function validatePdfFileSize($file_size){
        $return = true;
        if(!empty($file_size)){
            $file_size = round($file_size / 1024);
//            $file_size = round($file_size / 1024);
            $FTS_Doc_Size = FTS_Doc_Size;
            $FTS_Doc_Size = $FTS_Doc_Size * 1024;
            if($file_size > $FTS_Doc_Size){
                $return = false;
            }
        }
        return $return;
    }
    public function validatePdfFile($file){
		
        if(!empty($file)){
            $f = fopen($file, 'rb');
            $header1 = fread($f, 3);
            fclose($f);   
            $check1 = strncmp($header1, "\x50\x44\x46", 3)==0 && strlen ($header1)==3;
            $f = fopen($file, 'rb');
            $header2 = fread($f, 4);
            fclose($f);   
            $check2 = strncmp($header2, "\x25\x50\x44\x46", 4)==0 && strlen ($header2)==4;
            if($check1 || $check2){ return true; }else{ return false; }
        }else{
            return false;
        }
    }
    
    public function validateImage($image_type, $temppath){
        $info = getimagesize($temppath);
        
        $return = true;
        if(empty($info)){
            $return = false;
        }
        //OR $image_type == 'image/png'
        if($image_type == 'image/jpeg' OR $image_type == 'image/jpg' OR $image_type == 'image/png'){
        }else{
            $return = false;
        }
        return $return;
    }
    public function validateImageSize($file_size){
        $return = true;
        if(!empty($file_size)){
            $file_size = round($file_size / 1024);
            
            $FTS_Image_Size = FTS_Image_Size;
            $FTS_Image_Size = $FTS_Image_Size * 1024;
            if($file_size > $FTS_Image_Size){
                $return = false;
            }
        }
        return $return;
    }
	
	public function efile_add_update_dak_groups($Param_action_type, $Param_dak_group_id, $Param_file_id, $Param_group_name, $Param_is_active){
        $connection= Yii::$app->db;
        $connection->open();
        $sql =" CALL `efile_add_update_dak_groups`(:Param_action_type, :Param_dak_group_id, :Param_file_id, :Param_group_name, :Param_created_by, :Param_is_active, :Param_created_by_dept_id, @Result )";
        
	    $command = $connection->createCommand($sql); 
	    $command->bindValue(':Param_action_type', $Param_action_type);
	    $command->bindValue(':Param_dak_group_id', $Param_dak_group_id);
	    $command->bindValue(':Param_file_id', $Param_file_id);
	    $command->bindValue(':Param_created_by', Yii::$app->user->identity->e_id);
	    $command->bindValue(':Param_created_by_dept_id', Yii::$app->user->identity->dept_id);
	    $command->bindValue(':Param_is_active', $Param_is_active);
	    $command->bindValue(':Param_group_name', $Param_group_name);
		$command->execute();
		$valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
		$connection->close();
		return $valueOut;  
    }
	public function elif_add_efile_dak_notes($Param_action_type, $Param_file_id, $Param_note_comment, $Param_file_attach, $Param_note_subject, $Param_status, $Param_noteid, $Param_content_type){
        $connection= Yii::$app->db;
        $connection->open();
        $sql =" CALL `elif_add_efile_dak_notes`(:Param_action_type, :Param_file_id, :Param_note_comment, :Param_added_by, :Param_file_attach, :Param_note_subject, :Param_status, :Param_noteid, :Param_content_type, :Param_added_by_dept_id, @Result )";
        
	    $command = $connection->createCommand($sql); 
	    $command->bindValue(':Param_action_type', $Param_action_type);
	    $command->bindValue(':Param_file_id', $Param_file_id);
	    $command->bindValue(':Param_note_comment', $Param_note_comment);
		$command->bindValue(':Param_added_by', Yii::$app->user->identity->e_id);
		$command->bindValue(':Param_file_attach', $Param_file_attach);
		$command->bindValue(':Param_note_subject', $Param_note_subject);
		$command->bindValue(':Param_status', $Param_status);
		$command->bindValue(':Param_noteid', $Param_noteid);
		$command->bindValue(':Param_content_type', $Param_content_type);
		$command->bindValue(':Param_added_by_dept_id', Yii::$app->user->identity->dept_id);
		$command->execute();
		$valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
		$connection->close();
		return $valueOut;  
    }
	
	
	public function efile_add_update_efile_dak_movement($Param_action_type, $Param_id, $Param_file_id, $Param_fwd_to, $Param_dak_group_id, $Param_fwd_emp_code, $Param_is_time_bound, $Param_fwd_file_type, $Param_response_date, $Param_status, $Param_is_reply_required, $Param_reply_status, $Param_fwd_by, $Param_is_initiate_file, $Param_fwd_emp_dept_id){
        $connection= Yii::$app->db;
        $connection->open();
        $sql =" CALL `efile_add_update_efile_dak_movement`(:Param_action_type, :Param_id, :Param_file_id, :Param_fwd_to, :Param_dak_group_id, :Param_fwd_emp_code, :Param_is_time_bound, :Param_fwd_file_type, :Param_response_date, :Param_status, :Param_is_reply_required, :Param_reply_status, :Param_fwd_by, :Param_is_initiate_file, :Param_fwd_emp_dept_id, @Result )";
        
	    $command = $connection->createCommand($sql); 
	    $command->bindValue(':Param_action_type', $Param_action_type);
	    $command->bindValue(':Param_id', $Param_id);
	    $command->bindValue(':Param_file_id', $Param_file_id);
	    $command->bindValue(':Param_fwd_to', $Param_fwd_to);
	    $command->bindValue(':Param_dak_group_id', $Param_dak_group_id);
	    $command->bindValue(':Param_fwd_emp_code', $Param_fwd_emp_code);
	    $command->bindValue(':Param_is_time_bound', $Param_is_time_bound);
	    $command->bindValue(':Param_fwd_file_type', $Param_fwd_file_type);
	    $command->bindValue(':Param_response_date', $Param_response_date);
	    $command->bindValue(':Param_status', $Param_status);
	    $command->bindValue(':Param_is_reply_required', $Param_is_reply_required);
	    $command->bindValue(':Param_reply_status', $Param_reply_status);
	    $command->bindValue(':Param_fwd_by', $Param_fwd_by);
	    $command->bindValue(':Param_is_initiate_file', $Param_is_initiate_file);
	    $command->bindValue(':Param_fwd_emp_dept_id', $Param_fwd_emp_dept_id);
            $command->execute();
            $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
            $connection->close();
            return $valueOut;  
    }
	public function efile_dak_docs($param_file_id,$param_attach_with,$param_noteid,$param_tag_id, $param_doc_ext_type,$param_docs_path)
    {
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `efile_dak_docs`(:param_file_id,:param_attach_with,:param_noteid,:param_tag_id, :param_doc_ext_type,:param_docs_path,:param_added_by, :param_added_by_dept_id, @Result)";
        $command = $connection->createCommand($sql);
        $command->bindValue(':param_file_id', $param_file_id);
        $command->bindValue(':param_attach_with', $param_attach_with);
        $command->bindValue(':param_noteid', $param_noteid);
        $command->bindValue(':param_tag_id', $param_tag_id);
        $command->bindValue(':param_doc_ext_type', $param_doc_ext_type);
        $command->bindValue(':param_docs_path', $param_docs_path);
        $command->bindValue(':param_added_by', Yii::$app->user->identity->e_id);
        $command->bindValue(':param_added_by_dept_id', Yii::$app->user->identity->dept_id);
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut;
    }
    public function uploadImageTopdf($base64, $FTS_Documents){
       // require_once './mpdf/mpdf.php';
        $mpdf = new  \Mpdf\Mpdf();
        $stylesheet = file_get_contents("./css/mpdf_csss.css"); // external css
        $mpdf->WriteHTML($stylesheet, 1);
        
        $i=0;
        $newlist = array();
        $temp_folder = getcwd().$FTS_Documents."temp_folder/";
        if(!file_exists($temp_folder)){
            mkdir($temp_folder, 0777, true);
        }
        foreach($base64 as $key=>$b){
            $info = new \SplFileInfo($b['file_name']);
            $ext = $info->getExtension();
            $random_number = mt_rand(100000, 999999);
            $newName = $random_number.strtotime(date('Y-m-d H:i:s')).".$ext";
            $finalName = $temp_folder.$newName;
            $fileUploadedCheck = false;
            if(move_uploaded_file($b['tmp_name'],$finalName)){
                chmod($finalName, 0777);
                $newlist[$i]['image_name'] = $FTS_Documents."temp_folder/$newName";
                $i++;
            }            
        }
        $total = count($newlist);
        $j=0;
        foreach($newlist as $key=>$n){
            $url = "";
            $url = Yii::$app->homeUrl.$n['image_name'];
            $img = "<img src='$url' />";
            $mpdf->WriteHTML($img);
            if($total != $j OR $key == '0'){
            }else{
                $mpdf->AddPage();
            }
        }

        $e_id = Yii::$app->user->identity->e_id;
        $createFolder = getcwd()."/".$FTS_Documents.$e_id;
        if(!file_exists($createFolder)){
            mkdir($createFolder, 0777, true);
        }
        $random_number = mt_rand(100000, 999999);
        $newName = $random_number.strtotime(date('Y-m-d H:i:s')).".pdf";
        $finalName = $createFolder."/".$newName;
        $fileUploadedCheck = false;
        $mpdf->Output($finalName, "F");
        $returnPath = "$FTS_Documents/$e_id/$newName";
        
        foreach($newlist as $key=>$n){
            Yii::$app->fts_utility->removefile($n['image_name']);
        }
        
        return $returnPath;
    }
    public function uploadFile($temPth, $Name, $DocSavePath){
        $chk = substr_count($Name, '.');
		if($chk > 1 OR $chk == 0){
            $returnName = "";
        }else{
            $e_id = Yii::$app->user->identity->e_id;
            $createFolder = getcwd()."/".$DocSavePath.$e_id;
            if(!file_exists($createFolder)){
                mkdir($createFolder, 0777, true);
            }
            $info = new \SplFileInfo($Name);
            $ext = $info->getExtension();

            $random_number = mt_rand(100000, 999999);
            $newName = $random_number.strtotime(date('Y-m-d H:i:s')).".$ext";
            $finalName = $createFolder."/".$newName;
            $fileUploadedCheck = false;
            if(move_uploaded_file($temPth,$finalName)){
                chmod($finalName, 0777);
                $fileUploadedCheck = true;
            }

            if(!empty($fileUploadedCheck)){
                $returnName = $DocSavePath.$e_id."/".$newName;
            }else{
                $returnName = "";
            }
        }
        return $returnName;
    }

    public function uploadFileMunish($temPth, $Name, $DocSavePath)
    {
        $chk = substr_count($Name, '.');
        if($chk > 1 OR $chk == 0){
            $returnName = "";
        }else{
            $e_id = Yii::$app->user->identity->e_id;
            $createFolder = getcwd()."/".$DocSavePath;
            if(!file_exists($createFolder)){
                mkdir($createFolder, 0777, true);
            }
            $info = new \SplFileInfo($Name);
            $ext = $info->getExtension();

            $random_number = mt_rand(100000, 999999);
            $newName = $e_id.''.$random_number.strtotime(date('Y-m-d H:i:s')).".$ext";
            $finalName = $createFolder."/".$newName;
            $fileUploadedCheck = false;
            if(move_uploaded_file($temPth,$finalName)){
                chmod($finalName, 0777);
                $fileUploadedCheck = true;
            }

            if(!empty($fileUploadedCheck)){
                $returnName = $DocSavePath."/".$newName;
            }else{
                $returnName = "";
            }
        }
        return $returnName;
    }

	public function removefile($filename){
        if(!empty($filename)){
            $ld = getcwd()."/".$filename;
            if(file_exists($ld)){
                unlink($ld);
            }
        }
    }
	public function efile_add_update_efile_dak_group_members($Param_action_type, $Param_id, $Param_dak_group_id, $Param_employee_code, $Param_group_role, $Param_emp_dept_id){
        $connection= Yii::$app->db;
        $connection->open();
        $sql =" CALL `efile_add_update_efile_dak_group_members`(:Param_action_type, :Param_id, :Param_dak_group_id, :Param_employee_code, :Param_group_role, :Param_emp_dept_id, @Result )";
        
	    $command = $connection->createCommand($sql); 
	    $command->bindValue(':Param_action_type', $Param_action_type);
	    $command->bindValue(':Param_id', $Param_id);
	    $command->bindValue(':Param_dak_group_id', $Param_dak_group_id);
	    $command->bindValue(':Param_group_role', $Param_group_role);
	    $command->bindValue(':Param_employee_code', $Param_employee_code);
	    $command->bindValue(':Param_emp_dept_id', $Param_emp_dept_id);
		$command->execute();
		$valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
		$connection->close();
		return $valueOut;  
    }
	public function efile_add_update_efile_dak($Param_action, $Param_file_id, $Param_rec_id, $Param_file_type, $Param_reference_num, $Param_reference_date, $Param_subject, $Param_file_category_id, $Param_file_project_id, $Param_action_type, $Param_access_level, $Param_priority, $Param_is_confidential, $Param_meta_keywords, $Param_remarks, $Param_summary, $Param_status, $Param_sent_for_scan, $Param_emp_dept_id=NULL){
        $connection= Yii::$app->db;
        $connection->open();
        $sql =" CALL `efile_add_update_efile_dak`(:Param_action, :Param_file_id, :Param_rec_id, :Param_file_type, :Param_reference_num, :Param_reference_date, :Param_subject, :Param_file_category_id, :Param_file_project_id, :Param_action_type, :Param_access_level, :Param_priority, :Param_is_confidential, :Param_meta_keywords, :Param_remarks, :Param_summary, :Param_status, :Param_emp_code, :Param_sent_for_scan, :Param_emp_dept_id, @Result )";
        
	    $command = $connection->createCommand($sql); 
	    $command->bindValue(':Param_action', $Param_action);
	    $command->bindValue(':Param_file_id', $Param_file_id);
	    $command->bindValue(':Param_rec_id', $Param_rec_id);
	    $command->bindValue(':Param_file_type', $Param_file_type);
	    $command->bindValue(':Param_reference_num', $Param_reference_num);
	    $command->bindValue(':Param_reference_date', $Param_reference_date);
	    $command->bindValue(':Param_subject', $Param_subject);
	    $command->bindValue(':Param_file_category_id', $Param_file_category_id);
	    $command->bindValue(':Param_file_project_id', $Param_file_project_id);
	    $command->bindValue(':Param_action_type', $Param_action_type);
	    $command->bindValue(':Param_access_level', $Param_access_level);
	    $command->bindValue(':Param_priority', $Param_priority);
	    $command->bindValue(':Param_is_confidential', $Param_is_confidential);
	    $command->bindValue(':Param_meta_keywords', $Param_meta_keywords);
	    $command->bindValue(':Param_remarks', $Param_remarks);
	    $command->bindValue(':Param_summary', $Param_summary);
	    $command->bindValue(':Param_status', $Param_status);
	    $command->bindValue(':Param_emp_code', Yii::$app->user->identity->e_id);
	    $command->bindValue(':Param_sent_for_scan', $Param_sent_for_scan);
	    $command->bindValue(':Param_emp_dept_id', $Param_emp_dept_id);
		$command->execute();
		$valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
		$connection->close();
		return $valueOut;  
    }
	
	public function elib_add_update_project($Param_action_type, $Param_file_project_id, $Param_project_name){
        $connection= Yii::$app->db;
        $connection->open();
        $sql =" CALL `elib_add_update_project`(:Param_action_type, :Param_file_project_id, :Param_project_name, :Param_added_by, @Result )";
        
	    $command = $connection->createCommand($sql); 
	    $command->bindValue(':Param_action_type', $Param_action_type);
	    $command->bindValue(':Param_file_project_id', $Param_file_project_id);
	    $command->bindValue(':Param_project_name', $Param_project_name);
	    $command->bindValue(':Param_added_by', Yii::$app->user->identity->e_id);
		$command->execute();
		$valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
		$connection->close();
		return $valueOut;  
    }
	
	public function get_efile_access_level($type, $id){
		
		$efile_access_level = efile_access_level;
		if($type == 'G' AND !empty($id)){
			$return = "";
			foreach($efile_access_level as $a){
				
				if($a['shortname'] == $id){
					$return = $a['name'];
				}
			}
			return $return;
		}elseif($type == 'G'){
			$list = array();
			$i=0;
			foreach($efile_access_level as $a){
				$id = Yii::$app->utility->encryptString($a['shortname']);
				$list[$i]['id'] = $id;
				$list[$i]['name'] = $a['name'];
				$i++;
			}
			return $list;
		}elseif($type == 'C'){
			$return = "";
			foreach($efile_access_level as $a){
				if($a['shortname'] == $id){
					$return = "Yes";
				}
			}
			return $return;
		}
	}
	public function get_efile_check_yes_no($type, $id){
		$efile_check_yes_no = efile_check_yes_no;
		if($type == 'G'){
			$list = array();
			$i=0;
			foreach($efile_check_yes_no as $a){
				$id = Yii::$app->utility->encryptString($a['shortname']);
				$list[$i]['id'] = $id;
				$list[$i]['name'] = $a['name'];
				$i++;
			}
			return $list;
		}elseif($type == 'C'){
			$return = "";
			foreach($efile_access_level as $a){
				if($a['shortname'] == $id){
					$return = "Yes";
				}
			}
			return $return;
		}
	}
	
	public function get_efile_priority($type, $id){
		$efile_priority = efile_priority;
		if($type == 'G'){
			$list = array();
			$i=0;
			foreach($efile_priority as $a){
				$id = Yii::$app->utility->encryptString($a);
				$list[$i]['id'] = $id;
				$list[$i]['name'] = $a;
				$i++;
			}
			return $list;
		}elseif($type == 'C'){
			$return = "";
			foreach($efile_priority as $a){
				if($a == $id){
					$return = "Yes";
				}
			}
			return $return;
		}
	}
	
    public function efile_get_dak_received($Param_rec_id, $Param_dak_fwd_to){
        $connection= Yii::$app->db;
        $connection->open();
        $sql =" CALL `efile_get_dak_received`(:Param_rec_id, :Param_dak_fwd_to)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':Param_rec_id', $Param_rec_id);
        $command->bindValue(':Param_dak_fwd_to', $Param_dak_fwd_to);
        if(!empty($Param_rec_id)){
            $result=$command->queryOne();
        }else{
            $result=$command->queryAll();
        }
        $connection->close();
        return $result;   
    }
    
    public function efile_get_outbox_daks($Param_fwd_by){
        $connection= Yii::$app->db;
        $connection->open();
        $sql =" CALL `efile_get_outbox_daks`(:Param_fwd_by)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':Param_fwd_by', $Param_fwd_by);
        $result=$command->queryAll();
        $connection->close();
        return $result;   
    }
    
	public function efile_get_efile_dak_notes($Param_file_id, $Param_noteid){
        $connection= Yii::$app->db;
        $connection->open();
        $sql =" CALL `efile_get_efile_dak_notes`(:Param_file_id, :Param_noteid)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':Param_file_id', $Param_file_id);
        $command->bindValue(':Param_noteid', $Param_noteid);
        if(!empty($Param_noteid)){
            $result=$command->queryOne();
        }else{
            $result=$command->queryAll();
        }
        $connection->close();
        return $result;   
    }
	
	
	public function efile_get_efile_dak_movement($Param_fwd_emp_code, $Param_id){
        $connection= Yii::$app->db;
        $connection->open();
        $sql =" CALL `efile_get_efile_dak_movement`(:Param_fwd_emp_code, :Param_id)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':Param_fwd_emp_code', $Param_fwd_emp_code);
        $command->bindValue(':Param_id', $Param_id);
        // $command->bindValue(':Param_status', $Param_status);
		if(!empty($Param_id)){
			$result=$command->queryOne();
        }else{
            $result=$command->queryAll();
        }
      
        $connection->close();
        return $result;   
    }
	public function showYesNo($val){
		$return = "<span class='hindishow12'>????????????</span> / No";
		if($val == 'Y'){
			$return = "<span class='hindishow12'>?????????</span> / Yes";
		}
		return $return;
	}
	public function getdocumentpath($filepath){
        if(!empty($filepath)){
            $ext = pathinfo(getcwd()."/".$filepath, PATHINFO_EXTENSION);
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $p = getcwd()."/".$filepath;
            if(file_exists($p)){
                $mime = finfo_file($finfo, $p);
                $ext = Yii::$app->utility->encryptString($ext);
                $mime = Yii::$app->utility->encryptString($mime);
                $fileurl = Yii::$app->utility->encryptString($filepath);
                $url = Yii::$app->homeUrl."viewdocument/encryption?key=$fileurl&key1=$ext&key2=$mime";
                return $url;
            }
        }
        
    }
	public function efile_get_dak($Param_file_id, $Param_rec_id, $Param_emp_code, $Param_status){
        $connection= Yii::$app->db;
        $connection->open();
        $sql =" CALL `efile_get_dak`(:Param_file_id, :Param_rec_id, :Param_emp_code, :Param_status)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':Param_file_id', $Param_file_id);
        $command->bindValue(':Param_rec_id', $Param_rec_id);
        $command->bindValue(':Param_emp_code', $Param_emp_code);
        $command->bindValue(':Param_status', $Param_status);
        if(!empty($Param_file_id) OR !empty($Param_rec_id)){
            $result=$command->queryOne();
        }else{
            $result=$command->queryAll();
        }
        $connection->close();
        return $result;   
    }
	
	public function get_master_states($Param_state_id){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `get_master_states`(:Param_state_id)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':Param_state_id', $Param_state_id); 
		if(!empty($Param_state_id)){
			$result=$command->queryOne();
		}else{
			$result=$command->queryAll();
		}
        $connection->close();
        return $result;
    }
	public function get_master_districts($Param_distt_id, $Param_state_id){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `get_master_districts`(:Param_distt_id, :Param_state_id)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':Param_distt_id', $Param_distt_id); 
        $command->bindValue(':Param_state_id', $Param_state_id); 
		if(!empty($Param_distt_id)){
			$result=$command->queryOne();
		}else{
			$result=$command->queryAll();
		}
        $connection->close();
        return $result;
    }
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
    public function fts_getgroupmaster($param_group_id){
        $connection= Yii::$app->db;
        $connection->open();
        $sql =" CALL `fts_getgroupmaster`(:param_group_id)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_group_id', $param_group_id, PDO::PARAM_INT);
        if(!empty($param_group_id)){
            $result=$command->queryOne();
        }else{
            $result=$command->queryAll();
        }
        $connection->close();
        return $result;   
    }
    
    public function fts_getcategorymaster($param_fts_category_id = NULL){
        $connection= Yii::$app->db;
        $connection->open();
        $sql =" CALL `fts_getcategorymaster`(:param_fts_category_id)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_fts_category_id', $param_fts_category_id);
        if(!empty($param_fts_category_id)){
            $result=$command->queryOne();
        }else{
            $result=$command->queryAll();
        }
        $connection->close();
        return $result;   
    }
    
    public function fts_getdept(){
        $connection= Yii::$app->db;
        $connection->open();
        $sql =" CALL `get_dept`('')";
	    $command = $connection->createCommand($sql); 
        $result=$command->queryAll();
        $connection->close();
        return $result;   
    }
	public function fts_getdak($eid,$did=NULL){
        $connection= Yii::$app->db;
        $connection->open();
        $sql =" CALL `fts_getdak`(:e_id,:d_id) ";
	    $command = $connection->createCommand($sql); 
        $command->bindValue(':e_id', $eid, PDO::PARAM_INT);
        $command->bindValue(':d_id', $did, PDO::PARAM_INT);
        $result=$command->queryAll();
        $connection->close();
        return $result;   
    }
    
   
    public function fts_deptemployees($param_dept_id=NULL){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `get_dept_emp`(:param_dept_id)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_dept_id', $param_dept_id, PDO::PARAM_STR);
        $result=$command->queryAll();
        $connection->close();
        return $result;       
    }
    	 
     public function fts_updatedak($post){
        extract($post);
         
        $connection= Yii::$app->db;
        $connection->open();
        $sql =" CALL `fts_updatedak`(:refrence_no,:file_name,:subject,:send_to_group,:send_to_emp,:category,:summary,:remarks,:access_level,:priority,:is_confidential,:file_date,:meta_keywords,:doc_type,:filePathName,:document_path,:send_from,:dak_id,:status,:is_active,@Result )";
        
	    $command = $connection->createCommand($sql); 
	    $command->bindValue(':refrence_no', $refrence_no);
		$command->bindValue(':file_name', $file_name);         
		$command->bindValue(':subject', $subject);
		$command->bindValue(':send_to_group', $send_to_group);
		$command->bindValue(':send_to_emp', $send_to_emp);
		$command->bindValue(':category', $category);    
		$command->bindValue(':summary', $summary);
		$command->bindValue(':remarks', $remarks);
		$command->bindValue(':access_level', $access_level);
		
		$command->bindValue(':priority', $priority);
		$command->bindValue(':is_confidential', $is_confidential);
		$command->bindValue(':file_date', $file_date);
		$command->bindValue(':meta_keywords', $meta_keywords);    
		$command->bindValue(':doc_type', $doc_type);
		$command->bindValue(':filePathName', $document);      
		$command->bindValue(':document_path', $document_path);      
		$command->bindValue(':send_from', $send_from);
		$command->bindValue(':dak_id', $dak_id);
		$command->bindValue(':status', $status);
		$command->bindValue(':is_active', $is_active);
		
		$command->execute();
		$valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
		$connection->close();
		return $valueOut;  
    }
    
    public function fts_create_dak($param_ticket_number, $param_is_hierarchical, $param_send_to_group, $param_send_to_emp, $param_file_refrence_no, $param_file_date, $param_despatch_num, $param_despatch_date, $param_subject, $param_category, $param_access_level, $param_priority, $param_is_confidential, $param_reply_last_date, $param_next_order_num, $param_meta_keywords, $param_remarks, $param_summary, $param_doc_type, $param_docs_path, $param_status, $param_draft_flag){
    	$connection= Yii::$app->db;
        $connection->open();
        $sql =" CALL `fts_create_dak`(:param_ticket_number, :param_is_hierarchical, :param_send_to_group, :param_send_to_emp, :param_send_from, :param_file_refrence_no, :param_file_date, :param_despatch_num, :param_despatch_date, :param_subject, :param_category, :param_access_level, :param_priority, :param_is_confidential, :param_reply_last_date, :param_next_order_num, :param_meta_keywords, :param_remarks, :param_summary, :param_doc_type, :param_docs_path, :param_status, :param_draft_flag, @Result )";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_ticket_number', $param_ticket_number);
        $command->bindValue(':param_is_hierarchical', $param_is_hierarchical);
        $command->bindValue(':param_send_to_group', $param_send_to_group);
        $command->bindValue(':param_send_to_emp', $param_send_to_emp);
        $command->bindValue(':param_send_from', Yii::$app->user->identity->e_id);
        $command->bindValue(':param_file_refrence_no', $param_file_refrence_no);
        $command->bindValue(':param_file_date', $param_file_date);
        $command->bindValue(':param_despatch_num', $param_despatch_num);
        $command->bindValue(':param_despatch_date', $param_despatch_date);
        $command->bindValue(':param_subject', $param_subject);
        $command->bindValue(':param_category', $param_category);
        $command->bindValue(':param_access_level', $param_access_level);
        $command->bindValue(':param_priority', $param_priority);
        $command->bindValue(':param_is_confidential', $param_is_confidential);
        $command->bindValue(':param_reply_last_date', $param_reply_last_date);
        $command->bindValue(':param_next_order_num', $param_next_order_num);
        $command->bindValue(':param_meta_keywords', $param_meta_keywords);
        $command->bindValue(':param_remarks', $param_remarks);
        $command->bindValue(':param_summary', $param_summary);
        $command->bindValue(':param_doc_type', $param_doc_type);
        $command->bindValue(':param_docs_path', $param_docs_path);
        $command->bindValue(':param_status', $param_status);
        $command->bindValue(':param_draft_flag', $param_draft_flag);
	$command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
         
    }
    
    public function fts_add_update_group_master($param_group_id, $param_group_name, $param_group_description, $param_is_active, $param_is_hierarchical){
    	$connection= Yii::$app->db;
        $connection->open();
        $sql =" CALL `fts_add_update_group_master`(:param_group_id, :param_group_name, :param_group_description, :param_created_by, :param_is_active, :param_is_hierarchical, @Result )";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_group_id', $param_group_id);
        $command->bindValue(':param_group_name', $param_group_name);
        $command->bindValue(':param_group_description', $param_group_description);
        $command->bindValue(':param_created_by', Yii::$app->user->identity->e_id);
        $command->bindValue(':param_is_active', $param_is_active);
        $command->bindValue(':param_is_hierarchical', $param_is_hierarchical);
	$command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
    }
    public function fts_add_update_group_members($param_id, $param_group_id, $param_employee_code){
    	$connection= Yii::$app->db;
        $connection->open();
        $sql =" CALL `fts_add_update_group_members`(:param_id, :param_group_id, :param_employee_code, :param_added_by, @Result )";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_id', $param_id);
        $command->bindValue(':param_group_id', $param_group_id);
        $command->bindValue(':param_employee_code', $param_employee_code);
        $command->bindValue(':param_added_by', Yii::$app->user->identity->e_id);
	$command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
    }
    public function fts_get_group_members($param_group_id){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `fts_get_group_members`(:param_group_id)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_group_id', $param_group_id, PDO::PARAM_INT);
        $result=$command->queryAll();
        $connection->close();
        return $result;       
    }
    public function fts_get_category($fts_category_id){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `fts_get_category`(:fts_category_id)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':fts_category_id', $fts_category_id);
        if(!empty($fts_category_id)){
            $result=$command->queryOne();
        }else{
            $result=$command->queryAll();
        }
        $connection->close();
        return $result;       
    }
    
    public function fts_add_update_category($param_fts_category_id, $param_cat_name, $param_description){
    	$connection= Yii::$app->db;
        $connection->open();
        $sql =" CALL `fts_add_update_category`(:param_fts_category_id, :param_cat_name, :param_description, @Result )";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_fts_category_id', $param_fts_category_id);
        $command->bindValue(':param_cat_name', $param_cat_name);
        $command->bindValue(':param_description', $param_description);
	$command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
    }
    public function fts_add_update_group_process($param_hy_id, $param_group_id, $param_role_id, $param_order_number){
    	$connection= Yii::$app->db;
        $connection->open();
        $sql =" CALL `fts_add_update_group_process`(:param_hy_id, :param_group_id, :param_role_id, :param_order_number, :param_added_by, @Result )";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_hy_id', $param_hy_id);
        $command->bindValue(':param_group_id', $param_group_id);
        $command->bindValue(':param_role_id', $param_role_id);
        $command->bindValue(':param_order_number', $param_order_number);
        $command->bindValue(':param_added_by', Yii::$app->user->identity->e_id);
	$command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
    }
    public function fts_get_new_ticket_num(){
        
        $yr = date('Y');
        $m = date('m');
        if($m >= 3){ $CurrentYr = $yr+1; }else{ $CurrentYr = $yr+1;}
        $CurDate = date('d-m-Y');
        $CurYr = date('Y', strtotime($CurDate));
        $Curmonth = date('m', strtotime($CurDate));
        if($Curmonth >= 3){ $yrss = $CurYr+1; }else{ $yrss = $CurYr-1; }
        $fn ="";
        for($i=$CurrentYr;$i>=$yrss;$i--){
            $ly = $i-1;	
            $fn= $ly."-".$i;
        }
//        echo "$fn <br>";
        $aa = explode('-',$fn);
        $from = $aa[0];
        $to = $aa[1];
        $from = "$from-04-01";
        $to = "$to-03-31";
        
    	$connection= Yii::$app->db;
        $connection->open();
        $sql =" CALL `fts_get_new_ticket_num`(:param_from, :param_to, @Result )";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_from', $from);
        $command->bindValue(':param_to', $to);
	$command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        if($valueOut > 0){
            $valueOut = sprintf("%02d", $valueOut);
        }
        return $valueOut; 
    }
    public function fts_get_group_process($param_group_id){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `fts_get_group_process`(:param_group_id)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_group_id', $param_group_id);
        $result=$command->queryAll();
        $connection->close();
        return $result;
    }
    public function fts_get_dak($param_daktype, $param_emp_code){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `fts_get_dak`(:param_daktype, :param_emp_code)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_daktype', $param_daktype);
        $command->bindValue(':param_emp_code', $param_emp_code);
        $result=$command->queryAll();
        $connection->close();
        return $result;
    }
    public function fts_get_dak_detail($param_dak_id){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `fts_get_dak_detail`(:param_dak_id)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_dak_id', $param_dak_id);
        $result=$command->queryOne();
        $connection->close();
        return $result;
    }
    
    public function fts_add_dak_sent($param_dak_id, $param_send_from, $param_send_to_emp,$param_status){
    	$connection= Yii::$app->db;
        $connection->open();
        $sql =" CALL `fts_add_dak_sent`(:param_dak_id, :param_send_from, :param_send_to_emp, :param_status, @Result )";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_dak_id', $param_dak_id);
        $command->bindValue(':param_send_from', $param_send_from);
        $command->bindValue(':param_send_to_emp', $param_send_to_emp);
        $command->bindValue(':param_status', $param_status);
	$command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
    }
    public function fts_remove_dak_doc($param_dak_docs_id, $param_dak_id){
    	$connection= Yii::$app->db;
        $connection->open();
        $sql =" CALL `fts_remove_dak_doc`(:param_dak_docs_id, :param_dak_id, @Result )";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_dak_docs_id', $param_dak_docs_id);
        $command->bindValue(':param_dak_id', $param_dak_id);
	$command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
    }
    public function fts_update_dak($param_sentypechanged, $param_isdocchange, $param_dak_id, $param_ticket_number, $param_is_hierarchical, $param_send_to_group, $param_send_to_emp, $param_file_refrence_no, $param_file_date, $param_despatch_num, $param_despatch_date, $param_subject, $param_category, $param_access_level, $param_priority, $param_is_confidential, $param_meta_keywords, $param_remarks, $param_summary, $param_next_order_num, $param_status, $param_draft_flag, $param_doc_type, $param_docs_path){
    	$connection= Yii::$app->db;
        $connection->open();
        $sql =" CALL `fts_update_dak`(:param_sentypechanged, :param_isdocchange,:param_send_from, :param_dak_id, :param_ticket_number, :param_is_hierarchical, :param_send_to_group, :param_send_to_emp, :param_file_refrence_no, :param_file_date, :param_despatch_num, :param_despatch_date, :param_subject, :param_category, :param_access_level, :param_priority, :param_is_confidential, :param_meta_keywords, :param_remarks, :param_summary, :param_next_order_num, :param_status, :param_draft_flag, :param_doc_type, :param_docs_path, @Result )";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_sentypechanged', $param_sentypechanged);
        $command->bindValue(':param_isdocchange', $param_isdocchange);
        $command->bindValue(':param_send_from', Yii::$app->user->identity->e_id);
        $command->bindValue(':param_dak_id', $param_dak_id);
        $command->bindValue(':param_ticket_number', $param_ticket_number);
        $command->bindValue(':param_is_hierarchical', $param_is_hierarchical);
        $command->bindValue(':param_send_to_group', $param_send_to_group);
        $command->bindValue(':param_send_to_emp', $param_send_to_emp);
        $command->bindValue(':param_file_refrence_no', $param_file_refrence_no);
        $command->bindValue(':param_file_date', $param_file_date);
        $command->bindValue(':param_despatch_num', $param_despatch_num);
        $command->bindValue(':param_despatch_date', $param_despatch_date);
        $command->bindValue(':param_subject', $param_subject);
        $command->bindValue(':param_category', $param_category);
        $command->bindValue(':param_access_level', $param_access_level);
        $command->bindValue(':param_priority', $param_priority);
        $command->bindValue(':param_is_confidential', $param_is_confidential);
        $command->bindValue(':param_meta_keywords', $param_meta_keywords);
        $command->bindValue(':param_remarks', $param_remarks);
        $command->bindValue(':param_summary', $param_summary);
        $command->bindValue(':param_next_order_num', $param_next_order_num);
        $command->bindValue(':param_status', $param_status);
        $command->bindValue(':param_draft_flag', $param_draft_flag);
        $command->bindValue(':param_doc_type', $param_doc_type);
        $command->bindValue(':param_docs_path', $param_docs_path);
	$command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
    }
    public function fts_dak_sent_emplist($param_dak_id){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `fts_dak_sent_emplist`(:param_dak_id, :param_sent_from)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_dak_id', $param_dak_id);
        $command->bindValue(':param_sent_from', NULL);
        $result=$command->queryAll();
        $connection->close();
        return $result;
    }
    
    public function fts_add_dak_notes($param_dak_id, $param_note, $param_note_doc, $param_action_type, $param_fwd_to){
    	$connection= Yii::$app->db;
        $connection->open();
        $sql =" CALL `fts_add_dak_notes`(:param_dak_id, :param_emp_code, :param_note, :param_note_doc, :param_action_type, :param_fwd_to, @Result )";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_dak_id', $param_dak_id);
        $command->bindValue(':param_emp_code', Yii::$app->user->identity->e_id);
        $command->bindValue(':param_note', $param_note);
        $command->bindValue(':param_note_doc', $param_note_doc);
        $command->bindValue(':param_action_type', $param_action_type);
        $command->bindValue(':param_fwd_to', $param_fwd_to);
	$command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
    }
    
    public function fts_get_dak_notes($param_dak_id){
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `fts_get_dak_notes`(:param_dak_id)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_dak_id', $param_dak_id);
        $result=$command->queryAll();
        $connection->close();
        return $result;
    }
    
    public function fts_auth_for_view($param_dak_id){
    	$connection= Yii::$app->db;
        $connection->open();
        $sql =" CALL `fts_auth_for_view`(:param_dak_id, :param_employee_code, @Result )";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_dak_id', $param_dak_id);
        $command->bindValue(':param_employee_code', Yii::$app->user->identity->e_id);
	$command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
    }
	
	public function onlyCharacter($string) {
        $string =  trim(preg_replace('/[^A-Za-z ]/', '', $string));
        return $string;
    }
    public function onlyNumber($string) {
        $string =  trim(preg_replace('/[^0-9]/', '', $string));
        return $string;
    }
    public function onlyDecimalNumber($string) {
        $string =  trim(preg_replace('/[^0-9.]/', '', $string));
        return $string;
    }
    public function onlyChracterNumbers($string) {
        $string =  trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $string));
        return $string;
    }
	
	public function fts_add_dak_history($Param_dak_id, $Param_action_type, $Param_is_hierarchical, $Param_hierarchy_random_sr, $Param_send_to_group, $Param_send_to_emp, $Param_send_from, $Param_hierarchical_json){
    	$connection= Yii::$app->db;
        $connection->open();
        $sql =" CALL `fts_add_dak_history`(:Param_dak_id, :Param_action_type, :Param_is_hierarchical, :Param_hierarchy_random_sr, :Param_send_to_group, :Param_send_to_emp, :Param_send_from, :Param_hierarchical_json, @Result )";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':Param_dak_id', $Param_dak_id);
        $command->bindValue(':Param_action_type', $Param_action_type);
        $command->bindValue(':Param_is_hierarchical', $Param_is_hierarchical);
        $command->bindValue(':Param_hierarchy_random_sr', $Param_hierarchy_random_sr);
        $command->bindValue(':Param_send_to_group', $Param_send_to_group);
        $command->bindValue(':Param_send_to_emp', $Param_send_to_emp);
        $command->bindValue(':Param_send_from', $Param_send_from);
        $command->bindValue(':Param_hierarchical_json', $Param_hierarchical_json);
		$command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
    }
	
	public function scannerEmpCode(){
		$return = array();
		$model = RbacEmployeeRole::find()->where(['is_active' => 'Y', 'role_id'=>'20'])->all();
                $list = array();
		if(!empty($model)){
                    $i=0;
                    foreach($model as $m){
                        $memberInfo = Yii::$app->utility->get_employees($m->employee_code);
                        $memberInfo = $memberInfo['name_hindi'].' / '.$memberInfo['fullname'].", ".$memberInfo['desg_name']." ($memberInfo[dept_name])";
                        $list[$i]['employee_code'] = Yii::$app->utility->encryptString($m->employee_code);
                        $list[$i]['name'] = $memberInfo;
                        $i++;
                    }
		}
		return $list;
		
	}
        
    public function validateHindiString($string){
        $string = str_replace("</", '', $string);
        $string = str_replace("<script>", '', $string);
        $string = str_replace("<script>", '', $string);
        $string = trim($string);
        return $string;
    }
}
