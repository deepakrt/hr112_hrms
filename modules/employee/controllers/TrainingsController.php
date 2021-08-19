<?php
namespace app\modules\employee\controllers;
use yii;

class TrainingsController extends \yii\web\Controller
{
    public function beforeAction($action){
        if(isset($_GET['securekey']) AND !empty($_GET['securekey'])){
            $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
            if(empty($menuid)){ return $this->redirect(Yii::$app->homeUrl); }            
            $chkValid = Yii::$app->utility->validate_url($menuid);
            if(empty($chkValid)){ return $this->redirect(Yii::$app->homeUrl); }
            return true;
        }else{ return $this->redirect(Yii::$app->homeUrl); }
        parent::beforeAction($action);
    }
    
    public function actionIndex(){

        $this->layout = '@app/views/layouts/admin_layout.php';
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $tpms = Yii::$app->tr_utility->tr_get_trainingprogram_role(3);
       
        return $this->render('index', ['menuid'=>$menuid,'tpms'=>$tpms]);
    }
    public function actionTrainingrequest(){

        $this->layout = '@app/views/layouts/admin_layout.php';
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $employee_code = Yii::$app->user->identity->employee_code;

        $current_employee_code = Yii::$app->user->identity->employee_code;
        $current_employee_role_name = Yii::$app->user->identity->role;

        if($current_employee_role_name == 4)
        {
            $role_name='fla';
        }
        else
        {
            $role_name='sla';
        }

        $tpms = Yii::$app->tr_utility->tr_get_training_applied_action($employee_code,$role_name);
        
        /*echo "<pre>";
         print_r($tpms);

        die();*/


        return $this->render('trainingrequest', ['menuid'=>$menuid,'tpms'=>$tpms]);
    }

    public function actionTrainingview(){

        $this->layout = '@app/views/layouts/admin_layout.php';
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        
        // $employee_code = Yii::$app->user->identity->employee_code;        
        // $tpms = Yii::$app->tr_utility->tr_get_training_applied_action($employee_code,'fla');
        // echo '<pre>';
        // print_r($_GET);
        // die();

        $tpms = '';

        if(isset($_GET['tpm_id']))
        {
            // $employee_code = Yii::$app->user->identity->employee_code;

            $employee_code = $_GET['emp_code'];
            $applied_id = $_GET['applied_id'];
            $tpm_id = $_GET['tpm_id'];

            $employee_code = Yii::$app->utility->decryptString($employee_code);
            $applied_id = Yii::$app->utility->decryptString($applied_id);
            $tpm_id = Yii::$app->utility->decryptString($tpm_id);
            

            $tpms = Yii::$app->tr_utility->tr_get_training_by_applied_id_action($tpm_id, $employee_code, $applied_id);
            

            // $employee_code = Yii::$app->user->identity->employee_code;
            // $tpms = Yii::$app->tr_utility->tr_get_training_applied_action($employee_code,'fla');
           


        }
       
        return $this->render('trainingview', ['menuid'=>$menuid,'tpms'=>$tpms,'back_url_text'=>'trainingrequest']);
    }

    public function actionTrainingmultirequestaction()
    {
        $this->layout = '@app/views/layouts/admin_layout.php';
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);

        $tpms = '';

       /*echo "<pre>";
       print_r($_POST);

       die();*/

        $status_pass = 'No Respond.';
        $status = 0;
        $msgData = 'Please check carefully and try again.';
        $status_name = '';

        $statusVal = 0;

