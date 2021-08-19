<?php
namespace app\modules\hr\controllers;
use yii;
class MarkattendanceController extends \yii\web\Controller
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
        $url = Yii::$app->homeUrl."hr/markattendance?securekey=$menuid";
        if(isset($_POST['Attendence']) AND !empty($_POST['Attendence'])){
            
            // echo "<pre>"; print_r($_POST); die();

            $attenDate = date('Y-m-d', strtotime($_POST['attendence_date_f']));
            if($attenDate == '1970-01-01'){
                Yii::$app->getSession()->setFlash('danger', 'Invalid Attendance Date'); 
                return $this->redirect($url);
            }
            
            if(isset($_POST['saveasdraft']) AND !empty($_POST['saveasdraft'])){
                $status = 'Draft';
                $msg = "Attendance Marked as Draft Successfully.";
            }elseif(isset($_POST['submit']) AND !empty($_POST['submit'])){
                $status = 'Submitted';
                $msg = "Attendance Submitted Successfully.";
            }else{
                Yii::$app->getSession()->setFlash('danger', 'Invalid Submit Type'); 
                return $this->redirect($url);
            }
            $atten = $_POST['Attendence'];
            $newArray=array();
            $i=0;
            foreach($atten as $a){
                $attid = NULL;
                $ec = Yii::$app->utility->decryptString($a['ec']);
                $attenmark = Yii::$app->utility->decryptString($a['attenmark']); // die();


                if(empty($ec) OR empty($attenmark)){
                    Yii::$app->getSession()->setFlash('danger', 'Invalid params value found'); 
                    return $this->redirect($url);
                }
                if($attenmark == 'P'){ 
                }elseif($attenmark == 'A'){
                }elseif($attenmark == 'L'){
                }elseif($attenmark == 'FHL'){
                }elseif($attenmark == 'SHL'){
                }else{
                    Yii::$app->getSession()->setFlash('danger', 'Invalid params value found.'); 
                    return $this->redirect($url);
                }

                if(!empty($a['attid'])){
                    $attid = Yii::$app->utility->decryptString($a['attid']); // die();


                    if(empty($attid)){
                        Yii::$app->getSession()->setFlash('danger', 'Invalid params value found'); 
                        return $this->redirect($url);
                    }
                }
                /*echo "<pre>"; print_r($a);
                echo "<pre>"; print_r($a['attid']);
                die();*/
                $newArray[$i]['ec']=$ec;
                $newArray[$i]['attenmark']=$attenmark;
                $newArray[$i]['attid']=$attid;
                $i++;
            }
            
            if(empty($newArray)){
                Yii::$app->getSession()->setFlash('danger', 'No Record Found'); 
                return $this->redirect($url);
            }

            
           // echo "<pre>";print_r($newArray); die;
            foreach($newArray as $n){
                $ec = $n['ec'];
                $att = $n['attenmark'];
                $attid = $n['attid'];
                Yii::$app->hr_utility->hr_add_update_attendance($attid, $ec,$attenDate, $att, $status);
            }
            /*
             * Logs
             */
            $logs['status']=$status;
            $logs['attendate']=$attenDate;
            $logs['emp_list']=$newArray;
            $jsonlogs = json_encode($logs);
            Yii::$app->utility->activities_logs("Attendance", NULL, NULL, $jsonlogs, $msg);
            
            Yii::$app->getSession()->setFlash('success', $msg); 
            return $this->redirect($url);
        }
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('index', ['menuid'=>$menuid]);
    }
    
    public function actionGetattendence()
    {
        if(isset($_POST['attendate']) AND !empty($_POST['attendate'])){
            $attenDate = date('Y-m-d', strtotime($_POST['attendate']));
            if($attenDate == '1970-01-01'){
                $result['Status']='FF';
                $result['Res']='Invalid Date';
                echo json_encode($result);die;
            }
            $emplist = Yii::$app->hr_utility->hr_get_appraise_list();
            if(empty($emplist)){
                $result['Status']='FF';
                $result['Res']='Employees List Not Found';
                echo json_encode($result);die;
            }
            //echo "<pre>";print_r($emplist); die;
            $attenDate1 = date('d-M-Y', strtotime($attenDate));
            $html = "";
            $html .= " <h6><b>Attendance For $attenDate1</b></h6>
            <table class='table table-bordered'>
                <tr>
                    <th>Emp ID</th>
                    <th>Name</th>
                    <th>Designation</th>
                    <th>Department</th>
                    <th>Attendance</th>
                </tr>";
            $i=0;
            foreach($emplist as $e){
                $ecode = $e['employee_code'];
                $name = $e['fullname'];
                $desg_name = $e['desg_name'];
                $dept_name = $e['dept_name'];
                
                $ecode1 = Yii::$app->utility->encryptString($ecode);
                $attid = NULL;
                $chkAtten = Yii::$app->hr_utility->hr_get_attendance(Yii::$app->user->identity->role, "Day", NULL, $ecode, $attenDate, "Draft,Submitted", Yii::$app->user->identity->e_id);
                $att = "<select class='form-control form-control-sm' name='Attendence[$i][attenmark]'>
                            <option value='".Yii::$app->utility->encryptString('P')."'>Present</option>
                            <option value='".Yii::$app->utility->encryptString('A')."'>Absent</option>
                            <option value='".Yii::$app->utility->encryptString('L')."'>On Leave</option>
                            <option value='".Yii::$app->utility->encryptString('FHL')."'>First Half Leave</option>
                            <option value='".Yii::$app->utility->encryptString('SHL')."'>Second Half Leave</option>
                    </select>";
                $showbtn = "<br><div class='text-center'><input type='submit' name='saveasdraft' class='btn btn-primary btn-sm' value='Save As Draft' /> <input type='submit' class='btn btn-success btn-sm' name='submit' value='Submit' /></div>";
                //                echo "<pre>";print_r($chkAtten); die;
                if(!empty($chkAtten)){
                    $attid = Yii::$app->utility->encryptString($chkAtten['attid']);
                    if($chkAtten['status'] == 'Draft'){
                        $showbtn = "<br><div class='text-center'><input type='submit' name='saveasdraft' class='btn btn-primary btn-sm' value='Save As Draft' /> <input type='submit' class='btn btn-success btn-sm' name='submit' value='Submit' /></div>";
                        
                        $s_p= $s_a=$s_l=$s_fhl=$s_shl="";
                        if($chkAtten['attendance_mark'] == 'P'){
                            $s_p="selected=selected";
                        }elseif($chkAtten['attendance_mark'] == 'A'){
                            $s_a="selected=selected";
                        }elseif($chkAtten['attendance_mark'] == 'L'){
                            $s_l="selected=selected";
                        }elseif($chkAtten['attendance_mark'] == 'FHL'){
                            $s_fhl="selected=selected";
                        }elseif($chkAtten['attendance_mark'] == 'SHL'){
                            $s_shl="selected=selected";
                        }
                        $att = "<select class='form-control form-control-sm' name='Attendence[$i][attenmark]'>
                            <option $s_p value='".Yii::$app->utility->encryptString('P')."'>Present</option>
                            <option $s_a value='".Yii::$app->utility->encryptString('A')."'>Absent</option>
                            <option $s_l value='".Yii::$app->utility->encryptString('L')."'>On Leave</option>
                            <option $s_fhl value='".Yii::$app->utility->encryptString('FHL')."'>First Half Leave</option>
                            <option $s_shl value='".Yii::$app->utility->encryptString('SHL')."'>Second Half Leave</option>
                        </select>";
                    }else{
                        $showbtn = "<div class='text-center'><div class='alert alert-danger'>Attendance Marked.</div></div>";
                        if($chkAtten['attendance_mark'] == 'P'){
                            $att ="Present";
                        }elseif($chkAtten['attendance_mark'] == 'A'){
                            $att ="Absent";
                        }elseif($chkAtten['attendance_mark'] == 'L'){
                            $att ="On Leave";
                        }elseif($chkAtten['attendance_mark'] == 'FHL'){
                            $att ="First Half Leave";
                        }elseif($chkAtten['attendance_mark'] == 'SHL'){
                            $att ="Second Half Leave";
                        }
                    }
                }
                
                $html .="<tr>
                    <td>
                        <input type='hidden' name='Attendence[$i][ec]' value='$ecode1' /> 
                        <input type='hidden' name='Attendence[$i][attid]' value='$attid' /> 
                        $ecode</td>
                    <td>$name</td>
                    <td>$desg_name</td>
                    <td>$dept_name</td>
                    <td>$att</td>
                </tr>";
                $i++;
            }
            $html .="</table>";
            $result['Status']='SS';
            $result['Res']=$html;
            $result['Res_btn']=$showbtn;
            echo json_encode($result);die;
        }else{
            $result['Status']='FF';
            $result['Res']='Invalid params found';
            echo json_encode($result);die;
        }
    }


    public function actionGetattendencedata()
    {
        if(isset($_POST['attendate']) AND !empty($_POST['attendate'])){
            $dept_id = $_POST['dept_id'];
            $employment_type = $_POST['employment_type'];
            $attenDate = date('Y-m-d', strtotime($_POST['attendate']));

            if($attenDate == '1970-01-01'){
                $result['Status']='FF';
                $result['Res']='Invalid Date';
                echo json_encode($result);die;
            }
            $emplist = Yii::$app->hr_utility->hr_get_appraise_list_data($dept_id,$employment_type);
            if(empty($emplist)){
                $result['Status']='FF';
                $result['Res']='Employees List Not Found';
                echo json_encode($result);die;
            }
            //echo "<pre>";print_r($emplist); die;
            $attenDate1 = date('d-M-Y', strtotime($attenDate));
            $html = "";
            $html .= " <h6><b>Attendance For $attenDate1</b></h6>
            <table class='table table-bordered'>
                <tr>
                    <th>Emp ID</th>
                    <th>Name</th>
                    <th>Designation</th>
                    <th>Department</th>
                    <th>Attendance</th>
                </tr>";
            $i=0;
            foreach($emplist as $e){
                $ecode = $e['employee_code'];
                $name = $e['fullname'];
                $desg_name = $e['desg_name'];
                $dept_name = $e['dept_name'];
                
                $ecode1 = Yii::$app->utility->encryptString($ecode);
                $attid = NULL;
                $chkAtten = Yii::$app->hr_utility->hr_get_attendance(Yii::$app->user->identity->role, "Day", NULL, $ecode, $attenDate, "Draft,Submitted", Yii::$app->user->identity->e_id);


                $att = "<select class='form-control form-control-sm' name='Attendence[$i][attenmark]'>
                            <option value='".Yii::$app->utility->encryptString('P')."'>Present</option>
                            <option value='".Yii::$app->utility->encryptString('A')."'>Absent</option>
                            <option value='".Yii::$app->utility->encryptString('L')."'>On Leave</option>
                            <option value='".Yii::$app->utility->encryptString('FHL')."'>First Half Leave</option>
                            <option value='".Yii::$app->utility->encryptString('SHL')."'>Second Half Leave</option>
                    </select>";
                $showbtn = "<br><div class='text-center'><input type='submit' name='saveasdraft' class='btn btn-primary btn-sm' value='Save As Draft' /> <input type='submit' class='btn btn-success btn-sm' name='submit' value='Submit' /></div>";
                               // echo "<pre>";print_r($chkAtten); die;
                if(!empty($chkAtten)){
                    $attid = Yii::$app->utility->encryptString($chkAtten['attid']);
                    if($chkAtten['status'] == 'Draft'){
                        $showbtn = "<br><div class='text-center'><input type='submit' name='saveasdraft' class='btn btn-primary btn-sm' value='Save As Draft' /> <input type='submit' class='btn btn-success btn-sm' name='submit' value='Submit' /></div>";
                        
                        $s_p= $s_a=$s_l=$s_fhl=$s_shl="";
                        if($chkAtten['attendance_mark'] == 'P'){
                            $s_p="selected=selected";
                        }elseif($chkAtten['attendance_mark'] == 'A'){
                            $s_a="selected=selected";
                        }elseif($chkAtten['attendance_mark'] == 'L'){
                            $s_l="selected=selected";
                        }elseif($chkAtten['attendance_mark'] == 'FHL'){
                            $s_fhl="selected=selected";
                        }elseif($chkAtten['attendance_mark'] == 'SHL'){
                            $s_shl="selected=selected";
                        }
                        $att = "<select class='form-control form-control-sm' name='Attendence[$i][attenmark]'>
                            <option $s_p value='".Yii::$app->utility->encryptString('P')."'>Present</option>
                            <option $s_a value='".Yii::$app->utility->encryptString('A')."'>Absent</option>
                            <option $s_l value='".Yii::$app->utility->encryptString('L')."'>On Leave</option>
                            <option $s_fhl value='".Yii::$app->utility->encryptString('FHL')."'>First Half Leave</option>
                            <option $s_shl value='".Yii::$app->utility->encryptString('SHL')."'>Second Half Leave</option>
                        </select>";
                    }else{
                        $showbtn = "<div class='text-center'><div class='alert alert-danger'>Attendance Marked.</div></div>";
                        if($chkAtten['attendance_mark'] == 'P'){
                            $att ="Present";
                        }elseif($chkAtten['attendance_mark'] == 'A'){
                            $att ="Absent";
                        }elseif($chkAtten['attendance_mark'] == 'L'){
                            $att ="On Leave";
                        }elseif($chkAtten['attendance_mark'] == 'FHL'){
                            $att ="First Half Leave";
                        }elseif($chkAtten['attendance_mark'] == 'SHL'){
                            $att ="Second Half Leave";
                        }
                    }
                }
                
                $html .="<tr>
                    <td>
                        <input type='hidden' name='Attendence[$i][ec]' value='$ecode1' /> 
                        <input type='hidden' name='Attendence[$i][attid]' value='$attid' /> 
                        $ecode</td>
                    <td>$name</td>
                    <td>$desg_name</td>
                    <td>$dept_name</td>
                    <td>$att</td>
                </tr>";
                $i++;
            }
            $html .="</table>";
            $result['Status']='SS';
            $result['Res']=$html;
            $result['Res_btn']=$showbtn;
            echo json_encode($result);die;
        }else{
            $result['Status']='FF';
            $result['Res']='Invalid params found';
            echo json_encode($result);die;
        }
    }
}