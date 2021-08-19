<?php

namespace app\modules\finance\controllers;
use Yii;
class SalarydetailsController extends \yii\web\Controller
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
    public function actionIndex()
    {
        $this->layout = '@app/views/layouts/admin_layout.php';
        $allEmps = Yii::$app->utility->get_employees();
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        return $this->render('index', ['allEmps'=>$allEmps, 'menuid'=>$menuid]);
    }

    public function actionViewdetail(){
        if(isset($_GET['securecode']) AND !empty($_GET['securecode'])){
            $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
            $menuid = Yii::$app->utility->encryptString($menuid);
            $url = Yii::$app->homeUrl."finance/salarydetails?securekey=$menuid";
            $employee_code = Yii::$app->utility->decryptString($_GET['securecode']);
//            die($employee_code);
            if(empty($employee_code)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid ID.');
                return $this->redirect($url);
            }
            $info = Yii::$app->utility->get_employees($employee_code);
            if(empty($info)){
                Yii::$app->getSession()->setFlash('danger', 'No Record found.');
                return $this->redirect($url);
            }
            $salaryInfo = Yii::$app->finance->get_emp_yearly_sal($employee_code, 'Projected,Paid');
            if(empty($salaryInfo)){
                Yii::$app->getSession()->setFlash('danger', 'Salary detail not found. Contact Admin.');
                return $this->redirect($url);
            }
            $this->layout = '@app/views/layouts/admin_layout.php';
            return $this->render('viewdetail', ['info'=>$info, 'salaryInfo'=>$salaryInfo, 'menuid'=>$menuid]);
            
        }else{
            return $this->redirect(Yii::$app->homeUrl);
        }
    }
    public function actionUpdatesalary(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."finance/salarydetails?securekey=$menuid";
        if(isset($_POST['Salary']) AND !empty($_POST['Salary'])){
            $post = $_POST['Salary'];
            $employee_code = Yii::$app->utility->decryptString($post['employee_code']);
            $salMonth = Yii::$app->utility->decryptString($post['salMonth']);
            $salYear = Yii::$app->utility->decryptString($post['salYear']);
            
            if(empty($employee_code) OR empty($salMonth) OR empty($salYear)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid params value');
                return $this->redirect($url);
            }
            
            $allowance_da_arrear = trim(preg_replace('/[^0-9-]/', '', $post['allowance_da_arrear']));
            $allowance_ta_arrear = trim(preg_replace('/[^0-9-]/', '', $post['allowance_ta_arrear']));
            $ded_pf_on_arrear = trim(preg_replace('/[^0-9-]/', '', $post['ded_pf_on_arrear']));
            $ded_incomeTax = trim(preg_replace('/[^0-9-]/', '', $post['ded_incomeTax']));
            $ded_lfee = trim(preg_replace('/[^0-9-]/', '', $post['ded_lfee']));
            $ded_club = trim(preg_replace('/[^0-9-]/', '', $post['ded_club']));
            $ded_GSLI = trim(preg_replace('/[^0-9-]/', '', $post['ded_GSLI']));
            $ded_BenevolentFund = trim(preg_replace('/[^0-9-]/', '', $post['ded_BenevolentFund']));
            $child_edu = trim(preg_replace('/[^0-9-]/', '', $post['child_edu']));
            $other_income = trim(preg_replace('/[^0-9-]/', '', $post['other_income']));
            $perq_lease = trim(preg_replace('/[^0-9-]/', '', $post['perq_lease']));
            $perq_medical_reimbursement = trim(preg_replace('/[^0-9-]/', '', $post['perq_medical_reimbursement']));
            $perq_interest = trim(preg_replace('/[^0-9-]/', '', $post['perq_interest']));
            $hra_exemption = trim(preg_replace('/[^0-9-]/', '', $post['hra_exemption']));
            $transport_exemption = trim(preg_replace('/[^0-9-]/', '', $post['transport_exemption']));
            $child_education_allowance_exemption = trim(preg_replace('/[^0-9-]/', '', $post['child_education_allowance_exemption']));
            $other_income_reported_by_employee = trim(preg_replace('/[^0-9-]/', '', $post['other_income_reported_by_employee']));
            $income_from_house_property = trim(preg_replace('/[^0-9-]/', '', $post['income_from_house_property']));
            $previous_employer_income = trim(preg_replace('/[^0-9-]/', '', $post['previous_employer_income']));
            $professional_tax = trim(preg_replace('/[^0-9-]/', '', $post['professional_tax']));
            $loss_on_house_property = trim(preg_replace('/[^0-9-]/', '', $post['loss_on_house_property']));
            
            $result = Yii::$app->finance->fn_update_emp_salary($employee_code, $salMonth, $salYear, $allowance_da_arrear, $allowance_ta_arrear, $ded_pf_on_arrear, $ded_incomeTax, $ded_lfee, $ded_club, $ded_GSLI, $ded_BenevolentFund, $child_edu, $other_income, $perq_lease, $perq_medical_reimbursement, $perq_interest, $hra_exemption, $transport_exemption, $child_education_allowance_exemption, $other_income_reported_by_employee, $income_from_house_property, $previous_employer_income, $professional_tax, $loss_on_house_property);
            
            $code = Yii::$app->utility->encryptString($employee_code);
            $month = Yii::$app->utility->encryptString($salMonth);
            $year = Yii::$app->utility->encryptString($salYear);
            
            $url = Yii::$app->HomeUrl."finance/salarydetails/updatesalary?securekey=$menuid&securecode=$code&key=$month&key1=$year";
            $msg = "Salary Details Updated Successfully";
            $type = "success";
            if($result == '2'){
                $msg = "Salary Details Not Found";
                $type = "danger";
            }
            
            /*
             * Logs
             */
            $logs['employee_code']=$employee_code;
            $logs['month']=$salMonth;
            $logs['year']=$salYear;
            $logs['allowance_da_arrear']=$allowance_da_arrear;
            $logs['allowance_ta_arrear']=$allowance_ta_arrear;
            $logs['ded_pf_on_arrear']=$ded_pf_on_arrear;
            $logs['ded_incomeTax']=$ded_incomeTax;
            $logs['ded_lfee']=$ded_lfee;
            $logs['ded_club']=$ded_club;
            $logs['ded_GSLI']=$ded_GSLI;
            $logs['ded_BenevolentFund']=$ded_BenevolentFund;
            $logs['child_edu']=$child_edu;
            $logs['other_income']=$other_income;
            $logs['perq_lease']=$perq_lease;
            $logs['perq_medical_reimbursement']=$perq_medical_reimbursement;
            $logs['perq_interest']=$perq_interest;
            $logs['hra_exemption']=$hra_exemption;
            $logs['transport_exemption']=$transport_exemption;
            $logs['child_education_allowance_exemption']=$child_education_allowance_exemption;
            $logs['other_income_reported_by_employee']=$other_income_reported_by_employee;
            $logs['income_from_house_property']=$income_from_house_property;
            $logs['previous_employer_income']=$previous_employer_income;
            $logs['professional_tax']=$professional_tax;
            $logs['loss_on_house_property']=$loss_on_house_property;
            $jsonlogs = json_encode($logs);
            
            Yii::$app->utility->activities_logs("Finance", NULL, $employee_code, $jsonlogs, $msg);
            
            Yii::$app->getSession()->setFlash($type, $msg);
            return $this->redirect($url);
        }
        if(isset($_GET['securecode']) AND !empty($_GET['securecode']) AND isset($_GET['key']) AND !empty($_GET['key']) AND isset($_GET['key1']) AND !empty($_GET['key1'])){
            $employee_code = Yii::$app->utility->decryptString($_GET['securecode']);
            $month = Yii::$app->utility->decryptString($_GET['key']);
            $year = Yii::$app->utility->decryptString($_GET['key1']);
            if(empty($employee_code) OR empty($month) OR empty($year)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid ID.');
                return $this->redirect($url);
            }
            $info = Yii::$app->utility->get_employees($employee_code);
            if(empty($info)){
                Yii::$app->getSession()->setFlash('danger', 'No Record found.');
                return $this->redirect($url);
            }
            $salaryInfo = Yii::$app->finance->get_emp_yearly_sal($employee_code, 'Projected,Paid',$month, $year);
            if(empty($salaryInfo)){
                Yii::$app->getSession()->setFlash('danger', 'Salary Details Not Found.');
                return $this->redirect($url);
            }
            $this->layout = '@app/views/layouts/admin_layout.php';
            return $this->render('updatesalary', ['info'=>$info, 'salaryInfo'=>$salaryInfo, 'menuid'=>$menuid]);
        }else{
            return $this->redirect(Yii::$app->homeUrl);
        }
    }
}
