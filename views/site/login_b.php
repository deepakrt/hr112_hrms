<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = 'Login';
// $this->params['breadcrumbs'][] = $this->title;
?>
<div style="height:60px;"></div>
<div class="row">
	<div class="col-sm-8">
		<div class="activity">
			<ul>
				<li>
					<img src="<?=Yii::$app->homeUrl?>images/hr.png" />
					<p>HR Management</p>
				</li>
				<li>
					<img src="<?=Yii::$app->homeUrl?>images/pm.png" />
					<p>Project Management</p>
				</li>
				<li>
					<img src="<?=Yii::$app->homeUrl?>images/im.png" />
					<p>Inventory Management</p>
				</li>
				<li>
					<img src="<?=Yii::$app->homeUrl?>images/fm.png" />
					<p>Financial Management</p>
				</li>
				
			</ul>
		</div>
	</div>
	<div class="col-sm-4 loginsec">
		<div class="text-center">
			<img src="<?=Yii::$app->homeUrl?>images/logo1.png" />
		</div>
		<?php $form = ActiveForm::begin([
			'id' => 'login-form',
			'options' => ['class' => 'form-horizontal'],
			'fieldConfig' => [
				'template' => "{label}\n<div class=\"col-sm-10\">{input}</div>\n<div class=\"col-sm-8\">{error}</div>",
				'labelOptions' => ['class' => 'col-sm-8 control-label'],
			],
			]); 
		?>

		<?= $form->field($model, 'username')->textInput(['class' => 'form-control form-control-sm', 'placeholder'=>'Enter Email', 'title'=>'Enter Email']) ?>

		<?= $form->field($model, 'password')->passwordInput(['class' => 'form-control form-control-sm', 'placeholder'=>'Enter Password', 'title'=>'Enter password']) ?>

		<?php 
		//$form->field($model, 'rememberMe')->checkbox(['template' => "<div class=\"col-sm-3\">{input} {label}</div>\n<div class=\"col-sm-8\">{error}</div>",]) 
		?>
		<div class="col-sm-12 text-center">
			<button type="submit" class="btn btn-success btn-sm">Login</button>
			<button type="reset" class="btn btn-danger btn-sm">Reset</button>
			<br><br>
			<a href="#">Forgot Password</a>
		</div>

	<?php ActiveForm::end(); ?>
	</div>
</div>

    