        if(isset($_POST['combian_data']))
        {
            if(isset($_POST['parameter']))
            {
                $status = $_POST['parameter'];
            }

            $status_pass='Rejected';


            $current_employee_code = Yii::$app->user->identity->employee_code;
            $current_employee_role_name = Yii::$app->user->identity->role;

            if($current_employee_role_name == 4)
            {
                $status_name='Recommended';
            }
            elseif($current_employee_role_name == 2)
            {
                $status_name='Approved';
            }


            if($status == 1)
            {
                $status_pass = $status_name;
            }

            $statusVal = '000';
            $msgData = 'Record has been successfully updated with '.$status_pass.'.';

            // foreach($_POST['combian_data'] as $combian_data)
            
            $combian_data = $_POST['combian_data'];
            $countcombian_data = count($combian_data);

            for($prv=0;$prv<$countcombian_data;$prv++)
            {

                $rec = explode('_',$combian_data[$prv]);


                $employee_code = $rec[0];
                $applied_id = $rec[1];
                $tpm_id = $rec[2];
                $status = $status_pass;


                /*echo "<pre>";
                print_r($_GET);*/


                $employee_code = Yii::$app->utility->decryptString($employee_code);
                $applied_id = Yii::$app->utility->decryptString($applied_id);
                $tpm_id = Yii::$app->utility->decryptString($tpm_id);
                
                // die();

                Yii::$app->tr_utility->tr_apply_trainingprogram($tpm_id, $employee_code, $status, 'Edit', $applied_id);                    

            }

        }

