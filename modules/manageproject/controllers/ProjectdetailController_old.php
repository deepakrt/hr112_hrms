<?php

namespace app\modules\manageproject\controllers;

use Yii;
use yii\web\Controller;
use app\modules\manageproject\models\Projectdetail;
use app\modules\manageproject\models\Ordermaster;
use app\modules\manageproject\models\OrdermasterSearch;
use yii\web\NotFoundHttpException;
use app\modules\manageproject\facade\Csuserlog;


/**
 * ProjectsController implements the CRUD actions for Projects model.
 */
class ProjectdetailController extends Controller
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
        $projects = Yii::$app->pmis_project->pmis_get_projects();        
        $this->layout = '@app/views/layouts/admin_layout.php';     
                 
        $searchModel = new \app\modules\manageproject\models\ProjectdetailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if(empty($dataProvider))
        {
            return $this->redirect(['404']);
        }
        
        //Csuserlog::getUserlog('projectdetail/index', Yii::$app->session->getId(), Yii::$app->user->id);
        return $this->render('index', [
             'menuid'=>$menuid, 
             'projects'=>$projects                
        ]);
                    
    }
    
    /*public function actionDashboardall(){
        $this->view->params['roleasgn'] = Yii::$app->projectcls->GetRole();
        
        if(Yii::$app->projectcls->MemberRole()=='member'){
            Yii::$app->getSession()->addFlash('danger', 'Access Denied!');
            return $this->redirect(['site/index']);   
        }else {  
            $searchModel = new \app\modules\manageproject\models\ProjectdetailSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            if(empty($dataProvider))
            {
                return $this->redirect(['404']);
            }
            Csuserlog::getUserlog('projectdetail/dashboardall', Yii::$app->session->getId(), Yii::$app->user->id);
            return $this->render('dashboardall', [
                'model1' => new Ordermaster(),
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }


    public function actionView($id)
    {        
        $this->view->params['roleasgn'] = Yii::$app->projectcls->GetRole();
        
        if (isset($_SESSION['prjsession'])) {
            $id = Yii::$app->projectcls->Projectwithorder($_SESSION['prjsession'])->id;
        } 
            
            if (in_array(Yii::$app->projectcls->ProjectDetails($id)->orderid, Yii::$app->projectcls->MapMember())) { 
                
                if(Yii::$app->projectcls->SelectManpower(Yii::$app->user->identity->manpowerid) != null){
                    Csuserlog::getUserlog('projectdetail/view('.$id.')', Yii::$app->session->getId(), Yii::$app->user->id);
                    return $this->render('view', [
                        'model1' => new Ordermaster(),
                        'model' => $this->findModel($id),
                    ]);
                } else{                    
                    Yii::$app->getSession()->addFlash('danger', 'User does not exists!');
                    return $this->redirect(['site/index']);
                }
            } else{                   
                if(Yii::$app->projectcls->MemberRole()=='member'){
                    Yii::$app->getSession()->addFlash('danger', 'Access Denied!');
                    return $this->redirect(['site/index']);
                }else{
                    Yii::$app->getSession()->addFlash('danger', 'Access Denied!');
                    return $this->redirect(['site/projectdetail']);    
                }
            }    
      
        
    }
    
    public function actionCostmatrix($id)
    {        
        $this->view->params['roleasgn'] = Yii::$app->projectcls->GetRole();
        
        if (isset($_SESSION['prjsession'])) {
            $id = Yii::$app->projectcls->Projectwithorder($_SESSION['prjsession'])->id;
        } 
        
        if(Yii::$app->projectcls->MemberRole()=='member'){
            Yii::$app->getSession()->addFlash('danger', 'Access Denied!');
            return $this->redirect(['site/index']);   
        }else {  
            
            $model = $this->findModel($id);
            
            if (in_array(Yii::$app->projectcls->ProjectDetails($id)->orderid, Yii::$app->projectcls->MapMember())) { 
                $searchModel = new Projectdetail();                    
                $dataProvider = $searchModel->searchProject(Yii::$app->request->queryParams, $model->id);
                //$dataProvider->pagination->pageSize = 10;     
                if(empty($dataProvider))
                {
                    return $this->redirect(['404']);
                }        

                Csuserlog::getUserlog('projectdetail/costmatrix('.$id.')', Yii::$app->session->getId(), Yii::$app->user->id);
                return $this->render('costmatrix', [
                    'model1' => new Ordermaster(),
                    'model' => $model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                ]);
            }else{
                    Yii::$app->getSession()->addFlash('danger', 'Access Denied!');
                    return $this->redirect(['site/projectdetail']);    
            }  
        }
    }
    

    
    public function saveModel($model)
    {
       If (isset($_POST['Projectdetail']))
            {            
                $model->technologyid = implode(',',$_POST['Projectdetail']['technologyid']);
                //$model->manpowerid = implode(',',$_POST['Projects']['manpowerid']);
            }
           
        return $model->save();
    }
    
    public function actionCreate()
    {
        $this->view->params['roleasgn'] = Yii::$app->projectcls->GetRole();
        
        $model = new Projectdetail();
        
        $this->view->params['project'] = Yii::$app->projectcls->SelectProject();        
        $this->view->params['projecttype'] = Yii::$app->projectcls->Projecttype();
        $this->view->params['allprojects'] = Yii::$app->projectcls->AllProjects();
        
        $this->view->params['tech']=  \app\modules\manageproject\models\Projecttechnology::find()->where(['deleted'=>0])->all();

        if(Yii::$app->projectcls->MemberRole()=='member'){
            Yii::$app->getSession()->addFlash('danger', 'Access Denied!');
            return $this->redirect(['site/index']);   
        }else {     
            //if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if ($model->load(Yii::$app->request->post())){ // && $this->saveModel($model)) {
                try
                {
                    if(Yii::$app->projectcls->ProjectFileno($model->filenumber) != NULL){                        
                        Yii::$app->getSession()->addFlash('danger', 'Record with same file number already exists!');                           
                        return $this->redirect(['projectdetail/view', 'id' => Yii::$app->projectcls->ProjectFileno($model->filenumber)->id]);
                    } elseif (Yii::$app->projectcls->ProjectRefno($model->projectrefno) != NULL){                        
                        Yii::$app->getSession()->addFlash('danger', 'Record with same Project Number already exists!');                        
                        return $this->redirect(['projectdetail/view', 'id' => Yii::$app->projectcls->ProjectRefno($model->projectrefno)->id]);
                    }
                    else {                      
                        $model->cdacdeptid = Yii::$app->projectcls->SelectManpower(Yii::$app->user->identity->manpowerid)[0]->cdacdeptid;
                        
                        $query1 = Ordermaster::find()->select('id')
                                ->where([
                                    'deleted' => 0, 
                                    'cdacdeptid' =>Yii::$app->projectcls->SelectManpower(Yii::$app->user->identity->manpowerid)[0]->cdacdeptid
                                        ])->andwhere(['activeuser' => Yii::$app->user->getId()])->all();
                        
                        $order;
                        foreach ($query1 as $m)
                            $order[] = $m->id;     
                        
                        if (in_array($model->orderid, $order)) {
                            $this->saveModel($model);                       
                        
                            Csuserlog::getUserlog('projectdetail/create('.$model->id.')', Yii::$app->session->getId(), Yii::$app->user->id);
                            Yii::$app->getSession()->addFlash('success', 'Saved Successfully!');
                            
                            $session = Yii::$app->session;
                            $session['prjsession'] = $model->orderid;

                            return $this->redirect(['/investigator/create', 'model' => new Projectdetail(), 'o' => $model->id]);
                            //return $this->redirect(['/manpowermapping/create', 'model' => new Investigator(), 'p' => $model->id]);
                            //return $this->redirect(['view', 'id' => $model->id]);
                        } else{
                            Yii::$app->getSession()->addFlash('danger', 'Access Denied!');
                            return $this->redirect(Yii::$app->request->referrer);
                            //return $this->redirect(['site/projectdetail']);    
                        }
                    }
                } catch (ErrorException $e) {
                    Yii::warning("Sorry! Not Saved....");
                }
            } else {  
                if (isset($_SESSION['prjsession'])) {
                    
                    if(Yii::$app->projectcls->Projectwithorder($_SESSION['prjsession']) == null){
                        return $this->render('create', [
                            'model' => $model,
                        ]);
                    } else {
                        return $this->render('view', [
                            'model' => $this->findModel(Yii::$app->projectcls->Projectwithorder($_SESSION['prjsession'])->id),
                        ]);
                    }
                } else {
                        return $this->render('create', [
                            'model' => $model,
                        ]);
                }
                    //Yii::$app->getSession()->addFlash('danger', 'Sorry! Some error occured while saving...');
         
            }
        }
    }

    public function actionUpdate($id)
    {
        $this->view->params['roleasgn'] = Yii::$app->projectcls->GetRole();
        
        $this->view->params['tech']=  \app\modules\manageproject\models\Projecttechnology::find()->where(['deleted'=>0])->all();
        
        if (isset($_SESSION['prjsession'])) {
            $id = Yii::$app->projectcls->Projectwithorder($_SESSION['prjsession'])->id;
        } 
        if (in_array(Yii::$app->projectcls->ProjectDetails($id)->orderid, Yii::$app->projectcls->MapMember())) { 
            $model = $this->findModel($id);
        
            $Tech = explode(',', $model->technologyid); //Explode before viewing
            $model->technologyid = $Tech; // asign to attribute         

            //$Qual = explode(',', $model->manpowerid);
            //$model->manpowerid = $Qual;
            //$this->view->params['project'] = Yii::$app->projectcls->SelectProject();
            $this->view->params['project'] = NULL;
            $this->view->params['projecttype'] = Yii::$app->projectcls->Projecttype();
            $this->view->params['allprojects'] = Yii::$app->projectcls->AllProjects();

            if(Yii::$app->projectcls->MemberRole()=='member'){
                Yii::$app->getSession()->addFlash('danger', 'Access Denied!');
                return $this->redirect(['site/index']);   
            }else {    
                if (in_array(Yii::$app->projectcls->ProjectDetails($id)->orderid, Yii::$app->projectcls->MapMember())) { 
                    if ($model->load(Yii::$app->request->post()) && $this->saveModel($model)) {
                        
                        Csuserlog::getUserlog('projectdetail/update('.$model->id.')', Yii::$app->session->getId(), Yii::$app->user->id);
                        Yii::$app->getSession()->addFlash('success', 'Saved Successfully!');            
                        $session = Yii::$app->session;
                        $session['prjsession'] = $model->orderid;
                
                        return $this->redirect(['/investigator/create', 'model' => new Projectdetail(), 'o' => $model->id]);
            
                        //return $this->redirect(['view', 'id' => $model->id]);
                    } else {            
                        //Yii::$app->getSession()->addFlash('danger', 'Sorry! Some error occured while saving...');
                        return $this->render('update', [
                            'model1' => new Ordermaster(),
                            'model' => $model,
                        ]);
                    }
                }else{
                    Yii::$app->getSession()->addFlash('danger', 'Access Denied!');
                    return $this->redirect(['site/projectdetail']);    
                }
            }
        }else {
            $model = new Projectdetail();
            return $this->redirect(['create']);
        }

        //if ($model->load(Yii::$app->request->post()) && $model->save()) {
        
    }

    public function actionDelete($id)
    {
        //$this->findModel($id)->delete();
        $model = $this->findModel($id);
        Yii::$app->db->createCommand()->update('projectdetail', ['deleted' => 1,'sessionid' => $model->sessionid, 'activeuser' => Yii::$app->user->getId()], 'id = '.$id)->execute();
        Csuserlog::getUserlog('projectdetail/delete('.$id.')', Yii::$app->session->getId(), Yii::$app->user->id);
        //return $this->redirect(['index']);
        return $this->redirect(['/site/projectdetail']);
    }
    
   
    
    public function actionAlert()
    {        
        
        $this->view->params['roleasgn'] = Yii::$app->projectcls->GetRole();
        
        if(Yii::$app->user->id==NULL)
            return $this->render('index');
        Csuserlog::getUserlog('projectdetail/alert', Yii::$app->session->getId(), Yii::$app->user->id);
        return $this->render('alert');        
    }    
    
    public function actionRpt()
    {
        $this->view->params['roleasgn'] = Yii::$app->projectcls->GetRole();
        
        //$model = new Projects();
        
        
//        /$sk1=$_POST['ids']; 
        $model = new Ordermaster();
        //$posts = Ordermaster::find();//->all(); 
        
       
	
        If (isset($_POST['ids'])){
            //print_r("<pre>");
            //print_r($_POST['ids']); die();
            
            //$sk1=$_POST['ids']; 
            
            //$th=''; 
            $p_type=0;
            $p_name=0;
            $p_client=0;
            $p_odate=0;
            $p_amount=0;
            $p_bill=0;
            $p_audit=0;   
            $p_billD=0;
            $p_billA=0;

            foreach($_POST['ids'] as $data)
            {
                switch ($data) {
                    case 1:
                        $p_name=1;
                        break;
                    case 2:
                        $p_type=1;
                        break;
                    case 3:                        
                        $p_client=1;
                        break;
                    case 4:
                        $p_odate=1;
                        break;
                    case 5:
                        $p_amount=1;
                        break;
                    case 6:
                        $p_bill=1;
                        break;
                    case 7:
                        $p_billD=1;
                        //$p_audit=1;
                        break;
                    case 8:
                        $p_billA=1;
                        //$p_audit=1;
                        break;
                    default:   
                        break;
                }
            }             
               
            $searchModel = new OrdermasterSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            
            $arr = GridView::widget([
                        
                        'dataProvider' => $dataProvider,
                        //'filterModel' => $searchModel,
                        'tableOptions' => ['class' => 'table table-responsive table-bordered table-striped table-hover'],
                        'layout' => "{items}\n{pager}",
                        
                        'columns' => [                           
                            [
                                'class' => 'yii\grid\DataColumn',
                                'header' => 'Project Name',                                
                                'format' => 'raw',
                                'visible' => $p_name ? TRUE :FALSE,
                                'value' => function ($dataProvider) {
                                        return $dataProvider->projectname;                                                                
                                },
                            ],                            
                            [
                                'attribute' => 'projectType.type',
                                'visible' => $p_type ? TRUE :FALSE,
                                'value' => 'projectType.type'
                            ],
                            [
                                'attribute' => 'clientdetail1.deptName',
                                'visible' => $p_client ? TRUE :FALSE,
                                'value' => 'clientdetail1.deptName',
                                'label' => 'Client Name'
                            ],
                            [
                                'attribute' => 'orderdate',
                                'visible' => $p_odate ? TRUE :FALSE,
                                //'value' => 'orderdate',
                                'label' => 'Order Date',
                                'value' => function($dataProvider){ 
                                    return Yii::$app->formatter->asDate($dataProvider->orderdate);                                    
                                }
                            ],
                            [
                                'attribute' => 'amount',
                                'visible' => $p_amount ? TRUE :FALSE,
                                //'value' => 'amount',
                                'label' => 'Order Amount',
                                'value' => function ($dataProvider) {
                                    return 'Rs. '. $dataProvider->amount .' Lakhs';
                                }
                            ],
                            [
                                //'attribute' => 'bill.billnumber',
                                'visible' => $p_bill ? TRUE :FALSE,
                                //'value' => 'bill.billnumber'? 'bill.billnumber':'-',
                                'label' => 'Bill Number',
                                'format' => 'raw',
                                'value' => function ($dataProvider) {
                                    if($dataProvider->getBill()->exists()){
                                        foreach ($dataProvider->bill as $group1) {                                            
                                           // $groupNames1[] = "<b>Bill No: </b>".$group1->billnumber ."<br/> <b>Bill Date: </b>".Yii::$app->formatter->asDate($group1->billdate)."<br/>"
                                            //        . "<b>Bill Amount: </b> Rs. ".$group1->billamount." /-<br/><br/>";
                                           $groupNames1[] = $group1->billnumber ."<br/>";
                                        }
                                      return  implode("\n", $groupNames1);
                                    } else
                                    return ' - ';
                                }
                            ],
                            [
                                //'attribute' => 'bill.billnumber',
                                'visible' => $p_billD ? TRUE :FALSE,
                                //'value' => 'bill.billnumber'? 'bill.billnumber':'-',
                                'label' => 'Bill Date',
                                'format' => 'raw',
                                'value' => function ($dataProvider) {
                                    if($dataProvider->getBill()->exists()){
                                        foreach ($dataProvider->bill as $group1) {                                            
                                            $groupNames1[] = Yii::$app->formatter->asDate($group1->billdate)."<br/>";
                                        }
                                      return  implode("\n", $groupNames1);
                                    } else
                                    return ' - ';
                                }
                            ],
                             
                            [
                                //'attribute' => 'bill.billnumber',
                                'visible' => $p_billA ? TRUE :FALSE,
                                //'value' => 'bill.billnumber'? 'bill.billnumber':'-',
                                'label' => 'Bill Amount',
                                'format' => 'raw',
                                'value' => function ($dataProvider) {
                                    if($dataProvider->getBill()->exists()){
                                        foreach ($dataProvider->bill as $group1) {
                                           $groupNames1[] = "Rs. ".$group1->billamount." /-<br/>";
                                        }
                                      return  implode("\n", $groupNames1);
                                    } else
                                    return ' - ';
                                }
                            ],
                           
                        ],
                    ]);
                            
                            $arr1 = GridView::widget([
                        
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'tableOptions' => ['class' => 'table  table-bordered table-striped table-hover'],
                        'layout' => "{items}\n{pager}",
                        
                        'columns' => [                           
                            [
                                'class' => 'yii\grid\DataColumn',
                                'header' => 'Project Name',                                
                                'format' => 'raw',
                                'visible' => $p_name ? TRUE :FALSE,
                                
                            ],                            
                            [
                                'attribute' => 'projectType.type',
                                'visible' => $p_type ? TRUE :FALSE,
                                
                            ],
                            [
                                'attribute' => 'clientdetail1.deptName',
                                'visible' => $p_client ? TRUE :FALSE,
                                
                                'label' => 'Client Name'
                            ],
                            [
                                'attribute' => 'orderdate',
                                'visible' => $p_odate ? TRUE :FALSE,
                                //'value' => 'orderdate',
                                'label' => 'Order Date',
                                
                            ],
                            [
                                'attribute' => 'amount',
                                'visible' => $p_amount ? TRUE :FALSE,
                                //'value' => 'amount',
                                'label' => 'Order Amount',
                                
                            ],
                            [
                                //'attribute' => 'bill.billnumber',
                                'visible' => $p_bill ? TRUE :FALSE,
                                //'value' => 'bill.billnumber'? 'bill.billnumber':'-',
                                'label' => 'Bill Number',
                                'format' => 'raw',
                                
                            ],
                            
                        ],
                    ]);
            
                    $this->layout = FALSE;            
            return $this->render('rptindex', [
                //'model' => $model,
                //'searchModel' => $searchModel,
                //'dataProvider' => $dataProvider,                
                'arr' => $arr,                
                'arr1' => $arr1,
            ]);             
            
        } else {   
           
            return $this->render('rpt', [
                'model' => $model,
                'arr' => '',
                ]);
            
        }
    }
    
    

    public function actionReports()
    {    
        $this->view->params['roleasgn'] = Yii::$app->projectcls->GetRole();
        
        Csuserlog::getUserlog('projectdetail/reports', Yii::$app->session->getId(), Yii::$app->user->id);
        If (isset($_POST['Projectdetail']))
        {
            
            $id = implode($_POST['Projectdetail']['id']);
            
            echo $id;
            
            
        }
        else
        return $this->render('reports');
    }

    protected function findModel($id)
    {
        if (($model = Projectdetail::findOne(['id' => $id, 'deleted' => 0])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionLists() 
    {	   
        $p_name="";
	$p_type="";
	$p_client="";
	$p_odate="";
	$p_amount="";
	$p_audit="";
	$p_bill="";
        $model = new Ordermaster();
        $posts = Ordermaster::find()->all(); 
	$sk1=$_POST['ids']; 
        
        $th="";
        
        foreach($sk1 as $data)
        {
            switch ($data) {
                case 1:
                    $p_type=1;
                    $th .= "<th>Project Type</th>";  
                    break;
                case 2:
                    $p_client=2;
                    $th .= "<th>Client Name</th>";  
                    break;
                case 3:
                    $p_odate=3;
                    $th .= "<th>Order Date</th>";  
                    break;
                case 4:
                    $p_amount=4;
                    $th .= "<th>Amount</th>";  
                    break;
                case 5:
                    $p_bill=5;
                    $th .= "<th>Bill</th>";  
                    break;
                case 6:
                    $p_audit=6;
                    $th .= "<th>Audit</th>";  
                    break;
                case 7:
                    $p_name=7;
                    $th .= "<th>Project Name</th>";  
                    break;
                default:                    
            }

        }

	$query = new \yii\db\Query;
	$query  ->select(['ordermaster.projectname','ordermaster.orderdate','ordermaster.amount','project_type.type','clientdetail.deptName','auditmaster.audittype','billmaster.billnumber'])
                    ->from('ordermaster')
                    ->join('INNER JOIN','project_type','project_type.id=ordermaster.ordertype')
                    ->join('INNER JOIN','clientdetail','ordermaster.clientid =clientdetail.id')                    
                    ->join('LEFT JOIN','auditmaster','ordermaster.id =auditmaster.orderid')
                    ->join('LEFT JOIN','billmaster','ordermaster.id =billmaster.orderid')
                    ->orderBy(['(ordermaster.projectname)' => SORT_ASC]);	 
        $command = $query->createCommand();
        $data = $command->queryAll();
        
        echo "<table class='table table-striped table-bordered'>";
        echo "<tr>".$th."<tr>";
	
       
  
        foreach($data as $post)
        {
            echo "<tr>";
            if($p_name!="")
            { 
                echo "<td>";
                echo $post['projectname'];
                echo "</td>";                
            }
            
            if($p_type!="")
            { 
                echo "<td>";
                echo $post['type'];
                echo "</td>"; 
            }
	
            if($p_client!="")
            {
                echo "<td>";
                echo $post['deptName'];
		echo "</td>";
            }

            if($p_odate!="")
            {
                echo "<td>";
                echo Yii::$app->formatter->asDate($post['orderdate']);
		echo "</td>"; 
            }
		   
            if($p_amount!="")
            {
                echo "<td>";
                echo $post['amount'];
		echo "</td>"; 
            }
			 
            if($p_audit!="")
            {
                echo "<td>";
		echo $post['audittype'];
		echo "</td>";
            }
	
            if($p_bill!="")
            {
                echo "<td>";
		echo $post['billnumber'];
		echo "</td>";
            }
		  
            echo "</tr>";               
        }
        echo "</table>";   
    }  */
}
