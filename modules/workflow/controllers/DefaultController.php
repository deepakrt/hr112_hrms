<?php

namespace app\modules\workflow\controllers;
use yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
class DefaultController extends Controller
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
    public function actionAssignactivities($menu_id)
    {
        return $this->render('assignactivities',array('menuid'=>$menu_id));
    }
    
    public function actionViewcreditschemeums($menu_id)
    {
        return $this->render('viewcreditschemeums',array('menuid'=>$menu_id));
    }
    public function actionUpdatemultiplestudents($menu_id)
    {
        return $this->render('updatemultiplestudents',array('menuid'=>$menu_id));
    }
    
    public function actionUmsstudentmaster($menu_id)
    {
        return $this->render('umsstudentmaster',array('menuid'=>$menu_id));
    }
    
    public function actionAssignbranchtocollege($menu_id)
    {
        return $this->render('assignbranchtocollege',array('menuid'=>$menu_id));
    }
    public function actionDeleteregisteredstudent($menu_id)
    {
        return $this->render('deleteregisteredstudent',array('menuid'=>$menu_id));
    }
     public function actionResetpaswd($menu_id)
    {
        return $this->render('resetpaswd',array('menuid'=>$menu_id));
    }
    
     public function actionUmscollegemaster($menu_id)
    {        
        
        
    }
    
     public function actionUmsmasters($menu_id)
    {
        return $this->render('umsmasters',array('menuid'=>$menu_id));
    }
    
     public function actionUmsdepartmentmaster($menu_id)
    {
        return $this->render('umsdepartmentmaster',array('menuid'=>$menu_id));
    } 
     public function actionUmssubjectmaster($menu_id)
    {
        return $this->render('umssubjectmaster',array('menuid'=>$menu_id));
    } 
        public function actionUmsfacultymaster($menu_id)
    {
        return $this->render('umsfacultymaster',array('menuid'=>$menu_id));
    } 
       public function actionGrademasteraction($menu_id)
    {
        return $this->render('grademasteraction',array('menuid'=>$menu_id));
    } 
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}
