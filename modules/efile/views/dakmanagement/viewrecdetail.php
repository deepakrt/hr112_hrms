<?php
//echo "<pre>xx";print_r($dak_received);die;
$dak_fwd_dept= Yii::$app->utility->get_dept($dak_received['dak_fwd_dept']);
$dak_fwd_dept=$dak_fwd_dept["dept_name"];
//echo "<pre>";print_r($dak_received);die;
$disp_get_employees= Yii::$app->utility->get_employees($dak_received['dak_fwd_to']);

$empdetail=$disp_get_employees["fullname"].", ".$disp_get_employees["desg_name"]." ($disp_get_employees[dept_name])";
$rec_date=date("d-M-Y",strtotime($dak_received['rec_date']));
if(empty($dak_received['dak_document']))
{
    $doc="Not Uploaded";
}
else
{
    $doc = '<a target="_blank" style="text-decoration: underline;" href="'.Yii::$app->request->baseUrl.$dak_received['dak_document'].'"><img height="40px;" src="'.Yii::$app->request->baseUrl.'/images/view - Copy.png"></a>';
}
$org_state=Yii::$app->Dakutility->get_master_states($dak_received["org_state"]);
$org_state=$org_state["state_name"];
$org_district=Yii::$app->Dakutility->get_master_districts($dak_received["org_district"],$dak_received["org_state"]);
$org_district=$org_district["district_name"];

$recdFrom = "$dak_received[rec_from], $dak_received[org_address]";
$this->title ="डाक प्राप्त विवरण / Dak Received Detail";
?>
<h5 class="text-danger"  style="text-align:center"><b>डाक प्राप्त विवरण / Dak Received Detail</b></h5>

<table class="table table-bordered">
    <tr>
        <td style="width: 50%"><b>रसीद प्रवेश भाषा/ Entry Language</b></td>
        <td style="width: 50%"><?=$dak_received["entry_language"]?></td>
    </tr>
    <tr>
        <td style="width: 50%"><b>रसीद संख्या / Receipt Number:</b></td>
        <td style="width: 50%"><?=$dak_received["dak_number"]?></td>
    </tr>
    <tr>
        <td style="width: 50%"><b>प्राप्ति की विधि / Mode of Received:</b></td>
        <td style="width: 50%"><?=$dak_received["mode_of_rec"]?></td>
    </tr>
    <tr>
        <td style="width: 50%"><b>डाक किसे भेजा गया / Dak Forward To:</b></td>
        <td style="width: 50%"><?=$empdetail?></td>
    </tr>
    <tr>
        <td style="width: 50%"><b>कहाँ से प्राप्त / Dak Received From:</b></td>
        <td style="width: 50%"><?=$recdFrom?></td>
    </tr>
    <tr>
        <td style="width: 50%"><b>राज्य और जिला / State and District:</b></td>
        <td style="width: 50%"><?=$org_district.", ".$org_state?></td>
    </tr>
    <tr>
        <td style="width: 50%"><b>स्थिति / Status:</b></td>
        <td style="width: 50%"><?=$dak_received['status']?></td>
    </tr>
    <tr>
        <td style="width: 50%"><b> डाक भेजने की तारिख / Forwarded On:</b></td>
        <td style="width: 50%"><?=date('d-m-Y H:i:s', strtotime($dak_received['forwarded_date']))?></td>
    </tr>
</table>
<div class="col-sm-12 text-center">
    <a href="<?=Yii::$app->homeUrl?>efile/dakmanagement/viewreceiptdisptachentry?securekey=<?=$menuid?>" class="btn btn-danger btn-sm">Back</a>
</div>
