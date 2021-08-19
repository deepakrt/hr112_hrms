<?php
$this->title = "Initiate New File";
?>
<?php 

echo \Yii::$app->view->renderFile(Yii::getAlias('@app') . '/modules/efile/add_new_dak.php', ['recieveddak'=>"", 'model'=>$model, 'menuid'=>$menuid]);
?>
