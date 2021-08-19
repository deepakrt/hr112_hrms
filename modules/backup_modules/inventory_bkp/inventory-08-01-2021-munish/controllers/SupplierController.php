<?php

namespace app\modules\inventory\controllers;
use yii;
use yii\web\Controller;
use app\models\Supplier; 

class SupplierController extends Controller
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
        $category=Yii::$app->inventory->get_category();
        $lists = Yii::$app->inventory->store_get_supplier(0,0); //Inventoryutility
        return $this->render('index',['category'=>$category,'lists'=>$lists]);
    }

    public function actionViewfilter()
    {
        $this->layout = '@app/views/layouts/admin_layout.php';    
	 $category=Yii::$app->inventory->get_category();
         $Param_ITEM_CAT_CODE=$_REQUEST['cat_id'];
         $PARAM_Supplier_Code = 0;
        //echo "<pre>";print_r($Param_ITEM_CAT_CODE);die;
         $lists = Yii::$app->inventory->store_get_supplier($Param_ITEM_CAT_CODE,$PARAM_Supplier_Code); //Inventoryutility
       //echo "<pre>";print_r($lists );die;
         return $this->renderPartial('index', ['category'=>$category,'lists'=>$lists]);
    } 

   public function actionAdd()
    {
		 
	//echo "<pre>";print_r(Yii::$app->user->identity);die;
	$menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $this->layout = '@app/views/layouts/admin_layout.php';
       //echo "<pre>";print_r($_POST);die;
	 if(isset($_POST['Supplier']) AND !empty($_POST['Supplier'])){
            $post = $_POST['Supplier'];			
			$data['Supplier_name']	= $post['Supplier_name'];
                        $data['Supplier_address'] = $post['Supplier_address'];
                        $data['Phone_no']= $post['Phone_no'];
                        //$data['Category']= $post['Category'];
			//echo "<pre>";print_r($data);die;
			$res=Yii::$app->inventory->add_store_insert_Supplier_details($data);
			 if($res == '1'){
			    Yii::$app->getSession()->setFlash('success', 'Supplier added successfully');
			    return $this->redirect(Yii::$app->homeUrl."inventory/supplier?securekey=".$menuid); 
			}
			else {
			Yii::$app->getSession()->setFlash('danger', 'Invalid / Empty params found');
			return $this->redirect(Yii::$app->homeUrl."inventory/supplier?securekey=".$menuid); 
		      }			
		}
		$this->view->title = 'Add New Item';       	
 		$model = new Supplier();
        return $this->render('add', ['model'=>$model, 'menuid'=>$menuid]);
    }

   /*
     * Categorylink
     */
    public function actionCategorylink(){
                $id=$_REQUEST['id'];
                //echo "<pre>";print_r($id);die;
		$menuid = Yii::$app->utility->decryptString($_GET['securekey']);
                $menuid = Yii::$app->utility->encryptString($menuid);
		$data= Yii::$app->inventory->store_get_supplier(0,$id);              	
          return $this->renderPartial('categorylink', ['data'=>$data, 'menuid'=>$menuid]); 
          die;
    }

   public function actionSuppliercat_mapping() {              
              $datan=$_REQUEST['ITEM_CAT_CODE'];
              $CAT_CODE  = explode(",",$datan);
	      $m=count($CAT_CODE);   
               //print_r($m);die;
              for($i=0;$i<$m;$i++){
                     $Param_Supplier_Code=$_REQUEST['supplier_code'];                    
                     $Param_ITEM_CAT_CODE= $CAT_CODE[$i];      
            Yii::$app->inventory->sppliercat_mapping($Param_Supplier_Code,$Param_ITEM_CAT_CODE);
                       /* $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
			$menuid = Yii::$app->utility->encryptString($menuid);
                     if($res == '1'){
			    Yii::$app->getSession()->setFlash('success', 'Category Link successfully');
			    return $this->redirect(Yii::$app->homeUrl."inventory/supplier?securekey=".$menuid); 
			}
			else {
			Yii::$app->getSession()->setFlash('danger', 'Data Already Exists');
			return $this->redirect(Yii::$app->homeUrl."inventory/supplier?securekey=".$menuid); 
		      }*/
 		}
			
  }

}
