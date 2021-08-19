<?php 
$this->title = "Audit Details";
?>
<script>
$(document).ready(function() {
	$('#dataTableShow2').DataTable({
        "order": [[ 1, "asc" ]]
    });
});
</script>
<div class="row">
    <div class="col-sm-6 text-left"><h5><b><?=Yii::$app->pmis_project->pmis_get_projects(Yii::$app->utility->decryptString($projects))['projectname']?></b></h5></div>
    <div class="col-sm-6 text-right">
        <a href="<?=Yii::$app->homeUrl?>manageproject/ordermaster/addaudit?securekey=<?=$menuid?>&key=<?=$projects?>" class="btn btn-success btn-sm mybtn" title="Add New Project">Add New Project</a>
    </div>
    <div class="col-sm-12">
        <hr>
        <table id="dataTableShow2" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>Audit Type</th>
                    <th>Start Date</th>
                    <th>Audit Agency</th>
                    <th>Report Summary</th>
                    <th>Report Date</th>
                    
                    <th></th>
                </tr>
            </thead>
                <tbody>
                    <?php 
                        $pid = Yii::$app->utility->decryptString($projects);                    
//                    echo "<pre>";print_r($projects);
                    $lists='';
                    if(!empty($audits)){
                        $i =1;
                        foreach($audits as $p){ ?>
                    <tr>
                        <td><?=$i?></td>
                        
                        <td><?=$p['audittype']?></td>
                        <td><?=date('d-m-Y',strtotime($p['startdate']))?></td>
                        <td><?=$p['auditagency']?></td>
                        <td><?=$p['auditreport']?></td>
                        <td><?=$p['reportdate']?></td>                    
                    </tr>	
                    <?php $i++;	
                        }
                    }
                    ?>
                </tbody>
                
        </table>
    </div>
</div>