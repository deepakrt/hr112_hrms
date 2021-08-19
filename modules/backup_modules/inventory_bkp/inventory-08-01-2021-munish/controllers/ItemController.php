<?php

namespace app\modules\inventory\controllers;
use yii;
use yii\web\Controller;
use app\models\Item; 

class ItemController extends Controller
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

   public function actionIndex()
    {
        $this->layout = '@app/views/layouts/admin_layout.php';    
         //return $this->render('index');
         $groups=Yii::$app->inventory->get_groups();
	 $category=Yii::$app->inventory->get_category();
         //echo "<pre>";print_r($category);die;
         $lists = Yii::$app->inventory->get_cat_item(NULL,NULL); //Inventoryutility
         return $this->render('index', ['groups'=>$groups,'category'=>$category,'lists'=>$lists]);
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
         return $this->renderPartial('index', ['groups'=>$groups,'category'=>$category,'lists'=>$lists]);
    } 

   public function actionAdd()
    {
		 
	//echo "<pre>";print_r(Yii::$app->user->identity);die;
	$menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $this->view->title = 'Add New Item';
        $this->layout = '@app/views/layouts/admin_layout.php';
	if(isset($_POST) && !empty($_POST)){
			$post=$_POST['Item'];
			unset($_POST['Item']);
			$data['Classification_Code']    =$post['group'];
			$data['Item_Cat_Code']		=$post['category'];
                        $data['item_name']		=$post['item_name'];
			$data['Item_type']		=$post['item_type'];
			$data['Measuring_Unit']		=$post['units'];
			

			//echo "<pre>";print_r($data);die;
			$res=Yii::$app->inventory->store_insert_Item_details($data);
			if($res == '1'){
			    Yii::$app->getSession()->setFlash('success', 'Item added successfully');
			    return $this->redirect(Yii::$app->homeUrl."inventory/item?securekey=".$menuid); 
			}
			else {
			Yii::$app->getSession()->setFlash('danger', 'Invalid / Empty params found');
			return $this->redirect(Yii::$app->homeUrl."inventory/item?securekey=".$menuid); 
		      }					
		}
		
		$groups=Yii::$app->inventory->get_groups();
		$category=Yii::$app->inventory->get_category();
		$cost_centre=array();//Yii::$app->inventory->get_cost_centre();
		$unit_master=Yii::$app->inventory->get_unit_master();
                $itemtype_master=Yii::$app->inventory->get_item_type();

 		$model = new Item();
        return $this->render('add', ['model'=>$model,'groups'=>$groups,'category'=>$category,'cost_centre'=>$cost_centre,'unit_master'=>$unit_master,'itemtype_master'=>$itemtype_master,'menuid'=>$menuid]);
    }


}
