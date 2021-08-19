<?php
$this->title= "Change Password";
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin(['options'=>['id'=>'changepassword']]); ?>
<div class="row">
    <div class="col-sm-4" style="margin-bottom: 15px;">
        <label>Current Password</label>
        <input type="password" class="form-control" required="" id="current_password" name="Password[current_password]" placeholder="Current Password" />
    </div>
    <div class="col-sm-4" style="margin-bottom: 15px;">
        <label>New Password</label>
        <input type="password" class="form-control" required="" id="new_password" name="Password[new_password]" placeholder="New Password" />
    </div>
    <div class="col-sm-4" style="margin-bottom: 15px;">
        <label>Confirm New Password</label>
        <input type="password" class="form-control" required="" id="confirm_password" name="Password[confirm_password]" placeholder="Confirm New Password" />
    </div>   
  <div class="col-sm-12 text-center" style="margin-bottom: 15px;">
        <input type="button" class="btn btn-success btn-sm" id="submit_password" value="Submit" />
        <a href="" class="btn btn-danger btn-sm">Cancel</a>
    </div>
</div>
 

<?php ActiveForm::end(); ?>

