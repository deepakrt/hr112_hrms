<?php
$this->title= 'Manage Employee Leaves';
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
 	var title='';
	url='manageempleaves?tab='+url;
     if (typeof (history.pushState) != "undefined") {
        var obj = { Title: title, Url: url };
        history.pushState(obj, obj.Title, obj.Url);
        window.location.reload();
    } else {
        alert("Browser does not support HTML5.");
    }
} 
</script>
<?php if(!isset($_GET['tab'])){$_GET['tab']='';} ?>
<div id="exTab1" class="exTab1">	
	<ul  class="nav nav-pills">
		<?php  $iclass=$qclass=$fclass=$lclass=''; 
			if($_GET['tab']=='requests'){$iclass='active show'; } 
			elseif($_GET['tab']=='approved'){$qclass='active show'; }
			elseif($_GET['tab']=='rejected'){ $fclass='active show'; }
			elseif($_GET['tab']=='leaves'){ $lclass='active show'; }
			else{ $iclass='active show'; } ?>
		<li><a class="<?=$iclass;?>" onclick="changeUrl('requests')" href="#requests" data-toggle="tab">Leave Requests</a></li>
		<li><a class="<?=$qclass;?>" onclick="changeUrl('approved')" href="#approved" data-toggle="tab">Approved/Rejected</a></li>
 
		</ul>

<div class="tab-content clearfix">
<div class="tab-pane <?=$iclass;?>" id="requests">
<?php echo $this->render('leaves_requests', ['employee_leaves'=>$employee_leaves]);?>
</div>
<!-------------------2nd------------------------------->
<div class="tab-pane <?=$qclass;?>" id="approved">
<?php echo $this->render('approved_leaves', ['employee_leaves'=>$employee_leaves]);?>
</div>
<!-------------------------------------------------->
 
</div>
</div>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<div class="container">
  <!--h2>Modal Example</h2>
  <!-- Trigger the modal with a button -->
  <!--button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button-->

  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body">
          <p>Some text in the modal.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  
</div>
