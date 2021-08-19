<?php

namespace app\modules\manageproject\controllers;

use app\models\ProjectList;
use Yii;
use yii\web\Controller;
use app\modules\manageproject\models\Projectdetail;
use app\modules\manageproject\models\Proposal;
use app\modules\manageproject\models\Ordermaster;
use app\modules\manageproject\models\Clientdetail;
use app\modules\manageproject\models\Manpowermapping;
use app\modules\manageproject\models\OrdermasterSearch;
use app\modules\manageproject\models\ClientContact;
use yii\web\NotFoundHttpException;
use app\modules\manageproject\facade\Csuserlog;
/**
 * ManpowerController implements the CRUD actions for Manpower model.
 */
class ManpowerController extends Controller
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
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $projects =  Yii::$app->utility->get_dept_emp(Yii::$app->user->identity->dept_id);        
        $this->layout = '@app/views/layouts/admin_layout.php';     
        
        
        return $this->render('index', [
             'menuid'=>$menuid, 
             'projects'=>$projects                
        ]);
                    
    }
    
    public function actionDetail()
    {        
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        
        $m = Yii::$app->utility->decryptString($_GET['key']);
                
        $projects =  Yii::$app->projectcls->mapEd(base64_decode($m));   
                
        $this->layout = '@app/views/layouts/admin_layout.php';     
                
        return $this->render('detail', [
             'menuid'=>$menuid, 
             'projects'=>$projects                
        ]);
                    
    }
    
    protected function findModel($id)
    {        
            if (($model = Manpower::findOne($id)) !== null) {
                return $model;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
     
    }
}
