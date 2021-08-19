<h5 class="text-danger"  style="text-align:center"><b>प्राप्त डाक / Dak Received</b></h5>
<hr>
<div class="row">
    <table id="viewrecdata" class="display" style="width:100%">
        <thead>
            <tr class="text-center">
                <th>Sr. No.</th>
                <th>Receipt Number & Date</th>
                
                <th>Received From</th>
                <th>Dak Forward To</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
                <?php 
                //echo "<pre>";print_r($dak_received);die;
                if(!empty($dak_received))
                {
                    $i =1;
                    foreach($dak_received as $k=>$fd)
                    { 
                        $rec_id =Yii::$app->utility->encryptString($fd['rec_id']);
                        $rec_date=date("d-M-Y",strtotime($fd['rec_date']));
                        $url=Yii::$app->homeUrl."efile/dakmanagement/viewrecdetail?securekey=".$menuid."&rec_id=".$rec_id;
                        $verify_link ="<a href='$url' class='btn btn-sm btn-danger btn-xs'>View</a>"
                ?>
                <tr>
                    <td class="text-center"><?=$i?></td>
                    <td><?=ucwords($fd['dak_number'])?> Date <?=$rec_date?></td>
                    <td><?=ucwords($fd['rec_from'])?></td>
                    <td><?=ucwords($fd['Dak_fdw_to'])?></td>
                    <td><?=$fd['status']?></td>
                    <td><?=$verify_link?></td>
                </tr>	
                <?php $i++;	
                    }
                }
                ?>
            </tbody>
    </table>
 </div>