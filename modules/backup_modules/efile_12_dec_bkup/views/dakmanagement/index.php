<?php
$this->title = "डाक प्रबंधन / Dak Management";
$states = Yii::$app->Dakutility->get_master_states(NULL);
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
    <div class="col-sm-6" style="text-align:left"><button  class="btn <?=$recViewClss?> btn-sm" id="dakreceipt">डाक रसीद / Dak Receipt</button></div>
    <div class="col-sm-6" style="text-align:right"><button  class="btn <?=$desViewClss?> btn-sm align-right" id="dakdispatch">प्रेषण / Dispatch</button></div>
    <input type="hidden" value="<?=$value?>" id="daktype" name="daktype"/>
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
<div style="<?=$recView?>" id="dakrechtml" class="dakrechtml">
    <?= $this->render("dakreceipt",['states'=>$states, 'menuid'=>$menuid])?>
    
</div>
<div style="<?=$desView?>" id="dakdishtml" class="dakdishtml">
    <?= $this->render("dakdispatch",['states'=>$states, 'menuid'=>$menuid])?>
    
</div>
