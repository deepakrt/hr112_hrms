<?php
namespace app\modules\admin\controllers;
use yii;
class MenusController extends \yii\web\Controller
{
    public function beforeAction($action){
        if(isset($_GET['securekey']) AND !empty($_GET['securekey'])){
            $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
            if(empty($menuid)){ return $this->redirect(Yii::$app->homeUrl); }
            
            $chkValid = Yii::$app->utility->validate_url($menuid);
            if(empty($chkValid)){ return $this->redirect(Yii::$app->homeUrl); }
            return true;
        }else{ return $this->redirect(Yii::$app->homeUrl); }
        parent::beforeAction($action);
    }
    
    public function actionIndex(){
        $this->layout = '@app/views/layouts/admin_layout.php';
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        return $this->render('index', ['menuid'=>$menuid]);
    }
    
    public function actionViewmenus(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."admin/menus?securekey=$menuid";
        if(isset($_GET['key']) AND !empty($_GET['key'])){
            $role_id = Yii::$app->utility->decryptString($_GET['key']);
            
            if(empty($role_id)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid params value'); 
                return $this->redirect($url);
            }
            $allmenus = Yii::$app->utility->get_menu_mapping(NULL, $role_id);
            $this->layout = '@app/views/layouts/admin_layout.php';
            return $this->render('viewmenus', ['menuid'=>$menuid, 'allmenus'=>$allmenus, 'role_id'=>$role_id]);
        }
        Yii::$app->getSession()->setFlash('danger', 'Invalid params found'); 
        return $this->redirect($url);
        
    }
    public function actionNewmapping(){
        $this->layout = '@app/views/layouts/admin_layout.php';
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."admin/menus/newmapping?securekey=$menuid";
        if(isset($_POST['Assign']) AND !empty($_POST['Assign'])){
           $emp_code = NULL;
            $post = $_POST['Assign'];
            $role_id = Yii::$app->utility->decryptString($post['role_id']);
            
            if(empty($role_id)){
                Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid params data.'); 
                return $this->redirect($url);
            }
            
            if(!empty($post['menu'])){
                $menus = $post['menu'];
                $menuslist = [];
                $i=0;
                foreach($menus as $m){
                    $update_id = NULL;
                    if(!empty($m['id'])){
                        $update_id = Yii::$app->utility->decryptString($m['id']);
                        if(empty($update_id)){
                            Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid params data.'); 
                            return $this->redirect($url);
                        }
                    }
                    $update_menuid = Yii::$app->utility->decryptString($m['menuid']);
                    $update_status = Yii::$app->utility->decryptString($m['status']);
                    if(empty($update_menuid) OR empty($update_status)){
                        Yii::$app->getSession()->setFlash('danger', 'Fraudulent Data Detected. Invalid params data.'); 
                        return $this->redirect($url);
                    }
                    $menuslist[$i]['id'] = $update_id;
                    $menuslist[$i]['menuid'] = $update_menuid;
                    $menuslist[$i]['status'] = $update_status;
                    $i++;
                }
                $oldArray=  json_encode($menuslist);
                /*
                 * Final Array 
                 */
                //$finalArray="";
				$finalArray=[];
                $i=0;
                foreach($menuslist as $m){
                    if(!empty($m['id'])){
                        $finalArray[$i]['id'] = $m['id'];
                        $finalArray[$i]['menuid'] = $m['menuid'];
                        $finalArray[$i]['role_id'] = $role_id;
                        $finalArray[$i]['status'] = $m['status'];
                        $i++;
                    }else{
                        if($m['status'] == 'Y'){
                            $finalArray[$i]['id'] = NULL;
                            $finalArray[$i]['menuid'] = $m['menuid'];
                            $finalArray[$i]['role_id'] = $role_id;
                            $finalArray[$i]['status'] = $m['status'];
                            $i++;
                        }
                    }
                }
                $newarray = json_encode($finalArray);
                $logs['old_menu'] = $oldArray;
                $logs['new_menu'] = $newarray;
                $data_json = json_encode($logs);
                foreach($finalArray as $f){
                    if(!empty($f['id'])){
                        $result = Yii::$app->utility->add_update_menu_mapping($f['id'], $f['menuid'], $f['role_id'], $emp_code, $f['status']);
                    }else{
                        $result = Yii::$app->utility->add_update_menu_mapping(NULL, $f['menuid'], $f['role_id'], $emp_code, $f['status']);
                    }
                    if($result == '1'){
                        Yii::$app->utility->activities_logs("Master Data", NULL, NULL, $data_json, 'Invalid Employee Code.');
                        Yii::$app->getSession()->setFlash('danger', 'Invalid Employee Code.');
                        return $this->redirect($url);
                    }
                }
                $msg = "Menus Updated Successfully.";
                Yii::$app->getSession()->setFlash('success', $msg);
                Yii::$app->utility->activities_logs("Master Data", NULL, NULL, $data_json, $msg);
                return $this->redirect($url);
                
            }
            
            
            
            
        }
        return $this->render('newmapping', ['menuid'=>$menuid]);
    }
    
//    public function actionCheckmenutype(){
//        
//        if(isset($_GET['key']) AND !empty($_GET['key']) AND isset($_GET['key2']) AND !empty($_GET['key2'])){
//            $menutype = Yii::$app->utility->decryptString($_GET['key']);
//            $role_id = Yii::$app->utility->decryptString($_GET['key2']);
//            if($menutype == 'L'){
//                $topmenus = Yii::$app->utility->getMenus("T", NULL, $role_id, NULL);
//                $result['Status']='SS';
//                $result['MenuType']='L';
////                echo "<pre>";print_r($topmenus); die;
//                if(empty($topmenus)){
//                    $result['Res']='No Record Found';
//                }else{
//                    $html="<option value=''>Select Main Menu</option>";
//                    foreach($topmenus as $t){
//                        $menuid = Yii::$app->utility->encryptString($t['menuid']);
//                        $menu_name = $t['menu_name'];
//                        $html .="<option value='$menuid'>$menu_name</option>";
//                    }
//                    $result['Res']=$html;
//                }
//                echo json_encode($result); die;
//            }elseif($menutype == 'T'){
//                $topmenus = Yii::$app->utility->getMenus($menutype, NULL, $role_id, NULL);
//                $result['Status']='SS';
//                $result['MenuType']='T';
//                
//                if(empty($topmenus)){
//                    $result['Res']='No Record Found';
//                }else{
//                    $html="";
//                    $i=0;
//                    $j=1;
//                    foreach($topmenus as $t){
//                        $menuid = Yii::$app->utility->encryptString($t['menuid']);
//                        $menu_name = $t['menu_name'];
//                        $checkexits = Yii::$app->utility->get_menu_mapping($t['menuid'], $role_id);
//                        if(!empty($checkexits)){
//                            $checked="";
//                            $yesno = "N";
//                            if($checkexits['is_active'] == 'Y'){
//                                $checked="checked";
//                                $yesno = "Y";
//                            }
//                            $html .="<li><input type='checkbox' class='menuselect' data-key='$j' value='$menuid'  $checked title='$menu_name' />&nbsp&nbsp; $menu_name <input type='hidden' name='Assign[menu][$i][status]' id='yesno$j' value='$yesno' readonly /><input type='hidden' name='Assign[menu][$i][menuid]' value='$menuid' readonly /></li>";
//                        }else{
//                            $html .="<li><input type='checkbox' class='menuselect' data-key='$j' value='$menuid'  title='$menu_name' />&nbsp&nbsp; $menu_name <input type='hidden' name='Assign[menu][$i][status]' id='yesno$j' value='N' readonly /> <input type='hidden' name='Assign[menu][$i][menuid]' value='$menuid' readonly /></li>";
//                        }
//                        $i++;
//                        $j++;
//                    }
//                    $result['Res']=$html;
//                }
//                echo json_encode($result); die;
//            }else{
//                $result['Status']='FF';
//                $result['Res']='Invalid Menu Type';
//                echo json_encode($result); die;
//            }
//        }else{
//            $result['Status']='FF';
//            $result['Res']='Invalid param found.';
//            echo json_encode($result); die;
//        }
//        
//    }
    public function actionAddmenu(){
        $this->layout = '@app/views/layouts/admin_layout.php';
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."admin/menus/addmenu?securekey=$menuid";
        if(isset($_POST['Menu']) AND !empty($_POST['Menu'])){
            $post = $_POST['Menu'];
            $menu_name = trim(preg_replace('/[^A-Za-z ]/', '', $post['menu_name']));
            $menu_dsc = trim(preg_replace('/[^A-Za-z0-9\ \,(\).#\/\&-(\)-]/', '', $post['menu_dsc']));
            $menu_url = trim(preg_replace('/[^A-Za-z\/]/', '', $post['menu_url']));
            $menu_type = trim(preg_replace('/[^A-Z]/', '', $post['menu_type']));
            $order_number = trim(preg_replace('/[^0-9]/', '', $post['order_number']));
            
            $parent_menu = NULL;
            if($menu_type == 'T'){
            }elseif($menu_type == 'L'){
                $parent_menu = trim(preg_replace('/[^0-9]/', '', $post['parent_menu']));
            }else{
                Yii::$app->getSession()->setFlash('danger', 'Invalid Menu Type.'); 
                return $this->redirect($url);
            }
            $result = Yii::$app->utility->add_update_master_menu(NULL, $menu_name, $menu_dsc, $menu_url, $menu_type, $parent_menu, $order_number, 'Y');
            /*
             * Logs
             */
            $logs['menu_name']=$menu_name;
            $logs['menu_dsc']=$menu_dsc;
            $logs['menu_url']=$menu_url;
            $logs['menu_type']=$menu_type;
            $logs['parent_menu']=$parent_menu;
            $logs['order_number']=$order_number;
            $logs['is_active']="Y";
            $jsonlogs = json_encode($logs);
            
            if($result == '1'){
                Yii::$app->utility->activities_logs("Master Data", NULL, NULL, $jsonlogs, "Menu Added Successfully.");
                Yii::$app->getSession()->setFlash('success', 'Menu Added Successfully.'); 
                return $this->redirect($url);
            }else{
                Yii::$app->utility->activities_logs("Master Data", NULL, NULL, $jsonlogs, "Menu not added. Contact Admin.");
                Yii::$app->getSession()->setFlash('danger', 'Menu not added. Contact Admin.'); 
                return $this->redirect($url);
            }
        }
        return $this->render('addmenu', ['menuid'=>$menuid]);
    }
    
//    public function actionGetsubmenu(){
//        if(isset($_GET['key']) AND !empty($_GET['key'])){
//            $menuid = Yii::$app->utility->decryptString($_GET['key']);
//            if(empty($menuid)){
//                $result['Status']='FF';
//                $result['Res']='Invalid Menu ID';
//                echo json_encode($result); die;
//            }
//            $data = Yii::$app->utility->get_master_menu(NULL, $menuid);
//            $html='<option value="">Select Sub-Menu</option>';
//            if(!empty($data)){
//                foreach($data as $d){
//                    $id = Yii::$app->utility->encryptString($d['menuid']);
//                    $menu_name = $d['menu_name'];
//                    $html=$html."<option value='$id'>$menu_name</option>";
//                }
//                $result['Status']='SS';
//                $result['Res']=$html;
//            }else{
//                $result['Status']='FF';
//                $result['Res']='No Sub-Menu Found';
//            }
//            echo json_encode($result); die;
//        }else{
//            $result['Status']="SS";
//            $result['Res']="Invalid Params Found";
//            echo json_encode($result);
//            die;
//        }
//    }
    
