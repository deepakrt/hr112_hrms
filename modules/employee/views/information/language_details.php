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
    $('#languagetable').DataTable( {
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
    } );
} );
</script>
<div class="row">
    <div class="col-sm-6"><h3>Language Details</h3></div>
    <div class="col-sm-6 text-right">
        <a href="<?=Yii::$app->homeUrl?>employee/information/addlanguage_known?securekey=<?=$menuid?>" class="btn btn-primary btn-sm">Add/Edit</a>
    </div>
</div>
<div class="row">
    <table id="languagetable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Sr.</th>
                <th>Language name</th>
                <th>Mother-Tongue</th>
                <th>Can Read</th>
                <th>Can Write</th>
                <th>Can Speak</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php 
//                echo "<pre>";print_r($emp_language_details);die;
            if(!empty($emp_language_details))
            {
                $i =1;
                foreach($emp_language_details as $fd)
                { 
                
                ?>
                <tr>
                    <td><?=$i?></td>
                    <td><?=ucwords($fd['language_name'])?></td>
                    <td><?=$fd['mother_tongue']?></td>
                    <td><?=$fd['read']?></td>
                    <td><?=$fd['write']?></td>
                    <td><?=$fd['speak']?></td>
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
                <th>Language name</th>
                <th>Mother-Tongue</th>
                <th>Can Read</th>
                <th>Can Write</th>
                <th>Can Speak</th>
                <th>Status</th>
            </tr>
        </tfoot>
    </table>
 </div>
