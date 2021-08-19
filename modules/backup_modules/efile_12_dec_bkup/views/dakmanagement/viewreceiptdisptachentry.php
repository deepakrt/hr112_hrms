<?php
$this->title = "डाक प्रबंधन / Dak Management";
$dak_dispatch = Yii::$app->Dakutility->efile_get_dak_dispatch(NULL);
$dak_received= Yii::$app->Dakutility->efile_get_dak_received(NULL,NULL);

$recViewClss = "btn-success";
$desViewClss = "btn-primary";
$value = "dakreceipt";
$recView = "display:block;";
$desView = "display:none;";
if(isset($_GET['active']) AND !empty($_GET['active'])){
    $get = $_GET['active'];
    if($get == 'D'){
        $value = "dakdispatch";
        $recViewClss = "btn-primary";
        $desViewClss = "btn-success";
        $recView = "display:none;";
        $desView = "display:block;";
    }
}
?>
<div class="row">
    <div class="col-sm-6" style="text-align:left"><button  class="btn <?=$recViewClss?> btn-sm" id="viewdakreceipt">डाक प्राप्त / Dak Receipt</button></div>
    <div class="col-sm-6" style="text-align:right"><button  class="btn <?=$desViewClss?> btn-sm align-right" id="viewdakdispatch">डाक डिस्पैच / Dak Dispatch</button></div>
    <input type="hidden" value="<?=$value?>" id="viewdaktype" name="viewdaktype"/>
</div>
<hr>
<style>
.mb15
{
    margin-bottom:15px; 
}
.respantit {
    text-align: center;
}

</style>
<div style="<?=$recView?>" id="viewdakrechtml" class="viewdakrechtml">
    <?= $this->render("viewdakreceipt",['dak_received'=>$dak_received, 'menuid'=>$menuid])?>
</div>
<div style="<?=$desView?>" id="viewdakdishtml" class="viewdakdishtml">
    <?= $this->render("viewdakdispatch",['dak_dispatch'=>$dak_dispatch, 'menuid'=>$menuid])?>   
</div>
