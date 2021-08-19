<?php
$this->title ="DA Master";
?>
<div class="text-right">
    <a href="<?=Yii::$app->homeUrl?>finance/damaster/addda?securekey=<?=$menuid?>" class="btn btn-success btn-sm mybtn">Add New Entry</a>
</div>
<hr>
<table id="dataTableShow" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Sr.</th>
            <th>Financial Year</th>
            <th>Month-Year</th>
            <th>DA Percentage</th>
            <th>Effected From </th>
            <th>Added On</th>
        </tr>
    </thead>
    <tbody>
    <?php 
//    echo "<pre>";print_r($allEmps);
    if(!empty($daLists)){
        $i=1;
        foreach($daLists as $daList){
            $month_year = date('M-Y', strtotime($daList['month_year']));
            echo "<tr>
                <td>$i</td>
                <td>".$daList['financial_year']."</td>
                <td>".date('M-Y', strtotime("01"-$daList['month_year']))."</td>
                <td>".$daList['da_percentage']."</td>
                <td>".date('d-M-Y', strtotime($daList['effected_from']))."</td>
                <td>".date('d-M-Y', strtotime($daList['created_date']))."</td>
                </tr>";
            $i++;
        }
    }
    ?>
    </tbody>
    <tfoot>
        <tr>
            <th>Sr.</th>
            <th>Financial Year</th>
            <th>Month-Year</th>
            <th>DA Percentage</th>
            <th>Effected From </th>
            <th>Added On</th>
        </tr>
    </tfoot>
</table>