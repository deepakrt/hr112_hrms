<?php 
$menus = Yii::$app->utility->getTopMenus();
use yii\helpers\Html;
use  yii\web\Session;

$session = Yii::$app->session;
$leftActive = $session->get('activelmenu');
$leftActive = Yii::$app->utility->decryptString($leftActive);

// get value for check same top menu or not, if yes left menu will not empty, so that it can be active
$chkmenu = $session->get('activemenu');
$chkmenu = Yii::$app->utility->decryptString($chkmenu);
if(isset($_GET['securekeyl']) AND !empty($_GET['securekeyl'])){
    $menuid1 = Yii::$app->utility->decryptString($_GET['securekeyl']);
    $menuid = Yii::$app->utility->encryptString($menuid1);
    $session->set('activemenu', $menuid);
}

$activemenu = $session->get('activemenu');
$activemenu = Yii::$app->utility->decryptString($activemenu);
?>
<div id="header">
    <div class="topmenu">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <div class="mainlogo">
                       <!-- <a href="<?=Yii::$app->homeUrl?>"><img src="<?=Yii::$app->homeUrl?>images/logo.png" /></a> -->
                        <p style="font-size: 16px;"><?=CompanyNameHeader?></p>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="rightlogo text-right">
                        <img src="<?=Yii::$app->homeUrl?>images/hrp_logo.png" />
                    </div>
                    <?php
                    //if(!empty(Yii::$app->user->identity->e_id)){?>
<!--                    <div class="toprightcontent text-right">
                        <img class="rounded-circle" src="<?=Yii::$app->request->baseUrl.Yii::$app->user->identity->emp_image;?>" />
                        <p>Welcome <br>
                        <?=Yii::$app->user->identity->fullname;?>
                        (<span><?=Yii::$app->user->identity->desg_name;?></span>)</p>
                    </div>-->
                    <?php //}?>
                </div>
            </div>
        </div>
    </div>
    <div class="mainmenu">
        <div class="container">
            <div class="col-sm-12">
               
                <nav class="navbar navbar-expand-lg navbar-light bg-light bglight">
                    <a class="navbar-brand" href="#"></a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mr-auto">
                            <?php
                            if(!empty(Yii::$app->user->identity->e_id)){
                            if(!empty($menus)){
                                $i = 1;
                                foreach ($menus as $menu){
                                    $left = Yii::$app->utility->get_left_menu($menu['menuid']);

                                    $aaaa = $left[0]['menuid'];
                                    $menuurl = $menu['menu_url'];
                                    $leftmenuid = $left[0]['menuid'];
                                    $id = Yii::$app->utility->encryptString($leftmenuid);
                                    
                                    $id1 = Yii::$app->utility->encryptString($menu['menuid']);
                                    $url = "";
                                    $chk = "";
                                    if(!empty($menu['menu_url'])){
                                        $url = $menu['menu_url'];
//                                        $chk = "1---";
                                    }else{
                                        $url = $left[0]['menu_url'];
//                                        $chk = "2---";
                                    }
                                    $url = Yii::$app->homeUrl.$url."?securekey=$id&securekeyl=$id1";       //die($url);
                                    if($activemenu == $menu['menuid']){
                                       $cls="active"; 
                                    }else{
                                       $cls=""; 
                                    }
                                    echo '<li class="nav-item topmenuactive menuhover"><a class="nav-link mylink '.$cls.'" href="'.$url.'">'.$menu['menu_name'].'</a></li>'    ;
                                    $i++;
                                }
                            }
                            echo '<li>'
                                . Html::beginForm(['/site/logout'], 'post', ['class' => ''])
                                . Html::submitButton(
                                        'Logout',
                                        ['class' => 'logbtn mylink']
                                )
                                . Html::endForm()
                                . '</li>'; 
                            }else{
                                $url = Yii::$app->homeUrl."site/login";
                                $url1 = Yii::$app->homeUrl;
                                echo '<li class="nav-item topmenuactive" "><a class="nav-link mylink" href="'.$url1.'">Home</a></li>';
                                
                                echo '<li class="nav-item topmenuactive" "><a class="nav-link mylink" href="'.$url.'">Login</a></li>';
                            }
                            ?>
                        </ul>
                    </div>
                </nav>
                
            </div>
        </div>
    </div>  
</div>
<?php 
// FOR CSS PURPOSE
    if(Yii::$app->controller->module->id == 'admin'){
        echo '<div class="htop2"></div>';
    }else{
        echo '<div class="htop"></div>';
    }
    ?>
