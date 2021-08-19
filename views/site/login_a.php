<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = 'Login';
$url = Yii::$app->homeUrl;
?>
<style>
body{
	background: url('<?=$url?>images/newbg1.jpg') no-repeat;
	min-height: 100%;
	background-size:cover;
	z-index: 9999;
	padding:0px;
	margin:0px;
	color:#000;
}
.form-group{ margin:0px;}
</style>
<div style="height:20px;"></div>
<div class="col-sm-12 text-center">
	<img class="img-fluid" src="<?=Yii::$app->homeUrl?>images/logo.png" />
	<h5 class='CompanyName'><?=CompanyName?></h5>
</div>
<div class="row">
	<div class="col-sm-8 offset-sm-2">
		<div class="login">
			<div class="row">
				<div class="col-sm-6">
					<div class="activity">
						<ul>
							<li><img src="<?=Yii::$app->homeUrl?>images/hr.png" /><p>HR Management</p></li>
							<li><img src="<?=Yii::$app->homeUrl?>images/pm.png" /><p>Project Management</p></li>
							<li><img src="<?=Yii::$app->homeUrl?>images/im.png" /><p>Inventory Management</p></li>
							<li><img src="<?=Yii::$app->homeUrl?>images/fm.png" /><p>Financial Management</p></li>
						</ul>
					</div>
				</div>
				<div class="col-sm-6" style="border-left: 1px solid lightgray;">
					 <h5>Sign in to Your Account</h5> 
					<?php $form = ActiveForm::begin([
						'id' => 'login-form',
						'options' => ['class' => 'form-horizontal'],
						'fieldConfig' => [
							'template' => "{label}\n<div class=\"col-sm-10\">{input}</div>\n<div class=\"col-sm-8\">{error}</div>",
							'labelOptions' => ['class' => 'col-sm-8 control-label'],
						],
						]); 
					?>
					<?= $form->field($model, 'email')->textInput(['class' => 'form-control form-control-sm', 'placeholder'=>'Enter Email', 'title'=>'Enter Email']) ?>
					<?= $form->field($model, 'password')->passwordInput(['class' => 'form-control form-control-sm', 'placeholder'=>'Enter Password', 'title'=>'Enter password']) ?>
					<div class="col-sm-8 text-center">
						<br>
						<button type="submit" class="btn btn-success btn-sm">Login</button>
						<button type="reset" class="btn btn-danger btn-sm">Reset</button>
						<br><br>
						<a href="#">Forgot Password</a>
					</div>

					<?php ActiveForm::end(); ?>
				</div>
			</div>
			<div class="row ftr">
				<div class="col-sm-6">
					<p>e-Mulazim © copyright <?=date('Y')?>. All Right Reserved.</p>
				</div>
				<div class="col-sm-6 text-right">
					Designed & Developed by <a style="color:#D9371E;" href="https://cdac.in/index.aspx?id=mohali" title="C-DAC, Mohali" target="_blank">C-DAC, Mohali</a>
				</div>
			</div>
		</div>
	</div>
</div>
