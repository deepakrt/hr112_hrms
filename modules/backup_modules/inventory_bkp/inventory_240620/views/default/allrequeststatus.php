<!------ Include the above in your HEAD tag ---------->
<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
//echo "<pre>";print_r(Yii::$app->user->identity);die;
?>

                   
               <div class="col-sm-12">
    <table id="dataTableShow" class="display" style="width:100%">
        <thead>
            <tr>
               
                <th>Voucher No</th>
                <th>Employee</th>
                <th>Item Category</th>
                <th>Item</th>
                <th>Qty Required</th>
                <th>Qty Approved</th>
                <th>Status</th>
                
            </tr>
        </thead>
        <tbody>
            <?php 
            if(!empty($data)){
            foreach($data as $k=>$c){ ?>
            <tr>
                
                <td id="Voucher_No"><?=$c['Voucher_No']?></td>
                <td id="Employee"><?=$c['fname']?></td>
                <td id="ITEM_CAT_NAME"><?=$c['ITEM_CAT_NAME'].'('.$c['Item_Type'].')';?></td>
                <td id="item_name"><?=$c['item_name'].'('.$c['Measuring_Unit'].')';?></td>
                <td id="Quantity_Required"><?=$c['Quantity_Required']?></td>
                <td id="Qty_Approved"><?=$c['Qty_Approved']?></td>
                <td id="Status"><?=$c['Status']?></td>
                
            </tr>   
             <?php } } ?>
        </tbody>
        
    </table>
</div>