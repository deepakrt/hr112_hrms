<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\UserIdentity;
use frontend\models\Manpowermapping;
use frontend\models\ClientDetail;
use frontend\models\ProposalSearch;
use frontend\models\ClientDetailSearch;
use frontend\models\Manpower;
use frontend\models\Task;
use frontend\models\ManpowermappingSearch;
use frontend\models\PostmeetingsSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use common\models\User;
use common\rbac\models\Role;
use yii\rbac\CheckAccessInterface;


/* @var $this yii\web\View */
$this->title = Yii::t('app', Yii::$app->name);
?>
<div class="site-index">
    <div class="body-content">
    <?php     
        
        $_SESSION['userrole'] = Yii::$app->user->identity->role_name;        
        
        if ($_SESSION['userrole'] == 'admin') {
            echo \Yii::$app->view->render('@app/views/site/adminview');
        }
            //Editor Users
            else if ($_SESSION['userrole'] == 'FLA') {
                echo \Yii::$app->view->renderFile(Yii::getAlias('@app') . '/modules/manageproject/views/site/editorview.php', ['menuid'=>$menuid]);
                //echo \Yii::$app->view->render(Yii::getAlias('@app') .'/modules/manageproject/views/site/editorview');
            }
            //Director
            else if ($_SESSION['userrole'] == 'director') {
                echo \Yii::$app->view->render('@app/views/site/edview');
            } 
            //Premium Users
            else if ($_SESSION['userrole'] == 'premium') {
                echo '<div class="col-lg-12">'. \Yii::$app->view->render('@app/views/site/editorview') . '</div>'
                /*<div class="col-lg-12">*/
                    . $this->render('/task/ganttchart');
                /*</div>   */
            } 
            //Education and Training Users
            else if ($_SESSION['userrole'] == 'edu') {
                echo \Yii::$app->view->render('@app/views/site/eduview');
            //BDCC Users 
            }else if ($_SESSION['userrole'] == 'bdcc'){ 
                echo \Yii::$app->view->render('@app/views/site/bdccview');
            } 
            //hr Users 
            else if ($_SESSION['userrole'] == 'hr'){ 
                echo \Yii::$app->view->render('@app/views/site/hrview');
            } else if (($_SESSION['userrole'] == 'member') && (!Yii::$app->user->can('premium'))) {
                //print_r("check here ");
                echo \Yii::$app->view->render('@app/views/site/memberview');
            }
        
        
    ?>
    
    </div>    
</div>

