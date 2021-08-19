<?php 
use yii\helpers\Html;
use  yii\web\Session;
$leave = $e_im = $hr = $pm = $im = $dh = "";
$menus = Yii::$app->utility->getMenus("T",NULL);
$session = Yii::$app->session;
$leftActive = $session->get('activelmenu');
$leftActive = Yii::$app->utility->decryptString($leftActive);

// get value for check same top menu or not, if yes left menu will not empty, so that it can be active
$chkmenu = $session->get('activemenu');
$chkmenu = Yii::$app->utility->decryptString($chkmenu);
if(isset($_GET['securekey']) AND !empty($_GET['securekey'])){
    $menuid1 = Yii::$app->utility->decryptString($_GET['securekey']);
    $menuid = Yii::$app->utility->encryptString($menuid1);
    $session->set('activemenu', $menuid);
    // Check For Left Active
    if($chkmenu != $menuid1){
        $session->set('activelmenu', "");
    }elseif(isset($_GET['chkmenu']) AND !empty($_GET['chkmenu'])){
        $session->set('activelmenu', "");
    }
    if(isset($_GET['securekeyl']) AND !empty($_GET['securekeyl'])){
        $leftid = Yii::$app->utility->decryptString($_GET['securekeyl']);
        if(!empty($leftid)){
            $leftid = Yii::$app->utility->encryptString($leftid);
            $session->set('activelmenu', $leftid);
        }
    }
}

$activemenu = $session->get('activemenu');
$activemenu = Yii::$app->utility->decryptString($activemenu);
?>
<div id="header">
    <div class="topmenu">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="mainlogo">
                       <!-- <a href="<?=Yii::$app->homeUrl?>"><img src="<?=Yii::$app->homeUrl?>images/logo.png" /></a> -->
                        <p><?=CompanyName?></p>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="toprightcontent text-right">
                        <!--<img class="rounded-circle" src="<?=Yii::$app->request->baseUrl.Yii::$app->user->identity->emp_image;?>" />-->
                        <p>Welcome <br>
                        <?=Yii::$app->user->identity->fullname;?>
                        (<span><?=Yii::$app->user->identity->desg_name;?></span>)</p>
                    </div>
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
                            if(!empty($menus)){
                                $i = 1;
                                foreach ($menus as $menu){
                                   $id = Yii::$app->utility->encryptString($menu['menuid']);
                                   $url = Yii::$app->homeUrl.$menu['menu_url']."?securekey=$id&chkmenu=1";
                                   if($activemenu == $menu['menuid']){
                                       $cls="active"; 
                                   }else{
                                       $cls=""; 
                                   }
                                echo '<li class="nav-item topmenuactive" "><a class="nav-link mylink '.$cls.'" href="'.$url.'">'.$menu['menu_name'].'</a></li>'    ;
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
