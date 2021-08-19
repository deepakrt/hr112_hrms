<?php

namespace app\modules\employee\controllers;

use Yii;
use app\models\Grievance;
use app\models\GrievanceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GrievanceController implements the CRUD actions for Grievance model.
 */
class GrievanceController extends Controller
{

public function beforeAction($action){
        $this->layout = '@app/views/layouts/admin_layout.php';
        if (!\Yii::$app->user->isGuest) {
            if(isset($_GET['securekey']) AND !empty($_GET['securekey'])) {
                $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
                if(empty($menuid)){ return $this->redirect(Yii::$app->homeUrl); }

                $chkValid = Yii::$app->utility->validate_url($menuid);
                if(empty($chkValid)){ return $this->redirect(Yii::$app->homeUrl); }
                return true;
            } else { return $this->redirect(Yii::$app->homeUrl); }
        }else{
            return $this->redirect(Yii::$app->homeUrl);
        }
        parent::beforeAction($action);
    }



    /**
     * {@inheritdoc}
     */
    // public function behaviors()
    // {
    //     return [
    //         'verbs' => [
    //             'class' => VerbFilter::className(),
    //             'actions' => [
    //                 'delete' => ['POST'],
    //             ],
    //         ],
    //     ];
    // }

    /**
     * Lists all Grievance models.
     * @return mixed
     */
    public function actionIndex()
    {
   
        $searchModel = new GrievanceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Grievance model.
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
     * Creates a new Grievance model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Grievance();

        // if ($model->load(Yii::$app->request->post()) && $model->save()) {
        //     return $this->redirect(['view', 'id' => $model->id]);
        // }

        // return $this->render('create', [
        //     'model' => $model,
        // ]);
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid); 
         $year = date("Y");
         $app_uplodatedby=Yii::$app->user->identity->e_id; 
         if (isset($_POST['add']) && !empty($_POST['add'])) {
            $files = $_FILES['Grievance'];
            $app_document = $files['name']['filename']; 
            $post = $_POST['Grievance'];
            $filepath="";
            if(isset($app_document) && !empty($app_document))
             {
              $filepath=  time().'_'.Yii::$app->user->identity->e_id.'.pdf';      
              if(move_uploaded_file($_FILES['Grievance']['tmp_name']['filename'],Yii::$app->basePath .'/other_files/Grievance_doc/'.$filepath))
               {
               }
             }
             $app_status=0;
              $role=Yii::$app->user->identity->role; 
             $emp_id=Yii::$app->user->identity->e_id; 
             $docket=$role."GRV-emp-".$emp_id;
             $postData = Yii::$app->request->post();
             $app_title = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['title']));
             $app_description = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['description']));                      
             $complaint_type = preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '',$post['complaint_type']);
             $result = Yii::$app->utility->add_grievance(null, $app_title,$app_description,$filepath,$complaint_type, $app_uplodatedby, $app_status,$docket,$role);
              $url = Yii::$app->homeUrl."employee/grievance/view?securekey=$menuid&id=$result";
    
                return $this->redirect($url);

         }
         return $this->render('create', ['model' => $model, 'menuid'=>$menuid]);
    }

    /**
     * Updates an existing Grievance model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
 $app_uplodatedby=Yii::$app->user->identity->e_id; 
$menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);          
        if (isset($_POST['add']) && !empty($_POST['add'])) {
            $files = $_FILES['Grievance'];
            $app_document = $files['name']['filename']; 
            $post = $_POST['Grievance'];
            $filepath="";

        if(isset($app_document) && !empty($app_document))
        {
         $filepath=  time().'_'.Yii::$app->user->identity->e_id.'.pdf';
      
         if(move_uploaded_file($_FILES['Grievance']['tmp_name']['filename'],Yii::$app->basePath .'/other_files/Grievance_doc/'.$filepath))
         {
          }
        }
         $app_status = 0;
         $role=Yii::$app->user->identity->role; 
          
            $postData = Yii::$app->request->post();
             $app_title = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['title']));
             $app_description = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['description']));                      
             $complaint_type = preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '',$post['complaint_type']);
           $result = Yii::$app->utility->add_grievance($id, $app_title,$app_description,$filepath,$complaint_type, $app_uplodatedby, $app_status,null,$role);          
            
           $url = Yii::$app->homeUrl."employee/grievance/view?securekey=$menuid&id=$id";
    
                return $this->redirect($url);
                           
            }     

        return $this->render('update', [ 'model' => $model,  ]);
    }
     public function actionGrievancesubmit($id) 
    {
    $role=Yii::$app->user->identity->role; 
     $emp_id=Yii::$app->user->identity->e_id; 
     $status=1;
    $model = $this->findModel($id);
    $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
    $menuid = Yii::$app->utility->encryptString($menuid);    
    $result = Yii::$app->utility->update_grievance_status($id,$role, $emp_id, null, $status);  
    $url = Yii::$app->homeUrl."employee/grievance?securekey=$menuid";

        return $this->redirect($url);
      //die($result);
      
    }
    public function actionResubmit($id) 
    {
    $role=Yii::$app->user->identity->role; 
     $emp_id=Yii::$app->user->identity->e_id; 
     $status=1;
    
    $model = $this->findModel($id);
    $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
    $menuid = Yii::$app->utility->encryptString($menuid);    
    $result = Yii::$app->utility->update_grievance_resubmit($id,$role, $emp_id, $status);  
    $url = Yii::$app->homeUrl."employee/grievance?securekey=$menuid";
        return $this->redirect($url);
      //die($result);
      
    }

    /**
     * Deletes an existing Grievance model.
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
    public function actionRequestchange($id) 
    { 
         $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);         
    $role=Yii::$app->user->identity->role; 
     $emp_id=Yii::$app->user->identity->e_id; 
    $app_comment = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $_POST['request_comment']));
     $result = Yii::$app->utility->grievance_request($id, $app_comment, 1, $role,$emp_id);


  //  $model = $this->findModel($id);
   // $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
  //  $menuid = Yii::$app->utility->encryptString($menuid);    
  //  $result = Yii::$app->utility->update_apprasial_status($id, '1', $role, "","");  
   $url = Yii::$app->homeUrl."employee/grievance?securekey=$menuid";
        return $this->redirect($url);
      //die($result);
      
    }
     public function actionWithdraw($id)  
    { if(isset($_POST['Withdraw']))
         { 
            $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
             $menuid = Yii::$app->utility->encryptString($menuid);    
             $status=6;
             $role=Yii::$app->user->identity->role; 
         $emp_code=Yii::$app->user->identity->e_id; 
         $comment="";
         $result = Yii::$app->utility->update_grievance_status($id, $role, $emp_code, $comment,$status );
          $url = Yii::$app->homeUrl."employee/grievance/view?securekey=$menuid&id=$id";
    return $this->redirect($url);

        } 
      
    }


    /**
     * Finds the Grievance model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Grievance the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Grievance::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
