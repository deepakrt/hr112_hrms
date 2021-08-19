<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\PoliciesGuidelines;
use app\models\PoliciesGuidelinesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PoliciesguidelinesController implements the CRUD actions for PoliciesGuidelines model.
 */
class PoliciesguidelinesController extends Controller
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
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('index', ['menuid'=>$menuid]);
    }

    /**
     * Displays a single PoliciesGuidelines model.
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
     * Creates a new PoliciesGuidelines model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PoliciesGuidelines();

         $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $this->layout = '@app/views/layouts/admin_layout.php';
        if(isset($_POST['Policies'])){ 
            $app_uplodatedby=Yii::$app->user->identity->e_id; 
            $post = $_POST['PoliciesGuidelines'];
            $files = $_FILES['PoliciesGuidelines'];
            $app_document = $files['name']['document']; 
               $police_id = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['policy_id']));
            $police_name = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['title']));
            $police_desc = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['description']));
            $valid = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['valid_upto']));
            $isActive = trim(preg_replace('/[^A-Za-z]/', '', $post['is_active']));
            if(!empty($police_name)  AND !empty($isActive)){
                if(isset($app_document) && !empty($app_document))
             {
              $filepath=  time().'_'.Yii::$app->user->identity->e_id.'.pdf';  
              if(move_uploaded_file($_FILES['PoliciesGuidelines']['tmp_name']['document'],Yii::$app->basePath .'/other_files/Polices_doc/'.$filepath))
               {
               }
             }
                $result = Yii::$app->utility->add_update_policies(null, $police_name,$police_desc,$filepath,$isActive,$valid,$app_uplodatedby, $police_id);
                if($result == '1'){
                    Yii::$app->getSession()->setFlash('success', 'Polices added successfully');
                    return $this->redirect(Yii::$app->homeUrl."admin/policiesguidelines?securekey=".$menuid);
                }
            }else{
                Yii::$app->getSession()->setFlash('danger', 'Invalid / Empty params found.');
                return $this->redirect(Yii::$app->homeUrl."admin/policiesguidelines?securekey=".$menuid);
            }
        }
     
        return $this->render('create', ['model'=>$model, 'menuid'=>$menuid]);
    }

    /**
     * Updates an existing PoliciesGuidelines model.
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
        if(isset($_GET['id']) AND !empty($_GET['id'])){
        $id = ($_GET['id']); 
            $info = Yii::$app->utility->get_policies_gui($id);
            
             if(empty($info)){
                Yii::$app->getSession()->setFlash('danger', 'No Record found');
                return $this->redirect(Yii::$app->homeUrl."admin/policiesguidelines?securekey=".$menuid);
            }
          
            if(isset($_POST['PoliciesGuidelines']) AND !empty($_POST['PoliciesGuidelines'])){

$filepath="";
$app_uplodatedby=Yii::$app->user->identity->e_id; 
                $post = $_POST['PoliciesGuidelines'];
                 $files = $_FILES['PoliciesGuidelines'];
              $app_document = $files['name']['document'];  
                $police_id = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['policy_id']));
                $police_name = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['title']));
                $police_desc = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['description']));
                $isActive = trim(preg_replace('/[^A-Za-z]/', '', $post['is_active']));
                  $valid = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['valid_upto']));
              if(isset($app_document) && !empty($app_document))
             {
              $filepath=  time().'_'.Yii::$app->user->identity->e_id.'.pdf';  
              if(move_uploaded_file($_FILES['PoliciesGuidelines']['tmp_name']['document'],Yii::$app->basePath .'/other_files/Polices_doc/'.$filepath))
               {
               }
             }
                if(!empty($id) AND !empty($police_name)  AND !empty($isActive)){
                  $result = Yii::$app->utility->add_update_policies($id, $police_name,$police_desc,$filepath,$isActive,$valid,$app_uplodatedby,$police_id);
                    if($result == '2'){
                        Yii::$app->getSession()->setFlash('success', 'Policies updated successfully');
                        return $this->redirect(Yii::$app->homeUrl."admin/policiesguidelines?securekey=".$menuid);
                    }
                }else{
                    Yii::$app->getSession()->setFlash('danger', 'Invalid / Empty params found.');
                    return $this->redirect(Yii::$app->homeUrl."admin/policiesguidelines?securekey=".$menuid);
                }
                
            }
           
            
            $this->layout = '@app/views/layouts/admin_layout.php';
            
            return $this->render('update', ['model'=>$model, 'menuid'=>$menuid]);
        }else{
            return $this->redirect(Yii::$app->homeUrl."managedepartment?securekey=".$menuid);
        }
    }

    /**
     * Deletes an existing PoliciesGuidelines model.
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
     * Finds the PoliciesGuidelines model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PoliciesGuidelines the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PoliciesGuidelines::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
