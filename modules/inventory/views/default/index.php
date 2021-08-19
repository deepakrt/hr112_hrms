  <!------ Include the above in your HEAD tag ---------->
<style>
.btn.btn-lg.col-xs-5.col-md-5 {
  font-family: Roboto;
  font-weight: bolder;
  margin: 1px;
}.row {
  opacity: 0.88;
}
</style>
<?php
$menuid = "";
if(isset($_GET['securekey']) AND !empty($_GET['securekey']))
{
    $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
}
if(empty($menuid))
{
    header('Location: '.Yii::$app->homeUrl); 
    exit;
}
$menuid = Yii::$app->utility->encryptString($menuid);
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                
                <div class="panel-body">
                    <div class="row">
                        <div class="btn btn-info btn-lg col-xs-5 col-md-5">
							<a href="<?=Yii::$app->homeUrl?>inventory/default/irequest?securekey=<?=$menuid?>" class="" role="button"><span class="glyphicon glyphicon-list-alt"></span> Issue Request</a>
						</div>
						<div class="btn btn-warning btn-lg col-xs-5 col-md-5">
							<a href="<?=Yii::$app->homeUrl?>inventory/default/rstatus?securekey=<?=$menuid?>" role="button"><span class="glyphicon glyphicon-bookmark"></span> Request Status</a>
						</div>
						<div class="btn btn-danger btn-lg col-xs-5 col-md-5">
							<a href="<?=Yii::$app->homeUrl?>inventory/default/jrequest?securekey=<?=$menuid?>" role="button"><span class="glyphicon glyphicon-signal"></span> Job Request</a>
						</div>
						<div class="btn btn-success btn-lg col-xs-5 col-md-5">
							<a href="<?=Yii::$app->homeUrl?>inventory/default/jstatus?securekey=<?=$menuid?>" role="button"><span class="glyphicon glyphicon-comment"></span> Job Status</a>
                        </div> 
						<div class="btn btn-primary btn-lg col-xs-5 col-md-5">
							<a href="<?=Yii::$app->homeUrl?>inventory/default/aalloted?securekey=<?=$menuid?>" role="button"><span class="glyphicon glyphicon-comment"></span> Job Alloted</a>
                        </div>
                        
                    </div>
                   
                </div>
            </div>
        </div>
    </div>
</div>
