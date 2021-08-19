<?php
$this->title = "Children Education Allowance Requests";
?>
<div class="row">
    <div class="col-sm-12">
        <table id="dataTableShow" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>Sr.</th>
                    <th>Employee Name</th>
                    <th>Department</th>
                    <th>Claim Type</th>
                    <th>Child Name</th>  
                    <th>Total Claimed</th>  
                    <th>App. Dated</th>  
                    <th></th>            
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($cea_reqs)){
                    $i=1;
                    foreach($cea_reqs as $app){
                       $ea_id = Yii::$app->utility->encryptString($app['ea_id']);
                       $ec = Yii::$app->utility->encryptString($app['employee_code']);
                       $fy = Yii::$app->utility->encryptString($app['financial_year']);
                       $empInfo = Yii::$app->utility->get_employees($app['employee_code']);
                       $fullname = $empInfo['fullname'].", ".$empInfo['desg_name'];
                       $dept_name = $empInfo['dept_name'];
                       $claimtype =  Yii::$app->utility->getclaimtypename($app['claim_type']);
                       $toalclaimed = "";
                       if($app['claim_type'] == 'CEA'){
                           $toalclaimed = $app['books_amount']+$app['shoes_amount']+$app['notebooks_amount']+$app['uniform_amount']+$app['tuition_fees'];
                           $toalclaimed = number_format($toalclaimed, 2);
                       }elseif($app['claim_type'] == 'HS'){
                           $toalclaimed = $app['hostel_fees'];
                       }
                       $childName = "(".$app['relation_name'].") ".$app['m_name'];
                       $appdate=date('d-M-Y', strtotime($app['created_date']));
                       $viewUrl = Yii::$app->homeUrl."hr/requestcea/viewapp?securekey=$menuid&ea_id=$ea_id&ec=$ec&fy=$fy";
                       ?>

                <tr>
                    <td><?=$i?></td>
                    <td><?=$fullname?></td>
                    <td><?=$dept_name?></td>
                    <td><?=$claimtype?></td>
                    <td><?=$childName?></td>
                    <td align='right'><?=$toalclaimed?></td>
                    <td><?=$appdate?></td>
                    <td><a href="<?=$viewUrl?>" class="linkcolor">View & Action</a></td>
                </tr>
                <?php
                       $i++;

                   } 
                }?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Sr.</th>
                    <th>Employee Name</th>
                    <th>Department</th>
                    <th>Claim Type</th>
                    <th>Child Name</th>  
                    <th>Total Claimed</th>  
                    <th>App. Dated</th>  
                    <th></th>            
                </tr>
            </tfoot>
        </table>
    </div>
    
</div>
