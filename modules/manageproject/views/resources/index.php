<?php
$this->title = "Resources";
?>
<div class="row">
    <div class="col-sm-6 text-left"><h5><b>List of All Projects</b></h5></div>
    <div class="col-sm-12">
        <hr>
        <table id="dataTableShow" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>Project Name</th>
                    <th>Project Type</th>
                    <th>Project Cost</th>
                    <th>Status</th>
                    <th></th>
                    <th></th>
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
//                            $status = Yii::$app->utility->encryptString($p['status']);
                            $addUrl = Yii::$app->homeUrl."manageproject/resources/addresources?securekey=$menuid&key=$Prid";
                            $viewUrl = Yii::$app->homeUrl."manageproject/resources/viewresources?securekey=$menuid&key=$Prid";
                    ?>
                    <tr>
                        <td><?=$i?></td>
                        <td><?=$p['project_name']?></td>
                        <td><?=$p['project_type']?></td>
                        <td><?=$p['project_cost']?></td>
                        <td><?=$p['status']?></td>
                        <td><a href="<?=$viewUrl?>" title="View Project Resources" class="linkcolor">View Resources</a></td>
                        <td><a href="<?=$addUrl?>" class="linkcolor" title="Add Project Resources">Add</a></td>
                    </tr>	
                    <?php $i++;	
                        }
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Sr. No.</th>
                        <th>Project Name</th>
                        <th>Project Type</th>
                        <th>Project Cost</th>
                        <th>Status</th>
                        <th></th>
                        <th></th>
                    </tr>
                </tfoot>
        </table>
    </div>
</div>


