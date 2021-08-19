<?php

namespace app\modules\hr\controllers;

use Yii;
use app\models\Grievance;
use app\models\GrievanceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GrievanceController implements the CRUD actions for Grievance model.
 */
class GrievanceapproveController extends Controller
{

// public function beforeAction($action){
//         $this->layout = '@app/views/layouts/admin_layout.php';
//         if (!\Yii::$app->user->isGuest) {
//             if(isset($_GET['securekey']) AND !empty($_GET['securekey'])) {
//                 $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
//                 if(empty($menuid)){ return $this->redirect(Yii::$app->homeUrl); }

//                 $chkValid = Yii::$app->utility->validate_url($menuid);
//                 if(empty($chkValid)){ return $this->redirect(Yii::$app->homeUrl); }
//                 return true;
//             } else { return $this->redirect(Yii::$app->homeUrl); }
//         }else{
//             return $this->redirect(Yii::$app->homeUrl);
//         }
//         parent::beforeAction($action);
//     }



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
     $this->layout = '@app/views/layouts/admin_layout.php';
         return $this->render('index');
    }

    /**
     * Displays a single Grievance model.
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
     * Creates a new Grievance model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionForword($id) 
    {       
        $model = $this->findModel($id); 
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);   
        $url = Yii::$app->homeUrl."hr/grievanceapprove?securekey=$menuid"; 
        $role=Yii::$app->user->identity->role; 
         $emp_code=Yii::$app->user->identity->e_id;         
        $model = new Grievance();
         $comment = $_POST['comment'];
           $request_status="";
     // echo   $comment = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['comment'])); die;
         if(isset($_POST['Forword']))
         { 
            $status=2;
         $result = Yii::$app->utility->update_grievance_status($id, $role, $emp_code, $comment,$status );

        } 
        elseif(isset($_POST['Accept']))
        {
         $status=3;
         
           $result = Yii::$app->utility->update_grievance_status($id, $role, $emp_code, $comment,$status );

        }
         elseif(isset($_POST['Reject']))
         {
         $status=4;
        $result = Yii::$app->utility->update_grievance_status($id, $role, $emp_code, $comment,$status );

         }
         elseif(isset($_POST['Backtoemp']))
         { 
         $status=0;
         $request_status=2;
           $result = Yii::$app->utility->grievance_request($id, $comment, 2, $role,$emp_code);
         }

         
   

        return $this->redirect($url);
        //die($result);
      
    }
public function actionAccept($id) 
    {       
        $model = $this->findModel($id); 

        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);   
        $url = Yii::$app->homeUrl."hr/grievanceapprove?securekey=$menuid"; 
        $role=Yii::$app->user->identity->role; 
         $emp_code=Yii::$app->user->identity->e_id;         
        $model = new Grievance();
         $comment = $_POST['comment'];
     // echo   $comment = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['comment'])); die;
      $status=3;
      $result = Yii::$app->utility->update_grievance_status($id, $role, $emp_code, $comment,$status ); 
   

        return $this->redirect($url);
        //die($result);
      
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
            

        return $this->render('update', [ 'model' => $model,  ]);
    }
     public function actionGrievancesubmit($id) 
    {
        if($_POST['resubmit']){
    $role=Yii::$app->user->identity->role; 
     $emp_id=Yii::$app->user->identity->e_id; 
    $model = $this->findModel($id);
    $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
    $menuid = Yii::$app->utility->encryptString($menuid);    
    $result = Yii::$app->utility->update_grievance_status($id,$role, $emp_id, null);  
    $url = Yii::$app->homeUrl."employee/grievance?securekey=$menuid";
        return $this->redirect($url);
      //die($result);
      }
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
