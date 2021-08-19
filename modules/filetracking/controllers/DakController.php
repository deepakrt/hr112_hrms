<?php
namespace app\modules\filetracking\controllers;
use yii\web\Controller;
use Yii;
class DakController extends Controller
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
        $url = Yii::$app->homeUrl."filetracking/dak?securekey=$menuid";
        if(isset($_POST['Dak']) AND !empty($_POST['Dak']) AND isset($_FILES['docs_path']) AND !empty($_FILES['docs_path'])){
            $post = $_POST['Dak'];
            if($post['submit_type'] == 'D' OR $post['submit_type'] == 'F'){
                $ticket_number =NULL;
                
                $file_refrence_no = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['file_refrence_no']));
                $subject = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['subject']));                
                $meta_keywords = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['meta_keywords']));
                $summary = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['summary']));
                $remarks = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['remarks']));
                $despatch_num = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['despatch_num']));
                $file_date = date('Y-m-d', strtotime($post['file_date']));
                if($file_date == '1970-01-01'){
                    Yii::$app->getSession()->setFlash('danger', 'Invalid File Reference Date.'); 
                    return $this->redirect($url);
                }
                $despatch_date = NULL;
                if(!empty($post['despatch_date'])){
                    $despatch_date = date('Y-m-d', strtotime($post['despatch_date']));
                    if($despatch_date == '1970-01-01'){
                        Yii::$app->getSession()->setFlash('danger', 'Invalid Despatch Date.'); 
                        return $this->redirect($url);
                    }
                }
                
                $category = Yii::$app->utility->decryptString($post['category']);
                $access_level = Yii::$app->utility->decryptString($post['access_level']);
                $priority = Yii::$app->utility->decryptString($post['priority']);
                $is_confidential = Yii::$app->utility->decryptString($post['is_confidential']);
                $doctype1 = Yii::$app->utility->decryptString($post['doc_type']);
                if(empty($category) OR empty($access_level) OR empty($priority) OR empty($is_confidential) OR empty($doctype1)){
                    Yii::$app->getSession()->setFlash('danger', 'Invalid Params Value Found.'); 
                    return $this->redirect($url);
                }
                
                $dakSentTo = "";
                $next_order_num = $group_id =  $send_to_emp = NULL;
                
                if($post['sent_type'] == '1'){
                    $send_to_emp = Yii::$app->utility->decryptString($post['emp_code']);
                    if(empty($send_to_emp)){
                        Yii::$app->getSession()->setFlash('danger', 'Invalid Employee Code.'); 
                        return $this->redirect($url);
                    }
                    $dakSentTo[0]['emp_code'] = $send_to_emp;
                    $is_hierarchy = "N";
                }elseif($post['sent_type'] == '2'){
                    $is_hierarchy = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['is_hierarchy']));
                    $group_id = Yii::$app->utility->decryptString($post['group_id']);
                    if(empty($group_id)){
                        Yii::$app->getSession()->setFlash('danger', 'Invalid Group ID.'); 
                        return $this->redirect($url);
                    }
                    $chkgroup= Yii::$app->fts_utility->fts_getgroupmaster($group_id);
                    if(empty($chkgroup)){
                        Yii::$app->getSession()->setFlash('danger', 'No Group Information Found.'); 
                        return $this->redirect($url);
                    }
                    
                    // Sent Dak using process 
                    if($chkgroup['is_hierarchical'] == 'Y'){
                        $process = Yii::$app->fts_utility->fts_get_group_process($group_id);
                        if(empty($process)){
                            Yii::$app->getSession()->setFlash('danger', 'No Group Process Found.'); 
                            return $this->redirect($url);
                        }
                        $totalProcess = count($process);
                        if($totalProcess > 1){
                            $next_order_num = $process[1]['order_number'];
                        }
                        if($process[0]['role_id'] == '4'){
                            $sentTo = Yii::$app->user->identity->authority1;
                        }elseif($process[0]['role_id'] == '2'){
                            $sentTo = Yii::$app->user->identity->authority2;
                        }else{
                            $role_emp_code = Yii::$app->utility->get_emp_code_with_role_id($process[0]['role_id']);
                            if(empty($role_emp_code)){
                               Yii::$app->getSession()->setFlash('danger', 'Invalid Role Found in Process.'); 
                                return $this->redirect($url); 
                            }
                            $sentTo = $role_emp_code['employee_code'];
                        }
                        $dakSentTo[0]['emp_code'] = $sentTo;
                    }elseif($chkgroup['is_hierarchical'] == 'N'){
                        // Sent Dak using Group members 
                        $groupMembers = Yii::$app->fts_utility->fts_get_group_members($group_id);
                        if(empty($groupMembers)){
                            Yii::$app->getSession()->setFlash('danger', 'Group is empty, contact admin to add members.'); 
                            return $this->redirect($url);
                        }
                        $i=0;
                        foreach($groupMembers as $grp){
                            if(Yii::$app->user->identity->e_id != $grp['employee_code']){
                                $dakSentTo[$i]['emp_code'] = $grp['employee_code'];
                                $i++;
                            }
                            
                        }
                    }
                }elseif($post['sent_type'] == '3'){
                    $is_hierarchy="N";
                    $allemps = Yii::$app->utility->get_employees();
                    if(empty($allemps)){
                        Yii::$app->getSession()->setFlash('danger', 'Employee List Not Found.'); 
                        return $this->redirect($url);
                    }
                    $i=0;
                    foreach($allemps as $all){
                        $empCode = $all['employee_code'];
                        $Super_Admin_Emp_Code = Super_Admin_Emp_Code;
                        if($empCode == Yii::$app->user->identity->e_id){
                        }elseif($empCode == $Super_Admin_Emp_Code){
                        }else{
                           $dakSentTo[$i]['emp_code']= $empCode;
                           $i++;
                        }
                    }
                }else{
                    Yii::$app->getSession()->setFlash('danger', 'Invalid Sent Type.'); 
                    return $this->redirect($url);
                }
