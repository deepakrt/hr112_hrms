<?php
$this->title="Tour Requisition";
?>
<div class="col-sm-12">
        <hr>
        <h6><b><i>All Requisitions</i></b></h6>
        <table id="dataTableShow" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Tour Location</th>
                    <th>Dept</th>
                    <th>Project</th>
                    <th>Type</th>
                    <th>Advance <br>Required</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
                <tbody>
                    <?php 
//                    echo "<pre>";print_r($lists);
                    if(!empty($lists)){
                        $i =1;
                        foreach($lists as $list){
                            $req_id = Yii::$app->utility->encryptString($list['req_id']);
                            $durl = Yii::$app->homeUrl."employee/claim/downloadtr?securekey=$menuid&key=$req_id";
                            $durl = "<a href='$durl' target='_blank'><img width='20' src='".Yii::$app->homeUrl."images/pdf.png' /></a>";
                    ?>
                    <tr>
                        <td><?=$i?></td>
                        <td><?=date('d-M-y', strtotime($list['start_date']))?></td>
                        <td><?=date('d-M-y', strtotime($list['end_date']))?></td>
                        <td><?=$list['city_name']?></td>
                        <td><?=$list['dept_name']?></td>
                        <td><?=$list['project_name']?></td>
                        <td><?=$list['tour_type']?></td>
                        <td>Rs. <?=$list['advance_amount']?></td>
                        <td><?=$list['status']?></td>
                        <td><?=$durl?></td>
                    </tr>	
                    <?php $i++;	
                        }
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>No.</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Tour Location</th>
                        <th>Dept</th>
                        <th>Project</th>
                        <th>Type</th>
                        <th>Advance</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </tfoot>
        </table>
    </div>