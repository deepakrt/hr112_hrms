<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use  yii\web\Session;
//use app\models\ContactForm;

class PdfController extends Controller
{
    public function actionIndex()
    {
		$aaa =  Yii::$app->urlManager->parseRequest(Yii::$app->request);
		echo "<pre>";print_r($aaa);
	//echo "asdf"; die;
	// require_once './mpdf/mpdf.php';
	// $mpdf = new \mPDF();
	// $mpdf->SetImportUse();
	// $mpdf->enableImports=true;
	// $aa=$mpdf->SetDocTemplate($path, true);
	// $folder = getcwd().FTS_Documents.Yii::$app->user->identity->e_id;
	// echo $folder; 
	// $files = scandir($folder);
	// foreach($files as $f){
		// if($f != '.' OR $f != '..'){
			// $pagecount = $mpdf->SetSourceFile($f);
			// echo $pagecount; die;
			// $tplId = $mpdf->ImportPage($pagecount);
			// $mpdf->UseTemplate($tplId);
		// }
	// }
	// echo "<pre>";print_r($files); die;
	// $mpdf->WriteHTML($html = '<h4>HEllo</h4>');
	
	// $mpdf->WriteHTML($html);
        // $file = $mpdf->Output('download.pdf', 'I');
        // header('Content-Type: application/pdf');
        // header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        // header("Cache-Control: max-age=0");
	// die;
  	//return $this->redirect(['site/test']);        
        //return $this->render('index');
    }
    

}
