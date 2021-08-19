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
    $('#familytable').DataTable( {
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
    } );
} );
</script>
<div class="row">
    <div class="col-sm-6"><h3>Family Details</h3></div>
    <div class="col-sm-6 text-right">
        <a href="<?=Yii::$app->homeUrl?>employee/information/addmember?securekey=<?=$menuid?>" class="btn btn-primary btn-sm">Add</a>
    </div>
</div>
<div class="row">
    <table id="familytable" class="display" style="width:100%">
        <thead>
            <tr>
            <th>Sr.</th>
            <th>Member Name</th>
            <th>Relation</th>
            <th>Date of Birth</th>
            <th>Marital Status</th>
            <th>Document</th>
            <th>Status</th>
            </tr>
        </thead>
            <tbody>
                <?php 
//                echo "<pre>";print_r($family_details);die;
                if(!empty($family_details)){$i =1;
                    foreach($family_details as $fd){ 

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
                    $encry = base64_encode($fd['employee_code']);
                    $ef_id = base64_encode($fd['ef_id']);
                    if($fd['status']=='Verified'){$status=1;}else{$status=0;}
    $verify_link = Yii::$app->homeUrl."admin/manageemployees/verify_fmember?key=$encry&type=$ef_id&status=$status&tab=qualification";

                ?>
                <tr>
                    <td><?=$i?></td>
                    <td><?=ucwords($fd['m_name'])?></td>
                    <td><?=$fd['relation_name']?></td>
                    <td><?=$fd['m_dob']?></td>
                    <td><?=$fd['marital_status']?></td>
                    <td><?=$doc?></td>
                    <td style="padding: 0;">
                      <a href="javascript:void(0);" class="btn  btn-sm btn-xs"><?=$fd['status']?></a>
                    </td>
                </tr>	
                <?php $i++;	}
                }
                ?>
            </tbody>
            <tfoot>
                    <tr>
                    <th>Sr.</th>
                    <th>Member Name</th>
                    <th>Relation</th>
                    <th>Date of Birth</th>
                    <th>Marital Status</th>
                    <th>Document</th>
                    <th>Status</th>
                    </tr>
            </tfoot>
    </table>
 </div>
