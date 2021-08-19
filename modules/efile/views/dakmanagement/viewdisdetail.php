<?php
//echo "<pre>xx";print_r($dak_dispatch);die;
$dak_address= Yii::$app->Dakutility->efile_get_dak_dispatch_address($dak_dispatch['disp_id']);
$disp_from_dept= Yii::$app->utility->get_dept($dak_dispatch['disp_from_dept']);
$disp_from_dept=$disp_from_dept["dept_name"];
$disp_get_employees= Yii::$app->utility->get_employees($dak_dispatch['disp_from_emp']);
$empdetail=$disp_get_employees["fullname"].", ".$disp_get_employees["desg_name"]." ($disp_get_employees[dept_name])";
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
$this->title = "डिस्पैच विवरण / Dispatch Details";
?>
<h5 class="text-danger"  style="text-align:center"><b>डिस्पैच विवरण / Dispatch Details</b></h5>
<style>
    .col-sm-12{
        margin-bottom: 10px;
    }
</style>
<table class="table table-bordered">
    <tr>
        <td style="width:50%"><b>डिस्पैच नंबर / Dispatch Number:</b></td>
        <td style="width:50%"><?=$dak_dispatch["disp_number"]?></td>
    </tr>
    <tr>
        <td style="width:50%"><b>डिस्पैच तारीख / Dispatch Date</b></td>
        <td style="width:50%"><?=$disp_date?></td>
    </tr>
    <tr>
        <td style="width:50%"><b> पत्र संदर्भ संख्या / Letter Reference Number </b></td>
        <td style="width:50%"><?=$dak_dispatch["letter_reference_num"]?></td>
    </tr>
    <tr>
        <td style="width:50%"><b> किस भाषा में पत्र / Letter Language </b></td>
        <td style="width:50%"><?=$dak_dispatch["letter_language"]?></td>
    </tr>
    
    <tr>
        <td style="width:50%"><b> किसके द्वारा भेजा गया / Forwarded By</b></td>
        <td style="width:50%"><?=$empdetail?></td>
    </tr>
    <tr>
        <td style="width:50%"><b> डिस्पैच का तरीका / Mode of Dispatch</b></td>
        <td style="width:50%"><?=$dak_dispatch["mode_of_rec"]?></td>
    </tr>
    <?php 
    if(!empty($dak_dispatch["postal_amount"])){
    ?>
    <tr>
        <td style="width:50%"><b> डाक राशि / Postal Amount</b></td>
        <td style="width:50%">Rs. <?=$dak_dispatch["postal_amount"]?>/-</td>
    </tr>
    <tr>
        <td style="width:50%"><b> डाक की तारीख / Postal Date</b></td>
        <td style="width:50%"><?=date('d-m-Y', strtotime($dak_dispatch["postal_date"]))?></td>
    </tr>
    <tr>
        <td style="width:50%"><b> दस्तावेज़ संलग्न / Document Attached</b></td>
        <td style="width:50%"><?=$doc?></td>
    </tr>
    <?php }else{
        echo "<div class='text-right'><button type='button' class='btn btn-success btn-sm' data-toggle='modal' data-target='#postal_infor'>Update</button><br><br></div>";
    } ?>
</table>
<h6><b>पत्र प्रेषित किया गया / Forwarded To</b></h6>
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
<?php 
    if(empty($dak_dispatch["postal_amount"])){
    ?>
<!-- Modal -->
<div class="modal fade" id="postal_infor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Update Dispatch Information</h5>
      </div>
      <div class="modal-body">
          <form  id="postal_form" method="POST" action="<?=Yii::$app->homeUrl?>efile/dakmanagement/updatedistpach?securekey=<?=$menuid?>" enctype="multipart/form-data">
              <input type="hidden" name="key" value="<?=Yii::$app->utility->encryptString($dak_dispatch['disp_id'])?>" />
              <input type="hidden" name="key1" value="<?=Yii::$app->utility->encryptString($dak_dispatch['disp_number'])?>" />
              <div class="row">
                  <div class="col-sm-12">
                      <label>डाक राशि / Postal Amount</label>
                      <input type="text" class="form-control form-control-sm" id="postal_amount" name="postal_amount" placeholder="Postal Amount" required="" autocomplete="off" />
                  </div>
                  <div class="col-sm-12">
                      <label>डाक की तारीख / Postal Date</label>
                      <input type="text" class="form-control form-control-sm" id="postal_date" name="postal_date" placeholder="Postal Date" readonly="" />
                  </div>
                  <div class="col-sm-12">
                      <label>अपलोड दस्तावेज़ / Upload Document (if any)</label>
                      <input type="file" class="form-control form-control-sm" id="disp_document" name="disp_document"/>
                  </div>
              </div>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary btn-sm" onclick="postalform()">Update</button>
        <a href=""  class="btn btn-danger btn-sm">Cancel</a>
      </div>
    </div>
  </div>
</div>
<?php 
    }
?>