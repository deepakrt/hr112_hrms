<?php
namespace app\modules\admin\controllers;
use yii;

class ManagetrainingsController extends \yii\web\Controller
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
        $tpms = Yii::$app->tr_utility->tr_get_trainingprograms(NULL);
       
        return $this->render('index', ['menuid'=>$menuid,'tpms'=>$tpms]);
    }

     public function actionTechnology(){
        $this->layout = '@app/views/layouts/admin_layout.php';
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $technologies = Yii::$app->tr_utility->tr_get_technologies(0);

        return $this->render('technology', ['menuid'=>$menuid,'technologies'=>$technologies]);
    }



public function actionAddtrainingprogram(){

    $this->layout = '@app/views/layouts/admin_layout.php';
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."admin/managetrainings/index?securekey=$menuid";
       
    $err=0;
   
    $technologies = Yii::$app->tr_utility->tr_get_technologies(NULL);
    $courses = Yii::$app->tr_utility->tr_get_courses(NULL);
   // $departments = Yii::$app->tr_utility->tr_get_departments(NULL);
     $departments = Yii::$app->tr_utility->getall_departments1();
    $trainers = Yii::$app->tr_utility->tr_get_trainers(NULL);

    $roles =  Yii::$app->utility->get_roles(NULL);


    if(isset($_POST['Tpm']) AND !empty($_POST['Tpm'])){



            $course_id = $_POST['Tpm']['course_id'];
            $technology_id = $_POST['Tpm']['technology_id'];
            $course_code = $_POST['Tpm']['course_code'];
            $startDate = $_POST['Tpm']['startDate'];
            $endDate = $_POST['Tpm']['endDate'];
            $startTime = $_POST['Tpm']['startTime'];

            $endTime = $_POST['Tpm']['endTime'];
            $training_fees = $_POST['Tpm']['training_fees'];
            $installment = $_POST['Tpm']['installment'];
            $trainer_id = $_POST['Tpm']['trainer_id'];
            $trainer_amt = $_POST['Tpm']['trainer_amt'];
            $department_id = $_POST['Tpm']['department_id'];
            $seats = $_POST['Tpm']['seats'];
            $role_id = $_POST['Tpm']['role_id'];
/*echo 'A'.'<br>'.NULL.'<br>'.$course_id.'<br>'.$technology_id.'<br>'.$course_code.'<br>'.$startDate.'<br>'.$endDate.'<br>'.$startTime.'<br>'.$endTime.'<br>'.$training_fees.'<br>'.$installment.'<br>'.$trainer_id.'<br>'.$trainer_amt.'<br>'.$department_id.'<br>'.$seats.'<br>'.$role_id;
die;*/
            
            //argument A for Add record
            $result = Yii::$app->tr_utility->tr_add_update_trainingprogram('A',NULL,$course_id,$technology_id,$course_code,$startDate,$endDate,$startTime,$endTime,$training_fees,$installment,$trainer_id,$trainer_amt,$department_id,$seats,$role_id);
            if($result != '1'){
                $err = 1;
            }
             if($err == '0'){
            
            
            Yii::$app->getSession()->setFlash('success', 'Details Added Successfully.');
            return $this->redirect($url);
        }else{
           
            Yii::$app->getSession()->setFlash('danger', 'Entry Not Added. Contact Admin.');
            return $this->redirect($url);
        }
            
     }

        return $this->render('addtrainingprogram', ['menuid'=>$menuid,'technologies'=>$technologies,'courses'=>$courses,'departments'=>$departments,'trainers'=>$trainers,'roles'=>$roles]);

}

    public function actionAddnewtechnology(){

    	$this->layout = '@app/views/layouts/admin_layout.php';
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."admin/managetrainings/technology?securekey=$menuid";
       
    $err=0;
   

    if(isset($_POST['Technology']) AND !empty($_POST['Technology'])){

            $name = $_POST['Technology']['technology_name'];
            $code = $_POST['Technology']['technology_code'];
            
            //argument A for Add record
            $result = Yii::$app->tr_utility->tr_add_update_technology(NULL,'A',$name,$code);
            if($result != '1'){
                $err = 1;
            }
             if($err == '0'){
            
            
            Yii::$app->getSession()->setFlash('success', 'Details Added Successfully.');
            return $this->redirect($url);
        }else{
           
            Yii::$app->getSession()->setFlash('danger', 'Entry Not Added. Contact Admin.');
            return $this->redirect($url);
        }
            
     }

    
      
 


        return $this->render('addtechnology', ['menuid'=>$menuid]);

    }

   public function actionRemovetechnology(){

   		
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
		
		$url = Yii::$app->homeUrl."admin/managetrainings/technology?securekey=$menuid";
       
        $technology_id = $_GET['technology_id'];

$err=0;
         $result = Yii::$app->tr_utility->tr_add_update_technology($technology_id,'D',NULL,NULL);
            if($result != '1'){
                $err = 1;
            }
             if($err == '0'){
            
            
            Yii::$app->getSession()->setFlash('success', 'Record Removed Successfully.');
            return $this->redirect($url);
        }else{
           
            Yii::$app->getSession()->setFlash('danger', 'Entry Not Deleted. Contact Admin.');
            return $this->redirect($url);
        }
       
       

   }

   public function actionRemovetrainingprogram(){

        
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        
        $url = Yii::$app->homeUrl."admin/managetrainings/index?securekey=$menuid";
       
        $tpm_id = $_GET['tpm_id'];

$err=0;
         $result = Yii::$app->tr_utility->tr_add_update_trainingprogram('D',$tpm_id,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
            if($result != '1'){
                $err = 1;
            }
             if($err == '0'){
            
            
            Yii::$app->getSession()->setFlash('success', 'Record Removed Successfully.');
            return $this->redirect($url);
        }else{
           
            Yii::$app->getSession()->setFlash('danger', 'Entry Not Deleted. Contact Admin.');
            return $this->redirect($url);
        }
       
       

   }


   

   public function actionActivatechnology(){

   		
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
		
		$url = Yii::$app->homeUrl."admin/managetrainings/technology?securekey=$menuid";
       
        $technology_id = $_GET['technology_id'];
        
$err=0;
         $result = Yii::$app->tr_utility->tr_add_update_technology($technology_id,'AC',NULL,NULL);
            if($result != '1'){
                $err = 1;
            }
             if($err == '0'){
            
            
            Yii::$app->getSession()->setFlash('success', 'Record Activated Successfully.');
            return $this->redirect($url);
        }else{
           
            Yii::$app->getSession()->setFlash('danger', 'Entry Not Activated. Contact Admin.');
            return $this->redirect($url);
        }
       
       

   }
     public function actionUpdatetechnology(){

   		
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
		
		$url = Yii::$app->homeUrl."admin/managetrainings/technology?securekey=$menuid";
        
        $this->layout = '@app/views/layouts/admin_layout.php';
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $leaves = Yii::$app->hr_utility->hr_get_leaves_chart();
        $technology_id = $_GET['technology_id'];
        
        $technologies = Yii::$app->tr_utility->tr_get_technologies($technology_id);

       
        

       
        $err = 0;
if(isset($_POST['Technology']) AND !empty($_POST['Technology'])){

            $name = $_POST['Technology']['technology_name'];
            $code = $_POST['Technology']['technology_code'];
            
            //argument A for Add record
            $result = Yii::$app->tr_utility->tr_add_update_technology($technology_id,'U',$name,$code);
            if($result != '1'){
                $err = 1;
            }
             if($err == '0'){
            
            
            Yii::$app->getSession()->setFlash('success', 'Details Updated Successfully.');
            return $this->redirect($url);
        }else{
           
            Yii::$app->getSession()->setFlash('danger', 'Entry Not Updated. Contact Admin.');
            return $this->redirect($url);
        }
            
     }

 return $this->render('updatetechnology', ['menuid'=>$menuid,'technologies'=>$technologies]);
   }




public function actionUpdatetrainingprogram(){

        
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $err = 0;
        $url = Yii::$app->homeUrl."admin/managetrainings/index?securekey=$menuid";
        
        $this->layout = '@app/views/layouts/admin_layout.php';
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        
        $tpm_id = $_GET['tpm_id'];
        
        $tpms = Yii::$app->tr_utility->tr_get_trainingprograms($tpm_id);

       if(isset($_GET['check']) && $_GET['check']=='activate'){

             $result = Yii::$app->tr_utility->tr_add_update_trainingprogram('AC',$tpm_id,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
            if($result != '1'){
                $err = 1;
            }
             echo '<pre>';
print_r($result); die;
             if($err == '0'){
            
                Yii::$app->getSession()->setFlash('success', 'Request processed Successfully.');
                return $this->redirect($url);

            }else{
               
                Yii::$app->getSession()->setFlash('danger', 'Some Error Occured. Contact Admin.');
                return $this->redirect($url);
            }
       }
        

       
        

 $technologies = Yii::$app->tr_utility->tr_get_technologies(NULL);
    $courses = Yii::$app->tr_utility->tr_get_courses(NULL);
    $departments = Yii::$app->tr_utility->tr_get_departments(NULL);
    $trainers = Yii::$app->tr_utility->tr_get_trainers(NULL);

    $roles =  Yii::$app->utility->get_roles(NULL);


    if(isset($_POST['Tpm']) AND !empty($_POST['Tpm'])){



            $course_id = $_POST['Tpm']['course_id'];
            $technology_id = $_POST['Tpm']['technology_id'];
            $course_code = $_POST['Tpm']['course_code'];
            $startDate = $_POST['Tpm']['startDate'];
            $endDate = $_POST['Tpm']['endDate'];
            $startTime = $_POST['Tpm']['startTime'];

            $endTime = $_POST['Tpm']['endTime'];
            $training_fees = $_POST['Tpm']['training_fees'];
            $installment = $_POST['Tpm']['installment'];
            $trainer_id = $_POST['Tpm']['trainer_id'];
            $trainer_amt = $_POST['Tpm']['trainer_amt'];
            $department_id = $_POST['Tpm']['department_id'];
            $seats = $_POST['Tpm']['seats'];
            $role_id = $_POST['Tpm']['role_id'];
/*echo 'A'.'<br>'.NULL.'<br>'.$course_id.'<br>'.$technology_id.'<br>'.$course_code.'<br>'.$startDate.'<br>'.$endDate.'<br>'.$startTime.'<br>'.$endTime.'<br>'.$training_fees.'<br>'.$installment.'<br>'.$trainer_id.'<br>'.$trainer_amt.'<br>'.$department_id.'<br>'.$seats.'<br>'.$role_id;
die;*/
            
            //argument A for Add record
            $result = Yii::$app->tr_utility->tr_add_update_trainingprogram('U',$tpm_id,$course_id,$technology_id,$course_code,$startDate,$endDate,$startTime,$endTime,$training_fees,$installment,$trainer_id,$trainer_amt,$department_id,$seats,$role_id);
            if($result != '1'){
                $err = 1;
            }
             if($err == '0'){
            
            
            Yii::$app->getSession()->setFlash('success', 'Details Updated Successfully.');
            return $this->redirect($url);
        }else{
           
            Yii::$app->getSession()->setFlash('danger', 'Entry Not Updated. Contact Admin.');
            return $this->redirect($url);
        }
            
     }

        return $this->render('updatetrainingprogram', ['menuid'=>$menuid,'technologies'=>$technologies,'courses'=>$courses,'departments'=>$departments,'trainers'=>$trainers,'roles'=>$roles,'tpms'=>$tpms]);
   }


}
