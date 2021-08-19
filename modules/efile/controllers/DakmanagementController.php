<?php

namespace app\modules\efile\controllers;
use Yii;
use app\models\EfileDakReceived;
use app\models\EfileDakDispatch;
use app\models\EfileDakDispatchAddress;

class DakmanagementController extends \yii\web\Controller
{
    public function beforeAction($action){
    if (!\Yii::$app->user->isGuest) 
    {
        if(isset($_GET['securekey']) AND !empty($_GET['securekey']))
        {
            $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
//            echo $menuid;die;
            if(empty($menuid))
            { 
                return $this->redirect(Yii::$app->homeUrl);             
            }
            $chkValid = Yii::$app->utility->validate_url($menuid);
            if(empty($chkValid))
            { 
                return $this->redirect(Yii::$app->homeUrl);                
            }
            return true;
        }
        else
        { 
            return $this->redirect(Yii::$app->homeUrl);            
        }
    }
    else
    {
        return $this->redirect(Yii::$app->homeUrl);
    }
    parent::beforeAction($action);
    }
    
    public function actionPdf()
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
        $dak_received= Yii::$app->Dakutility->efile_get_dak_received(NULL);
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
        if(!empty($dak_received))
        {
            $mpdf->SetHTMLFooter($footer);
            $mpdf->AddPage();
            foreach($dak_received  as $key=>$value)
            {
                $path = getcwd().$value['dak_document'];
                echo $path;
                $pagecount = $mpdf->SetSourceFile($path);
                for ($i=1;$i<=$pagecount;$i++)
                {
                    $import_page = $mpdf->ImportPage($i);
                    $mpdf->UseTemplate($import_page);
                    if ($i < $pagecount)
                    {
                        $mpdf->AddPage();
                    }
                }
            }
        }
//        foreach($dak_received as $key=>$value)
//        {
//            if(!empty($value['dak_document']))
//            {
//                $mpdf->AddPage();
//                $doc_land_record= getcwd()."/".$value['dak_document'];
////                echo $doc_land_record; die;
//                $pagecount = $mpdf->SetSourceFile($doc_land_record);
//                $tplId = $mpdf->ImportPage($pagecount);
//                $mpdf->UseTemplate($tplId);
//            }
//            $i++;
//        }
        
        $name = "File".date('Y_m_d_H_i_s').".pdf";
        $file = $mpdf->Output($name, 'I');
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header("Cache-Control: max-age=0");
        readfile($file);
    }
    public function actionViewrecdetail() 
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);   
        $rec_id = Yii::$app->utility->decryptString($_GET['rec_id']);
//        $disp_id = Yii::$app->utility->encryptString($disp_id);   
        $this->layout = '@app/views/layouts/admin_layout.php';
        $dak_received = Yii::$app->Dakutility->efile_get_dak_received($rec_id,NULL);
//        echo "<pre>";print_r($dak_dispatch);die;
        return $this->render('viewrecdetail', ['menuid'=>$menuid,'dak_received'=>$dak_received]);
    }
    public function actionViewdisdetail() 
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);   
        $disp_id = Yii::$app->utility->decryptString($_GET['disp_id']);
//        $disp_id = Yii::$app->utility->encryptString($disp_id);   
        $this->layout = '@app/views/layouts/admin_layout.php';
        $dak_dispatch = Yii::$app->Dakutility->efile_get_dak_dispatch($disp_id);
