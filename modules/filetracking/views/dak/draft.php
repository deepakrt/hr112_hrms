<?php
$this->title = "Draft Dak";
//echo "<pre>";print_r($draftDaks);
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
            <th>Added On</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php 
        if(!empty($draftDaks)){
            $i=1;
            foreach($draftDaks as $draft){ 
                $dak_id = Yii::$app->utility->encryptString($draft['dak_id']);
                $editUrl = Yii::$app->homeUrl."filetracking/dak/editdraft?securekey=$menuid&dak_id=$dak_id";
                $editUrl = "<a href='$editUrl' class='linkcolor'>Edit Dak</a>";
                $access_level = "";
                if($draft['access_level'] == 'R'){
                    $access_level = "Read Only";
                }elseif($draft['access_level'] == 'W'){
                    $access_level = "Read / Write Only";
                }
            ?>
        <tr>
            <td><?=$i?></td>
            <td><?=$draft['subject']?></td>
            <td><?=$draft['file_refrence_no']?></td>
            <td><?=date('d-m-Y', strtotime($draft['file_date']))?></td>
            <td><?=$draft['cat_name']?></td>
            <td><?=$access_level?></td>
            <td><?=date('d-m-Y H:i:s', strtotime($draft['created_date']))?></td>
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
            <th>Added On</th>
            <th></th>
        </tr>
    </tfoot>
</table>
