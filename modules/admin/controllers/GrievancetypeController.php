<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\Grievancetype;
use app\models\GrievancetypeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GrievancetypeController implements the CRUD actions for Grievancetype model.
 */
class GrievancetypeController extends Controller
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
     * Displays a single Grievancetype model.
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
     * Creates a new Grievancetype model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    { 
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $this->layout = '@app/views/layouts/admin_layout.php';
        if(isset($_POST['GrievanceType']) AND !empty($_POST['GrievanceType'])){
            $post = $_POST['GrievanceType'];
            $grie_name = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['title']));
            $grie_desc = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['description']));
            $isActive = trim(preg_replace('/[^A-Za-z]/', '', $post['is_active']));
            if(!empty($grie_name) AND !empty($grie_desc) AND !empty($isActive)){
                $result = Yii::$app->utility->add_update_grievance(null, $grie_name,$grie_desc,$isActive);
                if($result == '1'){
                    Yii::$app->getSession()->setFlash('success', 'Grievance Type added successfully');
                    return $this->redirect(Yii::$app->homeUrl."admin/grievancetype?securekey=".$menuid);
                }
            }else{
                Yii::$app->getSession()->setFlash('danger', 'Invalid / Empty params found.');
                return $this->redirect(Yii::$app->homeUrl."admin/grievancetype?securekey=".$menuid);
            }
        }
        $model = new Grievancetype();
        return $this->render('create', ['model'=>$model, 'menuid'=>$menuid]);
    }

    /**
     * Updates an existing Grievancetype model.
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
            $info = Yii::$app->utility->get_grievance_type($id);
             if(empty($info)){
                Yii::$app->getSession()->setFlash('danger', 'No Record found');
                return $this->redirect(Yii::$app->homeUrl."admin/grievancetype?securekey=".$menuid);
            }
            
            if(isset($_POST['GrievanceType']) AND !empty($_POST['GrievanceType'])){
                $post = $_POST['GrievanceType'];
              //  $dept_id = Yii::$app->utility->decryptString($post['dept_id']);
                $title = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['title']));
                $description = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['description']));
                $isActive = trim(preg_replace('/[^A-Za-z]/', '', $post['is_active']));
              
                if(!empty($id) AND !empty($title) AND !empty($description) AND !empty($isActive)){
                    $result = Yii::$app->utility->add_update_grievance($id,$title,$description,$isActive);
                    if($result == '2'){
                        Yii::$app->getSession()->setFlash('success', 'Grievance type updated successfully');
                        return $this->redirect(Yii::$app->homeUrl."admin/grievancetype?securekey=".$menuid);
                    }
                }else{
                    Yii::$app->getSession()->setFlash('danger', 'Invalid / Empty params found.');
                    return $this->redirect(Yii::$app->homeUrl."admin/grievancetype?securekey=".$menuid);
                }
                
            }
           
            
            $this->layout = '@app/views/layouts/admin_layout.php';
            
            return $this->render('update', ['model'=>$model, 'menuid'=>$menuid]);
        }else{
            return $this->redirect(Yii::$app->homeUrl."managedepartment?securekey=".$menuid);
        }
    }

    /**
     * Deletes an existing Grievancetype model.
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
     * Finds the Grievancetype model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Grievancetype the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Grievancetype::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