//                 echo "Send to <pre>";print_r($dakSentTo); die;
                //List of members to send dak
                if(empty($dakSentTo)){
                    Yii::$app->getSession()->setFlash('danger', 'No Dak ​Recipient Found.'); 
                    return $this->redirect($url);
                }
                $draft_flag = rand(1000000,10000000);
                if($post['submit_type'] == 'D'){
                    $status = "Draft";
                    
                }elseif($post['submit_type'] == 'F'){
                    if(empty($despatch_num) OR empty($despatch_date)){
                        Yii::$app->getSession()->setFlash('danger', 'Enter Despatch Number & Date.'); 
                        return $this->redirect($url);
                    }
                    $numm = Yii::$app->fts_utility->fts_get_new_ticket_num();
                    if($numm < 1){
                        $numm = 1;
                    }
                    $ticket_number = FTS_Ticket_Number.$numm;
                    $status = "Pending";
                }else{
                    Yii::$app->getSession()->setFlash('danger', 'Invalid Submit Type Found.'); 
                    return $this->redirect($url);
                }
                
                //Upload Document
                $name = $_FILES['docs_path']['name'];
                if(empty($name)){
                    Yii::$app->getSession()->setFlash('danger', 'Upload File Scanned Document.'); 
                    return $this->redirect($url);
                }
                $doc_type = $_FILES['docs_path']['type'];
                $tmp_name = $_FILES['docs_path']['tmp_name'];
                if($doc_type == 'application/pdf' OR $doc_type == 'data:binary/octet-stream' OR $doc_type == 'data:application/x-download'){}else{
                    Yii::$app->getSession()->setFlash('danger', 'Invalid Document Found. Only .pdf Allowed'); 
                    return $this->redirect($url);
                }
                $doc_size = $this->formatSizeUnits($_FILES['docs_path']['size']);
                //$doc_size = $_FILES['docs_path']['size'];
                $FTS_Doc_Size = FTS_Doc_Size;
                if($doc_size > $FTS_Doc_Size){
                    Yii::$app->getSession()->setFlash('danger', "Document Size cannot greater then $FTS_Doc_Size MB."); 
                    return $this->redirect($url);
                }
                
                $docs_path = $this->uploadFile($tmp_name, $name);
                if(empty($docs_path)){
                    Yii::$app->getSession()->setFlash('danger', "Document Has Not Uploaded. Try again..."); 
                    return $this->redirect($url);
                }
                
                
                $reply_last_date = NULL;
                $dakSent=0;
                $result = Yii::$app->fts_utility->fts_create_dak($ticket_number, $is_hierarchy, $group_id, $send_to_emp, $file_refrence_no, $file_date, $despatch_num, $despatch_date, $subject, $category, $access_level, $priority, $is_confidential, $reply_last_date, $next_order_num, $meta_keywords, $remarks, $summary, $doctype1, $docs_path, $status, $draft_flag);
                /*
                 * Add Logs
                 */
                $logs['ticket_number']=$ticket_number;
                $logs['is_hierarchy']=$is_hierarchy;
                $logs['group_id']=$group_id;
                $logs['send_to_emp']=$send_to_emp;
                $logs['file_refrence_no']=$file_refrence_no;
                $logs['file_date']=$file_date;
                $logs['despatch_num']=$despatch_num;
                $logs['despatch_date']=$despatch_date;
                $logs['subject']=$subject;
                $logs['category']=$category;
                $logs['access_level']=$access_level;
                $logs['priority']=$priority;
                $logs['is_confidential']=$is_confidential;
                $logs['reply_last_date']=$reply_last_date;
                $logs['next_order_num']=$next_order_num;
                $logs['meta_keywords']=$meta_keywords;
                $logs['remarks']=$remarks;
                $logs['summary']=$summary;
                $logs['doc_type']=$doctype1;
                $logs['docs_path']=$docs_path;
                $logs['status']=$status;
                
                if($post['submit_type'] == 'D'){
                    $jsonlogs = json_encode($logs);
                    if($result != 'No'){
                        Yii::$app->utility->activities_logs("FTS", "filetracking/dak/", $send_to_emp, $jsonlogs, "Dak Created as Draft Successfully.");
                        Yii::$app->getSession()->setFlash('success', 'Dak Created as Draft Successfully.'); 
                        return $this->redirect($url);
                    }else{
                        Yii::$app->utility->activities_logs("FTS", "filetracking/dak/", $send_to_emp, $jsonlogs, "Error Found While Creating Dak as Draft.");
                        Yii::$app->getSession()->setFlash('danger', 'Error Found While Creating Dak as Draft.'); 
                        return $this->redirect($url);
                    }
                }elseif($post['submit_type'] == 'F'){
                    $logs['daksentto']=$dakSentTo;
                    $jsonlogs = json_encode($logs);
                    if($result != 'No'){
                        foreach($dakSentTo as $dak){
                            $empcode = $dak['emp_code'];
                            $check = Yii::$app->fts_utility->fts_add_dak_sent($result, Yii::$app->user->identity->e_id, $empcode, $status);
                            if($check == '1'){ 
                                $dakSent=$dakSent+1;
                            }
                        }
                        if($dakSent > 0){
                            Yii::$app->utility->activities_logs("FTS", "filetracking/dak/", $send_to_emp, $jsonlogs, "Dak Created Successfully.");
                            Yii::$app->getSession()->setFlash('success', 'Dak Created Successfully.'); 
                            return $this->redirect($url);
                        }
                    }else{
                        Yii::$app->utility->activities_logs("FTS", "filetracking/dak/", $send_to_emp, $jsonlogs, "Error Found While Creating Dak.");
                        Yii::$app->getSession()->setFlash('danger', 'Error Found While Creating Dak.'); 
                        return $this->redirect($url);
                    }
                }
                
            }else{
                Yii::$app->getSession()->setFlash('danger', 'Invalid Submit Type.'); 
                return $this->redirect($url);
            }
            
        } 
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('index', ['menuid'=>$menuid]);
    }
    
    public function actionDraft(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."filetracking/dak/draft?securekey=$menuid";
        $this->layout = '@app/views/layouts/admin_layout.php';
        $draftDaks = Yii::$app->fts_utility->fts_get_dak('D', Yii::$app->user->identity->e_id);
        return $this->render('draft', ['menuid'=>$menuid, 'draftDaks'=>$draftDaks]);
    }
    public function actionEditdraft(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."filetracking/dak/draft?securekey=$menuid";
        if(isset($_GET['dak_id']) AND !empty($_GET['dak_id'])){
            $dak_id = Yii::$app->utility->decryptString($_GET['dak_id']);
            if(empty($dak_id)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid Dak ID.'); 
                return $this->redirect($url);
            }
            $dakDetails = Yii::$app->fts_utility->fts_get_dak_detail($dak_id);
            if(empty($dakDetails)){
                Yii::$app->getSession()->setFlash('danger', 'Dak Details Not Found.'); 
                return $this->redirect($url);
            }
            if($dakDetails['status'] != 'Draft'){
                Yii::$app->getSession()->setFlash('danger', 'Dak has been sent.'); 
                return $this->redirect($url);
            }
            $grpProcess = $grpMembers = "";
            if(!empty($dakDetails['send_to_group'])){
                if($dakDetails['is_hierarchical'] == 'Y'){
                    $grpProcess = Yii::$app->fts_utility->fts_get_group_process($dakDetails['send_to_group']);
                }else{
                    $grpMembers = Yii::$app->fts_utility->fts_get_group_members($dakDetails['send_to_group']);;
                }
                
            }
            $this->layout = '@app/views/layouts/admin_layout.php';
            return $this->render('editdraft', [
                'menuid'=>$menuid, 
                'dakDetails'=>$dakDetails, 
                'grpProcess'=>$grpProcess, 
                'grpMembers'=>$grpMembers
            ]);
        }
        Yii::$app->getSession()->setFlash('danger', 'Invalid Params Found.'); 
        return $this->redirect($url);
    }
    public function actionSavedraft(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."filetracking/dak/draft?securekey=$menuid";
//        echo "<pre>";print_r($_POST); die;
        if(isset($_POST['Editdraft']) AND !empty($_POST['Editdraft']) AND isset($_POST['Dak']) AND !empty($_POST['Dak'])){
//            die('1');
            $post = $_POST['Editdraft'];
            $dak_id = Yii::$app->utility->decryptString($post['dak_id']);
            if(empty($dak_id)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid Dak ID.'); 
                return $this->redirect($url);
            }
            
            $dakDetails = Yii::$app->fts_utility->fts_get_dak_detail($dak_id);
            if(empty($dakDetails)){
                Yii::$app->getSession()->setFlash('danger', 'Dak Details Not Found.'); 
                return $this->redirect($url);
            }
            if($dakDetails['status'] != 'Draft'){
                Yii::$app->getSession()->setFlash('danger', 'Dak has been sent.'); 
                return $this->redirect($url);
            }
            
            
            $dakid = Yii::$app->utility->encryptString($dak_id);
            $url = Yii::$app->homeUrl."filetracking/dak/draft?securekey=$menuid&dak_id=$dakid";
            
            $file_refrence_no = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['file_refrence_no']));
            $subject = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['subject']));                
            $meta_keywords = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['meta_keywords']));
            $summary = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['summary']));
            $remarks = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['remarks']));
            $despatch_num = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['despatch_num']));
            $file_date = date('Y-m-d', strtotime($post['file_date']));
            if($file_date == '1970-01-01'){
                Yii::$app->getSession()->setFlash('danger', 'Invalid File Reference Date.'); 
                return $this->redirect($url);
            }
            $despatch_date = NULL;
            if(!empty($post['despatch_date'])){
                $despatch_date = date('Y-m-d', strtotime($post['despatch_date']));
                if($despatch_date == '1970-01-01'){
                    Yii::$app->getSession()->setFlash('danger', 'Invalid Despatch Date.'); 
                    return $this->redirect($url);
                }
            }

            $category = Yii::$app->utility->decryptString($post['category']);
            $access_level = Yii::$app->utility->decryptString($post['access_level']);
            $priority = Yii::$app->utility->decryptString($post['priority']);
            $is_confidential = Yii::$app->utility->decryptString($post['is_confidential']);
