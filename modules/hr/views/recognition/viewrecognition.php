<?php
$this->title= 'View Detail';
$menuid = "";
if(isset($_GET['securekey']) AND !empty($_GET['securekey'])){
    $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
}
if(empty($menuid)){
    header('Location: '.Yii::$app->homeUrl); 
    exit;
}
$menuid = Yii::$app->utility->encryptString($menuid);
$e_id = Yii::$app->utility->decryptString($_GET['recoid']);
$e_id = Yii::$app->utility->encryptString($e_id);
$encry = base64_encode($info['id']);
//$editUrl = Yii::$app->homeUrl."admin/manageemployees/updateemployee?key=$encry";
$editUrl = Yii::$app->homeUrl."admin/reward/updatereward?securekey=$menuid";

  $reco_type = array('1' => 'Bonus', '2' => 'Appreciation Letter', '3' => 'Verbal Appreciation');
  $reco_from_source = array( '1' => 'Internal', '2' => 'External');

?>
<style>
label{ font-weight:bold; font-size: 15px;}
.con {
	font-size: 15px;
}  
.col-sm-3{margin-bottom: 10px;}
</style>
 


<div id="exTab1" class="exTab1">	


    <div class="tab-content clearfix">
        <div  id="info">

            <div class="row">

                <div class="col-sm-4">
                    <label>Name </label>
                    <p class="con"><?php echo $info['name']; ?></p>
                </div>
                <div class="col-sm-4">
                    <label>Description</label>
                    <p class="con"><?php echo $info['description']; ?></p>
                </div>

            </div>
            <hr>
            <div class="row">

                <div class="col-sm-4">
                    <label>Recognition type</label>
                    <p class="con"><?php echo $reco_type[$info['reco_type']]; ?></p>
                </div>
                <div class="col-sm-4">
                    <label>Department</label>
                    <p class="con"><?php echo $info['from_department']; ?></p>
                </div>

            </div>
            <hr>
            <div class="row">

                <div class="col-sm-4">
                    <label>Source Type </label>
                    <p class="con"><?php echo $reco_from_source[$info['from_type']]; ?></p>
                </div>
                <div class="col-sm-4">
                    <label> Status</label>
                    <p class="con"><?php echo $info['is_active']; ?></p>
                </div>

            </div>
            <hr>
            <div class="row">

                <div class="col-sm-4">
                    <label>Created Date </label>
                    <p class="con"><?php echo date('d M  Y',strtotime($info['created'])); ?></p>
                </div>
               

            </div>

        </div>

    </div>
</div>