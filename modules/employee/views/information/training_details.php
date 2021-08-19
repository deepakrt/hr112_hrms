<?php 
$menuid = "";
if(isset($_GET['securekey']) AND !empty($_GET['securekey'])){
    $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
}
if(empty($menuid)){
    header('Location: '.Yii::$app->homeUrl); 
    exit;
}
$menuid = Yii::$app->utility->encryptString($menuid);
?>
<script>
$(document).ready(function() {
    $('#training_table').DataTable( {
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
    } );
} );
</script>
<div class="row">
    <div class="col-sm-6"><h3>Training Details</h3></div>

    <?php 
        $bkurl=Yii::$app->homeUrl."employee/information?securekey=$menuid&tab=training_det";
    ?>

    <div class="col-sm-6 text-right">
        <a href="<?=Yii::$app->homeUrl?>employee/information/addtraining_details?securekey=<?=$menuid?>" class="btn btn-primary btn-sm">Add</a>
    </div>
</div>
<div class="row">
    <table id="training_table" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Sr.</th>
                <th>Course Name</th>
                <th>Institute Name</th>
                <th>Ins. Address</th>
                <th>Training Attended</th>
                <th>From</th>
                <th>To</th>
                <th>Description</th>
                <th>Doc. Type</th>
                <th>Document</th>
                <th>Status</th>
                <!-- <th>Edit</th> -->
                <!-- <th>Delete</th> -->
            </tr>
        </thead>
        <tbody>
            <?php 
               // echo "<pre>";print_r($training_details);die;
            if(!empty($training_details))
            {
                $i =1;
                foreach($training_details as $fd)
                {
                    /*echo "<pre>";
                     print_r($fd);
                    echo "</pre>";*/

                    if(empty($fd['document_path'])){
                            $doc="Not Uploaded";
                    }else{
                        $doc = '<a target="_blank" style="text-decoration: underline;" href="'.Yii::$app->request->baseUrl.$fd['document_path'].'">View</a>';
                    }
                    if($fd['status'] == 'Verified'){
                        $class="btn-success";
                    }elseif($fd['status'] == 'Unverified'){
                        $class="btn-danger";
                    }

                    /*
                        $encry = base64_encode($fd['employee_code']);
                        $ef_id = base64_encode($fd['ef_id']);
                        if($fd['status']=='Verified'){$status=1;}else{$status=0;}
                         $verify_link = Yii::$app->homeUrl."admin/manageemployees/verify_fmember?key=$encry&type=$ef_id&status=$status&tab=qualification";*/

                ?>
                <tr>
                    <td><?=$i?></td>
                    <!-- <td><?php // $fd['employee_id']?></td> -->
                    <td><?=ucwords($fd['course_name'])?></td>
                    <td><?=$fd['institute_name']?></td>
                    <td><?=$fd['institute_address']?></td>
                    <td><?=$fd['training_attended']?></td>
                    <td><?=date('dS-M, Y', strtotime($fd['from_date']));?></td>
                    <td><?=date('dS-M, Y', strtotime($fd['to_date']));?></td>
                    <td><?=$fd['description']?></td>
                    <td><?=$fd['document_type']?></td>
                    <td><?=$doc?></td>
                    <td style="padding: 0;">
                      <a href="javascript:void(0);" class="btn  btn-sm btn-xs"><?=$fd['status']?></a>
                    </td>
                </tr>	
                <?php 
                    
                    $i++;	
                }
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th>Sr.</th>
                <th>Course Name</th>
                <th>Institute Name</th>
                <th>Ins. Address</th>
                <th>Training Attended</th>
                <th>From</th>
                <th>To</th>
                <th>Description</th>
                <th>Doc. Type</th>
                <th>Document</th>
                <th>Status</th>
                <!-- <th>Edit</th> -->
                <!-- <th>Delete</th> -->
            </tr>
        </tfoot>
    </table>
 </div>
