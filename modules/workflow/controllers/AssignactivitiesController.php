<?php

namespace app\modules\workflow\controllers;
use yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
class AssignactivitiesController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
           
             'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => TRUE,
//                        'roles' => ['@'],
//                        'actions' => ['@'],
                        'denyCallback' => function ($rule, $action) {
                                try {
                                    return $this->redirect($url = \Yii::$app->homeUrl);
                                } catch (Exception $ex) {
                                    throw new Exception(500, $ex);
                                }
                            },
                        'matchCallback' => function($rule, $action){
                            try {
                                $chk = Yii::$app->Utility->chkUserAccess();
                                       return $chk;
//                                if(isset(Yii::$app->user->identity) AND !empty(Yii::$app->user->identity)){
//                                    return true;
//                                }else{
//                                    return false;
//                                }
                            }catch (Exception $ex) {
                                throw new Exception(500, $ex);
                            }
                        },
                    ],
                ],
            ]
        ];
    }
     public function actionIndex()
    {
    if((isset($_GET['secureKey']) && !empty($_GET['secureKey'])) && (isset($_GET['secureHash']) && !empty($_GET['secureHash'])))
     {
     $secureKey = base64_decode($_GET['secureKey']);
     $secureHash = Yii::$app->Utility->getHashView($secureKey); 
     if($secureHash!=$_GET['secureHash'])
     {
      return $this->redirect(Yii::$app->homeUrl);   
     }     
     return $this->render('index',array('menuid'=>$secureKey));
     }
     else
     {
        return $this->redirect(Yii::$app->homeUrl);
     }
    }
     public function actionGetassignactivitydetails()
    {
        $response = array();
        $data = parse_str($_POST['formdata'], $info);        
       //  print_r($info); die;
    if((isset($info['AssignActivity']) && !empty($info['AssignActivity'])))
     {
        $_POST =  $info['AssignActivity'];
    if((isset($_POST['TypeofUser']) && !empty($_POST['TypeofUser'])) )
     {  
          extract($_POST);
          $secureKey = $info['secureKey'];
          $secureHash = $info['secureHash'] ;
         $USP_SearchUserDetails = Yii::$app->Utility->USP_SearchUserDetails($TypeofUser, $LoginName, $FirstName, $LastName);
         if(!empty($USP_SearchUserDetails))
         {
         $html = $this->renderPartial('activityinfo', array('activityinfodetails'=>$USP_SearchUserDetails,'role_id'=>$TypeofUser, 'secureKey'=>$secureKey,'secureHash'=>$secureHash));
         $return['STATUS_ID']="000";   
      $return['STATUS_MSG']="SUCCESS";
      $return['STATUS_RESPONSE']=$html;
         }
         else
         {
            $return['STATUS_ID']="111";   
            $return['STATUS_MSG']="SUCCESS";
            $return['STATUS_RESPONSE']="No Record Found";    
          }
     }
       else
     {
      $return['STATUS_ID']="111";   
      $return['STATUS_MSG']="FAILURE";
      $return['STATUS_RESPONSE']="Invalid Request";
     }
     }
        else
     {
      $return['STATUS_ID']="111";   
      $return['STATUS_MSG']="FAILURE";
      $return['STATUS_RESPONSE']="Invalid Request";
     }
       
     echo json_encode($return); die; 
    
}
public function actionViewassignactivity()
    {
    //print_r($_POST);
    //print_r($_GET);die;
    if(isset($_POST['View']) && !empty($_POST['View']))
    {
        
        if((isset($_GET['secureKey']) && !empty($_GET['secureKey'])) && (isset($_GET['secureHash']) && !empty($_GET['secureHash'])))
        {
        $menu_id = $secureKey = base64_decode($_GET['secureKey']);
        $secureHash = Yii::$app->Utility->getHashInsert($secureKey);
        if($secureHash!=$_GET['secureHash'])
        {
        return $this->redirect(Yii::$app->homeUrl);   
        }
        $_POST = $_POST['View'];    
        if((isset($_POST['username']) && !empty($_POST['username'])) && (isset($_POST['Roleid']) && !empty($_POST['Roleid'])) )
        {
        extract($_POST);
        $secureKey = base64_encode($menu_id);
        $secureHash = Yii::$app->Utility->getHashView($menu_id);
        $USP_ExtractAssignedRoles = Yii::$app->Utility->USP_ExtractAssignedRoles($Roleid, $username);
        
        if(!empty($USP_ExtractAssignedRoles))
        {   
         return $this->render('updateactivities',array('ExtractAssignedRoles' => $USP_ExtractAssignedRoles,'menuid'=>$menu_id,'FullName'=>$Full_Name,'RollName'=>$RollName,'username'=>$username,'Roleid'=>$Roleid));   
        }
        else
        {
            Yii::$app->session->setFlash($key = 'warning', $message = '<strong>No Assigned Activity Found.</strong>');  
            return $this->redirect(Yii::$app->homeUrl."workflow/assignactivities/index?secureKey=$secureKey&secureHash=$secureHash");
        }
        
        }
        else
        {
        // Yii::$app->session->setFlash($key = 'warning', $message = '<strong>Invalid Request.</strong>');      
        }
        }
        else {
          // Yii::$app->session->setFlash($key = 'warning', $message = '<strong>Invalid Request.</strong>');       
        }
    }
    else
    {
    // Yii::$app->session->setFlash($key = 'warning', $message = '<strong>Invalid Request.</strong>');         
    }
    
    return $this->redirect(Yii::$app->homeUrl);
    
    }
    
   public function actionUpdateassignactivity()
    {
    //   echo "<pre>";
   // print_r($_POST);
    //print_r($_GET);die;
    if((isset($_POST['secureKey']) && !empty($_POST['secureKey'])) && (isset($_POST['secureHash']) && !empty($_POST['secureHash'])) )
    {
        $menu_id = $secureKey = base64_decode($_POST['secureKey']);
        $secureHash = Yii::$app->Utility->getHashUpdate($secureKey);
        if($secureHash!=$_POST['secureHash'])
        {
        return $this->redirect(Yii::$app->homeUrl);   
        } 
        $secureKey = base64_encode($menu_id);
        $secureHash = Yii::$app->Utility->getHashView($menu_id);
        if((isset($_POST['Assign']) && !empty($_POST['Assign'])))
        {
            $post = $_POST['Assign'];
            if((isset($post['username']) && !empty($post['username'])) && (isset($post['All']) && !empty($post['All'])) )
            {
              $username = $post['username'];
              $Roleid = $post['Roleid'];
              $checkedMenu = array();
              $allMenu = $post['All'];
             if((isset($post['Menu']) && !empty($post['Menu'])))
             {
               $checkedMenu = $post['Menu'];   
             }
             $USP_InsertSubmenutoRole_count = 0;
             foreach($allMenu as $allMenuK=>$allMenuV)
             {
               $assignedMenu = 0;
               if(!empty($checkedMenu))
               {
                if(in_array($allMenuV, $checkedMenu))
                $assignedMenu = 1;
               }
               $USP_InsertSubmenutoRole = Yii::$app->Utility->USP_InsertSubmenutoRole($username,$allMenuV, $assignedMenu,$Roleid);
               $USP_InsertSubmenutoRole_count = $USP_InsertSubmenutoRole + $USP_InsertSubmenutoRole_count;
               
             }
             
             $log_JSON = json_encode($post);
             Yii::$app->Utility2->logEventDetail('Workflow','workflow/assignactivities/inserted OR updated','Successfully Assign Activity',$log_JSON);
          
             if(count($allMenu) == $USP_InsertSubmenutoRole_count)
             {
              Yii::$app->session->setFlash($key = 'success', $message = '<strong>Assign Activity Updated Successfully</strong>');  
            return $this->redirect(Yii::$app->homeUrl."workflow/assignactivities/index?secureKey=$secureKey&secureHash=$secureHash");   
             }
             else
             {
              Yii::$app->session->setFlash($key = 'warning', $message = '<strong>Some Of THe Records Not Updated, Contact Admin</strong>');  
            return $this->redirect(Yii::$app->homeUrl."workflow/assignactivities/index?secureKey=$secureKey&secureHash=$secureHash");      
             }
                     
            }
            else
            {
                
            }
            
        }
        else
        {
            
        }
        
        
    }
    return $this->redirect(Yii::$app->homeUrl);   
       
   }
}