        $return['STATUS_ID'] = $statusVal;
        $return['STATUS_MESSAGE'] = $msgData;
        echo json_encode($return); 

       
       // return $this->render('trainingview', ['menuid'=>$menuid,'tpms'=>$tpms]);
    }


    public function actionTrainingrequestaction()
    {

        $this->layout = '@app/views/layouts/admin_layout.php';
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
               

        $tpms = '';

        if(isset($_GET['tpm_id']))
        {
            // $employee_code = Yii::$app->user->identity->employee_code;

            $employee_code = $_GET['emp_code'];
            $applied_id = $_GET['applied_id'];
            $tpm_id = $_GET['tpm_id'];
            $status = $_GET['status'];


            /*echo "<pre>";
            print_r($_GET);*/


            $employee_code = Yii::$app->utility->decryptString($employee_code);
            $applied_id = Yii::$app->utility->decryptString($applied_id);
            $tpm_id = Yii::$app->utility->decryptString($tpm_id);
            
            // die();

            Yii::$app->tr_utility->tr_apply_trainingprogram($tpm_id, $employee_code, $status, 'Edit', $applied_id);                    

            $tpms = Yii::$app->tr_utility->tr_get_training_by_applied_id_action($tpm_id, $employee_code, $applied_id);
        }
       
        return $this->render('trainingview', ['menuid'=>$menuid,'tpms'=>$tpms]);
    }

    public function actionApplyfortraining(){

        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);



        if(isset($_GET['tpm_id'])){
                $employee_code = Yii::$app->user->identity->employee_code;
                $tpm_id = $_GET['tpm_id'];
                
                Yii::$app->tr_utility->tr_apply_trainingprogram($tpm_id,$employee_code,'Applied','Add');
        }
        return $this->redirect(Yii::$app->homeUrl."employee/trainings?securekey=$menuid");
    }

    public function actionViewtrainingstatus()
    {
        $this->layout = '@app/views/layouts/admin_layout.php';
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $employee_code = Yii::$app->user->identity->employee_code;

        $current_employee_code = Yii::$app->user->identity->employee_code;
        $current_employee_role_name = Yii::$app->user->identity->role_name;

       
        $role_name='flasla';
       
        $tpms = Yii::$app->tr_utility->tr_get_training_applied_action($employee_code,$role_name);
        
        /*echo "<pre>";
         print_r($tpms);
        die();*/


        return $this->render('viewtrainingstatus', ['menuid'=>$menuid,'tpms'=>$tpms]);
    }

    public function actionTrainingstatusview(){

        $this->layout = '@app/views/layouts/admin_layout.php';
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        
        // $employee_code = Yii::$app->user->identity->employee_code;        
        // $tpms = Yii::$app->tr_utility->tr_get_training_applied_action($employee_code,'fla');
        // echo '<pre>';
        // print_r($_GET);
        // die();

        $tpms = '';

        if(isset($_GET['tpm_id']))
        {
            // $employee_code = Yii::$app->user->identity->employee_code;

            $employee_code = $_GET['emp_code'];
            $applied_id = $_GET['applied_id'];
            $tpm_id = $_GET['tpm_id'];

            $employee_code = Yii::$app->utility->decryptString($employee_code);
            $applied_id = Yii::$app->utility->decryptString($applied_id);
            $tpm_id = Yii::$app->utility->decryptString($tpm_id);
            

            $tpms = Yii::$app->tr_utility->tr_get_training_by_applied_id_action($tpm_id, $employee_code, $applied_id);
            

            // $employee_code = Yii::$app->user->identity->employee_code;
            // $tpms = Yii::$app->tr_utility->tr_get_training_applied_action($employee_code,'fla');
           


        }
       
        return $this->render('trainingview', ['menuid'=>$menuid,'tpms'=>$tpms, 'back_url_text'=>'viewtrainingstatus']);
    }

    public function actionTraningvenureport()
    {
        $this->layout = '@app/views/layouts/admin_layout.php';
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $employee_code = Yii::$app->user->identity->employee_code;

        $current_employee_code = Yii::$app->user->identity->employee_code;
        $current_employee_role_name = Yii::$app->user->identity->role_name;

        // trng_Consolidated_report

        // $tpms = Yii::$app->tr_utility->tr_get_training_venue_action(0);
        // $consd_report = Yii::$app->tr_utility->trng_Consolidated_report();


        $getConsdReport = Yii::$app->tr_utility->trng_get_consolidated_report();

        // echo "<pre>";
        // // print_r($tpms);
        //  print_r($getConsdReport);
        // echo "</pre>";

        // die();

        $getRepoData = array();
        
        if(!empty($getConsdReport))
        {
            foreach($getConsdReport as $repData)
            {
                $repData = (object)$repData;
             
   $venueID = $repData->venueID;

                if($repData->Venue == 'Total Trainees')
                {
                    $getRepoData['total_venues'] = $repData->cntemployee_code;
                }
                else
                {
                    if($repData->course_name != 'Total Trainees Venue Wise')
                    {
                        $getRepoData[$venueID]['venueID'] = $repData->venueID;
                        $getRepoData[$venueID]['Venue'] = $repData->Venue;
                        $getRepoData[$venueID]['course_id']['code_'.$repData->course_id] = $repData->course_id;
                        $getRepoData[$venueID]['code_'.$repData->course_id]['course_name'] = $repData->course_name;
                        $getRepoData[$venueID]['code_'.$repData->course_id]['cntemployee_code'] = $repData->cntemployee_code;
                    }
                    else
                    {
                        $getRepoData[$venueID]['total_emp_venue_wise'] = $repData->cntemployee_code;
                    }
                }
            }
        }
        
        /*echo "<pre>";
         print_r($tpms);
        echo "</pre>";

        */ 
        /*echo "<pre>";
         print_r($getRepoData);
        die();*/

        $allData['menuid'] = $menuid;
        // $allData['tpms'] = $tpms;
        // $allData['get_consd_report'] = $getConsdReport;
        // $allData['consd_report'] = $consd_report;
        $allData['get_repo_data'] = $getRepoData;
         $getConsdReport2 = Yii::$app->tr_utility->trng_get_consolidated_report2();
         $getRepoData2 = array();
        //  echo "<pre>";
        //  print_r($getConsdReport2);
        // echo "</pre>";
          if(!empty($getConsdReport2))
        {
            foreach($getConsdReport2 as $repData)
            {
                $repData = (object)$repData;
             
               $venueID = $repData->venueID;

                if($repData->Venue == 'Total Trainees')
                {
                    $getRepoData2['total_venues'] = $repData->cntemployee_code; 
                }
                else
                {
                    if($repData->course_name != 'Total Trainees Venue Wise')
                    {
                        $getRepoData2[$venueID]['venueID'] = $repData->venueID;
                        $getRepoData2[$venueID]['Venue'] = $repData->Venue;
                        $getRepoData2[$venueID]['course_id']['code_'.$repData->course_id] = $repData->course_id;
                        $getRepoData2[$venueID]['code_'.$repData->course_id]['course_name'] = $repData->course_name;
                        $getRepoData2[$venueID]['code_'.$repData->course_id]['cntemployee_code'] = $repData->cntemployee_code;
                    }
                    else
                    {
                        $getRepoData2[$venueID]['total_emp_venue_wise'] = $repData->cntemployee_code;
                    }
                }
            }
        }


 $allData['get_repo_data2'] = $getRepoData2;
   // echo "<pre>";
   //       print_r($getRepoData2);
   //      echo "</pre>";

        return $this->render('trainingvenuereport', $allData);
    }

    public function actionTraininggroupmaster()
    {
        $this->layout = '@app/views/layouts/admin_layout.php';
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $employee_code = Yii::$app->user->identity->employee_code;

        $current_employee_code = Yii::$app->user->identity->employee_code;
        $current_employee_role_name = Yii::$app->user->identity->role_name;

        $data_arr['departments'] = Yii::$app->utility->getall_departments();
        $data_arr['course_master'] = Yii::$app->utility->getall_course_master();
        // $data_arr['districtName'] = Yii::$app->utility->getall_all_district_name();
        $data_arr['trgvenues'] = Yii::$app->utility->gettrgvenues();

        // gettrgvenues
        $data_arr['menuid'] = $menuid;

        // trng_course_master`
        /*echo "<pre>";
         print_r($data_arr);
        die();*/

        return $this->render('traininggroupmaster', $data_arr);       
    }

