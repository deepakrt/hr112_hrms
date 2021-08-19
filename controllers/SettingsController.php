<?php
namespace app\controllers;
use Yii;
class SettingsController extends \yii\web\Controller{
    
    public function beforeAction($action){
		$url =Yii::$app->homeUrl;
        if (!\Yii::$app->user->isGuest) {
            if(isset($_GET['securekey']) AND !empty($_GET['securekey'])){
                $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
				
                if(empty($menuid)){ 
					header("Location: $url");die;	
					return $this->redirect(Yii::$app->homeUrl);
				}

                $chkValid = Yii::$app->utility->validate_url($menuid);
				// die($chkValid);
                if(empty($chkValid)){ return $this->redirect(Yii::$app->homeUrl); 
									}
                return true;
            }else{ return $this->redirect(Yii::$app->homeUrl);
			}
        }else{
			
			header("Location: $url");die;	 
            
        }
        parent::beforeAction($action);
    }
    public function actionIndex()
    {
//        echo "<pre>";print_r(Yii::$app->user->identity);
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('index');
    }
    
    public function actionChangepassword()
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        if(isset($_POST['Password']) AND !empty($_POST['Password'])){
            $url = Yii::$app->homeUrl."settings/changepassword?securekey=$menuid";
            $post = $_POST['Password'];
            $current_password = $post['current_password'];
            $new_password = $post['new_password'];
            $confirm_password = $post['confirm_password'];
            
            if($new_password !=$confirm_password){
                Yii::$app->getSession()->setFlash('danger', 'New / Confirm password not matched.');
                return $this->redirect($url);
            }
            
            $result = Yii::$app->utility->update_password($current_password, $new_password);
            if($result == '2'){
                Yii::$app->getSession()->setFlash('danger', 'Current password not matched. Contact Admin.');
                return $this->redirect($url);
            }elseif($result == '1'){
                Yii::$app->getSession()->setFlash('success', 'Password updated successfully.');
                return $this->redirect($url);
            }else{
                Yii::$app->getSession()->setFlash('danger', 'Password not updated. Contact Admin.');
                return $this->redirect($url);
            }
        }
        $this->layout = '@app/views/layouts/admin_layout.php';
        
        return $this->render('changepassword', ['menuid'=>$menuid]);
    }
    
}