<?php
$this->title = "डाक प्रबंधन / Dak Management";
$states = Yii::$app->Dakutility->get_master_states(NULL);
?>
<div class="row">
    <div class="col-sm-6" style="text-align:left"><button  class="btn btn-success btn-sm" id="dakreceipt">डाक रसीद / Dak Receipt</button></div>
    <div class="col-sm-6" style="text-align:right"><button  class="btn btn-primary btn-sm align-right" id="dakdispatch">प्रेषण / Dispatch</button></div>
    <input type="hidden" value="dakreceipt" id="daktype" name="daktype"/>
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
<div style="display:block" id="dakrechtml" class="dakrechtml">
    <?= $this->render("dakreceipt",['states'=>$states, 'menuid'=>$menuid])?>
    
</div>
<div style="display:none" id="dakdishtml" class="dakdishtml">
    <?= $this->render("dakdispatch",['states'=>$states, 'menuid'=>$menuid])?>
    
</div>