public function actionTrainingapply()
    {
        $this->layout = '@app/views/layouts/admin_layout.php';
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $employee_code = Yii::$app->user->identity->employee_code;

        $current_employee_code = Yii::$app->user->identity->employee_code;
        $current_employee_role_name = Yii::$app->user->identity->role_name;

        if(isset($_POST['Tpm']) AND !empty($_POST['Tpm'])){


            $dept_id = $_POST['Tpm']['deptInfo'];
            $course_Id = $_POST['Tpm']['beCourse'];
            $technology_id = $_POST['Tpm']['technology_id'];
            $tmp_id = $_POST['Tpm']['beprogram_code'];
            $venu_id = $_POST['Tpm']['trg_venue'];
            $district_id = $_POST['Tpm']['beDistrictid'];
            $status = $_POST['Tpm']['status'];
            $emp_id =$_POST['Tpm']['employee_id'];
             echo   $result = Yii::$app->utility->get_trng_employee($district_id,$course_Id,$dept_id,$tmp_id,$emp_id,$status);
     if($result==0)
     {
        Yii::$app->getSession()->setFlash('danger', 'Employee already exists.');
           // return $this->redirect($url);
     }
     else{
            Yii::$app->getSession()->setFlash('success', 'Details Added Successfully.');
           // return $this->redirect($url);
     }
        }




        $data_arr['departments'] = Yii::$app->utility->getall_departments();
        $data_arr['course_master'] = Yii::$app->utility->getall_course_master();
        // $data_arr['districtName'] = Yii::$app->utility->getall_all_district_name();
        $data_arr['trgvenues'] = Yii::$app->utility->gettrgvenues();

        // gettrgvenues
        $data_arr['menuid'] = $menuid;

        // trng_course_master`
        /*echo "<pre>";
         print_r($data_arr);
        die();*/

        return $this->render('trainingapply', $data_arr);       
    }



    // getdistrict_venue//

    public function actionGetdistrict_venue()
    {
        $allConcat = array();
        if(isset($_POST['vanueID']))
        {
            // echo "<pre>"; print_r($_POST); die();



            $vanueID = $_POST['vanueID'];
            
            $data_arr = Yii::$app->utility->getall_all_district_name($vanueID);
            $concat = '';

            $concat .= '<option>Select district</option>';

            if(!empty($data_arr))
            {
                foreach($data_arr as $dta)
                {
                    $dta = (object)$dta;
                    $concat .= '<option value="'.$dta->district_code.'">'.$dta->district_name.'</option>';
                }
            }

            $allConcat['district_data'] = $concat;
         }
        echo json_encode($allConcat);
        die();
    }

    public function actionGetemployeefortraining()
    {
        $district_id= $_POST['district_name'];
        $course_Id = $_POST['assign_Course'];
        $department_Id = $_POST['department_id'];
        $start_end_date_time = $_POST['start_end_date_time']; 
        $tech_id = $_POST['tech_id'];

        $allDta = Yii::$app->utility->getall_employeefortraining($district_id,$course_Id,$department_Id,$start_end_date_time,$tech_id);

        /*echo "<pre>";
         print_r($allDta);
        echo "</pre>";

        die();*/

        $html = $this->renderPartial('employeedata', array('employeesinfo'=>$allDta));
        $concat = '';

        $allConcat['traning_data'] = $html;

        echo json_encode($allConcat);
        die();
    }

    public function actionGettraningprogramfortraining()
    {
        $tech_Id = $_POST['crsID'];
        $department_Id = $_POST['department_id'];
        $Course_Id=$_POST['course_id'];

        $allDta = Yii::$app->utility->getall_traningprogramdata($Course_Id,$department_Id,$tech_Id );

        // echo "<pre>"; print_r($allDta); die();

        $concat = '';

        $concat .= '<option>Select</option>';
        foreach($allDta as $dta)
        {
            $dta = (object)$dta;

            // Programme_code
            // $concat .= '<option value="'.$dta->tpm_id.'">'.date('d-m-Y h:i:s',strtotime($dta->start_date)).' to '.date('d-m-Y h:i:s',strtotime($dta->end_date)).'</option>';
           
            $concat .= '<option datedisp="'.date('d-m-Y',strtotime($dta->start_date)).' to '.date('d-m-Y',strtotime($dta->end_date)).'" value="'.$dta->tpm_id.'">'.$dta->Programme_code.'</option>';
        }

        $allConcat['traning_tpm_data'] = $concat;

        echo json_encode($allConcat);
        die();
    }

    // gettraningprogramfortraining

    // createtraininggroup

    public function actionCreatetraininggroup()
    {
        $appliedarr = $_POST['appliedarr'];
        $department_id = $_POST['department_id'];
        $assign_Course = $_POST['assign_Course'];
        $tpm_id = $_POST['start_end_date_time'];
        $trg_venue_id = $_POST['trg_venue_id'];
        $district_id = $_POST['district_name'];

        $PARAMGroupType = 'T';


        $appliedarr = implode(',',$appliedarr);
        /*echo "<pre>"; print_r($_POST);
        die();*/

        $allDta = Yii::$app->utility->assign_trng_grp($department_id,$assign_Course,$PARAMGroupType,$tpm_id,$trg_venue_id,$appliedarr,$district_id);

        /*echo "<pre>"; print_r($allDta);
        die();*/


        if($allDta == 1)
        {
            $sts = 111;
            $message_show = 'Group has been successfully created.';            
        }
        elseif($allDta == 2)
        {
            $sts = 222;
            $message_show = 'The group name is already exist.';
        }
        else
        {
            $sts = 000;
            $message_show = 'There are somthing wrong please try again.';
        }


     

        $allConcat['status'] = $sts;
        $allConcat['message_show'] = $message_show;

        echo json_encode($allConcat);
        die();
    }

    public function actionGettraningdatavenuwise()
    {
        $crs_code = $_POST['crs_code'];
        $venueID = $_POST['venueID'];
        $dist_id = $_POST['dist_id'];
       $tech_id = $_POST['tech_id'];
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);

        $stateid = STATEID;
        
        $allDta = Yii::$app->tr_utility->get_trngdata_venue_wise($venueID,$crs_code,$stateid,$dist_id,$tech_id);


        $collectData['menuid'] = $menuid;
        $collectData['venueID'] = $venueID;
        $collectData['courseID'] = $crs_code;
        $collectData['dist_id'] = $dist_id;
        $collectData['employeestrainingdata'] = $allDta;

        // echo "<pre>"; print_r($allDta);
        // die();

        $html = $this->renderPartial('employees_training_data', $collectData);
        $concat = '';

        $allConcat['traning_data'] = $html;

        echo json_encode($allConcat);
        die();
    }
    public function actionInsertemployee()

    {
        $this->layout = '@app/views/layouts/admin_layout.php';
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."employee/trainings/trainingapply?securekey=$menuid";  
        


            $dept_id = $_POST['dept_id'];
            $course_Id = $_POST['course_Id'];
            $tech_id = $_POST['tech_id'];
            $tmp_id = $_POST['tmp_id'];
            $venu_id = $_POST['venu_id'];
            $district_id = $_POST['district_id'];
            $status = $_POST['status'];
            $emp_id =$_POST['emp_id'];
            echo $result = Yii::$app->utility->get_trng_employee($district_id,$course_Id,$dept_id,$tmp_id,$emp_id,$status);

     
       

    }

}
