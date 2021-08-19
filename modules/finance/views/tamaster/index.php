<?php
$this->title ="T.A. Master";
?>
<table id="dataTableShow" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Sr.</th>
            <th>Financial Year</th>
            <th>Month-Year</th>
            <th>City Class</th>
            <th>TA Percentage</th>
            <th>Effected From </th>
            <th>Added On</th>
        </tr>
    </thead>
    <tbody>
    <?php 
    if(!empty($taLists)){
        $i=1;
        foreach($taLists as $taList){
            $month_year = date('M-Y', strtotime($taList['month_year']));
            echo "<tr>
                <td>$i</td>
                <td>".$taList['financial_year']."</td>
                <td>".date('M-Y', strtotime("01"-$taList['month_year']))."</td>
                <td>".$taList['ta_city_class']." Class</td>
                <td>".$taList['ta_amount']."</td>
                <td>".date('d-M-Y', strtotime($taList['effected_from']))."</td>
                <td>".date('d-M-Y', strtotime($taList['created_date']))."</td>
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
            <th>City Class</th>
            <th>TA Percentage</th>
            <th>Effected From </th>
            <th>Added On</th>
        </tr>
    </tfoot>
</table>