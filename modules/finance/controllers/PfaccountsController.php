<?php

namespace app\modules\finance\controllers;
use Yii;
class PfaccountsController extends \yii\web\Controller
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
        $this->layout = '@app/views/layouts/admin_layout.php';
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $pfacs = Yii::$app->finance->pf_get_accounts(NULL);
        return $this->render('index', ['menuid'=>$menuid, 'pfacs'=>$pfacs]);
    }
    
    public function actionEditaccountdetails(){
        $this->layout = '@app/views/layouts/admin_layout.php';
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."finance/pfaccounts?securekey=$menuid";
        if(isset($_POST['PF']) AND !empty($_POST['PF'])){
            $post = $_POST['PF'];
            $pfid = Yii::$app->utility->decryptString($post['pfid']);
            $ec = Yii::$app->utility->decryptString($post['ec']);
            if(empty($pfid) OR empty($ec)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid params value found.'); 
                return $this->redirect($url);
            }
            $uan_number = trim(preg_replace('/[^A-Za-z0-9\/-]/', '', $post['uan_number']));
            $pf_number = trim(preg_replace('/[^A-Za-z0-9\/-]/', '', $post['pf_number']));
            $fpf_account = trim(preg_replace('/[^A-Za-z0-9\/-]/', '', $post['fpf_account']));
            
            $vpf_deduct = trim(preg_replace('/[^A-Z-]/', '', $post['vpf_deduct']));
            $is_eligible_fpf = trim(preg_replace('/[^A-Z-]/', '', $post['is_eligible_fpf']));
            $is_active = trim(preg_replace('/[^A-Z-]/', '', $post['is_active']));
            
            $vpf_deduct1 = Yii::$app->finance->checkYesNo($vpf_deduct);
            $is_eligible_fpf1 = Yii::$app->finance->checkYesNo($is_eligible_fpf);
            $is_active1 = Yii::$app->finance->checkYesNo($is_active);
            
            if(empty($vpf_deduct1) OR empty($is_eligible_fpf1) OR empty($is_active1)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid params value found.'); 
                return $this->redirect($url);
            }
            
            $subscription_date = date('Y-m-d', strtotime($post['subscription_date']));
            
            $result = Yii::$app->finance->pf_add_update_account($pfid, $ec, $uan_number, $pf_number, $subscription_date, $fpf_account, $vpf_deduct, $is_eligible_fpf, $is_active);
            
            if($result == '2'){
                $alert = "success";
                $msg = "PF Account Details Updated Successfully.";
            }else{
                $alert = "danger";
                $msg = "PF Account Details didn't Updated. Contact Admin";
            }
            /*
             * Logs
             */
            $logs['pfid']=$pfid;
            $logs['ec']=$ec;
            $logs['uan_number']=$uan_number;
            $logs['pf_number']=$pf_number;
            $logs['subscription_date']=$subscription_date;
            $logs['fpf_account']=$fpf_account;
            $logs['vpf_deduct']=$vpf_deduct;
            $logs['is_eligible_fpf']=$is_eligible_fpf;
            $logs['is_active']=$is_active;
            $jsonlogs= json_encode($logs);
            
            Yii::$app->utility->activities_logs("PF", NULL, $ec, $jsonlogs, $msg);
            
            Yii::$app->getSession()->setFlash($alert, $msg); 
            return $this->redirect($url);
        }
        if(isset($_GET['key']) AND !empty($_GET['key']) AND isset($_GET['key1']) AND !empty($_GET['key1'])){
            $pfid = Yii::$app->utility->decryptString($_GET['key']);
            $ec = Yii::$app->utility->decryptString($_GET['key1']);
            if(empty($pfid) OR empty($ec)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid params value found.'); 
                return $this->redirect($url);
            }
            $pfacs = Yii::$app->finance->pf_get_accounts($ec);
            if(empty($pfacs)){
                Yii::$app->getSession()->setFlash('danger', 'No PF Account Details found.'); 
                return $this->redirect($url);
            }
            $pfid = Yii::$app->utility->encryptString($pfid);
            $ec = Yii::$app->utility->encryptString($ec);
            return $this->render('editaccountdetails', ['menuid'=>$menuid, 'pfacs'=>$pfacs, 'pfid'=>$pfid, 'ec'=>$ec]);
          
        }
        Yii::$app->getSession()->setFlash('danger', 'Invalid params found.'); 
        return $this->redirect($url);
    }
    
    public function actionGetpfsummary(){
        if(isset($_POST['ec']) AND !empty($_POST['ec'])){
            $ec = Yii::$app->utility->decryptString($_POST['ec']);
            if(empty($ec)){
                $result['Status'] = 'SS';
                $result['Res'] = 'Invalid params value found';
                echo json_encode($result);
                die;
            }
            $pfacc = Yii::$app->finance->pf_get_accounts($ec);
            if(empty($pfacc)){
                $result['Status'] = 'FF';
                $result['Res'] = 'Employee Account Details not found.';
                echo json_encode($result);
                die;
            }
            $record = Yii::$app->finance->pf_get_monthwise_details(NULL, NULL, NULL, $ec, "Paid");
            $html = "";
            $sd = date('d-M-Y', strtotime($pfacc['subscription_date']));
            $html .= "
                <table class='table table-bordered'>
                    <tr>
                        <th>Emp ID</th>
                        <th>".$pfacc['employee_code']."</th>
                        <th>Name</th>
                        <th>".$pfacc['fullname']."</th>
                        <th>PAN Number</th>
                        <th>".$pfacc['pan_number']."</th>
                    </tr>
                    <tr>
                        <th>PF Number</th>
                        <th>".$pfacc['pf_number']."</th>
                        <th>UAN Number</th>
                        <th>".$pfacc['uan_number']."</th>
                        <th>Subscription Date</th>
                        <th>$sd</th>
                    </tr>
                </table><hr>
                <table class='table table-bordered'>
                    <tr>
                        <th>Sr. No.</th>
                        <th>Financial Year</th>
                        <th>Period</th>
                        <th>Member PF</th>
                        <th>Member VPF</th>
                        <th>Employer PF</th>
                        <th>Employer FPF</th>
                        <th>Status</th>
                    </tr>";
            if(!empty($record)){
                $i=1;
                foreach($record as $pf){
                    
                    $d = $pf['pf_year']."-".$pf['pf_month']."-01";
                    $my = date('M-Y', strtotime($d));
                    
                
                  $html .= "<tr>
                        <td>$i</td>
                        <td>".$pf['financial_year']."</td>
                        <td>$my</td>
                        <td>".number_format($pf['member_pf'], 2)."</td>
                        <td>".number_format($pf['member_vpf'], 2)."</td>
                        <td>".number_format($pf['employer_pf'], 2)."</td>
                        <td>".number_format($pf['employer_fpf'], 2)."</td>
                        <td>".$pf['status']."</td>
                        </tr>";
                    $i++;
                }
            }else{
                $html .= "<tr><td colspan='7'>No PF Record Found</td></tr>";
            }
                    
            $html .= "</table>";
           
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
