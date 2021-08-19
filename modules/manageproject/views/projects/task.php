<script>
$(document).ready(function() {
	$('#dataTableShow2').DataTable({
        "order": [[ 1, "asc" ]]
    });
});
</script>
<?= $this->render('projectlist', ['menuid'=>$menuid]); ?>
<div class="row">
    <div class="col-sm-6 text-left"><h6><b>Task List</b></h6></div>
    <div class="col-sm-6 text-right">
        <a href="<?=Yii::$app->homeUrl?>manageproject/projects/addtask?securekey=<?=$menuid?>" class="btn btn-success btn-sm mybtn" title="Add New Project">Add New Task</a>
    </div>
     <div class="col-sm-12">
        <hr>
        <table id="dataTableShow2" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>Task</th>
                    <th>Assigned To</th>
                    <th>Priority</th>
                    <th>Progress</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th width="12%"> Action</th>
                    
                </tr>
            </thead>
                <tbody>
                    <?php 
//                    echo "<pre>";print_r($projects);
                    $lists='';
                    if(!empty($alltasks)){
                        $i =1;
                        foreach($alltasks as $p){
                            $task_id = Yii::$app->utility->encryptString($p['task_id']);
                            $editUrl = Yii::$app->homeUrl."manageproject/projects/viewtask?securekey=$menuid&key=$task_id";
                   	 ?>
                    <tr>
                        <td><?=$i?></td>
                        <td><?=$p['task_name']?></td>
                        <td><?=$p['assigned_to_name']?></td>
                        <td><?=$p['priority']?></td>
                        <td><?=$p['progress']?></td>
                        <td><?=$p['task_type']?></td>
                        <td><?=$p['state']?></td>
                        <td><a href="<?=$editUrl?>" title="Task Details">
							<img width='25' src='<?=Yii::$app->homeUrl?>images/details_open.png'></a>
						</td>
                     </tr>	
                    <?php $i++;	
                        }
                    } 
                    ?>
                </tbody>
                
        </table>
    </div>
</div>
