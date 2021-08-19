<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\PolicyMaster;
use app\models\PolicyMasterSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PolicymasterController implements the CRUD actions for PolicyMaster model.
 */
class PolicymasterController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all PolicyMaster models.
     * @return mixed
     */
    public function actionIndex()
    {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('index', ['menuid'=>$menuid]);
    }

    /**
     * Displays a single PolicyMaster model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new PolicyMaster model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PolicyMaster();

        
         $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $this->layout = '@app/views/layouts/admin_layout.php';
        if(isset($_POST['policymaster'])){ 
            $post = $_POST['PolicyMaster'];
          
          $app_uplodatedby=Yii::$app->user->identity->e_id; 
           $police_name = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['police_name']));
         
             $isActive = trim(preg_replace('/[^A-Za-z]/', '', $post['is_active']));
            if(!empty($police_name)  AND !empty($isActive)){
                
                $result = Yii::$app->utility->add_update_policy_mst(null, $police_name,$isActive, $app_uplodatedby);
                if($result == '1'){
                    Yii::$app->getSession()->setFlash('success', 'Polices added successfully');
                    return $this->redirect(Yii::$app->homeUrl."admin/policymaster?securekey=".$menuid);
                }
            }else{
                Yii::$app->getSession()->setFlash('danger', 'Invalid / Empty params found.');
                return $this->redirect(Yii::$app->homeUrl."admin/policymaster?securekey=".$menuid);
            }
        }
     
        return $this->render('create', ['model'=>$model, 'menuid'=>$menuid]);
    }

    /**
     * Updates an existing PolicyMaster model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);


        
         $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $this->layout = '@app/views/layouts/admin_layout.php';
       if(isset($_POST['PolicyMaster'])){  echo "asas";
            $post = $_POST['PolicyMaster'];
          
           $app_uplodatedby=Yii::$app->user->identity->e_id; 
           $police_name = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['police_name'])); 
         
             $isActive = trim(preg_replace('/[^A-Za-z]/', '', $post['is_active']));
            if(!empty($police_name)  AND !empty($isActive)){
                
                $result = Yii::$app->utility->add_update_policy_mst($id, $police_name,$isActive,$app_uplodatedby); 
                if($result == '2'){
                    Yii::$app->getSession()->setFlash('success', 'Polices added successfully');
                    return $this->redirect(Yii::$app->homeUrl."admin/policymaster?securekey=".$menuid);
                }
            }else{
                Yii::$app->getSession()->setFlash('danger', 'Invalid / Empty params found.');
                return $this->redirect(Yii::$app->homeUrl."admin/policymaster?securekey=".$menuid);
            }
       
     }
         return $this->render('update', ['model'=>$model, 'menuid'=>$menuid]);
    }

    /**
     * Deletes an existing PolicyMaster model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PolicyMaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PolicyMaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PolicyMaster::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
