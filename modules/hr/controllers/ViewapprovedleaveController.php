<?php
namespace app\modules\hr\controllers;
use yii;
class ViewapprovedleaveController extends \yii\web\Controller
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
        $this->layout = '@app/views/layouts/admin_layout.php';
        if(Yii::$app->user->identity->role == '5'){
            $apps = Yii::$app->hr_utility->hr_get_leave_request(NULL,"Approved,Rejected");

            // echo "<pre>------------";print_r($apps); die();
        }else{
           /* $emplist = Yii::$app->hr_utility->hr_get_appraise_list();
            $apps="";
            if(!empty($emplist)){
                $list="";
                foreach($emplist as $e){
                    $list .=$e['employee_code'].",";
                }
                $list = rtrim($list, ",");
                $apps = Yii::$app->hr_utility->hr_get_leave_request($list,"ABRA,In-Process,Approved,Rejected");
            }*/

            $apps = Yii::$app->hr_utility->hr_get_leave_requests(Yii::$app->user->identity->e_id,"ABRA,In-Process,Approved,Rejected");
        }
        return $this->render('index', ['menuid'=>$menuid, 'apps'=>$apps]);
    }
    
    public function actionViewleaverequests(){
        
        if(isset($_GET['key']) && !empty($_GET['key']) AND isset($_GET['key1']) && !empty($_GET['key1']) ){
            $leave_app_id = Yii::$app->utility->decryptString($_GET['key']);
            $ec = Yii::$app->utility->decryptString($_GET['key1']);
            if(empty($leave_app_id) OR empty($ec)){
                $result['Status']='FF';
                $result['Res']='Invalid ID';
                echo json_encode($result);
                die;
            }
            
            $leaves = Yii::$app->hr_utility->hr_get_leaves('R', $ec, $leave_app_id, "ABRA,Approved,Rejected");
            $html = "<table class='table table-bordered'>
                <tr>
                    <th>Leave Type</th>
                    <th>From Date</th>
                    <th>To Date</th>
                    <th>Total Days</th>
                </tr>";
            $detail = "";
            if(empty($leaves)){
                $detail = " <tr>
                    <td rowspan='4'>No Record Found</td>
                </tr>";
            }else{
                $detail = "";
                foreach($leaves as $l){
                    
                    $desc = $l['desc'];
                    $req_from_date = date('d-m-Y', strtotime($l['req_from_date']));
                    $req_to_date = date('d-m-Y', strtotime($l['req_to_date']));
                    $totaldays = $l['totaldays'];
                    $detail .= " <tr>
                        <td>$desc</td>
                        <td>$req_from_date</td>
                        <td>$req_to_date</td>
                        <td>$totaldays</td>
                    </tr>";
                }
            }
            $html = $html.$detail;
            $result['Status']='SS';
            $result['Res']=$html;
            echo json_encode($result);
            die;
        }else{
            $result['Status']='FF';
            $result['Res']='Invalid params found';
            echo json_encode($result);
            die;
        }
    }
}

