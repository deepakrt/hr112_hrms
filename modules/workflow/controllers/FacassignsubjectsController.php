<?php

namespace app\modules\workflow\controllers;
use yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
class FacassignsubjectsController extends Controller
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
    public function actionInsert()
    {
        if((isset($_POST['secureKey']) && !empty($_POST['secureKey'])) && (isset($_POST['secureHash']) && !empty($_POST['secureHash'])))
        {
        $menu_id = $secureKey = base64_decode($_POST['secureKey']);
        $secureHash = Yii::$app->Utility->getHashInsert($secureKey);
        if($secureHash!=$_POST['secureHash'])
        {
        return $this->redirect(Yii::$app->homeUrl);   
        }
        
        if(isset($_POST['beSubject']) && !empty($_POST['beSubject']) && isset($_POST['groups']) && !empty($_POST['groups']))
        {
        $beSubject = $_POST['beSubject'];
        if((isset($beSubject['session_yr']) && !empty($beSubject['session_yr'])) && (isset($beSubject['department']) && !empty($beSubject['department'])) && (isset($beSubject['course']) && !empty($beSubject['course'])) && (isset($beSubject['semesterId']) && !empty($beSubject['semesterId'])) && (isset($beSubject['subjectId']) && !empty($beSubject['subjectId'])))
        {
        extract($beSubject);
        $groups = $_POST['groups'];
        $grouplist='';              
        foreach($groups as $groupsK=>$groupsV)
        {
            $grouplist.= $groupsV.",";
        }
        //echo "<pre>";print_r($beSubject); die;
          $grouplist = rtrim($grouplist,',');
          $res_result = explode("###", $subjectId);
          $subjectId = $res_result['0'];            
        $username =  Yii::$app->user->identity->id;
        
        //Log Array
        $logarray = array();
        $logarray['semesterId'] = $semesterId;
        $logarray['session_yr'] = $session_yr;
        $logarray['department'] = $department;
        $logarray['course'] = $course;
        $logarray['username'] = $username;
        $logarray['grouplist'] = $grouplist;
        $logarray['subjectId'] = $subjectId;
        
        $USP_InsertAssignSubjectToGroups  = Yii::$app->Utility->USP_InsertAssignSubjectToGroups($semesterId,$session_yr,$department,$course, $username,$grouplist,$subjectId);         
        $secureKey = base64_encode($menu_id);
         $secureHash = Yii::$app->Utility->getHashView($menu_id);
         if($USP_InsertAssignSubjectToGroups=='1')
         {
             //Save Logs
            $json_data = json_encode($logarray);
            Yii::$app->Utility2->logEventDetail('Work-Flow', 'workflow/facassignsubjects/insert', 'Succesfully Assigned Subject To Group.', $json_data);
            
            Yii::$app->session->setFlash($key = 'success', $message = '<strong>Succesfully Assigned Subject To Group. </strong>');   
         }
         else if($USP_InsertAssignSubjectToGroups=='2')
         {
         Yii::$app->session->setFlash($key = 'warning', $message = '<strong>Already Assigned Subject To Group. </strong>');   
         }
         else
         {
         Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Unable To Assign Subject To Group, Contact Admin.</strong>');   
         }
         return $this->redirect(Yii::$app->homeUrl."workflow/facassignsubjects/index?secureKey=$secureKey&secureHash=$secureHash");
           
        }
        else
        {
        //Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Invaid Parameters</strong>');
        }
        }
        else
        {
        //Yii::$app->session->setFlash($key = 'danger', $message = '<strong>Invaid Parameters</strong>');
        }
        }
        return $this->redirect(Yii::$app->homeUrl);
         
    }
    public function actionGetsubjectlist()
    {
//        echo "<pre>"; print_r($_POST); die;
     if((isset($_POST['_csrf']) && !empty($_POST['_csrf'])) && (isset($_POST['semesterId']) && !empty($_POST['semesterId'])) && (isset($_POST['dept_id']) && !empty($_POST['dept_id'])) && (isset($_POST['course_id']) && !empty($_POST['course_id'])) && (isset($_POST['subject_val']) && !empty($_POST['subject_val'])))   
     {
        // echo "<pre>inside"; print_r($_POST); die;
        extract($_POST);
        if($subject_val=="ALL") $subject_val=0;
       // $semesterId = base64_decode($semesterId);
        $html="<option value=''>There is no Subject in DB, Contact Admin. </option>";
       $USP_ExtractSubject = Yii::$app->Utility->USP_ExtractSubject($dept_id,$course_id,$semesterId,$subject_val,$SubjectType,$batch);
       //echo "<pre>"; print_r($USP_ExtractSubject); die;
       if(!empty($USP_ExtractSubject))
       {
        $html="<option value=''>Select Subject</option>";
        $i =1;
        foreach($USP_ExtractSubject as $USP_ExtractSubjectK=>$USP_ExtractSubjectV)   
        {
         $Subject_Id ='';
         $Subject_Id = $USP_ExtractSubjectV['Subject_Id'];
         $Subject_Code = $USP_ExtractSubjectV['Subject_Code'];
         $Subject_Id = $Subject_Id."###".$i;
         $Subject_Name = $USP_ExtractSubjectV['Subject_Name'];
         $Subject_Name =$Subject_Name ."(".$Subject_Code.")";
         $html.="<option value='$Subject_Id'>$Subject_Name</option>";
         $i++;
        }
       }
       $return['STATUS_ID']="000";   
       $return['STATUS_MSG']="SUCCESS";
       $return['STATUS_RESPONSE']=$html;
     }
     else
     {
      $return['STATUS_ID']="111";   
      $return['STATUS_MSG']="FAILURE";
      $return['STATUS_RESPONSE']="Invalid Request";
     }
     echo json_encode($return); die;
    }
    public function actionGetsubjectandgroups()
    {
//        echo "<pre>"; print_r($_POST); die;
     if((isset($_POST['_csrf']) && !empty($_POST['_csrf'])) && (isset($_POST['batch']) && !empty($_POST['batch'])) && (isset($_POST['semesterId']) && !empty($_POST['semesterId'])) && (isset($_POST['dept_id']) && !empty($_POST['dept_id'])) && (isset($_POST['course_id']) && !empty($_POST['course_id'])) && (isset($_POST['subject_val']) && !empty($_POST['subject_val'])))   
     {
        
        extract($_POST);
        $res_result = explode("###", $subject_val);
           $subject_val = $res_result['0']; 
           $Subject_Number = $res_result['1']; 
        //$semesterId = base64_decode($semesterId);
        $html="<option value=''>There is no Subject in DB, Contact Admin. </option>";
       $USP_ExtractSubject = Yii::$app->Utility->USP_ExtractSubject($dept_id,$course_id,$semesterId,$subject_val,$Subject_Type,$batch);
       
//       echo "<pre>";
//       print_r($USP_ExtractSubject);
//        die;
       if(!empty($USP_ExtractSubject))
       {
           
               
           
        $USP_ExtractGroupforAssignSubject = Yii::$app->Utility->USP_ExtractGroupforAssignSubject($batch, $dept_id,$course_id,$semesterId,$subject_val,$Subject_Type,$Subject_Number);
//        echo "<pre>"; print_r($USP_ExtractGroupforAssignSubject); die;
        if(!empty($USP_ExtractGroupforAssignSubject))
        {
          $SUBJECT_HTML='<table id="studentView" class="display adminlist" cellspacing="0" width="100%"><thead><tr></tr><th>#</th><th>Subject Name</th><th>Subject Code</th><th>Subject Type</th><th>Semesetr</th></tr></thead>';  
          $SUBJECT_HTML.="<tbody><tr>";  
          $SUBJECT_HTML.="<td>1</td>";
          $SUBJECT_HTML.="<td>".Yii::$app->Utility->getupperstring($USP_ExtractSubject['Subject_Name'])."</td>";
          $SUBJECT_HTML.="<td>".  strtoupper($USP_ExtractSubject['Subject_Code'])."</td>";
          $SUBJECT_HTML.="<td>".Yii::$app->Utility->getupperstring($USP_ExtractSubject['Subject_Type'])."</td>";
          $SUBJECT_HTML.="<td>".$USP_ExtractSubject['Semester_Id']."</td>";
          
          $SUBJECT_HTML.="</tr></tbody></table>"; 
          
          $GROUPS_HTML='<div class="col-lg-2">Groups<span class="required">*</span></div>';
          $GROUPS_HTML.=' <div class="col-lg-10">';
          $cnt=1;
          foreach($USP_ExtractGroupforAssignSubject as $k=>$v)
          {
           $checkBOX_label= $v['GrpName'];
           $checkBOX_val= $v['Groups'];
           $GROUPS_HTML.="<input type='checkbox' class='assign_group_checkbox' name='groups[]' value='$checkBOX_val'>";
           $GROUPS_HTML.="<label class='checkboxLabel'>$checkBOX_label</label>";
           $GROUPS_HTML.="&nbsp;&nbsp;";
           if($cnt%3==0)
           {$GROUPS_HTML.="<br>"; }
           $cnt++;
          }
          $GROUPS_HTML.='</div>';
          
          
         $return['STATUS_ID']="000";   
         $return['STATUS_MSG']="SUCCESS";
         $return['STATUS_RESPONSE']='';   
         $return['STATUS_RESPONSE_GROUPS']= $GROUPS_HTML;
         $return['STATUS_RESPONSE_SUBJECT'] = $SUBJECT_HTML;
        }
        else
        {
        $return['STATUS_ID']="111";   
        $return['STATUS_MSG']="FAILURE";
        $return['STATUS_RESPONSE']="Groups Does Not Exist Or All Groups Already Assigned To The Selected Subject."; 
        }
       }
       else
       {
        $return['STATUS_ID']="111";   
      $return['STATUS_MSG']="FAILURE";
      $return['STATUS_RESPONSE']="No Info Found For The Subject Selected";   
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
    
    public function actionViewstudentlist()
    {
        
    $return = array();
    $return['STATUS_ID']="111";   
    $return['STATUS_MSG']="FAILURE";
    $return['STATUS_RESPONSE']="Invalid Request/Fraudulent data detected";
    if((isset($_POST['formdata']) && !empty($_POST['formdata'])))   
    {
        $response = array();
        $data = parse_str($_POST['formdata'], $info); 
        $_POST = $info;
        if(isset($_POST['beSubject']) && !empty($_POST['beSubject']) && isset($_POST['groups']) && !empty($_POST['groups']))
        {
        $beSubject = $_POST['beSubject'];
        if((isset($beSubject['session_yr']) && !empty($beSubject['session_yr'])) && (isset($beSubject['department']) && !empty($beSubject['department'])) && (isset($beSubject['course']) && !empty($beSubject['course'])) && (isset($beSubject['semesterId']) && !empty($beSubject['semesterId'])) && (isset($beSubject['subjectId']) && !empty($beSubject['subjectId'])))
        {
        extract($beSubject);
        $res_result = explode("###", $subjectId);
        $subjectId = $res_result['0'];
        $Subject_Number = $res_result['1']; 
        $grp_lists =  implode(",",$_POST['groups']);
        //echo "<pre>$grp_lists"; print_r($_POST); die;
        
        $USP_ExtractstudentforAssignSubjectGroup = Yii::$app->Utility->USP_ExtractstudentforAssignSubjectGroup($session_yr, $department,$course,$semesterId,$subjectId,$Subject_Type,$grp_lists);
        //echo "<pre>$grp_lists"; print_r($USP_ExtractstudentforAssignSubjectGroup); die;
        if(!empty($USP_ExtractstudentforAssignSubjectGroup))
        {
        $html =  $this->renderPartial('studentlists',array('studentsinfo'=>$USP_ExtractstudentforAssignSubjectGroup));
        $return['STATUS_ID']="000";   
        $return['STATUS_MSG']="SUCCESS";
        $return['STATUS_RESPONSE']=$html;
        }
        else
        {
        $return['STATUS_ID']="111";   
        $return['STATUS_MSG']="SUCCESS";
        $return['STATUS_RESPONSE']="<strong>No Records Found.</strong>";
        }
        }}}
    
    echo json_encode($return); die; 
    
    }
    
    
    
    }
