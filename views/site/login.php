<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
$this->title = 'e-Mulazim - Login';
$url = Yii::$app->homeUrl;
//$role1=Yii::$app->utility->get_master_roles();
//
//if(!empty($role1)){
//    $new = array();
//    $i=0;
//    foreach($role1 as $role){
//        if($role['role_id'] == '1' OR $role['role_id'] == '2' OR $role['role_id'] == '3' OR $role['role_id'] == '4' OR $role['role_id'] == '9'){  //OR $role['role_id'] == '5' OR $role['role_id'] == '6' OR $role['role_id'] == '7'
//            $id = Yii::$app->utility->encryptString($role['role_id']);
//            $new[$i]['role_id']=$id;
//            $new[$i]['role']=$role['role'];
//            $i++;
//        }
//    }
//    $role1 = $new;
//}
////echo "<pre>";print_r($role1); die;
//$roles = ArrayHelper::map($role1, 'role_id', 'role');
//$form->field($model, 'role')->dropDownList($roles, ['prompt'=>'Select Role', 'class'=>'role form-control head-content form-control-sm'])->label(false); 
?>
<style>
input, select{
margin-top:10px;padding:8px 0 8px 40px !important;
}
body{
  /*background: url('<?=$url?>images/newbg1.jpg') no-repeat;*/
  background-color: #fff;
  min-height: 100%;
  background-size:cover;
  z-index: 9999;
  padding:0px;
  margin:0px;
  color:#000;
}
.login{
box-shadow: 0 0 3px 1px;
}
.activity img {
    width: 50%;
}
.activity{padding:0;}
.footer-content {
    font-family: "Tahoma";
    font-size: 14px;
    color: #333333;
}
.h-content{font-family: "Tahoma";
     color: #333333;margin: 10px 0 15px 0;
}.head-content{font-family: "Tahoma";
     
}
label {

    margin-bottom:0;
}
.form-group{ margin:0px;}
.row {
  margin-right: 0px;
}
.modal-dialog {
  margin: 7.75rem auto;
}

img.img-fluid.newlogo {
    width: 8%;
}
</style>

<?php
	
?>
<div style="height:20px;border-top: 2px solid #88AC28;"></div>
<div class="col-sm-12 text-center">
    <img class="img-fluid newlogo" src="<?=Yii::$app->homeUrl?>images/hrp_logo.png" />
    <br>
    <br>
    <h5 class='CompanyName head-content'><?=CompanyNameLoginPage?></h5>
</div>
<div class="container">
<div class="row">
    <div class="col-sm-8 offset-sm-2">
        <div class="login">
            <div class="row">
                <div class="col-sm-6">
                    <div class="activity">
                        <ul>
                        <li data-toggle="modal" data-target="#myModal"><img src="<?=Yii::$app->homeUrl?>images/hr.png" /><p class="footer-content">HR Management</p></li>
                        <li data-toggle="modal" data-target="#myModal2"><img src="<?=Yii::$app->homeUrl?>images/pm.png" /><p class="footer-content">Project Management</p></li>
                        <li data-toggle="modal" data-target="#myModal3"><img src="<?=Yii::$app->homeUrl?>images/im.png" /><p class="footer-content">Inventory Management</p></li>
                        <li><a target="_blank" href="http://10.26.0.51/visitor"><img src="<?=Yii::$app->homeUrl?>images/visitor_img.png" /><p class="footer-content">Visitor Management</p></a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-6" style="border-left: 1px solid lightgray;">
                    <h5 class="text-center h-content">Login Here</h5>  
                    <?php $form = ActiveForm::begin([
                            'id' => 'login-form',
                            'options' => ['class' => 'form-horizontal'],
                            'fieldConfig' => [
                                    'template' => "{label}\n<div class=\"col-sm-12\">{input}</div>\n<div class=\"col-sm-8\">{error}</div>",
                                    'labelOptions' => ['class' => 'col-sm-8 control-label'],
                            ],
                            ]); 
                    ?>
                    <?= $form->field($model, 'email')->textInput(['class' => 'form-control username head-content form-control-sm', 'placeholder'=>'User Name', 'title'=>'User Name'])->label(false); ?>
                    <?= $form->field($model, 'password')->passwordInput(['class' => 'form-control password head-content form-control-sm', 'placeholder'=>'Password', 'title'=>'Password'])->label(false); ?>
                    
                    <div class="col-sm-12 text-left">
                        <br>
                        <button type="submit" class="btn btn-success btn-sm">Login</button>
                        <button type="reset" class="btn btn-danger btn-sm">Reset</button>
                         &nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="#" class="footer-content">Forgot Password</a>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
        <div class="row ftr">
            <div class="col-sm-12 text-center">
                <p class="footer-content"><img src="<?=Yii::$app->homeUrl?>images/logo_cdac.png" style="height: 29px;margin: 0 7px 16px 0;"><a style="color:#D9371E;" href="https://cdac.in/index.aspx?id=mohali" title="C-DAC, Mohali" target="_blank">C-DAC, Mohali</a> © copyright <?=date('Y')?>. All Right Reserved.</p>
            </div>
        </div>
    </div>
