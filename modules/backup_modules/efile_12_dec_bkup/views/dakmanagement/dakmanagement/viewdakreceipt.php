<?php 
$curdate=date("d-m-Y");
$DAKREC= Yii::$app->utility->encryptString("DAKREC");
$menuid = "";
if(isset($_GET['securekey']) AND !empty($_GET['securekey']))
{
    $menuid = $_GET['securekey'];
    $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
    $menuid = Yii::$app->utility->encryptString($menuid);
}
?>
<h5 class="text-danger"  style="text-align:center"><b>View Dak Received</b></h5>
<hr>
<div class="row">
    <table id="viewrecdata" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Sr. No.</th>
                <th>Dak Number</th>
                <th>Receive Date</th>
                <th>Receive From</th>
                <th>Dak Forward Dept</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
                <?php 
//                echo "<pre>";print_r($dak_received);die;
                if(!empty($dak_received))
                {
                    $i =1;
                    foreach($dak_received as $k=>$fd)
                    { 
                        $rec_id =Yii::$app->utility->encryptString($fd['rec_id']);
                        $rec_date=date("d-M-Y",strtotime($fd['rec_date']));
                        $disp_from_dept= Yii::$app->utility->get_dept($fd['dak_fwd_dept']);
                        $disp_from_dept=$disp_from_dept["dept_name"];
                        $url=Yii::$app->homeUrl."efile/dakmanagement/viewrecdetail?securekey=".$menuid."&rec_id=".$rec_id;
                        $verify_link ="<a href='$url' class='btn btn-sm btn-danger'>View</a>"
                ?>
                <tr>
                    <td><?=$i?></td>
                    <td><?=ucwords($fd['dak_number'])?></td>
                    <td><?=ucwords($rec_date)?></td>
                    <td><?=ucwords($fd['rec_from'])?></td>
                    <td><?=ucwords($disp_from_dept)?></td>
                    <td><?=$fd['status']?></td>
                    <td><?=$verify_link?></td>
                </tr>	
                <?php $i++;	
                    }
                }
                ?>
            </tbody>
        <tfoot>
            <tr>
                <th>Sr. No.</th>
                <th>Dak Number</th>
                <th>Receive Date</th>
                <th>Receive From</th>
                <th>Dak Forward Dept</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </tfoot>
    </table>
 </div>