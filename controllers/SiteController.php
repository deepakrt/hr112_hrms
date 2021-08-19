<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use  yii\web\Session;
//use app\models\ContactForm;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout','fpwd'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                 ],
            ],
        ];
    }

    public function actions()
    {
        return [
            /* 'error' => [
                'class' => 'yii\web\ErrorAction',
            ], */
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionError()
    {
			return $this->redirect(['/']);   
        $exception = Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            //$this->layout = 'yourNewLayout';
           // return $this->render('error', ['exception' => $exception]);
        }
    }
	public function actionFpwd(){
		$model = new LoginForm();
		$msj=[];
		//echo "<pre>";print_r($_POST);die;
		if(isset($_POST['LoginForm'])){
		if(isset($_POST['LoginForm']['email']) && filter_var($_POST['LoginForm']['email'], FILTER_VALIDATE_EMAIL)) {
			$email=$_POST['LoginForm']['email'];
			if(Yii::$app->inventory->send_fpwd_email($email)){
				$msj['success']='eMail Sent Successfully, Please check you Inbox';
			}else{
				$msj['error']='There is an error on sending email, Please Try again later';
			}
		}else{
			$msj['error']='Please enter valid email Address';
			//return $this->redirect(['site/fpwd']);        
		}
		}
		return $this->render('fpwd', [
            'msj' => $msj,
            'model' => $model,
        ]);
	}
	
	
	public function actionIndex()
    {
  	return $this->redirect(['site/login']);        
        //return $this->render('index');
    }
    // public function actionLogin1()
    // {
  	// if (!\Yii::$app->user->isGuest) {
            // return $this->goHome();
        // }

        // $model = new LoginForm();
        // if ($model->load(Yii::$app->request->post()) && $model->login()) {
            
            // return $this->goBack();
        // }
        // return $this->render('login_a', [
            // 'model' => $model,
        // ]);
    // }

    public function actionLogin()
    {

        //  echo "dfdf"; die();

        /*echo "<pre>"; print_r($_POST);
        die();*/
        if(!\Yii::$app->user->isGuest){
            if (Yii::$app->user->identity->e_id != NULL) {
                $id = Yii::$app->utility->encryptString("1");
                $session = Yii::$app->session;
                $session->set('activemenu', $id);
                Yii::$app->utility->activities_logs("Login", "/site/login", NULL, NULL, "User Login");
                $id1 = Yii::$app->utility->encryptString("1");
                Yii::$app->getSession()->setFlash('danger', '');
                Yii::$app->getSession()->setFlash('success', '');
                return $this->redirect(Yii::$app->homeUrl."dashboard?securekey=$id&securekeyl=$id1");
            }
        }
        
     
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
//            die("ok");
          return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }
    public function actionSwitchrole(){
        if(Yii::$app->user->isGuest){
            return $this->goBack();
        }
        if(isset($_POST['role_id']) AND !empty($_POST['role_id'])){
            $role_id =  Yii::$app->utility->decryptString($_POST['role_id']);
            if(empty($role_id)){
                if (Yii::$app->user->identity->e_id != NULL) {
                    \Yii::$app->utility->activities_logs("Logout", "/site/logout", NULL, NULL, "User Logged Out.");
                    Yii::$app->user->logout();
                    $session = Yii::$app->session;
                    $session->destroy();
                }
                return $this->goHome();
            }
            /*
             * Logs
             */
            $logs['old_role']=Yii::$app->user->identity->role;
            $logs['new_role']=$role_id;
            $jsonlogs = json_encode($logs);
            Yii::$app->utility->activities_logs("Login", NULL, NULL, $jsonlogs, "User Switched Role.");
            
            \Yii::$app->session->set('newrole',$role_id);
            return $this->goHome();
            //die($role_id);
        }else{
            \Yii::$app->utility->activities_logs("Logout", "/site/logout", NULL, NULL, "User Logged Out.");
            Yii::$app->user->logout();
            $session = Yii::$app->session;
            $session->destroy();
            return $this->goHome();
        }
        echo "<pre>";print_r($_POST); die;
    }
    public function actionLogout()
    {
        \Yii::$app->utility->activities_logs("Logout", "/site/logout", NULL, NULL, "User Logged Out.");
        Yii::$app->user->logout();
        $session = Yii::$app->session;
        $session->destroy();
        return $this->goHome();
    }

    // public function actionContact()
    // {
        // $model = new ContactForm();
        // if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            // Yii::$app->session->setFlash('contactFormSubmitted');

            // return $this->refresh();
        // }
        // return $this->render('contact', [
            // 'model' => $model,
        // ]);
    // }

    // public function actionAbout()
    // {
        // return $this->render('about');
    // }
}
