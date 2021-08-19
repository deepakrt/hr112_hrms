<?php

namespace app\modules\hr\controllers;

use Yii;
use app\models\Transferpromotion;
use app\models\TransferpromotiontSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TransferpromotionController implements the CRUD actions for Transferpromotion model.
 */
class TransferpromotionController extends Controller
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
     * Lists all Transferpromotion models.
     * @return mixed
     */
    public function actionIndex()
    {
          $this->layout = '@app/views/layouts/admin_layout.php';
        $searchModel = new TransferpromotiontSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
 public function actionViewlist()
    {
          $this->layout = '@app/views/layouts/admin_layout.php';
        $searchModel = new TransferpromotiontSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('viewlist', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Displays a single Transferpromotion model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
      
             $id = Yii::$app->utility->decryptString($id);
          
    if(empty($menuid)){ 
         $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('view', ['model' => $this->findModel($id),
        ]);
    }
}
public function actionStatus($id)
    {
         $model = new Transferpromotion();

         $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
         $url = Yii::$app->homeUrl."hr/transferpromotion/viewlist?securekey=$menuid"; 
        $this->layout = '@app/views/layouts/admin_layout.php';
        $emp=Yii::$app->user->identity->e_id;
        if(isset($_POST['Accepted'])){ 
              $emp=Yii::$app->user->identity->e_id; 
             $result = Yii::$app->utility->update_status_transfer_promotion($id, 2, $emp); 
              return $this->redirect($url);   
            
        }
        if(isset($_POST['Rejected'])){ 
             $result = Yii::$app->utility->update_status_transfer_promotion($id, 3, $emp); 
              return $this->redirect($url);


            
        }
        if(isset($_POST['Reapply'])){ 
             $result = Yii::$app->utility->update_status_transfer_promotion($id, 4, $emp); 
              return $this->redirect($url);


            
        }

    }
    /**
     * Creates a new Transferpromotion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Transferpromotion();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionViewemployee(){ 
        $model = new Transferpromotion();
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        if(isset($_GET['securekey']) AND !empty($_GET['securekey']) AND isset($_GET['empid']) AND !empty($_GET['empid'])){
            $securekey = Yii::$app->utility->decryptString($_GET['securekey']);
            $e_id = Yii::$app->utility->decryptString($_GET['empid']);
            if(empty($securekey) OR empty($e_id)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected');
                return $this->redirect(Yii::$app->homeUrl);
            }
            $info = Yii::$app->utility->get_employees($e_id);

            if(empty($info)){
                Yii::$app->getSession()->setFlash('danger', 'No Record found');
                return $this->redirect(Yii::$app->homeUrl."admin/manageemployees?securekey=".$menuid);
            }

            if(isset($_POST['Transfer'])){ 
            $post = $_POST['Transferpromotion'];
          
          $app_uplodatedby=Yii::$app->user->identity->e_id; 
           $title = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['title']));
            $remarks = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['remarks']));
            $request_for = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['request_for']));
            $result = Yii::$app->utility->add_update_transfer_promotion(null, $title,$remarks,$e_id, $request_for,$app_uplodatedby); 
            if($result == '1'){
                    Yii::$app->getSession()->setFlash('success', 'Request added successfully');
                    return $this->redirect(Yii::$app->homeUrl."hr/transferpromotion/viewlist?securekey=".$menuid);
                
            }else{
                Yii::$app->getSession()->setFlash('danger', 'Invalid / Empty params found.');
                return $this->redirect(Yii::$app->homeUrl."hr/transferpromotion/viewlist?securekey=".$menuid);
            }

       }




         //   $qualification = Yii::$app->utility->get_qualification('3', $e_id, NULL, NULL);
          //  $family_details = Yii::$app->utility->get_family_details($e_id);  
          //  $employee_leaves = Yii::$app->utility->get_employee_leaves($e_id);
            $this->layout = '@app/views/layouts/admin_layout.php';
           // return $this->render('viewemployee', ['info'=>$info,'qualification'=>$qualification,'family_details'=>$family_details,'employee_leaves'=>$employee_leaves]);
        }else{
            return $this->redirect(Yii::$app->homeUrl."admin/manageemployees?securekey=".$menuid);
        }
        return $this->render('viewemployee', ['model'=>$model, 'menuid'=>$menuid]);
    }

    /**
     * Updates an existing Transferpromotion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Transferpromotion model.
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
     * Finds the Transferpromotion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Transferpromotion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Transferpromotion::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
