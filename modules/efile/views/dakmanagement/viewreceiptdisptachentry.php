<?php
$this->title = "डाक प्रबंधन / Dak Management";
$dak_dispatch = Yii::$app->Dakutility->efile_get_dak_dispatch(NULL);
$dak_received= Yii::$app->Dakutility->efile_get_dak_received(NULL,NULL);
?>
<div class="row">
    <div class="col-sm-6" style="text-align:left"><button  class="btn btn-success btn-sm" id="viewdakreceipt">डाक प्राप्त / Dak Receipt</button></div>
    <div class="col-sm-6" style="text-align:right"><button  class="btn btn-primary btn-sm align-right" id="viewdakdispatch">डाक डिस्पैच / Dak Dispatch</button></div>
    <input type="hidden" value="dakreceipt" id="viewdaktype" name="viewdaktype"/>
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
<div style="display:block" id="viewdakrechtml" class="viewdakrechtml">
    <?= $this->render("viewdakreceipt",['dak_received'=>$dak_received])?>
</div>
<div style="display:none" id="viewdakdishtml" class="viewdakdishtml">
    <?= $this->render("viewdakdispatch",['dak_dispatch'=>$dak_dispatch])?>   
</div>
