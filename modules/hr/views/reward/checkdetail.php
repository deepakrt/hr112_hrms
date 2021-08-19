<?php
$this->title= 'View/check Detail';
$menuid = "";
if(isset($_GET['securekey']) AND !empty($_GET['securekey'])){
    $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
}
if(empty($menuid)){
    header('Location: '.Yii::$app->homeUrl); 
    exit;
}
$menuid = Yii::$app->utility->encryptString($menuid);
$e_id = Yii::$app->utility->decryptString($_GET['rewardapplyid']);
$e_id = Yii::$app->utility->encryptString($e_id);
$encry = base64_encode($info['id']);
//$editUrl = Yii::$app->homeUrl."admin/manageemployees/updateemployee?key=$encry";
$editUrl = Yii::$app->homeUrl."admin/reward/updatereward?securekey=$menuid";


?>
<style>
label{ font-weight:bold; font-size: 15px;}
.con {
	font-size: 15px;
}  
.col-sm-3{margin-bottom: 10px;}
</style>
 
 <script type="text/javascript">
function changeUrl(url) {
	var eid='<?=$encry;?>';
	var menuid='<?=$menuid;?>';
	var emp='<?=$e_id;?>';
	var title='';
	url='viewemployee?securekey='+menuid+'&empid='+emp+'&tab='+url;
     if (typeof (history.pushState) != "undefined") {
        var obj = { Title: title, Url: url };
        history.pushState(obj, obj.Title, obj.Url);
    } else {
        alert("Browser does not support HTML5.");
    }
} 
</script>

<div id="exTab1" class="exTab1">	


    <div class="tab-content clearfix">
        <div  id="info">

            <div class="row">

                <div class="col-sm-4">
                    <label>Employee Name </label>
                    <p class="con"><?php echo $info['fname']; ?></p>
                </div>
                <div class="col-sm-4">
                    <label>Employee Code</label>
                    <p class="con"><?php echo $info['employee_code']; ?></p>
                </div>

            </div>
            <hr>
            
            <div class="row">

                <div class="col-sm-4">
                    <label>Reward Name </label>
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
                    <label>Created By </label>
                    <p class="con"><?php echo $info['created_by']; ?></p>
                </div>
                <div class="col-sm-4">
                    <label>Reward Type</label>
                    <p class="con"><?php echo $info['reward_type_id']; ?></p>
                </div>

            </div>
            <hr>
            <div class="row">

                <div class="col-sm-4">
                    <label>Created By </label>
                    <p class="con"><?php echo $info['reward_sub_cat']; ?></p>
                </div>
                <div class="col-sm-4">
                    <label>Reward Status</label>
                    <p class="con"><?php echo $info['is_active']; ?></p>
                </div>

            </div>
            <hr>
            <div class="row">

                <div class="col-sm-4">
                    <label>Created Date </label>
                    <p class="con"><?php echo $info['created_date']; ?></p>
                </div>
               

            </div>
            
                        <hr>
            <div class="row">
                <?php if(Yii::$app->user->identity->role==3){?>
                <div class="col-sm-4">
                    <?php
                    $encry = base64_encode($info['apply_id']);
                    $applyUrl = Yii::$app->homeUrl."hr/reward/applyreward?securekey=$menuid&rewardid=$encry";
                    ?>
                    <a href="<?= $applyUrl ?>" class="btn btn-success btn-sm btn-xs">Apply</a> 
                </div>
                <?php }
                elseif (Yii::$app->user->identity->role==4) {
                    $encry = base64_encode($info['apply_id']);
                    ?>
                     <div class="col-sm-4">
                    <?php
                   
                    $approveUrl = Yii::$app->homeUrl."hr/reward/approvereward?securekey=$menuid&rewardapplyid=$encry";
                    $rejectUrl = Yii::$app->homeUrl."hr/reward/rejectreward?securekey=$menuid&rewardapplyid=$encry";
                    if($info['status']==1){
                    
                    ?>
                    <a href="<?= $approveUrl ?>" class="btn btn-success btn-sm ">Approve and Forward to SLA</a> 
                    <a href="<?= $rejectUrl ?>" class="btn btn-danger btn-sm ">Reject</a> 
                     <a href="<?=Yii::$app->homeUrl?>hr/reward/check?securekey=<?=$menuid?>&rewardid=<?=$encry?>" class="btn btn-danger btn-sm">Cancel</a>
                     <?php
                    }elseif ($info['status']==2) {
                             echo "<b>STATUS: </b> Approved By FLA";
                         }
                         elseif ($info['status']==3) {
                             echo "<b>STATUS: </b> Approved By SLA";
                         }
                         elseif ($info['status']==4) {
                             echo "<b>STATUS: </b> Rejected By FLA";
                         }
                         elseif ($info['status']==5) {
                             echo "<b>STATUS: </b> Rejected By SLA";
                         }
                     ?>
                     </div>
                    
               <?php }
                elseif (Yii::$app->user->identity->role==2) {
                    $encry = base64_encode($info['apply_id']);
                    ?>
                     <div class="col-sm-4">
                    <?php
                   
                    $approveUrl = Yii::$app->homeUrl."hr/reward/approvereward?securekey=$menuid&rewardapplyid=$encry";
                    $rejectUrl = Yii::$app->homeUrl."hr/reward/rejectreward?securekey=$menuid&rewardapplyid=$encry";
                    if($info['status']==2){
                    
                    ?>
                         <span>FLA Approved this request</span><br>
                    <a href="<?= $approveUrl ?>" class="btn btn-success btn-sm ">Approve </a> 
                    <a href="<?= $rejectUrl ?>" class="btn btn-danger btn-sm ">Reject</a> 
                     <a href="<?=Yii::$app->homeUrl?>hr/reward/check?securekey=<?=$menuid?>&rewardid=<?=$encry?>" class="btn btn-danger btn-sm">Cancel</a>
                     <?php
                    }
                         elseif ($info['status']==3) {
                             echo "<b>STATUS: </b> Approved By SLA";
                         }
                         elseif ($info['status']==4) {
                             echo "<b>STATUS: </b> Rejected By FLA";
                         }
                         elseif ($info['status']==5) {
                             echo "<b>STATUS: </b> Rejected By SLA";
                         }
                     ?>
                     </div>
                    
               <?php }
                ?>
               

            </div>

        </div>

    </div>
</div>