<?php
$homeUrl = Yii::$app->homeUrl;
?>


<style>
    /*#table-wrapper {
      position:relative;
    }*/
    .table-scroll {
      height:700px;
      overflow:auto;  
      margin-top:20px;
    }

    #subjectTable_wrapper table {
      width:100%;
      margin-top: 10px;
    }
    .statusClass
    {
        font-weight: bold;
    }
    .excel-btn
    {
      margin-bottom: 10px;  
      padding-left: 0px;  }

   
</style>

<script type="text/javascript" language="javascript" src="<?=Yii::$app->homeUrl?>/js/forexcel/jszip.js"></script>
<script type="text/javascript" language="javascript" src="<?=Yii::$app->homeUrl?>/js/forexcel/buttons.js"></script>


<div class="dataTables_wrapper no-footer table-scroll trngdata" id="subjectTable_wrapper">
    <div class="col-md-6 excel-btn">
    </div>    
    <table id="studentView" class="display adminlist" cellspacing="0" width="100%">
        <thead>
            <tr class="headrow">
                <th>#</th>
                <th>Supplier</th>
                <th>Qty.</th>
                <th>Amount</th>
               
            </tr>
        </thead>
        <tbody class="list">
        <?php
            $i=1;

            /*echo "<pre>"; print_r($item_data);
            die();*/

            foreach($item_data as $itemDky=>$itemData)
            {
             $Supplier = $itemData['Supplier_name'];
             $qty = $itemData['Qty'];
             $Amount = $itemData['amount'];
             
        ?>
          <tr>
            <td class=""><?=$i;?></td>
             <td class=""><?=$Supplier;?></td>              
               <td class=""><?=$qty;?></td>
               <td class=""><?=$Amount;?></td>
          
            
         </tr>
    <?php
         $i++;
         }
    ?>
    </table>
</div>

<script>
    $(document).ready(function() {
        var table = $('#studentView').DataTable( {
            lengthChange: true,
 

            buttons: [ 'copy', 'excel', 'print' ]
        } );

        table.buttons().container()
            .appendTo( '#subjectTable_wrapper  .col-md-6:eq(0)' );
    } );
    $('#exampleModalCenter').modal({backdrop: 'static', keyboard: false});

</script>