//            die($is_confidential);
            $doctype = Yii::$app->utility->decryptString($post['doc_type']);
            if(empty($category) OR empty($access_level) OR empty($priority) OR empty($is_confidential) OR empty($doctype)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid Params Value Found.'); 
                return $this->redirect($url);
            }
            $sentTypeChanged="N";
            $is_hierarchy = $next_order_num = $group_id =  $send_to_emp = NULL;
            $dakSentTo = "";
            if($post['sentdetailchange'] == 'Y'){
                $sentTypeChanged = "Y";
                $postdak = $_POST['Dak'];
                if($postdak['sent_type'] == '1'){
                    $send_to_emp = Yii::$app->utility->decryptString($postdak['emp_code']);
                    if(empty($send_to_emp)){
                        Yii::$app->getSession()->setFlash('danger', 'Invalid Employee Code.'); 
                        return $this->redirect($url);
                    }
                    $dakSentTo[0]['emp_code'] = $send_to_emp;
                    $is_hierarchy = "N";
                }elseif($postdak['sent_type'] == '2'){
                    $is_hierarchy = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $postdak['is_hierarchy']));
                    $group_id = Yii::$app->utility->decryptString($postdak['group_id']);
                    if(empty($group_id)){
                        Yii::$app->getSession()->setFlash('danger', 'Invalid Group ID.'); 
                        return $this->redirect($url);
                    }
                    $chkgroup= Yii::$app->fts_utility->fts_getgroupmaster($group_id);
                    if(empty($chkgroup)){
                        Yii::$app->getSession()->setFlash('danger', 'No Group Information Found.'); 
                        return $this->redirect($url);
                    }
                    
                    // Sent Dak using process 
                    if($chkgroup['is_hierarchical'] == 'Y'){
                        $process = Yii::$app->fts_utility->fts_get_group_process($group_id);
                        if(empty($process)){
                            Yii::$app->getSession()->setFlash('danger', 'No Group Process Found.'); 
                            return $this->redirect($url);
                        }
                        $totalProcess = count($process);
                        if($totalProcess > 1){
                            $next_order_num = $process[1]['order_number'];
                        }
                        if($process[0]['role_id'] == '4'){
                            $sentTo = Yii::$app->user->identity->authority1;
                        }elseif($process[0]['role_id'] == '2'){
                            $sentTo = Yii::$app->user->identity->authority2;
                        }else{
                            $role_emp_code = Yii::$app->utility->get_emp_code_with_role_id($process[0]['role_id']);
                            if(empty($role_emp_code)){
                               Yii::$app->getSession()->setFlash('danger', 'Invalid Role Found in Process.'); 
                                return $this->redirect($url); 
                            }
                            $sentTo = $role_emp_code['employee_code'];
                        }
                        $dakSentTo[0]['emp_code'] = $sentTo;
                    }elseif($chkgroup['is_hierarchical'] == 'N'){
                        // Sent Dak using Group members 
                        $groupMembers = Yii::$app->fts_utility->fts_get_group_members($group_id);
                        if(empty($groupMembers)){
                            Yii::$app->getSession()->setFlash('danger', 'Group is empty, contact admin to add members.'); 
                            return $this->redirect($url);
                        }
                        $i=0;
                        foreach($groupMembers as $grp){
                            $dakSentTo[$i]['emp_code'] = $grp['employee_code'];
                            $i++;
                        }
                    }
                }elseif($post['sent_type'] == '3'){
                    $is_hierarchy="N";
                    $allemps = Yii::$app->utility->get_employees();
                    if(empty($allemps)){
                        Yii::$app->getSession()->setFlash('danger', 'Employee List Not Found.'); 
                        return $this->redirect($url);
                    }
                    $i=0;
                    foreach($allemps as $all){
                        $empCode = $all['employee_code'];
                        $Super_Admin_Emp_Code = Super_Admin_Emp_Code;
                        if($empCode == Yii::$app->user->identity->e_id){
                        }elseif($empCode == $Super_Admin_Emp_Code){
                        }else{
                           $dakSentTo[$i]['emp_code']= $empCode;
                           $i++;
                        }
                    }
                }else{
                    Yii::$app->getSession()->setFlash('danger', 'Invalid Sent Type.'); 
                    return $this->redirect($url);
                }
                //List of members to send dak
                if(empty($dakSentTo)){
                    Yii::$app->getSession()->setFlash('danger', 'No Dak ​Recipient Found.'); 
                    return $this->redirect($url);
                }
            }else{
                /*
                 * If Sent Type Not Changed
                 */
                    if($post['sent_type'] == '1'){
                        $send_to_emp = Yii::$app->utility->decryptString($post['emp_code']);
                        if(empty($send_to_emp)){
                            Yii::$app->getSession()->setFlash('danger', 'Invalid Employee Code.'); 
                            return $this->redirect($url);
                        }
                        $dakSentTo[0]['emp_code'] = $send_to_emp;
                        $is_hierarchy = "N";
                    }elseif($post['sent_type'] == '2'){
//                        echo "<pre>";print_r($post); die;
                        $is_hierarchy = Yii::$app->utility->decryptString($post['is_hierarchy']); 
                        if(empty($is_hierarchy)){
                            Yii::$app->getSession()->setFlash('danger', 'Invalid Hierarchy Found..'); 
                            return $this->redirect($url);
                        }
                        if(empty($is_hierarchy)){
                            Yii::$app->getSession()->setFlash('danger', 'Invalid Hierarchy Found.'); 
                            return $this->redirect($url);
                        }
                        $group_id = Yii::$app->utility->decryptString($post['send_to_group']);
                        
                        if(empty($group_id)){
                            Yii::$app->getSession()->setFlash('danger', 'Invalid Group ID.'); 
                            return $this->redirect($url);
                        }
                        $chkgroup= Yii::$app->fts_utility->fts_getgroupmaster($group_id);
                        if(empty($chkgroup)){
                            Yii::$app->getSession()->setFlash('danger', 'No Group Information Found.'); 
                            return $this->redirect($url);
                        }

                        // Sent Dak using process 
                        if($chkgroup['is_hierarchical'] == 'Y'){
                            $process = Yii::$app->fts_utility->fts_get_group_process($group_id);
                            if(empty($process)){
                                Yii::$app->getSession()->setFlash('danger', 'No Group Process Found.'); 
                                return $this->redirect($url);
                            }
                            $totalProcess = count($process);
                            if($totalProcess > 1){
                                $next_order_num = $process[1]['order_number'];
                            }
                            if($process[0]['role_id'] == '4'){
                                $sentTo = Yii::$app->user->identity->authority1;
                            }elseif($process[0]['role_id'] == '2'){
                                $sentTo = Yii::$app->user->identity->authority2;
                            }else{
                                $role_emp_code = Yii::$app->utility->get_emp_code_with_role_id($process[0]['role_id']);
                                if(empty($role_emp_code)){
                                   Yii::$app->getSession()->setFlash('danger', 'Invalid Role Found in Process.'); 
                                    return $this->redirect($url); 
                                }
                                $sentTo = $role_emp_code['employee_code'];
                            }
                            $dakSentTo[0]['emp_code'] = $sentTo;
                        }elseif($chkgroup['is_hierarchical'] == 'N'){
                            // Sent Dak using Group members 
                            $groupMembers = Yii::$app->fts_utility->fts_get_group_members($group_id);
                            if(empty($groupMembers)){
                                Yii::$app->getSession()->setFlash('danger', 'Group is empty, contact admin to add members.'); 
                                return $this->redirect($url);
                            }
                            $i=0;
                            foreach($groupMembers as $grp){
                                $dakSentTo[$i]['emp_code'] = $grp['employee_code'];
                                $i++;
                            }
                        }
                    }elseif($post['sent_type'] == '3'){
                        $is_hierarchy="N";
                        $allemps = Yii::$app->utility->get_employees();
                        if(empty($allemps)){
                            Yii::$app->getSession()->setFlash('danger', 'Employee List Not Found.'); 
                            return $this->redirect($url);
                        }
                        $i=0;
                        foreach($allemps as $all){
                            $empCode = $all['employee_code'];
                            $Super_Admin_Emp_Code = Super_Admin_Emp_Code;
                            if($empCode == Yii::$app->user->identity->e_id){
                            }elseif($empCode == $Super_Admin_Emp_Code){
                            }else{
                               $dakSentTo[$i]['emp_code']= $empCode;
                               $i++;
                        }
                    }
                    }else{
                        Yii::$app->getSession()->setFlash('danger', 'Invalid Sent Type.'); 
                        return $this->redirect($url);
                    }
                /*
                 * If Sent Type Not Changed
                 */
            }
            if(empty($dakSentTo)){
                Yii::$app->getSession()->setFlash('danger', 'Employee list not found. Contact Admin.'); 
                return $this->redirect($url);
            }
            $isdocchange = "N";
            $ticket_number = $draft_flag = NULL;
