<?php

namespace app\modules\employee\controllers;
use yii;
use yii\web\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        return $this->redirect(Yii::$app->homeUrl."employee/information");
    }
    
}
