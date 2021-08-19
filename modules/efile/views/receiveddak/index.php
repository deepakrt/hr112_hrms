<?php
$this->title="प्राप्त डाक / Received Dak";
?>

<!--<div class='text-center'>
	<button type='button' class='btn btn-success btn-sm' id='newdaks' onclick='recieveddak("N")'>New Daks</button><h6 class='text-center'><b><u>New Daks</u></b></h6>
	<button type='button' class='btn btn-secondary btn-sm' id='previous_daks'  onclick='recieveddak("P")'>Previous Daks</button>
</div>-->
<hr class='hrline'>
<div id='new_daks_html'>
    <table id="dataTableShow" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Sr.</th>
                <th class="text-center">Received From</th>
                <th>Receipt No. & Date</th>
                <th>Forward On</th>
                <th>Receipt Mode</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php 
        if(!empty($lists)){
            $i=1;
            
            foreach($lists as $l){
                $check = Yii::$app->fts_utility->efile_get_dak(NULL, $l['rec_id'], NULL, NULL);
                if(empty($check)){
                    $dist = Yii::$app->fts_utility->get_master_districts($l['org_district'], NULL);
                    $address = $l['org_address'];
                    if(!empty($dist)){
                        $district_name = ucwords(strtolower($dist['district_name']));
                        $state_name = ucwords(strtolower($dist['state_name']));
                        $address .= "<br>Distt. $district_name, $state_name";
                    }
                    $dak_number = $l['dak_number']." <br>Date ".date('d-m-Y', strtotime($l['rec_date']));
                    $rec_id = Yii::$app->utility->encryptString($l['rec_id']);
                    $viewbtn = Yii::$app->homeUrl."efile/receiveddak/viewrecieveddak?securekey=$menuid&key=$rec_id";
                    $viewbtn = "<a href='$viewbtn' class='btn btn-success btn-xs'>Click to Enter Receive Dak Details</a>";
                    echo "<tr>
                            <td>$i</td>
                            <td>$l[rec_from], $address</td>
                            <td>$dak_number</td>
                            <td>".date('d-m-Y', strtotime($l['forwarded_date']))."</td>
                            <td>".$l['mode_of_rec']."</td>
                            <td>$viewbtn</td>
                    </tr>";
                    $i++;
                }
            }
        }
        ?>

        </tbody>
    </table>
</div>
<!--<div id='previous_daks_html'>
	<h6 class='text-center'><b><u>Previous Daks</u></b></h6>
</div>-->


