<?php
$this->title = "Projects List";
?>
<div class="row">
<script>
$(document).ready(function() {
	$('#dataTableShow2').DataTable({
        "order": [[ 1, "asc" ]]
    });
});
</script>
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
                        <td><?=date("d-M-Y",strtotime($p['start_date'])).'<hr style="margin: 2px 30px 2px 0px;">'.date("d-M-Y",strtotime($p['end_date']));?></td>
                        <td><?=$p['status']?></td>
                        
                    </tr>	
                    <?php $i++;	
                        }
                    }
                    ?>
                </tbody>
                
        </table>
    </div>
</div>
