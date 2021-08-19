<?php 
$this->beginContent('@app/views/layouts/main.php'); 
use app\components\Topmenu;
use app\components\Leftmenu;
use app\components\Bottommenu;

?>
<?=Topmenu::widget();?>
<style>
    .content-wrapper {
    margin-left: 0px;
    margin-right: 20px;
}
</style>
<input type="hidden" id="_csrf" name="_csrf" value="<?= Yii::$app->request->csrfToken; ?>" />
<div class='row'>
	<div class='col-sm-12'>
        <div class="respantit text-center"><?=$this->title?></div>
	</div>
</div>

<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-3"> <?=Leftmenu::widget();?></div>
            <div class="col-sm-9">
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
			
			<?= $content ?></div>
        </div>
        
        
    </div>
</div>


<?=Bottommenu::widget();?>

<?php $this->endContent(); ?>
