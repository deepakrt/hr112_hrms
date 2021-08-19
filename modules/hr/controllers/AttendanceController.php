<?php
namespace app\modules\hr\controllers;
use yii;
class AttendanceController extends \yii\web\Controller
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
        $st = "All Attendance";
        $attndn = Yii::$app->hr_utility->hr_get_attendance(Yii::$app->user->identity->role,NULL, NULL, NULL, NULL, "Submitted", Yii::$app->user->identity->e_id);
        
        return $this->render('index', ['menuid'=>$menuid, 'attndn'=>$attndn, 'st'=>$st]);
    }

    public function actionGetattendence_date_wise()
    {
        $atten_view_type = $_POST['atten_view_type'];
        $type_month = $_POST['type_month'];
        $type_year = $_POST['type_year'];
        $dept_id = $_POST['dept_id'];
        $employment_type = $_POST['employment_type'];


        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);


        if($atten_view_type == 'Day')
        {
            $attenDate = date('Y-m-d', strtotime($_POST['attendate']));
            if($attenDate == '1970-01-01'){
                $result['Status']='FF';
                $result['Res']='Invalid Date';
                echo json_encode($result);die;
            }            
        }
        else
        {
            $attenDate = $type_year.'-'.$type_month;
        }


        $collectData['menuid'] = $menuid;

        // $attid = NULL;
        $collectData['chkAtten'] = Yii::$app->hr_utility->hr_get_attendance_date_dept_wise(Yii::$app->user->identity->role, $atten_view_type, NULL, NULL, $attenDate, "Submitted", Yii::$app->user->identity->e_id,$dept_id,$employment_type);

        // echo "<pre>"; print_r($collectData); die();


        $html = $this->renderPartial('employees_attendance_data', $collectData);
        $concat = '';

        $allConcat['attend_data'] = $html;

        echo json_encode($allConcat);
        die();

    }
}
