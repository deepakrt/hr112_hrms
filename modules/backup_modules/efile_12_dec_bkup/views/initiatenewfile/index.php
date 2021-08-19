<?php
$this->title = "Initiate New File";
//$vpath = Yii::$app->utility->encryptString("/other_files/FTS_Documents/343732/2207831591696394.pdf");
//$vid = Yii::$app->utility->encryptString("100002");
//$mid = Yii::$app->utility->encryptString("140");
//$voucherurl = Yii::$app->homeUrl."efile/initiatenewfile?securekey=$mid&vid=$vid&vpath=$vpath";
?>
<!--<a href="<?php //$voucherurl?>">Voucher Entry</a>-->
<?php 

echo \Yii::$app->view->renderFile(Yii::getAlias('@app') . '/modules/efile/add_new_dak.php', ['recieveddak'=>"", 'model'=>$model, 'menuid'=>$menuid, 'voucher_number'=>$voucher_number, 'voucher_path'=>$voucher_path]);
?>
