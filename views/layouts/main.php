<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
	<link href="<?=Yii::$app->homeUrl?>images/logo1.png" rel="shortcut icon">
    <title><?= Html::encode($this->title) ?></title>
    <script>
        var BASEURL = "<?=Yii::$app->homeUrl?>";
        var Photo_Sign_Size = "<?=Photo_Sign_Size?>";
        var FTS_Doc_Size = "<?=FTS_Doc_Size?>";
        var PDF_File_Size = "<?=PDF_File_Size?>";
        var TODAY_DATE= "<?php echo Date('d-m-Y'); ?>";
    </script>
    <?php $this->head() ?>
    <?php // $module=Yii::$app->controller->module->id;$controller=Yii::$app->controller->id;$action=Yii::$app->controller->action->id;?>
    <?php 
//    if($module.$controller.$action!='ftsgroupcreate'){?>
    <!--<script src="/eMulazim/js/bootstrap.min.js?v=1516244616"></script>-->
    <?php // }?>
</head>
<body>
<?php $this->beginBody() ?>
<input type="hidden" id="_csrf" name="_csrf" value="<?= Yii::$app->request->csrfToken; ?>" />
<div style="display:none;" id="loading">
    <h1 style="font-family: georgia;border:none">Please wait....</h1>
    <img alt="Loading..." src="<?=Yii::$app->homeUrl?>images/loading.gif" id="loading-image">
</div>
<?= $content ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
