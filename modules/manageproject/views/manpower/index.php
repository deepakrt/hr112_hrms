<?php 
$this->title = "Manpower";
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
                    <th class="col-sm-5">NAME / DESIGNATION</th>                    
                    <th class="col-sm-5">SKILL SET</th>                                     
                    <th class="col-sm-1">Occupancy</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php                     
                    $lists='';
                    if(!empty($projects)){
                        $i =1;
                        foreach($projects as $p){                            
                            $Prid = Yii::$app->utility->encryptString($p['employee_code']);
                            $editUrl = Yii::$app->homeUrl."manageproject/manpower/detail?securekey=$menuid&key=$Prid";                            
                    ?>
                    <tr>
                        <td><?=$i?></td>                        
                        <td class="col-sm-5"><?=$p['name']?></td>                        
                        <td class="col-sm-5"><?//=$p['fname']?></td>                       
                        <td class="col-sm-1">
                            <?php                             
                                if(Yii::$app->projectcls->mapEd(base64_decode($p['employee_code']))!=null){
                                    echo Yii::$app->projectcls->SalaryPercentageToday(base64_decode($p['employee_code'])) .'%';                                    
                                } else {
                                    echo '-';
                                }                                
                            ?>
                        </td>                        
                        <td><a href="<?=$editUrl.'&view=1';?>" class="linkcolor">View</a></td>                        
                    </tr>	
                    <?php $i++;	
                        }
                    }
                    ?>
                </tbody>
            </table>
        
        
    </div>
</div>