<?php

namespace app\modules\inventory\controllers;
use yii;
use yii\web\Controller;
use app\models\Unit; 

class UnitController extends Controller
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
        $this->view->title = 'Add New Item';
        $this->layout = '@app/views/layouts/admin_layout.php';
	if(isset($_POST) && !empty($_POST)){
	  $post=$_POST['Unit'];
          unset($_POST['Unit']);
		$data['Unit_Name'] = $post['Unit_Name'];
		//echo "<pre>";print_r($data);die;
		$res=Yii::$app->inventory->add_unit_master($data);	
                if($res == '1'){
                    Yii::$app->getSession()->setFlash('success', 'Unit added successfully');
                    return $this->redirect(Yii::$app->homeUrl."inventory/unit?securekey=".$menuid); 
                }
                else {
                Yii::$app->getSession()->setFlash('danger', 'Invalid / Empty params found');
                return $this->redirect(Yii::$app->homeUrl."inventory/unit?securekey=".$menuid); 
              }			
	  }
		
 	$model = new Unit();
        return $this->render('add', ['model'=>$model, 'menuid'=>$menuid]);
    }

   /*
     * View for Update Designation
     */
    public function actionUpdate(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        if(isset($_GET['key']) AND !empty($_GET['key'])){
            $id = base64_decode($_GET['key']);
            $info = Yii::$app->utility->get_designation($id);
            
            if(empty($info)){
                Yii::$app->getSession()->setFlash('danger', 'No Record found');
                return $this->redirect(Yii::$app->homeUrl."inventory/unit?securekey=".$menuid);
            }
            
            if(isset($_POST['Unit']) AND !empty($_POST['Unit'])){
                $post = $_POST['Unit'];
                $Unit_id = Yii::$app->utility->decryptString($post['Unit_id']);
		$Unit_Name = trim(preg_replace('/[^A-Za-z]/', '', $post['Unit_Name']));
                if(!empty($desg_id) AND !empty($desg_name) AND !empty($desg_desc) AND !empty($isActive)){
                $result = Yii::$app->utility->add_update_desg($desg_id, $desg_name,$desg_desc,$isActive);
                    if($result == '2'){
                        Yii::$app->getSession()->setFlash('success', 'Designation updated successfully');
                        return $this->redirect(Yii::$app->homeUrl."inventory/unit?securekey=".$menuid); 
                    }
                }else{
                    Yii::$app->getSession()->setFlash('danger', 'Invalid / Empty params found');
                    return $this->redirect(Yii::$app->homeUrl."inventory/unit?securekey=".$menuid); 
                }
            }
            $model = new Unit();
            $model->Unit_id = $info['Unit_id'];
	    $model->Unit_Name = $info['Unit_Name'];	
            $this->layout = '@app/views/layouts/admin_layout.php';
            return $this->render('update', ['model'=>$model, 'menuid'=>$menuid]);
        }else{
            return $this->redirect(Yii::$app->homeUrl."unit?securekey=".$menuid);
        }
    }

}
