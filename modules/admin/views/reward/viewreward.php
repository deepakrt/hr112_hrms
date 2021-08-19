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
$e_id = Yii::$app->utility->decryptString($_GET['rewardid']);
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

        </div>

    </div>
</div>