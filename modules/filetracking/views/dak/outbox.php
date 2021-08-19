<?php
$this->title = "Outbox";
?>
<table id="dataTableShow" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Sr.</th>
            <th>Subject</th>
            <th>File Reference No.</th>
            <th>File Date</th>
            <th>Category</th>
            <th>Access Level</th>
            <th>Sent On</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php 
        if(!empty($draftDaks)){
            $i=1;
            foreach($draftDaks as $draft){ 
                $dak_id = Yii::$app->utility->encryptString($draft['dak_id']);
                $editUrl = Yii::$app->homeUrl."filetracking/dak/viewoutboxdak?securekey=$menuid&dak_id=$dak_id";
                $editUrl = "<a href='$editUrl' class='linkcolor'>View Dak</a>";
                $access_level = "";
                if($draft['access_level'] == 'R'){
                    $access_level = "Read Only";
                }elseif($draft['access_level'] == 'W'){
                    $access_level = "Read / Write Only";
                }
                $senton = date('d-m-Y H:i:s', strtotime($draft['created_date']));
                if(!empty($draft['modified_date'])){
                    $senton = date('d-m-Y  H:i:s', strtotime($draft['modified_date']));
                }
            ?>
        <tr>
            <td><?=$i?></td>
            <td><?=$draft['subject']?></td>
            <td><?=$draft['file_refrence_no']?></td>
            <td><?=date('d-m-Y', strtotime($draft['file_date']))?></td>
            <td><?=$draft['cat_name']?></td>
            <td><?=$access_level?></td>
            <td><?=$senton?></td>
            <td><?=$editUrl?></td>
        </tr>
        <?php   $i++; }
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <th>Sr.</th>
            <th>Subject</th>
            <th>File Reference No.</th>
            <th>File Date</th>
            <th>Category</th>
            <th>Access Level</th>
            <th>Sent On</th>
            <th></th>
        </tr>
    </tfoot>
</table>