    public function actionUpdatestatus(){
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $url = Yii::$app->homeUrl."admin/menus?securekey=$menuid";
        if(isset($_GET['status']) AND !empty($_GET['status']) AND isset($_GET['menuid']) AND !empty($_GET['menuid']) AND isset($_GET['map_id']) AND !empty($_GET['map_id'])){
            $status = Yii::$app->utility->decryptString($_GET['status']);
            $menuid = Yii::$app->utility->decryptString($_GET['menuid']);
            $map_id = Yii::$app->utility->decryptString($_GET['map_id']);
            
            if(empty($status) AND empty($menuid) AND empty($map_id)){
                Yii::$app->getSession()->setFlash('danger', 'Invalid params value'); 
                return $this->redirect($url);
            }
            if($status == 'Y'){
                $msg = "Mapping Active Successfully";
            }elseif($status == 'N'){
                $msg = "Mapping In-Active Successfully";
            }
            $result = Yii::$app->utility->add_update_menu_mapping($map_id, $menuid, NULL, NULL, $status);
            /*
             * Logs
             */
            $logs['map_id']=$map_id;
            $logs['menuid']=$menuid;
            $logs['status']=$status;
            $jsonlogs= json_encode($logs);
                    
            if($result == '4'){
                Yii::$app->utility->activities_logs("Master Data", NULL, NULL, $jsonlogs, $msg);
                Yii::$app->getSession()->setFlash('success', $msg); 
            }else{
                Yii::$app->utility->activities_logs("Master Data", NULL, NULL, $jsonlogs, "Mapping Not Updated. Contact Admin.");
                Yii::$app->getSession()->setFlash('danger', 'Mapping Not Updated. Contact Admin.'); 
            }
            return $this->redirect($url);
        }
        Yii::$app->getSession()->setFlash('danger', 'Invalid params found'); 
        return $this->redirect($url);
    }
    
