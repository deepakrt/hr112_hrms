<?php 
$curdate=date("d-m-Y");
$DISPATCH= Yii::$app->utility->encryptString("DISPATCH");
$menuid = "";
if(isset($_GET['securekey']) AND !empty($_GET['securekey']))
{
    $menuid = $_GET['securekey'];
    $menuid = Yii::$app->utility->decryptString($_GET['securekey']);
    $menuid = Yii::$app->utility->encryptString($menuid);
}
?>
<h5 class="text-danger"  style="text-align:center"><b>View Dak Dispatch</b></h5>
<hr>
<div class="row">
    <table id="viewdisdata" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Sr. No.</th>
                <th>Dispatch Number</th>
                <th>Dispatch Date</th>
                <th>Dispatch From Dept</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
          <tbody>
                <?php 
//                echo "<pre>";print_r($dak_received);die;
                if(!empty($dak_dispatch))
                {
                    $i =1;
                    foreach($dak_dispatch as $k=>$fd)
                    { 
                        $disp_id =Yii::$app->utility->encryptString($fd['disp_id']);
                        $rec_date=date("d-M-Y",strtotime($fd['disp_date']));
                        $disp_from_dept= Yii::$app->utility->get_dept($fd['disp_from_dept']);
                        $disp_from_dept=$disp_from_dept["dept_name"];
                        $url=Yii::$app->homeUrl."efile/dakmanagement/viewdisdetail?securekey=".$menuid."&disp_id=".$disp_id;
                        $verify_link ="<a href='$url' class='btn btn-sm btn-danger'>View</a>"
                ?>
                <tr>
                    <td><?=$i?></td>
                    <td><?=ucwords($fd['disp_number'])?></td>
                    <td><?=ucwords($rec_date)?></td>
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
                <th>Dispatch Number</th>
                <th>Dispatch Date</th>
                <th>Dispatch From Dept</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </tfoot>
    </table>
 </div>