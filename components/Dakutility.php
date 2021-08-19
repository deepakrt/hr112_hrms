<?php
namespace app\components;
use Yii;
use yii\base\Component;

class Dakutility extends Component 
{
    public function sendEmailwithAttachmenttouser($file_id, $fwd_to, $emp_list, $sender_empcode) {
        try 
        {
            $MAIL_HOST = MAIL_HOST;
            $MAIL_FROM = MAIL_FROM;
            $MAIL_PASSWORD = MAIL_PASSWORD;
            $MAIL_PORT = MAIL_PORT;
            $MAIL_FROM_LABEL = MAIL_FROM_LABEL;
            if (!empty($MAIL_FROM) && filter_var($MAIL_FROM, FILTER_VALIDATE_EMAIL) && !empty($MAIL_PASSWORD) && !empty($MAIL_PORT)){                  
                if(!empty($file_id)){
                    $fileinfo = Yii::$app->fts_utility->efile_get_dak($file_id, NULL, NULL, NULL);
                    $subject = $fileinfo['subject'];
                }else{
                    $subject = "New dak received from office reception";
                }
                $emp = Yii::$app->utility->get_employees($sender_empcode);
                $sender_name = $emp['fullname'].", ".$emp['desg_name']." ($emp[dept_name])";
                $link_CDAC = "Click here for login <a href='".emulazim_link_cdac."' style='color:red;font-weight:bold' title='".emulazim_lable."'>".emulazim_lable." (C-DAC Network)</a>";
                $link_Outside = "Click here for login <a href='".emulazim_link_outside."' style='color:red;font-weight:bold' title='".emulazim_lable."'>".emulazim_lable." (Other Network)</a>";
                $headers = '';
                $message = "<div style='font-size:13px;'>Dear Sir/Madam,<br><br> You have received file from $sender_name"."<br><br>$link_CDAC <br>OR<br>$link_Outside<br></br>Thanks<br><b>eMulazim Team<br>C-DAC, Mohali</b></div>";

                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
                require_once './PHPMailer/PHPMailerAutoload.php';
                $mail = new \PHPMailer;  



                $mail->isSMTP();                                         // Set mailer to use SMTP
                $mail->Host = $MAIL_HOST;                                      // Specify main and backup SMTP servers
                $mail->SMTPAuth = true;                                 // Enable SMTP authentication
                $mail->Username = $MAIL_FROM;                              // SMTP username
                $mail->Password = $MAIL_PASSWORD;                        // SMTP password
                $mail->SMTPSecure = 'tls';                              // Enable TLS encryption, `ssl` also accepted
                $mail->Port = $MAIL_PORT;                                    // TCP port to connect to
                $mail->isHTML(true);   
                $mail->setFrom($MAIL_FROM, $MAIL_FROM_LABEL);


                $mail->Subject = $subject;
                $mail->Body = $message;
                $email_id = "";
                if($fwd_to == 'E'){
                    $email_id = Yii::$app->utility->get_employees($emp_list[0]['employee_code']);
                    if(!empty($email_id)){
                        $mail->addAddress($email_id['email_id']);
                        $mail->send();
                        return true;
                    }

                }elseif($fwd_to == 'G'){
                    // Email To
                    $email_id = Yii::$app->utility->get_employees($emp_list[0]['employee_code']);
                    if(!empty($email_id)){
                        $mail->addAddress($email_id['email_id']);
                    }

                    // Email CC
                    foreach($emp_list as $key=>$val){
                        $email_id = "";
                        $email_id = Yii::$app->utility->get_employees($val['employee_code']);
                        if($key > 0){
                            if(!empty($email_id)){
                                $mail->AddCC($email_id['email_id']); 
                            }
                        }
                    }
                    $mail->send();
                    return true;
                }elseif($fwd_to == 'A'){
                    foreach($emp_list as $e){
                        $email_id = "";
                        $email_id = Yii::$app->utility->get_employees($e['employee_code']);
                        if(!empty($email_id)){
                            $mail->addAddress($email_id['email_id']);
                            $mail->send();
                        }

                    }
                    return true;
                }

//                $filename = 'Application.pdf';
//                if (file_exists($path)) 
//                {
//                    $mail->addAttachment($path, $filename); 
//                }
                    
               
            }

        } 
        catch (Exception $ex) 
        {
            throw new Exception(500, $ex);
        }
    }
    public function get_rbac_employee_rolefordashboard($param_employee_code)
    {
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `get_rbac_employee_role`(:param_employee_code)";
	$command = $connection->createCommand($sql); 
        $command->bindValue(':param_employee_code',$param_employee_code);
        $result=$command->queryAll();
        $connection->close();
        return $result;       
    }
    public function efile_get_hod_emps($param_employee_code)
    {
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `efile_get_hod_emps`(:param_employee_code)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_employee_code',$param_employee_code);
        $result=$command->queryAll();
        $connection->close();
        return $result;
    }
    public function efile_dashboard_get_dak($param_dept_id)
    {
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `efile_dashboard_get_dak`(:param_dept_id)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_dept_id',$param_dept_id);
        $result=$command->queryAll();
        $connection->close();
        return $result;
    }
    public function efile_get_dak_group_members_remarks($param_file_id,$param_status,$param_employee_code)
    {
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `efile_get_dak_group_members_remarks`(:param_file_id,:param_status,:param_employee_code)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_file_id', $param_file_id); 
        $command->bindValue(':param_status', $param_status);
        $command->bindValue(':param_employee_code', $param_employee_code); 
        $result=$command->queryAll();
        $connection->close();
        return $result;
    }
    public function efile_dak_group_members_remarks($param_action_type,$param_id,$param_dak_group_id,$param_file_id,$param_employee_code,
            $param_group_role,$param_remarks,$param_status,$param_is_active)
    {
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `efile_dak_group_members_remarks`(:param_action_type,:param_id,:param_dak_group_id,:param_file_id,:param_employee_code,"
                . ":param_group_role,:param_remarks,:param_status,:param_is_active, @Result)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_action_type', $param_action_type);
        $command->bindValue(':param_id', $param_id);
        $command->bindValue(':param_dak_group_id', $param_dak_group_id);
        $command->bindValue(':param_file_id', $param_file_id);
        $command->bindValue(':param_employee_code', $param_employee_code);
        $command->bindValue(':param_group_role', $param_group_role);
        $command->bindValue(':param_remarks', $param_remarks);
        $command->bindValue(':param_status', $param_status);
        $command->bindValue(':param_is_active', $param_is_active);
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
    }
    public function makenotesfile($fileid)
    {
        //require_once './mpdf/mpdf.php';

        $margin_left = 10;
        $margin_right = 10;
        $margin_top = 5;
        $margin_bottom = 20;
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
	//$mpdf = new \Mpdf\Mpdf('utf-8', '', '', '', $margin_left, $margin_right, $margin_top, $margin_bottom, 0, 0);
        $stylesheet = file_get_contents("./css/mpdf_csss.css"); // external css
        $mpdf->WriteHTML($stylesheet, 1);
	$deplLogo=getcwd()."/images/cdac.jpeg";
        $swachhbharatabhiyan=getcwd()."/images/swacchbharatlogo.jpeg";
        $header = "<br><div class='headerdiv hindi'>
                        <div class='headerdivLeft'><img src='$deplLogo' class='logo' /></div>
                        <div class='headerdivcenter'>
                            <div class='headerDetail'>
                            <h4 class='hindi' style='text-align:center;'>प्रगत संगणन विकास केंद्र,मोहाली</h4>
                            <h4 style='text-align:center;'>CENTRE FOR DEVELOPMENT OF ADVANCED COMPUTING</h4>
                            <h6 style='text-align:center;'>MOHALI</h6>
                            </div>
                        </div>
                        <div class='headerdivright'><img src='$swachhbharatabhiyan' class='logo' /></div></div>";
        
        $fileNotes = Yii::$app->Dakutility->efile_get_dak_notes($fileid);
        $filedocs = Yii::$app->Dakutility->efile_get_dak_docs($fileid,NULL);
       // $mpdf->SetImportUse();
        $i=1;
        $DepartmentName = "CDAC";
        $d= date('d-m-Y H:i:s');
        $footer = "<table class='mpdf_width hindi'>
                <tr>
                <td class='footerDept'>$DepartmentName</td>
                <td class='footerPage'>Page {PAGENO} of {nbpg}</td>
                <td class='footerDate' align='right'>Printed on : $d</td>
                </tr>
                </table>
                ";
        if(!empty($fileNotes))
        {
            $mpdf->WriteHTML($header);
            $mpdf->SetHTMLFooter($footer);
            $html="<h3 class='text-center'><b>Note Sheet</b></h3><br><div class='hrline'></div><br><div class='greensheet'>";
            foreach ($fileNotes as $key => $value) 
            {
                $note_comment=$value["note_comment"];
                $noteid=$value["noteid"];
                $added_date=date("d-M-Y",strtotime($value["added_date"]));
                $emp = Yii::$app->utility->get_employees($value['added_by']);
                $fwd_name = $emp['fname']." ".$emp['lname'].",".$emp['desg_name'];
                $html.="<div class='row greensheet hindi'><div class='col-sm-12 text-right'>
						<b>Date: $added_date</b></div>
                        <div class='col-sm-12'>
                        <p>$note_comment</p>
                        </div>
                        <div class='col-sm-12 text-right'><b>$fwd_name</b><hr></div>
				</div>";
            }
            $html.="</div>";
            $mpdf->WriteHTML($html);
		}
        $printDt = date('d-m-Y H:i:s');
        $name = "efile".date('Y_m_d_H_i_s').".pdf";
        $file = $mpdf->Output($name, 'I');
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header("Cache-Control: max-age=0");
        readfile($file);

    }
    public function makefilefromnotesanddocs($fileid)
    {
        if(!empty($fileid))
        {
            $outputName=getcwd().FTS_Documents.$fileid.".pdf";
            $flename="efile".date('Y_m_d_H_i_s').".pdf";
            header('Content-Type: application/pdf');
            header("Content-Disposition: attachment; filename=\"$flename\"");
            readfile($outputName); 
        }
    }
//    public function makefilefromnotesanddocs($fileid)
//    {
//        require_once './mpdf/mpdf.php';
//        $margin_left = 10;
//        $margin_right = 10;
//        $margin_top = 5;
//        $margin_bottom = 20;
//        $mpdf = new \mPDF('utf-8', '', '', '', $margin_left, $margin_right, $margin_top, $margin_bottom, 0, 0);
//        $stylesheet = file_get_contents("./css/mpdf_csss.css"); // external css
//        $mpdf->WriteHTML($stylesheet, 1);
//	$deplLogo=getcwd()."/images/cdac.jpeg";//Yii::$app->homeUrl."images/logo_cdac.png";
//        //$deplLogo=Yii::$app->homeUrl."images/logo_cdac.png";
//        $swachhbharatabhiyan=getcwd()."/images/swacchbharatlogo.jpeg";
//        $header = "<br><div class='headerdiv'>
//                        <div class='headerdivLeft'><img src='$deplLogo' class='logo' /></div>
//                        <div class='headerdivcenter'>
//                            <div class='headerDetail'>
//                            <h4 class='hindi' style='text-align:center;'>प्रगत संगणन विकास केंद्र,मोहाली</h4>
//                            <h4 style='text-align:center;'>CENTRE FOR DEVELOPMENT OF ADVANCED COMPUTING</h4>
//                            <h6 style='text-align:center;'>MOHALI</h6>
//                            </div>
//                        </div>
//                        <div class='headerdivright'><img src='$swachhbharatabhiyan' class='logo' /></div></div>";
//        $fileNotes = Yii::$app->Dakutility->efile_get_dak_notes($fileid);
//        $filedocs = Yii::$app->Dakutility->efile_get_dak_docs($fileid,NULL);
//        $mpdf->SetImportUse();
//        $i=1;
//        $DepartmentName = "CDAC";
//        $d= date('d-m-Y H:i:s');
//        $footer = "<table class='mpdf_width'>
//                <tr>
//                <td class='footerDept'>$DepartmentName</td>
//                <td class='footerPage'>Page {PAGENO} of {nbpg}</td>
//                <td class='footerDate' align='right'>Printed on : $d</td>
//                </tr>
//                </table>
//                ";
//        if(!empty($fileNotes))
//        {
//            $mpdf->WriteHTML($header);
//            $mpdf->SetHTMLFooter($footer);
//            $html="<h3 class='text-center'><b>Note Sheet</b></h3><br><div class='hrline'></div><br><div class='greensheet'>";
//            foreach ($fileNotes as $key => $value) 
//            {
//                $note_comment=$value["note_comment"];
//                $noteid=$value["noteid"];
//                $added_date=date("d-M-Y",strtotime($value["added_date"]));
//                $emp = Yii::$app->utility->get_employees($value['added_by']);
//                $fwd_name = $emp['fname']." ".$emp['lname'].",".$emp['desg_name'];
//				$html.="<div class='row greensheet'><div class='col-sm-12 text-right'>
//						<u><b>Date: $added_date</b></u></div>
//                        <div class='col-sm-12'>
//                        <p>$note_comment</p>
//                        </div>
//                        <div class='col-sm-12 text-right'><b>$fwd_name</b><hr></div>
//				</div>";
//            }
//            $html.="</div>";
//            $mpdf->WriteHTML($html);
//            $mpdf->AddPage();
//           
//        }
//        if(!empty($filedocs))
//        {
//            foreach($filedocs  as $key=>$value)
//            {
//                $path = getcwd().$value['docs_path'];
//                //echo $path; die;
//                if(!empty($path))
//                {
//                    $ext= explode(".", $path);
//                    $ext=$ext[1];
//                    $chkext=$value["doc_ext_type"];
//                    if($ext=="pdf" || $ext=="PDF" || $chkext=="PDF") 
//                    {        
//                        $mpdf->SetImportUse();
////                        $f=getcwd()."/"."other_files/DAK_RECEIVED/341814/4322701588236586.pdf";
//                        $pagecount = $mpdf->SetSourceFile($path);
//                        for ($i=1; $i<=$pagecount; $i++)
//                        {
//                            $import_page = $mpdf->ImportPage($i);	
//                            $mpdf->UseTemplate($import_page);
//                            if ($i <= $pagecount)
//                            {
//                                $mpdf->AddPage();
//                            }
//                        }
//                    }
//                    else 
//                    {
//                        $html="<div ><img  src='$path' /></div>";
//                        $mpdf->WriteHTML($html);
//                        $mpdf->AddPage();
//                    }
//                }
//            }
//        }
//        $printDt = date('d-m-Y H:i:s');
//        $name = "efile".date('Y_m_d_H_i_s').".pdf";
//        $file = $mpdf->Output($name, 'I');
//        header('Content-Type: application/pdf');
//        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
//        header("Cache-Control: max-age=0");
//        readfile($file);
//
//    }
    public function makefilefromdocs($fileid)
    {
        $filedocs = Yii::$app->Dakutility->efile_get_dak_docs($fileid,NULL);
//		echo "<pre>";print_R($filedocs); die;
        $createFolder = getcwd().FTS_Documents;
        if(!file_exists($createFolder))
        {
            mkdir($createFolder, 0777, true);
        }
        $finalPath = $createFolder.$fileid.".pdf";
        $outputName=FTS_Documents.$fileid.".pdf";
        if(!empty($filedocs))
        {
            $cmd = "gs -q -dNOPAUSE -dBATCH -dAutoRotatePages=1 -sPAPERSIZE=legal -sDEVICE=pdfwrite -sOutputFile=$finalPath ";
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
                        $cmd .= $path." ";
                        $result = shell_exec($cmd);
                    }
                }
            }
        }
        return $outputName;
    }
