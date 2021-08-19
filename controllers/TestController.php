<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use  yii\web\Session;
//use app\models\ContactForm;

class TestController extends Controller
{
    public function actionIndex()
    {
	require_once './mpdf/mpdf.php';
$mpdf = new \mPDF();

/* Step -I  Import uploaded files */
$mpdf->SetImportUse();
$path = getcwd() . '/SanctionLetter.pdf';
$pagecount = $mpdf->SetSourceFile($path);

for ($i=1;$i<=$pagecount;$i++)
{
$import_page = $mpdf->ImportPage($i);
$mpdf->UseTemplate($import_page);

        if ($i < $pagecount)
            $mpdf->AddPage();
}

/* Step -II Add Noting Here */
$mpdf->AddPage();
$mpdf->WriteHTML("yesss");

/* Step -III Upload Other Docs Here */
$mpdf->AddPage();
$pagecount = $mpdf->SetSourceFile($path);

for ($i=1;$i<=$pagecount;$i++)
{
$import_page = $mpdf->ImportPage($i);
        $mpdf->UseTemplate($import_page);

        if ($i < $pagecount)
            $mpdf->AddPage();
}





$mpdf->Output();
    }
    

}
