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
<h5 class="text-danger"  style="text-align:center"><b>डाक डिस्पैच / Dak Dispatch</b></h5>
<hr>
<div class="row">
    <table id="viewdisdata" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Sr. No.</th>
                <th>Dispatch Number</th>
                <th>Dispatch Date</th>
                <th>Dispatched From</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
          <tbody>
                <?php 
                
                if(!empty($dak_dispatch))
                {
                    $i =1;
                    foreach($dak_dispatch as $k=>$fd)
                    { 
                        $disp_id =Yii::$app->utility->encryptString($fd['disp_id']);
                        $rec_date=date("d-M-Y",strtotime($fd['disp_date']));
                        
                        $disp_get_employees= Yii::$app->utility->get_employees($fd['disp_from_emp']);
                        $empdetail=$disp_get_employees["fullname"].", ".$disp_get_employees["desg_name"]." ($disp_get_employees[dept_name])";  
                        
                        $url=Yii::$app->homeUrl."efile/dakmanagement/viewdisdetail?securekey=".$menuid."&disp_id=".$disp_id;
                        $verify_link ="<a href='$url' class='btn btn-sm btn-danger'>View</a>"
                ?>
                <tr>
                    <td><?=$i?></td>
                    <td><?=ucwords($fd['disp_number'])?></td>
                    <td><?=ucwords($rec_date)?></td>
                    <td><?=$empdetail?></td>
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