<?php

namespace app\modules\inventory\controllers;
use yii;
use yii\web\Controller;
use app\models\Reportchart; 

class ReportchartController extends Controller
{
	public function beforeAction($action){
		$url =Yii::$app->homeUrl;
        if (!\Yii::$app->user->isGuest) {
            if(isset($_GET['securekey']) AND !empty($_GET['securekey'])){
                $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
				
                if(empty($menuid)){ 
 					header("Location: $url",  true,  301 );die;	
					//return $this->redirect(Yii::$app->homeUrl);
				}
                 $chkValid = Yii::$app->utility->validate_url($menuid);
                 if(empty($chkValid)){ return $this->redirect(Yii::$app->homeUrl); }
                return true;
            }else{ return $this->redirect(Yii::$app->homeUrl); }
        }else{
             header("Location: $url");die;
        }
        parent::beforeAction($action);
    }
   
   public function actionGet_cat_code()
    {
		// echo "<pre>";print_r($_POST);die;
		if(!isset($_POST['cat_id']) || empty($_POST['cat_id'])){
			die('0');
		}
		$cat_id=$_POST['cat_id'];
		$ccode=$class_code=$_POST['ccode'];
		if(isset($_POST['ccode']) && !empty($_POST['ccode'])){
			$ccode=$_POST['ccode'];
		}
		// die($ccode);
 		$res=Yii::$app->inventory->get_cat_item($cat_id,$ccode);

        // echo "<pre>";print_r($res);die;
        $html='<option value="">-- Select --</option>';
		foreach($res as $r){
			$html.='<option alt="'.$r['Item_type'].'" data-quantity="'.$r['Quantity'].'" label1="'.$r['Measuring_Unit'].'" value="'.$r['ITEM_CODE'].'">'.$r['item_name'].'</option>';
		}
		
        if(!isset($_POST['page'])){
            echo $html.'<option value="000">Other</option>';
        }else{
            echo $html;
        }
        die;
	}

   public function actionIndex()
    {
        $this->layout = '@app/views/layouts/admin_layout.php';    
         //return $this->render('index');
         $groups=Yii::$app->inventory->get_groups();
	 $category=Yii::$app->inventory->get_category();
         //$cat_id=$_REQUEST['cat_id'];
        // $class_code=$_REQUEST['ccode'];
         //echo "<pre>";print_r($cat_id);print_r($ccode);die;
         $items = Yii::$app->inventory->get_cat_item(0,0); //Inventoryutility
         //echo "<pre>";print_r($items);die;
         return $this->render('index', ['groups'=>$groups,'category'=>$category,'items'=>$items]);
    }

    public function actionViewfilter()
    {
        $this->layout = '@app/views/layouts/admin_layout.php';    
         //return $this->render('index');
         $groups=Yii::$app->inventory->get_groups();
	 $category=Yii::$app->inventory->get_category();
         //echo "<pre>";print_r($category);die;
         $cat_code=$_REQUEST['cat_id'];
         $class_code=$_REQUEST['ccode'];
         //echo "<pre>";print_r($cat_id);print_r($ccode);die;
         $lists = Yii::$app->inventory->get_cat_item($cat_code,$class_code); //Inventoryutility
          //echo "<pre>";print_r($lists );die;
         return $this->render('index', ['groups'=>$groups,'category'=>$category,'lists'=>$lists]);
    } 

}
