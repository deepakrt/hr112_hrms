<?php
//echo "<pre>xx";print_r($dak_received);die;
$dak_fwd_dept= Yii::$app->utility->get_dept($dak_received['dak_fwd_dept']);
$dak_fwd_dept=$dak_fwd_dept["dept_name"];
//echo "<pre>";print_r($disp_from_dept);die;
$disp_get_employees= Yii::$app->utility->get_employees($dak_received['dak_fwd_to']);
$empdetail=$disp_get_employees["fullname"]."(".$disp_get_employees["desg_name"].")";
$rec_date=date("d-M-Y",strtotime($dak_received['rec_date']));
if(empty($dak_received['dak_document']))
{
    $doc="Not Uploaded";
}
else
{
    $doc = '<a target="_blank" style="text-decoration: underline;" href="'.Yii::$app->request->baseUrl.$dak_received['dak_document'].'"><img height="40px;" src="'.Yii::$app->request->baseUrl.'/images/view - Copy.png"></a>';
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
<h5 class="text-danger"  style="text-align:center"><b>Dak Received Detail</b></h5>
<hr>
<div class="row">
    <div class="col-sm-4">
        <label for="text">Dak Received Number:</label><br><?=$dak_received["dak_number"]?>
    </div>
    <div class="col-sm-4">
        <label for="text">Dak Received Date:</label><br><?=$rec_date?>
    </div>
    <div class="col-sm-4">
        <label for="text">Mode of Received:</label><br><?=$dak_received["mode_of_rec"]?>
    </div>
</div>
<br>
<div class="row">
    <div class="col-sm-4">
        <label for="text">Dak Forward Department:</label><br><?=$dak_fwd_dept?>
    </div>
    <div class="col-sm-4">
        <label for="text">Dak Forward Emp:</label><br><?=$empdetail?>
    </div>
    <div class="col-sm-4">
        <label for="text">Dak Received From:</label><br><?=$dak_received["rec_from"]?>
    </div>
</div>
<br>
<?php
$org_state=Yii::$app->Dakutility->get_master_states($dak_received["org_state"]);
$org_state=$org_state["state_name"];
$org_district=Yii::$app->Dakutility->get_master_districts($dak_received["org_district"],$dak_received["org_state"]);
$org_district=$org_district["district_name"];
?>
<div class="row">
    <div class="col-sm-4">
        <label for="text">State:</label><br><?=$org_state?>
    </div>
    <div class="col-sm-4">
        <label for="text">District:</label><br><?=$org_district?>
    </div>
    <div class="col-sm-4">
        <label for="text">Address:</label><br><?=$dak_received["org_address"]?>
    </div>
</div>
<br>
<div class="row">
    <div class="col-sm-12">
        <label for="text">Dak Summary:</label><br><?=$dak_received["dak_summary"]?>
    </div>
</div>
<br>
<div class="row">
    <div class="col-sm-6">
        <label for="text">Dak Remarks:</label><br><?=$dak_received["dak_remarks"]?>
    </div>
    <div class="col-sm-6">
        <label for="text">Document:</label><br><?=$doc;?>
    </div>
</div>
<br>