//            echo "1. $sentTypeChanged <br>";
//            echo "2. $isdocchange <br>";
//            echo "3. $dak_id <br>";
//            echo "4. $ticket_number <br>";
//            echo "5. $is_hierarchy <br>";
//            echo "6. $group_id <br>";
//            echo "7. $send_to_emp <br>";
           
            $draft_flag = rand(1000000,10000000);
            if($post['submit_type'] == 'D'){
                $status = "Draft";
                
            }elseif($post['submit_type'] == 'F'){
                if(empty($despatch_num) OR empty($despatch_date)){
                    Yii::$app->getSession()->setFlash('danger', 'Enter Despatch Number & Date.'); 
                    return $this->redirect($url);
                }
                $numm = Yii::$app->fts_utility->fts_get_new_ticket_num();
                if($numm < 1){
                    $numm = 1;
                }
                $ticket_number = FTS_Ticket_Number.$numm;
                $status = "Pending";
            }else{
                Yii::$app->getSession()->setFlash('danger', 'Invalid Submit Type Found.'); 
                return $this->redirect($url);
            }
            
            
            if($post['isdocchange'] == 'Y'){
                if(isset($_FILES['docs_path']) AND !empty($_FILES['docs_path'])){
                    //Upload Document
                    $name = $_FILES['docs_path']['name'];
                    if(empty($name)){
                        Yii::$app->getSession()->setFlash('danger', 'Upload File Scanned Document.'); 
                        return $this->redirect($url);
                    }
                    $doctype = $_FILES['docs_path']['type'];
                    $tmp_name = $_FILES['docs_path']['tmp_name'];
                    if($doctype == 'application/pdf' OR $doctype == 'data:binary/octet-stream' OR $doctype == 'data:application/x-download'){}else{
                        Yii::$app->getSession()->setFlash('danger', 'Invalid Document Found. Only .pdf Allowed'); 
                        return $this->redirect($url);
                    }
                    $doc_size = $this->formatSizeUnits($_FILES['docs_path']['size']);
                    //$doc_size = $_FILES['docs_path']['size'];
                    $FTS_Doc_Size = FTS_Doc_Size;
                    if($doc_size > $FTS_Doc_Size){
                        Yii::$app->getSession()->setFlash('danger', "Document Size cannot greater then $FTS_Doc_Size MB."); 
                        return $this->redirect($url);
                    }
                    
                    $dak_docs_id = Yii::$app->utility->decryptString($post['dak_docs_id']);
                    if(empty($dak_docs_id)){
                        Yii::$app->getSession()->setFlash('danger', 'Invalid Doc Path ID.'); 
                        return $this->redirect($url);
                    }
                    $docs_path = $this->uploadFile($tmp_name, $name);
                    if(empty($docs_path)){
                        Yii::$app->getSession()->setFlash('danger', "Document Has Not Uploaded. Try again..."); 
                        return $this->redirect($url);
                    }else{
                        /*Remove Older File*/
                        $olddocspath = Yii::$app->utility->decryptString($post['olddocspath']);
                        if(empty($olddocspath)){
                            Yii::$app->getSession()->setFlash('danger', 'Invalid Exits File Path.'); 
                            return $this->redirect($url);
                        }
                        $isremove = Yii::$app->fts_utility->fts_remove_dak_doc($dak_docs_id, $dak_id);
                        if($isremove != '1'){
                            $docs = getcwd().$docs_path;
                            @unlink($path);
                            Yii::$app->getSession()->setFlash('danger', "Document Has Not Deleted. Contact Admin"); 
                            return $this->redirect($url);
                        }
                        $path = getcwd().$olddocspath;
                        @unlink($path);
                        $isdocchange = "Y";
                    }
                    
                }else{
                    Yii::$app->getSession()->setFlash('danger', 'Invalid Files Params Found.'); 
                    return $this->redirect($url); 
                }
            }else{
                $olddocspath = Yii::$app->utility->decryptString($post['olddocspath']);
                $dak_docs_id = Yii::$app->utility->decryptString($post['dak_docs_id']);
                if(empty($olddocspath) OR empty($dak_docs_id)){
                    Yii::$app->getSession()->setFlash('danger', 'Invalid Exits File Path OR Invalid Doc Path ID.'); 
                    return $this->redirect($url);
                }
                $docs_path = $olddocspath;
            }
            $reply_last_date = NULL;
            $dakSent=0;
			// echo "111<pre>";print_r($dakSentTo); die;
            $result = Yii::$app->fts_utility->fts_update_dak($sentTypeChanged, $isdocchange, $dak_id, $ticket_number, $is_hierarchy, $group_id, $send_to_emp, $file_refrence_no, $file_date, $despatch_num, $despatch_date, $subject, $category, $access_level, $priority, $is_confidential, $meta_keywords, $remarks, $summary, $next_order_num, $status, $draft_flag, $doctype, $docs_path);
            
            /*
             * Add Log
             */
            $logs['sentTypeChanged'] = $sentTypeChanged;
            $logs['isdocchange'] = $isdocchange;
            $logs['dak_id'] = $dak_id;
            $logs['ticket_number'] = $ticket_number;
            $logs['is_hierarchy'] = $is_hierarchy;
            $logs['group_id'] = $group_id;
            $logs['send_to_emp'] = $send_to_emp;
            $logs['file_refrence_no'] = $file_refrence_no;
            $logs['file_date'] = $file_date;
            $logs['despatch_num'] = $despatch_num;
            $logs['despatch_date'] = $despatch_date;
            $logs['subject'] = $subject;
            $logs['category'] = $category;
            $logs['access_level'] = $access_level;
            $logs['priority'] = $priority;
            $logs['is_confidential'] = $is_confidential;
            $logs['meta_keywords'] = $meta_keywords;
            $logs['remarks'] = $remarks;
            $logs['summary'] = $summary;
            $logs['next_order_num'] = $next_order_num;
            $logs['status'] = $status;
            $logs['doctype'] = $doctype;
            $logs['docs_path'] = $docs_path;
            
            if($post['submit_type'] == 'D'){
                $jsonlogs = json_encode($logs);
                if($result == '1'){
                    Yii::$app->utility->activities_logs("FTS", "filetracking/dak/savedraft", $send_to_emp, $jsonlogs, "Dak Updated as Draft Successfully.");
                    Yii::$app->getSession()->setFlash('success', 'Dak Updated as Draft Successfully.'); 
                    return $this->redirect($url);
                }else{
                    Yii::$app->utility->activities_logs("FTS", "filetracking/dak/savedraft", $send_to_emp, $jsonlogs, "Error Found While Updating Dak as Draft.");
                    Yii::$app->getSession()->setFlash('danger', 'Error Found While Updating Dak as Draft.'); 
                    return $this->redirect($url);
                }
            }elseif($post['submit_type'] == 'F'){
                
                if($result == '1'){
                    foreach($dakSentTo as $dak){
                        $empcode = $dak['emp_code'];
                        $check = Yii::$app->fts_utility->fts_add_dak_sent($dak_id, Yii::$app->user->identity->e_id, $empcode, $status);
                        if($check == '1'){ 
                            $dakSent=$dakSent+1;
                        }
                    }
                    if($dakSent > 0){
                        $logs['daksentto']=$dakSentTo;
                        $jsonlogs = json_encode($logs);
                        Yii::$app->utility->activities_logs("FTS", "filetracking/dak/savedraft", $send_to_emp, $jsonlogs, "Dak Sent Successfully from Draft Dak.");
                        Yii::$app->getSession()->setFlash('success', 'Dak Sent Successfully.'); 
                        return $this->redirect($url);
                    }
                }else{
                    $jsonlogs = json_encode($logs);
                    Yii::$app->utility->activities_logs("FTS", "filetracking/dak/savedraft", $send_to_emp, $jsonlogs, "Error Found While Updating Dak as Draft.");
                    Yii::$app->getSession()->setFlash('danger', 'Error Found While Updating Dak.'); 
                    return $this->redirect($url);
                }
            }
        }else{
            Yii::$app->getSession()->setFlash('danger', '2 Invalid Params Found.'); 
            return $this->redirect($url);
        }
        
        
    }
    
    public function actionGetdeptemp(){
        if(isset($_GET['dept_id']) AND !empty($_GET['dept_id'])){
            $allemps = Yii::$app->utility->get_dept_emp($_GET['dept_id']);
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
            $result['Status']= 'FF';
            $result['Res']= 'Invalid Params Found';
            echo json_encode($result); die;
        }
    }
    public function actionGetgroups(){
        if(isset($_GET['typee']) AND !empty($_GET['typee'])){
            $allgrps = Yii::$app->fts_utility->fts_getgroupmaster(NULL);
//            echo "<pre>";print_r($allgrps);
//            die($_GET['typee']);
            if(empty($allgrps)){
                $result['Status']= 'FF';
                $result['Res']= 'No Group Found';
                echo json_encode($result); die;
            }
            $html = "<option value=''>Select Group</option>";
            $chk = false;
            foreach($allgrps as $g){
                if($g['is_hierarchical'] == $_GET['typee']){
                    $datatype = $g['is_hierarchical'];
                    $group_id = Yii::$app->utility->encryptString($g['group_id']);
                    $group_name = $g['group_name'];
                    $html .= "<option data-hy='$datatype' title='$group_name' value='$group_id'>$group_name</option>";
                    $chk = true;
                }
            }
            if(empty($chk)){
                $result['Status']= 'SS';
                $result['Res']= 'No Group Found';
                echo json_encode($result); die;
            }
            $result['Status']= 'SS';
            $result['Res']= $html;
            echo json_encode($result); die;
        }else{
            $result['Status']= 'FF';
            $result['Res']= 'Invalid Params Found';
            echo json_encode($result); die;
        }
    }
    public function actionGetgrouplist(){
        if(isset($_GET['group_id']) AND !empty($_GET['group_id']) AND isset($_GET['is_hy']) AND !empty($_GET['is_hy'])){
            $group_id = Yii::$app->utility->decryptString($_GET['group_id']);
            $is_hy = $_GET['is_hy'];
            if(empty($group_id)){
                $result['Status']= 'FF';
                $result['Res']= 'Invalid Group ID';
                echo json_encode($result); die;
            }
            if($is_hy == 'Y'){
                $process = Yii::$app->fts_utility->fts_get_group_process($group_id);
                if(empty($process)){
                    $result['Status']= 'FF';
                    $result['Res']= 'No Process Record Found';
                    echo json_encode($result); die;
                }
                $html = "<p><b><u>Hierarchy</u></b></p>";
                foreach($process as $p){
                    $role = $p['role'];
                    $html .= "<p>- $role</p>";
                }
                $result['Status']= 'SS';
                $result['Res']= $html;
                echo json_encode($result); die;
            }elseif($is_hy == 'N'){
                $grpMembers = Yii::$app->fts_utility->fts_get_group_members($group_id);
                if(empty($grpMembers)){
                    $result['Status']= 'FF';
                    $result['Res']= 'Group Members List Not Found';
                    echo json_encode($result); die;
                }
                $html = "<p><b><u>Group Members</u></b></p>";
                foreach($grpMembers as $p){
                    $emp_name = $p['emp_name'].", ".$p['desg_name'];
                    $html .= "<p>- $emp_name</p>";
                }
                $result['Status']= 'SS';
                $result['Res']= $html;
                echo json_encode($result); die;
            }else{
                $result['Status']= 'FF';
                $result['Res']= 'Invalid Hierarchy Type Found';
                echo json_encode($result); die;
            }
        }else{
            $result['Status']= 'FF';
            $result['Res']= 'Invalid Params Found';
            echo json_encode($result); die;
        }
    }
    
    function formatSizeUnits($bytes)
    {
        $kbs = $bytes / 1024;
        $mbs = $kbs / 1024;
        $mbs = round($mbs);
        return $mbs;
    }
    public function uploadFile($temPth, $Name){
        $info = new \SplFileInfo($Name);
        $ext = $info->getExtension();
        $FTS_Documents = FTS_Documents.Yii::$app->user->identity->e_id."/";
        $createFolder = getcwd().$FTS_Documents;
        if(!file_exists($createFolder)){
            mkdir($createFolder, 0777, true);
//            chmod($createFolder, 0777);
        }
        $random_number = mt_rand(100000, 999999);
        $newName = $random_number.strtotime(date('Y-m-d H:i:s')).".$ext";
        $finalName = $createFolder.$newName;
//        echo "$Name <br>$temPth <br>$finalName"; die;
        $fileUploadedCheck = false;
        if(move_uploaded_file($temPth,$finalName)){
            chmod($finalName, 0777);
            $fileUploadedCheck = true;
        }

        if(!empty($fileUploadedCheck)){
            $returnName = $FTS_Documents.$newName;
        }else{
            $returnName = "";
        }
        return $returnName;
    }
    
    public function actionOutbox(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $this->layout = '@app/views/layouts/admin_layout.php';
        $draftDaks = Yii::$app->fts_utility->fts_get_dak('O', Yii::$app->user->identity->e_id);
        return $this->render('outbox', ['menuid'=>$menuid, 'draftDaks'=>$draftDaks]);
    }
    public function actionViewoutboxdak(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."filetracking/dak/outbox?securekey=$menuid";
        if(isset($_GET['dak_id']) AND !empty($_GET['dak_id'])){
            $dak_id = Yii::$app->utility->decryptString($_GET['dak_id']);
            if(empty($dak_id)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid Dak ID.'); 
                return $this->redirect($url);
            }
            
            //Check Dak is related to login user
            $checkdak = Yii::$app->fts_utility->fts_auth_for_view($dak_id);
            if($checkdak == '2'){
                return $this->redirect(Yii::$app->homeUrl."filetracking/dak?securekey=$menuid");
            }
            $dakDetail = Yii::$app->fts_utility->fts_get_dak_detail($dak_id);
            //echo "<pre>";print_r($dakDetail);die;
            if(empty($dakDetail)){
                Yii::$app->getSession()->setFlash('danger', 'Dak Detail Not Found.'); 
                return $this->redirect($url);
            }
            if($dakDetail['send_from'] != Yii::$app->user->identity->e_id){
                //Yii::$app->getSession()->setFlash('danger', 'Dak Detail Not Found.'); 
                return $this->redirect($url);
            }
            $grpProcess = $grpMembers = "";
            if(!empty($dakDetail['send_to_group'])){
                if($dakDetail['is_hierarchical'] == 'Y'){
                    $grpProcess = Yii::$app->fts_utility->fts_get_group_process($dakDetail['send_to_group']);
                }else{
                    $grpMembers = Yii::$app->fts_utility->fts_get_group_members($dakDetail['send_to_group']);;
                }
            }
            $this->layout = '@app/views/layouts/admin_layout.php';
            return $this->render('viewoutboxdak', ['menuid'=>$menuid, 'dakDetails'=>$dakDetail, 'grpProcess'=>$grpProcess, 'grpMembers'=>$grpMembers]);
        }else{
            Yii::$app->getSession()->setFlash('danger', 'Invalid Params Found.'); 
            return $this->redirect($url);
        }
    }
    public function actionInbox(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."filetracking/dak/draft?securekey=$menuid";
        $this->layout = '@app/views/layouts/admin_layout.php';
        $inboxDaks = Yii::$app->fts_utility->fts_get_dak('I', Yii::$app->user->identity->e_id);
        return $this->render('inbox', ['menuid'=>$menuid, 'inboxDaks'=>$inboxDaks]);
    }
    public function actionViewinboxdak(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."filetracking/dak/inbox?securekey=$menuid";
        if(isset($_GET['dak_id']) AND !empty($_GET['dak_id'])){
            $dak_id = Yii::$app->utility->decryptString($_GET['dak_id']);
            if(empty($dak_id)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid Dak ID.'); 
                return $this->redirect($url);
            }
            //Check Dak is related to login user
            $checkdak = Yii::$app->fts_utility->fts_auth_for_view($dak_id);
            if($checkdak == '2'){
                return $this->redirect(Yii::$app->homeUrl."filetracking/dak?securekey=$menuid");
            }
            $dakDetail = Yii::$app->fts_utility->fts_get_dak_detail($dak_id);
            //echo "<pre>";print_r($dakDetail);die;
            if(empty($dakDetail)){
                Yii::$app->getSession()->setFlash('danger', 'Dak Detail Not Found.'); 
                return $this->redirect($url);
            }
            
            $grpProcess = $grpMembers = "";
            if(!empty($dakDetail['send_to_group'])){
                if($dakDetail['is_hierarchical'] == 'Y'){
                    $grpProcess = Yii::$app->fts_utility->fts_get_group_process($dakDetail['send_to_group']);
                }else{
                    $grpMembers = Yii::$app->fts_utility->fts_get_group_members($dakDetail['send_to_group']);;
                }
            }
//            die('ok');
            $this->layout = '@app/views/layouts/admin_layout.php';
            return $this->render('viewinboxdak', ['menuid'=>$menuid, 'dakDetails'=>$dakDetail, 'grpProcess'=>$grpProcess, 'grpMembers'=>$grpMembers]);
        }else{
            Yii::$app->getSession()->setFlash('danger', 'Invalid Params Found.'); 
            return $this->redirect($url);
        }
    }
    
    public function actionAddnote(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."filetracking/dak/inbox?securekey=$menuid";
        if(isset($_POST['Note']) AND !empty($_POST['Note'])){
            $post = $_POST['Note'];
            
            $dak_id = Yii::$app->utility->decryptString($post['dak_id']);
            if(empty($dak_id)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid Dak ID.'); 
                return $this->redirect($url);
            }
            //Check Dak is related to login user
            $checkdak = Yii::$app->fts_utility->fts_auth_for_view($dak_id);
            if($checkdak == '2'){
                return $this->redirect(Yii::$app->homeUrl."filetracking/dak?securekey=$menuid");
            }
            $viewtype = Yii::$app->utility->decryptString($post['viewtype']);
            if(empty($dak_id)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected.'); 
                return $this->redirect($url);
            }
            
            $dakid = Yii::$app->utility->encryptString($dak_id);
            if($viewtype == 'O'){
                $url = Yii::$app->homeUrl."filetracking/dak/viewoutboxdak?securekey=$menuid&dak_id=$dakid";
            }elseif($viewtype == 'I'){
                $url = Yii::$app->homeUrl."filetracking/dak/viewinboxdak?securekey=$menuid&dak_id=$dakid";
            }
//            die($url);
            $newnote = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['newnote']));
            if(empty($newnote)){
                Yii::$app->getSession()->setFlash('danger', 'Note cannot empty.'); 
                return $this->redirect($url);
            }
            $docu_type = $docs_path=NULL;
            if(isset($_FILES['notefile']['name']) AND !empty($_FILES['notefile']['name'])){
                $docu_type = Yii::$app->utility->decryptString($post['docu_type']);
                if(empty($docu_type)){
                    Yii::$app->getSession()->setFlash('danger', 'Invalid Document Type.'); 
                    return $this->redirect($url);
                }
                //Upload Document
                $name = $_FILES['notefile']['name'];
                if(empty($name)){
                    Yii::$app->getSession()->setFlash('danger', 'Upload File Scanned Document.'); 
                    return $this->redirect($url);
                }
                $doc_type = $_FILES['notefile']['type'];
                $tmp_name = $_FILES['notefile']['tmp_name'];
                if($doc_type == 'application/pdf' OR $doc_type == 'data:binary/octet-stream' OR $doc_type == 'data:application/x-download'){}else{
                    Yii::$app->getSession()->setFlash('danger', 'Invalid Document Found. Only .pdf Allowed'); 
                    return $this->redirect($url);
                }
                $doc_size = $this->formatSizeUnits($_FILES['notefile']['size']);
                //$doc_size = $_FILES['docs_path']['size'];
                $FTS_Doc_Size = FTS_Doc_Size;
                if($doc_size > $FTS_Doc_Size){
                    Yii::$app->getSession()->setFlash('danger', "Document Size cannot greater then $FTS_Doc_Size MB."); 
                    return $this->redirect($url);
                }

                $docs_path = $this->uploadFile($tmp_name, $name);
                if(empty($docs_path)){
                    Yii::$app->getSession()->setFlash('danger', "Document Has Not Uploaded. Try again..."); 
                    return $this->redirect($url);
                }
            }
            
            $result = Yii::$app->fts_utility->fts_add_dak_notes($dak_id, $newnote, $docs_path, 'Note', NULL);
            
            /*
             * Add Logs
             */
            $logs['dak_id']=$dak_id;
            $logs['newnote']=$newnote;
            $logs['docs_path']=$docs_path;
            $logs['action_type']='Note';
            $logs['fwd_to']=NULL;
            $logs['API_result']=$result;
            $jsonlogs = json_encode($logs);
            
            if($result == '1'){
                
                Yii::$app->utility->activities_logs("FTS", "filetracking/dak/addnote", NULL, $jsonlogs, "Note added successfully");
                
                Yii::$app->getSession()->setFlash('success', "Note added successfully."); 
                return $this->redirect($url);
            }else{
                Yii::$app->utility->activities_logs("FTS", "filetracking/dak/addnote", NULL, $jsonlogs, "Note did\'nt added. Contact Admin");
                
                Yii::$app->getSession()->setFlash('danger', "Note did\'nt added. Contact Admin"); 
                return $this->redirect($url);
            }
        }
        Yii::$app->getSession()->setFlash('danger', 'Invalid Params Found.'); 
        return $this->redirect($url);
    }
    
    public function actionGetnotes(){
        if(isset($_GET['dak_id']) AND !empty($_GET['dak_id'])){
            $dak_id = Yii::$app->utility->decryptString($_GET['dak_id']);
            if(empty($dak_id)){
                $result['Status']='FF';
                $result['Res']='Invalid Params Value Found';
                echo json_encode($result); die;
            }
            $records = Yii::$app->fts_utility->fts_get_dak_notes($dak_id);
            $html="";
            if(!empty($records)){
                foreach($records as $rec){
                    $emp_name = $rec['emp_name'];
                    $desg_name = $rec['desg_name'];
                    $dept_name = $rec['dept_name'];
                    $note = $rec['note'];
                    $note_doc = Yii::$app->homeUrl.$rec['note_doc'];
                    $note_date = date('d-m-Y H:i:s', strtotime($rec['note_date']));
                    $html .= "<div class='row'>
                        <div class='col-sm-8'><p ><b>Name : </b> $emp_name, $desg_name ($dept_name)</p></div>
                        <div class='col-sm-4'><p style='color: red;'><b>Note Dated : </b> $note_date</p></div>
                    </div>
                    <hr>";
                    
                    $html .= "<div class='row'>
                    <div class='col-sm-12'><p style='text-align:justify;'>$note</p></div><br>";
                        if(!empty($rec['note_doc'])){
                        $html .= "<div class='col-sm-12 text-right'>
                            <a href='$note_doc' class='linkcolor'>View Attachment</a>
                        </div>";
                        }
                    $html .= "</div>
                    <hr>"; 
                }
                $result['Status']='SS';
                $result['Res']=$html;
                echo json_encode($result); die;
            }else{
                $result['Status']='SS';
                $result['Res']='<div class="alert alert-danger text-align">No Record Found</div>';
                echo json_encode($result); die;
            }
        }else{
            $result['Status']='FF';
            $result['Res']='Invalid Params Found';
            echo json_encode($result); die;
        }
    }
    public function actionDownloaddak(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."filetracking/dak/inbox?securekey=$menuid";
        if(isset($_GET['dak_id']) AND !empty($_GET['dak_id'])){
            $dak_id = Yii::$app->utility->decryptString($_GET['dak_id']);
            if(empty($dak_id)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid Params Value Found.'); 
                return $this->redirect($url);
            }
            //Check Dak is related to login user
            $checkdak = Yii::$app->fts_utility->fts_auth_for_view($dak_id);
            if($checkdak == '2'){
                return $this->redirect(Yii::$app->homeUrl."filetracking/dak?securekey=$menuid");
            }
            $dakid = Yii::$app->utility->encryptString($dak_id);
            $url = Yii::$app->homeUrl."filetracking/dak/viewinboxdak?securekey=$menuid&dak_id=$dakid";
            
            $dakDetail = Yii::$app->fts_utility->fts_get_dak_detail($dak_id);
//            echo "<pre>";print_r($dakDetail); die;
            if(empty($dakDetail)){
                Yii::$app->getSession()->setFlash('danger', 'Dak Details Not Found.'); 
                return $this->redirect($url);
            }
            $notes = Yii::$app->fts_utility->fts_get_dak_notes($dak_id);
            $ticket_number = $dakDetail['ticket_number'];
            require_once './mpdf/mpdf.php';
            $mpdf = new \mPDF();
            $CompanyName = CompanyName;
            date_default_timezone_set('Asia/Kolkata');
            $printdt = date('d-m-Y H:i:s');
            $fonts="font-size:10px;color:lightgrey;";
            $footer = "<hr style='color:lightgrey'><div style='width:100%'>
                    <div style='width:33%;float:left;$fonts text-align:left;'>$ticket_number</div>
                    <div style='width:32%;float:left;$fonts text-align:center'>Page {PAGENO} of {nb}</div>
                    <div style='width:33%;float:left;text-align:right;$fonts'>Printed On $printdt</div>
                    <div style='clear:both;'></div>
                    </div>";
            $mpdf->SetHTMLFooter($footer);
            $mpdf->SetImportUse(true);
            $html = '';
            $header = '<h4 style="text-align:center;margin:0px;"> Notes : '.ORGANAZATION_NAME.': '.ORGANAZATION_CENTRE.'</h4>';
            $notehtml="";
            $notefiles=array();
            $i=0;
            if(!empty($notes)){
                foreach($notes as $note){ 
                    $emp_name = trim($note['emp_name']);
                    $desg_name = $note['desg_name'];
                    $dept_name = $note['dept_name'];
                    $note_doc = $note['note_doc'];
                    $note_date = date('d-m-Y H:i:s', strtotime($note['note_date']));
                    $note = $note['note'];
                    $notehtml .="<div style='width:100%;margin-bottom:15px;'>
                            <div style='width:60%;float:left;'><b>$emp_name, $desg_name ($dept_name)</b></div>
                            <div style='width:39%;float:right;text-align:right;color:red;'><b>Note Dated: </b>$note_date</div>
                            </div><div style='clear:both;'></div>
                            <div style='width:100%;margin-bottom:15px;text-align:justify;'>$note</div><hr>";
                    if(!empty($note_doc)){
                        $notefiles[$i] = $note_doc;
                        $i++;
                    }
                }
                /*
                 * Adding Notes
                 */
                $mpdf->WriteHTML($notehtml);

                /*
                 * Add Note Files
                 */
                if(!empty($notefiles)){
                    $mpdf->SetHTMLFooter($footer);
                    $mpdf->AddPage();
                    foreach($notefiles as $f){
                        $path = getcwd().$f;
                        $pagecount = $mpdf->SetSourceFile($path);
                        for ($i=1;$i<=$pagecount;$i++){
                            $import_page = $mpdf->ImportPage($i);
                            $mpdf->UseTemplate($import_page);
                            if ($i < $pagecount){
                                $mpdf->AddPage();
                            }
                        }
                    }
                }
            
            }
            /*
             * Add Main File
             */
            if(!empty($notes)){
                $mpdf->AddPage();
            }
            
            $orignalFile = $dakDetail['docs_path'];
            $path = getcwd().$orignalFile;
            $pagecount = $mpdf->SetSourceFile($path);
            for ($i=1;$i<=$pagecount;$i++){
                $import_page = $mpdf->ImportPage($i);
                $mpdf->UseTemplate($import_page);
                if ($i < $pagecount){
                    $mpdf->AddPage();
                }
            }
            
            $file = $mpdf->Output('download.pdf', 'I');
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . basename($file) . '"');
            header("Cache-Control: max-age=0");
            readfile($file);
        }
        Yii::$app->getSession()->setFlash('danger', 'Invalid Params Found.'); 
        return $this->redirect($url);
    }
    
    public function actionClosedfile(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."filetracking/dak?securekey=$menuid";
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('closedfile', ['menuid'=>$menuid]);
    }
}