</div></div>

 
  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog" style="min-width: 60%;">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="background: #F1A502;">
           <h4 class="modal-title text-left">HR Management</h4><button type="button" class="close" data-dismiss="modal">&times;</button>
         
        </div>
        <div class="modal-body" style="border-bottom: 5px solid #F1A502;">
          <p> <b>Human Resource Management System</b> is the strategic approach to the effective management of people in an organization so that they help the business to gain a competitive advantage. It is designed to maximize employee performance in service of an employer's strategic objectives. </p>
            <p><b>Major Functions of Human Resources</b></p>
            <ul>
                <li>Registration of all the Teaching & Non-teaching staff from the date of  joining till retirement along with Photographs / Signature / Full family details</li>
                <li>Employee benefits related to claim, advance and loan</li>
                <li>Provision to prepare training courses, training calendars and plans, faculty details, training budget details, capture training attendance, employees feedback about training, maintain training history</li>
                <li>Their Pay scales attached/ Seniority in each cadre / dates of promotion / eligibility etc.</li>
                <li>Maintenance of training details of staff members.</li>
                <li>Service Book, Pension, Gratuity and other superannuation benefits.</li>
                <li>Total service details and other information.</li>
                <li>Leave management process according to leave rules configuration, leave encashment, online leave approval workflow.</li>
                <li>Attendance tracking and management, integration options with biometric based attendance systems.</li>
            </ul>
        </div>
        
      </div>
      
    </div>
  </div>
  <div class="modal fade" id="myModal2" role="dialog">
    <div class="modal-dialog" style="min-width: 60%;">
    
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header" style="background: #58B4DB;">
          <h4 class="modal-title">Project Management</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          
        </div>
        <div class="modal-body" style="border-bottom: 5px solid #58B4DB;">
            <p>This Module creates Projects, Tasks , and resources allocation. Project Dashboard provide a visual representation of key projects information and metrics. Project Management Module is integrated with other components to build a comprehensive view of your activity.</p>
            <p><b>The Project Phases Involved:</b></p>
            <ul>
                <li>Project Initiation.</li>
                <li>Project Planning.</li>
                <li>Project Team.</li>
                <li>Project Assign Task.</li>
                <li>Project Execution.</li>
                <li>Project Closure.</li>
            </ul>
            <p><b>File & Record Management System</b></p>
            <ul>
                <li>Processing of Dak & Dairy, RTI receipts according to the Hierarchy.</li>
                <li>Manage all the Daks, RTI applications, files, records etc.</li>
                <li>Maintains records of Archive files.</li>
                <li>Management and searching the pending cases.</li>
                <li>Maintains and view the current status of the processed file.</li>
                <li>Last date reminder facility.</li>
            </ul>
        </div>
        
      </div>
      
    </div>
  </div>
  <div class="modal fade" id="myModal3" role="dialog">
    <div class="modal-dialog" style="min-width: 60%;">
    
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header" style="background: #DD7869;">
          <h4 class="modal-title">Inventory Management</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
         
        </div>
        <div class="modal-body" style="border-bottom: 5px solid #DD7869;">
            <p><b>Inventory management</b> is the supervision of non-capitalized assets (inventory) and stock items. Inventory or stock is the goods and materials that a business holds for the ultimate goal of resale. Inventory management is a discipline primarily about specifying the shape and placement of stocked goods. </p>
          <p><b>Major Functions of Inventory management</b></p>
            <ul>
                <li>Procurement: Raising purchase requisition, Quotation creation and approval, Purchase Order creation and approval.</li>
                <li>Managing purchase and store returns.</li>
                <li>Preparing of purchase tender templates and uploading on ERP.</li>
                <li>Online linking of tender with e-tendering websites.</li>
                <li>Maintaining several registers related to sale, purchase and returns.</li>
                <li>Job card management.</li>
                <li>Inventory: Goods Receipt Note (GRN), unplanned/cash purchases, indent raising, dispatch of items, stock adjustments, Management of audit objections.</li>
            </ul>
        </div>
         
      </div>
      
    </div>
  </div>