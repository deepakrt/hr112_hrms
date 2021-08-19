<?php
$this->title="Inbox";
//echo "<pre>"; print_r($inboxDaks);
?>
<table id="dataTableShow" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Sr.</th>
            <th>From</th>
            <th>Subject</th>
            <th>Priority</th>
            <th>Access Level</th>
            <th>Sent On</th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php 
        if(!empty($inboxDaks)){
            $i=1;
            foreach($inboxDaks as $inbox){
                $dak_id = Yii::$app->utility->encryptString($inbox['dak_id']);
                $viewUrl = Yii::$app->homeUrl."filetracking/dak/viewinboxdak?securekey=$menuid&dak_id=$dak_id";
                $viewUrl = "<a href='$viewUrl' class='linkcolor'>View Dak</a>";
                $downloadUrl = Yii::$app->homeUrl."filetracking/dak/downloaddak?securekey=$menuid&dak_id=$dak_id";
                $downloadUrl = "<a href='$downloadUrl' class='linkcolor'>Download Dak</a>";
                $access_level = "";
                if($inbox['access_level'] == 'R'){
                    $access_level = "Read Only";
                }elseif($inbox['access_level'] == 'W'){
                    $access_level = "Read / Write";
                }
                $senton = date('d-m-Y H:i:s', strtotime($inbox['sent_date']));
                $pri="";
                if($inbox['priority'] == 'High'){
                    $pri="style='background:red;color:#fff';text-align:center;";
                }elseif($inbox['priority'] == 'Moderate'){
                    $pri="style='background:#FBE7C3;color:#000';text-align:center;";
                }
            ?>
        <tr>
            <td><?=$i?></td>
            <td><?=$inbox['sentfrom']?> (<?=$inbox['dept_name']?>)</td>
            <td><?=$inbox['subject']?></td>
            <td <?=$pri?>><?=$inbox['priority']?></td>
            <td><?=$access_level?></td>
            <td><?=$senton?></td>
            <td><?=$viewUrl?></td>
            <td><?=$downloadUrl?></td>
        </tr>
        <?php   $i++; }
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <th>Sr.</th>
            <th>From</th>
            <th>Subject</th>
            <th>Priority</th>
            <th>Access Level</th>
            <th>Sent On</th>
            <th></th>
            <th></th>
        </tr>
    </tfoot>
</table>