//    public function makefilefromdocs($fileid)
//    {
//		
//        require_once './mpdf/mpdf.php';
//        $mpdf = new \mPDF();
//
//        $filedocs = Yii::$app->Dakutility->efile_get_dak_docs($fileid,NULL);
////		echo "<pre>";print_R($filedocs); die;
//        $mpdf->SetImportUse();
//        $i=1;
//        $DepartmentName = "CDAC";
//        $d= date('d-m-Y H:i:s');
//        $footer = "<table class='mpdf_width'>
//                <tr>
//                <td class='footerDept'>$DepartmentName</td>
//                <td class='footerPage'>Page {PAGENO} of {nbpg}</td>
//                <td class='footerDate' align='right'>Printed on : $d</td>
//                </tr>
//                </table>
//                ";
//        if(!empty($filedocs))
//        {
//            $mpdf->SetHTMLFooter($footer);
//            $mpdf->AddPage();
//            foreach($filedocs  as $key=>$value)
//            {
//                $path = getcwd().$value['docs_path'];
//                if(!empty($path))
//                {
//                    $ext= explode(".", $path);
//                    $ext=$ext[1];
//                    $chkext=$value["doc_ext_type"];
//                    if($ext=="pdf" || $ext=="PDF" || $chkext=="PDF") 
//                    {        
//                        $mpdf->SetImportUse();
////                        $f=getcwd()."/"."other_files/DAK_RECEIVED/341814/4322701588236586.pdf";
//                        $pagecount = $mpdf->SetSourceFile($path);
//                        for ($i=1; $i<=$pagecount; $i++)
//                        {
//                            $import_page = $mpdf->ImportPage($i);	
//                            $mpdf->UseTemplate($import_page);
//                            if ($i <= $pagecount)
//                            {
//                                $mpdf->AddPage();
//                            }
//                        }
//                    }
//                    else 
//                    {
//                        $html="<div ><img  src='$path' /></div>";
//                        $mpdf->WriteHTML($html);
//                        $mpdf->AddPage();
//                    }
//                }
//            }
//        }
//        $createFolder = getcwd().FTS_Documents;
//        if(!file_exists($createFolder))
//        {
//            mkdir($createFolder, 0777, true);
//        }
//        $finalPath = $createFolder.$fileid.".pdf";
//        $returnPath=FTS_Documents.$fileid.".pdf";
//        $returnName=$mpdf->Output($finalPath, "F");
//        return $returnPath;
//
//    }
    public function efile_get_dak_notes($param_file_id)
    {
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `efile_get_dak_notes`(:param_file_id)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_file_id', $param_file_id); 
        $result=$command->queryAll();
        $connection->close();
        return $result;
    }
    public function efile_get_dak_docs($param_file_id,$param_noteid)
    {
        
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `efile_get_dak_docs`(:param_file_id,:param_noteid)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_file_id', $param_file_id); 
        $command->bindValue(':param_noteid', $param_noteid); 
        $result=$command->queryAll();
        $connection->close();
        return $result;
    }

    public function getefileNo($t)
    {
        $yr = date('Y');
        $m = date('m');
        if($m >= 3){ $CurrentYr = $yr+1; }else{ $CurrentYr = $yr+1;}
        $CurDate = date('d-m-Y');
        $CurYr = date('Y', strtotime($CurDate));
        $Curmonth = date('m', strtotime($CurDate));
        if($Curmonth >= 3){ $yrss = $CurYr+1; }else{ $yrss = $CurYr-1; }
        $fn ="";
        for($i=$CurrentYr;$i>=$yrss;$i--)
        {
            $ly = $i-1;	
            $fn= $ly."-".$i;
        }
        $no= "CDAC(M)/eFile/$fn/$t/";
        return $no;
    }
    public function efile_get_dakno($param_fiile_type)
    {
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `efile_get_dakno`(:param_fiile_type)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_fiile_type', $param_fiile_type); 
        $result=$command->queryOne();
        $connection->close();
        return $result;
    }
    public function efile_dak_received($param_rec_id,$param_dak_number,$param_mode_of_rec,$param_rec_date,$param_recfrom, $param_org_state,$param_org_district,$param_org_address,$param_dak_summary,$param_dak_remarks,$param_dak_document, $param_dak_fwd_dept,$param_dak_fwd_to,$param_is_active,$param_status,$param_forwaded_by, $param_entry_language)
    {
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `efile_dak_received`(:param_rec_id,:param_dak_number,:param_mode_of_rec,:param_rec_date,:param_recfrom,"
                . ":param_org_state,:param_org_district,:param_org_address,:param_dak_summary,:param_dak_remarks"
                . ",:param_dak_document,:param_dak_fwd_dept,:param_dak_fwd_to,:param_is_active"
                . ",:param_status,:param_forwaded_by, :param_entry_language, @Result)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_rec_id', $param_rec_id);
        $command->bindValue(':param_dak_number', $param_dak_number);
        $command->bindValue(':param_mode_of_rec', $param_mode_of_rec);
        $command->bindValue(':param_rec_date', $param_rec_date);
        $command->bindValue(':param_recfrom', $param_recfrom);
        $command->bindValue(':param_org_state', $param_org_state);
        $command->bindValue(':param_org_district', $param_org_district);
        $command->bindValue(':param_org_address', $param_org_address);
        $command->bindValue(':param_dak_summary', $param_dak_summary);
        $command->bindValue(':param_dak_remarks', $param_dak_remarks);
        $command->bindValue(':param_dak_document', $param_dak_document);
        $command->bindValue(':param_dak_fwd_dept', $param_dak_fwd_dept);
        $command->bindValue(':param_dak_fwd_to', $param_dak_fwd_to);
        $command->bindValue(':param_is_active', $param_is_active);
        $command->bindValue(':param_status', $param_status);
        $command->bindValue(':param_forwaded_by', $param_forwaded_by);
        $command->bindValue(':param_entry_language', $param_entry_language);
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
    }
    public function efile_dak_dispatch($param_disp_id,$param_disp_number,$param_disp_date,$param_file_id,
            $param_disp_summary,$param_mode_of_rec,$param_disp_remarks,$param_disp_document,$param_disp_from_dept,
            $param_disp_from_emp,$param_diapatch_by,$param_is_active,$param_status)
    {
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `efile_dak_dispatch`(:param_disp_id,:param_disp_number,:param_disp_date,:param_file_id,"
                . ":param_disp_summary,:param_mode_of_rec,:param_disp_remarks,:param_disp_document,:param_disp_from_dept"
                . ",:param_disp_from_emp,:param_diapatch_by,:param_is_active,:param_status, @Result)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_disp_id', $param_disp_id);
        $command->bindValue(':param_disp_number', $param_disp_number);
        $command->bindValue(':param_disp_date', $param_disp_date);
        $command->bindValue(':param_file_id', $param_file_id);
        $command->bindValue(':param_disp_summary', $param_disp_summary);
        $command->bindValue(':param_mode_of_rec', $param_mode_of_rec);
        $command->bindValue(':param_disp_remarks', $param_disp_remarks);
        $command->bindValue(':param_disp_document', $param_disp_document);
        $command->bindValue(':param_disp_from_dept', $param_disp_from_dept);
        $command->bindValue(':param_disp_from_emp', $param_disp_from_emp);
        $command->bindValue(':param_diapatch_by', $param_diapatch_by);
        $command->bindValue(':param_is_active', $param_is_active);
        $command->bindValue(':param_status', $param_status);
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
    }
    public function efile_dak_dispatch_address($param_disp_add_id,$param_disp_id,$param_disp_to,$param_org_state,$param_org_district,$param_org_address)
    {
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `efile_dak_dispatch_address`(:param_disp_add_id,:param_disp_id,:param_disp_to,:param_org_state,:param_org_district,:param_org_address, @Result)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_disp_add_id', $param_disp_add_id);
        $command->bindValue(':param_disp_id', $param_disp_id);
        $command->bindValue(':param_disp_to', $param_disp_to);
        $command->bindValue(':param_org_state', $param_org_state);
        $command->bindValue(':param_org_district', $param_org_district);
        $command->bindValue(':param_org_address', $param_org_address);
        $command->execute();
        $valueOut = $connection->createCommand("select @Result as ress;")->queryScalar();
        $connection->close();
        return $valueOut; 
    }
    public function efile_get_dak_dispatch($param_dispid)
    {
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `efile_get_dak_dispatch`(:param_dispid)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_dispid', $param_dispid); 
        if(!empty($param_dispid)){
                $result=$command->queryOne();
        }else{
                $result=$command->queryAll();
        }
        $connection->close();
        return $result;
    }
    public function efile_get_dak_received($param_recid,$Param_dak_fwd_to)
    {
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `efile_get_dak_received`(:param_recid,:Param_dak_fwd_to)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_recid', $param_recid); 
        $command->bindValue(':Param_dak_fwd_to', $Param_dak_fwd_to); 
        if(!empty($param_recid)){
                $result=$command->queryOne();
        }else{
                $result=$command->queryAll();
        }
        $connection->close();
        return $result;
    }
    public function efile_get_dak_dispatch_address($param_dispid)
    {
        $connection=   Yii::$app->db;
        $connection->open();
        $sql =" CALL `efile_get_dak_dispatch_address`(:param_dispid)";
        $command = $connection->createCommand($sql); 
        $command->bindValue(':param_dispid', $param_dispid); 
        $result=$command->queryAll();
        $connection->close();
        return $result;
    }
    public function get_master_states($Param_state_id)
    {
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
    public function get_master_districts($Param_distt_id, $Param_state_id)
    {
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
    public function getYesorNO($y)
    {
        $isactive="No";
        if($y=="Y")
        {
            $isactive="Yes";
        }
        return $isactive;
    }
//    public function sendEmailwithAttachmenttouser() 
//    {
//        try 
//        {
//            $email="pankajgoel999@gmail.com";
//            if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) 
//            {     
//                $headers = '';
//                $message = "Dear Sir/Madam,<br><br>"."Testing Purpose"."<br><br>" ;
//                $message .= "Thanks<br><br>";
//                $message .= "ERSS<br><br>";
//                $headers = "MIME-Version: 1.0" . "\r\n";
//                $headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
//                require_once './PHPMailer/PHPMailerAutoload.php';
//                $mail = new \PHPMailer;  
//                
//                $mail->isSMTP();                                         // Set mailer to use SMTP
//                $mail->Host = "smtp.gmail.com";                                      // Specify main and backup SMTP servers
//                $mail->SMTPAuth = true;                                 // Enable SMTP authentication
//                $mail->Username = "pankajgoel999@gmail.com";                              // SMTP username
//                $mail->Password = "";                        // SMTP password
//                $mail->SMTPSecure = 'tls';                              // Enable TLS encryption, `ssl` also accepted
//                $mail->Port = "587";                                    // TCP port to connect to
//                $mail->isHTML(true);   
//                $mail->setFrom("pankajgoel999@gmail.com", "ERSS-Haryana");
//
//                $mail->addAddress("pankajgoel999@gmail.com");
//                $mail->Subject = "Hello";
//                $mail->Body    = $message;
//                
////                $filename = 'Application.pdf';
////                if (file_exists($path)) 
////                {
////                    $mail->addAttachment($path, $filename); 
////                }
//                if(!$mail->send()) 
//                {
//                    return FALSE;
//                } 
//                else 
//                {
//                    return true;
//                }
//            }
//
//        } 
//        catch (Exception $ex) 
//        {
//            throw new Exception(500, $ex);
//        }
//    }
    
}
