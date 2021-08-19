<?php
$this->title ="I.T. Slab Master";
?>
<table id="dataTableShow" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Sr.</th>
            <th>Financial Year</th>
            <th>Effected From </th>
            <th>Amount Between</th>
            <th>Tax Percentage</th>
            <th>Save upto</th>
            <th>Added On</th>
        </tr>
    </thead>
    <tbody>
    <?php 
    if(!empty($slabLists)){
        $i=1;
        foreach($slabLists as $slabList){
            echo "<tr>
                <td>$i</td>
                <td>".$slabList['financial_year']."</td>
                    <td>".date('d-M-Y', strtotime($slabList['effected_from']))."</td>
                <td>".$slabList['amt_between']."</td>
                <td>".$slabList['tax_percentage']."</td>
                <td>".$slabList['saving_amt_upto']."</td>
                <td>".date('d-M-Y', strtotime($slabList['created_on']))."</td>
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
            <th>Effected From </th>
            <th>Amount Between</th>
            <th>Tax Percentage</th>
            <th>Save upto</th>
            <th>Added On</th>
        </tr>
    </tfoot>
</table>