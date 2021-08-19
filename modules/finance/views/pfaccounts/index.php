<?php
$this->title = "Provident Fund Accounts";
//echo "<pre>";print_r($pfacs);
?>
<input type="hidden" id="menuid" value="<?=$menuid?>" />
<table id="dataTableShow" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Sr.</th>
            <th>Emp Code</th>
            <th>Name</th>
            <th>PAN Number</th>
            <th>UAN Number</th>
            <th>PF Number</th>
            <!--<th>FPF Account</th>-->
            <th>Edit</th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php 
    if(!empty($pfacs)){
        $i=1;
        foreach($pfacs as $pf){
            $pfid = Yii::$app->utility->encryptString($pf['pfid']);
            $ec = Yii::$app->utility->encryptString($pf['employee_code']);
            $edit = Yii::$app->homeUrl."finance/pfaccounts/editaccountdetails?securekey=$menuid&key=$pfid&key1=$ec";
            $view = Yii::$app->homeUrl."finance/pfaccounts/editaccountdetails?securekey=$menuid&key=$pfid&key1=$ec";
//            <td>".$pf['fpf_account']."</td>
            echo "<tr>
                <td>$i</td>
                <td>".$pf['employee_code']."</td>
                <td>".$pf['fullname']."</td>
                <td>".$pf['pan_number']."</td>
                <td>".$pf['uan_number']."</td>
                <td>".$pf['pf_number']."</td>
                
                <td><a href='$edit' title='Edit Provident Fund Account Details'><img src='".Yii::$app->homeUrl."images/edit.gif' /></a></td>
                <td><a href='$view' title='View Provident Fund Account Details' class='linkcolor'>View</a></td>
                <td><a href='javascript:void(0)'  data-key='$ec' title='View Provident Fund' class='linkcolor pfshow'>View PF</a></td>
                </tr>";
            $i++;
        }
    }
    ?>
    </tbody>
    <tfoot>
        <tr>
            <th>Sr.</th>
            <th>Emp Code</th>
            <th>Name</th>
            <th>PAN Number</th>
            <th>UAN Number</th>
            <th>PF Number</th>
            <!--<th>FPF Account</th>-->
            <th>Edit</th>
            <th></th>
            <th></th>
        </tr>
    </tfoot>
</table>


<div class="modal fade" id="modelPFview" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Provident Fund Summary</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="modelPFview_html"></div>
            </div>
            
        </div>
    </div>
</div>