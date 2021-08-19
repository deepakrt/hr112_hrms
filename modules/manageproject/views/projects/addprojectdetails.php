<link href="<?=Yii::$app->homeUrl?>css/select2.min.css" rel="stylesheet" />
<script src="<?=Yii::$app->homeUrl?>js/select2.min.js"></script>
<?php
if(empty($menuid)){
    header('Location: '.Yii::$app->homeUrl); 
    exit;
}
?>
<style>
label{ font-weight:bold; font-size: 15px;}
.con { font-size: 15px;} 
.col-sm-3{margin-bottom: 10px;}
.nav > li {
	background: #dadada3b;
	border-radius: 2px 2px 0 0;
}
.active.show {
	color: #000;
	font-weight: bolder;
}
legend {
	margin: 0 0 15px 0;
	font-size: 18px;
}
</style>
 
 <script type="text/javascript">
	 var securekey='<?=$menuid?>';
	 function changeUrl(url) {
		var title=url;
		var key='<?=Yii::$app->utility->encryptString($model->project_id)?>';
		url='addprojectdetails?securekey='+securekey+'&key='+key+'&tab='+url;
		 if (typeof (history.pushState) != "undefined") {
			var obj = { Title: title, Url: url };
			history.pushState(obj, obj.Title, obj.Url);
		} else {
			alert("Browser does not support HTML5.");
		}
	} 
	$(function(){
		$("#project_id").change(function () {
			if(this.value==''){return false;}
			var URL=BASEURL+'manageproject/projects/addprojectdetails?securekey='+menuid+'&key='+this.value;
			 window.location.href = URL;  
  	    }); 
  });
</script>
<?= $this->render('projectlist', ['menuid'=>$menuid]); ?>
 <div class="col-sm-12 form-group form-control">
 <div class="row">
                <div class="col-sm-12">  <b>Cost  : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b> <?php if($model->project_cost){echo $model->project_cost." (".Yii::$app->inventory->get_amount_in_words($model->project_cost).").";}?> </div>
              <div class="col-sm-4">  <b>Start Date : </b> <?php echo $model->start_date;?> </div>
             <div class="col-sm-4">   <b>End Date : </b> <?php echo $model->end_date;?></div>
</div>
</div>
<?php if(!isset($_GET['tab'])){$_GET['tab']='';} ?>
<?php if(Yii::$app->user->identity->role==6){$_GET['tab']='costbreakdown';} ?>
<div id="exTab1" class="exTab1">	
	<ul  class="nav nav-pills">
		<?php  $iclass=$qclass=$fclass=$lclass=''; 
			if($_GET['tab']=='manpower'){$qclass='active show'; }
			elseif($_GET['tab']=='costbreakdown'){ $fclass='active show'; }
			elseif($_GET['tab']=='other'){ $lclass='active show'; }
			else{ $qclass='active show'; } ?>
			<li><a class="<?=$qclass;?>" onclick="changeUrl('manpower')" href="#manpower" data-toggle="tab">Team Details</a> </li>
			<li><a class="<?=$fclass;?>" onclick="changeUrl('costbreakdown')" href="#costbreakdown" data-toggle="tab"> Cost Break-Down </a></li>
			<li><a class="<?=$lclass;?>" onclick="changeUrl('other')" href="#other" data-toggle="tab">Other Details</a> </li>
		</ul>

<div class="tab-content clearfix">
 
<!-------------------2nd------------------------------->
<div class="tab-pane <?=$qclass;?>" id="manpower">
<?php echo $this->render('manpower', ['menuid'=>$menuid,'model'=>$model]);?>
</div>
 
<!----------------------3th---------------------------->

<div class="tab-pane <?=$fclass;?>" id="costbreakdown"> 
<?php echo $this->render('costbreak', ['menuid'=>$menuid,'model'=>$model]);?>
</div>
<!----------------------4th---------------------------->
<div class="tab-pane <?=$lclass;?>" id="other">
<?php echo $this->render('otherdetails', ['menuid'=>$menuid,'model'=>$model]);?>
</div>
<!-------------------------------------------------->
</div>
</div>
