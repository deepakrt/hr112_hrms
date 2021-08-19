<?php
$this->title ="HRA Master";
?>
<table id="dataTableShow" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Sr.</th>
            <th>Financial Year</th>
            <th>HRA Percentage</th>
            <th>Effected From </th>
            <th>Added On</th>
        </tr>
    </thead>
    <tbody>
    <?php 
    if(!empty($hraLists)){
        $i=1;
        foreach($hraLists as $hraList){
            echo "<tr>
                <td>$i</td>
                <td>".$hraList['financial_year']."</td>
                <td>".$hraList['hra_percentage']."</td>
                <td>".date('d-M-Y', strtotime($hraList['effected_from']))."</td>
                <td>".date('d-M-Y', strtotime($hraList['created_date']))."</td>
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
            <th>HRA Percentage</th>
            <th>Effected From </th>
            <th>Added On</th>
        </tr>
    </tfoot>
</table>