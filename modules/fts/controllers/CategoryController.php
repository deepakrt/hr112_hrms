<?php
namespace app\modules\fts\controllers;
use Yii;
use yii\web\Controller;
class CategoryController extends Controller
{
    public function beforeAction($action){
        if(isset($_GET['securekey']) AND !empty($_GET['securekey'])){
            $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
            if(empty($menuid)){ return $this->redirect(Yii::$app->homeUrl); }
            
            $chkValid = Yii::$app->utility->validate_url($menuid);
            if(empty($chkValid)){ return $this->redirect(Yii::$app->homeUrl); }
            return true;
        }else{ return $this->redirect(Yii::$app->homeUrl); }
        parent::beforeAction($action);
    }

    public function actionIndex(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."fts/category?securekey=$menuid";
        if(isset($_POST['Category']) AND !empty($_POST['Category'])){
            $post = $_POST['Category'];
//            echo "<pre>";print_r($post); die;
            $fts_category_id = NULL;
            $cat_name = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['cat_name']));
            $description = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['description']));
            $msg = "Category Created Successfully.";
            if(!empty($post['fts_category_id'])){
                $fts_category_id = Yii::$app->utility->decryptString($post['fts_category_id']);
                if(empty($fts_category_id)){
                    Yii::$app->getSession()->setFlash('danger', "Invalid Params Value Found."); 
                    return $this->redirect($url);
                }
                $msg = "Category Updated Successfully.";
            }
            $result = Yii::$app->fts_utility->fts_add_update_category($fts_category_id, $cat_name, $description);
            if($result == '1' OR $result == '2'){
                Yii::$app->getSession()->setFlash('success', $msg); 
                return $this->redirect($url);
            }elseif($result == '3'){
                Yii::$app->getSession()->setFlash('danger', 'Category Already Exits'); 
                return $this->redirect($url);
            }else{
                Yii::$app->getSession()->setFlash('danger', 'Category Not Added / Updated. Contact Admin'); 
                return $this->redirect($url);
            }
        }
        $catys = Yii::$app->fts_utility->fts_get_category(NULL);
	$this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('index', ['menuid' => $menuid, 'catys'=>$catys]);
    }

    /**
     * Displays a single FtsCategory model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
    $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new FtsCategory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FtsCategory();
	$this->layout = '@app/views/layouts/admin_layout.php';
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->fts_category_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing FtsCategory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
	$this->layout = '@app/views/layouts/admin_layout.php';
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->fts_category_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing FtsCategory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the FtsCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FtsCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FtsCategory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
