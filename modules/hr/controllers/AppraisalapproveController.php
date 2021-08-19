<?php
namespace app\modules\hr\controllers;
use Yii;
use app\models\Appraisal;
use app\models\AppraisalSearch;
use yii\web\Controller;

/**
 * AppraisalController implements the CRUD actions for Appraisal model.
 */
class AppraisalapproveController extends Controller
{
    /**
     * {@inheritdoc}
     */
   

    // public function beforeAction($action){
    //     $this->layout = '@app/views/layouts/admin_layout.php';
    //     if (!\Yii::$app->user->isGuest) {
    //         if(isset($_GET['securekey']) AND !empty($_GET['securekey'])) {
    //             $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
    //             if(empty($menuid)){ return $this->redirect(Yii::$app->homeUrl); }
    //             $chkValid = Yii::$app->utility->validate_url($menuid);
    //             if(empty($chkValid)){ return $this->redirect(Yii::$app->homeUrl); }
    //             return true;
    //         } else { return $this->redirect(Yii::$app->homeUrl); }
    //     }else{
    //         return $this->redirect(Yii::$app->homeUrl);
    //     }
    //     parent::beforeAction($action);
    // }
    
    /*public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }*/

    /**
     * Lists all Appraisal models.
     * @return mixed
     */
    public function actionIndex()
    {

       
       $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('index');
        /*$searchModel = new AppraisalSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);*/
    }

    /**
     * Displays a single Appraisal model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
      $this->layout = '@app/views/layouts/admin_layout.php';

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Appraisal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Appraisal();
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
         $url = Yii::$app->homeUrl."employee/appraisal?securekey=$menuid";


         
        if (isset($_POST['add']) && !empty($_POST['add'])) {
            $files = $_FILES['Appraisal'];
            $app_document = $files['name']['document']; 
            $post = $_POST['Appraisal'];

        if(isset($files) && !empty($files))
        {
         $filepath=  time().'_'.Yii::$app->user->identity->e_id.'.pdf';
      
         if(move_uploaded_file($_FILES['Appraisal']['tmp_name']['document'],Yii::$app->basePath .'/other_files/Appraisal_doc/'.$filepath))
         {


          }
        }
            
            
            $postData = Yii::$app->request->post();
            $app_title = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['title']));
            $app_job_description = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['job_description']));
            $app_deleted = 0;
            $app_uplodatedby=Yii::$app->user->identity->e_id;       
            $app_status = 1;
             $achievement = preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '',$post['achievement']);
           // $result = Yii::$app->utility->add_appraisal(null, $app_title,$app_job_description,$app_document,$app_deleted, $app_uplodatedby, $app_status,$achievement);
            Yii::$app->getSession()->setFlash('success', 'Appraisal added Successfully.');
                            return $this->redirect($url);

            }
          

        
        // return $this->render('add', ['model'=>$model, 'menuid'=>$menuid]);
        // if ($model->load(Yii::$app->request->post()) && $model->save()) {
        //     return $this->redirect(['view', 'id' => $model->id]);
        // }

        return $this->render('create', ['model' => $model, 'menuid'=>$menuid]);
    }

    /**
     * Updates an existing Appraisal model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $this->layout = '@app/views/layouts/admin_layout.php';

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }
     public function actionAppupdbyauth($id) 
    {       
        $model = $this->findModel($id); 
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);   
        $url = Yii::$app->homeUrl."hr/appraisalapprove?securekey=$menuid";
        $role=Yii::$app->user->identity->role; 
        if($role==4)
        {
        $model = new Appraisal();
        $post = $_POST['Appraisal'];
        $app_feedback = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['feedback']));
        $app_rating = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['rating'])); 
        $result = Yii::$app->utility->update_apprasial_status($id, $role, $app_feedback,$app_rating ); 
    } else{ $result = Yii::$app->utility->update_apprasial_status($id, $role, "","" ); }

        return $this->redirect($url);
        //die($result);
      
    }
     public function actionApprevoke($id) 
    {   
        $model = $this->findModel($id); 
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);   
        $url = Yii::$app->homeUrl."hr/appraisalapprove?securekey=$menuid";
        $role=Yii::$app->user->identity->role; 
        $model = new Appraisal();
       // $post = $_POST['Appraisal'];       
        $result = Yii::$app->utility->Apprasial_revoke($id,'2',$role);  
        return $this->redirect($url);
        //die($result);
      
    }

    /**
     * Deletes an existing Appraisal model.
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
     public function actionViewall()
    {
      $model = new Appraisal();
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
         $url = Yii::$app->homeUrl."employee/appraisal?securekey=$menuid";


       return $this->render('viewall', ['model' => $model, 'menuid'=>$menuid]);
   }

    /**
     * Finds the Appraisal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Appraisal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Appraisal::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
