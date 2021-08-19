<?php

namespace app\modules\admin\controllers;
use yii;

class ViewassignedleavesController extends \yii\web\Controller
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
        $allEmps = Yii::$app->utility->get_employees();
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('index', ['allEmps'=>$allEmps, 'menuid'=>$menuid]);
    }
    
    
    public function actionGetleavedetail() {
        if(isset($_GET['code']) AND !empty($_GET['code'])){
            $code = Yii::$app->utility->decryptString($_GET['code']);
            $info = Yii::$app->utility->get_employee_leaves($code);
            if(empty($info)){
                $result['Status']='FF';
                $result['Res']='Invalid Employee ID';
                echo json_encode($result);
                die;
            }
             $html = "<tr>
                        <th></th>
                        <th>Session Year</th>
                        <th>Balance</th>
                        <th>Pending</th>
                        <th>Available</th>
                    </tr>";
            foreach($info as $in){
                $bal=$in['balance_leaves'];
                $pending=$in['pending_leaves'];
                
                $avail=$bal-$pending;
                $avail = number_format($avail,1);
                $desc=$in['desc']." [".$in['label']."]";
                $session_year=$in['session_year'];
                $html .= "<tr>
                        <td>$desc</td>
                        <td>$session_year</td>
                        <td>$bal</td>
                        <td>$pending</td>
                        <td>$avail</td>
                        </tr>";
            }
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