//        echo "<pre>";print_r($dak_dispatch);die;
        return $this->render('viewdisdetail', ['menuid'=>$menuid,'dak_dispatch'=>$dak_dispatch]);
    }
    public function actionViewreceiptdisptachentry() 
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);   
        $this->layout = '@app/views/layouts/admin_layout.php';        
        return $this->render('viewreceiptdisptachentry', ['menuid'=>$menuid]);
    }
    public function actionIndex()
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('index', ['menuid'=>$menuid]);
    }
    public function actionDakdispatch()
    {
        
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);  
        $urll=Yii::$app->homeUrl."efile/dakmanagement/index?securekey=$menuid";
        if(isset($_POST) && !empty($_POST))
        {
//            $_POST=$postdata;
            $dispatch_language = $_POST['dispatch_language'];
            $post=$_POST;
            
            if(!empty($post['daknodispatch']) && !empty($post['dispdate']) && !empty($post['disptchfor']) && !empty($post['disporgadd']) && !empty($post['dipatch_mode']) && !empty($post['dept_emp_dropdown_disptach']) && !empty($dispatch_language)){
//                echo "<pre>";print_r($post); die;
                if($dispatch_language == 'Hindi' OR $dispatch_language == 'English'){
                }else{
                    Yii::$app->getSession()->setFlash('danger', 'Invalid Language.');
                    return $this->redirect($urll);
                }
                
                $param_disp_id="";
                $param_status="Dispatch";
                $param_file_id="";
                $param_diapatch_by=Yii::$app->user->identity->employee_code;
                $param_disp_number = trim($post['daknodispatch']);
                $param_disp_date = date("Y-m-d H:i:s",strtotime($post['dispdate']));
                $param_disp_summary = trim($post['dissummary']);
                $param_mode_of_rec = trim($post['dipatch_mode']);
                $param_disp_remarks = NULL;
                $letter_reference_num = trim($post['letter_reference_num']);
                $letter_language = trim($post['letter_language']);
                $param_disp_from_dept = Yii::$app->utility->decryptString($post['dept_emp_dropdown_disptach']);
                $param_disp_from_emp = Yii::$app->utility->decryptString($post['dept_emp_list_disptach']);
                $param_disp_document="";
                $param_is_active="Y";
                if(isset($_FILES['dispatchfile']) && !empty($_FILES['dispatchfile']))
                {
                    $files = $_FILES['dispatchfile'];
                    if(!empty($files['tmp_name']))
                    {
                    $fileResult = $this->uploadFile($files['tmp_name'], $files['name']);
                    if(empty($fileResult))
                    {
                        Yii::$app->getSession()->setFlash('danger', 'Error Found in document upload. Try again or Contact Admin.');
                        return $this->redirect($urll);
                    }
                    $param_disp_document=$fileResult;
                    }
                }
                
                $param_disp_to =$post['disptchfor'];
                $param_org_address = $post['disporgadd'];
                $param_org_state = $post['state_id_dis'];
                $param_org_district = $post['district_id_dis'];
                $param_disp_add_id="";
                $NEWLIST = array();
                $i=1;
                foreach($param_disp_to as $key=>$value)
                {
                    $param_orgaddress = trim($param_org_address[$key]);
                    $state = Yii::$app->utility->decryptString($param_org_state[$key]);
                    $district = Yii::$app->utility->decryptString($param_org_district[$key]);
                    if(!empty($value) && !empty($param_orgaddress) && !empty($state) && !empty($district)){
                        $NEWLIST[$i]['param_disp_to']=$value;
                        $NEWLIST[$i]['param_org_address']=trim($param_org_address[$key]);
                        $NEWLIST[$i]['param_org_state']= $state;
                        $NEWLIST[$i]['param_org_district']=$district;
                        $i++;
                    }

                }
                
                if(empty($NEWLIST)){
                    Yii::$app->getSession()->setFlash('danger', 'Address list not found.');
                    return $this->redirect($urll);
                }
                $model = new EfileDakDispatch();
                $model->disp_number = $param_disp_number;
                $model->disp_date = $param_disp_date;
                $model->file_id = $param_file_id;
                $model->disp_summary = $param_disp_summary;
                $model->entry_language = $dispatch_language;
                $model->disp_remarks = $param_disp_remarks;
                $model->disp_document = $param_disp_document;
                $model->disp_from_dept = $param_disp_from_dept;
                $model->disp_from_emp = $param_disp_from_emp;
                $model->dispatch_by = $param_diapatch_by;
                $model->is_active = "Y";
                $model->status = $param_status;
                $model->dispatch_date = date('Y-m-d H:i:s');
                $model->mode_of_rec = $param_mode_of_rec;
                $model->letter_language = $letter_language;
                $model->letter_reference_num = $letter_reference_num;
                
                if(!$model->validate()){
                    Yii::$app->getSession()->setFlash('danger', 'Enter All Required Fields.');
                    return $this->redirect($urll);
                }else{
                    $model->save();
//                    echo "<pre>";print_r($NEWLIST);
                    $id = $model->disp_id; 
                    foreach($NEWLIST as $n){
                        
                        $Add = "";
                        $Add = new EfileDakDispatchAddress();
                        $Add->disp_id = $id;
                        $Add->disp_to = $n['param_disp_to'];
                        $Add->org_state = $n['param_org_state'];
                        $Add->org_district = $n['param_org_district'];
                        $Add->org_address = $n['param_org_address'];
                        $Add->save();
                    }
                    Yii::$app->getSession()->setFlash('success', ' Dak Dispatch information added successfully');
                    return $this->redirect($urll);
                }
//                die("****");
//                $result = Yii::$app->Dakutility->efile_dak_dispatch($param_disp_id,$param_disp_number,$param_disp_date,$param_file_id,
//            $param_disp_summary,$param_mode_of_rec,$param_disp_remarks,$param_disp_document,$param_disp_from_dept,
//            $param_disp_from_emp,$param_diapatch_by,$param_is_active,$param_status);
//            if($result)
//            {
//                $param_disp_id=$result;
//            }
            
            
            
            
           // echo "<pre>";print_r($NEWLIST); die;
//            foreach($NEWLIST as $key => $value) 
//            {
//                $param_disp_to=$value["param_disp_to"];
//                $param_org_address=$value["param_org_address"];
//                $param_org_state=Yii::$app->utility->decryptString($value["param_org_state"]);
//                $param_org_district=Yii::$app->utility->decryptString($value["param_org_district"]);
//                $param_is_active="Y";
//                $param_disp_add_id="";
//                $result = Yii::$app->Dakutility->efile_dak_dispatch_address($param_disp_add_id,$param_disp_id,$param_disp_to,
//                $param_org_state,$param_org_district,$param_org_address);
//
//            }
            /*
             * Logs
             */
//            $logs['disp_id']=$param_disp_id;
//            $logs['disp_number']=$param_disp_number;
//            $logs['disp_date']=$param_disp_date;
//            $logs['file_id']=$param_file_id;
//            $logs['disp_summary']=$param_disp_summary;
//            $logs['mode_of_rec']=$param_mode_of_rec;
//            $logs['disp_remarks']=$param_disp_remarks;
//            $logs['disp_document']=$param_disp_document;
//            $logs['disp_from_dept']=$param_disp_from_dept;
//            $logs['disp_from_emp']=$param_disp_from_emp;
//            $logs['is_active']=$param_is_active;
//            $logs['forwaded_by']=$param_diapatch_by;
//            $logs['status']=$param_status;
//            $logs['org_address']=$param_org_address;
//            $logs['org_state']=$param_org_state;
//            $logs['org_district']=$param_org_district;
//            $jsonlogs = json_encode($logs);
//            if(empty($result))
//            {
//                Yii::$app->utility->activities_logs('Dak Dispatch', 'efile/dakmanagement/index', NULL, $jsonlogs, "Error Found. Contact Admin.");
//                Yii::$app->getSession()->setFlash('danger', 'Error Found. Contact Admin.');
//                return $this->redirect($urll);
//            }
//            else
//            {
//                Yii::$app->utility->activities_logs('Dak Dispatch', 'efile/dakmanagement/index', NULL, $jsonlogs, "Dak Dispatch information added successfully.");
//                Yii::$app->getSession()->setFlash('success', ' Dak Dispatch information added successfully');
//                return $this->redirect($urll);
//            }
        }
        else
        {
            Yii::$app->getSession()->setFlash('danger', 'Error Found. Contact Admin.');
            return $this->redirect($urll);
        }
        }else{
            die("****");
        }
    }
    public function actionDakreceipt()
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $urll=Yii::$app->homeUrl."efile/dakmanagement/index?securekey=$menuid";
        
        
        if(isset($_POST) && !empty($_POST))
        {
            $daktype = Yii::$app->utility->decryptString($_POST['daktype']);
            if($daktype!="DAKREC"){
                Yii::$app->getSession()->setFlash('danger', 'Invalid Receipt.');
                return $this->redirect($urll);
            }
            $recpt_language = $_POST['recpt_language'];
            $post=$_POST;
            
            if(!empty($post['dakno']) && !empty($post['receiptdate']) && !empty($post['receiptfrom']) && !empty($post['rece_mode']) && !empty($post['orgname']) && !empty($post['dept_emp_dropdown']) && !empty($recpt_language)){
//                echo "$recpt_language<pre>"; print_r($_POST); die;
                
                if($recpt_language == 'Hindi' OR $recpt_language == 'English'){
                }else{
                    Yii::$app->getSession()->setFlash('danger', 'Invalid Language.');
                    return $this->redirect($urll);
                }
                $param_rec_id="";
                $param_status="Forwarded";
                $param_forwaded_by=Yii::$app->user->identity->employee_code;
                $param_dak_number = trim($post['dakno']);
                $param_rec_date = date("Y-m-d H:i:s",strtotime($post['receiptdate']));
                $param_recfrom = trim($post['receiptfrom']);
                $param_mode_of_rec = trim($post['rece_mode']);
                $param_org_address = trim($post['orgname']);
                $param_dak_summary = trim($post['recsummary']);
                $param_dak_remarks = trim($post['recremarks']);
                $param_org_state = Yii::$app->utility->decryptString($post['state_id_rec']);
                $param_org_district = Yii::$app->utility->decryptString($post['district_id_rec']);
                $param_dak_fwd_dept = Yii::$app->utility->decryptString($post['dept_emp_dropdown']);
                $param_dak_fwd_to = Yii::$app->utility->decryptString($post['dept_emp_list_dropdown']);
                $param_is_active="Y";
                $param_dak_document="";
                if(isset($_FILES['recnotefile']) && !empty($_FILES['recnotefile'])){
                    $files = $_FILES['recnotefile'];
                    if(!empty($files['tmp_name']))
                    {
                    $fileResult = $this->uploadFile($files['tmp_name'], $files['name']);
                    if(empty($fileResult))
                    {
                        Yii::$app->getSession()->setFlash('danger', 'Error Found in document upload. Try again or Contact Admin.');
                        return $this->redirect($urll);
                    }
                    $param_dak_document=$fileResult;
                    }
                }
                
                $model = new EfileDakReceived();
                $model->dak_number = $param_dak_number;
                $model->entry_language =  $recpt_language;
                $model->mode_of_rec =  $param_mode_of_rec;
                $model->rec_date =  date('Y-m-d');
                $model->rec_from =  $param_recfrom;
                $model->org_state =  $param_org_state;
                $model->org_district =  $param_org_district;
                $model->org_address =  $param_org_address;
                $model->dak_summary =  $param_dak_summary;
                $model->dak_remarks =  $param_dak_remarks;
                $model->dak_document =  $param_dak_document;
                $model->dak_fwd_dept =  $param_dak_fwd_dept;
                $model->dak_fwd_to =  $param_dak_fwd_to;
                $model->is_active =  "Y";
                $model->status = $param_status;
                $model->forwaded_by = $param_forwaded_by;
                $model->forwarded_date = date('Y-m-d H:i:s');
                $model->save();
                
                
                
//                die("OK");
//                $result = Yii::$app->Dakutility->efile_dak_received($param_rec_id,$param_dak_number,$param_mode_of_rec,$param_rec_date,$param_recfrom, $param_org_state,$param_org_district,$param_org_address,$param_dak_summary,$param_dak_remarks,$param_dak_document, $param_dak_fwd_dept,$param_dak_fwd_to,$param_is_active,$param_status,$param_forwaded_by, $recpt_language);
                
                /*
                 * Logs
                 */
                $logs['rec_id']=$param_rec_id;
                $logs['mode_of_rec']=$param_mode_of_rec;
                $logs['dak_number']=$param_dak_number;
                $logs['rec_date']=$param_rec_date;
                $logs['rec_from']=$param_recfrom;
                $logs['org_state']=$param_org_state;
                $logs['org_district']=$param_org_district;
                $logs['org_address']=$param_org_address;
                $logs['dak_summary']=$param_dak_summary;
                $logs['dak_remarks']=$param_dak_remarks;
                $logs['dak_document']=$param_dak_document;
                $logs['dak_fwd_dept']=$param_dak_fwd_dept;
                $logs['dak_fwd_to']=$param_dak_fwd_to;
                $logs['is_active']=$param_is_active;
                $logs['forwaded_by']=$param_forwaded_by;
                $logs['status']=$param_status;
                $logs['recpt_language']=$recpt_language;
                $jsonlogs = json_encode($logs);
                if(!$model->save())
                {
                    Yii::$app->utility->activities_logs('Dak Received', 'efile/dakmanagement/index', NULL, $jsonlogs, "Error Found. Contact Admin.");
                    Yii::$app->getSession()->setFlash('danger', 'Error Found. Contact Admin.');
                    return $this->redirect($urll);
                }
                else
                {
                    
                    Yii::$app->utility->activities_logs('Dak Received', 'efile/dakmanagement/index', NULL, $jsonlogs, "Dak Received info added successfully.");
                    //Email Configuration
                    $fwd_emp_list =array();
                    $fwd_emp_list[0]['employee_code'] = $param_dak_fwd_to;
                    Yii::$app->Dakutility->sendEmailwithAttachmenttouser(NULL, "E", $fwd_emp_list, Yii::$app->user->identity->e_id);
                
                    Yii::$app->getSession()->setFlash('success', ' Dak Forwarded successfully');
                    return $this->redirect($urll);
                }
            }
            else
            {
                Yii::$app->getSession()->setFlash('danger', 'Error Found. Contact Admin.');
                return $this->redirect($urll);
            }
        }
        $this->layout = '@app/views/layouts/admin_layout.php';
        
        return $this->render('index');
    }

    public function uploadFile($temPth, $Name)
    {
        $createFolder = getcwd().FTS_Documents.Yii::$app->user->identity->e_id;
        if(!file_exists($createFolder))
        {
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
            $returnName = FTS_Documents.Yii::$app->user->identity->e_id."/".$newName;
        }else{
            $returnName = "";
        }
        return $returnName;
    }
    
    public function actionUpdatedistpach(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."efile/dakmanagement/viewreceiptdisptachentry?securekey=$menuid";
        
        if(isset($_POST['postal_amount']) AND !empty($_POST['postal_amount']) AND isset($_POST['postal_date']) AND !empty($_POST['postal_date'])){
            $disp_id = Yii::$app->utility->decryptString($_POST['key']);
            $disp_number = Yii::$app->utility->decryptString($_POST['key1']);
            $postal_amount = trim($_POST['postal_amount']);
            
            if(empty($disp_id) OR empty($disp_number) OR empty($postal_amount)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid params value found.');
                return $this->redirect($url);
            }
            $postal_date = date('Y-m-d', strtotime($_POST['postal_date']));
            if($postal_date == '1970-01-01'){
                Yii::$app->getSession()->setFlash('danger', 'Invalid postal date.');
                return $this->redirect($url);
            }
            
            $param_dak_document="";
            if(isset($_FILES['disp_document']) && !empty($_FILES['disp_document'])){
                $files = $_FILES['disp_document'];
                if(!empty($files['tmp_name']))
                {
                $fileResult = $this->uploadFile($files['tmp_name'], $files['name']);
                if(empty($fileResult))
                {
                    Yii::$app->getSession()->setFlash('danger', 'Error Found in document upload. Try again or Contact Admin.');
                    return $this->redirect($url);
                }
                $param_dak_document=$fileResult;
                }
            }
            
            $model = EfileDakDispatch::find()->where(['disp_id' => $disp_id, 'disp_number'=>$disp_number, 'is_active'=>'Y'])->one();
            $model->disp_document = $param_dak_document;
            $model->postal_amount = $postal_amount;
            $model->postal_date = $postal_date;
            $model->save();
            
            Yii::$app->getSession()->setFlash('success', 'Disptach Details Updated Successfully.');
            return $this->redirect($url);            
        }else{
            Yii::$app->getSession()->setFlash('danger', 'Invalid params found.');
            return $this->redirect($urll);
        }
    }
}
