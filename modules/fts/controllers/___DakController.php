<?php

namespace app\modules\fts\controllers;

use Yii;
use app\models\FtsDak;
use app\models\FtsDakSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FtsdakController implements the CRUD actions for FtsDak model.
 */
class DakController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all FtsDak models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FtsDakSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

					$this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single FtsDak model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new FtsDak model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
       
        
         
        $model = new FtsDak();

		$this->layout = '@app/views/layouts/admin_layout.php';
		
		 	// echo "<pre>";print_r(Yii::$app->request->post()); die;
		
		if(!empty(Yii::$app->request->post()) && (isset($_FILES['FtsDak']) && !empty($_FILES['FtsDak'])) ) 
		{
		$_POST = Yii::$app->request->post();
		if(isset($_POST['FtsDak']) && !empty($_POST['FtsDak']))
		{
            
            
 
		    $FtsDak = $_POST['FtsDak'];
            
            
            
		    if((isset($FtsDak['refrence_no'])    && !empty($FtsDak['refrence_no'])) &&
               (isset($FtsDak['file_name'])      && !empty($FtsDak['file_name']))  && 
               (isset($FtsDak['subject'])        && !empty($FtsDak['subject'])) &&  
               (isset($FtsDak['send_to_type'])   && !empty($FtsDak['send_to_type'])) && 
               ((isset($FtsDak['send_to_emp'])    && !empty($FtsDak['send_to_emp'])) || 
               (isset($FtsDak['send_to_group'])  && !empty($FtsDak['send_to_group']))) && 
               (isset($FtsDak['category'])       && !empty($FtsDak['category'])) && 
               (isset($FtsDak['summary'])        && !empty($FtsDak['summary'])) && 
               (isset($FtsDak['access_level'])   && !empty($FtsDak['access_level'])) && 
               (isset($FtsDak['priority'])       && !empty($FtsDak['priority'])) &&
               (isset($FtsDak['is_confidential'])&& !empty($FtsDak['is_confidential'])) &&
               (isset($FtsDak['file_date'])      && !empty($FtsDak['file_date'])) &&
               (isset($FtsDak['meta_keywords'])  && !empty($FtsDak['meta_keywords'])) &&
               (isset($FtsDak['remarks']) && !empty($FtsDak['remarks']))){
  
                
              $_POST['FtsDak']['send_from'] = $FtsDak['send_from'] =  Yii::$app->user->identity->e_id;
             
                $_POST['FtsDak']['is_active'] = $FtsDak['is_active'] = 'Y';
                $_POST['FtsDak']['file_date'] = $FtsDak['file_date'] = date('Y-m-d',strtotime($FtsDak['file_date']));
                
                if((isset($FtsDak['send_to_emp'])    && !empty($FtsDak['send_to_emp']))){
                    $_POST['FtsDak']['send_to_group'] = $FtsDak['send_to_group'] = NULL;
                }else{
                    $_POST['FtsDak']['send_to_emp'] = $FtsDak['send_to_emp'] = NULL;
                } 
                //echo "<pre>"; print_r($_FILES);

                $uploaddoc_File = $_FILES['FtsDak'];
                $fileUpload = $uploaddoc_File["name"]["document"];
                $folderName = FTS_Documents;		 
                $ext = pathinfo($fileUpload, PATHINFO_EXTENSION);
                $fileName = rand(10000, 990000). '_'. time().'.'.$ext;
                $filePath = $folderName. $fileName;
                if (move_uploaded_file($uploaddoc_File["tmp_name"]["document"], $filePath)) 
                {
                    $_POST['FtsDak']['document'] = $FtsDak['document'] =  $uploaddoc_File["name"]["document"];
                    $_POST['FtsDak']['document_path'] = $FtsDak['document_path'] =  $filePath;

                }

             //   echo "<pre>@@"; print_r($_POST);echo "****"; print_r($_FILES); echo "^^^^"; print_r($FtsDak);       die('444');        

                
                //echo "<pre>"; print_r($_POST);die;
                $model = new FtsDak();
                $model->load($_POST);

                if (!$model->validate()) {
                    echo "<pre>=@@="; print_r($model->errors);//die;
                }else{
                 
                $fts_createdak = Yii::$app->fts_utility->fts_createdak($FtsDak);
             //   echo "<pre>"; print_r($_GET);    var_dump($fts_createdak); die;
                    
                $rr=$_POST['FtsDak']['securekey'];
                $menuid = Yii::$app->utility->decryptString($rr);
                $menuid = Yii::$app->utility->encryptString($menuid);
            
                Yii::$app->getSession()->setFlash('success', 'Dak Successfully added');
                return $this->redirect(["/fts?securekey=$menuid"]);
                
            }
               }else{
                    $model->load($_POST);
                    if (!$model->validate()) {
                        echo "<pre>=#="; print_r($model->errors);die;
                    }
                }
              }
            }
             else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
    }

       public function actionUpdatedak(){
         //  echo "<pre>"; print_r($_GET); die;
        if(isset($_GET['securekey']) AND !empty($_GET['securekey']) AND isset($_GET['dakid']) AND !empty($_GET['dakid'])){
          //  die('888');
            $securekey = Yii::$app->utility->decryptString($_GET['securekey']);
            $dakid = Yii::$app->utility->decryptString($_GET['dakid']);

            if(empty($securekey) OR empty($dakid)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected');
                return $this->redirect(Yii::$app->homeUrl);
            }
            $dak_info = Yii::$app->fts_utility->fts_getdak(NULL,$dakid);
            //echo "<pre>";print_r($dak_info); die;
            
            if(empty($dak_info)){
                Yii::$app->getSession()->setFlash('danger', 'No Record found');
                return $this->redirect(Yii::$app->homeUrl."fts");
            }
            //$dak_info = $dak_info[0];
            $model = new FtsDak();
            $dak_id = $dak_info[0]['dak_id'];
            $model->send_from = $dak_info[0]['send_from'];
            $model->refrence_no = $dak_info[0]['refrence_no'];
            $model->file_date = date('d-m-Y', strtotime($dak_info[0]['file_date']));
            $model->file_name = $dak_info[0]['file_name'];
            $model->subject = $dak_info[0]['subject'];
            $model->category = $dak_info[0]['category'];
            $model->access_level = $dak_info[0]['access_level'];
            $model->priority = $dak_info[0]['priority'];
            $model->is_confidential = $dak_info[0]['is_confidential'];
            $model->meta_keywords = $dak_info[0]['meta_keywords'];
            $model->remarks = $dak_info[0]['remarks'];
            $model->summary = $dak_info[0]['summary'];
            $model->doc_type = $dak_info[0]['doc_type'];
            $model->document = $dak_info[0]['docs_path'];
            $model->status = $dak_info[0]['status'];
            $model->created_date = date('d-m-Y', strtotime($dak_info[0]['created_date']));
            $model->modified_date = date('d-m-Y', strtotime($dak_info[0]['modified_date']));
            $model->is_active = $dak_info[0]['is_active'];
            
            if(isset($dak_info[0]['send_to_group']) && !empty($dak_info[0]['send_to_group'])){
                 $model->send_to = $dak_info[0]['send_to_group'];
                 $model->send_to_type = "G";
            }else{
                $model->send_to = $dak_info[0]['send_to_emp'];
                 $model->send_to_type = "I";
            }
            
          //  echo "<pre>"; print_r($model);die;
            
            $this->layout = '@app/views/layouts/admin_layout.php';
            return $this->render('updatedak', ['model'=>$model,'dak_id'=>$dak_id]);
        }else{
            return $this->redirect(Yii::$app->homeUrl."fts");
        }
    }
    /**
     * Updates an existing FtsDak model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate()
    {
      //  echo "<pre>";print_r($_POST); print_r($_FILES); die;
         $FtsDak = $_POST['FtsDak'];
        
        $FtsDak['send_from'] =  Yii::$app->user->identity->e_id;
        $FtsDak['is_active'] = 'Y';
        $FtsDak['status'] = 'SENT';
        $FtsDak['file_date'] = date('Y-m-d',strtotime($FtsDak['file_date']));
                
        if((isset($FtsDak['send_to_emp'])    && !empty($FtsDak['send_to_emp']))){
            $FtsDak['send_to_group'] = NULL;
        }else{
            $FtsDak['send_to_emp'] = NULL;
        } 
        
        if ($FtsDak['doc_change'] == "1"){
        $uploaddoc_File = $_FILES['FtsDak'];
        $fileUpload = $uploaddoc_File["name"]["document"];
        $folderName = FTS_Documents;		 
        $ext = pathinfo($fileUpload, PATHINFO_EXTENSION);
        $fileName = rand(10000, 990000). '_'. time().'.'.$ext;
        $filePath = $folderName. $fileName;
        if (move_uploaded_file($uploaddoc_File["tmp_name"]["document"], $filePath)) 
         {
             $FtsDak['document'] =  $uploaddoc_File["name"]["document"];
             $FtsDak['document_path'] =  $filePath;

         }
        }else {
            $FtsDak['document'] = NULL;
             $FtsDak['document_path'] = NULL;
        }    
        
         // $fts_updatedak = Yii::$app->fts_utility->fts_updatedak($FtsDak);
           //echo "<pre>"; print_r($_GET);    var_dump($fts_createdak); die;
        
      //  echo "@@@@@<pre>"; print_r($FtsDak);die;
        
        $fts_updatedak = Yii::$app->fts_utility->fts_updatedak($FtsDak);
             //   echo "<pre>"; print_r($_GET);    var_dump($fts_createdak); die;
                    
                $rr=$_POST['FtsDak']['securekey'];
                $menuid = Yii::$app->utility->decryptString($rr);
                $menuid = Yii::$app->utility->encryptString($menuid);
            
                Yii::$app->getSession()->setFlash('success', 'Dak Successfully Dispatched');
                return $this->redirect(["/fts?securekey=$menuid"]);

    }

    /**
     * Deletes an existing FtsDak model.
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
     * Finds the FtsDak model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FtsDak the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FtsDak::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
