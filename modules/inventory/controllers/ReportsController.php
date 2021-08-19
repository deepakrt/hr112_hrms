<?php
namespace app\modules\inventory\controllers;
use yii;
use yii\web\Controller;
use app\models\Inventory; 
use app\modules\inventory\models\StoreMatReceiptTemp;

class ReportsController extends Controller
{
	public function beforeAction($action)
	{
    if (!\Yii::$app->user->isGuest)
    {
      if(isset($_GET['securekey']) AND !empty($_GET['securekey']))
      {
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        if(empty($menuid)){ return $this->redirect(Yii::$app->homeUrl); }

        $chkValid = Yii::$app->utility->validate_url($menuid);
				// die($chkValid);
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
		$this->view->title = 'Stock Reports: Inventory Management';
   	$this->layout = '@app/views/layouts/admin_layout.php';
		$connection=   Yii::$app->db;
    $connection->open();

    $sql =" SELECT * FROM `store_item_master` as a 
    				LEFT JOIN store_item_type_master AS d ON d.Type_id=a.Item_type
    				LEFT JOIN store_unit_master AS e ON e.Unit_id=a.Measuring_Unit
    				LEFT JOIN store_item_cat_master AS f ON a.ITEM_CAT_CODE=f.ITEM_CAT_CODE
            where Quantity!=0";

		if(isset($_POST['group_name'])){
			$sql.=" and a.CLASSIFICATION_CODE=".$_POST['group_name'];
		}
		if(isset($_POST['cat_name'])){
			$sql.=" and a.ITEM_CAT_CODE=".$_POST['cat_name'];
		}
		
		$command = $connection->createCommand($sql); 
		$result=$command->queryAll();
		$connection->close();
		$groups=Yii::$app->inventory->get_groups();
		$category=Yii::$app->inventory->get_category();
		//echo "<pre>";print_r($category);die;
		return $this->render('index', ['data'=>$result,'groups'=>$groups,'category'=>$category]);
  }
	
	public function actionStock()
  {
		$this->view->title = 'Stock Reports: Inventory Management';
   	$this->layout = '@app/views/layouts/admin_layout.php';
		$connection=   Yii::$app->db;
    $connection->open();
		
    $sql =" SELECT concat(b.fname,b.lname) as emp_name,c.item_name,d.ITEM_CAT_NAME,e.Supplier_name,e.Supplier_address,a.*
			FROM `store_mat_receipt` a 
			LEFT JOIN employee AS b ON b.employee_code=a.Emp_code
			LEFT JOIN store_item_master AS c ON c.ITEM_CODE=a.ITEM_CODE
			LEFT JOIN store_item_cat_master AS d ON d.ITEM_CAT_CODE=a.ITEM_CAT_CODE
			LEFT JOIN store_supplier_master AS e ON e.Supplier_Code=a.Supplier_Code";

		if(isset($_POST['group_name'])){
			$sql.=" and a.CLASSIFICATION_CODE=".$_POST['group_name'];
		}
		if(isset($_POST['cat_name'])){
			$sql.=" and a.ITEM_CAT_CODE=".$_POST['cat_name'];
		}
		 
 		$command = $connection->createCommand($sql); 
    $result=$command->queryAll();
	  $connection->close();
		$groups=Yii::$app->inventory->get_groups();
		$category=Yii::$app->inventory->get_category();
		//echo "<pre>";print_r($category);die;
	  return $this->render('stock', ['data'=>$result,'groups'=>$groups,'category'=>$category]);
  }
	
	public function actionLedger()
  {
		$this->view->title = 'Ledger Reports: Inventory Management';
   	$this->layout = '@app/views/layouts/admin_layout.php';
		$result=$items=[];

		// echo "<pre>"; print_r($_POST);

		if(isset($_POST['item_code']))
		{
			$connection=   Yii::$app->db;
      $connection->open();
		
      $sql =" SELECT a.Access_id,concat(b.fname,b.lname) as emp_name,c.item_name,d.ITEM_CAT_NAME,c.Measuring_Unit, e.Supplier_name, 
				e.Supplier_address,e.Phone_no,a.Receipt_Qty,a.Issued_Qty,a.Balance_Qty,a.Transaction_Date,a.Description
				
				FROM `store_stock_ledger` a
				LEFT JOIN employee AS b ON b.employee_code=a.Emp_code
				LEFT JOIN store_item_master AS c ON c.ITEM_CODE=a.Item_Code
				LEFT JOIN store_item_cat_master AS d ON d.ITEM_CAT_CODE=c.ITEM_CAT_CODE
				LEFT JOIN store_supplier_master AS e ON e.Supplier_Code=a.Supplier_Code
				WHERE deleted='N' and a.`Item_Code` = ".$_POST['item_code']." order by a.Access_Id DESC";
	 		 
 			$command = $connection->createCommand($sql); 
      $result=$command->queryAll();
      /*echo "<pre>"; print_r($result);
      die();*/

			$cat_id=$_POST['cat_name'];
			$class_code=$_POST['class_code'];
 			$items=Yii::$app->inventory->get_cat_item($cat_id,$class_code);
		}
		
 		$category=Yii::$app->inventory->get_category();
		//echo "<pre>";print_r($category);die;
	  return $this->render('ledger', ['data'=>$result,'category'=>$category,'items'=>$items]);
  }
	
	public function actionIrequest()
  {
		//echo "<pre>";print_r(Yii::$app->user->identity);die;
		$menuid = Yii::$app->utility->decryptString($_GET['securekey']);
    $menuid = Yii::$app->utility->encryptString($menuid);
		if(isset($_POST) && !empty($_POST))
		{
			$post=$_POST['Inventory'];
			unset($_POST['Inventory']);
			$data['Voucher_No']			=Yii::$app->user->identity->e_id.substr(time(), -5);
			$data['Emp_code']			=Yii::$app->user->identity->e_id;
			$data['Division']			=$post['dept_id'];
			$data['Classification_Code']=$post['group'];
			$data['Item_Cat_Code']		=$post['category'];
			$data['Item_Code']			=$post['item'];
			$data['Item_Type']			=$post['item_type'];
			$data['Measuring_Unit']		=$post['units'];
			$data['Quantity_Required']	=$post['qty_required'];
			$data['Item_Purpose']		=$post['purpose'];
			$data['Remarks']			=$post['remarks'];
			$data['Flag']				=1;
			$data['Role']				=Yii::$app->user->identity->role;
			$data['FLA']				=Yii::$app->user->identity->authority1;
			//echo "<pre>";print_r($data);die;
			$res=Yii::$app->inventory->add_issue_request($data);
			Yii::$app->getSession()->setFlash('success', 'Request submit successfully!');
			
		}
		$this->view->title = 'Inventory Management: Issue Request';
     	$this->layout = '@app/views/layouts/admin_layout.php';
		$eid = Yii::$app->user->identity->e_id;
		$groups=Yii::$app->inventory->get_groups();
		$category=Yii::$app->inventory->get_category();
		$cost_centre=array();//Yii::$app->inventory->get_cost_centre();
		$unit_master=Yii::$app->inventory->get_unit_master();
		//echo "<pre>";print_r($groups);die;
 		$model = new Inventory();
    return $this->render('Irequest', ['model'=>$model,'groups'=>$groups,'category'=>$category,'cost_centre'=>$cost_centre,'unit_master'=>$unit_master,'menuid'=>$menuid]);
  }	 
}