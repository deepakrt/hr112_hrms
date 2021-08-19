<?php 
$this->beginContent('@app/views/layouts/main.php'); 
use app\components\Topmenu;
use app\components\Leftmenu;
use app\components\Bottommenu;

?>

<?=Topmenu::widget();?>
<input type="hidden" id="_csrf" name="_csrf" value="<?= Yii::$app->request->csrfToken; ?>" />
<div class="container">
            <div class="text-right dttime"><?=date('l, d-M-Y H:i A');?></div>

    <div class="row">
        <div class="leftmenudiv col-sm-3" style="padding: 0px;">
            <?=Leftmenu::widget();?>
        </div>
        <div class="col-sm-9 maincontentdiv">
            <div class="maincontent">
                <div class="respantit"><?=$this->title?></div>
                    <?php
                foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
                    if(!empty($message)){
                        echo '<div class="col-sm-12 col-xs-12 text-center alert alert-' . $key . '"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <b>' . $message . '</b></div>';
                    }
                    
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
