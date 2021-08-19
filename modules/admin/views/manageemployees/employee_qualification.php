<script>
$(document).ready(function() {
    $('#qualificationtable').DataTable( {
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
    } );
} );
</script>
<div class="row">
    <div class="col-sm-6"><h3>Qualification:</h3></div>
    <div class="col-sm-6"></div>
</div>
<div class="row">
    <table id="qualificationtable" class="display" style="width:100%">
	<thead>
            <tr>
            <th>Sr.</th>
            <th>Qualifications</th>
            <th>Institute</th>
             <th>University/Board</th>
            <th>Docs</th>
            <th>Status</th>
            </tr>
	</thead>
	<tbody>
            <?php 
            if(!empty($qualification)){$i =1;
                foreach($qualification as $q){ 
                $qualifi = ucwords($q['qualification_level'])." ".ucwords($q['discipline']);
                $Institute = $q['Institute'];
                $view_doc = '<a target="_blank" style="text-decoration: underline;" href="'.Yii::$app->request->baseUrl.$q['docs'].'">View</a>';
                if($q['status'] == 'Verified'){
                        $class="btn-success";
                }elseif($q['status'] == 'Unverified'){
                        $class="btn-danger";
                }
                $encry = base64_encode($q['e_id']);
                $eq_id = base64_encode($q['eq_id']);
                if($q['status']=='Verified'){$status=1;}else{$status=0;}
$verify_link = Yii::$app->homeUrl."admin/manageemployees/verifydocs?key=$encry&type=$eq_id&status=$status&tab=qualification";

                ?>
                <tr>
                <td><?=$i?></td>
                <td><?=$qualifi?></td>
                <td><?=$Institute?></td>
                <td><?=$q['univ_board']?></td>
                <td><?=$view_doc?></td>
                <td style="padding: 0;">
                  <a href="<?=$verify_link?>" class="btn <?=$class?> btn-sm btn-xs"><?=$q['status']?></a>
                </td>
                </tr>	
            <?php $i++;	}
            }
            ?>
	</tbody>
	<tfoot>
            <th>Sr.</th>
            <th>Qualifications</th>
            <th>Institute</th> <th>University/Board</th>
            <th>Docs</th>
            <th>Status</th>
	</tfoot>
    </table>
 </div>
