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
    }
    .statusClass
    {
        font-weight: bold;
    }

   
</style>

<script type="text/javascript" language="javascript" src="<?=Yii::$app->homeUrl?>/js/forexcel/jszip.js"></script>
<script type="text/javascript" language="javascript" src="<?=Yii::$app->homeUrl?>/js/forexcel/buttons.js"></script>


<div class="dataTables_wrapper no-footer table-scroll trngdata" id="subjectTable_wrapper">
    <div class="col-md-6">
    </div>    
    <table id="studentView" class="display adminlist" cellspacing="0" width="100%">
        <thead>
            <tr class="headrow">
                <th>#</th>
                <!-- <th>Item Code</th> -->
                <th>Item Unique Id</th>
               <!--  <th>Item Barcode</th> -->
                <th>Item Sr. No</th>
                <th>Item Model No</th>
                <th>Issued Status</th>
                <th>ID-Employee Name (Rank)</th>
                <th>Issued Voucher No</th>
                <th>Issued Date</th>
            </tr>
        </thead>
        <tbody class="list">
        <?php
            $i=1;

            /*echo "<pre>"; print_r($item_data);
            die();*/

            foreach($item_data as $itemDky=>$itemData)
            {
             $item_code = $itemData['item_code'];
             $item_unique_id = $itemData['item_unique_id'];
             // $item_barcode = $itemData['item_barcode'];
             $item_Sr_no = $itemData['item_Sr_no'];
             $item_model_no = $itemData['item_model_no'];
             $issued_status = $itemData['issued_status'];
             $emp_name = $itemData['emp_name'];
             $issue_voucher_no = $itemData['issue_voucher_no'];

             $approval_Date = '';

             if($issued_status == 'Y')
             {
                $approval_Date = date('d-m-Y', strtotime($itemData['Approval_Date']));
                $issued_status = 'ISSUED';
             }
             else
             {
                $issued_status = 'NOT ISSUED';
             }
        ?>
          <tr>
            <td class=""><?=$i;?></td>
            <!-- <td class=""><?=$item_code;?></td> -->
            <td class=""><?=$item_unique_id ;?></td>
          
            <td class=""><?=$item_Sr_no;?></td>
            <td class=""><?=$item_model_no;?></td>
            <td class=""><?=$issued_status;?></td>
            <td class=""><?=$emp_name;?></td>
            <td class=""><?=$issue_voucher_no;?></td>
            <td class=""><?=$approval_Date;?></td>
            
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
            .appendTo( '#subjectTable_wrapper .col-md-6:eq(0)' );
    } );
    $('#exampleModalCenter').modal({backdrop: 'static', keyboard: false});

</script>