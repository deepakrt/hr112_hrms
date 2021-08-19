<?php

namespace app\modules\inventory\controllers;
use yii;
use yii\web\Controller;
use app\models\Group; 

class GroupController extends Controller
{
	public function beforeAction($action){
		$url =Yii::$app->homeUrl;
        if (!\Yii::$app->user->isGuest) {
            if(isset($_GET['securekey']) AND !empty($_GET['securekey'])){
                $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
				
                if(empty($menuid)){ 
 					header("Location: $url",  true,  301 );die;	
					//return $this->redirect(Yii::$app->homeUrl);
				}
                 $chkValid = Yii::$app->utility->validate_url($menuid);
                 if(empty($chkValid)){ return $this->redirect(Yii::$app->homeUrl); }
                return true;
            }else{ return $this->redirect(Yii::$app->homeUrl); }
        }else{
             header("Location: $url");die;
        }
        parent::beforeAction($action);
    }

   public function actionIndex()
    {
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('index');
    }

    


   public function actionAdd()
    {		 
	//echo "<pre>";print_r(Yii::$app->user->identity);die;
	$menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $this->view->title = 'Add New Group';
        $this->layout = '@app/views/layouts/admin_layout.php';
	if(isset($_POST) && !empty($_POST)){
	  $post=$_POST['Group'];
          unset($_POST['Group']);
		$data['CLASSIFICATION_NAME'] = $post['CLASSIFICATION_NAME'];
		//echo "<pre>";print_r($data);die;
		$res=Yii::$app->inventory->add_group_master($data);	
                if($res == '1'){
                    Yii::$app->getSession()->setFlash('success', 'Group added successfully');
                    return $this->redirect(Yii::$app->homeUrl."inventory/group?securekey=".$menuid); 
                }
                else {
                Yii::$app->getSession()->setFlash('danger', 'Invalid / Empty params found');
                return $this->redirect(Yii::$app->homeUrl."inventory/group?securekey=".$menuid); 
              }			
	  }
		
 	$model = new Group();
        return $this->render('add', ['model'=>$model, 'menuid'=>$menuid]);
    }

 
}
