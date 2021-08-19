<?php

namespace app\modules\manageproject\controllers;

use Yii;
use app\modules\manageproject\models\ClientContact;
use app\modules\manageproject\models\ClientDetail;
use app\modules\manageproject\models\Projectdetail;
use app\modules\manageproject\models\ClientContactSearch;
use app\modules\manageproject\models\Ordermaster;
use app\modules\manageproject\models\Proposal;
use app\modules\manageproject\models\Site;
use yii\web\Controller;
use app\models\ProjectList;

class ClientContactController extends Controller
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
    
    public function actionCreate()
    {        
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."manageproject/client-contact/create?securekey=$menuid";
        
        //print_r($_POST);
//        die($menuid);
        $model = new ClientContact();
        if(isset($_POST['Proposal']) AND !empty($_POST['Proposal'])){
            $post = $_POST['Proposal'];
            
            
            //if(isset($_GET['key']) AND !empty($_GET['key']) ){                
                //$proposal_id = Yii::$app->utility->decryptString($_GET['key']);
                
                $model = new Proposal();
                
                $model->activeuser = Yii::$app->user->identity->e_id;
                $model->sessionid = Yii::$app->user->identity->accessToken;
                $model->cdacdeptid = Yii::$app->user->identity->dept_id;
                $model->cost = $post['cost'];
                $model->validity = $post['validity'];
                $model->proposalnumber = $post['proposalnumber'];
                $model->proposaltype = $post['proposaltype'];
                if($post['receivingdate']==NULL){
                    $model->receivingdate = date('Y-m-d', strtotime($post['submissiondate']));                
                }else{
                    $model->receivingdate = date('Y-m-d', strtotime($post['receivingdate']));
                }
                $model->remarks = $post['remarks'];
                $model->submissiondate = date('Y-m-d', strtotime($post['submissiondate']));  
                $model->clientid = $post['clientid'];
                $model->submissionmedium = $post['submissionmedium'];
                $model->validity = $post['validity'];
                
                if($model->validate()){                    
                    $model->save();
                    $class = "success";                    
                    $msg = "Proposal Added Successfully, Please add Project details.";	
                    $result = 1;
                }else{
                    $class = "danger";
                    $msg = "Proposal didn't Added. Contact Admin.";
                }
            //}
            
            /*$result = Yii::$app->projects->pr_add_update_project_detail("I", NULL, $project_name, $short_name, $project_type, $description, $address, $contact_person, $contact_no, $alternate_contact_no, $project_cost, $start_date, $end_date, $num_working_days, $duration_month, $num_manpower, $technology_used, $manager_dept, $approval_doc, "Started", 'Y');
            
            if($result > '0'){
                $class = "success";
                $msg = "Project Added Successfully";
				if($post['enterpcb']==1){
					$msg = "Project Added Successfully, Please add Project Cost Breakdown below.";
				}
            }else{
                $class = "danger";
                $msg = "Project didn't Added. Contact Admin.";
            }*/
            
            /*
             * Logs
             */
            $logs['action_type']="I";
            $logs['$proposal_id']=$model->id;
            $logs['activeuser']=$model->activeuser;
            $logs['sessionid']=$model->sessionid;
            $logs['cdacdeptid']=$model->cdacdeptid;
            $logs['cost']=$model->cost;
            $logs['validity']=$model->validity;
            $logs['proposalnumber']=$model->proposalnumber;
            $logs['proposaltype']=$model->proposaltype;
            $logs['receivingdate']=$model->receivingdate;
            $logs['remarks']=$model->remarks;
            $logs['submissiondate']=$model->submissiondate;
            $logs['submissionmedium']=$model->submissionmedium;
            $logs['validity']=$model->validity;
            
            $logs['result']=$result;
            $jsonlogs = json_encode($logs);
            
            Yii::$app->utility->activities_logs("pmis_Proposal", NULL, NULL, $jsonlogs, $msg);
            Yii::$app->getSession()->setFlash($class, $msg);
            
            $this->view->params['projectType']=  \app\modules\manageproject\models\Projecttype::find()->where(['deleted'=>0])->all();
            $this->view->params['client'] = Yii::$app->projectcls->selectClientdetails();
                
            $this->view->params['submissiontype']=  \app\modules\manageproject\models\Submissiontype::find()->where(['deleted'=>0])->all();
                     
            
            
            
        }elseif(isset($_GET['key']) AND !empty($_GET['key']) AND isset($_GET['key1']) AND !empty($_GET['key1'])){
            //key = orderid
            //key1 = clientid
            
            $id = Yii::$app->utility->decryptString($_GET['key']);
            $status = Yii::$app->utility->decryptString($_GET['key1']);
            
            
            if(empty($id)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid params value found.');
                return $this->redirect($url);
            }
            
            
            
        }
        
        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('create', ['menuid'=>$menuid, 'model'=>$model]);
    }  
    
    
    
    public function actionIndex()
    {
        $this->view->params['roleasgn'] = Yii::$app->projectcls->GetRole();
        
        $searchModel = new ClientContactSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if(empty($dataProvider))
        {
            return $this->redirect(['404']);
        }
        
        Csuserlog::getUserlog('client-contact/index', Yii::$app->session->getId(), Yii::$app->user->id);
        return $this->render('index', [
            'model' => new Ordermaster(),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ClientContact model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {           
        $this->view->params['roleasgn'] = Yii::$app->projectcls->GetRole();
        
        if (isset($_SESSION['prjsession'])) {            
            if(Yii::$app->projectcls->Clientcntct($_SESSION['prjsession']) !=NULL){
                $id = Yii::$app->projectcls->Clientcntct($_SESSION['prjsession'])->id;
            } else {
                return $this->redirect(['/client-contact/index']);                
            }           
        } 
        Csuserlog::getUserlog('client-contact/view('.$id.')', Yii::$app->session->getId(), Yii::$app->user->id);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'searchModel' => ClientDetail::find()->where(['id' => $id])->all(),
            'model1' => new ClientDetail()
        ]);
        
    }

    /**
     * Creates a new ClientContact model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
     

    /**
     * Updates an existing ClientContact model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->view->params['roleasgn'] = Yii::$app->projectcls->GetRole();
        
        if (isset($_SESSION['prjsession'])) {            
            if(Yii::$app->projectcls->Clientcntct($_SESSION['prjsession']) !=NULL){
                $id = Yii::$app->projectcls->Clientcntct($_SESSION['prjsession'])->id;
            } else {
                return $this->redirect(['/client-contact/index']);                
            }           
        }
        
        $this->view->params['chk']=Yii::$app->projectcls->CheckLogin();
        $this->view->params['client']=ClientDetail::find()->where(['deleted'=>0, 'cdacdeptid' =>Yii::$app->projectcls->SelectManpower(Yii::$app->user->identity->manpowerid)[0]->cdacdeptid])->all(); 
        
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Csuserlog::getUserlog('client-contact/update('.$model->id.')', Yii::$app->session->getId(), Yii::$app->user->id);
            Yii::$app->getSession()->addFlash('success', 'Saved Successfully!');            
            return $this->redirect(['view', 'id' => $model->id]);
        } else {            
            //Yii::$app->getSession()->addFlash('danger', 'Sorry! Some error occured while saving...');
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ClientContact model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        //$this->findModel($id)->delete();        
        $this->findModel($id);
        Yii::$app->db->createCommand()->update('client_contact', ['deleted' => 1,'sessionid' => $model->sessionid, 'activeid' => Yii::$app->user->getId()], 'id = '.$id)->execute();
        Csuserlog::getUserlog('client-contact/delete('.$model->id.')', Yii::$app->session->getId(), Yii::$app->user->id);
        return $this->redirect(['index']);
    }

    /**
     * Finds the ClientContact model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ClientContact the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ClientContact::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    protected function findModelo($id)
    {
        if (($model = Ordermaster::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }  
    
    protected function findModelp($id)
    {
        if (($model = Projectdetail::findOne(['orderid' => $id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }  
}
