<?php

namespace app\modules\inventory\controllers;
use yii;
use yii\web\Controller;
use app\models\Inventory; 
use app\modules\inventory\models\ComparisonReport;

class ComparisonController extends Controller
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
      $model = new ComparisonReport();
      $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
      $this->layout = '@app/views/layouts/admin_layout.php';
        $lists = Yii::$app->inventory->get_camp_item(); //Inventoryutility
       
      return $this->render('index',['model'=>$model,'menuid'=>$menuid,'lists'=>$lists]);
    }


   public function actionReport()
    {
$model = new ComparisonReport();


$menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);

if(isset($_POST['Save']))
      {

$eid = Yii::$app->user->identity->e_id;
$post = $_POST['ComparisonReport'];
$res=Yii::$app->inventory->insert_camp_report($_POST['ComparisonReport']);
if($res==0)
{
  Yii::$app->getSession()->setFlash('info', 'Already  exists!');
}
if(!empty($res))
{ 
 $model = new ComparisonReport();
      $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
      $this->layout = '@app/views/layouts/admin_layout.php';
        $lists = Yii::$app->inventory->get_camp_item(); //Inventoryutility
       
      return $this->render('index',['model'=>$model,'menuid'=>$menuid,'lists'=>$lists]);
}

}


    $groups=Yii::$app->inventory->get_groups();
  //  $depts=Yii::$app->inventory->get_alldept();
    $category=Yii::$app->inventory->get_category();
    //$cost_centre=Yii::$app->inventory->get_cost_centre();
   // $unit_master=Yii::$app->inventory->get_unit_master();
    $suppliers=Yii::$app->inventory->get_all_supplier();
   // $tmp_mat_receipt=Yii::$app->inventory->get_mat_receipt_tmp();
  //  $new_mrn=Yii::$app->inventory->get_new_mrn_no();

        $this->layout = '@app/views/layouts/admin_layout.php';
        return $this->render('report',['model'=>$model, 'menuid'=>$menuid,'groups'=>$groups,'category'=>$category,
                    'suppliers'=>$suppliers]);

       

    }

    


   public function actionGet_cat_code()
    {
    // echo "<pre>";print_r($_POST);die;
    if(!isset($_POST['cat_id']) || empty($_POST['cat_id'])){
      die('0');
    }
    $cat_id=$_POST['cat_id'];
    $ccode=2;
    if(isset($_POST['ccode']) && !empty($_POST['ccode'])){
      $ccode=$_POST['ccode'];
    }
    //die($ccode);
    $res=Yii::$app->inventory->get_cat_item($cat_id,$ccode);

    // echo "<pre>"; print_r($res); die();


        $html='<option value="">-- Select --</option>';
    foreach($res as $r){
      $html.='<option alt="'.$r['Item_type'].'" data_type_id="'.$r['Type_id'].'" data-quantity="'.$r['Quantity'].'" label1="'.$r['Measuring_Unit'].'" value="'.$r['ITEM_CODE'].'">'.$r['item_name'].'</option>';
    }
    if(!isset($_POST['page'])){
      echo $html;
    }else{
      echo $html;
    }
    die;
  }


   public function actionGet_item_code()
    {
      if(!isset($_POST['item_id']) || empty($_POST['item_id'])){
      die('0');
    }
    $item_id=$_POST['item_id'];

    $res_sup=Yii::$app->inventory->get_item_supplier($item_id);
    $html='<option value="">-- Select --</option>';
    foreach($res_sup as $r){
      $html.='<option alt="" data_type_id="" data-quantity="" label1="" value="'.$r['Supplier_Code'].'">'.$r['Supplier_name'].'</option>';
    }
    if(!isset($_POST['page'])){
      echo $html;
    }else{
      echo $html;
    }
    die;
    
  //  die;

    }
     public function actionSupplier()
    {
      $this->layout = false;
       //echo "<pre>";print_r(Yii::$app->user->identity);die;
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        
        $data = array();

        if(isset($_POST) && !empty($_POST))
        {
            // echo "<pre>";print_r($_POST);die;
          
            $itemcode = $_POST['itemcode'];

            $lists = Yii::$app->inventory->get_camp_supplier_details($itemcode); //Inventoryutility

        //  echo ""; print_r($lists); die();



            $html = $this->renderPartial('item_data', array('item_data'=>$lists));

            $allConcat['result'] = $html;
            //   echo ""; print_r($html); die();

        }

        echo json_encode($allConcat);
       die();
    }


    public function actionEdit()
    {
      $this->view->title = 'Inventory Management: Edit Report';
      $this->layout = '@app/views/layouts/admin_layout.php';
      $model = new ComparisonReport();
    $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
   if(isset($_POST['Update'])){
      $_POST['ComparisonReport']['id']=$_GET['id']; 
           $lists = Yii::$app->inventory->get_camp_item();
       $res=Yii::$app->inventory->update_camp_report($_POST['ComparisonReport']);
        if($res){
          Yii::$app->getSession()->setFlash('info', 'Report Updated successfully!!');
            $r_url='index?securekey='.$menuid;
          return $this->redirect($r_url);
        }
       
    }
    
    
    $eid = Yii::$app->user->identity->e_id;
    $groups=Yii::$app->inventory->get_groups();
    $report_detail['ComparisonReport']=Yii::$app->inventory->get_camp_detail($_GET['id']);

    $category=Yii::$app->inventory->get_category();

     $model->load($report_detail);
    // echo "<pre>";print_r($model);die;
        $this->layout = '@app/views/layouts/admin_layout.php';
    
        return $this->render('edit',['model'=>$model, 'menuid'=>$menuid,'groups'=>$groups,'category'=>$category,
                    ]);


    }

  


}
