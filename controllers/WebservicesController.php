<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use  yii\web\Session;
//use app\models\ContactForm;

class WebservicesController extends Controller
{
   
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

	public function actionIndex()
    {
    	 // echo "Test web.............";

         $getUserData = Yii::$app->utility->web_app_get_employee_details(); 

         // echo "<pre>"; print_r($getUserData); die();

         $arr['employees_data'] = $getUserData;
         $arr['parse'] = 1;


         echo json_encode($arr); die();

    }   
}
