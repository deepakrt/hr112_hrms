<?php 
$this->beginContent('@app/views/layouts/main.php'); 
use app\components\Ftstopmenu;
use app\components\Leftmenu;
use app\components\Bottommenu;

?>

<?=Ftstopmenu::widget();?>

<div class="container">
            <div class="text-right dttime"><?=date('D M d h:i:s T Y');?></div>

    <div class="row">
        <div class="col-sm-3" style="padding: 0px;">
            <?=Leftmenu::widget();?>
        </div>
        <div class="col-sm-9">
            <div class="maincontent">
                <div class="respantit"><?=$this->title?></div>
                    <?php
                foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
                    echo '<div class="col-sm-12 col-xs-12 text-center alert alert-' . $key . '"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <b>' . $message . '</b></div>';
                }
                ?>
                <span id="display_error" style="display:none;">
                    <div class="col-sm-12 text-center">
                        <div class="alert alert-danger" role="alert">
                          <span id="display_error_message"></span>
                        </div>
                    </div>
                </span>
                <?= $content ?>
            </div>
        </div>
    </div>
</div>
<?=Bottommenu::widget();?>
<?php $this->endContent(); ?>
