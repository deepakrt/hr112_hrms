<?php
namespace app\modules\admin\controllers;
use yii;
use app\models\HrServiceDetails;
use app\models\HrDeptMapping;
class ManageservicedetailController extends \yii\web\Controller
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
     public function actionAdd()
    {
        $this->layout = '@app/views/layouts/admin_layout.php';
       
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);

        if(isset($_POST['save']))
        {
             $dept_id = $_POST['dept_id'];
            $fla_emp_code = $_POST['fla_emp_code'];
            $sla_emp_code = $_POST['sla_emp_code'];
             $designation_id = $_POST['designation_id'];
            $empcode = $_POST['abc'];
            Yii::$app->utility->add_service_details($empcode,$dept_id, $designation_id,$fla_emp_code,$sla_emp_code);
             Yii::$app->getSession()->setFlash('success', 'Employee Add Successfully.'); 
        }
         $emp_code = Yii::$app->utility->decryptString($_GET['securecode']);
         if(empty($menuid) OR empty($emp_code)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected.'); 
                return $this->redirect($url);
            }
           // $servicedetail = Yii::$app->utility->get_service_details($emp_code,'Full');
            $info = Yii::$app->utility->get_employees_by_empcode($emp_code);
              // echo "<pre>";print_r($info); die;
            if(empty($info)){
               // $menuid = Yii::$app->utility->encryptString($menuid);
                Yii::$app->getSession()->setFlash('danger', 'No record found.'); 
              return $this->redirect(Yii::$app->homeUrl."admin/manageservicedetail?securekey=$menuid");
            }
          return $this->render('add', ['info'=>$info, 'menuid'=>$menuid]);
    }
    
    public function actionIndex()
    {
        $this->layout = '@app/views/layouts/admin_layout.php';
        $allEmps = Yii::$app->utility->get_employees();
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
         if(isset($_POST['empcode']) AND !empty($_POST['empcode']))
        {
             $url = Yii::$app->homeUrl."admin/manageservicedetail?securekey=$menuid";          
           $emp_code = Yii::$app->utility->encryptString($_POST['empcode']);           
            $this->layout = '@app/views/layouts/admin_layout.php';
              return $this->redirect(Yii::$app->homeUrl."admin/manageservicedetail/add?securekey=$menuid&&securecode=$emp_code");
            //return $this->render('add', ['info'=>$info, 'menuid'=>$menuid]);
        }
        return $this->render('index', ['allEmps'=>$allEmps,'menuid'=>$menuid]);
    }
    
    public function actionView_OLD()
    {
        
        if(isset($_GET['securekey']) AND !empty($_GET['securekey']) AND isset($_GET['securecode']) AND !empty($_GET['securecode'])){
            $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
            $emp_code = Yii::$app->utility->decryptString($_GET['securecode']);
            if(empty($menuid) OR empty($emp_code)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected.'); 
                return $this->redirect(Yii::$app->homeUrl);
            }
            $servicedetail = Yii::$app->utility->get_service_details($emp_code,'Full');
            $info = Yii::$app->utility->get_employees($emp_code);
            //            echo "<pre>";print_r($servicedetail); die;
            if(empty($servicedetail) OR empty($info)){
                $menuid = Yii::$app->utility->encryptString($menuid);
                Yii::$app->getSession()->setFlash('danger', 'No record found.'); 
                return $this->redirect(Yii::$app->homeUrl."admin/manageservicedetail?securekey=$menuid");
            }
            $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
            $menuid = Yii::$app->utility->encryptString($menuid);
            $this->layout = '@app/views/layouts/admin_layout.php';
            return $this->render('view', ['servicedetail'=>$servicedetail, 'info'=>$info, 'menuid'=>$menuid]);
        }else{
            return $this->redirect(Yii::$app->homeUrl);
        }
    }

    public function actionView(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."admin/manageservicedetail?securekey=$menuid";
        
        if(isset($_POST['ser_id']) AND !empty($_POST['ser_id']))
        {

            $ser_id = Yii::$app->utility->decryptString($_POST['ser_id']);
            /*$fla_emp_code = Yii::$app->utility->decryptString($_POST['fla_emp_code']);
            $sla_emp_code = Yii::$app->utility->decryptString($_POST['sla_emp_code']);*/

            $fla_emp_code = $_POST['fla_emp_code'];
            $sla_emp_code = $_POST['sla_emp_code'];
            
            /*echo 'fla_emp_code-----'.$fla_emp_code;
            echo '<br>sla_emp_code-----'.$sla_emp_code;

            echo "<pre>"; print_r($_POST); die();*/


            if(empty($ser_id) OR empty($fla_emp_code) OR empty($sla_emp_code)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected.'); 
                return $this->redirect($url);
            }
            $dept_id = $_POST['dept_id'];
            //            echo "<pre>";print_r($_POST); 
           
            $model = HrServiceDetails::find()->where(['ser_id'=>$ser_id, 'is_active'=>'Y'])->one();

            // echo "<pre>";print_r($model); die();

            // echo '+----'.$dept_id.'------'.$model->dept_id.'--'.$_POST['designation_id']; die();
            
            if($dept_id != $model->dept_id)
            {
                $oldMap = HrDeptMapping::find()->where(['dept_id'=>$model->dept_id, 'employee_code'=>$model->employee_code])->one();
                if(!empty($oldMap)){
                    $oldMap->is_active = 'N';
                    $oldMap->last_updated=date('Y-m-d H:i:s');
                    $oldMap->updated_by=Yii::$app->user->identity->e_id;
                    $oldMap->save();
                }
                $newMap = HrDeptMapping::find()->where(['dept_id'=>$dept_id, 'employee_code'=>$model->employee_code])->one();
                if(!empty($newMap)){
                    $newMap->is_active = 'Y';
                    $newMap->save();
                }else{
                    $new = new HrDeptMapping();
                    $new->employee_code=$model->employee_code;
                    $new->dept_id=$dept_id;
                    $new->role_id="3";
                    $new->created_date=date('Y-m-d H:i:s');
                    $new->updated_by=Yii::$app->user->identity->e_id;
                    $new->is_active = 'Y';
                    $new->save();
                }
            }

            // echo "<pre>";print_r($model); die;            
            
            $model->designation_id = $_POST['designation_id'];
            $model->dept_id = $dept_id;
            $model->authority1 = $fla_emp_code;
            $model->authority2 = $sla_emp_code;

            /*echo '============='.$model->authority1;
            echo '============='.$model->authority2;
            echo "<pre>";print_r($model); */
           /* die;*/

            /*echo "<pre>";print_r($model->save()); die('okkkkkkkkkkkkkk'); 
            
            if($model->save())
            {
            }*/
            //   return $this->redirect($url);

             Yii::$app->utility->update_service_details($model);
             
            Yii::$app->getSession()->setFlash('success', 'Employee Updated Successfully.'); 
        }

        if(isset($_GET['securekey']) AND !empty($_GET['securekey']) AND isset($_GET['securecode']) AND !empty($_GET['securecode'])){
            $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
            $emp_code = Yii::$app->utility->decryptString($_GET['securecode']);
            if(empty($menuid) OR empty($emp_code)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected.'); 
                return $this->redirect($url);
            }
            $servicedetail = Yii::$app->utility->get_service_details($emp_code,'Full');
            $info = Yii::$app->utility->get_employees($emp_code);
            //            echo "<pre>";print_r($servicedetail); die;
            if(empty($servicedetail) OR empty($info)){
                $menuid = Yii::$app->utility->encryptString($menuid);
                Yii::$app->getSession()->setFlash('danger', 'No record found.'); 
                return $this->redirect(Yii::$app->homeUrl."admin/manageservicedetail?securekey=$menuid");
            }
            $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
            $menuid = Yii::$app->utility->encryptString($menuid);
            $this->layout = '@app/views/layouts/admin_layout.php';
            return $this->render('view', ['servicedetail'=>$servicedetail, 'info'=>$info, 'menuid'=>$menuid]);
        }else{
            return $this->redirect($url);
        }
    }

    public function actionUpdate(){
        echo "<pre>";print_r($_GET); die;
    }

}