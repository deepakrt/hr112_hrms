<?php

namespace app\modules\admin\controllers;
use yii;
use yii\web\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        return $this->redirect(Yii::$app->homeUrl.'admin/manageemployees');
//        return $this->render('index');
    }
}
