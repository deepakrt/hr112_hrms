<?php 
$this->title = "Employee Occupancy";
?>
<script>
$(document).ready(function() {
	$('#dataTableShow2').DataTable({
        "order": [[ 1, "asc" ]]
    });
});
</script>
<div class="row">
    <div class="col-sm-6 text-left"><h5><b>List of Employees</b></h5></div>
    <div class="col-sm-6 text-right"></div>
    <div class="col-sm-12">
        <hr>
        <table id="dataTableShow2" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th class="col-sm-3">Project Name</th>
                    <th class="col-sm-3">Start Date</th>
                    <th class="col-sm-3">Completion Date</th>                                     
                    <th class="col-sm-1">Occupancy</th>  
                    <th class="col-sm-1">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php                     
                    $lists='';
                    if(!empty($projects)){
                        $i =1;
                        foreach($projects as $p){                            
                            $editUrl = Yii::$app->homeUrl."manageproject/manpower/detail?securekey=$menuid";                            
                    ?>
                    <tr>
                        <td><?=$i?></td>
                        
                        <td class="col-sm-5"><?= Yii::$app->projectcls->SelectOrder($p['orderid'])['projectname']?></td>
                        <td class="col-sm-2"><?=date('d-m-Y',strtotime(Yii::$app->projectcls->Projectwithorder($p['orderid'])[0]['start_date']))?></td>
                        <td class="col-sm-2"><?php if(Yii::$app->projectcls->Projectwithorder($p['orderid'])[0]['actualcompletiondate'] == null){
                                        echo  date('d-m-Y',strtotime(Yii::$app->projectcls->Projectwithorder($p['orderid'])[0]['end_date'])) ;                                        
                                    } else {
                                        echo  date('d-m-Y',strtotime(Yii::$app->projectcls->Projectwithorder($p['orderid'])[0]['actualcompletiondate']));
                                    }?></td>                       
                        <td class="col-sm-1"><?=$p['salary'] .'%'?></td> 
                        <td class="col-sm-1">
                            <?php
                                if(strtotime(Yii::$app->projectcls->Projectwithorder($p['orderid'])[0]['end_date']) < strtotime(date("Y/m/d"))){
                                    echo 'Inactive';
                                } else{
                                    echo 'Active';
                                }
                            ?>
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