<?php
$this->title = "Projects List";
?>
<script>
$(document).ready(function() {
	$('#dataTableShow2').DataTable({
        "order": [[ 1, "asc" ]]
    });
});
</script>
<div class="row">
    <div class="col-sm-6 text-left"><h5><b>List of All Projects</b></h5></div>
    <div class="col-sm-6 text-right">
        <a href="<?=Yii::$app->homeUrl?>manageproject/projects/addnewproject?securekey=<?=$menuid?>" class="btn btn-success btn-sm mybtn" title="Add New Project">Add New Project</a>
    </div>
     <div class="col-sm-12">
        <hr>
        <table id="dataTableShow2" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>Project Name</th>
                    <th>Project Type</th>
                    <th>Contact Person</th>
                    <th>Project Cost</th>
                    <th>Date(From-To)</th>
                    <th>Status</th>
                    
                </tr>
            </thead>
                <tbody>
                    <?php 
//                    echo "<pre>";print_r($projects);
                    $lists='';
                    if(!empty($projects)){
                        $i =1;
                        foreach($projects as $p){
                            $Prid = Yii::$app->utility->encryptString($p['project_id']);
                            $status = Yii::$app->utility->encryptString($p['status']);
                            $editUrl = Yii::$app->homeUrl."manageproject/projects/updateproject?securekey=$menuid&key=$Prid&key1=$status";
                            $add_cbd = Yii::$app->homeUrl."manageproject/projects/addnewproject?securekey=$menuid&key=$Prid&key1=$status";
                    ?>
                    <tr>
                        <td><?=$i?></td>
                        
                        <td><?=$p['project_name']?></td>
                        <td><?=$p['project_type']?></td>
                        <td><?=$p['fname']?></td>
                       
                        <td><?=$p['project_cost']?></td>
                        <td><?=$p['status']?></td>
                        <td><a href="<?=$add_cbd?>" title="Add Project BreakDown">+</a>&nbsp; &nbsp;<a href="<?=$editUrl?>" title="Edit Project Information"><img src='<?=Yii::$app->homeUrl?>images/edit.gif'></a>&nbsp; &nbsp;<a href="<?=$editUrl.'&view=1';?>" class="linkcolor">View</a></td>
                        
                    </tr>	
                    <?php $i++;	
                        }
                    }
                    ?>
                </tbody>
                
        </table>
    </div>
</div>
