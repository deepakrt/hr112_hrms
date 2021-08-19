<?php
//echo "<pre>xx";print_r($dak_dispatch);die;
$dak_address= Yii::$app->Dakutility->efile_get_dak_dispatch_address($dak_dispatch['disp_id']);
$disp_from_dept= Yii::$app->utility->get_dept($dak_dispatch['disp_from_dept']);
$disp_from_dept=$disp_from_dept["dept_name"];
$disp_get_employees= Yii::$app->utility->get_employees($dak_dispatch['disp_from_emp']);
$empdetail=$disp_get_employees["fullname"]."(".$disp_get_employees["desg_name"].")";
//echo "<pre>";print_r($empdetail);die;
$disp_date=date("d-M-Y",strtotime($dak_dispatch['disp_date']));
if(empty($dak_dispatch['disp_document']))
{
    $doc="Not Uploaded";
}
else
{
    $doc = '<a target="_blank" style="text-decoration: underline;" href="'.Yii::$app->request->baseUrl.$dak_dispatch['disp_document'].'"><img height="40px;" src="'.Yii::$app->request->baseUrl.'/images/view - Copy.png"></a>';
}
?>
<style>
 body {
    padding: 0px;
    margin: 0px;
    color: #000;
    font-family: 'Roboto', sans-serif;
    /*font-size: 14px;*/
}   
</style>
<h5 class="text-danger"  style="text-align:center"><b>Dispatch Detail</b></h5>
<hr>
<div class="row">
    <div class="col-sm-4">
        <label for="text">Dispatch Number:</label><br><?=$dak_dispatch["disp_number"]?>
    </div>
    <div class="col-sm-4">
        <label for="text">Dispatch Date:</label><br><?=$disp_date?>
    </div>
    <div class="col-sm-4">
        <label for="text">Mode of Dispatch:</label><br><?=$dak_dispatch["mode_of_rec"]?>
    </div>
</div>
<br>
<div class="row">
    <div class="col-sm-4">
        <label for="text">Dispatch From Dept:</label><br><?=$disp_from_dept?>
    </div>
    <div class="col-sm-4">
        <label for="text">Dispatch From Emp:</label><br><?=$empdetail?>
    </div>
    <div class="col-sm-4">
       
    </div>
</div>
<br>
<div class="row">
    <div class="col-sm-12">
        <label for="text"> Summary:</label><br><?=$dak_dispatch["disp_summary"]?>
    </div>
</div>
<br>
<div class="row">
    <div class="col-sm-6">
        <label for="text"> Remarks:</label><br><?=$dak_dispatch["disp_remarks"]?>
    </div>
    <div class="col-sm-6">
        <label for="text">Document:</label><br><?=$doc;?>
    </div>
</div>
<br>
<div class="row">
    <table class="table table-striped table-bordered">
        <tr>
            <th>Sr. No.</th>
            <th>Dispatch To</th>
            <th>State</th>
            <th>District</th>
            <th>Address</th>
        </tr>
    <?php
    $i=1;
    foreach($dak_address as $key=>$value)
    {
        $org_state=Yii::$app->Dakutility->get_master_states($value["org_state"]);
        $org_state=$org_state["state_name"];
        $org_district=Yii::$app->Dakutility->get_master_districts($value["org_district"],$value["org_state"]);
        $org_district=$org_district["district_name"];
//        echo "<pre>xx";print_r($org_district);die;
        echo "<tr><td>$i</td>";
        echo "<td>$value[disp_to]</td>";
        echo "<td>$org_state</td>";
        echo "<td>$org_district</td>";
        echo "<td>$value[org_address]</td></tr>";
        $i++;
    }
    ?>
    </table>
</div>