    public function actionGetsubmenulist(){
        if(isset($_GET['key']) AND !empty($_GET['key']) AND isset($_GET['key2']) AND !empty($_GET['key2'])){
            $menuid = Yii::$app->utility->decryptString($_GET['key']);
            $role_id = Yii::$app->utility->decryptString($_GET['key2']);
            
            if(empty($menuid) OR empty($role_id)){
                $result['Status']='FF';
                $result['Res']='Invalid Params value Found';
                echo json_encode($result); die;
            }
//            die($menuid."----".$role_id);
            $data = Yii::$app->utility->get_master_menu(NULL, $menuid);
//            echo "<pre>";print_r($data); die;
            $result['Status']='SS';
            if(empty($data)){
                $result['Res']="No Record Found";
            }else{
                $html="";
                $i=0;
                $j=1;
                foreach($data as $t){
                    $n = rand (9000,10000);
                    $menuid = Yii::$app->utility->encryptString($t['menuid']);
                    $menu_name = $t['menu_name'];
                    $menu_dsc = $t['menu_dsc'];
                    $checkexits = Yii::$app->utility->get_menu_mapping($t['menuid'], $role_id);
//                    echo "<pre>";print_r($checkexits); die;
                    $showCls = "btn-light";
                    $hideCls = "btn-success";
                    $yesno = "N";
                    $checked="";
                    $no = $status = Yii::$app->utility->encryptString('N');
                    $yes = Yii::$app->utility->encryptString('Y');
                    $mapid = NULL;
                    if(!empty($checkexits)){
                        $mapid = Yii::$app->utility->encryptString($checkexits['id']);
                        if($checkexits['is_active'] == 'Y'){
                            $showCls = "btn-success";
                            $hideCls = "btn-light";
                            $status = Yii::$app->utility->encryptString('Y');
                            $checked="checked";
                            $yesno = "Y";
                        }else{
                            $showCls = "btn-light";
                            $hideCls = "btn-success";
                        }
                        $html .="<li title='$menu_dsc'>$menu_name 
                        <input type='hidden' name='Assign[menu][$i][id]' value='$mapid' />
                        <input type='hidden' name='Assign[menu][$i][menuid]' value='$menuid' />
                        <input type='hidden' name='Assign[menu][$i][status]' id='status_$n' value='$status' />
                        <button id='show_$n' type='button' data-status='$yes' data-id='$n' class='btn $showCls btn-sm btnxs changebtnmenustatus' value='Y'>Enable</button>
                        <button id='hide_$n' type='button' value='N' data-status='$no' data-id='$n' class='btn $hideCls btn-sm btnxs changebtnmenustatus'>Disable</button></li>";
                    }else{
                        $html .="<li title='$menu_dsc'>$menu_name 
                        <input type='hidden' name='Assign[menu][$i][id]' value='$mapid' />
                        <input type='hidden' name='Assign[menu][$i][menuid]' value='$menuid' />
                        <input type='hidden' name='Assign[menu][$i][status]' id='status_$n' value='$status' />
                        <button id='show_$n' type='button' data-status='$yes' data-id='$n' class='btn $showCls btn-sm btnxs changebtnmenustatus' value='Y'>Enable</button>
                        <button id='hide_$n' type='button' value='N' data-status='$no' data-id='$n' class='btn $hideCls btn-sm btnxs changebtnmenustatus'>Disable</button></li>";
                    }
                    $i++;
                    $j++;
                }
                $result['Res']=$html;
            }
            echo json_encode($result); die;
        }else{
            $result['Status']='FF';
            $result['Res']='Invalid params found';
            echo json_encode($result); die;
        }
    }
    
    public function actionMastermenus(){
        $this->layout = '@app/views/layouts/admin_layout.php';
        $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
        $menuid = Yii::$app->utility->encryptString($menuid);
        $menus = Yii::$app->utility->get_master_menu(NULL, NULL);
        return $this->render('mastermenus', ['menuid'=>$menuid, 'menus'=>$menus]);
    }
}
