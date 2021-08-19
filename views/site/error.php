<?php
$this->beginContent('@app/views/layouts/main.php'); 
use yii\helpers\Html;
$this->title = "Error";

use app\components\Topmenu;
use app\components\Leftmenu;
use app\components\Bottommenu;
?>
<?=Topmenu::widget();?>
<div class="container">
    <div class="row">
        <div class="col-sm-3">
            <?php 
            if (!\Yii::$app->user->isGuest) {
            Leftmenu::widget(); }?>
        </div>
        <div class="col-sm-9">
            <div class="text-right dttime"><?=date('D M d h:i:s T Y');?></div>
            <div class="maincontent">
                <div class="respantit">Error : Page Not Found</div>
                <div class="text-center">
                    <img style="width: 75%" src="<?=Yii::$app->homeUrl?>images/error.jpg" />
                </div>
<!--                <div class="respantit">Error Found</div>
                <div class="site-error">

                    <h2><?php //Html::encode($this->title) ?></h2>

                    <div class="alert alert-danger">
                        <?php //nl2br(Html::encode($message)) ?>
                    </div>

                    <p>
                        The above error occurred while the Web server was processing your request.
                    </p>
                    <p>
                        Please contact us if you think this is a server error. Thank you.
                    </p>

                </div>-->
            </div>
        </div>
    </div>
</div>
<?=Bottommenu::widget();?>


<?php $this->endContent(); ?>